<?php
$pageTitle = 'Worldview primer experiment';
include 'includes/header.php';
include 'includes/functions.php';
include 'includes/components/critique-card.php';

// =============================================================================
// DATA LOADING
// =============================================================================

// Worldview primer results (with primer context)
$wvResultsDir = __DIR__ . '/data/experiments/critique-prompt/results-gpt-wv';
$wvParsedDir = __DIR__ . '/data/experiments/critique-prompt/outputs-gpt-wv/parsed';

// Original results for comparison (No Easy Eutopia only)
$origResultsDir = __DIR__ . '/data/experiments/critique-prompt/results-gpt';
$origParsedDir = __DIR__ . '/data/experiments/critique-prompt/outputs-gpt/parsed';

// ACORN v3 re-graded results (with relevance dimension)
$v3OrigResultsDir = __DIR__ . '/data/experiments/critique-prompt/results-gpt-v3';
$v3WvResultsDir = __DIR__ . '/data/experiments/critique-prompt/results-gpt-wv-v3';

$paperName = 'No Easy Eutopia';
$paperUrl = 'https://www.forethought.org/research/no-easy-eutopia';
$paperKey = 'no-easy-eutopia';

// The 4 prompts tested
$testedPrompts = ['Conversational', 'Pivot-attack', 'Unforgettable', 'Personas'];

// Parsed dirs mapping (needed by renderCritiqueCard)
$parsedDirs = [
    'no-easy-eutopia' => $wvParsedDir,
];
$origParsedDirsMap = [
    'no-easy-eutopia' => $origParsedDir,
];

// =============================================================================
// FILENAME PARSING (matches main experiment page)
// =============================================================================

function parseWvFilename($name) {
    $parts = explode('-', $name);
    $baseVariant = array_shift($parts);

    // Handle compound prompt names
    if ($baseVariant === 'pivot' && count($parts) > 0 && $parts[0] === 'attack') {
        $baseVariant = 'pivot-attack';
        array_shift($parts);
    }

    $variant = $baseVariant;
    $number = array_pop($parts);
    $paper = implode('-', $parts);

    $variantDisplay = ucfirst($baseVariant);
    if ($baseVariant === 'pivot-attack') {
        $variantDisplay = 'Pivot-attack';
    }

    return [
        'variant' => $variant,
        'variant_display' => $variantDisplay,
        'base_variant' => $baseVariant,
        'paper' => $paper,
        'number' => intval($number),
        'paper_display' => ucwords(str_replace('-', ' ', $paper))
    ];
}

// =============================================================================
// LOAD RESULTS
// =============================================================================

function loadResults($resultsDir, $paperKey) {
    $results = [];
    if (is_dir($resultsDir)) {
        $files = glob("$resultsDir/*-[0-9][0-9].json");
        foreach ($files as $file) {
            $name = basename($file, '.json');
            $data = json_decode(file_get_contents($file), true);
            if ($data && isset($data['overall'])) {
                $data['_filename'] = $name;
                $data['_paper_key'] = $paperKey;
                $data['_parsed'] = parseWvFilename($name);
                $results[] = $data;
            }
        }
    }
    return $results;
}

$wvResults = loadResults($wvResultsDir, $paperKey);
$origResults = loadResults($origResultsDir, $paperKey);

// Filter original results to only include the 4 tested prompts
$origResults = array_values(array_filter($origResults, function($r) use ($testedPrompts) {
    return in_array($r['_parsed']['variant_display'], $testedPrompts);
}));

// Load ACORN v3 results
$v3OrigResults = loadResults($v3OrigResultsDir, $paperKey);
$v3WvResults = loadResults($v3WvResultsDir, $paperKey);

// =============================================================================
// GROUP BY VARIANT
// =============================================================================

$wvByVariant = [];
foreach ($wvResults as $r) {
    $variant = $r['_parsed']['variant_display'];
    if (!isset($wvByVariant[$variant])) {
        $wvByVariant[$variant] = [];
    }
    $wvByVariant[$variant][] = $r;
}

$origByVariant = [];
foreach ($origResults as $r) {
    $variant = $r['_parsed']['variant_display'];
    if (!isset($origByVariant[$variant])) {
        $origByVariant[$variant] = [];
    }
    $origByVariant[$variant][] = $r;
}

// =============================================================================
// CALCULATE AVERAGES
// =============================================================================

$wvAverages = [];
$origAverages = [];
$wvStdDevs = [];
$origStdDevs = [];

