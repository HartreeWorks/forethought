#!/usr/bin/env python3
"""
Clean up surgery critique files to standardize claim/attack-type formatting.

Transforms inline "Attack type:" patterns into structured multiline format:

**Load-bearing claim:**
{claim text}

**Attack type:**
{type}

{rest of critique}

Usage:
    python cleanup-surgery-format.py          # dry run (preview changes)
    python cleanup-surgery-format.py --apply  # write changes to files
"""

import re
import os
import glob
import sys

DRY_RUN = "--apply" not in sys.argv

BASE = os.path.dirname(os.path.abspath(__file__))


def find_target_files():
    """Find all surgery .txt files with inline 'Attack type:' that need reformatting."""
    files = set()
    for pat in [
        os.path.join(BASE, "**", "parsed", "surgery-*.txt"),
        os.path.join(BASE, "**", "parsed", "gpt-surgery-*.txt"),
    ]:
        files.update(glob.glob(pat, recursive=True))

    targets = []
    for f in sorted(files):
        # Skip Gemini-CB (already in clean multiline format)
        if "outputs-gemini-cb" in f:
            continue
        with open(f) as fh:
            content = fh.read().strip()
        if "Attack type:" not in content:
            continue
        # Skip if already in clean format ("**Attack type:**" on its own line)
        lines = content.split('\n')
        if any(l.strip() == "**Attack type:**" for l in lines):
            continue
        targets.append(f)
    return targets


def split_at_attack_type(text):
    """Split text into (before, type_value, body) around 'Attack type:' marker."""

    # Find the attack type marker with surrounding formatting
    pattern = r'\s*\(?\s*\*{0,2}\s*Attack type:\s*\*{0,2}\s*'
    match = re.search(pattern, text, re.IGNORECASE)
    if not match:
        return None, None, None

    before = text[:match.start()]
    rest = text[match.end():]

    # Extract the type value from rest.
    # Try patterns from most specific to most general:

    # Pattern 1a: **VALUE**). BODY  (Claude parens pattern, bold value)
    m = re.match(r'\*\*(.+?)\*\*\s*\)[\.\s]*(.+)', rest, re.DOTALL)
    if m:
        return before, m.group(1).strip().rstrip('.'), m.group(2).strip()

    # Pattern 1b: VALUE**). BODY  (value ends with closing bold + paren)
    m = re.match(r'(.+?)\*\*\s*\)\s*[\.\s]*(.+)', rest, re.DOTALL)
    if m:
        return before, m.group(1).strip(), m.group(2).strip()

    # Pattern 2: VALUE.** BODY  (GPT bold-section pattern)
    # Match value (possibly with parens) up to ".**"
    m = re.match(r'(.+?)\.\*\*\s*(.+)', rest, re.DOTALL)
    if m and m.group(1).count('(') == m.group(1).count(')'):
        return before, m.group(1).strip().strip('*').strip(), m.group(2).strip()

    # Pattern 3: VALUE**. BODY
    m = re.match(r'(.+?)\*\*\.\s*(.+)', rest, re.DOTALL)
    if m:
        return before, m.group(1).strip().strip('*').strip().rstrip('.'), m.group(2).strip()

    # Pattern 4: VALUE. BODY  (plain sentence boundary)
    # Match up to ". " followed by ** or capital letter, respecting balanced parens
    m = re.match(r'(.+?)\.\s+(\*\*|[A-Z])', rest, re.DOTALL)
    if m:
        candidate = m.group(1)
        if candidate.count('(') == candidate.count(')'):
            return before, candidate.strip().strip('*').strip(), rest[m.start(2):].strip()
        # Unbalanced parens - find closing paren first
        m2 = re.match(r'(.+?\))\.\s+(.+)', rest, re.DOTALL)
        if m2:
            return before, m2.group(1).strip().strip('*').strip(), m2.group(2).strip()

    # Fallback: take first sentence as type
    parts = rest.split('. ', 1)
    if len(parts) == 2:
        return before, parts[0].strip().strip('*').strip(), parts[1].strip()

    return before, rest.strip().strip('*').strip(), ""


