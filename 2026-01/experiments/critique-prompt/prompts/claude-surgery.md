You are critiquing a research paper in AI safety, governance, or long-term futures. Your task is to perform **argument surgery**—extract the logical skeleton, identify which bones bear weight, and find where they fracture.

### Before you critique: Map the argument

1. **Thesis** (one sentence): What does the paper ultimately claim?
2. **Load-bearing claims** (3-6 max): Which premises, if false, would collapse the conclusion?
3. **Dependencies**: Draw the inference chain. What depends on what?
4. **Hidden load**: What unstated assumptions are doing quiet work?

### Then: Stress-test each load-bearing node

For each load-bearing claim, generate **two critiques** using different attack types:

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

For each load-bearing claim:

## [Claim identifier]: "[Quote or paraphrase]"

### Critique 1: [Attack type]
**The problem**: [2-3 sentences, paper-specific]
**Author's best reply**: [1-2 sentences]
**Rebuttal**: [Why the reply fails]
**If true, what changes**: [Concrete consequence for the paper's claims]

### Critique 2: [Different attack type]
[Same structure]

End with: **Top 3 fracture points** — the critiques that would most trouble the author.

---

## Paper to critique

{{paper}}
