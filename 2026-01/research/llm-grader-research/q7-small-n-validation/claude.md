# Statistical validation with small sample sizes for LLM graders

**Research question:** What statistical approaches are appropriate for validating an LLM grader when you only have ~20 human-rated samples?

**Context:** Building an LLM grader to evaluate AI-generated critiques of research papers for Forethought Research. Constraint: ~20 human-rated critiques available for validation. Success criterion: >75% agreement with researchers.

---

## Executive summary

Validating an LLM grader with only 20 samples is statistically challenging but not impossible. The key insights are:

1. **Use the right metric:** Simple percentage agreement with Wilson score confidence intervals is more appropriate than Cohen's kappa for this sample size. Kappa is unstable with n<60-80.

2. **Expect wide confidence intervals:** With n=20 and 75% observed agreement, your 95% CI will be approximately 53-89%. You cannot make precise claims.

3. **Consider Bayesian framing:** A Beta-Binomial model lets you quantify "what is the probability agreement exceeds 75%?" rather than just point estimates.

4. **Bootstrap with care:** BCa bootstrap can help, but percentile intervals are too narrow for n=20. Use at least 2000-5000 resamples.

5. **Reframe the question:** With n=20, you can detect "clearly above 75%" or "clearly below 75%" but not borderline cases. Plan for iterative validation.

---

## 1. Choosing the right agreement metric

### 1.1 Simple percentage agreement (recommended for n=20)

For small samples, simple percentage agreement is often more appropriate than chance-corrected measures:

```
Agreement = (number of matches) / (total comparisons)
```

**Why it works for your case:**
- Transparent and interpretable
- Stable with small samples
- Your threshold (75%) is already defined in these terms
- Avoids kappa paradoxes (see Section 1.3)

### 1.2 Cohen's kappa (use with extreme caution)

Cohen's kappa corrects for chance agreement:

```
κ = (p_o - p_e) / (1 - p_e)

where:
  p_o = observed agreement
  p_e = expected agreement by chance
```

**Interpretation thresholds** (Landis & Koch, 1977):
- 0.81-1.00: Almost perfect
- 0.61-0.80: Substantial
- 0.41-0.60: Moderate
- 0.21-0.40: Fair
- 0.00-0.20: Slight

**Critical problem for n=20:** Studies show kappa estimates are highly unstable below n=60-80. The minimum sample size formula 2k^2 (where k = number of categories) suggests n=8 minimum for binary classification, but this is a floor for *validity*, not *reliability* of estimates.

> "It has been shown that such formulae underestimate the variance in small samples (<80), so that any results below 80 should be treated with great caution." ([Guidelines for Cohen's Kappa Sample Size](https://www.researchgate.net/publication/320148141_Guidelines_of_the_minimum_sample_size_requirements_for_Cohen's_Kappa))

### 1.3 The kappa paradoxes

Two issues make kappa problematic for small samples with imbalanced classes:

1. **Prevalence paradox:** When one category dominates, kappa can be low despite high agreement
2. **Bias paradox:** When raters have different marginal distributions, kappa is artificially reduced

**Example:** If 18/20 critiques are truly "valuable" and you both agree on all of them, but split on the 2 "noise" items, your agreement is 90% but kappa might be only 0.4-0.6 due to prevalence effects.

**Alternatives:**
- **Gwet's AC1:** Addresses prevalence paradox, but [some research suggests](https://pmc.ncbi.nlm.nih.gov/articles/PMC5978587/) it performs poorly in binary tasks
- **PABAK (Prevalence-Adjusted, Bias-Adjusted Kappa):** Byrt et al.'s correction, but adds complexity

### 1.4 Krippendorff's alpha (alternative)

Krippendorff's alpha is more flexible and [self-adjusts for small samples](https://www.asc.upenn.edu/sites/default/files/2021-03/Computing%20Krippendorff's%20Alpha-Reliability.pdf):

```
α = 1 - (observed disagreement / expected disagreement)
```

