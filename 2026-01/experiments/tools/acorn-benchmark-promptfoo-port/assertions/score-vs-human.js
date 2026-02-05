/**
 * Custom MAE (Mean Absolute Error) scoring assertion for ACORN benchmark.
 * Compares LLM ratings against human ratings from ACORN dataset.
 *
 * Returns:
 * - pass: true if MAE < 0.3 (model is reasonably calibrated)
 * - score: 1 - MAE (higher is better, suitable for promptfoo ranking)
 * - namedScores: individual dimension errors for detailed analysis
 */
module.exports = (output, context) => {
  const dimensions = [
    'centrality',
    'strength',
    'correctness',
    'clarity',
    'dead_weight',
    'single_issue',
    'overall',
  ];

  try {
    // Clean markdown code fences if present
    const cleanOutput = output.replace(/```json\n?|\n?```/g, '').trim();
    const modelRatings = JSON.parse(cleanOutput);
    const humanRatings = context.test.metadata.human_ratings;

    if (!humanRatings) {
      return {
        pass: false,
        score: 0,
        reason: 'No human ratings in test metadata',
      };
    }

    const errors = {};
    let totalError = 0;
    let count = 0;

    for (const dim of dimensions) {
      if (humanRatings[dim] !== undefined && modelRatings[dim] !== undefined) {
        const error = Math.abs(modelRatings[dim] - humanRatings[dim]);
        errors[dim] = error;
        totalError += error;
        count++;
      }
    }

    if (count === 0) {
      return {
        pass: false,
        score: 0,
        reason: 'No matching dimensions found between model and human ratings',
      };
    }

    const mae = totalError / count;
    const score = 1 - mae; // Invert so higher is better

    return {
      pass: mae < 0.3,
      score: score,
      reason: `MAE: ${mae.toFixed(3)} across ${count} dimensions`,
      namedScores: {
        mae: mae,
        ...errors,
      },
    };
  } catch (e) {
    return {
      pass: false,
      score: 0,
      reason: `Parse error: ${e.message}`,
    };
  }
};
