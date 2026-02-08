<?php
$pageTitle = 'Iterating on prompts with ACORN feedback';
include 'includes/header.php';
include 'includes/functions.php';
include 'includes/components/critique-card.php';
include 'includes/components/paper-examples.php';

// =============================================================================
// DATA LOADING
// =============================================================================

// Load experiment results from all three papers (GPT 5.2 Pro only for main report)
$resultsDirs = [
    'no-easy-eutopia' => __DIR__ . '/data/experiments/critique-prompt/results-gpt',
    'compute-bottlenecks' => __DIR__ . '/data/experiments/critique-prompt/results-gpt-cb',
    'convergence-compromise' => __DIR__ . '/data/experiments/critique-prompt/results-gpt-cc',
];

$parsedDirs = [
    'no-easy-eutopia' => __DIR__ . '/data/experiments/critique-prompt/outputs-gpt/parsed',
    'compute-bottlenecks' => __DIR__ . '/data/experiments/critique-prompt/outputs-gpt-cb/parsed',
    'convergence-compromise' => __DIR__ . '/data/experiments/critique-prompt/outputs-gpt-cc/parsed',
];

$paperNames = [
    'no-easy-eutopia' => 'No Easy Eutopia',
    'compute-bottlenecks' => 'Compute Bottlenecks',
    'convergence-compromise' => 'Convergence and Compromise',
];

