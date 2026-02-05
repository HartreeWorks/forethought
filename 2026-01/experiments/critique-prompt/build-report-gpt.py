#!/usr/bin/env python3
"""Build HTML reports for GPT-5.2 critique experiment results.

Usage:
    python build-report-gpt.py          # Build all reports
    python build-report-gpt.py eutopia  # Build specific report
    python build-report-gpt.py cc cb    # Build multiple specific reports
"""

import json
import html as html_lib
import sys
from pathlib import Path

BASE_DIR = Path(__file__).parent

# Experiment configurations
EXPERIMENTS = {
    "eutopia": {
        "id": "eutopia",
        "results_dir": "results-gpt",
        "outputs_dir": "outputs-gpt",
        "paper_key": "no-easy-eutopia",
        "paper_title": "No Easy Eutopia",
        "short_title": "No Easy Eutopia",
    },
    "cc": {
        "id": "cc",
        "results_dir": "results-gpt-cc",
        "outputs_dir": "outputs-gpt-cc",
        "paper_key": "convergence-and-compromise",
        "paper_title": "Convergence and Compromise",
        "short_title": "Convergence & Compromise",
    },
    "cb": {
        "id": "cb",
        "results_dir": "results-gpt-cb",
        "outputs_dir": "outputs-gpt-cb",
        "paper_key": "compute-bottlenecks",
        "paper_title": "Compute Bottlenecks",
        "short_title": "Compute Bottlenecks",
    },
}

PROMPTS = [
    "surgery", "personas", "unforgettable",
    "gemini-surgery", "gemini-personas", "gemini-unforgettable",
    "gpt-surgery", "gpt-personas", "gpt-unforgettable",
]
NUM_CRITIQUES = 10


def load_critique_data(results_dir: Path, parsed_dir: Path, paper_key: str):
    """Load all critiques and their grades."""
    critiques = []

    for prompt in PROMPTS:
        for i in range(1, NUM_CRITIQUES + 1):
            critique_file = parsed_dir / f"{prompt}-{paper_key}-{i:02d}.txt"
            grade_file = results_dir / f"{prompt}-{paper_key}-{i:02d}.json"

            if not critique_file.exists() or not grade_file.exists():
                print(f"Missing: {critique_file} or {grade_file}")
                continue

            with open(critique_file) as f:
                text = f.read().strip()

            with open(grade_file) as f:
                scores = json.load(f)

            critiques.append({
                "prompt": prompt,
                "paper": paper_key,
                "num": i,
                "text": html_lib.escape(text),
                "scores": {
                    **scores,
                    "reasoning": html_lib.escape(scores.get("reasoning", ""))
                }
            })

    return critiques


def load_summary(results_dir: Path):
    """Load summary statistics."""
    summary_file = results_dir / "summary.json"
    with open(summary_file) as f:
        return json.load(f)


def score_class(value, inverted=False):
    """Return CSS class for score colouring."""
    if inverted:
        if value < 0.1:
            return "high"
        elif value < 0.15:
            return "mid"
        else:
            return "low"
    else:
        if value >= 0.4:
            return "high"
        elif value >= 0.25:
            return "mid"
        else:
            return "low"


def build_nav_links(current_id: str) -> str:
    """Build navigation links for all experiments."""
    links = []
    for exp_id, exp in EXPERIMENTS.items():
        rel_path = f"../{exp['results_dir']}/report.html" if exp_id != current_id else "report.html"
        active = ' class="active"' if exp_id == current_id else ''
        links.append(f'<a href="{rel_path}"{active}>{exp["short_title"]}</a>')
    return "\n        ".join(links)


