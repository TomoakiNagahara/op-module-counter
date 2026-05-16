# OP Module Counter

This module provides a file-based access counter.

## Usage

Call `save.php` from the top page to increment the counter:

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
```

Call `view.php` from the top page to display the counter:

```php
<?php OP()->Template('asset:/module/counter/view.php') ?>
```

## Storage

The counter does not use a database.

Counter files are stored under `asset/db/counter/<domain>/`.

- `total.txt`
- `yyyy/total.txt`
- `yyyy/mm/total.txt`
- `yyyy/mm/dd.txt`

The daily file stores the count for one day.
The yearly, monthly, and total files are updated at save time so display does not need to scan every daily file.
