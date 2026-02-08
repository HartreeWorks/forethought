#!/usr/bin/env python3
"""
Critique Prompt Experiment: Worldview Primer

Re-runs 4 prompts (conversational, pivot-attack, unforgettable, personas) on
"No Easy Eutopia" with the Forethought Worldview Primer included as context.
Compares results against the original run to test whether organisational
context improves critique quality.

The primer is injected alongside the paper text in the critique generation
prompt, but the grader receives only the original paper text (matching the
original experiment's grading conditions).
"""

import openai
import json
import re
import os
import sys
import time
from pathlib import Path
from dataclasses import dataclass
from typing import Optional
import statistics
from dotenv import load_dotenv

# Load API keys - try local .env first, fall back to research .env
ENV_PATH = Path(__file__).parent / ".env"
if not ENV_PATH.exists() or ENV_PATH.is_symlink():
    # Local .env may be a broken symlink; use the research .env
    ENV_PATH = Path(__file__).parent.parent.parent / "research" / "llm-grader-research" / ".env"
load_dotenv(ENV_PATH)

# Configuration
MODEL = "gpt-5.2-pro"
NUM_CRITIQUES = 10
BASE_DIR = Path(__file__).parent
PROMPTS_DIR = BASE_DIR / "prompts"

# Output directories for worldview experiment
OUTPUTS_DIR = BASE_DIR / "outputs-gpt-wv"
PARSED_DIR = OUTPUTS_DIR / "parsed"
RESULTS_DIR = BASE_DIR / "results-gpt-wv"

# Paper
PAPER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/no-easy-eutopia.md")
PAPER_NAME = "no-easy-eutopia"

# Worldview primer
PRIMER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/forethought-worldview/forethought-worldview-primer.md")

# 4 prompts to test with primer
PROMPTS = {
    "conversational": PROMPTS_DIR / "conversational-parameterised.md",
    "pivot-attack": PROMPTS_DIR / "pivot-attack-parameterised.md",
    "unforgettable": PROMPTS_DIR / "unforgettable-parameterised.md",
    "personas": PROMPTS_DIR / "personas-parameterised.md",
}

# ACORN Grader
GRADER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/shared/2026-01/experiments/tools/acorn-benchmark-promptfoo-port/prompts/grader-v2-acorn-rubric.txt")


@dataclass
class CritiqueScore:
    """ACORN scores for a single critique."""
    centrality: float
    strength: float
    correctness: float
    clarity: float
    dead_weight: float
    single_issue: float
    overall: float
    reasoning: str


def ensure_dirs():
    """Create output directories if they don't exist."""
    OUTPUTS_DIR.mkdir(exist_ok=True)
    PARSED_DIR.mkdir(exist_ok=True)
    RESULTS_DIR.mkdir(exist_ok=True)


def load_file(path: Path) -> str:
    """Load a file's contents."""
    with open(path, "r") as f:
        return f.read()


def generate_critiques(client: openai.OpenAI, prompt_template: str, paper_text: str, primer_text: str) -> str:
    """Generate critiques using GPT-5.2 Pro via Responses API.

    Injects the worldview primer alongside the paper text using XML-style tags.
    """
    combined = f"""<organisational-context>
{primer_text}
</organisational-context>

<paper>
{paper_text}
</paper>"""

    full_prompt = prompt_template.replace("{{paper}}", combined)
    full_prompt = full_prompt.replace("{{num_critiques}}", str(NUM_CRITIQUES))

    print(f"  Generating critiques...")
    response = client.responses.create(
        model=MODEL,
        input=full_prompt,
    )

    return response.output_text


def parse_critiques(output: str) -> list[str]:
    """Parse numbered critiques from output text."""
    pattern = r'^\s*(\d+)\.\s+'
    matches = list(re.finditer(pattern, output, re.MULTILINE))

    critiques = []
    for i, match in enumerate(matches):
        start = match.end()
        if i + 1 < len(matches):
            end = matches[i + 1].start()
        else:
            end = len(output)

        critique_text = output[start:end].strip()
        if critique_text:
            critiques.append(critique_text)

    return critiques


def grade_critique(client: openai.OpenAI, grader_template: str, paper_text: str, critique: str) -> Optional[CritiqueScore]:
    """Grade a single critique using the ACORN rubric via Responses API.

    Note: The grader receives only the original paper text (without primer),
    matching the original experiment's grading conditions.
    """
    grading_prompt = grader_template.replace("{{position}}", paper_text).replace("{{critique}}", critique)

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


def compute_stats(scores: list[float]) -> dict:
    """Compute statistics for a list of scores."""
    if not scores:
        return {"mean": 0, "std": 0, "min": 0, "max": 0, "count": 0}

    return {
        "mean": statistics.mean(scores),
        "std": statistics.stdev(scores) if len(scores) > 1 else 0,
        "min": min(scores),
        "max": max(scores),
        "count": len(scores)
    }


