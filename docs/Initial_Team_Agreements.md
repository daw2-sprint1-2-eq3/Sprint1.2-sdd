___

> [!info] 
> **Project:** _Sprint 1.2_
**Team:** Herman, Arnau, Adrià
**Document owner:** Documentation Lead
**Status:** Agreed and in force from the date of signature

---
## 1. Purpose and scope

This document records the organisational agreements adopted by the team at the start of the project. It defines membership, role assignment and responsibilities, communication and attendance rules, and the governance model applied to the source code repository. It is a living document: any amendment requires the consensus procedure described in section 5 and must be recorded in the change log (section 7).

---
## 2. Team members and role assignment

| Member       | Assigned role      |
| ------------ | ------------------ |
| yoherman203  | Coordinator        |
| arnaumontsia | GitHub Manager     |
| adriagisbert | Documentation Lead |

Each role is held for the whole duration of the project unless the team agrees otherwise by consensus. Roles are complementary, not exclusive: every member remains responsible for the delivery of their own work.

>[!info] Scalability clause 
>Should the team grow beyond three members, the role of Assistant to the Coordinator, Assistant to the GitHub Manager or Assistant to the Documentation Lead will be assigned to any member who does not hold one of the primary roles, so that every member has a defined area of responsibility.

---
## 3. Role responsibilities

### 3.1 Coordinator

- Coordinate the team's overall work and track progress against agreed milestones.
	
- Act as the single point of contact and lead communication with the teaching staff.
	
- Chair and moderate team meetings, circulate the agenda beforehand and the minutes afterwards.
	
- Remind the team of pending tasks and deadlines.
	
- Upload the required deliverables to the virtual classroom (Moodle) within the established deadlines.
	
- Escalate blockers and unresolved disagreements in accordance with section 5.
	

### 3.2 GitHub Manager

- Administer the repository: visibility, access permissions, branch protection rules, labels, milestones and project board.
	
- Manage the pull request workflow: review requests, assignment of reviewers, merge criteria and conflict resolution.
	
- Maintain the repository structure and enforce the conventions defined in section 6.
	
- Ensure the repository remains private and free of sensitive or personal data.
	

### 3.3 Documentation Lead

- Safeguard the technical quality of the documentation: accuracy, completeness and consistency with the implemented solution.
	
- Ensure syntactic, orthographic and formatting correctness, applying a single style guide across all deliverables.
	
- Normalise references and bibliography where applicable, using a consistent citation standard.
	
- Keep this document and the project's supporting documentation up to date.
	

---
## 4. Attendance, availability and notice of impediments

- **Notice period:** Any member who is unable to attend a scheduled meeting — virtual or on-site — must notify the rest of the team at least 24 hours in advance. Notice must be given through the team's private channels (group chat and, where appropriate, email or phone), never through public repository content.
	
- **Early notice:** Whenever the impediment is foreseeable, notice must be given as early as possible, and in any case before the 24-hour threshold.
	
- **Force majeure:** In case of an unforeseeable emergency, the member must notify the team as soon as it becomes feasible, and propose a substitute date or an asynchronous update. The 24-hour rule is waived, but the notification obligation is not.
	
- **Minimum content of the notice:** Reason for absence (at the level of detail the member considers appropriate), expected duration, and any tasks or decisions that require prior input from the absent member.
	
- **Quorum and validity of decisions:** Meetings are valid with the attendance of at least two members. Decisions affecting scope, deadlines or deliverable content require the participation — synchronous or asynchronous — of all three members.
	
- **Async fallback:** If a meeting cannot be held, the Coordinator will propose an asynchronous review of the agenda and the team will record its conclusions in writing, so that no decision depends on a single informal conversation.
	
- **Response expectation:** Messages requiring a decision should be acknowledged within the same working day.
	

---
## 5. Decision-making and conflict resolution

- **Consensus by default:** Technical and organisational decisions are adopted by consensus of the three members.
	
- **Documented decisions:** Decisions with a lasting impact are recorded as a GitHub issue or in the corresponding document, including the rationale, so that the team keeps a traceable history.
	
- **Escalation:** If consensus cannot be reached within one working day, the Coordinator proposes an option and, if the disagreement persists, raises the matter with the teaching staff.
	
- **No unilateral changes:** No member may unilaterally modify shared resources (repository configuration, agreed scope, deadlines or this document).
	

---
## 6. GitHub governance and shared workflow

Repository administration is a shared responsibility exercised by consensus among the three members, with the GitHub Manager acting as executor of the agreed configuration.

### 6.1 Repository configuration

- The repository is private from its creation and must remain so. Visibility changes require explicit consensus.
	
- `main` holds releases and documentation; `develop` is the integration branch. Code or mixed changes require a pull request to `develop`. Documentation-only changes may be pushed directly to `main`.
	
- No credentials, tokens, personal data, phone numbers or email addresses are committed at any time.
	

### 6.2 Branching and commits

- For code or mixed changes, create each issue branch from up-to-date `develop`, using `issue/<number>-<short-slug>` with a short English slug.
	
- Keep commits small and related to one task. Write them in English using `<type>: <imperative description> #<issue-number>`. Types: `feat`, `fix`, `docs`, `test`, `refactor`. Include the issue number when possible.
	
- Commit messages are descriptive enough to be understood without reading the diff.

### 6.3 Issues and traceability

- Every task is tracked as an issue with an assignee, a milestone and the labels defined in [`CONTRIBUTING.md`](../CONTRIBUTING.md) (at least one `type:*`, one `phase:*`, one `prio:*` and one `area:*`).
	
- Issues describe the expected outcome, not just the activity, and are closed only when the work is merged.
	

### 6.4 Pull requests and review

- Code or mixed changes go through a pull request targeting `develop`. Documentation-only changes may go directly to `main`.
	
- A pull request requires at least one approving review from a member other than the author, a description of the change and a link to the related issue. The author cannot approve their own PR.

- Before merging an issue PR, CI must be green and the issue branch must be up to date with `develop`.
	
- PRs must be small and single-purpose; large or mixed PRs may be returned for splitting.
	
- Issue PR merge strategy: squash merge into `develop`. Promote tested releases from `develop` to `main` through a reviewed PR with green CI.

- Delete the issue branch after merging; keep `develop`.
	
- The GitHub Manager monitors the queue and applies the agreed criteria, but does not merge a PR that fails review.
	

### 6.5 AI-assisted development and the AGENTS.md file

- The team will maintain an AGENTS.md file at the repository root, agreed by consensus, defining the conventions for AI-assisted development: coding standards, expected scope of assisted changes, review obligations and the prohibition on pasting secrets, credentials or personal data into prompts.
	
- AI-generated code is treated as unreviewed code: it is subject to the same review process as any other contribution and must never be merged without human validation.
	
- AGENTS.md is versioned like any other project artefact; amendments follow the workflow in section 6, including the documentation-only exception.
	

### 6.6 Common conventions

- Language conventions: source code identifiers, comments, documentation and commit messages in English. User interface text and validation messages in Catalan.
	
- A consistent style and formatting pass is completed before requesting review.
	

---
## 7. Documentation, reproducibility and change control

- All deliverables are stored in the repository and, where required, uploaded to the virtual classroom by the Coordinator.
	
- Documentation is reviewed by the Documentation Lead before delivery, focusing on technical accuracy, terminology consistency and formatting.
	
- Amendments to this document are proposed via pull request and approved by consensus.
	
