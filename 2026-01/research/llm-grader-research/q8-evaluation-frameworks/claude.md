# LLM Evaluation Frameworks: Landscape Analysis for Research Critique Grading

**Research Question:** What tools and frameworks exist beyond Promptfoo for evaluating LLM outputs, and which are best suited for evaluating research critiques?

**Context:** Building an LLM grader to evaluate AI-generated critiques of research papers for Forethought Research (philosophy/economics research).

---

## Executive Summary

The LLM evaluation landscape in 2025-2026 has matured significantly, with tools spanning from lightweight open-source testing frameworks to enterprise-grade observability platforms. For Forethought's use case—evaluating AI-generated research critiques—the key requirements are:

1. **Custom rubric support** for domain-specific evaluation criteria
2. **LLM-as-judge capabilities** with calibration mechanisms
3. **Human-in-the-loop workflows** for expert validation
4. **Small-N statistical robustness** given limited researcher time

The best-fit tools for this use case are **Promptfoo** (already being considered), **DeepEval** (pytest-style testing with custom metrics), and **Ragas** (rubric-based evaluation). For human feedback integration, **Humanloop** stands out. For specialised LLM judges, **Atla Selene** and **Prometheus 2** offer purpose-built evaluation models that outperform general-purpose LLMs.

---

## Tool Landscape Overview

### Open-Source Frameworks

#### Promptfoo
**Focus:** Prompt engineering, testing, and red teaming

Promptfoo is an open-source toolkit for prompt engineering, testing, and evaluation. It enables A/B testing of prompts and LLM outputs using simple YAML or command-line configurations. The standout feature is red teaming: Promptfoo can probe prompts for vulnerabilities, test for prompt injections, check for PII leaks, and identify edge cases.

