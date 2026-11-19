# Generation prompt — Sprint CA weight validation

Agent: OpenAI Codex
Date: 2026-11-19
Model/tool context: Codex in the current ChatGPT coding session; the team uses VS Code.

## Prompt used

Read spec.md and plan.md. Implement only the Sprint CA weight validation slice
described there. Create generated/SprintCaWeightValidator.php and
generated/test_sprint_ca_weights.php. Use strict_types, PHP 8.2-compatible
plain PHP, English code and exception messages, and no framework or database.

Implement sum_sprint_ca_weights(array $weights): int and
validate_sprint_ca_weights(int|string $raId, array $weights): true. Check each
weight with is_int before summing, reject values outside 0–100, reject an empty
map in the validator, and require a total of exactly 100. Include the RA id in
validation errors where useful. Cover every behavior in spec section 7 and the
validation rules for the sum helper. Keep the test standalone and make it exit
non-zero on the first failure and print OK on success.

Do not copy or change the grade application. Run the three PHP commands in
plan.md and report their results.

## Agent output summary

Codex generated the two requested PHP files with stable exception messages and
a standalone test script. It used only the spec and plan for behavior and made
no changes to the grade application.
