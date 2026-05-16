# save.php

`save.php` は、テキストファイル形式のアクセスカウンターを加算します。

テンプレートから次のように呼び出します。

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
```

現在のリクエストドメインに対して、次のファイルを更新します。

- `asset/db/counter/<domain>/total.txt`
- `asset/db/counter/<domain>/yyyy/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/total.txt`
- `asset/db/counter/<domain>/yyyy/mm/dd.txt`

ファイル更新には `flock()` を使い、同時アクセスでカウントが上書きされにくいようにします。
