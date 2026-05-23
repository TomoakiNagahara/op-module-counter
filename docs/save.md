# save.php

`save.php` increments the file-based access counter.

Use it from a template:

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
```

It calls `init.php` before writing counter files.
If `asset/db/` is not ready, it displays setup guidance and does not increment the counter.
The reusable save behavior is implemented by `Counter.class.php`.

By default, every request is counted.
Only when `OP()->Config('counter')['skip'] === 'admin'`, `Counter::ShouldCount()` checks `OP()->isAdmin()`.
If that config value is set and `OP()->isAdmin()` is `true`, the counter skips incrementing.
When an admin request is skipped, `D()` always outputs a debug message for administrators.

It updates these files for the current request domain:

- `asset/db/counter/<domain>/total.txt`
- `asset/db/counter/<domain>/yyyy/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/dd.txt`

The file update uses `flock()` so simultaneous requests do not overwrite each other.
