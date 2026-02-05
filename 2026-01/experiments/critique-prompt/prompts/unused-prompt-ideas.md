# Unused prompt ideas from multi-model brainstorm

Saved from the 2026-02-04 brainstorming session for potential future use.

---

## Ideas with strong potential (consider for future iterations)

### CRUX-FRACTURE (GPT-5.2)
**Summary:** Each critique identifies one crux assumption and asks: "What is the smallest perturbation to the world/model that keeps most of the paper intact but flips the conclusion?"

**Why interesting:** Produces highly paper-specific countermodels. The "smallest perturbation" constraint prevents kitchen-sink objections and tends to create memorable named objections anchored to a small, vivid change.

**Key elements:**
- "Find the conclusion-sign: what would have to be different for the sign to flip?"
- Must state whether the perturbation is empirical, strategic, normative, or measurement
- Constraint: perturbation must be minimal

---

### TRILEMMA-PROBE (GPT-5.2)
**Summary:** Search for a hidden trilemma (or impossibility triangle) implied by the paper's commitments, then argue the paper implicitly assumes away one corner.

**Why interesting:** Creates "hard-to-route-around" structural critiques. Trilemmas force the author to either weaken claims, add new machinery, or accept an undesirable implication.

**Key elements:**
- Extract 3 desiderata the paper appears to want simultaneously
- Show why all 3 can't hold together under the paper's own mechanisms
- Must tie directly to a key inference chain

---

### RECURSIVE SHARPENING / RSIP (Claude 4.5)
**Summary:** Build self-critique loop into single prompt: generate → identify 3 ways it's generic/vague → rewrite sharper. Output only sharpened versions.

**Why interesting:** Transforms generic critiques rather than just banning them. Wang et al. (2026) validates self-critique when anchored to specific criteria.

**Key elements:**
- Three-phase structure: Draft → Diagnose genericity → Sharpen
- Explicit "genericity diagnosis" categories: noun-swappable, priced-in, peripheral, easily-routed-around
- Sharpened version must add specific details from the paper

**Risk:** High complexity—model might not execute three-phase structure cleanly in single prompt.

---

### NEUTRAL LENS / RHETORIC-STRIPPED (Multiple models)
**Summary:** First rewrite the paper's core argument in dry, neutral terms (stripping rhetoric and prestige), then critique that stripped version.

**Why interesting:** Uses System 2 Attention (S2A) to reduce sycophancy. Reveals logical gaps masked by persuasive writing.

**Key elements:**
- "De-rhetoricizing" step first
- Critique only the logical reconstruction
- Ban rhetorical complaints

**Risk:** Extra step uses tokens and might oversimplify, leading to straw-man critiques.

---

### MEASURED-ATTACK / Operationalization Adversary (GPT-5.2)
**Summary:** Attack the paper by targeting a single operationalization/measurement mapping, showing how plausible alternative metrics reverse the inference.

**Why interesting:** Many academic arguments hinge on fragile concept → measure → inference mappings. Especially relevant for empirical/modeling papers like "Compute Bottlenecks."

**Key elements:**
- Cite the exact mapping ("The paper treats X as a proxy for Y, then infers Z")
- Provide one alternative operationalization and show inference flips
- Ban generic "measurement is hard"—must specify what breaks

**Limitation:** Less applicable to purely conceptual papers.

---

## Ideas that overlap with chosen prompts (may inform refinements)

### AUTHOR'S NIGHTMARE (Claude 4.5)
Similar to AUTHOR'S TRIBUNAL but with psychological framing: "What would the author be most worried about if a hostile expert reviewer found it?"

Could inform refinements to the Tribunal prompt by adding: "blindspot detector" (what does the author conspicuously not address?) and "would this make the author lose sleep?" criterion.

---

### META-FILTER PANEL (Grok)
Generate 2× critiques, then apply internal ACORN-like scoring filter. Explicit formula: `Centrality × Strength × (Correctness + Clarity)/2 ≥ 0.6 to pass`.

**Interesting twist:** Directly games the grader by mimicking its scoring. Risk: model might game its own checklist superficially without genuine quality improvement.

---

### SCENARIO STRESS TEST (OpenAI Deep Research)
Similar to PRE-MORTEM but framed as constructing plausible counterexamples where premises hold but conclusion fails.

Already incorporated into PRE-MORTEM's causal failure stories, but the "construct a world where..." framing could be useful for more abstract papers.

---

### CHECKLIST CRITIQUER (OpenAI Deep Research)
Explicit ACORN-inspired checklist for each critique: "Does this attack a central claim?", "Is it specific to this paper?", etc. Binary pass/fail gates.

Useful concept but risk of formulaic/stiff outputs. Could inform anti-slop constraints in other prompts.

---

## Ideas with significant drawbacks (lower priority)

### CONCEPTUAL ANGLE FIRST / CoCT (Claude 4.5)
Tag each critique with a conceptual angle before elaborating (e.g., "Causal Inference Validity").

**Drawback:** Too similar to Surgery's attack types. Model might force-fit angles that don't apply.

---

### FRACTURE DEBATE ARENA (Grok)
Simulate 2-agent debate (Pro defends, Con attacks), extract strongest Con rebuttals.

**Drawback:** High token cost for rounds; could devolve into balanced ping-pong without sharp extraction. AUTHOR'S TRIBUNAL achieves similar filtering more efficiently.

---

### REVERSAL FORGE (Grok)
Generate critiques by flipping key inferences into opposites, mining the paper itself for evidence supporting the reversal.

**Drawback:** May underperform on papers without self-undermining text; could generate forced flips. The "reversal" attack type is already in Surgery/Unforgettable.

---

### INOCULATION PROTOCOL (Gemini)
Dynamically generate field-specific list of lazy critiques to ban, then generate critiques not on that list.

**Why not chosen:** Adds complexity; existing anti-slop constraints may suffice. Could be added as a warm-up step to any prompt if slop remains a problem.

---

## Notes for future experiments

1. **Hybrid approach:** After testing individual prompts, consider combining PIVOT ATTACK (centrality) + AUTHOR'S TRIBUNAL (strength) into a single prompt: extract pivots → generate critiques → stress-test survivors.

2. **Token efficiency:** AUTHOR'S TRIBUNAL roughly doubles token cost. May need to adjust `num_critiques` parameter or accept fewer but higher-quality outputs.

3. **Paper type matching:** Some approaches work better for certain paper types:
   - MEASURED-ATTACK → empirical/modeling papers
   - TRILEMMA-PROBE → normative/policy papers
   - PRE-MORTEM → papers with recommendations or predictions

4. **Execution risk:** Multi-phase prompts (RSIP, PIVOT+TRIBUNAL hybrid) may not execute cleanly. Start with simpler versions, add complexity only if baseline works.
