# Contributing

This document is the canonical format for issues, labels and pull requests in
this repository. Follow it when you create the Sprint 1.2 backlog and when you
implement any issue.

Related documents:

- Plan: `docs/Plan de implementación - Sprint 1.2.md`. It owns the sprint scope,
  the splits, the week and the priority of each issue; this document does not
  repeat them.
- Decisions: [`docs/requirements-and-decisions.md`](docs/requirements-and-decisions.md),
  section 8.
- Branch, commit and CI rules: [`docs/github-workflow.md`](docs/github-workflow.md).
- Code rules and agent micro-step workflow: [`AGENTS.md`](AGENTS.md).

### Split of responsibilities

| Document | Owns |
| --- | --- |
| **CONTRIBUTING.md** (this file) | Issue format, labels, dependencies, project board, pull requests, review and merge |
| **[AGENTS.md](AGENTS.md)** | PHP/MVC/security rules, test commands, agent session protocol (`ho faig jo` / `fes-ho tu`), links to `docs/agents/` |
| [`docs/github-workflow.md`](docs/github-workflow.md) | Git commands, branch naming, team roles on GitHub |
| [`docs/requirements-and-decisions.md`](docs/requirements-and-decisions.md) §8 | Sprint 1.2 product decisions (weights, class group, grade storage) |

Before opening a pull request, read **both** this file and **AGENTS.md**. Agents
must follow CONTRIBUTING for GitHub and AGENTS for code; neither file replaces
the other.

The rule is simple: **one issue = one branch = one pull request**. If an issue
does not fit in one session, split it into sub-issues under the same plan ID
(`2.1a`, `2.1b`, ...), not into an unrelated new issue.

## 1. Issue format

### 1.1 Title

```text
[<plan-id>] <short imperative title>
```

Examples:

```text
[0.1] Close the RA/CA weighting and grade model
[2.1a] Add class group tables
[3.4] Configure project modules and RA weights
```

The plan ID comes first so the ID is visible and easy to search. The GitHub
issue number is used only in branches, commits and `Closes #N`, never in the
title.

### 1.2 Body

Create issues with the `Task` Issue Form. The form is created in issue 1.1;
until then, copy the sections from the example in section 7.1. The form
produces these sections in this order:

| Section | Required | What to write |
| --- | --- | --- |
| Objective | yes | One or two sentences: what is true after this issue is closed. |
| Context | yes | Why it is needed; link the plan section or the decision record. |
| What to do | yes | Checkbox list of concrete changes. |
| Acceptance criteria | yes | Checkbox list of verifiable results. No "it works". |
| Files | yes | Paths expected to change. It is a forecast, not a limit. |
| How to test | yes | Exact command or manual flow. |
| Dependencies | yes | `Depends on:` and `Blocks:` with issue numbers, or `None`. |
| Estimate | yes | `Half session`, `1 session` or `2 sessions (split)`. |
| Out of scope | no | What this issue deliberately does not do. |

Rules:

- One assignee, set in the sidebar. The team can help, but one person owns the
  issue.
- Self-contained: a teammate can close the issue without opening a sibling issue
  of the sprint. `Out of scope` says what the sibling split does not do here.
- Set the milestone to `Sprint 1.2`.
- Acceptance criteria must be checkable by another teammate without asking you.
- If the issue touches SQL, auth, routes, schema or tests, name the document
  you read from **AGENTS.md** (table “Read only what you need”) and
  **CONTRIBUTING.md** if you changed issue or PR process.
- If the brief is silent, stop and ask. Do not invent a feature.
- Keep the issue small enough for one session. If it needs two, edit the issue
  and say how it will split (e.g. `2.1a`/`2.1b`) before opening the
  pull request.

### 1.3 Definition of ready

An issue is ready when:

- [ ] The title starts with the plan ID.
- [ ] The objective and the context are clear.
- [ ] The acceptance criteria are checkboxes.
- [ ] The files and the test command are named.
- [ ] The dependencies are linked.
- [ ] The labels are applied (section 2).
- [ ] The project fields are set (section 5).
- [ ] One assignee is set in the sidebar.
- [ ] The milestone is `Sprint 1.2`.
- [ ] The estimate is one session, or it says how it will split.

## 2. Labels

Every issue has:

- exactly one `type:*` label;
- exactly one `phase:*` label;
- one or two `area:*` labels;
- exactly one `prio:*` label;
- `status:blocked` only while the issue is blocked.

### 2.1 Type

