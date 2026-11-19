# Prompt 01 — Generate the sprint CA weight validator

Tool: pending the decision in `docs/ai-agents-comparison.md`.
Spec: [../spec.md](../spec.md)
Plan: [../plan.md](../plan.md)

## Prompt

Implement the sprint CA weight validation slice from `docs/sdd/sprint-ca-weights/spec.md` and `plan.md`.

Create only these two files:

- `generated/SprintCaWeightValidator.php`
- `generated/test_sprint_ca_weights.php`

Rules:

- PHP 8.2+, `declare(strict_types=1);` in both files.
- No framework, no database, no HTTP. School-level PHP: loops, `array_*`, exceptions.
- Code and comments in English.
- `sum_sprint_ca_weights(array $weights): int` rejects any value that is not an int, or that is < 0 or > 100, with `InvalidArgumentException`. Then it returns the sum.
- `validate_sprint_ca_weights(int|string $raId, array $weights): true` throws `InvalidArgumentException` when the map is empty (message includes the RA id). It uses the sum function. If the sum is not 100, the message includes the expected total and the actual total. On success it returns `true`.
- Cover every row of spec section 7 in the test script. Expected failures use try/catch. On the first failure, `exit(1)`. On success, `echo "OK\n"; exit(0);`.
- Do not copy files from Sprint1.2-app. Do not add other PHP files.

## What the agent returned

Run on 2026-11-19 with Cursor. No human edit after generation.

Files created:

- `generated/SprintCaWeightValidator.php` — `sum_sprint_ca_weights()` and `validate_sprint_ca_weights()`.
- `generated/test_sprint_ca_weights.php` — the six cases from spec section 7.

Commands from the repository root:

```sh
php -l generated/SprintCaWeightValidator.php
php -l generated/test_sprint_ca_weights.php
php generated/test_sprint_ca_weights.php
```

The tool named in the comparison document is still pending. This run used Cursor, which `plan.md` already lists as the generation tool.

