# Synthesis: Adversarial and devil's advocate prompting

**Question:** Do adversarial prompts improve LLM critique quality?

**Sources:** Claude Opus 4.5, OpenAI o3-deep-research (Gemini incomplete due to rate limits)

---

## Key findings

### 1. Mixed but cautiously positive for specific techniques

Adversarial prompting is **not a silver bullet**, but specific implementations show promise:

| Technique | Evidence | Effect |
|-----------|----------|--------|
| Multi-agent devil's advocate (DEBATE) | Strong (ACL 2024) | +16% Spearman on NLG evaluation |
| Rubric-based structured critique | Strong | Better than free-form adversarial |
| Single "be critical" prompt | Negative | Increases hallucinations and nitpicks |
| Self-critique without external feedback | Negative | Amplifies self-bias |

### 2. Overcriticism is the primary failure mode

**Critical finding from CriticGPT (OpenAI, 2024):**
- Models catch ~85% of bugs vs ~25% for humans
- BUT: Rate of nitpicks and hallucinated bugs is "much higher for models than for humans"
- There is a configurable precision-recall tradeoff

**Implication:** More aggressive adversarial prompting increases BOTH bug detection AND hallucinations/nitpicks.

### 3. Structured rubric-based > free-form adversarial

**LLM-Rubric (ACL 2024):**
- Multidimensional rubrics with calibrated scoring predict human judgments better than unstructured critique
- DeCE achieved r=0.78 vs r=0.35 for holistic scoring

**Recommendation:** Use structured rubric decomposition rather than general "be critical" prompts.

### 4. Sycophancy and self-bias are pervasive

From Anthropic's sycophancy research:
- LLMs prefer responses that match user views
- Optimizing against preference models sometimes sacrifices truthfulness
- SycEval: Sycophancy observed in 58-62% of cases across models

**Implication:** Asking an LLM to be critical may partially counteract sycophancy, but the underlying tendency remains.

---

## Validated techniques

### Recommended

1. **Multi-agent devil's advocate (DEBATE framework):**
   - Commander, Scorer, and Critic roles
   - Critic provides constructive criticism on Scorer's output
   - Substantially outperformed SOTA on SummEval and TopicalChat

2. **Rubric-based evaluation:**
   - Define specific dimensions: argument quality, evidence use, logical coherence
   - Use Likert scales (1-4 or 1-5) for each dimension
   - Include explicit rubric descriptions for each score level

3. **Human-LLM teams:**
   - CriticGPT + human review hallucinate and nitpick less than models alone
   - Preferred in 63% of cases over ChatGPT baseline

### Not recommended

1. **Single persona adversarial ("be critical"):**
   - Increases nitpicks and hallucinations
   - Use structured multi-dimensional evaluation instead

2. **Multiple rounds of self-critique:**
   - Self-bias amplifies over iterations
   - External feedback is necessary

3. **Expert persona prompting for factual accuracy:**
   - No improvement demonstrated
   - May increase false confidence

---

## Failure modes

1. **Hallucinated problems:** LLMs invent issues that don't exist when prompted to find problems

2. **Nitpicking:** Focus on trivial issues while missing substantive problems

3. **Self-bias reinforcement:** Multi-round self-critique amplifies rather than corrects errors

4. **Sycophantic reversal:** Abandoning correct positions too readily when challenged

5. **Strawmanning disguised as steelmanning:** Weak representations of opposing views presented as strong ones

6. **Overcorrection:** GPT-3.5 may be "overcorrecting for known biases" in ways that introduce new problems

---

## Practical recommendations for Forethought

### For critique generation

1. **Use structured rubric decomposition** rather than free-form adversarial prompting
2. **Define specific critique dimensions:** Logical soundness, novelty, relevance to core premises
3. **Include confidence scores** and filter low-confidence evaluations

### For the grader

1. **Calibrate for precision vs. recall:**
   - For critique quality assessment: favour precision (fewer false positives)
   - Consider two-stage: permissive first pass, then filter for precision

2. **Mitigate sycophancy:**
   - Avoid revealing your own position before critique
   - Use different models for generation vs. evaluation
   - Use external reference points where possible

3. **For philosophical content:**
   - Accept that novelty detection requires human judgment
   - Use LLMs for structural and logical analysis
   - Reserve substantive intellectual critique for human reviewers

---

## Key papers

1. **DEBATE (Kim et al., ACL 2024)** - Multi-agent devil's advocate for NLG evaluation
2. **CriticGPT (McAleese et al., OpenAI, 2024)** - Quantifies hallucinations, nitpicks, human-machine teams
3. **LLM-Rubric (ACL 2024)** - Multidimensional calibrated evaluation
4. **Sycophancy research (Anthropic, 2023)** - Documents causes and mitigation
5. **Self-correction survey (TACL 2024)** - "When Can LLMs Actually Correct Their Own Mistakes?"
