# Research question: Multi-model consensus for LLM evaluation

## Question

Does multi-model voting, averaging, or ensemble approaches improve correlation with human judgment when evaluating research critiques?

## Context

We are building a system to evaluate AI-generated critiques of research papers for **Forethought Research**, a nonprofit focused on the transition to superintelligent AI. Their research sits at the intersection of philosophy and economics — examining AI governance, post-AGI economics, digital mind rights, and longtermist macrostrategy.

The critiques we need to evaluate engage with philosophical arguments, economic models, and policy analysis — not empirical science. A valuable critique might challenge:
- Logical coherence of an argument
- Plausibility of economic assumptions
- Missing considerations or counterarguments
- Framing that obscures important distinctions

The grader needs to distinguish valuable critiques (novel insights, identifies real flaws, actionable) from noise (generic, already addressed, trivial).

## What we want to know

1. **Multi-model voting:** Does having multiple different LLMs evaluate the same critique and taking majority vote improve reliability vs. single-model evaluation?

2. **Score averaging:** Does averaging scores across models reduce noise and improve correlation with human judgment?

3. **Ensemble approaches:** Are there more sophisticated ensemble methods (e.g., weighted voting, stacking, Bayesian aggregation) that outperform simple voting/averaging?

4. **Model diversity:** Does diversity of model families matter? (e.g., GPT-4 + Claude + Gemini vs. multiple GPT-4 runs)

5. **Cost-benefit:** What's the accuracy gain vs. computational cost multiplier?

6. **Failure modes:** When do ensemble approaches fail or make things worse?

## Desired output

- Summary of empirical findings from academic papers and industry research
- Specific ensemble techniques that have been validated for LLM-as-a-Judge
- Quantitative comparisons (e.g., "ensemble improved agreement from X% to Y%")
- Known failure modes and when ensemble approaches break down
- Practical recommendations for our use case (evaluating research critiques)
- Key papers and resources for deeper reading
