# Gemini Deep Research: q1-calibration-techniques

**Model:** deep-research-pro-preview-12-2025
**Generated:** 2026-01-20T11:30:42.020Z
**Interaction ID:** v1_ChdzV1Z2YWViYUxxT0t2ZElQdHUzZGtRcxIXc1dWdmFlYmFMcU9LdmRJUHR1M2RrUXM

---

# Calibration Techniques for LLM Graders in High-Stakes Philosophical and Economic Research

## Executive Summary

### Key Findings
For Forethought Research’s goal of evaluating AI-generated critiques within the domains of philosophy, economics, and AI governance, empirical evidence suggests that **holistic, single-score evaluation by LLMs is insufficient** and prone to specific failure modes such as sycophancy and length bias. To achieve >75% agreement with human researchers on a small validation set, a composite approach is required.

Research indicates that **Chain-of-Thought (CoT)** prompting improves reasoning but suffers from a "reasoning-score mismatch," where the model’s qualitative analysis does not match its quantitative score. **Verbalized confidence scores** (asking the model to state its certainty) are significantly better calibrated than internal log probabilities for RLHF-tuned models like GPT-4. **Rubric decomposition**, specifically using frameworks like DeCE (Decomposed Criteria-Based Evaluation), outperforms holistic scoring by separating factual precision from conceptual recall. Finally, while **pairwise comparison** generally yields higher human alignment, it is uniquely vulnerable to "distracted evaluation," where models prefer assertive or verbose critiques regardless of quality.

### Strategic Recommendation
We recommend a **Hybrid "PREPAIR" Workflow** adapted for philosophical argumentation. This involves:
1.  **Toulmin-based CoT:** Forcing the grader to identify the *Claim, Warrant, and Backing* of the critique before scoring.
2.  **Pointwise-First, Pairwise-Second:** Evaluating critiques individually against a rubric to ground the model, then performing pairwise comparisons only on high-scoring candidates to filter noise.
3.  **Verbalized Confidence Filtering:** Discarding judgments where the model’s self-reported confidence is low, rather than relying on the score alone.

---

## 1. Introduction

### 1.1 The Challenge of Meta-Evaluation in Abstract Domains
Forethought Research operates at the intersection of philosophy and economics, dealing with "wicked problems" such as post-AGI economic structures and the moral status of digital minds. Unlike code generation or mathematical reasoning, where ground truth is binary (compiles/does not compile), evaluating critiques of philosophical arguments involves assessing **logical coherence, argumentative weight, and novelty**.

Building an LLM grader for this purpose is a "meta-evaluation" task. The system must distinguish between a critique that is merely grammatically correct and plausible (noise) and one that identifies a genuine structural flaw in a complex argument (signal). This is complicated by the **"Sycophancy Bias,"** where LLMs tend to agree with the text they are analyzing or prioritize user-aligned views over objective truth [cite: 1, 2].

### 1.2 The Reliability Bottleneck
The primary bottleneck in scaling AI research assistance is not generation, but verification. If an LLM generates 100 critiques of a longtermist macrostrategy paper, and 95 are generic noise, the researcher spends more time filtering than they would have spent critiquing the paper themselves. A grader with <75% reliability is functionally useless because the cost of verifying the grader’s false negatives (missed valuable critiques) and false positives (noise flagged as good) exceeds the value of automation.

### 1.3 Scope of Analysis
This report synthesizes empirical findings to answer five core questions regarding calibration techniques:
1.  **Chain-of-Thought (CoT):** Efficacy and failure modes.
2.  **Confidence Scores:** Reliability of self-reported metrics.
3.  **Rubric Decomposition:** Analytic vs. holistic scoring.
4.  **Comparative Approaches:** Pairwise vs. absolute scoring.
5.  **Multi-turn Verification:** Iterative self-correction.

---

## 2. Chain-of-Thought (CoT) and Reasoning Alignment

