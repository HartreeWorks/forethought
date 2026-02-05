# Synthesis: Statistical validation with small sample sizes

**Question:** What statistical approaches work for validating an LLM grader with ~20 samples?

**Sources:** Claude Opus 4.5, OpenAI o3-deep-research (Gemini incomplete due to rate limits)

---

## Core finding

**Validating with n=20 is statistically challenging but not impossible.** The key is using appropriate methods and accepting wide uncertainty intervals.

---

## Key findings

### 1. Use the right metric

| Metric | Recommendation | Reason |
|--------|----------------|--------|
| **Percentage agreement + Wilson CI** | Primary | Stable at small n, matches your threshold definition |
| **Krippendorff's alpha** | Secondary | Self-adjusts for sample size |
| **Cohen's kappa** | Avoid | Unstable below n=60-80 |

**Why avoid kappa at n=20:**
- Estimates are highly unstable
- Prevalence paradox: Low kappa despite high agreement when one category dominates
- Better alternatives exist

### 2. Expect wide confidence intervals

With n=20 and 75% observed agreement:
- **Wilson 95% CI:** approximately 53-89%
- **You cannot make precise claims**

| Observed | Wilson 95% CI | Width |
|----------|---------------|-------|
| 75% (15/20) | 53%-89% | 36 pp |
| 80% (16/20) | 58%-92% | 34 pp |
| 85% (17/20) | 64%-95% | 31 pp |
| 90% (18/20) | 70%-97% | 27 pp |

**Key insight:** Even with 90% observed agreement, CI lower bound is only 70%.

### 3. Bayesian framing is valuable

Using Beta-Binomial model, you can answer: "What is P(true agreement > 75%)?"

| Observed (out of 20) | P(true > 75%) | Verdict |
|---------------------|----------------|---------|
| 15 | 46% | Inconclusive |
| 16 | 61% | Suggestive |
| 17 | 75% | Moderate evidence |
| 18 | 87% | Strong evidence |
| 19 | 95% | Very strong |

**Critical insight:** To have 80%+ confidence that true agreement exceeds 75%, you need at least **18/20 (90%)** observed agreement.

### 4. Bootstrap with care

For n=20:
- **Use BCa (Bias-Corrected and Accelerated)** method
- **Avoid basic percentile bootstrap** - too narrow for small samples
- **Use 5000+ resamples**

### 5. What can you actually detect?

With n=20, you can answer:
- "Is agreement **clearly above** 75%?" (need ~90%+ observed)
- "Is agreement **clearly below** 75%?" (need ~55%- observed)

You **cannot** reliably detect borderline cases (75% vs 80%).

---

## Recommended workflow

### Phase 1: Preparation
1. Define rating criteria clearly (what is "valuable" vs "noise"?)
2. Have researchers rate 3-5 examples together to calibrate
3. Identify edge cases and ambiguities

### Phase 2: Data collection
4. Collect 20+ human ratings on **fresh** critiques (not used for calibration)
5. Ensure diversity across topics/papers/critique types
6. Record confidence for each rating

### Phase 3: Analysis
7. Compute percentage agreement + Wilson 95% CI
8. Compute Bayesian P(agreement > 0.75)
9. If using kappa, report with bootstrap CI and interpret cautiously

### Phase 4: Decision
- **P(>0.75) > 80%:** Proceed with confidence
- **P(>0.75) < 30%:** Revise grader
- **Ambiguous:** Collect more samples or accept uncertainty

---

## Reporting template

```
LLM Grader Validation Results
=============================
Sample size: n = 20
Agreement: 17/20 = 85%
95% CI (Wilson): [65%, 95%]

Bayesian analysis (uniform prior):
  P(true agreement > 75%) = 75.4%
  Posterior mean: 81.8%
  95% credible interval: [60%, 94%]

Interpretation:
  Observed agreement exceeds the 75% threshold. However,
  with only 20 samples, there is 25% probability that
  true agreement is below 75%. Recommend collecting
  additional validation samples before full deployment.
```

---

## Sample size planning for future

If you can collect more samples later:

| n | CI width (p̂=0.80) | P(>0.75) if observe 80% |
|---|-------------------|------------------------|
| 20 | 34 pp | 61% |
| 30 | 27 pp | 70% |
| 50 | 21 pp | 80% |
| 100 | 15 pp | 92% |

**Recommendation:** Aim for n=50 eventually for moderate confidence.

---

## Common pitfalls to avoid

### Statistical
1. Treating CIs as "95% chance true value is here" (frequentist misinterpretation)
2. Ignoring base rate of "valuable" critiques
3. Choosing statistical method after seeing data (p-hacking)

### Methodological
1. **Using calibration data for validation** - if you tuned on examples, can't use them to evaluate
2. Non-random sample selection
3. Rater drift over time

### Interpretation
1. Overconfidence from n=20 - any conclusion is provisional
2. Binary "passed/failed" thinking - report uncertainty
3. Assuming stability across future samples

---

## Quick reference: Python code

```python
from scipy.stats import beta

# Observe k agreements out of n
k, n = 17, 20

# Posterior: Beta(k+1, n-k+1) with uniform prior
alpha_post = k + 1
beta_post = n - k + 1

# P(agreement > 0.75)
prob_exceeds = 1 - beta.cdf(0.75, alpha_post, beta_post)
print(f"P(true agreement > 75%) = {prob_exceeds:.1%}")

# 95% credible interval
ci = (beta.ppf(0.025, alpha_post, beta_post),
      beta.ppf(0.975, alpha_post, beta_post))
print(f"95% CI: [{ci[0]:.1%}, {ci[1]:.1%}]")
```

---

## Key references

1. **McHugh (2012)** - "Interrater reliability: the kappa statistic"
2. **Krippendorff (2011)** - "Computing Krippendorff's Alpha-Reliability"
3. **Wilson score interval** - Best CI method for small-sample proportions
4. **Efron (1987)** - "Better bootstrap confidence intervals" (BCa method)
