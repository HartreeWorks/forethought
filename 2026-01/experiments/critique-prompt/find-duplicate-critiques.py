#!/usr/bin/env python3
"""
Find duplicate critiques across prompts for a given paper.

Usage:
    python find-duplicate-critiques.py no-easy-eutopia
    python find-duplicate-critiques.py compute-bottlenecks
    python find-duplicate-critiques.py convergence-compromise

Outputs:
    duplicates-{paper}.json with cluster assignments and descriptions
"""

import argparse
import json
import os
import sys
from pathlib import Path
from dotenv import load_dotenv
from openai import OpenAI

# Load API keys from the acorn-benchmark .env file
ENV_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/shared/2026-01/research/llm-grader-research/.env")
load_dotenv(ENV_PATH)

# Paper configurations
PAPERS = {
    'no-easy-eutopia': {
        'parsed_dir': 'outputs-gpt/parsed',
        'suffix': 'no-easy-eutopia',
    },
    'compute-bottlenecks': {
        'parsed_dir': 'outputs-gpt-cb/parsed',
        'suffix': 'compute-bottlenecks',
    },
    'convergence-compromise': {
        'parsed_dir': 'outputs-gpt-cc/parsed',
        'suffix': 'convergence-and-compromise',
    },
}

# All 8 prompt variants
PROMPTS = [
    'baseline-v2',
    'conversational',
    'unforgettable',
    'personas',
    'surgery',
    'pivot-attack',
    'authors-tribunal',
    'pre-mortem',
]


def load_critiques(paper_key: str, base_dir: Path) -> dict[str, str]:
    """Load all critique texts for a paper."""
    config = PAPERS[paper_key]
    parsed_dir = base_dir / config['parsed_dir']
    suffix = config['suffix']

    critiques = {}
    for prompt in PROMPTS:
        for i in range(1, 11):
            filename = f"{prompt}-{suffix}-{i:02d}.txt"
            filepath = parsed_dir / filename
            if filepath.exists():
                critiques[f"{prompt}-{suffix}-{i:02d}"] = filepath.read_text()
            else:
                print(f"Warning: Missing file {filepath}", file=sys.stderr)

    return critiques


def find_duplicates_with_llm(critiques: dict[str, str], client: OpenAI) -> dict:
    """Use LLM to identify duplicate clusters."""

    # Format critiques for the prompt
    critique_list = "\n\n".join([
        f"[{name}]\n{text[:1500]}..." if len(text) > 1500 else f"[{name}]\n{text}"
        for name, text in critiques.items()
    ])

    system_prompt = """You are an expert at identifying when different texts make fundamentally the same argument.

Your task is to identify clusters of critiques that make the same core argument, even if expressed differently.

Rules:
1. Two critiques are "duplicates" if they identify the same fundamental weakness or make the same core point
2. Surface-level differences (wording, structure, examples) don't matter—focus on the underlying argument
3. If a critique makes multiple points, cluster it based on its PRIMARY argument
4. Critiques with genuinely distinct arguments should NOT be clustered together
5. A cluster must have at least 2 critiques

Output JSON with this structure:
{
    "clusters": [
        {
            "description": "Brief description of the shared argument (1-2 sentences)",
            "critiques": ["filename1", "filename2", ...]
        }
    ],
    "unique": ["filename3", "filename4", ...]  // Critiques that don't belong to any cluster
}"""

    user_prompt = f"""Analyze these {len(critiques)} critiques and identify which ones make the same fundamental argument.

{critique_list}

Return your analysis as JSON."""

    response = client.chat.completions.create(
        model="gpt-5.2",
        messages=[
            {"role": "system", "content": system_prompt},
            {"role": "user", "content": user_prompt},
        ],
        response_format={"type": "json_object"},
        temperature=0.3,
    )

    return json.loads(response.choices[0].message.content)


def main():
    parser = argparse.ArgumentParser(description="Find duplicate critiques for a paper")
    parser.add_argument("paper", choices=list(PAPERS.keys()), help="Paper to analyze")
    parser.add_argument("--dry-run", action="store_true", help="Load critiques but don't call LLM")
    args = parser.parse_args()

    # Determine base directory (script is in the data dir)
    base_dir = Path(__file__).parent

    print(f"Loading critiques for {args.paper}...", file=sys.stderr)
    critiques = load_critiques(args.paper, base_dir)
    print(f"Loaded {len(critiques)} critiques", file=sys.stderr)

    if args.dry_run:
        print("Dry run - critiques loaded:")
        for name in sorted(critiques.keys()):
            print(f"  {name}: {len(critiques[name])} chars")
        return

    # Initialize OpenAI client
    client = OpenAI()

    print("Analyzing with LLM...", file=sys.stderr)
    result = find_duplicates_with_llm(critiques, client)

    # Add metadata
    output = {
        "paper": args.paper,
        "total_critiques": len(critiques),
        "clusters": result.get("clusters", []),
        "unique": result.get("unique", []),
    }

    # Calculate summary stats
    clustered_count = sum(len(c["critiques"]) for c in output["clusters"])
    output["summary"] = {
        "clustered": clustered_count,
        "unique": len(output["unique"]),
        "cluster_count": len(output["clusters"]),
    }

    # Write output
    output_path = base_dir / f"duplicates-{args.paper}.json"
    output_path.write_text(json.dumps(output, indent=2))
    print(f"Wrote results to {output_path}", file=sys.stderr)

    # Also print summary
    print(f"\nSummary for {args.paper}:")
    print(f"  Total critiques: {len(critiques)}")
    print(f"  Clusters found: {len(output['clusters'])}")
    print(f"  Critiques in clusters: {clustered_count}")
    print(f"  Unique critiques: {len(output['unique'])}")

    for i, cluster in enumerate(output["clusters"], 1):
        print(f"\n  Cluster {i}: {cluster['description']}")
        print(f"    Critiques: {', '.join(cluster['critiques'])}")


if __name__ == "__main__":
    main()
