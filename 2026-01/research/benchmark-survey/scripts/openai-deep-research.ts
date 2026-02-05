/**
 * OpenAI Deep Research Script
 *
 * Uses o3-deep-research-2025-06-26 via the Responses API with background mode.
 * Background mode allows long-running tasks without connection timeouts.
 *
 * Usage: yarn openai <question-id> <prompt-file-or-text>
 * Example: yarn openai q1-calibration q1-calibration/prompt.md
 */

import OpenAI from 'openai';
import { readFileSync, writeFileSync, existsSync, mkdirSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';
import { homedir } from 'os';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT_DIR = join(__dirname, '..');
const SKILL_ENV_PATH = join(homedir(), '.claude', 'skills', 'deep-research', '.env');

/**
 * Load an environment variable, falling back to the central skill .env file
 */
function getEnvVar(name: string): string | undefined {
  // First check process.env (includes local .env via tsx --env-file)
  if (process.env[name]) {
    return process.env[name];
  }

  // Fall back to central skill .env
  if (existsSync(SKILL_ENV_PATH)) {
    const envContent = readFileSync(SKILL_ENV_PATH, 'utf-8');
    const match = envContent.match(new RegExp(`^${name}=(.+)$`, 'm'));
    if (match) {
      return match[1].trim();
    }
  }

  return undefined;
}

interface ResearchConfig {
  questionId: string;
  prompt: string;
  contextFile?: string;
  pollIntervalMs?: number;
  maxWaitMs?: number;
}

async function sleep(ms: number): Promise<void> {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function runDeepResearch(config: ResearchConfig): Promise<string> {
  const apiKey = getEnvVar('OPENAI_API_KEY');
  if (!apiKey) {
    console.error('\n❌ OPENAI_API_KEY not found.\n');
    console.error('To set your key, paste it in Claude Code chat:');
    console.error('  "My OpenAI key is sk-proj-xxx"');
    console.error('\nOr manually create ~/.claude/skills/deep-research/.env with:');
    console.error('  OPENAI_API_KEY=sk-proj-xxx\n');
    process.exit(1);
  }

  const client = new OpenAI({
    apiKey,
  });

  // Load context if provided
  let contextContent = '';
  if (config.contextFile && existsSync(config.contextFile)) {
    contextContent = readFileSync(config.contextFile, 'utf-8');
  } else if (existsSync(join(ROOT_DIR, 'context.md'))) {
    contextContent = readFileSync(join(ROOT_DIR, 'context.md'), 'utf-8');
  }

  const systemPrompt = contextContent
    ? `${contextContent}\n\n---\n\nYou are conducting research to help with the above context.`
    : 'You are a research assistant conducting comprehensive literature review and synthesis.';

  console.log(`Starting OpenAI deep research for: ${config.questionId}`);
  console.log(`Prompt: ${config.prompt.substring(0, 100)}...`);

  // Start the research with background mode enabled
  let response = await client.responses.create({
    model: 'o3-deep-research-2025-06-26',
    input: [
      {
        role: 'developer',
        content: [
          {
            type: 'input_text',
            text: systemPrompt,
          },
        ],
      },
      {
        role: 'user',
        content: [
          {
            type: 'input_text',
            text: config.prompt,
          },
        ],
      },
    ],
    reasoning: {
      summary: 'detailed',
    },
    tools: [
      {
        type: 'web_search_preview',
      },
    ],
    background: true, // Enable background mode for long-running tasks
  });

  console.log(`Research started with ID: ${response.id}`);
  console.log(`Initial status: ${response.status}`);

  const pollInterval = config.pollIntervalMs || 10000; // 10 seconds
  const maxWait = config.maxWaitMs || 3600000; // 60 minutes
  const startTime = Date.now();

  // Poll for completion
  while (response.status === 'queued' || response.status === 'in_progress') {
    // Check timeout
    if (Date.now() - startTime > maxWait) {
      throw new Error(`Research timed out after ${maxWait / 1000} seconds`);
    }

    console.log(`Status: ${response.status} - waiting ${pollInterval / 1000}s...`);
    await sleep(pollInterval);

    // Retrieve updated status
    response = await client.responses.retrieve(response.id);
  }

  console.log(`Final status: ${response.status}`);

  if (response.status === 'failed') {
    throw new Error(`Research failed: ${JSON.stringify(response)}`);
  }

  if (response.status === 'cancelled') {
    throw new Error('Research was cancelled');
  }

  // Extract the final report
  const output = response.output;
  const lastOutput = output[output.length - 1];

  let reportText = '';
  let citations: Array<{ title: string; url: string; startIndex: number; endIndex: number }> = [];

  // Try to get output_text directly if available
  if ((response as any).output_text) {
    reportText = (response as any).output_text;
  } else if (lastOutput && 'content' in lastOutput && Array.isArray(lastOutput.content)) {
    const textContent = lastOutput.content.find((c: unknown) =>
      c && typeof c === 'object' && 'type' in c && (c as { type: string }).type === 'output_text'
    ) as { text?: string; annotations?: Array<{ title: string; url: string; start_index: number; end_index: number }> } | undefined;

    if (textContent) {
      reportText = textContent.text || '';

      if (textContent.annotations) {
        citations = textContent.annotations.map((a) => ({
          title: a.title,
          url: a.url,
          startIndex: a.start_index,
          endIndex: a.end_index,
        }));
      }
    }
  }

  if (!reportText) {
    // Fallback: try to extract text from any content
    console.log('Debug - Full response structure:', JSON.stringify(response, null, 2).substring(0, 2000));
    reportText = 'No output text found. See debug output above.';
  }

  // Format output with citations
  let formattedOutput = `# OpenAI Deep Research: ${config.questionId}\n\n`;
  formattedOutput += `**Model:** o3-deep-research-2025-06-26\n`;
  formattedOutput += `**Generated:** ${new Date().toISOString()}\n`;
  formattedOutput += `**Response ID:** ${response.id}\n\n`;
  formattedOutput += `---\n\n`;
  formattedOutput += reportText;

  if (citations.length > 0) {
    formattedOutput += `\n\n---\n\n## Sources\n\n`;
    const uniqueCitations = [...new Map(citations.map(c => [c.url, c])).values()];
    uniqueCitations.forEach((c, i) => {
      formattedOutput += `${i + 1}. [${c.title}](${c.url})\n`;
    });
  }

  return formattedOutput;
}

async function main() {
  const args = process.argv.slice(2);

  if (args.length < 2) {
    console.log('Usage: yarn openai <question-id> <prompt-file-or-text>');
    console.log('Example: yarn openai q1-calibration-techniques q1-calibration-techniques/prompt.md');
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
    const outputPath = join(outputDir, 'openai.md');
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
