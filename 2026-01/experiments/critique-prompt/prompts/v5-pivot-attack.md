# Prompt: PIVOT ATTACK

You are critiquing a research paper by identifying its **decision pivots**—the minimal claims that must be true for the argument to work—then attacking those pivots specifically.

## Phase 1: Extract the pivots

Before critiquing, identify 3-5 **pivot points**: the smallest set of claims or inferences such that *if any one is false, the paper's main conclusion fails or is severely weakened*.

For each pivot:
- State it in the paper's own terms (quote or close paraphrase)
- Explain in one sentence why it's load-bearing

**Pivot quality check**: For each pivot, ask: "If I grant everything else but deny this, does the conclusion still follow?" If yes, it's not a true pivot—find a more essential claim.

## Phase 2: Attack the pivots

Generate critiques that attack the identified pivots. Each critique must:
- Name which pivot it attacks
- Explain why that pivot might be false, unsupported, or breaks under pressure
- Show concretely how the paper's main conclusion fails if the pivot fails

**Attack approaches** (use whichever fits):
- The pivot relies on an unstated assumption that doesn't hold
- The pivot conflates two things that come apart under scrutiny
- The pivot holds locally but not at the scale/scope the paper needs
- The evidence for the pivot equally supports an alternative interpretation
- The pivot is self-undermining when examined closely
- Strategic actors would adapt in ways that defeat the pivot

## Validation test

Before outputting each critique, ask: "Could the author grant this objection but still reach essentially the same conclusion?"
- If **yes**: The critique doesn't attack a true pivot. Discard and try again.
- If **no**: The critique is load-bearing. Keep it.

## Anti-slop constraints

- Every critique must reference a specific pivot
- "Needs more evidence" is only valid if you specify *what* evidence would distinguish the pivot from alternatives
- If your critique applies to 30%+ of papers in the field with noun swaps, it doesn't attack a paper-specific pivot—find the actual failure point
- Hard bans: "scope unclear", "definitions needed", "assumptions may not hold" (unless tied to a specific pivot and shown to change the conclusion)
