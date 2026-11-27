# Requirements and technical decisions — Sprint 1.2

This document maps the Sprint 1.2 brief to the implementation and records the
decisions taken by Team 3. It complements the root `README.md`; it does not
replace the teaching staff's statement.

## 1. Product scope

The product is a grade-management application for one vocational training
class group. A teacher must be able to answer:

- Which modules does the group study?
- Which learning outcomes (RA) and assessment criteria (CA) does each module
  have?
- Which instruments assess each CA, with what weight, and are they individual
  or team instruments?
- Which projects and sprints is the group working on?
- Which students form each team?
- What grade did each student, or each team, obtain in each instrument?
- What is each student's average and which cells are still pending?

The application is an **MPA**: each navigation action loads a full page from the
server. It is not a single-page application.

## 2. Requirement traceability

| Requirement | Implementation | Verification |
| --- | --- | --- |
| One MPA, no SPA | Server-rendered PHP views; links and forms reload the page | Manual navigation; no SPA router or fetch-based screen |
| PHP without framework | Custom front controller, router, autoloader and MVC classes | `public/index.php`, `app/Core/` |
| OOP | Controllers, models and core services are classes | `app/` |
| PDO | One shared `PDO` connection in `App\Core\Database`; prepared statements | `app/Core/Database.php`, all models |
| MVC | Controllers call models and render views under `app/Views/` | Folder structure |
| MariaDB | `database/schema-mariadb.sql`; `mariadb:11.4` container | `docker/docker-compose.yml` |
| Separate app and DB servers | Two Compose services, private network | `docker/docker-compose.yml` |
| Bootstrap 5, responsive | Bootstrap 5.3 CDN + `custom.css` + offcanvas sidebar | Manual check at mobile/desktop widths |
| Catalan UI | All visible labels, messages and validation text in Catalan | Views and model messages |
| English identifiers/docs | Classes, methods, variables and docs in English | Code review |
| Modules, RAs, CAs | `modules`, `learning_outcomes`, `assessment_criteria` + CRUD | `test-evaluation-model.php` |
| Assessment instruments | `instruments`, kinds, weight, criteria links | `test-evaluation-model.php` |
| Individual and team sprints | `sprints.type = individual\|team`; one of each per project in the seed | `test-evaluation-model.php` |
| Teams | `teams`, `team_student`; teams only in team sprints | `test-team-grades.php` |
| Individual grades | `grades`; one row per professor/student/instrument | `test-grade-avg.php` |
| Team grades | `team_grades`; one row per professor/team/instrument | `test-team-grades.php` |
| Dashboards | Admin, professor and student dashboards | Manual + route smoke test |
| Documentation | `README.md`, `docs/`, `docker/DOCKER.md`, `tests/README.md` | Review |
| Docker Compose | `docker/docker-compose.yml` + `docker/Dockerfile` | `docker compose config` |
| GitHub Actions CI | `.github/workflows/ci.yml` | Push/PR run |
| Automated tests | `php tests/run.php`, 7 isolated test files | Local + CI |
| Wireframes/mockup | `docs/design/` (Stitch reference) | Review |
| Diagrams | `docs/diagrams.md` (Mermaid) | Review |
| Floci/RDS test | Procedure in `docs/floci-rds.md` | To be executed in the lab environment |

## 3. Roles and permissions

> **Sprint 1.2 target.** Professor permissions move from `project_professor` to
> `class_group_module` (responsible professor plus optional collaborators). The
> table below describes the Sprint 1.1 implementation until issue 2.1 lands; the
> target rules are in section 8.6.

| Action | Admin | Professor | Student |
| --- | --- | --- | --- |
| Manage users | yes | no | no |
| Manage modules, RAs, CAs | yes | no | no |
| Manage instruments | yes | no | no |
| Create/edit/delete projects | yes | no | no |
| View a project | yes | yes (assigned only) | no |
| Assign professors/students/instruments | yes | no (read-only) | no |
| Create/edit sprints | yes | no | no |
| Create/edit teams and members | yes | yes | no |
| Grade a project | yes | yes, if assigned | no |
| See own grades | — | — | yes |
| See pending cells | — | yes (own) | — |

