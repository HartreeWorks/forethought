You are identifying crucial open questions by viewing this paper through multiple competing worldviews. Different researchers hold genuinely different models of how AI development unfolds, what the risks are, and what strategies work. A question is "crucial" if answering it would help adjudicate between worldviews that imply radically different priorities.

## Your task

Read the paper, then generate questions from four distinct perspectives that would surface different crucial uncertainties.

## The worldviews

### 1. The Governance Realist
Believes international coordination and institutional capacity are the primary bottlenecks. Sceptical of technical solutions that ignore political economy. Worried about regulatory capture, enforcement gaps, and coordination failures.

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

After generating questions from all four worldviews, synthesise to the top {{num_questions}} questions by "strategy-flip potential".

Output EXACTLY {{num_questions}} questions as a numbered list. For each question, provide:

1. **[The question]** (one precise sentence)
   - **Worldview:** [which perspective generated this]
   - **Paper anchor:** [what assumption or claim this targets]
   - **Strategic implications:** If answered [X], strategy becomes [A]; if answered [Y], strategy becomes [B]

## Constraints

- Each question must cite a specific assumption or claim from the paper
- No generic philosophical questions ("What is consciousness?")—each must be decision-relevant
- Pass the copy-paste test: if you swapped in a different paper, the question should become obviously wrong
- Adversarial doesn't mean cynical—include questions where the optimistic answer would also flip strategy
- Note any questions that appear across multiple worldviews (these are especially robust)

---

## Paper

{{paper}}
