# Counter.class.php

`Counter.class.php` owns the reusable logic for the file-based access counter.

The entry files remain separate:

- `view.php` renders counter values.

Reusable behavior belongs in this class instead of `function.php` because ONEPIECE Framework CI inspects module class files that use `OP_CI`.

The class maintains:

- admin counting rules
- counter config access through `OP()->Config('counter')`
- daily, monthly, yearly, and total counter paths
- file locking for counter writes
- file reads for display values

Shared path and domain helpers are supplied by `Common.class.php`.
Countup behavior is not kept in this class.
`countup.php` loads `Countup.class.php`, so countup requests do not load `Counter.class.php`.

Initialization logic is not kept in this class.
`init.php` calls `Init.class.php`, and `Init.class.php` loads `InitGuidance.class.php` only after initialization fails.
This keeps counter runtime behavior and initialization recovery behavior separated.

Calendar display logic is also not kept in this class.
`calendar.php` loads `Calendar.class.php` only when calendar display is requested, because many sites may never use the calendar feature.

`ci/Counter.php` is the matching CI loader.
Each deterministic method has its own CI config file under `ci/Counter/`.
`CI_AllMethods()` intentionally lists only deterministic helper methods such as `IsOne()`.
Runtime methods like `Counts()` depend on dates or file storage, so they are not listed as class-level deterministic CI targets.

See `asset/docs/cicd/ci-file-layout.md` for the framework-wide CI file layout rules.
