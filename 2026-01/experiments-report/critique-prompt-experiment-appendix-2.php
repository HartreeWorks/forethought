<?php
$pageTitle = 'Unique critiques analysis';
include 'includes/header.php';
include 'includes/functions.php';

// Load experiment results from all three papers (GPT 5.2 Pro only)
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

$results = [];

foreach ($resultsDirs as $paperKey => $resultsPath) {
    if (is_dir($resultsPath)) {
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
if (!function_exists('parseFilename')) {
    function parseFilename($name) {
        $parts = explode('-', $name);
        $prefix = '';
        if ($parts[0] === 'gemini' || $parts[0] === 'gpt') {
            $prefix = array_shift($parts) . '-';
        }
        $baseVariant = array_shift($parts);
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
        if ($baseVariant === 'baseline' && count($parts) > 0 && preg_match('/^v\d+$/', $parts[0])) {
            $baseVariant .= '-' . array_shift($parts);
        }
        $number = array_pop($parts);
        $paper = implode('-', $parts);
        $variantForDisplay = ucfirst(str_replace('-', ' ', $baseVariant));
        if (strpos($baseVariant, 'baseline') === 0) {
            $variantForDisplay = 'Baseline';
        }
        return [
            'variant' => $prefix . $baseVariant,
            'variant_display' => $variantForDisplay,
            'paper' => $paper,
            'number' => $number,
        ];
    }
}

// Short variant name for badges
if (!function_exists('shortVariantName')) {
    function shortVariantName($variant) {
        $map = [
            'Baseline' => 'Baseline',
            'Surgery' => 'Surgery',
            'Personas' => 'Personas',
            'Unforgettable' => 'Unforgettable',
            'Pivot attack' => 'Pivot-attack',
            'Authors tribunal' => 'Authors-tribunal',
            'Pre mortem' => 'Pre-mortem',
        ];
        return $map[$variant] ?? $variant;
    }
}

// Add parsed info to each result
foreach ($results as &$r) {
    $r['_parsed'] = parseFilename($r['_filename']);
}
unset($r);

// Sort by overall score descending
usort($results, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Group by paper
$baseByPaper = [];
foreach ($results as $r) {
    $baseByPaper[$r['_paper_key']][] = $r;
}

// Sort each paper's results by overall score
$baseCritiquesByPaper = [];
foreach ($baseByPaper as $paperKey => $paperResults) {
    usort($paperResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
    $baseCritiquesByPaper[$paperKey] = $paperResults;
}

// Helper function to render a critique card
if (!function_exists('renderCritiqueCard')) {
function renderCritiqueCard($result, $parsedDirs) {
    $parsed = $result['_parsed'];
    $paperKey = $result['_paper_key'];
    $parsedPath = $parsedDirs[$paperKey] ?? '';
    $critiqueFile = "$parsedPath/{$result['_filename']}.txt";
    $critiqueText = file_exists($critiqueFile) ? file_get_contents($critiqueFile) : 'Critique text not found.';

    ob_start();
    ?>
    <details class="critique-card" id="critique-<?= htmlspecialchars($result['_filename']) ?>">
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
    <?php
    return ob_get_clean();
}
}
?>

<main>
<p class="breadcrumb"><a href="critique-prompt-experiment.php">&larr; Back to main report</a></p>

<h1>How many unique critiques?</h1>

<p>Each of the eight prompts independently generated 10 critiques per paper. How many of those 80 critiques are genuinely distinct arguments?</p>

<?php
// Load duplicate cluster data from JSON files
$duplicatesDir = __DIR__ . '/data/experiments/critique-prompt';
$duplicateData = [];
$paperResultsByFilename = [];

foreach ($paperNames as $paperKey => $paperName) {
    $jsonPath = "$duplicatesDir/duplicates-$paperKey.json";
    if (file_exists($jsonPath)) {
        $duplicateData[$paperKey] = json_decode(file_get_contents($jsonPath), true);
    } else {
        $duplicateData[$paperKey] = ['clusters' => [], 'unique' => [], 'summary' => ['clustered' => 0, 'unique' => 70, 'cluster_count' => 0]];
    }

    // Build filename -> result mapping for this paper
    $paperResultsByFilename[$paperKey] = [];
    foreach ($baseCritiquesByPaper[$paperKey] as $r) {
        $paperResultsByFilename[$paperKey][$r['_filename']] = $r;
    }
}

// Count standalone unique critiques per prompt across all papers
$promptVariantMap = [
    'baseline-v2' => 'Baseline',
    'conversational' => 'Conversational',
    'unforgettable' => 'Unforgettable',
    'personas' => 'Personas',
    'surgery' => 'Surgery',
    'pivot-attack' => 'Pivot-attack',
    'authors-tribunal' => 'Authors-tribunal',
    'pre-mortem' => 'Pre-mortem',
];
$exclusiveByPrompt = [];
foreach ($promptVariantMap as $variant => $label) {
    $exclusiveByPrompt[$variant] = 0;
}
foreach ($paperNames as $paperKey => $paperName) {
    $uniqueFns = $duplicateData[$paperKey]['unique'] ?? [];
    foreach ($uniqueFns as $fn) {
        $parsed = parseFilename($fn);
        $variant = $parsed['variant'];
        if (isset($exclusiveByPrompt[$variant])) {
            $exclusiveByPrompt[$variant]++;
        }
    }
}
arsort($exclusiveByPrompt);
?>

<p><em>The uniqueness analysis was done by LLM. I've validated by eyeballing it, I think it's good enough, though surely some mistakes.</em></p>


<h2>Exclusive ideas per prompt</h2>

<p>How many critiques did each prompt surface that no other prompt generated?</p>

<table class="data-table">
<thead>
<tr>
    <th>Prompt</th>
    <th>Exclusive ideas</th>
</tr>
</thead>
<tbody>
<?php foreach ($exclusiveByPrompt as $variant => $count): ?>
<tr>
    <td><?= htmlspecialchars($promptVariantMap[$variant]) ?></td>
    <td class="num"><?= $count ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<p>According to the ACORN grader, exclusive critiques score lower on average than clustered ones (0.28 vs 0.31). This makes sense: critiques that multiple prompts independently converge on likely target more obvious, central weaknesses. That said, the exclusive ideas are not all obviously useless.</p>

<p>The five highest-scoring exclusive ideas for No Easy Eutopia:</p>

<?php
// Top 5 exclusive critiques for No Easy Eutopia, sorted by score
$neeUniques = $duplicateData['no-easy-eutopia']['unique'] ?? [];
$neeExclusives = [];
foreach ($neeUniques as $fn) {
    if (isset($paperResultsByFilename['no-easy-eutopia'][$fn])) {
        $neeExclusives[] = $paperResultsByFilename['no-easy-eutopia'][$fn];
    }
}
usort($neeExclusives, fn($a, $b) => $b['overall'] <=> $a['overall']);
$topExclusives = array_slice($neeExclusives, 0, 5);
foreach ($topExclusives as $exResult) {
    echo renderCritiqueCard($exResult, $parsedDirs);
}
?>
<p>&nbsp;</p>

<div class="filter-bar" id="results-filter-bar">
    <div class="filter-bar-container">
        <div class="filter-group">
            <span class="filter-label">Paper</span>
            <div class="filter-pills" id="appendix2-paper-filter">
                <?php foreach ($paperNames as $paperKey => $paperName): ?>
                <button class="pill<?= $paperKey === 'no-easy-eutopia' ? ' active' : '' ?>" data-value="<?= htmlspecialchars($paperKey) ?>"><?= htmlspecialchars($paperName) ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php foreach ($paperNames as $paperKey => $paperName):
    $data = $duplicateData[$paperKey];
    $clusters = $data['clusters'] ?? [];
    $uniqueFilenames = $data['unique'] ?? [];
    $summary = $data['summary'] ?? [];
    $totalCritiques = count($baseCritiquesByPaper[$paperKey]);
    $analysedCritiques = $data['total_critiques'] ?? 80;
    $clusteredCount = $summary['clustered'] ?? 0;
    $uniqueCount = $summary['unique'] ?? 0;
?>
<div class="appendix2-paper-section" data-appendix2-paper="<?= htmlspecialchars($paperKey) ?>" style="<?= $paperKey === 'no-easy-eutopia' ? '' : 'display: none;' ?>">

<?php
// Build filename -> cluster index mapping (null = standalone unique)
$filenameToCluster = [];
foreach ($clusters as $clusterIdx => $cluster) {
    foreach ($cluster['critiques'] as $fn) {
        $filenameToCluster[$fn] = $clusterIdx;
    }
}
foreach ($uniqueFilenames as $fn) {
    $filenameToCluster[$fn] = null;
}

// Filter to only analysed critiques, sorted by score descending
$analysedResults = array_filter($baseCritiquesByPaper[$paperKey], fn($r) => isset($filenameToCluster[$r['_filename']]));
$analysedResults = array_values($analysedResults);
usort($analysedResults, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Count unique arguments in top 10
$top10 = array_slice($analysedResults, 0, 10);
$seenClusters = [];
$top10Unique = 0;
foreach ($top10 as $r) {
    $cid = $filenameToCluster[$r['_filename']];
    if ($cid === null) {
        $top10Unique++;
    } elseif (!isset($seenClusters[$cid])) {
        $seenClusters[$cid] = true;
        $top10Unique++;
    }
}

// Count standalone unique critiques per prompt (arguments only that prompt surfaced)
$promptStandaloneUniques = [];
foreach ($uniqueFilenames as $fn) {
    $parsed = parseFilename($fn);
    $variant = $parsed['variant_display'];
    if (!isset($promptStandaloneUniques[$variant])) {
        $promptStandaloneUniques[$variant] = 0;
    }
    $promptStandaloneUniques[$variant]++;
}
arsort($promptStandaloneUniques);
$topPrompt = array_key_first($promptStandaloneUniques);
$topPromptCount = $promptStandaloneUniques[$topPrompt];
?>
<h2>Unique critiques of each paper</h2>

<p>The models generated <?= $uniqueCount + count($clusters) ?> unique critiques of <?= htmlspecialchars($paperNames[$paperKey]) ?>. The remaining <?= $analysedCritiques - $uniqueCount - count($clusters) ?> critiques were duplicates. Of the top 10 critiques by score, <?= $top10Unique ?> were unique. The &ldquo;<?= htmlspecialchars(strtolower($topPrompt)) ?>&rdquo; prompt contributed the most arguments that no other prompt surfaced (<?= $topPromptCount ?>).</p>

<p><em>Limitation: we only asked for 10 critiques per prompt. If we'd requested ~50 per prompt, we might have ended up with many more unique critiques.</em></p>

<?php if (count($clusters) > 0): ?>
<?php
// Show up to 5 largest clusters
$sortedClusters = $clusters;
usort($sortedClusters, fn($a, $b) => count($b['critiques']) <=> count($a['critiques']));
$topClusters = array_slice($sortedClusters, 0, 5);
?>

<?php foreach ($topClusters as $clusterIdx => $cluster):
    $clusterCritiques = [];
    foreach ($cluster['critiques'] as $filename) {
        if (isset($paperResultsByFilename[$paperKey][$filename])) {
            $clusterCritiques[] = $paperResultsByFilename[$paperKey][$filename];
        }
    }
    usort($clusterCritiques, fn($a, $b) => $b['overall'] <=> $a['overall']);
?>
<h2 id="cluster-<?= $paperKey ?>-<?= $clusterIdx + 1 ?>">Cluster <?= $clusterIdx + 1 ?>: <?= count($clusterCritiques) ?> critiques</h2>

<p><?= htmlspecialchars($cluster['description']) ?></p>

<?php foreach ($clusterCritiques as $result): ?>
<?= renderCritiqueCard($result, $parsedDirs) ?>
<?php endforeach; ?>

<?php endforeach; ?>

<?php if (count($clusters) > 5): ?>
<p class="text-muted">Showing top 5 of <?= count($clusters) ?> clusters. <?= count($clusters) - 5 ?> smaller clusters omitted.</p>
<?php endif; ?>

<?php endif; ?>

<?php
// Get unique results for this paper
$uniqueResultsForPaper = [];
foreach ($uniqueFilenames as $filename) {
    if (isset($paperResultsByFilename[$paperKey][$filename])) {
        $uniqueResultsForPaper[] = $paperResultsByFilename[$paperKey][$filename];
    }
}
usort($uniqueResultsForPaper, fn($a, $b) => $b['overall'] <=> $a['overall']);
?>

<h2 id="unique-<?= $paperKey ?>">Unique critiques (<?= count($uniqueResultsForPaper) ?> of <?= $totalCritiques ?>)</h2>

<p>The remaining <?= count($uniqueResultsForPaper) ?> critiques appear to be distinct arguments.</p>

<div id="unique-critiques-list-<?= $paperKey ?>">
<?php foreach ($uniqueResultsForPaper as $index => $result): ?>
<?php $hiddenClass = $index >= 3 ? ' unique-hidden-' . $paperKey : ''; ?>
<?= str_replace('class="critique-card"', 'class="critique-card' . $hiddenClass . '"', renderCritiqueCard($result, $parsedDirs)) ?>
<?php endforeach; ?>
</div>
<?php if (count($uniqueResultsForPaper) > 3): ?>
<button class="show-all-btn show-all-unique-btn" data-paper="<?= $paperKey ?>">Show all <?= count($uniqueResultsForPaper) ?> unique critiques</button>
<?php endif; ?>

<p class="text-muted" style="margin-top: 1.5rem;"><strong>Summary:</strong> Of <?= $totalCritiques ?> critiques, <?= $uniqueCount ?> are unique (<?= number_format($uniqueCount / $totalCritiques * 100, 0) ?>%). <?= $clusteredCount ?> critiques fall into <?= count($clusters) ?> duplicate clusters.</p>

</div>
<?php endforeach; ?>

</main>

<script>
// Appendix 2 paper filter
(function() {
    const appendix2FilterBar = document.getElementById('appendix2-paper-filter');
    if (!appendix2FilterBar) return;

    const appendix2Sections = document.querySelectorAll('.appendix2-paper-section');

    function updateAppendix2Filter(paper) {
        // Update button states
        appendix2FilterBar.querySelectorAll('.pill').forEach(p => {
            p.classList.toggle('active', p.dataset.value === paper);
        });

        // Show/hide sections
        appendix2Sections.forEach(section => {
            const sectionPaper = section.dataset.appendix2Paper;
            section.style.display = sectionPaper === paper ? '' : 'none';
        });
    }

    // Handle clicks on filter pills
    appendix2FilterBar.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            updateAppendix2Filter(e.target.dataset.value);
        }
    });
})();

// Show all unique critiques buttons (per paper)
(function() {
    document.querySelectorAll('.show-all-unique-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const paper = this.dataset.paper;
            document.querySelectorAll('.unique-hidden-' + paper).forEach(el => {
                el.classList.remove('unique-hidden-' + paper);
            });
            this.classList.add('hidden');
        });
    });
})();

// Sticky filter bar detection
(function() {
    const filterBar = document.getElementById('results-filter-bar');
    if (!filterBar) return;

    const sentinel = document.createElement('div');
    sentinel.style.height = '1px';
    sentinel.style.position = 'absolute';
    sentinel.style.top = '0';
    sentinel.style.left = '0';
    sentinel.style.right = '0';
    sentinel.style.pointerEvents = 'none';
    filterBar.parentNode.insertBefore(sentinel, filterBar);

    const observer = new IntersectionObserver(
        ([entry]) => {
            filterBar.classList.toggle('is-stuck', !entry.isIntersecting);
        },
        { threshold: 0, rootMargin: '-1px 0px 0px 0px' }
    );
    observer.observe(sentinel);
})();
</script>

<?php include 'includes/footer.php'; ?>
