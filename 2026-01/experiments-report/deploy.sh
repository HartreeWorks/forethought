#!/bin/bash
set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Load environment variables
if [ -f "$SCRIPT_DIR/.env" ]; then
  source "$SCRIPT_DIR/.env"
fi
BUILD_DIR="$SCRIPT_DIR/build"
EXPERIMENTS_SRC="$SCRIPT_DIR/../experiments"
RESEARCH_SRC="$SCRIPT_DIR/../research"

# Clean build directory (preserve .git for incremental git-ftp)
# Note: .git contains git-ftp.log which tracks deployed state
mkdir -p "$BUILD_DIR"
if [ -d "$BUILD_DIR/.git" ]; then
  # Keep .git, trash everything else
  for item in "$BUILD_DIR"/*; do
    [ -e "$item" ] && trash "$item"
  done
else
  # Fresh build dir, will init git later
  [ -d "$BUILD_DIR" ] && trash "$BUILD_DIR" && mkdir -p "$BUILD_DIR"
fi

# Copy UI files (all PHP files)
cp "$SCRIPT_DIR"/*.php "$BUILD_DIR/"
cp -r "$SCRIPT_DIR/includes" "$BUILD_DIR/"
cp -r "$SCRIPT_DIR/assets" "$BUILD_DIR/"
cp -r "$SCRIPT_DIR/content" "$BUILD_DIR/"
cp -r "$SCRIPT_DIR/research" "$BUILD_DIR/"

# Bundle data
mkdir -p "$BUILD_DIR/data/experiments/critique-prompt"
mkdir -p "$BUILD_DIR/data/experiments/full-critique-chain-acorn/prompts"

# Critique prompt experiment - Results (JSON scores)
for dir in results-gpt results-gpt-cb results-gpt-cc results-claude results-claude-cb results-claude-cc results-gemini results-gemini-cb results-gemini-cc results-gpt41mini; do
  if [ -d "$EXPERIMENTS_SRC/critique-prompt/$dir" ]; then
    cp -r "$EXPERIMENTS_SRC/critique-prompt/$dir" \
          "$BUILD_DIR/data/experiments/critique-prompt/"
  fi
done

# Critique prompt experiment - Parsed critique text
for dir in outputs-gpt outputs-gpt-cb outputs-gpt-cc outputs-claude outputs-gemini outputs-gpt41mini; do
  if [ -d "$EXPERIMENTS_SRC/critique-prompt/$dir/parsed" ]; then
    mkdir -p "$BUILD_DIR/data/experiments/critique-prompt/$dir"
    cp -r "$EXPERIMENTS_SRC/critique-prompt/$dir/parsed" \
          "$BUILD_DIR/data/experiments/critique-prompt/$dir/"
  fi
done

# Critique prompt experiment - Prompts
if [ -d "$EXPERIMENTS_SRC/critique-prompt/prompts" ]; then
  cp -r "$EXPERIMENTS_SRC/critique-prompt/prompts" \
        "$BUILD_DIR/data/experiments/critique-prompt/"
fi

# Crucial questions experiment
mkdir -p "$BUILD_DIR/data/experiments/crucial-questions"

# Crucial questions - Results (JSON scores)
for dir in results-gpt results-gpt-cb results-gpt-cc; do
  if [ -d "$EXPERIMENTS_SRC/crucial-questions/$dir" ]; then
    cp -r "$EXPERIMENTS_SRC/crucial-questions/$dir" \
          "$BUILD_DIR/data/experiments/crucial-questions/"
  fi
done

# Crucial questions - Parsed question text
for dir in outputs-gpt outputs-gpt-cb outputs-gpt-cc; do
  if [ -d "$EXPERIMENTS_SRC/crucial-questions/$dir/parsed" ]; then
    mkdir -p "$BUILD_DIR/data/experiments/crucial-questions/$dir"
    cp -r "$EXPERIMENTS_SRC/crucial-questions/$dir/parsed" \
          "$BUILD_DIR/data/experiments/crucial-questions/$dir/"
  fi
done

# Crucial questions - Prompts
if [ -d "$EXPERIMENTS_SRC/crucial-questions/prompts" ]; then
  cp -r "$EXPERIMENTS_SRC/crucial-questions/prompts" \
        "$BUILD_DIR/data/experiments/crucial-questions/"
fi

# Crucial questions - Grader
if [ -f "$EXPERIMENTS_SRC/crucial-questions/grader-crucial-questions.txt" ]; then
  cp "$EXPERIMENTS_SRC/crucial-questions/grader-crucial-questions.txt" \
     "$BUILD_DIR/data/experiments/crucial-questions/"
fi

# Deep research data (exclude node_modules, scripts, etc.)
if [ -d "$RESEARCH_SRC/prompt-engineering-deep-research" ]; then
  mkdir -p "$BUILD_DIR/data/research/prompt-engineering-deep-research"
  [ -f "$RESEARCH_SRC/prompt-engineering-deep-research/synthesis.md" ] && \
    cp "$RESEARCH_SRC/prompt-engineering-deep-research/synthesis.md" \
       "$BUILD_DIR/data/research/prompt-engineering-deep-research/"
  [ -d "$RESEARCH_SRC/prompt-engineering-deep-research/query-a-do-prompts-matter" ] && \
    cp -r "$RESEARCH_SRC/prompt-engineering-deep-research/query-a-do-prompts-matter" \
          "$BUILD_DIR/data/research/prompt-engineering-deep-research/"
  [ -d "$RESEARCH_SRC/prompt-engineering-deep-research/query-b-how-to-invest" ] && \
    cp -r "$RESEARCH_SRC/prompt-engineering-deep-research/query-b-how-to-invest" \
          "$BUILD_DIR/data/research/prompt-engineering-deep-research/"
fi

# ACORN grader
if [ -f "$EXPERIMENTS_SRC/full-critique-chain-acorn/prompts/grader-v2-acorn-rubric.txt" ]; then
  cp "$EXPERIMENTS_SRC/full-critique-chain-acorn/prompts/grader-v2-acorn-rubric.txt" \
     "$BUILD_DIR/data/experiments/full-critique-chain-acorn/prompts/"
fi

# Git setup for git-ftp
cd "$BUILD_DIR"

# Init git repo if needed
if [ ! -d ".git" ]; then
  git init
fi

# Commit changes (if any)
git add -A
if git diff --cached --quiet; then
  echo "No changes to deploy"
  exit 0
fi
git commit -m "Deploy $(date +%Y-%m-%d_%H:%M)"

FTP_URL="ftp://89.248.52.130"
REMOTE_ROOT="/files.hartreeworks.org/forethought/2026-01/experiments"

# Deploy with git-ftp
# - catchup: sync state without uploading (after manual FTP upload)
# - (default): push changes, or init if first time
if [ "$1" = "catchup" ]; then
  git ftp catchup \
    --user "ph@hartreeworks.org" \
    --passwd "$FTP_PASS" \
    --remote-root "$REMOTE_ROOT" \
    "$FTP_URL"
  echo "Caught up git-ftp state (no files uploaded)"
else
  # Try push first; if no .git-ftp.log exists, fall back to init
  set +e
  git ftp push \
    --user "ph@hartreeworks.org" \
    --passwd "$FTP_PASS" \
    --remote-root "$REMOTE_ROOT" \
    "$FTP_URL"
  PUSH_EXIT=$?
  set -e

  if [ $PUSH_EXIT -ne 0 ]; then
    echo "Push failed (likely first deploy), using init to upload all files..."
    git ftp init \
      --user "ph@hartreeworks.org" \
      --passwd "$FTP_PASS" \
      --remote-root "$REMOTE_ROOT" \
      "$FTP_URL"
  fi
  echo "Deployed to https://files.hartreeworks.org/forethought/2026-01/experiments/"
fi
