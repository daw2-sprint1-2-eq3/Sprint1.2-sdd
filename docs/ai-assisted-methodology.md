# AI-assisted development methodology

Team 3, Sprint 1.2. Reuse this method in later sprints.
App issue: [#6 — Write the team AI-assisted methodology](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app/issues/6).

The method has four parts: **tools**, **phases**, **validation** and **GitHub**.
An agent may draft code. A person decides, reviews and merges.

## 1. Where each kind of work lives

| Work | Repository | Rule |
| --- | --- | --- |
| Grade application (PHP, schema, tests, UI) | [Sprint1.2-app](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app) | Follow [`AGENTS.md`](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app/blob/main/AGENTS.md). Do not copy that application into this repo. |
| Tool comparison | This repo, [`docs/ai-agents-comparison.md`](ai-agents-comparison.md) | Update the comparison before a slice uses a different tool. |
| Spec, plan, prompt, generated code, review, conclusions | This repo, under `docs/sdd/<slice>/` and `generated/` | One small feature per slice. School-level PHP only. |
| Product rules (weights, class group, stored grades) | App repo, `docs/requirements-and-decisions.md` section 8 | If the brief is silent, stop and ask. Then record the decision there. |

## 2. Tools

The current choice is in [`docs/ai-agents-comparison.md`](ai-agents-comparison.md):

- **OpenSpec** is the named spec-driven workflow: the spec, the plan and the tasks stay in the repository and are reviewed there. The sprint CA weight slice used that idea as markdown files (`spec.md`, `plan.md`, prompt, review, conclusions). It did not add an `openspec/` directory or run the OpenSpec CLI.
- **OpenAI Codex** is the coding agent named for a slice. It is available in the team's VS Code setup. It can edit files and run the PHP checks. Its output is still unreviewed code.

Limits of that choice:

- Codex does not replace the spec. The spec is written first.
- Usage depends on the ChatGPT plan. If the limit blocks the session, stop and say so. Do not switch tool in the middle of a slice.
- Another tool (Cursor, Claude Code, OpenCode, Antigravity) needs a new row and a new decision in the comparison before it is used.
- Never paste secrets, passwords, tokens, `.env` values or personal data into a prompt.

## 3. Phases

Use these phases for one SDD slice. The example in section 6 followed them.

| Phase | Who | Result |
| --- | --- | --- |
| 1. Issue | The team | One GitHub issue: objective, acceptance, files, how to test, dependencies. |
| 2. Spec | A person | `spec.md`: problem, inputs, rules, error messages, and the cases the test must cover. |
| 3. Plan | A person | `plan.md`: file list, order of work, commands, and the tool named in the comparison. |
| 4. Prompt | A person | `prompts/01-....md`: the exact prompt, the tool that ran it, and a short note of what came back. No secrets. |
| 5. Generate | The agent | Only the files named in the plan. |
| 6. Check | A person runs the commands | Lint and the slice test exit 0. |
| 7. Review | A person, not the agent | `review.md`: each spec rule checked, result `SPEC MET` or a list of gaps, and any edit made after the agent. |
| 8. Conclusions | A person | `conclusions.md`: what worked, what this slice does not do. |
| 9. Pull request | A person | Review by someone other than the author, then merge. Close the issue. |

Inside **Sprint1.2-app**, the agent does not jump ahead. One micro-step at a time, from `AGENTS.md`:

1. The agent proposes one step: goal, path, and what each part does.
2. The team answers **ho faig jo** (they write it) or **fes-ho tu** (the agent writes only that step).
3. After hand work, the agent says what is correct and the exact fix if one is needed.
4. **seguim** means the next micro-step only.

## 4. Validation criteria

A slice is acceptable only when all of these are true:

- The spec names the functions, the rules and the cases before any PHP is generated.
- The prompt file names the same tool as the plan and the comparison.
- Generated PHP starts with `declare(strict_types=1);`, uses no framework, and contains no SQL built by concatenating input.
- `php -l` passes on every generated PHP file.
- The test script covers every case in the spec and exits 0 only when the messages match.
- `review.md` is written by a person. `SPEC MET` means each rule was checked. Any later edit is listed.
- The grade application was not copied into this repository.
- No secrets are in the prompt, the code or the commit.

When the same change is applied in Sprint1.2-app, also run:

```sh
php tests/run.php
find app config database public routes tests -name '*.php' -print0 | xargs -0 -n1 php -l
```

AI-generated code is unreviewed code. It uses the same pull-request review as any other change. The author does not approve their own pull request.

## 5. GitHub integration

- **One issue = one branch = one pull request.** Branch name: `issue/<number>-<short-slug>`.
- This evidence repo: branch from `main`, pull request to `main`.
- The app repo: code targets `develop`. A documentation-only change may target `main`. Commands and review rules: [`docs/github-workflow.md`](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app/blob/main/docs/github-workflow.md) and [`CONTRIBUTING.md`](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app/blob/main/CONTRIBUTING.md).
- Commit in English, with the issue number. Example: `docs: add the AI-assisted methodology #6`.
- Pull request body includes `Closes #N` (or `Closes daw2-sprint1-2-eq3/Sprint1.2-app#N` from this repo).
- Open the pull request as a draft. Mark it ready when the checks in section 4 pass.
- Board columns: `Todo` → `Blocked` → `In Progress` → `In Review` → `Done`. Use `Blocked` only while a dependency is still open, and say why in a comment.
- The app decision record links here. It does not copy this document.

## 6. Example — sprint CA weights

Slice: [docs/sdd/sprint-ca-weights/](sdd/sprint-ca-weights/README.md).
App issue [#5](https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app/issues/5).

The spec says each CA weight is an integer from 0 to 100, and the weights of one RA in one sprint sum to 100. The plan lists two PHP files and these commands:

```sh
php -l generated/SprintCaWeightValidator.php
php -l generated/test_sprint_ca_weights.php
php generated/test_sprint_ca_weights.php
```

The prompt in [`prompts/01-generate-validator.md`](sdd/sprint-ca-weights/prompts/01-generate-validator.md) asks only for those two files. The review checks the six cases and records `SPEC MET`. The conclusions say this slice does not save weights in the app.

**Prompt that fits this method** (shortened from that file):

```text
Implement the sprint CA weight validation slice from spec.md and plan.md.
Create only generated/SprintCaWeightValidator.php and generated/test_sprint_ca_weights.php.
PHP 8.2+, declare(strict_types=1). No framework, no database, no HTTP.
Cover every row of spec section 7. Do not copy files from Sprint1.2-app.
```

**Prompt that does not fit:**

```text
Build the weight editor, invent the missing grade rules, and update the app database.
```

That prompt skips the spec, invents product rules, and mixes the evidence repo with the application.

Limit of this first slice: the plan names OpenAI Codex, and the prompt file still says the run used Cursor and that the tool was pending. The next slice records one tool in the comparison, the plan and the prompt before generation starts.

## 7. Limits of the method

- The agent does not choose product rules and does not open the next feature on its own.
- A slice proves the rules. It is not the screen, the schema or the stored grade.
- A green test is not a review. `review.md` is still required.
- If a line cannot be explained to the teacher, leave it out.
- Change this method with a pull request in this repo. Then check that the link in the app decision record still opens this file.
