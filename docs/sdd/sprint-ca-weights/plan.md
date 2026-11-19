# Plan — Sprint CA weight validation

Implements: [spec.md](spec.md)  
Tool for generation: Cursor (see [docs/ai-agents-comparison.md](../../ai-agents-comparison.md))

## 1. File layout

| Path | Role |
| --- | --- |
| `generated/SprintCaWeightValidator.php` | `sum_sprint_ca_weights()`, `validate_sprint_ca_weights()` |
| `generated/test_sprint_ca_weights.php` | Standalone test script (no PHPUnit); exits 0 on success |
| `prompts/01-generate-validator.md` | Prompt + agent output summary |
| `review.md` | Human review after generation |
| `conclusions.md` | What worked / limits |

No other PHP files required for this slice.

## 2. Implementation order

1. **`sum_sprint_ca_weights(array $weights): int`**
   - Reject non-integer values (use `is_int` on each weight after iteration).
   - Reject values `< 0` or `> 100`.
   - Return sum of weights.

2. **`validate_sprint_ca_weights(int|string $raId, array $weights): true`**
   - If `$weights` is empty → `InvalidArgumentException` (English message, include RA id).
   - Call sum logic (inline or via `sum_sprint_ca_weights`).
   - If sum ≠ 100 → exception with expected vs actual.
   - Return `true` on success.

3. **Tests** in `generated/test_sprint_ca_weights.php`
   - Cover every row in spec §7.
   - Pattern: try/catch for expected failures; `exit(1)` on first failure; `echo "OK\n"; exit(0)`.

4. **Evidence**
   - Save prompt text and a short “what the agent returned” in `prompts/01-generate-validator.md`.
   - Do not commit chat secrets or API keys.

## 3. Validation commands

From repository root:

```sh
php -l generated/SprintCaWeightValidator.php
php -l generated/test_sprint_ca_weights.php
php generated/test_sprint_ca_weights.php
```

All three must succeed before review.

## 4. Definition of done (this slice)

- [x] Both functions match spec §4–§5.
- [x] Test script passes locally.
- [x] Prompt evidence file exists.
- [x] `review.md` lists manual checks and any code edits after the agent.
- [x] `conclusions.md` closes the SDD loop for issue #5.

## 5. Integration with the real app (future, not here)

When the app implements the weight window, it may `require` equivalent logic
or copy the validated rules into a model — that work belongs in Sprint1.2-app,
not in this evidence repo.
