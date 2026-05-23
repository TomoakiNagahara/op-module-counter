# config.php

counter module は default config template を同梱します。

```text
asset/module/counter/config.php
```

この file は自動では読み込まれません。
counter config を有効化または変更する場合は、application config area に copy してください。

```sh
cp asset/module/counter/config.php asset/config/counter.php
```

`OP()->Config('counter')` が読み込む application config は次です。

```text
asset/config/counter.php
```

任意の local override config は次です。

```text
asset/config/_counter.php
```

`asset/config/_counter.php` は `asset/config/counter.php` の後に読み込まれ、local-only value を override できます。

copy step を明示することで、runtime behavior を制御している config file が user にとって明瞭になります。

## Options

### `skip`

default:

```php
[
	//	When set to "admin", admin access is not counted.
	'skip' => 'admin',
]
```

template では `null` ではなく明示的な `admin` value を使います。
これは、third-party user が documentation を読んだり AI assistant に尋ねたりしなくても、`config.php` を見るだけで設定可能な値を理解できるようにするためです。

`skip` が `admin` の場合、`Counter::ShouldCount()` は `OP()->isAdmin()` をチェックします。
`OP()->isAdmin()` が `true` なら、counter は加算を skip します。
admin request を skip した場合、counter は常に `D()` debug message を出します。

`skip` が `admin` ではない場合、全ての request をカウントします。

application config の例:

```php
<?php
return [
	'skip' => 'admin',
];
```
