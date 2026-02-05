# Synthesis: LLM evaluation frameworks and tooling

**Question:** What tools beyond Promptfoo exist for LLM evaluation?

**Sources:** Claude Opus 4.5, OpenAI o3-deep-research (Gemini incomplete due to rate limits)

---

## Core finding

For Forethought's use case (evaluating critiques of philosophy/economics research), **Promptfoo + custom rubrics is likely sufficient**. More sophisticated frameworks add complexity without proportional benefit for your specific needs.

---

## Framework landscape

### Tier 1: Recommended for Forethought

| Framework | Best for | Why |
|-----------|----------|-----|
| **Promptfoo** | Prompt iteration, regression testing | Simple, CLI-based, supports custom rubrics |
| **Custom rubrics + Toulmin CoT** | Critique evaluation | Domain-specific, low overhead |

### Tier 2: Consider if needs grow

| Framework | Best for | Trade-offs |
|-----------|----------|------------|
| **RAGAS** | RAG pipelines | Good if adding retrieval; overkill otherwise |
| **DeepEval** | Structured evaluation pipelines | More opinionated than Promptfoo |
| **Langfuse** | Production observability | Good for monitoring after deployment |

### Tier 3: Enterprise/Research

| Framework | Best for | Trade-offs |
|-----------|----------|------------|
| **PromptLayer** | Team collaboration | SaaS dependency |
| **Weights & Biases** | Experiment tracking | Heavier infrastructure |
| **AgentOps** | Agent debugging | Overkill for single-turn evaluation |

---

## Promptfoo for Forethought's needs

Promptfoo is well-suited because:

1. **Custom evaluators:** You can define rubric-based graders in YAML or TypeScript
2. **Multi-model comparison:** Easy to test Claude vs GPT vs Gemini
3. **Regression testing:** Detect when prompt changes break performance
4. **No vendor lock-in:** Open source, runs locally

### Example Promptfoo configuration

```yaml
prompts:
  - file://prompts/toulmin-grader.txt

providers:
  - openai:gpt-4o-mini
  - anthropic:claude-3-5-sonnet-latest
  - google:gemini-1.5-flash

tests:
  - vars:
      critique: "{{critique}}"
      paper_context: "{{context}}"
    assert:
      - type: llm-rubric
        value: |
          Score this critique 1-5 on each dimension:
          - Logical Soundness: Does the reasoning hold?
          - Relevance: Does it address core premises?
          - Clarity: Is the argument clear?
          Return JSON: {"logical": X, "relevance": Y, "clarity": Z}
```

---

## Key evaluation techniques (from research)

### 1. PREPAIR-Toulmin workflow

The most validated approach from our research:

1. **Structured argumentation:** Decompose critiques into Claim, Warrant, Backing (Toulmin model)
2. **Rubric decomposition:** Score on multiple dimensions
3. **Verbalized confidence:** Filter evaluations with confidence < 80%
4. **Pointwise-first, pairwise-second:** Ground with individual analysis before comparison

### 2. G-Eval (probability-weighted scoring)

If you need fine-grained scores:
- Extract token probabilities for scores 1-5
- Compute weighted average
- Produces continuous scores (e.g., 3.7) rather than discrete

**Note:** Requires API access to logprobs.

### 3. Multi-model ensemble

For higher reliability:
- Use 3 models from different families
- Compute score average + standard deviation
- Flag high-variance items for human review

---

## What not to overcomplicate

### Don't need for ~20 sample validation:
- Full MLOps pipelines
- Real-time monitoring dashboards
- Complex A/B testing infrastructure

### Do need:
- Consistent rubric definitions
- Simple logging of predictions vs ground truth
- Statistical analysis (Wilson CI, Bayesian posterior)

---

## Implementation recommendation

### Phase 1: MVP (this sprint)

1. **Create YAML rubric** with Toulmin dimensions
2. **Run Promptfoo** against your 20 validation samples
3. **Log results** to CSV for statistical analysis
4. **Compute** agreement + confidence intervals

### Phase 2: Scale (if successful)

1. Add multi-model ensemble via Promptfoo providers
2. Implement confidence filtering
3. Build simple dashboard for ongoing monitoring
4. Expand validation set to n=50

---

## Alternative evaluation approaches

### Reference-free evaluation

For critiques where no "ground truth" exists:
- Use dimension-based rubrics
- Focus on internal consistency, logical validity
- Accept that "novelty" requires human judgment

### Human-in-the-loop

Design for hybrid evaluation:
- LLM handles 80-90% automatically
- Flags ambiguous cases for human review
- Human feedback improves system over time

---

## Tools comparison matrix

| Tool | Open Source | Custom Rubrics | Multi-Model | Learning Curve |
|------|-------------|----------------|-------------|----------------|
| Promptfoo | Yes | Yes | Yes | Low |
| RAGAS | Yes | Limited | Yes | Medium |
| DeepEval | Yes | Yes | Yes | Medium |
| Langfuse | Partial | Yes | Yes | Medium |
| Weights & Biases | No | Yes | Yes | High |

---

## Key resources

### Promptfoo
- [Documentation](https://promptfoo.dev/docs/intro)
- [Custom evaluators](https://promptfoo.dev/docs/configuration/expected-outputs#llm-rubric)
- [Multi-model comparison](https://promptfoo.dev/docs/providers/)

### Research frameworks
- **LLM-Rubric (ACL 2024)** - Multidimensional calibrated approach
- **PREPAIR** - Pointwise + pairwise hybrid
- **G-Eval** - Probability-weighted scoring

### Survey papers
- **"A Survey on LLM-as-a-Judge"** (2024) - Comprehensive overview
- **"Justice or Prejudice?"** (2024) - 12 biases + CALM framework
