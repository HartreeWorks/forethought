#!/bin/bash
# Wrapper script that shows cost estimate before running eval

cd "$(dirname "$0")/.." || exit 1

# Load environment
source .env 2>/dev/null
export OPENAI_API_KEY ANTHROPIC_API_KEY GOOGLE_API_KEY

# Show cost estimate
echo ""
node scripts/estimate-cost.js
echo ""

# Ask for confirmation
read -p "Proceed with evaluation? [y/N] " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Starting evaluation..."
    echo ""
    npx promptfoo@latest eval "$@"
else
    echo "Cancelled."
    exit 0
fi
