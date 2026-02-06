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
