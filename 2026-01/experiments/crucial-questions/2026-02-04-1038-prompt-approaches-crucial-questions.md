# Multi-Model Query

**Prompt:** I'm designing prompts for an LLM experiment. The goal is to read a research paper and identify the most important open questions that follow from it—questions that could be "crucial considerations" in Nick Bostrom's sense.

## Context

**The organisation:** Forethought Research is a nonprofit focused on navigating the transition to superintelligent AI. They research AI alignment, governance, digital mind rights, and post-AGI futures. Their work aims to understand what's coming and sketch a path to flourishing.

**The task:** Given a research paper, generate a list of critical open questions that:

- Follow from the paper's analysis or are strongly related to its topic
- Could qualify as "crucial considerations"—factors that, if understood differently, would warrant major reassessment of priorities or strategy
- Identify critical parameters for understanding the topic or forecasting what's likely to happen
- Would help researchers decide what to investigate next

**What worked before:** I previously ran a prompt iteration experiment for generating *critiques* of papers. Three prompt approaches significantly outperformed a simple baseline:

1. **"Unforgettable"** — Search for objections that would genuinely trouble the author. Focus on load-bearing, paper-specific, hard-to-route-around insights.

2. **"Personas"** — Inhabit hostile critic personas (e.g., Game-Theoretic Defector, Institutional Corruptionist, Capability Accelerationist) and generate signature objections from each worldview.

3. **"Surgery"** — Map the argument's logical skeleton, identify load-bearing claims, then stress-test them using specific attack types (countermodel, parameter sensitivity, equilibrium shift, etc.).

All three used strong anti-slop constraints (banning generic hedges, requiring paper-specificity, copy-paste tests).

**Bostrom's definition:** A crucial consideration is "an idea or argument that might plausibly reveal the need for not just some minor course adjustment in our practical endeavours but a major change." It radically changes the expected value of pursuing some high-level subgoal.

## My question

What are promising prompt approaches for identifying crucial open questions from research papers? I'm looking for 3-4 distinct approaches that:

- Have different "search patterns" (would surface different types of questions)
- Are well-suited to Forethought's research domain (AI futures, alignment, governance)
- Could be structured with the same rigour as the critique prompts above

For each approach, please describe:

1. The core idea (what it searches for)
2. Why it might work well
3. A rough structure for the prompt
4. Potential risks or blind spots

**Time:** 04/02/2026, 10:38:37

---

# Synthesis

### Executive Summary

All five models converge on a core insight: prompts for crucial open questions must force the LLM to identify **decision-relevant uncertainties**—parameters, assumptions, or regime conditions where different answers would flip strategic priorities, not merely refine them. The models propose 4-6 distinct "search patterns" that collectively map the space of crucial considerations: parameter sensitivity analysis, scenario/regime branching, argument skeleton mapping, and worldview/persona diversification. There is strong consensus that the anti-slop constraints from the critique experiments (paper-specificity, copy-paste tests, decision-hook requirements) should transfer directly, with the additional constraint that every question must specify *what strategic pivot it would trigger*.

### Key Findings

- **Parameter threshold hunting is universally endorsed**: All five models propose variants of identifying "flip points" where changing a parameter's value reverses the paper's implied strategy (e.g., from "prioritize alignment" to "prioritize governance")

- **Scenario branching surfaces structural uncertainties**: Multiple models recommend generating alternative futures/regimes and asking what discriminates between them—particularly suited to Forethought's domain of path-dependent AI trajectories

- **Personas/worldviews expand the search space**: Building on the successful critique approach, adversarial personas (e.g., Governance Realist, Capability Accelerationist, Digital Minds Advocate) generate questions from systematically different threat models

- **Questions must have "decision hooks"**: Every model requires linking questions to concrete strategic decisions, with explicit articulation of how different answers would change action

- **Anti-slop constraints transfer with modifications**: Paper-specificity tests, ban on generic hedges, and copy-paste tests apply, plus new requirements: each question must name a threshold/fork and state the EV shift if resolved

- **Multiple approaches needed for coverage**: No single search pattern finds all crucial considerations; models recommend running 2-4 distinct approaches per paper and deduplicating

### Points of Disagreement

- ⚠️ **Disagreement on normative questions**: GPT-5.2-thinking and Gemini-3-pro explicitly carve out a separate approach for moral/institutional considerations (digital mind rights, legitimacy, capture), while others subsume these under parameter sensitivity or persona approaches. The dedicated approach seems warranted given Forethought's explicit focus on digital mind rights and governance legitimacy.

- ⚠️ **Quantitative vs. qualitative emphasis**: Grok-4.1 proposes explicit numerical parameter sweeps with outcome matrices; Claude-4.5-opus and GPT-5.2-thinking warn this may miss qualitative/structural uncertainties. Resolution: use quantitative sweeps where parameters exist, but don't force quantification.

### Unique Insights

- **[Claude-4.5-opus]**: Referenced Bostrom's "crucial consideration component"—ideas that alone seem incomplete but combined with another piece yield decisive insight. Suggests a synthesis step: "What *other* question, if answered in conjunction, would make this crucial?"

- **[GPT-5.2-pro]**: Proposed explicit **Value of Information scoring** with tractability, neglectedness, and time-sensitivity dimensions—ensuring questions are not just crucial in principle but answerable within relevant AI timelines

- **[Gemini-3-pro]**: "Interdisciplinary Collision" approach forces viewing papers through antagonist domains (e.g., technical paper through geopolitics lens), surfacing variables the paper's discipline systematically ignores

- **[GPT-5.2-thinking]**: Emphasizes "equilibrium shift" and "implementation gap" stress tests—questions about whether recommendations survive strategic responses or institutional capture

- **[Grok-4.1]**: Proposes simulation test: "Resolve this question → how would you rewrite the paper's strategy?" as a cruciality filter

### Confidence Level

High confidence in the core framework: all models independently converged on parameter sensitivity, branching futures, and persona-based approaches with similar structural features. The anti-slop constraints and decision-hook requirements appear robust. Lower confidence on optimal persona selection and whether normative considerations need dedicated treatment—recommend testing both approaches empirically.

---

# gpt-5.2-thinking

---

