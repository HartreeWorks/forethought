# Synthesis: Novelty detection in LLM-generated critiques

**Question:** Can we detect genuine novelty vs. sophisticated recombination?

**Sources:** Claude Opus 4.5, OpenAI o3-deep-research (Gemini incomplete due to rate limits)

---

## Core finding

**Novelty detection remains fundamentally dependent on human expert judgment.** No fully automated solution exists that can reliably distinguish genuine insight from sophisticated recombination.

---

## Key findings

### 1. LLMs excel at combinatorial, struggle with transformational creativity

Using Boden's creativity framework:

| Type | Definition | LLM capability |
|------|-----------|----------------|
| **Combinatorial** | Connecting familiar ideas in novel ways | Strong |
| **Exploratory** | Discovering novel ideas within existing spaces | Moderate |
| **Transformational** | Altering the defining rules of a space | Very weak |

**Implication:** Most LLM critiques represent novel recombinations of known critique patterns, not fundamentally new ways of thinking.

### 2. LLMs cannot reliably self-assess novelty

> "For the most part, LLMs are not capable of administering creativity tests, as the three LLMs experimented with achieve correlations with experts that are close to zero."

**Root causes:**
- No self-feedback loop for novelty/surprise
- Overconfidence in verbalized confidence
- Lack of metacognition about training data

**Recommendation:** Disregard any LLM claims that its output is "novel" or "original."

### 3. Relative assessment > absolute assessment

Research on SchNovel benchmark:
- **Binary classification** ("is this novel?") performs poorly
- **Pairwise comparison** ("is A more novel than B?") works better

**Why:** Novelty is inherently relative and context-dependent. Learning a binary boundary from isolated inputs is difficult.

### 4. "Smart plagiarism" is a documented failure mode

> "A considerable fraction of such research documents are smartly plagiarized... The proposal proposes the same technical approach while using different terminology."

LLMs can disguise existing work through:
- Terminological shifts ("resonance graph" instead of "weighted adjacency matrix")
- Structural reordering
- Style transformation

### 5. Domain-specific challenges

For Forethought's domains (AI governance, digital minds, longtermism):
- These are **emerging fields** with rapidly evolving frameworks
- Much relevant work is unpublished or in working papers
- Novelty is domain-relative—what's novel in AI governance may not be novel in philosophy of mind

---

## What can be done reliably

1. **Detect obvious restatements** via semantic similarity to source paper
2. **Flag unusual semantic patterns** for human review
3. **Compare critiques against retrieved prior work** (RAG-based grounding)
4. **Measure diversity** across multiple generated critiques
5. **Relative ranking** of critiques by novelty (pairwise comparison)

## What cannot be done reliably

1. Determine whether a critique represents **genuine insight** vs. sophisticated recombination
2. Have LLMs **self-assess their own novelty**
3. Assign accurate **absolute novelty scores**
4. Detect **"smart plagiarism"** that disguises existing work terminologically

---

## Practical recommendations for Forethought

### 1. Do not automate novelty scoring

Design the grader to **flag critiques as potentially novel** for human review rather than assigning novelty scores.

Suggested flags:
- "Contains claim not found in retrieved context"
- "Makes unusual conceptual connection"
- "Challenges assumption in source paper"

### 2. Use pairwise comparison

Instead of "Is this critique novel?", ask:
- "Is critique A more novel than critique B?"
- Compare against known critiques of similar papers
- Compare against obvious/baseline critiques

### 3. Implement RAG-based grounding

Before evaluating novelty, retrieve:
- Existing critiques of the paper (if any)
- Critiques of similar papers in the same research area
- Relevant methodological literature

Then evaluate whether the critique adds something not in retrieved context.

### 4. Build domain-specific novelty criteria

Have Forethought researchers define what "novel" means for each area:
- **AI governance:** New policy mechanism? New stakeholder consideration?
- **Digital minds:** New conceptual distinction? New ethical argument?
- **Post-AGI economics:** New economic mechanism? New distributional consideration?

### 5. Track homogenisation

If generating multiple critiques of the same paper, measure semantic diversity. Low diversity suggests "novel" critiques may be recombinations of the same patterns.

---

## Honest assessment

**The bottom line:** For a research organisation like Forethought working on conceptually sophisticated problems, the grader should be designed to **reduce the evaluation burden** on researchers rather than **replace** their judgment on novelty. LLMs can assist by screening, flagging, and retrieving relevant context, but final determination requires human evaluation.

---

## Key papers

1. **Si et al. (2024)** - "Can LLMs Generate Novel Research Ideas?" - Landmark study with 100+ NLP researchers
2. **Boden (1998)** - "Creativity in a Nutshell" - Combinatorial/exploratory/transformational framework
3. **SchNovel benchmark** - "Evaluating and Enhancing LLMs for Novelty Assessment"
4. **"All That Glitters is Not Novel"** - Documents smart plagiarism in LLM research
5. **NoveltyRank** - Pairwise comparison approach for novelty estimation
