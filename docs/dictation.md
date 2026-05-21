アクセスカウンターをAIに作って貰う
===

# アクセスカウンターの仕組み

## 前提
 * 保存と表示は別のファイルにする
 * データベースは使わず、テキストファイルに保存する
 * ソースコードは全て英語で作成する
 * ソースコードのコメントは全て英語で作成する
 * エラーなど表示するメッセージは全て英語で作成する（後述の注意を参照）
 * HTMLのソースコードは、PHPのソースコード内に記述しない（メンテナンス性が落ちる）
 * HTMLのソースコードは、テンプレートファイルに分け `OP()->Template()` で呼び出す
 * テンプレートファイルの拡張子は `.phtml` にする
 * セキュリティを重視して下さい

### 注意
 * ONEPIECE Frameworkには、翻訳機能があるので、英語前提で作成し、ユーザーが必要な場合は翻訳機能を使えば良い

### コーディングルール
 * PHPdocの `@author`を `Codex CLI` にする
 * 複数行コメントの開始の　`/**` の次はスペースではなくタブにすること
 * `OP()` はネームスペースなしでどこでも呼べるのでバックスラッシュは要らない
 * `function.php` では CI が行われないため、再利用する module logic は ONEPIECE Framework の class-based CI に従って `Counter.class.php` に置く
 * `Counter.class.php` は `OP_CI` を使い、対応する CI loader は `ci/Counter.php` に置く
 * CI config は、`ci/Counter.php` から同名フォルダを読み込み、各メソッド名のphpファイルを `ci/Counter/<Method>.php` に置く
 * CI file の汎用的な作法は `asset/docs/cicd/ci-file-layout.md` と `asset/docs/cicd/ci-file-layout.ja.md` に残す
 * `phtml` ファイル内にもPHPDocを追加して下さい。
 * `phtml` ファイル内の変数には、`/* @var */` を使って型ヒントを付ける

## 初期化
 * `asset/db/` のパーミッションが適切かチェックして、不適切ならどうすればいいか、ユーザーに手順を教えてあげて欲しい。
 * チェックは `init.php` にまとめて。それを `save.php` と `view.php` から呼び出す形式にしたい。

## 保存
 * topページからは、`<?php OP()->Template('asset:/module/counter/save.php') ?>`で呼び出すと、訪問毎に1ずつカウントアップされる。
 * データベースは使わず、テキストファイルに保存する。
 * テキストファイルの保存先は `asset/db/counter/（ドメイン名）/yyyy/mm/dd.txt` です。
 * ドメイン名は、自分のドメイン名です。これは、サブドメイン毎にアクセス回数を別に保存したいからです。
 * OP()->isAdmin()が `true` の場合はカウントしない。ただし、Admin判定が `true` でも `OP()->Request('admin')` で取得した値が `1` の場合はカウントする

## 表示
 * topページからは `<?php OP()->Template('asset:/module/counter/view.php') ?>` で呼び出すと、訪問回数が表示される。
 * 表示は、今日と昨日と今月と今年とトータルでお願いします。
 * 計算しやすいように、各年と各月はtotal.txtに保存しておく。
 * 表示形式は `キー : 数値` の形式でお願いします。 ` : ` で中央揃えにして
