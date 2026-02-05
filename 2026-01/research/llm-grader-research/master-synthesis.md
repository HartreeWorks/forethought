# Master synthesis: LLM grader research

**Generated:** 2026-01-20
**Research scope:** All 8 questions (Q1-Q8)
**Sources:** Claude Opus 4.5 (WebSearch), OpenAI o3-deep-research, Gemini deep-research-pro
**Status:** 22/24 outputs completed (Gemini Q5-Q8 hit rate limits)

---

## Executive summary

Building an LLM grader to evaluate critiques of philosophical/economic research is **feasible but requires moving beyond naive "LLM-as-a-Judge" prompting**. The research converges on a clear architecture with specific techniques that improve human correlation while acknowledging fundamental limitations.

### Recommended approach: PREPAIR-Toulmin Workflow

1. **Structured argumentation** — Use Toulmin model to decompose critiques into Claim, Warrant, Backing
2. **Rubric decomposition** — Score on multiple dimensions (Logical Soundness, Novelty, Relevance)
3. **Verbalized confidence filtering** — Discard evaluations with confidence < 80%
4. **Multi-model ensemble** — Use 3 diverse models, average scores
5. **Pointwise-first, pairwise-second** — Ground the model with individual analysis before comparative ranking

### Critical limitations

- **Novelty detection** requires human judgment—LLMs cannot reliably self-assess novelty (near-zero correlation with experts)
- **No domain-specific research** exists for longtermist philosophy
- **Sycophancy** is pervasive—all tested models show sycophantic behaviour
- **Small-N validation** requires careful statistics—need ~90% observed agreement to confidently claim >75% true accuracy

---

## Key findings by research question

### Q1: Calibration techniques

**Core finding:** Structured techniques improve calibration, but some popular approaches fail.

| Technique | Verdict | Evidence |
|-----------|---------|----------|
| **Rubric decomposition** | Strong | DeCE achieved r=0.78 vs r=0.35 for holistic scoring |
| **Verbalized confidence** | Strong | Reduces Expected Calibration Error by ~50% |
| **Toulmin CoT** | Strong | Better than generic "think step by step" |
| Generic Chain-of-Thought | Weak | Causes "Reasoning-Score Mismatch" |
| Raw log probabilities | Fails | Miscalibrated for RLHF models |

### Q2: Systematic biases

**Core finding:** 12+ documented biases; mitigation requires explicit countermeasures.

| Bias | Description | Mitigation |
|------|-------------|------------|
| **Position bias** | Favours first or second response | Swap and average both orderings |
| **Verbosity bias** | Longer = better | Strict rubric with length-quality trade-off |
| **Self-preference** | Favours own outputs | Use different model for generation vs evaluation |
| **Sycophancy** | Agrees with user's stated position | Neutral framing, avoid revealing position |
| **Fallacy oversight** | Misses logical errors | Structured argumentation (Toulmin) |

### Q3: Multi-model consensus

**Core finding:** Ensembles improve correlation by 10-20%; model diversity matters more than number.

- **Score averaging > majority voting** across all tested scenarios
- **Different model families** provide better ensemble performance than same-model reruns
- **PoLL approach** (3 smaller models) beats single GPT-4 at 7x lower cost
- **Shared blindspots** cannot be corrected—26% of invalid labels missed by all validators

### Q4: Multi-persona prompting

**Core finding:** Modest benefits for diversity, inconsistent for quality.

- **Diversity gains are real** but moderate
- **Quality improvements** are task-dependent; expert personas don't improve factual accuracy
- **Jekyll & Hyde ensemble** (persona + neutral baseline) shows +9.98% accuracy
- **80% of personas demonstrate bias** in experiments; avoid demographic personas

### Q5: Adversarial prompts

**Core finding:** Specific structured techniques help; generic "be critical" prompts backfire.

- **Multi-agent devil's advocate** (DEBATE framework) improves evaluation quality
- **Single "be critical" prompts** increase hallucinations and nitpicks
- **Rubric-based evaluation** outperforms free-form adversarial critique
- **Primary failure mode:** Overcriticism, not undercriticism

### Q6: Novelty detection

**Core finding:** **Cannot be reliably automated**—requires human judgment.

- LLMs excel at **combinatorial creativity**, struggle with **transformational creativity**
- Self-assessment of novelty has **near-zero correlation** with expert judgment
- **Pairwise comparison** works better than binary "is this novel?" classification
- **Smart plagiarism** is documented: LLMs disguise existing work through terminological shifts

**Recommendation:** Use LLMs to *flag* potential novelty for human review, not to score it.

### Q7: Small-N validation

**Core finding:** With n=20, expect wide uncertainty; need ~90% observed for confidence.

| Observed | P(true > 75%) | Verdict |
|----------|---------------|---------|
| 15/20 (75%) | 46% | Inconclusive |
| 17/20 (85%) | 75% | Moderate evidence |
| 18/20 (90%) | 87% | Strong evidence |

