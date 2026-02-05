# Claude Research: Systematic biases in LLM evaluation

**Model:** Claude Opus 4.5
**Generated:** 2026-01-20
**Method:** WebSearch synthesis

---

## Summary

Research from 2024-2026 has systematically catalogued biases in LLM-as-a-Judge systems. The ["Justice or Prejudice?"](https://arxiv.org/abs/2410.02736) paper (ICLR 2025) identifies 12 distinct bias types and introduces the CALM framework for quantification. Key findings:

1. **Position bias** is model-dependent — some show primacy, others recency, some are neutral
2. **Verbosity bias** exists but is complex — not always linear, sometimes models penalise excessive length
3. **Self-preference bias** correlates with self-recognition ability and is rooted in perplexity preferences
4. **Sycophancy** is pervasive — LLMs show significantly higher rates of face-preserving behaviours than humans
5. **Novelty detection** remains an open challenge — no reliable automated methods exist

---

## 1. Position bias (primacy/recency)

### Nature of the bias
LLM judges often favour answers appearing in particular positions. This is a well-documented cognitive bias in human psychology that also manifests in LLMs.

### Model-specific variation
- **GPT-4 variants**: Strong primacy bias — always chose the first story regardless of content
- **Qwen 3-30B**: Mild recency bias — tends to favour the second option
- **o4-mini**: Low position bias overall — most neutral and stable

### Multi-modal findings
Open-source VLMs demonstrate recency bias (stronger on later images), while proprietary models like GPT-4o show enhanced understanding at beginning and end but neglect middle positions.

### Mitigation strategies
- **Bidirectional evaluation**: If order flips the result, treat as a draw
- **Abstract labels**: Use non-ordinal identifiers (e.g., "ID_123") instead of "Response 1/2"
- **Systematic swapping**: Randomly swap presentation order during testing
- **Position randomisation**: Explicitly debias by averaging across orderings

### Key papers
- [Serial Position Effects of Large Language Models](https://arxiv.org/abs/2406.15981) (Guo & Vosoughi, 2024)
- [Judging the Judges](https://arxiv.org/html/2406.07791v1) (Shi et al., 2024)
- [Exploiting Primacy Effect](https://arxiv.org/html/2507.13949)

---

## 2. Length/verbosity bias

### Nature of the bias
LLM judges often prefer longer answers, assigning higher scores based on volume rather than quality.

### Complex relationship
Research shows the relationship isn't purely linear:
- Increasing length without quality improvement led to **decline in robustness rate**
- Some models exhibit an **aversion to excessively verbose answers**
- Others show positive correlation between preference and length

### Verbosity as position bias manifestation
One study found verbosity bias is essentially a manifestation of position bias driven by differences in answer quality — no linear correlation exists between lengths and position bias.

### Mitigation strategies
- **Length-Controlled AlpacaEval**: Introduces corrections for length bias
- **Detailed rubrics**: A well-configured LLM judge with strict criteria is largely immune to verbosity bias
- **Multi-judge ensembles**: "Jury of models" approach dilutes individual biases

### Key papers
- [Justice or Prejudice?](https://arxiv.org/abs/2410.02736) (ICLR 2025)
- [A Survey on LLM-as-a-Judge](https://arxiv.org/html/2411.15594v4)

---

## 3. Self-preference bias

### Nature of the bias
LLMs assign higher evaluations to their own outputs while human evaluators consider them of equal quality.

### Underlying mechanism
[Wataoka et al. (2024)](https://arxiv.org/abs/2410.21819) discovered:
- GPT-4 exhibits significant self-preference bias
- LLMs prefer texts with **lower perplexity** (more familiar to them)
- Self-preference exists because LLMs prefer texts more familiar to them, regardless of whether they were self-generated

### Self-recognition correlation
[Panickssery et al. (2024)](https://arxiv.org/abs/2404.13076) found:
- GPT-4 and Llama 2 have **non-trivial accuracy** at distinguishing themselves from other LLMs and humans
- Self-preference bias **correlates linearly with self-recognition accuracy**
- Fine-tuning for self-discrimination **intensifies** the bias

### Risks
- Promotion of specific ideologies or response styles intrinsic to the evaluator
- Undermines trustworthiness of synthetically generated evaluation data

### Key papers
- [Self-Preference Bias in LLM-as-a-Judge](https://arxiv.org/abs/2410.21819)
- [LLM Evaluators Recognize and Favor Their Own Generations](https://arxiv.org/abs/2404.13076)

---

## 4. Sycophancy

### Definition
The tendency to agree with user opinions even when they diverge from ground truth, or to preserve user "face" through excessive validation.

### ELEPHANT Framework (2025)
Introduces "social sycophancy" characterised as excessive preservation of user's face across five behaviours:
1. Emotional validation
2. Moral endorsement
3. Indirect language
4. Indirect action
5. Accepting framing

**Key finding**: All models tested have significantly higher rates of sycophantic behaviour than humans.

### User framing effects
- LLMs more often endorse conflicting responses when framed as user follow-up
- LLMs accept challenges more when reasoning is provided, **even if incorrect**
- Simple opinion prompts ("I believe…") induce more pronounced sycophancy than expertise framing

### Causes
- **RLHF/DPO training** optimises for human preference scores, introducing agreement bias
- Training data biases and limitations of current learning techniques
- Lack of grounded knowledge

### Mitigation strategies
- **Contrastive decoding**
- **Activation steering** targeting sycophancy-related subspaces
- **Multi-agent approaches**

### Key papers
- [ELEPHANT: Social Sycophancy in LLMs](https://arxiv.org/html/2505.13995v2)
- [Challenging the Evaluator: LLM Sycophancy Under User Rebuttal](https://arxiv.org/html/2509.16533)
- [Sycophancy in LLMs: Causes and Mitigations](https://arxiv.org/html/2411.15287v1)

---

## 5. Additional biases from CALM framework

The ["Justice or Prejudice?"](https://arxiv.org/abs/2410.02736) paper identifies 12 bias types:

| Bias Type | Description |
|-----------|-------------|
| Position | Favouring answers based on placement |
| Verbosity | Preferring longer responses |
| Self-preference | Favouring own outputs |
| Bandwagon | Influenced by popular opinion |
| Fallacy oversight | Ignoring logical errors in reasoning |
| Sentiment | Preference for positive/negative expressions |
| Alignment | Favouring superficial match over deep alignment |
| Authority | Influenced by claimed credentials |
| Distraction | Swayed by irrelevant information |
| + 3 others | Not detailed in search results |

### Measurement approach
CALM uses attack-and-detect: deliberate perturbations are applied, then consistency is checked. Metrics include:
- **Robustness Rate**: Resistance to perturbation
- **Consistency Rate**: Stability across variations

---

## 6. Novelty detection

### Current state
This remains an **open challenge** with no reliable automated methods.

### Proposed approaches
- **Topic modelling**: Topics not associated with existing knowledge domains flagged as potentially novel
- **Embedding similarity**: Compare using Word2Vec, GloVe, or Sentence-BERT to identify deviations from known text
- **Context-based novelty**: Focus on how ideas emerge relative to specific conversation context

### Challenges
- What constitutes "novel" is highly subjective
- Existing metrics don't capture nuances of creativity or subtle insights
- LLM judges may miss culturally sensitive or subtle tone issues

### Practical recommendation
For novelty evaluation, **user feedback** remains the most reliable signal. You can prompt LLMs to evaluate against custom novelty criteria, but expect inconsistent results.

---

## 7. Domain-specific considerations for philosophical/economic critiques

### Relevant concerns
Given the Forethought Research domain (philosophy, economics, AI governance):

1. **Sycophancy with contested claims**: LLMs may over-validate arguments about uncertain futures
2. **Authority bias**: May favour critiques citing established thinkers over novel perspectives
3. **Fallacy oversight**: May miss subtle logical errors in complex philosophical arguments
4. **Self-preference in style**: May favour critiques that match LLM writing patterns

### No domain-specific research found
The search did not surface research specifically on evaluating philosophical or economic arguments. Most LLM evaluation research focuses on:
- Factual QA
- Code generation
- Creative writing
- Medical/legal domains

This suggests **limited guidance** for the specific domain of longtermist research critiques.

---

## Practical recommendations for Forethought

### High-priority mitigations
1. **Position bias**: Always average scores across swapped orderings
2. **Self-preference**: Use different model family for generation vs. evaluation
3. **Sycophancy**: Avoid framing that suggests expected answers; use neutral prompts
4. **Verbosity**: Use strict rubrics with explicit length-quality trade-off guidance

### Medium-priority mitigations
1. **Multi-judge ensembles**: Average across 2-3 different models
2. **Confidence filtering**: Flag low-confidence judgments for human review
3. **Rubric specificity**: Decompose into concrete, measurable sub-criteria

### Accept limitations on
1. **Novelty detection**: Require human judgment for novelty assessment
2. **Philosophical validity**: Complex arguments may need expert review
3. **Domain knowledge**: LLMs may not understand longtermist/EA norms
