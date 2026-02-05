#!/usr/bin/env python3
"""
Chain-ACORN experiment: 6-step paper critique chain with ACORN grading at steps 2 and 6.

Uses GPT-5.2 Pro for all steps. Reads prompts and paper from local files.
Outputs structured JSON at each step for reproducibility.
"""

import json
import os
import re
import sys
import time
from pathlib import Path

from dotenv import load_dotenv
from openai import OpenAI

# ---------------------------------------------------------------------------
# Config
# ---------------------------------------------------------------------------

load_dotenv(Path(__file__).resolve().parent / ".env")

MODEL = "gpt-5.2-pro"
BASE_DIR = Path(__file__).resolve().parent
PROMPTS_DIR = BASE_DIR / "prompts"
PAPER_PATH = BASE_DIR / "paper" / "no-easy-eutopia.md"
OUTPUT_DIR = BASE_DIR / "outputs"

# Create output dirs
for subdir in ["step2-grades", "step6-grades", "step6-final-grades"]:
    (OUTPUT_DIR / subdir).mkdir(parents=True, exist_ok=True)


def load_prompt(name: str) -> str:
    return (PROMPTS_DIR / name).read_text()


def load_paper() -> str:
    return PAPER_PATH.read_text()


# ---------------------------------------------------------------------------
# OpenAI helpers
# ---------------------------------------------------------------------------

client = OpenAI()  # uses OPENAI_API_KEY env var


def chat(system: str, user: str, *, max_tokens: int = 16000) -> str:
    """Send a request via the OpenAI responses API and return the output text."""
    print(f"  Calling {MODEL}...", end=" ", flush=True)
    t0 = time.time()
    resp = client.responses.create(
        model=MODEL,
        instructions=system,
        input=user,
        max_output_tokens=max_tokens,
    )
    elapsed = time.time() - t0
    print(f"done ({elapsed:.1f}s)")
    return resp.output_text


def parse_json(text: str):
    """Extract JSON from model output, stripping code fences if present."""
    # Strip markdown code fences
    text = text.strip()
    if text.startswith("```"):
        text = re.sub(r"^```(?:json)?\s*\n?", "", text)
        text = re.sub(r"\n?```\s*$", "", text)
    return json.loads(text)


def save(path: Path, data):
    """Save data as formatted JSON."""
    path.write_text(json.dumps(data, indent=2, ensure_ascii=False))
    print(f"  Saved {path}")


# ---------------------------------------------------------------------------
# Step 1: Brainstorm 20 critiques
# ---------------------------------------------------------------------------

def step1_brainstorm(paper: str) -> list[dict]:
    print("\n=== Step 1: Brainstorm 20 critiques ===")

    prompt_template = load_prompt("01-brainstorm.md")
    prompt = prompt_template.replace("{{num_critiques}}", "20").replace("{{paper}}", paper)

    raw = chat(
        system="You are a sharp philosophical critic.",
        user=prompt,
    )

    # Parse numbered list into objects
    critiques = []
    # Split on numbered items: "1. ", "2. ", etc.
    parts = re.split(r"\n\d+\.\s+", "\n" + raw)
    for i, part in enumerate(parts):
        part = part.strip()
        if not part:
            continue
        critiques.append({
            "id": f"c{i:02d}",
            "text": part,
        })

    print(f"  Parsed {len(critiques)} critiques")
    save(OUTPUT_DIR / "step1-brainstorm.json", critiques)
    return critiques


# ---------------------------------------------------------------------------
# Step 2: ACORN-grade each brainstorm critique, select top 5
# ---------------------------------------------------------------------------

def acorn_grade(critique_text: str, paper: str) -> dict:
    """Grade a single critique using the ACORN rubric."""
    grader_template = load_prompt("02-acorn-grader.txt")
    prompt = grader_template.replace("{{position}}", paper).replace("{{critique}}", critique_text)

    raw = chat(
        system="You are an expert evaluator. Return ONLY valid JSON.",
        user=prompt,
        max_tokens=2000,
    )
    return parse_json(raw)


