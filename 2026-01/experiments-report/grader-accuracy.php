<?php
$pageTitle = 'Grader accuracy sanity check';
include 'includes/header.php';
include 'includes/functions.php';

$expBase = __DIR__ . '/data/experiments/critique-prompt';

// Model configurations
$models = [
    'gpt52pro' => [
        'label' => 'GPT-5.2 Pro',
        'results_dir' => "$expBase/results-gpt",
        'parsed_dir' => "$expBase/outputs-gpt/parsed",
        'badge_color' => '#d4f0d4',
        'is_baseline' => true,
    ],
    'gpt41mini' => [
        'label' => 'GPT-4.1 Mini',
        'results_dir' => "$expBase/results-gpt41mini",
        'parsed_dir' => "$expBase/outputs-gpt41mini/parsed",
        'badge_color' => '#f0e4d4',
        'is_baseline' => false,
    ],
];

// Load results for each model (No Easy Eutopia only, prompts: baseline-v1, surgery, personas, unforgettable)
$targetPrompts = ['baseline-v1', 'surgery', 'personas', 'unforgettable'];
$modelResults = [];

foreach ($models as $modelKey => $modelConfig) {
    $modelResults[$modelKey] = [];
    $resultsPath = $modelConfig['results_dir'];

    if (!is_dir($resultsPath)) continue;

    $files = glob("$resultsPath/*-no-easy-eutopia-[0-9][0-9].json");
    foreach ($files as $file) {
        $name = basename($file, '.json');
        $data = json_decode(file_get_contents($file), true);
        if (!$data || !isset($data['overall'])) continue;

        // Extract prompt name from filename (e.g. surgery-no-easy-eutopia-01 → surgery)
        $prompt = preg_replace('/-no-easy-eutopia-\d{2}$/', '', $name);
        if (!in_array($prompt, $targetPrompts)) continue;

        $data['_filename'] = $name;
        $data['_prompt'] = $prompt;
        $data['_model'] = $modelKey;
        $modelResults[$modelKey][] = $data;
    }
}

// Calculate per-model averages
$modelAverages = [];
$modelByPrompt = [];
foreach ($models as $modelKey => $modelConfig) {
    $results = $modelResults[$modelKey];
    $count = count($results);
    if ($count === 0) {
        $modelAverages[$modelKey] = null;
        continue;
    }

    $sums = ['centrality' => 0, 'strength' => 0, 'correctness' => 0, 'clarity' => 0, 'dead_weight' => 0, 'single_issue' => 0, 'overall' => 0];
    foreach ($results as $r) {
        foreach ($sums as $key => &$sum) {
            $sum += $r[$key] ?? 0;
        }
    }
    $modelAverages[$modelKey] = [
        'count' => $count,
        'avgs' => array_map(fn($s) => $s / $count, $sums),
    ];

    // Break down by prompt
    $byPrompt = [];
    foreach ($results as $r) {
        $p = $r['_prompt'];
        if (!isset($byPrompt[$p])) $byPrompt[$p] = [];
        $byPrompt[$p][] = $r;
    }
    $modelByPrompt[$modelKey] = $byPrompt;
}

// Calculate per-prompt per-model averages
$promptModelAverages = [];
foreach ($targetPrompts as $prompt) {
    foreach ($models as $modelKey => $config) {
        $results = $modelByPrompt[$modelKey][$prompt] ?? [];
        $count = count($results);
        if ($count === 0) {
            $promptModelAverages[$prompt][$modelKey] = null;
            continue;
        }
        $sum = 0;
        foreach ($results as $r) $sum += $r['overall'];
        $promptModelAverages[$prompt][$modelKey] = $sum / $count;
    }
}

// Prompt display names
$promptNames = [
    'baseline-v1' => 'Baseline v1',
    'surgery' => 'Surgery',
    'personas' => 'Personas',
    'unforgettable' => 'Unforgettable',
];
?>

<h1>Grader accuracy experiment</h1>

<p><a href="index.php">&larr; Back to main experiment</a></p>

<p>Does the ACORN grader work at all?</p>

<h2 id="method">Method</h2>