The guards live in the controllers (`Auth::requireRole(...)`). A professor who
is not assigned to a project cannot open its grading matrix. The admin account
is unique: `User::create()` rejects role `admin` and `User::update()` /
`User::delete()` protect the existing admin row.

## 4. Data model

> **Sprint 1.2 target.** The new model adds class groups, module-based professor
> assignments and six stored grade tables, and removes `project_professor` and
> `instruments.weight`. The diagram and the rules below describe the Sprint 1.1
> schema until issue 2.1 lands; the target schema is in section 8.2.

```text
users ──< project_professor >── projects
users ──< project_student   >── projects
projects ──< sprints ──< teams ──< team_student >── users
projects ──< team_grades >── teams
projects ──< grades >── users (student)
modules ──< learning_outcomes ──< assessment_criteria
instruments >── instrument_criterion ──< assessment_criteria
instruments ──< grades
instruments ──< team_grades
```

Key rules:

- `instruments.is_team = 1` marks a team instrument. It must belong to a
  `sprints.type = 'team'` sprint; the model rejects any other combination.
- A student can belong to at most one team per sprint. The `team_student` table
  does not enforce this (SQLite/MariaDB cannot express it simply), so
  `TeamController::syncStudents()` moves the student out of the previous team.
- A grade is unique per `(project, student, instrument, professor)`. Each
  professor writes their own row; the UI shows the arithmetic mean of those
  rows.
- A team grade is unique per `(project, team, instrument, professor)` and
  follows the same mean rule.
- The student project average is weighted by `instruments.weight`. If the
  weights are zero or missing, the model falls back to a simple mean.

The full column list is in `database/schema.sql` (SQLite) and
`database/schema-mariadb.sql` (MariaDB). Both files must stay in sync.

## 5. Architecture

```text
Browser
  │  GET/POST ?controller=...&action=...
  ▼
public/index.php  (front controller, session, autoloader)
  ▼
App\Core\Router  ──reads──>  routes/web.php
  ▼
Controller (Auth, User, Project, Module, ...)
  ├── Models (PDO prepared statements)
  └── Views (PHP templates + Bootstrap)
        ▼
     HTML response
```

- **Route table** — `routes/web.php` maps the `controller` query parameter to a
  controller class. `Router` checks that the class and the action method exist
  and returns 404 otherwise.
- **Autoloader** — a small `spl_autoload_register()` in `public/index.php` maps
  `App\Foo\Bar` to `app/Foo/Bar.php`. This keeps the project runnable without
  `composer install`.
- **Session/Auth** — `Session` wraps `session_start()` and flash messages;
  `Auth` stores the logged user in the session and provides `requireLogin()`,
  `requireRole()` and `isRole()`.
- **Views** — `Controller::view()` extracts the data array into variables and
  includes the header, the view and the footer. `Page` and `Card` are small
  helpers for repeated Bootstrap markup.
- **Database** — `Database::getInstance()` returns one shared PDO connection.
  Models call it directly and use named prepared statements.

## 6. Decisions and rationale

