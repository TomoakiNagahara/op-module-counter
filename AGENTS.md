# AGENTS.md

This file is the thin entry guide for AI coding agents editing `asset/module/counter/`.

Detailed rules belong in the owning documents. Do not expand this file with copied specifications unless there is no better owner document.

## Required First Read

Read `docs/dictation.md` every time before answering or editing this module.
The user's latest spoken or chat instructions may be summarized there.

If the original Japanese dictation matters, read `docs/dictation.ja.md`.

## Module Documents

- Overview and usage: `README.md`
- Shared helpers: `docs/Common.class.md`
- Counter behavior: `docs/Counter.class.md`
- Countup behavior: `docs/Countup.class.md`
- Initialization: `docs/init.md`
- Initialization class: `docs/Init.class.md`
- Initialization guidance helper: `docs/InitGuidance.class.md`
- Countup entry: `docs/countup.md`
- View entry: `docs/view.md`
- Calendar entry: `docs/calendar.md`
- Calendar helper: `docs/Calendar.class.md`
- Config: `docs/config.md`
- Original Japanese dictation: `docs/dictation.ja.md`

Update the matching `.ja.md` translation when changing an English module document.

## Framework Documents

- Repository agent guide: `asset/docs/AGENTS.md`
- Customization boundaries: `asset/docs/CUSTOMIZATION_MAP.md`
- Documentation authoring and dictation placement: `asset/docs/documentation-authoring.md`
- Framework-wide coding rules: `asset/docs/op/coding-rules.md`
- UNIT/MODULE authoring, namespace, lazy loading, and CI visibility: `asset/docs/op/unit-module-authoring.md`
- Split CI file layout: `asset/docs/cicd/ci-file-layout.md`
- Global `OP()` function contract: `asset/docs/core/op-function.md`
- Request API: `asset/docs/core/op-request.md`
- Admin API: `asset/docs/core/is-admin.md`

For prior mistakes that are easy to repeat, read:

- `asset/docs/agent-mistake/op-function.md`
- `asset/docs/agent-mistake/ci-loader-contract-verification.md`
- `asset/docs/agent-mistake/misspelled-public-name.md`

## Verification

For PHP or PHTML changes, run `php -l` on changed files.

When practical, run module CI through the project `cicd` command and report the exact command and result.
