<h2 id="introduction">Introduction</h2>

<div class="prose">
    <p>A recurring issue with AI-generated critiques of Forethought papers is that they sometimes naively question premises the organisation has already deeply considered—"longtermism might be wrong", "AGI may not happen", and so on. These critiques waste researcher review time on settled questions.</p>

    <p>The <strong>Forethought Worldview Primer</strong> is a ~16KB document that organises Forethought's intellectual framework into three tiers:</p>

    <ol>
        <li><strong>Foundational premises</strong> (Tier 1) — positions treated as settled across 3+ papers (e.g. "AI will be transformative", "the long-term future matters enormously")</li>
        <li><strong>Strong working assumptions</strong> (Tier 2) — positions with broad support but where genuinely novel arguments could shift thinking</li>
        <li><strong>Active research questions</strong> (Tier 3) — areas where Forethought papers disagree, flag uncertainty, or are actively investigating</li>
    </ol>

    <p>The primer explicitly instructs the critique model to focus energy on Tier 3 questions and to avoid spending critique space on Tier 1 premises.</p>

    <h3 id="hypothesis">Hypothesis</h3>

    <p>Including the worldview primer as context alongside the paper will produce more targeted, organisation-relevant critiques that focus on genuine uncertainties rather than rehashing settled positions. We expect this to show up as:</p>

    <ul>
        <li>Higher <strong>centrality</strong> scores (critiques target what actually matters to Forethought)</li>
        <li>Higher <strong>overall</strong> scores (less dead weight from naive objections)</li>
        <li>Qualitatively different critiques that engage with Tier 3 tensions</li>
    </ul>

    <h3 id="method">Method</h3>

    <p>We re-ran 4 of the 8 prompts from the <a href="critique-prompt-experiment.php">main critique prompt experiment</a> on "No Easy Eutopia" with the primer included as additional context. The primer is wrapped in <code>&lt;organisational-context&gt;</code> tags alongside the paper text in <code>&lt;paper&gt;</code> tags.</p>

    <p>The same ACORN grader was used, receiving only the original paper text (without the primer) — matching the original experiment's grading conditions. This means any score differences reflect genuine changes in critique quality, not a grading artefact.</p>

    <p>The 4 prompts tested were: <strong>Conversational</strong>, <strong>Pivot-attack</strong>, <strong>Unforgettable</strong>, and <strong>Personas</strong>. Each generated 10 critiques, for 40 total.</p>
</div>
