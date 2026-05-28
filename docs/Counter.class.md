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
- counter config access through `OP()->Config('counter')`
- domain normalization
- daily, monthly, yearly, and total counter paths
- file locking for counter writes
- file reads for display values

Initialization recovery guidance is not kept in this class.
When initialization fails, `Counter::Init()` loads `InitGuidance.class.php` on demand and delegates the guidance rendering to it.
This keeps rarely used error-handling code out of memory during normal counter requests.

`ci/Counter.php` is the matching CI loader.
Each deterministic method has its own CI config file under `ci/Counter/`.
`CI_AllMethods()` intentionally lists only deterministic helper methods such as `IsOne()` and `NormalizeDomain()`.
Runtime methods like `ShouldCount()`, `Increment()`, and `Counts()` depend on request state, config, debug output, dates, or file storage, so they are not listed as class-level deterministic CI targets.

See `asset/docs/cicd/ci-file-layout.md` for the framework-wide CI file layout rules.