**Key features:**
- LLM-as-judge evaluations with [`llm-rubric`](https://www.promptfoo.dev/docs/configuration/expected-outputs/model-graded/llm-rubric/) assertion type
- Custom rubric support with threshold scoring
- Model-graded metrics with configurable prompts
- No cloud setup required; runs locally
- CI/CD integration via command line

**Limitations:**
- Less visual/UI-driven than alternatives—basic web UI
- No production monitoring or real-time alerting
- Testing tool, not an observability platform

**Best for:** Developers who prefer code-first, local evaluation without platform lock-in.

#### DeepEval (Confident AI)
**Focus:** Pytest-style LLM testing

DeepEval treats evaluations as unit tests, providing a simple, unit-test-like interface integrated with pytest. It includes 30+ built-in metrics covering correctness, consistency, relevancy, hallucination checks, and more.

**Key features:**
- 14+ LLM evaluation metrics (RAG and fine-tuning use cases)
- Self-explaining metrics that indicate why scores cannot be higher
- Custom metric definition using LLMs or local NLP models
- Synthetic dataset generation
- Works with any LLM application (chatbots, RAG, agents)

**Limitations:**
- Requires Python; less suitable for non-Python environments
- Cloud dashboard (Confident AI) is commercial

**Best for:** Teams wanting testing framework integration familiar to software developers.

#### Langfuse
**Focus:** Open-source LLM observability and evaluation

Langfuse is MIT-licensed with full self-hosting capability. It provides comprehensive tracing, prompt versioning, and flexible evaluation workflows.

**Key features:**
- Full traceability of LLM calls (inputs, outputs, API calls)
- LLM-as-judge metrics and human annotation support
- Centralised prompt versioning with playground
- A/B experiments and production monitoring dashboards
- Custom test sets and benchmark tracking

**Limitations:**
- Slower performance than newer tools (~327s for trace logging and evaluation vs. Opik's ~23s)
- Requires stronger DevOps capabilities for self-hosting

**Best for:** Teams valuing open-source principles, complete infrastructure control, and compliance requirements.

#### Opik (Comet)
**Focus:** Fast, comprehensive LLM evaluation and monitoring

Opik is an Apache 2.0 licensed framework that achieves 7-14x faster performance than competitors for rapid iteration.

**Key features:**
- Deep tracing of LLM calls, conversation logging, agent activity
- Pre-configured and custom evaluation metrics via SDK
- Built-in LLM judges for hallucination detection, factuality, moderation
- PyTest-based "model unit tests" for CI/CD integration
- Agent Optimizer for automated prompt optimisation (beta, June 2025)
- Guardrails for real-time content screening

**Limitations:**
- Newer platform with less mature ecosystem
- Enterprise features require Comet subscription

**Best for:** Teams prioritising speed and comprehensive open-source features.

#### Arize Phoenix
**Focus:** Production observability with agent evaluation

Phoenix provides OpenTelemetry-native observability with strong capabilities for tracing and debugging LLM applications in production.

**Key features:**
- Built-in evaluation suite for Q&A accuracy, hallucination detection, toxicity
- Deep support for agent evaluation with multi-step trace capture
- Prompt management module (released April 2025)
- Extensible plugin system
- Open architecture for customisation

**Limitations:**
- Limited evaluation metrics compared to dedicated evaluation tools
- Better suited as complementary tool rather than complete solution

**Best for:** Teams with existing production LLM applications needing deep observability.

#### Ragas
**Focus:** RAG-specific evaluation

Ragas (Retrieval Augmented Generation Assessment) is purpose-built for evaluating RAG pipelines, though its rubric-based evaluation is applicable beyond RAG.

**Key features:**
- Reference-free evaluation metrics (Faithfulness, Answer Relevancy, Context Precision/Recall)
- [`DomainSpecificRubrics`](https://docs.ragas.io/en/stable/concepts/metrics/available_metrics/rubrics_based/) and `InstanceSpecificRubrics` for custom evaluation
- 1-5 score descriptions for different evaluation criteria
- Integration with LangChain, LlamaIndex
- Multi-turn conversation evaluation

**Limitations:**
- Primarily optimised for RAG; may need adaptation for general critique evaluation
- Library-style integration requires custom code

**Best for:** Teams building RAG applications or wanting flexible rubric-based evaluation.

### Commercial Platforms

#### LangSmith
**Focus:** LangChain ecosystem integration

Built by the creators of LangChain, LangSmith offers seamless integration with the popular framework while supporting framework-agnostic workflows.

**Key features:**
- Deep LangChain integration with native tracing
- Granular visibility into multi-step LLM applications
- Dataset management and evaluation results visualisation
- Agent logic inspection and decision-making dissection

**Limitations:**
- Python-first design can feel limiting for JavaScript teams
- Advanced metric summarisation may require custom code
- Tightly coupled to LangChain ecosystem

**Pricing:** Free tier available; paid plans for production use.

**Best for:** Teams already invested in LangChain seeking deep framework visibility.

#### Braintrust
**Focus:** Unified evaluation and monitoring

Braintrust provides strong TypeScript/JavaScript support with automated evaluation workflows.

**Key features:**
- Custom-built database (Brainstore) with 86x faster full-text search
- Easy LLM-as-judge configuration and custom scorers
- Native GitHub Action for PR-integrated eval results
- Enterprise-grade security with self-hosting options

**Limitations:**
- Tracing considered an advanced use case, not core feature
- Less mature production observability

**Pricing:** Free tier available; enterprise plans with self-hosting.

**Best for:** Teams wanting unified evaluation and monitoring with strong TypeScript support.

#### Humanloop
**Focus:** Human-in-the-loop feedback

Humanloop centres on collecting and analysing human judgments on LLM outputs, making it particularly relevant for research evaluation workflows.

**Key features:**
- Large-scale human-in-the-loop evaluation orchestration
- Custom rating templates and expert task routing
- RLHF pipelines connecting feedback to model improvement
- Non-technical collaboration support

**Limitations:**
- Evaluation feels secondary to versioning features
- Developer experience (SDK, local workflows) less polished than competitors

**Pricing:** Commercial; contact for pricing.

**Best for:** Product-led teams prioritising non-technical collaboration and human feedback.

#### Galileo
**Focus:** Enterprise observability with guardrails

Galileo provides comprehensive monitoring with specialised hallucination detection and safety features.

**Key features:**
- 20+ out-of-box evals for RAG, agents, safety, security
- Luna models: distilled LLM judges running at 97% lower cost
- Galileo Protect: real-time hallucination firewall
- Eval-to-guardrail lifecycle for production governance
- NVIDIA NeMo integration

**Limitations:**
- Enterprise pricing
- May be overkill for research/academic use cases

**Pricing:** Enterprise; contact for pricing.

**Best for:** Enterprises requiring real-time guardrails and comprehensive safety evaluation.

#### Weights & Biases (W&B Weave)
**Focus:** Experiment tracking and ML lifecycle

W&B Weave evolved from the original W&B Prompts functionality, providing evaluation within the broader ML experimentation platform.

**Key features:**
- Industry-standard scorers plus custom scorer definition
- Automatic versioning of code, datasets, and scorers
- Trace lineage back to LLMs used
- RAG evaluation with LLM judges
- Human feedback collection for real-life testing

**Limitations:**
- More focused on general ML workflow than LLM-specific evaluation
- Requires familiarity with W&B ecosystem

**Pricing:** Free tier; team and enterprise plans.

**Best for:** Teams already using W&B for ML experimentation.

#### Patronus AI
**Focus:** Regulated industries and safety

Patronus provides automated AI evaluation specifically for enterprise teams in regulated environments.

**Key features:**
- Real-time and offline evaluation workflows
- Adversarial test case generation
- HaluBench for evaluating LLM faithfulness
- Fine-tuned hallucination detection evaluator
- Model comparison capabilities

**Pricing:** Commercial; enterprise-focused.

**Best for:** Regulated industries requiring safety compliance and adversarial testing.

#### Maxim
**Focus:** Enterprise-grade end-to-end platform

Maxim enables AI teams to build applications with speed, reliability, and quality at enterprise scale.

**Key features:**
- Multi-level tracing (session, operation, function levels)
- Advanced agent debugging
- Built-in simulation capabilities
- Playground for rapid engineering

**Pricing:** Commercial; enterprise-focused.

**Best for:** Large AI teams needing end-to-end observability and simulation.

### Specialised LLM Judge Models

#### Atla Selene
**Focus:** Purpose-built evaluation models

Atla has developed state-of-the-art LLM judge models specifically trained for evaluation tasks.

**Selene 1:** Beats frontier models from OpenAI (o-series), Anthropic (Claude 3.5 Sonnet), and DeepSeek (R1) across 11 evaluation benchmarks.

**Selene Mini (8B):** State-of-the-art small language model judge. 2x faster and 3x cheaper than Selene 1. Highest-scoring 8B generative model on RewardBench.

**Key features:**
- General-purpose evaluation: absolute scoring (1-5), classification (yes/no), pairwise preference
- [MCP Server](https://www.atla-ai.com/post/atla-mcp-server) for direct integration
- 30,000+ downloads on Hugging Face and Ollama
- Judge Arena community platform for model comparison

**Best for:** Teams wanting specialised evaluation models rather than prompted general-purpose LLMs.

#### Prometheus / Prometheus 2
**Focus:** Open-source fine-tuned evaluation LLM

Prometheus is a 13B evaluator LLM that can assess any given long-form text based on customised score rubrics.

**Key features:**
- Fine-grained evaluation capability based on custom rubrics
- Feedback Collection dataset: 1K rubrics, 20K instructions, 100K responses
- 72-85% agreement with human judgments on pairwise ranking benchmarks
- Prometheus 2 (7B): Runs on consumer GPUs (16 GB VRAM)
- M-Prometheus (2025): Multilingual judges (3B-14B parameters)

**Availability:** Open weights at [prometheus-eval/prometheus](https://github.com/prometheus-eval/prometheus-eval).

**Best for:** Academic/research use cases wanting free, controllable evaluation without sending data to commercial APIs.

---

## Feature Comparison Table

| Tool | Open Source | LLM-as-Judge | Custom Rubrics | Human-in-Loop | Self-Hosted | Best For |
|------|:-----------:|:------------:|:--------------:|:-------------:|:-----------:|----------|
| **Promptfoo** | Yes | Yes | Yes | Limited | Yes | Developers, red teaming |
| **DeepEval** | Yes | Yes | Yes | Limited | Yes | Pytest-style testing |
| **Langfuse** | Yes (MIT) | Yes | Yes | Yes | Yes | Full control, compliance |
| **Opik** | Yes (Apache 2.0) | Yes | Yes | Yes | Yes | Speed, comprehensive features |
| **Phoenix** | Yes | Limited | Limited | No | Yes | Production observability |
| **Ragas** | Yes | Yes | Yes | No | Yes | RAG evaluation |
| **LangSmith** | No | Yes | Yes | Yes | No | LangChain users |
| **Braintrust** | No | Yes | Yes | Yes | Optional | TypeScript teams |
| **Humanloop** | No | Yes | Yes | **Strong** | No | Human feedback focus |
| **Galileo** | No | Yes | Yes | Yes | No | Enterprise guardrails |
| **W&B Weave** | No | Yes | Yes | Yes | No | ML experiment tracking |
| **Patronus** | No | Yes | Yes | No | No | Regulated industries |
| **Atla Selene** | Model weights | **Purpose-built** | Yes | No | Yes | Specialised judging |
| **Prometheus** | Yes | **Purpose-built** | Yes | No | Yes | Academic/research |

---

## LLM-as-Judge Support Analysis

### Framework Support

Most platforms now support LLM-as-judge evaluation, but implementations vary:

| Platform | Judge Implementation | Calibration Support | Bias Mitigation |
|----------|---------------------|---------------------|-----------------|
| **Promptfoo** | Configurable prompts | Threshold scoring | N/A |
| **DeepEval** | Built-in metrics | Self-explaining scores | Limited |
| **Ragas** | Domain/instance rubrics | Reference-free | N/A |
| **Opik** | Built-in judges | Metric comparison | N/A |
| **Atla Selene** | Fine-tuned model | Task-specific training | Less self-bias |
| **Prometheus** | Fine-tuned model | Custom rubrics | Human-aligned training |

### Academic Research on LLM Judges

Recent research (August 2025) on inter-rater reliability between LLMs and humans found:
- **Substantial agreement (kappa > 0.6)** achievable with optimised prompts and few-shot examples
- Moderate agreement common for complex evaluation themes (kappa ~ 0.55)
- LLMs show tendency toward false positives, requiring enhanced contextual analysis

Key paper: ["Investigation of the Inter-Rater Reliability between Large Language Models and Human Raters in Qualitative Analysis"](https://arxiv.org/abs/2508.14764) (August 2025)

---

## Custom Rubric Support

### Best Options for Research Critique Evaluation

1. **Promptfoo's LLM-Rubric:** Define criteria in YAML with threshold scoring. Simple but effective.

2. **Ragas DomainSpecificRubrics:** Code-based rubric definition with 1-5 score descriptions per criterion.

3. **Prometheus:** Purpose-built for custom rubric evaluation. The Feedback Collection dataset includes 1,000 fine-grained rubrics.

4. **PEARL Framework (Academic):** Integrates Technical, Argumentative, and Explanation-focused rubrics covering factual accuracy, clarity, completeness, originality, dialecticality, and explanatory quality. Defines seven complementary metrics.

### Research on Rubric Design

**"Rubric Is All You Need" (2025):** Found that question-specific rubrics outperform question-agnostic rubrics for logical assessment. Human instructors use specific rubrics; evaluation systems should too.

**Rulers Framework (January 2025):** Identified three failure modes in rubric prompts:
1. Rubric instability (criteria drift due to prompt sensitivity)
2. Unverifiable reasoning (scores unsupported by checkable evidence)
3. Scale misalignment (judge confidence doesn't map to human scoring scale)

---

## Human-in-the-Loop Workflows

### Platform Comparison

| Platform | HITL Features | Expert Routing | RLHF Support |
|----------|---------------|----------------|--------------|
| **Humanloop** | Core focus | Yes | Yes |
| **Langfuse** | Annotation workflows | Limited | No |
| **Opik** | Manual annotation view | Limited | No |
| **Braintrust** | Collaborative review | Limited | No |
| **W&B Weave** | Feedback collection | Limited | No |

### Dedicated RLHF Platforms

For intensive human feedback collection, dedicated platforms may be warranted:

- **Surge AI:** Used by Anthropic for RLHF. Custom task schemas, expert labelers, API/SDK integration.
- **SuperAnnotate:** LLM response refinement with API integration.
- **Label Studio:** Open-source annotation tool; can be configured for RLHF.
- **LXT:** Global workforce (250K+ experts), structured RLHF frameworks.

**Cost context:** Basic annotation tasks cost $0.02-0.09 per object; complex LLM tasks can reach $100 per example.

---

## Small-N Statistical Considerations

### Sample Size Guidance

Research indicates:
- **Minimum ~97 samples** needed for representative population with proportion test (expected proportion 0.50, margin of error 0.10, 95% confidence, 80% power)
- Cohen's kappa calculation requires separate sample size consideration for inter-rater reliability

### Tool Support for Small-N

Most evaluation platforms assume large-scale testing. For small-N research contexts:

1. **Statistical limitations of LLM metrics:** "Statistical methods perform poorly whenever reasoning is required, making them too inaccurate as a scorer for most LLM evaluation criteria."

2. **Recommended approach:** Combine LLM-as-judge (for scalability) with human expert validation (for ground truth on small sample).

3. **Metrics to use:**
   - Cohen's kappa for LLM-human agreement
   - ICC (Intraclass Correlation Coefficient) for multiple raters
   - Gwet's AC2 for skewed distributions (preferred over Krippendorff's alpha)

---

## Open Source vs. Commercial Trade-offs

### Open Source Advantages

| Advantage | Tools |
|-----------|-------|
| **No vendor lock-in** | All open-source options |
| **Data privacy** (local execution) | Promptfoo, DeepEval, Phoenix, Langfuse (self-hosted) |
| **Cost** (no platform fees) | All; note GPU costs for Prometheus/Atla |
| **Customisation** | Full code access |
| **Transparency** | Audit evaluation logic |

### Commercial Advantages

| Advantage | Tools |
|-----------|-------|
| **Managed infrastructure** | LangSmith, Braintrust, Galileo |
| **Support and SLAs** | All commercial platforms |
| **Advanced features** | Galileo (guardrails), Patronus (adversarial) |
| **Faster setup** | Ready-to-use dashboards |

### Hybrid Recommendation for Research Use

For Forethought's research critique evaluation:

1. **Start with open-source** (Promptfoo or DeepEval) for development and iteration
2. **Use Prometheus or Atla Selene Mini** as judge model to avoid commercial API costs
3. **Add Humanloop** if human feedback collection becomes a bottleneck
4. **Consider Langfuse** for production tracing if grader becomes widely used

---

## Academic and Research-Specific Tools

### AI for Peer Review Research

Large-scale studies on LLM feedback for scientific papers found:

- **30.85% overlap** between GPT-4 and human reviewers for Nature journals (comparable to 28.58% human-human overlap)
- **57.4%** of users found GPT-4 feedback helpful/very helpful
- **82.4%** found it more beneficial than feedback from at least some human reviewers

Source: ["Can Large Language Models Provide Useful Feedback on Research Papers?"](https://ai.nejm.org/doi/abs/10.1056/AIoa2400196) (NEJM AI)

### Tools for Systematic Reviews

- **Review Copilot:** Multi-agent architecture for document triage
- **LLMSurver:** Filtration pipelines with LLM embeddings and relevance scoring
- Can reduce manual screening by 60%+ while maintaining 90%+ recall

### Evaluation Framework for Research

**ReviewEval** (EMNLP 2025 Findings): Framework specifically for evaluating AI-generated academic reviews. Worth investigating for research critique applications.

---

## Emerging Tools Worth Watching

### 2025-2026 Developments

1. **Atla Selene:** Rapidly gaining adoption; MCP server integration (April 2025) enables direct Claude/other LLM integration.

2. **Opik Agent Optimizer:** Automated prompt optimisation in public beta (June 2025).

3. **M-Prometheus:** Multilingual judge models for non-English evaluation.

4. **Galileo Protect:** Real-time hallucination firewall for production systems.

5. **Judge Arena:** Community platform for comparing evaluation models head-to-head.

### Trends to Monitor

- **OpenTelemetry standardisation:** Arize leading OTel-based LLM observability standards
- **Eval-to-guardrail pipelines:** Pre-production evals becoming production governance
- **Agent evaluation maturity:** As agentic AI grows, evaluation of multi-step reasoning becoming critical

---

## Recommendations for Forethought Use Case

### Primary Recommendation: Promptfoo + Prometheus/Atla

**Rationale:**
- Promptfoo provides lightweight, code-first evaluation with custom rubrics
- Prometheus 2 (7B) or Atla Selene Mini offers purpose-built evaluation without commercial API costs
- Both run locally, preserving data privacy for unpublished research
- YAML configuration makes iteration fast

### Implementation Approach

```yaml
# Example Promptfoo config for research critique evaluation
prompts:
  - file://prompts/critique_evaluator.txt

providers:
  - id: prometheus-7b
    config:
      model: prometheus-eval/prometheus-7b-v2.0

tests:
  - vars:
      critique: "{{critique}}"
      paper_abstract: "{{abstract}}"
    assert:
      - type: llm-rubric
        value: |
          Evaluate this research critique on:
          1. Argument identification (1-5): Does the critique correctly identify the paper's main claims?
          2. Evidence quality (1-5): Are objections supported with valid reasoning or references?
          3. Constructiveness (1-5): Does the critique suggest improvements, not just problems?
          4. Accuracy (1-5): Are factual claims in the critique correct?
        threshold: 3.5
```

### Alternative: DeepEval for Pytest Integration

If you prefer pytest-style testing integrated into CI:

```python
from deepeval import assert_test
from deepeval.metrics import GEval
from deepeval.test_case import LLMTestCase

critique_metric = GEval(
    name="Research Critique Quality",
    criteria="""
    Evaluate the critique's:
    - Argument identification accuracy
    - Evidence quality and reasoning
    - Constructiveness of feedback
    - Factual accuracy
    """,
    evaluation_params=[LLMTestCaseParams.INPUT, LLMTestCaseParams.ACTUAL_OUTPUT],
)

def test_critique_quality():
    test_case = LLMTestCase(
        input=paper_abstract,
        actual_output=generated_critique
    )
    assert_test(test_case, [critique_metric])
```

### Human Validation Layer

For ground truth establishment with limited researcher time:

1. Use **Humanloop** or **Label Studio** for expert annotation interface
2. Annotate 50-100 critiques to establish inter-rater reliability baseline
3. Calculate Cohen's kappa between LLM judge and expert ratings
4. Target kappa > 0.6 (substantial agreement) before deploying at scale

### Statistical Validation

Given small-N constraints:

1. **Stratified sampling:** Ensure test set covers different paper types, critique lengths, topic areas
2. **Bootstrap confidence intervals:** For uncertainty estimation with limited samples
3. **Disagreement analysis:** Manually review cases where LLM-human ratings diverge > 1 point

---

## Key Resources

### Documentation and Guides

- [Promptfoo LLM-Rubric Documentation](https://www.promptfoo.dev/docs/configuration/expected-outputs/model-graded/llm-rubric/)
- [Ragas Rubric-Based Evaluation](https://docs.ragas.io/en/stable/concepts/metrics/available_metrics/rubrics_based/)
- [DeepEval LLM Evaluation Guide](https://www.confident-ai.com/blog/llm-evaluation-metrics-everything-you-need-for-llm-evaluation)
- [Prometheus GitHub Repository](https://github.com/prometheus-eval/prometheus-eval)
- [Atla Selene Models](https://www.atla-ai.com/selene-models)

### Research Papers

- ["Rubric Is All You Need" (2025)](https://arxiv.org/abs/2503.23989) - Question-specific rubrics for LLM evaluation
- ["Prometheus 2" (2024)](https://arxiv.org/abs/2405.01535) - Open-source evaluation language model
- ["PEARL Framework"](https://www.mdpi.com/2078-2489/16/11/926) - Multi-metric rubric framework
- ["Rulers Framework" (2025)](https://arxiv.org/html/2601.08654) - Addressing rubric instability
- ["LLM-Human Inter-Rater Reliability" (2025)](https://arxiv.org/abs/2508.14764) - Kappa analysis
- ["A Practical Guide for Evaluating LLMs"](https://arxiv.org/html/2506.13023v1) - Evaluation methodology framework

### Comparison Resources

- [Arize: Comparing LLM Evaluation Platforms (2025)](https://arize.com/llm-evaluation-platforms-top-frameworks/)
- [Braintrust: Best LLM Evaluation Platforms (2025)](https://www.braintrust.dev/articles/best-llm-evaluation-platforms-2025)
- [LangWatch vs. LangSmith vs. Braintrust vs. Langfuse (2025)](https://langwatch.ai/blog/langwatch-vs-langsmith-vs-braintrust-vs-langfuse-choosing-the-best-llm-evaluation-monitoring-tool-in-2025)
- [Comet: LLM Evaluation Frameworks Comparison](https://www.comet.com/site/blog/llm-evaluation-frameworks/)
- [Atla: LLM Evaluation Tooling Review](https://www.atla-ai.com/post/llm-evaluation-tooling-review)

---

## Conclusion

For Forethought's research critique evaluation use case, the recommended stack is:

1. **Promptfoo** for evaluation orchestration and custom rubric definition
2. **Prometheus 2 (7B)** or **Atla Selene Mini** as the LLM judge model
3. **Humanloop** or **Label Studio** for expert feedback collection
4. **Custom statistical validation** using Cohen's kappa with bootstrap confidence intervals

This combination provides:
- Full local execution for data privacy
- Purpose-built evaluation models outperforming prompted general LLMs
- Flexible custom rubrics aligned to research critique quality dimensions
- Integrated human validation workflow
- Cost efficiency (no per-evaluation API fees)

The main trade-off is setup complexity compared to fully managed platforms. However, for a research organisation evaluating philosophical and economic research, the control, transparency, and cost benefits of open-source tools outweigh the convenience of commercial alternatives.
