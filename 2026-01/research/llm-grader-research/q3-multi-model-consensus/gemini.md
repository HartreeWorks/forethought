# Gemini Deep Research: q3-multi-model-consensus

**Model:** deep-research-pro-preview-12-2025
**Generated:** 2026-01-20T14:37:53.678Z
**Interaction ID:** v1_ChdENDl2YWVUOUc2ZUd4TjhQNDhPRDhBSRIXRDQ5dmFlVDlHNmVHeE44UDQ4T0Q4QUk

---

# Multi-Model Consensus and Ensemble Architectures for LLM-Based Evaluation of Philosophical and Economic Argumentation

## Executive Summary

**Key Findings**
*   **Multi-model voting significantly improves reliability:** Empirical evidence confirms that aggregating judgments from multiple Large Language Models (LLMs) outperforms single-judge evaluations, particularly for subjective and complex tasks like philosophical argumentation. The "Panel of LLM Evaluators" (PoLL) approach has been shown to reduce intra-model bias and improve correlation with human labels, often outperforming a single GPT-4 judge while being up to 7x cheaper [cite: 1, 2].
*   **Voting drives performance, not debate:** While multi-agent debate (MAD) is a popular concept, recent rigorous ablation studies indicate that the majority of performance gains in MAD systems stem from the **majority voting** component rather than the iterative dialogue itself. For Forethought Research, this suggests that implementing a voting ensemble is a more cost-effective and reliable strategy than complex debate architectures [cite: 3, 4, 5].
*   **Heterogeneity is critical:** Ensembles composed of diverse model families (e.g., Claude + GPT + Llama) outperform homogeneous ensembles (e.g., multiple calls to GPT-4). Heterogeneity mitigates shared blind spots and "mode collapse" where models from the same lineage exhibit identical biases [cite: 6, 7].
*   **Probabilistic scoring (G-Eval) enhances granularity:** Rather than relying on integer scores generated as text, accessing the token probabilities of the LLM's output allows for weighted averaging. This technique, known as G-Eval, provides finer-grained continuous scores that correlate more strongly with human judgment than discrete Likert scales [cite: 8, 9, 10].
*   **Small sample validation requires bootstrapping:** With only ~20 human-labeled critiques, standard accuracy metrics are statistically noisy. Bootstrapping and permutation tests are essential to establish confidence intervals and determine if improvements in the grader are statistically significant or merely random noise [cite: 11, 12].

**Recommendations for Forethought Research**
To achieve >75% agreement with researchers on high-level critiques, we recommend a **Heterogeneous Panel of Evaluators (PoLL)** using a **Weighted Average Scoring** mechanism. Specifically, the system should employ 3–5 distinct strong models (e.g., GPT-4o, Claude 3.5 Sonnet, Gemini 1.5 Pro) to independently evaluate critiques against a rubric. The final score should be derived using the G-Eval method (probability-weighted summation) to smooth out quantization artifacts. For validation, a bootstrap resampling approach must be adopted to rigorously assess the grader's performance against the small golden dataset.

---

## 1. Introduction

### 1.1 Research Context and Objectives
Forethought Research operates at the frontier of philosophy and economics, addressing the existential and societal implications of superintelligent AI. The organization’s research output—spanning longtermism, AI governance, and post-AGI economics—relies on rigorous argumentation rather than empirical observation. Consequently, the evaluation of critiques directed at this research requires a high degree of semantic understanding, logical sensitivity, and philosophical nuance.

The objective is to construct an **LLM grader** capable of filtering AI-generated critiques. The primary bottleneck is the scarcity of expert researcher time. An automated system must reliably distinguish between "noise" (generic, trivial, or hallucinated critiques) and "valuable" insights (novel counterarguments, identification of logical flaws, or economic implausibilities). The target success metric is a >75% agreement rate with human expert assessment.

### 1.2 The Challenge of Subjective Evaluation
Evaluating philosophical critiques differs fundamentally from code generation or fact-based Q&A. There is rarely a single "ground truth." A critique regarding the "rights of digital minds" or "discount rates in longtermist economics" involves weighing plausibility, coherence, and framing.
Current literature indicates that single LLM judges (even state-of-the-art models like GPT-4) suffer from specific failure modes in this domain:
*   **Self-Enhancement Bias:** Models tend to favor outputs that resemble their own training data or writing style [cite: 13, 14].
*   **Reasoning Instability:** On complex logical tasks, LLMs exhibit high variance; a slight change in prompt or seed can flip the verdict [cite: 15, 16].
*   **Style-Over-Substance:** Models often rate "fluent but vacuous" critiques higher than "rough but insightful" ones [cite: 17, 18].

