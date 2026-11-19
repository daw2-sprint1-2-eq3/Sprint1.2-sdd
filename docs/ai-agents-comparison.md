# Comparison of OpenSpec and AI coding agents

Checked on: 2026-11-19
Author: Sprint 1.2 team

## Comparison

OpenSpec is an SDD workflow, not a coding agent. The other tools below can
generate or change code. Costs and usage limits change, so this comparison links
to the official plan pages instead of fixing prices in this document.

| Tool | What it is | Possibilities | Limits | Cost | Fit for this team |
| --- | --- | --- | --- | --- | --- |
| OpenSpec | Open-source spec-driven development workflow | Keeps proposals, specs, plans and tasks reviewable in the repository; works with many coding agents | Does not supply the coding model; adds documentation steps | Open-source tool; model or agent usage is separate | Use it to organize the SDD evidence, together with a coding agent |
| OpenCode | Open-source coding agent for terminal, desktop and web | Edits project files; supports several model providers and local models | Requires a model/provider setup; results depend on the selected model | Tool is open source; model use may be billed by its provider or through an optional subscription | Flexible, but adds setup the team does not need for this slice |
| Google Antigravity | Google's agent-focused development environment | Agents can plan and work across project files; supports IDE extensions and Google Cloud controls | Uses Google's environment and account; plan limits and availability apply | Free individual access with paid Google AI plans and usage credits | Capable, but the team already works in VS Code |
| Claude Code | Anthropic's coding agent for terminal and IDE workflows | Reads and changes a repository, runs commands and supports review | Uses Anthropic models and account limits; the team must choose its plan or provider | Subscription or API/provider billing; usage limits apply | A viable VS Code option, but not selected for this run |
| Cursor | AI code editor with an agent | Searches the codebase, edits multiple files, runs commands and reviews diffs | Proprietary editor; usage depends on plan and model | Limited free plan and paid plans; see current pricing | Not selected because the team uses VS Code |
| OpenAI Codex | OpenAI coding agent, available in the ChatGPT app, CLI and IDE extension | Can implement, review and test repository changes; can work from project instructions | Usage limits depend on the ChatGPT plan; output still needs review | Included with supported ChatGPT plans, with plan-specific limits and options | Selected for this slice: Codex is available in the team's VS Code setup and can run the PHP checks |

## Decision

Use **OpenSpec as the SDD workflow** and **OpenAI Codex as the coding agent**
for the Sprint CA weight validation slice.

The team uses VS Code, where the ChatGPT/Codex extension is installed. Codex is
also the agent used for this work, so the prompt and code output can be recorded
from the same execution. The earlier plan named Cursor; it was updated to match
the team's VS Code setup and the agent actually used.

## Excluded options

- OpenCode was not selected because configuring a model provider would add setup
  for this small, isolated PHP exercise.
- Antigravity was not selected because the team already uses VS Code and does not
  need to move this work to another development environment.
- Claude Code was not selected for this run because Codex is already available
  in the team's VS Code setup.
- Cursor was not selected because the team uses VS Code.
- OpenSpec is not an alternative coding agent; it supplies the specification
  workflow used alongside Codex.

This decision is for the evidence slice only. It does not prevent the team from
choosing another tool for later work.

## Sources

- OpenSpec concepts: https://github.com/Fission-AI/OpenSpec/blob/main/docs/overview.md
- OpenCode documentation: https://opencode.ai/docs/
- Google Antigravity documentation and plans: https://antigravity.google/docs/overview and https://antigravity.google/pricing
- Claude Code documentation and plans: https://code.claude.com/docs/en/overview and https://claude.com/pricing
- Cursor Agent documentation and plans: https://cursor.com/help/ai-features/agent and https://cursor.com/pricing
- OpenAI Codex availability: https://help.openai.com/en/articles/11369540-using-codex-with-your-chatgpt-plan
