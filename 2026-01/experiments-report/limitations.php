<?php
$pageTitle = 'Limitations and open questions';
include 'includes/header.php';
?>

<h1>Limitations and open questions</h1>

<p>These experiments are exploratory. Here are the main limitations and questions that would need addressing before drawing strong conclusions.</p>

<h2 id="circular-validation">1. Circular validation problem</h2>

<p>The ACORN grader is used to evaluate prompts, but the grader itself hasn't been validated against Forethought's standards—it's borrowed from CMU's work on a different task. The claim that it "roughly tracks human judgement" is based on a few spot-checks, not systematic validation.</p>

<p>Without structured human validation (e.g., 50+ pairwise comparisons between ACORN rankings and researcher judgements), we don't know if we're measuring quality or just ACORN's idiosyncratic preferences.</p>

<h2 id="same-model-grades">2. Same model grades its own outputs</h2>

<p>GPT-5.2 Pro generates the critiques AND grades them via ACORN. The <a href="grader-accuracy.php">grader accuracy experiment</a> acknowledges this concern:</p>

<blockquote>
    "Since the grader is also GPT-5.2 Pro, it might systematically prefer GPT-style outputs"
</blockquote>

<p>But this concern is then set aside for the main experiments. The <a href="critique-prompt-experiment-appendix-4.php">cross-model comparison</a> where GPT-5.2 Pro "significantly outperforms" Claude and Gemini is particularly suspect—that result might reflect grader bias rather than genuine quality differences.</p>

<p>Running the grader with different models (Claude, Gemini) would help distinguish signal from bias.</p>

<h2 id="small-samples">3. Small samples with no significance testing</h2>

<p>Sample sizes are modest:</p>

<ul>
    <li><a href="critique-prompt-experiment.php">Critique prompt experiment</a>: 30 samples per prompt</li>
    <li><a href="crucial-questions-experiment.php">Crucial questions experiment</a>: 21 samples per prompt</li>
    <li><a href="chain-acorn.php">Chain experiment</a>: N=1</li>
</ul>

<p>The reports compare averages (e.g., 0.35 vs 0.29) without assessing statistical significance. Given the noted high variance (e.g., the "personas" prompt), these differences could be noise. The experiments are better understood as exploratory than confirmatory.</p>

<h2 id="chain-result">4. The chain experiment result is concerning</h2>

<p>The multi-step critique chain didn't clearly improve on single-step brainstorming:</p>

<blockquote>
    "The best final critique scored 0.58. For context...the best single-step brainstorm critiques also scored around 0.55–0.65. The chain doesn't seem to be producing dramatically better critiques."
</blockquote>

<p>This is either evidence that elaborate chains don't help much, or evidence that ACORN isn't measuring what matters for longer, more developed critiques. The current experiments don't resolve which interpretation is correct.</p>

<h2 id="crucial-questions-grader">5. The crucial questions grader is unvalidated</h2>

<p>Unlike ACORN (which has <em>some</em> external validation from the CMU research team), the <a href="crucial-questions-experiment.php">crucial questions grader</a> was created fresh for this experiment with no validation against human judgement.</p>

<p>The grading dimensions (cruciality, paper-specificity, tractability, etc.) are plausible, but we have no evidence that the grader's scores correlate with researcher assessments of which questions are actually most valuable.</p>

<hr style="margin: 3rem 0;">

<h2 id="what-would-help">What would strengthen these conclusions?</h2>

<ul>
    <li><strong>Systematic human validation</strong>: 50+ pairwise comparisons between grader rankings and researcher judgements</li>
    <li><strong>Grader model diversification</strong>: Run grading with Claude and Gemini, not just GPT-5.2 Pro</li>
    <li><strong>Larger N with significance tests</strong>: Or explicitly frame all results as exploratory</li>
    <li><strong>A proper A/B test on the chain</strong>: Same chain with ACORN selection vs ad-hoc selection, compare final outputs</li>
</ul>

<?php include 'includes/footer.php'; ?>
