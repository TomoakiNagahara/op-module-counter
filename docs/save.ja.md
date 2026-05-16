# save.php

`save.php` は、テキストファイル形式のアクセスカウンターを加算します。

テンプレートから次のように呼び出します。

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
```

カウンターファイルへ書き込む前に `init.php` を呼び出します。
`asset/db/` が準備できていない場合は、設定手順を表示し、カウンターを加算しません。

`OP()->isAdmin()` が `true` の場合、通常はカウンターを加算しません。
ただし、`OP()->Request('admin')` が `1` の場合だけ、管理者リクエストでも加算します。

現在のリクエストドメインに対して、次のファイルを更新します。

- `asset/db/counter/<domain>/total.txt`
- `asset/db/counter/<domain>/yyyy/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/dd.txt`

ファイル更新には `flock()` を使い、同時アクセスでカウントが上書きされにくいようにします。
