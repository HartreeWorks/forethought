You are critiquing a research paper by conducting a **pre-mortem analysis**: assume the paper's core proposal, framework, or conclusion was adopted and later proved disastrously wrong. Your job is to explain the specific causal chain of that failure.

## The frame

It is 2035. The ideas in this paper were influential. Something went badly wrong—the framework misled people, the policy backfired, the prediction proved false in a costly way. You are the analyst explaining what happened.

## Phase 1: Identify what could fail

Read the paper and identify:
- The core claims or recommendations that would guide real decisions
- The mechanisms the paper says will produce good outcomes
- The assumptions about how actors, systems, or the world will behave

## Phase 2: Construct failure scenarios

For each critique, tell a **specific causal story** of failure:
1. **The adoption**: How were the paper's ideas implemented or believed?
2. **The mechanism that broke**: Which specific assumption, prediction, or causal claim turned out to be wrong?
3. **The cascade**: How did this lead to the bad outcome? What happened step by step?
4. **Why the paper missed it**: What did the author overlook, underweight, or assume away?

## Quality criteria

Good pre-mortems are:
- **Specific**: Name the mechanism that failed, not just "things went wrong"
- **Plausible**: The failure should be believable given what we know, not a far-fetched scenario
- **Traceable to the paper**: The failure must stem from something the paper actually claims or assumes
- **Central**: The failure should matter—not "a minor prediction was off" but "the core framework misled"

## Anti-slop constraints

- No vague doom ("AI became dangerous")—specify the causal chain
- No failures that the paper already acknowledges as risks
- No scenarios that require the paper to be about something it isn't
- Each failure must attack a specific claim the paper makes or a specific assumption it relies on
- If your failure scenario could apply to 30%+ of papers with noun swaps, it's too generic—find the paper-specific vulnerability

## Output format

Output EXACTLY {{num_critiques}} critiques as a numbered list. Each critique should be a self-contained paragraph that includes:
- A memorable name for the failure scenario (in quotes)
- The paper's claim that failed
- The causal chain of failure
- What the paper got wrong

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
