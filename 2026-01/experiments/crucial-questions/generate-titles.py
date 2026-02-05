#!/usr/bin/env python3
"""
Generate titles for crucial question files using GPT 5.2 Pro.
Adds a 'title' field to each result JSON file.
"""

import json
import os
import sys
from pathlib import Path
from dotenv import load_dotenv
import anthropic

# Load environment variables from .env file
ENV_PATH = Path("/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/acorn-benchmark/.env")
load_dotenv(ENV_PATH)

# Configuration - map results directory to parsed directory
RESULTS_TO_PARSED = {
    # GPT results
    'results-gpt': 'outputs-gpt/parsed',
    'results-gpt-cb': 'outputs-gpt-cb/parsed',
    'results-gpt-cc': 'outputs-gpt-cc/parsed',
}

TITLE_PROMPT = """You are helping to label research questions for a research interface. Given a crucial open question generated from an academic paper, generate a short title that captures the essence of the question.

## Requirements

- **Length**: 5-12 words (a short phrase is ideal)
- **Style**: A noun phrase or question fragment that summarizes the core uncertainty
- **Content**: Capture what the question is actually asking about, not just the topic area
- **Tone**: Academic but accessible

## Good examples

- "Threshold for coordination failure vs technical solutions"
- "Where correlated governance dimensions break down"
- "Digital minds' moral weight under copying"
- "When slow takeoff assumptions become false"
- "Enforcement capacity vs distributed compute"

## Bad examples (too vague or generic)

- "An important question"
- "Issues with assumptions"
- "Uncertainty about timelines"
- "The alignment question" (too short, doesn't say what specifically)

## Question

{question}

## Response

Respond with ONLY the title, no quotes, no explanation."""


def generate_title(client: anthropic.Anthropic, question_text: str) -> str:
    """Generate a title for a question using Claude Haiku."""
    response = client.messages.create(
        model="claude-haiku-4-5-20251001",
        max_tokens=100,
        messages=[{"role": "user", "content": TITLE_PROMPT.format(question=question_text)}],
    )
    return response.content[0].text.strip()


def process_all_questions(dry_run: bool = False):
    """Process all question files and add titles."""
    client = anthropic.Anthropic()
    base_dir = Path(__file__).parent

    processed = 0
    skipped = 0
    errors = 0

    for results_dir, parsed_dir in RESULTS_TO_PARSED.items():
        results_path = base_dir / results_dir
        parsed_path = base_dir / parsed_dir

        if not results_path.exists():
            print(f"Warning: {results_path} does not exist, skipping")
            continue

        # Find all result JSON files (exclude summary.json)
        for result_file in sorted(results_path.glob("*.json")):
            if result_file.name == "summary.json":
                continue

            # Load result JSON
            with open(result_file) as f:
                result_data = json.load(f)

            # Skip if already has a title
            if result_data.get('title'):
                print(f"Skipping {result_file.name} (already has title)")
                skipped += 1
                continue

            # Find corresponding question text
            question_file = parsed_path / f"{result_file.stem}.txt"
            if not question_file.exists():
                print(f"Warning: No question file for {result_file.name}")
                errors += 1
                continue

            with open(question_file) as f:
                question_text = f.read().strip()

            if dry_run:
                print(f"Would process: {result_file.name}")
                continue

            # Generate title
            try:
                title = generate_title(client, question_text)
                print(f"{result_file.name}: {title}")

                # Update result JSON
                result_data['title'] = title
                with open(result_file, 'w') as f:
                    json.dump(result_data, f, indent=2)

                processed += 1

            except Exception as e:
                print(f"Error processing {result_file.name}: {e}")
                errors += 1

    print(f"\nDone! Processed: {processed}, Skipped: {skipped}, Errors: {errors}")


if __name__ == "__main__":
    dry_run = "--dry-run" in sys.argv
    if dry_run:
        print("DRY RUN - no changes will be made\n")

    process_all_questions(dry_run=dry_run)
