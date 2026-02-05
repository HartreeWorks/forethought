# Research question: Multi-persona prompting for critique generation

## Question

Does prompting an LLM to adopt multiple personas (e.g., "economist", "philosopher", "skeptic") when generating critiques improve the quality or diversity of outputs?

## Context

We are building a system to evaluate AI-generated critiques of research papers for **Forethought Research**, a nonprofit focused on the transition to superintelligent AI. Their research sits at the intersection of philosophy and economics — examining AI governance, post-AGI economics, digital mind rights, and longtermist macrostrategy.

Before we can grade critiques, we need to generate them. One promising approach is multi-persona prompting — having the LLM adopt different expert perspectives when critiquing a research paper.

## What we want to know

1. **Persona diversity:** Does asking an LLM to critique from multiple perspectives (philosopher, economist, policy analyst, skeptic) produce more diverse and valuable critiques than generic prompting?

2. **Quality improvement:** Is there evidence that persona prompting improves critique quality (not just diversity)?

3. **Persona specification:** How detailed should persona descriptions be? Does "think like an economist" work as well as detailed expert backstories?

4. **Multi-agent debate:** Does having "personas" debate or respond to each other improve output quality?

5. **Failure modes:** When does persona prompting produce worse outputs? (e.g., stereotyped responses, persona confusion, loss of coherence)

6. **Evaluation considerations:** How should we evaluate persona-generated critiques differently, if at all?

## Desired output

- Summary of empirical findings on persona prompting from academic papers and industry research
- Specific techniques that have been validated
- Evidence for quality/diversity improvements (quantitative if available)
- Known failure modes and limitations
- Practical recommendations for our use case (generating diverse research critiques)
- Key papers and resources for deeper reading
