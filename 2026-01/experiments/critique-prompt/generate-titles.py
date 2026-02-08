#!/usr/bin/env python3
"""
Generate titles for critique files using Claude Opus 4.5.
Adds a 'title' field to each result JSON file.
"""

import json
import os
import sys
from pathlib import Path
from dotenv import load_dotenv
import anthropic

# Load environment variables from .env file
load_dotenv()

# Remove ANTHROPIC_BASE_URL if set (SDK adds /v1 automatically)
if 'ANTHROPIC_BASE_URL' in os.environ:
    del os.environ['ANTHROPIC_BASE_URL']

# Configuration - map results directory to parsed directory
RESULTS_TO_PARSED = {
    # GPT results
    'results-gpt': 'outputs-gpt/parsed',
    'results-gpt-cb': 'outputs-gpt-cb/parsed',
    'results-gpt-cc': 'outputs-gpt-cc/parsed',
    # Claude results
    'results-claude': 'outputs-claude/parsed',
    'results-claude-cb': 'outputs-claude-cb/parsed',
    'results-claude-cc': 'outputs-claude-cc/parsed',
    # Gemini results
    'results-gemini': 'outputs-gemini/parsed',
    'results-gemini-cb': 'outputs-gemini-cb/parsed',
    'results-gemini-cc': 'outputs-gemini-cc/parsed',
    # GPT-4.1 Mini results
    'results-gpt41mini': 'outputs-gpt41mini/parsed',
    # GPT worldview primer results
    'results-gpt-wv': 'outputs-gpt-wv/parsed',
    # ACORN v3 re-graded results
    'results-gpt-v3': 'outputs-gpt/parsed',
    'results-gpt-wv-v3': 'outputs-gpt-wv/parsed',
    # Claude Opus 4.6 grader results
    'results-gpt-claude-grader': 'outputs-gpt/parsed',
    'results-gpt41mini-claude-grader': 'outputs-gpt41mini/parsed',
}

TITLE_PROMPT = """You are helping to label philosophical critiques for a research interface. Given a critique of an academic paper, generate a short title that captures the essence of the objection.

## Requirements

- **Length**: 5-15 words (a short sentence is ideal)
- **Style**: A sentence that summarizes the core objection
- **Content**: Capture what the critique actually argues, not just the topic area
- **Tone**: Academic but accessible

## Good examples

- "Correlated moral dimensions undermine the multiplicative fragility model"
- "The toy model's independence assumption lacks empirical grounding"
- "Error-correcting institutions could make eutopia easier to hit"
- "Moral convergence over time may enlarge the target"
- "The paper conflates moral uncertainty with moral disagreement"

## Bad examples (too vague or generic)

- "A problem with the argument"
- "Issues with the model"
- "Disagreement about values"
- "The independence objection" (too short, doesn't say what the objection is)

## Critique

{critique}

## Response

Respond with ONLY the title, no quotes, no explanation."""


def generate_title(client: anthropic.Anthropic, critique_text: str) -> str:
    """Generate a title for a critique using Claude."""
    response = client.messages.create(
        model="claude-3-haiku-20240307",
        max_tokens=100,
        messages=[
            {"role": "user", "content": TITLE_PROMPT.format(critique=critique_text)}
        ],
    )
    return response.content[0].text.strip()


def process_all_critiques(dry_run: bool = False):
    """Process all critique files and add titles."""
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

        # Find all result JSON files
        for result_file in sorted(results_path.glob("*.json")):
            # Load result JSON
            with open(result_file) as f:
                result_data = json.load(f)

            # Skip if already has a title
            if result_data.get('title'):
                print(f"Skipping {result_file.name} (already has title)")
                skipped += 1
                continue

            # Find corresponding critique text
            critique_file = parsed_path / f"{result_file.stem}.txt"
            if not critique_file.exists():
                print(f"Warning: No critique file for {result_file.name}")
                errors += 1
                continue

            with open(critique_file) as f:
                critique_text = f.read().strip()

            if dry_run:
                print(f"Would process: {result_file.name}")
                continue

            # Generate title
            try:
                title = generate_title(client, critique_text)
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

    process_all_critiques(dry_run=dry_run)
