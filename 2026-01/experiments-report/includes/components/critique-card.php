<?php
/**
 * Reusable critique card component for rendering individual critiques.
 *
 * Usage:
 *   <?= renderCritiqueCard($result, $parsedDirs) ?>
 *   <?= renderCritiqueCard($result, $parsedDirs, true) ?> // with paper badge
 */

/**
 * Render a critique card with score grid and two-column layout.
 *
 * @param array $result The critique result data
 * @param array $parsedDirs Mapping of paper keys to parsed output directories
 * @param bool $showPaperBadge Whether to show the paper name badge
 * @return string The rendered HTML
 */
function renderCritiqueCard($result, $parsedDirs, $showPaperBadge = false) {
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
