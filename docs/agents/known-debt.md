# Known debt

**Read when** you review or refactor existing code.

New code follows `docs/agents/php-pdo.md`, `mvc-recipes.md`, and `security.md`. If an old file breaks those rules, do not copy the break.

## Do not copy

| Debt | Where | Do this instead |
|---|---|---|
| `SELECT *` | `Module::all`, `Instrument::all`, `User::all`, and the same pattern on other models | List the columns you need |
| Averages built in the controller | `GradeController::averages` and `teamAverages` | Use `Grade::average` / the model. The controller may pass the result to the view |
| Untyped guard argument | `Auth::requireRole($roles)` | Type it `string\|array` on new signatures |
| Title not escaped | `App\Core\Card` prints `$title` inside `<h6>` without `htmlspecialchars` | Escape every string that reaches HTML |
| Long controller | `GradeController::save` both checks membership and loops POST cells | Keep the membership check; do not grow the method with new SQL |

`tests/test-grade-avg.php` is the behaviour to keep when you move an average. Refactors must stay green under `php tests/run.php`.

## Safe to follow

These match the rules and are the right templates:

- `app/Models/Module.php` — CRUD shape, prepared `find`, validation before insert.
- `app/Models/Instrument.php` — kind list, team/sprint rule.
- `app/Controllers/ModuleController.php` — guard, POST-only write, flash, redirect.
- `app/Controllers/GradeController.php` — ignore POST ids that are not on the project (copy the check, not the SQL-less average loop).
- `app/Core/Database.php` — one PDO, exceptions, SQLite and MySQL.

## Refactor limits

- Do not rename tables or routes in a drive-by cleanup.
- Do not add a framework, an ORM, or a JSON API to “fix” debt.
- One debt item per change, with the test that already covers it (or one new `expect` if none exists).

Product scope is not debt. If behaviour is wrong, record it in `docs/requirements-and-decisions.md` before changing it.
