# Experiments report

Interactive PHP reports for AI critique experiments.

## PHP compatibility

**Target: PHP 7.4+** (deployed on shared hosting with PHP 7.4)

When writing PHP code:
- Arrow functions `fn() =>` are allowed (PHP 7.4+)
- `array_key_first()` and `array_key_last()` are allowed (PHP 7.4+)
- Do NOT use named arguments (PHP 8.0+)
- Do NOT use match expressions (PHP 8.0+)
- Do NOT use constructor property promotion (PHP 8.0+)
- Do NOT use nullsafe operator `?->` (PHP 8.0+)
- Do NOT use union types in declarations (PHP 8.0+)

## Structure

```
experiments-report/
├── includes/
│   ├── header.php          # Page header, CSS link
│   ├── footer.php          # Page footer
│   ├── functions.php       # Shared utility functions
│   └── components/         # Reusable PHP components
│       ├── critique-card.php
│       └── paper-examples.php
├── content/
│   └── critique-prompt/    # Prose content for critique-prompt-experiment
├── assets/
│   ├── style.css
│   └── critique-filters.js
├── data/                   # Experiment data (JSON, prompts, outputs)
└── *.php                   # Main report pages
```

## Development

Start local server:
```bash
php -S localhost:8080
```

Check syntax before committing:
```bash
php -l critique-prompt-experiment.php
```

## Deployment

Run `./deploy.sh` to build and deploy to the `build/` directory.
