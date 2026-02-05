<?php
$pageTitle = 'Why automated graders?';
include 'includes/header.php';

// Load the ACORN grader prompt for display
$graderPath = __DIR__ . '/data/experiments/full-critique-chain-acorn/prompts/grader-v2-acorn-rubric.txt';
$graderPrompt = file_exists($graderPath) ? file_get_contents($graderPath) : '';
?>

<h1>Why automated graders?</h1>

<p>Systematic context engineering is impractical without automated evaluation.</p>

<h2>The evaluation bottleneck</h2>

<p>If you want to systematically improve LLM outputs through context engineering, you need to run experiments: try different prompts, chain architectures, few-shot examples, and model combinations. Even a modest experiment might generate hundreds of outputs. A serious exploration could produce thousands.</p>

<p>Having researchers manually evaluate all these outputs is prohibitively expensive. You need an automated grader that roughly tracks human judgement—something that can surface the best and worst outputs, give overall scores, and massively reduce the amount of material that needs human evaluation.</p>

<p>Without this, you're limited to ad-hoc vibes-based iteration. With it, you can run structured experiments, measure improvements, and make evidence-based decisions about where to invest further effort.</p>

<h2>Two approaches to automated grading</h2>

<p>There are two main approaches to building automated graders:</p>

<h3>1. Deterministic graders</h3>

<p>Code-based evaluation using regex matching, rubric checklists, or structural analysis. These are reliable and fast, but only work when quality can be reduced to checkable rules. For creative or reasoning tasks—like evaluating the quality of a philosophical critique—deterministic graders are rarely useful.</p>

<h3>2. LLM-as-judge</h3>

<p>Prompt an LLM to evaluate the output, usually against a rubric. This is more flexible and can handle nuanced quality judgements, but introduces its own problems: LLM judges can be biased toward their own outputs, may not calibrate well to human standards, and can be inconsistent across runs.</p>

<p>For evaluating research critiques, we need the LLM-as-judge approach. The question is: can we find or build a judge that correlates well enough with human judgement to be useful?</p>

<h2>The ACORN grader</h2>

<p>Building a custom grader from scratch would require collecting human-rated samples and validating that the grader tracks human judgement—expensive and time-consuming.</p>

<p>Instead, we adopted the grader from the <a href="https://www.andrew.cmu.edu/user/coesterh/conceptual_reasoning_benchmark.html" target="_blank">ACORN Conceptual Reasoning benchmark</a>. ACORN is a dataset for evaluating LLMs' ability to critique philosophical and conceptual arguments—similar to the kind of reasoning Forethought's researchers work with.</p>

<p>Crucially, the ACORN team has already validated that their LLM grader correlates fairly well with their researchers' quality judgements. They provide a detailed rubric covering:</p>

<ul>
    <li><strong>Centrality</strong> — Does the critique target a central claim or assumption?</li>
    <li><strong>Strength</strong> — How logically compelling is the objection?</li>
    <li><strong>Correctness</strong> — Is the critique factually and logically accurate?</li>
    <li><strong>Clarity</strong> — Is it clearly expressed?</li>
</ul>

<p>This is a pragmatic shortcut. The ACORN grader may not perfectly calibrate to Forethought's specific standards, but it should be directionally correct for comparing different approaches against each other.</p>

<details class="prompt-card">
    <summary>View the ACORN grader prompt</summary>
    <div class="content">
        <pre><?= htmlspecialchars($graderPrompt) ?></pre>
    </div>
</details>

<h2>Using ACORN for these experiments</h2>

<p>For the experiments in this sprint, we use the ACORN grader (run on GPT-5.2 Pro) to evaluate critique quality. This lets us:</p>

<ul>
    <li>Compare different prompting strategies at scale</li>
    <li>Identify which approaches produce the highest-scoring outputs</li>
    <li>Surface interesting examples for human review</li>
    <li>Test whether the grader itself is discerning enough to be useful</li>
</ul>

<p>The next step is to validate that ACORN actually gives us useful signal. Does it reliably distinguish good critiques from bad ones? That's what the <a href="grader-accuracy.php">grader discernment experiment</a> tests.</p>

<?php include 'includes/footer.php'; ?>
