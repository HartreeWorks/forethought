<?php
$pageTitle = 'Deep research: Prompting techniques (2025-2026)';
include '../includes/header.php';
?>

<details class="prompt-card">
    <summary>
        <strong>Multi-model query</strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
<p><strong>Prompt:</strong> # Deep research: Prompting techniques for critique generation (2025-2026)</p>
<p><strong>Task:</strong> Research the most effective prompting techniques for complex reasoning and critique generation tasks, focusing exclusively on sources from 2025 and 2026.</p>
<p><strong>Context:</strong> I&#39;m developing prompts that ask LLMs to generate substantive critiques of academic papers. My current best prompts use techniques like:</p>
<ul>
<li>Explicit &quot;anti-slop&quot; constraints (banning generic outputs)</li>
<li>Structured reasoning steps before output</li>
<li>Specificity requirements (&quot;must be paper-specific, not copy-pasteable&quot;)</li>
<li>Role/persona assignment</li>
<li>Attack taxonomies (countermodel, causal reversal, etc.)</li>
</ul>
<p><strong>Research questions:</strong></p>
<ol>
<li><p><strong>What prompting techniques have emerged or been validated in 2025-2026</strong> for improving quality, specificity, and avoiding generic outputs? Look for:</p>
<ul>
<li>Academic papers on prompting (NeurIPS, ICML, ACL, arXiv)</li>
<li>Technical reports from AI labs (Anthropic, OpenAI, Google DeepMind)</li>
<li>High-quality practitioner sources (e.g., Simon Willison, Ethan Mollick, prompt engineering teams)</li>
</ul>
</li>
<li><p><strong>What techniques specifically help with critique/evaluation tasks?</strong> (red-teaming, debate, adversarial prompting, etc.)</p>
</li>
<li><p><strong>What has changed about effective prompting with 2025/2026 models</strong> compared to earlier models? (e.g., do chain-of-thought techniques still help? Are certain older techniques now unnecessary or counterproductive?)</p>
</li>
<li><p><strong>Are there any novel structural approaches</strong> (multi-turn, self-critique loops, verification steps, etc.) that have shown strong results?</p>
</li>
</ol>
<p><strong>Constraints:</strong></p>
<ul>
<li><strong>Ignore all sources dated before 2025</strong> — prompting best practices have changed significantly with recent models</li>
<li>Prioritise academically validated techniques, but include high-quality practitioner advice</li>
<li>Focus on techniques applicable to text generation (not image/code-specific)</li>
</ul>
<p><strong>Output format:</strong>
For each technique you find, provide:</p>
<ul>
<li>Name/description</li>
<li>Source (with date)</li>
<li>Key insight</li>
<li>Relevance to critique generation tasks</li>
</ul>
<p><strong>Time:</strong> 04/02/2026, 09:59:55</p>
    </div>
</details>