### 2.1 Efficacy of CoT in Evaluation
Chain-of-Thought (CoT) prompting—instructing the model to "think step-by-step" before generating a final answer—is a foundational technique for improving LLM performance on complex reasoning tasks. In the context of an LLM grader, CoT serves a dual purpose: it forces the model to generate a rationale that can be inspected by humans, and it theoretically grounds the final score in the generated evidence.

Research confirms that CoT generally improves performance on reasoning-heavy tasks compared to direct-answer prompting. However, a critical failure mode known as **"Reasoning-Score Mismatch"** has been observed. Studies show that models often generate sound reasoning chains that identify flaws, yet subsequently assign a high score that contradicts their own reasoning [cite: 3]. Conversely, models may hallucinate reasoning steps to justify a pre-determined score bias [cite: 4].

### 2.2 The "Brittle Mirage" of Inference
Recent investigations into CoT suggest that what appears to be reasoning is often a "brittle mirage" based on pattern matching rather than genuine logical inference. When test queries deviate slightly from training distributions (e.g., novel philosophical thought experiments vs. standard textbook examples), CoT performance degrades sharply [cite: 4].

For Forethought’s domain, this implies that a generic "Let's think step by step" prompt is insufficient. The reasoning process must be constrained to specific argumentative structures to prevent the model from drifting into generic plausibility.

### 2.3 Domain-Specific Application: The Toulmin Model
To mitigate the brittleness of standard CoT, we recommend implementing **Structured Argumentation CoT** using the **Toulmin Model**. This framework decomposes arguments into six components: Claim, Data, Warrant, Backing, Qualifier, and Rebuttal [cite: 5, 6].

**Implementation for Forethought:**
Instead of asking "Is this critique good?", the prompt should require the LLM grader to:
1.  Identify the **Claim** of the critique.
2.  Identify the **Warrant** (the logical bridge connecting evidence to the claim).
3.  Assess if the Warrant is supported by **Backing**.
4.  Check for **Qualifiers** that limit the scope.

Research indicates that using "Critical Questions" derived from the Toulmin model (e.g., "Is the warrant supported by evidence?") significantly improves the model's ability to detect logical fallacies compared to unstructured reasoning [cite: 7, 8]. This transforms the evaluation from a subjective "vibe check" to a structural audit.

### 2.4 Failure Modes of CoT
*   **Hallucinated Steps:** The model invents intermediate steps to force a logical connection that doesn't exist [cite: 9].
*   **Sycophancy:** If the critique being evaluated uses authoritative language, the CoT process may adopt a supportive tone, rationalizing the critique's validity regardless of its actual merit [cite: 1].
*   **Length Bias:** Longer reasoning chains in the critique often trick the CoT process into assuming higher quality, a phenomenon where the model conflates verbosity with depth [cite: 10].

---

## 3. Confidence Scores and Calibration

### 3.1 The Calibration Problem in RLHF Models
A "calibrated" model is one where its predicted confidence aligns with its actual accuracy (e.g., answers marked with 80% confidence are correct 80% of the time). However, widely used models like GPT-4 and Claude, which are fine-tuned with Reinforcement Learning from Human Feedback (RLHF), are notoriously **miscalibrated**. RLHF tends to push model probabilities toward the extremes (0 or 1), making raw log probabilities unreliable indicators of correctness [cite: 11, 12].

### 3.2 Verbalized Confidence vs. Log Probabilities
A crucial finding for building the LLM grader is the superiority of **Verbalized Confidence**. Research demonstrates that explicitly asking the model to state its confidence (e.g., "Confidence: High" or "Confidence: 85%") results in significantly better calibration than extracting the model's internal conditional probabilities (logprobs) [cite: 11, 13].

*   **Empirical Evidence:** On benchmarks like TruthfulQA and TriviaQA, verbalized confidence reduced expected calibration error (ECE) by up to 50% compared to log probabilities [cite: 12].
*   **Mechanism:** Verbalized confidence forces the model to reflect on its own uncertainty as a token generation task, leveraging the same reasoning capabilities used for text generation.

