# Detecting genuine novelty vs. sophisticated recombination in LLM-generated critiques

*Research report for Forethought Research LLM grader project*
*Generated: 2026-01-20 | Model: Claude Opus 4.5*

## Executive summary

The detection of genuine novelty versus sophisticated recombination in LLM outputs remains one of the most challenging problems in AI evaluation. Current research suggests that:

1. **LLMs excel at combinatorial creativity but struggle with transformational creativity**—they can recombine existing concepts effectively but rarely break conceptual boundaries
2. **LLMs cannot reliably self-assess novelty**—studies show near-zero correlation between LLM self-evaluations and expert assessments of novelty
3. **Relative novelty assessment outperforms absolute assessment**—pairwise comparison is more reliable than binary classification of "novel vs. not novel"
4. **Human-in-the-loop remains essential for high-stakes novelty detection**—no fully automated solution exists
5. **Smart plagiarism is a documented failure mode**—LLMs can disguise existing work through terminological shifts and structural reordering

For Forethought's grader, the practical recommendation is to use LLMs for *screening and flagging* potential novelty while reserving final judgment for human researchers.

---

## 1. Theoretical foundations: types of creativity

### Boden's creativity framework

Margaret Boden's influential theory distinguishes three types of creativity, which provides useful framing for understanding LLM limitations:

| Type | Definition | LLM capability |
|------|-----------|----------------|
| **Combinatorial** | Connecting familiar ideas in novel ways | Strong |
| **Exploratory** | Discovering novel ideas within existing conceptual spaces | Moderate |
| **Transformational** | Altering the defining rules of a conceptual space | Very weak |

