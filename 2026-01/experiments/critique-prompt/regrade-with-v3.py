#!/usr/bin/env python3
"""
Re-grade critiques with ACORN v3 (Forethought relevance-aware grader).

Grades existing parsed critiques from the original and/or worldview-primer
experiments using the v3 grader, which adds a relevance dimension scored
against the Forethought worldview primer.

Usage:
    python regrade-with-v3.py --condition original   # 40 original critiques
    python regrade-with-v3.py --condition worldview   # 40 worldview critiques
    python regrade-with-v3.py --condition both         # all 80
"""

import openai
import json
import re
import os
import sys
import time
import argparse
from pathlib import Path
from dataclasses import dataclass
from typing import Optional
import statistics
from dotenv import load_dotenv

# Load API keys - try local .env first, fall back to research .env
ENV_PATH = Path(__file__).parent / ".env"
if not ENV_PATH.exists() or ENV_PATH.is_symlink():
    ENV_PATH = Path(__file__).parent.parent.parent / "research" / "llm-grader-research" / ".env"
load_dotenv(ENV_PATH)

# Configuration
MODEL = "gpt-5.2-pro"
BASE_DIR = Path(__file__).parent
PAPER_NAME = "no-easy-eutopia"

# Paths
PAPER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/no-easy-eutopia.md")
PRIMER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/forethought-worldview/forethought-worldview-primer.md")
GRADER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/shared/2026-01/experiments/tools/acorn-benchmark-promptfoo-port/prompts/grader-v3-acorn-forethought.txt")

# The 4 shared prompts between original and worldview experiments
SHARED_PROMPTS = ["conversational", "pivot-attack", "unforgettable", "personas"]

# Source and destination directories
CONDITIONS = {
    "original": {
        "parsed_dir": BASE_DIR / "outputs-gpt" / "parsed",
        "results_dir": BASE_DIR / "results-gpt-v3",
    },
    "worldview": {
        "parsed_dir": BASE_DIR / "outputs-gpt-wv" / "parsed",
        "results_dir": BASE_DIR / "results-gpt-wv-v3",
    },
}


@dataclass
class CritiqueScore:
    """ACORN v3 scores for a single critique."""
    centrality: float
    strength: float
    relevance: float
    overall: float
    reasoning: str


def load_file(path: Path) -> str:
    with open(path, "r") as f:
        return f.read()


def grade_critique(client: openai.OpenAI, grader_template: str, paper_text: str, primer_text: str, critique: str) -> Optional[CritiqueScore]:
    """Grade a single critique using the ACORN v3 rubric."""
    grading_prompt = (
        grader_template
        .replace("{{position}}", paper_text)
        .replace("{{organisational_context}}", primer_text)
        .replace("{{critique}}", critique)
    )

    try:
        response = client.responses.create(
            model=MODEL,
            input=grading_prompt,
        )

        response_text = response.output_text

        # Extract JSON from response
        json_match = re.search(r'```(?:json)?\s*(\{.*?\})\s*```', response_text, re.DOTALL)
        if json_match:
            json_str = json_match.group(1)
        else:
            json_match = re.search(r'\{[^{}]*"centrality"[^{}]*\}', response_text, re.DOTALL)
            if json_match:
                json_str = json_match.group(0)
            else:
                json_str = response_text.strip()

        scores = json.loads(json_str)

        return CritiqueScore(
            centrality=scores.get("centrality", 0),
            strength=scores.get("strength", 0),
            relevance=scores.get("relevance", 0),
            overall=scores.get("overall", 0),
            reasoning=scores.get("reasoning", "")
        )
    except Exception as e:
        print(f"    Error grading critique: {e}")
        return None


def compute_stats(scores: list[float]) -> dict:
    if not scores:
        return {"mean": 0, "std": 0, "min": 0, "max": 0, "count": 0}
    return {
        "mean": statistics.mean(scores),
        "std": statistics.stdev(scores) if len(scores) > 1 else 0,
        "min": min(scores),
        "max": max(scores),
        "count": len(scores)
    }