This report investigates whether **multi-model consensus**—leveraging the collective intelligence of multiple LLMs—can mitigate these biases and achieve the required reliability threshold.

---

## 2. Theoretical Foundations of LLM Ensembling

### 2.1 The Condorcet Jury Theorem in AI
The theoretical underpinning of using multiple LLMs to improve evaluation accuracy is analogous to the **Condorcet Jury Theorem**. The theorem posits that if each individual juror (or model) has a probability $p > 0.5$ of reaching the correct verdict, and if their errors are statistically independent, the probability of the majority being correct approaches 1 as the number of jurors increases.

In the context of LLM graders:
*   **Competence ($p > 0.5$):** State-of-the-art models (GPT-4, Claude 3, etc.) have demonstrated "judge" capabilities that generally exceed random chance and often correlate with human preferences [cite: 14, 19].
*   **Independence:** This is the critical constraint. If all models in an ensemble share the same pre-training data (e.g., Common Crawl) and alignment techniques (RLHF), their errors may be highly correlated. This violation of independence diminishes the returns of ensembling [cite: 6, 20].

### 2.2 Types of Ensemble Architectures
Research identifies three primary architectures for LLM consensus:
1.  **Voting Ensembles:** Each model casts a discrete vote (e.g., "Valuable" vs. "Noise"). The final decision is a majority or supermajority vote. This is robust to outliers but loses nuance [cite: 3, 5].
2.  **Score Averaging (Mean/Median):** Models output a scalar score (e.g., 1–10). The system averages these scores. This reduces the variance inherent in stochastic sampling [cite: 8, 21].
3.  **Probabilistic Fusion:** Instead of averaging the final text output, the system aggregates the *probability distributions* of the tokens. This captures the model's uncertainty and provides a continuous signal from discrete classification tasks [cite: 22].

---

## 3. Empirical Evidence on Multi-Model Consensus

### 3.1 The "Panel of LLM Evaluators" (PoLL)
The most direct evidence supporting multi-model evaluation comes from Verga et al. (2024), who introduced the **Panel of LLM Evaluators (PoLL)**. Their research explicitly compared single large judges against ensembles of smaller, diverse models.

*   **Findings:** A panel of smaller models (e.g., combining Llama-3, Haiku, and Mistral) consistently achieved higher correlation with human judgment than a single pass of GPT-4.
*   **Bias Reduction:** The PoLL method significantly reduced **intra-model bias**. Single models often favor answers that align with their specific alignment fine-tuning. By pooling judgments from disjoint model families, these idiosyncratic biases tend to cancel out [cite: 1, 2, 8].
*   **Cost Efficiency:** The study found that a PoLL ensemble could be **7x less expensive** than a single large judge while delivering superior accuracy. This is particularly relevant for Forethought Research, where budget constraints might limit the use of multiple GPT-4 calls for every critique generated [cite: 23, 24].

### 3.2 Debate vs. Voting: The "Debate or Vote" Study
A popular hypothesis in AI research is that "Multi-Agent Debate" (MAD)—where models argue with each other to reach a conclusion—yields better results than static evaluation. However, a pivotal 2025 study titled *"Debate or Vote: Which Yields Better Decisions in Multi-Agent Large Language Models?"* challenges this.

*   **The Verdict:** The researchers disentangled the effects of "debate" (communication) and "voting" (ensembling). They found that **majority voting alone accounts for most of the performance gains** attributed to MAD.
*   **Implication:** The complex orchestration of multi-turn debate adds latency and cost with marginal accuracy gains over a simple parallel voting ensemble. For evaluating research critiques, a non-interactive voting ensemble is likely the optimal starting point [cite: 3, 4, 5, 25].
*   **Theoretical Insight:** The study modeled debate as a martingale process, proving that without specific interventions (like an "oracle" or strong bias toward correctness), debate does not inherently improve expected correctness beyond the initial ensemble aggregate [cite: 26, 27].

### 3.3 Homogeneous vs. Heterogeneous Ensembles
Research consistently favors **heterogeneous ensembles** (diverse models) over homogeneous ones (same model, multiple samples).
*   **Homogeneous Ensembles:** Running GPT-4 five times with high temperature (Self-Consistency) helps reduce stochastic noise but cannot correct for systematic blind spots or "hallucination modes" inherent to the model architecture [cite: 28, 29].
*   **Heterogeneous Ensembles:** Combining models with different architectures (e.g., Mixture-of-Experts vs. Dense) and different training data distributions (e.g., Anthropic's Constitutional AI vs. OpenAI's RLHF) creates a more robust jury. A study on "X-MAS" (Heterogeneous LLM-driven Multi-Agent Systems) demonstrated that heterogeneous configurations yielded up to an 8.4% performance improvement on math tasks and 47% on reasoning datasets compared to homogeneous setups [cite: 6, 7].

