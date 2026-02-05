<?php
$pageTitle = 'Crucial questions prompt experiment';
include 'includes/header.php';
include 'includes/functions.php';

// Load experiment results from all three papers (GPT 5.2 Pro only for main report)
$resultsDirs = [
    'no-easy-eutopia' => __DIR__ . '/data/experiments/crucial-questions/results-gpt',
    'compute-bottlenecks' => __DIR__ . '/data/experiments/crucial-questions/results-gpt-cb',
    'convergence-compromise' => __DIR__ . '/data/experiments/crucial-questions/results-gpt-cc',
];

$parsedDirs = [
    'no-easy-eutopia' => __DIR__ . '/data/experiments/crucial-questions/outputs-gpt/parsed',
    'compute-bottlenecks' => __DIR__ . '/data/experiments/crucial-questions/outputs-gpt-cb/parsed',
    'convergence-compromise' => __DIR__ . '/data/experiments/crucial-questions/outputs-gpt-cc/parsed',
];

$paperNames = [
    'no-easy-eutopia' => 'No Easy Eutopia',
    'compute-bottlenecks' => 'Compute Bottlenecks',
    'convergence-compromise' => 'Convergence and Compromise',
];

$results = [];

foreach ($resultsDirs as $paperKey => $resultsPath) {
    if (is_dir($resultsPath)) {
        // Match all variant files (format: variant-paper-NN.json)
        $files = glob("$resultsPath/*-[0-9][0-9].json");
        foreach ($files as $file) {
            $name = basename($file, '.json');
            $data = json_decode(file_get_contents($file), true);
            if ($data && isset($data['overall'])) {
                $data['_filename'] = $name;
                $data['_paper_key'] = $paperKey;
                $results[] = $data;
            }
        }
    }
}

// Parse filename to extract prompt variant and paper
function parseFilename($name) {
    // Format: baseline-no-easy-eutopia-01 or decision-pivots-no-easy-eutopia-01
    $parts = explode('-', $name);

    $baseVariant = array_shift($parts);

    // Handle compound prompt names (decision-pivots, adversarial-worldviews, branching-futures)
    if ($baseVariant === 'decision' && count($parts) > 0 && $parts[0] === 'pivots') {
        $baseVariant = 'decision-pivots';
        array_shift($parts);
    } elseif ($baseVariant === 'adversarial' && count($parts) > 0 && $parts[0] === 'worldviews') {
        $baseVariant = 'adversarial-worldviews';
        array_shift($parts);
    } elseif ($baseVariant === 'branching' && count($parts) > 0 && $parts[0] === 'futures') {
        $baseVariant = 'branching-futures';
        array_shift($parts);
    }

    $variant = $baseVariant;

    // Rest is paper name and number
    $number = array_pop($parts);
    $paper = implode('-', $parts);

    // Create display name for variant
    $variantDisplayMap = [
        'decision-pivots' => 'Decision pivots',
        'adversarial-worldviews' => 'Adversarial worldviews',
        'branching-futures' => 'Branching futures',
        'baseline' => 'Baseline',
    ];
    $variantDisplay = $variantDisplayMap[$baseVariant] ?? ucfirst($baseVariant);

    return [
        'variant' => $variant,
        'variant_display' => $variantDisplay,
        'base_variant' => $baseVariant,
        'paper' => $paper,
        'number' => intval($number),
        'paper_display' => ucwords(str_replace('-', ' ', $paper))
    ];
}

// Group by variant and by paper
$byVariant = [];
$byPaper = [];
foreach ($results as &$result) {
    $parsed = parseFilename($result['_filename']);
    $result['_parsed'] = $parsed;
    $variant = $parsed['variant_display'];
    $paperKey = $result['_paper_key'];

    if (!isset($byVariant[$variant])) {
        $byVariant[$variant] = [];
    }
    if (!isset($byPaper[$paperKey])) {
        $byPaper[$paperKey] = [];
    }

    $byVariant[$variant][] = &$result;
    $byPaper[$paperKey][] = &$result;
}
unset($result); // Break the reference

