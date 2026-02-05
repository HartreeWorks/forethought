#!/usr/bin/env python3
"""
Convert ACORN benchmark JSONL to promptfoo test cases YAML.

Usage:
    python convert-acorn.py [--sample N] [--output FILE]

Options:
    --sample N      Number of samples to include (default: 20)
    --output FILE   Output file path (default: ../tests/acorn-sample.yaml)
    --seed N        Random seed for reproducible sampling (default: 42)
"""

import json
import random
import argparse
from pathlib import Path
import yaml

# ACORN dataset location
ACORN_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/assets/acorn-conceptual-reasoning-dataset/shared_dataset_v1-0.jsonl")


def load_acorn_data(path: Path) -> list[dict]:
    """Load ACORN JSONL dataset."""
    entries = []
    with open(path, "r") as f:
        for line in f:
            if line.strip():
                entries.append(json.loads(line))
    return entries


def extract_human_ratings(ratings: dict) -> dict:
    """
    Extract average human ratings from ACORN format.
    ACORN has ratings from multiple raters - we average them.
    """
    all_ratings = []
    for rater_name, rater_ratings in ratings.items():
        if rater_ratings:  # May be a list
            for r in rater_ratings:
                all_ratings.append(r)

    if not all_ratings:
        return {}

    # Average across raters
    dimensions = ["centrality", "strength", "correctness", "clarity", "dead weight", "single issue", "overall"]
    averaged = {}
    for dim in dimensions:
        # Handle different key formats (spaces vs underscores)
        key_variants = [dim, dim.replace(" ", "_"), dim.replace(" ", "")]
        values = []
        for r in all_ratings:
            for key in key_variants:
                if key in r:
                    values.append(r[key])
                    break
        if values:
            averaged[dim.replace(" ", "_")] = round(sum(values) / len(values), 3)

    return averaged


def convert_to_promptfoo_test(entry: dict, index: int) -> dict:
    """Convert a single ACORN entry to promptfoo test format."""
    position = entry["messages"][0]
    critique = entry["messages"][1]
    human_ratings = extract_human_ratings(entry.get("ratings", {}))

    return {
        "description": f"ACORN #{index}",
        "vars": {
            "position": position,
            "critique": critique,
        },
        "metadata": {
            "acorn_index": index,
            "human_ratings": human_ratings,
        }
    }


def main():
    parser = argparse.ArgumentParser(description="Convert ACORN to promptfoo format")
    parser.add_argument("--sample", type=int, default=20, help="Number of samples")
    parser.add_argument("--output", type=str, default=None, help="Output file path")
    parser.add_argument("--seed", type=int, default=42, help="Random seed")
    args = parser.parse_args()

    # Set output path
    if args.output:
        output_path = Path(args.output)
    else:
        output_path = Path(__file__).parent.parent / "tests" / "acorn-sample.yaml"

    # Load data
    print(f"Loading ACORN data from {ACORN_PATH}...")
    entries = load_acorn_data(ACORN_PATH)
    print(f"Loaded {len(entries)} entries")

    # Sample
    random.seed(args.seed)
    if args.sample < len(entries):
        sampled_indices = random.sample(range(len(entries)), args.sample)
        sampled = [(i, entries[i]) for i in sampled_indices]
    else:
        sampled = list(enumerate(entries))

    print(f"Sampled {len(sampled)} entries")

    # Convert
    tests = []
    for original_index, entry in sampled:
        test = convert_to_promptfoo_test(entry, original_index)
        tests.append(test)

    # Write YAML
    output_path.parent.mkdir(parents=True, exist_ok=True)
    with open(output_path, "w") as f:
        yaml.dump(tests, f, default_flow_style=False, allow_unicode=True, width=120)

    print(f"Wrote {len(tests)} test cases to {output_path}")

    # Print sample
    print("\nSample test case:")
    print(yaml.dump(tests[0], default_flow_style=False, allow_unicode=True, width=80))


if __name__ == "__main__":
    main()
