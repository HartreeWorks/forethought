<h2 id="sophisticated-prompts">The sophisticated prompts</h2>

<div class="prose">
   <p>Back in November 2025, I wrote a super low-effort prompt for our <a href="https://clients.hartreeworks.org/forethought/critique">prompt-chain experiment</a>:</p>
</div>

<details class="prompt-card">
    <summary>
        <strong>November <span style="font-weight: normal;"></span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars(stripOutputFormat($baselineV2Prompt)) ?></pre>
    </div>
</details>

<div class="prose">
    <p>To go further, I created three new critique prompts inspired by <a href="https://wow.pjh.is/journal/claude-front-end-design-skill" target="_blank">Anthropic's frontend design skill</a>:</p>
</div>

<details class="prompt-card">
    <summary>
        <strong>Unforgettable <span style="font-weight: normal;">(Single most troubling objection)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars(stripOutputFormat($unforgettablePrompt)) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Personas <span style="font-weight: normal;">(Hostile critic perspectives)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars(stripOutputFormat($personasPrompt)) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Surgery <span style="font-weight: normal;">(Toulmin-style structural analysis)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars(stripOutputFormat($surgeryPrompt)) ?></pre>
    </div>
</details>

<div class="prose" style="margin-top: 1.5rem;">
    <p>I also developed three more prompts, drawing on research on prompting techniques, and extensive multi-model brainstorming:</p>
</div>

<details class="prompt-card">
    <summary>
        <strong>Pivot-attack <span style="font-weight: normal;">(Target centrality via decision pivots)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars(stripOutputFormat($pivotAttackPrompt)) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Authors-tribunal <span style="font-weight: normal;">(Target strength via adversarial filtering)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars(stripOutputFormat($authorsTribunalPrompt)) ?></pre>
    </div>
</details>

<details class="prompt-card">
    <summary>
        <strong>Pre-mortem <span style="font-weight: normal;">(Construct failure scenarios)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars(stripOutputFormat($preMortemPrompt)) ?></pre>
    </div>
</details>

<div class="prose" style="margin-top: 1.5rem;">
    <p>So now we have seven sophisticated prompts to compare against the conversational baseline. Which one generates the best outputs?</p>
</div>

<h3 id="running-the-prompts">Running the prompts</h3>
<p>I selected three Forethought papers (No Easy Eutopia, Convergence & Compromise and Compute Bottlenecks). I sent each of the eight prompts to GPT 5.2 Pro, requesting 10 critiques from each. Then I asked GPT 5.2 Pro to evaluate each critique using the ACORN grader (one model call per critique).*</p>

<p class="table-footnote">* So that's 24 critique generation prompts, then 240 evaluation prompts. Total cost ~$300 in API credits.</p>
