# init.php

`init.php` checks whether the access counter can use `asset/db/`.

`save.php` and `view.php` call it before reading or writing counter files.
If guidance must be displayed, `init.php` renders `init.phtml` through `OP()->Template()`.

`init.php` is intentionally a thin entry file.
The reusable initialization logic is owned by `Counter.class.php` so the module behavior is covered by the ONEPIECE Framework class-based CI flow.

It checks:

- Whether `asset/db/` exists.
- Whether `asset/db/` is a directory.
- Whether `asset/db/` is writable by the current PHP process.

If the check fails, it displays setup steps for the site operator and returns `false`.
The counter does not read or write storage when initialization fails.

The guidance renderer is split into `CounterInitGuidance.class.php`.
`Counter.class.php` loads that class only after initialization issues are found.
ONEPIECE Framework treats unnecessary memory use as forbidden, so rarely used recovery logic should not be expanded into memory during normal successful requests.

When possible, the setup guidance also displays the PHP process user and group.
It checks POSIX process information first and falls back to `id -un` / `id -gn` when shell execution is available.
The displayed `chown` command uses that detected user and group so the operator can apply a concrete ownership fix.
