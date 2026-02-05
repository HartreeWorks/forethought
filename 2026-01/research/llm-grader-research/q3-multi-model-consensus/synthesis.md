# Synthesis: Multi-model consensus for LLM evaluation

**Question:** Does multi-model voting/averaging improve correlation with human judgment?

**Sources:** Claude Opus 4.5, OpenAI o3-deep-research, Gemini deep-research-pro

---

## Consensus findings

All three models **strongly agree** on core findings:

### 1. Multi-model ensembles reliably improve human correlation

| Source | Finding |
|--------|---------|
| Claude | 10-20% improvement for general tasks, up to 140% for specialised domains |
| OpenAI | Panel of LLM Evaluators (PoLL) outperforms single GPT-4 at 7x lower cost |
| Gemini | Heterogeneous panel yields better correlation than single large judge |

**Confidence:** Very high. Multiple independent studies cited across all three reports.

### 2. Score averaging > majority voting

All three models identify **score averaging** as superior to majority voting:

- Claude: "Non-deterministic scoring with mean averaging consistently shows the largest correlations"
- OpenAI: "After averaging their scores the results closely mirrored the human consensus"
- Gemini: "G-Eval (probability-weighted scoring) provides finer-grained continuous scores that correlate more strongly with human judgment"

**Confidence:** High.

### 3. Model diversity is critical

Using different model families matters more than multiple runs of the same model:

- Claude: "Different model families provide significantly better ensemble performance"
- OpenAI: "Empirical studies strongly support using heterogeneous model families"
- Gemini: "Heterogeneous ensembles yield up to 8.4% improvement on math, 47% on reasoning"

**Recommendation:** Use one model from each of Claude, GPT, and Gemini/open-source.

### 4. Cost can be reduced with smaller model panels

The PoLL approach (3 smaller models) is 7-8x cheaper than single GPT-4 while achieving better performance.

---

## Disagreements and nuances

### Debate vs. voting

- **Gemini** uniquely emphasises that **voting drives performance, not debate**: "Majority voting alone accounts for most of the performance gains attributed to multi-agent debate"
- Claude and OpenAI discuss multi-agent debate more positively but acknowledge it adds complexity

**Resolution:** For Forethought's use case, a simple voting ensemble is sufficient and more cost-effective than complex debate architectures.

### G-Eval / probabilistic scoring

- **Gemini** strongly recommends G-Eval (extracting token probabilities for scores 1-5 and computing weighted average)
- Claude and OpenAI mention it but don't emphasise it as heavily

**Consideration:** G-Eval requires model API access to logprobs, which may not be available for all providers.

---

## Failure modes (consensus)

All three models warn about **shared blindspots**:

- If all models share a bias, the ensemble reinforces rather than corrects it
- 26% of invalid labels were missed by every validator in one study
- Ensembles exhibit "agreeableness bias" - high True Positive Rate but low True Negative Rate

**Mitigation:** Ensure genuine diversity; include at least one model from each major family.

---

## Practical recommendations for Forethought

### Recommended architecture

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
         + Std Dev as Confidence
```

### Implementation steps

1. **Select 3 models** from different families (Claude, OpenAI, Google)
2. **Use structured rubrics** with Likert scales (1-5)
3. **Compute mean score** across models
4. **Report standard deviation** as disagreement signal
5. **Flag high-variance items** for human review

### Expected performance

- **Correlation improvement:** 10-20% over single model
- **Cost:** ~$0.01-0.05 per evaluation (3 models, ~1000 tokens each)
- **Sweet spot:** 3 models provides most benefit; diminishing returns beyond

### What ensembles cannot solve

- Novelty detection (all models share training data limitations)
- Domain-specific expertise in emerging fields
- Subtle logical errors that fool all models

---

## Key papers

1. **Verga et al. (2024)** - [Replacing Judges with Juries](https://arxiv.org/abs/2404.18796) - PoLL approach, 7x cost reduction
2. **ChatEval (ICLR 2024)** - Multi-agent debate framework, +16% Spearman correlation
3. **Liu et al. (2023)** - [G-Eval](https://arxiv.org/abs/2303.16634) - Probability-weighted scoring
4. **Choi et al. (2025)** - "Debate or Vote?" - Evidence that voting > debate
