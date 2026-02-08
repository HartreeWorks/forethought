<?php
/**
 * Shared utility functions for the experiments UI
 */

/**
 * Parse inline markdown (bold, italic, bold-italic) and preserve line breaks.
 * Does NOT handle headings or lists.
 */
function parseMarkdownInline($text) {
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\*\*\*(.+?)\*\*\*/s', '<strong><em>$1</em></strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $text);
    $text = nl2br($text);
    return $text;
}

/**
 * Parse markdown including inline formatting, headings (## and ###), and bullet lists.
 */
function parseMarkdown($text) {
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    // Bold and italic
    $text = preg_replace('/\*\*\*(.+?)\*\*\*/s', '<strong><em>$1</em></strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $text);
    // Headings (### and ##)
    $text = preg_replace('/^### (.+)$/m', '<h5>$1</h5>', $text);
    $text = preg_replace('/^## (.+)$/m', '<h4>$1</h4>', $text);
    // Bullet lists
    $text = preg_replace('/^- (.+)$/m', '<li>$1</li>', $text);
    $text = preg_replace('/(<li>.*<\/li>\n?)+/s', '<ul>$0</ul>', $text);
    // Preserve line breaks
    $text = nl2br($text);
    return $text;
}

/**
 * Get CSS class for score display based on score value.
 */
function scoreClass($score) {
    if ($score >= 0.6) return 'score-high';
    if ($score >= 0.3) return 'score-mid';
    return 'score-low';
}

/**
 * Calculate averages across multiple metrics for a set of results.
 * Guarded so experiment pages can define their own version with different metrics.
 */
if (!function_exists('calculateAverages')) {
    function calculateAverages($results) {
        $sums = ['centrality' => 0, 'strength' => 0, 'correctness' => 0, 'clarity' => 0, 'dead_weight' => 0, 'single_issue' => 0, 'overall' => 0];
        // Add relevance if present in the data
        $hasRelevance = false;
        foreach ($results as $r) {
            if (isset($r['relevance'])) {
                $hasRelevance = true;
                break;
            }
        }
        if ($hasRelevance) {
            $sums['relevance'] = 0;
        }
        $count = count($results);
        foreach ($results as $r) {
            foreach ($sums as $key => &$sum) {
                $sum += $r[$key] ?? 0;
            }
        }
        return array_map(fn($s) => $count > 0 ? $s / $count : 0, $sums);
    }
}

/**
 * Calculate standard deviation for overall scores.
 */
if (!function_exists('calculateStdDev')) {
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
}

/**
 * Shorten baseline variant names for display.
 */
function shortVariantName($variant) {
    return $variant;
}
