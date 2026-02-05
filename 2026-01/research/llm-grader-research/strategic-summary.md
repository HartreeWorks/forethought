# Strategic Summary: LLM Grader Research

**Generated:** 2026-01-20

---

## The Core Problem You're Solving

You want to test different prompts for generating critiques of Forethought papers and get a reliable signal about whether you're making progress. The bottleneck is that human evaluation is expensive, so you need an LLM grader to give you that signal.

## The Central Insight

**An LLM grader can work, but only if you build it correctly.** The research is unambiguous: naive "LLM-as-a-Judge" prompting (just asking "is this critique good?") fails. But structured approaches achieve 75-85% agreement with human judgment—which is often comparable to human-human agreement.

---

## Critical Findings by Question

### Q1: Calibration Techniques — *How to make the grader reliable*

**Crucial insight:** Holistic single-score evaluation is unreliable. Rubric decomposition (scoring multiple dimensions separately) achieves **r=0.78** correlation with experts vs **r=0.35** for holistic scoring.

**What this means for you:**
- Don't ask "rate this critique 1-10"
- Instead, score separately on: Logical Soundness, Relevance to Core Claims, Actionability, Clarity
- Use the Toulmin model (Claim → Warrant → Backing) to force structured analysis before scoring

### Q2: Systematic Biases — *What will sabotage your grader*

**Crucial insight:** 12+ documented biases exist. The most dangerous for your case:
- **Sycophancy** — LLMs agree with confident-sounding critiques even when wrong
- **Verbosity bias** — longer critiques get rated higher regardless of quality
- **Self-preference** — if you use the same model to generate and evaluate, it will prefer its own outputs

**What this means for you:**
- Use different model families for generation vs evaluation
- Swap presentation order and average (mitigates position bias)
- Add explicit anti-sycophancy instructions: "Do not assume the critique is correct just because it is confident"

### Q3: Multi-Model Consensus — *How to improve reliability cheaply*

**Crucial insight:** A panel of 3 smaller models (Claude Sonnet, GPT-4o-mini, Gemini Flash) outperforms single GPT-4 at **7x lower cost** and gives 10-20% better human correlation.

**What this means for you:**
- Use score averaging (not majority voting) across 3 diverse models
- Standard deviation across models = disagreement signal → flag for human review
- This is your primary reliability mechanism

### Q4: Multi-Persona Prompting — *Better for generation than evaluation*

**Crucial insight:** Personas increase diversity but not necessarily quality. Expert personas **do not improve factual accuracy**. The Jekyll & Hyde approach (persona + neutral baseline, then select best) gives +9.98% accuracy.

**What this means for you:**
- For **generating** critiques: use 3-5 expert personas (philosopher, economist, skeptic) + always include a neutral baseline
- For the **grader**: be cautious—persona-based evaluation can decrease agreement with humans in specialised domains

### Q5: Adversarial Prompts — *"Be critical" backfires*

**Crucial insight:** Generic "be critical" prompts **increase hallucinations and nitpicks**. The primary failure mode is overcriticism, not undercriticism. Multi-agent devil's advocate (structured debate) works; single-agent adversarial does not.

**What this means for you:**
- Don't prompt your grader to "look for problems"
- Use structured rubric-based evaluation instead
- If using adversarial approaches, use multi-agent debate with distinct roles (Scorer, Critic, Judge)

### Q6: Novelty Detection — *The hard limit*

**Crucial insight:** LLM novelty detection has **near-zero correlation** with expert judgment. LLMs cannot reliably distinguish genuine insight from sophisticated recombination of existing ideas.

**What this means for you:**
- **Accept this limitation.** Don't try to automate novelty scoring.
- Use the grader to flag potential novelty ("contains claim not found in paper") for human review
- Novelty will require Fin or other researchers to judge

### Q7: Small-N Validation — *How to know if it's working*

**Crucial insight:** With n=20, you need **90% observed agreement** to be 80%+ confident that true agreement exceeds 75%. With 75% observed, you're at coin-flip confidence.

**What this means for you:**
- Use Wilson confidence intervals (not Cohen's kappa, which is unstable at small n)
- Use Bayesian Beta-Binomial to quantify P(agreement > 75%)
- If you observe 17/20 agreement, you have ~75% confidence the true rate is >75%. If 18/20, ~87% confidence.

### Q8: Evaluation Frameworks — *What tools to use*

**Crucial insight:** Promptfoo + custom rubrics is sufficient. More sophisticated frameworks add complexity without proportional benefit for your use case.

**What this means for you:**
- Start simple: Promptfoo YAML configs with your Toulmin-based rubric
- Don't overbuild infrastructure before you have a working grader

---

## The Upshot: What You Should Actually Do

### The Architecture

```
Critique to Evaluate
        │
        ├─────────────────────────────────┐
        │                │                │
        ▼                ▼                ▼
   Claude Sonnet    GPT-4o-mini     Gemini Flash
   (Toulmin eval)   (Toulmin eval)  (Toulmin eval)
        │                │                │
        └────────────────┼────────────────┘
                         │
                         ▼
               Score Averaging
          + Std Dev as Disagreement
                         │
              ┌──────────┴──────────┐
              ▼                     ▼
      Low variance:           High variance:
      Accept score            Human review
```

### The Grader Prompt (Toulmin-based)

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

### The Validation Process

1. Have Fin rate 20 critiques as "valuable" vs "noise"
2. Run your grader on those 20
3. Compute agreement + Wilson CI
4. Compute Bayesian P(agreement > 75%)
5. If P < 80%, adjust thresholds/prompts and iterate

### What the Grader Can and Cannot Do

**Can do reliably:**
- Filter obvious noise (generic, off-topic critiques)
- Identify structural and logical issues
- Compare critiques on well-defined dimensions
- Flag high-uncertainty cases for human review
- **Reduce researcher evaluation burden by 60-80%**

**Cannot do reliably:**
- Detect genuine novelty vs sophisticated recombination
- Evaluate cutting-edge philosophical arguments
- Assess domain-specific expertise in longtermism
- Replace human judgment on high-stakes decisions

---

## Practical Next Steps

1. **Create the grader prompt** using the Toulmin template above
2. **Set up 3-model ensemble** (Promptfoo makes this easy)
3. **Get 20 human-rated critiques** from Fin
4. **Run baseline validation** and compute statistics
5. **Iterate on prompts/thresholds** based on results
6. **Accept novelty limitation** — flag for human review, don't score

The research gives you a clear path. The grader won't be perfect, but it can be good enough to tell you whether your critique-generation prompts are getting better or worse—which is exactly what you need for systematic improvement.

---

## Key Papers (Prioritised Reading)

1. **[A Survey on LLM-as-a-Judge](https://arxiv.org/abs/2411.15594)** — Comprehensive overview
2. **[DeCE: Beyond Pointwise Scores](https://arxiv.org/html/2509.16093)** — Rubric decomposition (r=0.78)
3. **[Replacing Judges with Juries (PoLL)](https://arxiv.org/abs/2404.18796)** — Multi-model ensemble, 7x cost reduction
4. **[CriticGPT](https://cdn.openai.com/llm-critics-help-catch-llm-bugs-paper.pdf)** — Overcriticism failure mode
5. **[Can LLMs Generate Novel Research Ideas?](https://arxiv.org/abs/2409.04109)** — Novelty limitations
6. **[Justice or Prejudice?](https://arxiv.org/abs/2410.02736)** — 12 biases + CALM framework
