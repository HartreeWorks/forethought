/**
 * Test script to verify API connections
 */

import OpenAI from 'openai';
import { GoogleGenAI } from '@google/genai';

async function testOpenAI() {
  console.log('\n=== Testing OpenAI ===');
  const client = new OpenAI();

  console.log('Has responses API:', 'responses' in client);
  console.log('responses.create:', typeof (client.responses as any).create);

  // Test with a simple chat completion first
  try {
    const response = await client.chat.completions.create({
      model: 'gpt-4o-mini',
      messages: [{ role: 'user', content: 'Say "API working" and nothing else.' }],
      max_tokens: 10,
    });
    console.log('OpenAI chat test:', response.choices[0]?.message?.content);
  } catch (error: any) {
    console.error('OpenAI error:', error.message);
  }
}

async function testGemini() {
  console.log('\n=== Testing Gemini ===');
  const client = new GoogleGenAI({ apiKey: process.env.GEMINI_API_KEY });

  console.log('Has interactions:', 'interactions' in client);

  // Check interactions API
  const interactions = client.interactions as any;
  console.log('interactions.create:', typeof interactions.create);
  console.log('interactions.get:', typeof interactions.get);

  // Test with a simple generation
  try {
    const response = await client.models.generateContent({
      model: 'gemini-2.0-flash',
      contents: 'Say "API working" and nothing else.',
    });
    console.log('Gemini test:', response.text);
  } catch (error: any) {
    console.error('Gemini error:', error.message);
  }
}

async function main() {
  await testOpenAI();
  await testGemini();
}

main().catch(console.error);
