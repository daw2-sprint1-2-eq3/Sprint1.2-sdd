# Domain

**Read when** you need a term or you are unsure whether a feature is in scope.

Do **not** open `DAW2 26-27 - Programació didàctica.md` unless the user asks for the teaching programme. Decisions and the permission table: `docs/requirements-and-decisions.md`.

## What this app is

One class group. Teachers record modules, instruments, projects, sprints, teams, and grades. The app is a multi-page PHP site, not a SPA.

A teacher must be able to answer: which modules, which RA/CA, which instruments (weight, individual or team), which projects and sprints, who is on each team, which score each student or team got, and what the average is.

## Words

| Term | In the app | Table / class |
|---|---|---|
| Module | Vocational module (M0613, …) | `modules` / `Module` |
| RA | Learning outcome | `learning_outcomes` / `LearningOutcome` |
| CA | Assessment criterion | `assessment_criteria` / `AssessmentCriterion` |
| Instrument | Exam, project, presentation, … | `instruments` / `Instrument` |
| Project | Globalized project of the group | `projects` / `Project` |
| Sprint | Slice of a project; individual or team | `sprints` / `Sprint` |
| Team | Students of one team sprint | `teams` / `Team` |
| Grade | One professor’s score for one student + instrument | `grades` / `Grade` |
| Team grade | One professor’s score for one team + instrument | `team_grades` / `TeamGrade` |

Roles: `admin`, `professor`, `student`. Exactly one admin.

## Rules that code must keep

- Score: 0 to 10, at most one decimal. Several professors on the same cell → arithmetic mean (`Grade::average`, `tests/test-grade-avg.php`).
- `instruments.is_team = 1` only on a sprint with `type = team`. Enforced in `Instrument`, not only in the form.
- A student is on at most one team per sprint.
- Professors and students see a project only when assigned (`project_professor`, `project_student`). Admin is not limited by assignment. Grading entry: `GradeController`.
- UI language is Catalan. Code and this file are English.

If the brief is silent, stop and ask. Then add a row to `docs/requirements-and-decisions.md` before coding the guess.