| Decision | Why |
| --- | --- |
| Reuse the Sprint 1.1 MVC base | The team already knew it; it already satisfied the no-framework/MVC/OOP/PDO requirement and saved time for the 1.2 domain work. |
| Keep the query-string router, move the table to `routes/web.php` | Minimal and readable for juniors; the route table is now separate from the router logic. |
| Custom autoloader instead of Composer autoload | The project runs with a plain PHP install and no network; `composer.json` remains for metadata and optional tooling. |
| SQLite for development/tests, MariaDB in Docker | Tests need no server and are fast; production/demo uses the required MariaDB. |
| Two schema files | SQLite and MariaDB differ in types and auto-increment syntax; keeping both explicit is easier to review than a migration tool. |
| `is_team` flag on instruments | A team sprint can contain individual instruments (for example, an individual exam). Deriving team-ness from the sprint would be wrong. |
| One grade row per professor | Real projects are often graded by more than one teacher; the mean is shown without losing each teacher's input. |
| Server-rendered confirmation page | Simpler than a JavaScript modal, works without JS and keeps destructive actions POST-only. |
| Custom test runner | Zero dependencies; each test file runs in a separate process with a fresh in-memory database. |
| Bootstrap via CDN | No build step and no `node_modules`; the classroom machines only need PHP and a browser. |
| Seed data in `database/seed.php` | Gives the teaching staff a complete demo in one command and is idempotent. |

### Deliberate limitations

- **No CSRF token.** Forms are POST-only and the app is a classroom project;
  adding a per-session token is the next security step.
- **No pagination or search.** The class group is small; adding pagination
  would complicate the first version.
- **No audit log.** Grades can be edited by their author; the database keeps
  the last value only.
- **Demo credentials on the login page.** Intentional for evaluation; remove
  the hint and change the passwords before any real use.
- **Design mockup is a reference.** `docs/design/` comes from the previous
  Stitch mockup and was not re-exported for every 1.2 screen; the implemented
  layout follows its palette and structure.

## 7. Floci/RDS check

