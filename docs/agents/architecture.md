# Architecture

**Read when** you add a layer, a new folder, or you need the request path before coding.

Do not copy this into `README.md`. Setup, Docker and CI stay in `README.md`, `docs/development-environment.md` and `docker/DOCKER.md`.

## Request path

```text
public/index.php
  → routes/web.php (controller name → class)
  → App\Core\Router
  → App\Controllers\*
  → App\Models\*  (PDO via App\Core\Database)
  → App\Views\*   (header + view + footer)
```

URL shape: `?controller=grades&action=index`. Empty controller is `auth`; empty action is `index` (`login` for auth). See `app/Core/Router.php`.

## Layers

| Layer | Path | Does | Does not |
|---|---|---|---|
| Front controller | `public/index.php` | Boot, autoload, dispatch | SQL, HTML pages |
| Router | `app/Core/Router.php` + `routes/web.php` | Map name → class → method | Auth rules, SQL |
| Controller | `app/Controllers/` | Guard, read input, call model, `view()` / `redirect()` | SQL |
| Model | `app/Models/` | Validation, prepared SQL, domain rules | HTML, sessions |
| View | `app/Views/` | Markup; escape output | Queries |
| Core | `app/Core/` | `Database`, `Auth`, `Session`, `Controller`, `Page`, `Card` | Feature rules |

Canonical shapes: `app/Models/Module.php` (simple model), `app/Models/Instrument.php` (links and rules), `app/Controllers/GradeController.php` (guard + POST write).

## Rules for new work

- MPA only: a click or form reloads a full page. No SPA router and no JSON API unless explicitly approved.
- One new screen = route key + controller method + view. Do not add a second front controller.
- Shared connection: `Database::getInstance()`. SQLite for local/tests; MariaDB in Docker. Same tables in both schemas.
- Controllers extend `App\Core\Controller` and use `$this->view()` / `$this->redirect()`.

Product decisions live in `docs/requirements-and-decisions.md`. This file only says where code goes.
