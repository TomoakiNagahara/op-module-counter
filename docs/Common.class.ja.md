# Common.class.php

`Common.class.php` は、counter module の shared helper を所有します。

複数 class が必要とする次の logic を集約します。

- `DbRoot()`
- `StorageRoot()`
- `Paths()`
- `Domain()`
- `NormalizeDomain()`
- `IsAdminSkipEnabled()`
- `HasConfigFile()`

`Counter.class.php`、`Countup.class.php`、`Init.class.php`、`Calendar.class.php` は、同じ method を各 class にコピーせず、`OP\MODULE\COUNTER\Common` から shared value を取得します。

`Common.class.php` は visible な module class file なので、deterministic な shared behavior は class-based CI に対応している必要があります。
`NormalizeDomain()` は `ci/Common.php` と `ci/Common/NormalizeDomain.php` で検査します。
