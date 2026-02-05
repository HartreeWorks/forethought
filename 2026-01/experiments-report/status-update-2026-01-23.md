# Status update: Moonshot exploration week

*23 January 2026*

Hi Max and Fin,

This week I focused on the "moonshot" exploration: **Can systematic prompt engineering meaningfully improve the quality of AI-generated research insights?** This is the key question for deciding whether Forethought should invest in dedicated AI expertise beyond basic researcher adoption.

## What I did

### 2. Used a pre-validated LLM grader

A key question for this work was: how do we evaluate critique quality at scale? Building a custom grader would require collecting human-rated samples and validating that the grader tracks human judgment—expensive and time-consuming.

Instead, I used the grader from the **ACORN benchmark** (a dataset for evaluating conceptual reasoning). The ACORN team has already validated that their LLM grader correlates well with their researchers' quality judgments. Since ACORN focuses on philosophical/conceptual reasoning—similar to Forethought's domain—it seemed reasonable to assume it would track the qualities we care about (centrality, logical strength, correctness).

This is a pragmatic shortcut. The grader may not perfectly calibrate to Forethought's specific standards, but it should be directionally correct for comparing prompts against each other.

### 1. Tested brainstorm prompts using the ACORN grader

I created three variations of the "brainstorm critiques" prompt from the Paper Critique chain, inspired by Anthropic's finding that specific prompts dramatically improved Claude's frontend design outputs:

- **Surgery** — Toulmin-style structural analysis (identify premises → find weakest link → attack)
- **Personas** — Generate critiques from distinct hostile critic perspectives (methodologist, consequentialist, contrarian, etc.)
- **Unforgettable** — Focus on the single most author-troubling objection

I ran these against three Forethought papers using GPT-5.2 Pro, generating 90 critiques total and grading them using an LLM grader.

### 3. Researched prompt engineering returns

Rather than rely solely on my small-n experiments, I ran comprehensive deep research using OpenAI's o3-deep-research and Gemini's deep-research-pro to survey the empirical literature on prompt engineering gains.

## Key findings

### From the deep research

The literature is clearer than I expected. Key findings:

1. **Magnitude of gains is large for reasoning tasks.** Chain-of-thought and structured prompting can improve accuracy from ~50% to ~85% on complex reasoning benchmarks. Legal reasoning studies (a close proxy for philosophical work) show similarly dramatic gains.

2. **Philosophical/analytical tasks are highly prompt-sensitive.** Tasks requiring multi-step reasoning, constraint satisfaction, and synthesis benefit most from structured prompting—exactly Forethought's core tasks.

3. **The skill is evolving, not dying.** As models improve, basic prompting tricks matter less, but "context engineering" and multi-step orchestration matter more. Tree of Thoughts (branching exploration) can turn 4% success into 74% success on complex reasoning tasks.

4. **There's a competence trap.** Techniques from 2023 can now *degrade* performance on reasoning models like OpenAI's o1. Using "think step by step" on o1 can reduce accuracy by 36%. Expertise is needed to know what works when.

5. **The gains require engineering, not chat.** Researchers can't implement Tree of Thoughts or multi-agent debate via a chat interface—these require programmatic orchestration.

### From the experiment

The "Unforgettable" prompt consistently outperformed, while "Surgery" led on correctness:

| Prompt | Overall Score (mean) | Centrality | Correctness |
|--------|---------------------|------------|-------------|
| Unforgettable | 0.30 | 0.45 | 0.75 |
| Surgery | 0.31 | 0.39 | 0.82 |
| Personas | 0.27 | 0.49 | 0.75 |

The differences are modest but consistent across papers. With n=10 per condition, they're borderline statistically significant. More importantly: **qualitative review of the top critiques is encouraging**. The best outputs from GPT-5.2 Pro are genuinely incisive—the kind of objections that would give an author pause.

## What this means for the investment decision

The evidence points toward **prompt engineering mattering a lot for Forethought's work**, but the nature of the skill has shifted:

| Old framing | New framing |
|------------|-------------|
| "Prompt writer" | AI systems architect |
| Finding magic words | Designing reasoning workflows |
| Single-turn optimization | Multi-step orchestration |
| Phrasing tricks | Context engineering |

**My updated view:** The "wing it" approach likely captures 50-60% of available value. A specialist (or trained researcher) focusing on structured reasoning systems could capture significantly more—potentially the difference between "fluent but shallow" outputs and "load-bearing" insights.

However, there's a legitimate question about whether researchers could learn enough prompt engineering in a few weeks to capture most gains themselves, vs. needing ongoing specialist support. The deep research suggests the learning curve is moderate for basics but the frontier is moving fast.

## Two categories of value (clarified)

1. **Affordances/mundane utility** — Making AI easier to use (interfaces, workflows, filtering). This clearly justifies ongoing investment regardless of moonshot results. The forethought-publish skill, chain orchestrator, and starter kit are already valuable.

2. **Intelligence extraction** — Getting better insights through systematic prompting. The evidence suggests this is real and meaningful, but requires either (a) dedicated expertise or (b) researchers investing significant time in learning prompt engineering.

## What's still pending

- Your input on whether the qualitative critique outputs seem valuable



## What should I do next?

Research team uplift:

- Share first version of a “Forethought Skills” library
  - Ask Many Models tool
- Will has booked a call on Tuesday
- Check-in with Fin
- Decide whether to recommend Claude Cowork or Claude Code (probably former)
- (?) Offer some more 1-1 calls, or a small workshop focussed on X.

## Recommendation preview

I'm still forming my final recommendation, but the evidence so far suggests:

1. **Yes:** Researchers should invest 2+ hours/week in AI tools (already in mid-project report)
2. **Probably yes:** Forethought should have someone (contractor or trained researcher) focused on building reasoning systems/workflows, not just prompts
3. **Open question:** Whether that's a multi-month contractor engagement vs. intensive researcher training

Happy to discuss any of this on our next call.

Best,
Peter
