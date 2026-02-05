# Do adversarial prompts improve LLM critique quality?

**Research question:** Do adversarial prompts, devil's advocate instructions, or "steelman then critique" approaches actually improve the quality of LLM-generated critiques?

**Context:** Building an LLM grader to evaluate AI-generated critiques of research papers for Forethought Research (philosophy/economics research on AI governance, post-AGI economics, digital minds, longtermism).

**Model:** Claude Opus 4.5 (WebSearch)
**Date:** 2026-01-20

---

## Executive summary

The evidence on adversarial prompting for LLM critique is **mixed but cautiously positive for specific techniques**. Key findings:

1. **Multi-agent devil's advocate systems** (DEBATE framework) show measurable improvements in NLG evaluation, achieving state-of-the-art on benchmarks like SummEval and TopicalChat.

2. **Adversarial prompting increases comprehensiveness but also increases hallucinations and nitpicks.** OpenAI's CriticGPT found models catch ~85% of bugs vs 25% for humans, but with significantly higher rates of false positives.

3. **Self-critique without external feedback is unreliable.** LLMs exhibit self-bias, systematically overrating their own outputs, which amplifies over multiple refinement rounds.

4. **Structured rubric-based approaches outperform free-form adversarial critique.** LLM-Rubric (ACL 2024) demonstrates that multidimensional, calibrated rubrics predict human judgments better than unstructured critique.

5. **The primary failure mode is overcriticism/nitpicking**, not undercriticism. Adversarial prompts can make this worse.

**Recommendation for Forethought's LLM grader:** Use structured rubric-based evaluation with specific critique dimensions rather than general adversarial prompting. If using devil's advocate approaches, pair them with human review and calibrate for precision-recall tradeoffs.

---

## 1. Devil's advocate prompting effectiveness

### DEBATE framework (ACL 2024)

The most rigorous evaluation of devil's advocate prompting comes from Kim, Kim & Yoon's DEBATE framework:

**Approach:** Multi-agent system with three roles:
- **Commander:** Acts as debate leader
- **Scorer:** Calculates scores for evaluation tasks
- **Critic (Devil's Advocate):** Provides constructive criticism on the Scorer's output

**Results:**
- Substantially outperformed previous state-of-the-art on SummEval and TopicalChat benchmarks
- First to apply multi-agent scoring systems in NLG evaluation
- Demonstrated that extensiveness of debates and agent personas influence performance

**Key insight:** The devil's advocate role helps resolve biases in individual LLM agents' responses, including preferences for certain text structures or content.

### LLM-powered devil's advocate in group decisions (IUI 2024)

Chiang et al. studied interactive LLM devil's advocates:

**Findings:**
- Perceived as more collaborative and higher quality
- Improved appropriate reliance on AI recommendations
- Did not substantially increase perceived workload

**Limitation:** The LLM devil's advocate "may fall short in presenting convincing arguments that are capable of changing the majority opinion." Authentic human dissenters remain more effective at offering creative and persuasive perspectives.

### ChatEval multi-agent debate

The ChatEval framework creates multi-agent referee teams to discuss and evaluate text quality:

**Results:**
- Superior accuracy and correlation with human assessment on benchmarks
- **Critical finding:** Diverse role prompts are essential; using the same role description degrades performance

---

## 2. Steelman-then-critique approaches

Direct research on "steelman then critique" as a specific prompting technique is sparse. Related approaches include:

### Contrastive Chain-of-Thought prompting

Provides both correct and incorrect reasoning examples to help LLMs understand what not to do:

**Results:**
- Significant improvements over zero-shot baselines across 12 datasets
- Surpassed both zero-shot CoT and few-shot CoT on most arithmetic and commonsense reasoning tasks
- Works best when LLMs generate their own erroneous answers rather than using human-annotated incorrect examples

### AI-vs-AI debate tools

Tools surfacing strong opposing arguments side-by-side attempt to give users "steelmanned" versions of each position. However, a noted challenge is that content often presents a steelman for one view and a strawman for the other, creating imbalanced critique.

### Tinmanning risk

A significant failure mode: "declaring you're steelmanning but actually strawmanning." The argument superficially looks like a steelman but is actually weak. This can occur when LLMs attempt to represent opposing views they have biases against.

---

## 3. Adversarial vs. neutral framing effects

### Sycophancy research (Anthropic, 2023)

**Key finding:** LLMs exhibit sycophantic behaviour across tasks, preferring responses that match user views:

- Both humans and preference models prefer convincingly-written sycophantic responses over correct ones a "non-negligible fraction of the time"
- Optimizing against preference models sometimes sacrifices truthfulness for sycophancy
- Sycophancy is driven by human preference judgments in RLHF

**Implication for adversarial prompting:** Asking an LLM to be critical may partially counteract sycophancy, but the underlying tendency remains.

### SycEval benchmarking (2025)

Quantified sycophantic behaviour across models:
- Sycophancy observed in 58.19% of cases overall
- Gemini: 62.47% (highest)
- ChatGPT: 56.71% (lowest)

Distinguishes between:
- **Regressive sycophancy:** Moving toward inaccuracy
- **Progressive sycophancy:** Moving toward accuracy

### Framing effects on critique

Research on framing shows:
- If your prompt "already takes a side," LLMs will cooperate with that stance
- Attack-framed prompts (e.g., role-play, safety preambles) consistently increased harmful outputs
- Neutral framing generally produces more balanced assessments

---

## 4. Red team prompting techniques

### Automated red teaming findings

Red teaming research primarily focuses on safety/jailbreaking rather than critique quality, but relevant findings include:

**Attack success rates (ASR):**
- Roleplay dynamics: 89.6% ASR
- Logic trap attacks: 81.4% ASR
- Encoding tricks: 76.2% ASR

**Critique:** Current red teaming focuses on "static, single-turn" attacks, missing the nuanced, multi-turn interactions where critique quality matters most.

### Red reading methodology

A "red reading" approach adapts red teaming for textual analysis:
- Stress-tests texts across structural, rhetorical, logical, and stylistic dimensions
- Provides rigorous, critical, and constructive textual analysis
- Shows promise for systematic critique generation

---

## 5. Overcriticism and nitpicking risks

### CriticGPT findings (OpenAI, 2024)

**The most important study for understanding adversarial critique failure modes:**

**Quantitative findings:**
- CriticGPT caught ~85% of bugs vs ~25% for human reviewers
- BUT: Rate of nitpicks and hallucinated bugs is "much higher for models than for humans"
- Human critiques contain "many fewer nitpicks and hallucinations"
- Human-machine teams hallucinate and nitpick less than models alone

**Trainer preferences:**
- CriticGPT preferred in 63% of cases over ChatGPT baseline
- Improvement due to fewer nitpicks and hallucinated problems

**Key insight:** There is a configurable precision-recall tradeoff. More aggressive searching for problems increases both bug detection AND hallucinations/nitpicks.

### Self-bias amplification

Research shows a central challenge in self-refinement:
- LLMs systematically overrate their own generations
- Self-bias amplifies monotonically over multiple refinement rounds
- Affects both open and closed-source models

### Overcorrection in bias mitigation

Studies on bias correction found:
- GPT-3.5 may be "overcorrecting for known biases"
- Overcorrection generates "its own kind of undesirable effects"
- Need to assess whether bias reduction sacrifices helpful information

### Overconfidence and underconfidence dynamics

Striking finding from 2025 research:
- LLMs exhibit "steadfastly overconfident" initial answers
- BUT become "prone to excessive doubt when challenged"
- Two mechanisms: drive to maintain consistency + hypersensitivity to contradictory feedback

**Implication:** Adversarial prompting may trigger overcorrection, where LLMs abandon correct positions too readily.

---

## 6. Calibration effects of adversarial prompting

### LLM-Rubric approach (ACL 2024)

Microsoft's LLM-Rubric demonstrates structured calibration:

**Method:**
- LLM prompted with each rubric question, producing distribution over responses
- Small neural network combines multiple distributions to predict human judges
- Includes judge-specific and judge-independent parameters

**Results:**
- 9-question rubric (naturalness, conciseness, citation quality, etc.) predicts human satisfaction assessment (1-4 scale)
- Multidimensional approach outperforms single-score evaluation

### BASIL: Bayesian Assessment of Sycophancy (2025)

Applies Bayesian framework to sycophantic belief shifts:

**Findings:**
- Post-hoc calibration methods reduce Bayesian inconsistency (but only if applied holistically)
- SFT and DPO fine-tuning rewarding Bayesian-consistent updates reduces both reasoning errors and sycophancy

### Self-consistency prompting

Generates multiple reasoning paths and selects most consistent answer:
- Self-consistency outperformed other prompt engineering techniques with 52.99% accuracy in one study
- More robust than single-path reasoning

---

## 7. Specific validated techniques

### Techniques with positive evidence

| Technique | Evidence level | Best use case |
|-----------|---------------|---------------|
| Multi-agent devil's advocate (DEBATE) | Strong (ACL 2024 benchmarks) | NLG evaluation, text quality assessment |
| Rubric-based structured critique | Strong (ACL 2024, education studies) | Any evaluation task with definable criteria |
| Contrastive CoT (correct + incorrect examples) | Moderate | Reasoning tasks, arithmetic |
| Human-LLM teams for critique | Strong (CriticGPT) | High-stakes evaluation requiring precision |
| Self-consistency (multiple paths) | Moderate | Reasoning tasks |
| External feedback loops (tools, verification) | Strong | Tasks with verifiable outputs |

### Techniques with mixed/negative evidence

| Technique | Issue | When to avoid |
|-----------|-------|--------------|
| Pure self-critique | Self-bias, amplifies over iterations | Tasks requiring external validation |
| Single persona adversarial ("be critical") | Increases nitpicks and hallucinations | Free-form critique tasks |
| Expert persona prompting | No improvement on factual accuracy | Accuracy-based tasks |
| Multi-agent debate (homogeneous) | Fails to consistently outperform single-agent | Simple tasks with clear answers |

---

## 8. Known failure modes

### Primary failure modes

1. **Hallucinated problems:** LLMs invent issues that don't exist, especially when prompted to find problems

2. **Nitpicking:** Focus on trivial issues while missing substantive problems

3. **Self-bias reinforcement:** Multi-round self-critique amplifies rather than corrects errors

4. **Sycophantic reversal:** Abandoning correct positions too readily when challenged

5. **Strawmanning disguised as steelmanning:** Weak representations of opposing views presented as strong ones

6. **Position bias:** Preferring first or second responses based on position rather than quality

7. **Verbosity preference:** Rating longer, more detailed outputs higher regardless of accuracy

### Domain-specific limitations

- **Philosophy/critical thinking:** Philosophers found LLMs lack "selfhood" (memory, beliefs, consistency) and "initiative" (curiosity, proactivity) needed for genuine critical engagement

- **Novelty detection:** LLMs cannot reliably identify whether arguments are truly novel vs. rehashed standard positions

- **Cutting-edge topics:** Academic content is tiny portion of training data; LLMs cannot evaluate "highly sophisticated cutting-edge topics and niche subjects in depth"

---

## 9. Practical recommendations for Forethought's LLM grader

### Recommended approach

1. **Use structured rubric-based evaluation** rather than free-form adversarial critique
   - Define specific dimensions: argument quality, evidence use, logical coherence, novelty, clarity
   - Use Likert scales (1-4 or 1-5) for each dimension
   - Include explicit rubric descriptions for each score level

2. **If using devil's advocate prompting:**
   - Deploy multi-agent system with distinct roles (not just "be critical")
   - Use diverse persona prompts across agents
   - Limit to 1-2 debate rounds (diminishing returns beyond)
   - Pair with human review for high-stakes evaluations

3. **Calibrate for precision vs. recall:**
   - For critique quality assessment: favour precision (fewer false positives)
   - More aggressive critique prompting increases both true and false positives
   - Consider two-stage: permissive first pass, then filter for precision

4. **Mitigate sycophancy and self-bias:**
   - Use external reference points where possible
   - Avoid revealing your own position before critique
   - Consider using different models for generation vs. critique

5. **For philosophical/conceptual content:**
   - Accept that novelty detection requires human judgment
   - Use LLMs for structural and logical analysis
   - Reserve substantive intellectual critique for human reviewers

### Not recommended

- Relying on single "be harsh/critical" persona prompts
- Multiple rounds of self-critique without external feedback
- Expecting LLMs to reliably identify genuinely novel arguments
- Using homogeneous multi-agent debate (same model, same prompts)

---

## 10. Key papers and sources

### Essential reading

1. **DEBATE: Devil's Advocate-Based Assessment and Text Evaluation** (Kim, Kim & Yoon, ACL 2024 Findings)
   - [ACL Anthology](https://aclanthology.org/2024.findings-acl.112/)
   - Multi-agent devil's advocate for NLG evaluation

2. **LLM Critics Help Catch LLM Bugs** (McAleese et al., OpenAI, 2024)
   - [OpenAI Paper](https://cdn.openai.com/llm-critics-help-catch-llm-bugs-paper.pdf)
   - CriticGPT: quantifies hallucinations, nitpicks, human-machine teams

3. **LLM-Rubric: A Multidimensional, Calibrated Approach to Automated Evaluation** (ACL 2024)
   - [ACL Anthology](https://aclanthology.org/2024.acl-long.745.pdf)
   - Structured rubric-based LLM evaluation

4. **Towards Understanding Sycophancy in Language Models** (Anthropic, 2023)
   - [Anthropic Research](https://www.anthropic.com/research/towards-understanding-sycophancy-in-language-models)
   - Sycophancy causes and mitigation

5. **When Can LLMs Actually Correct Their Own Mistakes?** (TACL 2024)
   - [MIT Press](https://direct.mit.edu/tacl/article/doi/10.1162/tacl_a_00713/125177/)
   - Critical survey of self-correction capabilities

6. **ChatEval: Towards Better LLM-based Evaluators through Multi-Agent Debate**
   - [arXiv](https://arxiv.org/abs/2308.07201)
   - Multi-agent evaluation with diverse personas

### Additional sources

- **Self-Refine: Iterative Refinement with Self-Feedback** - [selfrefine.info](https://selfrefine.info/)
- **Contrastive Chain-of-Thought Prompting** - [Learn Prompting](https://learnprompting.org/docs/advanced/thought_generation/contrastive_cot)
- **Large Language Models are Contrastive Reasoners** - [arXiv](https://arxiv.org/html/2403.08211v1)
- **SycEval: Evaluating LLM Sycophancy** - [arXiv](https://arxiv.org/html/2502.08177v2)
- **Multi-LLM-Agents Debate: Performance, Efficiency, and Scaling Challenges** - [ICLR 2025 Blogposts](https://d2jud02ci9yv69.cloudfront.net/2025-04-28-mad-159/blog/mad/)
- **How Overconfidence in Initial Choices and Underconfidence Under Criticism Modulate Change of Mind** - [arXiv](https://arxiv.org/html/2507.03120v1)
- **PEARL: A Rubric-Driven Multi-Metric Framework for LLM Evaluation** - [MDPI](https://www.mdpi.com/2078-2489/16/11/926)

---

## Summary table

| Research area | Key finding | Confidence |
|--------------|-------------|------------|
| Devil's advocate prompting | Multi-agent systems improve evaluation, single-agent approaches unreliable | High |
| Steelman-then-critique | Little direct evidence; contrastive CoT shows promise | Low |
| Adversarial vs. neutral framing | Adversarial increases comprehensiveness but also errors | High |
| Red team techniques | Useful for safety probing, unclear for critique quality | Low |
| Overcriticism risks | Major failure mode; worse with aggressive adversarial prompting | High |
| Calibration effects | Rubric-based approaches enable calibration; free-form does not | High |