foreach ($testedPrompts as $variant) {
    if (isset($wvByVariant[$variant])) {
        $wvAverages[$variant] = calculateAverages($wvByVariant[$variant]);
        $wvStdDevs[$variant] = calculateStdDev($wvByVariant[$variant], $wvAverages[$variant]['overall']);
    }
    if (isset($origByVariant[$variant])) {
        $origAverages[$variant] = calculateAverages($origByVariant[$variant]);
        $origStdDevs[$variant] = calculateStdDev($origByVariant[$variant], $origAverages[$variant]['overall']);
    }
}

// Overall averages across all prompts
$wvOverall = count($wvResults) > 0 ? calculateAverages($wvResults) : null;
$origOverall = count($origResults) > 0 ? calculateAverages($origResults) : null;

// ACORN v3 grouping and averages
$v3OrigByVariant = [];
foreach ($v3OrigResults as $r) {
    $variant = $r['_parsed']['variant_display'];
    $v3OrigByVariant[$variant][] = $r;
}
$v3WvByVariant = [];
foreach ($v3WvResults as $r) {
    $variant = $r['_parsed']['variant_display'];
    $v3WvByVariant[$variant][] = $r;
}

$v3OrigAverages = [];
$v3WvAverages = [];
foreach ($testedPrompts as $variant) {
    if (isset($v3OrigByVariant[$variant])) {
        $v3OrigAverages[$variant] = calculateAverages($v3OrigByVariant[$variant]);
    }
    if (isset($v3WvByVariant[$variant])) {
        $v3WvAverages[$variant] = calculateAverages($v3WvByVariant[$variant]);
    }
}
$v3OrigOverall = count($v3OrigResults) > 0 ? calculateAverages($v3OrigResults) : null;
$v3WvOverall = count($v3WvResults) > 0 ? calculateAverages($v3WvResults) : null;

// Sort both result sets by overall score for display
usort($wvResults, function($a, $b) { return $b['overall'] <=> $a['overall']; });
usort($origResults, function($a, $b) { return $b['overall'] <=> $a['overall']; });

// Top 5 from each set for examples section
$topOriginal = array_slice($origResults, 0, 5);
$topWorldview = array_slice($wvResults, 0, 5);

// Build v3 top 5 for original critiques (same critiques, ranked by v3 overall)
// Index v3 results by filename for lookup
$v3OrigByFilename = [];
foreach ($v3OrigResults as $r) {
    $v3OrigByFilename[$r['_filename']] = $r;
}

// Create merged records: original critique with v3 scores attached
$origWithV3 = [];
foreach ($origResults as $r) {
    $fn = $r['_filename'];
    if (isset($v3OrigByFilename[$fn])) {
        $merged = $r;
        $merged['_v3'] = $v3OrigByFilename[$fn];
        $origWithV3[] = $merged;
    }
}

// Sort by v3 overall
usort($origWithV3, function($a, $b) {
    return ($b['_v3']['overall'] ?? 0) <=> ($a['_v3']['overall'] ?? 0);
});
$topOrigByV3 = array_slice($origWithV3, 0, 5);

// Check which critiques differ between v2 and v3 top 5
$topV2Names = array_map(fn($r) => $r['_filename'], $topOriginal);
$topV3Names = array_map(fn($r) => $r['_filename'], $topOrigByV3);
$v3OnlyNames = array_diff($topV3Names, $topV2Names);
$v2OnlyNames = array_diff($topV2Names, $topV3Names);
$topOverlap = count($topV2Names) - count($v2OnlyNames);

$dimensions = ['centrality', 'strength', 'correctness', 'clarity', 'dead_weight', 'single_issue', 'overall'];
?>

<h1>Worldview primer experiment</h1>

<p>Does providing organisational context improve AI-generated critiques? This experiment tests the <a href="critique-prompt-experiment.php">critique prompts</a> with Forethought's worldview primer included as additional context.</p>

<?php include 'content/worldview-primer/intro.php'; ?>

<!-- =================================================================== -->
<!-- COMPARISON TABLE -->
<!-- =================================================================== -->

<h2 id="comparison">Score comparison</h2>

<?php if (count($wvResults) === 0): ?>
<p><em>No worldview primer results found yet. Run the experiment first.</em></p>
<?php else: ?>

<p>Side-by-side average ACORN scores for each prompt on "<?= htmlspecialchars($paperName) ?>". The "Delta" column shows the change from original to worldview-primer version.</p>