### 3.3 Calibration Techniques
To utilize confidence scores effectively for filtering "noise" critiques:
1.  **Verbalized Numerical Probabilities:** Ask the model to output a score between 0.0 and 1.0 representing the probability that its evaluation is correct [cite: 14].
2.  **Consistency Checking (Self-Consistency):** Sample the model’s evaluation multiple times (e.g., 5 runs with temperature > 0). If the model gives the critique a "High Quality" rating in 5/5 runs, confidence is high. If it oscillates between "High" and "Low," the critique is likely borderline or the model is hallucinating [cite: 15, 16].
3.  **Invert Softmax Trick:** For classification tasks, treating the verbalized probabilities as logits and applying a post-hoc temperature scaling can further refine calibration [cite: 14].

**Recommendation:** Implement a **Confidence Threshold**. If the LLM grader rates a critique as "Valuable" but assigns a verbalized confidence score of <80%, flag it for human review or discard it. This directly addresses the "Success Criteria" by filtering out uncertain judgments.

---

## 4. Rubric Decomposition and Analytic Scoring

### 4.1 Holistic vs. Analytic Scoring
Holistic scoring (asking for a single 1-5 score) is prone to high variance and subjectivity. **Rubric Decomposition** (or Analytic Scoring) involves breaking the evaluation into orthogonal dimensions.

Recent research introduces frameworks like **DeCE (Decomposed Criteria-Based Evaluation)**, which separates evaluation into **Precision** (factual accuracy/relevance) and **Recall** (coverage of required concepts) [cite: 17, 18].
*   **Performance:** DeCE achieved a correlation of **r=0.78** with expert judgments in high-stakes domains (legal QA), compared to **r=0.35** for pointwise holistic scoring [cite: 17, 19].
*   **Relevance to Forethought:** In philosophical critiques, "Precision" maps to the logical validity of the counter-argument, while "Recall" maps to whether the critique addresses the core premises of the original paper rather than tangential points.

### 4.2 Hierarchical Aggregation (LLM-Rubric)
The **LLM-Rubric** approach suggests evaluating text across multiple specific dimensions (e.g., "Novelty," "Actionability," "Logical Soundness") and then using a learned function (like a small logistic regression or a weighted average) to combine these into a final score [cite: 20, 21].
*   **Benefit:** This method allows you to weight dimensions differently. For Forethought, "Novelty" and "Logical Soundness" might be weighted heavily, while "Tone" is weighted lightly.
*   **Calibration:** With a small sample size (~20), you can manually tune these weights to maximize agreement with human labels.

### 4.3 Question-Specific Rubrics
Generic rubrics (e.g., "Is this helpful?") perform worse than **Question-Specific Rubrics** generated dynamically for the specific research paper [cite: 22].
*   **Workflow:**
    1.  Feed the Research Paper Abstract to the LLM.
    2.  Ask the LLM to generate a specific rubric: "What would a valid critique of *this specific economic model* look like?"
    3.  Use this dynamic rubric to grade the incoming critiques.

---

## 5. Comparative Approaches: Pairwise vs. Pointwise

### 5.1 The Pairwise Advantage
Pairwise comparison ("Is Critique A better than Critique B?") is generally more reliable than absolute scoring because it simplifies the cognitive load on the model. It aligns better with human ranking preferences and avoids the calibration issues of absolute scales (where one model's "7/10" is another's "9/10") [cite: 23, 24].

### 5.2 The "Distracted Evaluation" Risk
However, pairwise evaluation introduces a critical vulnerability: **Distracted Evaluation**. Research shows that in pairwise settings, LLM judges are easily swayed by "distractor features" such as **assertiveness, verbosity, and formatting** [cite: 25, 26].
*   **Finding:** Pairwise preferences flipped in **35%** of cases when a distractor (like an assertive tone) was added to the lower-quality response, compared to only **9%** flipping in absolute scoring [cite: 25, 26].
*   **Implication:** A "noise" critique that is long and confidently written might beat a short, insightful critique in a pairwise comparison.