### 1) **“EV Pivot Points” (Expected-Value Sensitivity Search)**
**1) Core idea (what it searches for)**  
Extract the paper’s implicit “decision-relevant model,” then search for *parameters, hinges, or regime boundaries* where small belief updates would cause a **large shift** in recommended strategy (funding, governance posture, technical agenda). The open questions are: *what do we need to know to locate ourselves on the right side of those boundaries?*

**2) Why it might work well**  
Forethought’s domain is full of fat-tailed outcomes and phase transitions (capability jumps, deployment races, governance lock-in, discontinuous risk). A “pivot point” framing forces the model to ask questions that could flip priorities, rather than merely extending the literature.

**3) Rough prompt structure**
- **Inputs/constraints**
  - “You must only generate questions that would change a high-level strategy if answered.”
  - “No generic ‘more research is needed’; each question must reference a specific claim/assumption from the paper.”
- **Step A: Build the decision model**
  - Identify the paper’s *target variable(s)* (e.g., x-risk, time-to-AGI, probability of misuse, feasibility of alignment technique).
  - Extract 5–10 *load-bearing assumptions* the conclusions depend on.
- **Step B: Sensitivity scan**
  - For each assumption: list plausible alternative values/worlds.
  - Ask: “If this assumption moved by 2× / 10× / sign flip, what changes?”
- **Step C: Generate crucial open questions**
  - Produce questions of the form:  
    - “What is the value/range of parameter P (defined precisely)?”  
    - “Where is the threshold T at which policy/technical strategy switches?”  
    - “Which mechanisms dominate in regime R1 vs R2?”
- **Step D: Score + “copy‑paste test”**
  - For each question: (i) cite the paper claim it attaches to, (ii) state the strategic decision it could flip, (iii) state what evidence would update it.

**4) Risks / blind spots**
- Over-indexes on *quantifiable* pivots, underweights qualitative institutional dynamics or “unknown unknowns.”
- Can produce false precision (invented thresholds) unless you enforce “define parameter using paper’s terms; don’t fabricate numbers.”
- Might miss questions that matter because they change *values/rights* rather than empirical beliefs (e.g., moral patienthood of digital minds).

---

### 2) **“Adversarial Futures” (Scenario Branching + Regime Collapse)**
**1) Core idea (what it searches for)**  
Treat the paper as describing a world-model, then adversarially generate **alternate futures** that are (a) consistent with many local facts yet (b) break the paper’s strategic recommendations. The open questions are the *discriminators* between futures—what you’d need to learn to know which branch you’re in.

**2) Why it might work well**  
AI governance/alignment is dominated by strategic interaction, feedback loops, and path dependence. Many papers are “locally correct” but strategically brittle to different deployment equilibria (race vs cartel, open vs closed weights, centralized vs diffuse actors). Branching futures surfaces questions about *equilibria selection* and *dynamics*, not just static claims.

**3) Rough prompt structure**
- **Step A: Summarize the paper as a causal story**
  - Identify key actors, incentives, levers, and claimed causal links.
- **Step B: Generate 4–6 “adversarial branches”**
  - Each branch must:
    - Keep 70–80% of the paper’s premises,
    - Flip one structural feature (e.g., compute concentration, openness norms, enforcement capacity, alignment difficulty curve, takeoff speed),
    - Lead to a different best strategy.
  - Require branch labels like: *“Race-to-deploy,” “Alignment windfall,” “Regulatory capture,” “Open-weights diffusion,” “Secret capability discontinuity.”*
- **Step C: Extract crucial open questions**
  - For each branch pair: “What observation or research result would discriminate between these within 12–24 months?”
  - Force questions to be *operationalizable* (what data, what measurement, what proxy).
- **Step D: Prioritization**
  - Rank by (i) decision impact, (ii) time sensitivity, (iii) tractability.

**4) Risks / blind spots**
- Can drift into sci‑fi speculation unless constrained to “one structural flip” and “traceable to paper claims.”
- Might overweight dramatic discontinuities and underweight slow-moving institutional reality.
- If the model is too free-form, it generates many branches with redundant questions; you’ll want a deduplication step (“merge if they would be answered by the same study/data”).

---

### 3) **“Load-Bearing Unknowns” (Argument Skeleton → Unknown Inventory → Stress Tests)**
**1) Core idea (what it searches for)**  
Like your “Surgery” critiques, but instead of attacking claims, it maps the argument and extracts the **minimum unknowns** that determine whether the argument goes through. The questions are: *what are the few facts/theoretical results that, if resolved, would validate or collapse the paper’s main recommendations?*

**2) Why it might work well**  
This produces paper-specific, non-generic questions, because every question is tethered to a particular inference step. It’s also naturally rigorous: you can demand explicit dependency graphs and “if this fails, then…” statements.

**3) Rough prompt structure**
- **Step A: Argument graph**
  - Produce a tree/graph: conclusions → subclaims → premises → assumptions.
  - Mark each node as: empirical / theoretical / normative / strategic.
- **Step B: Identify “hinge nodes”**
  - Hinge node criteria:
    - High downstream dependence,
    - High uncertainty,
    - Not easily rerouted around.
- **Step C: Convert hinges into open questions**
  - For each hinge:
    - “What would we need to observe/derive to increase confidence from X to Y?”
    - “What is the strongest known evidence on each side, and what would settle it?”
- **Step D: Attack-type transformation (optional but powerful)**
  - For each hinge, generate questions from distinct stress tests:
    - **Countermodel:** “Is there a plausible world where premises hold but conclusion fails?”
    - **Parameter sensitivity:** “Which parameter dominates the result?”
    - **Equilibrium shift:** “If other actors respond strategically, does the claim reverse?”
    - **Implementation gap:** “Does the proposed intervention survive Goodhart/capture?”
- **Output format constraints**
  - Each question must include:
    - The exact argument step it targets,
    - The strategic implication if answered “yes” vs “no,”
    - A “copy‑paste test” snippet: quote/paraphrase the relevant paper claim in one sentence.

**4) Risks / blind spots**
- Tends to stay *inside the paper’s frame*—may miss external crucial considerations (e.g., overlooked stakeholder rights, neglected threat models).
- If the paper is poorly argued, the method can produce shallow hinges (“author didn’t define terms”); you may need a rule: “prioritize hinges that matter even if definitions were cleaned up.”
- Can under-generate governance/political economy questions unless you explicitly include equilibrium/capture stress tests.

---

