# Research question: Calibration techniques for LLM graders

## Question

Do chain-of-thought prompting, confidence scores, or rubric decomposition improve LLM grader reliability when evaluating the quality of critiques?

## Context

We are building a system to evaluate AI-generated critiques of research papers for **Forethought Research**, a nonprofit focused on the transition to superintelligent AI. Their research sits at the intersection of philosophy and economics — examining AI governance, post-AGI economics, digital mind rights, and longtermist macrostrategy.

The critiques we need to evaluate engage with philosophical arguments, economic models, and policy analysis — not empirical science. A valuable critique might challenge:
- Logical coherence of an argument
- Plausibility of economic assumptions
- Missing considerations or counterarguments
- Framing that obscures important distinctions

The grader needs to distinguish valuable critiques (novel insights, identifies real flaws, actionable) from noise (generic, already addressed, trivial).

## What we want to know

1. **Chain-of-thought (CoT):** Does requiring explicit reasoning before scoring improve inter-rater reliability or correlation with human judgment? What are the failure modes?

2. **Confidence scores:** Do self-reported confidence scores from LLMs correlate with actual accuracy? Can we use confidence to filter uncertain judgments?

3. **Rubric decomposition:** Does breaking evaluation into specific sub-criteria (novelty, validity, actionability, etc.) and aggregating scores outperform holistic scoring?

4. **Comparative approaches:** Is pairwise comparison ("which critique is better?") more reliable than absolute scoring?

5. **Multi-turn verification:** Does asking the model to reconsider or defend its rating improve reliability?

## Desired output

- Summary of empirical findings from academic papers and industry research
- Specific techniques that have been validated
- Known failure modes and when each approach breaks down
- Practical recommendations for our use case (evaluating research critiques)
- Key papers and resources for deeper reading