// Calculate averages
function calculateAverages($results) {
    $sums = ['cruciality' => 0, 'paper_specificity' => 0, 'tractability' => 0, 'clarity' => 0, 'decision_hook' => 0, 'dead_weight' => 0, 'overall' => 0];
    $count = count($results);
    foreach ($results as $r) {
        foreach ($sums as $key => &$sum) {
            $sum += $r[$key] ?? 0;
        }
    }
    return array_map(fn($s) => $count > 0 ? $s / $count : 0, $sums);
}

// Calculate standard deviation for overall scores
function calculateStdDev($results, $mean) {
    $count = count($results);
    if ($count < 2) return 0;

    $sumSquaredDiff = 0;
    foreach ($results as $r) {
        $diff = ($r['overall'] ?? 0) - $mean;
        $sumSquaredDiff += $diff * $diff;
    }
    return sqrt($sumSquaredDiff / $count);
}

$variantAverages = [];
$variantStdDevs = [];
foreach ($byVariant as $variant => $variantResults) {
    $variantAverages[$variant] = calculateAverages($variantResults);
    $variantStdDevs[$variant] = calculateStdDev($variantResults, $variantAverages[$variant]['overall']);
}

// Sort by overall score descending
uasort($variantAverages, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Get the winning variant
$winningVariant = array_key_first($variantAverages);

// Define base variants
$baseVariants = ['Baseline', 'Decision pivots', 'Adversarial worldviews', 'Branching futures'];

// Create filtered versions of byVariant and byPaper for base variants only
$baseByVariant = [];
$baseByPaper = [];
foreach ($results as $result) {
    $variant = $result['_parsed']['variant_display'] ?? '';
    $paperKey = $result['_paper_key'];

    if (in_array($variant, $baseVariants)) {
        if (!isset($baseByVariant[$variant])) {
            $baseByVariant[$variant] = [];
        }
        if (!isset($baseByPaper[$paperKey])) {
            $baseByPaper[$paperKey] = [];
        }
        $baseByVariant[$variant][] = $result;
        $baseByPaper[$paperKey][] = $result;
    }
}

// Separate base variant averages
$baseVariantAverages = array_filter($variantAverages, fn($avgs, $v) => in_array($v, $baseVariants), ARRAY_FILTER_USE_BOTH);

// Sort base variants by overall score
uasort($baseVariantAverages, fn($a, $b) => $b['overall'] <=> $a['overall']);
$winningBaseVariant = array_key_first($baseVariantAverages);

// Count base questions only
$baseQuestionCount = 0;
foreach ($baseVariants as $v) {
    $baseQuestionCount += count($baseByVariant[$v] ?? []);
}

// Calculate base paper averages
$basePaperAverages = [];
foreach ($baseByPaper as $paperKey => $paperResults) {
    $basePaperAverages[$paperKey] = calculateAverages($paperResults);
}
uasort($basePaperAverages, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Calculate top N questions by prompt
$topNCutoff = min(20, $baseQuestionCount);
$allBaseQuestions = [];
foreach ($baseByVariant as $variant => $variantResults) {
    foreach ($variantResults as $r) {
        $allBaseQuestions[] = $r;
    }
}
usort($allBaseQuestions, fn($a, $b) => $b['overall'] <=> $a['overall']);

$topN = array_slice($allBaseQuestions, 0, $topNCutoff);

$topNByPrompt = [];
foreach ($baseVariants as $v) {
    $topNByPrompt[$v] = 0;
}
foreach ($topN as $question) {
    $variant = $question['_parsed']['variant_display'] ?? '';
    if (isset($topNByPrompt[$variant])) {
        $topNByPrompt[$variant]++;
    }
}
arsort($topNByPrompt);

// Calculate base variant std devs
$baseVariantStdDevs = [];
foreach ($baseByVariant as $variant => $variantResults) {
    $avg = $baseVariantAverages[$variant]['overall'] ?? 0;
    $baseVariantStdDevs[$variant] = calculateStdDev($variantResults, $avg);
}

// Load prompt texts
$promptsPath = __DIR__ . '/data/experiments/crucial-questions/prompts';
$baselinePrompt = file_exists("$promptsPath/baseline-parameterised.md") ? file_get_contents("$promptsPath/baseline-parameterised.md") : '';
$decisionPivotsPrompt = file_exists("$promptsPath/decision-pivots-parameterised.md") ? file_get_contents("$promptsPath/decision-pivots-parameterised.md") : '';
$adversarialWorldviewsPrompt = file_exists("$promptsPath/adversarial-worldviews-parameterised.md") ? file_get_contents("$promptsPath/adversarial-worldviews-parameterised.md") : '';
$branchingFuturesPrompt = file_exists("$promptsPath/branching-futures-parameterised.md") ? file_get_contents("$promptsPath/branching-futures-parameterised.md") : '';

// Load grader prompt
$graderPath = __DIR__ . '/data/experiments/crucial-questions/grader-crucial-questions.txt';
$graderPrompt = file_exists($graderPath) ? file_get_contents($graderPath) : '';

// Map variant names to prompts
$promptMap = [
    'Baseline' => $baselinePrompt,
    'Decision pivots' => $decisionPivotsPrompt,
    'Adversarial worldviews' => $adversarialWorldviewsPrompt,
    'Branching futures' => $branchingFuturesPrompt,
];

// Paper URLs for linking
$paperUrls = [
    'no-easy-eutopia' => 'https://www.forethought.org/research/no-easy-eutopia',
    'compute-bottlenecks' => 'https://www.forethought.org/research/will-compute-bottlenecks-prevent-a-software-intelligence-explosion',
    'convergence-compromise' => 'https://www.forethought.org/research/convergence-and-compromise',
];

// Group base questions by paper for per-paper examples
$baseQuestionsByPaper = [];
foreach ($paperNames as $paperKey => $paperName) {
    $baseQuestionsByPaper[$paperKey] = [];
}
foreach ($baseByVariant as $variant => $variantResults) {
    foreach ($variantResults as $r) {
        $paperKey = $r['_paper_key'];
        $baseQuestionsByPaper[$paperKey][] = $r;
    }
}

// Sort each paper's questions by overall score
foreach ($baseQuestionsByPaper as $paperKey => &$paperQuestions) {
    usort($paperQuestions, fn($a, $b) => $b['overall'] <=> $a['overall']);
}
unset($paperQuestions);

// Prepare per-paper top and bottom questions
$paperTopQuestions = [];
$paperBottomQuestions = [];
foreach ($paperNames as $paperKey => $paperName) {
    $paperTopQuestions[$paperKey] = array_slice($baseQuestionsByPaper[$paperKey], 0, 5);
    $bottom = array_slice($baseQuestionsByPaper[$paperKey], -3);
    $paperBottomQuestions[$paperKey] = array_reverse($bottom);
}

// Helper function to render a question card
function renderQuestionCard($result, $parsedDirs, $paperNames) {
    $parsed = $result['_parsed'];
    $paperKey = $result['_paper_key'];
    $parsedPath = $parsedDirs[$paperKey] ?? '';
    $questionFile = "$parsedPath/{$result['_filename']}.txt";
    $questionText = file_exists($questionFile) ? file_get_contents($questionFile) : 'Question text not found.';

    ob_start();
    ?>
    <details class="critique-card" id="question-<?= htmlspecialchars($result['_filename']) ?>">
        <summary>
            <span><?= htmlspecialchars($result['title'] ?? "#{$parsed['number']}") ?></span>
            <span>
                <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars($parsed['variant_display']) ?></span>
                <span class="font-mono <?= scoreClass($result['overall']) ?>"><?= number_format($result['overall'], 2) ?></span>
            </span>
        </summary>
        <div class="content">
            <div class="score-grid">
                <div class="score-box">
                    <div class="label">Cruciality</div>
                    <div class="value <?= scoreClass($result['cruciality'] ?? 0) ?>"><?= number_format($result['cruciality'] ?? 0, 2) ?></div>
                </div>
                <div class="score-box">
                    <div class="label">Specificity</div>
                    <div class="value <?= scoreClass($result['paper_specificity'] ?? 0) ?>"><?= number_format($result['paper_specificity'] ?? 0, 2) ?></div>
                </div>
                <div class="score-box">
                    <div class="label">Tractability</div>
                    <div class="value <?= scoreClass($result['tractability'] ?? 0) ?>"><?= number_format($result['tractability'] ?? 0, 2) ?></div>
                </div>
                <div class="score-box">
                    <div class="label">Decision hook</div>
                    <div class="value <?= scoreClass($result['decision_hook'] ?? 0) ?>"><?= number_format($result['decision_hook'] ?? 0, 2) ?></div>
                </div>
                <div class="score-box overall">
                    <div class="label">Overall</div>
                    <div class="value <?= scoreClass($result['overall'] ?? 0) ?>"><?= number_format($result['overall'] ?? 0, 2) ?></div>
                </div>
            </div>

            <div class="two-column">
                <div class="column">
                    <div class="column-header">
                        <h4>Question</h4>
                        <span class="prompt-label"><?= htmlspecialchars($parsed['variant_display']) ?></span>
                    </div>
                    <div class="critique-text"><?= parseMarkdownInline($questionText) ?></div>
                </div>
                <div class="column">
                    <h4>Grader reasoning</h4>
                    <p class="reasoning"><?= htmlspecialchars($result['reasoning'] ?? 'No reasoning provided.') ?></p>
                </div>
            </div>
        </div>
    </details>
    <?php
    return ob_get_clean();
}
?>

<h1>Crucial questions prompt experiment</h1>

<p>Which prompts best generate "crucial considerations" from research papers—questions that, if answered differently, would warrant major changes in strategy?</p>

<h2 id="what-i-did">What I did</h2>

<div class="prose">
    <p>I created four prompts to generate crucial open questions from research papers:</p>
</div>

<details class="prompt-card">
    <summary>
        <strong>Baseline</strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($baselinePrompt) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Decision pivots <span style="font-weight: normal;">(Parameter sensitivity / threshold hunting)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($decisionPivotsPrompt) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Adversarial worldviews <span style="font-weight: normal;">(Questions from competing perspectives)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($adversarialWorldviewsPrompt) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Branching futures <span style="font-weight: normal;">(Forward projection to find trajectory divergences)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($branchingFuturesPrompt) ?></pre>
    </div>
</details>

<h3 id="running-the-prompts">Running the prompts</h3>
<p>I tested each prompt on three Forethought papers (No Easy Eutopia, Compute Bottlenecks, Convergence & Compromise), generating 7 questions per prompt. Each question was then graded using a custom "crucial questions grader" rubric.</p>

<h3 id="grading-dimensions">Grading dimensions</h3>
<p>The grader evaluates each question on six dimensions:</p>
<ul>
    <li><strong>Cruciality</strong>: Would answering this significantly change strategy?</li>
    <li><strong>Paper-specificity</strong>: Is this tied to THIS paper's claims?</li>
    <li><strong>Tractability</strong>: Could this question plausibly be answered?</li>
    <li><strong>Clarity</strong>: Is the question precise enough to pursue?</li>
    <li><strong>Decision hook</strong>: Does it articulate what would change if answered?</li>
    <li><strong>Dead weight</strong>: Extraneous material penalty</li>
</ul>

<?php if ($baseQuestionCount > 0): ?>

<h2 id="results">Results</h2>

<?php if ($topNCutoff > 0): ?>
<h3 id="top-questions-by-prompt">Top <?= $topNCutoff ?> questions by prompt (all papers)</h3>

<p>Which prompts generated the highest-scoring questions? If we take the top <?= $topNCutoff ?> questions (out of <?= $baseQuestionCount ?>), here's how they break down by prompt:</p>

<table class="table-narrow">
    <thead>
        <tr>
            <th>Prompt</th>
            <th>Count in top <?= $topNCutoff ?></th>
            <th>Share</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($topNByPrompt as $variant => $count): ?>
        <tr>
            <td><strong><?= htmlspecialchars($variant) ?></strong></td>
            <td class="font-mono"><?= $count ?></td>
            <td class="font-mono"><?= number_format($count / $topNCutoff * 100, 0) ?>%</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<h3 id="average-scores">Average scores by prompt (all papers)</h3>
<p>Each prompt generated 7 questions for each of three papers, for 21 total questions per prompt. Average scores are below:</p>
<table>
    <thead>
        <tr>
            <th>Prompt variant</th>
            <th>N</th>
            <th>Cruciality</th>
            <th>Specificity</th>
            <th class="col-hidden">Tractability</th>
            <th class="col-hidden">Clarity</th>
            <th class="col-hidden">Decision hook</th>
            <th class="col-hidden">Dead weight</th>
            <th>Overall *</th>
            <th>Std dev</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($baseVariantAverages as $variant => $avgs): ?>
        <tr>
            <td>
                <strong><?= htmlspecialchars($variant) ?></strong>
            </td>
            <td><?= count($baseByVariant[$variant] ?? []) ?></td>
            <td class="font-mono <?= scoreClass($avgs['cruciality']) ?>"><?= number_format($avgs['cruciality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['paper_specificity']) ?>"><?= number_format($avgs['paper_specificity'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($avgs['tractability']) ?>"><?= number_format($avgs['tractability'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($avgs['clarity']) ?>"><?= number_format($avgs['clarity'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($avgs['decision_hook']) ?>"><?= number_format($avgs['decision_hook'], 2) ?></td>
            <td class="font-mono col-hidden"><?= number_format($avgs['dead_weight'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['overall']) ?>"><strong><?= number_format($avgs['overall'], 2) ?></strong></td>
            <td class="font-mono"><?= number_format($baseVariantStdDevs[$variant] ?? 0, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p class="table-footnote">* To calculate the "overall" score, the grader anchors to cruciality × paper_specificity, then adjusts for tractability, clarity, and decision hook.</p>

<h2 id="examples">Examples</h2>

<div class="filter-bar" id="results-filter-bar">
    <div class="filter-bar-container">
        <div class="filter-group">
            <span class="filter-label">Paper</span>
            <div class="filter-pills" id="results-paper-filter">
                <button class="pill active" data-value="all">No Easy Eutopia</button>
                <?php foreach ($paperNames as $paperKey => $paperName): ?>
                <?php if ($paperKey !== 'no-easy-eutopia'): ?>
                <button class="pill" data-value="<?= htmlspecialchars($paperKey) ?>"><?= htmlspecialchars($paperName) ?></button>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- No Easy Eutopia version (default) -->
<div class="results-paper-section" data-results-paper="all">
<?php
// Get questions for "No Easy Eutopia" only, base variants
$eutopiaResults = array_filter($results, function($r) use ($baseVariants) {
    return $r['_paper_key'] === 'no-easy-eutopia'
        && in_array($r['_parsed']['variant_display'] ?? '', $baseVariants);
});
$eutopiaResults = array_values($eutopiaResults);
usort($eutopiaResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
$topFive = array_slice($eutopiaResults, 0, 5);
$bottomThree = array_slice($eutopiaResults, -3);
$bottomThree = array_reverse($bottomThree); // Show worst first
?>

<h3 id="example-1">Example 1. Top scoring questions</h3>

<p>The five top-scoring questions for "<a href="https://www.forethought.org/research/no-easy-eutopia" target="_blank">No Easy Eutopia</a>":</p>

<?php foreach ($topFive as $result): ?>
<?= renderQuestionCard($result, $parsedDirs, $paperNames) ?>
<?php endforeach; ?>

<p>Use the paper toggle above to see questions for the other papers, or see the <a href="#appendix-2">appendix</a> for all questions.</p>

<h3 id="example-2">Example 2. Bottom-scoring questions</h3>

<p>Here are the three questions that the grader rated worst:</p>

<?php foreach ($bottomThree as $result): ?>
<?= renderQuestionCard($result, $parsedDirs, $paperNames) ?>
<?php endforeach; ?>
</div><!-- End No Easy Eutopia section -->

<!-- Per-paper versions of Examples (skip No Easy Eutopia since it's the default) -->
<?php foreach ($paperNames as $paperKey => $paperName): ?>
<?php if ($paperKey === 'no-easy-eutopia') continue; ?>
<div class="results-paper-section" data-results-paper="<?= htmlspecialchars($paperKey) ?>" style="display: none;">
<h3>Example 1. Top scoring questions</h3>

<p>The five top-scoring questions for "<a href="<?= htmlspecialchars($paperUrls[$paperKey] ?? '#') ?>" target="_blank"><?= htmlspecialchars($paperName) ?></a>":</p>

<?php foreach ($paperTopQuestions[$paperKey] as $result): ?>
<?= renderQuestionCard($result, $parsedDirs, $paperNames) ?>
<?php endforeach; ?>

<h3>Example 2. Bottom-scoring questions</h3>

<p>Here are the three questions that the grader rated worst:</p>

<?php foreach ($paperBottomQuestions[$paperKey] as $result): ?>
<?= renderQuestionCard($result, $parsedDirs, $paperNames) ?>
<?php endforeach; ?>
</div>
<?php endforeach; ?>

<hr style="margin: 3rem 0;">

<h2 id="appendix-1">Appendix 1: Average scores by paper</h2>

<table>
    <thead>
        <tr>
            <th>Paper</th>
            <th>N</th>
            <th>Cruciality</th>
            <th>Specificity</th>
            <th>Tractability</th>
            <th>Clarity</th>
            <th>Decision hook</th>
            <th>Dead weight</th>
            <th>Overall</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($basePaperAverages as $paperKey => $avgs): ?>
        <tr>
            <td><strong><?= htmlspecialchars($paperNames[$paperKey]) ?></strong></td>
            <td><?= count($baseByPaper[$paperKey] ?? []) ?></td>
            <td class="font-mono <?= scoreClass($avgs['cruciality']) ?>"><?= number_format($avgs['cruciality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['paper_specificity']) ?>"><?= number_format($avgs['paper_specificity'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['tractability']) ?>"><?= number_format($avgs['tractability'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['clarity']) ?>"><?= number_format($avgs['clarity'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['decision_hook']) ?>"><?= number_format($avgs['decision_hook'], 2) ?></td>
            <td class="font-mono"><?= number_format($avgs['dead_weight'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['overall']) ?>"><strong><?= number_format($avgs['overall'], 2) ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr style="margin: 3rem 0;">

<h2 id="appendix-2">Appendix 2: All questions</h2>
<p>All questions generated by GPT 5.2 Pro, sorted by overall score.</p>

<div class="filter-bar">
    <div class="filter-group">
        <span class="filter-label">Paper</span>
        <div class="filter-pills" id="paper-filter">
            <button class="pill active" data-value="all">All</button>
            <?php foreach ($paperNames as $paperKey => $paperName): ?>
            <button class="pill" data-value="<?= htmlspecialchars($paperKey) ?>"><?= htmlspecialchars($paperName) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="filter-group">
        <span class="filter-label">Prompt</span>
        <div class="filter-pills" id="variant-filter">
            <button class="pill active" data-value="all">All</button>
            <?php foreach ($baseVariants as $variant): ?>
            <button class="pill" data-value="<?= htmlspecialchars($variant) ?>"><?= htmlspecialchars($variant) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="filter-count">
        Showing <span id="visible-count"><?= $baseQuestionCount ?></span> of <?= $baseQuestionCount ?>
    </div>
</div>

<?php
// Get all base variant results sorted by overall score
$allBaseResults = array_filter($results, fn($r) => in_array($r['_parsed']['variant_display'] ?? '', $baseVariants));
$allBaseResults = array_values($allBaseResults);
usort($allBaseResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
?>

<div id="question-list">
<?php foreach ($allBaseResults as $allIdx => $result): ?>
<?php
$parsed = $result['_parsed'];
$paperKey = $result['_paper_key'];
$parsedPath = $parsedDirs[$paperKey] ?? '';
$questionFile = "$parsedPath/{$result['_filename']}.txt";
$questionText = file_exists($questionFile) ? file_get_contents($questionFile) : 'Question text not found.';
$allHiddenClass = $allIdx >= 5 ? ' all-hidden' : '';
?>
<details class="critique-item<?= $allHiddenClass ?>" id="question-<?= htmlspecialchars($result['_filename']) ?>" data-paper="<?= htmlspecialchars($paperKey) ?>" data-variant="<?= htmlspecialchars($parsed['variant_display']) ?>">
    <summary>
        <span>
            <span class="badge" style="background: #e0e0e0; margin-right: 0.5rem;"><?= htmlspecialchars($paperNames[$paperKey]) ?></span>
            <?= htmlspecialchars($result['title'] ?? "#{$parsed['number']}") ?>
        </span>
        <span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars($parsed['variant_display']) ?></span>
            <span class="font-mono <?= scoreClass($result['overall']) ?>"><?= number_format($result['overall'], 2) ?></span>
        </span>
    </summary>
    <div class="content">
        <div class="score-grid">
            <div class="score-box">
                <div class="label">Cruciality</div>
                <div class="value <?= scoreClass($result['cruciality'] ?? 0) ?>"><?= number_format($result['cruciality'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Specificity</div>
                <div class="value <?= scoreClass($result['paper_specificity'] ?? 0) ?>"><?= number_format($result['paper_specificity'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Tractability</div>
                <div class="value <?= scoreClass($result['tractability'] ?? 0) ?>"><?= number_format($result['tractability'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Clarity</div>
                <div class="value <?= scoreClass($result['clarity'] ?? 0) ?>"><?= number_format($result['clarity'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Decision hook</div>
                <div class="value <?= scoreClass($result['decision_hook'] ?? 0) ?>"><?= number_format($result['decision_hook'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Dead weight</div>
                <div class="value"><?= number_format($result['dead_weight'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box overall">
                <div class="label">Overall</div>
                <div class="value <?= scoreClass($result['overall'] ?? 0) ?>"><?= number_format($result['overall'] ?? 0, 2) ?></div>
            </div>
        </div>

        <div class="two-column">
            <div class="column">
                <div class="column-header">
                    <h4>Question</h4>
                    <span class="prompt-label"><?= htmlspecialchars($parsed['variant_display']) ?></span>
                </div>
                <div class="critique-text"><?= parseMarkdownInline($questionText) ?></div>
            </div>
            <div class="column">
                <h4>Grader reasoning</h4>
                <p class="reasoning"><?= htmlspecialchars($result['reasoning'] ?? 'No reasoning provided.') ?></p>
            </div>
        </div>
    </div>
</details>
<?php endforeach; ?>
</div>
<button class="show-all-btn" id="show-all-questions">Show all <?= count($allBaseResults) ?> questions</button>

<script>
(function() {
    const paperFilter = document.getElementById('paper-filter');
    const variantFilter = document.getElementById('variant-filter');
    const questionList = document.getElementById('question-list');
    const visibleCount = document.getElementById('visible-count');
    const items = questionList.querySelectorAll('.critique-item');
    const showAllBtn = document.getElementById('show-all-questions');
    let allExpanded = false;

    let selectedPaper = 'all';
    let selectedVariant = 'all';

    function updateURL() {
        const params = new URLSearchParams();
        if (selectedPaper !== 'all') params.set('paper', selectedPaper);
        if (selectedVariant !== 'all') params.set('variant', selectedVariant);
        const queryString = params.toString();
        const newURL = window.location.pathname + (queryString ? '?' + queryString : '') + '#appendix-2';
        history.pushState({ paper: selectedPaper, variant: selectedVariant }, '', newURL);
    }

    function updateFilters(updateHistory = true) {
        let count = 0;
        items.forEach(item => {
            const matchPaper = selectedPaper === 'all' || item.dataset.paper === selectedPaper;
            const matchVariant = selectedVariant === 'all' || item.dataset.variant === selectedVariant;
            const visible = matchPaper && matchVariant;
            item.style.display = visible ? '' : 'none';
            if (visible) count++;
        });
        visibleCount.textContent = count;
        if (updateHistory) updateURL();
    }

    function setActiveButton(container, value) {
        container.querySelectorAll('.pill').forEach(p => {
            p.classList.toggle('active', p.dataset.value === value);
        });
    }

    function loadFromURL() {
        const params = new URLSearchParams(window.location.search);
        selectedPaper = params.get('paper') || 'all';
        selectedVariant = params.get('variant') || 'all';
        setActiveButton(paperFilter, selectedPaper);
        setActiveButton(variantFilter, selectedVariant);
        updateFilters(false);
    }

    function expandAllQuestions() {
        if (!allExpanded) {
            items.forEach(item => item.classList.remove('all-hidden'));
            showAllBtn.classList.add('hidden');
            allExpanded = true;
        }
    }

    paperFilter.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            selectedPaper = e.target.dataset.value;
            setActiveButton(paperFilter, selectedPaper);
            expandAllQuestions();
            updateFilters();
        }
    });

    variantFilter.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            selectedVariant = e.target.dataset.value;
            setActiveButton(variantFilter, selectedVariant);
            expandAllQuestions();
            updateFilters();
        }
    });

    window.addEventListener('popstate', e => {
        if (e.state) {
            selectedPaper = e.state.paper || 'all';
            selectedVariant = e.state.variant || 'all';
            setActiveButton(paperFilter, selectedPaper);
            setActiveButton(variantFilter, selectedVariant);
            updateFilters(false);
        }
    });

    showAllBtn.addEventListener('click', expandAllQuestions);

    // Load initial state from URL
    loadFromURL();

    // If URL has filter params, expand all
    if (selectedPaper !== 'all' || selectedVariant !== 'all') {
        expandAllQuestions();
    }
})();

// Results section paper filter (for Examples section)
(function() {
    const resultsFilterBar = document.getElementById('results-paper-filter');
    if (!resultsFilterBar) return;

    const resultsSections = document.querySelectorAll('.results-paper-section');
    let selectedResultsPaper = 'all';

    function updateResultsFilter(paper) {
        selectedResultsPaper = paper;

        // Update button states
        resultsFilterBar.querySelectorAll('.pill').forEach(p => {
            p.classList.toggle('active', p.dataset.value === paper);
        });

        // Show/hide sections
        resultsSections.forEach(section => {
            const sectionPaper = section.dataset.resultsPaper;
            if (sectionPaper === paper) {
                section.style.display = '';
            } else {
                section.style.display = 'none';
            }
        });

        // Update URL without changing hash
        const params = new URLSearchParams(window.location.search);
        if (paper !== 'all') {
            params.set('results-paper', paper);
        } else {
            params.delete('results-paper');
        }
        const hash = window.location.hash || '';
        const queryString = params.toString();
        const newURL = window.location.pathname + (queryString ? '?' + queryString : '') + hash;
        history.replaceState(null, '', newURL);
    }

    // Handle clicks on filter pills
    resultsFilterBar.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            updateResultsFilter(e.target.dataset.value);
        }
    });

    // Load initial state from URL
    const params = new URLSearchParams(window.location.search);
    const initialPaper = params.get('results-paper') || 'all';
    if (initialPaper !== 'all') {
        updateResultsFilter(initialPaper);
    }
})();

// Sticky filter bar detection and visibility
(function() {
    const filterBar = document.getElementById('results-filter-bar');
    const appendixHeading = document.getElementById('appendix-1');
    if (!filterBar) return;

    // Use IntersectionObserver to detect when filter is stuck
    const sentinel = document.createElement('div');
    sentinel.style.height = '1px';
    sentinel.style.position = 'absolute';
    sentinel.style.top = '0';
    sentinel.style.left = '0';
    sentinel.style.right = '0';
    sentinel.style.pointerEvents = 'none';
    filterBar.parentNode.insertBefore(sentinel, filterBar);

    const stuckObserver = new IntersectionObserver(
        ([entry]) => {
            filterBar.classList.toggle('is-stuck', !entry.isIntersecting);
        },
        { threshold: 0, rootMargin: '-1px 0px 0px 0px' }
    );
    stuckObserver.observe(sentinel);

    // Hide filter bar when Appendix 1 heading is at or above the top of viewport
    if (appendixHeading) {
        const hideObserver = new IntersectionObserver(
            ([entry]) => {
                // Hide when Appendix 1 heading reaches the top of the viewport
                filterBar.classList.toggle('filter-hidden', entry.boundingClientRect.top <= 50);
            },
            { threshold: 0, rootMargin: '-50px 0px 0px 0px' }
        );
        hideObserver.observe(appendixHeading);
    }
})();
</script>

<?php else: ?>

<div class="info-box">
    <p><strong>No results yet.</strong> Run the experiment script to generate questions and grades:</p>
    <pre>cd /Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/2026-01/experiments/crucial-questions
python run-experiment-gpt.py --paper no-easy-eutopia</pre>
</div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
