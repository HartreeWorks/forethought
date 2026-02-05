# Q1 Synthesis: Calibration techniques for LLM graders

**Sources:** Claude Opus 4.5 (WebSearch), Gemini deep-research-pro-preview-12-2025, OpenAI o3-deep-research
**Generated:** 2026-01-20 (updated with OpenAI)

---

## Executive summary

All three models (Claude, Gemini, OpenAI) converge on a clear finding: **holistic single-score evaluation is insufficient** for reliably grading philosophical critiques. A composite approach combining structured reasoning, decomposed rubrics, and confidence filtering is required to achieve >75% agreement with human researchers.

### Key recommendation: PREPAIR-Toulmin Workflow

Gemini's research proposes a specific workflow adapted for philosophical argumentation:

1. **Pointwise analysis with Toulmin CoT** — Force the grader to identify Claim, Warrant, and Backing before scoring
2. **Confidence filtering** — Discard judgments where verbalized confidence < 80%
3. **Pairwise ranking** — Only for top candidates, using pre-computed rationales to avoid "distracted evaluation"

---

## Areas of agreement

### 1. Chain-of-thought improves reasoning but has critical failure modes

**Both sources agree:**
- CoT improves alignment with human judgements vs. direct scoring
- Reasoning should be produced **before** the final score
- CoT does not eliminate all biases (position, verbosity, sycophancy)

**Key failure mode:** "Reasoning-Score Mismatch" — models generate sound reasoning that identifies flaws, then assign scores contradicting their own analysis.

**Mitigation:** Use **structured argumentation CoT** (Toulmin model) rather than generic "think step by step."

### 2. Verbalized confidence outperforms log probabilities

**Both sources agree:**
- RLHF-tuned models (GPT-4, Claude) are miscalibrated on raw log probabilities
- Asking the model to state its confidence explicitly yields better calibration
- Confidence can be used to filter uncertain judgments

**Quantitative finding (Gemini):** Verbalized confidence reduced Expected Calibration Error by up to 50% vs. log probabilities.

**Recommendation:** Implement a hard confidence threshold — discard evaluations with confidence < 80%.

### 3. Rubric decomposition outperforms holistic scoring

**Both sources agree:**
- Single-score holistic evaluation is prone to high variance
- Breaking evaluation into orthogonal dimensions (novelty, validity, actionability) improves reliability
- Domain-specific criteria outperform generic criteria

**Quantitative finding (Gemini):** DeCE framework achieved r=0.78 correlation with expert judgments vs. r=0.35 for holistic scoring.

**Gemini's domain adaptation:** For philosophical critiques, decompose into "Logical Validity" (precision) and "Premise Relevance" (recall).

### 4. Pairwise vs. absolute: Task-dependent

**Both sources agree:**
- Pairwise comparison is generally more intuitive and stable
- However, pairwise is vulnerable to "distracted evaluation" (biased by length, tone, assertiveness)
- Pairwise preferences flip in ~35% of cases when distractors added vs. ~9% for absolute

**Recommendation:** Use **PREPAIR** — pointwise analysis first (to ground the model), then pairwise comparison using the rationales.

### 5. Self-consistency improves reliability

**Both sources agree:**
- Running multiple evaluation passes and aggregating reduces noise
- However, LLMs show significant self-inconsistency across runs

**Quantitative finding (Claude):** Few-shot prompting increased GPT-4 consistency from 65% to 77.5%.

---

## Unique contributions

### From Gemini: Toulmin model for philosophical argumentation

Gemini specifically recommends using the **Toulmin argumentation model** to structure CoT for philosophical critiques:

| Component | Application to Critique Grading |
|-----------|--------------------------------|
| **Claim** | What is the critique asserting? |
| **Data** | What evidence supports it? |
| **Warrant** | What logical bridge connects data to claim? |
| **Backing** | What supports the warrant? |
| **Qualifier** | What limits the scope? |
| **Rebuttal** | What counterarguments exist? |

**Implementation prompt:**
```
Instead of "Is this critique good?", ask:
1. What is the CLAIM of this critique?
2. What WARRANT connects the evidence to the claim?
3. Is the Warrant supported by BACKING?
```