- **Use percentage agreement + Wilson CI** (not Cohen's kappa, which is unstable at small n)
- **Bayesian Beta-Binomial** lets you quantify P(agreement > threshold)
- **Sequential testing** allows early stopping if results are clear

### Q8: Evaluation frameworks

**Core finding:** Promptfoo + custom rubrics is sufficient for Forethought's needs.

- **Promptfoo:** Simple, CLI-based, supports custom rubrics, multi-model comparison
- More sophisticated frameworks (RAGAS, DeepEval, Langfuse) add complexity without proportional benefit for this use case
- Design for **hybrid evaluation:** LLM handles 80-90% automatically, humans review flagged cases

---

## Implementation blueprint

### Phase 1: Pointwise analysis (Toulmin grader)

For each critique, prompt the LLM grader (temperature=0):

```markdown
## Context
[Research paper abstract and key claims]

## Critique to evaluate
[The critique]

## Instructions
1. CLAIM: What is the main assertion of this critique?
2. WARRANT: What logical bridge connects the evidence to the claim?
3. BACKING: Is the warrant supported? By what?
4. QUALIFIER: What limits the scope of this critique?

Score 1-5 on:
- Logical Soundness: Does the reasoning hold?
- Relevance: Does this address core premises?
- Clarity: Is the argument clear and well-structured?

Confidence (0.0-1.0): How certain are you of this evaluation?

IMPORTANT: Do not assume the critique is correct just because it is confident.
Verify the reasoning independently.
```

### Phase 2: Multi-model ensemble

```
Research Critique
       │
       ├─────────────────────────────────┐
       │                │                │
       ▼                ▼                ▼
   Claude           GPT-4o-mini       Gemini
   Sonnet                              Flash
       │                │                │
       └────────────────┼────────────────┘
                        │
                        ▼
              Score Averaging
         + Std Dev as Disagreement Signal
```

### Phase 3: Filtering

Discard if:
- Confidence < 0.8
- Warrant is "Missing" or "Fallacious"
- High disagreement between models (std dev > threshold)
- Average score < threshold (tune on validation set)

### Phase 4: Validation with n=20

1. Run workflow on all 20 samples
2. Calculate percentage agreement
3. Compute Wilson 95% CI
4. Compute Bayesian P(agreement > 75%)
5. If lower bound < 75%, adjust thresholds/prompts

---

## Bias mitigation checklist

| Bias | Mitigation | Implementation |
|------|------------|----------------|
| Position | Swap and average | Run with orderings A-B and B-A |
| Verbosity | Strict rubric | Include explicit length-quality trade-off |
| Self-preference | Different models | Use different model family for generation vs evaluation |
| Sycophancy | Neutral framing | Avoid framing that suggests expected answers |
| Overconfidence | Verbalized confidence | Filter low-confidence evaluations |
| Shared blindspots | Model diversity | Include models from OpenAI, Anthropic, and Google |

---

## What the grader can and cannot do

### Can do reliably
- Filter obvious noise (generic, off-topic critiques)
- Identify structural and logical issues
- Compare critiques on well-defined dimensions
- Flag high-uncertainty cases for human review
- Reduce researcher evaluation burden by 60-80%

### Cannot do reliably
- Detect genuine novelty vs sophisticated recombination
- Evaluate cutting-edge philosophical arguments
- Assess domain-specific expertise in longtermism
- Replace human judgment on high-stakes decisions

---

## Cost and latency estimates

| Configuration | Cost per critique | Latency |
|---------------|-------------------|---------|
| Single model, Toulmin CoT | ~$0.01-0.02 | 2-5s |
| 3-model ensemble | ~$0.03-0.05 | 3-8s (parallel) |
| With confidence filtering | +10-20% overhead | +1-2s |

For ~100 critiques: ~$3-5 total cost

---

## Next steps for Forethought

### Immediate (this sprint)

1. **Create 20-item validation set** — Have Fin rate 20 critiques as "valuable" vs "noise"
2. **Implement Toulmin grader prompt** — Use template above
3. **Run baseline evaluation** — Single model, compute agreement + Wilson CI
4. **Compute Bayesian posterior** — P(agreement > 75%)

### If baseline < 75%

1. Add multi-model ensemble
2. Tune confidence threshold
3. Adjust rubric weights
4. Consider pairwise comparison as secondary signal

### Accept limitations

1. **Novelty:** Keep as human-judgment dimension; flag for review, don't score
2. **Complex philosophy:** Flag for expert review when confidence low
3. **Domain context:** Provide extensive background in prompts

---

## Key papers (prioritised reading)

1. **[A Survey on LLM-as-a-Judge](https://arxiv.org/abs/2411.15594)** — Comprehensive overview
2. **[DeCE: Beyond Pointwise Scores](https://arxiv.org/html/2509.16093)** — Rubric decomposition (r=0.78)
3. **[Replacing Judges with Juries (PoLL)](https://arxiv.org/abs/2404.18796)** — Multi-model ensemble, 7x cost reduction
4. **[CriticGPT](https://cdn.openai.com/llm-critics-help-catch-llm-bugs-paper.pdf)** — Overcriticism failure mode
5. **[Can LLMs Generate Novel Research Ideas?](https://arxiv.org/abs/2409.04109)** — Novelty limitations
6. **[Justice or Prejudice?](https://arxiv.org/abs/2410.02736)** — 12 biases + CALM framework

---

## Appendix: Research methodology

This synthesis is based on multi-model deep research:
- **Claude Opus 4.5** — WebSearch synthesis (8/8 questions)
- **OpenAI o3-deep-research-2025-06-26** — Background mode with polling (8/8 questions)
- **Gemini deep-research-pro-preview-12-2025** — Interactions API (4/8 questions; Q5-Q8 hit rate limits)

**Total outputs:** 22/24 completed
**Total cost:** ~$50-80 for all deep research queries

All outputs saved in `/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/llm-grader-research/q*/`