| Label | Color | Use when |
| --- | --- | --- |
| `type:feature` | `#1f883d` | The issue adds behaviour, a screen or a capability required by the plan. |
| `type:docs` | `#0969da` | The issue changes documentation, decisions or templates only. |
| `type:test` | `#8250df` | The issue adds tests, fixtures or CI checks. |
| `type:refactor` | `#6e7781` | The issue changes internal code without new behaviour. |
| `type:fix` | `#cf222e` | The issue fixes a bug found during development or the demo. |

### 2.2 Area

| Label | Color | Use when |
| --- | --- | --- |
| `area:database` | `#d4a72c` | Schema, seed, SQL or migrations. |
| `area:backend` | `#bf8700` | Models, controllers, routes and validation. |
| `area:frontend` | `#54aeff` | Views, CSS and JavaScript. |
| `area:qa` | `#a475f9` | Tests, manual checks and acceptance. |
| `area:devops` | `#8c959f` | Docker, CI, environment and repository setup. |

### 2.3 Phase

| Label | Color | Phase |
| --- | --- | --- |
| `phase:0` | `#d0d7de` | Decisions and scope. |
| `phase:1` | `#bfd4f2` | Team, GitHub and SDD. |
| `phase:2` | `#a5d6ff` | Domain: weights and derived grades. |
| `phase:3` | `#7ee787` | Screens: class group, module, weights, breakdown. |
| `phase:4` | `#56d364` | Server filters and client. |
| `phase:5` | `#2da44e` | Quality, documentation and delivery. |

### 2.4 Priority

| Label | Color | Use when |
| --- | --- | --- |
| `prio:must` | `#b60205` | Required for the sprint; it blocks the demo. |
| `prio:should` | `#fbca04` | Expected in the sprint; it can slip only with a team decision. |
| `prio:optional` | `#c5def5` | Backlog for the next sprint (4.5–4.7 and 5.2). |

The plan (section 7.4) decides which issue carries which priority. This document
only defines the labels.

### 2.5 Status

| Label | Color | Use when |
| --- | --- | --- |
| `status:blocked` | `#000000` | The issue cannot continue. It always goes with Status = `Blocked` and a comment. |

Do not create `status:in-progress` or `status:in-review`. The project board
Status field owns those states.

## 3. Dependencies and blocked work

Dependencies use three layers on purpose:

1. **Native relationship.** In the issue sidebar, use *Mark as blocked by* and
   *Mark as blocking* when the repository shows those options. This is the
   source of truth.
2. **Body.** Write `Depends on: #12` and `Blocks: #14` in the Dependencies
   section, because the Issue Form cannot create the native link.
3. **Label and column.** Add `status:blocked` so the repository issue list
   shows it, and set Status = `Blocked` so the board shows it without opening
   the issue.

### 3.0 Hard and soft dependencies

Not every dependency means the same thing. Before blocking an issue, decide
which kind it is:

| Kind | Meaning | What to do |
| --- | --- | --- |
| Hard | The issue needs the other one's code or schema to work at all. | Block it: three layers. |
| Soft (merge order) | Both issues edit the same files (`database/schema.sql`, `database/schema-mariadb.sql`, `database/seed.php`) but neither needs the other's logic. | Do not block it. Land one pull request first and merge the second on top. |
| Soft (documentation) | The dependency is only a convention the other issue defines. | Do not block it. |

Only a hard dependency takes the `Blocked` column. A shared file is an ordering
problem for the reviewer, not a reason to stop a teammate.

### 3.1 When you block an issue

- [ ] Add the native *blocked by* link.
- [ ] Write `Depends on: #12` in the body.
- [ ] Add the `status:blocked` label.
- [ ] Set the project Status to `Blocked`.
- [ ] Comment why it is blocked and what will unblock it.

### 3.2 When it unblocks

- [ ] Remove the native link if it no longer applies.
- [ ] Remove the `status:blocked` label.
- [ ] Set the project Status to `Todo` or `In Progress`.
- [ ] Comment what changed.

The `Blocked` column is not a parking lot for "not started". It means exactly
"cannot continue".

## 4. Pull request format

### 4.1 Title

```text
<type>: <short description> #<issue-number>
```

Use `feat`, `fix`, `docs`, `test` or `refactor`:

```text
feat: add class group tables #12
fix: reject a team instrument in an individual sprint #18
docs: document the Docker reset #21
test: cover team grades #24
```

### 4.2 Body

Use the pull request template. It contains these sections:

