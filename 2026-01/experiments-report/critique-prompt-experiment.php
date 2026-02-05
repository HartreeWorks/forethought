<?php
$pageTitle = 'Iterating on prompts with ACORN feedback';
include 'includes/header.php';
include 'includes/functions.php';

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
    $sums = ['centrality' => 0, 'strength' => 0, 'correctness' => 0, 'clarity' => 0, 'dead_weight' => 0, 'single_issue' => 0, 'overall' => 0];
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

// Shorten baseline variant names for display
function shortVariantName($variant) {
    return $variant;
}

// Check if a variant is a baseline variant (only the current baseline, not legacy versions)
function isBaselineVariant($variant) {
    return $variant === 'November';
}

$selectedVariant = $_GET['variant'] ?? 'all';
$selectedPaper = $_GET['paper'] ?? 'all';

// Load prompt texts (parameterised versions that were actually used)
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
?>

<?php
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

// Calculate top 16 critiques by prompt (16 chosen to avoid ties - 0.42 vs 0.40)
$top16Cutoff = 16;

// Calculate top N critiques by prompt (25 chosen to avoid ties at cutoff)
$topNCutoff = 50;
$allBaseCritiques = [];
foreach ($baseByVariant as $variant => $variantResults) {
    foreach ($variantResults as $r) {
        $allBaseCritiques[] = $r;
    }
}
usort($allBaseCritiques, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Top 16
$top16 = array_slice($allBaseCritiques, 0, $top16Cutoff);
$top16ByPrompt = [];
foreach ($baseVariants as $v) {
    $top16ByPrompt[$v] = 0;
}
foreach ($top16 as $critique) {
    $variant = $critique['_parsed']['variant_display'] ?? '';
    if (isset($top16ByPrompt[$variant])) {
        $top16ByPrompt[$variant]++;
    }
}
arsort($top16ByPrompt);

// Top 25
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

// Calculate top 48 critiques by prompt (48 chosen to avoid ties at cutoff - 0.32 vs 0.30)
$top48Cutoff = 48;
$top48 = array_slice($allBaseCritiques, 0, $top48Cutoff);

$top48ByPrompt = [];
foreach ($baseVariants as $v) {
    $top48ByPrompt[$v] = 0;
}
foreach ($top48 as $critique) {
    $variant = $critique['_parsed']['variant_display'] ?? '';
    if (isset($top48ByPrompt[$variant])) {
        $top48ByPrompt[$variant]++;
    }
}
arsort($top48ByPrompt);

// Calculate base variant std devs
$baseVariantStdDevs = [];
foreach ($baseByVariant as $variant => $variantResults) {
    $avg = $baseVariantAverages[$variant]['overall'] ?? 0;
    $baseVariantStdDevs[$variant] = calculateStdDev($variantResults, $avg);
}

// Calculate per-paper data for filtering
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

// Calculate top 10 critiques by prompt for each paper
$topNByPromptByPaper = [];
$topNCutoffPerPaper = 10;
foreach ($paperNames as $paperKey => $paperName) {
    $topNByPromptByPaper[$paperKey] = [];
    foreach ($baseVariants as $v) {
        $topNByPromptByPaper[$paperKey][$v] = 0;
    }
    $topNCritiques = array_slice($baseCritiquesByPaper[$paperKey], 0, $topNCutoffPerPaper);
    foreach ($topNCritiques as $critique) {
        $variant = $critique['_parsed']['variant_display'] ?? '';
        if (isset($topNByPromptByPaper[$paperKey][$variant])) {
            $topNByPromptByPaper[$paperKey][$variant]++;
        }
    }
    arsort($topNByPromptByPaper[$paperKey]);
}

// Calculate per-paper per-prompt averages
$perPaperPromptAverages = [];
$perPaperPromptStdDevs = [];
$perPaperPromptCounts = [];
foreach ($paperNames as $paperKey => $paperName) {
    $perPaperPromptAverages[$paperKey] = [];
    $perPaperPromptStdDevs[$paperKey] = [];
    $perPaperPromptCounts[$paperKey] = [];
    foreach ($baseVariants as $variant) {
        // Get critiques for this paper and variant
        $paperVariantCritiques = array_filter($baseCritiquesByPaper[$paperKey], function($r) use ($variant) {
            return ($r['_parsed']['variant_display'] ?? '') === $variant;
        });
        $paperVariantCritiques = array_values($paperVariantCritiques);
        $perPaperPromptCounts[$paperKey][$variant] = count($paperVariantCritiques);
        $perPaperPromptAverages[$paperKey][$variant] = calculateAverages($paperVariantCritiques);
        $avg = $perPaperPromptAverages[$paperKey][$variant]['overall'] ?? 0;
        $perPaperPromptStdDevs[$paperKey][$variant] = calculateStdDev($paperVariantCritiques, $avg);
    }
    // Sort by overall score
    uasort($perPaperPromptAverages[$paperKey], fn($a, $b) => $b['overall'] <=> $a['overall']);
}

// Map variant names to prompts for the intro section
$promptMap = [
    'November' => $baselineV2Prompt,
    'Surgery' => $surgeryPrompt,
    'Personas' => $personasPrompt,
    'Unforgettable' => $unforgettablePrompt,
    'Pivot-attack' => $pivotAttackPrompt,
    'Authors-tribunal' => $authorsTribunalPrompt,
    'Pre-mortem' => $preMortemPrompt,
];
?>

<h1>Iterating on prompts with ACORN feedback</h1>

<p>Can the <a href="why-automated-graders.php">ACORN grader</a> help us evaluate prompt iterations? I drafted six alternative critique prompts across two iterations and used the grader to compare them against a simple baseline.</p>

<h2 id="preamble">Preamble</h2>

<div class="prose">
    <p>For this experiment, our use case is: a researcher has drafted a paper, and they want AI to critique it.</p>

    <p>Naively, they should just go to ChatGPT.com, select the best model, and request critique using a conversational prompt. Something like this:</p>

<details class="prompt-card">
    <summary>
        <strong>Conversational <span style="font-weight: normal;">(Ultra-minimal)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($conversationalPrompt) ?></pre>
    </div>
</details>

    <p>Our question is: could we get much more useful outputs if we apply more advanced techniques?</p> 

    <p>Two ways to make the outputs more useful:</p>

    <ol>
        <li><strong>Make review easier:</strong> reviewing LLM outputs can be a slog. Minimally, the prompt could request progressive summarisation. Maximally, we could build a custom review interface—perhaps something like this. #todo - link to screengrab of the november UI.</li>
        <li><strong>Get more insightful outputs:</strong>  Sophisticated context enginering might generate higher quality critiques.</li>
</ol>
<p>Today's experiment will focus on <strong>getting more insightful outputs</strong>.</p>

<p>I'll ask GPT 5.2 Pro to critique three Forethought papers, using the conversational prompt above, and then also using a handful of more sophisticated prompts. Then I'll compare the outputs, using the ACORN grader—plus my own judgement.</p>

<p>There are two things I care about here:</p>

<ol>
    <li><strong>Is the ACORN grader any good?</strong> To do systematic context engineering experiments at non-crazy-expense, we need automated graders we can trust to roughly track human judgement.</li>
    <li><strong>Do some prompts clearly outperform the conversational baseline?</strong>
    </li>
</ol>

    <p>This experiment will inform our views on the following question:</p>
    <p>
    <blockquote>Should Forethought hire a specialist to work on sophisticated context engineering experiments, or can researchers just get most of the value from simpler prompting techniques?</blockquote>
</p>
<p><strong>Limitation:</strong> I am only experimenting with prompt texts. I'm not experimenting with other kinds of context engineering and orchestration, e.g. prompt chains, multi-model synthesis, self-critique, etc. There are reasons to think that these techniques are more powerful.</p>

<h2 id="sophisticated-prompts">The sophisticated prompts</h2>        
    
   <p>So, we need some prompts to compare to the conversational prompt.</p>

   <p>Back in November 2025, I wrote a super low-effort prompt for a prompt-chain experiment:</p>
   
<details class="prompt-card">
    <summary>
        <strong>November <span style="font-weight: normal;"></span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($baselineV2Prompt) ?></pre>
    </div>
</details>
    <p>To go further, I created three new critique prompts inspired by <a href="https://github.com/anthropics/anthropic-quickstarts/blob/main/claude-code-plugins/frontend-design-skill/agents/frontend-designer.md" target="_blank">Anthropic's frontend design skill</a>:</p>
</div>

<details class="prompt-card">
    <summary>
        <strong>Unforgettable <span style="font-weight: normal;">(Single most troubling objection)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($unforgettablePrompt) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Personas <span style="font-weight: normal;">(Hostile critic perspectives)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($personasPrompt) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Surgery <span style="font-weight: normal;">(Toulmin-style structural analysis)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($surgeryPrompt) ?></pre>
    </div>
</details>

<div class="prose" style="margin-top: 1.5rem;">
    <p>After trying those, I developed three more prompts, drawing insights from their peformance, some deep research on prompting techniques, and extensive multi-model brainstorming:</p>
</div>

<details class="prompt-card">
    <summary>
        <strong>Pivot-attack <span style="font-weight: normal;">(Target centrality via decision pivots)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($pivotAttackPrompt) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Authors-tribunal <span style="font-weight: normal;">(Target strength via adversarial filtering)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($authorsTribunalPrompt) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Pre-mortem <span style="font-weight: normal;">(Construct failure scenarios)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($preMortemPrompt) ?></pre>
    </div>
</details>


<div class="prose" style="margin-top: 1.5rem;">
    <p>So now we have seven sophisticated prompts to compare against the conversational baseline. Which one generates the best outputs?</p>
</div>

<h3 id="running-the-prompts">Running the prompts</h3>
<p>I selected three Forethought papers (No Easy Eutopia, Convergence & Compromise and Compute Bottlenecks). I ran each of the eight prompts using GPT 5.2 Pro, requesting 10 critiques each time. Then I asked GPT 5.2 Pro to evaluate each critique using the ACORN grader (one model call per critique).*</p>

<p class="table-footnote">* So that's 24 critique generation prompts, then 240 evaluation prompts. Total cost ~$300 in credits.</p>

<h2 id="results">Results</h2>

<p>According to ACORN, there was a fairly clear split:</p>

<ul>
    <li><strong>Top performers:</strong> "Pivot-attack", "Unforgettable", and "Authors-tribunal", "Surgery", "Personas".</li>
    <li><strong>Bottom performers:</strong> "Conversational", "November", and "Pre-mortem".</li>
</ul>

<p>But: do I agree with ACORN's judgement? To answer that, I'll just read some of the critiques it considers best, and some it considers worst. On those, its judgements track mine reasonably well (#todo ideally I'd do 20+ more pairwise comparisons, and enable others to do the same). So—this is useful signal. I could use this grader—or some better version—to run many more context engineering experiments.</p>

<p>So, going back to the two questions I care about:</p>

<ol>
    <li><strong>Is the ACORN grader any good?</strong> Yes, it tracks my judgement reasonably well, and could be used to run many more context engineering experiments.</li>
    <li><strong>Do some prompts clearly outperform the conversational baseline?</strong> Yes, the "top performers" do in fact give notably better insights.</li>
</ol>

<p><strong>#todo:</strong> how confident am I? need to pairwise compare</p>

<p>Now, I want you to form your own view. Below, I'll share the ACORN grader ratings, and then give you a bunch of example critiques.</p>

<h2 id="the-acorn-grader-ratings">The ACORN grader ratings</h2>
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

<?php
// Helper function to render a critique card
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

// Paper URLs for linking
$paperUrls = [
    'no-easy-eutopia' => 'https://www.forethought.org/research/no-easy-eutopia',
    'compute-bottlenecks' => 'https://www.forethought.org/research/will-compute-bottlenecks-prevent-a-software-intelligence-explosion',
    'convergence-compromise' => 'https://www.forethought.org/research/convergence-and-compromise',
];

// Prepare per-paper top and bottom critiques
$paperTopCritiques = [];
$paperBottomCritiques = [];
foreach ($paperNames as $paperKey => $paperName) {
    $paperTopCritiques[$paperKey] = array_slice($baseCritiquesByPaper[$paperKey], 0, 5);
    $bottom = array_slice($baseCritiquesByPaper[$paperKey], -3);
    $paperBottomCritiques[$paperKey] = array_reverse($bottom);
}
?>

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
// Get critiques for "No Easy Eutopia" only, base variants
$eutopiaResults = array_filter($results, function($r) use ($baseVariants) {
    return $r['_paper_key'] === 'no-easy-eutopia'
        && in_array($r['_parsed']['variant_display'] ?? '', $baseVariants);
});
$eutopiaResults = array_values($eutopiaResults);
usort($eutopiaResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
$topThree = array_slice($eutopiaResults, 0, 3);
$nextTwo = array_slice($eutopiaResults, 3, 2);
$bottomThree = array_slice($eutopiaResults, -3);
$bottomThree = array_reverse($bottomThree); // Show worst first

// Get top 3 conversational critiques for No Easy Eutopia
$conversationalResults = array_filter($results, function($r) {
    return $r['_paper_key'] === 'no-easy-eutopia'
        && ($r['_parsed']['variant_display'] ?? '') === 'Conversational';
});
$conversationalResults = array_values($conversationalResults);
usort($conversationalResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
$topConversational = array_slice($conversationalResults, 0, 3);
?>

<h3 id="example-1-conversational-prompt-outputs">Example 1. Conversational prompt outputs</h3>

<p>The conversational prompt used minimal scaffolding: just "What are the strongest objections to this paper's central argument?" Here are its three top-scoring critiques of "<a href="https://www.forethought.org/research/no-easy-eutopia" target="_blank">No Easy Eutopia</a>":</p>

<?php foreach ($topConversational as $result): ?>
<?= renderCritiqueCard($result, $parsedDirs) ?>
<?php endforeach; ?>

<?php
// Top 3 unique critiques per paper (manually curated to avoid duplicates)
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

// Bottom 3 unique critiques per paper (manually curated to avoid duplicates)
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

// Get the unique top critiques for No Easy Eutopia
$eutopiaUniqueIds = $topUniqueIds['no-easy-eutopia'];
$eutopiaUniqueCritiques = [];
foreach ($eutopiaUniqueIds as $id) {
    foreach ($eutopiaResults as $r) {
        if ($r['_filename'] === $id) {
            $eutopiaUniqueCritiques[] = $r;
            break;
        }
    }
}

// Get the unique bottom critiques for No Easy Eutopia
$eutopiaBottomUniqueIds = $bottomUniqueIds['no-easy-eutopia'];
$eutopiaBottomUniqueCritiques = [];
foreach ($eutopiaBottomUniqueIds as $id) {
    foreach ($eutopiaResults as $r) {
        if ($r['_filename'] === $id) {
            $eutopiaBottomUniqueCritiques[] = $r;
            break;
        }
    }
}
?>

<h3 id="example-2-top-scoring-unique-critiques">Example 2. Top scoring unique critiques (all prompts)</h3>

<p>The three top-scoring <em>unique</em> critiques of "<a href="https://www.forethought.org/research/no-easy-eutopia" target="_blank">No Easy Eutopia</a>" (excluding duplicates of the same argument):</p>

<?php foreach ($eutopiaUniqueCritiques as $result): ?>
<?= renderCritiqueCard($result, $parsedDirs) ?>
<?php endforeach; ?>

<p>Use the paper toggle above to see critiques for the other papers, or see the <a href="#appendix-3-all-critiques">appendix</a> for all critiques.</p>

<h3 id="example-3-bottom-scoring-unique-critiques">Example 3. Bottom-scoring unique critiques</h3>

<p>Here are the three lowest-scoring <em>unique</em> critiques (excluding duplicates of the same argument):</p>

<?php foreach ($eutopiaBottomUniqueCritiques as $result): ?>
<?= renderCritiqueCard($result, $parsedDirs) ?>
<?php endforeach; ?>
</div><!-- End all papers section for examples -->

<!-- Per-paper versions of Examples (skip No Easy Eutopia since it's the default) -->
<?php foreach ($paperNames as $paperKey => $paperName): ?>
<?php if ($paperKey === 'no-easy-eutopia') continue; ?>
<?php
// Get top 3 conversational critiques for this paper
$paperConversationalResults = array_filter($results, function($r) use ($paperKey) {
    return $r['_paper_key'] === $paperKey
        && ($r['_parsed']['variant_display'] ?? '') === 'Conversational';
});
$paperConversationalResults = array_values($paperConversationalResults);
usort($paperConversationalResults, fn($a, $b) => $b['overall'] <=> $a['overall']);
$paperTopConversational = array_slice($paperConversationalResults, 0, 3);

// Get top 3 unique critiques for this paper
$paperUniqueIds = $topUniqueIds[$paperKey] ?? [];
$paperUniqueCritiques = [];
$paperAllResults = array_filter($results, function($r) use ($paperKey, $baseVariants) {
    return $r['_paper_key'] === $paperKey
        && in_array($r['_parsed']['variant_display'] ?? '', $baseVariants);
});
foreach ($paperUniqueIds as $id) {
    foreach ($paperAllResults as $r) {
        if ($r['_filename'] === $id) {
            $paperUniqueCritiques[] = $r;
            break;
        }
    }
}

// Get bottom 3 unique critiques for this paper
$paperBottomUniqueIds = $bottomUniqueIds[$paperKey] ?? [];
$paperBottomUniqueCritiques = [];
foreach ($paperBottomUniqueIds as $id) {
    foreach ($paperAllResults as $r) {
        if ($r['_filename'] === $id) {
            $paperBottomUniqueCritiques[] = $r;
            break;
        }
    }
}
?>
<div class="results-paper-section" data-results-paper="<?= htmlspecialchars($paperKey) ?>" style="display: none;">
<h3>Example 1. Conversational prompt outputs</h3>

<p>The conversational prompt used minimal scaffolding: just "What are the strongest objections to this paper's central argument?" Here are its three top-scoring critiques of "<?= htmlspecialchars($paperName) ?>":</p>

<?php foreach ($paperTopConversational as $result): ?>
<?= renderCritiqueCard($result, $parsedDirs) ?>
<?php endforeach; ?>

<h3>Example 2. Top scoring unique critiques</h3>

<p>The three top-scoring <em>unique</em> critiques of "<a href="<?= htmlspecialchars($paperUrls[$paperKey] ?? '#') ?>" target="_blank"><?= htmlspecialchars($paperName) ?></a>" (excluding duplicates of the same argument):</p>

<?php foreach ($paperUniqueCritiques as $result): ?>
<?= renderCritiqueCard($result, $parsedDirs) ?>
<?php endforeach; ?>

<h3>Example 3. Bottom-scoring unique critiques</h3>

<p>Here are the three lowest-scoring <em>unique</em> critiques (excluding duplicates of the same argument):</p>

<?php foreach ($paperBottomUniqueCritiques as $result): ?>
<?= renderCritiqueCard($result, $parsedDirs) ?>
<?php endforeach; ?>
</div>
<?php endforeach; ?>

<h2 id="discussion">Discussion</h2>
<p>                                                       
  This experiment used a human-validated grader to compare a low-effort baseline prompt against three slightly more sophisticated prompts.</p>

<p>The results are consistent with general consensus that context engineering can improve LLM performace over simple "wing it" prompts. But—how much improvement is there to be had, either now, or later in 2026? This quick experiment doesn't tell us much.</p>

<p class="todo">#todo: What experiment actually <strong>would</strong> 
tell us a bunch more? Or perhaps experiments aren't what we need right now—I should just talk to Casper and others?</p>

<p class="todo">#todo: What did I learn about the ACORN grader from these outputs? How trustworthy is it? How discerning? What did I learn about making graders more generally, aside from "yeah, if you want to really trust it, you need a lot of human time to validate...". Is it actually fine to just use pretty slapdash grading steps?</p>

<hr style="margin: 3rem 0;">

<h2 id="appendix-1-how-did-critique-quality-vary-by-paper">Appendix 1: How did critique quality vary by paper?</h2>

<p>According to the ACORN grader, it didn't vary much. I've not done my own comparisons.</p>
<table>
    <thead>
        <tr>
            <th>Paper</th>
            <th>N</th>
            <th>Centrality</th>
            <th>Strength</th>
            <th>Correctness</th>
            <th>Clarity</th>
            <th>Dead weight</th>
            <th>Single issue</th>
            <th>Overall</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($basePaperAverages as $paperKey => $avgs): ?>
        <tr>
            <td><strong><?= htmlspecialchars($paperNames[$paperKey]) ?></strong></td>
            <td><?= count($baseByPaper[$paperKey] ?? []) ?></td>
            <td class="font-mono <?= scoreClass($avgs['centrality']) ?>"><?= number_format($avgs['centrality'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['strength']) ?>"><?= number_format($avgs['strength'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['correctness']) ?>"><?= number_format($avgs['correctness'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['clarity']) ?>"><?= number_format($avgs['clarity'], 2) ?></td>
            <td class="font-mono"><?= number_format($avgs['dead_weight'], 2) ?></td>
            <td class="font-mono"><?= number_format($avgs['single_issue'], 2) ?></td>
            <td class="font-mono <?= scoreClass($avgs['overall']) ?>"><strong><?= number_format($avgs['overall'], 2) ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr style="margin: 3rem 0;">

<h2 id="appendix-2-how-many-unique-critiques">Appendix 2: How many unique critiques?</h2>

<p>Each of the seven prompts independently generated 10 critiques per paper. How many of those 70 critiques are genuinely distinct arguments? LLM analysis identified clusters of duplicates—arguments that multiple prompts generated independently.</p>

<p><a href="critique-prompt-experiment-appendix-2.php">View the full uniqueness analysis &rarr;</a></p>

<hr style="margin: 3rem 0;">

<h2 id="appendix-3-all-critiques">Appendix 3: All critiques</h2>
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
    <div class="filter-count">
        Showing <span id="visible-count"><?= $baseCritiqueCount ?></span> of <?= $baseCritiqueCount ?>
    </div>
</div>

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
$allHiddenClass = $allIdx >= 3 ? ' all-hidden' : '';
?>
<details class="critique-item<?= $allHiddenClass ?>" id="critique-<?= htmlspecialchars($result['_filename']) ?>" data-paper="<?= htmlspecialchars($paperKey) ?>" data-variant="<?= htmlspecialchars($parsed['variant_display']) ?>">
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

<script>
(function() {
    const paperFilter = document.getElementById('paper-filter');
    const variantFilter = document.getElementById('variant-filter');
    const critiqueList = document.getElementById('critique-list');
    const visibleCount = document.getElementById('visible-count');
    const items = critiqueList.querySelectorAll('.critique-item');
    const showAllBtn = document.getElementById('show-all-critiques');
    let allExpanded = false;

    let selectedPaper = 'all';
    let selectedVariant = 'all';

    function updateURL() {
        const params = new URLSearchParams();
        if (selectedPaper !== 'all') params.set('paper', selectedPaper);
        if (selectedVariant !== 'all') params.set('variant', selectedVariant);
        const queryString = params.toString();
        const newURL = window.location.pathname + (queryString ? '?' + queryString : '') + '#appendix-3-all-critiques';
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

    paperFilter.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            selectedPaper = e.target.dataset.value;
            setActiveButton(paperFilter, selectedPaper);
            expandAllCritiques();
            updateFilters();
        }
    });

    variantFilter.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            selectedVariant = e.target.dataset.value;
            setActiveButton(variantFilter, selectedVariant);
            expandAllCritiques();
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

    // Update URL hash when any critique card is opened
    const allCritiqueCards = document.querySelectorAll('.critique-item, .critique-card');
    allCritiqueCards.forEach(card => {
        card.addEventListener('toggle', e => {
            if (card.open && card.id) {
                const newURL = window.location.pathname + window.location.search + '#' + card.id;
                history.replaceState(history.state, '', newURL);
            }
        });
    });

    // Open a specific critique from URL hash
    function openCritiqueFromHash() {
        const hash = window.location.hash;
        if (hash && hash.startsWith('#critique-')) {
            const critiqueEl = document.getElementById(hash.slice(1));
            if (critiqueEl) {
                // If it's in the filtered list and hidden, reset filters
                if (critiqueEl.classList.contains('critique-item') && critiqueEl.style.display === 'none') {
                    selectedPaper = 'all';
                    selectedVariant = 'all';
                    setActiveButton(paperFilter, 'all');
                    setActiveButton(variantFilter, 'all');
                    updateFilters(false);
                }

                // Open the card and scroll to it
                critiqueEl.open = true;
                setTimeout(() => {
                    critiqueEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }
    }

    // Show all button functionality for All critiques
    function expandAllCritiques() {
        if (!allExpanded) {
            items.forEach(item => item.classList.remove('all-hidden'));
            showAllBtn.classList.add('hidden');
            allExpanded = true;
        }
    }

    showAllBtn.addEventListener('click', expandAllCritiques);

    // Auto-expand when using filters (any filter that's not 'all')
    function onFilterUsed() {
        if (selectedPaper !== 'all' || selectedVariant !== 'all') {
            expandAllCritiques();
        }
    }

    // Load initial state from URL
    loadFromURL();
    openCritiqueFromHash();

    // If URL has filter params, expand all
    if (selectedPaper !== 'all' || selectedVariant !== 'all') {
        expandAllCritiques();
    }

    // Handle hash changes (e.g., clicking internal links)
    window.addEventListener('hashchange', openCritiqueFromHash);
})();

// Unique critiques Show all button (separate from filter logic)
(function() {
    const uniqueBtn = document.getElementById('show-all-unique');
    if (uniqueBtn) {
        uniqueBtn.addEventListener('click', function() {
            document.querySelectorAll('#unique-critiques-list .unique-hidden').forEach(el => {
                el.classList.remove('unique-hidden');
            });
            uniqueBtn.classList.add('hidden');
        });
    }
})();

// Sticky filter bar detection and visibility
(function() {
    const filterBar = document.getElementById('results-filter-bar');
    const discussionHeading = document.getElementById('discussion');
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

    // Hide filter bar when Discussion heading is at or above the top of viewport
    if (discussionHeading) {
        const hideObserver = new IntersectionObserver(
            ([entry]) => {
                // Hide when Discussion heading reaches the top of the viewport
                filterBar.classList.toggle('filter-hidden', entry.boundingClientRect.top <= 50);
            },
            { threshold: 0, rootMargin: '-50px 0px 0px 0px' }
        );
        hideObserver.observe(discussionHeading);
    }
})();

// Results section paper filter
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
</script>


