# countup.php

`countup.php` は、テキストファイル形式のアクセスカウンターを加算します。

テンプレートから次のように呼び出します。

```php
<?php OP()->Template('asset:/module/counter/countup.php') ?>
```

カウンターファイルへ書き込む前に `init.php` を呼び出します。
`asset/db/` が準備できていない場合は、設定手順を表示し、カウンターを加算しません。
再利用する countup behavior は `Countup.class.php` に実装されています。
この entry は `Counter.class.php` を読み込みません。

標準では、session 内の最初の eligible access をカウントします。
countup が成功した後、module は current counter domain を framework session に記録します。
同一 session 内の以後の access は、counter を再加算しません。
User-Agent が空、または common robot-like User-Agent pattern に一致する access は、counter file を更新する前に skip します。
`OP()->Config('counter')['skip'] === 'admin'` の場合だけ、`Countup::Increment()` は `OP()->isAdmin()` をチェックします。
その config value が設定され、かつ `OP()->isAdmin()` が `true` の場合、counter は加算を skip します。
管理者リクエストを skip した場合、counter は `D()` debug message を出しません。

現在のリクエストドメインに対して、次のファイルを更新します。

- `asset/db/counter/<domain>/total.txt`
- `asset/db/counter/<domain>/yyyy/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/dd.txt`

ファイル更新には `flock()` を使い、同時アクセスでカウントが上書きされにくいようにします。
