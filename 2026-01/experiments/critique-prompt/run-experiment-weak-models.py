#!/usr/bin/env python3
"""
Grader Accuracy Experiment: Weak Model Comparison

Tests ACORN grader accuracy by generating critiques with weaker models
(GPT-4.1 Mini, GPT-5 Nano) and checking that the grader scores them
lower than GPT-5.2 Pro critiques.

Generation uses the weak model; grading always uses GPT-5.2 Pro.
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
import argparse

# Load API keys from the acorn-benchmark .env file
ENV_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/acorn-benchmark/.env")
load_dotenv(ENV_PATH)

# Configuration
GRADER_MODEL = "gpt-5.2-pro"  # Grader is always GPT-5.2 Pro
NUM_CRITIQUES = 10
BASE_DIR = Path(__file__).parent
PROMPTS_DIR = BASE_DIR / "prompts"

# Model configurations
MODEL_CONFIGS = {
    "gpt-4.1-mini": {
        "dir_suffix": "gpt41mini",
    },
    "gpt-5-nano": {
        "dir_suffix": "gpt5nano",
    },
}

# Paper configurations
PAPER_CONFIGS = {
    "no-easy-eutopia": {
        "path": Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/no-easy-eutopia.md"),
    },
    "compute-bottlenecks": {
        "path": Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/will-compute-bottlenecks-prevent-a-sie.md"),
    },
    "convergence-and-compromise": {
        "path": Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/convergence-and-compromise.md"),
    },
}

# Prompts to test (subset of the full experiment)
PROMPTS = {
    "baseline-v1": PROMPTS_DIR / "baseline-v1-parameterised.md",
    "surgery": PROMPTS_DIR / "surgery-parameterised.md",
    "personas": PROMPTS_DIR / "personas-parameterised.md",
    "unforgettable": PROMPTS_DIR / "unforgettable-parameterised.md",
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


def load_file(path: Path) -> str:
    """Load a file's contents."""
    with open(path, "r") as f:
        return f.read()


