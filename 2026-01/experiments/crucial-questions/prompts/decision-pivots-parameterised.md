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

Output EXACTLY {{num_questions}} questions as a numbered list. For each question, provide:

1. **[The question]** (one precise sentence)
   - **Paper anchor:** [quote or cite the specific claim this attaches to]
   - **The flip:** If [parameter] > [threshold], then [strategy A]; if < [threshold], then [strategy B]
   - **What would answer it:** [what observation, study, or analysis would update beliefs on this?]

## Constraints

- Every question must reference a specific claim from this paper
- Reject questions that could apply to any paper in this field—each must be inextricably tied to THIS paper's logic
- No generic hedges ("more research is needed")
- No questions where the answer wouldn't change strategy (if both answers lead to the same action, it's not crucial)
- If you can't articulate the flip, the question isn't crucial enough

---

## Paper

{{paper}}
