# AGENTS.md

Grade-management MPA. PHP 8.2+, no framework, MVC, PDO, MariaDB.
UI Catalan; code, comments, docs English.

The team is second-year DAW students. Priority: **understand** the work, not finish fast.
School-level PHP only. If a line cannot be explained to the teacher, leave it out.
Do not invent features. If the brief is silent, stop, ask, then record the decision in `docs/requirements-and-decisions.md`.
Terms: `docs/agents/domain.md`. Do not open `DAW2 26-27 - Programació didàctica.md` by default.

## How this file relates to CONTRIBUTING

| Document | Owns |
| --- | --- |
| **[CONTRIBUTING.md](CONTRIBUTING.md)** | GitHub **process**: issue format (nine sections), labels, dependencies, project board, pull requests, review and merge |
| **This file (`AGENTS.md`)** | **Code rules**, checks, and **agent session workflow** (micro-steps below) |
| [`docs/github-workflow.md`](docs/github-workflow.md) | Branch commands, commits, remote, day-to-day git |
| [`docs/requirements-and-decisions.md`](docs/requirements-and-decisions.md) §8 | Sprint 1.2 **product design** (class group, weights, stored grades) |

Read **CONTRIBUTING.md** when you create, update or close an issue or pull request.
Read **AGENTS.md** (this file) when you write or review PHP and when you guide
implementation in micro-steps.

Sprint 1.2 domain: start with `docs/agents/domain.md` for vocabulary. If it
disagrees with **section 8** of `docs/requirements-and-decisions.md`, **section 8
wins** until `domain.md` is updated.

SDD evidence (M0616): keep spec, prompts and generated artefacts in
[Sprint1.2-sdd](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-sdd). Link from
this repo; do not copy the full grade application there (plan issues 1.3–1.5).

## Who drives the work

The team decides. The agent does not auto-advance and does not implement “the next feature” on its own.

1. Propose **one** micro-step: goal, exact path, full file contents or the exact commands, and what each part does.
2. Wait. The team chooses:
   - **ho faig jo** / **a mà**: they write it. Do not create or edit those files in that turn.
   - **fes-ho tu**: apply only that micro-step, then explain each touched file in words they can repeat.
3. After hand work (**revisa**): say what is correct and the exact fix if needed. Then wait.
4. **seguim** / **continua** / **next** means the next micro-step only, not a whole issue.
5. Do not start another step, a refactor, or extra files without that choice.

## GitHub

After a coherent block, say when it is time for an Issue, branch, commit, push, or PR, and why. Do not assume they remember. They run git unless they ask the agent to run a specific command.

**Canonical rules:** [CONTRIBUTING.md](CONTRIBUTING.md) sections 1–4 (issues, labels,
dependencies, pull requests) and [`docs/github-workflow.md`](docs/github-workflow.md)
(commands). This section is a reminder only.

- **One issue = one branch = one pull request.** Split with sub-issues (`2.1a`, …)
  if the work does not fit one session (CONTRIBUTING introduction).
- Issue title: `[<plan-id>] <imperative title>` (CONTRIBUTING §1.1). Branch name:
  `issue/<github-number>-short-slug` (not the plan id in the branch name). Which
  branch to branch **from** and which base the PR **targets**: follow
  [`docs/github-workflow.md`](docs/github-workflow.md) (code → `develop`; docs-only
  exception → `main`).
- Pull request title: `feat:` / `fix:` / `docs:` / `test:` + description +
  `#<issue-number>`. Body must include `Closes #N` (CONTRIBUTING §4).
- Draft PR while in progress; ready for review only when complete and CI is green.
- English commits with the issue number when possible. Suggest the message.
- Do not commit, push, or merge unless they ask.

## Always

- `declare(strict_types=1);` in every PHP file.
- Protected controller actions: guard first; writes POST-only; no SQL.
- Model: SQL + validation; prepared statements only; never concatenate input.
- View: escape dynamic output with `htmlspecialchars()`.
- Schema change: update `database/schema.sql`, `database/schema-mariadb.sql`, seed and tests.
- No frameworks, runtime dependencies, or SPA/API layers without approval; never commit secrets.
- Existing code has known debt: do not copy it; follow `docs/agents/`.

## Read only what you need

If the task touches SQL, auth, schema, routes or tests, read the linked doc first.

| If you are going to... | Read |
|---|---|
| Open or close an issue, labels, PR, or project board | [CONTRIBUTING.md](CONTRIBUTING.md) and `docs/github-workflow.md` |
| Sprint 1.2 weights, class group, stored grade rules | `docs/requirements-and-decisions.md` section 8 |
| Understand the architecture or add a layer | `docs/agents/architecture.md` |
| Write PHP, models, SQL, schema or transactions | `docs/agents/php-pdo.md` |
| Add a route, controller, CRUD, field or validation | `docs/agents/mvc-recipes.md` |
| Touch auth, sessions, permissions or input/output | `docs/agents/security.md` |
| Add tests or prepare a PR | `docs/agents/testing-dod.md` |
| Check domain terms or requirements | `docs/agents/domain.md` |
| Review or refactor existing code | `docs/agents/known-debt.md` |

## Checks

`php tests/run.php` · lint: `find app config database public routes tests -name '*.php' -print0 | xargs -0 -n1 php -l`
Seed: `php database/seed.php` · Docker: see `docker/DOCKER.md`.

## Done

Tests OK, lint OK, both schemas + seed + tests + docs updated, no secrets.
Pull request follows [CONTRIBUTING.md](CONTRIBUTING.md) §4 (template, `Closes #N`,
review). Product rule changes recorded in `docs/requirements-and-decisions.md`.