### From Gemini: Statistical approaches for small-N validation

With only ~20 human-rated critiques:

1. **Bootstrap resampling** — Create 1000 resampled datasets, calculate accuracy on each, get confidence interval
2. **Calibration set strategy** — Train a simple logistic regression mapping LLM outputs (Score, Confidence, Length) to Human Label
3. **Hierarchical GLMs** — Estimate effect of critique features (length, topic) to separate true quality from bias

### From Claude: Overconfidence as primary failure mode

Claude's research emphasizes the "Overconfidence Phenomenon" more strongly:
- LLMs predict confidence that significantly overstates actual correctness
- **TH-Score** metric focuses on high- and low-confidence intervals for better calibration measurement
- **LLM-as-a-Fuser** ensemble achieved +47% accuracy improvement

### From Claude: Multi-turn verification limitations

Claude highlights that simple "Are you sure?" verification has limited value:
- Models tend to "double down" on initial errors
- Better approach: **Debate protocols** — one model argues for, another against, third judges

### From OpenAI: Quantitative validation of CoT + Rubric

OpenAI research provides specific numbers validating the recommended approach:
- CoT prompting improved evaluation accuracy by **15-20%** vs. single-step judgments
- G-Eval framework (detailed rubric + CoT explanation before scoring) achieves high agreement
- LLM judges aligned with expert judgments about **85%** of the time when using rubric-guided evaluation

OpenAI also emphasizes **specific failure modes** to watch for:
- CoT can generate "plausible-sounding reasoning chains that ultimately justify a flawed conclusion"
- Generic CoT prompts may miss domain-specific details
- Models can "cheat" rubrics by giving maximum scores without substantiation

**Mitigation:** Ensure CoT prompts emphasize specific qualities to scrutinize; require evidence from the critique for each sub-criterion rating.

---

## Disagreements / Tensions

### Confidence threshold level

- **Gemini** recommends 80% confidence threshold
- **Claude** suggests using uncertainty quantification but doesn't specify threshold

**Resolution:** 80% is a reasonable starting point; calibrate based on validation data.

### Novelty of pairwise findings

- **Claude** presents the "pairwise is worse" finding as newer/surprising
- **Gemini** presents PREPAIR as an established solution

**Resolution:** Both agree on the solution (PREPAIR); the framing difference doesn't affect implementation.

---

## Recommended implementation for Forethought

### Phase 1: Pointwise analysis (for each critique)

```
Prompt structure:
1. [Context from research paper]
2. [Critique to evaluate]
3. Instructions:
   - Identify the CLAIM of this critique
   - Identify the WARRANT (logical bridge)
   - Assess if Warrant has BACKING
   - Score 1-10 on: Logical Soundness, Novelty, Relevance
   - Output confidence (0.0-1.0)
```

### Phase 2: Filtering

- Discard if confidence < 0.8
- Discard if Warrant is "Missing" or "Fallacious"

### Phase 3: Pairwise ranking (top candidates only)

- Feed Toulmin analyses from Phase 1 into pairwise comparator
- Compare only high-scoring candidates to select best 5-10

### Phase 4: Validation with 20 samples

1. Run workflow on 20 human-rated critiques
2. Calculate (True Positives + True Negatives) / Total
3. Bootstrap 1000x to get 95% CI
4. If lower bound < 75%, adjust rubric weights or confidence threshold

---

## Key papers to read

| Paper | Focus |
|-------|-------|
| [DeCE: Beyond Pointwise Scores](https://arxiv.org/html/2509.16093) | Rubric decomposition framework |
| [Verbalized Probabilities for RLHF-LMs](https://openreview.net) | Confidence calibration |
| [PREPAIR](https://arxiv.org/abs/2507) | Pointwise + pairwise hybrid |
| [Toulmin Argument Analysis with LLMs](https://arxiv.org/abs/2307) | Structured argumentation |
| [Overconfidence in LLM-as-a-Judge](https://arxiv.org/html/2508.06225v2) | TH-Score metric |
| [A Survey on LLM-as-a-Judge](https://arxiv.org/abs/2411.15594) | Comprehensive overview |