def run_condition(client: openai.OpenAI, condition_name: str, config: dict,
                  grader_template: str, paper_text: str, primer_text: str) -> dict:
    """Grade all critiques for a condition, return results by prompt."""
    parsed_dir = config["parsed_dir"]
    results_dir = config["results_dir"]
    results_dir.mkdir(exist_ok=True)

    all_scores = {}

    for prompt_name in SHARED_PROMPTS:
        # Find parsed critique files for this prompt
        pattern = f"{prompt_name}-{PAPER_NAME}-*.txt"
        parsed_files = sorted(parsed_dir.glob(pattern))

        if not parsed_files:
            print(f"  No files found for {prompt_name} in {parsed_dir}")
            continue

        print(f"  Grading {prompt_name} ({len(parsed_files)} critiques)...")
        all_scores[prompt_name] = []

        for i, parsed_file in enumerate(parsed_files, 1):
            critique = load_file(parsed_file)
            grade_file = results_dir / f"{parsed_file.stem}.json"

            if grade_file.exists():
                with open(grade_file, "r") as f:
                    cached = json.load(f)
                score = CritiqueScore(**{k: cached[k] for k in CritiqueScore.__dataclass_fields__})
                print(f"    {i:02d}: cached (overall={score.overall:.2f}, relevance={score.relevance:.2f})")
            else:
                print(f"    {i:02d}: grading...", end=" ", flush=True)
                score = grade_critique(client, grader_template, paper_text, primer_text, critique)

                if score:
                    with open(grade_file, "w") as f:
                        json.dump(score.__dict__, f, indent=2)
                    print(f"overall={score.overall:.2f}, relevance={score.relevance:.2f}")
                else:
                    print("FAILED")
                    continue

                time.sleep(0.5)

            all_scores[prompt_name].append(score)

    return all_scores


def print_summary(condition_name: str, all_scores: dict):
    """Print summary statistics for a condition."""
    dimensions = ["centrality", "strength", "relevance", "overall"]

    print(f"\n{'=' * 60}")
    print(f"  {condition_name.upper()} CONDITION - ACORN v3 RESULTS")
    print(f"{'=' * 60}")

    all_flat = []
    for prompt_name, scores in all_scores.items():
        if not scores:
            continue
        all_flat.extend(scores)

        print(f"\n  {prompt_name.upper()} ({len(scores)} critiques)")
        print(f"  {'-' * 50}")
        for dim in dimensions:
            values = [getattr(s, dim) for s in scores]
            stats = compute_stats(values)
            print(f"    {dim:12s}: mean={stats['mean']:.3f}, std={stats['std']:.3f}, range=[{stats['min']:.2f}, {stats['max']:.2f}]")

    if all_flat:
        print(f"\n  ALL PROMPTS ({len(all_flat)} critiques)")
        print(f"  {'-' * 50}")
        for dim in dimensions:
            values = [getattr(s, dim) for s in all_flat]
            stats = compute_stats(values)
            print(f"    {dim:12s}: mean={stats['mean']:.3f}, std={stats['std']:.3f}")


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Re-grade critiques with ACORN v3")
    parser.add_argument("--condition", choices=["original", "worldview", "both"], default="both",
                        help="Which condition(s) to grade")
    args = parser.parse_args()

    print("ACORN v3 Re-grading")
    print(f"Model: {MODEL}")
    print(f"Grader: {GRADER_PATH.name}")
    print(f"Condition: {args.condition}")
    print()

    client = openai.OpenAI()
    grader_template = load_file(GRADER_PATH)
    paper_text = load_file(PAPER_PATH)
    primer_text = load_file(PRIMER_PATH)

    conditions_to_run = (
        list(CONDITIONS.keys()) if args.condition == "both"
        else [args.condition]
    )

    for condition_name in conditions_to_run:
        config = CONDITIONS[condition_name]
        print(f"\n=== {condition_name.upper()} CONDITION ===\n")
        all_scores = run_condition(client, condition_name, config, grader_template, paper_text, primer_text)
        print_summary(condition_name, all_scores)

    print("\n=== COMPLETE ===")
