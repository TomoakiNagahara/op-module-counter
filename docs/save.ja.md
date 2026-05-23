# save.php

`save.php` は、テキストファイル形式のアクセスカウンターを加算します。

テンプレートから次のように呼び出します。

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
```

カウンターファイルへ書き込む前に `init.php` を呼び出します。
`asset/db/` が準備できていない場合は、設定手順を表示し、カウンターを加算しません。
再利用する save behavior は `Counter.class.php` に実装されています。

標準では全ての request をカウントします。
`OP()->Config('counter')['skip'] === 'admin'` の場合だけ、`Counter::ShouldCount()` は `OP()->isAdmin()` をチェックします。
その config value が設定され、かつ `OP()->isAdmin()` が `true` の場合、counter は加算を skip します。
管理者リクエストを skip した場合は、administrator 向けに `D()` で常に debug message を出します。

現在のリクエストドメインに対して、次のファイルを更新します。

- `asset/db/counter/<domain>/total.txt`
- `asset/db/counter/<domain>/yyyy/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/dd.txt`

ファイル更新には `flock()` を使い、同時アクセスでカウントが上書きされにくいようにします。
