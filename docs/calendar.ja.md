# calendar.php

`calendar.php` は、日ごとの access count を月別 calendar で表示します。

template から次のように呼び出します。

```php
<?php OP()->Template('asset:/module/counter/calendar.php') ?>
```

counter file を読む前に `init.php` を呼び出します。
`asset/db/` が利用できない場合は、calendar ではなく setup guidance を表示します。

表示する月は request value で選択できます。

- `counter_year`
- `counter_month`

どちらかの値が未指定、または許可範囲外の場合は、現在の月を表示します。

calendar は、選択された月の日別 `dd.txt` file だけを読みます。
counter の加算は行わず、関係のない月の scan もしません。

calendar aggregation logic は `Calendar.class.php` に実装されています。
この file は `calendar.php` からだけ読み込まれるため、通常の `countup.php` や `view.php` request では optional な calendar-display code を読み込みません。
