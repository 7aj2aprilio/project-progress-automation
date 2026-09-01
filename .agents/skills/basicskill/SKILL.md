---
name: basi
description: Track coding progress and keep project documentation up to date
license: MIT
compatibility: opencode
metadata:
  audience: developers
  workflow: development
---

## What I do

- Track every code change made during development
- Create and maintain a progress file
- Record completed tasks, changes, and current status
- Record important decisions and issues encountered
- Update the README when code changes affect project setup, features, usage, or documentation
- Keep the project progress easy to continue after a previous session

## When to use me

Use this whenever making changes to the project code.

Update the progress file after every meaningful code change or completed task.

Update the README whenever the code changes affect:
- Project features
- Installation or setup
- Configuration
- Environment variables
- Usage instructions
- API endpoints
- Database structure
- Project architecture
- Dependencies
- Deployment instructions

## Progress File

Create a new file named:

`PROGRESS.md`

If `PROGRESS.md` already exists, update it instead of creating another progress file.

Keep the progress file concise and use this format:

# Project Progress

## Current Status

Describe the current state of the project.

## Completed

- Completed task
- Completed task

## Recent Changes

- Date: Change that was made
- Date: Change that was made

## In Progress

- Current task
- Current task

## Issues

- Known issue or blocker

## Next Steps

- Next task
- Next task

## Important Notes

- Important implementation decisions
- Configuration notes
- Anything needed to continue development

## Rules

- Update `PROGRESS.md` after every meaningful code change.
- Do not delete previous progress information unless it is obsolete.
- Keep the progress history clear and chronological.
- Do not claim a task is completed unless it has actually been implemented.
- Record bugs, blockers, and incomplete work.
- Before starting a new task, check `PROGRESS.md` to understand the current project state.
- Before finishing a task, update `PROGRESS.md`.
- If the README is affected by the code changes, update it in the same task.
- Do not modify the README unnecessarily when the changes do not affect documentation.
- Preserve the existing README structure and style.
- Never overwrite useful existing documentation.
- Keep documentation synchronized with the actual code.