def step2_grade_and_select(critiques: list[dict], paper: str) -> list[dict]:
    print("\n=== Step 2: ACORN grade brainstorm critiques, select top 5 ===")

    grades = []
    for c in critiques:
        print(f"  Grading {c['id']}...")
        grade = acorn_grade(c["text"], paper)
        grade["id"] = c["id"]
        grade["text"] = c["text"]
        grades.append(grade)
        save(OUTPUT_DIR / "step2-grades" / f"{c['id']}.json", grade)

    # Rank by overall score, take top 5
    grades.sort(key=lambda g: g.get("overall", 0), reverse=True)
    top5 = grades[:5]

    top5_summary = []
    for g in top5:
        top5_summary.append({
            "id": g["id"],
            "text": g["text"],
            "overall": g["overall"],
            "reasoning": g.get("reasoning", ""),
        })

    save(OUTPUT_DIR / "step2-top5.json", top5_summary)
    print(f"  Top 5 IDs: {[g['id'] for g in top5]}")
    print(f"  Top 5 scores: {[g['overall'] for g in top5]}")
    return top5_summary


# ---------------------------------------------------------------------------
# Step 3: Develop top 5 (300-400 words each)
# ---------------------------------------------------------------------------

def step3_develop(top5: list[dict], paper: str) -> list[dict]:
    print("\n=== Step 3: Develop top 5 critiques ===")

    prompt_template = load_prompt("03-develop.md")

    # Format top5 for the prompt: the template expects {{json top5}}
    # The develop prompt expects critique objects; we'll format as the chain expects
    top5_for_prompt = json.dumps(
        [{"id": c["id"], "explanation": c["text"]} for c in top5],
        indent=2, ensure_ascii=False,
    )

    prompt = prompt_template.replace("{{json top5}}", top5_for_prompt).replace("{{paper}}", paper)

    raw = chat(
        system="You are a philosophy journal referee.",
        user=prompt,
    )

    developed = parse_json(raw)
    save(OUTPUT_DIR / "step3-developed.json", developed)
    return developed


# ---------------------------------------------------------------------------
# Step 4: Critique the critiques
# ---------------------------------------------------------------------------

def step4_counter(developed: list[dict], paper: str) -> list[dict]:
    print("\n=== Step 4: Counter-arguments ===")

    prompt_template = load_prompt("04-counter.md")
    prompt = prompt_template.replace(
        "{{json developed}}", json.dumps(developed, indent=2, ensure_ascii=False)
    ).replace("{{paper}}", paper)

    raw = chat(
        system="You are a charitable but rigorous philosophy professor defending the paper.",
        user=prompt,
    )

    counters = parse_json(raw)
    save(OUTPUT_DIR / "step4-counters.json", counters)
    return counters


# ---------------------------------------------------------------------------
# Step 5: Revise critiques
# ---------------------------------------------------------------------------

def step5_revise(developed: list[dict], counters: list[dict], paper: str) -> list[dict]:
    print("\n=== Step 5: Revise critiques ===")

    prompt_template = load_prompt("05-revise.md")
    prompt = prompt_template.replace(
        "{{json developed}}", json.dumps(developed, indent=2, ensure_ascii=False)
    ).replace(
        "{{json counters}}", json.dumps(counters, indent=2, ensure_ascii=False)
    ).replace("{{paper}}", paper)

    raw = chat(
        system="You are a sharp philosophical critic revising your work after considering objections.",
        user=prompt,
    )

    revised = parse_json(raw)
    save(OUTPUT_DIR / "step5-revised.json", revised)
    return revised


# ---------------------------------------------------------------------------
# Step 6: ACORN-grade revised, select top 3, expand
# ---------------------------------------------------------------------------