### 5.3 The Hybrid Solution: PREPAIR
To get the ranking benefits of pairwise comparison without the bias, we recommend the **PREPAIR (Pointwise Reasoning Enhance Pairwise Evaluating)** framework [cite: 10, 27].
*   **Method:**
    1.  **Pointwise Phase:** The model analyzes Critique A and Critique B independently, generating a detailed score and rationale for each *without seeing the other*.
    2.  **Pairwise Phase:** The model is presented with the *rationales* and *scores* from the first phase and asked to make a final comparison.
*   **Result:** This method significantly reduces bias toward superficial features like length and tone while maintaining high ranking accuracy [cite: 27].

---

## 6. Multi-turn Verification and Sycophancy

### 6.1 Sycophancy in Critique Evaluation
Sycophancy—the tendency of LLMs to agree with the user or the input text—is a major barrier to evaluating critiques. If a critique attacks a paper, the grader might rate it highly simply because the critique sounds authoritative, even if the reasoning is flawed [cite: 1].
*   **Regressive Sycophancy:** Agreeing with incorrect information or flawed logic.
*   **Prevalence:** Studies show sycophantic behavior in ~58% of cases across major models like Claude and GPT-4 [cite: 1].

### 6.2 Multi-turn Verification
Asking the model to "double-check" its answer (Multi-turn verification) can help, but it has diminishing returns.
*   **Pros:** Can catch obvious hallucinations or calculation errors.
*   **Cons:** Models often "double down" on their initial error to maintain consistency [cite: 28]. Furthermore, extended multi-turn conversations can degrade context and introduce drift.
*   **Better Approach:** Instead of simple "Are you sure?", use **Debate Protocols**. Ask one instance of the model to argue *for* the critique and another to argue *against* it, then have a third instance judge the debate. This forces the exploration of the critique's weaknesses [cite: 7].

---

## 7. Statistical Approaches for Small Sample Validation

### 7.1 The "Small N" Constraint
With only ~20 human-rated critiques, you cannot fine-tune a model. You are in the regime of **Few-Shot Prompting** and **Calibration**.

### 7.2 Bootstrapping for Uncertainty Estimation
To validate if your grader meets the >75% success criteria, you must use **Bootstrap Resampling** [cite: 29].
*   **Method:** Create 1000 resampled datasets (with replacement) from your 20 examples. Calculate the grader's accuracy on each.
*   **Output:** This will give you a Confidence Interval (e.g., "Accuracy is 78% ± 12%"). If the lower bound is below 75%, you cannot be statistically confident in the grader.

### 7.3 Hierarchical Modeling
Use **Hierarchical Generalized Linear Models (GLMs)** to analyze bias even with small samples. This statistical framework allows you to estimate the effect of "critique length" or "topic" on the score, separating true quality from bias [cite: 30].

### 7.4 The "Calibration Set" Strategy
Use the 20 examples as a **Calibration Set** rather than a Test Set.
1.  Run the LLM grader on the 20 examples.
2.  Train a simple logistic regression (or find a manual threshold) that maps the LLM's outputs (Score, Confidence, Length) to the Human Label [cite: 20, 31].
3.  This "aligned" score is your final metric.

---

## 8. Practical Recommendations for Forethought Research

Based on the synthesis of the above research, we propose the following architecture for the LLM Grader.

### 8.1 The "PREPAIR-Toulmin" Workflow

**Step 1: Pointwise Analysis with Toulmin CoT**
For each critique, prompt the LLM (Temperature=0) to:
*   **Decompose:** Identify the Claim, Warrant, and Backing.
*   **Verify:** Answer specific Critical Questions (e.g., "Is the Warrant valid in a post-AGI economic context?").
*   **Score:** Assign scores (1-10) on "Logical Soundness," "Novelty," and "Relevance."
*   **Confidence:** Output a verbalized confidence score (0.0-1.0).

**Step 2: Filtering**
*   Discard any critique with **Confidence < 0.8**.
*   Discard any critique where the **Warrant** is identified as "Missing" or "Fallacious."

