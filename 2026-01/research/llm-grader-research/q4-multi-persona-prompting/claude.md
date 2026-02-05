# Multi-persona prompting for LLM critique generation

**Research question:** Does prompting an LLM to adopt multiple personas (e.g., "economist", "philosopher", "skeptic") when generating critiques improve the quality or diversity of outputs?

**Context:** Building an LLM grader to evaluate AI-generated critiques of research papers for Forethought Research (philosophy/economics research on AI governance, post-AGI economics, digital minds, longtermism).

**Date:** 2026-01-20

---

## Executive summary

Multi-persona prompting offers **modest benefits for diversity but inconsistent benefits for quality**, with significant caveats. The evidence suggests:

1. **Diversity gains are real but moderate:** Multi-persona approaches reliably increase semantic diversity of outputs, particularly for open-ended tasks like argumentation and brainstorming.

2. **Quality improvements are task-dependent:** For factual accuracy and reasoning tasks, persona prompting shows minimal or no improvement over baseline prompts—and sometimes degrades performance. For creative and argumentative tasks, quality gains are more consistent.

3. **The "cognitive synergy" effect is model-dependent:** Benefits from multi-persona self-collaboration (like Solo Performance Prompting) emerge primarily in highly capable models (GPT-4 class) and are absent or minimal in less capable models.

4. **Bias risks are significant:** Persona assignment can activate latent stereotypes and biases, with 80% of personas demonstrating bias in some studies and performance drops of 70%+ for certain demographic personas.

**Bottom line for Forethought:** For generating diverse critiques of philosophy/economics research, multi-persona prompting is worth implementing but should be combined with neutral baseline outputs and careful persona design. The approach is better suited for generating candidate critiques than for the grader itself.

---

## Empirical findings by research area

### 1. Persona diversity effects on critique quality

**Key finding:** Multi-persona approaches increase diversity but not necessarily quality.

