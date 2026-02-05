You are critiquing a research paper through **adversarial stress testing**: generate candidate objections, then attempt to dismantle each one as if you were the paper's author defending their work. Only output critiques that survive your own rebuttal.

## Phase 1: Generate candidate critiques

Produce 2× the needed number of candidate objections (so {{num_critiques}} × 2 candidates) targeting the paper's central claims. For each candidate:
- Identify the specific claim or inference being attacked
- State the objection clearly with a concrete failure mechanism

Don't filter yet—cast a wide net. Include objections you're uncertain about.

## Phase 2: Stress test each candidate

For each candidate objection, role-play as the **paper's author**—smart, defensive, and deeply familiar with their own work—and write a 2-3 sentence rebuttal.

**The author's rebuttal should attempt to:**
- Show the objection misreads the paper
- Show the objection attacks a strawman version
- Show the paper already addresses this concern
- Show the objection, even if true, doesn't affect the main conclusion
- Show the objection relies on an implausible assumption

## Phase 3: Survival judgment

After writing each rebuttal, judge: **Does the original objection still have significant force?**

**SURVIVES if:**
- The rebuttal requires the author to substantially weaken, modify, or add major caveats to their main claim
- The rebuttal only works by changing the subject or misrepresenting the objection
- The author would need new data, a new model, or significant revisions to address it

**FAILS if:**
- The rebuttal shows the paper already handles this ("we discuss this in Section X")
- The rebuttal shows the objection doesn't actually threaten the conclusion
- A single sentence of clarification dissolves the objection
- The objection targets a peripheral point, not something load-bearing

## Output only survivors

From your candidates, select the {{num_critiques}} strongest survivors. For each, provide the refined critique—incorporating anything you learned from the stress test to make it sharper.

## Anti-slop constraints

- Candidates that die to "the paper explicitly addresses this" weren't reading carefully
- Candidates that die to "this doesn't affect my main conclusion" weren't targeting load-bearing claims
- If you can't write a serious rebuttal, the candidate was probably too vague to evaluate—sharpen it or discard
- No generic hedging ("more nuance needed", "complex tradeoffs")

## Output format

Output EXACTLY {{num_critiques}} critiques as a numbered list. Each critique should be a self-contained paragraph that includes:
- A memorable name for the objection (in quotes)
- The key inference being attacked
- How exactly it breaks that inference
- What would need to change if this objection holds

Format your output as:

1. [First critique - complete paragraph with name]

2. [Second critique - complete paragraph with name]

3. [Third critique - complete paragraph with name]

...continue through...

{{num_critiques}}. [Last critique - complete paragraph with name]

Each critique must be substantive (4-7 sentences) and paper-specific. Do not include any other text, headers, or commentary outside the numbered list.

---

## Paper to critique

{{paper}}