Sprint 1.2 requires a test against the course's Floci/RDS environment. The
procedure and the checklist are in [`floci-rds.md`](floci-rds.md). The app is
configured entirely through environment variables, so no code change is needed:
only `DB_DRIVER=mysql`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` and
`DB_PASSWORD` change.

## 8. Sprint 1.2 — RA/CA weighting

This section records the decisions taken by Team 3 on 29–30 October 2026 for
the RA/CA weighting and stored grade model. It is the source of truth for issue
0.1. Sections 3 and 4 describe the Sprint 1.1 implementation; where they differ,
this section wins.

**Status:** closed on 30 October 2026.

### 8.1. Scope

- A **class group** studies several modules. A project belongs to one class
  group and may involve several of its modules.
- RAs and CAs already exist when the weights are configured. Weights are
  editable; they are not fixed at creation time.
- Percentages are **integers from 0 to 100**. Grades use `DECIMAL(4,2)`
  (`0.00–10.00`).
- CA, RA, sprint, module and project grades are **stored** in new tables.
- Derived grades are **not edited by hand**. They are recalculated from
  instrument grades and weights.
- There is **no weight history**: only the current value is stored.
- The module grade is **final** and comes from RA grades, not from project
  grades. A project grade is stored separately.

### 8.2. Data model

| Table or column | Change | Purpose |
| --- | --- | --- |
| `class_groups` | New | Class group (code, name, year). |
| `class_group_module` | New | Modules studied by the group; `professor_id` is the responsible professor. |
| `class_group_module_professor` | New | Optional collaborators for the module. |
| `class_group_student` | New | Students in the class group. |
| `projects.class_group_id` | New FK | Each project belongs to one class group. |
| `project_module` | New | Modules involved in a project. |
| `project_outcome` | New | RAs assessed by the project; integer weight, sum 100 per project. |
| `sprints.weight` | New column | Integer sprint weight, sum 100 per project. |
| `sprint_criterion` | New | Integer CA weight in a sprint, sum 100 per RA and sprint. |
| `instruments.weight` | Removed | An instrument may assess several CAs; the CA weight lives in the sprint. |
| `learning_outcomes.weight` | Not added | There is no global RA weight; the weight lives in `project_outcome`. |
| `project_professor` | Removed | Professors are assigned to class-group modules, not to projects. |
| `grades.score`, `team_grades.score` | `DECIMAL(4,2)` | Two decimals. |
| `criterion_grades` | New | CA grade per sprint, student and CA. |
| `sprint_outcome_grades` | New | RA grade per sprint and student. |
| `sprint_grades` | New | Sprint grade per student. |
| `outcome_grades` | New | Final RA grade per student, class group and RA (no `project_id`). |
| `module_grades` | New | Final module grade per student, class group and module (no `project_id`). |
| `project_grades` | New | Project grade per student. |

The six calculated tables (`criterion_grades`, `sprint_outcome_grades`,
`sprint_grades`, `outcome_grades`, `module_grades`, `project_grades`) have
`is_provisional BOOLEAN NOT NULL DEFAULT 0`. It is set to 1 when a grade has
been calculated with missing data; the value is still shown.

### 8.3. Weights and validation

| Weight | Scope | Rule |
| --- | --- | --- |
| `project_outcome.weight` | Project | Integer 0–100; sum **100 per project**, across all RAs assessed by the project. |
| `sprints.weight` | Project | Integer 0–100; sum **100 per project**, across all sprints. |
| `sprint_criterion.weight` | RA in a sprint | Integer 0–100; sum **100 per RA and sprint**. |

Validation rules:

- A sum of **0** means “not configured”. The calculation falls back to a
  **simple provisional mean** and shows “pendent de configurar”.
- A **partial sum** (for example, 60) is **blocked** with
  “configuració incompleta”. It never produces a grade that looks final.
- A CA can be assessed in several sprints; each `sprint_criterion` row has its
  own weight.
- An instrument without a sprint does not count for CA/RA. It remains in the
  grading matrix and in its own mean.
- An instrument may assess several CAs; its grade is used in each linked CA.

### 8.4. Grade formulas

1. **Instrument grade:** arithmetic mean of all professors' grades for that
   instrument and that student (or team), rounded to two decimals. There is no
   instrument weight.
2. **CA grade in a sprint:** simple mean of the instrument grades that assess
   that CA in that sprint. If there are no grades, `null`; if instruments are
   missing, `is_provisional = 1`.
3. **RA grade in a sprint:** weighted mean of its CA grades using
   `sprint_criterion.weight`. A missing CA is renormalised among the CAs with a
   grade and marked provisional.
4. **Sprint grade:** weighted mean of the RA grades in the sprint using
   `project_outcome.weight`, renormalised among the RAs assessed in that
   sprint. A missing RA is marked provisional.
5. **RA grade in a project:** weighted mean of the RA-in-sprint grades using
   `sprints.weight`, renormalised among the sprints where the RA is assessed.
   A missing sprint is marked provisional.
6. **Project grade:** weighted mean of the RA-in-project grades using
   `project_outcome.weight`. A missing RA is renormalised among the available
   RAs and marked provisional.
7. **Final RA grade** (`outcome_grades`): simple mean of the RA-in-project
   grades with a grade; each project counts once. If a project where the RA is
   assessed has no grade, the result is provisional.
8. **Final module grade** (`module_grades`): simple mean of the final RA grades
   of the module with a grade. If an RA of the module has no grade, the result
   is provisional.
9. **Rounding:** each level is stored with two decimals; the next level uses the
   stored value. Percentages are integers.

### 8.5. Worked example

Project P1 belongs to class group G1. It assesses RA1 and RA2 from M1 and RA3
from M2. Project weights: RA1 40, RA2 30, RA3 30 (sum 100). Sprints: S1 50,
S2 50 (sum 100). Student A.

**CA weights**

| RA | Sprint | CA | Weight |
| --- | --- | --- | --- |
| RA1 | S1 | CA1 | 60 |
| RA1 | S1 | CA2 | 40 |
| RA1 | S2 | CA3 | 100 |
| RA2 | S1 | CA4 | 100 |
| RA3 | S2 | CA5 | 100 |

**Grades**

| Level | Calculation | Result |
| --- | --- | --- |
| CA1 in S1 | mean of instruments | 7.00 |
| CA2 in S1 | mean of instruments | 5.00 |
| RA1 in S1 | 7.00 × 60 % + 5.00 × 40 % | 6.20 |
| CA4 in S1 | mean of instruments | 8.00 |
| RA2 in S1 | only CA4 | 8.00 |
| CA3 in S2 | mean of instruments | 9.00 |
| RA1 in S2 | only CA3 | 9.00 |
| CA5 in S2 | mean of instruments | 4.00 |
| RA3 in S2 | only CA5 | 4.00 |
| Sprint S1 | (6.20 × 40 + 8.00 × 30) / 70 | 6.97 |
| Sprint S2 | (9.00 × 40 + 4.00 × 30) / 70 | 6.86 |
| RA1 in P1 | (6.20 × 50 + 9.00 × 50) / 100 | 7.60 |
| RA2 in P1 | only S1 | 8.00 |
| RA3 in P1 | only S2 | 4.00 |
| Project P1 | (7.60 × 40 + 8.00 × 30 + 4.00 × 30) / 100 | 6.64 |
| M1 | (7.60 + 8.00) / 2 | 7.80 |
| M2 | only RA3 | 4.00 |

**Provisional case:** if RA3 has no grade yet, the project grade is calculated
with RA1 and RA2: `(7.60 × 40 + 8.00 × 30) / 70 = 7.77`, stored with
`is_provisional = 1`. M2 stays `null` until RA3 has a grade.

### 8.6. Permissions

- **Admin:** manages class groups, modules, projects, weights and grades.
- **Professor:** a professor assigned to `class_group_module` (responsible or
  collaborator) can view and edit the grades and weights of **that module**.
  A project may involve several modules; the professor sees the part of the
  project that belongs to their module.
- **One responsible professor per module and class group** is required.
  Collaborators are optional.
- `project_professor` is removed. A professor is never assigned to a project
  as the source of permissions.

### 8.7. Recalculation and stored values

- `GradeCalculator` owns the formulas. Controllers do not calculate grades and
  do not write SQL.
- Saving or deleting a grade, a weight or an instrument–CA link recalculates the
  affected stored grades **in the same transaction**. If anything fails,
  `rollBack()` leaves the database unchanged.
- Views read `criterion_grades`, `sprint_outcome_grades`, `sprint_grades`,
  `outcome_grades`, `module_grades` and `project_grades`; they never recalculate.
- The instrument grade remains the source of truth. Derived grades are a stored
  snapshot for consultation and auditing.

### 8.8. Out of scope

- Weight history or audit log of weight changes.
- A global RA weight per module.
- A weight for `project_module` or for the module inside the project.
- The 80 % CA / 20 % key-skills rule and per-component pass conditions
  (not confirmed by the teaching staff).
- Manual editing of derived grades.
- CSRF tokens, REST API or SPA navigation.

### 8.9. Acceptance

- [ ] The three team members have reviewed and approved these decisions.
- [ ] The formulas and the worked example match the implementation plan, sections 4 and 5.
- [ ] Issues 2.1–2.5 update both schemas, the seed and the tests.
- [ ] Issue 0.1 is closed before starting 2.1.

## 9. Frontend presentation demo — 30 October 2026

The user explicitly requested the complete frontend as a fast, functional JavaScript demonstration based on the existing mockup and final implementation plan. A standalone version lives in `docs/design/prototype/`, using Bootstrap 5 CSS, vanilla JavaScript and localStorage. It reproduces the section 8 calculation rules and role workflows, with full-document navigation. Its login and data persistence are local simulations; the PHP routes, schemas and server implementation remain the separate sprint deliverable. Run instructions and the presentation walkthrough are in `docs/design/prototype/README.md`.

## 10. AI-assisted methodology (M0616)

The reusable method (tools, phases, validation and GitHub) lives in the SDD evidence repository:

[AI-assisted development methodology](ai-assisted-methodology.md)

The tool comparison that method uses is [AI coding agents comparison](ai-agents-comparison.md).

This section is a link only. The grade rules in section 8 do not change.
