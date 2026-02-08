<?php
$pageTitle = 'Why automated graders?';
include 'includes/header.php';

// Load the ACORN grader prompt for display
$graderPath = __DIR__ . '/data/experiments/tools/acorn-benchmark-promptfoo-port/prompts/grader-v2-acorn-rubric.txt';
$graderPrompt = file_exists($graderPath) ? file_get_contents($graderPath) : '';
?>

<h1>Why automated graders?</h1>

<p>Systematic experimentation is impractical without automated evaluation.</p>

<h2 id="the-evaluation-bottleneck">The evaluation bottleneck</h2>

<p>If you want to systematically improve LLM outputs, you need to run experiments: try different prompts, chain architectures, few-shot examples, and model combinations. Even a modest experiment might generate hundreds of outputs. A serious exploration could produce thousands.</p>

<p>Having researchers manually evaluate all these outputs is prohibitively expensive. You need an automated grader that roughly tracks human judgement—something that can surface the best and worst outputs, give overall scores, and massively reduce the amount of material that needs human evaluation.</p>

<p>Without this, you're limited to ad-hoc vibes-based iteration. With it, you can run structured experiments, measure improvements, and make evidence-based decisions about where to invest further effort.</p>

<h2 id="two-approaches">Two approaches to automated grading</h2>

<p>There are two main approaches to building automated graders:</p>

<div style="margin-top: -1rem; margin-left: 2rem">
<h3 id="deterministic-graders">1. Deterministic graders</h3>

<p>Code-based evaluation using regex matching, rubric checklists, or structural analysis. These are reliable and fast, but only work when quality can be reduced to checkable rules. For creative or reasoning tasks—like evaluating the quality of a philosophical critique—deterministic graders are rarely useful.</p>

<h3 id="llm-as-judge">2. LLM-as-judge</h3>

<p>Prompt an LLM to evaluate the output, usually against a rubric. This is more flexible and can handle nuanced quality judgements, but it's harder to implement.</p>
</div>

<p>For evaluating research critiques, we need the LLM-as-judge approach. The question is: can we find or build a grader that correlates well enough with human judgement to be useful?</p>

<h2 id="the-acorn-grader">The ACORN grader</h2>

<p>Building a custom grader from scratch require collecting human-rated outputs and validating that the grader tracks those ratings.</p>

<p>As a pragmatic shortcut, I adapted an already-human-validated grader from the <a href="https://www.andrew.cmu.edu/user/coesterh/conceptual_reasoning_benchmark.html" target="_blank">ACORN Conceptual Reasoning benchmark</a>. ACORN is a dataset for evaluating LLMs' ability to critique philosophical and conceptual arguments—similar to the kind of reasoning Forethought's researchers work with.</p>

<p>The ACORN rubric covers:</p>

<ul>
    <li><strong>Centrality</strong> — Does the critique target a central claim or assumption?</li>
    <li><strong>Strength</strong> — How logically compelling is the objection?</li>
    <li><strong>Correctness</strong> — Is the critique factually and logically accurate?</li>
    <li><strong>Clarity</strong> — Is it clearly expressed?</li>
</ul>

<p>The ACORN grader was not designed to capture Forethought's specific requirements, but I'm hoping it'll be directionally useful for the current experiments.</p>

<details class="prompt-card" style="margin-bottom: 4rem;">
    <summary>View the ACORN grader prompt</summary>
    <div class="content">
        <pre><?= htmlspecialchars($graderPrompt) ?></pre>
    </div>
</details>