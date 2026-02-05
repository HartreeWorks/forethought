# Running critique prompt experiments

This document explains how to run new prompt variants through the critique experiment pipeline.

## Overview

The experiment:
1. Generates critiques using GPT-5.2 Pro with a given prompt
2. Parses the numbered critiques from the output
3. Grades each critique using the ACORN rubric (also via GPT-5.2 Pro)
4. Saves results as JSON files

## Prerequisites

- OpenAI API key with credits in `.env` file (symlinked to `~/.claude/skills/ask-many-models/.env`)
- Python with `openai` and `python-dotenv` packages

## Directory structure

```
critique-prompt/
├── prompts/                    # Prompt templates (parameterised)
├── outputs-gpt/                # Raw outputs for no-easy-eutopia
│   └── parsed/                 # Individual parsed critiques
├── outputs-gpt-cb/             # Raw outputs for compute-bottlenecks
│   └── parsed/
├── outputs-gpt-cc/             # Raw outputs for convergence-and-compromise
│   └── parsed/
├── results-gpt/                # Grading results (JSON) for no-easy-eutopia
├── results-gpt-cb/             # Grading results for compute-bottlenecks
├── results-gpt-cc/             # Grading results for convergence-and-compromise
└── run-experiment-gpt.py       # Main experiment runner
```

## Adding a new prompt variant

### 1. Create the prompt file

Create a new file in `prompts/` with the naming convention `{name}-parameterised.md`.

The prompt must include these placeholders:
- `{{num_critiques}}` - Will be replaced with 10
- `{{paper}}` - Will be replaced with the full paper text

Example minimal prompt (`conversational-parameterised.md`):
```markdown
What are the strongest objections to this paper's central argument?

Output {{num_critiques}} critiques as a numbered list. Each critique should begin with a title in quotes, followed by 4-7 sentences.

---

{{paper}}
```

### 2. Add the prompt to run-experiment-gpt.py

Edit the `PROMPTS` dict (around line 60):

```python
PROMPTS = {
    # ... existing prompts ...
    "your-prompt-name": PROMPTS_DIR / "your-prompt-name-parameterised.md",
}
```

### 3. Run the experiment

Run for all 3 papers (these take several minutes each due to API calls):

```bash
cd /Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/2026-01/experiments/critique-prompt

# Run as background tasks (recommended - they take 5-15 minutes each)
python run-experiment-gpt.py --paper no-easy-eutopia --prompt your-prompt-name &
python run-experiment-gpt.py --paper compute-bottlenecks --prompt your-prompt-name &
python run-experiment-gpt.py --paper convergence-and-compromise --prompt your-prompt-name &
```

Or run sequentially if you want to monitor:
```bash
python run-experiment-gpt.py --paper no-easy-eutopia --prompt your-prompt-name
python run-experiment-gpt.py --paper compute-bottlenecks --prompt your-prompt-name
python run-experiment-gpt.py --paper convergence-and-compromise --prompt your-prompt-name
```

The script:
- Checks for cached outputs and skips regeneration if found
- Saves raw output to `outputs-gpt{-cb,-cc}/{prompt}-{paper}.md`
- Parses into individual files in `parsed/` subdirectory
- Grades each critique and saves to `results-gpt{-cb,-cc}/{prompt}-{paper}-{num}.json`

### 4. Update the PHP report

Edit `/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/2026-01/experiments-report/critique-prompt-experiment.php`:

Add your variant to the `$baseVariants` array (around line 204):
```php
$baseVariants = ['Conversational', 'Baseline', 'Surgery', 'Personas', ...];
```

Note: Use Title Case for the variant name (e.g., 'Conversational' not 'conversational').

### 5. Verify results

Check the PHP report at: http://localhost:8080/critique-prompt-experiment.php

The new variant should appear in the results table with scores.

## Troubleshooting

### API quota errors
Check your OpenAI billing at https://platform.openai.com/account/billing

### Missing files errors
The script references:
- `.env` - API key (should be symlinked to `~/.claude/skills/ask-many-models/.env`)
- `GRADER_PATH` - Points to the ACORN grader prompt

### Output not appearing
The script buffers output during API calls. Check if processes are running:
```bash
ps aux | grep run-experiment-gpt
```

Check for generated output files:
```bash
ls outputs-gpt*/*.md
```

## Re-running experiments

To re-run with fresh outputs, delete the cached files:
```bash
# Delete outputs and grades for a specific prompt/paper
rm outputs-gpt/your-prompt-no-easy-eutopia.md
rm outputs-gpt/parsed/your-prompt-no-easy-eutopia-*.txt
rm results-gpt/your-prompt-no-easy-eutopia-*.json
```

Then run the experiment again.
