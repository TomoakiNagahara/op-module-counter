asset/module/counter/にアクセスカウンターを作って欲しい。

# アクセスカウンターの仕組み

保存と表示は別のファイルに行う

## 保存
topページからは、<?php OP()->Template('asset:/module/counter/save.php') ?>で呼び出すと、訪問毎に1ずつカウントアップされる。
データベースは使わず、テキストファイルに保存する。
テキストファイルの保存先は、asset/db/counter/（ドメイン名）/yyyy/mm/dd.txt

## 表示
topページからは、<?php OP()->Template('asset:/module/counter/view.php') ?>で呼び出すと、訪問回数が表示される。

上記の仕組みで何か質問はありますか？
