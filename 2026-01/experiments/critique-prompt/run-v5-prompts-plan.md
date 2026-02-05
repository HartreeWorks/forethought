# Plan: Run V5 critique prompts experiment

## Overview

Run the three new critique prompts (Pivot Attack, Author's Tribunal, Pre-Mortem) against all three Forethought papers using GPT 5.2 Pro, then grade each critique with the ACORN grader.

**Prompts to test:**
- `pivot-attack` - Centrality-focused via decision pivots
- `authors-tribunal` - Strength-focused via adversarial rebuttal filtering
- `pre-mortem` - Failure scenario construction

**Papers:**
- No Easy Eutopia
- Compute Bottlenecks
- Convergence and Compromise

**Expected outputs:**
- 10 critiques × 3 prompts × 3 papers = 90 new critiques
- Each critique graded by ACORN (90 grading calls)
- Results automatically displayed in PHP viewer

## Prerequisites

1. OpenAI API key configured in `/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/acorn-benchmark/.env`
2. Python dependencies installed (openai, python-dotenv)

## Execution steps

### Step 1: Navigate to experiment directory

```bash
cd /Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/january-experiments-ui/data/research/critique-prompt-experiment
```

### Step 2: Run experiments (9 runs total)

Each run generates 10 critiques and grades them. Runs are independent and can be executed sequentially or in parallel (if API rate limits allow).

**No Easy Eutopia:**
```bash
python run-experiment-gpt.py --paper no-easy-eutopia --prompt pivot-attack
python run-experiment-gpt.py --paper no-easy-eutopia --prompt authors-tribunal
python run-experiment-gpt.py --paper no-easy-eutopia --prompt pre-mortem
```

**Compute Bottlenecks:**
```bash
python run-experiment-gpt.py --paper compute-bottlenecks --prompt pivot-attack
python run-experiment-gpt.py --paper compute-bottlenecks --prompt authors-tribunal
python run-experiment-gpt.py --paper compute-bottlenecks --prompt pre-mortem
```

**Convergence and Compromise:**
```bash
python run-experiment-gpt.py --paper convergence-and-compromise --prompt pivot-attack
python run-experiment-gpt.py --paper convergence-and-compromise --prompt authors-tribunal
python run-experiment-gpt.py --paper convergence-and-compromise --prompt pre-mortem
```

### Step 3: Verify results

Check that output files were created:
```bash
# Should show 30 new result files (10 per prompt)
ls -la results-gpt/*pivot-attack* results-gpt/*authors-tribunal* results-gpt/*pre-mortem* 2>/dev/null | wc -l
ls -la results-gpt-cb/*pivot-attack* results-gpt-cb/*authors-tribunal* results-gpt-cb/*pre-mortem* 2>/dev/null | wc -l
ls -la results-gpt-cc/*pivot-attack* results-gpt-cc/*authors-tribunal* results-gpt-cc/*pre-mortem* 2>/dev/null | wc -l
```

### Step 4: View results

Open the experiment viewer (already updated to include new prompts):
```
http://localhost:8080/critique-prompt-experiment.php
```

## Estimated time and cost

- **Generation:** ~20 calls per prompt × 3 papers = 180 API calls total (90 critique generation + 90 grading)
- **Time:** Depends on API latency; roughly 1-2 minutes per prompt/paper combination
- **Cost:** GPT 5.2 Pro pricing × ~180 calls (estimate: $5-15 depending on token usage)

## Rollback

Results are cached—if a run fails partway through, re-running the same command will skip already-completed critiques and grades.

## Files modified

- `/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/january-experiments-ui/data/research/critique-prompt-experiment/run-experiment-gpt.py` - Added new prompts to PROMPTS dict
- `/Users/ph/Documents/Projects/2025-09-forethought-ai-uplift/work/january-experiments-ui/critique-prompt-experiment.php` - Added new prompts to viewer

## Files created

- `prompts/pivot-attack-parameterised.md`
- `prompts/authors-tribunal-parameterised.md`
- `prompts/pre-mortem-parameterised.md`
