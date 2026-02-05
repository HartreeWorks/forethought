# Research question: Statistical validation with small sample sizes

## Question

What statistical approaches are appropriate for validating an LLM grader when you only have ~20 human-rated samples?

## Context

We are building a system to evaluate AI-generated critiques of research papers for **Forethought Research**, a nonprofit focused on the transition to superintelligent AI. Their research sits at the intersection of philosophy and economics — examining AI governance, post-AGI economics, digital mind rights, and longtermist macrostrategy.

We have a constraint: human researcher time is expensive, so we can only get ~20 human-rated critiques for validation. We need to determine whether our LLM grader meets our success criterion (>75% agreement with researchers) with appropriate statistical rigor.

## What we want to know

1. **Appropriate metrics:** What metrics should we use? (accuracy, Cohen's kappa, Pearson/Spearman correlation, F1, etc.)

2. **Confidence intervals:** How do we calculate confidence intervals for agreement metrics with n≈20?

3. **Bootstrap methods:** Is bootstrap resampling appropriate? How many resamples? What are the pitfalls?

4. **Bayesian approaches:** Are Bayesian methods better suited for small-N inference? What priors are appropriate?

5. **Calibration sets:** Can we use the small sample as a calibration set rather than a test set? What are the trade-offs?

6. **Power analysis:** What effect size can we reliably detect with n≈20?

7. **Sequential testing:** Can we use sequential testing to stop early if agreement is clearly above/below threshold?

## Desired output

- Summary of statistical approaches appropriate for small-N validation
- Specific formulas and methods we should use
- Worked examples if possible
- Pitfalls and common mistakes
- Practical recommendations for our use case
- Key papers and resources (especially from psychometrics, inter-rater reliability)