---

## 4. Advanced Ensemble Techniques for Grader Reliability

### 4.1 G-Eval: Probability-Weighted Scoring
For the specific task of grading critiques (which is likely a scalar rating or binary classification), the **G-Eval** framework (Liu et al., 2023) offers a significant reliability upgrade over standard prompting.

*   **Mechanism:** Instead of asking the LLM to output a number "4", G-Eval prompts the model to output the *probability* of the tokens "1", "2", "3", "4", and "5". The final score is the weighted average: $Score = \sum (p_i \times v_i)$.
*   **Benefit:** This converts a discrete, integer-based metric into a continuous, fine-grained score (e.g., 4.23). This continuous signal correlates much better with human judgment because it captures the model's uncertainty. If a model is torn between 3 and 4, a standard output forces a choice (introducing quantization noise), whereas G-Eval reflects the ambiguity [cite: 8, 9, 10].
*   **Application:** Forethought Research should implement G-Eval for the critique grader. If the rubric asks "Is this critique valid? (1-5)", the probability-weighted score will allow for a more precise threshold setting (e.g., "Keep all critiques > 3.7").

### 4.2 DeePEn: Distribution Fusion
For more advanced implementation, the **DeePEn** framework proposes fusing probability distributions across different models.
*   **Challenge:** Different LLMs have different vocabularies (tokenizers), making direct probability averaging difficult.
*   **Solution:** DeePEn maps probabilities to a universal "relative space" before aggregation. This allows the ensemble to agree on the *semantic meaning* of the next token (or judgment) even if they use different sub-word representations.
*   **Result:** This method has shown consistent improvements across reasoning and knowledge benchmarks, suggesting it effectively combines the "internal representations" of confidence from multiple models [cite: 22, 30].

### 4.3 Auto-Prompt Ensemble (APE)
Reliability is heavily dependent on the prompt. The **Auto-Prompt Ensemble (APE)** framework suggests that instead of a single "perfect" prompt, one should use diverse prompts that frame the evaluation differently (e.g., one prompt focusing on "logic," another on "economic assumptions," another on "tone").
*   **Collective Confidence:** APE introduces a confidence-based ensemble mechanism to decide when to trust specific evaluation dimensions. This dynamic weighting can enhance agreement rates on benchmarks like RewardBench [cite: 31].

---

## 5. Addressing Domain Specificity: Philosophy and Economics

### 5.1 The "Style Over Substance" Failure Mode
A critical failure mode for LLM judges in high-level domains is the **"Style Over Substance"** bias. Research using the SOS-Bench (Substance Outweighs Style) indicates that LLM judges often prefer responses that are polite, verbose, and structured, even if they contain factual errors or weak arguments.
*   **Relevance:** In evaluating critiques of longtermism or economics, a "noisy" critique might be written in perfect academic prose but miss the point, while a "valuable" critique might be shorter but identify a fatal flaw in a discount rate assumption.
*   **Mitigation:** Ensembles help, but **rubric design** is vital. The prompt must explicitly instruct the judge to penalize "generic fluff" and reward "novelty" and "falsifiability." Research suggests that "Chain-of-Thought" (CoT) prompting—forcing the model to reason *before* scoring—helps decouple style from substance [cite: 17, 18, 32].

### 5.2 Reasoning Instability in Abstract Domains
The domain of "digital minds" and "existential risk" relies on multi-step reasoning. Recent benchmarks like **ReasonBench** highlight that LLM reasoning is highly unstable; a model might get the right answer for the wrong reason, or flip its answer with a minor prompt change.
*   **Implication:** A single judge is unreliable for philosophical consistency.
*   **Solution:** **Max-Voting** or **Consensus Filtering**. If 3 out of 5 models flag a critique as "illogical," it is highly likely to be so. If the vote is 3-2, the critique is likely "borderline" or "ambiguous," which itself is a valuable signal for human review [cite: 15, 16].

---

## 6. Statistical Validation with Small Sample Sizes

### 6.1 The Small Sample Constraint (~20 Critiques)
Forethought Research has a validation set of only ~20 human-rated critiques. This is statistically dangerous; a "75% agreement" on 20 samples means getting just 15 right. A change of 1 or 2 correct predictions can swing the metric by 5-10%, making it hard to distinguish real improvement from noise.

### 6.2 Bootstrap Resampling
To validate the grader with such a small sample, **Bootstrap Resampling** is the gold standard technique recommended in data-scarce ML environments.
*   **Method:** Create $N$ (e.g., 1,000) "synthetic" datasets by resampling the 20 critiques *with replacement*. Calculate the agreement score for each resampled dataset.
*   **Output:** This produces a *distribution* of agreement scores rather than a single number. You might find the grader has an agreement of 75% $\pm$ 12% (95% Confidence Interval).
*   **Utility:** If a new ensemble method shifts the mean to 80% but the confidence intervals heavily overlap, the improvement is not statistically significant. This prevents the team from chasing "ghost" improvements [cite: 11, 12].

