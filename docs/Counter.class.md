# Counter.class.php

`Counter.class.php` owns the reusable logic for the file-based access counter.

The entry files remain separate:

- `init.php` calls initialization logic.
- `save.php` increments counters.
- `view.php` renders counter values.

Reusable behavior belongs in this class instead of `function.php` because ONEPIECE Framework CI inspects module class files that use `OP_CI`.

The class maintains:

- initialization checks for `asset/db/`
- admin counting rules
- domain normalization
- daily, monthly, yearly, and total counter paths
- file locking for counter writes
- file reads for display values

`ci/Counter.php` is the matching CI loader.
Each deterministic method has its own CI config file under `ci/Counter/`.

See `asset/docs/cicd/ci-file-layout.md` for the framework-wide CI file layout rules.
