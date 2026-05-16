# view.php

`view.php` displays the file-based access counter.
It reads counter values and renders `view.phtml` through `OP()->Template()`.

Use it from a template:

```php
<?php OP()->Template('asset:/module/counter/view.php') ?>
```

It calls `init.php` before reading counter files.
If `asset/db/` is not ready, it displays setup guidance instead of the counter values.

It displays:

- `Today : <count>`
- `Yesterday : <count>`
- `This month : <count>`
- `This year : <count>`
- `Total : <count>`

The monthly, yearly, and total values are read from precomputed `total.txt` files.
The separator column is centered so each row uses the same `key : value` format.