def build_report(exp_id: str):
    """Build the HTML report for a specific experiment."""
    exp = EXPERIMENTS[exp_id]
    results_dir = BASE_DIR / exp["results_dir"]
    parsed_dir = BASE_DIR / exp["outputs_dir"] / "parsed"
    output_path = results_dir / "report.html"

    critiques = load_critique_data(results_dir, parsed_dir, exp["paper_key"])
    summary = load_summary(results_dir)
    print(f"[{exp_id}] Loaded {len(critiques)} critiques")

    # Calculate overall stats
    all_overall = [c["scores"]["overall"] for c in critiques]
    mean_overall = sum(all_overall) / len(all_overall) if all_overall else 0
    max_overall = max(all_overall) if all_overall else 0

    # Find winner
    winner = max(summary.keys(), key=lambda k: summary[k]["overall"]["mean"])

    # Sort prompts by overall score (descending)
    sorted_prompts = sorted(
        [p for p in PROMPTS if p in summary],
        key=lambda p: summary[p]["overall"]["mean"],
        reverse=True
    )

    # Build nav links
    nav_links = build_nav_links(exp_id)

    # Generate HTML
    html = f'''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPT-5.2 Critique Experiment: {exp["paper_title"]}</title>
    <style>
        :root {{ --bg: #1a1a2e; --surface: #16213e; --surface-2: #0f3460; --accent: #10b981; --text: #eaeaea; --text-muted: #a0a0a0; --green: #4ade80; --yellow: #fbbf24; --red: #f87171; }}
        * {{ box-sizing: border-box; }}
        body {{ font-family: -apple-system, BlinkMacSystemFont, sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 2rem; line-height: 1.6; max-width: 1400px; margin: 0 auto; }}
        h1 {{ color: var(--accent); margin-bottom: 0.5rem; }}
        h2 {{ color: var(--accent); border-bottom: 1px solid var(--surface-2); padding-bottom: 0.5rem; margin-top: 2rem; }}
        .subtitle {{ color: var(--text-muted); margin-bottom: 2rem; }}

        .nav-bar {{ background: var(--surface-2); padding: 0.75rem 1.5rem; margin: -2rem -2rem 2rem -2rem; display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }}
        .nav-bar .nav-label {{ color: var(--text-muted); font-size: 0.85rem; margin-right: 0.5rem; }}
        .nav-bar a {{ color: var(--text); text-decoration: none; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem; transition: background 0.2s; }}
        .nav-bar a:hover {{ background: var(--surface); }}
        .nav-bar a.active {{ background: var(--accent); color: white; }}

        table {{ width: 100%; border-collapse: collapse; margin-bottom: 2rem; }}
        th {{ background: var(--surface-2); padding: 0.75rem; text-align: left; font-size: 0.85rem; }}
        th.has-tooltip {{ cursor: help; position: relative; }}
        th.has-tooltip:hover::after {{ content: attr(data-tooltip); position: absolute; left: 50%; transform: translateX(-50%); top: 100%; background: var(--bg); color: var(--text); padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.8rem; font-weight: normal; white-space: pre-wrap; width: 300px; z-index: 100; border: 1px solid var(--surface-2); box-shadow: 0 4px 12px rgba(0,0,0,0.3); }}
        td {{ padding: 0.75rem; border-bottom: 1px solid var(--surface-2); font-family: monospace; }}
        tr:hover {{ background: var(--surface); }}
        .winner {{ background: rgba(74, 222, 128, 0.1); }}
        .high {{ color: var(--green); }}
        .mid {{ color: var(--yellow); }}
        .low {{ color: var(--red); }}

        .info-box {{ background: var(--surface); padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid var(--accent); }}
        .info-box h3 {{ margin: 0 0 0.5rem 0; color: var(--accent); }}
        .info-box p {{ margin: 0.5rem 0; color: var(--text-muted); }}
        .info-box code {{ background: var(--bg); padding: 0.2rem 0.4rem; border-radius: 4px; }}
        .info-box a {{ color: var(--accent); }}

        .filters {{ background: var(--surface); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }}
        .filters label {{ color: var(--text-muted); font-size: 0.9rem; }}
        .filters select, .filters input {{ background: var(--bg); border: 1px solid var(--surface-2); color: var(--text); padding: 0.5rem; border-radius: 4px; }}

        .critique-card {{ background: var(--surface); border-radius: 8px; margin-bottom: 1rem; overflow: hidden; }}
        .critique-header {{ padding: 1rem; background: var(--surface-2); display: flex; justify-content: space-between; align-items: center; cursor: pointer; }}
        .critique-header:hover {{ background: var(--accent); }}
        .critique-header h4 {{ margin: 0; font-size: 0.95rem; }}
        .critique-meta {{ display: flex; gap: 1rem; align-items: center; }}
        .badge {{ padding: 0.25rem 0.6rem; border-radius: 12px; font-size: 0.8rem; font-weight: bold; }}
        .badge-prompt {{ background: var(--surface); }}
        .badge-score {{ background: var(--accent); color: white; }}

        .critique-body {{ padding: 1.5rem; display: none; }}
        .critique-card.open .critique-body {{ display: block; }}
        .critique-card.open .critique-header {{ background: var(--accent); }}

        .scores-grid {{ display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.75rem; margin-bottom: 1.5rem; }}
        .score-item {{ background: var(--bg); padding: 0.75rem; border-radius: 6px; text-align: center; }}
        .score-item .label {{ font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.25rem; }}
        .score-item .value {{ font-size: 1.1rem; font-weight: bold; font-family: monospace; }}

        .section {{ margin: 1rem 0; }}
        .section-label {{ font-size: 0.75rem; color: var(--accent); text-transform: uppercase; font-weight: bold; margin-bottom: 0.5rem; }}
        blockquote {{ background: var(--bg); border-left: 3px solid var(--accent); padding: 1rem; margin: 0; white-space: pre-wrap; font-size: 0.9rem; }}
        .reasoning {{ color: var(--text-muted); font-size: 0.85rem; line-height: 1.7; }}

        .stats-row {{ display: flex; gap: 2rem; margin-bottom: 1rem; flex-wrap: wrap; }}
        .stat {{ background: var(--surface); padding: 1rem 1.5rem; border-radius: 8px; }}
        .stat .value {{ font-size: 2rem; font-weight: bold; color: var(--accent); font-family: monospace; }}
        .stat .label {{ font-size: 0.8rem; color: var(--text-muted); }}
    </style>
</head>
<body>
    <nav class="nav-bar">
        <span class="nav-label">Papers:</span>
        {nav_links}
    </nav>
    <h1>GPT-5.2 Critique Experiment</h1>
    <p class="subtitle">9 prompts x "{exp["paper_title"]}" x 10 critiques = 90 critiques graded with ACORN rubric using GPT-5.2 Pro</p>

    <div class="stats-row">
        <div class="stat"><div class="value">{len(critiques)}</div><div class="label">Critiques Graded</div></div>
        <div class="stat"><div class="value">{mean_overall:.3f}</div><div class="label">Mean Overall Score</div></div>
        <div class="stat"><div class="value">{max_overall:.2f}</div><div class="label">Highest Score</div></div>
        <div class="stat"><div class="value">{winner}</div><div class="label">Winner (by overall)</div></div>
    </div>

    <div class="info-box">
        <h3>About this experiment</h3>
        <p>This experiment tests 9 critique prompts using <strong>GPT-5.2 Pro</strong> on the "{exp["paper_title"]}" paper.</p>
        <p>Compare with the <a href="../results/report.html">Claude results</a> to see if rankings correlate across models.</p>
    </div>

    <h2>Summary comparison</h2>
    <table>
        <tr>
            <th>Rank</th>
            <th>Prompt</th>
            <th>Centrality</th>
            <th>Strength</th>
            <th>Correctness</th>
            <th>Clarity</th>
            <th>Dead Weight</th>
            <th>Single Issue</th>
            <th class="has-tooltip" data-tooltip="Anchored to Strength x Centrality (how much the critique damages the position), then adjusted for clarity, correctness, and extraneous material.">Overall &#9432;</th>
            <th>Str x Cent</th>
        </tr>
'''

    # Add rows for each prompt (sorted by overall)
    for rank, prompt in enumerate(sorted_prompts, 1):
        s = summary[prompt]
        is_winner = prompt == winner
        row_class = ' class="winner"' if is_winner else ''
        winner_badge = ' <span class="badge badge-score">Winner</span>' if is_winner else ''

        html += f'''        <tr{row_class}>
            <td>{rank}</td>
            <td><strong>{prompt}</strong>{winner_badge}</td>
            <td class="{score_class(s['centrality']['mean'])}">{s['centrality']['mean']:.3f}</td>
            <td class="{score_class(s['strength']['mean'])}">{s['strength']['mean']:.3f}</td>
            <td class="{score_class(s['correctness']['mean'])}">{s['correctness']['mean']:.3f}</td>
            <td class="{score_class(s['clarity']['mean'])}">{s['clarity']['mean']:.3f}</td>
            <td class="{score_class(s['dead_weight']['mean'], inverted=True)}">{s['dead_weight']['mean']:.3f}</td>
            <td class="{score_class(s['single_issue']['mean'])}">{s['single_issue']['mean']:.3f}</td>
            <td class="{score_class(s['overall']['mean'])}">{s['overall']['mean']:.3f}</td>
            <td class="{score_class(s['strength_x_centrality']['mean'])}">{s['strength_x_centrality']['mean']:.3f}</td>
        </tr>
'''

    # Build prompt filter options
    prompt_options = '\n            '.join(
        f'<option value="{p}">{p}</option>' for p in PROMPTS
    )

    html += f'''    </table>

    <h2>All critiques</h2>

    <div class="filters">
        <label>Filter by prompt:</label>
        <select id="promptFilter" onchange="filterCritiques()">
            <option value="all">All prompts</option>
            {prompt_options}
        </select>
        <label>Min overall score:</label>
        <input type="number" id="minScore" min="0" max="1" step="0.05" value="0" onchange="filterCritiques()">
        <label>Sort by:</label>
        <select id="sortBy" onchange="sortCritiques()">
            <option value="default">Default order</option>
            <option value="overall-desc" selected>Overall (high to low)</option>
            <option value="overall-asc">Overall (low to high)</option>
        </select>
    </div>

    <div id="critiques-container"></div>

    <script>
    const critiques = '''

    html += json.dumps(critiques, indent=2)

    html += ''';

    function scoreClass(value, inverted = false) {
        if (inverted) {
            return value < 0.1 ? 'high' : value < 0.15 ? 'mid' : 'low';
        }
        return value >= 0.4 ? 'high' : value >= 0.25 ? 'mid' : 'low';
    }

    function renderCritique(c) {
        const s = c.scores;
        const card = document.createElement('div');
        card.className = 'critique-card';
        card.dataset.prompt = c.prompt;
        card.dataset.paper = c.paper;
        card.dataset.overall = s.overall;

        const header = document.createElement('div');
        header.className = 'critique-header';
        header.onclick = function() { card.classList.toggle('open'); };

        const title = document.createElement('h4');
        title.textContent = c.prompt + ' #' + c.num + ' — ' + c.paper;
        header.appendChild(title);

        const meta = document.createElement('div');
        meta.className = 'critique-meta';

        const promptBadge = document.createElement('span');
        promptBadge.className = 'badge badge-prompt';
        promptBadge.textContent = c.prompt;
        meta.appendChild(promptBadge);

        const scoreBadge = document.createElement('span');
        scoreBadge.className = 'badge badge-score';
        scoreBadge.textContent = s.overall.toFixed(2);
        meta.appendChild(scoreBadge);

        header.appendChild(meta);
        card.appendChild(header);

        const body = document.createElement('div');
        body.className = 'critique-body';

        const scoresGrid = document.createElement('div');
        scoresGrid.className = 'scores-grid';

        const dimensions = [
            ['Centrality', s.centrality, false],
            ['Strength', s.strength, false],
            ['Correctness', s.correctness, false],
            ['Clarity', s.clarity, false],
            ['Dead Weight', s.dead_weight, true],
            ['Single Issue', s.single_issue, false],
            ['Overall', s.overall, false]
        ];

        dimensions.forEach(function(dim) {
            const item = document.createElement('div');
            item.className = 'score-item';

            const label = document.createElement('div');
            label.className = 'label';
            label.textContent = dim[0];
            item.appendChild(label);

            const value = document.createElement('div');
            value.className = 'value ' + scoreClass(dim[1], dim[2]);
            value.textContent = dim[1].toFixed(2);
            item.appendChild(value);

            scoresGrid.appendChild(item);
        });

        body.appendChild(scoresGrid);

        const critiqueSection = document.createElement('div');
        critiqueSection.className = 'section';

        const critiqueLabel = document.createElement('div');
        critiqueLabel.className = 'section-label';
        critiqueLabel.textContent = 'Critique';
        critiqueSection.appendChild(critiqueLabel);

        const blockquote = document.createElement('blockquote');
        blockquote.textContent = c.text;
        critiqueSection.appendChild(blockquote);

        body.appendChild(critiqueSection);

        const reasoningSection = document.createElement('div');
        reasoningSection.className = 'section';

        const reasoningLabel = document.createElement('div');
        reasoningLabel.className = 'section-label';
        reasoningLabel.textContent = 'Grader reasoning';
        reasoningSection.appendChild(reasoningLabel);

        const reasoning = document.createElement('p');
        reasoning.className = 'reasoning';
        reasoning.textContent = s.reasoning || 'No reasoning provided';
        reasoningSection.appendChild(reasoning);

        body.appendChild(reasoningSection);
        card.appendChild(body);

        return card;
    }

    let currentCritiques = critiques.slice();

    function filterCritiques() {
        const prompt = document.getElementById('promptFilter').value;
        const minScore = parseFloat(document.getElementById('minScore').value) || 0;

        currentCritiques = critiques.filter(function(c) {
            if (prompt !== 'all' && c.prompt !== prompt) return false;
            if (c.scores.overall < minScore) return false;
            return true;
        });

        sortCritiques();
    }

    function sortCritiques() {
        const sortBy = document.getElementById('sortBy').value;

        if (sortBy === 'overall-desc') {
            currentCritiques.sort(function(a, b) { return b.scores.overall - a.scores.overall; });
        } else if (sortBy === 'overall-asc') {
            currentCritiques.sort(function(a, b) { return a.scores.overall - b.scores.overall; });
        } else {
            currentCritiques.sort(function(a, b) {
                if (a.prompt !== b.prompt) return a.prompt.localeCompare(b.prompt);
                if (a.paper !== b.paper) return a.paper.localeCompare(b.paper);
                return a.num - b.num;
            });
        }

        renderAll();
    }

    function renderAll() {
        const container = document.getElementById('critiques-container');
        container.textContent = '';
        currentCritiques.forEach(function(c) {
            container.appendChild(renderCritique(c));
        });
    }

    sortCritiques();
    </script>
</body>
</html>'''

    with open(output_path, "w") as f:
        f.write(html)

    print(f"[{exp_id}] Report written to {output_path}")


def main():
    """Build reports based on command line arguments."""
    args = sys.argv[1:]

    if not args:
        # Build all reports
        exp_ids = list(EXPERIMENTS.keys())
    else:
        # Build specific reports
        exp_ids = []
        for arg in args:
            if arg in EXPERIMENTS:
                exp_ids.append(arg)
            else:
                print(f"Unknown experiment: {arg}")
                print(f"Available: {', '.join(EXPERIMENTS.keys())}")
                sys.exit(1)

    for exp_id in exp_ids:
        build_report(exp_id)

    print(f"\nBuilt {len(exp_ids)} report(s)")


if __name__ == "__main__":
    main()