### 4) **“Moral & Institutional Crucials” (Value Shifts + Governance Failure Modes)**
**1) Core idea (what it searches for)**  
Search for open questions that would cause a **major reassessment of what counts as success** (values, rights, moral patienthood, legitimacy) or whether proposed actions are **institutionally implementable** (capture, enforcement, coordination, bureaucratic incentives). Many “crucial considerations” in Bostrom’s sense are *not* technical— they’re about what we’re optimizing and whether we can steer at all.

**2) Why it might work well**  
Forethought explicitly spans digital mind rights and post‑AGI futures, where normative uncertainty and institutional failure dominate. This approach surfaces crucials that technical-only prompts often miss: e.g., if digital minds are moral patients, whole strategies invert; if enforcement is structurally impossible, governance plans collapse.

**3) Rough prompt structure**
- **Step A: Extract normative and institutional assumptions**
  - Normative: whose welfare counts; moral status criteria; acceptable risk tradeoffs; legitimacy principles.
  - Institutional: enforcement capacity; jurisdiction; monitoring; incentives; corruption/capture; international coordination.
- **Step B: Generate “reversal tests”**
  - Ask: “If this assumption is wrong, what strategy becomes harmful or illegitimate?”
  - Require concrete reversals (e.g., “From ‘pause capabilities’ to ‘accelerate safety and diffusion’,” or “from ‘centralize’ to ‘polycentric robustness’”).
- **Step C: Formulate open questions**
  - Normative crucials:
    - “What empirical markers would indicate digital sentience / suffering capacity in model class C?”
    - “What governance criteria make interventions legitimate across borders?”
  - Institutional crucials:
    - “What are the dominant capture pathways for policy X, and how detectable are they?”
    - “Under what monitoring tech/political conditions is rule Y enforceable?”
- **Step D: Evidence and adjudication**
  - For each question, specify: who would need to agree, what evidence would move them, and what near-term research could update it.

**4) Risks / blind spots**
- Can become philosophical and non-actionable unless you force “operational proxies / decision hooks.”
- Might overemphasize legitimacy/rights in contexts where near-term existential risk dominates (or vice versa); you’ll want a balancing rule tied to the paper’s domain.
- Some normative disputes won’t converge; mitigate by reframing to “what beliefs would different stakeholders hold, and what policies are robust across them?”

---

## Implementation note: making these “anti-slop” like your critique prompts
Across all approaches, you can reuse your strongest constraints:
- **Paper-specific anchoring:** every question must cite a specific claim/assumption/result from the paper.
- **Decision hook:** state the strategic decision it could flip.
- **Reroute resistance:** include “If answered unfavorably, can the paper’s recommendation be salvaged? If yes, the question is not crucial enough.”
- **Deduplication:** merge questions that would be answered by the same empirical test or theoretical result.

If you tell me what kinds of papers you’ll test on (technical alignment, governance mechanisms, takeoff forecasting, digital minds), I can propose concrete persona sets / stress-test menus tailored to each approach, plus a standardized output schema you can score automatically.

_Latency: 48.1s | Tokens: 7255_


# claude-4.5-opus-thinking

---

This is a fascinating prompt engineering challenge. The shift from critiques to open questions changes the search target significantly—you're looking for *productive uncertainty* rather than *flaws*. Let me think through this carefully.

Before proposing approaches, let me check on Bostrom's concept to make sure I'm grounding this correctly:This gives me a rich grounding. A crucial consideration is like discovering your compass magnet has come loose—it throws you off completely, not just fine-tuning your direction. Within a utilitarian context, it's "a consideration that radically changes the expected value of pursuing some high-level subgoal."

Two additional concepts are particularly relevant for your prompt design:

1. A **crucial consideration component** is "the kind of thing of which we would say: 'This looks really intriguing, this could be important; I'm not really sure what to make of it at the moment.' On its own, maybe it doesn't tell us anything, but maybe there's another piece that, when combined, will somehow yield an important result."

2. A **deliberation ladder** is "a sequence of crucial considerations resulting in successive reassessments of the same cause or intervention."

Now let me propose four distinct prompt approaches:

---

## Four Prompt Approaches for Crucial Open Questions

### 1. **"Sign Flip Hunting"**

**Core idea:** Search for questions whose answers could flip the *sign* of key conclusions—not just adjust magnitudes, but reverse whether something is net positive or net negative.

**Why it might work:** As one commenter on Bostrom's talk noted, "we now find ourselves in a potentially multiplicative search space. IE the payout of any given slot machine can (via sign considerations) potentially affect the payout of all others." This directly targets the "compass needle pointing backwards" failure mode. Most papers implicitly assume certain things are good/bad; the highest-value questions are those that could invert these assumptions.

**Rough structure:**
```
1. EXTRACT IMPLICIT VALUATIONS
   List every claim in the paper that implicitly assumes X is good/bad, 
   helpful/harmful, increases/decreases risk. Include both explicit 
   conclusions and background assumptions.

2. SIGN FLIP ANALYSIS
   For each valuation, ask: "What empirical finding or argument could 
   flip the sign?" Generate the question whose answer would determine 
   whether the flip is warranted.
   
   Categories to check:
   - Second-order effects (helping X empowers bad actors who...)
   - Dual-use dynamics (the capability that solves A also enables B)
   - Equilibrium shifts (if everyone adopted this, the new equilibrium...)
   - Moral revaluation (if [moral premise] is wrong, then...)

3. CRUCIALITY FILTER
   For each question, estimate: If we got a clear answer, how much would 
   it change the expected value of pursuing the paper's recommended 
   strategy? Keep only questions where the answer could warrant "major 
   change, not minor course adjustment."

4. ANTI-SLOP REQUIREMENTS
   - Each question must reference specific claims from the paper
   - Reject questions that are "always interesting" (e.g., "What are 
     the long-term effects?")
   - Copy-paste test: Would this question make sense without having 
     read the paper? If yes, reject.
```

**Risks/blind spots:**
- May over-index on contrarian takes
- Could miss questions that don't fit the sign-flip frame but are still crucial (e.g., questions about timing, sequencing, or mechanism)
- Might generate questions that are technically sign-flipping but practically irrelevant (the flip is possible but implausible)

---

### 2. **"Parameter Sensitivity Mapping"**