<div class="prose">
<h1>Synthesis</h1>
<h2>Executive Summary</h2>
<p>The 2025-2026 prompting landscape has fundamentally shifted: <strong>Chain-of-Thought prompting is no longer the default best practice</strong> for modern reasoning models (o1/o3, Claude 4+, GPT-5), which reason internally and may perform worse with explicit step-by-step instructions. The field has moved from &quot;prompt engineering&quot; to <strong>&quot;context engineering&quot;</strong>—focusing on <em>what information</em> you provide rather than how you phrase instructions. For critique generation specifically, the most validated approaches combine <strong>structured self-critique loops</strong>, <strong>checklist-based evaluation</strong> (binary decompositions rather than scalar scores), and <strong>multi-agent debate frameworks</strong> with explicit diversity mechanisms.</p>
<h3>Key Findings</h3>
<ul>
<li><p><strong>CoT has diminishing returns on reasoning models</strong>: The June 2025 Wharton report and OpenAI&#39;s own documentation confirm that reasoning models gain &quot;only marginal benefits despite substantial time costs (20-80% increase).&quot; For these models, focus on clear output constraints and success criteria rather than &quot;think step-by-step&quot; instructions.</p>
</li>
<li><p><strong>Context engineering supersedes prompt phrasing</strong>: Anthropic&#39;s research shows &quot;context rot&quot;—model accuracy degrades as context grows. For critique tasks, provide focused, relevant paper sections rather than entire documents. A targeted 300-token context often outperforms unfocused longer contexts.</p>
</li>
<li><p><strong>Self-critique loops work, but must be structured</strong>: Multiple 2025 papers (PANEL, Bohnet et al., Ho &amp; Fan) validate that iterative self-critique improves output quality. However, Wang et al. (Jan 2026) warns that reflection is often &quot;superficial&quot;—each critique point must be anchored to specific paper claims with testable criteria.</p>
</li>
<li><p><strong>Checklist-based judging outperforms Likert scales</strong>: CheckEval (EMNLP 2025) demonstrates that binary decomposed questions (&quot;Does critique X reference a specific claim?&quot;) improve reliability and reduce variance compared to subjective scoring.</p>
</li>
<li><p><strong>Multi-agent debate frameworks are validated for critique</strong>: DynaDebate (Jan 2026) and Tool-MAD (Jan 2026) show that debate succeeds when agents have enforced diversity (e.g., different reviewer personas) and process-centric critique (evaluating reasoning steps, not just conclusions).</p>
</li>
<li><p><strong>Constraints can backfire on stronger models (&quot;Prompting Inversion&quot;)</strong>: Khan (Oct 2025) found that rule-based prompts that helped GPT-4 actually <em>hurt</em> GPT-5 performance by inducing &quot;hyper-literalism.&quot; Keep constraints behavioural and testable rather than verbose and prescriptive.</p>
</li>
</ul>
<h3>Points of Disagreement</h3>
<ul>
<li><p>⚠️ <strong>Disagreement on CoT utility</strong>: Most models emphasise CoT&#39;s declining value, but <strong>OpenAI-deep-research</strong> still presents it as &quot;foundational&quot; for complex reasoning. <em>Analysis</em>: The consensus is that <strong>explicit</strong> CoT is unnecessary for reasoning models (which do this internally), but structured reasoning steps may still help <strong>non-reasoning models</strong> or when you need interpretable audit trails.</p>
</li>
<li><p>⚠️ <strong>Disagreement on instruction explicitness</strong>: <strong>Claude 4.5 Opus</strong> notes that Claude 4.x models require <em>more</em> explicit instructions than predecessors (&quot;If you say &#39;suggest,&#39; Claude will suggest. Not implement.&quot;), while <strong>GPT-5.2</strong> and <strong>OpenAI-deep-research</strong> warn that over-specification hurts GPT-5. <em>Analysis</em>: This is likely model-specific—Claude 4.x benefits from precise instructions while GPT-5 reasoning models prefer higher-level goals with fewer constraints.</p>
</li>
<li><p>⚠️ <strong>Disagreement on self-critique efficacy</strong>: <strong>Claude 4.5 Opus</strong> cites Bohnet et al. showing &quot;significant performance gains,&quot; while <strong>GPT-5.2</strong> cites Wang et al. warning reflection is &quot;often superficial.&quot; <em>Analysis</em>: Both are correct—self-critique works when properly structured (anchored to specific evidence, with testable criteria), but fails when generic (&quot;make it better&quot;).</p>
</li>
</ul>
<h3>Unique Insights</h3>
<ul>
<li><p><strong>[GPT-5.2-thinking]</strong>: PANEL framework (Mar 2025) uses natural-language critiques as search signals rather than scalar rewards, which directly maps to generating critique candidates then filtering via meta-critique. Also: YESciEval&#39;s approach to mitigating &quot;optimism bias&quot; in scientific evaluation.</p>
</li>
<li><p><strong>[Claude-4.5-opus-thinking]</strong>: &quot;Context rot&quot; phenomenon—model recall degrades with context length, suggesting focused excerpts outperform full papers. Also: XML tagging validated as top-5 technique in December 2025 testing.</p>
</li>
<li><p><strong>[Gemini-3-pro]</strong>: &quot;Decision Pivots&quot; technique—force model to identify the 3-5 minimal facts that must be true for the paper&#39;s argument to hold before writing critique. Also: &quot;Inoculation Prompting&quot;—explicitly list and ban &quot;lazy critique&quot; patterns (e.g., &quot;complaining about sample size without calculating power&quot;).</p>
</li>
<li><p><strong>[Grok-4.1]</strong>: Recursive Language Models (RLMs, Dec 2025) handle 100x longer contexts by having the LLM write code to recursively decompose and inspect sections—potentially enabling full-paper analysis without context rot. Also: ProRefine for inference-time prompt refinement without training.</p>
</li>
<li><p><strong>[OpenAI-deep-research]</strong>: Chain-of-Conceptual-Thought (CoCT, Oct 2025)—for open-ended critique, have model first tag the conceptual angle (e.g., &quot;Causal Inference Validity&quot;) then elaborate, producing more strategically organised critiques. Also: Buffer-of-Thought for reusing proven critique frameworks.</p>
</li>
</ul>
<h3>Confidence Level</h3>
<p><strong>High confidence</strong> on the core findings: CoT&#39;s diminishing returns on reasoning models, the shift to context engineering, and the effectiveness of structured self-critique loops are consistently supported across multiple academic sources (arXiv, ACL, EMNLP, NAACL 2025-2026). <strong>Moderate uncertainty</strong> remains about optimal constraint levels, which appear model-dependent—practitioners should test whether their specific model benefits from more or fewer explicit instructions.</p>
<hr>
<h1>gpt-5.2-thinking</h1>
<hr>
<p>Below are <strong>prompting techniques with credible 2025–2026 sources</strong> that specifically target <strong>reasoning quality, paper-specificity, and non-generic critique generation</strong>. I’ve biased toward methods that (a) <em>operationalize</em> critique quality (rubrics/checklists), (b) <em>force diverse counterarguments</em>, and/or (c) <em>add verification/reflection loops</em> without turning into “empty self-critique”.</p>
<hr>
<h2>Techniques (2025–2026 only)</h2>
<h3>1) <strong>“Don’t ask for Chain-of-Thought; ask for results + constraints” (Reasoning-model prompting)</strong></h3>
<ul>
<li><strong>Source (date):</strong> OpenAI Docs, <em>Reasoning best practices</em> (crawled recently; guidance applies to o‑series / GPT‑5 reasoning models). (<a href="https://platform.openai.com/docs/guides/reasoning-best-practices">platform.openai.com</a>)</li>
<li><strong>Name/description:</strong> For reasoning-optimized models, avoid “think step by step / show reasoning”. Use <strong>simple, direct instructions</strong>, strong <strong>output constraints</strong>, <strong>delimiters</strong>, and explicit <strong>success criteria</strong>.</li>
<li><strong>Key insight:</strong> For newer “reasoning models,” <strong>CoT-style prompting can be unnecessary or even counterproductive</strong>; model does the reasoning internally, and your leverage comes from <strong>tight specs</strong> and <strong>format/criteria</strong>. (<a href="https://platform.openai.com/docs/guides/reasoning-best-practices">platform.openai.com</a>)</li>
<li><strong>Relevance to critique generation:</strong> For paper critique prompts, this supports a shift from “reason step-by-step” → “produce an expert review that satisfies this checklist/rubric; cite paper-specific evidence; no generic filler.” It also aligns with your “anti-slop constraints”: keep them crisp and testable rather than verbose.</li>
</ul>
<hr>
<h3>2) <strong>Empirical: “CoT has diminishing returns on modern models”</strong></h3>
<ul>
<li><strong>Source (date):</strong> Wharton Generative AI Labs tech report, <strong>June 8, 2025</strong>, <em>The Decreasing Value of Chain of Thought in Prompting</em>. (<a href="https://gail.wharton.upenn.edu/research-and-insights/tech-report-chain-of-thought/">gail.wharton.upenn.edu</a>)</li>
<li><strong>Name/description:</strong> Treat CoT as a tool with <strong>model- and task-dependent</strong> payoff; on modern reasoning models, gains may be marginal vs time/cost.</li>
<li><strong>Key insight:</strong> CoT can increase latency/cost substantially and may not reliably improve outcomes on newer reasoning models; results vary by model/task. (<a href="https://gail.wharton.upenn.edu/research-and-insights/tech-report-chain-of-thought/">gail.wharton.upenn.edu</a>)</li>
<li><strong>Relevance to critique generation:</strong> If your critique prompt currently forces long “reasoning steps,” you may get better signal by reallocating tokens to: (a) paper-specific evidence quoting/paraphrase anchors, (b) explicit failure modes, (c) targeted counterexamples.</li>
</ul>
<hr>
<h3>3) <strong>Stepwise Natural-Language Self-Critique as <em>feedback</em> (PANEL)</strong></h3>
<ul>
<li><strong>Source (date):</strong> Li et al., <strong>Mar 21, 2025</strong>, <em>Dancing with Critiques… Stepwise Natural Language Self-Critique (PANEL)</em> (arXiv). (<a href="https://arxiv.org/abs/2503.17363?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Name/description:</strong> Generate <strong>explicit natural-language critiques of intermediate steps</strong> and use them to guide search/selection (an inference-time scaling method).</li>
<li><strong>Key insight:</strong> Rich critiques can outperform scalar “reward scores” by preserving <em>why</em> a step is wrong, improving selection of better reasoning trajectories. (<a href="https://arxiv.org/abs/2503.17363?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> This directly matches your goal: structure the model to produce <strong>paper-specific critique text</strong> as an intermediate artifact that <em>drives</em> refinement. Practical adaptation: have the model propose 2–4 critique candidates, then produce <strong>targeted critiques of each candidate</strong> (vagueness, not grounded in paper, missing countermodel), then synthesize.</li>
</ul>
<hr>
<h3>4) <strong>Two-stage Self-Critique → Refinement prompting (training-free)</strong></h3>
<ul>
<li><strong>Source (date):</strong> Ho &amp; Fan, <strong>Jun 19, 2025</strong>, <em>Self‑Critique‑Guided Curiosity Refinement</em> (arXiv). (<a href="https://arxiv.org/abs/2506.16064?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Name/description:</strong> Lightweight in-context loop: <strong>(1) self-critique (2) rewrite/refine</strong>, improving output quality without training.</li>
<li><strong>Key insight:</strong> Adding explicit critique+refinement steps can measurably reduce poor outputs and improve quality metrics (in their honesty/helpfulness setting). (<a href="https://arxiv.org/abs/2506.16064?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> Use this as a template for “anti-slop” enforcement:<ul>
<li>Draft review → self-critique against “paper-specificity / falsifiability / novelty / threat-to-validity” → rewrite.</li>
<li>Crucially: require the self-critique to point to <strong>exact sections/claims</strong> in the paper input, otherwise it becomes generic.</li>
</ul>
</li>
</ul>
<hr>
<h3>5) <strong>Guard against “superficial reflection” (reflection quality matters)</strong></h3>
<ul>
<li><strong>Source (date):</strong> Wang et al., <strong>Jan 19, 2026</strong>, <em>Teaching Large Reasoning Models Effective Reflection</em> (arXiv). (<a href="https://arxiv.org/abs/2601.12720?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Name/description:</strong> Identifies that reflection is often superficial; proposes methods (SCFT, RLERR) to make reflection <em>effective</em> (training-side).</li>
<li><strong>Key insight:</strong> “Reflection” isn’t automatically helpful; you need mechanisms that <strong>filter/penalize shallow critiques</strong>. (<a href="https://arxiv.org/abs/2601.12720?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> Even if you’re only prompting (not training), you can borrow the principle: <strong>make reflection earn its keep</strong>. E.g., require that each critique item includes (a) <em>paper anchor</em>, (b) <em>consequence if true</em>, (c) <em>minimal fix</em>, (d) <em>one falsifiable test</em>. Shallow critiques fail the rubric and trigger rewrite.</li>
</ul>
<hr>
<h3>6) <strong>Reasoning-Aware Self-Consistency (efficient multi-sampling + rationale selection)</strong></h3>
<ul>
<li><strong>Source (date):</strong> Wan et al., <strong>NAACL 2025</strong>, <em>Reasoning Aware Self-Consistency (RASC)</em>. (<a href="https://aclanthology.org/2025.naacl-long.184/">aclanthology.org</a>)</li>
<li><strong>Name/description:</strong> Sample multiple outputs/reasoning paths, then <strong>evaluate both answers and rationales</strong>, with early stopping + weighted voting to pick faithful rationales.</li>
<li><strong>Key insight:</strong> Multi-sampling can be made more efficient and can select <strong>higher-fidelity rationales</strong>, not just majority answers. (<a href="https://aclanthology.org/2025.naacl-long.184/">aclanthology.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> For paper reviews, sample multiple critiques from different “angles” (methods/statistics/related work/threats), then run a <em>judge prompt</em> that scores (1) paper-specificity, (2) novelty of insight, (3) severity/impact, (4) actionable fix. Keep sampling until marginal gains flatten.</li>
</ul>
<hr>
<h3>7) <strong>Multi-Agent Debate frameworks with <em>process-centric</em> critique</strong></h3>
<ul>
<li><strong>Source (date):</strong> Li et al., <strong>Jan 9, 2026</strong>, <em>DynaDebate</em> (arXiv). (<a href="https://arxiv.org/abs/2601.05746?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Name/description:</strong> Debate that avoids homogeneity via dynamic path generation; shifts from outcome voting to <strong>process-centric debate</strong> (step-by-step logic critique) and uses trigger-based verification on disagreements.</li>
<li><strong>Key insight:</strong> Debate fails when agents are too similar; explicit diversification + “process critique” improves results. (<a href="https://arxiv.org/abs/2601.05746?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> This maps cleanly onto academic review: instantiate agents as <strong>(A) friendly reviewer (B) hostile reviewer (C) stats/methods auditor (D) novelty/positioning reviewer</strong>. Force each to produce <em>non-overlapping</em> critiques, then run rebuttal/defense, then a “meta-review” judge.</li>
</ul>
<hr>
<h3>8) <strong>Debate with external tools + adaptive retrieval (Tool-MAD)</strong></h3>
<ul>
<li><strong>Source (date):</strong> Jeong et al., <strong>Jan 8, 2026</strong>, <em>Tool‑MAD</em> (arXiv). (<a href="https://arxiv.org/abs/2601.04742?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Name/description:</strong> Multi-agent debate where agents use <strong>different tools</strong> (search/RAG/etc.) and retrieval adapts as arguments evolve; judge uses faithfulness/relevance scores.</li>
<li><strong>Key insight:</strong> Debate quality improves when arguments can be grounded with <strong>iteratively retrieved evidence</strong>, not one-shot retrieval. (<a href="https://arxiv.org/abs/2601.04742?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> For paper critiques: one agent can retrieve related work claims (if allowed), another checks methodological validity, another checks definitions/assumptions—then judge scores “groundedness.” (If you restrict to only the paper text, use “tooling” as internal structured extraction instead.)</li>
</ul>
<hr>
<h3>9) <strong>Self-Debate Reinforcement Learning (SDRL) → practical prompting lesson: “debate-conditioned second pass”</strong></h3>
<ul>
<li><strong>Source (date):</strong> Liu et al., <strong>Jan 29, 2026</strong>, <em>Prepare Reasoning Language Models for Multi-Agent Debate…</em> (arXiv). (<a href="https://arxiv.org/abs/2601.22297?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Name/description:</strong> Training method, but its workflow is promptable: generate multiple solutions → create debate context → produce improved second-turn responses conditioned on the debate.</li>
<li><strong>Key insight:</strong> Conditioning on diverse candidate rationales can improve both debate performance and single-model reasoning. (<a href="https://arxiv.org/abs/2601.22297?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> Promptable recipe: “Generate 3 competing reviews with different theses; then write a second-pass review that explicitly addresses conflicts and preserves the strongest, most paper-grounded critiques.”</li>
</ul>
<hr>
<h3>10) <strong>Checklist-based judging (binary decomposition) to reduce rubric variance (CheckEval)</strong></h3>
<ul>
<li><strong>Source (date):</strong> Lee et al., <strong>EMNLP 2025</strong>, <em>CheckEval: … evaluating text generation using checklists</em>. (<a href="https://aclanthology.org/2025.emnlp-main.796/">aclanthology.org</a>)</li>
<li><strong>Name/description:</strong> Replace vague Likert scoring with <strong>checklists of binary questions</strong> (did it do X / did it ground claim Y), improving agreement and interpretability.</li>
<li><strong>Key insight:</strong> Decomposed binary criteria can improve reliability/consistency across judge models and reduce variance. (<a href="https://aclanthology.org/2025.emnlp-main.796/">aclanthology.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> This is extremely actionable for “anti-slop”:<ul>
<li>Judge checklist items like: “Does each critique reference a concrete paper claim/experiment?” “Does it propose a falsifiable test?” “Does it avoid generic ‘needs more experiments’ unless specifying which and why?”</li>
<li>Use checklist failures to trigger rewrite loops.</li>
</ul>
</li>
</ul>
<hr>
<h3>11) <strong>Rubric-based scientific judging with optimism-bias mitigation (YESciEval)</strong></h3>
<ul>
<li><strong>Source (date):</strong> D’Souza et al., <strong>ACL 2025</strong>, <em>YESciEval: Robust LLM-as-a-Judge for Scientific Question Answering</em>. (<a href="https://aclanthology.org/2025.acl-long.675/">aclanthology.org</a>)</li>
<li><strong>Name/description:</strong> A framework combining <strong>fine-grained rubric assessment</strong> with RL to reduce optimism bias in scientific evaluation.</li>
<li><strong>Key insight:</strong> Scientific evaluation needs robustness; rubric granularity and bias control matter. (<a href="https://aclanthology.org/2025.acl-long.675/">aclanthology.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> Even if you’re not training a judge, the lesson is: <strong>build domain rubrics that fight “everything is great” bias</strong>. In paper critique prompts, explicitly require “major concerns” and “fatal flaws check,” and judge them separately from “minor comments.”</li>
</ul>
<hr>
<h3>12) <strong>Prompt constraints can flip from helpful to harmful as models improve (“Prompting inversion”)</strong></h3>
<ul>
<li><strong>Source (date):</strong> Khan, <strong>Oct 25, 2025</strong>, <em>You Don’t Need Prompt Engineering Anymore: The Prompting Inversion</em> (arXiv). (<a href="https://arxiv.org/abs/2510.22251?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Name/description:</strong> Finds that a constrained, rule-based prompting method can help mid-tier models but hurt stronger models (“guardrail-to-handcuff”).</li>
<li><strong>Key insight:</strong> As models become more capable, some rigid constraints induce <strong>hyper-literalism</strong> and degrade results; optimal prompting must co-evolve with model strength. (<a href="https://arxiv.org/abs/2510.22251?utm_source=openai">arxiv.org</a>)</li>
<li><strong>Relevance to critique generation:</strong> This is a warning for “anti-slop” constraints: keep them <strong>behavioral and testable</strong> (must cite paper anchors, must include counterexample) rather than overly prescriptive prose rules that can handcuff strong models.</li>
</ul>
<hr>
<h2>What changed in 2025–2026 vs earlier prompting (for your use case)</h2>
<ol>
<li><strong>CoT prompting is no longer the default win</strong>—for modern reasoning models, best practice is trending toward <strong>direct task specs + evaluation criteria</strong>, not “think step-by-step.” (<a href="https://platform.openai.com/docs/guides/reasoning-best-practices">platform.openai.com</a>)  </li>
<li><strong>Critique generation is becoming a first-class control signal</strong>: newer research uses <em>natural-language critiques</em> as feedback to guide search/selection (e.g., PANEL), rather than only scalar scores. (<a href="https://arxiv.org/abs/2503.17363?utm_source=openai">arxiv.org</a>)  </li>
<li><strong>Reliable evaluation is moving from Likert scores to checklists</strong> (binary decompositions) to reduce judge variance and improve interpretability. (<a href="https://aclanthology.org/2025.emnlp-main.796/">aclanthology.org</a>)  </li>
<li><strong>Structural methods (debate / multi-agent / second-pass conditioned on disagreements)</strong> are increasingly formalized and validated in 2025–2026 work, with explicit mechanisms to avoid “agents all saying the same thing.” (<a href="https://arxiv.org/abs/2601.05746?utm_source=openai">arxiv.org</a>)</li>
</ol>
<hr>
<h2>If you want, I can turn this into “drop-in” prompt templates</h2>
<p>If you tell me:</p>
<ul>
<li>your target model family (e.g., GPT‑5 / Claude / Gemini / open-weight),</li>
<li>whether the critique must be <strong>paper-only</strong> or may use <strong>external citations</strong>,</li>
<li>desired review format (NeurIPS-style, ACL-style, etc.),</li>
</ul>
<p>…I’ll produce 2–3 concrete prompt blueprints implementing: <strong>CheckEval-style checklist judging</strong>, <strong>PANEL-like critique-guided refinement</strong>, and a <strong>DynaDebate-inspired multi-agent review</strong>—all tuned for “not copy-pasteable” critiques.</p>
<p><em>Latency: 69.3s | Tokens: 28642</em></p>
<h1>claude-4.5-opus-thinking</h1>
<hr>
<p>Based on my extensive research, I can now provide a comprehensive synthesis of prompting techniques for critique generation in 2025-2026.</p>
<hr>
<h2>Prompting Techniques for Critique Generation: 2025-2026 Research Synthesis</h2>
<h2>Executive Summary</h2>
<p>The prompting landscape has shifted significantly in 2025-2026. The most important finding is that <strong>newer models (Claude 4.x, GPT-5) require different prompting approaches</strong> than earlier models—many traditional techniques are now less effective or even counterproductive. The field has also expanded from &quot;prompt engineering&quot; to the broader concept of <strong>&quot;context engineering.&quot;</strong></p>
<hr>
<h2>1. What Has Changed in 2025-2026</h2>
<h3>Chain-of-Thought (CoT) Has Diminishing Returns</h3>
<p>A June 2025 Wharton technical report by Meincke, Mollick et al. tested Chain-of-Thought prompting and found that &quot;its effectiveness varies significantly by model type and task: non-reasoning models show modest average improvements but increased variability in answers, while reasoning models gain only marginal benefits despite substantial time costs (20-80% increase).&quot;</p>
<p><strong>Key insight for critique generation:</strong> Chain-of-Thought prompting is not universally optimal. Its effectiveness depends significantly on model type and specific use case. For non-reasoning models, CoT may improve average performance but can introduce inconsistency. For reasoning models, the minimal accuracy gains rarely justify the increased response time.</p>
<h3>Claude 4.x Models Require More Explicit Instructions</h3>
<p>Claude 4.x models have been trained for &quot;more precise instruction following than previous generations.&quot; These models respond well to clear, explicit instructions. Being specific about your desired output can help enhance results. &quot;Customers who desire the &#39;above and beyond&#39; behavior from previous Claude models might need to more explicitly request these behaviors with newer models.&quot;</p>
<p>Claude 4.x models were trained for precise instruction following. &quot;If you say &#39;suggest some changes,&#39; Claude will suggest. Not implement. Suggest.&quot; If you want changes made, you need to say &quot;make these changes&quot; or &quot;implement this.&quot;</p>
<p><strong>Relevance to critique generation:</strong> You must explicitly request the depth, specificity, and type of critique you want. Generic requests like &quot;critique this paper&quot; will yield surface-level responses.</p>
<h3>Reasoning Models Don&#39;t Need CoT Prompts</h3>
<p>Reasoning models like O1 &quot;internally reason by default. Explicit step-by-step prompts are usually unnecessary and can be counterproductive.&quot; This is a significant departure from 2023-2024 best practices.</p>
<hr>
<h2>2. The Shift to Context Engineering</h2>
<h3>Core Concept</h3>
<p>According to Anthropic, &quot;Building with language models is becoming less about finding the right words and phrases for your prompts, and more about answering the broader question of &#39;what configuration of context is most likely to generate our model&#39;s desired behavior?&#39; Context refers to the set of tokens included when sampling from a large-language model (LLM). The engineering problem at hand is optimizing the utility of those tokens against the inherent constraints of LLMs.&quot;</p>
<p>Simon Willison notes that &quot;the inferred definition of &#39;context engineering&#39; is likely to be much closer to the intended meaning&quot; than prompt engineering, which many view as &quot;a laughably pretentious term for typing things into a chatbot.&quot;</p>
<h3>Context Rot Is Real</h3>
<p>Anthropic&#39;s research shows that &quot;as the number of tokens in the context window increases, the model&#39;s ability to accurately recall information from that context decreases. While some models exhibit more gentle degradation than others, this characteristic emerges across all models. Context, therefore, must be treated as a finite resource with diminishing marginal returns.&quot;</p>
<p><strong>Relevance to critique generation:</strong> When asking for paper critiques, provide only the most relevant sections rather than entire papers. A focused 300-token context often outperforms unfocused longer contexts.</p>
<hr>
<h2>3. Validated Techniques for 2025-2026</h2>
<h3>Extended Thinking / Reasoning Modes</h3>
<p><strong>Source:</strong> OpenAI GPT-5 Cookbook, Anthropic Claude 4.x documentation (2025)</p>
<p>Extended thinking shows significant gains. &quot;On the AIME 2025 math competition, scores improved significantly. Cognition AI reported an 18% increase in planning performance with Sonnet 4.5.&quot; For complex reasoning: &quot;Understand the logic of this puzzle systematically. Go through the constraints step by step, checking each possibility before reaching conclusions.&quot;</p>
<p>Effectiveness rating: &quot;10/10 for complex reasoning, 3/10 for simple queries.&quot;</p>
<p><strong>Relevance to critique generation:</strong> Enable extended thinking for substantive academic critique. The reasoning overhead is justified for complex analytical tasks.</p>
<h3>Self-Critique / Self-Refinement Loops</h3>
<p><strong>Source:</strong> arXiv (December 2025)</p>
<p>A December 2025 paper by Bohnet et al. demonstrates &quot;an approach for LLMs to critique their own answers with the goal of enhancing their performance that leads to significant improvements over established planning benchmarks. Despite the findings of earlier research that has cast doubt on the effectiveness of LLMs leveraging self critique methods, we show significant performance gains on planning datasets... through intrinsic self-critique, without external source such as a verifier.&quot;</p>
<p>&quot;Self-refinement enables an LLM to iteratively improve its own outputs through self-critique and feedback loops. The process follows a structured cycle: (1) generating an initial response, (2) evaluating or critiquing the response based on task-specific prompts, and (3) refining the output iteratively until a defined stopping criterion is met.&quot;</p>
<p><strong>Relevance to critique generation:</strong> Build multi-turn critique workflows where the model first generates a critique, then critiques its own critique for specificity and rigor.</p>
<h3>Dual-Loop Reflection (Extrospection + Introspection)</h3>
<p><strong>Source:</strong> Nature npj Artificial Intelligence (December 2025)</p>
<p>A 2025 Nature publication proposes &quot;a dual-loop reflection method. First, the LLM critiques its own reasoning process against human reference responses (extrospection). The reflections gained from this process build a reflection bank.&quot; This addresses &quot;the shallow reasoning problem, which means that the LLM sometimes outputs responses that are polished in structure and style but fail to address the core of the comment.&quot;</p>
<p><strong>Relevance to critique generation:</strong> This directly addresses the &quot;slop&quot; problem you identified. The technique forces models to compare their reasoning against substantive examples rather than just polishing surface structure.</p>
<h3>Adversarial Prompting for Quality</h3>
<p><strong>Source:</strong> Skim AI (February 2025)</p>
<p>Adversarial prompting &quot;involves challenging the LLM&#39;s initial responses or assumptions to improve the quality, accuracy, and robustness of its outputs. This method simulates a debate or critical thinking process, pushing the model to consider alternative viewpoints, potential flaws in its reasoning, or overlooked factors. The adversarial approach works by first asking the LLM to provide an initial response or solution, then prompting it to critique or challenge its own answer. This process can be repeated multiple times, each iteration refining and strengthening the final output.&quot;</p>
<p><strong>Relevance to critique generation:</strong> Perfect for academic paper critique—have the model first generate a critique, then challenge each criticism from the paper author&#39;s perspective, then synthesize.</p>
<h3>XML Tagging for Structure</h3>
<p><strong>Source:</strong> Anthropic documentation, DreamHost testing (December 2025)</p>
<p>&quot;Claude can parse both plain text and XML-formatted prompts. XML provides better structure for complex instructions with multiple sections, examples, or constraints.&quot;</p>
<p>After testing 25 techniques, researchers found that &quot;5 deliver measurable results: XML tags, extended thinking, explicit instructions, few-shot examples, and context placement.&quot;</p>
<p><strong>Relevance to critique generation:</strong> Use XML tags to structure your critique taxonomy (e.g., <code>&lt;countermodel_critique&gt;</code>, <code>&lt;causal_reversal_critique&gt;</code>, <code>&lt;methodological_critique&gt;</code>).</p>
<h3>Calibrated Confidence Prompting</h3>
<p><strong>Source:</strong> Medium (October 2025)</p>
<p>&quot;Calibrated Confidence Prompting ensures the model provides accurate confidence levels with each claim... This technique is essential when accuracy and reliability are paramount — situations like research, fact-checking, or making critical business decisions.&quot;</p>
<p><strong>Relevance to critique generation:</strong> Ask the model to rate its confidence in each criticism—this helps filter out generic or weakly-supported critiques.</p>
<hr>
<h2>4. LLM-as-Judge for Critique Quality</h2>
<p>A major development relevant to your task is using LLMs to evaluate critique quality:</p>
<p><strong>Source:</strong> arXiv Survey (October 2025)</p>
<p>The &quot;LLM-as-a-Judge&quot; paradigm uses &quot;LLMs as evaluators for complex tasks. With their ability to process diverse data types and provide scalable, cost-effective, and consistent assessments, LLMs present a compelling alternative to traditional expert-driven evaluations. However, ensuring the reliability of LLM-as-a-Judge systems remains a significant challenge that requires careful design and standardization.&quot;</p>
<p>&quot;Chain-of-thought (CoT) in LLM as a judge means prompting the judge model to explain its reasoning step-by-step before giving a final score... This improves reliability by 10-15% and provides debuggable reasoning trails.&quot;</p>
<p><strong>Relevance to critique generation:</strong> Use a second LLM call to evaluate whether each generated critique is paper-specific, substantive, and actionable—exactly the anti-slop check you need.</p>
<hr>
<h2>5. Techniques Specifically for Evaluation/Critique Tasks</h2>
<h3>Self-Ask for Research Analysis</h3>
<p><strong>Source:</strong> Learn Prompting (March 2025)</p>
<p>&quot;If you&#39;re a student or researcher, LLMs can serve as valuable research assistants. By using the Self-Ask method, you can critically analyze a research paper&#39;s findings. For instance, you might start by asking, &#39;Does this research paper provide sufficient evidence to support its conclusions?&#39; This can then lead to more specific sub-questions like, &#39;What methodology was used to gather data?&#39; or &#39;Are there any gaps in the evidence presented?&#39; This way, you can dig deeper and make sure LLM notices the details all the way through the process.&quot;</p>
<h3>Multi-Perspective Simulation</h3>
<p><strong>Source:</strong> Medium (October 2025)</p>
<p>&quot;Multi-Perspective Simulation essentially runs a virtual expert panel within a single AI conversation... This technique has helped me identify critical considerations that were initially overlooked in approximately 70% of strategic analyses. It&#39;s like having a diverse advisory board available instantly.&quot;</p>
<p><strong>Relevance to critique generation:</strong> Simulate multiple reviewer perspectives (methodologist, domain expert, statistician) to generate diverse critique angles.</p>
<h3>Dynamic Recursive CoT (DR-CoT)</h3>
<p><strong>Source:</strong> Nature Scientific Reports (October 2025)</p>
<p>&quot;DR-CoT synergistically integrates recursive reasoning, dynamic context truncation, and a voting mechanism. By selectively retaining the most salient context within a fixed token budget and aggregating inferences from multiple independent reasoning chains, DR-CoT significantly enhances reasoning accuracy.&quot;</p>
<hr>
<h2>6. What to Avoid in 2025-2026</h2>
<h3>Over-Prompting with CoT for Reasoning Models</h3>
<p>Research shows &quot;Complex prompting showed no benefits over simpler approaches in medical tasks... Complex prompting techniques do not significantly enhance performance compared to simpler approaches. Dataset characteristics and model architecture have greater impact.&quot;</p>
<h3>Aggressive Language for Instruction Following</h3>
<p>&quot;If you&#39;ve been using aggressive language like &#39;CRITICAL: You MUST use this tool when...&#39;, Claude 4.5 will overtrigger. The guide says: &#39;Claude Opus 4.5 is more responsive to the system prompt than previous models.&#39;&quot;</p>
<h3>Assuming Models Need Examples</h3>
<p>For reasoning models: &quot;Minimal Use of Few-Shot Examples: Unlike earlier LLMs, advanced reasoning models perform better with fewer or no examples, as excess context can reduce performance.&quot;</p>
<hr>
<h2>7. Recommended Architecture for Academic Critique Generation</h2>
<p>Based on this research, here&#39;s a suggested approach for your academic paper critique system:</p>
<h3>Prompt Structure</h3>
<pre><code class="language-xml">&lt;critique_task&gt;
  &lt;paper_context&gt;
    [Relevant sections only - avoid context rot]
  &lt;/paper_context&gt;
  
  &lt;critique_taxonomy&gt;
    &lt;attack_type name=&quot;countermodel&quot;&gt;
      [Definition and what makes it paper-specific]
    &lt;/attack_type&gt;
    &lt;attack_type name=&quot;causal_reversal&quot;&gt;
      [Definition and specificity requirements]
    &lt;/attack_type&gt;
    &lt;!-- etc --&gt;
  &lt;/critique_taxonomy&gt;
  
  &lt;anti_slop_constraints&gt;
    Each critique MUST:
    - Reference specific claims, methods, or data from THIS paper
    - Be impossible to apply to a different paper without modification
    - Include confidence rating (high/medium/low) with justification
  &lt;/anti_slop_constraints&gt;
  
  &lt;output_requirements&gt;
    After generating each critique, evaluate: &quot;Would this criticism apply equally well to a different paper in the same field?&quot; If yes, the critique is too generic—revise or discard.
  &lt;/output_requirements&gt;
&lt;/critique_task&gt;
</code></pre>
<h3>Multi-Turn Workflow</h3>
<ol>
<li><strong>Initial critique generation</strong> (with extended thinking enabled)</li>
<li><strong>Self-critique pass</strong>: &quot;Which of these criticisms are generic enough to apply to other papers?&quot;</li>
<li><strong>Specificity enhancement</strong>: &quot;For each criticism flagged as generic, tie it more specifically to this paper&#39;s claims/methods&quot;</li>
<li><strong>Adversarial challenge</strong>: &quot;If you were the paper author, how would you defend against each critique?&quot;</li>
<li><strong>Final synthesis</strong>: Retain only critiques that survive adversarial challenge</li>
</ol>
<h3>Model Selection</h3>
<ul>
<li>For Claude 4.x: Use explicit instructions, enable extended thinking for complex papers</li>
<li>For GPT-5/reasoning models: Skip explicit CoT instructions—they reason internally</li>
<li>Consider using a second model as a &quot;judge&quot; to evaluate critique quality</li>
</ul>
<hr>
<h2>Key Sources Summary</h2>
<table>
<thead>
<tr>
<th>Technique</th>
<th>Source</th>
<th>Date</th>
<th>Key Finding</th>
</tr>
</thead>
<tbody><tr>
<td>CoT diminishing returns</td>
<td>Wharton Technical Report</td>
<td>June 2025</td>
<td>CoT helps non-reasoning models slightly, not reasoning models</td>
</tr>
<tr>
<td>Intrinsic Self-Critique</td>
<td>arXiv (Bohnet et al.)</td>
<td>Dec 2025</td>
<td>Self-critique works for planning/reasoning tasks</td>
</tr>
<tr>
<td>Dual-Loop Reflection</td>
<td>Nature npj AI</td>
<td>Dec 2025</td>
<td>Extrospection + introspection addresses shallow reasoning</td>
</tr>
<tr>
<td>Context Engineering</td>
<td>Anthropic Blog</td>
<td>2025</td>
<td>Focus on context configuration, not just prompt wording</td>
</tr>
<tr>
<td>Extended Thinking</td>
<td>Anthropic/OpenAI docs</td>
<td>2025</td>
<td>18%+ improvement on complex reasoning</td>
</tr>
<tr>
<td>Claude 4.x Specificity</td>
<td>Anthropic Docs</td>
<td>Nov 2025</td>
<td>Must explicitly request behaviors you want</td>
</tr>
<tr>
<td>XML Tagging</td>
<td>DreamHost testing</td>
<td>Dec 2025</td>
<td>Top 5 validated techniques for Claude</td>
</tr>
<tr>
<td>LLM-as-Judge</td>
<td>arXiv Survey</td>
<td>Oct 2025</td>
<td>CoT explanations improve judge reliability 10-15%</td>
</tr>
</tbody></table>
<p><em>Latency: 111.2s | Tokens: 379961</em></p>
<h1>gemini-3-pro</h1>
<hr>
<p>Current research from 2025 and 2026 highlights a significant shift in prompting strategies. The focus has moved from simple instruction tuning (&quot;act as X&quot;) to <strong>structural engineering</strong> of the reasoning process itself. The emergence of &quot;Reasoning Models&quot; (like OpenAI&#39;s o1/o3 and DeepSeek R1) has also changed the utility of older techniques like Chain-of-Thought (CoT).</p>
<p>The following techniques and insights are most effective for substantive critique generation.</p>
<h3>1. New &amp; Validated Prompting Techniques (2025-2026)</h3>
<p>These techniques have emerged from recent academic conferences (ICML, NeurIPS) and technical reports, specifically targeting reasoning quality.</p>
<h4><strong>A. Decision Pivots (Short-Path Reasoning)</strong></h4>
<ul>
<li><strong>Source:</strong> <em>ICML 2025 Workshop on Reasoning</em></li>
<li><strong>Description:</strong> Instead of asking for a long chain of reasoning immediately, this technique forces the model to first identify the &quot;pivots&quot;—the minimal set of critical facts or logic points that <em>must</em> be true for an argument to hold.</li>
<li><strong>Relevance to Critique:</strong> Prevents the model from hallucinating a critique based on fluff. It anchors the critique in verifiable facts before the model writes the narrative.</li>
<li><strong>Implementation:</strong><blockquote>
<p>&quot;Before critiquing, identify the 3-5 &#39;Decision Pivots&#39; in this paper—the specific data points or logical bridges that, if falsified, would cause the entire conclusion to collapse. List these pivots first, then evaluate only these points.&quot;</p>
</blockquote>
</li>
</ul>
<h4><strong>B. Recursive Self-Improvement Prompting (RSIP)</strong></h4>
<ul>
<li><strong>Source:</strong> <em>Practitioner reports &amp; arXiv 2025</em></li>
<li><strong>Description:</strong> A structural loop explicitly defined in the prompt that forces the model to generate, critique, and then rewrite its own output <em>within a single response</em>.</li>
<li><strong>Relevance to Critique:</strong> Substantially increases the depth of analysis by forcing the model to &quot;climb&quot; its own reasoning ladder.</li>
<li><strong>Implementation:</strong><blockquote>
<p>&quot;Task: Critique the methodology.
Process:</p>
<ol>
<li>Write an initial critique.</li>
<li><strong>Reflect:</strong> Identify 3 specific ways your initial critique is generic, vague, or charitable.</li>
<li><strong>Refine:</strong> Rewrite the critique to address these weaknesses, making it sharper and more evidence-based.</li>
<li>Output ONLY the final refined critique.&quot;</li>
</ol>
</blockquote>
</li>
</ul>
<h4><strong>C. System 2 Attention (S2A) / Preventative Context</strong></h4>
<ul>
<li><strong>Source:</strong> <em>NeurIPS 2024/2025 papers</em></li>
<li><strong>Description:</strong> A technique to reduce bias and &quot;sycophancy&quot; (the model agreeing with the user/text). It involves asking the model to re-generate the input prompt to remove irrelevant or biasing context <em>before</em> answering.</li>
<li><strong>Relevance to Critique:</strong> LLMs often hesitate to harshly critique a &quot;famous&quot; paper or one presented with authority. S2A strips this bias.</li>
<li><strong>Implementation:</strong><blockquote>
<p>&quot;Read the paper below. First, rewrite the paper&#39;s core claims into a neutral, anonymous summary that removes all author names, institutional prestige, and rhetorical flourishing. Then, critique <em>only</em> that neutral summary.&quot;</p>
</blockquote>
</li>
</ul>
<hr>
<h3>2. Techniques for Critique &amp; Evaluation Tasks</h3>
<h4><strong>Inoculation Prompting (Preventative)</strong></h4>
<p>While originally a fine-tuning technique, &quot;inoculation&quot; has been adapted for prompting in 2025 to defend against generic/safe outputs.</p>
<ul>
<li><strong>Concept:</strong> Explicitly &quot;inoculate&quot; the context against specific failure modes by listing them as forbidden patterns.</li>
<li><strong>Application:</strong><blockquote>
<p>&quot;You are an adversarial reviewer. I have inoculated you against the following &#39;lazy critique&#39; patterns:</p>
<ul>
<li>Complaining about sample size without calculating power.</li>
<li>Vague calls for &#39;more longitudinal studies.&#39;</li>
<li>Criticizing formatting or style.
<em>If you use these patterns, the critique fails.</em>&quot;</li>
</ul>
</blockquote>
</li>
</ul>
<h4><strong>Atomic Decomposition (Taxonomy-Based)</strong></h4>
<p>Recent work on logical fallacy detection suggests that LLMs fail when asked to &quot;find fallacies&quot; generally but succeed when the task is broken into atomic binary questions.</p>
<ul>
<li><strong>Application:</strong> Instead of asking &quot;Critique the logic,&quot; use a stepwise taxonomy:<ol>
<li>&quot;Does the conclusion rely on a correlation?&quot; (Y/N)</li>
<li>&quot;If Yes, did the authors control for confounders X, Y, and Z?&quot; (Y/N)</li>
<li>&quot;If No, flag this as a Critical Causal Fallacy.&quot;</li>
</ol>
</li>
</ul>
<hr>
<h3>3. Changes for 2025/2026 Models (System 2 / Reasoning Models)</h3>
<p>If you are using reasoning-heavy models (e.g., OpenAI <strong>o1/o3</strong>, <strong>DeepSeek R1</strong>), specific prompting habits must change:</p>
<ul>
<li><strong>STOP using &quot;Chain-of-Thought&quot; (CoT):</strong> Asking these models to &quot;think step-by-step&quot; is now redundant and often degrades performance because the model <em>already</em> does this internally. Adding it artificially can confuse its internal reinforcement learning policies.</li>
<li><strong>Focus on Constraints, Not Process:</strong> Instead of telling the model <em>how</em> to think (which it now does autonomously), focus on <strong>Context Engineering</strong>—defining the <em>boundaries</em> of the output.<ul>
<li><em>Old:</em> &quot;Think step by step and analyze the data...&quot;</li>
<li><em>New (2025):</em> &quot;The goal is a brutal methodological takedown. Constraints: No politeness markers. No summary of the paper. Focus only on statistical errors.&quot;</li>
</ul>
</li>
<li><strong>Context Engineering:</strong> For these models, providing the &quot;holistic state&quot; is critical. They perform better when you dump raw data/tables and ask them to derive the structure, rather than feeding them pre-chewed summaries.</li>
</ul>
<hr>
<h3>4. Novel Structural Approaches</h3>
<h4><strong>The &quot;Meta-Prompting&quot; Architect Loop</strong></h4>
<p>Use a multi-persona approach where the &quot;prompter&quot; is distinct from the &quot;writer.&quot;</p>
<ol>
<li><strong>Persona A (Architect):</strong> &quot;Analyze this paper and design a specific &#39;Attack Plan&#39; consisting of 4 specific angles of inquiry that would most damage its credibility.&quot;</li>
<li><strong>Persona B (Executor):</strong> &quot;Execute this Attack Plan. For each angle, write a specific critique paragraph.&quot;</li>
<li><strong>Persona C (Judge):</strong> &quot;Review the critique. Delete any point that could be copy-pasted to a different paper. Keep only paper-specific points.&quot;</li>
</ol>
<h4><strong>Reverse-Inference Verification</strong></h4>
<p>A strong verification step for critiques:</p>
<ul>
<li>&quot;After generating your critique, pretend to be the author of the paper. Attempt to dismantle your own critique using evidence from the text. If the critique can be easily dismantled, discard it and generate a new one.&quot;</li>
</ul>
<h3>Summary Checklist for Your Prompts</h3>
<table>
<thead>
<tr>
<th align="left">Technique</th>
<th align="left">Action Item</th>
</tr>
</thead>
<tbody><tr>
<td align="left"><strong>Decision Pivots</strong></td>
<td align="left">Ask the model to isolate the 3 facts the paper <em>dies</em> without.</td>
</tr>
<tr>
<td align="left"><strong>RSIP</strong></td>
<td align="left">Force a &quot;Critique -&gt; Weakness ID -&gt; Rewrite&quot; loop in one output.</td>
</tr>
<tr>
<td align="left"><strong>Inoculation</strong></td>
<td align="left">List specific &quot;lazy critique&quot; patterns and ban them explicitly.</td>
</tr>
<tr>
<td align="left"><strong>Model Specificity</strong></td>
<td align="left">If using o1/R1, remove &quot;think step-by-step&quot; and focus on constraints.</td>
</tr>
</tbody></table>
<p><em>Latency: 68.1s | Tokens: 2903</em></p>
<h1>grok-4.1</h1>
<hr>
<p><strong>1. Exemplar Selection KNN (ES-KNN)</strong>  </p>
<ul>
<li><strong>Description</strong>: Selects k-nearest neighbor exemplars based on semantic similarity to provide structured in-context examples.<a href="https://arxiv.org/pdf/2506.05614.pdf">[1]</a>  </li>
<li><strong>Source</strong>: arXiv:2506.05614 (June 2025), &quot;Which Prompting Technique Should I Use? An Empirical Investigation...&quot;  </li>
<li><strong>Key insight</strong>: Consistently top performer across reasoning-heavy software engineering tasks (e.g., code translation, defect detection); emphasizes structured guidance and lexical diversity to boost specificity and reasoning over generic outputs. Shorter, precise prompts with diverse vocabulary correlate with better performance (r=0.444 for diversity).  </li>
<li><strong>Relevance to critique generation</strong>: Ideal for paper critiques by selecting paper-specific examples/flaws, enabling precise semantic analysis and flaw detection akin to code review.<a href="https://arxiv.org/pdf/2506.05614.pdf">[1]</a></li>
</ul>
<p><strong>2. Universal Self-Consistency (USC)</strong>  </p>
<ul>
<li><strong>Description</strong>: Generates multiple reasoning paths and selects the most consistent answer via majority vote.<a href="https://arxiv.org/pdf/2506.05614.pdf">[1]</a><a href="https://arxiv.org/abs/2505.01482">[2]</a>  </li>
<li><strong>Source</strong>: arXiv:2506.05614 (June 2025) &amp; arXiv:2505.01482 (July 2025), scientific reasoning on GPQA.  </li>
<li><strong>Key insight</strong>: Highest accuracy (52.99%) on scientific reasoning benchmarks; outperforms complex methods like multipath/decomposition. With 2025 models, consistency voting enhances reliability without added complexity.  </li>
<li><strong>Relevance to critique generation</strong>: Reduces hallucinations/generic outputs in evaluations by cross-verifying critiques across paths, ensuring substantive, paper-specific analysis.<a href="https://arxiv.org/abs/2505.01482">[2]</a></li>
</ul>
<p><strong>3. Thread of Thought (ToT)</strong>  </p>
<ul>
<li><strong>Description</strong>: Decomposes tasks into threaded sub-components for step-by-step examination.<a href="https://arxiv.org/pdf/2506.05614.pdf">[1]</a>  </li>
<li><strong>Source</strong>: arXiv:2506.05614 (June 2025).  </li>
<li><strong>Key insight</strong>: Excels in defect detection/critique-like tasks by breaking down content logically; structured decomposition outperforms unstructured prompting on modern LLMs.  </li>
<li><strong>Relevance to critique generation</strong>: Mirrors attack taxonomies (e.g., causal reversal) by threading critiques through paper sections, promoting specificity over generic summaries.<a href="https://arxiv.org/pdf/2506.05614.pdf">[1]</a></li>
</ul>
<p><strong>4. Recursive Language Models (RLMs)</strong>  </p>
<ul>
<li><strong>Description</strong>: Treats prompts as external environment; LLM writes code to decompose, inspect, and recursively invoke itself on snippets for ultra-long contexts.<a href="https://arxiv.org/abs/2512.24601">[3]</a>  </li>
<li><strong>Source</strong>: arXiv:2512.24601 (Dec 2025), MIT researchers.  </li>
<li><strong>Key insight</strong>: Handles 100x longer inputs than context windows at comparable cost; outperforms baselines on long-context reasoning, mitigating &quot;context rot.&quot; Novel structural shift for 2025+ models.  </li>
<li><strong>Relevance to critique generation</strong>: Enables deep, recursive analysis of full academic papers (e.g., self-critique loops on sections), ideal for substantive, non-generic critiques.<a href="https://arxiv.org/abs/2512.24601">[3]</a></li>
</ul>
<p><strong>5. ProRefine (Inference-Time Prompt Refinement)</strong>  </p>
<ul>
<li><strong>Description</strong>: Dynamically refines prompts at inference using textual feedback loops, task-agnostic, no training needed.<a href="https://arxiv.org/abs/2506.05305">[4]</a>  </li>
<li><strong>Source</strong>: arXiv:2506.05305 (June 2025), NeurIPS 2025 Workshop on Efficient Reasoning.  </li>
<li><strong>Key insight</strong>: Improves multi-step reasoning/agentic workflows via iterative refinement; counters prompt sensitivity in 2025 frontier models.  </li>
<li><strong>Relevance to critique generation</strong>: Self-refines for paper-specific critiques (e.g., incorporating &quot;anti-slop&quot; feedback), boosting specificity and reasoning depth.<a href="https://neurips.cc/media/neurips-2025/Slides/126759.pdf">[5]</a></li>
</ul>
<p><strong>6. Simplified/Direct Prompting (vs. Complex CoT)</strong>  </p>
<ul>
<li><strong>Description</strong>: Rephrase instructions simply, add background knowledge; avoid step-by-step for high-perf models.<a href="https://arxiv.org/abs/2507.13525">[6]</a><a href="https://arxiv.org/abs/2505.01482">[2]</a>  </li>
<li><strong>Source</strong>: arXiv:2507.13525 (July 2025) &amp; arXiv:2505.01482 (July 2025).  </li>
<li><strong>Key insight</strong>: Complex CoT reduces accuracy/cost-efficiency on 2025 models; simple prompts best for reasoning-heavy tasks like recsys/scientific QA (direct answer: 52.23% accuracy). Change: Baked-in reasoning makes older CoT counterproductive.  </li>
<li><strong>Relevance to critique generation</strong>: Promotes concise, specific outputs; pairs with personas/taxonomies for targeted paper attacks without verbosity.<a href="https://arxiv.org/abs/2507.13525">[6]</a></li>
</ul>
<p><strong>7. Context Engineering &amp; Slop Avoidance</strong>  </p>
<ul>
<li><strong>Description</strong>: Curate high-quality context, adjust reasoning &quot;dials,&quot; avoid ethical/system prompts triggering unwanted behaviors.<a href="https://simonwillison.net/2025/Dec/31/the-year-in-llms">[7]</a>  </li>
<li><strong>Source</strong>: Simon Willison&#39;s &quot;2025: The Year in LLMs&quot; (Dec 2025).  </li>
<li><strong>Key insight</strong>: 2025 shift to agentic/reasoning models (o3 series) favors optimized context over raw prompting; reduces &quot;slop&quot; via precise instructions.  </li>
<li><strong>Relevance to critique generation</strong>: Directly addresses &quot;anti-slop&quot; constraints; ensures paper-specific, non-copy-pasteable critiques via curated examples/feedback.<a href="https://simonwillison.net/2025/Dec/31/the-year-in-llms">[7]</a></li>
</ul>
<p><strong>8. Self-Refine &amp; Self-Generated ICL</strong>  </p>
<ul>
<li><strong>Description</strong>: Iterative self-critique/refinement; LLM generates its own in-context examples.<a href="https://arxiv.org/pdf/2506.05614.pdf">[1]</a>  </li>
<li><strong>Source</strong>: arXiv:2506.05614 (June 2025).  </li>
<li><strong>Key insight</strong>: Strong on bug fixing/summarization; enables loops without external data, validated on 2025 LLMs.  </li>
<li><strong>Relevance to critique generation</strong>: Builds self-critique loops/taxonomies; generates paper-tailored examples for substantive evaluations.<a href="https://arxiv.org/pdf/2506.05614.pdf">[1]</a></li>
</ul>
<p><strong>Overarching Changes (2025-2026 Models):</strong> CoT/decomposition often underperforms simples/self-consistency; inference-time opt/recursion rises for long/complex tasks; specificity via exemplars/diversity/feedback prioritized over verbosity.<a href="https://arxiv.org/abs/2507.13525">[6]</a><a href="https://arxiv.org/abs/2505.01482">[2]</a></p>
<p><em>Latency: 79.0s | Tokens: 59662</em></p>
<h1>openai-deep-research</h1>
<hr>
<h2>Prompting Techniques (2025–2026) for Complex Reasoning &amp; Critique Generation</h2>
<p>Recent literature (2025–2026) highlights several advanced prompt engineering techniques that improve an LLM’s reasoning quality and specificity while avoiding generic “slop.” Below, each technique is described with its source (year), key insight, and relevance to generating thorough critiques of academic papers.</p>
<h3>Context Engineering / Retrieval-Augmentation (2025)</h3>
<p><strong>Source (2025):</strong> Daniel Lozovsky’s <strong>“Complete Guide to Context Engineering”</strong> (June 2025) (<a href="https://medium.com/%40daniel.lozovsky/the-complete-guide-to-context-engineering-why-its-replacing-prompt-engineering-in-2025-780d6aa33976#:~:text=match%20at%20L27%20Context%20engineering,It%E2%80%99s%20all%20about%20context%20now">medium.com</a>); Anthropic Claude context window update (Aug 2025) (<a href="https://www.tomsguide.com/ai/anthropic-looks-to-beat-gpt-5-and-grok-4-with-this-one-major-upgrade#:~:text=The%20company%27s%20latest%20trick%20is,roughly%20five%20times%20higher%20and">www.tomsguide.com</a>).<br><strong>Key insight:</strong> Instead of obsessing over phrasing, this approach focuses on <em>what information</em> you provide to the model. Context engineering means supplying rich, relevant background data or documents to ground the model’s response. It has overtaken classic prompt tweaking as the crucial factor for high-quality outputs (<a href="https://medium.com/%40daniel.lozovsky/the-complete-guide-to-context-engineering-why-its-replacing-prompt-engineering-in-2025-780d6aa33976#:~:text=match%20at%20L27%20Context%20engineering,It%E2%80%99s%20all%20about%20context%20now">medium.com</a>). For instance, Anthropic’s Claude was upgraded to handle up to <strong>1 million tokens</strong> of context (about 750k words) (<a href="https://www.tomsguide.com/ai/anthropic-looks-to-beat-gpt-5-and-grok-4-with-this-one-major-upgrade#:~:text=The%20company%27s%20latest%20trick%20is,roughly%20five%20times%20higher%20and">www.tomsguide.com</a>), allowing entire papers or books to be given as input. This ensures the model isn’t guessing or relying on generic training data – it has the exact material to work with.<br><strong>Relevance to critique generation:</strong> Providing the <strong>full text or key excerpts of the target paper</strong> as context enables the LLM to generate <em>paper-specific</em> critiques. The model can reference specific sections, data, or claims, leading to critiques that are grounded in the actual content rather than high-level boilerplate. This directly combats generic “slop” because the AI must engage with the details of the paper. In short, <strong>feeding more relevant context</strong> produces more pointed and accurate evaluations.</p>
<h3>Constrained Prompting (“Anti-Slop” Constraints) (2025)</h3>
<p><strong>Source (2025):</strong> <em>Skim AI</em> blog – “10 Best Prompting Techniques for LLMs in 2025” (Feb 2025) (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=6">skimai.com</a>).<br><strong>Key insight:</strong> Constrained prompting means explicitly instructing the model about what <strong>not</strong> to do or setting rules for the format/content of its answer (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=6">skimai.com</a>). For example, you might ban filler phrases, require the answer to stay within certain limits, or demand factuality. By adding such guardrails in the prompt, the model’s output becomes more focused and less likely to degenerate into generic or irrelevant text. Essentially, it reduces “slop” by <strong>forbidding vagueness and forcing specificity</strong>.<br><strong>Relevance to critique generation:</strong> When prompting for a critique, you can include instructions like <em>“Do not give generic praise or summary; focus on unique aspects of this paper,”</em> or <em>“Avoid common review platitudes.”</em> These constraints push the LLM to provide substantive, <strong>paper-specific criticisms</strong>. The result is a more rigorous critique: instead of “The paper is well-written and has interesting results” (which could apply to any paper), a constrained prompt yields concrete critiques pinpointing that paper’s methods or assumptions. This technique helps ensure <em>originality</em> and <em>relevance</em> in the feedback, aligning with the “anti-slop” goal of banning generic outputs.</p>
<h3>Role/Persona Prompting (2025)</h3>
<p><strong>Source (2025):</strong> <em>Skim AI</em> blog – “10 Best Prompting Techniques for LLMs in 2025” (Feb 2025) (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=Role%20prompting%20is%20a%20creative,method%20can%20dramatically%20alter%20the">skimai.com</a>).<br><strong>Key insight:</strong> Role prompting means asking the LLM to <em>adopt a specific persona or point of view</em> when responding (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=Role%20prompting%20is%20a%20creative,method%20can%20dramatically%20alter%20the">skimai.com</a>). For example, “You are an expert journal reviewer in machine learning...” or “Act as a skeptical peer reviewer.” This technique can dramatically change the tone and depth of the output. By imbuing the model with a persona, you steer its knowledge and style – it will draw on the relevant domain knowledge and critical attitude of that role (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=Role%20prompting%20is%20a%20creative,method%20can%20dramatically%20alter%20the">skimai.com</a>).<br><strong>Relevance to critique generation:</strong> Assigning the model the role of a <strong>subject-matter expert or critical reviewer</strong> tends to produce a more incisive critique. The model-as-“reviewer” will more naturally identify flaws and weaknesses, use field-specific terminology, and avoid overly polite or generic language. For instance, prompting <em>“As a seasoned statistics professor, critique the experimental design of this paper”</em> will likely yield a critique focusing on sample size, controls, and analysis rigor. Role prompts thus ensure the <strong>critique is context-appropriate and detailed</strong> – the LLM channels the mindset of a real expert evaluator, leading to higher-quality feedback on the paper.</p>
<h3>Chain-of-Thought Prompting (Step-by-Step Reasoning) (2025)</h3>
<p><strong>Source (2025):</strong> Maxim.ai – “Advanced Prompt Engineering Techniques in 2025” (Oct 2025) (<a href="https://www.getmaxim.ai/articles/advanced-prompt-engineering-techniques-in-2025/#:~:text=When%20applied%20to%20PaLM%2C%20a,tuned%20models%20on%20several%20tasks">www.getmaxim.ai</a>).<br><strong>Key insight:</strong> Chain-of-Thought (CoT) prompting remains a foundational technique for complex reasoning. It involves instructing the model to <strong>reason through the problem step by step</strong>, rather than jumping straight to the final answer. Research in recent years showed that even very large models benefit from this approach: getting the model to articulate intermediate reasoning steps can significantly improve accuracy on tasks like math and logic (<a href="https://www.getmaxim.ai/articles/advanced-prompt-engineering-techniques-in-2025/#:~:text=When%20applied%20to%20PaLM%2C%20a,tuned%20models%20on%20several%20tasks">www.getmaxim.ai</a>). For example, PaLM (540B) was able to match fine-tuned models on certain tasks just by using chain-of-thought prompts (<a href="https://www.getmaxim.ai/articles/advanced-prompt-engineering-techniques-in-2025/#:~:text=When%20applied%20to%20PaLM%2C%20a,tuned%20models%20on%20several%20tasks">www.getmaxim.ai</a>). CoT works because it forces the LLM to make its implicit reasoning explicit, reducing errors from skipped logic.<br><strong>Relevance to critique generation:</strong> When critiquing a paper, a <strong>structured step-by-step approach</strong> ensures no aspect is overlooked. A prompt might say: <em>“First, list the key claims and methods of the paper. Then, for each, analyze potential weaknesses or counterarguments, step by step, before giving a final evaluative summary.”</em> This makes the LLM <strong>lay out its reasoning</strong> (e.g., identify an assumption, examine if evidence supports it, consider counterexamples, then conclude). Such an approach yields a critique with clear logic: the model doesn’t just state “The methodology has issues” – it demonstrates the reasoning by walking through those issues. Overall, CoT prompting helps generate comprehensive, logically sound critiques by breaking the evaluation into smaller reasoning steps.</p>
<h3><strong>Chain-of-Conceptual-Thought (CoCT)</strong> (2025)</h3>
<p><strong>Source (2025):</strong> Gu <em>et al.</em>, <em>Chain-of-Conceptual-Thought</em> (arXiv Oct 2025) (<a href="https://arxiv.org/abs/2510.18434#:~:text=Chain,We%20experiment%20with">arxiv.org</a>).<br><strong>Key insight:</strong> CoCT is a new prompting paradigm proposed to handle <strong>open-ended or abstract tasks</strong> where a standard step-by-step chain-of-thought might falter. In Chain-of-Conceptual-Thought, the model is prompted to first <strong>generate a high-level concept or tag</strong> for its response, then elaborate on that concept (<a href="https://arxiv.org/abs/2510.18434#:~:text=Chain,We%20experiment%20with">arxiv.org</a>). Essentially, it plans <em>conceptually</em> before diving into details. Gu et al. note that classic CoT struggles when there’s no single “right” reasoning path (e.g. in free-form advice or open-domain dialogue) (<a href="https://arxiv.org/abs/2510.18434#:~:text=Chain,first%20tags%20a%20concept%2C%20then">arxiv.org</a>). CoCT mitigates this by letting the model outline key concepts or angles first (like emotions, strategies, topics in a conversation) (<a href="https://arxiv.org/abs/2510.18434#:~:text=To%20mitigate%20such%20challenges%2C%20we,Automatic%2C%20human%20and%20model">arxiv.org</a>). This encourages deeper, more <strong>strategic thinking</strong> because the model frames the answer around a thoughtful concept rather than generating ad-hoc.<br><strong>Relevance to critique generation:</strong> Academic critiques are often open-ended – there isn’t a formulaic solution, but there are <em>conceptual angles</em> from which to examine the work (e.g. theoretical soundness, methodology, impact, etc.). Using a CoCT-inspired prompt, you might ask: <em>“Identify the main conceptual angle for critique (e.g., a specific assumption, methodology aspect, or theoretical framework) and label it, then provide a detailed critique under that concept.”</em> For instance, the model might respond with a concept tag like “<strong>Causal Inference Validity:</strong>” and then expand into a critique of the paper’s causal claims. By having the LLM <strong>name the critique concept first</strong>, you ensure it picks a focused, high-level issue and then drills down. This leads to critiques that are not just a scattered list of points, but one or more <strong>conceptually organized critiques</strong> that demonstrate deeper understanding of the paper’s content.</p>
<h3>Multi-Path Reasoning (Self-Consistency &amp; Tree-of-Thought) (2025)</h3>
<p><strong>Source (2025):</strong> Maxim.ai – “Advanced Prompt Engineering Techniques in 2025” (Oct 2025) (<a href="https://www.getmaxim.ai/articles/advanced-prompt-engineering-techniques-in-2025/#:~:text=Self,to%20determine%20the%20final%20answer">www.getmaxim.ai</a>).<br><strong>Key insight:</strong> Instead of relying on a single chain of reasoning, multi-path techniques have the model explore <strong>multiple lines of thought in parallel</strong>. Two notable approaches are <strong>Self-Consistency</strong> and <strong>Tree-of-Thought (ToT)</strong> prompting. <em>Self-consistency</em> means running the prompt multiple times to produce several reasoning paths/answers, then <strong>choosing the most consistent outcome</strong> (e.g., via majority vote) (<a href="https://www.getmaxim.ai/articles/advanced-prompt-engineering-techniques-in-2025/#:~:text=Self,to%20determine%20the%20final%20answer">www.getmaxim.ai</a>). This reduces random errors or variability in any one chain – the correct answer or critique point is likely to appear across multiple independent attempts. <em>Tree-of-Thought</em>, on the other hand, explicitly instructs the model to branch out: the model generates a <strong>tree of reasoning</strong>, exploring different possibilities or hypotheses, and can backtrack if a branch seems unpromising (<a href="https://www.getmaxim.ai/articles/advanced-prompt-engineering-techniques-in-2025/#:~:text=Self,to%20determine%20the%20final%20answer">www.getmaxim.ai</a>). This is like systematically searching the solution space (using strategies like breadth-first or depth-first search through the reasoning tree). Both methods acknowledge that complex problems might have several plausible approaches – by examining many, the model is more likely to hit on a thorough and correct analysis.<br><strong>Relevance to critique generation:</strong> For evaluating something as complex as a research paper, multi-path prompting yields a more <strong>robust and comprehensive critique</strong>. Self-consistency can be applied by asking the model to generate, say, <em>three separate critiques or lists of concerns</em>, and then aggregating the points that appear frequently. This way, you filter out any one-off odd opinions and highlight the issues that consistently emerge – a bit like getting a second and third opinion from the same model and seeing where they agree. Tree-of-Thought prompting can be applied by having the model systematically consider different angles: <em>“Consider the paper’s methods, results, and assumptions. For each, branch into potential criticisms (e.g., for methods: sample size issues, biases, uncertainties, etc.), then evaluate which branches are most significant.”</em> The model might produce a structured, hierarchical critique, exploring each major aspect of the paper. This ensures <strong>no major area is missed</strong>. In practice, multi-path reasoning leads to critiques that cover multiple facets of the paper and converge on the most significant problems, rather than hinging on the model’s first idea. It’s a way to simulate diverse reviewers or multiple read-throughs using one model.</p>
<h3>Buffer-of-Thought (Reusable Reasoning Templates) (2024→2025)</h3>
<p><strong>Source (2024/25):</strong> Yang <em>et al.</em>, <em>“Buffer of Thoughts: Thought-Augmented Reasoning”</em> (NeurIPS 2024, spotlight) as cited in Philipp Gabriel’s 2025 overview (<a href="https://medium.com/%40philipp-gabriel/bolt-on-prompting-tricks-or-the-future-of-llm-development-9b02fd62607d#:~:text=3">medium.com</a>).<br><strong>Key insight:</strong> Buffer-of-Thought (BoT) is a newer technique that helps models <strong>“think” more reliably by reusing proven reasoning patterns</strong>. The idea is to maintain a <em>buffer</em> or library of reasoning templates distilled from previous problems, which the model can draw from (<a href="https://medium.com/%40philipp-gabriel/bolt-on-prompting-tricks-or-the-future-of-llm-development-9b02fd62607d#:~:text=3">medium.com</a>). Instead of solving every new question from scratch (which can lead to inconsistency), the model recalls similar problem-solving steps from its buffer and adapts them to the new context (<a href="https://medium.com/%40philipp-gabriel/bolt-on-prompting-tricks-or-the-future-of-llm-development-9b02fd62607d#:~:text=3">medium.com</a>). This is akin to how an experienced researcher recalls relevant examples or frameworks when approaching a new problem. BoT can improve reliability (fewer leaps of logic) and cut down on token usage, since the model doesn’t have to unroll a massive chain-of-thought every time – it can jump to a known useful reasoning pattern.<br><strong>Relevance to critique generation:</strong> For generating critiques, especially in a specialized domain, a buffer-of-thought approach could mean the model <strong>reuses critique frameworks</strong> that have worked well on similar papers. For example, if an LLM has “seen” many examples of critiquing a medical clinical trial, it might have a template: check for sample size, check for control group, check for statistical significance. By prompting it to use such a template (or by it having a buffered memory of successful critiques), the model can systematically apply a <strong>proven critique structure</strong> to the new paper. This yields a thorough analysis without forgetting key standard questions. Essentially, BoT brings consistency and completeness: the LLM is less likely to produce a totally off-the-cuff (and possibly off-target) critique, and more likely to cover all the important bases because it’s following a reliable pattern. In 2025, this idea is still emerging from research, but it suggests that future prompt techniques might include warming the LLM up with some <strong>schema or checklist</strong> (possibly retrieved from past critiques) before it critiques the new paper.</p>
<h3>Iterative Refinement (Multi-Turn Prompting) (2025)</h3>
<p><strong>Source (2025):</strong> <em>Skim AI</em> – “10 Best Prompting Techniques for LLMs in 2025” (Feb 2025) (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=7">skimai.com</a>).<br><strong>Key insight:</strong> Iterative refinement uses <strong>multiple turns or prompts in sequence to gradually improve the output</strong> (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=7">skimai.com</a>). Rather than expecting a perfect response in one go, the approach is to have the model produce an answer, then analyze or critique that answer, then improve it, possibly repeating this cycle. Each iteration allows the model to fix errors or add details that were missing. This approach acknowledges that the first draft from an LLM might be only “okay,” and treats the LLM as both writer and editor via different prompts.<br><strong>Relevance to critique generation:</strong> This technique can be invaluable for critiques. For example, you might prompt: <em>“Give an initial critique of the paper.”</em> Once the model responds, you then prompt: <em>“Now, refine this critique. Identify any overlooked weaknesses or nuances, correct any inaccuracies, and make the critique more specific to the paper.”</em> The first pass might surface the obvious points, and the second pass (or even a third) can introduce more insight or precision. Another variant is a <strong>two-model (or two-role) approach</strong>: one instance of the model generates a critique, and another instance (or the same model with a different prompt) is asked to <strong>play devil’s advocate or proofreader</strong> on that critique – pointing out any shallow statements or errors – after which the critique is revised. This multi-turn process often yields a far superior final critique. It mimics the human process of writing a draft review, then rereading and refining it. The result is a critique that has effectively been self-vetted by the model, tending to be more accurate and less generic. (Notably, even outside of academic critique, researchers have found that prompting ChatGPT with an <strong>additional critical pass increases accuracy</strong> – for instance, one 2025 study found that when users implicitly “were mean” or challenging to ChatGPT, its answers became <strong>more accurate</strong> on factual questions (<a href="https://www.livescience.com/technology/artificial-intelligence/being-mean-to-chatgpt-increases-its-accuracy-but-you-may-end-up-regretting-it-scientists-warn#:~:text=2025,choice%20questions%20in%20five%20different">www.livescience.com</a>), which underscores that pushing the model to reflect and refine can enhance quality.)</p>
<h3>Adversarial &amp; Debate Prompting (2025)</h3>
<p><strong>Source (2025):</strong> <em>Skim AI</em> – “10 Best Prompting Techniques for LLMs in 2025” (Feb 2025), on <strong>Adversarial Prompting</strong> (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=10">skimai.com</a>); Asad et al., <em>“RedDebate: Multi-Agent Red Teaming Debates”</em> (June 2025) (<a href="https://arxiv.org/abs/2506.11083#:~:text=effectiveness,to%20progressively%20enhance%20AI%20safety">arxiv.org</a>).<br><strong>Key insight:</strong> Adversarial prompting involves <strong>intentionally challenging the model’s responses or assumptions</strong> to force it into a more critical, accurate mode (<a href="https://skimai.com/10-best-prompting-techniques-for-llms-in-2025/#:~:text=10">skimai.com</a>). Rather than accepting the first answer, the prompt (or a subsequent prompt) might say, “That seems incorrect – reconsider your answer,” or pose a counter-point for the AI to rebut. This pushes the model to address potential flaws or alternative interpretations. An extension of this idea is <em>debate prompting</em>, where <strong>multiple AI agents or multiple roles within one prompt debate each other</strong>. In research settings, pitting two language models against each other (one taking a pro stance, one con) and then evaluating the outcome has been shown to reduce errors and harmful content – effectively, the models catch each other’s mistakes (<a href="https://arxiv.org/abs/2506.11083#:~:text=effectiveness,to%20progressively%20enhance%20AI%20safety">arxiv.org</a>). For instance, a 2025 framework called <em>RedDebate</em> had AI “red teamers” argue with an AI “defender,” resulting in more reliable and safer answers (<a href="https://arxiv.org/abs/2506.11083#:~:text=effectiveness,to%20progressively%20enhance%20AI%20safety">arxiv.org</a>). The key insight is that conflict and debate, when controlled, lead to deeper reasoning: the model must defend a position or see a problem from opposite sides.<br><strong>Relevance to critique generation:</strong> Critiquing a paper is inherently an adversarial act – you’re probing for weaknesses – so this technique is very apt. One practical approach is to simulate a <strong>debate about the paper’s merits</strong>. For example: <em>“AssistantA, argue in favor of the paper’s approach; AssistantB, criticize it. Then AssistantA rebut the criticisms.”</em> By prompting a back-and-forth, the model(s) will surface points and counterpoints, much like two experts hashing out the paper’s quality. This can reveal subtler issues (because one “agent” might notice something the other missed). Even without two separate agents, a single model can be prompted to do this internally: <em>“List a strong claim the paper makes. Now, argue against this claim as if you doubt its validity. Now, counter that argument.”</em> This <strong>dialectical method</strong> drives the model to examine the paper from multiple angles, often yielding a richer critique. Moreover, providing an explicit “opponent” (even if imaginary) helps the LLM avoid being too easily satisfied with surface-level observations. By <strong>red-teaming its own analysis</strong>, the model will produce a critique that includes potential rebuttals and thus is more balanced and thorough. In essence, adversarial prompting ensures the critique isn’t one-dimensional – it’s tested against counter-arguments, much like in an actual scholarly debate.</p>
<h3>Self-Critique and Verification (2025)</h3>
<p><strong>Source (2025):</strong> Chowdhury &amp; Caragea, <em>“Zero-Shot Verification-Guided CoT”</em> (Jan 2025) (<a href="https://arxiv.org/abs/2501.13122#:~:text=Previous%20works%20have%20demonstrated%20the,the%20correctness%20of%20reasoning%20chains">arxiv.org</a>).<br><strong>Key insight:</strong> This technique has the model <strong>inspect and critique its own reasoning or answers</strong>, guided by additional prompts or “verifier” modules. Recent research proposes letting the LLM generate its chain-of-thought, and then <em>stop and verify each step</em> with either another instance of the model or a special prompt, all in a zero-shot manner (<a href="https://arxiv.org/abs/2501.13122#:~:text=Previous%20works%20have%20demonstrated%20the,the%20correctness%20of%20reasoning%20chains">arxiv.org</a>). Essentially, the model asks itself: <em>“Is this reasoning step correct? If not, backtrack.”</em> By incorporating a self-verification stage, the LLM can catch logical errors or unsupported claims in its output before presenting a final answer. This is related to approaches like Anthropic’s “Constitutional AI” (where the AI has rules to reflect on outputs and avoid bad behavior) – the model uses an <strong>internal audit</strong> process to improve quality.<br><strong>Relevance to critique generation:</strong> When producing a critique, an LLM might initially make a mistake about the paper (e.g. misinterpret a result or overlook a section). Self-critique prompting tries to minimize that. For example, you can prompt: <em>“Critique the paper in detail. Then, <strong>check each of your critique points</strong>: are they factually correct and fair based on the paper? If any point seems incorrect or too generic, revise it.”</em> The model will first output a critique, and then (following the instructions) go through its own text to verify each point against the paper’s content or known facts, and fix any issues. This results in a more <strong>accurate and trustworthy critique</strong>. It’s like asking the AI to play both the role of the reviewer <em>and</em> the meta-reviewer who checks the review. Additionally, you can explicitly ask the model to list potential errors or biases in its critique (self-reflection) or to rate the critique against given criteria (like specificity, correctness). By closing the feedback loop in the prompt itself, the model often produces a second draft that is sharper. Another powerful use of this idea is to provide the model with a checklist of common critique dimensions (e.g., <em>“Check: Did I evaluate the methodology? Did I consider alternative explanations? Did I avoid unsupported claims?”</em>). Prompting it to verify along these lines ensures that <strong>all key aspects are covered</strong> and that any over-generalizations are caught and removed. In summary, self-verification turns the model into its own critic, leading to critiques that are not only insightful but also double-checked for quality – a crucial need for high-stakes academic feedback.</p>
<h3>Adaptive Prompting for Advanced Models (“Prompting Inversion”) (2025–2026)</h3>
<p><strong>Source (2025):</strong> Imran Khan, <em>“You Don’t Need Prompt Engineering Anymore: The Prompting Inversion”</em> (Oct 2025) (<a href="https://arxiv.org/abs/2510.22251#:~:text=OpenAI%20model%20generations%20%28gpt,tier%20models%20induce%20hyper">arxiv.org</a>) (<a href="https://arxiv.org/abs/2510.22251#:~:text=full%20benchmark%29,evolve%20with%20model%20capabilities%2C%20suggesting">arxiv.org</a>); Neha Ummareddy, <em>“Top 5 Prompt Engineering Techniques for LLMs in 2025”</em> (Oct 2025) (<a href="https://dev.to/neha/top-5-prompt-engineering-techniques-for-llms-in-2025-1gb4#:~:text=which%20prompting%20is%20needed%20is,the%20results%20are%20outright%20unacceptable">dev.to</a>).<br><strong>Key insight:</strong> As LLMs become more capable (e.g., GPT-4 → GPT-5), the prompt strategies that worked for earlier models may <strong>no longer be optimal – or might even hinder performance</strong>. Khan (2025) demonstrated this with a technique called “<strong>Sculpting</strong>,” a rule-based, constrained prompting method meant to improve reasoning. Sculpting significantly boosted accuracy on a GPT-4-level model (<a href="https://arxiv.org/abs/2510.22251#:~:text=OpenAI%20model%20generations%20%28gpt,tier%20models%20induce%20hyper">arxiv.org</a>), but when the same technique was applied to a more advanced GPT-5 model, it <strong>backfired</strong> – the GPT-5 actually <strong>performed worse</strong> with the extra constraints than it did with a simpler prompt (<a href="https://arxiv.org/abs/2510.22251#:~:text=OpenAI%20model%20generations%20%28gpt,tier%20models%20induce%20hyper">arxiv.org</a>). This “prompting inversion” effect is explained as a <em>“Guardrail-to-Handcuff”</em> phenomenon (<a href="https://arxiv.org/abs/2510.22251#:~:text=full%20benchmark%29,evolve%20with%20model%20capabilities%2C%20suggesting">arxiv.org</a>): constraints act like helpful guardrails for a weaker model, but for a stronger model they became handcuffs, inducing overly literal, rigid behavior that degraded results (<a href="https://arxiv.org/abs/2510.22251#:~:text=full%20benchmark%29,evolve%20with%20model%20capabilities%2C%20suggesting">arxiv.org</a>). In short, the <strong>newer models already have stronger reasoning and instruction-following baked in</strong>, so heavy-handed prompts can unnecessarily limit them. Concurrently, some prompt engineers in 2025 observed that “prompt engineering” is <em>less needed</em> with top-tier models – the models can do more with minimal prompting – yet <strong>giving the right high-level guidance</strong> is still important (<a href="https://dev.to/neha/top-5-prompt-engineering-techniques-for-llms-in-2025-1gb4#:~:text=which%20prompting%20is%20needed%20is,the%20results%20are%20outright%20unacceptable">dev.to</a>) (they still need directions on “whats” and “hows,” just maybe not overly intricate ones).<br><strong>Relevance to critique generation:</strong> If you’re using the latest 2025/2026 models (which are far more advanced than those from 2023), you may need to <strong>simplify or adapt your prompting techniques</strong> accordingly. Older techniques like extremely long, structured prompts or forcing an exhaustive chain-of-thought might not yield the best results with, say, GPT-5 – the model might do better if you <strong>trust its internal reasoning</strong> more and just ask clearly for what you want. The research suggests finding the sweet spot: give enough structure to avoid sloppiness, but not so much that the model is straitjacketed. For example, a GPT-3.5 or GPT-4 might have needed a very explicit step-by-step instruction to produce a good critique, whereas a GPT-5-level model might produce an excellent critique with a simpler prompt like, <em>“Critically evaluate the attached paper, focusing on its methodology, validity of results, and significance. Be specific.”</em> – because GPT-5 has likely “learned” the skill of critical evaluation during training. Effective prompting in 2026 means <strong>reading the model</strong>: use more scaffolding and examples for mid-tier models, but consider more open-ended prompts (or at least fewer micromanaging rules) for the newest models to let them shine. In all cases, continue to provide clarity about the task and criteria (advanced models aren’t telepathic), but don’t assume the prompt formula that worked in 2023 is optimal now. The field is finding that prompt engineering is evolving into <strong>prompt strategy</strong> – knowing how much guidance a particular model needs and tailoring it. For critique tasks, this could mean the difference between a stiff, template-like critique (from over-engineering the prompt) and a more natural yet still rigorous critique that a top-tier model can generate when given the right freedom.</p>
<p><em>Latency: 11.3 min | Request ID: resp_0a5...</em></p>
<h1>gemini-deep-research</h1>
<hr>
<p><strong>Error:</strong> 400 {&quot;error&quot;:{&quot;message&quot;:&quot;There was a problem processing your request. You will not be charged.&quot;,&quot;code&quot;:&quot;invalid_request&quot;}}</p>
</div>

<?php include '../includes/footer.php'; ?>
