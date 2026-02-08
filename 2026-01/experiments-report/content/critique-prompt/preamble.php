<h2 id="preamble">Preamble</h2>

<div class="prose">
    <p>For this experiment, our use case is: a researcher has drafted a paper, and they want AI to critique it.</p>

    <p>Naively, they could just go to chatgpt.com, select the best model, and request critique using a conversational prompt. Something like this:</p>

<details class="prompt-card">
    <summary>
        <strong>Conversational <span style="font-weight: normal;">(Ultra-minimal)</span></strong>
        <span class="text-muted" style="margin-left: 0.5rem;">Click to expand</span>
    </summary>
    <div class="content">
        <pre><?= htmlspecialchars(stripOutputFormat($conversationalPrompt)) ?></pre>
    </div>
</details>

    <p>Our question: could we get much more useful outputs with a more advanced prompt?</p>

    <p>Two ways to make the outputs more useful:</p>

    <ol>
        <li><strong>Make review easier:</strong> reviewing LLM outputs can be a slog. Minimally, the prompt could request progressive summarisation. Going further, we might provide lots of context on "the Forethought worldview", to help it filter out critiques that won't be of interest to us.</li>
        <li><strong>Generate more insightful outputs:</strong>  Sophisticated context engineering might generate higher quality critiques.</li>
</ol>
<p>This experiment will focus on <strong>(2)</strong>.</p>

<p>I'll ask GPT 5.2 Pro to critique three Forethought papers, using the conversational prompt above, and seven more sophisticated prompts. Then I'll compare the outputs, using the ACORN grader—plus my own judgement.</p>

<p>There are two things I care about here:</p>

<ol>
    <li><strong>Is the ACORN grader good for our purposes?</strong> To do systematic experiments at non-crazy-expense, we need automated graders that roughly track our judgement.</li>
    <li><strong>Do some prompts clearly outperform the conversational baseline?</strong>
    </li>
</ol>


<p><strong>Limitation:</strong> I am only experimenting with prompt texts. I'm not experimenting with other kinds of context engineering (e.g. prompt chains, multi-model synthesis, best-of-N, self-critique, etc). Some of those techniques may be more powerful.</p>
</div>
