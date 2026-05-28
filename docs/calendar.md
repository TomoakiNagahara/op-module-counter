# calendar.php

`calendar.php` displays daily access counts in a monthly calendar.

Use it from a template:

```php
<?php OP()->Template('asset:/module/counter/calendar.php') ?>
```

It calls `init.php` before reading counter files.
If `asset/db/` is not ready, it displays setup guidance instead of the calendar.

The displayed month is selected from request values:

- `counter_year`
- `counter_month`

When either value is missing or outside the accepted range, the current month is displayed.

The calendar reads only daily `dd.txt` files for the selected month.
It does not increment counters and does not scan unrelated months.

The calendar aggregation logic is implemented in `Calendar.class.php`.
That file is loaded only by `calendar.php`, so normal `countup.php` and `view.php` requests do not load optional calendar-display code.
