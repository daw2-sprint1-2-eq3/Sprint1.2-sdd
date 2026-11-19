# Review — Sprint CA weight validation

Reviewer: YoHerman
Date: 2026-11-19
Reviewed files: `generated/SprintCaWeightValidator.php`, `generated/test_sprint_ca_weights.php`
Spec: [spec.md](spec.md)

## What I checked

- [x] Each weight is an int from 0 to 100. Anything else throws `InvalidArgumentException`.
- [x] An empty map fails, and the message includes the RA id.
- [x] A non-empty map is valid only when the weights sum to 100. The message shows the expected total and the actual total.
- [x] The comment states that a repeated criterion id keeps the last value.
- [x] The test script covers every row of spec section 7.
- [x] `php -l` on both files and `php generated/test_sprint_ca_weights.php` from the repo root.

## Result

SPEC MET.

Each weight must be an integer from 0 to 100, an empty map fails with the RA id in the message, and any other total than 100 fails with the expected and actual sums. The test script covers spec section 7.

## Edits after the agent

None.
