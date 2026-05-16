# save.php

`save.php` increments the file-based access counter.

Use it from a template:

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
```

It updates these files for the current request domain:

- `asset/db/counter/<domain>/total.txt`
- `asset/db/counter/<domain>/yyyy/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/dd.txt`

The file update uses `flock()` so simultaneous requests do not overwrite each other.
