# Init.class.php

`Init.class.php` owns the counter initialization checks.

`init.php` is intentionally only an entry file.
It loads `Init.class.php`, creates `OP\MODULE\COUNTER\Init`, and calls `Init()`.

The class checks whether `asset/db/`:

- exists
- is a directory
- is writable by the current PHP process

The `asset/db/` path is supplied by `Common.class.php`.

If initialization fails, `Init` loads `InitGuidance.class.php` on demand and delegates the recovery guidance rendering to it.
This keeps the entry file thin and keeps recovery guidance out of memory unless initialization has actually failed.

`Init.class.php` is a visible module class file, so it must remain compatible with class-based CI.
Its deterministic decision method is inspected through `ci/Init.php` and `ci/Init/DbRootIssues.php`.
