<?php
$pageTitle = 'January 2026 experiments';
include 'includes/header.php';

// Section 1: Grader experiments
$graderExperiments = [
    [
        'title' => 'Why do we need an automated grader?',
        'description' => 'The evaluation bottleneck, two approaches to grading, and introducing the ACORN grader.',
        'file' => 'why-automated-graders.php',
        'status' => '',
    ],
    [
        'title' => 'Grader accuracy sanity check',
        'description' => 'We generate critiques with GPT-5.2 Pro and GPT-4.1 Mini. Can the ACORN grader detect the quality gap? This should be easy.',
        'file' => 'grader-accuracy.php',
        'status' => 'complete',
    ],
];

// Section 2: Context engineering experiments
$contextExperiments = [
    [
        'title' => 'Iterating on prompts with ACORN feedback',
        'description' => 'We draft three more sophisticated critique prompts and use the grader to compare them against a baseline.',
        'file' => 'critique-prompt-experiment.php',
        'status' => 'complete',
    ],
    [
        'title' => 'Worldview primer experiment',
        'description' => 'Does providing Forethought\'s worldview primer as context produce more targeted critiques that focus on genuine uncertainties?',
        'file' => 'worldview-primer-experiment.php',
        'status' => 'complete',
    ],
    [
        'title' => 'Crucial questions prompt experiment',
        'description' => 'Which prompts best generate "crucial considerations" from research papers—questions that could change strategy?',
        'file' => 'crucial-questions-experiment.php',
        'status' => 'in-progress',
    ],
    [
        'title' => 'Full critique chain with ACORN grading',
        'description' => 'Does LLM grading at selection steps improve final critique quality?',
        'file' => 'chain-acorn.php',
        'status' => 'in-progress',
    ],
];

// Section 3: Context engineering research
$researchExperiments = [
    [
        'title' => 'Deep research prompting techniques (2025–2026)',
        'description' => 'A survey of prompting techniques for deep research tasks, covering chain-of-thought, self-consistency, reflection, and multi-agent approaches.',
        'file' => 'research/deep-research-prompting-techniques.php',
        'status' => 'reference',
    ],
];

// Section 4: Meta
$metaPages = [
    [
        'title' => 'Limitations and open questions',
        'description' => 'Key caveats about these experiments: validation gaps, sample sizes, and what would strengthen the conclusions.',
        'file' => 'limitations.php',
        'status' => '',
    ],
];
?>

<h1>January 2026 experiments</h1>

<p>How much could systematic context engineering improve the quality of LLM-generated critiques?</p>

<h2 id="automated-grader">Can we build an automated grader?</h2>


<p>Systematic context engineering is impractical unless we can make automated graders that track our own judgement about the quality of LLM research outputs. Can we?</p>

<div class="experiment-list">
    <?php foreach ($graderExperiments as $exp): ?>
    <a href="<?= htmlspecialchars($exp['file']) ?>" class="experiment-card">
        <div class="experiment-header">
            <h2><?= htmlspecialchars($exp['title']) ?></h2>
            <?php if ($exp['status'] === 'complete'): ?>
            <span class="status-badge status-complete">Complete</span>
            <?php elseif ($exp['status'] === 'in-progress'): ?>
            <span class="status-badge status-progress">In progress</span>
            <?php elseif ($exp['status'] === 'reference'): ?>
            <span class="status-badge status-reference">Reference</span>
            <?php endif; ?>
        </div>
        <p><?= htmlspecialchars($exp['description']) ?></p>
    </a>
    <?php endforeach; ?>
</div>

<h2 id="context-engineering-experiments">Context engineering experiments using the ACORN grader</h2>
<p>Does some quick prompt iteration deliver meaningfully better outputs?</p>
<div class="experiment-list">
    <?php foreach ($contextExperiments as $exp): ?>
    <a href="<?= htmlspecialchars($exp['file']) ?>" class="experiment-card">
        <div class="experiment-header">
            <h2><?= htmlspecialchars($exp['title']) ?></h2>
            <?php if ($exp['status'] === 'complete'): ?>
            <span class="status-badge status-complete">Complete</span>
            <?php elseif ($exp['status'] === 'in-progress'): ?>
            <span class="status-badge status-progress">In progress</span>
            <?php elseif ($exp['status'] === 'reference'): ?>
            <span class="status-badge status-reference">Reference</span>
            <?php endif; ?>
        </div>
        <p><?= htmlspecialchars($exp['description']) ?></p>
    </a>
    <?php endforeach; ?>
</div>

<h2 id="context-engineering-research">Context engineering research</h2>
<p>Background research on prompting techniques and context engineering.</p>
<div class="experiment-list">
    <?php foreach ($researchExperiments as $exp): ?>
    <a href="<?= htmlspecialchars($exp['file']) ?>" class="experiment-card">
        <div class="experiment-header">
            <h2><?= htmlspecialchars($exp['title']) ?></h2>
            <?php if ($exp['status'] === 'complete'): ?>
            <span class="status-badge status-complete">Complete</span>
            <?php elseif ($exp['status'] === 'in-progress'): ?>
            <span class="status-badge status-progress">In progress</span>
            <?php elseif ($exp['status'] === 'reference'): ?>
            <span class="status-badge status-reference">Reference</span>
            <?php endif; ?>
        </div>
        <p><?= htmlspecialchars($exp['description']) ?></p>
    </a>
    <?php endforeach; ?>
</div>

<h2 id="meta">Meta</h2>
<div class="experiment-list">
    <?php foreach ($metaPages as $exp): ?>
    <a href="<?= htmlspecialchars($exp['file']) ?>" class="experiment-card">
        <div class="experiment-header">
            <h2><?= htmlspecialchars($exp['title']) ?></h2>
            <?php if ($exp['status'] === 'complete'): ?>
            <span class="status-badge status-complete">Complete</span>
            <?php elseif ($exp['status'] === 'in-progress'): ?>
            <span class="status-badge status-progress">In progress</span>
            <?php elseif ($exp['status'] === 'reference'): ?>
            <span class="status-badge status-reference">Reference</span>
            <?php endif; ?>
        </div>
        <p><?= htmlspecialchars($exp['description']) ?></p>
    </a>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
