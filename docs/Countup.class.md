# Countup.class.php

`Countup.class.php` owns counter increment behavior.

`countup.php` loads this class after `init.php` succeeds.
The countup request does not load `Counter.class.php`.

The class handles:

- deciding whether the current request should be counted
- skipping access that has already been counted in the same session
- incrementing daily, monthly, yearly, and total counter files
- recording a successful countup in the framework session
- writing counter files with `flock()`

Admin skip configuration and counter paths come from `Common.class.php`.
Session state is stored through `OP_SESSION` and `self::Session()` so the module keeps the marker in its package-scoped session namespace and does not use raw `$_SESSION`.

`Countup.class.php` is a visible module class file, so it must use `OP_CI`.
Its runtime behavior depends on session state, request admin state, config, dates, and file storage, so it currently has no deterministic method-level CI target.
