# AGENTS.md

This file is the working guide for AI coding agents editing `asset/module/counter/`.

Read this file before changing the counter module. Also read `docs/dictation.md` every time before answering or editing, because the user's latest instructions may be added there.

## Required Context

Before making changes, read these documents:

- `docs/dictation.md`
- `asset/docs/core/op-function.md`
- `asset/docs/core/op-request.md` when request values are involved
- `asset/docs/core/is-admin.md` when admin behavior is involved
- `asset/docs/CUSTOMIZATION_MAP.md` when deciding whether a change belongs in this module

Use `OP()` directly. The global `OP()` function can be called from any namespace, so do not write `\OP\OP()` and do not import `OP\OP` with `use function`.

For the prior mistake and root-cause note behind this rule, read `asset/docs/agent-mistake/op-function.md`.

## Module Purpose

This module is a file-based access counter.

It is called from a top page like this:

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
<?php OP()->Template('asset:/module/counter/view.php') ?>
```

`save.php` increments counters.
`view.php` displays counters.

Saving and viewing must remain separate files.

## Storage Contract

Do not use a database.

Store text files under:

```text
asset/db/counter/<domain>/yyyy/mm/dd.txt
```

The domain is the current request domain. Subdomains must be counted separately.

Maintain precomputed total files so display does not need to scan every daily file:

```text
asset/db/counter/<domain>/total.txt
asset/db/counter/<domain>/yyyy/total.txt
asset/db/counter/<domain>/yyyy/mm/total.txt
asset/db/counter/<domain>/yyyy/mm/dd.txt
```

Use file locking for writes.

## Initialization

Keep initialization checks in `init.php`.

Both `save.php` and `view.php` must call `init.php` before reading or writing counter files.

`init.php` must call the counter initialization logic before reading or writing counter files.
Keep reusable counter logic in `Counter.class.php`, not in standalone function files, because ONEPIECE Framework CI inspects class files that use `OP_CI`.
Keep `init.php`, `save.php`, and `view.php` as thin entry files.

`Counter.class.php` must check whether `asset/db/` exists, is a directory, and is writable by the current PHP process.

If storage is not ready, show English guidance through `init.phtml`. Include concrete setup commands and the detected PHP process user and group when possible.

## Counting Rules

Do not count admin access by default.

If `OP()->isAdmin()` is `true`, skip incrementing.

Exception: if `OP()->isAdmin()` is `true` and `OP()->Request('admin')` is `1`, then count the request.

Treat only this request value as count-enabled for admin requests:

- `1`

## Display Rules

Display these values:

- Today
- Yesterday
- This month
- This year
- Total

Use this display format:

```text
Key : Number
```

Align the ` : ` separator as the centered column.

## Source And Template Rules

Write source code, comments, and user-visible messages in English.

Do not put HTML markup inside `.php` files.

Put HTML markup in `.phtml` template files and render it with `OP()->Template()`.

Keep `save.php` and `view.php` as controller-like entry files.

Use `.phtml` for display templates.

Do not add reusable module behavior to `function.php`. Put reusable behavior in `Counter.class.php`.
Use the ONEPIECE Framework split CI layout: `ci/Counter.php` is the loader called by CI, and each method has its own file under `ci/Counter/<Method>.php`.
See `asset/docs/cicd/ci-file-layout.md` before adding or changing CI files.

Add PHPDoc to `.phtml` files.

Add Eclipse-compatible `/* @var $name type */` type hints for variables used inside `.phtml` files. Avoid PHPStan-style array shapes in PHTML templates.

Keep PHPDoc Eclipse-compatible in all PHP and PHTML files. Avoid PHPStan/Psalm-specific array shapes and generic array syntax such as `array{...}` or `array<string,int>`.

Use `OP()->Request()` instead of raw request superglobals where appropriate.

Avoid raw `$_GET`, `$_POST`, `$_REQUEST`, `$_COOKIE`, `$_SESSION`, and `$_SERVER` unless there is a clear reason. The current domain detection is one of the few places where server host information is needed.

Do not use `var_dump()` or `print_r()` for debugging.

## PHP Comment Style

For multiline comments, put a tab immediately after `/**`.

Correct:

```php
/**	Comment
 *
 */
```

Incorrect:

```php
/** Comment
 *
 */
```

When adding PHPDoc file headers, set:

```php
* @author    Codex CLI
```

## Documentation Rules

When behavior changes, update the matching module documentation.

Use same-name documentation where possible:

- `docs/init.md` and `docs/init.ja.md`
- `docs/save.md` and `docs/save.ja.md`
- `docs/view.md` and `docs/view.ja.md`

English documents are canonical. Japanese translations are required so the user can verify the English document accurately.

## Verification

For PHP changes, run syntax checks on changed PHP and PHTML files.

For counter behavior, verify at least:

- normal access increments
- admin access does not increment
- admin access with `admin=1` increments
- uninitialized `asset/db/` displays setup guidance
- initialized storage displays counter values
- PHP files do not contain display HTML
- PHP and PHTML user-visible text remains English

When practical, run:

```sh
./cicd cd=0
```

The project may emit existing submodule warnings, but the exit code should be checked.
