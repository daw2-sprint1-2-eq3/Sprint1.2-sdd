# MVC recipes

**Read when** you add a route, controller, CRUD, field, or validation.

Canonical CRUD: `app/Controllers/ModuleController.php` + `app/Models/Module.php` + `app/Views/modules/`. Harder case (permissions + POST matrix): `app/Controllers/GradeController.php`.

## New route

1. Add one key in `routes/web.php` (`'modules' => ModuleController::class`).
2. Public methods on the controller are the `action` query values (`index`, `create`, `store`, `edit`, `update`, `delete`).
3. Links and forms use `?controller=modules&action=store`. Do not invent pretty URLs.

## Controller

```php
public function store(): void
{
    Auth::requireRole('admin');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('?controller=modules&action=create');
    }
    $result = Module::create($this->readForm());
    // errors → view with old + errors; success → flash + redirect
}
```

- Guard first (`Auth::requireRole` or `Auth::requireLogin`).
- Writes (`store`, `update`, `delete`, `save`) are POST-only.
- No SQL in the controller. No `echo` of HTML except what `view()` / `notFound()` already do.
- Missing row: `$this->notFound('…')` (Catalan message).
- Success: `Session::flash('success', '…')` then `$this->redirect(...)`.

Copy the method split from `ModuleController` (index / create / store / edit / update / delete). Copy the project check from `GradeController::save` when the row must belong to a project the user can grade.

## Model

- `all`, `find`, `findByCode` as needed.
- `create` / `update` / `delete` validate first, then prepared SQL.
- Empty or duplicate checks return Catalan messages in `errors`, not exceptions.
- New column: model + both schemas + seed + view + test. See `docs/agents/php-pdo.md`.

## View

- File: `app/Views/<area>/<page>.php`.
- Forms: `method="post"` and `action="?controller=…&action=store"`.
- Repopulate with `$old`; show `$errors['field']` next to the field.
- Escape every dynamic value (`docs/agents/security.md`).
- Reuse `App\Core\Page` for the page head. Do not add a new layout.

## Field checklist

| Step | Where |
|---|---|
| Column | `database/schema.sql` and `database/schema-mariadb.sql` |
| Read/write | model |
| Form + list | view |
| Sample row | `database/seed.php` |
| Rule | `tests/test-*.php` |

Domain names (RA, CA, instrument, team): `docs/agents/domain.md`. Role matrix: `docs/requirements-and-decisions.md` (do not copy the whole table here).
