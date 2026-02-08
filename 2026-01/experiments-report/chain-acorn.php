<?php
$pageTitle = 'Full critique chain with ACORN grading';
include 'includes/header.php';
include 'includes/functions.php';

// Load data
$dataDir = __DIR__ . '/data/experiments/full-critique-chain-acorn/outputs';
$promptsDir = __DIR__ . '/data/experiments/full-critique-chain-acorn/prompts';

$step1 = json_decode(file_get_contents("$dataDir/step1-brainstorm.json"), true);
$step2Top5 = json_decode(file_get_contents("$dataDir/step2-top5.json"), true);
$step3 = json_decode(file_get_contents("$dataDir/step3-developed.json"), true);
$step4 = json_decode(file_get_contents("$dataDir/step4-counters.json"), true);
$step5 = json_decode(file_get_contents("$dataDir/step5-revised.json"), true);
$step6Top3 = json_decode(file_get_contents("$dataDir/step6-top3.json"), true);
$step6Final = json_decode(file_get_contents("$dataDir/step6-final.json"), true);

// Load all step 2 grades
$step2Grades = [];
foreach (glob("$dataDir/step2-grades/c*.json") as $file) {
    $grade = json_decode(file_get_contents($file), true);
    if ($grade) $step2Grades[] = $grade;
}
usort($step2Grades, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Load step 6 grades (revised)
$step6Grades = [];
foreach (glob("$dataDir/step6-grades/c*.json") as $file) {
    $grade = json_decode(file_get_contents($file), true);
    if ($grade) $step6Grades[] = $grade;
}
usort($step6Grades, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Load step 6 final grades
$step6FinalGrades = [];
foreach (glob("$dataDir/step6-final-grades/c*.json") as $file) {
    $grade = json_decode(file_get_contents($file), true);
    if ($grade) $step6FinalGrades[] = $grade;
}
usort($step6FinalGrades, fn($a, $b) => $b['overall'] <=> $a['overall']);

// Index final grades by id
$finalGradesById = [];
foreach ($step6FinalGrades as $g) {
    $finalGradesById[$g['id']] = $g;
}

// Load prompts for display
$brainstormPrompt = file_exists("$promptsDir/01-brainstorm.md") ? file_get_contents("$promptsDir/01-brainstorm.md") : '';
$acornPrompt = file_exists("$promptsDir/02-acorn-grader.txt") ? file_get_contents("$promptsDir/02-acorn-grader.txt") : '';
?>

<h1>Full critique chain with ACORN grading</h1>

<p>Does using a validated grader at the selection steps of a multi-step critique chain lead to better final outputs? Can the <a href="https://www.andrew.cmu.edu/user/coesterh/conceptual_reasoning_benchmark.html" target="_blank">ACORN grader</a> reliably assess longer, more developed critiques?</p>

<h2 id="design">Design</h2>

<div class="prose">
    <p>The <a href="experiment.php">previous experiment</a> tested brainstorm prompts in isolation—step 1 only. This experiment runs the <strong>full 6-step critique chain</strong> on "<a href="https://www.forethought.org/research/no-easy-eutopia" target="_blank">No Easy Eutopia</a>", replacing the chain's ad-hoc scoring at steps 2 and 6 with the ACORN grader.</p>
</div>

<div class="flowchart">
    <div class="step-wrapper">
        <div class="step">
            <div class="step-number">Step 1</div>
            <div class="step-title">Brainstorm 20 critiques</div>
        </div>
    </div>
    <div class="arrow"></div>
    <div class="step-wrapper">
        <div class="step" style="border-color: #e67e22;">
            <div class="step-number" style="color: #e67e22;">Step 2</div>
            <div class="step-title">ACORN grade each, select top 5</div>
        </div>
    </div>
    <div class="arrow"></div>
    <div class="step-wrapper">
        <div class="step">
            <div class="step-number">Step 3</div>
            <div class="step-title">Develop top 5 (300–400 words)</div>
        </div>
    </div>
    <div class="arrow"></div>
    <div class="step-wrapper">
        <div class="step">
            <div class="step-number">Step 4</div>
            <div class="step-title">Counter-arguments</div>
        </div>
    </div>
    <div class="arrow"></div>
    <div class="step-wrapper">
        <div class="step">
            <div class="step-number">Step 5</div>
            <div class="step-title">Revise critiques</div>
        </div>
    </div>
    <div class="arrow"></div>
    <div class="step-wrapper">
        <div class="step" style="border-color: #e67e22;">
            <div class="step-number" style="color: #e67e22;">Step 6</div>
            <div class="step-title">ACORN grade, select top 3, expand</div>
        </div>
    </div>
</div>

<div class="prose">
    <p>Steps highlighted in orange use the ACORN grader instead of the chain's default ad-hoc scoring. All steps use GPT-5.2 Pro.</p>
</div>

<h2 id="step-2-results">Step 2: ACORN grading of 20 brainstorm critiques</h2>

<p>The ACORN grader scored all 20 brainstorm critiques individually. Scores ranged from <?= number_format(end($step2Grades)['overall'], 2) ?> to <?= number_format($step2Grades[0]['overall'], 2) ?>.</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Centrality</th>
            <th>Strength</th>
            <th>Correctness</th>
            <th>Overall</th>
            <th>Selected</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $top5Ids = array_map(fn($c) => $c['id'], $step2Top5);
        foreach ($step2Grades as $g):
            $isTop5 = in_array($g['id'], $top5Ids);
        ?>
        <tr<?= $isTop5 ? ' style="font-weight: bold;"' : '' ?>>
            <td><strong><?= htmlspecialchars($g['title'] ?? $g['id']) ?></strong><br><span class="text-muted"><?= htmlspecialchars($g['id']) ?></span></td>
            <td class="font-mono <?= scoreClass($g['centrality'] ?? 0) ?>"><?= number_format($g['centrality'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($g['strength'] ?? 0) ?>"><?= number_format($g['strength'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($g['correctness'] ?? 0) ?>"><?= number_format($g['correctness'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($g['overall'] ?? 0) ?>"><?= number_format($g['overall'] ?? 0, 2) ?></td>
            <td><?= $isTop5 ? '&#10003;' : '' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>One critique (c20) was a clear standout at 0.65. The remaining top 5 clustered between 0.32–0.38. The bottom half sat in the 0.18–0.28 range—reasonable discrimination.</p>

<h3 id="step-2-top-5">Top 5 brainstorm critiques</h3>

<?php foreach ($step2Top5 as $c): ?>
<details class="critique-card">
    <summary>
        <span><?= htmlspecialchars($c['title'] ?? $c['id']) ?></span>
        <span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars($c['id']) ?></span>
            <span class="font-mono <?= scoreClass($c['overall']) ?>"><?= number_format($c['overall'], 2) ?></span>
        </span>
    </summary>
    <div class="content">
        <div class="two-column">
            <div class="column">
                <h4>Critique</h4>
                <div class="critique-text"><?= parseMarkdown($c['text']) ?></div>
            </div>
            <div class="column">
                <h4>ACORN reasoning</h4>
                <p class="reasoning"><?= htmlspecialchars($c['reasoning'] ?? '') ?></p>
            </div>
        </div>
    </div>
</details>
<?php endforeach; ?>

<h2 id="step-6-results">Step 6: ACORN grading of revised critiques</h2>

<p>After the develop→counter→revise cycle (steps 3–5), the ACORN grader scored all 5 revised critiques:</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Centrality</th>
            <th>Strength</th>
            <th>Correctness</th>
            <th>Overall (revised)</th>
            <th>Overall (brainstorm)</th>
            <th>Change</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $top3Ids = array_map(fn($c) => $c['id'], $step6Top3);
        // Index brainstorm scores by id
        $brainstormById = [];
        foreach ($step2Top5 as $c) { $brainstormById[$c['id']] = $c['overall']; }
        foreach ($step6Grades as $g):
            $isTop3 = in_array($g['id'], $top3Ids);
            $brainstormScore = $brainstormById[$g['id']] ?? null;
            $change = $brainstormScore !== null ? $g['overall'] - $brainstormScore : null;
        ?>
        <tr<?= $isTop3 ? ' style="font-weight: bold;"' : '' ?>>
            <td><strong><?= htmlspecialchars($g['title'] ?? $g['id']) ?></strong><br><span class="text-muted"><?= htmlspecialchars($g['id']) ?></span></td>
            <td class="font-mono <?= scoreClass($g['centrality'] ?? 0) ?>"><?= number_format($g['centrality'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($g['strength'] ?? 0) ?>"><?= number_format($g['strength'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($g['correctness'] ?? 0) ?>"><?= number_format($g['correctness'] ?? 0, 2) ?></td>
            <td class="font-mono <?= scoreClass($g['overall'] ?? 0) ?>"><?= number_format($g['overall'] ?? 0, 2) ?></td>
            <td class="font-mono"><?= $brainstormScore !== null ? number_format($brainstormScore, 2) : '—' ?></td>
            <td class="font-mono"><?= $change !== null ? ($change >= 0 ? '+' : '') . number_format($change, 2) : '—' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>The develop→counter→revise cycle had mixed effects on ACORN scores. The strongest critique (c20) dropped from 0.65 to 0.55—possibly because the expanded version introduced more caveats that reduced perceived strength. Two others (c02, c16) showed modest changes.</p>

<h2 id="final-critiques">Final expanded critiques</h2>

<p>The top 3 revised critiques were expanded to 500–700 words each, then graded one final time with ACORN:</p>

<?php foreach ($step6Final as $f):
    $grade = $finalGradesById[$f['id']] ?? null;
?>
<details class="critique-card" open>
    <summary>
        <span><?= htmlspecialchars($f['conversational_title'] ?? $f['id']) ?></span>
        <span>
            <span class="badge" style="margin-right: 0.5rem;"><?= htmlspecialchars($f['id']) ?></span>
            <?php if ($grade): ?>
            <span class="font-mono <?= scoreClass($grade['overall']) ?>"><?= number_format($grade['overall'], 2) ?></span>
            <?php endif; ?>
        </span>
    </summary>
    <div class="content">
        <?php if ($grade): ?>
        <div class="score-grid">
            <div class="score-box">
                <div class="label">Centrality</div>
                <div class="value <?= scoreClass($grade['centrality'] ?? 0) ?>"><?= number_format($grade['centrality'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Strength</div>
                <div class="value <?= scoreClass($grade['strength'] ?? 0) ?>"><?= number_format($grade['strength'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box">
                <div class="label">Correctness</div>
                <div class="value <?= scoreClass($grade['correctness'] ?? 0) ?>"><?= number_format($grade['correctness'] ?? 0, 2) ?></div>
            </div>
            <div class="score-box overall">
                <div class="label">Overall</div>
                <div class="value <?= scoreClass($grade['overall'] ?? 0) ?>"><?= number_format($grade['overall'] ?? 0, 2) ?></div>
            </div>
        </div>
        <?php endif; ?>

        <div class="prose" style="margin-bottom: 1rem;">
            <p><strong>Summary:</strong> <?= htmlspecialchars($f['summary'] ?? '') ?></p>
        </div>

        <div class="two-column">
            <div class="column">
                <h4>Full critique</h4>
                <div class="critique-text"><?= parseMarkdown($f['deep'] ?? '') ?></div>
            </div>
            <div class="column">
                <h4>ACORN reasoning</h4>
                <p class="reasoning"><?= htmlspecialchars($grade['reasoning'] ?? 'No reasoning provided.') ?></p>
            </div>
        </div>
    </div>
</details>
<?php endforeach; ?>

<h2 id="score-trajectory">Score trajectory across the chain</h2>

<p>How did ACORN scores change as critiques progressed through the chain?</p>

<table>
    <thead>
        <tr>
            <th>Critique</th>
            <th>Step 2 (brainstorm)</th>
            <th>Step 6 (revised)</th>
            <th>Step 6 (final expanded)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Index revised grades by id
        $revisedById = [];
        foreach ($step6Grades as $g) { $revisedById[$g['id']] = $g; }

        foreach ($step6Final as $f):
            $id = $f['id'];
            $brainstorm = $brainstormById[$id] ?? null;
            $revised = isset($revisedById[$id]) ? $revisedById[$id]['overall'] : null;
            $final = isset($finalGradesById[$id]) ? $finalGradesById[$id]['overall'] : null;
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($f['conversational_title'] ?? $id) ?></strong><br><span class="text-muted"><?= htmlspecialchars($id) ?></span></td>
            <td class="font-mono <?= $brainstorm !== null ? scoreClass($brainstorm) : '' ?>"><?= $brainstorm !== null ? number_format($brainstorm, 2) : '—' ?></td>
            <td class="font-mono <?= $revised !== null ? scoreClass($revised) : '' ?>"><?= $revised !== null ? number_format($revised, 2) : '—' ?></td>
            <td class="font-mono <?= $final !== null ? scoreClass($final) : '' ?>"><?= $final !== null ? number_format($final, 2) : '—' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2 id="discussion">Discussion</h2>

<div class="prose">
    <h3 id="what-worked">What worked</h3>
    <ul>
        <li><strong>ACORN discriminated well at step 2.</strong> The 20 brainstorm critiques received a wide range of scores (0.18–0.65), and the grader's top pick (c20—attacking the gap between "many ways to fail" and "failing is likely") is a genuinely central objection to the paper.</li>
        <li><strong>The chain produced coherent outputs.</strong> All six steps ran cleanly with GPT-5.2 Pro, and the final expanded critiques are well-structured and substantive.</li>
        <li><strong>ACORN handled longer texts.</strong> The grader assessed 300–400 word revised critiques and 500–700 word expanded critiques without obvious problems—scores remained in sensible ranges and the reasoning was specific.</li>
    </ul>

    <h3 id="concerns">Concerns and limitations</h3>
    <ul>
        <li><strong>Scores dropped after revision.</strong> The strongest critique (c20) went from 0.65 at brainstorm to 0.55 after revision, then back up to 0.58 after expansion. This could mean the develop→counter→revise cycle introduced hedging that weakened the argument in ACORN's view, or it could reflect noise in the grader. Either way, it's not clear the chain <em>improved</em> critique quality as measured by ACORN.</li>
        <li><strong>N=1 run.</strong> With a single run on a single paper, we can't distinguish signal from noise. The scores could look quite different on a second run.</li>
        <li><strong>No comparison baseline.</strong> We didn't run the same chain with the original ad-hoc scoring at steps 2 and 6, so we can't directly measure whether ACORN selection produced better final outputs.</li>
        <li><strong>Moderate absolute scores.</strong> The best final critique scored 0.58. For context, in the <a href="experiment.php">brainstorm experiment</a>, the best single-step brainstorm critiques also scored around 0.55–0.65. The chain doesn't seem to be producing dramatically better critiques than a good single-step brainstorm—at least as ACORN measures them.</li>
    </ul>

    <h3 id="next-steps">Possible next steps</h3>
    <ul>
        <li>Run the same chain with the original ad-hoc scoring to get a direct comparison.</li>
        <li>Run multiple times to assess score stability.</li>
        <li>Try different papers to see if the pattern holds.</li>
        <li>Have Fin or another researcher read the final critiques and compare quality to single-step outputs.</li>
    </ul>
</div>

<hr style="margin: 3rem 0;">

<h2 id="appendix-prompts">Appendix: Prompts used</h2>

<details class="prompt-card">
    <summary>
        <strong>Step 1: Brainstorm prompt</strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($brainstormPrompt) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Steps 2 &amp; 6: ACORN grader</strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars($acornPrompt) ?></pre>
    </div>
</details>

<?php include 'includes/footer.php'; ?>