<div class="prose">
    <p>I generated critiques of "<a href="https://www.forethought.org/research/no-easy-eutopia" target="_blank">No Easy Eutopia</a>" using two models:</p>

    <ol style="margin-left: 1.5rem;">
        <li><strong>GPT-5.2 Pro</strong> — the strongest model (existing data from the <a href="critique-prompt-experiment.php">main experiment</a>)</li>
        <li><strong>GPT-4.1 Mini</strong> — a mid-tier model, clearly weaker on reasoning benchmarks (~70% on GPQA vs ~89% for GPT-5)</li>
    </ol>

    <p>Each model generated 10 critiques using four different prompts, for <strong>40 critiques per model</strong>. All 80 critiques were graded by GPT-5.2 Pro using the ACORN rubric—the grader model stays constant, only the generation model varies.*</p>

    <p>If the grader is doing its job, GPT-5.2 Pro critiques should score much higher than GPT-4.1 Mini critiques.**</p>

    <p class="text-muted"><em>* One might worry that GPT-5.2 Pro is biased to prefer its own outputs. But I also graded the same critiques with Claude Opus 4.6, and got the same bottom line.</em></p>

    <p class="text-muted"><em>** I'm assuming, on priors, that GPT 4.1 Mini critiques are in fact a bunch weaker.</em></p>
</div>

<h2 id="results">Results</h2>

<h3 id="overall-scores">Average overall score by model</h3>

<?php
$hasData = false;
foreach ($modelAverages as $avg) {
    if ($avg !== null) $hasData = true;
}
?>

