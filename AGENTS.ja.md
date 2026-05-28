# AGENTS.md

この file は、`asset/module/counter/` を編集する AI coding agent のための薄い entry guide です。

詳細な rule は、その責任を持つ document に置きます。より適切な owner document がある場合、この file に仕様をコピーして厚くしないでください。

## 最初に読むもの

この module について回答または編集する前に、毎回 `docs/dictation.md` を読んでください。
user の最新の口頭または chat 指示がそこに要約されている可能性があります。

日本語の original dictation が必要な場合は、`docs/dictation.ja.md` を読んでください。

## module documents

- overview and usage: `README.md`
- shared helpers: `docs/Common.class.md`
- counter behavior: `docs/Counter.class.md`
- countup behavior: `docs/Countup.class.md`
- initialization: `docs/init.md`
- initialization class: `docs/Init.class.md`
- initialization guidance helper: `docs/InitGuidance.class.md`
- countup entry: `docs/countup.md`
- view entry: `docs/view.md`
- calendar entry: `docs/calendar.md`
- calendar helper: `docs/Calendar.class.md`
- config: `docs/config.md`
- original Japanese dictation: `docs/dictation.ja.md`

English module document を変更した場合は、対応する `.ja.md` translation も更新してください。

## framework documents

- repository agent guide: `asset/docs/AGENTS.md`
- customization boundaries: `asset/docs/CUSTOMIZATION_MAP.md`
- documentation authoring and dictation placement: `asset/docs/documentation-authoring.md`
- framework-wide coding rules: `asset/docs/op/coding-rules.md`
- UNIT/MODULE authoring, namespace, lazy loading, and CI visibility: `asset/docs/op/unit-module-authoring.md`
- split CI file layout: `asset/docs/cicd/ci-file-layout.md`
- global `OP()` function contract: `asset/docs/core/op-function.md`
- request API: `asset/docs/core/op-request.md`
- admin API: `asset/docs/core/is-admin.md`

繰り返しやすい過去の mistake は、次を読んでください。

- `asset/docs/agent-mistake/op-function.md`
- `asset/docs/agent-mistake/ci-loader-contract-verification.md`
- `asset/docs/agent-mistake/misspelled-public-name.md`

## verification

PHP または PHTML を変更した場合は、変更 file に `php -l` を実行してください。

可能な場合は、project の `cicd` command で module CI を実行し、実行した command と結果を報告してください。
