# save.php

`save.php` increments the file-based access counter.

Use it from a template:

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
```

It calls `init.php` before writing counter files.
If `asset/db/` is not ready, it displays setup guidance and does not increment the counter.

If `OP()->isAdmin()` is `true`, it does not increment the counter by default.
An admin request is counted only when `OP()->Request('admin')` is `1`.

It updates these files for the current request domain:

- `asset/db/counter/<domain>/total.txt`
- `asset/db/counter/<domain>/yyyy/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/dd.txt`

The file update uses `flock()` so simultaneous requests do not overwrite each other.
