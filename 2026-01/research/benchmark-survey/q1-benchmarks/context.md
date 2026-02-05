# Context: LLM Grader Benchmark Research

## Project Background

Forethought Research is a nonprofit focused on navigating the transition to superintelligent AI, working on AI alignment, governance, digital mind rights, and post-AGI futures.

We're developing an **LLM grader** to evaluate LLM-generated outputs (critiques, arguments, analyses) in philosophical, economic, and conceptual domains. This grader will help us scale AI-assisted research by automating quality assessment of generated content.

## Use Case

The grader needs to evaluate:
- **Critiques** of philosophical arguments, policy proposals, and research claims
- **Analytical summaries** of complex topics in AI safety, economics, and philosophy
- **Argument quality** in domains where there's no single "correct" answer
- **Research-style writing** including literature reviews and conceptual analyses

## Why We Need Benchmarks

To validate our LLM grader, we need datasets with:
1. **Human expert ratings** of argument/critique quality (ground truth)
2. **Philosophical/conceptual content** rather than just factual QA
3. **Pairwise comparisons** or rubric-based assessments (not just binary correct/incorrect)

## Known Benchmark

We've identified the **CMU Conceptual Reasoning Benchmark (LMCA)** by Cooper & Oesterheld which has:
- 224 texts with 608 critique pairs
- Expert pairwise ratings of critique quality
- Focus on philosophical reasoning and AI alignment arguments

This is close to what we need, but we want to survey the full landscape of similar resources.

## Constraints

- Small validation budget: ~20-50 items for human rating
- Focus on critique/argument quality, not factual correctness
- Domains: philosophy, economics, AI alignment, policy analysis
- Need ground-truth human ratings to validate the grader

## Not Interested In

- Pure factual QA benchmarks (TriviaQA, Natural Questions)
- Code generation benchmarks (HumanEval, MBPP)
- Math-only benchmarks unless they include reasoning quality assessment
- General chat/instruction-following benchmarks unless they specifically evaluate argument quality