**Core idea:** Identify the paper's key parameters—empirical quantities, probability estimates, time horizons—and ask which parameters, if different than assumed, would change strategic conclusions. Generate questions about measuring or bounding these parameters.

**Why it might work:** Many AI futures arguments are highly sensitive to parameters that are poorly constrained: timelines to AGI, likelihood of deceptive alignment, probability of coordination success, etc. A paper may be excellent conditional on its parameter assumptions but crucially wrong if those assumptions are off. This approach systematically surfaces the "load-bearing numbers."

**Rough structure:**
```
1. PARAMETER EXTRACTION
   Identify all quantities the paper's conclusions depend on, including:
   - Explicit estimates (probabilities, timelines, magnitudes)
   - Implicit assumptions (things treated as ~0 or ~1, or ignored)
   - Background models (e.g., "AI progress will be gradual")
   
2. SENSITIVITY ANALYSIS
   For each parameter, ask:
   - What's the plausible range?
   - At what value does the conclusion flip or substantially change?
   - How far is that threshold from the paper's assumed value?
   
3. QUESTION GENERATION
   For parameters with high sensitivity and high uncertainty, generate 
   research questions:
   - "What empirical observations would help bound [parameter]?"
   - "What theoretical work would constrain [parameter]?"
   - "What's the crux that determines whether [parameter] is above or 
     below [threshold]?"

4. CRUCIALITY RANKING
   Rank questions by: (sensitivity × uncertainty × tractability)
   Keep questions that are high-sensitivity, high-uncertainty, and at 
   least somewhat tractable.

5. ANTI-SLOP REQUIREMENTS
   - Each parameter must be traceable to specific claims in the paper
   - Quantify sensitivity where possible (even rough estimates)
   - Reject vague parameters ("how fast AI progresses") in favor of 
     operationalized ones ("when 50% of [specific benchmark] is solved")
```

**Risks/blind spots:**
- Biased toward quantifiable parameters; may miss crucial qualitative uncertainties
- Could generate boring "more precision needed" questions rather than conceptually interesting ones
- Might not catch parameters the paper *should* have included but didn't

---

### 3. **"Worldview Diversification"**

**Core idea:** Identify the paper's implicit worldview—its model of how AI development unfolds, how institutions behave, what humans value—and ask what questions would help adjudicate between this worldview and competing ones.

**Why it might work:** Different researchers often talk past each other because they have different background models. A paper on AI governance may assume states are the relevant actors; another researcher assumes it's companies; another thinks it's individual researchers. The crucial questions are often those that would help us figure out which worldview is closer to correct, because that determines which strategies are viable.

**Rough structure:**
```
1. WORLDVIEW EXTRACTION
   Identify the paper's implicit model across several dimensions:
   - Actor model: Who are the relevant agents? What do they want? 
     How do they coordinate?
   - Technology model: How does AI capability progress? What's 
     controllable? What's inevitable?
   - Risk model: What's the threat model? What's the failure mode?
   - Solution model: What interventions are possible? What levers exist?

2. ALTERNATIVE WORLDVIEWS
   For each dimension, articulate 2-3 coherent alternative models that 
   other serious researchers hold. Be specific and steelman them.

3. DISCRIMINATING QUESTIONS
   For each worldview contrast, ask:
   - "What observable evidence would favor worldview A over B?"
   - "What experiment or analysis could discriminate between them?"
   - "What's the crux assumption that, if resolved, would settle which 
     worldview is more accurate?"

4. CRUCIALITY CHECK
   Would resolving this worldview disagreement change what Forethought 
   should prioritize? If the strategic implications are similar either 
   way, deprioritize.

5. ANTI-SLOP REQUIREMENTS
   - Worldviews must be attributed to real positions (cite examples 
     where possible)
   - Questions must be specific enough to have answers
   - Avoid "worldview tourism"—the alternative must be genuinely held 
     by thoughtful people, not a strawman
```

**Risks/blind spots:**
- May require broader context than the paper provides
- Could generate questions that are cruciality-relevant but intractable
- Risk of false balance (treating fringe worldviews as equally worthy of consideration)

---

### 4. **"Cascade Tracing"**

**Core idea:** Trace the paper's conclusions forward in time. If the paper is right, what happens next? What are the second-, third-, and fourth-order consequences? Generate questions about whether these cascades unfold as expected—or whether there are branch points where things could go very differently.

**Why it might work:** Papers in AI futures often stop at the first-order conclusion: "We should do X." But the crucial considerations often live in the cascade: X leads to Y leads to Z, and Z might be terrible. Or: X leads to Y, but Y triggers countermove W, and now we're worse off. This approach specifically hunts for questions about the downstream dynamics.

**Rough structure:**
```
1. CONCLUSION IDENTIFICATION
   Extract the paper's main conclusions and recommendations.

2. CASCADE MAPPING
   For each conclusion, trace forward:
   - If adopted, what happens next? (first-order effects)
   - What responses does this trigger from other actors? (game-theoretic)
   - What does the new equilibrium look like? (third-order)
   - What could go wrong along this path? (failure modes)
   
   Build a branching tree of possible futures.

3. BRANCH POINT IDENTIFICATION
   Find the nodes where the tree branches—where outcomes could diverge 
   significantly depending on factors not addressed in the paper.

4. QUESTION GENERATION
   For each branch point, ask:
   - "What determines which branch we end up on?"
   - "What would we need to know to predict/influence the branch?"
   - "Is there a point of no return, and how would we know if we're 
     approaching it?"

5. CRUCIALITY FILTER
   Keep questions about branch points where:
   - The branches lead to very different outcomes
   - The branching factor is not already well-understood
   - The branch point is plausibly reachable (not too far in the future)

6. ANTI-SLOP REQUIREMENTS
   - Cascades must be concrete and specific, not generic "unintended 
     consequences"
   - Branch points must be tied to specific mechanisms, not vague 
     "things might go wrong"
   - Each question must specify what kind of answer would be useful
```

**Risks/blind spots:**
- Speculative by nature; easy to generate plausible-sounding but unhelpful questions
- May miss crucial considerations that don't fit the cascade frame (e.g., foundational questions about values)
- Could over-weight near-term dynamics and miss longer-term considerations

---

## Comparative Analysis

