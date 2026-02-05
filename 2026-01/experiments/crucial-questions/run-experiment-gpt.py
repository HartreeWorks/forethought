#!/usr/bin/env python3
"""
Crucial Questions Prompt Experiment: GPT-5.2 Pro

Tests which of 4 prompts produces the highest quality crucial questions
using GPT-5.2 Pro, measured by a custom crucial questions grader.

This version uses parameterised prompts with configurable question count.
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
MODEL = "gpt-5.2-pro"
NUM_QUESTIONS = 7
BASE_DIR = Path(__file__).parent
PROMPTS_DIR = BASE_DIR / "prompts"

# Paper configurations
PAPER_CONFIGS = {
    "no-easy-eutopia": {
        "path": Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/no-easy-eutopia.md"),
        "outputs_dir": BASE_DIR / "outputs-gpt",
        "results_dir": BASE_DIR / "results-gpt",
    },
    "compute-bottlenecks": {
        "path": Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/will-compute-bottlenecks-prevent-a-sie.md"),
        "outputs_dir": BASE_DIR / "outputs-gpt-cb",
        "results_dir": BASE_DIR / "results-gpt-cb",
    },
    "convergence-and-compromise": {
        "path": Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/papers/convergence-and-compromise.md"),
        "outputs_dir": BASE_DIR / "outputs-gpt-cc",
        "results_dir": BASE_DIR / "results-gpt-cc",
    },
}

# These will be set by command line args
OUTPUTS_DIR = None
PARSED_DIR = None
RESULTS_DIR = None
PAPERS = {}

# Prompts - parameterised versions
PROMPTS = {
    "baseline": PROMPTS_DIR / "baseline-parameterised.md",
    "decision-pivots": PROMPTS_DIR / "decision-pivots-parameterised.md",
    "adversarial-worldviews": PROMPTS_DIR / "adversarial-worldviews-parameterised.md",
    "branching-futures": PROMPTS_DIR / "branching-futures-parameterised.md",
}

# Crucial Questions Grader
GRADER_PATH = BASE_DIR / "grader-crucial-questions.txt"


@dataclass
class QuestionScore:
    """Scores for a single crucial question."""
    cruciality: float
    paper_specificity: float
    tractability: float
    clarity: float
    decision_hook: float
    dead_weight: float
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


def generate_questions(client: openai.OpenAI, prompt_template: str, paper_text: str) -> str:
    """Generate questions using GPT-5.2 Pro via Responses API."""
    # Replace the placeholders with actual values
    full_prompt = prompt_template.replace("{{paper}}", paper_text)
    full_prompt = full_prompt.replace("{{num_questions}}", str(NUM_QUESTIONS))

    print(f"  Generating questions...")
    response = client.responses.create(
        model=MODEL,
        input=full_prompt,
    )

    return response.output_text


def parse_questions(output: str) -> list[str]:
    """Parse numbered questions from output text.

    Expects format:
    1. [question text]

    2. [question text]
    ...
    """
    # Split on numbered patterns (1. , 2. , etc.)
    # This regex matches the start of a numbered item
    pattern = r'^\s*(\d+)\.\s+'

    # Find all matches and their positions
    matches = list(re.finditer(pattern, output, re.MULTILINE))

    questions = []
    for i, match in enumerate(matches):
        start = match.end()
        # End is either the next match or end of string
        if i + 1 < len(matches):
            end = matches[i + 1].start()
        else:
            end = len(output)

        question_text = output[start:end].strip()
        if question_text:
            questions.append(question_text)

    return questions


def grade_question(client: openai.OpenAI, grader_template: str, paper_text: str, question: str) -> Optional[QuestionScore]:
    """Grade a single question using the crucial questions rubric via Responses API."""
    # Build the grading prompt (reusing {{position}} and {{critique}} placeholders from ACORN format)
    grading_prompt = grader_template.replace("{{position}}", paper_text).replace("{{critique}}", question)

    try:
        response = client.responses.create(
            model=MODEL,
            input=grading_prompt,
        )

        response_text = response.output_text

        # Extract JSON from response
        # Try to find JSON block in markdown code fence first
        json_match = re.search(r'```(?:json)?\s*(\{.*?\})\s*```', response_text, re.DOTALL)
        if json_match:
            json_str = json_match.group(1)
        else:
            # Try to find raw JSON
            json_match = re.search(r'\{[^{}]*"cruciality"[^{}]*\}', response_text, re.DOTALL)
            if json_match:
                json_str = json_match.group(0)
            else:
                # Try the whole response
                json_str = response_text.strip()

        scores = json.loads(json_str)

        return QuestionScore(
            cruciality=scores.get("cruciality", 0),
            paper_specificity=scores.get("paper_specificity", 0),
            tractability=scores.get("tractability", 0),
            clarity=scores.get("clarity", 0),
            decision_hook=scores.get("decision_hook", 0),
            dead_weight=scores.get("dead_weight", 0),
            overall=scores.get("overall", 0),
            reasoning=scores.get("reasoning", "")
        )
    except Exception as e:
        print(f"    Error grading question: {e}")
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
    parser = argparse.ArgumentParser(description="Run crucial questions prompt experiment")
    parser.add_argument("--paper", choices=list(PAPER_CONFIGS.keys()), required=True,
                        help="Which paper to run experiment on")
    parser.add_argument("--prompt", default=None,
                        help="Run only a specific prompt (e.g., 'baseline')")
    return parser.parse_args()


if __name__ == "__main__":
    args = parse_args()

    # Configure directories for selected paper
    config = PAPER_CONFIGS[args.paper]
    OUTPUTS_DIR = config["outputs_dir"]
    PARSED_DIR = OUTPUTS_DIR / "parsed"
    RESULTS_DIR = config["results_dir"]
    PAPERS = {args.paper: config["path"]}

    # If running specific prompt, filter PROMPTS
    if args.prompt:
        if args.prompt not in PROMPTS:
            print(f"Unknown prompt: {args.prompt}")
            print(f"Available prompts: {', '.join(PROMPTS.keys())}")
            sys.exit(1)
        PROMPTS = {args.prompt: PROMPTS[args.prompt]}

    print(f"Running experiment for paper: {args.paper}")
    print(f"Outputs: {OUTPUTS_DIR}")
    print(f"Results: {RESULTS_DIR}")
    print(f"Prompts: {', '.join(PROMPTS.keys())}")
    print()

    # Run with configured globals
    ensure_dirs()

    client = openai.OpenAI()
    grader_template = load_file(GRADER_PATH)

    # Store all results
    all_results = {}

    # Step 1: Generate questions for each prompt/paper combination
    print("\n=== STEP 1: Generating Questions ===\n")

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
                output = generate_questions(client, prompt_template, paper_text)

                # Save output
                with open(output_file, "w") as f:
                    f.write(output)
                print(f"  Saved to {output_file}")

            # Parse questions
            questions = parse_questions(output)
            print(f"  Parsed {len(questions)} questions")

            if len(questions) != NUM_QUESTIONS:
                print(f"  WARNING: Expected {NUM_QUESTIONS} questions, got {len(questions)}")

            # Save parsed questions
            for i, question in enumerate(questions, 1):
                parsed_file = PARSED_DIR / f"{prompt_name}-{paper_name}-{i:02d}.txt"
                with open(parsed_file, "w") as f:
                    f.write(question)

            print()

    # Step 2: Grade each question
    print("\n=== STEP 2: Grading Questions ===\n")

    for prompt_name in PROMPTS.keys():
        all_results[prompt_name] = {
            "scores": [],
            "by_paper": {}
        }

        for paper_name, paper_path in PAPERS.items():
            paper_text = load_file(paper_path)
            all_results[prompt_name]["by_paper"][paper_name] = []

            # Find all parsed questions for this combination
            parsed_files = sorted(PARSED_DIR.glob(f"{prompt_name}-{paper_name}-*.txt"))

            print(f"Grading {prompt_name} x {paper_name} ({len(parsed_files)} questions)...")

            for i, parsed_file in enumerate(parsed_files, 1):
                question = load_file(parsed_file)

                # Check for cached grade
                grade_file = RESULTS_DIR / f"{prompt_name}-{paper_name}-{i:02d}.json"

                if grade_file.exists():
                    with open(grade_file, "r") as f:
                        cached = json.load(f)
                    score = QuestionScore(**cached)
                    print(f"  Question {i}: cached (overall={score.overall:.2f})")
                else:
                    print(f"  Grading question {i}...", end=" ")
                    score = grade_question(client, grader_template, paper_text, question)

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

    dimensions = ["cruciality", "paper_specificity", "tractability", "clarity", "decision_hook", "dead_weight", "overall"]

    summary = {}

    for prompt_name, data in all_results.items():
        scores = data["scores"]

        if not scores:
            print(f"{prompt_name}: No scores collected")
            continue

        summary[prompt_name] = {}

        print(f"\n{prompt_name.upper()} ({len(scores)} questions)")
        print("-" * 50)

        for dim in dimensions:
            values = [getattr(s, dim) for s in scores]
            stats = compute_stats(values)
            summary[prompt_name][dim] = stats

            print(f"  {dim:20s}: mean={stats['mean']:.3f}, std={stats['std']:.3f}, range=[{stats['min']:.2f}, {stats['max']:.2f}]")

        # Compute composite score: cruciality * paper_specificity (key metric)
        composite = [s.cruciality * s.paper_specificity for s in scores]
        composite_stats = compute_stats(composite)
        summary[prompt_name]["cruciality_x_specificity"] = composite_stats
        print(f"  {'crucial*specific':20s}: mean={composite_stats['mean']:.3f}, std={composite_stats['std']:.3f}")

    # Save full summary
    summary_file = RESULTS_DIR / "summary.json"
    with open(summary_file, "w") as f:
        json.dump(summary, f, indent=2)
    print(f"\nFull summary saved to {summary_file}")

    print("\n=== COMPLETE ===")