**Advantages:**
- Works with any number of raters
- Handles missing data
- Adjusts for sample size
- Available in R (`krippendorffsalpha` package)

**Thresholds:** α ≥ 0.80 is reliable; 0.667 ≤ α < 0.80 allows tentative conclusions.

### 1.5 Recommendation for Forethought

**Primary metric:** Percentage agreement with Wilson CI
**Secondary metric:** Krippendorff's alpha with bootstrap CI
**Avoid:** Cohen's kappa as primary metric at n=20

---

## 2. Confidence intervals for small samples

### 2.1 The Wilson score interval (recommended)

For proportions (like agreement rate), the [Wilson score interval](https://www.econometrics.blog/post/the-wilson-confidence-interval-for-a-proportion/) outperforms alternatives for small samples:

```
p̃ = (p̂ + z²/2n) / (1 + z²/n)

CI = p̃ ± z·√(p̂(1-p̂)/n + z²/4n²) / (1 + z²/n)

where:
  p̂ = observed proportion (agreement rate)
  n = sample size
  z = z-score for desired confidence (1.96 for 95%)
```

**Worked example for your case:**

If you observe 15/20 agreements (75%):
- p̂ = 0.75
- n = 20
- z = 1.96

```
z²/n = 3.84/20 = 0.192
p̃ = (0.75 + 0.096) / (1.192) = 0.71

SE_term = √(0.75×0.25/20 + 3.84/1600) / 1.192 = 0.095

95% CI = 0.71 ± 1.96×0.095 = (0.53, 0.89)
```

**Interpretation:** With 15/20 agreements, you're 95% confident the true agreement rate is between 53% and 89%.

### 2.2 Exact binomial (Clopper-Pearson) interval

The [exact method](https://en.wikipedia.org/wiki/Binomial_proportion_confidence_interval#Clopper%E2%80%93Pearson_interval) is conservative but guaranteed to have at least 95% coverage:

```python
# Python
from scipy.stats import binom
lower = binom.ppf(0.025, n, p) / n
upper = binom.ppf(0.975, n, p) / n

# R
binom.test(15, 20, conf.level=0.95)$conf.int
```

For 15/20: exact 95% CI = (0.509, 0.913)

**Trade-off:** Exact intervals are wider (more conservative) than Wilson. Wilson is preferred for general use; exact is useful when you need guaranteed coverage.

### 2.3 Confidence intervals for kappa

If you do use kappa, the asymptotic standard error is:

```
SE(κ) = √(p_o(1-p_o) / (n(1-p_e)²))
```

But this underestimates variance for small samples. Use bootstrap instead (Section 4).

### 2.4 Width expectations at n=20

| Observed agreement | Wilson 95% CI | CI Width |
|-------------------|---------------|----------|
| 60% (12/20) | (0.39, 0.78) | 39 pp |
| 70% (14/20) | (0.48, 0.86) | 38 pp |
| 75% (15/20) | (0.53, 0.89) | 36 pp |
| 80% (16/20) | (0.58, 0.92) | 34 pp |
| 85% (17/20) | (0.64, 0.95) | 31 pp |
| 90% (18/20) | (0.70, 0.97) | 27 pp |

**Key insight:** Even with 90% observed agreement, the lower bound of your CI is only 70%. You need very high observed agreement to be confident you've exceeded 75%.

---

## 3. Power analysis: What can you detect with n=20?

### 3.1 The fundamental limit

Power analysis tells you the minimum effect size you can reliably detect. With n=20, your power is limited.

**One-sided test against 75% threshold:**

To test H₀: p ≤ 0.75 vs H₁: p > 0.75 at α=0.05:

| True agreement | Power (n=20) |
|---------------|--------------|
| 80% | 0.22 |
| 85% | 0.44 |
| 90% | 0.70 |
| 95% | 0.92 |

**Interpretation:** If true agreement is 85%, you have only 44% chance of concluding it exceeds 75% at n=20. You need ~95% true agreement to have 90%+ power.

### 3.2 What effect size is detectable?

Using the [kappaSize R package](https://cran.r-project.org/web/packages/kappaSize/kappaSize.pdf) and similar tools:

With n=20, 80% power, α=0.05 (one-sided), you can detect:
- A difference of ~25 percentage points from null
- Distinguishing 75% vs 95% agreement is feasible
- Distinguishing 75% vs 85% is unreliable

### 3.3 Practical implication

With n=20, you can answer:
- "Is agreement clearly above 75%?" (need observed ~90%+)
- "Is agreement clearly below 75%?" (need observed ~55%-)
- You **cannot** answer: "Is agreement exactly at or slightly above 75%?"

**Recommendation:** Reframe your validation as "demonstrate clearly exceeds threshold" rather than "prove meets threshold exactly."

---

## 4. Bootstrap resampling methods

### 4.1 When to use bootstrap

Bootstrap is valuable when:
- Analytical formulas don't exist (complex metrics)
- You want to avoid distributional assumptions
- You need CIs for non-standard statistics (e.g., F1)

### 4.2 Choosing the right bootstrap method

For n=20, **avoid basic percentile bootstrap**---it produces intervals that are [too narrow for small samples](https://pmc.ncbi.nlm.nih.gov/articles/PMC4283293/).

**Recommended: BCa (Bias-Corrected and Accelerated)**

The [BCa method](https://blogs.sas.com/content/iml/2017/07/12/bootstrap-bca-interval.html) adjusts for:
- Bias in the bootstrap distribution
- Skewness (acceleration)

```python
# Python implementation
from scipy.stats import bootstrap
import numpy as np

def agreement_rate(x):
    return np.mean(x)

data = np.array([1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0])  # 15/20
result = bootstrap((data,), agreement_rate, n_resamples=5000,
                   confidence_level=0.95, method='BCa')
print(f"95% CI: ({result.confidence_interval.low:.3f}, {result.confidence_interval.high:.3f})")
```

```r
# R implementation
library(boot)

agreement_stat <- function(data, indices) {
  mean(data[indices])
}

data <- c(rep(1, 15), rep(0, 5))  # 15/20
boot_result <- boot(data, agreement_stat, R = 5000)
boot.ci(boot_result, type = "bca")
```

### 4.3 How many bootstrap samples?

| Purpose | Minimum resamples | Recommended |
|---------|------------------|-------------|
| SE estimation | 200 | 1000 |
| 95% CI | 1000 | 5000 |
| 99% CI | 5000 | 10000 |

For BCa at n=20, use **at least 5000 resamples** due to the small original sample.

### 4.4 Bootstrap pitfalls for n=20

1. **Representativeness:** Bootstrap assumes your sample represents the population. With n=20, this is a strong assumption.

2. **Discrete data:** With only 20 binary outcomes, bootstrap distributions are lumpy. Many resamples will give the same statistic.

3. **Edge cases:** If observed agreement is 100% (20/20), bootstrap can't capture uncertainty below 100%.

### 4.5 Bootstrap for kappa

```python
def compute_kappa(human, llm):
    # Compute Cohen's kappa from paired ratings
    n = len(human)
    po = np.mean(human == llm)
    pe = (np.mean(human) * np.mean(llm) +
          (1 - np.mean(human)) * (1 - np.mean(llm)))
    return (po - pe) / (1 - pe) if pe < 1 else 1.0

def bootstrap_kappa(human, llm, n_boot=5000):
    n = len(human)
    kappas = []
    for _ in range(n_boot):
        idx = np.random.choice(n, n, replace=True)
        kappas.append(compute_kappa(human[idx], llm[idx]))
    return np.percentile(kappas, [2.5, 97.5])
```

---

## 5. Bayesian approaches for small-N inference

Bayesian methods are particularly well-suited to small samples because they:
- Naturally quantify uncertainty
- Can incorporate prior information
- Answer practical questions like "P(agreement > 0.75)"

### 5.1 Beta-Binomial model for agreement rate

The [Beta-Binomial is the canonical Bayesian model](https://www.bayesrulesbook.com/chapter-3) for proportions:

**Prior:** p ~ Beta(α, β)
**Likelihood:** Y | p ~ Binomial(n, p)
**Posterior:** p | Y ~ Beta(α + Y, β + n - Y)

### 5.2 Choosing priors

| Prior type | Beta parameters | Interpretation |
|-----------|----------------|----------------|
| Uniform (uninformative) | Beta(1, 1) | "I have no prior belief" |
| Jeffreys (reference) | Beta(0.5, 0.5) | Minimally informative |
| Weakly informative | Beta(2, 2) | "Probably not extreme" |
| Skeptical of 75% | Beta(3, 1) | "Expecting high agreement" |

**Recommendation:** Start with Beta(1, 1) for transparency, then sensitivity-test with Beta(2, 2).

### 5.3 Worked example

**Scenario:** Observe 15/20 agreements. What is P(true agreement > 0.75)?

```python
from scipy.stats import beta

# Prior: Beta(1, 1) = Uniform
alpha_prior, beta_prior = 1, 1

# Data: 15 agreements, 5 disagreements
agreements = 15
disagreements = 5

# Posterior: Beta(16, 6)
alpha_post = alpha_prior + agreements      # 16
beta_post = beta_prior + disagreements     # 6

# P(agreement > 0.75)
prob_exceeds_threshold = 1 - beta.cdf(0.75, alpha_post, beta_post)
print(f"P(agreement > 0.75) = {prob_exceeds_threshold:.3f}")  # ≈ 0.456

# Posterior mean
posterior_mean = alpha_post / (alpha_post + beta_post)
print(f"Posterior mean = {posterior_mean:.3f}")  # ≈ 0.727

# 95% credible interval
ci_lower = beta.ppf(0.025, alpha_post, beta_post)
ci_upper = beta.ppf(0.975, alpha_post, beta_post)
print(f"95% CI = ({ci_lower:.3f}, {ci_upper:.3f})")  # ≈ (0.514, 0.890)
```

**Results for 15/20:**
- P(agreement > 0.75) = 45.6%
- Posterior mean = 72.7%
- 95% credible interval = (51.4%, 89.0%)

**Interpretation:** Despite observing 75% agreement, there's only ~46% probability that true agreement exceeds 75%. This is *worse than a coin flip*.

### 5.4 How many agreements needed?

| Agreements (out of 20) | P(true > 0.75) | Verdict |
|-----------------------|----------------|---------|
| 14 | 0.31 | Weak evidence |
| 15 | 0.46 | Inconclusive |
| 16 | 0.61 | Suggestive |
| 17 | 0.75 | Moderate evidence |
| 18 | 0.87 | Strong evidence |
| 19 | 0.95 | Very strong |
| 20 | 0.99 | Near-certain |

**Key insight:** To have 80%+ confidence that true agreement exceeds 75%, you need at least 18/20 (90%) observed agreement.

### 5.5 Bayesian kappa (advanced)

[Bayesian approaches to kappa](https://pubmed.ncbi.nlm.nih.gov/34448633/) exist but are more complex:

- Place priors on the cell probabilities of the 2x2 table
- Use MCMC (Stan, PyMC) to sample posterior
- Compute kappa as a derived quantity

This is overkill for n=20---stick to the Beta-Binomial for simple agreement.

---

## 6. Calibration set vs. test set trade-offs

### 6.1 The dilemma

With only 20 samples, how should you split them?

**Option A: All 20 as test set**
- Pro: Maximum statistical power for validation
- Con: No data to tune prompts/thresholds

**Option B: Split (e.g., 10 calibration + 10 test)**
- Pro: Can tune grader before final evaluation
- Con: CIs become enormous (n=10 makes everything worse)

**Option C: Iterative/adaptive approach**
- Use Leave-One-Out Cross-Validation
- Or treat as sequential test (Section 7)

### 6.2 Leave-One-Out Cross-Validation (LOOCV)

[LOOCV](https://machinelearningmastery.com/loocv-for-evaluating-machine-learning-algorithms/) is ideal for small samples:

1. Train/calibrate on 19 samples
2. Test on the 1 held-out sample
3. Repeat 20 times
4. Report average performance

**Advantages:**
- Uses nearly all data for both calibration and testing
- Unbiased estimate of generalization
- Deterministic (no random split variance)

**Implementation:**
```python
def loocv_agreement(human_ratings, llm_ratings, calibration_fn):
    """
    calibration_fn: function that takes (train_human, train_llm) and
                    returns a calibrated grader
    """
    n = len(human_ratings)
    correct = 0

    for i in range(n):
        # Leave one out
        train_idx = [j for j in range(n) if j != i]

        # Calibrate on n-1 samples
        calibrated_grader = calibration_fn(
            human_ratings[train_idx],
            llm_ratings[train_idx]
        )

        # Test on held-out sample
        prediction = calibrated_grader(llm_ratings[i])
        if prediction == human_ratings[i]:
            correct += 1

    return correct / n
```

### 6.3 Recommendation

**For prompt development (before validation):**
- Use 5-10 examples for initial prompt design
- These become your "calibration set"
- Document what you learned from them

**For final validation:**
- Collect 20 *fresh* samples not used for calibration
- Use all 20 as test set
- If fresh samples are impossible, use LOOCV on the full 20

**Critical:** Never report test performance on data you used to make decisions about the grader.

---

## 7. Sequential testing approaches

### 7.1 The appeal of sequential testing

Rather than committing to n=20 upfront, sequential testing lets you:
- Stop early if agreement is clearly above threshold
- Stop early if agreement is clearly below threshold
- Continue if results are ambiguous

### 7.2 The peeking problem

**Warning:** You cannot simply test after each sample and stop when p < 0.05. This inflates false positive rates from 5% to [17-30%](https://www.statsig.com/perspectives/sequential-testing-ab-peek).

### 7.3 Group sequential methods

[Established approaches](https://pmc.ncbi.nlm.nih.gov/articles/PMC10260346/) adjust significance thresholds:

**O'Brien-Fleming bounds:**
- Very strict early (hard to stop)
- Lenient at final analysis
- Preserves overall α = 0.05

**Pocock bounds:**
- Constant threshold (~0.016 for two-sided)
- Easier to stop early
- Slightly less power at end

### 7.4 Practical sequential design for n=20

If you can collect samples one at a time:

**Interim analysis schedule:**
- Look 1: After n=10 (α₁ = 0.005)
- Look 2: After n=15 (α₂ = 0.014)
- Look 3: After n=20 (α₃ = 0.045)

**Stopping rules (one-sided, testing H₀: p ≤ 0.75):**

| Look | n | Stop if agreements ≥ | Stop if agreements ≤ |
|------|---|---------------------|---------------------|
| 1 | 10 | 10 (futility only) | 4 (futile) |
| 2 | 15 | 14 | 7 |
| 3 | 20 | 17 | 12 |

**Interpretation:**
- After 10 samples: Only stop if failing badly (≤4 agreements)
- After 15 samples: Stop if 14+ (looking good) or ≤7 (failing)
- After 20 samples: Conclude based on thresholds

### 7.5 Bayesian sequential updating

A simpler approach: after each sample, update your Beta-Binomial posterior and check P(p > 0.75):

```python
def sequential_validation(samples, threshold=0.75,
                          stop_prob_high=0.95, stop_prob_low=0.10):
    """
    samples: list of 0/1 (disagreement/agreement)
    Returns: (conclusion, n_used, final_probability)
    """
    alpha, beta = 1, 1  # Uniform prior

    for i, agreement in enumerate(samples, 1):
        alpha += agreement
        beta += (1 - agreement)

        prob_above = 1 - beta_dist.cdf(threshold, alpha, beta)

        if prob_above >= stop_prob_high:
            return ("PASS", i, prob_above)
        if prob_above <= stop_prob_low:
            return ("FAIL", i, prob_above)

    # Reached end without clear conclusion
    final_prob = 1 - beta_dist.cdf(threshold, alpha, beta)
    if final_prob > 0.5:
        return ("PASS (weak)", len(samples), final_prob)
    else:
        return ("FAIL (weak)", len(samples), final_prob)
```

**Advantages:**
- No need for pre-specified stopping rules
- Natural probability statements
- Can stop at any point

**Disadvantages:**
- No formal control of error rates
- Harder to justify to frequentist reviewers

---

## 8. Pitfalls and common mistakes

### 8.1 Statistical pitfalls

1. **Treating CIs as magic:** A 95% CI doesn't mean "95% chance the true value is in this range" (frequentist interpretation). It means "this procedure captures the true value 95% of the time."

2. **Ignoring the prevalence of "valuable" critiques:** If 90% of critiques are truly valuable, 90% agreement is unimpressive (random guessing gets 82%).

3. **p-hacking via method selection:** Choosing the statistical method that gives the best result after seeing data inflates false positives.

4. **Confusing statistical and practical significance:** Even if p < 0.05, the effect might be too small to matter. With n=20, this is less of a problem (you can only detect large effects).

5. **Ignoring multiple comparisons:** If you test 5 different grader configurations, your family-wise error rate inflates.

### 8.2 Methodological pitfalls

1. **Using calibration data for validation:** If you tuned your grader on examples, you can't use those examples to evaluate it.

2. **Non-random sample selection:** If your 20 samples are "easy" cases or specially selected, results won't generalize.

3. **Temporal dependence:** If your 20 samples are sequential and the grader or researchers improved over time, i.i.d. assumptions fail.

4. **Rater drift:** Human ratings may shift over time. Consider having researchers re-rate early samples at the end.

5. **Missing the base rate:** If humans themselves only agree 80% of the time with each other, LLM-human agreement of 75% might be excellent.

### 8.3 Interpretation pitfalls

1. **Overconfidence from n=20:** Any conclusion from 20 samples is provisional. Plan for more validation.

2. **Binary thinking:** "Passed" vs "failed" validation obscures uncertainty. Report confidence/credible intervals.

3. **Ignoring the practical threshold:** >75% was chosen for a reason. If you observe 74%, that's practically equivalent to 76%.

4. **Assuming stability:** Agreement on these 20 samples doesn't guarantee agreement on future samples with different topics/styles.

---

## 9. Practical recommendations for Forethought

### 9.1 Recommended workflow

**Phase 1: Preparation**
1. Define rating criteria clearly (what is "valuable" vs "noise"?)
2. Have researchers rate 3-5 examples together to calibrate
3. Identify any edge cases or ambiguities in criteria

**Phase 2: Data collection**
4. Collect 20+ human ratings on fresh critiques
5. Ensure diversity across topics/papers/critique types
6. Record confidence for each rating (useful for analysis)

**Phase 3: Analysis**
7. Compute simple agreement and Wilson 95% CI
8. Compute Bayesian P(agreement > 0.75)
9. If using kappa, report with bootstrap CI and interpret cautiously

**Phase 4: Decision**
10. If P(agreement > 0.75) > 0.80: Proceed with confidence
11. If P(agreement > 0.75) < 0.30: Revise grader
12. If ambiguous: Collect more samples or accept uncertainty

### 9.2 Reporting template

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

### 9.3 Sample size for future validation

If you can collect more samples later, here's what you gain:

| n | CI width (p̂=0.80) | P(>0.75) if observe 80% |
|---|-------------------|------------------------|
| 20 | 34 pp | 61% |
| 30 | 27 pp | 70% |
| 50 | 21 pp | 80% |
| 100 | 15 pp | 92% |
| 200 | 10 pp | 99% |

**Recommendation:** Aim for n=50 eventually to have moderate confidence in borderline results.

---

## 10. Key references and resources

### Academic papers

1. **McHugh (2012)** - "Interrater reliability: the kappa statistic" - Clear introduction to kappa interpretation. [PMC](https://pmc.ncbi.nlm.nih.gov/articles/PMC3900052/)

2. **Gwet (2014)** - *Handbook of Inter-Rater Reliability* - Comprehensive treatment of agreement statistics.

3. **Krippendorff (2011)** - "Computing Krippendorff's Alpha-Reliability" - Authoritative guide to alpha. [PDF](https://www.asc.upenn.edu/sites/default/files/2021-03/Computing%20Krippendorff's%20Alpha-Reliability.pdf)

4. **Koo & Li (2016)** - "A Guideline of Selecting and Reporting Intraclass Correlation Coefficients for Reliability Research" - Best practices for ICC. [PMC](https://pmc.ncbi.nlm.nih.gov/articles/PMC4913118/)

5. **Efron (1987)** - "Better bootstrap confidence intervals" - Original BCa paper. JASA 82:171-200.

6. **Takahashi et al. (2022)** - "Confidence interval for micro-averaged F1 and macro-averaged F1 scores" - F1 CI methods. [PMC](https://pmc.ncbi.nlm.nih.gov/articles/PMC8936911/)

### Software and tools

1. **R packages:**
   - `irr` - Inter-rater reliability (kappa, ICC)
   - `krippendorffsalpha` - Alpha with bootstrap CI
   - `kappaSize` - Power analysis for kappa studies
   - `boot` - General bootstrap methods

2. **Python:**
   - `sklearn.metrics.cohen_kappa_score`
   - `scipy.stats.bootstrap` (BCa method)
   - `scipy.stats.beta` (Bayesian analysis)
   - `statsmodels.stats.proportion.proportion_confint`

3. **Web calculators:**
   - [Arifin's Sample Size Calculator](https://wnarifin.github.io/ssc/sskappa.html)
   - [GraphPad proportion CI calculator](https://www.graphpad.com/quickcalcs/confInterval1/)

### Relevant LLM evaluation literature

1. **Zheng et al. (2024)** - "Judging LLM-as-a-Judge with MT-Bench and Chatbot Arena" - GPT-4 agrees with humans ~80%

2. **Shankar et al. (2024)** - "Who Validates the Validators?" - Criteria drift in LLM evaluation. [arXiv](https://arxiv.org/abs/2404.12272)

3. **Chen et al. (2024)** - "Sample-Efficient Human Evaluation of Large Language Models via Maximum Discrepancy Competition" - MAD method for efficient evaluation. [arXiv](https://arxiv.org/abs/2404.08008)

---

## Appendix: Quick reference formulas

### Wilson score interval
```
z = 1.96 (for 95% CI)
p̃ = (p̂ + z²/2n) / (1 + z²/n)
CI = p̃ ± z·√(p̂(1-p̂)/n + z²/4n²) / (1 + z²/n)
```

### Cohen's kappa
```
κ = (p_o - p_e) / (1 - p_e)
p_o = (a + d) / n  [observed agreement]
p_e = ((a+b)(a+c) + (c+d)(b+d)) / n²  [expected by chance]
```

### Beta-Binomial posterior
```
Prior: p ~ Beta(α, β)
Data: k agreements out of n
Posterior: p ~ Beta(α + k, β + n - k)
P(p > threshold) = 1 - BetaCDF(threshold, α + k, β + n - k)
```

### Bootstrap BCa adjustment
```
z_0 = Φ⁻¹(proportion of bootstrap estimates < observed)
a = skewness adjustment from jackknife
α_1 = Φ(z_0 + (z_0 + z_α)/(1 - a(z_0 + z_α)))
α_2 = Φ(z_0 + (z_0 + z_{1-α})/(1 - a(z_0 + z_{1-α})))
CI = (θ*_{α_1}, θ*_{α_2})
```

---

*Report generated: 2026-01-20*
*Model: Claude Opus 4.5*
