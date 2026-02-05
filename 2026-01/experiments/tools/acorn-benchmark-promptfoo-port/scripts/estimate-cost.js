#!/usr/bin/env node
/**
 * Simple cost estimator for promptfoo evaluations.
 * Run before `npx promptfoo eval` to get order-of-magnitude cost estimate.
 *
 * Usage: node scripts/estimate-cost.js
 */

const fs = require('fs');
const yaml = require('yaml');
const path = require('path');

// Rough pricing per 1M tokens (input + output averaged, as of Jan 2026)
const PRICING = {
  // OpenAI
  'gpt-4o': 5,           // ~$2.5 input, ~$10 output → avg ~$5
  'gpt-5.2': 8,          // estimate
  'gpt-5.2-pro': 30,     // estimate - reasoning model
  'gpt-5-mini': 1,       // estimate
  // Anthropic
  'claude-opus': 30,     // ~$15 input, ~$75 output → avg ~$30
  'claude-sonnet': 6,    // ~$3 input, ~$15 output
  // Google (free tier often, but estimate if paid)
  'gemini-3-pro': 5,     // estimate
  'gemini-3-flash': 1,   // estimate
  'gemini-flash-lite': 0.5,
  // Default for unknown
  'default': 10,
};

function getModelPrice(providerId) {
  const id = providerId.toLowerCase();
  for (const [model, price] of Object.entries(PRICING)) {
    if (id.includes(model.replace('-', ''))) return price;
    if (id.includes(model)) return price;
  }
  // Check for opus/sonnet/etc
  if (id.includes('opus')) return PRICING['claude-opus'];
  if (id.includes('sonnet')) return PRICING['claude-sonnet'];
  if (id.includes('flash-lite')) return PRICING['gemini-flash-lite'];
  if (id.includes('flash')) return PRICING['gemini-3-flash'];
  if (id.includes('pro') && id.includes('gemini')) return PRICING['gemini-3-pro'];

  return PRICING['default'];
}

async function main() {
  // Load config
  const configPath = path.join(process.cwd(), 'promptfooconfig.yaml');
  if (!fs.existsSync(configPath)) {
    console.error('No promptfooconfig.yaml found');
    process.exit(1);
  }

  const config = yaml.parse(fs.readFileSync(configPath, 'utf8'));

  // Count providers
  const providers = config.providers || [];
  console.log(`\n📊 Cost Estimate for Promptfoo Evaluation\n`);
  console.log(`Providers: ${providers.length}`);

  // Count tests
  let testCount = 0;
  for (const testFile of config.tests || []) {
    if (typeof testFile === 'string' && testFile.startsWith('file://')) {
      const testPath = path.join(process.cwd(), testFile.replace('file://', ''));
      if (fs.existsSync(testPath)) {
        const tests = yaml.parse(fs.readFileSync(testPath, 'utf8'));
        testCount += Array.isArray(tests) ? tests.length : 1;
      }
    }
  }
  console.log(`Test cases: ${testCount}`);

  // Count prompts
  const promptCount = (config.prompts || []).length || 1;
  console.log(`Prompts: ${promptCount}`);

  const totalCalls = providers.length * testCount * promptCount;
  console.log(`Total API calls: ${totalCalls}`);

  // Estimate tokens per call (rough: 2k prompt + 0.5k completion)
  const tokensPerCall = 2500;
  const totalTokens = totalCalls * tokensPerCall;
  console.log(`Est. tokens: ~${(totalTokens / 1e6).toFixed(2)}M`);

  // Calculate cost per provider
  console.log(`\n💰 Estimated costs by provider:\n`);
  let totalCost = 0;

  for (const provider of providers) {
    const id = typeof provider === 'string' ? provider : provider.id;
    const label = provider.label || id;
    const pricePerM = getModelPrice(id);
    const providerCalls = testCount * promptCount;
    const providerTokens = providerCalls * tokensPerCall;
    const cost = (providerTokens / 1e6) * pricePerM;
    totalCost += cost;

    console.log(`  ${label.padEnd(20)} $${cost.toFixed(2)} (${providerCalls} calls @ $${pricePerM}/M tokens)`);
  }

  console.log(`\n  ${'TOTAL'.padEnd(20)} $${totalCost.toFixed(2)}`);

  // Warnings
  console.log('');
  if (totalCost > 10) {
    console.log(`⚠️  WARNING: Estimated cost > $10`);
  } else if (totalCost > 5) {
    console.log(`⚠️  Note: Estimated cost > $5`);
  } else if (totalCost > 1) {
    console.log(`✓ Estimated cost is moderate (~$${totalCost.toFixed(2)})`);
  } else {
    console.log(`✓ Estimated cost is low (~$${totalCost.toFixed(2)})`);
  }
  console.log('');
}

main().catch(console.error);
