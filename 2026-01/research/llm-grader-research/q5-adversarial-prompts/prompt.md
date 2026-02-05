# Research question: Adversarial and devil's advocate prompting

## Question

Do adversarial prompts, devil's advocate instructions, or "steelman then critique" approaches actually improve the quality of LLM-generated critiques?

## Context

We are building a system to evaluate AI-generated critiques of research papers for **Forethought Research**, a nonprofit focused on the transition to superintelligent AI. Their research sits at the intersection of philosophy and economics — examining AI governance, post-AGI economics, digital mind rights, and longtermist macrostrategy.

A common intuition is that asking an LLM to "play devil's advocate" or "find weaknesses in this argument" will produce better critiques than neutral prompting. But does this actually work?

## What we want to know

1. **Devil's advocate prompting:** Does explicitly asking the model to argue against a position produce higher-quality critiques?

2. **Steelman-then-critique:** Does requiring the model to first articulate the strongest version of the argument before critiquing improve critique quality?

3. **Adversarial framing:** Does framing the task as "find flaws" vs. "evaluate this argument" change output quality?

4. **Red team prompting:** What techniques from AI red-teaming transfer to research critique generation?

5. **Overcriticism:** Does adversarial prompting lead to nitpicking or invalid objections? How can this be mitigated?

6. **Calibration effects:** Does adversarial prompting affect the model's calibration (e.g., confidence in critiques)?

## Desired output

- Summary of empirical findings from academic papers and industry research
- Specific adversarial prompting techniques that have been validated
- Evidence for quality improvements (quantitative if available)
- Known failure modes (overcriticism, strawmanning, etc.)
- Practical recommendations for our use case (generating research critiques)
- Key papers and resources for deeper reading
