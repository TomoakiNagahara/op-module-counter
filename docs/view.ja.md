# view.php

`view.php` は、テキストファイル形式のアクセスカウンターを表示します。
カウンター値を読み込み、`OP()->Template()` 経由で `view.phtml` を表示します。

テンプレートから次のように呼び出します。

```php
<?php OP()->Template('asset:/module/counter/view.php') ?>
```

カウンターファイルを読み込む前に `init.php` を呼び出します。
`asset/db/` が準備できていない場合は、カウンター値ではなく設定手順を表示します。

表示する項目は次の通りです。

- `Today : <count>`
- `Yesterday : <count>`
- `This month : <count>`
- `This year : <count>`
- `Total : <count>`

今月、今年、トータルは、あらかじめ保存時に更新された `total.txt` から読み取ります。
区切り文字の列を中央揃えにし、各行を同じ `key : value` 形式で表示します。
