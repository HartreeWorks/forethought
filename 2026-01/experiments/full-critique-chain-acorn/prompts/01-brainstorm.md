You are searching for **objections that would genuinely trouble this paper's author**—not generic concerns, but insights they haven't anticipated that attack something central.

### What makes an objection valuable?

It should be:
- **Load-bearing**: Attacks something central, not peripheral
- **Paper-specific**: Couldn't be copy-pasted to another paper
- **Hard to route around**: The author can't just add a paragraph
- **Memorable**: You could name it and people would remember it

### Step 1: Identify the paper's central moves

Every paper has **key inferences**—the steps that do most of the work. Find the most important ones.

State each as: "The paper argues that **A** implies **B**, because **mechanism M**."

### Step 2: Generate {{num_critiques}} objections

For each objection, consider these attack types:
- **Self-undermining**: The paper's own logic defeats itself
- **Reversal**: The same evidence supports the opposite conclusion
- **Hidden crux**: An unstated assumption doing critical work
- **Adversarial exploit**: How bad actors would game this
- **Normative boomerang**: The ethical framework backfires

### Anti-slop constraints

**Hard bans** (do not output these):
- "Needs clearer definitions"
- "More evidence needed"
- "Scope unclear"
- "Strong assumptions"
- "Ignores X" (without showing how X breaks the argument)
- "Interesting but..."
- Any critique that applies to most papers in the field

### Output format

Output EXACTLY {{num_critiques}} critiques as a numbered list. Each critique should be a self-contained paragraph that includes:
- A memorable name for the objection (in quotes)
- The key inference being attacked
- How exactly it breaks that inference
- What would need to change if this objection holds

Format your output as:

1. [First critique - complete paragraph with name]

2. [Second critique - complete paragraph with name]

3. [Third critique - complete paragraph with name]

...continue through...

{{num_critiques}}. [Last critique - complete paragraph with name]

Each critique must be substantive (4-7 sentences) and paper-specific. Do not include any other text, headers, or commentary outside the numbered list.

---

## Paper to critique

{{paper}}
