<?php
/**
 * Reusable component for rendering paper example sections.
 *
 * This component renders the three example sections for a single paper:
 * 1. Conversational prompt outputs (top 3)
 * 2. Top scoring unique critiques
 * 3. Bottom-scoring unique critiques
 *
 * Usage:
 *   <?= renderPaperExamples($paperKey, $paperName, $paperUrl, $topConversational, $uniqueCritiques, $bottomUniqueCritiques, $parsedDirs) ?>
 */

/**
 * Render all three example sections for a single paper.
 *
 * @param string $paperKey The paper's key identifier
 * @param string $paperName The paper's display name
 * @param string $paperUrl The paper's URL on forethought.org
 * @param array $topConversational Top 3 conversational prompt critiques
 * @param array $uniqueCritiques Top 3 unique critiques (manually curated)
 * @param array $bottomUniqueCritiques Bottom 3 unique critiques (manually curated)
 * @param array $parsedDirs Mapping of paper keys to parsed output directories
 * @param bool $isDefault Whether this is the default paper (affects heading IDs)
 * @return string The rendered HTML
 */
function renderPaperExamples($paperKey, $paperName, $paperUrl, $topConversational, $uniqueCritiques, $bottomUniqueCritiques, $parsedDirs, $isDefault = false) {
    ob_start();

    // Heading ID suffix for non-default papers (to avoid duplicate IDs)
    $idSuffix = $isDefault ? '' : '-' . $paperKey;
    ?>

    <h3<?= $isDefault ? ' id="example-1-conversational-prompt-outputs"' : '' ?>>Example 1. Conversational prompt outputs</h3>

    <p>The conversational prompt used minimal scaffolding: just "What are the strongest objections to this paper's central argument?" Here are its three top-scoring critiques of "<a href="<?= htmlspecialchars($paperUrl) ?>" target="_blank"><?= htmlspecialchars($paperName) ?></a>":</p>

    <?php foreach ($topConversational as $result): ?>
    <?= renderCritiqueCard($result, $parsedDirs) ?>
    <?php endforeach; ?>

    <h3<?= $isDefault ? ' id="example-2-top-scoring-unique-critiques"' : '' ?>>Example 2. Top scoring unique critiques (all prompts)</h3>

    <p>The three top-scoring <em>unique</em> critiques of "<a href="<?= htmlspecialchars($paperUrl) ?>" target="_blank"><?= htmlspecialchars($paperName) ?></a>" (excluding duplicates of the same argument):</p>

    <?php foreach ($uniqueCritiques as $result): ?>
    <?= renderCritiqueCard($result, $parsedDirs) ?>
    <?php endforeach; ?>

    <?php if ($isDefault): ?>
    <p>Use the paper toggle above to see critiques for the other papers, or see the <a href="#appendix-3-all-critiques">appendix</a> for all critiques.</p>
    <?php endif; ?>

    <h3<?= $isDefault ? ' id="example-3-bottom-scoring-unique-critiques"' : '' ?>>Example 3. Bottom-scoring unique critiques</h3>

    <p>Here are the three lowest-scoring <em>unique</em> critiques (excluding duplicates of the same argument):</p>

    <?php foreach ($bottomUniqueCritiques as $result): ?>
    <?= renderCritiqueCard($result, $parsedDirs) ?>
    <?php endforeach; ?>

    <?php
    return ob_get_clean();
}
