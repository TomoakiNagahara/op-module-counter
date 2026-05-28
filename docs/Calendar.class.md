# Calendar.class.php

`Calendar.class.php` owns optional calendar-display logic for the counter module.

The class is intentionally not loaded by `countup.php`, `view.php`, or `Counter.class.php`.
`calendar.php` loads it only when the calendar display is requested.

This follows the ONEPIECE Framework memory guideline: optional features that an end user may never use should not expand code into memory during normal successful requests.

The file stays at the module root as a visible `*.class.php` class file so ONEPIECE Framework CI can inspect it.
Runtime memory saving comes from not requiring the file during normal `countup.php` and `view.php` requests, not from hiding it from the CI collector.

`Calendar` reads daily `dd.txt` files for the selected month and builds the array rendered by `calendar.phtml`.
It does not increment counters.
Shared storage path and domain helpers come from `Common.class.php`.

The class belongs to the `OP\MODULE\COUNTER` subnamespace so it does not collide with other module helper classes.
