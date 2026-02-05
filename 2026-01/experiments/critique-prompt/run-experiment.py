#!/usr/bin/env python3
"""
Critique Prompt Experiment: Individual Critique Grading

Tests which of 3 Claude critique prompts produces the highest quality individual
critiques, measured by ACORN grader scores across 2 papers.
"""

import anthropic
import json
import re
import os
import time
from pathlib import Path
from dataclasses import dataclass
from typing import Optional
import statistics
from dotenv import load_dotenv

# Load API keys from the acorn-benchmark .env file
ENV_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/acorn-benchmark/.env")
load_dotenv(ENV_PATH)

# Configuration
MODEL = "claude-opus-4-5-20251101"
BASE_DIR = Path(__file__).parent
PROMPTS_DIR = BASE_DIR / "prompts"
OUTPUTS_DIR = BASE_DIR / "outputs"
PARSED_DIR = OUTPUTS_DIR / "parsed"
RESULTS_DIR = BASE_DIR / "results"

# Papers
PAPERS = {
    "convergence": Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/convergence-and-compromise.md"),
    "no-easy-eutopia": Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/no-easy-eutopia.md"),
}

# Prompts
PROMPTS = {
    "surgery": PROMPTS_DIR / "claude-surgery-15.md",
    "personas": PROMPTS_DIR / "claude-personas-15.md",
    "unforgettable": PROMPTS_DIR / "claude-unforgettable-15.md",
}

# ACORN Grader
GRADER_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/acorn-benchmark/prompts/grader-v2-acorn-rubric.txt")


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


def generate_critiques(client: anthropic.Anthropic, prompt_template: str, paper_text: str) -> str:
    """Generate critiques using Claude Opus 4.5."""
    # Replace the {{paper}} placeholder with actual paper text
    full_prompt = prompt_template.replace("{{paper}}", paper_text)

    print(f"  Generating critiques...")
    response = client.messages.create(
        model=MODEL,
        max_tokens=8192,
        messages=[
            {"role": "user", "content": full_prompt}
        ]
    )

    return response.content[0].text


def parse_critiques(output: str) -> list[str]:
    """Parse numbered critiques from output text.

    Expects format:
    1. [critique text]

    2. [critique text]
    ...
    """
    # Split on numbered patterns (1. , 2. , etc.)
    # This regex matches the start of a numbered item
    pattern = r'^\s*(\d+)\.\s+'

    # Find all matches and their positions
    matches = list(re.finditer(pattern, output, re.MULTILINE))

    critiques = []
    for i, match in enumerate(matches):
        start = match.end()
        # End is either the next match or end of string
        if i + 1 < len(matches):
            end = matches[i + 1].start()
        else:
            end = len(output)

        critique_text = output[start:end].strip()
        if critique_text:
            critiques.append(critique_text)

    return critiques


def grade_critique(client: anthropic.Anthropic, grader_template: str, paper_text: str, critique: str) -> Optional[CritiqueScore]:
    """Grade a single critique using the ACORN rubric."""
    # Build the grading prompt
    grading_prompt = grader_template.replace("{{position}}", paper_text).replace("{{critique}}", critique)

    try:
        response = client.messages.create(
            model=MODEL,
            max_tokens=2048,
            messages=[
                {"role": "user", "content": grading_prompt}
            ]
        )

        response_text = response.content[0].text

        # Extract JSON from response
        # Try to find JSON block in markdown code fence first
        json_match = re.search(r'```(?:json)?\s*(\{.*?\})\s*```', response_text, re.DOTALL)
        if json_match:
            json_str = json_match.group(1)
        else:
            # Try to find raw JSON
            json_match = re.search(r'\{[^{}]*"centrality"[^{}]*\}', response_text, re.DOTALL)
            if json_match:
                json_str = json_match.group(0)
            else:
                # Try the whole response
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


