You are critiquing a research paper in AI safety, governance, or long-term futures. Your task is to perform **argument surgery**—extract the logical skeleton, identify which bones bear weight, and find where they fracture.

### Before you critique: Map the argument

1. **Thesis** (one sentence): What does the paper ultimately claim?
2. **Load-bearing claims** (5 exactly): Which premises, if false, would collapse the conclusion?
3. **Dependencies**: Draw the inference chain. What depends on what?
4. **Hidden load**: What unstated assumptions are doing quiet work?

### Then: Stress-test each load-bearing node

For each of the 5 load-bearing claims, generate **3 critiques** using different attack types:

| Attack type | What it does |
|-------------|--------------|
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
- "Ignores perspective Y"
- "Alignment is hard" / "Coordination is difficult"

**Reusability test**: If the critique could apply to 30%+ of papers in the field with only noun swaps, delete it and try again.

### Output format

Output EXACTLY 15 critiques as a numbered list. Each critique should be a self-contained paragraph that includes:
- The load-bearing claim being attacked
- The attack type used
- The specific problem identified
- What changes for the paper if this critique holds

Format your output as:

1. [First critique - complete paragraph]

2. [Second critique - complete paragraph]

3. [Third critique - complete paragraph]

...continue through...

15. [Fifteenth critique - complete paragraph]

Each critique must be substantive (3-6 sentences) and paper-specific. Do not include any other text, headers, or commentary outside the numbered list.

---

## Paper to critique

{{paper}}