### 6.3 Permutation Tests
For comparing two grader versions (e.g., Single GPT-4 vs. Ensemble), use a **Paired Permutation Test**.
*   **Method:** Randomly swap the predictions of Grader A and Grader B for each critique and calculate the difference in performance. Repeat 10,000 times to build a null distribution.
*   **Result:** This calculates a p-value: the probability that Grader B's improvement over Grader A occurred by chance. This is robust even for small sample sizes where normality assumptions (like in t-tests) might be violated [cite: 11].

### 6.4 Confidence Intervals for Reliability
When reporting the success metric, always include the **Standard Error**. For binary agreement (Success/Failure), the standard error can be approximated, but with $n=20$, exact confidence intervals (e.g., Clopper-Pearson interval) should be used.
*   **Guidance:** Do not trust a single "80%" number. Trust the lower bound of the confidence interval. If the lower bound > 60%, the system is likely better than a coin flip [cite: 33, 34, 35].

---

## 7. Failure Modes and Limitations

### 7.1 Correlated Errors (Mode Collapse)
If the ensemble consists of models that are too similar (e.g., GPT-4, GPT-4-Turbo, GPT-4o), they will likely share the same "blind spots." If GPT-4 has a bias against a specific philosophical argument (e.g., regarding population ethics), the entire ensemble will fail simultaneously.
*   **Mitigation:** Ensure the panel includes models with different training methodologies (e.g., Claude 3 Opus, which uses Constitutional AI, vs. Llama 3, which uses standard RLHF) [cite: 6, 24].

### 7.2 Cost and Latency
While PoLL is cheaper than a single *massive* model, running 5 models is still more expensive than running 1 small model.
*   **Trade-off:** For "batch" processing of critiques (offline), latency is less critical. However, cost scales linearly with the number of voters.
*   **Optimization:** Use a **Cascading Ensemble**. Run a cheap, fast model (e.g., Llama-3-8B) first. If it assigns a very high or very low score (high confidence), accept it. Only trigger the full "Jury" of large models for ambiguous/middle-range cases. This preserves the ensemble's accuracy while drastically cutting costs [cite: 20, 36].

### 7.3 The "Pedantic Judge" Problem
LLMs trained on RLHF often become overly pedantic, flagging critiques as "noise" because of minor tone issues or lack of formal structure, even if the core economic argument is sound.
*   **Mitigation:** Few-shot prompting with examples of "messy but valuable" critiques is essential to calibrate the judges [cite: 37, 38].

---

## 8. Practical Recommendations for Forethought Research

Based on the synthesis of the research, we propose the following architecture for the LLM Grader:

### 8.1 Recommended Architecture: The "Heterogeneous G-Eval Jury"

1.  **Model Selection (The Jury):** Select 3 distinct models to maximize diversity.
    *   **Judge A:** GPT-4o (OpenAI) – Strong general reasoning.
    *   **Judge B:** Claude 3.5 Sonnet (Anthropic) – Strong on nuance and "Constitutional" alignment (often better for safety/ethics topics).
    *   **Judge C:** Llama-3-70B (Open Source/Meta) – Different training distribution, cost-effective.

2.  **Scoring Mechanism:**
    *   Do not ask for a binary "Yes/No."
    *   Ask for a score (1-5) on specific criteria: **Novelty**, **Logical Coherence**, **Relevance to Longtermism**.
    *   Use **G-Eval (Probabilistic Scoring):** Extract the token probabilities for scores 1-5 and calculate the weighted mean for each judge.
    *   **Aggregation:** Average the G-Eval scores of the three judges.

3.  **Decision Threshold:**
    *   Set a threshold (e.g., Score > 3.5) to classify a critique as "Valuable."
    *   Tune this threshold using the 20-sample validation set to maximize F1-score (balancing precision and recall).

### 8.2 Validation Workflow (Small Sample Protocol)
1.  **Annotate:** Ensure the 20 critiques have high-quality human labels (consensus of 2 researchers if possible).
2.  **Bootstrap:** Run the "Heterogeneous G-Eval Jury" on the 20 critiques. Perform 1,000 bootstrap resamples to estimate the 95% Confidence Interval of the agreement rate.
3.  **Iterate:** If the lower bound of the CI is < 70%, analyze the "disagreement" cases. Are the models missing nuance? If so, refine the rubric/prompt (add few-shot examples) rather than changing the models.