def run_experiment():
    """Run the full experiment."""
    ensure_dirs()

    client = anthropic.Anthropic()
    grader_template = load_file(GRADER_PATH)

    # Store all results
    all_results = {}

    # Step 1: Generate critiques for each prompt/paper combination
    print("\n=== STEP 1: Generating Critiques ===\n")

    for prompt_name, prompt_path in PROMPTS.items():
        prompt_template = load_file(prompt_path)

        for paper_name, paper_path in PAPERS.items():
            print(f"Processing {prompt_name} x {paper_name}...")

            paper_text = load_file(paper_path)
            output_file = OUTPUTS_DIR / f"{prompt_name}-{paper_name}.md"

            # Check if we already have this output
            if output_file.exists():
                print(f"  Using cached output from {output_file}")
                output = load_file(output_file)
            else:
                output = generate_critiques(client, prompt_template, paper_text)

                # Save output
                with open(output_file, "w") as f:
                    f.write(output)
                print(f"  Saved to {output_file}")

            # Parse critiques
            critiques = parse_critiques(output)
            print(f"  Parsed {len(critiques)} critiques")

            if len(critiques) != 15:
                print(f"  WARNING: Expected 15 critiques, got {len(critiques)}")

            # Save parsed critiques
            for i, critique in enumerate(critiques, 1):
                parsed_file = PARSED_DIR / f"{prompt_name}-{paper_name}-{i:02d}.txt"
                with open(parsed_file, "w") as f:
                    f.write(critique)

            print()

    # Step 2: Grade each critique
    print("\n=== STEP 2: Grading Critiques ===\n")

    for prompt_name in PROMPTS.keys():
        all_results[prompt_name] = {
            "scores": [],
            "by_paper": {}
        }

        for paper_name, paper_path in PAPERS.items():
            paper_text = load_file(paper_path)
            all_results[prompt_name]["by_paper"][paper_name] = []

            # Find all parsed critiques for this combination
            parsed_files = sorted(PARSED_DIR.glob(f"{prompt_name}-{paper_name}-*.txt"))

            print(f"Grading {prompt_name} x {paper_name} ({len(parsed_files)} critiques)...")

            for i, parsed_file in enumerate(parsed_files, 1):
                critique = load_file(parsed_file)

                # Check for cached grade
                grade_file = RESULTS_DIR / f"{prompt_name}-{paper_name}-{i:02d}.json"

                if grade_file.exists():
                    with open(grade_file, "r") as f:
                        cached = json.load(f)
                    score = CritiqueScore(**cached)
                    print(f"  Critique {i}: cached (overall={score.overall:.2f})")
                else:
                    print(f"  Grading critique {i}...", end=" ")
                    score = grade_critique(client, grader_template, paper_text, critique)

                    if score:
                        # Save grade
                        with open(grade_file, "w") as f:
                            json.dump(score.__dict__, f, indent=2)
                        print(f"overall={score.overall:.2f}")
                    else:
                        print("FAILED")
                        continue

                    # Rate limit: be nice to the API
                    time.sleep(0.5)

                if score:
                    all_results[prompt_name]["scores"].append(score)
                    all_results[prompt_name]["by_paper"][paper_name].append(score)

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

        # Compute composite score: strength * centrality (key metric from ACORN rubric)
        composite = [s.strength * s.centrality for s in scores]
        composite_stats = compute_stats(composite)
        summary[prompt_name]["strength_x_centrality"] = composite_stats
        print(f"  {'strength*central':15s}: mean={composite_stats['mean']:.3f}, std={composite_stats['std']:.3f}")

    # Save full summary
    summary_file = RESULTS_DIR / "summary.json"
    with open(summary_file, "w") as f:
        json.dump(summary, f, indent=2)
    print(f"\nFull summary saved to {summary_file}")

    # Print comparison table
    print("\n\n=== COMPARISON TABLE ===\n")
    print(f"{'Dimension':<20} | ", end="")
    for prompt_name in PROMPTS.keys():
        print(f"{prompt_name:^15} | ", end="")
    print()
    print("-" * 70)

    for dim in dimensions + ["strength_x_centrality"]:
        print(f"{dim:<20} | ", end="")
        for prompt_name in PROMPTS.keys():
            if prompt_name in summary and dim in summary[prompt_name]:
                mean = summary[prompt_name][dim]["mean"]
                print(f"{mean:^15.3f} | ", end="")
            else:
                print(f"{'N/A':^15} | ", end="")
        print()

    # Determine winner
    print("\n\n=== WINNER ===\n")

    # Rank by overall score
    rankings = []
    for prompt_name in PROMPTS.keys():
        if prompt_name in summary and "overall" in summary[prompt_name]:
            rankings.append((prompt_name, summary[prompt_name]["overall"]["mean"]))

    rankings.sort(key=lambda x: x[1], reverse=True)

    for i, (name, score) in enumerate(rankings, 1):
        print(f"  {i}. {name}: {score:.3f}")

    if rankings:
        print(f"\nBest prompt by overall score: {rankings[0][0]}")


if __name__ == "__main__":
    run_experiment()