**Step 3: Pairwise Ranking (Optional for Top Candidates)**
*   If you need to select the *best* 5 critiques from a batch of 20 passing ones:
*   Use the **PREPAIR** method: Feed the Toulmin analysis from Step 1 into a pairwise comparator to rank the remaining critiques.

### 8.2 Prompt Engineering Guidelines
*   **Avoid:** "Rate this critique 1-5." (Too holistic, prone to noise).
*   **Use:** "Does this critique identify a structural flaw in the author's assumption regarding capital accumulation? Answer Yes/No and explain the economic reasoning." (Domain-specific, binary).
*   **Defense against Sycophancy:** Add a system instruction: "You are a critical reviewer. Do not assume the critique is correct just because it is confident. Verify the economic assumptions independently."

### 8.3 Validation Protocol
1.  **Run the workflow** on the 20 human-rated critiques.
2.  **Calculate Agreement:** (True Positives + True Negatives) / Total.
3.  **Bootstrap:** Ensure the 95% confidence interval lower bound is acceptable.
4.  **Qualitative Audit:** Manually review the "Reasoning-Score Mismatch" cases. If the model reasons correctly but scores wrong, adjust the rubric weights.

## 9. Conclusion
Achieving >75% reliability in grading philosophical critiques is feasible but requires moving beyond simple "LLM-as-a-Judge" prompting. By anchoring the model in **Toulmin's argumentation structure**, utilizing **verbalized confidence** to filter uncertainty, and employing **decomposed rubrics** to isolate logical validity from tone, Forethought Research can build a robust grader. The key is to treat the LLM not as an oracle, but as a structured reasoning engine that must justify every step of its evaluation.

---

## 10. Key Papers & Resources
*   **[cite: 17, 18]** *Beyond Pointwise Scores: Decomposed Criteria-Based Evaluation (DeCE)* – Essential for rubric design.
*   **[cite: 11, 12]** *Verbalized Probabilities for RLHF-LMs* – Critical for calibration.
*   **[cite: 10, 27]** *PREPAIR: Pointwise Reasoning Enhances Pairwise Evaluation* – The solution to pairwise bias.
*   **[cite: 5, 8]** *Toulmin Argument Analysis with LLMs* – Domain-specific application for philosophy.
*   **[cite: 1]** *SycEval: Evaluating LLM Sycophancy* – Understanding the primary failure mode.

---
### Detailed Analysis of Calibration Techniques

#### 1. Chain-of-Thought (CoT)
**Findings:** CoT improves reasoning but introduces "Reasoning-Score Mismatch."
**Failure Modes:** Hallucination of steps, sycophancy toward assertive text.
**Recommendation:** Use **Toulmin-structured CoT** to force identification of Claim/Warrant/Backing.

#### 2. Confidence Scores
**Findings:** RLHF models are miscalibrated on logprobs. Verbalized confidence is superior.
**Recommendation:** Use **Verbalized Confidence** as a hard filter (discard <0.8).

#### 3. Rubric Decomposition
**Findings:** Holistic scoring fails on nuance. Decomposed criteria (Precision/Recall) correlate higher with experts.
**Recommendation:** Use **DeCE framework** adapted for philosophy (Logical Validity / Premise Relevance).

#### 4. Comparative Approaches
**Findings:** Pairwise is easier but biased by length/tone ("Distracted Evaluation").
**Recommendation:** Use **PREPAIR** (Pointwise analysis first, then Pairwise comparison) to mitigate bias.

#### 5. Multi-turn Verification
**Findings:** Self-correction is weak without external feedback; models tend to double down.
**Recommendation:** Use **Debate/Criticism** protocols rather than simple "Are you sure?" prompts.