def extract_claim(before_text):
    """Extract the claim from the text before 'Attack type:'."""
    text = before_text.strip()

    # Try known prefix patterns (most specific first)

    # "The load-bearing claim being attacked is:"
    m = re.match(r'^The load-bearing claim being attacked is:\s*', text, re.IGNORECASE)
    if m:
        return clean_claim_text(text[m.end():])

    # "**Node attacked:**"
    m = re.match(r'^\*\*Node attacked:\*\*\s*', text)
    if m:
        return clean_claim_text(text[m.end():])

    # "**Load-bearing claim attacked:" or "**Load-bearing claim attacked**:"
    m = re.match(r'^\*\*Load-bearing claim attacked\*{0,2}:\s*', text)
    if m:
        return clean_claim_text(text[m.end():])

    # "The paper's {ordinal} load-bearing claim is that"
    m = re.match(
        r"^The paper'?s?\s+(?:first|second|third|fourth|fifth|sixth|seventh|eighth|ninth|tenth|\d+(?:st|nd|rd|th))\s+load-bearing claim is that\s+",
        text, re.IGNORECASE,
    )
    if m:
        return clean_claim_text(text[m.end():])

    # Generic "...load-bearing claim that ..."
    m = re.search(r'load-bearing claim (?:that|is that)\s+', text, re.IGNORECASE)
    if m:
        return clean_claim_text(text[m.end():])

    # No known prefix - use the whole before-text as the claim
    return clean_claim_text(text)


def clean_claim_text(text):
    """Clean up a claim text string - remove wrapping bold, trailing artifacts."""
    text = text.strip()

    # Handle ** markers based on count and position:
    # - If fully wrapped (**text**), strip both
    # - If only trailing ** (remnant from prefix strip), strip it
    # - If internal bold (**word** in middle), leave as-is
    num_stars = len(text.split('**')) - 1
    has_trailing = bool(re.search(r'\*\*[.\s]*$', text))

    if num_stars == 2 and text.startswith('**') and has_trailing:
        # Full wrapping: **claim text** or **claim text**.
        text = text[2:]
        text = re.sub(r'\*\*[.\s]*$', '', text)
    elif num_stars % 2 == 1 and has_trailing:
        # Odd count with trailing ** = remnant from bold prefix
        text = re.sub(r'\*\*[.\s]*$', '', text)
    # Even count (2+) without full wrapping = internal bold formatting, leave as-is

    # Remove trailing ( left from formatting
    text = text.strip().rstrip('(').strip()
    # Remove trailing period
    text = text.rstrip('.')
    # Clean up double spaces
    text = re.sub(r'  +', ' ', text)
    return text.strip()


def format_output(claim, attack_type, body):
    """Format the cleaned components into the target format."""
    # Clean any remaining bold markers and trailing periods from attack_type
    attack_type = re.sub(r'\*+', '', attack_type).strip().rstrip('.')
    # Strip trailing ) only if unbalanced (remnant from formatting, not part of type name)
    while attack_type.endswith(')') and attack_type.count(')') > attack_type.count('('):
        attack_type = attack_type[:-1].strip()
    return f"**Load-bearing claim:**\n{claim}\n\n**Attack type:**\n{attack_type}\n\n{body}"


def process_file(filepath):
    """Process a single file. Returns (new_content, original_content) or (None, original)."""
    with open(filepath) as f:
        content = f.read().strip()

    before, attack_type, body = split_at_attack_type(content)
    if before is None:
        return None, content

    claim = extract_claim(before)
    if not claim or not attack_type:
        return None, content

    return format_output(claim, attack_type, body), content


def main():
    targets = find_target_files()
    print(f"Found {len(targets)} files to process")
    print(f"Mode: {'DRY RUN' if DRY_RUN else 'APPLYING CHANGES'}\n")

    success = 0
    failed = []

    for filepath in targets:
        result, original = process_file(filepath)
        relpath = os.path.relpath(filepath, BASE)

        if result is None:
            failed.append(relpath)
            print(f"FAILED: {relpath}")
            with open(filepath) as f:
                print(f"  First 200 chars: {f.read()[:200]}")
            print()
            continue

        success += 1

        if DRY_RUN:
            print(f"--- {relpath} ---")
            # Show first 300 chars of reformatted result
            preview = result[:300]
            if len(result) > 300:
                preview += "..."
            print(preview)
            print()
        else:
            with open(filepath, 'w') as f:
                f.write(result + '\n')
            print(f"OK: {relpath}")

    print(f"\n{'DRY RUN - ' if DRY_RUN else ''}Processed {success}/{len(targets)} files successfully")
    if failed:
        print(f"Failed: {len(failed)} files:")
        for f in failed:
            print(f"  {f}")


if __name__ == "__main__":
    main()