<?php if ($hasData): ?>
<table>
    <thead>
        <tr>
            <th>Generation model</th>
            <th>N</th>
            <th>Centrality</th>
            <th>Strength</th>
            <th>Correctness</th>
            <th>Clarity</th>
            <th>Overall</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $modelKey => $config):
            $avg = $modelAverages[$modelKey];
            if ($avg === null) continue;
            $a = $avg['avgs'];
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($config['label']) ?></strong><?= $config['is_baseline'] ? ' (baseline)' : '' ?></td>
            <td><?= $avg['count'] ?></td>
            <td class="font-mono <?= scoreClass($a['centrality']) ?>"><?= number_format($a['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($a['strength']) ?>"><?= number_format($a['strength'], 2) ?></td>
            <td class="font-mono <?= scoreClass($a['correctness']) ?>"><?= number_format($a['correctness'], 2) ?></td>
            <td class="font-mono <?= scoreClass($a['clarity']) ?>"><?= number_format($a['clarity'], 2) ?></td>
            <td class="font-mono <?= scoreClass($a['overall']) ?>"><strong><?= number_format($a['overall'], 2) ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
// Check if order matches expectation
$proAvg = $modelAverages['gpt52pro']['avgs']['overall'] ?? 0;
$miniAvg = $modelAverages['gpt41mini']['avgs']['overall'] ?? 0;
?>

<?php if ($proAvg > 0 && $miniAvg > 0): ?>
<?php $ratio = $miniAvg > 0 ? $proAvg / $miniAvg : 0; ?>
<p>GPT-5.2 Pro critiques scored <?= number_format($ratio, 1) ?>x higher on average (<?= number_format($proAvg, 2) ?> vs <?= number_format($miniAvg, 2) ?>). The distributions barely overlap—GPT-4.1 Mini's <em>best</em> critique (<?= number_format(max(array_map(fn($r) => $r['overall'], $modelResults['gpt41mini'])), 2) ?>) is below GPT-5.2 Pro's mean.</p>
<?php endif; ?>


<?php else: ?>
<p class="todo">Weak model data not yet available. Run the experiment first.</p>
<?php endif; ?>

<h3 id="by-prompt">Breakdown by prompt</h3>

<?php if ($hasData): ?>
<table>
    <thead>
        <tr>
            <th>Prompt</th>
            <?php foreach ($models as $config): ?>
            <th><?= htmlspecialchars($config['label']) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($targetPrompts as $prompt): ?>
        <tr>
            <td><strong><?= htmlspecialchars($promptNames[$prompt]) ?></strong></td>
            <?php foreach ($models as $modelKey => $config):
                $val = $promptModelAverages[$prompt][$modelKey] ?? null;
            ?>
            <td class="font-mono <?= $val !== null ? scoreClass($val) : '' ?>">
                <?= $val !== null ? number_format($val, 2) : '—' ?>
            </td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p class="todo">Data not yet available.</p>
<?php endif; ?>

<?php
// =====================================================
// Cross-grader comparison: Claude Opus 4.6 as independent grader
// =====================================================
$claudeGraderDirs = [
    'gpt52pro' => "$expBase/results-gpt-claude-grader",
    'gpt41mini' => "$expBase/results-gpt41mini-claude-grader",
];

$claudeResults = [];
$claudeAverages = [];
$claudeByPrompt = [];

foreach ($claudeGraderDirs as $modelKey => $dirPath) {
    $claudeResults[$modelKey] = [];
    if (!is_dir($dirPath)) continue;

    $files = glob("$dirPath/*-no-easy-eutopia-[0-9][0-9].json");
    foreach ($files as $file) {
        $name = basename($file, '.json');
        $data = json_decode(file_get_contents($file), true);
        if (!$data || !isset($data['overall'])) continue;

        $prompt = preg_replace('/-no-easy-eutopia-\d{2}$/', '', $name);
        if (!in_array($prompt, $targetPrompts)) continue;

        $data['_filename'] = $name;
        $data['_prompt'] = $prompt;
        $data['_model'] = $modelKey;
        $claudeResults[$modelKey][] = $data;
    }

    $results = $claudeResults[$modelKey];
    $count = count($results);
    if ($count === 0) {
        $claudeAverages[$modelKey] = null;
        continue;
    }

    $sum = 0;
    foreach ($results as $r) $sum += $r['overall'];
    $claudeAverages[$modelKey] = [
        'count' => $count,
        'avg_overall' => $sum / $count,
    ];

    // Break down by prompt
    $byPrompt = [];
    foreach ($results as $r) {
        $p = $r['_prompt'];
        if (!isset($byPrompt[$p])) $byPrompt[$p] = [];
        $byPrompt[$p][] = $r;
    }
    $claudeByPrompt[$modelKey] = $byPrompt;
}

$hasCrossGraderData = ($claudeAverages['gpt52pro'] !== null && $claudeAverages['gpt41mini'] !== null);
?>

<?php
$claudeProOverall = isset($claudeAverages['gpt52pro']) ? $claudeAverages['gpt52pro']['avg_overall'] : 0;
$claudeMiniOverall = isset($claudeAverages['gpt41mini']) ? $claudeAverages['gpt41mini']['avg_overall'] : 0;
$gptProOverall = $modelAverages['gpt52pro']['avgs']['overall'] ?? 0;
$gptMiniOverall = $modelAverages['gpt41mini']['avgs']['overall'] ?? 0;
$claudeAgrees = $claudeProOverall > $claudeMiniOverall;
$claudeRatio = $claudeMiniOverall > 0 ? $claudeProOverall / $claudeMiniOverall : 0;
$gptRatio = $gptMiniOverall > 0 ? $gptProOverall / $gptMiniOverall : 0;
?>

<h3 id="top-unique-critiques">Top unique critiques by model</h3>

<p>To get a qualitative sense of the difference, here are the three highest-scoring <em>unique</em> critiques from each model.</p>

<?php
// === GPT-5.2 Pro: top 3 unique critiques ===
// Duplicate cluster: correlated dimensions argument (keep best representative only)
$proCorrelatedCluster = [
    'surgery-no-easy-eutopia-02',      // 0.55 - duplicate
    'personas-no-easy-eutopia-01',     // 0.52 - duplicate
    'unforgettable-no-easy-eutopia-01', // 0.35 - duplicate
    'baseline-v1-no-easy-eutopia-05',  // 0.22 - duplicate
];

$proResults = $modelResults['gpt52pro'];
usort($proResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
$proUnique = [];
foreach ($proResults as $r) {
    if (in_array($r['_filename'], $proCorrelatedCluster)) continue;
    $proUnique[] = $r;
}
$proTop3 = array_slice($proUnique, 0, 3);
$proParsedDir = $models['gpt52pro']['parsed_dir'];
?>

<h4 style="margin-bottom: 1rem;">GPT-5.2 Pro: top 3 unique critiques</h4>

<div>
<?php foreach ($proTop3 as $result):
    $critiqueFile = "$proParsedDir/{$result['_filename']}.txt";
    $critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
?>
<details class="critique-card" id="critique-<?= htmlspecialchars($result['_filename']) ?>">
    <summary>
        <span><?= htmlspecialchars($result['title'] ?? $result['_prompt'] . ' #' . substr($result['_filename'], -2)) ?></span>
        <span>
            <span class="font-mono <?= scoreClass($result['overall']) ?>"><?= number_format($result['overall'], 2) ?></span>
        </span>
    </summary>
    <div class="content">
        <div class="score-grid">
            <div class="score-box">
                <div class="label">Centrality</div>
                <div class="value <?= scoreClass($result['centrality'] ?? 0) ?>"><?= number_format($result['centrality'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Strength</div>
                <div class="value <?= scoreClass($result['strength'] ?? 0) ?>"><?= number_format($result['strength'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Correctness</div>
                <div class="value <?= scoreClass($result['correctness'] ?? 0) ?>"><?= number_format($result['correctness'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Clarity</div>
                <div class="value <?= scoreClass($result['clarity'] ?? 0) ?>"><?= number_format($result['clarity'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box overall">
                <div class="label">Overall</div>
                <div class="value <?= scoreClass($result['overall'] ?? 0) ?>"><?= number_format($result['overall'] ?? 0, 2) ?></div>
            </div>
        </div>

        <div class="two-column">
            <div class="column">
                <h4>Critique</h4>
                <div class="critique-text"><?= parseMarkdownInline($critiqueText) ?></div>
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

<?php
// === GPT-4.1 Mini: top 3 unique critiques ===
// Duplicate cluster: correlated/independent factors argument
$miniCorrelatedCluster = [
    'unforgettable-no-easy-eutopia-01', // 0.20 - "Fragile Island" (correlated factors)
    'personas-no-easy-eutopia-10',      // 0.20 - complexity theorist (correlated/nonlinear)
    'personas-no-easy-eutopia-01',      // 0.20 - empirical hardliner (factor independence)
    'surgery-no-easy-eutopia-07',       // 0.22 - correlated factors + non-uniform distributions
    'personas-no-easy-eutopia-04',      // 0.15 - correlated factors
    'surgery-no-easy-eutopia-01',       // 0.15 - correlated factors
    'baseline-v1-no-easy-eutopia-01',   // 0.15 - correlated factors
];

$miniResults = $modelResults['gpt41mini'];
usort($miniResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
$miniUnique = [];
foreach ($miniResults as $r) {
    if (in_array($r['_filename'], $miniCorrelatedCluster)) continue;
    $miniUnique[] = $r;
}
$miniTop3 = array_slice($miniUnique, 0, 3);
$miniParsedDir = $models['gpt41mini']['parsed_dir'];
?>

<h4 style="margin-top: 2rem; margin-bottom: 1rem;">GPT-4.1 Mini: top 3 unique critiques</h4>

<div>
<?php foreach ($miniTop3 as $result):
    $critiqueFile = "$miniParsedDir/{$result['_filename']}.txt";
    $critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
?>
<details class="critique-card" id="critique-<?= htmlspecialchars($result['_filename']) ?>">
    <summary>
        <span><?= htmlspecialchars($result['title'] ?? $result['_prompt'] . ' #' . substr($result['_filename'], -2)) ?></span>
        <span>
            <span class="font-mono <?= scoreClass($result['overall']) ?>"><?= number_format($result['overall'], 2) ?></span>
        </span>
    </summary>
    <div class="content">
        <div class="score-grid">
            <div class="score-box">
                <div class="label">Centrality</div>
                <div class="value <?= scoreClass($result['centrality'] ?? 0) ?>"><?= number_format($result['centrality'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Strength</div>
                <div class="value <?= scoreClass($result['strength'] ?? 0) ?>"><?= number_format($result['strength'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Correctness</div>
                <div class="value <?= scoreClass($result['correctness'] ?? 0) ?>"><?= number_format($result['correctness'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Clarity</div>
                <div class="value <?= scoreClass($result['clarity'] ?? 0) ?>"><?= number_format($result['clarity'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box overall">
                <div class="label">Overall</div>
                <div class="value <?= scoreClass($result['overall'] ?? 0) ?>"><?= number_format($result['overall'] ?? 0, 2) ?></div>
            </div>
        </div>

        <div class="two-column">
            <div class="column">
                <h4>Critique</h4>
                <div class="critique-text"><?= parseMarkdownInline($critiqueText) ?></div>
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

<h2 id="discussion">Conclusion</h2>

<div class="prose" style="margin-bottom: 2rem;">
    <p>The grader clearly distinguishes GPT-5.2 Pro critiques from GPT-4.1 Mini critiques. The gap is large and consistent across all four prompts. So: the grader passes a basic sanity check.</p>

    <p>Worry: the highest scoring GPT 5.2 Pro critique is the same basic idea as the highest scoring GPT-4.1 Mini critique. But the ACORN rating is very different (0.60 vs 0.30). The grader might be too sensitive to the precision with which the critique is stated, rather than the strength of the core idea.</p>
</div>

<?php if ($hasCrossGraderData): ?>
    <div style="display: none;">
<h2 id="cross-grader">Appendix: cross-grader comparison</h2>

<div class="prose">
    <p>To check whether the GPT-5.2 Pro grader's preference for GPT-5.2 Pro critiques reflects genuine quality differences (rather than self-preference bias), the same 80 critiques were re-graded by Claude Opus 4.6 using the identical ACORN rubric.</p>
</div>

<h3>Average overall score by generation model and grader</h3>

<table>
    <thead>
        <tr>
            <th>Generation model</th>
            <th>GPT-5.2 Pro grader</th>
            <th>Claude Opus 4.6 grader</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $modelKey => $config):
            $gptAvg = $modelAverages[$modelKey] ? $modelAverages[$modelKey]['avgs']['overall'] : null;
            $claudeAvg = isset($claudeAverages[$modelKey]) ? $claudeAverages[$modelKey]['avg_overall'] : null;
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($config['label']) ?></strong></td>
            <td class="font-mono <?= $gptAvg !== null ? scoreClass($gptAvg) : '' ?>">
                <?= $gptAvg !== null ? number_format($gptAvg, 2) : '—' ?>
            </td>
            <td class="font-mono <?= $claudeAvg !== null ? scoreClass($claudeAvg) : '' ?>">
                <?= $claudeAvg !== null ? number_format($claudeAvg, 2) : '—' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Per-prompt breakdown</h3>

<table>
    <thead>
        <tr>
            <th rowspan="2">Prompt</th>
            <th colspan="2">GPT-5.2 Pro grader</th>
            <th colspan="2">Claude Opus 4.6 grader</th>
        </tr>
        <tr>
            <th>GPT-5.2 Pro</th>
            <th>GPT-4.1 Mini</th>
            <th>GPT-5.2 Pro</th>
            <th>GPT-4.1 Mini</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($targetPrompts as $prompt):
            $gptProVal = $promptModelAverages[$prompt]['gpt52pro'] ?? null;
            $gptMiniVal = $promptModelAverages[$prompt]['gpt41mini'] ?? null;

            $claudeProResults = $claudeByPrompt['gpt52pro'][$prompt] ?? [];
            $claudeMiniResults = $claudeByPrompt['gpt41mini'][$prompt] ?? [];
            $claudeProVal = count($claudeProResults) > 0 ? array_sum(array_map(fn($r) => $r['overall'], $claudeProResults)) / count($claudeProResults) : null;
            $claudeMiniVal = count($claudeMiniResults) > 0 ? array_sum(array_map(fn($r) => $r['overall'], $claudeMiniResults)) / count($claudeMiniResults) : null;
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($promptNames[$prompt]) ?></strong></td>
            <td class="font-mono <?= $gptProVal !== null ? scoreClass($gptProVal) : '' ?>">
                <?= $gptProVal !== null ? number_format($gptProVal, 2) : '—' ?>
            </td>
            <td class="font-mono <?= $gptMiniVal !== null ? scoreClass($gptMiniVal) : '' ?>">
                <?= $gptMiniVal !== null ? number_format($gptMiniVal, 2) : '—' ?>
            </td>
            <td class="font-mono <?= $claudeProVal !== null ? scoreClass($claudeProVal) : '' ?>">
                <?= $claudeProVal !== null ? number_format($claudeProVal, 2) : '—' ?>
            </td>
            <td class="font-mono <?= $claudeMiniVal !== null ? scoreClass($claudeMiniVal) : '' ?>">
                <?= $claudeMiniVal !== null ? number_format($claudeMiniVal, 2) : '—' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="prose">
    <?php if ($claudeAgrees): ?>
    <p>Claude Opus 4.6 <?= $claudeRatio >= 1.3 ? 'clearly agrees' : 'agrees' ?> that GPT-5.2 Pro critiques are stronger: <?= number_format($claudeProOverall, 2) ?> vs <?= number_format($claudeMiniOverall, 2) ?> (<?= number_format($claudeRatio, 1) ?>x ratio, compared to <?= number_format($gptRatio, 1) ?>x from the GPT grader). This suggests the quality gap is real and not primarily driven by grader self-preference.</p>
    <?php else: ?>
    <p>Interestingly, Claude Opus 4.6 does <em>not</em> rank GPT-5.2 Pro critiques higher (<?= number_format($claudeProOverall, 2) ?> vs <?= number_format($claudeMiniOverall, 2) ?>), unlike the GPT-5.2 Pro grader (<?= number_format($gptProOverall, 2) ?> vs <?= number_format($gptMiniOverall, 2) ?>). This raises the possibility that the GPT grader's preference partly reflects self-preference bias rather than genuine quality differences.</p>
    <?php endif; ?>
</div>
</div>
<?php endif; ?>


<script src="assets/critique-deeplink.js"></script>
<?php include 'includes/footer.php'; ?>
