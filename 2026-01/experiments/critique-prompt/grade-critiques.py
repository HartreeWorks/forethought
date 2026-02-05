#!/usr/bin/env python3
"""
Grade critique outputs using the custom rubric and collect JSON scores.
"""

import json
import os
import anthropic
from pathlib import Path
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

def load_grader_prompt():
    with open('grader-critique-collection.txt', 'r') as f:
        return f.read()

def load_paper():
    with open('paper.md', 'r') as f:
        return f.read()

def grade_critique(client, grader_prompt: str, paper: str, critique: str) -> dict:
    """Grade a single critique collection."""

    prompt = grader_prompt.replace('{{position}}', paper).replace('{{critique}}', critique)

    message = client.messages.create(
        model="claude-sonnet-4-5-20250929",  # Use Sonnet for grading to save costs
        max_tokens=1500,
        temperature=0,
        messages=[
            {"role": "user", "content": prompt}
        ]
    )

    response_text = message.content[0].text

    # Extract JSON from response
    try:
        # Find JSON in response
        start = response_text.find('{')
        end = response_text.rfind('}') + 1
        if start >= 0 and end > start:
            json_str = response_text[start:end]
            return json.loads(json_str)
    except json.JSONDecodeError:
        pass

    return {"error": "Failed to parse JSON", "raw_response": response_text}

def main():
    client = anthropic.Anthropic()

    grader_prompt = load_grader_prompt()
    paper = load_paper()

    critiques_dir = Path('critiques')
    results = {}

    for critique_file in sorted(critiques_dir.glob('*.md')):
        if critique_file.stat().st_size < 100:  # Skip empty/tiny files
            continue

        name = critique_file.stem
        print(f"Grading: {name}...")

        with open(critique_file, 'r') as f:
            critique = f.read()

        scores = grade_critique(client, grader_prompt, paper, critique)
        results[name] = scores

        # Print scores
        if 'error' not in scores:
            print(f"  Centrality: {scores.get('centrality', 'N/A')}")
            print(f"  Specificity: {scores.get('specificity', 'N/A')}")
            print(f"  Depth: {scores.get('depth', 'N/A')}")
            print(f"  Incisiveness: {scores.get('incisiveness', 'N/A')}")
            print(f"  Variety: {scores.get('variety', 'N/A')}")
            print(f"  Slop-Free: {scores.get('slop_free', 'N/A')}")
            print(f"  Overall: {scores.get('overall', 'N/A')}")
        else:
            print(f"  Error: {scores['error']}")
        print()

    # Save results
    with open('output/grading-results.json', 'w') as f:
        json.dump(results, f, indent=2)

    print("Results saved to output/grading-results.json")

    # Print summary table
    print("\n" + "="*80)
    print("SUMMARY")
    print("="*80)
    print(f"{'Prompt':<35} {'Cent':>6} {'Spec':>6} {'Dept':>6} {'Inci':>6} {'Vari':>6} {'Slop':>6} {'Over':>6}")
    print("-"*80)

    for name, scores in sorted(results.items()):
        if 'error' not in scores:
            print(f"{name:<35} {scores.get('centrality', 0):>6.2f} {scores.get('specificity', 0):>6.2f} {scores.get('depth', 0):>6.2f} {scores.get('incisiveness', 0):>6.2f} {scores.get('variety', 0):>6.2f} {scores.get('slop_free', 0):>6.2f} {scores.get('overall', 0):>6.2f}")

if __name__ == '__main__':
    main()