def step6_grade_select_expand(revised: list[dict], paper: str) -> list[dict]:
    print("\n=== Step 6: ACORN grade revised critiques, select top 3, expand ===")

    # Grade each revised critique
    grades = []
    for c in revised:
        critique_text = c.get("revised", c.get("text", ""))
        cid = c.get("id", "unknown")
        print(f"  Grading revised {cid}...")
        grade = acorn_grade(critique_text, paper)
        grade["id"] = cid
        grade["revised_text"] = critique_text
        grades.append(grade)
        # Save with sequential numbering for the 5 revised critiques
        idx = revised.index(c) + 1
        save(OUTPUT_DIR / "step6-grades" / f"c{idx:02d}.json", grade)

    # Select top 3
    grades.sort(key=lambda g: g.get("overall", 0), reverse=True)
    top3 = grades[:3]

    top3_summary = []
    for g in top3:
        top3_summary.append({
            "id": g["id"],
            "revised": g["revised_text"],
            "overall": g["overall"],
            "reasoning": g.get("reasoning", ""),
        })

    save(OUTPUT_DIR / "step6-top3.json", top3_summary)
    print(f"  Top 3 IDs: {[g['id'] for g in top3]}")
    print(f"  Top 3 scores: {[g['overall'] for g in top3]}")

    # Now expand using modified 06-final prompt
    prompt_template = load_prompt("06-final.md")
    top3_for_prompt = json.dumps(
        [{"id": g["id"], "revised": g["revised_text"]} for g in top3],
        indent=2, ensure_ascii=False,
    )
    prompt = prompt_template.replace("{{json top3}}", top3_for_prompt).replace("{{paper}}", paper)

    raw = chat(
        system="You are a world-class philosophy journal referee writing final expanded critiques.",
        user=prompt,
        max_tokens=16000,
    )

    final = parse_json(raw)
    save(OUTPUT_DIR / "step6-final.json", final)

    # Grade the final expanded critiques with ACORN
    print("\n  Grading final expanded critiques...")
    for i, f in enumerate(final):
        critique_text = f.get("deep", "")
        fid = f.get("id", f"final_{i}")
        print(f"  Grading final {fid}...")
        grade = acorn_grade(critique_text, paper)
        grade["id"] = fid
        save(OUTPUT_DIR / "step6-final-grades" / f"c{i+1:02d}.json", grade)

    return final


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------

def main():
    print("Chain-ACORN Experiment")
    print("=" * 60)

    paper = load_paper()
    print(f"Paper loaded: {len(paper)} chars")

    # Check for resumption: skip completed steps
    resume_from = 1
    critiques = top5 = developed = counters = revised = None

    if (OUTPUT_DIR / "step1-brainstorm.json").exists():
        critiques = json.loads((OUTPUT_DIR / "step1-brainstorm.json").read_text())
        print(f"Resuming: step 1 already complete ({len(critiques)} critiques)")
        resume_from = 2

    if (OUTPUT_DIR / "step2-top5.json").exists() and resume_from >= 2:
        top5 = json.loads((OUTPUT_DIR / "step2-top5.json").read_text())
        print(f"Resuming: step 2 already complete (top 5: {[c['id'] for c in top5]})")
        resume_from = 3

    if (OUTPUT_DIR / "step3-developed.json").exists() and resume_from >= 3:
        developed = json.loads((OUTPUT_DIR / "step3-developed.json").read_text())
        print(f"Resuming: step 3 already complete ({len(developed)} developed)")
        resume_from = 4

    if (OUTPUT_DIR / "step4-counters.json").exists() and resume_from >= 4:
        counters = json.loads((OUTPUT_DIR / "step4-counters.json").read_text())
        print(f"Resuming: step 4 already complete ({len(counters)} counters)")
        resume_from = 5

    if (OUTPUT_DIR / "step5-revised.json").exists() and resume_from >= 5:
        revised = json.loads((OUTPUT_DIR / "step5-revised.json").read_text())
        print(f"Resuming: step 5 already complete ({len(revised)} revised)")
        resume_from = 6

    if (OUTPUT_DIR / "step6-final.json").exists() and resume_from >= 6:
        print("All steps already complete. Delete outputs/ to re-run.")
        return

    # Run steps
    if resume_from <= 1:
        critiques = step1_brainstorm(paper)

    if resume_from <= 2:
        top5 = step2_grade_and_select(critiques, paper)

    if resume_from <= 3:
        developed = step3_develop(top5, paper)

    if resume_from <= 4:
        counters = step4_counter(developed, paper)

    if resume_from <= 5:
        revised = step5_revise(developed, counters, paper)

    if resume_from <= 6:
        step6_grade_select_expand(revised, paper)

    print("\n" + "=" * 60)
    print("Experiment complete! Outputs in:", OUTPUT_DIR)


if __name__ == "__main__":
    main()
