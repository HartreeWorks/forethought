#!/usr/bin/env python3
"""
Re-grade critiques with Claude Opus 4.6 as an independent grader.

Uses the same V2 ACORN rubric (7 dimensions) that the GPT-5.2 Pro grader uses,
but grades with Claude instead. This lets us check whether the finding that
GPT-5.2 Pro critiques score higher than GPT-4.1 Mini holds with an independent
grader, controlling for potential self-preference bias.

Usage:
    python regrade-with-claude-grader.py --condition gpt52pro    # 40 GPT-5.2 Pro critiques
    python regrade-with-claude-grader.py --condition gpt41mini   # 40 GPT-4.1 Mini critiques
    python regrade-with-claude-grader.py --condition both         # all 80
"""

import anthropic
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

# Load Anthropic API key from the working location
# (the local .env symlink is broken, so use the browser-tools .env)
ENV_PATH = Path.home() / ".claude" / "plugins" / "marketplaces" / "browser-tools" / ".env"
load_dotenv(ENV_PATH)

# Remove ANTHROPIC_BASE_URL if set (SDK adds /v1 automatically)
if 'ANTHROPIC_BASE_URL' in os.environ:
    del os.environ['ANTHROPIC_BASE_URL']

# Configuration
MODEL = "claude-opus-4-6"
BASE_DIR = Path(__file__).parent
PAPER_NAME = "no-easy-eutopia"

# Paths
PAPER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/no-easy-eutopia.md")
GRADER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/shared/2026-01/experiments/tools/acorn-benchmark-promptfoo-port/prompts/grader-v2-acorn-rubric.txt")

# The 4 prompts used in the grader accuracy experiment
TARGET_PROMPTS = ["baseline-v1", "surgery", "personas", "unforgettable"]

# Source and destination directories
CONDITIONS = {
    "gpt52pro": {
        "parsed_dir": BASE_DIR / "outputs-gpt" / "parsed",
        "results_dir": BASE_DIR / "results-gpt-claude-grader",
    },
    "gpt41mini": {
        "parsed_dir": BASE_DIR / "outputs-gpt41mini" / "parsed",
        "results_dir": BASE_DIR / "results-gpt41mini-claude-grader",
    },
}


@dataclass
class CritiqueScore:
    """ACORN v2 scores for a single critique."""
    centrality: float
    strength: float
    correctness: float
    clarity: float
    dead_weight: float
    single_issue: float
    overall: float
    reasoning: str


def load_file(path: Path) -> str:
    with open(path, "r") as f:
        return f.read()


def grade_critique(client: anthropic.Anthropic, grader_template: str, paper_text: str, critique: str) -> Optional[CritiqueScore]:
    """Grade a single critique using the ACORN v2 rubric via Claude."""
    grading_prompt = (
        grader_template
        .replace("{{position}}", paper_text)
        .replace("{{critique}}", critique)
    )

    try:
        response = client.messages.create(
            model=MODEL,
            max_tokens=2000,
            messages=[
                {"role": "user", "content": grading_prompt}
            ],
        )

        response_text = response.content[0].text

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
            correctness=scores.get("correctness", 0),
            clarity=scores.get("clarity", 0),
            dead_weight=scores.get("dead_weight", 0),
            single_issue=scores.get("single_issue", 0),
            overall=scores.get("overall", 0),
            reasoning=scores.get("reasoning", "")
        )
    except Exception as e:
        print(f"    Error grading critique: {e}")
        return None


def compute_stats(scores: list) -> dict:
    if not scores:
        return {"mean": 0, "std": 0, "min": 0, "max": 0, "count": 0}
    return {
        "mean": statistics.mean(scores),
        "std": statistics.stdev(scores) if len(scores) > 1 else 0,
        "min": min(scores),
        "max": max(scores),
        "count": len(scores)
    }


def run_condition(client: anthropic.Anthropic, condition_name: str, config: dict,
                  grader_template: str, paper_text: str) -> dict:
    """Grade all critiques for a condition, return results by prompt."""
    parsed_dir = config["parsed_dir"]
    results_dir = config["results_dir"]
    results_dir.mkdir(exist_ok=True)

    all_scores = {}

    for prompt_name in TARGET_PROMPTS:
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
                print(f"    {i:02d}: cached (overall={score.overall:.2f})")
            else:
                print(f"    {i:02d}: grading...", end=" ", flush=True)
                score = grade_critique(client, grader_template, paper_text, critique)

                if score:
                    with open(grade_file, "w") as f:
                        json.dump(score.__dict__, f, indent=2)
                    print(f"overall={score.overall:.2f}")
                else:
                    print("FAILED")
                    continue

                time.sleep(0.5)

            all_scores[prompt_name].append(score)

    return all_scores


def print_summary(condition_name: str, all_scores: dict):
    """Print summary statistics for a condition."""
    dimensions = ["centrality", "strength", "correctness", "clarity", "dead_weight", "single_issue", "overall"]

    print(f"\n{'=' * 60}")
    print(f"  {condition_name.upper()} CONDITION - Claude Opus 4.6 GRADER")
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
    parser = argparse.ArgumentParser(description="Re-grade critiques with Claude Opus 4.6")
    parser.add_argument("--condition", choices=["gpt52pro", "gpt41mini", "both"], default="both",
                        help="Which condition(s) to grade")
    args = parser.parse_args()

    print("ACORN v2 Re-grading with Claude Opus 4.6")
    print(f"Model: {MODEL}")
    print(f"Grader rubric: {GRADER_PATH.name}")
    print(f"Condition: {args.condition}")
    print()

    client = anthropic.Anthropic()
    grader_template = load_file(GRADER_PATH)
    paper_text = load_file(PAPER_PATH)

    conditions_to_run = (
        list(CONDITIONS.keys()) if args.condition == "both"
        else [args.condition]
    )

    for condition_name in conditions_to_run:
        config = CONDITIONS[condition_name]
        print(f"\n=== {condition_name.upper()} CONDITION ===\n")
        all_scores = run_condition(client, condition_name, config, grader_template, paper_text)
        print_summary(condition_name, all_scores)

    print("\n=== COMPLETE ===")