<table>
    <thead>
        <tr>
            <th>Prompt</th>
            <th>Condition</th>
            <th>N</th>
            <th>Centrality</th>
            <th>Strength</th>
            <th class="col-hidden">Correctness</th>
            <th class="col-hidden">Clarity</th>
            <th>Overall</th>
            <th>Std dev</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($testedPrompts as $variant): ?>
        <?php
        $wvAvg = $wvAverages[$variant] ?? null;
        $origAvg = $origAverages[$variant] ?? null;
        $wvN = count($wvByVariant[$variant] ?? []);
        $origN = count($origByVariant[$variant] ?? []);
        ?>
        <?php if ($origAvg): ?>
        <tr>
            <td rowspan="3"><strong><?= htmlspecialchars($variant) ?></strong></td>
            <td>Original</td>
            <td><?= $origN ?></td>
            <td class="font-mono <?= scoreClass($origAvg['centrality']) ?>"><?= number_format($origAvg['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($origAvg['strength']) ?>"><?= number_format($origAvg['strength'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($origAvg['correctness']) ?>"><?= number_format($origAvg['correctness'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($origAvg['clarity']) ?>"><?= number_format($origAvg['clarity'], 2) ?></td>
            <td class="font-mono <?= scoreClass($origAvg['overall']) ?>"><?= number_format($origAvg['overall'], 2) ?></td>
            <td class="font-mono"><?= number_format($origStdDevs[$variant] ?? 0, 2) ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($wvAvg): ?>
        <tr<?= !$origAvg ? '' : '' ?>>
            <?php if (!$origAvg): ?><td rowspan="2"><strong><?= htmlspecialchars($variant) ?></strong></td><?php endif; ?>
            <td>With primer</td>
            <td><?= $wvN ?></td>
            <td class="font-mono <?= scoreClass($wvAvg['centrality']) ?>"><?= number_format($wvAvg['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($wvAvg['strength']) ?>"><?= number_format($wvAvg['strength'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($wvAvg['correctness']) ?>"><?= number_format($wvAvg['correctness'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($wvAvg['clarity']) ?>"><?= number_format($wvAvg['clarity'], 2) ?></td>
            <td class="font-mono <?= scoreClass($wvAvg['overall']) ?>"><?= number_format($wvAvg['overall'], 2) ?></td>
            <td class="font-mono"><?= number_format($wvStdDevs[$variant] ?? 0, 2) ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($origAvg && $wvAvg): ?>
        <?php
        $deltaCentrality = $wvAvg['centrality'] - $origAvg['centrality'];
        $deltaStrength = $wvAvg['strength'] - $origAvg['strength'];
        $deltaCorrectness = $wvAvg['correctness'] - $origAvg['correctness'];
        $deltaClarity = $wvAvg['clarity'] - $origAvg['clarity'];
        $deltaOverall = $wvAvg['overall'] - $origAvg['overall'];
        $deltaClass = function($d) { return $d > 0.02 ? 'score-high' : ($d < -0.02 ? 'score-low' : ''); };
        $deltaFmt = function($d) { return ($d >= 0 ? '+' : '') . number_format($d, 2); };
        ?>
        <tr class="delta-row">
            <td><em>Delta</em></td>
            <td></td>
            <td class="font-mono <?= $deltaClass($deltaCentrality) ?>"><?= $deltaFmt($deltaCentrality) ?></td>
            <td class="font-mono <?= $deltaClass($deltaStrength) ?>"><?= $deltaFmt($deltaStrength) ?></td>
            <td class="font-mono col-hidden <?= $deltaClass($deltaCorrectness) ?>"><?= $deltaFmt($deltaCorrectness) ?></td>
            <td class="font-mono col-hidden <?= $deltaClass($deltaClarity) ?>"><?= $deltaFmt($deltaClarity) ?></td>
            <td class="font-mono <?= $deltaClass($deltaOverall) ?>"><strong><?= $deltaFmt($deltaOverall) ?></strong></td>
            <td></td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($wvOverall && $origOverall): ?>
        <?php
        $deltaCentrality = $wvOverall['centrality'] - $origOverall['centrality'];
        $deltaStrength = $wvOverall['strength'] - $origOverall['strength'];
        $deltaCorrectness = $wvOverall['correctness'] - $origOverall['correctness'];
        $deltaClarity = $wvOverall['clarity'] - $origOverall['clarity'];
        $deltaOverall = $wvOverall['overall'] - $origOverall['overall'];
        $wvOverallStd = calculateStdDev($wvResults, $wvOverall['overall']);
        $origOverallStd = calculateStdDev($origResults, $origOverall['overall']);
        ?>
        <tr style="border-top: 2px solid #333;">
            <td rowspan="3"><strong>All prompts</strong></td>
            <td>Original</td>
            <td><?= count($origResults) ?></td>
            <td class="font-mono <?= scoreClass($origOverall['centrality']) ?>"><?= number_format($origOverall['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($origOverall['strength']) ?>"><?= number_format($origOverall['strength'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($origOverall['correctness']) ?>"><?= number_format($origOverall['correctness'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($origOverall['clarity']) ?>"><?= number_format($origOverall['clarity'], 2) ?></td>
            <td class="font-mono <?= scoreClass($origOverall['overall']) ?>"><?= number_format($origOverall['overall'], 2) ?></td>
            <td class="font-mono"><?= number_format($origOverallStd, 2) ?></td>
        </tr>
        <tr>
            <td>With primer</td>
            <td><?= count($wvResults) ?></td>
            <td class="font-mono <?= scoreClass($wvOverall['centrality']) ?>"><?= number_format($wvOverall['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($wvOverall['strength']) ?>"><?= number_format($wvOverall['strength'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($wvOverall['correctness']) ?>"><?= number_format($wvOverall['correctness'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($wvOverall['clarity']) ?>"><?= number_format($wvOverall['clarity'], 2) ?></td>
            <td class="font-mono <?= scoreClass($wvOverall['overall']) ?>"><?= number_format($wvOverall['overall'], 2) ?></td>
            <td class="font-mono"><?= number_format($wvOverallStd, 2) ?></td>
        </tr>
        <tr class="delta-row">
            <td><em>Delta</em></td>
            <td></td>
            <td class="font-mono <?= $deltaClass($deltaCentrality) ?>"><?= $deltaFmt($deltaCentrality) ?></td>
            <td class="font-mono <?= $deltaClass($deltaStrength) ?>"><?= $deltaFmt($deltaStrength) ?></td>
            <td class="font-mono col-hidden <?= $deltaClass($deltaCorrectness) ?>"><?= $deltaFmt($deltaCorrectness) ?></td>
            <td class="font-mono col-hidden <?= $deltaClass($deltaClarity) ?>"><?= $deltaFmt($deltaClarity) ?></td>
            <td class="font-mono <?= $deltaClass($deltaOverall) ?>"><strong><?= $deltaFmt($deltaOverall) ?></strong></td>
            <td></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php endif; ?>

<!-- =================================================================== -->
<!-- ACORN v3: RELEVANCE-AWARE GRADING -->
<!-- =================================================================== -->

<?php if (count($v3OrigResults) > 0 || count($v3WvResults) > 0): ?>

<h2 id="v3-comparison">Relevance-aware grading (ACORN v3)</h2>

<p>The v2 grader measures generic argument quality. The v3 grader adds a <strong>relevance</strong> dimension that scores how well a critique targets questions Forethought actually cares about, using the <a href="#" onclick="document.getElementById('primer-details').open=true;return false;">worldview primer's</a> Tier 1/2/3 framework. Both conditions are graded with relevance awareness&mdash;the question is whether primer critiques score higher on relevance, and whether this reverses the v2 finding.</p>

<table>
    <thead>
        <tr>
            <th>Prompt</th>
            <th>Condition</th>
            <th>N</th>
            <th>Centrality</th>
            <th>Strength</th>
            <th>Relevance</th>
            <th>Overall (v3)</th>
            <th>Overall (v2)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($testedPrompts as $variant): ?>
        <?php
        $v3Orig = $v3OrigAverages[$variant] ?? null;
        $v3Wv = $v3WvAverages[$variant] ?? null;
        $v2Orig = $origAverages[$variant] ?? null;
        $v2Wv = $wvAverages[$variant] ?? null;
        $v3OrigN = count($v3OrigByVariant[$variant] ?? []);
        $v3WvN = count($v3WvByVariant[$variant] ?? []);
        ?>
        <?php if ($v3Orig): ?>
        <tr>
            <td rowspan="<?= $v3Wv ? '3' : '1' ?>"><strong><?= htmlspecialchars($variant) ?></strong></td>
            <td>Original</td>
            <td><?= $v3OrigN ?></td>
            <td class="font-mono <?= scoreClass($v3Orig['centrality']) ?>"><?= number_format($v3Orig['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($v3Orig['strength']) ?>"><?= number_format($v3Orig['strength'], 2) ?></td>
            <td class="font-mono <?= scoreClass($v3Orig['relevance'] ?? 0) ?>"><?= number_format($v3Orig['relevance'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($v3Orig['overall']) ?>"><?= number_format($v3Orig['overall'], 2) ?></td>
            <td class="font-mono" style="opacity:0.6"><?= $v2Orig ? number_format($v2Orig['overall'], 2) : '&mdash;' ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($v3Wv): ?>
        <tr>
            <?php if (!$v3Orig): ?><td rowspan="2"><strong><?= htmlspecialchars($variant) ?></strong></td><?php endif; ?>
            <td>With primer</td>
            <td><?= $v3WvN ?></td>
            <td class="font-mono <?= scoreClass($v3Wv['centrality']) ?>"><?= number_format($v3Wv['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($v3Wv['strength']) ?>"><?= number_format($v3Wv['strength'], 2) ?></td>
            <td class="font-mono <?= scoreClass($v3Wv['relevance'] ?? 0) ?>"><?= number_format($v3Wv['relevance'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($v3Wv['overall']) ?>"><?= number_format($v3Wv['overall'], 2) ?></td>
            <td class="font-mono" style="opacity:0.6"><?= $v2Wv ? number_format($v2Wv['overall'], 2) : '&mdash;' ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($v3Orig && $v3Wv): ?>
        <?php
        $dCentrality = $v3Wv['centrality'] - $v3Orig['centrality'];
        $dStrength = $v3Wv['strength'] - $v3Orig['strength'];
        $dRelevance = ($v3Wv['relevance'] ?? 0) - ($v3Orig['relevance'] ?? 0);
        $dOverall = $v3Wv['overall'] - $v3Orig['overall'];
        $dClass = function($d) { return $d > 0.02 ? 'score-high' : ($d < -0.02 ? 'score-low' : ''); };
        $dFmt = function($d) { return ($d >= 0 ? '+' : '') . number_format($d, 2); };
        ?>
        <tr class="delta-row">
            <td><em>Delta</em></td>
            <td></td>
            <td class="font-mono <?= $dClass($dCentrality) ?>"><?= $dFmt($dCentrality) ?></td>
            <td class="font-mono <?= $dClass($dStrength) ?>"><?= $dFmt($dStrength) ?></td>
            <td class="font-mono <?= $dClass($dRelevance) ?>"><?= $dFmt($dRelevance) ?></td>
            <td class="font-mono <?= $dClass($dOverall) ?>"><strong><?= $dFmt($dOverall) ?></strong></td>
            <td></td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($v3OrigOverall && $v3WvOverall): ?>
        <?php
        $dCentrality = $v3WvOverall['centrality'] - $v3OrigOverall['centrality'];
        $dStrength = $v3WvOverall['strength'] - $v3OrigOverall['strength'];
        $dRelevance = ($v3WvOverall['relevance'] ?? 0) - ($v3OrigOverall['relevance'] ?? 0);
        $dOverall = $v3WvOverall['overall'] - $v3OrigOverall['overall'];
        ?>
        <tr style="border-top: 2px solid #333;">
            <td rowspan="3"><strong>All prompts</strong></td>
            <td>Original</td>
            <td><?= count($v3OrigResults) ?></td>
            <td class="font-mono <?= scoreClass($v3OrigOverall['centrality']) ?>"><?= number_format($v3OrigOverall['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($v3OrigOverall['strength']) ?>"><?= number_format($v3OrigOverall['strength'], 2) ?></td>
            <td class="font-mono <?= scoreClass($v3OrigOverall['relevance'] ?? 0) ?>"><?= number_format($v3OrigOverall['relevance'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($v3OrigOverall['overall']) ?>"><?= number_format($v3OrigOverall['overall'], 2) ?></td>
            <td class="font-mono" style="opacity:0.6"><?= $origOverall ? number_format($origOverall['overall'], 2) : '&mdash;' ?></td>
        </tr>
        <tr>
            <td>With primer</td>
            <td><?= count($v3WvResults) ?></td>
            <td class="font-mono <?= scoreClass($v3WvOverall['centrality']) ?>"><?= number_format($v3WvOverall['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($v3WvOverall['strength']) ?>"><?= number_format($v3WvOverall['strength'], 2) ?></td>
            <td class="font-mono <?= scoreClass($v3WvOverall['relevance'] ?? 0) ?>"><?= number_format($v3WvOverall['relevance'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($v3WvOverall['overall']) ?>"><?= number_format($v3WvOverall['overall'], 2) ?></td>
            <td class="font-mono" style="opacity:0.6"><?= $wvOverall ? number_format($wvOverall['overall'], 2) : '&mdash;' ?></td>
        </tr>
        <tr class="delta-row">
            <td><em>Delta</em></td>
            <td></td>
            <td class="font-mono <?= $dClass($dCentrality) ?>"><?= $dFmt($dCentrality) ?></td>
            <td class="font-mono <?= $dClass($dStrength) ?>"><?= $dFmt($dStrength) ?></td>
            <td class="font-mono <?= $dClass($dRelevance) ?>"><?= $dFmt($dRelevance) ?></td>
            <td class="font-mono <?= $dClass($dOverall) ?>"><strong><?= $dFmt($dOverall) ?></strong></td>
            <td></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php endif; ?>

<!-- =================================================================== -->
<!-- V3 RERANKING COMPARISON -->
<!-- =================================================================== -->

<?php if (count($topOrigByV3) > 0): ?>

<h2 id="v3-reranking">Does the relevance-aware grader surface better critiques?</h2>

<p>Even though the v3 grader's averages barely differ from v2, it may still rerank individual critiques in useful ways. Below we compare the top 5 original critiques as ranked by v2 (generic quality) vs v3 (relevance-aware). <?= $topOverlap ?> of 5 critiques appear in both lists<?= $topOverlap < 5 ? '&mdash;the v3 grader promotes ' . count($v3OnlyNames) . ' different critiques into the top 5' : '' ?>.</p>

<h3 id="top-v2">Top 5 by ACORN v2 (generic quality)</h3>

<?php foreach ($topOriginal as $result): ?>
<?php
$parsed = $result['_parsed'];
$critiqueFile = "$origParsedDir/{$result['_filename']}.txt";
$critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
$v3Data = $v3OrigByFilename[$result['_filename']] ?? null;
$isShared = !in_array($result['_filename'], $v2OnlyNames);
?>
<details class="critique-card">
    <summary>
        <span><?= htmlspecialchars($result['title'] ?? "#{$parsed['number']}") ?></span>
        <span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars(shortVariantName($parsed['variant_display'])) ?></span>
            <span class="font-mono <?= scoreClass($result['overall']) ?>"><?= number_format($result['overall'], 2) ?></span>
            <?php if ($isShared): ?><span class="badge" style="background: #d4edda; margin-left: 0.5rem;" title="Also in v3 top 5">v2+v3</span><?php endif; ?>
            <?php if (!$isShared): ?><span class="badge" style="background: #fff3cd; margin-left: 0.5rem;" title="Only in v2 top 5">v2 only</span><?php endif; ?>
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
                <div class="label">Overall (v2)</div>
                <div class="value <?= scoreClass($result['overall'] ?? 0) ?>"><?= number_format($result['overall'] ?? 0, 2) ?></div>
            </div>
        </div>
        <?php if ($v3Data): ?>
        <div class="score-grid" style="margin-top: 0.5rem;">
            <div class="score-box">
                <div class="label">v3 centrality</div>
                <div class="value <?= scoreClass($v3Data['centrality'] ?? 0) ?>"><?= number_format($v3Data['centrality'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">v3 strength</div>
                <div class="value <?= scoreClass($v3Data['strength'] ?? 0) ?>"><?= number_format($v3Data['strength'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">v3 relevance</div>
                <div class="value <?= scoreClass($v3Data['relevance'] ?? 0) ?>"><?= number_format($v3Data['relevance'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box overall">
                <div class="label">Overall (v3)</div>
                <div class="value <?= scoreClass($v3Data['overall'] ?? 0) ?>"><?= number_format($v3Data['overall'] ?? 0, 2) ?></div>
            </div>
        </div>
        <?php endif; ?>
        <div class="two-column">
            <div class="column">
                <div class="column-header">
                    <h4>Critique</h4>
                    <span class="prompt-label"><?= htmlspecialchars($parsed['variant_display']) ?></span>
                </div>
                <div class="critique-text"><?= parseMarkdownInline($critiqueText) ?></div>
            </div>
            <div class="column">
                <h4>Grader reasoning (v2)</h4>
                <p class="reasoning"><?= htmlspecialchars($result['reasoning'] ?? 'No reasoning provided.') ?></p>
                <?php if ($v3Data): ?>
                <h4>Grader reasoning (v3)</h4>
                <p class="reasoning"><?= htmlspecialchars($v3Data['reasoning'] ?? 'No reasoning provided.') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</details>
<?php endforeach; ?>

<h3 id="top-v3">Top 5 by ACORN v3 (relevance-aware)</h3>

<?php foreach ($topOrigByV3 as $result): ?>
<?php
$parsed = $result['_parsed'];
$critiqueFile = "$origParsedDir/{$result['_filename']}.txt";
$critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
$v3Data = $result['_v3'];
$isShared = !in_array($result['_filename'], $v3OnlyNames);
?>
<details class="critique-card">
    <summary>
        <span><?= htmlspecialchars($result['title'] ?? "#{$parsed['number']}") ?></span>
        <span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars(shortVariantName($parsed['variant_display'])) ?></span>
            <span class="font-mono <?= scoreClass($v3Data['overall']) ?>"><?= number_format($v3Data['overall'], 2) ?></span>
            <?php if ($isShared): ?><span class="badge" style="background: #d4edda; margin-left: 0.5rem;" title="Also in v2 top 5">v2+v3</span><?php endif; ?>
            <?php if (!$isShared): ?><span class="badge" style="background: #cce5ff; margin-left: 0.5rem;" title="New in v3 top 5">v3 only</span><?php endif; ?>
        </span>
    </summary>
    <div class="content">
        <div class="score-grid">
            <div class="score-box">
                <div class="label">v3 centrality</div>
                <div class="value <?= scoreClass($v3Data['centrality'] ?? 0) ?>"><?= number_format($v3Data['centrality'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">v3 strength</div>
                <div class="value <?= scoreClass($v3Data['strength'] ?? 0) ?>"><?= number_format($v3Data['strength'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">v3 relevance</div>
                <div class="value <?= scoreClass($v3Data['relevance'] ?? 0) ?>"><?= number_format($v3Data['relevance'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box overall">
                <div class="label">Overall (v3)</div>
                <div class="value <?= scoreClass($v3Data['overall'] ?? 0) ?>"><?= number_format($v3Data['overall'] ?? 0, 2) ?></div>
            </div>
        </div>
        <div class="score-grid" style="margin-top: 0.5rem;">
            <div class="score-box">
                <div class="label">v2 centrality</div>
                <div class="value <?= scoreClass($result['centrality'] ?? 0) ?>"><?= number_format($result['centrality'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">v2 strength</div>
                <div class="value <?= scoreClass($result['strength'] ?? 0) ?>"><?= number_format($result['strength'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">v2 correctness</div>
                <div class="value <?= scoreClass($result['correctness'] ?? 0) ?>"><?= number_format($result['correctness'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box overall">
                <div class="label">Overall (v2)</div>
                <div class="value <?= scoreClass($result['overall'] ?? 0) ?>"><?= number_format($result['overall'] ?? 0, 2) ?></div>
            </div>
        </div>
        <div class="two-column">
            <div class="column">
                <div class="column-header">
                    <h4>Critique</h4>
                    <span class="prompt-label"><?= htmlspecialchars($parsed['variant_display']) ?></span>
                </div>
                <div class="critique-text"><?= parseMarkdownInline($critiqueText) ?></div>
            </div>
            <div class="column">
                <h4>Grader reasoning (v3)</h4>
                <p class="reasoning"><?= htmlspecialchars($v3Data['reasoning'] ?? 'No reasoning provided.') ?></p>
                <h4>Grader reasoning (v2)</h4>
                <p class="reasoning"><?= htmlspecialchars($result['reasoning'] ?? 'No reasoning provided.') ?></p>
            </div>
        </div>
    </div>
</details>
<?php endforeach; ?>

<?php endif; ?>

<!-- =================================================================== -->
<!-- EXAMPLE CRITIQUES (v2 top 5 by condition) -->
<!-- =================================================================== -->

<?php if (count($wvResults) > 0 && count($origResults) > 0): ?>

<h2 id="examples">Example critiques by condition (ACORN v2)</h2>

<p>The 5 highest-scoring critiques from each condition by the v2 grader, to give a feel for the qualitative difference the primer makes.</p>

<h3 id="top-original">Top 5 without primer</h3>

<?php foreach ($topOriginal as $result): ?>
<?php
$parsed = $result['_parsed'];
$critiqueFile = "$origParsedDir/{$result['_filename']}.txt";
$critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
?>
<details class="critique-card">
    <summary>
        <span><?= htmlspecialchars($result['title'] ?? "#{$parsed['number']}") ?></span>
        <span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars(shortVariantName($parsed['variant_display'])) ?></span>
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
                <div class="column-header">
                    <h4>Critique</h4>
                    <span class="prompt-label"><?= htmlspecialchars($parsed['variant_display']) ?></span>
                </div>
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

<h3 id="top-primer">Top 5 with primer</h3>

<?php foreach ($topWorldview as $result): ?>
<?php
$parsed = $result['_parsed'];
$critiqueFile = "$wvParsedDir/{$result['_filename']}.txt";
$critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
?>
<details class="critique-card">
    <summary>
        <span><?= htmlspecialchars($result['title'] ?? "#{$parsed['number']}") ?></span>
        <span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars(shortVariantName($parsed['variant_display'])) ?> + Primer</span>
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
                <div class="column-header">
                    <h4>Critique</h4>
                    <span class="prompt-label"><?= htmlspecialchars($parsed['variant_display']) ?> + Primer</span>
                </div>
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

<?php endif; ?>

<!-- =================================================================== -->
<!-- ALL WORLDVIEW CRITIQUES -->
<!-- =================================================================== -->

<h2 id="all-critiques">All worldview primer critiques</h2>

<?php if (count($wvResults) === 0): ?>
<p><em>No worldview primer results found yet.</em></p>
<?php else: ?>

<p>All <?= count($wvResults) ?> critiques generated with the worldview primer, sorted by overall score.</p>

<div class="filter-bar">
    <div class="filter-group">
        <span class="filter-label">Prompt</span>
        <div class="filter-pills" id="wv-variant-filter">
            <button class="pill active" data-value="all">All</button>
            <?php foreach ($testedPrompts as $variant): ?>
            <button class="pill" data-value="<?= htmlspecialchars($variant) ?>"><?= htmlspecialchars(shortVariantName($variant)) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="filter-count">
        Showing <span id="wv-visible-count"><?= count($wvResults) ?></span> of <?= count($wvResults) ?>
    </div>
</div>

<div id="wv-critique-list">
<?php foreach ($wvResults as $idx => $result): ?>
<?php
$parsed = $result['_parsed'];
$critiqueFile = "$wvParsedDir/{$result['_filename']}.txt";
$critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
$hiddenClass = $idx >= 3 ? ' all-hidden' : '';
?>
<details class="critique-item<?= $hiddenClass ?>" id="wv-critique-<?= htmlspecialchars($result['_filename']) ?>" data-variant="<?= htmlspecialchars($parsed['variant_display']) ?>">
    <summary>
        <span>
            <?= htmlspecialchars($result['title'] ?? "#{$parsed['number']}") ?>
        </span>
        <span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars(shortVariantName($parsed['variant_display'])) ?></span>
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
                <div class="column-header">
                    <h4>Critique</h4>
                    <span class="prompt-label"><?= htmlspecialchars($parsed['variant_display']) ?> + Primer</span>
                </div>
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
<?php if (count($wvResults) > 3): ?>
<button class="show-all-btn" id="show-all-wv-critiques">Show all <?= count($wvResults) ?> critiques</button>
<?php endif; ?>

<?php endif; ?>

<script>
// Filter functionality for worldview critiques
(function() {
    var variantFilter = document.getElementById('wv-variant-filter');
    if (!variantFilter) return;

    var pills = variantFilter.querySelectorAll('.pill');
    var items = document.querySelectorAll('#wv-critique-list .critique-item');
    var countEl = document.getElementById('wv-visible-count');

    function updateFilters() {
        var activeVariant = variantFilter.querySelector('.pill.active');
        var variant = activeVariant ? activeVariant.getAttribute('data-value') : 'all';
        var visible = 0;

        items.forEach(function(item) {
            var itemVariant = item.getAttribute('data-variant');
            var show = (variant === 'all' || itemVariant === variant);
            item.style.display = show ? '' : 'none';
            item.classList.remove('all-hidden');
            if (show) visible++;
        });

        if (countEl) countEl.textContent = visible;

        // Hide show-all button when filtering
        var showAllBtn = document.getElementById('show-all-wv-critiques');
        if (showAllBtn) {
            showAllBtn.style.display = variant === 'all' ? '' : 'none';
        }
    }

    pills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            pills.forEach(function(p) { p.classList.remove('active'); });
            pill.classList.add('active');
            updateFilters();
        });
    });

    // Show all button
    var showAllBtn = document.getElementById('show-all-wv-critiques');
    if (showAllBtn) {
        showAllBtn.addEventListener('click', function() {
            items.forEach(function(item) {
                item.classList.remove('all-hidden');
            });
            showAllBtn.style.display = 'none';
        });
    }
})();
</script>

<?php include 'includes/footer.php'; ?>