| Section | What to write |
| --- | --- |
| What changed | A short summary in plain English. |
| Issue | `Closes #12`. One issue per pull request. |
| How it was checked | Commands, screens and manual flow. |
| Reviewer focus | The file or decision the reviewer should look at first. |
| Screenshots | UI changes only; before and after. |
| Risks and rollback | What could break and how to undo it. |
| Checklist | Tests, lint, docs, secrets and language. |

### 4.3 Review and merge rules

- Open a **Draft Pull Request** while the work is in progress. Keep using the
  same branch and pull request for later commits.
- Mark it **Ready for review** only when the change is complete and CI is green.
- At least one teammate reviews. Address feedback on the same branch.
- Merge only when CI is green, the review is approved, the branch is up to date
  with `main` and the change has been checked in the browser.
- Do not merge your own pull request without a review.
- Delete the branch after merging.
- Never commit `.env`, `database/database.sqlite`, logs or real credentials.

## 5. GitHub Projects

Project: `Sprint 1.2 — Team 3`, at repository level and linked to this
repository. The plan ID in the issue title is the link back to the plan.

### 5.1 Fields

| Field | Type | Values |
| --- | --- | --- |
| Status | Single select | `Todo`, `Blocked`, `In Progress`, `In Review`, `Done` |
| Phase | Single select | `0`, `1`, `2`, `3`, `4`, `5` |
| Week | Single select | `1`, `2`, `3`, `4`, `5`, `6` |
| Estimate | Single select | `Half session`, `1 session`, `2 sessions (split)` |
| Plan ID | Text | `0.1`, `1.1`, `2.1a`, ... |

Labels are the source of truth for type, area and priority. Do not duplicate
them as project fields.

The week comes from the plan calendar (section 7.1). It is a recommendation: it
can change; the hard dependency cannot.

### 5.2 Views

| View | Layout | Group or filter |
| --- | --- | --- |
| Kanban | Board | Group by Status |
| Backlog | Table | Group by Phase, sort by Plan ID |
| Week | Board | Group by Week |
| Blocked | Table | `status:Blocked` |
| My work | Table | `assignee:@me` |

The `status:blocked` label and the `Blocked` column are both required. The
column is the board view; the label is what you can filter in the repository
issue list and on the cards.

### 5.3 Built-in workflows

Activate these in the project settings:

| Workflow | Setting |
| --- | --- |
| Auto-add to project | `is:issue is:open` |
| Item closed | Status = `Done` |
| Item reopened | Status = `Todo` |
| Auto-add sub-issues | On, if the plan splits an issue into sub-issues |
| Pull request merged | Status = `Done`, only if pull requests are added to the project |
| Auto-archive items | Off during the sprint |

Recommended: keep pull requests out of the project. A pull request with
`Closes #12` closes the issue, and **Item closed** moves it to `Done`. That is
enough. No custom Actions are needed for the board.

### 5.4 Setup checklist (issue 1.1)

- [ ] Create the repository project and link it to this repository.
- [ ] Create the five Status options, including `Blocked`.
- [ ] Create the Phase, Week, Estimate and Plan ID fields.
- [ ] Create the five views.
- [ ] Activate the built-in workflows in section 5.3.
- [ ] Create the sprint milestone and assign every issue to it.
- [ ] Create issue `0.1` first and check that it lands in `Todo`.
- [ ] Add labels and project fields to the rest of the issues.

## 6. Checklists

### 6.1 Before creating an issue

- [ ] The plan ID is in the title.
- [ ] The objective is one or two sentences.
- [ ] Every acceptance criterion is checkable.
- [ ] Files and test command are named.
- [ ] Dependencies are linked in the body.
- [ ] Labels are applied.
- [ ] Project fields are set.
- [ ] The milestone is `Sprint 1.2`.

### 6.2 Before opening a pull request

- [ ] The branch is `issue/<number>-<short-slug>` and comes from `main`.
- [ ] `php tests/run.php` passes.
- [ ] PHP lint passes:

  ```sh
  find app config database public routes tests -name '*.php' -print0 \
    | xargs -0 -n1 php -l
  ```

- [ ] Both schemas are updated when the schema changed.
- [ ] Seed and tests are updated when the schema or behaviour changed.
- [ ] Documentation is updated when setup or behaviour changed.
- [ ] No `.env`, database file, log or credential is staged.
- [ ] UI text is in Catalan; code, comments and docs are in English.

### 6.3 Before merging

- [ ] CI is green.
- [ ] One teammate approved the pull request.
- [ ] The branch is up to date with `main`.
- [ ] The change was checked in the browser, not only by the tests.
- [ ] The issue is linked with `Closes #N`.
- [ ] The issue is in `Done` and the branch is deleted after merging.

