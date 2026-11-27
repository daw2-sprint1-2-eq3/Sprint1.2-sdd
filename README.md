# Sprint 1.2 — reusable SDD method

Clone this repository to reuse the team's spec-driven method. It holds the agent rules, every document those rules name, and one finished slice. The grade application stays in [Sprint1.2-app](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app). Do not copy that application here.

## Follow it

1. Read [the methodology](docs/ai-assisted-methodology.md). It is the method: tools, phases, validation, and GitHub.
2. An agent reads [AGENTS.md](AGENTS.md) before it writes or reviews code. The files that page names are in this repository.
3. Record the tool in [the comparison](docs/ai-agents-comparison.md) before generating a slice. Change that file first if the tool changes.
4. For one feature, add `docs/sdd/<slice>/` with `spec.md`, `plan.md`, `prompts/01-....md`, `review.md`, and `conclusions.md`. Copy the shape from [sprint CA weights](docs/sdd/sprint-ca-weights/README.md).
5. A person writes the spec, the plan, and the review. The agent writes only the files named in the plan. Generated evidence for this example is in `generated/`.
6. One issue, one branch `issue/<number>-<short-slug>`, one pull request. Issue and review format: [CONTRIBUTING.md](CONTRIBUTING.md). Commands: [docs/github-workflow.md](docs/github-workflow.md).
7. In this evidence repository, branch from `main` and open the pull request against `main`. In a product repository, code targets `develop`. A documentation-only change may target `main`.

## On another product

Copy this repository, then replace the grade-app decisions:

- [docs/requirements-and-decisions.md](docs/requirements-and-decisions.md) section 8
- [docs/agents/domain.md](docs/agents/domain.md)

Keep `docs/sdd/sprint-ca-weights/` as the example of the folder shape, or delete it after the first new slice matches that shape.

Paths such as `app/`, `database/`, and `tests/README.md` belong to the product repository. This repository keeps the rules, not that code. The sprint implementation plan (`docs/Plan de implementación - Sprint 1.2.md`) stays in Sprint1.2-app; write a new plan for another product.

Do not add `DAW2 26-27 - Programació didàctica.md`. Open the teaching programme only when someone asks for it.

## Files the agent reads

| When | File |
| --- | --- |
| The session rules | [AGENTS.md](AGENTS.md) |
| Issue, labels, pull request | [CONTRIBUTING.md](CONTRIBUTING.md) |
| Branch and commit | [docs/github-workflow.md](docs/github-workflow.md) |
| Product rules | [docs/requirements-and-decisions.md](docs/requirements-and-decisions.md) §8 |
| Terms | [docs/agents/domain.md](docs/agents/domain.md) |
| Where code goes | [docs/agents/architecture.md](docs/agents/architecture.md) |
| PHP and SQL | [docs/agents/php-pdo.md](docs/agents/php-pdo.md) |
| Route, controller, CRUD | [docs/agents/mvc-recipes.md](docs/agents/mvc-recipes.md) |
| Auth and output | [docs/agents/security.md](docs/agents/security.md) |
| Tests and a pull request | [docs/agents/testing-dod.md](docs/agents/testing-dod.md) |
| Review existing code | [docs/agents/known-debt.md](docs/agents/known-debt.md) |
| Run the grade app in Docker | [docker/DOCKER.md](docker/DOCKER.md) |

Also here, because those documents link to them: [team agreements](docs/Initial_Team_Agreements.md) and the [Floci/RDS check](docs/floci-rds.md).

`docker/DOCKER.md` includes the classroom demo passwords. They are for the class app, not for a public deployment.