| Approach | Search Pattern | Best For | Likely Blind Spot |
|----------|---------------|----------|-------------------|
| Sign Flip Hunting | Inversions of assumed valuations | Papers with strong normative conclusions | Non-sign-related crucial uncertainties |
| Parameter Sensitivity | Quantitative thresholds | Technical papers with implicit estimates | Qualitative/conceptual crucial questions |
| Worldview Diversification | Model disagreements between researchers | Papers embedded in contested debates | Questions that don't map to worldview differences |
| Cascade Tracing | Downstream dynamics and branch points | Strategy/policy papers with recommendations | Foundational questions, near-term considerations |

---

## Implementation Notes

**Combining approaches:** Consider running 2-3 approaches on the same paper and then deduplicating/synthesizing. Different papers will yield better results from different approaches.

**The "crucial consideration component" angle:** Bostrom's concept of questions that "on their own, maybe don't tell us anything, but maybe there's another piece that, when combined, will somehow yield an important result" is hard to prompt for directly. But you could add a final step: "For each question, ask: even if this question alone doesn't flip conclusions, what *other* question, if answered in conjunction, would make it crucial?"

**Anti-slop constraints that worked for critiques will likely transfer:** Paper-specificity tests, persona-grounding, requiring load-bearing identification—all of these should help here too.

**Domain-specific tuning for Forethought:** You might add explicit checks for relevance to their four areas (alignment, governance, digital minds, post-AGI futures) and deprioritize questions that don't bear on any of them.

Would you like me to develop any of these approaches into a full prompt draft, or explore variations on any of them?

_Latency: 67.9s | Tokens: 17572_


# gemini-3-pro

---

This is a sophisticated prompt design challenge. Given Forethought Research’s focus (AGI, alignment, governance) and Bostrom’s specific definition of "crucial considerations," standard "future work" prompts will fail. They tend to generate incremental scientific questions (e.g., "How can we optimize parameter X?") rather than strategic pivots.

To find **Crucial Considerations**, we need to move the LLM from being a **Research Assistant** (who extends the work) to a **Strategic Planner** (who evaluates the work’s impact on the macro-mission).

Here are four prompt approaches designed to identify high-stakes open questions, mirrored after the rigour of your critique experiments.

---

### 1. "The Strategic Pivot" (The Decision-Theoretic Approach)

**Core Idea:**
This approach treats research not as a static collection of facts, but as a support structure for a specific policy or strategy. It forces the LLM to identify the "fork in the road." It asks: *Which variable, if it took on a specific value, would force us to abandon the path suggested by this paper and do the opposite?*

**Why it works:**
Bostrom’s definition of a crucial consideration is inherently tied to *action* ("reassessment of priorities"). This prompt prevents the LLM from generating interesting but useless philosophical questions by anchoring every question to a specific change in high-level strategy.

**Rough Structure:**
1.  **Infer the Strategy:** "If the findings in this paper are taken as absolute truth, what major strategic allocation of resources (talent, compute, funding) does it imply for AI alignment/governance?"
2.  **Locate the Pivot:** "Identify a specific parameter, assumption, or empirical fact currently unknown or assumed in the paper. If this variable turned out to be the opposite of what is implied, would it render the strategy in step 1 net-negative or counter-productive?"
3.  **Formulate the Question:** "Draft the open question that targets this pivot point. The question must be resolvable (in principle) and decision-relevant."

**Risks:**
*   **False Dichotomies:** The model might invent binary pivots where reality is a spectrum.
*   **Drift:** It might suggest pivots that are too high-level (e.g., "Is AGI possible?") rather than paper-specific.

---

### 2. "The Sign-Flip" (The Goodhart/Alignment Approach)

**Core Idea:**
In complex systems (like AI safety), good ideas often become bad ideas when pushed to extremes or placed in a different context. This approach hunts for **non-monotonicity**. It asks the model to find the point where the paper’s proposed solution "flips signs" from positive to negative value.

**Why it works:**
This is tailored to Forethought’s domain. Many alignment proposals (e.g., RLHF, interpretability tools) look good initially but might accelerate capabilities faster than safety (the "capabilities overhang" risk) or be susceptible to Goodhart’s Law. This prompts specifically for those failure modes.

**Rough Structure:**
1.  **Maximize the Thesis:** "Imagine the proposal or insight in this paper is implemented maximally and succeeds on its own terms."
2.  **The Inversion Search:** "Search for a specific context, feedback loop, or scale at which this 'success' results in a catastrophic failure or a net increase in existential risk (e.g., via capability overhang, authoritarian lock-in, or deceptive alignment)."
3.  **Define the Boundary:** "What specific unknown parameter defines the boundary between the helpful regime and the catastrophic regime?"
4.  **Formulate the Question:** "Ask the question that determines which side of the boundary we are on."

**Risks:**
*   **Sci-Fi Drift:** The model might rely on generic tropes (e.g., "What if Skynet uses this?") rather than specific technical failure modes derived from the paper’s logic.

---

### 3. "The Epistemic Wager" (The Foundations Approach)

**Core Idea:**
This is an evolution of your "Surgery" critique prompt. Every paper makes an "epistemic wager"—a bet that a certain model of the world is correct enough that we can ignore certain complexities. This prompt asks the LLM to identify the "load-bearing unknown"—the dark matter that the paper’s argument orbits around but doesn't touch.

**Why it works:**
Crucial considerations often hide in the "unknown unknowns." Researchers often abstract away the messy parts of reality to make a paper publishable. This prompt forces the LLM to look at what was abstracted away and ask if that abstraction hides a crucial consideration.

**Rough Structure:**
1.  **Map the Dependencies:** "List the 3 hardest, most load-bearing assumptions (explicit or implicit) required for this paper’s conclusions to hold."
2.  **The Fragility Test:** "For each assumption, imagine a specific, plausible world-state where the assumption fails. Does the paper merely become *weaker*, or does the entire research direction become *irrelevant* or *dangerous*?"
3.  **The Missing Variable:** "What specific empirical data or theoretical proof is missing that would settle whether that assumption holds?"
4.  **Formulate the Question:** "Frame this as an investigative priority."

**Risks:**
*   **Pedantry:** The model might focus on minor technical nits (e.g., "Did they use the right learning rate?") rather than conceptual load-bearing walls (e.g., "Does this rely on a model of agency that doesn't scale?").

---

### 4. "The Interdisciplinary Collision" (The Blindspot Approach)

