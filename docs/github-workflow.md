# GitHub workflow

## 1. Repository

- Remote: `https://github.com/daw2-sprint1-2-eq3/Sprint1.2-app`
- Default and release branch: `main`
- Development branch: `develop`
- Documentation-only changes may be committed directly to `main`.
- Visibility: private (class project)
- Team: `yoherman203` (coordinator), `arnaumontsia` (GitHub manager),
  `adriagisbert` (documentation lead)

The agreements about roles, meetings and decision-making are in
[`Initial_Team_Agreements.md`](Initial_Team_Agreements.md). This document only
covers the repository workflow.

## 2. Issues

Every task starts as an Issue. The canonical format (fields, labels and
dependency rules) is in [`../CONTRIBUTING.md`](../CONTRIBUTING.md). A good Issue
states:

- **What** must be done.
- **How to check** it is complete (a concrete acceptance check).
- **Who** is responsible.
- **Estimate**: one session by default; split the issue if it needs more.
- **Type**: feature, fix, documentation or test.

Use the project board columns
`Todo -> Blocked -> In Progress -> In Review -> Done`. An Issue is `Blocked`
only while it cannot continue: set the column, add the `status:blocked` label
and say why in a comment.

## 3. Branches

For code or mixed changes, create the issue branch from up-to-date `develop`:

```sh
git switch develop
git pull --ff-only origin develop
git switch -c issue/12-grading-matrix
```

Use `issue/<number>-<short-slug>` with a short English slug. Do not develop
code or mixed changes directly on `main` or `develop`.

Documentation-only changes may go directly to `main`. If a change also touches
code, use an issue branch and a PR targeting `develop`.

## 4. Commits

Keep commits small and related to one task. Write them in English and include
the Issue number when possible. Use `<type>: <imperative description> #<issue-number>`.
Types: `feat`, `fix`, `docs`, `test`, `refactor`.

Examples:

```text
feat: add grading matrix #12
fix: reject a team instrument in an individual sprint #18
docs: document the Docker reset #21
test: cover team grades #24
```

Before committing:

```sh
php tests/run.php
find app config database public routes tests -name '*.php' -print0 \
  | xargs -0 -n1 php -l
git status
```

Check that `.env`, `database/database.sqlite`, logs and credentials are not
staged.

## 5. Continuous integration

`.github/workflows/ci.yml` runs on every push and pull request:

1. Sets up PHP 8.3 with `pdo_sqlite`.
2. Lints every PHP file under `app`, `config`, `database`, `public`, `routes`
   and `tests`.
3. Runs `php tests/run.php`.

The CI does not start Docker: the tests use SQLite `:memory:` and need no
server. If a run fails, open the **Actions** tab, read the failing step and
reproduce it locally with the same command.

## 6. Pull requests

Open a **Draft Pull Request** targeting `develop` while the work is in progress. Keep using the
same branch and PR for later commits. Mark it **Ready for review** when the
change is complete and CI is green.

Use the pull request template. The canonical format is in
[`../CONTRIBUTING.md`](../CONTRIBUTING.md). The PR description must include:

- What changed.
- Which Issue it resolves (`Closes #12`).
- How it was checked (commands, screens, manual flow).
- Anything the reviewer should look at first.

At least one teammate reviews. Address feedback on the same branch; do not open
a second PR for the fixes. Merge an issue PR only when:

- CI is green.
- At least one teammate other than the author has approved the PR.
- The issue branch is up to date with `develop`.
- The change has been checked in the browser, not only by the tests.

Use squash merge to keep one commit for the issue on `develop`. Delete the issue
branch after merging.

Promote tested releases from `develop` to `main` through a separate reviewed PR
with green CI. Keep `develop` after the release merge. Documentation-only changes
may be committed directly to `main`; no feature PR is required for that exception.

## 7. Definition of done

A task is done only when:

- The Issue acceptance check passes.
- The code follows the MVC/PDO/escaping conventions.
- The UI text is in Catalan and the code/docs are in English.
- Tests were added or updated when the behaviour changed.
- `php tests/run.php` passes.
- Documentation was updated if the setup or behaviour changed.
- The PR is merged and the Issue is in `Done`.

## 8. Delivery checklist

Before the final delivery:

- All required changes are on `main`.
- The README and `docs/` describe the delivered version.
- `.env` and real credentials are not in the repository.
- The Docker stack starts from a clean clone.
- The test suite passes locally and in GitHub Actions.
- The final commit is tagged if the teaching staff requests it.