**Sources:**
1. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEhvWpBsCTCjaopC6EC2MNpKE4vz0BDwi7rBbE4DeLzg7Kg0TlWLhmoNCkGCRx09f1ktfU-1to57578_2E-zrn0DDi0zfHhdi_JifC7emLCN_FM1LPttcbs)
2. [pacific.ai](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEow_4sKM6eoLc88THyWdGX8oH5z0Yp7llVijaGzGXlVoa1eU6aG519KtXyobJAdDWLvrwC_Av0Ij4-dC0V2HTdNL7sLiuYP91bvrZmQUIoXxifpwHMMU9q6M034ZbUmkqr76FMXmQN3XRqRWYBqBIvy-6y6tjRS2ztvsIFtnfYvyfGkhqEFHFWSIKQlDIMVdtRKjmbBg==)
3. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEycAMB5ky4UlzVDkdlpfQbNPtfPV5hBikJZ0BXQjf-t6L7cIKWYjC910qRBtO0H9IPQZ95kmtC5yTsHwMYkQVfE_XE7Va0_yUTXC-VAJSm0KnVVOqtISra)
4. [youtube.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQELYWyP-Xwhz5GKl0M9snWM8vPctUUfcGE4M6tQG83rtpJwjMX595Lh4MTiKBCZCToFWHT1Jcih5oLJA7vkvdSV9S28T5cYmLExd1j54ACVvwPoGfVyYeOKVb36Kplv8FI=)
5. [advanced-stack.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEo7B_5KILH8INjCgwi4E_yTQ-AOMWab61AaWlKggkKIjHwq-OgK1XReOmwQUFp5BSkQ4-APPLw-LzA6RHAyKneBvPBcD5VDHg1X6cO8xMe7i5OPd87BHcgolE-CIe9_WZADgRVLzAz6uzW_zmkvI_49S_NStDN4ls3gAFyWtghjO4gmk4GHR2dv_NCt_ZBNiZUgDwg-b19AqzIaDcaT5nnvkl3KqjnNg==)
6. [purdue.edu](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGJgT8ctmdD_Ewwqn8YBgLdcy6dS6KXqr-Tyqmtp_8NUoXjQCiXHu1eGM8bkYF1jbKzYmCs938OCvGqgGZ4vabxUs3o3AVczbGrAgTl05QE0hLBKg0nCnUboIqdtgxFgi5jeq8VrSdAFo2ficvkh6dVCMc8c9QAHjzk0gx_b3PfvaWoVaGjL5REOwPpaum4AHb64ikhGRcACp90ZIH9Izt9TD9cE1HEGscV1AWCewEn)
7. [researchgate.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEGO04lFdbu1HdsU5xbH2SxGb1tsMj1tvxRs03uOwbQEqmMnAVAS7li9kDP2jkAoxPXVch0QU0mgsDqC--sS9mTCLwhEpbkHQbp2dw8EUCJ8VSRgrWtadnGPKn-k9azvQJQUhaUWvksTAiDWhZ2Bn93TVX9zUpM0YBy7JmX_lRatFrEkIBzw20vY9qUpk1XXeJwrlTF4PaNM7jYr_GBwA_X4WvMRBMl0qy-Rr4ABBs3uCA4dPcrof8jcQ==)
8. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEnQ_RgtyykjNCV3IDerrB1qSw4mOHYay7R-ImxQZ_np0sWz39M5nF5DbHSQUhx190KlCkUjPvzURWktRPWHbLHbyUip9px--t0T9l0iv-75Fwpf8OurrTe)
9. [gopenai.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEDUl_S8V8obnrDGgQ9Y3AEunuo5hsF4vfzpx4EKjd6sfgurOrQ7ZfX3L38IUshmYaRMj7FljV7P1P5kJpzeyJgFLNrqYxlS2oWICYCmC_czlqvCBx8uO6aVFwlJJlIZITzSg6AnAN1LKQuTWtaTsaulJQEGtk-cJp8NhP3VqauqGVcAL2T6b4pzVYENEc3x5GjC4lqM1L0P6CQklsv5WfZAf_0QKpbaO5FhdiHX9w=)
10. [aclanthology.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEQk3lmPZjAo6BRdy1enmWDM7EgioGEqkjDyrnOncDL4YUY4Qrwza6qBI27EEQHiFqSLBo9lcYYrU36fAeMu_LTlwEdGZMmsKou8aqFhVT1LTLtowK6C3x8Wg6qmF8rNftYxP8BtPA=)
11. [openreview.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGXyRlNhu2G2Ev9whqg5qAEpAtfNFrAPn7qCSziSMReIWBuowpw9_pP7x4dTv4b2JwGo61F8ZQiP8J7L1KkgquERK0L_OwYz6A9bAOiYqdS_K82wy3N64qWcqAxHmlieA==)
12. [aclanthology.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEjYqkJgoqBzL0ECHppv-j5WwfMOrN5lkgvgl3_VToEzL6uWwB-CVi7_tXqsx2qj6vohBEtNK8Zv5yrUvg0teHKdX2jaMy6TOL0KSe1I-aLbnNFXjEJVWqzsJ47XTucbBe-HaujBQ==)
13. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFQosC1f5nqP9q5M2x69ybwwwMnIk8D5in3qR6uosXOtUFZWSIqflT87ME4RaJEEtfMbuFfwe33WQfrqLikQbxxFd6AG07G9JbpbljoSgBFBfVIF29T)
14. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFSmyjjSd-kdVe2SKEOjIIR794hs8YjiXy1uIQ05WDtWGTVDW2sbdDb8OApN9Lhygikv_Ndwb8jw0hsb74_JRjQI-l8orewFNKwCIGbmQzn3YFhK6zH368o)
15. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH5oso85ZnHu62Yw3Uh70IvM701Sq9I4NoP5pyaV03E5UKJI_uwvoQDQUPTYyAT_FTjc-1h7x_opCLfGqfDzZkK5u7qSXDVn900RT5SZ1bbQ1Qk3Y5LCKDa)
16. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEja6Xy9QuQ7e69gPI54h8azyO-UESNH03BLjw1N4DVHhsctiC-oTCk6hJUhMAvfKXMrEngSUrGc0oRp3X4bXTJ29kzW2x20VeUkeyGchRS4ldnD2Eecb5C2MncrkaofThzMHeDj-BhmS8GeSet_rhz7rW3kcHEXrxixmHryq5CXXrjg9xgSRU=)
17. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGt18s7_kIzJC8N8pbFlzdiE-mzS2ceb1FHDYc25tEJHxkYO_04hvNEqfwTdsbo0w-vpH7FmQ_tsd5-yEFcQQvabs6l9YkiTSMu6W0unubRiIkv_v8q3w==)
18. [aclanthology.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGhd2BTxtdTqQ5tS9I-oxN9HM4HiZGrIWovdD48VystuHKqgmztK70CgmUkHkVdL__LLmzr4LiqqpUm-zBogPz4euy_cMwptvu-eX5E8Tdo9oRv_bT6K0tvSYN7SvRjjzNAw6fWHfE=)
19. [aclanthology.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE79B7QOEAdfEQR34nhLau7-10C420FqWAyU9cTjWfTechrWQ22nnzh5_PLbYZle3Blf6dUYqDem4YUNbv21ebTqCq9a7doRfSv4qrNAI7LcYOZfoZpddE04TLpelL00VqPVl1yXzBCfJg=)
20. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHacQ29XYIZ7UnLWtn5udwZp0B-RmkSyypv4_TWcfZApJ8W9fC3m9c9uWpB8K4SXlPvbb7A6-zjhtlZywl67N-c83w5jOmEZF3o8klBgosU9N0GHYpyHDiX)
21. [aclanthology.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGQu8FuYBgIJV8qABQRZ-uvsdKIzHG_sfRiaBbokmGkmhNtM3Qcj2F_8WJewKa7Kqb_68wRbBtNuRp1eqhCaYQMquriGDrp4ytFgG-V52pOS7jG9JTJCsU55OrxTyphDj5cQ4A=)
22. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHg8QWZ1t95XvVQ8spfyz8ImW7sxbIo4UAKjt8FJ6rMgStHrBO0Zl9LCJE7nT3rnIcRVMXTqi2rVpmtBItq7qOr5BM5D9qaMgQk6uQIWkavfOdmC22EenuL)
23. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH4pDU1GavNg0dMO-M6LWyBfm202oyxrQuDH2Gyy7UhkKZf-_Y8NdfS--kbC5lbMx2wj0A7iIX1Zob5BpefOEGo8VVYxPQXpgLHMp2SXy7xn9bXkkhaxknLO1fgOPGKk6l-539A1FfHJi5JwJYrTkGH5GsEYHhqHbL3sw8ytSMeZ05b_P3K_YB4BHzUdKY8N62GKSsy95u6-sgHkDfC)
24. [lseg.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGZaMLv7VupjJIm8ivGx6M5Qv16Nz5EqzbYzgFb3QzLcgmEz1ieIKnbqFV0DGc150BxzauIcolJf70n8qNMp6WGUq0TOiRiPmmEjyFfK91nLewth_n_N-hbzf3F_Q_AuR3Z7V9sAJGgPXRyR79fCkeiApkxSdIyLrEIIz7y78LtbaBLE5CJeMWPzQ==)
25. [openreview.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFgaMz_i9Z2jwfQxblmv1MhmW7XYFWmJxmpApY_Zfgo8qjwWaO-ghV7cP1IpRdPv3iIrYAvw0alv4rTVTLlxYI_gr2OGNzn7NFuBL2Tb4CgmpMXZxgFjmhvTCh2J528vw==)
26. [researchgate.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQETZRD-T4bKH9pP1ncTy8IQpuEFg3Ec5_EtHQQ5jR7pOKMnFlo8ce-kkqpFl8roCVarZywxJ2kLoOm3HPat_kVY4-iTKog1BltBPwMMeuNLqwVlMTCNw5N4ZdrJa6NBjUQN-yOnbKgGdxFoakuxuhwTybm0hxiYb5PFUwk-jqLKiTq8hRwMdBDSHWGY21ktjbmffx2twAqEiFGJeukpSlWQ25xVy9HMrd6hcXNxFT07vL0sluDUXhnbzXjJKn0=)
27. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEEP76N3_wtec_c2A7iuk4twcDcvZ4MHGwJ6AKZXtrMtECejTdwxMlP7CPRxLjvdl7SXSpPWGqc1bjorFMwDpSnfQ2V3jY9J2PxOCX87b85WKoS48sxb1dU)
28. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH-3JBhUhPj2MIkZS6CarUmhpPJUiCmmF3kemYSbbZ8ouCxcSCp8hDBj5kJHj2Q3qw5GpgV-tnSpwAZU_HYEWX6ZTh6mxOReR5uX8tYFOj3G4BhTka2WxOXaEPHkw07ZIdzWnvG06HkqiZfS84ZCRDmmfmTu0FlzPZOG2O_c0MKSdq93f5tfUdHybWflNS9nkKE)
29. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHgouWKpvRZULHlp8P12UKkUw_MaHImmOJt-TmkQskm5sCM72si_SCkr37hGYKbfPMhKDA53umSlqKw6fwakwU90iakvR6Uo_mU4xXhFpieSfJqVD0WU9oSOWVDZBRVJgAOy3JQO4yn-S_ZIcsRGmlhpu9Ky4TzrQxBIDie8ZpePaWPz2YRmlsCl7UjQHpxWZ4yo1CQoHVyWvLaLylXU8q9IZ_ewaG36Y14AC4LsqnRCeB61VixigezNv4=)
30. [openreview.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQESviMAXnExmQBUbpTuJcFeIF5rv88KK6vTJQkQ6umyj9Q2209jRjBzcv5Z5kw7wQOGiyk5M2KqeCTGD37rvfhJRM7Ye-VpWP75NWondwyHepPyVCJ9cMLzytagz1cwJw==)
31. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGsCNyg8IcvJqJbj7CO9RL04HxrXiF15bWhjCHZI0D47v7Sq02J1stqf0s0vfroqJSZdLR8e4TGHDWb3FhbnXeFlUPozAOXZTSR3x9TDcJqRcXmqOiHl3WZ)
