<?php
$pageTitle = 'Grader accuracy experiment';
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

<p>Can the ACORN grader distinguish strong critiques from weak ones? If it can't reliably tell the difference between critiques from a frontier model and a much weaker one, that's a problem for any experiment that relies on it.</p>

<h2 id="method">Method</h2>

<div class="prose">
    <p>Simple test: generate critiques with models of different capability, grade them all with the same grader, and check whether the scores reflect the expected quality ordering.</p>

    <p>I generated critiques of "<a href="https://www.forethought.org/research/no-easy-eutopia" target="_blank">No Easy Eutopia</a>" using two models:</p>

    <ol style="margin-left: 1.5rem;">
        <li><strong>GPT-5.2 Pro</strong> — the strongest model (existing data from the <a href="experiment.php">main experiment</a>)</li>
        <li><strong>GPT-4.1 Mini</strong> — a mid-tier model, clearly weaker on reasoning benchmarks (~70% on GPQA vs ~89% for GPT-5)</li>
    </ol>

    <p>Each model generated 10 critiques using four prompts (baseline-v1, surgery, personas, unforgettable), for <strong>40 critiques per model</strong>. All 80 critiques were graded by GPT-5.2 Pro using the ACORN rubric—the grader model stays constant, only the generation model varies.</p>

    <p>If the grader is doing its job, GPT-5.2 Pro critiques should score substantially higher than GPT-4.1 Mini critiques.</p>
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

<h3 id="top-unique-critiques">Top unique critiques by model</h3>

<p>To get a qualitative sense of the difference, here are the three highest-scoring <em>unique</em> critiques from each model (i.e. distinct arguments, with duplicates removed).</p>

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

<h4>GPT-5.2 Pro: top 3 unique critiques</h4>

<?php foreach ($proTop3 as $result):
    $critiqueFile = "$proParsedDir/{$result['_filename']}.txt";
    $critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
?>
<details class="critique-card">
    <summary>
        <span><?= htmlspecialchars($result['title'] ?? $result['_prompt'] . ' #' . substr($result['_filename'], -2)) ?></span>
        <span>
            <span class="badge" style="background: <?= $models['gpt52pro']['badge_color'] ?>; margin-right: 0.5rem;"><?= htmlspecialchars($models['gpt52pro']['label']) ?></span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars($promptNames[$result['_prompt']] ?? $result['_prompt']) ?></span>
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

<?php
// === GPT-4.1 Mini: top 3 unique critiques ===
// Duplicate cluster: correlated/independent factors argument
$miniCorrelatedCluster = [
    'unforgettable-no-easy-eutopia-01', // 0.20 - "Fragile Island" (correlated factors)
    'personas-no-easy-eutopia-10',      // 0.20 - complexity theorist (correlated/nonlinear)
    'personas-no-easy-eutopia-01',      // 0.20 - empirical hardliner (factor independence)
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

<h4>GPT-4.1 Mini: top 3 unique critiques</h4>

<?php foreach ($miniTop3 as $result):
    $critiqueFile = "$miniParsedDir/{$result['_filename']}.txt";
    $critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
?>
<details class="critique-card">
    <summary>
        <span><?= htmlspecialchars($result['title'] ?? $result['_prompt'] . ' #' . substr($result['_filename'], -2)) ?></span>
        <span>
            <span class="badge" style="background: <?= $models['gpt41mini']['badge_color'] ?>; margin-right: 0.5rem;"><?= htmlspecialchars($models['gpt41mini']['label']) ?></span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars($promptNames[$result['_prompt']] ?? $result['_prompt']) ?></span>
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

<p class="text-muted">Both models' top critiques were deduplicated by removing variations on the "correlated dimensions undermine the multiplicative model" argument—the most common critique across all runs. The best representative of that cluster is kept; the rest are excluded.</p>

<h2 id="discussion">Discussion</h2>

<div class="prose">
    <p>The grader clearly distinguishes GPT-5.2 Pro critiques from GPT-4.1 Mini critiques. The gap is large and consistent across all four prompts. This is the minimum bar for a useful grader—if it couldn't tell these apart, we'd have a problem.</p>

    <p>What this <em>doesn't</em> tell us:</p>

    <ul style="margin-left: 1.5rem;">
        <li><strong>Fine-grained discrimination.</strong> The grader can spot a big quality gap. Can it reliably distinguish critiques that are <em>slightly</em> different in quality? The prompt comparison experiment is a softer test of this, but we'd need human validation to be confident.</li>
        <li><strong>Grader bias.</strong> Since the grader is also GPT-5.2 Pro, it might systematically prefer GPT-style outputs. The <a href="experiment.php#appendix-4">cross-model comparison</a> (where GPT-5.2 Pro also scored higher than Claude and Gemini) could partly reflect this bias rather than genuine quality differences.</li>
        <li><strong>Calibration.</strong> Are the absolute scores meaningful, or just the relative ordering? GPT-4.1 Mini averages 0.15—is that really half as good as GPT-5.2 Pro's 0.29, or is the grader compressing the lower end of the scale?</li>
    </ul>

    <p>Overall: the grader passes a basic sanity check. It's good enough to use for comparing prompts within the same model (the <a href="experiment.php">main experiment</a>), where grader bias cancels out. It's less reliable for cross-model comparisons.</p>
</div>

<?php include 'includes/footer.php'; ?>