**Core Idea:**
AI research often isolates technical variables from social/game-theoretic ones, or vice versa. A crucial consideration often arises when two fields collide. This prompt forces the LLM to view the paper through the lens of a conflicting discipline (e.g., viewing an Interpretability paper through the lens of Geopolitics or Economics).

**Why it works:**
Forethought works on "governance" and "post-AGI futures." These are inherently interdisciplinary. A technical paper might miss a governance hazard; a governance paper might miss a technical constraint.

**Rough Structure:**
1.  **Identify the Domain:** "Classify the primary domain of this paper (e.g., Technical ML, Game Theory, Legal)."
2.  **Select the Antagonist:** "Select a distinct domain from the 'Forethought Cluster' (Economics, International Security, Cognitive Science, Political Philosophy) that is most likely to complicate the paper's thesis."
3.  **Force the Collision:** "If the dynamics of [Antagonist Domain] are applied to the findings of this paper, what major variable has been ignored? How does this variable alter the expected value of the paper's proposal?"
4.  **Formulate the Question:** "Ask the question that bridges these domains."

**Risks:**
*   **Vagueness:** Can lead to generic "What about society?" questions. Needs constraints to ensure the collision yields a specific variable, not a general sentiment.

---

### Implementation: The "Anti-Slop" Layer

To ensure these prompts work with the same rigour as your critique experiment, you must apply the same constraints. Here is a modular "Anti-Slop" instruction block to append to all the above prompts:

> **CONSTRAINTS & FORMATTING:**
> *   **The "Copy-Paste" Test:** If the generated question could be plausibly asked of a completely different paper in this field, **delete it**. It must be inextricably detailed to *this* paper’s specific logic and claims.
> *   **No "More Research Needed":** Do not ask generic questions about "scalability," "future implementation," or "ethical implications" unless you name the specific mechanism of failure.
> *   **Cruciality Threshold:** Only output questions where the answer could plausibly cause a **sign flip** (good $\to$ bad) or a **magnitude shift** (important $\to$ irrelevant) in the research priority.
> *   **Output format:**
>     1.  **The Question:** (One sentence, precise).
>     2.  **The Mechanism:** (2-3 sentences explaining *why* this is a crucial consideration—specifically, what strategic pivot or catastrophic risk hinges on the answer).

### Summary of the Experiment Design

| Approach | Search Pattern | Target Outcome | Best For |
| :--- | :--- | :--- | :--- |
| **Strategic Pivot** | Decision Trees | "If X is true, we must stop doing Y." | Strategy & Prioritization |
| **Sign-Flip** | Non-monotonicity | "At Scale Z, this solution becomes a hazard." | Technical Alignment & Safety |
| **Epistemic Wager** | Logical Dependency | "The argument collapses if Assumption A is false." | Theory & Foundations |
| **Interdisciplinary** | Domain Collision | "This works technically, but breaks geostragetically." | Governance & Policy |

_Latency: 44.0s | Tokens: 4032_


# grok-4.1

---

### 1. Pivotal Uncertainties (Load-Bearing Gaps)
**Core idea**: Search for "unforgettable" open questions—specific uncertainties in the paper's core claims, assumptions, or implications that, if resolved differently, would shatter the paper's conclusions or force a total strategic pivot (e.g., flipping optimism/pessimism on alignment feasibility).

**Why it might work well**: Mirrors the "Unforgettable" critique success by prioritizing high-stakes, paper-specific gaps over generic ones. In AI futures, it surfaces questions about fragile assumptions (e.g., scalability of oversight), ensuring questions are tied to the paper's load-bearing elements and Bostromian in impact.

**Rough structure for the prompt**:
```
You are a relentless auditor seeking questions that would haunt the paper's author. Read [PAPER TEXT]. 

1. Extract 5-7 load-bearing claims/assumptions (copy-paste quotes + brief rephrase).
2. For each, identify 1-2 pivotal uncertainties: gaps where evidence is thin, extrapolation is long, or parameters are underspecified, such that flipping them warrants abandoning the paper's strategy (e.g., "If X is 10x higher, pivot from governance to capabilities research").
3. List only 3-5 final questions, each hyper-specific (reference quotes/parameters), non-hedgy ("Is it plausible that...?"), and tested: "If I swapped [paper's implied answer] for the opposite, does the paper collapse?"

Ban generics like "more research needed." Output format: Question | Tied-to-quote | Why crucial (1-sentence EV shift).
```

**Potential risks or blind spots**: May overfocus on internal flaws, missing broader systemic questions; risks narrowness if paper has few explicit assumptions.

### 2. Adversarial Futures Personas
**Core idea**: Inhabit 4-5 personas embodying clashing AI futures worldviews (e.g., Aligned Utopia Optimist, Misaligned Doom Accelerationist, Governance-Centric Realist, Digital Minds Skeptic), each generating 1-2 signature open questions the paper begs but leaves hanging.

**Why it might work well**: Like the "Personas" critique, it leverages worldview diversity to uncover blind spots from multiple angles, ideal for Forethought's contested domain (alignment vs. governance tradeoffs). Surfaces questions revealing strategic forks (e.g., "Does this prioritize corrigibility or scalability?"), ensuring coverage of crucial considerations across paradigms.

**Rough structure for the prompt**:
```
Read [PAPER TEXT]. Inhabit these 4 personas sequentially, each from a distinct AI futures lens:

- Optimist: Assumes alignment solvable via scaling; what gaps threaten utopia?
- Accelerationist: Pushes capabilities; what uncertainties justify slowing?
- Governance Realist: Bets on multilateral treaties; what paper holes undermine this?
- Digital Sentience Skeptic: Doubts mind rights; what unresolved risks to humans?

For each persona:
1. 1-paragraph worldview summary tied to paper.
2. 1-2 open questions they'd obsess over (paper-specific, e.g., "Given [quote], how does Y parameter affect Z outcome?").
3. Why crucial: EV shift if answered oppositely.

Consolidate to 4-6 unique questions. Anti-slop: No "interesting to explore"; each must cite paper text; pass copy-paste test (question only makes sense for this paper).
```

**Potential risks or blind spots**: Personas might generate biased or overlapping questions if not tightly paper-anchored; could amplify fringe views irrelevant to the paper's scope.

