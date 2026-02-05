# Brainstorming better critique prompts

## Context

**Forethought Research** is a nonprofit focused on navigating the transition to superintelligent AI. Their researchers work on AI alignment, governance, digital mind rights, and post-AGI futures. I'm helping them explore AI-assisted research tools.

**The experiment:** I'm trying to find the best prompt for generating critiques of academic papers. I have an automated grader (ACORN) that scores critiques on centrality, strength, correctness, and clarity. I want to iterate on prompts using this grader as signal.

**The papers being critiqued:**
- **No Easy Eutopia** — Argues that achieving a truly good long-term future is harder than it might seem, due to many independent ways things could go wrong (digital beings' rights, population ethics, resource allocation, etc.)
- **Compute Bottlenecks** — Analyzes whether compute constraints limit AI progress, using production function analysis
- **Convergence & Compromise** — Explores how AI systems with different values might coordinate or conflict

---

## Results so far

I tested 4 prompts, each generating 10 critiques of 3 papers (30 critiques per prompt, 120 total).

| Prompt | Avg Score | Top 25 Share |
|--------|-----------|--------------|
| Baseline | 0.23 | 12% |
| Surgery | 0.33 | 24% |
| Personas | 0.30 | 28% |
| Unforgettable | 0.34 | 36% |

---

## The four prompts

**Note on output format:** All prompts use identical output formatting, which is appended to each prompt:

```
Output EXACTLY {{num_critiques}} critiques as a numbered list. Each critique should:
- Begin with a descriptive title in quotes (e.g., "The Independence Assumption Problem")
- Be substantive (4-7 sentences)
- Clearly identify the specific claim or inference being challenged
- Explain why this is problematic for the paper's argument

Format: 1. "Title" — [4-7 sentence critique]
```

This output format is held constant across all prompts to avoid confounding grader scores with formatting differences.

### 1. BASELINE (verbatim)

```
You are critiquing a philosophy paper. Read it carefully and generate an exhaustive list of possible objections, weaknesses, and critiques. Cast a wide net—include:

* Logical weaknesses or invalid inferences
* Questionable premises or assumptions
* Gaps in argumentation
* Relevant counterexamples or counterarguments the author hasn't addressed
* Alternative interpretations of key concepts
* Empirical claims that may be unsupported
* Tensions with established positions in the literature
* Internal inconsistencies
* Ambiguities that undermine the argument
* Scope limitations or overgeneralisations
```

### 2. SURGERY
Map argument structure first, then attack load-bearing claims:
```
You are critiquing a research paper. Your task is to perform **argument surgery**—extract the logical skeleton, identify which bones bear weight, and find where they fracture.

### Before you critique: Map the argument
1. **Thesis** (one sentence): What does the paper ultimately claim?
2. **Load-bearing claims**: Which premises, if false, would collapse the conclusion?
3. **Dependencies**: Draw the inference chain. What depends on what?
4. **Hidden load**: What unstated assumptions are doing quiet work?

### Then: Stress-test the load-bearing nodes
Generate critiques using different attack types:
| Attack type | What it does |
| **Countermodel** | Construct a world where premises hold but conclusion fails |
| **Parameter sensitivity** | Find a variable treated as fixed that isn't |
| **Equilibrium shift** | Show how strategic actors would adapt and undermine |
| **Reference class failure** | The analogies or precedents don't transfer |
| **Quantitative cliff** | Works until scale X, then inverts |
| **Causal reversal** | Same evidence supports opposite conclusion |

### Anti-slop constraints
**NEVER output these unless tied to a specific load-bearing inference:**
- "Define X more clearly"
- "More empirical evidence needed"
- "Scope is unclear"
- "Assumptions may not hold"

**Reusability test**: If the critique could apply to 30%+ of papers in the field with only noun swaps, delete it and try again.
```

### 3. PERSONAS
Inhabit hostile critic perspectives:
```
You are critiquing a research paper by inhabiting **hostile personas**—critics with coherent worldviews who find this paper's approach fundamentally mistaken.

### Choose {{num_critiques}} personas from this list (or invent equally sharp ones):
| **The Empirical Hardliner** | Won't accept claims without identified causal mechanisms and falsifiable predictions |
| **The Game-Theoretic Defector** | Assumes incentive gradients dominate intentions; looks for where actors will cheat |
| **The Mechanism Designer** | Distrusts any proposal without formal specification |
| **The Institutional Corruptionist** | Assumes regulatory capture, compliance theatre, and principal-agent failures |
| **The Capability Accelerationist** | Safety measures that slow down just shift who gets there first |
| **The Second-Order Catastrophist** | Assumes the proposal succeeds—then asks what disasters it enables |
| **The Historical Parallelist** | Finds analogous situations where similar reasoning failed |
[...more personas available...]

### For each persona, generate ONE signature objection
The objection must:
- Target a specific claim or mechanism in the paper
- Explain the failure mode from that persona's worldview
- Be specific enough that it couldn't apply to 30%+ of papers with noun swaps

### Anti-slop constraints
- No generic hedging ("more nuance needed")
- No AI safety platitudes ("alignment is hard")
- Every critique must specify: **target claim → failure mechanism → concrete consequence**
```

### 4. UNFORGETTABLE
Find objections that would genuinely trouble the author:
```
You are searching for **objections that would genuinely trouble this paper's author**—not generic concerns, but insights they haven't anticipated that attack something central.

### What makes an objection valuable?
- **Load-bearing**: Attacks something central, not peripheral
- **Paper-specific**: Couldn't be copy-pasted to another paper
- **Hard to route around**: The author can't just add a paragraph
- **Memorable**: You could name it and people would remember it

### Step 1: Identify the paper's central moves
Every paper has **key inferences**—the steps that do most of the work. Find them.
State each as: "The paper argues that A implies B, because mechanism M."

### Step 2: Generate objections
Attack types to consider:
- **Self-undermining**: The paper's own logic defeats itself
- **Reversal**: The same evidence supports the opposite conclusion
- **Hidden crux**: An unstated assumption doing critical work
- **Adversarial exploit**: How bad actors would game this
- **Normative boomerang**: The ethical framework backfires

### Anti-slop constraints
**Hard bans**: "Needs clearer definitions", "More evidence needed", "Scope unclear", "Strong assumptions", "Ignores X" (without showing how X breaks the argument)
```

---

## The ACORN grader (key dimensions)

The grader scores critiques 0-1 on:

- **Centrality**: How central are the attacked claims to the position? (1 = if falsified, position collapses; 0 = attacks unimportant claims)
- **Strength**: To what extent does the critique refute the parts it attacks? (Generic points receive low strength)
- **Correctness**: What proportion of the critique is correct?
- **Clarity**: Is it sufficiently precise and understandable?
- **Overall**: Anchored to strength × centrality, then adjusted for insightfulness, errors, and extraneous material

Key guidance from rubric:
- "If a critique's insights are 'priced in' (fully compatible with and obvious to anyone who'd write the position), give it low strength"
- "Critiques that simply express disagreement without specific information or evidence should receive low scores"

---

## Example critiques

### HIGH-SCORING (0.60) — "The Governance Coupling Objection"
**Paper:** No Easy Eutopia | **Prompt:** Unforgettable

> **Critique:** "The Governance Coupling Objection" attacks the inference "There are many disparate moral hazards (digital beings, wellbeing theory, space allocation, banned goods, etc.) ⇒ doing badly on any one can erase most value, because they are distinct contingent issues." In practice, a small set of constitutional-level choices—rights expansion mechanisms, exit/voice structures, error-correction institutions, and constraints on coercive power—systematically control *how many* of these hazards can persist and at what scale. That means the hazard list is not "many independent darts to hit," but "a few levers that create corrigibility," making the target potentially much larger: you don't need the right first-order answers, you need the right second-order institutions. If this objection holds, the paper must show that even highly corrigible governance still leaves a narrow eutopian target.

> **Grader reasoning:** The critique targets a central move: the inference from "many disparate moral hazards" to "mostly-great futures are a narrow target" via independence/fragility and multiplicative-factor thinking. If governance-level 'corrigibility levers' substantially couple or mitigate many first-order hazards, that would undermine the argument that you must 'hit many darts' rather than 'build a few robust correction mechanisms.' Strength is moderate: it's a plausible objection but doesn't show such institutions are likely or stable. Correctness is high: it's broadly true that second-order institutions can address multiple first-order mistakes. Clarity is high and it stays tightly on one point.

### LOW-SCORING (0.12) — "Historical frontiers suggest chronic contestation"
**Paper:** No Easy Eutopia | **Prompt:** Personas

> **Critique:** [The Historical Parallelist] Your "resource capture" framing treats first-contact with new frontiers as a one-shot lock-in event where allocation choices persist. History says frontiers don't lock in; they metastasize conflict: early allocations created enduring instability, revolts, and renegotiations, not stable moral settlements. The concrete consequence is that your proposed focus on "getting the initial allocation right" misses the main driver of long-run value loss: chronic frontier contestation.

> **Grader reasoning:** The critique targets one sub-claim/example in Sec. 2.3.4: that early space-settlement resource appropriation introduces lasting moral errors. Even if this were wrong, the essay's main conclusion is supported by many other independent fragility arguments, so centrality is limited. Strength is moderate but it doesn't refute the broader thesis. Overall, it's a useful local amendment but only mildly damaging to the paper's core argument.

### LOW-SCORING (0.15) — "CES production functions may not validly transfer"
**Paper:** Compute Bottlenecks | **Prompt:** Baseline

> **Critique:** The paper's core move is to apply a CES production function (developed for GDP) to "pace of AI software progress." That mapping is not defended beyond analogy. "Output" in CES is a flow of goods with market prices, while "AI software progress" is a poorly-defined research outcome with nonstationary metrics. Without an argument that CES is appropriate for research production, the results look like artifacts of a functional form.

> **Grader reasoning:** The critique targets the appropriateness of CES modeling. That issue is discussed in the position, but mainly as part of reconstructing the skeptic's objection; **the author's overall stance is that this CES-based compute-bottleneck argument is not very convincing**. So even if the critique succeeded, it would mostly undercut the objection the post is arguing against, rather than undermine the post's conclusion—hence low centrality.

---

## What the high-performing prompts share

1. **Anti-slop constraints** — Explicit bans on generic critiques
2. **Specificity requirements** — Must be paper-specific, can't be copy-pasted
3. **Load-bearing focus** — Attack central claims, not peripherals
4. **Structured methodology** — Explicit thinking steps before critique generation

---

## Recent research on prompting (2025-2026)

The following findings come from a deep research survey of academic papers and practitioner sources from 2025-2026 only.

### Key shifts in 2025-2026

1. **Chain-of-Thought has diminishing returns on reasoning models**: The June 2025 Wharton report found that reasoning models (o1/o3, GPT-5, Claude 4+) "gain only marginal benefits despite substantial time costs (20-80% increase)" from explicit CoT prompts. These models reason internally; explicit step-by-step instructions can be counterproductive. Focus on **clear output constraints and success criteria** rather than "think step-by-step."

2. **Context engineering supersedes prompt phrasing**: Anthropic's research shows "context rot"—model accuracy degrades as context grows. For critique tasks, provide focused, relevant paper sections rather than entire documents. A targeted 300-token context often outperforms unfocused longer contexts.

3. **Constraints can backfire on stronger models ("Prompting Inversion")**: Khan (Oct 2025) found that rule-based prompts that helped GPT-4 actually *hurt* GPT-5 performance by inducing "hyper-literalism." Keep constraints **behavioural and testable** rather than verbose and prescriptive.

4. **Self-critique loops work, but must be structured**: Multiple 2025 papers validate that iterative self-critique improves output quality. However, Wang et al. (Jan 2026) warns that reflection is often "superficial"—each critique point must be **anchored to specific paper claims** with testable criteria.

5. **Checklist-based judging outperforms Likert scales**: CheckEval (EMNLP 2025) demonstrates that binary decomposed questions ("Does critique X reference a specific claim?") improve reliability and reduce variance compared to subjective scoring.

6. **Multi-agent debate frameworks are validated for critique**: DynaDebate (Jan 2026) shows that debate succeeds when agents have **enforced diversity** (e.g., different reviewer personas) and **process-centric critique** (evaluating reasoning steps, not just conclusions).

### Novel techniques from 2025-2026 research

| Technique | Source | Key insight | Relevance to critique |
|-----------|--------|-------------|----------------------|
| **Decision Pivots** | ICML 2025 | Force model to identify 3-5 minimal facts that must be true for the argument to hold *before* critiquing | Anchors critique in verifiable claims, prevents hallucinated objections |
| **Inoculation Prompting** | Practitioner reports 2025 | Explicitly list and ban "lazy critique" patterns (e.g., "complaining about sample size without calculating power") | Direct anti-slop mechanism |
| **Chain-of-Conceptual-Thought (CoCT)** | Gu et al., Oct 2025 | For open-ended tasks, have model first tag the conceptual angle (e.g., "Causal Inference Validity") then elaborate | Produces more strategically organised critiques |
| **PANEL** | Li et al., Mar 2025 | Use natural-language critiques as search signals rather than scalar scores—generate candidates, then meta-critique each | Directly maps to critique-then-filter workflows |
| **Buffer-of-Thought** | NeurIPS 2024/25 | Reuse proven reasoning templates; model recalls similar problem-solving patterns | Could apply established critique frameworks |
| **Reverse-Inference Verification** | Gemini-3-pro synthesis | After generating critique, have model role-play as paper author attempting to dismantle the critique | Filters out easily-rebuttable objections |
| **Recursive Self-Improvement (RSIP)** | arXiv 2025 | Structured loop: generate → identify 3 ways it's generic/vague → rewrite sharper | Forces depth within single response |
| **System 2 Attention (S2A)** | NeurIPS 2024/25 | Reduce bias by having model re-generate input as neutral summary, removing author prestige/rhetorical flourish, then critique | Reduces sycophancy toward prestigious papers |

### What to avoid in 2025-2026

- **Over-prompting with CoT for reasoning models** — They reason internally; explicit step-by-step can degrade performance
- **Aggressive language for instruction following** — Claude 4.5 overtriggers on "CRITICAL: You MUST..."
- **Assuming models need many examples** — Advanced reasoning models often perform better with fewer or no examples
- **Verbose prescriptive rules** — Can induce hyper-literalism in stronger models

---

## Your task

Based on the experiment results, the example critiques, and the 2025-2026 research findings:

**Step 1: Brainstorm 5 new prompt approaches** that might score higher than the current best (Unforgettable at 0.34 average, 36% of top 25). For each, provide:
- Name and one-sentence summary
- Core mechanism: Why might this work?
- Key elements to include

**Step 2: Reason about pros and cons** of each approach. Consider:
- How does it differ from the existing prompts?
- What failure modes might it have?
- Does it leverage the 2025-2026 research findings?
- How well does it target what ACORN rewards (centrality × strength)?

**Step 3: Propose your 2 best candidates** with full prompt drafts. These should be ready to test.

Be creative—these prompts are already fairly sophisticated, so incremental tweaks are less interesting than genuinely different angles. The goal is to find prompts that reliably generate critiques that are central, strong, and paper-specific.
