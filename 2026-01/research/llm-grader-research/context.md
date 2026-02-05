# Research context

## About Forethought Research

Forethought Research is a nonprofit focused on navigating the transition to a world with superintelligent AI systems. Their research sits at the intersection of **philosophy and economics**, examining questions like:

- How should we govern increasingly powerful AI systems?
- What economic structures will emerge post-AGI?
- What rights should digital minds have?
- How do we ensure broadly shared benefits from transformative AI?
- What does "flourishing" mean in a world with superintelligent systems?

Key researchers include Will MacAskill (philosopher, author of *What We Owe the Future*) and Fin Moorhouse (researcher focused on AI governance and longtermist economics).

The research style is rigorous but accessible — serious engagement with philosophical arguments, economic modelling, and empirical analysis, written for an educated general audience rather than purely academic journals.

## Our goal

We are building an **LLM grader** — a system that can reliably evaluate the quality of AI-generated critiques of research papers and posts.

**Use case:** Generate a batch of AI critiques of a research draft, then use the grader to surface the best ones for human review. This reduces researcher time spent sifting through noise while preserving valuable critiques.

**Why this matters:** Scalable evaluation of LLM outputs is the primary bottleneck for ambitious AI research applications. While generating ideas, critiques, or content is straightforward, having researchers evaluate this output is expensive and time-consuming. A reliable grader unlocks more sophisticated research workflows.

**Success criteria:** >75% agreement with researcher assessments on which critiques are valuable vs. noise.

## Domain context

The critiques we need to evaluate are about:
- **Longtermism and macrostrategy** — how to positively influence the long-run future
- **AI alignment and governance** — technical and policy approaches to safe AI
- **Post-AGI economics** — labour markets, capital accumulation, inequality after AI
- **Digital minds** — consciousness, moral status, rights of AI systems
- **Existential risk** — analysis of catastrophic and extinction-level scenarios

These are philosophical and economic arguments, not empirical science. Valid critiques might challenge:
- Logical coherence of arguments
- Plausibility of economic assumptions
- Missing considerations or counterarguments
- Practical implications the author overlooked
- Framing that obscures important distinctions

## Constraints

- Small sample sizes: We have ~20 human-rated critiques for validation
- Domain specificity: Critiques require genuine engagement with philosophical and economic reasoning
- Quality bar: Researchers have high standards; "sounds plausible" is insufficient

## What we need from research

Practical, evidence-based guidance on:
1. Which techniques actually improve LLM grader reliability
2. Known failure modes to watch for
3. Statistical approaches for validation with small sample sizes
4. Tools and frameworks beyond basic prompting