if __name__ == "__main__":
    print("Worldview Primer Experiment")
    print(f"Model: {MODEL}")
    print(f"Paper: {PAPER_NAME}")
    print(f"Prompts: {', '.join(PROMPTS.keys())}")
    print(f"Outputs: {OUTPUTS_DIR}")
    print(f"Results: {RESULTS_DIR}")
    print()

    ensure_dirs()

    client = openai.OpenAI()
    grader_template = load_file(GRADER_PATH)
    paper_text = load_file(PAPER_PATH)
    primer_text = load_file(PRIMER_PATH)

    all_results = {}

    # Step 1: Generate critiques for each prompt
    print("\n=== STEP 1: Generating Critiques (with Worldview Primer) ===\n")

    for prompt_name, prompt_path in PROMPTS.items():
        prompt_template = load_file(prompt_path)

        print(f"Processing {prompt_name} x {PAPER_NAME}...")

        output_file = OUTPUTS_DIR / f"{prompt_name}-{PAPER_NAME}.md"

        # Check if we already have this output
        if output_file.exists():
            print(f"  Using cached output from {output_file}")
            output = load_file(output_file)
        else:
            output = generate_critiques(client, prompt_template, paper_text, primer_text)

            with open(output_file, "w") as f:
                f.write(output)
            print(f"  Saved to {output_file}")

        # Parse critiques
        critiques = parse_critiques(output)
        print(f"  Parsed {len(critiques)} critiques")

        if len(critiques) != NUM_CRITIQUES:
            print(f"  WARNING: Expected {NUM_CRITIQUES} critiques, got {len(critiques)}")

        # Save parsed critiques
        for i, critique in enumerate(critiques, 1):
            parsed_file = PARSED_DIR / f"{prompt_name}-{PAPER_NAME}-{i:02d}.txt"
            with open(parsed_file, "w") as f:
                f.write(critique)

        print()

    # Step 2: Grade each critique
    print("\n=== STEP 2: Grading Critiques ===\n")

    for prompt_name in PROMPTS.keys():
        all_results[prompt_name] = {
            "scores": [],
        }

        # Find all parsed critiques for this prompt
        parsed_files = sorted(PARSED_DIR.glob(f"{prompt_name}-{PAPER_NAME}-*.txt"))

        print(f"Grading {prompt_name} x {PAPER_NAME} ({len(parsed_files)} critiques)...")

        for i, parsed_file in enumerate(parsed_files, 1):
            critique = load_file(parsed_file)

            # Check for cached grade
            grade_file = RESULTS_DIR / f"{prompt_name}-{PAPER_NAME}-{i:02d}.json"

            if grade_file.exists():
                with open(grade_file, "r") as f:
                    cached = json.load(f)
                score = CritiqueScore(**cached)
                print(f"  Critique {i}: cached (overall={score.overall:.2f})")
            else:
                print(f"  Grading critique {i}...", end=" ")
                # Grade with original paper text only (no primer)
                score = grade_critique(client, grader_template, paper_text, critique)

                if score:
                    with open(grade_file, "w") as f:
                        json.dump(score.__dict__, f, indent=2)
                    print(f"overall={score.overall:.2f}")
                else:
                    print("FAILED")
                    continue

                # Rate limit
                time.sleep(0.5)

            if score:
                all_results[prompt_name]["scores"].append(score)

        print()

    # Step 3: Aggregate and analyze
    print("\n=== STEP 3: Results Analysis ===\n")

    dimensions = ["centrality", "strength", "correctness", "clarity", "dead_weight", "single_issue", "overall"]

    summary = {}

    for prompt_name, data in all_results.items():
        scores = data["scores"]

        if not scores:
            print(f"{prompt_name}: No scores collected")
            continue

        summary[prompt_name] = {}

        print(f"\n{prompt_name.upper()} ({len(scores)} critiques)")
        print("-" * 50)

        for dim in dimensions:
            values = [getattr(s, dim) for s in scores]
            stats = compute_stats(values)
            summary[prompt_name][dim] = stats

            print(f"  {dim:15s}: mean={stats['mean']:.3f}, std={stats['std']:.3f}, range=[{stats['min']:.2f}, {stats['max']:.2f}]")

        # Composite score
        composite = [s.strength * s.centrality for s in scores]
        composite_stats = compute_stats(composite)
        summary[prompt_name]["strength_x_centrality"] = composite_stats
        print(f"  {'strength*central':15s}: mean={composite_stats['mean']:.3f}, std={composite_stats['std']:.3f}")

    # Save full summary
    summary_file = RESULTS_DIR / "summary.json"
    with open(summary_file, "w") as f:
        json.dump(summary, f, indent=2)
    print(f"\nFull summary saved to {summary_file}")

    print("\n=== COMPLETE ===")
