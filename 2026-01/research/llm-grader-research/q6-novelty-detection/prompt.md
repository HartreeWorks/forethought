# Research question: Detecting genuine novelty in LLM outputs

## Question

What is the state of the art on detecting genuine novelty vs. sophisticated recombination in LLM-generated critiques?

## Context

We are building a system to evaluate AI-generated critiques of research papers for **Forethought Research**, a nonprofit focused on the transition to superintelligent AI. Their research sits at the intersection of philosophy and economics — examining AI governance, post-AGI economics, digital mind rights, and longtermist macrostrategy.

A key dimension of critique quality is **novelty** — does the critique identify something the researcher hadn't considered, or is it a restatement of common knowledge in sophisticated language? This is difficult because LLMs excel at producing text that sounds insightful but may not be.

## What we want to know

1. **Novelty detection:** Can LLMs reliably distinguish novel insights from sophisticated restatements of common knowledge?

2. **Self-assessment:** Can an LLM accurately assess the novelty of its own outputs?

3. **Comparison approaches:** Is novelty better assessed absolutely ("is this novel?") or relatively ("which of these critiques is more novel?")?

4. **Domain knowledge:** How does novelty detection depend on the LLM's knowledge of the specific domain? How can we help it assess novelty in specialized areas?

5. **Embedding approaches:** Can semantic similarity to existing literature detect lack of novelty?

6. **Human-in-the-loop:** What role should human judgment play in novelty assessment?

## Desired output

- Summary of research on novelty/creativity detection in LLM outputs
- Specific techniques that have been proposed or validated
- Honest assessment of current limitations
- Known failure modes (false positives/negatives)
- Practical recommendations for our use case
- Key papers and resources for deeper reading