## 7. Examples

Issue and pull request numbers are illustrative.

### 7.1 Issue: 2.1a

```text
[2.1a] Add class group tables
```

```markdown
### Objective
Create the class group tables and link each project to one class group, without
changing professor permissions yet.

### Context
Plan section 5.1 and decision record section 8.2. Issue 2.1 is split because the
schema and the permission refactor do not fit in one session.

### What to do
- [ ] Add `class_groups`, `class_group_module`, `class_group_module_professor`
      and `class_group_student` to both schemas.
- [ ] Add `projects.class_group_id` as a foreign key.
- [ ] Create `ClassGroup` and `ClassGroupModule`.
- [ ] Update the seed with one class group, its modules and its students.
- [ ] Add a test for project membership.

### Acceptance criteria
- [ ] `database/schema.sql` and `database/schema-mariadb.sql` contain the four
      tables and `projects.class_group_id`.
- [ ] A project belongs to exactly one class group.
- [ ] A module can belong to several class groups.
- [ ] `php tests/run.php` passes.

### Files
`database/schema.sql`, `database/schema-mariadb.sql`, `database/seed.php`,
`app/Models/ClassGroup.php`, `app/Models/ClassGroupModule.php`,
`tests/test-class-groups.php`.

### How to test
`php tests/run.php` and `php -l` on every changed file.

### Dependencies
Depends on: #10 (0.1)
Blocks: #13 (2.1b), #14 (2.2)

### Estimate
1 session

### Out of scope
Professor permissions, controllers and views.
```

Labels: `type:feature`, `phase:2`, `area:database`, `area:backend`,
`prio:must`.

### 7.2 Pull request: 2.1a

```text
feat: add class group tables #12
```

```markdown
## What changed
Added the class group tables, the project foreign key, the two models and the
seed data. Professor permissions are unchanged; they move in 2.1b.

## Issue
Closes #12

## How it was checked
- `php tests/run.php`
- PHP lint on every changed file
- Manual: ran the seed and checked the new rows with a SQLite query

## Reviewer focus
`database/schema-mariadb.sql` must match `database/schema.sql`.

## Screenshots
Not applicable (schema and seed only).

## Risks and rollback
The local database must be reset and reseeded. No production data exists.

## Checklist
- [x] Tests pass
- [x] Lint passes
- [x] Both schemas updated
- [x] Seed and tests updated
- [x] No secrets
- [x] UI text in Catalan, code and docs in English
```

## Appendix A. Bulk-create labels with `gh`

Optional. Run from the repository root after `gh auth login`.

```sh
gh label create "type:feature"  --color "1f883d" --description "New behaviour or screen required by the plan" --force
gh label create "type:docs"     --color "0969da" --description "Documentation, decisions or templates only" --force
gh label create "type:test"     --color "8250df" --description "Tests, fixtures or CI checks" --force
gh label create "type:refactor" --color "6e7781" --description "Internal change without new behaviour" --force
gh label create "type:fix"      --color "cf222e" --description "Bug found during development or demo" --force
gh label create "area:database" --color "d4a72c" --description "Schema, seed, SQL or migrations" --force
gh label create "area:backend"  --color "bf8700" --description "Models, controllers, routes and validation" --force
gh label create "area:frontend" --color "54aeff" --description "Views, CSS and JavaScript" --force
gh label create "area:qa"       --color "a475f9" --description "Tests, manual checks and acceptance" --force
gh label create "area:devops"   --color "8c959f" --description "Docker, CI, environment and repository setup" --force
gh label create "phase:0"       --color "d0d7de" --description "Decisions and scope" --force
gh label create "phase:1"       --color "bfd4f2" --description "Team, GitHub and SDD" --force
gh label create "phase:2"       --color "a5d6ff" --description "Domain: weights and derived grades" --force
gh label create "phase:3"       --color "7ee787" --description "Screens: class group, module, weights, breakdown" --force
gh label create "phase:4"       --color "56d364" --description "Server filters and client" --force
gh label create "phase:5"       --color "2da44e" --description "Quality, documentation and delivery" --force
gh label create "prio:must"     --color "b60205" --description "Required for the sprint; blocks the demo" --force
gh label create "prio:should"   --color "fbca04" --description "Expected in the sprint; can slip only with a team decision" --force
gh label create "prio:optional" --color "c5def5" --description "Backlog for the next sprint" --force
gh label create "status:blocked" --color "000000" --description "Cannot continue; see the blocking issue" --force
```
