#!/usr/bin/env python3
"""Build a manifest of all Forethought Research papers with metadata."""

import json
import os
import re
from pathlib import Path

PAPERS_DIR = Path(__file__).parent / "papers-and-blog-posts"
OUTPUT_FILE = Path(__file__).parent / "manifest.json"


def parse_frontmatter(text: str) -> dict:
    """Extract YAML frontmatter fields (title, url) from markdown."""
    match = re.match(r"^---\n(.*?)\n---", text, re.DOTALL)
    if not match:
        return {}
    fm = {}
    for line in match.group(1).splitlines():
        if ":" in line:
            key, _, value = line.partition(":")
            fm[key.strip()] = value.strip().strip('"')
    return fm


def main():
    papers = []
    for path in sorted(PAPERS_DIR.glob("*.md")):
        text = path.read_text()
        fm = parse_frontmatter(text)
        slug = path.stem
        papers.append({
            "slug": slug,
            "title": fm.get("title", slug),
            "url": fm.get("url", ""),
            "path": str(path.relative_to(Path(__file__).parent)),
            "size_bytes": path.stat().st_size,
        })

    with open(OUTPUT_FILE, "w") as f:
        json.dump(papers, f, indent=2)

    print(f"Wrote {len(papers)} papers to {OUTPUT_FILE}")


if __name__ == "__main__":
    main()
