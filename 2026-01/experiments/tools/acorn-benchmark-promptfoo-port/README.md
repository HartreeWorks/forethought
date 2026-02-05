# ACORN benchmark harness

Evaluates LLM critique grading against the [ACORN dataset](https://www.andrew.cmu.edu/user/coesterh/conceptual_reasoning_benchmark.html) (468 position-critique pairs with human ratings).

## Setup

```bash
# Add API keys to .env
cp .env.example .env  # Then edit with your keys

# Generate test cases (default: 20 samples)
python scripts/convert-acorn.py --sample 20

# Run evaluation
source .env && export OPENAI_API_KEY ANTHROPIC_API_KEY
npx promptfoo@latest eval

# View results
npx promptfoo@latest view
```

## Configuration

Edit `promptfooconfig.yaml` to:
- Add/remove models in `providers`
- Add prompt variants in `prompts/`
- Change test sample size

## Files

| File | Purpose |
|------|---------|
| `promptfooconfig.yaml` | Main config (models, prompts, tests) |
| `prompts/grader-v1.txt` | Grader prompt (rates critiques on 7 dimensions) |
| `scripts/convert-acorn.py` | Converts ACORN JSONL → promptfoo YAML |
| `tests/acorn-sample.yaml` | Generated test cases |
| `output/results.json` | Evaluation results |

## Scaling up

```bash
# Run on more samples
python scripts/convert-acorn.py --sample 100

# Run full dataset
python scripts/convert-acorn.py --sample 468
```
