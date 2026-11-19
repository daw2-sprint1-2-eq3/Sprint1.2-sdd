# SDD evidence — Sprint CA weight validation

App context (not copied here): [Sprint1.2-app](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app).
Related app issue: [#5 — Run one SDD feature](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app/issues/5).

## Scope of this slice

Validate integer weights (0–100) for assessment criteria (CA) within one learning
outcome (RA) in one sprint: they must sum to **100**.

## Evidence trail

| Step | Artifact | Status |
| --- | --- | --- |
| 1 | [spec.md](spec.md) | done |
| 2 | [plan.md](plan.md) | done |
| 3 | [Generation prompt](prompts/01-generate-validator.md) | done |
| 4 | [Validator](generated/SprintCaWeightValidator.php) and [tests](generated/test_sprint_ca_weights.php) | done |
| 5 | [review.md](review.md) | done |
| 6 | [conclusions.md](conclusions.md) | done |

## Domain reference (read-only)

Weights rules in the real product are documented in the app repo
(`docs/requirements-and-decisions.md`, weight sections). This repository only
implements and tests the small validation slice above.
