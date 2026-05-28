# countup.php

`countup.php` increments the file-based access counter.

Use it from a template:

```php
<?php OP()->Template('asset:/module/counter/countup.php') ?>
```

It calls `init.php` before writing counter files.
If `asset/db/` is not ready, it displays setup guidance and does not increment the counter.
The reusable countup behavior is implemented by `Countup.class.php`.
This entry does not load `Counter.class.php`.

By default, the first eligible access in a session is counted.
After a successful countup, the module records the current counter domain in the framework session.
Further accesses in the same session do not increment the counter again.
Only when `OP()->Config('counter')['skip'] === 'admin'`, `Countup::Increment()` checks `OP()->isAdmin()`.
If that config value is set and `OP()->isAdmin()` is `true`, the counter skips incrementing.
When an admin request is skipped, the counter does not output a `D()` debug message.

It updates these files for the current request domain:

- `asset/db/counter/<domain>/total.txt`
- `asset/db/counter/<domain>/yyyy/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/dd.txt`

The file update uses `flock()` so simultaneous requests do not overwrite each other.