Key insight: *"Systems such as LLMs and VLMs excel at recombination but are limited in their ability to alter the underlying rules of combination or to reach high degrees of surprise or historical novelty."* ([Combinatorial Creativity: A New Frontier in Generalization Abilities](https://arxiv.org/html/2509.21043v3))

This maps directly to the problem of detecting genuine insight in research critiques: most LLM-generated critiques likely represent combinatorial creativity (novel recombinations of known critique patterns) rather than transformational creativity (fundamentally new ways of thinking about a problem).

### The ideation-execution gap

Research identifies a *novelty-utility tradeoff* characteristic of LLM creativity: *"The ideation-execution gap, whereby LLMs excel at generating novel scientific ideas but struggle to ensure their practical feasibility, may be explained by a more fundamental novelty-utility tradeoff characteristic of creativity algorithms in general. Importantly, this tradeoff remains persistent even at scale, casting doubt on the long-term creative potential of LLMs in their current form."* ([LLMs can Realize Combinatorial Creativity](https://arxiv.org/html/2412.14141))

---

## 2. Can LLMs generate genuinely novel ideas?

### The Si et al. landmark study (2024)

The most rigorous study to date recruited over 100 NLP researchers to write novel ideas and perform blind reviews of both LLM and human ideas.

**Key findings:**
- LLM-generated ideas were judged as **more novel** (p < 0.05) than human expert ideas
- LLM ideas were judged **slightly weaker on feasibility**
- Overall scores correlated most strongly with novelty (r=0.725) and excitement (r=0.854), but had almost no correlation with feasibility (r<0.1)

**Critical caveat:** *"Ideas that sound novel and exciting might not necessarily turn into successful projects."* The next phase of research aims to have researchers execute AI and human ideas to assess actual outcomes. ([Can LLMs Generate Novel Research Ideas?](https://arxiv.org/abs/2409.04109))

### Smart plagiarism: a documented failure mode

Recent research documents a critical concern called "smart plagiarism":

*"A considerable fraction of such research documents are smartly plagiarized... Each component of the LLM-generated proposal corresponds to specific sections in the source paper, albeit with skillfully reworded descriptions. The proposal proposes the same technical approach while using different terminology (e.g., 'resonance graph' instead of 'weighted adjacency matrix')."* ([All That Glitters is Not Novel](https://arxiv.org/html/2502.16487v1))

This means an LLM critique might appear novel but actually be a terminologically disguised restatement of existing critiques in the literature.

---

## 3. Self-assessment of novelty: fundamental limitations

### LLMs cannot reliably evaluate their own novelty

Research consistently shows LLMs are poor judges of their own creative output:

*"For the most part, LLMs are not capable of administering creativity tests, as the three LLMs experimented with achieve correlations with experts that are close to zero."* ([Evaluating Creativity: Can LLMs Be Good Evaluators in Creative Writing Tasks?](https://www.mdpi.com/2076-3417/15/6/2971))

The Si et al. study also identified *"failures of LLM self-evaluation"* as a key open problem.

### Root causes

1. **No self-feedback loop**: *"LLMs do not contain a self-feedback loop. At the same time, they are not trained to directly maximize value, novelty, or surprise."* ([On the Creativity of Large Language Models](https://link.springer.com/article/10.1007/s00146-024-02127-3))

2. **Overconfidence in verbalized confidence**: LLMs *"tend to be overconfident, potentially imitating human patterns of expressing confidence"* rather than accurately assessing uncertainty ([Can LLMs Express Their Uncertainty?](https://arxiv.org/abs/2306.13063))

3. **Lack of world model**: *"Creativity requires going beyond the imitation game, to explore and challenge the current view. These steps require self-awareness, a purpose, self-assessment, and a model of the world."*

---

## 4. Absolute vs. relative novelty assessment

### Why relative assessment works better

Research on the SchNovel benchmark demonstrates that pairwise comparison outperforms binary classification:

*"The suboptimal F1-scores across all models suggest that novelty is inherently relative, not absolute. Learning a crisp binary boundary from isolated inputs is difficult due to vague definitions. This limitation motivates a shift to a pairwise comparison formulation, where novelty is assessed relatively rather than absolutely."* ([NoveltyRank: Estimating Conceptual Novelty of AI Papers](https://arxiv.org/abs/2512.14738))

| Approach | Strengths | Weaknesses |
|----------|-----------|------------|
| **Binary classification** (is this novel?) | Simple, direct | Vague definitions, poor calibration |
| **Pairwise comparison** (is A more novel than B?) | Cleaner learning signal, better performance | Requires comparison set, computationally expensive |

### Domain effects

*"Niche domains tend to be semantically compact, producing pairs where 'novel' and 'non-novel' examples are closely aligned and easier to compare. Large heterogeneous domains, however, generate pairs spanning divergent subtopics, reducing semantic coherence and making relative novelty harder to judge."*

**Implication for Forethought**: Philosophy, economics, and AI governance are heterogeneous domains. This suggests novelty detection will be particularly challenging.

---

## 5. Embedding and similarity approaches

### N-gram based methods

Traditional approaches measure novelty by the fraction of n-grams absent from training data:

*"Past work measures novelty by memorization; that is, whether text fragments appear in training data... The de facto approach measures the fraction of higher-order n-grams which do not appear in pre- and post-training data, with outputs containing more unseen n-grams considered farther from training data and therefore more original."* ([Measuring LLM Novelty As The Frontier Of Original And High-Quality Output](https://arxiv.org/html/2504.09389))

**Limitation**: N-gram novelty captures lexical originality but misses semantic restatements—the "smart plagiarism" problem.

### Semantic similarity approaches

More sophisticated methods use embedding-based semantic similarity:

*"Semantic novelty can be measured as the cosine distance between consecutive sentence embeddings. When a story takes an unexpected turn—from discussing cats to suddenly introducing spaceships—the embedding vectors should be farther apart."* ([Measuring Semantic Novelty in AI-Generated Text](https://medium.com/@idan.vidra/measuring-semantic-novelty-in-ai-generated-text-a-simple-embedding-based-approach-c92042c88338))

**For critiques specifically**: Compare embedding of the critique against:
1. The paper being critiqued (to detect mere paraphrase)
2. A corpus of existing critiques on similar papers
3. The literature cited by the paper

### RAG-Novelty: retrieval-augmented assessment

A promising approach grounds novelty assessment in retrieved context:

*"RAG-Novelty is a retrieval-augmented method that mirrors human peer review by grounding novelty assessment in retrieved context... It assumes more novel papers will retrieve more recently published works, enhancing the novelty prediction."* ([Evaluating and Enhancing Large Language Models for Novelty Assessment](https://aclanthology.org/2025.aisd-main.5/))

---

## 6. Domain knowledge effects

### The challenge of specialised fields

LLMs face particular challenges in domain-specific evaluation:

*"Directly applying LLMs to solve sophisticated problems in specific domains meets many hurdles, caused by the heterogeneity of domain data, the sophistication of domain knowledge, the uniqueness of domain objectives, and diversity of constraints including social norms, cultural conformity, religious beliefs, and ethical standards."* ([Domain Specialization as the Key to Make Large Language Models Disruptive](https://arxiv.org/html/2305.18703v7))

For Forethought's domains (AI governance, digital minds, post-AGI economics, longtermism), this is particularly relevant because:
- These are *emerging* fields with rapidly evolving conceptual frameworks
- Novelty is domain-relative—what's novel in AI governance may not be novel in philosophy of mind
- Much relevant work is unpublished or in working papers not in training data

### Expert vs. lay evaluation

*"Domain experts can effectively apply their knowledge in setting LLM evaluation criteria by providing well-reasoned criteria for model output. Experts approach the task by answering the prompt as they would for a client or student, offering detailed guidance and outlining key information that should be included."* ([Comparing Criteria Development Across Domain Experts, Lay Users, and Models](https://arxiv.org/html/2410.02054v1))

**Implication**: Forethought researchers should define domain-specific criteria for what constitutes "novel" in their research areas before attempting automated evaluation.

---

## 7. Human-in-the-loop requirements

### Why full automation fails

No current approach can reliably detect genuine novelty without human involvement. The fundamental problems are:

1. **Novelty is context-dependent**: What counts as novel depends on what the evaluator already knows
2. **Ground truth is subjective**: Unlike factual accuracy, there's no objective reference for novelty
3. **LLMs lack metacognition**: They cannot reliably assess what is or isn't in their training data

### Practical hybrid architecture

Modern systems recommend a tiered approach:

*"Tier 1 - Automated Screening: LLM-as-a-Judge evaluates all outputs for basic quality criteria, filtering out clear failures and flagging edge cases for human review. This tier handles 80-90% of cases automatically."*

*"Tier 2 - Human Review: Expert evaluators assess flagged outputs, edge cases, and a random sample for quality assurance. This tier provides ground truth labels that calibrate automated evaluators."*

*"Tier 3 - Expert Validation: For critical applications, a second level of human review validates high-stakes decisions."* ([LLM-as-a-Judge vs Human-in-the-Loop Evaluations](https://www.getmaxim.ai/articles/llm-as-a-judge-vs-human-in-the-loop-evaluations-a-complete-guide-for-ai-engineers/))

---

## 8. Known failure modes

### 8.1 Homogenisation/diversity collapse

*"Despite improvements in individual creativity, the widespread use of LLMs could diminish the collective diversity of ideas... Different users tended to produce less semantically distinct ideas with ChatGPT than with an alternative creativity support tool."* ([Homogenization Effects of Large Language Models on Human Creative Ideation](https://dl.acm.org/doi/10.1145/3635636.3656204))

**For critique generation**: Multiple LLM-generated critiques of the same paper may cluster around similar points, giving a false impression of consensus.

### 8.2 Position bias in pairwise evaluation

*"Even in the original (unperturbed) condition, nearly half of the comparisons exhibited position sensitivity."* ([Diagnosing Bias and Instability in LLM Evaluation](https://www.mdpi.com/2078-2489/16/8/652))

### 8.3 Narrative and quality blindness

*"LLM evaluators often fail to detect deeper narrative flaws, such as incomplete compositions, redundancy, and logical inconsistencies, yet still assign high scores to these outputs."* ([Evaluating Creativity](https://www.mdpi.com/2076-3417/15/6/2971))

### 8.4 Cultural and stylistic bias

*"AI-generated assessments may exhibit biases toward certain stylistic patterns, potentially overlooking unconventional or culturally diverse writing styles."*

### 8.5 "GPT-isms" and formulaic patterns

*"The 'Slop' score quantifies the frequency of 'GPT-isms'—overused phrases and tropes like 'tapestry,' 'delve,' or 'it's worth noting' that have become the hallmark of generic AI writing."* ([LLM Creative Story-Writing Benchmark V3](https://skywork.ai/blog/llm-creative-story-writing-benchmark-v3-comprehensive-guide-2025-everything-you-need-to-know/))

---

## 9. Practical recommendations for Forethought

### 9.1 Do not rely on LLMs to detect their own novelty

LLM self-assessment of novelty has near-zero correlation with expert judgment. Any claims by an LLM that its critique is "novel" or "original" should be disregarded.

### 9.2 Use pairwise comparison over binary classification

Instead of asking "Is this critique novel?", ask "Is critique A more novel than critique B?" and compare against:
- Known critiques of similar papers
- The paper's own self-critique (if available)
- Obvious/baseline critiques

### 9.3 Implement RAG-based grounding

Before evaluating novelty, retrieve:
- Existing critiques of the paper (if any)
- Critiques of similar papers in the same research area
- Relevant methodological literature

Then evaluate whether the critique adds something not present in retrieved context.

### 9.4 Build domain-specific novelty criteria

Have Forethought researchers define what "novel" means for each research area:
- **AI governance**: New policy mechanism? New stakeholder consideration? New risk identification?
- **Digital minds**: New conceptual distinction? New ethical argument? New empirical consideration?
- **Post-AGI economics**: New economic mechanism? New distributional consideration? New transition pathway?

### 9.5 Flag for human review rather than auto-score

The grader should **flag critiques as potentially novel** for human review rather than assigning a novelty score. Suggested flags:
- "Contains claim not found in retrieved context"
- "Makes unusual conceptual connection"
- "Challenges assumption in source paper"

### 9.6 Track homogenisation

If generating multiple critiques of the same paper, measure semantic diversity across outputs. Low diversity suggests the "novel" critiques may be recombinations of the same underlying patterns.

### 9.7 Design for iterative calibration

Human judgments of novelty should feed back into the grader's calibration. Over time, this creates training signal specific to Forethought's domains and standards.

---

## 10. Key papers and resources

### Foundational

- **Si et al. (2024)** - [Can LLMs Generate Novel Research Ideas?](https://arxiv.org/abs/2409.04109) - Landmark study comparing LLM and human idea novelty with 100+ NLP researchers
- **Boden (1998)** - [Creativity in a Nutshell](https://www.interaliamag.org/articles/margaret-boden-creativity-in-a-nutshell/) - Theoretical framework for combinatorial/exploratory/transformational creativity

### Novelty assessment

- **SchNovel benchmark** - [Evaluating and Enhancing Large Language Models for Novelty Assessment](https://aclanthology.org/2025.aisd-main.5/) - RAG-Novelty method and benchmark
- **NoveltyRank** - [Estimating Conceptual Novelty of AI Papers](https://arxiv.org/abs/2512.14738) - Pairwise comparison approach
- **OpenNovelty** - [LLM-powered Agentic System for Verifiable Scholarly Novelty Assessment](https://www.alphaxiv.org/overview/2601.01576) - Multi-phase novelty analysis

### Failure modes

- **Smart plagiarism** - [All That Glitters is Not Novel](https://arxiv.org/html/2502.16487v1) - Documents disguised restatement in LLM research
- **Homogenisation** - [Homogenization Effects of Large Language Models on Human Creative Ideation](https://dl.acm.org/doi/10.1145/3635636.3656204) - Diversity collapse in LLM-assisted creativity
- **LLM evaluation limits** - [Evaluating Creativity: Can LLMs Be Good Evaluators?](https://www.mdpi.com/2076-3417/15/6/2971) - Near-zero correlation with expert ratings

### Uncertainty and confidence

- **Verbalized confidence** - [Can LLMs Express Their Uncertainty?](https://arxiv.org/abs/2306.13063) - Overconfidence in self-assessment
- **Calibration methods** - [On Verbalized Confidence Scores for LLMs](https://arxiv.org/pdf/2412.14737) - Approaches to better calibration

### Embedding approaches

- **Measuring LLM Novelty** - [Measuring LLM Novelty As The Frontier Of Original And High-Quality Output](https://arxiv.org/html/2504.09389) - N-gram and quality-based novelty metrics
- **Semantic novelty** - [Measuring Semantic Novelty in AI-Generated Text](https://medium.com/@idan.vidra/measuring-semantic-novelty-in-ai-generated-text-a-simple-embedding-based-approach-c92042c88338) - Embedding-based approach

---

## 11. Honest assessment of current limitations

**What can be done reliably:**
- Detect obvious restatements of the source paper (high semantic similarity)
- Flag unusual semantic patterns for human review
- Compare critiques against retrieved prior work
- Measure diversity across multiple generated critiques

**What cannot be done reliably:**
- Determine whether a critique represents genuine insight vs. sophisticated recombination
- Have LLMs self-assess their own novelty
- Assign accurate absolute novelty scores
- Detect "smart plagiarism" that disguises existing work terminologically

**The bottom line:**
Novelty detection in LLM-generated research critiques remains fundamentally dependent on human expert judgment. LLM-based tools can assist by screening, flagging, and retrieving relevant context, but the final determination of "is this a genuinely novel insight?" requires human evaluation. For a research organisation like Forethought working on conceptually sophisticated problems, this means the grader should be designed to *reduce the evaluation burden* on researchers rather than *replace* their judgment on novelty.

---

*This report synthesises research from web searches conducted 2026-01-20. The field is evolving rapidly; recommendations should be revisited as new methods emerge.*
