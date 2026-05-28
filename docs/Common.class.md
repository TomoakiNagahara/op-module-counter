# Common.class.php

`Common.class.php` owns shared counter module helpers.

It centralizes logic that multiple classes need:

- `DbRoot()`
- `StorageRoot()`
- `Paths()`
- `Domain()`
- `NormalizeDomain()`
- `IsAdminSkipEnabled()`
- `HasConfigFile()`

`Counter.class.php`, `Countup.class.php`, `Init.class.php`, and `Calendar.class.php` should get these shared values through `OP\MODULE\COUNTER\Common` instead of copying the same methods into each class.

`Common.class.php` is a visible module class file, so deterministic shared behavior must remain compatible with class-based CI.
`NormalizeDomain()` is inspected through `ci/Common.php` and `ci/Common/NormalizeDomain.php`.