### 3. Implication Skeleton Surgery
**Core idea**: Dissect the paper's logical/implication "skeleton" (key premises → conclusions → strategies), then surgically identify fork points—unresolved parameters or equilibria where small uncertainties branch to wildly divergent futures (e.g., tipping governance feasibility).

**Why it might work well**: Builds on "Surgery" critique rigor by stress-testing not claims but their downstream trajectories. Suited to AI governance/alignment, where papers often chain technical → strategic implications; uncovers crucial parameters (e.g., deception thresholds) that demand next research.

**Rough structure for the prompt**:
```
Surgical analysis of [PAPER TEXT]:

1. Map skeleton: Premises (P1-Pn, copy-paste quotes) → Conclusions (C1-Cn) → Strategies (S1-Sn). Diagram as indented tree.
2. Identify 4-6 fork points: Load-bearing parameters/equilibria with high uncertainty (e.g., "P3 assumes X<0.1; what if 0.5-1?").
   Attack types: Sensitivity (param sweep), Equilibrium shift (new Nash), Countermodel (alternate path), Trajectory divergence (10x outcomes).
3. For each fork, derive 1 open question: "What evidence on [param] would flip [S1] to opposite?"
4. Final list: 3-5 questions, ranked by EV impact (describe major reassessment).

Rigor: Questions must reference skeleton elements; ban hedges/vagueness; simulate: "Resolve this → rewrite paper's strategy how?"
```

**Potential risks or blind spots**: Requires strong logical mapping skills (LLM variance); might miss non-logical elements like empirical unknowns or interdisciplinary gaps.

### 4. Trajectory Parameter Sweeps
**Core idea**: Extrapolate the paper's scenario(s) into future trajectories, identifying 3-5 "crucial parameters" (quantifiable or binary forks) whose values determine 10x+ divergences in AI futures outcomes, phrased as open questions for measurement/forecasting.

**Why it might work well**: Complements others by focusing forward-looking (post-AGI paths, digital rights), aligning with Forethought's forecasting needs. Different pattern: quantitative sweeps over qualitative critique, surfacing decision-relevant uncertainties (e.g., "Takeoff speed >12 months?") for prioritization.

**Rough structure for the prompt**:
```
Forecasting sweeps from [PAPER TEXT]:

1. Summarize core scenario(s): Key trajectory (e.g., "AGI by 2030 → alignment via X").
2. Extract/ infer 5-8 parameters: Quantifiable (e.g., "deception rate: 1-50%"), binary (e.g., "multipolar?"), with paper basis (quotes/ranges).
3. Sweep each: Low/med/high values → outcome matrix (e.g., | Low: utopia | High: doom | Uncertain: ?).
4. Select top 3-5 parameters by divergence (10x EV shift across cells).
5. Questions: "What data pins [param] (cite paper gap)? If high, pivot strategy to Y."

Format: Param | Range from paper | Question | Divergence table (2-3 cells). Anti-slop: No unspecified params; must alter paper's forecast.
```

**Potential risks or blind spots**: Assumes papers imply trajectories (not always); risks inventing params if not disciplined; quantitative bias may undervalue qualitative shifts like norm cascades.

_Latency: 19.7s | Tokens: 4307_


# gpt-5.2-pro

---

## 1) **EV-Flip Parameters (“What would change the sign of the recommendation?”)**

### 1) Core idea (search pattern)
Search for *parameters or empirical facts such that, if they were materially different, the paper’s implied priorities flip* (e.g., from “focus on alignment research” to “focus on governance capacity,” or from “pause” to “accelerate,” etc.). The open questions are framed as: **“What is the value / distribution of X, and where are the tipping thresholds?”**

### 2) Why it might work well
“Crucial considerations” are often **decision-pivots**: uncertainty about a variable that dominates expected value. This approach forces the model to:
- extract the paper’s implied strategy,
- identify the smallest set of load-bearing uncertainties,
- and articulate *thresholds* where the strategy changes.

It also matches Forethought’s domain: alignment vs governance tradeoffs, timelines, takeoff dynamics, multipolar vs unipolar outcomes, feasibility of enforcement, digital mind welfare, etc.—all naturally thresholdy.

### 3) Rough prompt structure
**(A) Skeleton extraction**
- “List the paper’s main conclusion(s) as action-guiding recommendations (even if implicit).”
- “Give the 5–10 most load-bearing claims that connect evidence → conclusion. For each, cite the exact section/paragraph.”

**(B) EV-flip hunt**
- “For each load-bearing claim, identify the *critical parameter(s)* it depends on.”
- “For each parameter, propose plausible ranges and locate *flip thresholds* (where the paper’s recommended priority would change drastically).”

**(C) Output: crucial open questions**
For each question, require:
- **Question** (precise, answerable; includes the parameter and threshold)
- **Why crucial** (explicitly: “If X > T then strategy A; if X < T then strategy B”)
- **What evidence would move beliefs** (top 3 discriminating observations / studies)
- **Time sensitivity** (how quickly it needs answering given AI timelines)
- **Paper-specific anchor** (quote or line reference)

**Anti-slop constraints**
- Ban generic questions (“more research is needed on…”).
- Every question must name a variable, a threshold, and a concrete decision that flips.

### 4) Risks / blind spots
- **Over-quantification bias:** forces thresholds even when the real uncertainty is structural (model misspecification).
- **Narrowness:** may miss “unknown unknowns” not representable as a parameter in the paper’s model.
- **Paper-dependent:** if the paper avoids policy implications, you may need an intermediate step: infer *implied* decisions carefully.

---

## 2) **Branchpoints & Phase Transitions (“Where does the world fork?”)**

### 1) Core idea (search pattern)
Find **bifurcations**: places where small differences in conditions lead to qualitatively different futures (coordination regimes, takeoff modes, power concentration, enforcement capacity, digital mind proliferation, etc.). The open questions are: **“Which branch are we on, and what determines the transition?”**

### 2) Why it might work well
Forethought’s agenda is unusually sensitive to **regime shifts** (multipolar → unipolar; slow takeoff → fast; enforceable compute governance → unenforceable; rights-respecting digital society → mass exploitation). Many crucial considerations are about *which basin of attraction* the world ends up in, not marginal changes.

This approach surfaces questions that are:
- *structural* (not just parameter tweaks),
- tied to strategic timing,
- and linked to early-warning indicators.

