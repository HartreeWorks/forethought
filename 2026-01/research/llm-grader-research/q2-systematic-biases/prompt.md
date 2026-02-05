# Research question: Systematic biases in LLM evaluation

## Question

What are the known systematic biases and failure modes where LLMs over-rate or under-rate certain types of critiques?

## Context

We are building a system to evaluate AI-generated critiques of research papers for **Forethought Research**, a nonprofit focused on the transition to superintelligent AI. Their research sits at the intersection of philosophy and economics — examining AI governance, post-AGI economics, digital mind rights, and longtermist macrostrategy.

The critiques we need to evaluate engage with philosophical arguments, economic models, and policy analysis — not empirical science. This domain context matters because:
- Philosophical critiques may be harder to evaluate than empirical claims
- Economic arguments often rest on contested assumptions
- Arguments about AI futures involve significant uncertainty
- The research community has specific norms about what constitutes valid criticism

We need to understand where LLM graders systematically fail so we can either correct for these biases or avoid relying on the grader in those cases.

## What we want to know

1. **Position bias:** Do LLMs favour critiques presented first/last in a list?

2. **Length bias:** Do LLMs favour longer, more verbose critiques over concise ones?

3. **Style bias:** Do LLMs favour critiques with certain rhetorical styles (formal academic language, confident assertions, hedged claims)?

4. **Self-preference:** Do LLMs rate their own outputs higher than equivalent human-written content?

5. **Sycophancy:** Do LLMs tend to agree with framing provided in the prompt or favour critiques that align with expected views?

6. **Domain blindness:** Are there types of valid critiques (methodological, empirical, philosophical) that LLMs systematically undervalue?

7. **Novelty detection:** Can LLMs reliably distinguish genuinely novel points from sophisticated restatements of common knowledge?

## Desired output

- Catalogue of documented biases with citations
- Magnitude of effects (how much do these biases distort ratings?)
- Mitigation strategies that have been tested
- Specific failure modes relevant to critique evaluation
- Recommendations for our use case
