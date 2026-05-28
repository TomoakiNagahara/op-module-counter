# OP Module Counter

This module provides a file-based access counter.

## Usage

Call `countup.php` from the top page to increment the counter:

```php
<?php OP()->Template('asset:/module/counter/countup.php') ?>
```

Call `view.php` from the top page to display the counter:

```php
<?php OP()->Template('asset:/module/counter/view.php') ?>
```

Call `calendar.php` to display daily counts in a monthly calendar:

```php
<?php OP()->Template('asset:/module/counter/calendar.php') ?>
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

`countup.php` and `view.php` call `init.php` before reading or writing counter files.

`init.php` is a thin entry file that calls `Init.class.php`.
`Init.class.php` checks whether `asset/db/` exists, is a directory, and is writable by PHP.
If the storage is not ready, it displays recovery steps instead of reading or writing counter files.
The recovery guidance is split into `InitGuidance.class.php` and is loaded only after initialization fails.

## Implementation

Reusable module logic is implemented in class files so ONEPIECE Framework CI can inspect them.
Display behavior is in `Counter.class.php`.
Countup behavior is in `Countup.class.php`.
Initialization behavior is in `Init.class.php`.
Shared path and domain helpers are in `Common.class.php`.
Each class has a matching CI loader under `ci/`.

## Config

Default config template is stored in `asset/module/counter/config.php`.
It is not loaded automatically.
Copy it to `asset/config/counter.php` when you want to enable or customize counter config.