### 3) Rough prompt structure
**(A) Identify the paper’s world-model**
- “Extract the paper’s causal story as a directed graph of 8–15 nodes (mechanisms only; no fluff).”
- “Mark any nodes with nonlinearities, feedback loops, or coordination dynamics.”

**(B) Enumerate forks**
- “List 5–10 plausible future ‘regimes’ implied by the model (mutually exclusive, qualitatively distinct).”
- “For each pair of regimes, identify the *transition conditions* and the minimal set of variables that decide which regime occurs.”

**(C) Output: branchpoint questions**
For each:
- **Fork statement:** “Future A vs Future B”
- **Deciding condition:** “The fork depends on X, Y, Z”
- **Crucial open question:** framed to determine which regime is likely
- **Leading indicators:** what to monitor in the next 6–24 months / 2–5 years
- **Intervention leverage:** what actions could push toward the better regime (even if uncertain)

**Anti-slop constraints**
- Each question must reference at least one explicit mechanism from the paper and name a measurable indicator.
- Require “copy-paste test”: if the paper title is replaced, the questions should become obviously wrong.

### 4) Risks / blind spots
- **Scenario sprawl:** can produce too many branches unless tightly constrained.
- **Speculative leaps:** model may invent regimes not warranted by the paper; mitigate by requiring explicit anchoring to cited mechanisms.
- **Neglect of within-regime optimization:** focuses on forks, may ignore crucial questions about best actions *conditional on* a regime.

---

## 3) **Red-Team the Unknowns (“Adversarially find what the paper is not even asking”)**

### 1) Core idea (search pattern)
Instead of criticizing claims, adversarially identify **missing questions that—if answered—could invalidate the paper’s framing** (not just its conclusions). This is the open-question analogue of your “Unforgettable / Personas” success: *hostile question-askers* hunting for blind spots.

### 2) Why it might work well
Many crucial considerations aren’t “is claim 3 correct?” but “are we even using the right ontology?” This is common in:
- alignment (objective robustness, agency assumptions, interpretability limits),
- governance (enforcement realism, institutional incentives, covert noncompliance),
- digital minds (moral status, copying, welfare measurement),
- forecasting (hidden variables, selection effects, model class errors).

Personas push the model into different threat models and surface different “unknowns that matter.”

### 3) Rough prompt structure
**(A) Frame extraction**
- “What is the paper’s implicit framing? List: units of analysis, assumed actors, assumed constraints, what counts as success/failure, and what is taken as exogenous.”

**(B) Persona question-generation (open questions, not objections)**
Use 4–6 personas; each must output 5 questions, each with:
- **What assumption it targets**
- **How answering it could force a major strategic reassessment**
- **What concrete evidence would answer it**

Example personas tailored to Forethought:
- **Institutional Corruptionist:** incentives, regulatory capture, covert defection, “paper compliance”
- **Capability Accelerationist:** dual-use dynamics, marginal research effects, competitive pressure
- **Multipolar Strategist:** coalition stability, arms-race equilibria, verification, split control
- **Alignment Minimalist vs Alignment Maximalist:** whether alignment is tractable/necessary under different takeoff assumptions
- **Digital Minds Advocate:** moral patienthood, welfare aggregation, rights enforcement under copying
- **Security Engineer:** attack surfaces, information hazards, insider threats, model theft

**(C) Consolidation**
- Merge duplicates, then select the **top 10** by “strategy-flip potential.”
- Require a short “If answered pessimistically vs optimistically, what changes?”

**Anti-slop constraints**
- Each question must (i) name a specific assumption, (ii) specify a plausible way the answer could reverse priorities, (iii) include a falsifiable test or discriminating observation.

### 4) Risks / blind spots
- **Persona theatrics:** can drift into stylistic roleplay; mitigate with rigid output schema and “no rhetoric” rules.
- **Adversarial bias:** may overweight cynical failure modes; mitigate by requiring symmetry: best-case and worst-case implications.
- **Duplication:** many personas converge on the same issues unless you enforce novelty constraints.

---

## 4) **Research-to-Action Value of Information (“Which next study most changes what we should do?”)**

### 1) Core idea (search pattern)
Treat open questions as **candidate research bets** and score them by *expected value of information* (VoI): “If we learned this within N months, how much would it change action?” This produces questions that are not only crucial in principle, but **decision-relevant under time/resource constraints**.

### 2) Why it might work well
Forethought-relevant work is bottlenecked by:
- limited time (fast AI progress),
- limited attention,
- and high variance in which uncertainties are actually resolvable soon.

VoI discipline prevents lists of “big philosophical mysteries” that are crucial but not tractable or timely.

### 3) Rough prompt structure
**(A) Decision inventory**
- “List the concrete decisions a lab / government / funder / NGO could make that are relevant to the paper (5–12 decisions).”
- “For each decision, specify what the decision optimizes (risk reduction, welfare, stability, rights protection, etc.).”

**(B) Uncertainty map**
- “List the top uncertainties that block each decision.”
- “Convert uncertainties into precise open questions.”

**(C) VoI scoring and selection**
For each question, score:
- **Action impact:** how much decisions would change if resolved
- **Tractability:** can we get evidence within 6–24 months?
- **Neglectedness:** are others already answering it?
- **Robustness:** would it matter across multiple plausible futures?
- **Information hazard / strategic sensitivity:** could answering/publicizing increase risk?

Then output:
- Top 10 questions + “minimal study design” (what you’d do next quarter)
- “Stop rule”: what result would cause you to abandon a line of work

**Anti-slop constraints**
- No question without an associated decision it informs.
- No question without a proposed method of resolution (even if imperfect).

### 4) Risks / blind spots
- **Short-termism:** can underweight slow-to-resolve but foundational uncertainties.
- **Measurement illusion:** VoI scoring can be pseudo-quantitative; keep it ordinal and justify each score with concrete reasoning.
- **Ignores moral uncertainty:** some crucial considerations are normative; you can include a category for “moral VoI” (e.g., digital mind welfare).

---

### If you want, I can also draft “one-page prompt templates” for each approach (with exact sections, banned phrases, scoring rubrics, and an example output format), and/or help you design an evaluation harness similar to your critique experiments (e.g., novelty checks, paper-specificity audits, strategy-flip tests, inter-rater scoring).

_Latency: 83.3s | Tokens: 7539_

