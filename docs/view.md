# view.php

`view.php` displays the file-based access counter.

Use it from a template:

```php
<?php OP()->Template('asset:/module/counter/view.php') ?>
```

It displays:

- Today
- Yesterday
- This month
- This year
- Total

The monthly, yearly, and total values are read from precomputed `total.txt` files.
