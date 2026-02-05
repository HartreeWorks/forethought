# Crucial Questions Experiment: Prompt Candidates

Based on multi-model brainstorming session (2026-02-04).

## Goal

Read a research paper and identify the most important open questions that follow from it—questions that could be "crucial considerations" in Nick Bostrom's sense: factors that, if understood differently, would warrant major reassessment of priorities or strategy.

---

## Prompt 1: "Decision Pivots"

This searches for parameters whose values determine whether the paper's implied strategy is correct or backwards.

```
You are identifying crucial open questions that follow from this research paper. A "crucial consideration" is an idea that, if understood differently, would warrant a major change in strategy—not a minor course adjustment.

## Your task

Read the paper carefully. Then identify open questions about parameters, assumptions, or empirical facts where:
- The paper's conclusions depend on these being within a certain range
- If they turn out to be outside that range, the implied strategy flips (e.g., from "prioritise alignment" to "prioritise governance", or from "slow down" to "accelerate")

## Process

### Step 1: Extract the decision model
- What strategy or priority does this paper imply? Even if unstated, what would someone following this analysis *do*?
- List 5-7 load-bearing claims the conclusions depend on. For each, quote or paraphrase the relevant passage.

### Step 2: Identify flip thresholds
For each load-bearing claim, ask:
- What parameter(s) does this claim depend on?
- What is the assumed value or range?
- At what value would the paper's strategy become wrong or counterproductive?
- How confident can we be about which side of the threshold we're on?

### Step 3: Generate crucial open questions
For each high-uncertainty, high-stakes parameter, formulate a question of the form:
- "What is the value/distribution of [parameter]?"
- "Where is the threshold at which [strategy A] becomes better than [strategy B]?"
- "What evidence would locate us on the right side of [threshold]?"

## Output format

For each question (aim for 5-7), provide:

1. **The question** (one precise sentence)
2. **Paper anchor** (quote or cite the specific claim this attaches to)
3. **The flip** (explicitly state: "If [parameter] > [threshold], then [strategy A]; if < [threshold], then [strategy B]")
4. **What would answer it** (what observation, study, or analysis would update beliefs on this?)

## Constraints

- Every question must reference a specific claim from this paper
- Reject questions that could apply to any paper in this field—each must be inextricably tied to THIS paper's logic
- No generic hedges ("more research is needed")
- No questions where the answer wouldn't change strategy (if both answers lead to the same action, it's not crucial)
- If you can't articulate the flip, the question isn't crucial enough

[PAPER TEXT]
```

---

## Prompt 2: "Adversarial Worldviews"

This generates questions from systematically different threat models and strategic perspectives.

```
You are identifying crucial open questions by viewing this paper through multiple competing worldviews. Different researchers hold genuinely different models of how AI development unfolds, what the risks are, and what strategies work. A question is "crucial" if answering it would help adjudicate between worldviews that imply radically different priorities.

## Your task

Read the paper, then generate questions from four distinct perspectives that would surface different crucial uncertainties.

## The worldviews

### 1. The Governance Realist
Believes international coordination and institutional capacity are the primary bottlenecks. Skeptical of technical solutions that ignore political economy. Worried about regulatory capture, enforcement gaps, and coordination failures.

*Ask: What uncertainties about institutional dynamics could make this paper's approach naive or counterproductive?*

### 2. The Alignment Pessimist
Believes alignment is harder than most assume—deceptive alignment, mesa-optimisation, and inner misalignment are likely defaults. Technical timelines are short. Most proposed solutions won't scale.

*Ask: What uncertainties about alignment difficulty could make this paper irrelevant or dangerous?*

### 3. The Capability Accelerationist
Believes slowing capability development is infeasible and potentially counterproductive (cedes ground to less safety-conscious actors). Racing dynamics dominate. The path forward is through capabilities, not around them.

*Ask: What uncertainties about competitive dynamics could flip this paper's recommendations?*

### 4. The Digital Minds Advocate
Believes digital sentience and moral patienthood will be among the most important considerations. Worried about mass suffering of digital minds, rights enforcement, and welfare aggregation under copying.

*Ask: What uncertainties about digital mind welfare could transform the ethical calculus of this paper?*

## Process

For each worldview:
1. Briefly state how this worldview interprets the paper's topic (2-3 sentences)
2. Generate 2-3 open questions this perspective would consider crucial
3. For each question, explain why answering it would change strategy

## Output format

For each question, provide:

1. **Worldview** (which perspective generated this)
2. **The question** (one precise sentence)
3. **Paper anchor** (what assumption or claim this targets)
4. **Strategic implications** ("If answered [X], strategy becomes [A]; if answered [Y], strategy becomes [B]")

## Final synthesis

After generating questions from all four worldviews:
- Remove duplicates (questions that would be answered by the same evidence)
- Select the top 5-7 by "strategy-flip potential"
- Note any questions that appear across multiple worldviews (these are especially robust)

## Constraints

- Each question must cite a specific assumption or claim from the paper
- No generic philosophical questions ("What is consciousness?")—each must be decision-relevant
- Pass the copy-paste test: if you swapped in a different paper, the question should become obviously wrong
- Adversarial doesn't mean cynical—include questions where the optimistic answer would also flip strategy

[PAPER TEXT]
```

