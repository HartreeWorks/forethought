#!/usr/bin/env python3
"""Build the HTML report with all critique data."""

import json
from pathlib import Path

BASE_DIR = Path(__file__).parent
RESULTS_DIR = BASE_DIR / "results"
PARSED_DIR = BASE_DIR / "outputs" / "parsed"
TEMPLATE_PATH = RESULTS_DIR / "report.html"
OUTPUT_PATH = RESULTS_DIR / "report.html"

PROMPTS = ["surgery", "personas", "unforgettable"]
PAPERS = ["convergence", "no-easy-eutopia"]


def load_critique_data():
    """Load all critiques and their grades."""
    critiques = []

    for prompt in PROMPTS:
        for paper in PAPERS:
            for i in range(1, 16):
                # Load critique text
                critique_file = PARSED_DIR / f"{prompt}-{paper}-{i:02d}.txt"
                grade_file = RESULTS_DIR / f"{prompt}-{paper}-{i:02d}.json"

                if not critique_file.exists() or not grade_file.exists():
                    print(f"Missing: {critique_file} or {grade_file}")
                    continue

                with open(critique_file) as f:
                    text = f.read().strip()

                with open(grade_file) as f:
                    scores = json.load(f)

                critiques.append({
                    "prompt": prompt,
                    "paper": paper,
                    "num": i,
                    "text": text,
                    "scores": scores
                })

    return critiques


def build_report():
    """Build the final HTML report."""
    critiques = load_critique_data()
    print(f"Loaded {len(critiques)} critiques")

    # Read template
    with open(TEMPLATE_PATH) as f:
        html = f.read()

    # Inject data
    data_json = json.dumps(critiques, indent=2)
    html = html.replace("CRITIQUE_DATA_PLACEHOLDER", data_json)

    # Write output
    with open(OUTPUT_PATH, "w") as f:
        f.write(html)

    print(f"Report written to {OUTPUT_PATH}")


if __name__ == "__main__":
    build_report()
