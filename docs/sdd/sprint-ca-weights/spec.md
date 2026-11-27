# Spec — Sprint CA weight validation (SDD slice)

Status: implemented and verified for issue #5
Author: Team 3  
Date: 2026-11-04

## 1. Problem

In the grade-management app, each sprint assigns an integer weight to every
assessment criterion (CA) that belongs to a learning outcome (RA) in that
sprint. Those weights must sum to **100** for each RA within that sprint.

This repository does **not** implement the full weight editor UI or database.
It implements only the **validation rules** that the real app will rely on when
saving weights.

Product reference (read-only):
[requirements §8](../../requirements-and-decisions.md)
(`sprint_criterion.weight`: sum 100 per RA and sprint).

## 2. Users and context

| Actor | Need |
| --- | --- |
| Admin / professor (in the real app) | Enter or adjust CA weights in the sprint weight window without invalid totals. |
| Developer (here) | Pure PHP validation that can be unit-tested in isolation. |

## 3. Inputs

- **RA identifier** (string or int): labels the group of CAs; only used for error messages.
- **Weights map**: associative array `criterion_id => weight`, where:
  - `criterion_id` is a non-empty string or positive integer key.
  - `weight` is an **integer** in the inclusive range **0–100**.

Empty map is allowed as input (see rules).

## 4. Rules

1. Each weight must be an integer between 0 and 100 inclusive.
2. For a non-empty map, the sum of all weights must equal **100**.
3. If the map is empty, validation **fails** with a clear message (at least one CA is required when configuring weights).
4. Duplicate criterion IDs are not possible in a PHP associative array; if the caller passes duplicate keys, last value wins — document that callers must not rely on duplicates.

## 5. Outputs

Two public functions in generated PHP (names fixed in the implementation plan):

| Function | Success | Failure |
| --- | --- | --- |
| `validate_sprint_ca_weights(int|string $raId, array $weights): true` | Returns `true` | Throws `\InvalidArgumentException` with an English message mentioning `$raId` when useful |
| `sum_sprint_ca_weights(array $weights): int` | Returns integer sum | Same range checks as rule 1 before summing |

Messages must be stable enough for tests (exact string or predictable substring).

## 6. Non-functional

- PHP 8.2+ compatible, `declare(strict_types=1);`
- No framework, no database, no HTTP.
- School-level PHP only (loops, `array_*`, exceptions).
- Code and comments in English.

## 7. Acceptance tests (behaviour)

| Case | Input | Expected |
| --- | --- | --- |
| Valid split | RA `1`, `[10, 40, 50]` | `validate_*` → `true`, sum → 100 |
| Valid zeros allowed | RA `1`, `[0, 100]` | valid |
| Sum not 100 | RA `2`, `[30, 30]` | exception |
| Weight out of range | RA `1`, `[101]` | exception |
| Non-integer weight | RA `1`, `[50, '50']` or `[50.5]` | exception (strict types / type check) |
| Empty map | RA `1`, `[]` | exception |

## 8. Out of scope

- Sprint-level weights (`sprints.weight`), project RA weights (`project_outcome.weight`).
- Renormalisation, grade calculation, UI, persistence, auth.
- Copying files from Sprint1.2-app.

## 9. Done when

- [x] `plan.md` lists files and steps.
- [x] Prompts and generated code implement this spec.
- [x] Tests cover section 7.
- [x] Human review and conclusions recorded.
