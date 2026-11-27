# Testing and definition of done

**Read when** you add a test or prepare a PR.

Runner and isolation details: `tests/README.md`. Do not copy that guide here. Product checklist: `docs/requirements-and-decisions.md`.

## How a test file works

- Name: `tests/test-<topic>.php`. `tests/run.php` starts each file in its own process.
- First lines: `declare(strict_types=1);` and `require __DIR__ . '/bootstrap.php';`.
- Bootstrap gives an empty in-memory SQLite database and loads `database/schema.sql`.
- Assert with `expect($condition, 'message')`. A false expect fails the process.
- Create rows through models (`User::create`, `Module::create`, `Grade::store`), not by hand-written SQL, unless the test is about SQL itself.

Grade mean, score range, and “two professors → two rows”: `tests/test-grade-avg.php`. Copy that style.

| Behaviour | Test file |
|---|---|
| Score + average | `tests/test-grade-avg.php` |
| Modules, RA, CA, instruments | `tests/test-evaluation-model.php` |
| Team grades | `tests/test-team-grades.php` |
| Single admin | `tests/test-admin-unique.php` |
| Login / roles | `tests/test-auth-rbac.php` |
| Users | `tests/test-user-model.php` |

## When to add a test

- New validation or permission rule.
- New column that changes what `create` accepts.
- A bug you just fixed (one `expect` that failed before the fix).

Do not add a test that only checks a happy path already covered.

## Definition of done

- `php tests/run.php` exits 0.
- Lint: `find app config database public routes tests -name '*.php' -print0 | xargs -0 -n1 php -l`
- Schema change: both `database/schema.sql` and `database/schema-mariadb.sql`, plus `database/seed.php` if the sample data needs the column.
- New route or screen: Catalan UI strings, English code.
- No `.env` or secrets in the diff.
- Docs that changed behaviour: `docs/requirements-and-decisions.md` if a product rule changed. Agent rules stay in `docs/agents/`.

PR text: what changed and how you ran `php tests/run.php`. GitHub steps: `docs/github-workflow.md`.
