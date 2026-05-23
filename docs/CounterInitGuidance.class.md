# CounterInitGuidance.class.php

`CounterInitGuidance.class.php` owns initialization failure guidance for the counter module.

It is intentionally separate from `Counter.class.php`.
Normal counter requests should not load recovery logic that is used only when `asset/db/` is missing, not a directory, or not writable.

The class uses the `OP\MODULE\COUNTER` namespace.
The counter main class is exposed as `OP\MODULE\Counter`, but helper classes stay inside the module subnamespace to avoid collisions with other MODULE classes.

ONEPIECE Framework treats unnecessary memory use as forbidden.
For that reason, `Counter::Init()` loads this class only after initialization issues are found.

The class handles:

- rendering `init.phtml`
- detecting the current PHP process user and group
- falling back to `id -un` and `id -gn` when POSIX process functions are unavailable
- building the concrete `chown` command shown to the site operator
