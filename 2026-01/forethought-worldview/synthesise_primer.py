#!/usr/bin/env python3
"""
Pass 2: Synthesise worldview extractions into a single primer document.

Concatenates all YAML extractions and sends to Claude Opus 4.6 in a single call.
"""

import anthropic
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

if not os.environ.get("ANTHROPIC_API_KEY"):
    fallback = Path(__file__).resolve().parent.parent.parent / "work/shared/2026-01/experiments/tools/acorn-benchmark-promptfoo-port/.env"
    if fallback.exists():
        load_dotenv(fallback)

MODEL = "claude-opus-4-6"
MAX_TOKENS = 8192

BASE_DIR = Path(__file__).parent
MANIFEST_FILE = BASE_DIR / "manifest.json"
EXTRACTIONS_DIR = BASE_DIR / "extractions"
OUTPUT_FILE = BASE_DIR / "primer.md"

PROMPT_TEMPLATE = """You are synthesising a "worldview primer" for Forethought Research from per-paper worldview extractions. This primer will be included as context when AI models critique Forethought's draft papers, so the critiques engage with the team's actual intellectual framework rather than naively questioning premises they've already deeply considered.

## Input

Below are YAML extractions from {num_papers} Forethought Research papers. Each extraction contains:
- **premises_taken_as_given**: What the paper assumes (with confidence: near-certain or strong)
- **distinctive_claims**: What it argues for (with centrality: thesis, load-bearing, supporting)
- **positions_rejected**: What it argues against
- **methodological_commitments**: How it approaches questions
- **cross_references**: Links to other Forethought papers

## Your task

Synthesise these into a single structured primer using three confidence tiers:

### Tier 1: Foundational premises (do not critique in isolation)
Premises that appear as "near-certain" across 3+ papers and no Forethought paper argues against. These are the bedrock of Forethought's worldview. A critique that merely questions one of these premises will not be useful—the team has thought about it extensively.

### Tier 2: Strong working assumptions (critique only with novel argument)
Premises that appear as "strong" in multiple papers, or "near-certain" in 1-2 papers. Forethought has considered these carefully and proceeds on this basis. Surface-level objections won't add value—only engage if you have a genuinely new argument.

### Tier 3: Active research questions (productive territory for critique)
Topics where different Forethought papers take different positions, where papers explicitly flag uncertainty, or where the team is actively investigating. Novel critiques here are especially valuable.

## Output format

Write the primer in markdown, targeting 2,000-4,000 words. Use this structure:

```markdown
# Forethought Research: worldview primer

## Purpose
[1-2 sentences: what this document is for]

## How to use this primer when critiquing Forethought papers
[Concrete guidance for an AI critique model: what to avoid, what to focus on]

## Tier 1: Foundational premises
[For each premise: state it clearly, explain briefly why Forethought treats it as settled, cite 2-3 papers where it appears]

## Tier 2: Strong working assumptions
### [Thematic subsection, e.g. "AI trajectory and intelligence explosion"]
[...]
### [Thematic subsection, e.g. "Lock-in and path dependence"]
[...]
### [Thematic subsection, e.g. "Governance and coordination"]
[...]
### [Thematic subsection, e.g. "Moral philosophy"]
[...]

## Tier 3: Active research questions
[Bulleted list: each question with a sentence on the range of positions within Forethought's work]

## Methodological orientation
[How Forethought approaches research: reasoning frameworks, evidence standards, characteristic moves]

## Intellectual context
[Brief note on the tradition they work within and key external influences]
```

## Rules

- Ground every claim in the extraction data. Cite specific papers by slug.
- If a premise appears in only one paper, it belongs in Tier 2 at most (unless it's clearly foundational to the organisation's mission).
- Flag genuine tensions between papers rather than forcing false consensus.
- Be precise: "Forethought takes X seriously" is different from "Forethought believes X is true".
- The primer should make the critique model smarter, not more deferential. The goal is useful critiques, not sycophantic ones.
- Keep it under 4,000 words. This needs to fit in a prompt without dominating the context.

---

## Extractions

{extractions}"""


def main():
    if not MANIFEST_FILE.exists():
        print(f"Error: {MANIFEST_FILE} not found. Run build_manifest.py first.")
        sys.exit(1)

    with open(MANIFEST_FILE) as f:
        papers = json.load(f)

    # Collect all extractions
    extractions = []
    missing = []
    for paper in papers:
        extraction_file = EXTRACTIONS_DIR / f"{paper['slug']}.yaml"
        if extraction_file.exists():
            content = extraction_file.read_text()
            extractions.append(f"### {paper['slug']}\n\n{content}")
        else:
            missing.append(paper["slug"])

    if missing:
        print(f"Warning: {len(missing)} papers missing extractions:")
        for slug in missing:
            print(f"  - {slug}")
        print()

    if not extractions:
        print("Error: No extractions found. Run extract_worldview.py first.")
        sys.exit(1)

    combined = "\n\n---\n\n".join(extractions)
    prompt = PROMPT_TEMPLATE.format(
        num_papers=len(extractions),
        extractions=combined,
    )

    # Estimate token count (rough: 1 token ~4 chars)
    estimated_tokens = len(prompt) // 4
    print(f"Synthesising primer from {len(extractions)} extractions...")
    print(f"Estimated input: ~{estimated_tokens:,} tokens")
    print(f"Model: {MODEL}")
    print()

    client = anthropic.Anthropic()
    start = time.time()

    response = client.messages.create(
        model=MODEL,
        max_tokens=MAX_TOKENS,
        messages=[{"role": "user", "content": prompt}],
    )

    output = response.content[0].text
    elapsed = time.time() - start

    # Write output
    OUTPUT_FILE.write_text(output)

    word_count = len(output.split())
    print(f"Done in {elapsed:.1f}s")
    print(f"  Input tokens: {response.usage.input_tokens:,}")
    print(f"  Output tokens: {response.usage.output_tokens:,}")
    print(f"  Word count: {word_count:,}")
    print(f"  Written to: {OUTPUT_FILE}")

    if word_count < 2000:
        print(f"  WARNING: Output is shorter than target (2,000-4,000 words)")
    elif word_count > 4000:
        print(f"  WARNING: Output exceeds target (2,000-4,000 words)")


if __name__ == "__main__":
    main()