The [Debate-to-Write framework](https://arxiv.org/abs/2406.19643) (COLING 2025) provides the most directly relevant evidence. This persona-driven multi-agent framework for argument generation:

- Assigns each agent a unique persona representing distinct viewpoints on a topic
- Enables agents to debate and collaboratively develop argument plans
- Produces outputs with significantly higher diversity (both semantic and perspective) compared to direct prompting or linear planning baselines
- Achieves highest scores for output relevance and second-highest for overall quality in GPT-based evaluations

However, the [Adaptive Heterogeneous Multi-Agent Debate (A-HMAD)](https://link.springer.com/article/10.1007/s44443-025-00353-3) research found that **simple majority voting accounts for most observed gains** in multi-agent systems—the debate process itself contributes less than the aggregation mechanism.

Research on [effective semantic diversity](https://arxiv.org/html/2504.12522v1) shows that diversity without quality is trivial to achieve. The meaningful metric is "effective semantic diversity"—diversity among outputs that meet quality thresholds.

### 2. Quality improvement from persona prompting

**Key finding:** Benefits are highly task-dependent, with newer models showing diminishing returns.

**When persona prompting helps:**
- Open-ended tasks (creative writing, brainstorming, argumentation)
- Tasks requiring diverse perspectives or viewpoints
- Situations where role-specific framing improves relevance

**When persona prompting does not help (or hurts):**
- Factual accuracy tasks—[Mollick et al. (December 2025)](https://papers.ssrn.com/sol3/papers.cfm?abstract_id=5879722) found expert personas **do not improve factual accuracy**
- Classification and objective reasoning tasks
- Simple, well-defined tasks on frontier models

The landmark study ["When 'A Helpful Assistant' Is Not Really Helpful"](https://arxiv.org/html/2311.10054v3) analysed 162 personas across 26 categories and found:
- Adding personas does not necessarily improve performance on objective tasks
- The effect of each persona varies across questions, making it difficult to identify consistently beneficial personas
- "The effect of personas on LLMs' performance might largely be random"

The ["Better Zero-Shot Reasoning with Role-Play Prompting"](https://arxiv.org/html/2308.07702v2) paper presents a more optimistic view, showing role-play prompting can act as an implicit Chain-of-Thought trigger and outperforms Zero-Shot-CoT on most benchmarks. However, this benefit is specific to reasoning tasks with a particular framework.

### 3. Persona specification depth

**Key finding:** Specific, detailed, LLM-generated personas outperform generic or human-written ones.

Key principles from the research:

1. **Generic personas are ineffective:** "You are a mathematician" performs poorly. Role descriptions must be detailed and comprehensive.

2. **LLM-generated > human-written:** [ExpertPrompting](https://learnprompting.org/docs/advanced/zero_shot/role_prompting) and related work shows auto-generated expert personas consistently outperform human-crafted ones.

3. **Domain alignment matters but less than expected:** The effect size of matching persona domain to task (e.g., "lawyer" for legal tasks) is relatively small.

4. **Contextual depth enhancement:** Adding backgrounds, motivations, and constraints to personas enables more nuanced responses.

5. **Instance-aligned > dataset-aligned:** Using personas tailored to specific instances yields more accurate and stable performance than using dataset-level personas.

**Practical specification:**
Instead of: "You are a philosopher"
Use: "You are a moral philosopher specialising in population ethics and longtermism, with particular expertise in person-affecting views and their implications for existential risk prioritisation. You tend to be skeptical of strong totalist positions and look for edge cases that challenge utilitarian aggregation."

### 4. Multi-agent debate approaches

**Key finding:** Multi-agent debate improves factual accuracy and reduces hallucinations, but current implementations underperform simpler alternatives.

The foundational [Multi-Agent Debate paper (Du et al., 2023)](https://arxiv.org/abs/2305.14325) demonstrated that having multiple LLM instances debate their responses:
- Significantly enhances mathematical and strategic reasoning
- Improves factual validity
- Reduces hallucinations

However, [ICLR Blogposts 2025](https://d2jud02ci9yv69.cloudfront.net/2025-04-28-mad-159/blog/mad/) reports that **current MAD methods fail to consistently outperform simpler single-agent strategies**, even with increased computational resources. The gains primarily come from output aggregation rather than the debate process itself.

**Specific multi-agent frameworks:**

| Framework | Approach | Key finding |
|-----------|----------|-------------|
| **[Solo Performance Prompting (SPP)](https://arxiv.org/abs/2307.05300)** | Single LLM dynamically identifies and simulates multiple personas | Reduces hallucination; effect only emerges in GPT-4 class models |
| **[Jekyll & Hyde](https://arxiv.org/html/2408.08631v2)** | Ensembles role-playing and neutral prompts with cross-checking | 9.98% average accuracy gain on GPT-4 across 12 reasoning datasets |
| **[CourtEval](https://arxiv.org/html/2508.02994v1)** | Grader, Critic (prosecutor), Defender roles | Improves judgment quality through devil's advocate mechanism |
| **[MAJ-EVAL](https://arxiv.org/html/2508.02994v1)** | Automatically constructs evaluator personas from domain documents | Addresses ad-hoc persona design; improves cross-task generality |

### 5. Failure modes

**Key finding:** Persona prompting carries significant bias risks that are difficult to mitigate.

#### Stereotyping and bias amplification

The [Bias Runs Deep](https://arxiv.org/abs/2311.04892) research demonstrates that:
- **80% of personas demonstrate bias** in ChatGPT-3.5 experiments
- **Performance drops of 70%+** occur for some datasets with certain personas
- LLMs harbour "deep-rooted bias against various socio-demographics underneath a veneer of fairness"
- Example: Physically-disabled personas frequently abstain from mathematical reasoning under mistaken presumptions

Even GPT-4-Turbo shows problematic bias in **42% of personas tested**.

#### Default persona drift

LLMs tend to default to a "middle-aged, able-bodied, native-born, Caucasian, atheistic, centrist" persona unless explicitly instructed otherwise. Demographic deviation from this default increases response shift and often reduces quality.

#### Difficult to mitigate

De-biasing prompts have been found to have **minimal to no effect** on persona-induced errors.

#### Additional failure modes

- **Persona confusion:** When multiple personas are assigned, models may conflate characteristics
- **Stereotypical narrative patterns:** Even when impersonating specific individuals, LLMs produce generalised, stereotypical descriptions
- **Inconsistent persona maintenance:** Models may drop persona characteristics mid-generation
- **Inquiry persona effects:** Credulous and adversarial personas increase stereotyping risk, especially in smaller models

### 6. Evaluation considerations for persona-generated content

**Key finding:** Evaluating persona-generated content requires specialised metrics and awareness of evaluator biases.

#### Diversity metrics

The research distinguishes between:
- **Form-based metrics** (self-BLEU, distinct n-grams): Measure lexical overlap
- **Content-based metrics** (self-CosSim, Vendi-Score): Capture semantic variation

Content-based metrics are more appropriate for evaluating persona-generated critiques. The [Debate-to-Write paper](https://arxiv.org/abs/2406.19643) proposes a novel metric specifically for evaluating **perspective diversity** in long-form output.

#### LLM-as-Judge considerations

When using LLMs to evaluate persona-generated content:
- **Position bias:** Evaluators favour outputs based on prompt position
- **Verbosity bias:** Longer responses receive better scores
- **Self-enhancement bias:** Models favour their own outputs
- **Expert persona paradox:** Assigning expert personas to evaluators can **decrease** agreement with human judgments in some domains

The [Jekyll & Hyde framework](https://arxiv.org/html/2408.08631v2) addresses position bias through a robust LLM evaluator that cross-checks from both perspectives.

---

## Validated techniques

Based on the research, these techniques have empirical support:

### 1. Jekyll & Hyde ensemble (recommended)

Generate outputs from both persona-prompted and neutral-prompted LLMs, then use an LLM evaluator to select the better response.

- **Evidence:** 9.98% accuracy improvement on GPT-4 across 12 reasoning datasets
- **Key insight:** Mitigates cases where personas degrade performance (13.78% of problems in studies)

### 2. Solo Performance Prompting (SPP)

Let the LLM dynamically identify and simulate relevant personas for the specific task, rather than pre-assigning fixed personas.

- **Evidence:** Reduces hallucination, maintains reasoning capability
- **Caveat:** Only effective on GPT-4 class models

### 3. Debate-to-Write for diverse argumentation

For generating diverse critiques:
1. Generate a pool of 5-10 distinct persona viewpoints relevant to the topic
2. Have persona-agents debate and critique each other's positions
3. Distill into a comprehensive critique plan
4. Generate final critique incorporating multiple perspectives

- **Evidence:** Highest relevance scores and significantly higher diversity than baselines

### 4. Multi-dimensional evaluation with MAJ-EVAL

For evaluation (the grader itself):
1. Extract key evaluation dimensions from domain-relevant documents
2. Automatically construct evaluator personas representing each dimension
3. Aggregate judgments across personas

- **Evidence:** Addresses ad-hoc persona design, improves generality

### 5. ExpertPrompting with automatic persona generation

Use the LLM itself to generate detailed expert personas for each task, rather than hand-crafting them.

- **Evidence:** Auto-generated personas outperform human-written ones; more scalable

---

## Evidence summary: Quality and diversity

### Quality improvements

| Context | Evidence | Effect size |
|---------|----------|-------------|
| Factual accuracy | Mollick et al. 2025 | **No improvement** from expert personas |
| Mathematical reasoning | Multi-agent debate | Significant improvement |
| Argumentation quality | Debate-to-Write | Highest relevance, second-highest overall quality |
| Zero-shot reasoning | Role-play prompting | Outperforms Zero-Shot-CoT on most benchmarks |
| General reasoning (ensemble) | Jekyll & Hyde | +9.98% accuracy on GPT-4 |

### Diversity improvements

| Context | Evidence | Effect size |
|---------|----------|-------------|
| Semantic diversity | Debate-to-Write | Significantly surpasses all baselines |
| Perspective diversity | Multi-persona frameworks | Consistently increased |
| Idea diversity (human brainstorming) | How AI Ideas Affect... | "AI made ideas different, not better" |

### Caveats

- Benefits are **model-dependent** (primarily GPT-4 class)
- Benefits are **task-dependent** (open-ended > factual)
- **Bias risks** are substantial and hard to mitigate
- **Simple aggregation** may capture most of the benefit

---

## Practical recommendations for Forethought

### For critique generation (recommended approach)

1. **Use a hybrid Jekyll & Hyde approach:**
   - Generate critiques with both persona-prompted and neutral-prompted calls
   - Include 3-5 diverse expert personas (e.g., "analytic philosopher specialising in decision theory", "empirical economist focused on causal inference", "AI safety researcher with technical ML background", "skeptic of longtermist arguments")
   - Use an LLM evaluator to select or synthesise the best elements

2. **Let the LLM generate detailed personas:**
   - Provide the paper topic and ask the LLM to generate 5 relevant expert personas with detailed backgrounds, methodological commitments, and likely objections
   - This outperforms hand-crafted generic personas

3. **Include a neutral baseline:**
   - Always include outputs from a non-persona-prompted call
   - This catches cases where personas degrade quality

4. **Design personas for your specific domain:**
   - Philosophy/economics of AI: Include methodologically diverse perspectives (formal modelling vs. qualitative, consequentialist vs. deontological, optimistic vs. pessimistic about AI)
   - Avoid demographic personas (high bias risk); use professional/methodological personas

### For the grader (evaluation)

1. **Be cautious with persona-based evaluation:**
   - Expert personas can **decrease** agreement with human judgments in specialised domains
   - Consider using dimension-based evaluation (MAJ-EVAL style) rather than persona-based

2. **Address position bias:**
   - If comparing outputs, evaluate in both orders and check consistency
   - Use the Jekyll & Hyde robust evaluator approach

3. **Measure effective diversity, not just diversity:**
   - Filter for quality threshold before measuring diversity
   - Use content-based metrics (semantic similarity) rather than form-based (n-gram overlap)

### Implementation priority

Given Forethought's use case (generating and evaluating critiques of philosophy/economics research), prioritise in this order:

1. **Immediate:** Implement Jekyll & Hyde ensemble for critique generation
2. **Short-term:** Develop auto-generated expert personas tailored to each paper's topic
3. **Medium-term:** Build debate-based critique generation for comprehensive analysis
4. **Evaluate carefully:** Whether persona-based evaluation improves grader performance—test against human judgments before deploying

---

## Key papers

### Core methodology

1. **[Solo Performance Prompting (SPP)](https://arxiv.org/abs/2307.05300)** — Wang et al. (NAACL 2024)
   Multi-persona self-collaboration for cognitive synergy in LLMs

2. **[Jekyll & Hyde Framework](https://arxiv.org/html/2408.08631v2)** — Kim et al. (2024)
   Ensembling role-playing and neutral prompts for robust reasoning

3. **[Debate-to-Write](https://arxiv.org/abs/2406.19643)** — Hu et al. (COLING 2025)
   Persona-driven multi-agent framework for diverse argument generation

4. **[Multi-Agent Debate](https://arxiv.org/abs/2305.14325)** — Du et al. (2023)
   Foundational work on improving factuality through multiagent debate

### Empirical evaluations

5. **[Expert Personas Don't Improve Factual Accuracy](https://papers.ssrn.com/sol3/papers.cfm?abstract_id=5879722)** — Mollick et al. (December 2025)
   Critical negative result for factual tasks

6. **["A Helpful Assistant" Is Not Really Helpful](https://arxiv.org/html/2311.10054v3)** — Wang et al. (2023)
   Comprehensive analysis showing personas don't improve objective task performance

7. **[Bias Runs Deep](https://arxiv.org/abs/2311.04892)** — Gupta et al. (2023)
   Implicit reasoning biases in persona-assigned LLMs

8. **[Better Zero-Shot Reasoning with Role-Play Prompting](https://arxiv.org/html/2308.07702v2)** — Kong et al. (2023)
   Positive results for role-play as implicit CoT trigger

### Evaluation frameworks

9. **[CritiqueLLM](https://arxiv.org/abs/2311.18702)** — Ke et al. (ACL 2024)
   Informative critique generation for LLM evaluation

10. **[CriticBench](https://arxiv.org/abs/2402.14809)** — Lin et al. (2024)
    Benchmarking LLMs for critique-correct reasoning

11. **[Evaluating Diversity and Quality](https://arxiv.org/html/2504.12522v1)** — Chen et al. (2025)
    Framework for effective semantic diversity measurement

12. **[PersonaFlow](https://dl.acm.org/doi/10.1145/3715336.3735789)** — DIS 2025
    LLM-simulated expert perspectives for research ideation

---

## Limitations of this review

- Most studies use GPT models; results may not transfer to other model families
- Many studies focus on reasoning benchmarks rather than critique/evaluation tasks specifically
- Research on persona-based critique evaluation (rather than generation) is limited
- Bias studies focus primarily on demographic personas; professional/methodological persona bias is less studied
- Long-term effects (e.g., persona drift over extended conversations) are under-researched

---

## Sources

- [PersonaFlow: Designing LLM-Simulated Expert Perspectives](https://dl.acm.org/doi/10.1145/3715336.3735789)
- [LLM Generated Persona is a Promise with a Catch](https://openreview.net/forum?id=qh9eGtMG4H)
- [Expert Personas Don't Improve Factual Accuracy](https://papers.ssrn.com/sol3/papers.cfm?abstract_id=5879722)
- [Quantifying the Persona Effect in LLM Simulations](https://axi.lims.ac.uk/paper/2402.10811)
- [Persona is a Double-Edged Sword](https://openreview.net/forum?id=2sQRGVprpL)
- [When "A Helpful Assistant" Is Not Really Helpful](https://arxiv.org/html/2311.10054v3)
- [Adaptive Heterogeneous Multi-Agent Debate](https://link.springer.com/article/10.1007/s44443-025-00353-3)
- [Multi-Agent Debate Strategies for Requirements Engineering](https://arxiv.org/html/2507.05981v1)
- [Improving Factuality through Multiagent Debate](https://arxiv.org/abs/2305.14325)
- [Multi-LLM-Agents Debate: Performance and Challenges](https://d2jud02ci9yv69.cloudfront.net/2025-04-28-mad-159/blog/mad/)
- [Debate-to-Write Framework](https://arxiv.org/abs/2406.19643)
- [Better Zero-Shot Reasoning with Role-Play Prompting](https://arxiv.org/html/2308.07702v2)
- [Role Prompting Guide](https://learnprompting.org/docs/advanced/zero_shot/role_prompting)
- [Role-Prompting: Does Adding Personas Make a Difference?](https://www.prompthub.us/blog/role-prompting-does-adding-personas-to-your-prompts-really-make-a-difference)
- [Solo Performance Prompting](https://arxiv.org/abs/2307.05300)
- [Bias Runs Deep: Implicit Reasoning Biases](https://arxiv.org/abs/2311.04892)
- [Simulating Identity, Propagating Bias](https://arxiv.org/html/2509.08484)
- [From Single to Societal: Persona-Induced Bias](https://arxiv.org/html/2511.11789v1)
- [Agent-as-a-Judge Evaluation for LLMs](https://arxiv.org/html/2508.02994v1)
- [LLM-as-a-Judge Guide](https://www.evidentlyai.com/llm-guide/llm-as-a-judge)
- [CritiqueLLM](https://arxiv.org/abs/2311.18702)
- [CriticBench: Benchmarking Critique-Correct Reasoning](https://arxiv.org/abs/2402.14809)
- [Can LLM Multi-Agent Systems Augment Human Creativity?](https://dl.acm.org/doi/10.1145/3715928.3737479)
- [How AI Ideas Affect Creativity and Diversity](https://arxiv.org/html/2401.13481v3)
- [Evaluating Diversity and Quality of LLM Content](https://arxiv.org/html/2504.12522v1)
- [Self-Consistency Prompting](https://www.promptingguide.ai/techniques/consistency)
- [Reasoning Aware Self-Consistency](https://arxiv.org/abs/2408.17017)
- [SIEV: Dialectical Reasoning Evaluation](https://arxiv.org/html/2510.18134)
- [Critical-Questions-of-Thought](https://arxiv.org/html/2412.15177v1)