### 8.3 Prompt Engineering for Philosophy/Economics
*   **Persona:** "You are an expert editor at a top journal of philosophy and economics, specializing in longtermism and AI governance."
*   **Rubric:** Explicitly define "Noise."
    *   *Noise:* "Generic statements (e.g., 'AI is dangerous'), surface-level tone policing, or arguments already addressed in the text."
    *   *Valuable:* "Identifies a specific unaddressed premise, challenges the economic discount rate with a plausible alternative, or highlights a category error in the philosophical framework."

---

## 9. Conclusion

The empirical evidence strongly supports the hypothesis that **multi-model consensus improves correlation with human judgment**. For Forethought Research, a voting ensemble (specifically a heterogeneous panel using probability-weighted scoring) offers the most reliable path to the >75% success criteria.

While "debate" systems are intellectually appealing, the data suggests they are computationally inefficient compared to robust voting mechanisms. By leveraging a diverse jury of LLMs and applying rigorous statistical validation (bootstrapping) to handle the small sample size, Forethought Research can build a grader that effectively scales the evaluation of complex, high-stakes research critiques.

---

## Key Papers & Resources
*   **Verga et al. (2024):** *Replacing Judges with Juries: Evaluating LLM Generations with a Panel of Diverse Models.* (The core evidence for PoLL). [cite: 1, 39]
*   **Choi et al. (2025):** *Debate or Vote: Which Yields Better Decisions in Multi-Agent Large Language Models?* (Evidence that voting > debate). [cite: 3, 5]
*   **Liu et al. (2023):** *G-Eval: NLG Evaluation using GPT-4 with Better Human Alignment.* (The probabilistic scoring method). [cite: 10, 40]
*   **Zheng et al. (2023):** *Judging LLM-as-a-Judge with MT-Bench and Chatbot Arena.* (Foundational work on LLM judge biases). [cite: 14, 19]

