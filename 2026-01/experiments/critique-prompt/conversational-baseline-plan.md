# Plan: Run conversational baseline prompt experiment

## Goal
Test whether a simple, conversational prompt ("What are the strongest objections to this paper's central argument?") produces critiques of similar quality to the elaborate prompts—directly testing whether fancy prompt engineering is worth it.

## Key files
- **Experiment runner**: `/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/2026-01/experiments-report/data/experiments/critique-prompt/run-experiment-gpt.py`
- **Prompts directory**: `.../critique-prompt/prompts/`
- **Results directories**: `.../results-gpt/`, `.../results-gpt-cb/`, `.../results-gpt-cc/`
- **Parsed outputs**: `.../outputs-gpt/parsed/`, etc.
- **PHP report**: `/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/2026-01/experiments-report/critique-prompt-experiment.php`

## Steps

### 1. Create the conversational prompt file
Create `prompts/conversational-parameterised.md`:

```markdown
What are the strongest objections to this paper's central argument?

Output {{num_critiques}} critiques as a numbered list. Each critique should begin with a title in quotes, followed by 4-7 sentences.

---

{{paper}}
```

This keeps the output format (for fair comparison) but strips out:
- The long list of critique types to consider
- Multi-phase reasoning instructions
- Persona/perspective scaffolding
- Validation steps
- Anti-slop constraints

The prompt is just the core question. If the elaborate scaffolding matters, this should score noticeably worse.

### 2. Update run-experiment-gpt.py
Add to the PROMPTS dict:
```python
"conversational": PROMPTS_DIR / "conversational-parameterised.md",
```

### 3. Run the experiment
```bash
cd /Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/2026-01/experiments-report/data/experiments/critique-prompt

python run-experiment-gpt.py --paper no-easy-eutopia --prompt conversational
python run-experiment-gpt.py --paper compute-bottlenecks --prompt conversational
python run-experiment-gpt.py --paper convergence-and-compromise --prompt conversational
```

This will:
- Generate critiques (saved to `outputs-gpt/conversational-{paper}.md`)
- Parse them (saved to `outputs-gpt/parsed/conversational-{paper}-*.txt`)
- Grade each with ACORN (saved to `results-gpt/conversational-{paper}-*.json`)

### 4. Update PHP report to include new variant
Edit `critique-prompt-experiment.php`:

1. Add to `$baseVariants` array (line 204):
```php
$baseVariants = ['Conversational', 'Baseline', 'Surgery', 'Personas', 'Unforgettable', 'Pivot-attack', 'Authors-tribunal', 'Pre-mortem'];
```

2. Add parsing for 'conversational' in `parseFilename()` - actually not needed, single-word names parse automatically.

3. Optionally add the prompt text display in the "What I did" section.

### 5. Verification
- Check http://localhost:8080/critique-prompt-experiment.php
- Verify "Conversational" appears in the results table
- Compare average scores to other prompts
- Review top/bottom critiques to sanity-check grader behaviour

## Expected outcome
We'll see whether GPT-5.2 Pro with a minimal prompt produces critiques that score:
- **Comparably** to elaborate prompts (within ~0.05 of best) → fancy prompting not worth it
- **Significantly worse** (>0.10 below best) → prompt engineering adds real value
- **Somewhere in between** → marginal gains, case-by-case decision

For context, current results show:
- Best prompts (Pivot-attack, Unforgettable): ~0.35 overall
- Baseline: ~0.25 overall
- Gap between best and baseline: ~0.10

If "Conversational" scores ~0.25 (like Baseline), that suggests the elaborate prompts help.
If it scores ~0.35 (like the best), that suggests the scaffolding is unnecessary.