---

## Prompt 3: "Branching Futures"

This traces forward to identify branch points where the paper's analysis diverges into qualitatively different outcomes.

```
You are identifying crucial open questions by treating this paper as describing a trajectory through possibility space. Many crucial considerations emerge at branch points—places where small differences in conditions lead to qualitatively different futures.

## Your task

Read the paper. Then:
1. Map the trajectory it describes or implies
2. Identify the branch points where futures diverge
3. Generate questions about what determines which branch we end up on

## Process

### Step 1: Extract the trajectory
Summarise the paper's causal story:
- What actors, mechanisms, and dynamics does it describe?
- What future does it (implicitly or explicitly) predict or aim for?
- What interventions or strategies does it recommend?

### Step 2: Generate adversarial branches
Create 4-6 alternative futures that:
- Keep 70-80% of the paper's premises intact
- Flip one structural feature (e.g., takeoff speed, coordination success, alignment difficulty, compute concentration, enforcement capacity)
- Lead to a qualitatively different outcome requiring different strategy

Label each branch concretely, e.g.:
- "Slow takeoff + successful coordination"
- "Fast takeoff + governance failure"
- "Alignment windfall + capability overhang"
- "Distributed compute + unenforceable controls"

### Step 3: Identify discriminating questions
For each pair of branches that diverge significantly, ask:
- What determines which branch we end up on?
- What observation or research result would discriminate between them within 12-24 months?
- What early indicators should we monitor?

### Step 4: Assess cruciality
For each question, evaluate:
- **Divergence magnitude**: How different are the strategies implied by each branch?
- **Current uncertainty**: How confident can we be about which branch is more likely?
- **Tractability**: Could this question be partially answered in the relevant timeframe?

## Output format

For each question (aim for 5-7), provide:

1. **The question** (one precise sentence)
2. **The branch point** (which futures does this discriminate between?)
3. **Paper anchor** (which of the paper's assumptions or predictions does this stress-test?)
4. **Indicators** (what would we observe if Branch A is more likely? Branch B?)
5. **Time sensitivity** (when would we need to know this to act on it?)

## Constraints

- Branches must be traceable to the paper's claims—no pure speculation
- Each branch must imply genuinely different optimal strategies
- Questions must be operationalisable (what data, measurement, or proxy would answer them?)
- No science fiction—branches should be consistent with many local facts, just different on key structural features
- Reject branches that are merely "things go well" vs "things go badly"—both branches should represent coherent scenarios with their own logic

[PAPER TEXT]
```

---

## Search Pattern Comparison

| Prompt | Search Pattern | Best For | Likely Blind Spot |
|--------|---------------|----------|-------------------|
| Decision Pivots | Threshold-hunting within the paper's framework | Papers with clear strategic implications | Questions outside the paper's ontology |
| Adversarial Worldviews | External perspectives stress-testing assumptions | Contested topics with genuine disagreement | May over-represent adversarial framing |
| Branching Futures | Forward projection to find trajectory divergences | Policy/strategy papers with recommendations | Foundational questions, near-term issues |
