/**
 * Gemini Deep Research Script
 *
 * Uses deep-research-pro-preview-12-2025 via the Interactions API
 * to conduct comprehensive research on a given question.
 */

import { GoogleGenAI } from '@google/genai';
import { readFileSync, writeFileSync, existsSync, mkdirSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT_DIR = join(__dirname, '..');

interface ResearchConfig {
  questionId: string;
  prompt: string;
  contextFile?: string;
  pollIntervalMs?: number;
  maxWaitMs?: number;
}

async function runDeepResearch(config: ResearchConfig): Promise<string> {
  const client = new GoogleGenAI({
    apiKey: process.env.GEMINI_API_KEY || process.env.GOOGLE_API_KEY,
  });

  // Load context if provided
  let contextContent = '';
  if (config.contextFile && existsSync(config.contextFile)) {
    contextContent = readFileSync(config.contextFile, 'utf-8');
  } else if (existsSync(join(ROOT_DIR, 'context.md'))) {
    contextContent = readFileSync(join(ROOT_DIR, 'context.md'), 'utf-8');
  }

  // Combine context with prompt
  const fullPrompt = contextContent
    ? `## Background Context\n\n${contextContent}\n\n---\n\n## Research Question\n\n${config.prompt}`
    : config.prompt;

  console.log(`Starting Gemini deep research for: ${config.questionId}`);
  console.log(`Prompt: ${config.prompt.substring(0, 100)}...`);

  // Start the deep research interaction
  const interaction = await client.interactions.create({
    input: fullPrompt,
    agent: 'deep-research-pro-preview-12-2025',
    background: true,
  });

  console.log(`Research started with ID: ${interaction.id}`);

  const pollInterval = config.pollIntervalMs || 10000; // 10 seconds
  const maxWait = config.maxWaitMs || 3600000; // 60 minutes
  const startTime = Date.now();

  // Poll for completion
  while (true) {
    const result = await client.interactions.get(interaction.id);

    if (result.status === 'completed') {
      console.log('Research completed!');

      // Extract the final output
      const outputs = result.outputs || [];
      const lastOutput = outputs[outputs.length - 1];
      const reportText = lastOutput?.text || 'No output text available';

      // Format output
      let formattedOutput = `# Gemini Deep Research: ${config.questionId}\n\n`;
      formattedOutput += `**Model:** deep-research-pro-preview-12-2025\n`;
      formattedOutput += `**Generated:** ${new Date().toISOString()}\n`;
      formattedOutput += `**Interaction ID:** ${interaction.id}\n\n`;
      formattedOutput += `---\n\n`;
      formattedOutput += reportText;

      return formattedOutput;
    } else if (result.status === 'failed') {
      throw new Error(`Research failed: ${result.error || 'Unknown error'}`);
    }

    // Check timeout
    if (Date.now() - startTime > maxWait) {
      throw new Error(`Research timed out after ${maxWait / 1000} seconds`);
    }

    console.log(`Status: ${result.status} - waiting ${pollInterval / 1000}s...`);
    await new Promise(resolve => setTimeout(resolve, pollInterval));
  }
}

async function main() {
  const args = process.argv.slice(2);

  if (args.length < 2) {
    console.log('Usage: yarn gemini <question-id> <prompt-file-or-text>');
    console.log('Example: yarn gemini q1-calibration-techniques q1-calibration-techniques/prompt.md');
    process.exit(1);
  }

  const questionId = args[0];
  const promptArg = args[1];

  // Check if prompt is a file path or direct text
  let prompt: string;
  const promptPath = join(ROOT_DIR, promptArg);
  if (existsSync(promptPath)) {
    prompt = readFileSync(promptPath, 'utf-8');
  } else {
    prompt = promptArg;
  }

  try {
    const result = await runDeepResearch({
      questionId,
      prompt,
    });

    // Save to output file
    const outputDir = join(ROOT_DIR, questionId);
    if (!existsSync(outputDir)) {
      mkdirSync(outputDir, { recursive: true });
    }
    const outputPath = join(outputDir, 'gemini.md');
    writeFileSync(outputPath, result);
    console.log(`\nResearch saved to: ${outputPath}`);
  } catch (error) {
    console.error('Error running deep research:', error);
    process.exit(1);
  }
}

// Export for programmatic use
export { runDeepResearch, ResearchConfig };

// Run if called directly
main();
