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

## Initialization

`save.php` and `view.php` call `init.php` before reading or writing counter files.

`init.php` is a thin entry file that calls `Counter.class.php`.
`Counter.class.php` checks whether `asset/db/` exists, is a directory, and is writable by PHP.
If the storage is not ready, it displays recovery steps instead of reading or writing counter files.

## Implementation

Reusable module logic is implemented in `Counter.class.php` so ONEPIECE Framework CI can inspect the class.
The matching CI loader is `ci/Counter.php`.
Method-level CI configs are stored under `ci/Counter/`.
