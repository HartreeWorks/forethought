# Deep research: Prompting techniques for critique generation (2025-2026)

**Task:** Research the most effective prompting techniques for complex reasoning and critique generation tasks, focusing exclusively on sources from 2025 and 2026.

**Context:** I'm developing prompts that ask LLMs to generate substantive critiques of academic papers. My current best prompts use techniques like:
- Explicit "anti-slop" constraints (banning generic outputs)
- Structured reasoning steps before output
- Specificity requirements ("must be paper-specific, not copy-pasteable")
- Role/persona assignment
- Attack taxonomies (countermodel, causal reversal, etc.)

**Research questions:**

1. **What prompting techniques have emerged or been validated in 2025-2026** for improving reasoning quality, specificity, and avoiding generic outputs? Look for:
   - Academic papers on prompting (NeurIPS, ICML, ACL, arXiv)
   - Technical reports from AI labs (Anthropic, OpenAI, Google DeepMind)
   - High-quality practitioner sources (e.g., Simon Willison, Ethan Mollick, prompt engineering teams)

2. **What techniques specifically help with critique/evaluation tasks?** (red-teaming, debate, adversarial prompting, etc.)

3. **What has changed about effective prompting with 2025/2026 models** compared to earlier models? (e.g., do chain-of-thought techniques still help? Are certain older techniques now unnecessary or counterproductive?)

4. **Are there any novel structural approaches** (multi-turn, self-critique loops, verification steps, etc.) that have shown strong results?

**Constraints:**
- **Ignore all sources dated before 2025** — prompting best practices have changed significantly with recent models
- Prioritise academically validated techniques, but include high-quality practitioner advice
- Focus on techniques applicable to text generation (not image/code-specific)

**Output format:**
For each technique you find, provide:
- Name/description
- Source (with date)
- Key insight
- Relevance to critique generation tasks