**Sources:**
1. [semanticscholar.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEiT3c7iHemyvbHsYzAfEn6_ZVV8VVmP2MLjhhrG3AZNAXunjGh87FX3lFEc6LcNO1sJBrqNUDRfQxTmjUDrfN91O1xXtoZb9Q96eFmM-iiDKKQGW3NteQavlBJUn6CnC2YJS4rfFdCrFxw6yZ_V-_SibfYvG91mOlwb9MDpDHF5fwzaHUhijFhmLMAdnFlRMiBJdAhCaS8OxTFMlXf8lsjmBm8ZL4TJTfdzb3cl_SX4jXF2IvfcdetkN8OipcM56HYqCwPUZQnNb-p9YQ=)
2. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGaBobqbS-GZM9GErOMB4UUoIKDCMOT_x9Ri6rubMSwSur6bnONt7jKJZl9xU45Q9c5u11f62vlC0tXBVA2lln4JDOEP0P6WKkI3wSoIynwkhdsDWz0Bl2Jow==)
3. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQElJAhylm3GcFd8o5LTqOTFPfR4Axuef5TosmCGPtGEw68ngxn5ZxDzIy1QJH8F6N2ogjp6BKzNoHxDNcn_7QyIQD7qpow7kLnBy89am9Yht16V35vNaw==)
4. [themoonlight.io](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFqU1WpX6F5lpMrgDldQ63BMcL1stB6ukOuFnwdUj58X05VQdnqRO78i2XshIRT_GaEUqXkS6Shu5EFokdURgbn5TOe9JZYduS73CSb9d691QGoqdVOEeIcV43ShECFcmMN4SgoLpeAjmUC_TQEQOZ76b3x84fKUpAiyVKAo252VnzVlQ0E77tFGIQkvfUDz9_scK6x02OZTWrzd52cti-MV5hRPs1rnd5TeFQ-hA==)
5. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQErz1Jv7vXLV5jbQP7_2mTpXl1nomp3ypW-KacDVsSSEUAxNBjmlWjKy03U2z7n51WXv-oADt3qbPEB9HhFleawHVWGW8LuAg3Y-iZjkxTGyT9jqnJR62xZmw==)
6. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFprxJyBKJfVcPVpXO00FdaXux1u06DExaDb-K-zAGvd9DyvxLVwybjy7CW0OyZrFRYNLOGX7W7lUPGFZEzqYcFloEEZkks6nh8n_pi0Kt__mJzhEBERw==)
7. [rtu.lv](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFlw-jpBCgl7BIbClH3QHJkdXERQZ2uhXNSP-B17bxfA8i3NForhith6oCZqPqPNIk0lpE8GlQYQtGIw9Fi9ZYAYrxmtmb4KN24PhKxf-JYW216icM7_iryf2SEWaBmDQ8l2chmCyiZAS-dRdVY3LwQDSRHQJ_LMA==)
8. [eugeneyan.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHsYYQ6iiy2sB6vqgWJtuLphj8GVVF8kZVdLoYZWuia6Jap4eirFOKhXzfCEJcfzgB_rwRqRoAvDxKJy-LHGBBi-9FrJsMe4_pEVrrC5NZFADskiTWdTFf-KMpzgETBhx-x50k=)
9. [towardsdatascience.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE4cYSbGpaTgP52VScPUdaDj4Mj7T16psLfE3yxpFBnZgmhOFBDevX3MEat-2NbnWxUP3Rna5A4CjLcKd6wmmye8ES-ouDVybpR9HV_B5Y7hm7KVrsAxlwN0s0Cjs8YUBA6pzsyDqHXLV-mQMcdq3s8TqEYSaOcb_aXryrmUUo=)
10. [liner.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFVZadWef4l27Y-ZGKnd12q7al_dtw8UNK0C9byuIjU8xZb9kWSj8INcBNqchTwJw9lIhpK0pLKkSRd1_dJlz74mbLadAM9Lzlv1QBeLMDfz8tRSp9URLZEXlM0oYX3v82Zy-TNEwYqpKL6EcOfxtsWQO9EzzpJ_Pc-XdcrN7fVaW6KVqPVdT4iFhU=)
11. [towardsai.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE6y6VRzBU9S9HSSrt0FmAl-jsv_UOYExFPpp-lUBP0dLlW6aGnSz5OmQslirU8yZiCHZBzqoECZNDp4aLRZxjF3loP4B5Ph7l1UglzyU4kPMlhgyPioxr83xcbmSSmOM8KyUO7z8F54LlEDtKckNtn2FgcLQ7x87fFQWhbzx-h0POD7Gs-RrfOC6KzvlnVeaM=)
12. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFl-ovJ1cMCdZ4QcT7hiVOWEhnaKZob0KHlBN2UwtssAnrlu9zA-iQj-mL_9P5UoLUbbc9iB7snQjy51bX7j6IY-9V-oJIFyWQ6Tw_sIhDcyCHBhlzP3OD8Eyh_G_zbQ9qL2ftU9SHFgKfxQ5eq1QmDfIGv_wLJp2C1H_-ZJvCl1lU0uqY2mvyvRBxfeLMNvSX515P9ILvE4rdh0pkY4EW1nr5G-tiS9n5dIPM9IILnSVkF0EVhQKQiwXUp)
13. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQG9NQ7Ku-R2XwRUlMsSo4jUAnl1Xu91wDqgMWzEIpomq6nwl03P8d2GcGdYL_OAdFvrsmzqZPAKllm-QtX7bENAYcgVth02_xHpg-fNptHH69VJmhlbDGIMow==)
14. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEshV1MavqR4qouICrq9FiWR3ys4KP1b3PjOUWVbX5Ns-6WG0DasXtBVDl44VjvaYGySt3fOuwJ7V0UQtQUAdNWGrTDh6tHYT4Af4-WUrvRiwm3ZKkKfA==)
15. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGOh87mRUOV0mCrkG9-l21-8o6XyER-u7S3c8I9RTQ4v6XPe32vdbKe0TKTie20Cxko6xP45Fv4Xd1JFH521t1AIoFwI4Yon26uvORwJ49fSfUxmtaJ13ZAEA==)
16. [researchgate.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFeVQH42t48OBWZCAyGf-65EdFb0cVq0Amj6VGX2TOlRIRZX8sIhewtGQRRGQ3XfxrGEjTx9psLH6z2ffN9SuVYJCiwY-eBt7UgFq7gVuXF_Q2OuHQMNQ9ij9b_BCOQszea9QirHzbyMAPMOthuNgZqxa_i2jH3EK9cFoCYKB5cIIb4botaKbBhK89pY-jZY5e6Rzu2g5DneHTy9Qvz-W40veM=)
17. [openreview.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGU1owX4Bb-PL0wHlWh_hMmYSnT4-c9IugyhpJ4hqTe3u23faCUwIs77jt3FS1xtv2aOHQd85gKpgADpymOQeuXvxzbvmx1ynE6PVgbVgiS5qZiLfUKHSsXIYhrOJSrVYY=)
18. [researchgate.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHuSUtE7hbCsCeGy7Q7l3mOiuUtybtbAQkWVjHXvmf0i05KKlAh12iEFC3edA5hK-eEmMJnJMGasosmV6e0ACnEsT2RY6M_PoH4pL7OWxCyL4AgH2MliXXwssXenbBCsNerit0CBJxb4cXKvK0E3zKMVe3f8NThTtvHw0feNouNDejAsL23YqdmvlEerCA-tvQpsbWP7-bSIZvUR_FEtnrKwbxTu-Zcs-KRnIDSIetwfTUDhQ==)
19. [semanticscholar.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHbPiM50SrBoMfSGTtu1_n3atVhj8aYXwCU5ABeAt1F21OzXWnUu6qdEAJLeij8TsjjLtquU5ydCz57VcpPct_VTAwpxn2V1rvAH0YRcMqKjsiTPv7ZXhWHZgOqPq1g9jVSTlJvZf-fH_4Hc88ZSRQ5uOI-bnySSjQa14lTBIg_u5C9s6CvdzAGItNNAyL4MdAfitf7Tasp1SL2GbOngApiuRuLwWLsriFBUe3lCz6CseoElpAK48W-wC-AkCkrldUZ-Zo=)
20. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGeh6HvNINjYNmm25wAgoC2Z_6rhkcMvcpFYn6GSe8hEsAfzqiqK8eHiTY3ByBTQi5RFr1e3pn36d2O81FTV8Kg8hK7szmvKhwh0CIGnGZV1pQziLiFVNj1nMJ9kxbohk2ACocnskE9Qe_5vMQh_kVx4toEi-fspSqwIKIY_sM122JZGlOKZws0C2x9LQo8h4dOY_iA9okTx0Bl)
21. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE60pQRh2BGRbjYEe9EAnZ68BbvXzDrf1e1thY738eHzWCSBPif3YXRM3dNeZCaWOEKY49eWeWPKhBsWna30lvidJyBmyYPKFYD6u9cjdYypExngy0bNCwqFQ==)
22. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHQWtWle852LIPrmMMNCqcZbKFAJgfvMH-Go46lcGuvBiIp1J1dHo7omptRIypt1pIl6qHdFqErUBFLMH0KPQOq-Nzp5GXctt6B1lOfOASaRDR3gkieMw==)
23. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE1xp6g8CB4J587QbE_Kb5ojwXRv6O-xNZjtIxQ1jzRn9_fiHZ3suXYQoLMo9ve8AxpQVqwuSYbDYxYoquq8twN677dZVl_i2LtpN-2U9jB9ETZDXKuVhRmYJoZetZQXkh87kfUN0-XdAYyX-Y75HJPI5m8SLMNhbiU832_wNWGRbj1g7XyfNOWKJ6_EA==)
24. [huggingface.co](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFr5LylzIWYUcstcF_OS1Cb2oolhnOTqOPAXAnwXyBFMFReNt0fELUQSjCri_AZMYXfzoqZgyqTlhRkKsAvn5w0WU69BZiPiA4_ty9SrKO6qgS78PZZROuiy-2a8RDSaZgFTiUgLm6aX4vRbkm9-irtzpCaMevUi3F2KrfCNjSNjK1uLodN)
25. [researchgate.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFG7ld_l9K8VNPRpCdjajwerCaBLE2V80nmxvAt2pg6wfwEyRqWXtJmJEeQfrAJeTgUF894rSvNUuOba0eI7GgNlxDkd-vVuNDr-izvjOsQdIco4MvhSSaDFYDeJx484Xjaqamm9A6xrzfhVYhMxmHdu1ODjZKh8MNP_DM3fufQpricMQ--WapY0AosR7oMym-P03OLI0RRqGTSdbl8rZ9_nzbXyXQISaIVBLYUnUlOyN6yn8VTubHzpNvG)
26. [openreview.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHNlMFWd0mokRCCZiDtQburRE7seRgEO3HfDS0YXqkIIf5HNDPJXjLUdbjCg1nfxGYu8NH7luB9-Pj0pSgBPc8WEbqp3EXJXnGb-NqGRfRlOgPrz6nH_I_I66Bt6fEpoOU=)
27. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGR7tQ5QblecSBOmHj1gJ6Spv_1HXRJVIuLAC_TsN9-QRmhxRO-lYwSInIiE8KPeDOoyp_GEPLmtXDKuBOOsnir-LkkoVlIX4gJ9A-JSKrRlMJY8DFuhQ==)
28. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFxhJB5OYG_be2k47CqFdKICuXNZNeq0HvRhIX3nfEZxcNnMPsMuVXz0wN3VuNlZytoog_SmVlB2Zi0YhQwF6DDU4A2UBkdqTVl3eGtW--FmYdQzywB0uobFA==)
29. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFwBzs7BfmU5ND8AUtbMoEg4xYr4VVgRFG_pkg_tSTvHFa8rghxJ_qSh9DzdWQs9OEOK9W4aJT_7jiln8w5dvST9iPkmYBRphCPsXQpiYw71NYhxMrHRnXHNTZ5NW5Psk36Z6a9_B8LQXW5uS8TGVk8u9UKUnkuxqFpsrAyHz1Tt9TXUwoASlqznJUF)
30. [openreview.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEIvnb_Pj30rjWZ1SvJLUjwYHE8vSXSsDei1t15uzueFjJK0bwXRZ68ZDLXXFPpAN715BvzpS-_GMUeI-DpyFfo-TFDpeMYhFnGCagAjbTjzDrt7lCarDFvsqvhOYbggA4lF9HNulbTHj7q7MIZWSvpBq79jVBsBLFSuF0ANcK4wfMQUrRSX6RyDFO1oABJbNrd)
31. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGXV4_FV3yrKBo_aNbGuk4fvSq6osVjfZ_BBMXlh4YH2B3OWUhAhU5m0iuIX1J86T6YzjQAiXyMLzmzkdioFqgqHolMcDwmfJYXx7FyBxlH-2fVgLYd2w==)
32. [liner.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEb0MPHftmTevn2YKPNE_srBYt2JMfpidhalfZKaWra8zqcmpB7Z2wXuK8q52k9_Pzb_4Pdm5P4VHwhMevDVGAT0FrqJNVmoJSNZP6pd8fTMBkOkbNxTHYCdkfRtboVeiwUxTq0u-igYyB1kWbOWRgLDaVB-QV0AllhIqiPSeOCGbqk8a2zaiY5hHSe)
33. [medium.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHaowo-oaiguMA6XtrNv6miYG-0USlgAZ0xcNJglNRREV6vTVKq3eu_biEV8neWcXztxJR9xrY5EAzzQP1P0R3IDvQNQB5C6el90q7hJkVkOOrd0hToaseOpR-sJctNDF1uPYsMInCUxF5xWmt09kQB6BUDxBMP7Y0gkQyYCOdDTeQ5dTdQpJsZjbw3iW53-o8kYAo=)
34. [sunnybak.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQENIs8UV-4Xq2llioqY63uiVMpu7YDnCEUFIgLCstvkWkKXFxUsFRgHMf9mMBcpRQtFymX2pwVhb2dZv8q5rQiAzvin16Sm3n24L_jNDQHBRt4qU6LgMUZ5pksAKtv4ucxmV_btP0LkJMuXQs8=)
35. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFVWXcwxIHVB2XU6WfQywbKeuBEQS7j1liZbIIfLYz5dytpynm-ieGUfa-LMTw6KROxhjQ2T7edpAEUIPTS8NVJF4L6faCNb5zn_HskFKDiMCm13rxSxtpakQ==)
36. [researchgate.net](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHkromyFn0d8l0XWOOYGHdpZO7Tsx5KsHjPlClbkaJTAO2dFNw9DHOH1n3kJ5_YNRHnQvhbyxlo9nuZiQMT9h3jugmksbMdNB0mRXa5INyU-0hDLsgVXmv8mlX3ecRHjaFyMqmy8xXqhlrXlX_pITlEAcDXxnzwxrCur6XcrvKPQumE3tiKt-xDyOBOCO5UTu8Hz_8K2tQECXLO3MH9Z1wLC3DRa5n7WlPrxTKTa-M=)
37. [reddit.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGpBrM8s_dzOCTzSpueYh2_IXGeed9wjIK1SruwHA1BZIQwNmcm1Vv-65B9KMTrluj2RjHlY63VePcYhFo2x64_KKSs2gUbU9RMVUWeqVVJnMsUiT1-1IYZEW3XLBuzxxio4rM7o9eMGIbz7YIhiWdakVrL41VXZopV9HbmvKDGX-fyPK-Khs6n85s=)
38. [confident-ai.com](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH9616wK_5tILT3XgQVrY3G9jLAvCJvD1jpUM52LrAI2JHSGFInwkCP6p8HcfEY4LzdA5evwo5tIaGlQzM3msx9P9wtkIAXSmTKnScEVSixpU-Z3qT9It8tCExE9fsm7pH5yzCuVcjEHZf4KyiPNydoCH4_r-01nVoZcfGwnT2UtygKWcDP5zKgw5t1qg==)
39. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHdWN5OFLuOlfjS68WqPQJlMajuidg2ciD8kbJEItazjd6h_uxEDOqRDLaEeQ2kiFP3dfBgKA84PJRqoOp7oYd0n6vFVcEhE5KjCJoroTA8lAVePSF2VA==)
40. [arxiv.org](https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFyKh7U4xjI0cr2hc4v6ri-zZBNzbvJzE0ozV8nYu97GK16qIrkg04iZrJCZXF2A3UPO7ycJO5tXMrH-eNHQ3mgCLUUIJuPyC3ygHMYFVac8I0rnp86dA==)