$paperUrls = [
    'no-easy-eutopia' => 'https://www.forethought.org/research/no-easy-eutopia',
    'compute-bottlenecks' => 'https://www.forethought.org/research/will-compute-bottlenecks-prevent-a-software-intelligence-explosion',
    'convergence-compromise' => 'https://www.forethought.org/research/convergence-and-compromise',
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

// =============================================================================
// FILENAME PARSING
// =============================================================================

// Parse filename to extract prompt variant and paper
function parseFilename($name) {
    // Format: surgery-no-easy-eutopia-01 or gemini-surgery-no-easy-eutopia-01 or gpt-surgery-no-easy-eutopia-01 or baseline-v1-no-easy-eutopia-01
    // Also handles compound names: pivot-attack-no-easy-eutopia-01, authors-tribunal-..., pre-mortem-...
    $parts = explode('-', $name);

    // Check if first part is a model prefix (gemini or gpt)
    $prefix = '';
    if ($parts[0] === 'gemini' || $parts[0] === 'gpt') {
        $prefix = array_shift($parts) . '-';
    }

    $baseVariant = array_shift($parts); // surgery/personas/unforgettable/baseline/pivot/authors/pre

    // Handle compound prompt names (pivot-attack, authors-tribunal, pre-mortem)
    if ($baseVariant === 'pivot' && count($parts) > 0 && $parts[0] === 'attack') {
        $baseVariant = 'pivot-attack';
        array_shift($parts);
    } elseif ($baseVariant === 'authors' && count($parts) > 0 && $parts[0] === 'tribunal') {
        $baseVariant = 'authors-tribunal';
        array_shift($parts);
    } elseif ($baseVariant === 'pre' && count($parts) > 0 && $parts[0] === 'mortem') {
        $baseVariant = 'pre-mortem';
        array_shift($parts);
    }

    // Check for versioned baseline (baseline-v1, baseline-v2)
    $version = '';
    if ($baseVariant === 'baseline' && count($parts) > 0 && preg_match('/^v\d+$/', $parts[0])) {
        $version = '-' . array_shift($parts);
    }

    $variant = $prefix . $baseVariant . $version;

    // Rest is paper name and number
    $number = array_pop($parts);
    $paper = implode('-', $parts);

    // Create display name for variant
    $variantDisplay = ucfirst($baseVariant);
    // Handle compound names with proper capitalization
    if ($baseVariant === 'pivot-attack') {
        $variantDisplay = 'Pivot-attack';
    } elseif ($baseVariant === 'authors-tribunal') {
        $variantDisplay = 'Authors-tribunal';
    } elseif ($baseVariant === 'pre-mortem') {
        $variantDisplay = 'Pre-mortem';
    } elseif ($baseVariant === 'baseline' && $version === '-v2') {
        $variantDisplay = 'November';
    } elseif ($baseVariant === 'baseline' && $version === '-v1') {
        $variantDisplay = 'November v1 (excluded)';  // Will be filtered out
    } elseif ($baseVariant === 'baseline') {
        $variantDisplay = 'November (legacy)';  // Will be filtered out
    }

    if ($prefix === 'gemini-') {
        $variantDisplay = 'Gemini ' . $variantDisplay;
    } elseif ($prefix === 'gpt-') {
        $variantDisplay = 'GPT ' . $variantDisplay;
    }

    return [
        'variant' => $variant,
        'variant_display' => $variantDisplay,
        'base_variant' => $baseVariant . $version,
        'paper' => $paper,
        'number' => intval($number),
        'paper_display' => ucwords(str_replace('-', ' ', $paper))
    ];
}

// =============================================================================
// GROUP AND CALCULATE STATISTICS
// =============================================================================

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

// =============================================================================
// PROMPT TEXT UTILITIES
// =============================================================================

// Strip output format instructions from prompt text for cleaner display
function stripOutputFormat($prompt) {
    // Pattern 1: Remove "### Output format" section (structured prompts)
    // Matches from "### Output format" to either "---" or the next "###" or end
    $prompt = preg_replace('/### Output format\s*\n[\s\S]*?(?=\n---|\n###|$)/', '', $prompt);

    // Pattern 2: Remove single-line output instruction (conversational prompt)
    // Matches lines starting with "Output {{num_critiques}}"
    $prompt = preg_replace('/^Output \{\{num_critiques\}\}[^\n]*\n*/m', '', $prompt);

    // Clean up any resulting double blank lines
    $prompt = preg_replace('/\n{3,}/', "\n\n", $prompt);

    return trim($prompt);
}

// Check if a variant is a baseline variant (only the current baseline, not legacy versions)
function isBaselineVariant($variant) {
    return $variant === 'November';
}

$selectedVariant = $_GET['variant'] ?? 'all';
$selectedPaper = $_GET['paper'] ?? 'all';

// =============================================================================
// LOAD PROMPT TEXTS
// =============================================================================

$promptsPath = __DIR__ . '/data/experiments/critique-prompt/prompts';
$baselineV1Prompt = file_exists("$promptsPath/baseline-v1-parameterised.md") ? file_get_contents("$promptsPath/baseline-v1-parameterised.md") : '';
$baselineV2Prompt = file_exists("$promptsPath/baseline-v2-parameterised.md") ? file_get_contents("$promptsPath/baseline-v2-parameterised.md") : '';
$surgeryPrompt = file_exists("$promptsPath/surgery-parameterised.md") ? file_get_contents("$promptsPath/surgery-parameterised.md") : '';
$personasPrompt = file_exists("$promptsPath/personas-parameterised.md") ? file_get_contents("$promptsPath/personas-parameterised.md") : '';
$unforgettablePrompt = file_exists("$promptsPath/unforgettable-parameterised.md") ? file_get_contents("$promptsPath/unforgettable-parameterised.md") : '';
$pivotAttackPrompt = file_exists("$promptsPath/pivot-attack-parameterised.md") ? file_get_contents("$promptsPath/pivot-attack-parameterised.md") : '';
$authorsTribunalPrompt = file_exists("$promptsPath/authors-tribunal-parameterised.md") ? file_get_contents("$promptsPath/authors-tribunal-parameterised.md") : '';
$preMortemPrompt = file_exists("$promptsPath/pre-mortem-parameterised.md") ? file_get_contents("$promptsPath/pre-mortem-parameterised.md") : '';
$conversationalPrompt = file_exists("$promptsPath/conversational-parameterised.md") ? file_get_contents("$promptsPath/conversational-parameterised.md") : '';

// Load grader prompt (ACORN v2 rubric that was actually used)
$graderPath = __DIR__ . '/data/experiments/full-critique-chain-acorn/prompts/grader-v2-acorn-rubric.txt';
$graderPrompt = file_exists($graderPath) ? file_get_contents($graderPath) : '';

// =============================================================================
// BASE VARIANT STATISTICS
// =============================================================================

// Define base variants (excluding Gemini/GPT refinements)
$baseVariants = ['Conversational', 'November', 'Surgery', 'Personas', 'Unforgettable', 'Pivot-attack', 'Authors-tribunal', 'Pre-mortem'];
$isGeminiOrGpt = fn($v) => strpos($v, 'Gemini ') === 0 || strpos($v, 'GPT ') === 0;

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

// Separate base and refined variant averages
$baseVariantAverages = array_filter($variantAverages, fn($avgs, $v) => in_array($v, $baseVariants), ARRAY_FILTER_USE_BOTH);
$refinedVariantAverages = array_filter($variantAverages, fn($avgs, $v) => $isGeminiOrGpt($v), ARRAY_FILTER_USE_BOTH);

// Sort base variants by overall score
uasort($baseVariantAverages, fn($a, $b) => $b['overall'] <=> $a['overall']);
$winningBaseVariant = array_key_first($baseVariantAverages);

// Count base critiques only
$baseCritiqueCount = 0;
foreach ($baseVariants as $v) {
    $baseCritiqueCount += count($baseByVariant[$v] ?? []);
}

// Calculate base paper averages
$basePaperAverages = [];
foreach ($baseByPaper as $paperKey => $paperResults) {
    $basePaperAverages[$paperKey] = calculateAverages($paperResults);
}
uasort($basePaperAverages, fn($a, $b) => $b['overall'] <=> $a['overall']);

// =============================================================================
// DUPLICATE CLUSTER DATA (for unique/duplicate filtering)
// =============================================================================

$duplicatesDir = __DIR__ . '/data/experiments/critique-prompt';
$uniqueRepresentatives = []; // filename => true for critiques to show in "unique" mode
$convFoundIdea = []; // filename => true if conversational also generated this idea

foreach ($paperNames as $dupPaperKey => $dupPaperName) {
    $dupJsonPath = "$duplicatesDir/duplicates-$dupPaperKey.json";
    if (!file_exists($dupJsonPath)) continue;
    $dupData = json_decode(file_get_contents($dupJsonPath), true);
    if (!$dupData) continue;

    // Standalone uniques are all representatives
    foreach ($dupData['unique'] ?? [] as $fn) {
        $uniqueRepresentatives[$fn] = true;
        $convFoundIdea[$fn] = strpos($fn, 'conversational-') === 0;
    }

    // For each cluster, pick the top-scoring critique as representative
    // and track whether conversational contributed to the cluster
    foreach ($dupData['clusters'] ?? [] as $cluster) {
        $bestScore = -1;
        $bestFilename = null;
        $hasConv = false;
        foreach ($cluster['critiques'] as $fn) {
            if (strpos($fn, 'conversational-') === 0) {
                $hasConv = true;
            }
            // Look up score from results
            foreach ($baseByPaper[$dupPaperKey] ?? [] as $r) {
                if ($r['_filename'] === $fn && ($r['overall'] ?? 0) > $bestScore) {
                    $bestScore = $r['overall'];
                    $bestFilename = $fn;
                }
            }
        }
        if ($bestFilename) {
            $uniqueRepresentatives[$bestFilename] = true;
        }
        foreach ($cluster['critiques'] as $fn) {
            $convFoundIdea[$fn] = $hasConv;
        }
    }
}

// =============================================================================
// TOP N CRITIQUES CALCULATIONS
// =============================================================================

$topNCutoff = 50;
$allBaseCritiques = [];
foreach ($baseByVariant as $variant => $variantResults) {
    foreach ($variantResults as $r) {
        $allBaseCritiques[] = $r;
    }
}
usort($allBaseCritiques, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Top 50
$topN = array_slice($allBaseCritiques, 0, $topNCutoff);

$topNByPrompt = [];
foreach ($baseVariants as $v) {
    $topNByPrompt[$v] = 0;
}
foreach ($topN as $critique) {
    $variant = $critique['_parsed']['variant_display'] ?? '';
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

// =============================================================================
// PER-PAPER CRITIQUE DATA
// =============================================================================

// Group base critiques by paper
$baseCritiquesByPaper = [];
foreach ($paperNames as $paperKey => $paperName) {
    $baseCritiquesByPaper[$paperKey] = [];
}
foreach ($baseByVariant as $variant => $variantResults) {
    foreach ($variantResults as $r) {
        $paperKey = $r['_paper_key'];
        $baseCritiquesByPaper[$paperKey][] = $r;
    }
}

// Sort each paper's critiques by overall score
foreach ($baseCritiquesByPaper as $paperKey => &$paperCritiques) {
    usort($paperCritiques, fn($a, $b) => $b['overall'] <=> $a['overall']);
}
unset($paperCritiques);

// =============================================================================
// CURATED UNIQUE CRITIQUES (manually selected to avoid duplicates)
// =============================================================================

$topUniqueIds = [
    'no-easy-eutopia' => [
        'unforgettable-no-easy-eutopia-07',
        'unforgettable-no-easy-eutopia-05',
        'authors-tribunal-no-easy-eutopia-01',
    ],
    'compute-bottlenecks' => [
        'authors-tribunal-compute-bottlenecks-02',
        'pivot-attack-compute-bottlenecks-10',
        'personas-compute-bottlenecks-09',
    ],
    'convergence-compromise' => [
        'pre-mortem-convergence-and-compromise-05',
        'pivot-attack-convergence-and-compromise-03',
        'pivot-attack-convergence-and-compromise-10',
    ],
];

$bottomUniqueIds = [
    'no-easy-eutopia' => [
        'personas-no-easy-eutopia-09',
        'personas-no-easy-eutopia-07',
        'surgery-no-easy-eutopia-03',
    ],
    'compute-bottlenecks' => [
        'pre-mortem-compute-bottlenecks-10',
        'baseline-v2-compute-bottlenecks-01',
        'personas-compute-bottlenecks-06',
    ],
    'convergence-compromise' => [
        'baseline-v2-convergence-and-compromise-02',
        'baseline-v2-convergence-and-compromise-07',
        'pre-mortem-convergence-and-compromise-09',
    ],
];

// =============================================================================
// HELPER: GET CRITIQUES BY IDS
// =============================================================================

function getCritiquesByIds($ids, $critiques) {
    $result = [];
    foreach ($ids as $id) {
        foreach ($critiques as $c) {
            if ($c['_filename'] === $id) {
                $result[] = $c;
                break;
            }
        }
    }
    return $result;
}

// =============================================================================
// PREPARE PAPER-SPECIFIC DATA FOR EXAMPLES SECTION
// =============================================================================

$paperExamplesData = [];
foreach ($paperNames as $paperKey => $paperName) {
    // Get top 3 conversational critiques for this paper
    $paperConversationalResults = array_filter($results, function($r) use ($paperKey) {
        return $r['_paper_key'] === $paperKey
            && ($r['_parsed']['variant_display'] ?? '') === 'Conversational';
    });
    $paperConversationalResults = array_values($paperConversationalResults);
    usort($paperConversationalResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
    $topConversational = array_slice($paperConversationalResults, 0, 3);

    // Get all base variant results for this paper
    $paperAllResults = array_filter($results, function($r) use ($paperKey, $baseVariants) {
        return $r['_paper_key'] === $paperKey
            && in_array($r['_parsed']['variant_display'] ?? '', $baseVariants);
    });

    // Get curated unique critiques
    $uniqueCritiques = getCritiquesByIds($topUniqueIds[$paperKey] ?? [], $paperAllResults);
    $bottomUniqueCritiques = getCritiquesByIds($bottomUniqueIds[$paperKey] ?? [], $paperAllResults);

    $paperExamplesData[$paperKey] = [
        'topConversational' => $topConversational,
        'uniqueCritiques' => $uniqueCritiques,
        'bottomUniqueCritiques' => $bottomUniqueCritiques,
    ];
}
?>

<h1>Iterating on prompts with ACORN feedback</h1>

<p>Can the <a href="why-automated-graders.php">ACORN grader</a> help us systematically improve our prompting? I compared eight "critique this paper" prompts to find out.</p>

<?php include 'content/critique-prompt/preamble.php'; ?>

<?php include 'content/critique-prompt/prompts-intro.php'; ?>

<?php include 'content/critique-prompt/results-summary.php'; ?>
<hr style="margin: 6rem 0 3rem 0;">
<h2 id="appendix-1-acorn-grader-ratings">Appendix 1: The ACORN grader ratings</h2>
<h3 id="top-critiques-by-prompt">Top <?= $topNCutoff ?> critiques by prompt</h3>

<p>Which prompts generated the highest-scoring critiques? If we take the top <?= $topNCutoff ?> critiques (out of <?= $baseCritiqueCount ?>), here's how they break down by prompt:</p>

<table class="table-narrow">
    <thead>
        <tr>
            <th>Prompt</th>
            <th>Count in top <?= $topNCutoff ?></th>
            <th>Share</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($topNByPrompt as $variant => $count):
            $share = $count / $topNCutoff * 100;
            $highlightClass = $share > 15 ? ' class="highlight-green"' : '';
        ?>
        <tr<?= $highlightClass ?>>
            <td><strong><?= htmlspecialchars(shortVariantName($variant)) ?></strong></td>
            <td class="font-mono"><?= $count ?></td>
            <td class="font-mono"><?= number_format($share, 0) ?>%</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$topPrompt = array_key_first($topNByPrompt);
$topPromptCount = $topNByPrompt[$topPrompt];
$topPromptName = shortVariantName($topPrompt);
$topPromptPct = number_format($topPromptCount / $topNCutoff * 100, 0);
$novemberCount = $topNByPrompt['November'] ?? 0;
?>
<p>The "<?= htmlspecialchars($topPromptName) ?>" prompt generated <?= $topPromptPct ?>% of the top-scoring critiques (<?= $topPromptCount ?>/<?= $topNCutoff ?>), despite representing only <?= number_format(100/count($baseVariants), 0) ?>% of the total pool. November generated just <?= $novemberCount ?> of the top <?= $topNCutoff ?>.</p>

<h3 id="average-scores-by-prompt">Average scores by prompt</h3>
<p>Each prompt generated 10 critiques of three different papers, for 30 total critiques. Average scores are below:</p>
<table>
    <thead>
        <tr>
            <th>Prompt variant</th>
            <th>N</th>
            <th>Centrality</th>
            <th>Strength</th>
            <th class="col-hidden">Correctness</th>
            <th class="col-hidden">Clarity</th>
            <th class="col-hidden">Dead weight</th>
            <th class="col-hidden">Single issue</th>
            <th>Overall *</th>
            <th>Std dev</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($baseVariantAverages as $variant => $avgs):
            $highlightClass = $avgs['overall'] > 0.3 ? ' class="highlight-green"' : '';
        ?>
        <tr<?= $highlightClass ?>>
            <td>
                <strong><?= htmlspecialchars($variant) ?></strong>
            </td>
            <td><?= count($baseByVariant[$variant] ?? []) ?></td>
            <td class="font-mono <?= scoreClass($avgs['centrality']) ?>"><?= number_format($avgs['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['strength']) ?>"><?= number_format($avgs['strength'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($avgs['correctness']) ?>"><?= number_format($avgs['correctness'], 2) ?></td>
            <td class="font-mono col-hidden <?= scoreClass($avgs['clarity']) ?>"><?= number_format($avgs['clarity'], 2) ?></td>
            <td class="font-mono col-hidden"><?= number_format($avgs['dead_weight'], 2) ?></td>
            <td class="font-mono col-hidden"><?= number_format($avgs['single_issue'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['overall']) ?>"><strong><?= number_format($avgs['overall'], 2) ?></strong></td>
            <td class="font-mono"><?= number_format($baseVariantStdDevs[$variant] ?? 0, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>The "personas" prompt is highest variance, which makes sense given the variety of perspectives it asks the model to adopt.</p>

<p class="table-footnote">* To calculate the "overall" score, the grader is instructed to: "anchor to strength × centrality, then adjust for insightfulness, clarity, precision, errors, and extraneous material."</p>

<hr style="margin: 6rem 0 3rem 0;">

<h2 id="appendix-2-example-critiques">Appendix 2: Example critiques</h2>

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
<?= renderPaperExamples(
    'no-easy-eutopia',
    $paperNames['no-easy-eutopia'],
    $paperUrls['no-easy-eutopia'],
    $paperExamplesData['no-easy-eutopia']['topConversational'],
    $paperExamplesData['no-easy-eutopia']['uniqueCritiques'],
    $paperExamplesData['no-easy-eutopia']['bottomUniqueCritiques'],
    $parsedDirs,
    true // isDefault
) ?>
</div>

<!-- Per-paper versions of Examples (skip No Easy Eutopia since it's the default) -->
<?php foreach ($paperNames as $paperKey => $paperName): ?>
<?php if ($paperKey === 'no-easy-eutopia') continue; ?>
<div class="results-paper-section" data-results-paper="<?= htmlspecialchars($paperKey) ?>" style="display: none;">
<?= renderPaperExamples(
    $paperKey,
    $paperName,
    $paperUrls[$paperKey],
    $paperExamplesData[$paperKey]['topConversational'],
    $paperExamplesData[$paperKey]['uniqueCritiques'],
    $paperExamplesData[$paperKey]['bottomUniqueCritiques'],
    $parsedDirs,
    false // isDefault
) ?>
</div>
<?php endforeach; ?>

<?php include 'content/critique-prompt/appendix-1.php'; ?>

<?php include 'content/critique-prompt/appendix-2.php'; ?>

<h2 id="appendix-5-all-critiques">Appendix 5: All critiques</h2>
<p>All the critiques generated by GPT 5.2 Pro.</p>
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
            <button class="pill" data-value="<?= htmlspecialchars($variant) ?>"><?= htmlspecialchars(shortVariantName($variant)) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="filter-group">
        <span class="filter-label">Critiques</span>
        <div class="filter-pills" id="uniqueness-filter">
            <button class="pill active" data-value="all">All (including duplicates)</button>
            <button class="pill" data-value="unique">Unique</button>
            <button class="pill" data-value="not-conv">Unique (but not generated by conversational prompt)</button>
        </div>
    </div>
    <div class="filter-count">
        Showing <span id="visible-count"><?= $baseCritiqueCount ?></span> of <?= $baseCritiqueCount ?>
    </div>
</div>

<p id="not-conv-explainer" class="filter-explainer" style="display: none; margin-bottom: 2rem;">These are unique critique ideas that the conversational prompt did <strong>not</strong> generate. For duplicate idea clusters, an idea counts as "in conversational" if the conversational prompt produced any critique in that cluster&mdash;even if its version scored lower.</p>

<?php
// Get all base variant results sorted by overall score
$allBaseResults = array_filter($results, fn($r) => in_array($r['_parsed']['variant_display'] ?? '', $baseVariants));
$allBaseResults = array_values($allBaseResults);
usort($allBaseResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
?>

<div id="critique-list">
<?php foreach ($allBaseResults as $allIdx => $result): ?>
<?php
$parsed = $result['_parsed'];
$paperKey = $result['_paper_key'];
$parsedPath = $parsedDirs[$paperKey] ?? '';
$critiqueFile = "$parsedPath/{$result['_filename']}.txt";
$critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';
$allHiddenClass = $allIdx >= 10 ? ' all-hidden' : '';
$isUniqueRep = isset($uniqueRepresentatives[$result['_filename']]);
$isConvFound = !empty($convFoundIdea[$result['_filename']]);
?>
<details class="critique-item<?= $allHiddenClass ?>" id="critique-<?= htmlspecialchars($result['_filename']) ?>" data-paper="<?= htmlspecialchars($paperKey) ?>" data-variant="<?= htmlspecialchars($parsed['variant_display']) ?>" data-uniqueness="<?= $isUniqueRep ? 'unique' : 'duplicate' ?>" data-conv-found="<?= $isConvFound ? 'yes' : 'no' ?>">
    <summary>
        <span>
            <span class="badge" style="background: #e0e0e0; margin-right: 0.5rem;"><?= htmlspecialchars($paperNames[$paperKey]) ?></span>
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
            <div class="score-box">
                <div class="label">Dead weight</div>
                <div class="value"><?= number_format($result['dead_weight'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Single issue</div>
                <div class="value"><?= number_format($result['single_issue'] ?? 0, 2) ?></div>
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
</div>
<button class="show-all-btn" id="show-all-critiques">Show all <?= count($allBaseResults) ?> critiques</button>

<script src="assets/critique-filters.js"></script>

<?php include 'includes/footer.php'; ?>
