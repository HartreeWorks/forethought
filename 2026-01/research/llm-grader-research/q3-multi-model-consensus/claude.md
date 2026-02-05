# Multi-model voting and ensemble approaches for LLM-as-Judge evaluation

## Research question

Does multi-model voting, averaging, or ensemble approaches improve correlation with human judgment when evaluating research critiques?

## Executive summary

**Yes, with important caveats.** Multi-model ensemble approaches consistently improve correlation with human judgment over single-model evaluation, with empirically validated gains ranging from 5-16% for general evaluation tasks and up to 30-140% for specialised domains. However, ensembles cannot correct systematic biases shared across models, and the added complexity may not justify marginal gains for simpler evaluation tasks.

**Key findings:**
- **Score averaging** outperforms majority voting and greedy decoding across all tested scenarios
- **Model diversity** (different families) matters more than multiple runs of the same model
- **Cost reduction of 7-8x** is achievable using panels of smaller models vs. single GPT-4
- **Failure mode:** Ensembles amplify shared blindspots rather than correcting them
- **For research critique evaluation:** Ensemble approaches are recommended, but with structured rubrics and diverse model selection

---

## 1. Empirical findings on multi-model evaluation

### 1.1 Quantitative improvements in human correlation

| Approach | Improvement vs. single model | Source |
|----------|------------------------------|--------|
| ChatEval (multi-agent debate) | +16.3% Spearman, +10.0% Kendall-Tau | [ChatEval, ICLR 2024](https://arxiv.org/abs/2308.07201) |
| PoLL (Panel of LLM evaluators) | Better correlation than GPT-4 at 7x lower cost | [Verga et al., 2024](https://arxiv.org/abs/2404.18796) |
| SE-Jury (ensemble for code) | +29.6% to +140.8% over existing metrics | [SE-Jury, ASE 2025](https://arxiv.org/abs/2505.20854) |
| Score averaging vs. greedy | Consistently highest correlation across all evaluators | [LLM-as-Judge Study, 2025](https://arxiv.org/html/2506.13639v1) |
| MAJ-Eval (multi-agent debate) | Spearman ρ = 0.47 vs. 0.15-0.36 for baselines | [Multi-agent evaluation research](https://www.emergentmind.com/topics/multi-llm-evaluator-framework) |

### 1.2 Specific correlation metrics

**TopicalChat benchmark:**
- ChatEval multi-agent discussion: Kendall Tau = 0.57
- Single GPT-4 evaluator: Kendall Tau = 0.52
- Improvement: +9.6% relative gain

**MT-Bench:**
- GPT-4 single judge to human agreement: 85%
- Human-human agreement baseline: 81%
- Note: Single strong model already exceeds human-human agreement here

**Code generation (HumanEval-X):**
- SE-Jury ensemble: +32.1% to +78.8% improvement over all baselines
- Approaches inter-annotator agreement levels

### 1.3 When ensembles provide the largest gains

Ensembles show the greatest improvements when:
1. **Task complexity is high** (multi-hop reasoning, nuanced quality assessment)
2. **Subjective criteria** are being evaluated (coherence, engagingness, persuasiveness)
3. **Domain expertise is limited** in any single model
4. **Bias mitigation** is critical (position bias, length bias, self-preference)

Ensembles show smaller gains when:
1. Tasks are objective with clear correct answers
2. A single strong model already achieves >85% human agreement
3. Evaluation criteria are simple and well-defined

---

## 2. Validated techniques

### 2.1 Score averaging (highest recommendation)

**Finding:** Non-deterministic scoring with mean averaging consistently shows the largest correlations with human judges across all tested evaluator LLMs, reasoning types, and evaluation designs.

**Why it works:** Averaging captures fine-grained nuances (e.g., 4.5 when torn between 4 and 5) that majority voting or greedy decoding cannot express.

**Implementation:**
```
1. Run evaluation N times (typically 3-5) with temperature 0.3-0.5
2. Compute mean of all scores
3. Optionally: report standard deviation as confidence measure
```

**Source:** [An Empirical Study of LLM-as-a-Judge](https://arxiv.org/html/2506.13639v1)

### 2.2 Panel of LLM evaluators (PoLL)

**Finding:** An ensemble of three smaller models (Command-R, GPT-3.5-Turbo, Haiku) outperforms a single GPT-4 judge while being 7-8x cheaper.

**Key design principles:**
- Use models from **disjoint model families** to reduce shared biases
- Aggregate via **max voting** or **average pooling**
- Select models with **different training paradigms** and architectures

**Source:** [Replacing Judges with Juries](https://arxiv.org/abs/2404.18796)

### 2.3 JudgeBlender (prompt and model diversity)

Two validated variants:

**PromptBlender:**
- Single model with diverse prompts (e.g., direct scoring, multi-criteria breakdown, two-step binary-then-graded)
- Aggregate scores via averaging or majority vote
- Competitive with multi-model approaches at lower cost

**LLMBlender:**
- Multiple models, each with their optimal prompt
- Leverages complementary strengths across models
- Best for reducing both random variance and systematic bias

**Aggregation strategies tested:**
| Method | Tie-breaking | Performance |
|--------|--------------|-------------|
| Average voting (AV) | N/A | Best overall |
| Majority voting (MV) | Random | Moderate |
| Majority voting (MV) | Max score | Higher precision |
| Majority voting (MV) | Min score | Higher recall |

**Source:** [JudgeBlender](https://arxiv.org/abs/2412.13268)

### 2.4 ChatEval (multi-agent debate)

**Finding:** Assigning distinct personas to multiple LLM agents and having them debate leads to more comprehensive evaluation.

**Design:**
- Each agent has a unique perspective or expertise
- Agents debate nuances and disparities
- Final judgment emerges from structured discussion
- Captures subtleties that single perspectives miss

**Results:**
- +6.2% accuracy improvement for ChatGPT
- +2.5% accuracy improvement for GPT-4

**Source:** [ChatEval, ICLR 2024](https://openreview.net/forum?id=FQepisCUWu)

### 2.5 SE-Jury (dynamic team selection)

**Finding:** Not all judges are needed for all evaluations. Dynamic selection of the most appropriate judge subset reduces cost by ~50% without sacrificing performance.

**Implementation:**
1. Define multiple evaluation strategies (5 in SE-Jury)
2. Train a selector to identify which strategies suit each evaluation
3. Ensemble only the selected subset
4. Aggregate via weighted combination

**Source:** [SE-Jury](https://arxiv.org/abs/2505.20854)

---

## 3. Model diversity effects

### 3.1 Different model families vs. same model multiple runs

**Key finding:** Different model families provide significantly better ensemble performance than multiple runs of the same model.

**Theoretical basis:**
- Models trained on diverse datasets with varying architectures develop unique problem-solving capabilities
- Theoretical upperbound for ensemble performance can be 83% above the best single model
- Same-model reruns only address random variance, not systematic biases

**Empirical evidence:**

| Approach | Diversity type | Effectiveness |
|----------|---------------|---------------|
| Different architectures + training | High structural diversity | Best for bias mitigation |
| Same model, different prompts | Prompt diversity only | Moderate improvement |
| Same model, different temperatures | Random variance only | Limited improvement |
| Same training lineage (e.g., all GPT) | Low true diversity | Shared blindspots persist |

**Source:** [LLM-TOPLA: Efficient LLM Ensemble by Maximising Diversity](https://aclanthology.org/2024.findings-emnlp.698.pdf)

### 3.2 Self-ensemble approaches (single model, multiple prompts)

**Finding:** When multiple models aren't feasible, prompt diversity within a single model can provide meaningful gains.

**Dipper framework:**
- Optimised diverse prompts fed in parallel
- Elicits varied reasoning paths
- A Dipper ensemble of three Qwen2-MATH-1.5B instances outperformed a larger 7B model

**Limitation:** Cannot address model-specific systematic biases.

**Source:** [Dipper: Diversity in Prompts](https://arxiv.org/html/2412.15238)

### 3.3 Family clustering patterns

Analysis of 150,000+ evaluation instances shows:
- Models sharing architecture and training lineage (GPT-4/Turbo, Claude-3 variants) exhibit higher internal agreement
- This indicates **shared systematic bias patterns**
- Ensemble diversity should prioritise **cross-family selection** (e.g., Claude + GPT + open-source)

---

## 4. Advanced aggregation methods

### 4.1 Weighted voting

**Finding:** Optimal weighting functions differ significantly across model pairs and often include substantial negative weights.

**Implementation approaches:**
1. **Confidence-weighted voting:** Weight by model's self-reported confidence
2. **Calibrated weighting:** Pre-compute optimal weights using a gold-standard calibration set
3. **Performance-based weighting:** Weight by historical correlation with human judgment

**Calibration recommendation:**
- Create a small gold-standard test set (30-50 examples labelled by humans)
- Compare each model's scores to this set
- Derive weights that maximise ensemble correlation

**Source:** [Optimal Aggregation of LLM and PRM Signals](https://arxiv.org/html/2510.13918)

### 4.2 Bayesian aggregation

**Bayesian Win Rate Sampling (BWRS) and Dawid-Skene methods:**
- Explicitly model evaluator accuracy
- Incorporate prior information from human judgments
- Produce calibrated estimates with quantified uncertainty
- Correct for bias amplification in aggregation

**Use case:** Particularly valuable when you have varying quality of judges and want uncertainty quantification.

**Source:** [Multi-LLM Evaluator Framework research](https://www.emergentmind.com/topics/multi-llm-evaluator-framework)

### 4.3 Meta-judge frameworks

**Multi-agent meta-judge approach:**
1. Multiple LLM agents score each judgment using a weighted rubric
2. Consensus aggregation (weighted average, voting, or simulated panel debate)
3. Precision-based threshold filtering to select trustworthy judgments

**Finding:** Meta-judge pipelines boost accuracy and robustness significantly over single-model scoring.

**Source:** [Leveraging LLMs as Meta-Judges](https://arxiv.org/html/2504.17087v1)

### 4.4 Minority-veto ensemble

**Finding:** Standard majority voting cannot fully correct systematic biases. A minority-veto approach (where a few vetoes force an "invalid" label) substantially increases True Negative Rate.

**Use case:** When false positives are costly (e.g., approving flawed critiques).

---

## 5. Cost-benefit analysis

### 5.1 Cost comparison

| Approach | Relative cost | Performance |
|----------|---------------|-------------|
| Single GPT-4 Turbo | 1.0x | Baseline |
| PoLL (3 smaller models) | 0.12-0.14x | Better than GPT-4 |
| Single GPT-4o-mini | ~0.05x | Comparable for simple tasks |
| Ensemble of 3 mini models | ~0.15x | Better than single GPT-4 |

**Key insight:** Cost has dropped dramatically. The 2024 finding that PoLL is 7-8x cheaper than GPT-4 may be even more favourable now with 2025 pricing.

### 5.2 When ensemble overhead is justified

**Justified:**
- High-stakes evaluations where accuracy matters more than cost
- Subjective or nuanced quality assessment
- Known bias concerns (position, length, self-preference)
- Evaluation at scale where initial calibration cost is amortised

**Not justified:**
- Simple, objective tasks where single-model achieves >90% agreement
- Rapid prototyping and iteration
- When models share significant training data/biases
- When human review is available for edge cases

### 5.3 The Netflix Prize lesson

The winning team used 100+ algorithms, but Netflix never implemented it because "the additional accuracy gains did not seem to justify the engineering effort."

**Implication:** A 3-model ensemble is likely the sweet spot for most applications. Beyond that, returns diminish rapidly while complexity increases.

---

## 6. Failure modes and limitations

### 6.1 Shared blindspots (critical limitation)

**Finding:** Ensembles cannot correct biases shared across constituent models.

**Evidence:**
- 26% of invalid labels were missed by **every** validator in one study
- Models from the same family (e.g., GPT-4/Turbo, Claude-3 variants) show higher internal agreement, indicating shared bias patterns
- If most models exhibit the same measurement bias, the ensemble reinforces rather than corrects it

**Mitigation:**
- Ensure genuine diversity in model architectures and training data
- Include at least one model from each major family (OpenAI, Anthropic, open-source)
- Cannot rely solely on ensemble methods for systematic bias correction

### 6.2 Agreeableness bias

**Finding:** LLM judges exhibit systematic agreeableness bias - they reliably confirm correct feedback but frequently fail to reject incorrect feedback.

**Metrics:**
- High True Positive Rate (TPR)
- Low True Negative Rate (TNR)
- Majority voting alone cannot fix this

**Implication for research critique evaluation:** Ensembles may be overly generous, accepting flawed critiques. Use minority-veto or threshold-based filtering.

### 6.3 Conformity bias in multi-agent systems

**Finding:** In multi-agent debate, agents may reinforce each other's errors rather than providing independent evaluation, creating dangerous false consensus.

**Mitigation:**
- Assign genuinely different personas/expertise
- Structure debate to reward disagreement
- Include adversarial agents explicitly tasked with finding flaws

### 6.4 Error cascading

**Finding:** In sequential multi-agent systems, errors cascade down the dependency chain, amplifying initial mistakes.

**Mitigation:**
- Use parallel evaluation (each judge evaluates independently)
- Aggregate at the end rather than passing intermediate results

### 6.5 Overhead and coordination complexity

**Practical challenges:**
- Setting up and aligning multiple models to specific evaluation criteria
- Ensuring consistent output formats across models
- Managing API costs and rate limits
- Small models may produce misleading critiques due to limited reasoning

### 6.6 Pairwise comparison instability

**Finding:** Pairwise preferences flip in about 35% of cases, compared to only 9% for absolute scores. Pairwise protocols are more vulnerable to distracted evaluation.

**Recommendation:** Use pointwise scoring for reliability, with optional pairwise as a secondary signal.

---

## 7. Practical recommendations for Forethought's LLM grader

### 7.1 Recommended architecture

```
                    ┌─────────────────┐
                    │  Research       │
                    │  Critique       │
                    └────────┬────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
              ▼              ▼              ▼
        ┌──────────┐  ┌──────────┐  ┌──────────┐
        │ Claude   │  │ GPT-4o   │  │ Gemini   │
        │ Sonnet   │  │ -mini    │  │ Flash    │
        └────┬─────┘  └────┬─────┘  └────┬─────┘
             │             │             │
             └──────────┬──────────┬─────┘
                        │          │
                        ▼          ▼
                  ┌──────────────────────┐
                  │  Score Averaging     │
                  │  + Std Dev Reporting │
                  └──────────────────────┘
```

### 7.2 Implementation steps

1. **Select diverse models:** Choose one model from each major family
   - Claude (Sonnet or Haiku) - Anthropic
   - GPT-4o-mini or GPT-4o - OpenAI
   - Gemini Flash or Gemini Pro - Google

2. **Design structured rubrics:**
   - Binary or 3-point scales where possible
   - Clear criteria for each score level
   - Separate rubric dimensions (argument quality, evidence use, clarity, etc.)

3. **Implement score averaging:**
   - Run each model at temperature 0.3-0.5
   - Optionally run 2-3 times per model
   - Compute mean score and standard deviation
   - Flag high-variance items for human review

4. **Create calibration set:**
   - 30-50 research critiques with human gold-standard ratings
   - Use to validate ensemble performance
   - Adjust model weights if needed

5. **Handle edge cases:**
   - High disagreement (std dev > threshold) → human review
   - Unanimous low scores → accept as low quality
   - Mixed signals on novel arguments → human review

### 7.3 Expected performance

Based on the research:
- **Correlation improvement:** 10-20% over single model
- **Cost:** ~$0.01-0.05 per evaluation (3 models, ~1000 tokens each)
- **Reliability:** Reduced variance, better handling of edge cases
- **Limitation:** Will still miss systematic errors that all models share

### 7.4 What ensembles cannot solve

For Forethought's research critique evaluation:
- **Novelty detection:** Ensembles cannot reliably identify genuinely novel ideas that deviate from training data consensus
- **Domain-specific expertise:** Philosophy of mind, digital consciousness, post-AGI economics may exceed model capabilities
- **Subtle logical errors:** Sophisticated-sounding but flawed arguments may fool all models

**These require human expert review** as a complementary layer, not replacement.

---

## 8. Key papers and resources

### Primary research papers

1. **[Replacing Judges with Juries](https://arxiv.org/abs/2404.18796)** (Verga et al., 2024)
   - Introduces PoLL approach
   - Shows 7x cost reduction with better performance

2. **[ChatEval: Multi-Agent Debate](https://arxiv.org/abs/2308.07201)** (ICLR 2024)
   - Multi-agent debate framework
   - +16.3% Spearman correlation improvement

3. **[JudgeBlender](https://arxiv.org/abs/2412.13268)** (2024)
   - Prompt diversity and model ensemble
   - PromptBlender and LLMBlender variants

4. **[SE-Jury](https://arxiv.org/abs/2505.20854)** (ASE 2025)
   - Dynamic team selection for ensemble
   - 50% cost reduction via smart judge selection

5. **[An Empirical Study of LLM-as-a-Judge](https://arxiv.org/html/2506.13639v1)** (2025)
   - Score averaging is best aggregation method
   - Non-deterministic scoring outperforms greedy

6. **[Beyond Consensus: Agreeableness Bias](https://arxiv.org/abs/2510.11822)** (2025)
   - Documents systematic positive bias
   - Minority-veto mitigation strategy

7. **[LLM-TOPLA: Diversity Maximising](https://aclanthology.org/2024.findings-emnlp.698.pdf)** (EMNLP 2024)
   - Formal diversity metric for ensemble selection
   - Diversity-performance correlation

### Survey papers

8. **[LLMs-as-Judges: Comprehensive Survey](https://arxiv.org/html/2412.05579v2)** (2024)
   - Overview of all evaluation methods
   - Taxonomy of approaches

9. **[A Survey on LLM-as-a-Judge](https://arxiv.org/html/2411.15594v6)** (2024)
   - Covers biases, mitigation strategies
   - Design choices analysis

### Practical guides

10. **[LLM-as-a-Judge Guide](https://www.evidentlyai.com/llm-guide/llm-as-a-judge)** (Evidently AI)
    - Implementation best practices
    - Common pitfalls

11. **[Using LLMs for Evaluation](https://cameronrwolfe.substack.com/p/llm-as-a-judge)** (Cameron R. Wolfe)
    - Detailed technical guide
    - Cost analysis

---

## 9. Conclusion

Multi-model ensemble approaches provide meaningful improvements in correlation with human judgment for LLM-as-Judge evaluation, with empirical gains of 10-20% for general tasks and up to 140% for specialised domains. The most validated approach is **score averaging across diverse model families** (not multiple runs of the same model).

For Forethought's LLM grader evaluating research critiques:

**Do:**
- Use 3 models from different families
- Implement score averaging with temperature 0.3-0.5
- Create a 30-50 item calibration set
- Report disagreement (high std dev) for human review
- Use structured rubrics with clear criteria

**Don't:**
- Expect ensembles to catch errors all models share
- Use only models from the same family
- Rely on majority voting for nuanced quality assessment
- Skip human review for novel or domain-specific content

The primary bottleneck remains **novelty detection and domain expertise** - areas where even ensemble approaches fall short of human expert judgment.

---

*Research conducted: January 2026*
*Model: Claude Opus 4.5*
