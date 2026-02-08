#!/usr/bin/env python3
"""
Add one-line conversational summaries to critique files.

Uses Claude Opus 4.6 to generate a ~30-word summary capturing the core insight,
then prepends it to each file.

Usage:
    python add-summaries.py          # dry run (preview summaries)
    python add-summaries.py --apply  # write summaries to files
"""

import os
import glob
import subprocess
import sys
from concurrent.futures import ThreadPoolExecutor, as_completed

DRY_RUN = "--apply" not in sys.argv
MAX_WORKERS = 10  # parallel API calls
MODEL = "claude-opus-4-6"

BASE = os.path.dirname(os.path.abspath(__file__))

SYSTEM_PROMPT = """You are helping researchers quickly scan academic critiques during pairwise comparison. Given a critique of a research paper, write a single conversational sentence that captures the core insight or "aha" of the critique.

Guidelines:
- Write as if explaining the key idea to a smart colleague over coffee
- Focus on the *insight* or *mechanism*, not a description of what the critique does
- Be specific enough that someone could distinguish this critique from others on the same paper
- Avoid jargon where possible; where technical terms are needed, use them naturally
- Don't start with "The critique argues..." or similar meta-framing—just state the insight directly
- One sentence only, no more than ~30 words

Respond with only the summary sentence, nothing else."""


GPT_DIRS = [
    os.path.join(BASE, "outputs-gpt", "parsed"),
    os.path.join(BASE, "outputs-gpt-cb", "parsed"),
    os.path.join(BASE, "outputs-gpt-cc", "parsed"),
    os.path.join(BASE, "outputs-gpt-wv", "parsed"),
]

BASE_VARIANT_PREFIXES = [
    "conversational-",
    "baseline-v2-",
    "surgery-",
    "personas-",
    "unforgettable-",
    "pivot-attack-",
    "authors-tribunal-",
    "pre-mortem-",
]


def find_target_files():
    """Find all base-variant critique files in GPT dirs that need a summary."""
    files = set()
    for d in GPT_DIRS:
        for prefix in BASE_VARIANT_PREFIXES:
            pat = os.path.join(d, prefix + "*.txt")
            files.update(glob.glob(pat))

    targets = []
    for f in sorted(files):
        with open(f) as fh:
            first_line = fh.readline().strip()
        # Skip files that already have a summary (start with > blockquote)
        if first_line.startswith(">"):
            continue
        targets.append(f)
    return targets


def generate_summary(filepath):
    """Call Claude to generate a one-line summary for a critique."""
    with open(filepath) as f:
        critique = f.read().strip()

    prompt = f"{SYSTEM_PROMPT}\n\n---\n\n{critique}"

    try:
        result = subprocess.run(
            ["claude", "-p", prompt, "--model", MODEL],
            capture_output=True, text=True, timeout=60,
        )
        if result.returncode != 0:
            return filepath, None, f"CLI error: {result.stderr[:200]}"
        summary = result.stdout.strip()
        # Remove quotes if the model wrapped the sentence in them
        if summary.startswith('"') and summary.endswith('"'):
            summary = summary[1:-1]
        return filepath, summary, None
    except subprocess.TimeoutExpired:
        return filepath, None, "Timeout"
    except Exception as e:
        return filepath, None, str(e)


def prepend_summary(filepath, summary):
    """Prepend a blockquote summary to a file."""
    with open(filepath) as f:
        content = f.read()
    new_content = f"> {summary}\n\n{content}"
    with open(filepath, "w") as f:
        f.write(new_content)


def main():
    targets = find_target_files()
    print(f"Found {len(targets)} files to process")
    print(f"Mode: {'DRY RUN' if DRY_RUN else 'APPLYING CHANGES'}")
    print(f"Model: {MODEL}")
    print(f"Workers: {MAX_WORKERS}\n")

    success = 0
    failed = []

    with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
        futures = {executor.submit(generate_summary, f): f for f in targets}

        for future in as_completed(futures):
            filepath, summary, error = future.result()
            relpath = os.path.relpath(filepath, BASE)

            if error:
                failed.append((relpath, error))
                print(f"FAILED: {relpath} ({error})")
                continue

            success += 1
            word_count = len(summary.split())

            if DRY_RUN:
                print(f"[{success}/{len(targets)}] {relpath}")
                print(f"  ({word_count}w) {summary}\n")
            else:
                prepend_summary(filepath, summary)
                print(f"[{success}/{len(targets)}] OK: {relpath} ({word_count}w)")

    print(f"\n{'DRY RUN - ' if DRY_RUN else ''}Processed {success}/{len(targets)} files")
    if failed:
        print(f"Failed: {len(failed)} files:")
        for path, err in failed:
            print(f"  {path}: {err}")


if __name__ == "__main__":
    main()
