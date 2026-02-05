# Synthesis: Multi-persona prompting for critique generation

**Question:** Does prompting an LLM to adopt multiple personas improve quality or diversity of critiques?

**Sources:** Claude Opus 4.5, OpenAI o3-deep-research, Gemini deep-research-pro

---

## Consensus findings

### 1. Personas reliably increase diversity

All three models agree that multi-persona prompting **increases diversity** of outputs:

| Source | Finding |
|--------|---------|
| Claude | "Diversity gains are real but moderate" |
| OpenAI | "Personas push the model to tap into different knowledge and styles" |
| Gemini | "LLM-generated personas outperform human-crafted ones" |

**Confidence:** High. This is the most consistent finding across sources.

### 2. Quality improvements are task-dependent

Critical nuance: personas help diversity more than quality.

- **When personas help:** Open-ended tasks, brainstorming, argumentation
- **When personas don't help:** Factual accuracy, classification, objective reasoning

Claude and OpenAI both cite **Mollick et al. (December 2025)**: Expert personas do not improve factual accuracy.

### 3. Simple aggregation captures most benefit

All sources note that **debate itself adds little** beyond voting/aggregation:

- Gemini: "Simple majority voting accounts for most observed gains in multi-agent systems"
- Claude: "Current MAD methods fail to consistently outperform simpler single-agent strategies"
- OpenAI: "Limit to 1-2 debate rounds; diminishing returns beyond"

### 4. Significant bias risks from personas

All three models warn about persona-induced biases:

- **80% of personas demonstrate bias** in ChatGPT-3.5 experiments
- **Performance drops of 70%+** for certain demographic personas
- LLMs default to "middle-aged, able-bodied, Caucasian" persona unless instructed otherwise
- De-biasing prompts have **minimal effect**

**Recommendation:** Use professional/methodological personas, not demographic ones.

---

## Unique insights by source

### Claude
- **Jekyll & Hyde framework:** Generate outputs from both persona-prompted AND neutral-prompted calls, then use evaluator to select best. +9.98% accuracy gain on GPT-4.
- **Solo Performance Prompting (SPP):** Let LLM dynamically identify and simulate relevant personas rather than pre-assigning. Only effective on GPT-4 class models.

### OpenAI
- **Parallel > collective prompting:** Having personas speak separately outperforms mashing them together in one prompt.
- **Limit debate rounds:** 1-2 rounds is enough; beyond that, discussion repeats or "over-corrects" valid points.
- **Groupthink risk:** In naive debates, correct answers were sometimes overturned by persuasive but wrong arguments.

### Gemini
- **LLM-generated personas outperform human-written:** Auto-generated expert personas consistently better than hand-crafted ones.
- **Instance-aligned > dataset-aligned:** Personas tailored to specific papers yield better results than generic domain personas.

---

## Practical recommendations for Forethought

### For critique generation (primary use case)

1. **Use Jekyll & Hyde ensemble:**
   - Generate critiques with both persona-prompted AND neutral-prompted calls
   - Use LLM evaluator to select or synthesise best elements
   - This catches cases where personas degrade quality

2. **Let LLM generate detailed personas:**
   - Provide paper topic and ask LLM to generate 5 relevant expert personas
   - Include backgrounds, methodological commitments, likely objections
   - Auto-generated personas outperform hand-crafted ones

3. **Design personas for your domain:**
   - Use professional/methodological personas: "analytic philosopher specialising in decision theory", "empirical economist focused on causal inference"
   - **Avoid demographic personas** (high bias risk)

4. **Include neutral baseline:**
   - Always include outputs from non-persona-prompted call
   - Catches cases where personas degrade quality (~14% of problems in studies)

### For the grader (evaluation)

1. **Be cautious with persona-based evaluation:**
   - Expert personas can **decrease** agreement with human judgments in specialised domains
   - Consider dimension-based evaluation rather than persona-based

2. **Measure effective diversity, not just diversity:**
   - Filter for quality threshold before measuring diversity
   - Use semantic similarity metrics rather than n-gram overlap

---

## Example personas for Forethought research

Instead of generic:
> "You are a philosopher"

Use specific:
> "You are a moral philosopher specialising in population ethics and longtermism, with particular expertise in person-affecting views and their implications for existential risk prioritisation. You tend to be skeptical of strong totalist positions and look for edge cases that challenge utilitarian aggregation."

For AI governance papers, consider:
- "Analytic philosopher specialising in decision theory"
- "Empirical economist focused on causal inference"
- "AI safety researcher with technical ML background"
- "Skeptic of longtermist arguments"
- "Policy analyst focused on implementation feasibility"

---

## Key papers

1. **Jekyll & Hyde (Kim et al., 2024)** - Ensembling role-playing and neutral prompts; +9.98% accuracy
2. **Solo Performance Prompting (Wang et al., NAACL 2024)** - Multi-persona self-collaboration
3. **Debate-to-Write (Hu et al., COLING 2025)** - Persona-driven argument generation
4. **Bias Runs Deep (Gupta et al., 2023)** - Implicit reasoning biases in persona-assigned LLMs
5. **Mollick et al. (December 2025)** - Expert personas don't improve factual accuracy
