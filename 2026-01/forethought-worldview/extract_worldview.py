#!/usr/bin/env python3
"""
Pass 1: Extract worldview content from each Forethought Research paper.

Uses Claude Opus 4.6 via Anthropic SDK with async parallelism.
Outputs one YAML file per paper in extractions/.
"""

import anthropic
import argparse
import asyncio
import json
import os
import sys
import time
from pathlib import Path
from dotenv import load_dotenv

# Load API keys
ENV_PATH = Path(__file__).parent / ".env"
if ENV_PATH.exists():
    load_dotenv(ENV_PATH)

# Fallback: try the experiments .env
if not os.environ.get("ANTHROPIC_API_KEY"):
    fallback = Path(__file__).resolve().parent.parent.parent / "work/shared/2026-01/experiments/tools/acorn-benchmark-promptfoo-port/.env"
    if fallback.exists():
        load_dotenv(fallback)

MODEL = "claude-opus-4-6"
MAX_CONCURRENT = 7
MAX_TOKENS = 4096

BASE_DIR = Path(__file__).parent
MANIFEST_FILE = BASE_DIR / "manifest.json"
PAPERS_DIR = BASE_DIR / "papers-and-blog-posts"
EXTRACTIONS_DIR = BASE_DIR / "extractions"

PROMPT_TEMPLATE = """You are extracting **worldview content** from a Forethought Research paper. Your goal is NOT to summarise the paper but to identify what it reveals about the research group's underlying premises, commitments, and intellectual orientation.

This extraction will be used to build a "worldview primer"—a reference document that helps AI critique tools understand what Forethought researchers already accept, so that generated critiques don't waste time attacking premises the team has deeply considered.

## What to extract

1. **Premises taken as given**: What does this paper assume without arguing for it? These are the foundations it builds on. For each, assess confidence level:
   - **near-certain**: The paper treats this as essentially settled (e.g. "AI will be transformative")
   - **strong**: The paper leans heavily on this but acknowledges it's debatable (e.g. "lock-in is feasible")

2. **Distinctive claims**: What does this paper argue for that is novel or characteristic of Forethought's perspective? Mark centrality:
   - **thesis**: The paper's central argument
   - **load-bearing**: Critical to the argument but not the main point
   - **supporting**: Contributes but isn't essential

3. **Positions rejected or deprioritised**: What views does this paper explicitly argue against, dismiss, or set aside? Why?

4. **Methodological commitments**: How does this paper approach its questions? (e.g. expected value reasoning, historical analogy, formal modelling, thought experiments, quantitative estimates)

5. **Cross-references**: Which other Forethought papers does this one cite, build upon, or respond to?

## Rules

- Do NOT produce a generic summary. If your output could describe many papers, it's too vague.
- Do NOT list every claim. Focus on worldview-revealing content: premises, commitments, and distinctive positions.
- Do NOT conflate "what the paper argues" with "what it takes as given". The distinction is crucial.
- If the paper is primarily descriptive (e.g. a case study or overview), note what its choice of topic and framing reveals about Forethought's priorities.
- Keep each entry concise—one sentence for the claim, one for the evidence/reasoning.

## Output format

Output valid YAML matching this schema:

```yaml
paper:
  slug: "example-slug"
  title: "Paper Title"

premises_taken_as_given:
  - claim: "..."
    confidence: "near-certain|strong"
    evidence: "How the paper treats this (brief)"

distinctive_claims:
  - claim: "..."
    centrality: "thesis|load-bearing|supporting"
    key_argument: "Core reasoning (brief)"

positions_rejected:
  - position: "..."
    why_rejected: "..."

methodological_commitments:
  - "..."

cross_references:
  - "slug-of-referenced-paper"
```

Output ONLY the YAML, with no markdown code fences or other surrounding text.

---

## Paper

{paper}"""


async def extract_one(client: anthropic.AsyncAnthropic, paper: dict, semaphore: asyncio.Semaphore) -> dict:
    """Extract worldview content from a single paper."""
    slug = paper["slug"]
    output_file = EXTRACTIONS_DIR / f"{slug}.yaml"

    # Skip if already extracted
    if output_file.exists() and output_file.stat().st_size > 100:
        return {"slug": slug, "status": "skipped"}

    paper_path = BASE_DIR / paper["path"]
    paper_text = paper_path.read_text()

    prompt = PROMPT_TEMPLATE.format(paper=paper_text)

    async with semaphore:
        try:
            print(f"  Extracting: {slug} ({paper['size_bytes'] // 1024}KB)...")
            start = time.time()

            response = await client.messages.create(
                model=MODEL,
                max_tokens=MAX_TOKENS,
                messages=[{"role": "user", "content": prompt}],
            )

            output = response.content[0].text
            elapsed = time.time() - start

            # Strip markdown code fences if present
            if output.startswith("```"):
                lines = output.split("\n")
                # Remove first line (```yaml or ```) and last line (```)
                if lines[-1].strip() == "```":
                    lines = lines[1:-1]
                else:
                    lines = lines[1:]
                output = "\n".join(lines)

            output_file.write_text(output)

            input_tokens = response.usage.input_tokens
            output_tokens = response.usage.output_tokens
            print(f"  Done: {slug} ({elapsed:.1f}s, {input_tokens}+{output_tokens} tokens)")
            return {"slug": slug, "status": "ok", "input_tokens": input_tokens, "output_tokens": output_tokens}

        except Exception as e:
            print(f"  FAILED: {slug}: {e}")
            return {"slug": slug, "status": "error", "error": str(e)}


async def main():
    parser = argparse.ArgumentParser(description="Extract worldview content from papers")
    parser.add_argument("--limit", type=int, default=0, help="Process only first N papers (0 = all)")
    parser.add_argument("--slug", type=str, default=None, help="Process a single paper by slug")
    args = parser.parse_args()

    if not MANIFEST_FILE.exists():
        print(f"Error: {MANIFEST_FILE} not found. Run build_manifest.py first.")
        sys.exit(1)

    with open(MANIFEST_FILE) as f:
        papers = json.load(f)

    if args.slug:
        papers = [p for p in papers if p["slug"] == args.slug]
        if not papers:
            print(f"Error: No paper found with slug '{args.slug}'")
            sys.exit(1)
    elif args.limit > 0:
        papers = papers[:args.limit]

    EXTRACTIONS_DIR.mkdir(exist_ok=True)

    print(f"Extracting worldview content from {len(papers)} papers...")
    print(f"Model: {MODEL}, concurrency: {MAX_CONCURRENT}")
    print()

    client = anthropic.AsyncAnthropic()
    semaphore = asyncio.Semaphore(MAX_CONCURRENT)

    start_time = time.time()
    results = await asyncio.gather(*[extract_one(client, p, semaphore) for p in papers])
    elapsed = time.time() - start_time

    # Summary
    ok = [r for r in results if r["status"] == "ok"]
    skipped = [r for r in results if r["status"] == "skipped"]
    errors = [r for r in results if r["status"] == "error"]

    total_input = sum(r.get("input_tokens", 0) for r in ok)
    total_output = sum(r.get("output_tokens", 0) for r in ok)

    print(f"\nDone in {elapsed:.1f}s")
    print(f"  OK: {len(ok)}, Skipped: {len(skipped)}, Errors: {len(errors)}")
    print(f"  Tokens: {total_input:,} input + {total_output:,} output")

    if errors:
        print("\nFailed papers:")
        for r in errors:
            print(f"  - {r['slug']}: {r['error']}")


if __name__ == "__main__":
    asyncio.run(main())
