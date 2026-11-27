# Security

**Read when** you touch auth, sessions, permissions, forms, or output.

Canonical guards: `app/Core/Auth.php`. Canonical “this cell belongs to this project” check: `app/Controllers/GradeController.php` (`save`). Password hash never goes into the session (`User::toArray` / `Auth` file comment).

## Session and login

- Logged user is `Session` key `user`: `id`, `username`, `role`, `name`. Never store `password_hash`.
- `Auth::requireLogin()` sends guests to `?controller=auth&action=login` and `exit`.
- `Auth::requireRole('admin')` or `Auth::requireRole(['admin', 'professor'])` sends the wrong role to HTTP 403.
- Call the guard at the start of every action that is not public. Public today: login (and logout). Everything else is protected.
- Role checks use strict comparison. Do not treat the string `'1'` as an id.

Who may do what is the table in `docs/requirements-and-decisions.md`. Do not restate it here. Enforce it in the controller, then again in the model when a rule is data (example: only one admin — `tests/test-admin-unique.php`).

## Object-level access

A role check is not enough. A professor may grade only an assigned project.

- Load the project (or sprint, team) by id.
- Refuse when `Assignment` / model says the user is not on that project (`GradeController::canManage`).
- On POST, ignore ids that are not in the project’s students, instruments, or teams. A crafted body must not grade another project.

## Input

- Writes are POST. GET is for showing pages.
- Cast ids with `(int)` before use.
- Validate in the model (`Grade::validateScore`: 0–10, at most one decimal). Show the Catalan string; do not trust the browser `required` attribute alone.
- Bind SQL parameters. Never build SQL with `$_GET` / `$_POST` text.

## Output

- In views, dynamic text uses `htmlspecialchars($value)` (or `(string)` first).
- Same rule for attributes (`value=""`).
- `Page` escapes its own titles. Do not pass raw HTML into helpers that escape, and do not skip escape because “it comes from the database”.
- Flash messages are Catalan and escaped by the layout. Do not put HTML in a flash string.

## Secrets

- Credentials live in `.env` (see `.env.example`). Never commit `.env`, password hashes from production, or real student data.
- Tests may use obvious local passwords (`admin123` in seed). That does not make them safe to log.

Known holes in old code: `docs/agents/known-debt.md`. New code follows this file, not the holes.
