#!/usr/bin/env python3
"""Scrape all Forethought Research pages to markdown using Firecrawl API."""

import json
import os
import re
import time
import urllib.request
import urllib.error

API_KEY = "fc-f00fdc414fe84f069b597a2a5e6f75bc"
API_URL = "https://api.firecrawl.dev/v1/scrape"
INDEX_FILE = os.path.join(os.path.dirname(__file__), "forethought-research-index.json")
OUTPUT_DIR = os.path.join(os.path.dirname(__file__), "scraped")

# HTML elements to exclude before markdown conversion
EXCLUDE_TAGS = [
    "nav",
    "[data-component=PodcastSection]",
    "[data-component=SmallGraphic]",
    "[data-component=TableOfContents]",
    "[data-component=SeriesSidebarNavigation]",
    "[data-component=ArticleButtons]",
    "[data-component=MobileNav]",
    "[data-component=ArticleMetaData]",
    "section.footnotes",
    "footer",
]

os.makedirs(OUTPUT_DIR, exist_ok=True)


def slug_from_url(url: str) -> str:
    return url.rstrip("/").split("/")[-1]


def scrape_url(url: str) -> dict:
    payload = json.dumps({
        "url": url,
        "formats": ["markdown"],
        "onlyMainContent": True,
        "excludeTags": EXCLUDE_TAGS,
    }).encode()

    req = urllib.request.Request(
        API_URL,
        data=payload,
        headers={
            "Authorization": f"Bearer {API_KEY}",
            "Content-Type": "application/json",
        },
    )

    with urllib.request.urlopen(req, timeout=60) as resp:
        return json.loads(resp.read())


def clean_markdown(md: str) -> str:
    """Post-process markdown to remove remaining artefacts."""

    # Remove "Link to heading" anchor text
    md = re.sub(r'\s*\[Link to heading\]\([^)]*\)', '', md)

    # Remove "## Image" headings (artefact from figure containers)
    md = re.sub(r'^## Image\s*$', '', md, flags=re.MULTILINE)

    # Remove decorative SVG images
    md = re.sub(r'!\[[^\]]*\]\(https://www\.forethought\.org/graphics/(?:svg|sm)/[^)]+\)', '', md)

    # Remove nav bar line at top (Home/Research/About/etc links)
    md = re.sub(
        r'^\[?\*?\*?Home\*?\*?\]?\([^)]*\)\s*'
        r'(\[?\*?\*?\w+\*?\*?\]?\([^)]*\)\s*)+',
        '', md, count=1
    )

    # Remove podcast icon images (Spotify, Apple, YouTube, etc)
    md = re.sub(r'\[!\[(?:Spotify|Apple Podcasts?|YouTube|Podcast Addict|Pocket Casts?|Overcast|RSS)\][^\]]*\]\([^)]*\)', '', md)

    # Remove podcast download links
    md = re.sub(r'\[!\[\]\(https://pinecast\.com/static/img/download\.svg\)\]\([^)]*\)', '', md)

    # Remove "## Search" section and everything after it at the bottom
    md = re.sub(r'^## Search\s*\n.*', '', md, flags=re.MULTILINE | re.DOTALL)

    # Remove "## Citations" sections (usually just the heading with no content)
    md = re.sub(r'^## Citations\s*$', '', md, flags=re.MULTILINE)

    # Remove standalone "Cite" / "PDF" / "Contact" lines
    md = re.sub(r'^(?:Cite|PDF|Contact)\s*$', '', md, flags=re.MULTILINE)

    # Remove "### Footnotes" heading (content already stripped)
    md = re.sub(r'^###? Footnotes?\s*$', '', md, flags=re.MULTILINE)

    # Remove audio player lines (bold title with duration)
    md = re.sub(r'^\*\*[^*]+\*\*\s*·\s*\d{2}:\d{2}:\d{2}\s*$', '', md, flags=re.MULTILINE)

    # Remove series nav (Previous/Next article links at top)
    md = re.sub(r'^\[Previous.*?\]\([^)]*\)\s*\[Next\]\([^)]*\)\s*$', '', md, flags=re.MULTILINE)
    md = re.sub(r'^\[Previous.*?\]\([^)]*\)\s*$', '', md, flags=re.MULTILINE)
    md = re.sub(r'^\[Next\]\([^)]*\)\s*$', '', md, flags=re.MULTILINE)

    # Remove series breadcrumb (e.g. '[Better Futures](...)\nPart 2 of6')
    md = re.sub(r'^\[[^\]]+\]\(https://www\.forethought\.org/research/[^)]+\)\s*\nPart \d+ of\s*\d+\s*\n', '', md, flags=re.MULTILINE)

    # Collapse 3+ consecutive blank lines to 2
    md = re.sub(r'\n{4,}', '\n\n\n', md)

    # Strip leading/trailing whitespace
    md = md.strip()

    return md


def main():
    with open(INDEX_FILE) as f:
        papers = json.load(f)

    total = len(papers)
    print(f"Scraping {total} papers...")

    results = {"success": [], "failed": [], "skipped": []}

    for i, paper in enumerate(papers, 1):
        slug = slug_from_url(paper["url"])
        out_path = os.path.join(OUTPUT_DIR, f"{slug}.md")

        if os.path.exists(out_path) and os.path.getsize(out_path) > 100:
            print(f"[{i}/{total}] SKIP (exists): {slug}")
            results["skipped"].append(slug)
            continue

        print(f"[{i}/{total}] Scraping: {paper['title'][:60]}...")

        try:
            resp = scrape_url(paper["url"])
            markdown = resp.get("data", {}).get("markdown", "")

            if not markdown:
                print(f"  WARNING: Empty markdown for {slug}")
                results["failed"].append({"slug": slug, "error": "empty markdown"})
                continue

            markdown = clean_markdown(markdown)

            # Prepend title as YAML frontmatter
            escaped_title = paper['title'].replace('"', '\\"')
            frontmatter = f"---\ntitle: \"{escaped_title}\"\nurl: {paper['url']}\n---\n\n"
            with open(out_path, "w") as f:
                f.write(frontmatter + markdown)

            size_kb = os.path.getsize(out_path) / 1024
            print(f"  OK ({size_kb:.1f} KB)")
            results["success"].append(slug)

        except urllib.error.HTTPError as e:
            body = e.read().decode() if e.fp else ""
            print(f"  FAILED: HTTP {e.code} - {body[:200]}")
            results["failed"].append({"slug": slug, "error": f"HTTP {e.code}"})

            if e.code == 429:
                print("  Rate limited, waiting 30s...")
                time.sleep(30)
        except Exception as e:
            print(f"  FAILED: {e}")
            results["failed"].append({"slug": slug, "error": str(e)})

        # Small delay between requests
        time.sleep(1)

    print(f"\nDone! {len(results['success'])} scraped, {len(results['skipped'])} skipped, {len(results['failed'])} failed.")
    if results["failed"]:
        print("Failed:")
        for item in results["failed"]:
            print(f"  - {item['slug']}: {item['error']}")


if __name__ == "__main__":
    main()