def generate_critiques(client: openai.OpenAI, model: str, prompt_template: str, paper_text: str) -> str:
    """Generate critiques using the specified model via Responses API."""
    full_prompt = prompt_template.replace("{{paper}}", paper_text)
    full_prompt = full_prompt.replace("{{num_critiques}}", str(NUM_CRITIQUES))

    print(f"  Generating critiques with {model}...")
    response = client.responses.create(
        model=model,
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
    """Grade a single critique using the ACORN rubric. Always uses GRADER_MODEL."""
    grading_prompt = grader_template.replace("{{position}}", paper_text).replace("{{critique}}", critique)

    try:
        response = client.responses.create(
            model=GRADER_MODEL,
            input=grading_prompt,
        )

        response_text = response.output_text

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


def parse_args():
    parser = argparse.ArgumentParser(description="Run weak model grader accuracy experiment")
    parser.add_argument("--model", choices=list(MODEL_CONFIGS.keys()), required=True,
                        help="Which weak model to generate critiques with")
    parser.add_argument("--paper", choices=list(PAPER_CONFIGS.keys()), required=True,
                        help="Which paper to run experiment on")
    parser.add_argument("--prompt", default=None,
                        help="Run only a specific prompt (e.g., 'surgery')")
    return parser.parse_args()


if __name__ == "__main__":
    args = parse_args()

    # Configure directories
    model_config = MODEL_CONFIGS[args.model]
    dir_suffix = model_config["dir_suffix"]

    # Add paper suffix for non-default papers
    paper_dir_suffix = ""
    if args.paper == "compute-bottlenecks":
        paper_dir_suffix = "-cb"
    elif args.paper == "convergence-and-compromise":
        paper_dir_suffix = "-cc"

    OUTPUTS_DIR = BASE_DIR / f"outputs-{dir_suffix}{paper_dir_suffix}"
    PARSED_DIR = OUTPUTS_DIR / "parsed"
    RESULTS_DIR = BASE_DIR / f"results-{dir_suffix}{paper_dir_suffix}"

    paper_path = PAPER_CONFIGS[args.paper]["path"]
    PAPERS = {args.paper: paper_path}

    # Filter prompts if specified
    prompts = dict(PROMPTS)
    if args.prompt:
        if args.prompt not in prompts:
            print(f"Unknown prompt: {args.prompt}")
            print(f"Available prompts: {', '.join(prompts.keys())}")
            sys.exit(1)
        prompts = {args.prompt: prompts[args.prompt]}

    # Create directories
    OUTPUTS_DIR.mkdir(exist_ok=True)
    PARSED_DIR.mkdir(exist_ok=True)
    RESULTS_DIR.mkdir(exist_ok=True)

    print(f"Weak model experiment")
    print(f"  Generation model: {args.model}")
    print(f"  Grader model: {GRADER_MODEL}")
    print(f"  Paper: {args.paper}")
    print(f"  Outputs: {OUTPUTS_DIR}")
    print(f"  Results: {RESULTS_DIR}")
    print(f"  Prompts: {', '.join(prompts.keys())}")
    print()

    client = openai.OpenAI()
    grader_template = load_file(GRADER_PATH)

    all_results = {}

    # Step 1: Generate critiques
    print("\n=== STEP 1: Generating Critiques ===\n")

    for prompt_name, prompt_path in prompts.items():
        prompt_template = load_file(prompt_path)

        for paper_name, paper_path in PAPERS.items():
            print(f"Processing {prompt_name} x {paper_name}...")

            paper_text = load_file(paper_path)
            output_file = OUTPUTS_DIR / f"{prompt_name}-{paper_name}.md"

            if output_file.exists():
                print(f"  Using cached output from {output_file}")
                output = load_file(output_file)
            else:
                output = generate_critiques(client, args.model, prompt_template, paper_text)
                with open(output_file, "w") as f:
                    f.write(output)
                print(f"  Saved to {output_file}")

            critiques = parse_critiques(output)
            print(f"  Parsed {len(critiques)} critiques")

            if len(critiques) != NUM_CRITIQUES:
                print(f"  WARNING: Expected {NUM_CRITIQUES} critiques, got {len(critiques)}")

            for i, critique in enumerate(critiques, 1):
                parsed_file = PARSED_DIR / f"{prompt_name}-{paper_name}-{i:02d}.txt"
                with open(parsed_file, "w") as f:
                    f.write(critique)

            print()

    # Step 2: Grade each critique (using GRADER_MODEL)
    print(f"\n=== STEP 2: Grading Critiques (using {GRADER_MODEL}) ===\n")

    for prompt_name in prompts.keys():
        all_results[prompt_name] = {
            "scores": [],
            "by_paper": {}
        }

        for paper_name, paper_path in PAPERS.items():
            paper_text = load_file(paper_path)
            all_results[prompt_name]["by_paper"][paper_name] = []

            parsed_files = sorted(PARSED_DIR.glob(f"{prompt_name}-{paper_name}-*.txt"))

            print(f"Grading {prompt_name} x {paper_name} ({len(parsed_files)} critiques)...")

            for i, parsed_file in enumerate(parsed_files, 1):
                critique = load_file(parsed_file)

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
                        with open(grade_file, "w") as f:
                            json.dump(score.__dict__, f, indent=2)
                        print(f"overall={score.overall:.2f}")
                    else:
                        print("FAILED")
                        continue

                    time.sleep(0.5)

                if score:
                    all_results[prompt_name]["scores"].append(score)
                    all_results[prompt_name]["by_paper"][paper_name].append(score)

            print()

    # Step 3: Aggregate and analyze
    print("\n=== STEP 3: Results Analysis ===\n")

    dimensions = ["centrality", "strength", "correctness", "clarity", "dead_weight", "single_issue", "overall"]

    summary = {
        "_meta": {
            "generation_model": args.model,
            "grader_model": GRADER_MODEL,
            "paper": args.paper,
        }
    }

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
