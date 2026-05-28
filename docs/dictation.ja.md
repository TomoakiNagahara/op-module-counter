アクセスカウンターをAIに作って貰う
===

# アクセスカウンターの仕組み

## 前提
 * カウントアップと表示は別のファイルにする
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
 * `function.php` では CI が行われないため、再利用する module logic は ONEPIECE Framework の class-based CI に従って責務ごとの class file に置く
 * visible な module class file は `OP_CI` を使い、対応する CI loader は `ci/<Class>.php` に置く
 * CI config は、`ci/<Class>.php` から同名フォルダを読み込み、各メソッド名のphpファイルを `ci/<Class>/<Method>.php` に置く
 * CI file の汎用的な作法は `asset/docs/cicd/ci-file-layout.md` と `asset/docs/cicd/ci-file-layout.ja.md` に残す
 * `phtml` ファイル内にもPHPDocを追加して下さい。
 * `phtml` ファイル内の変数には、`/* @var */` を使って型ヒントを付ける

## 初期化
 * `asset/db/` のパーミッションが適切かチェックして、不適切ならどうすればいいか、ユーザーに手順を教えてあげて欲しい。
 * initialization entry は `init.php` にまとめて、それを `countup.php` と `view.php` から呼び出す形式にしたい。
 * Init 機能の実装は `Init.class.php` に分離する。`init.php` は `Init` class だけを呼び出す形にする。
 * `DisplayInitGuidance()`、`PhpProcessOwner()`、`ShellCommand()` は初期化エラー時だけ必要なので、`Counter.class.php` ではなく別クラスに分離する。
 * initialization guidance の class name は、`OP\MODULE\COUNTER\CounterInitGuidance` では冗長なので、`OP\MODULE\COUNTER\InitGuidance` にする。file name も `InitGuidance.class.php` にする。
 * ONEPIECE Framework ではメモリーの無駄使いは禁忌なので、不要な処理を通常 request の memory に展開しない。
 * module の main class は `OP\MODULE` に置くが、sub class は他 module と衝突しないように module 名の subnamespace に隔離する。counter module の sub class は `OP\MODULE\COUNTER` に置く。

## 保存
 * topページからは、`<?php OP()->Template('asset:/module/counter/countup.php') ?>`で呼び出すと、訪問毎に1ずつカウントアップされる。
 * `save.php` は `countup.php` に rename する。
 * countup request では `Counter.class.php` を読み込まない。
 * `Countup::Increment()` は `bool` を返す。`countup.php` は success / failure だけ分かればよいので、使わない count array を作らない。
 * countup が成功したら framework session に記録し、同一 session 内の以後の access は countup しない。
 * データベースは使わず、テキストファイルに保存する。
 * テキストファイルの保存先は `asset/db/counter/（ドメイン名）/yyyy/mm/dd.txt` です。
 * ドメイン名は、自分のドメイン名です。これは、サブドメイン毎にアクセス回数を別に保存したいからです。
 * `DbRoot()` など class ごとに分散している共通部品は `Common.class.php` に置き、各 class は `OP\MODULE\COUNTER\Common` から調達する。
 * 原則、eligible access をカウントする
 * `OP()->Config('counter')['skip'] === 'admin'` の場合だけ、`OP()->isAdmin()` をチェックし、`true` ならカウントを skip する
 * admin access を skip した場合、`D()` debug message は出さない
 * UNIT/MODULE の default config は、その UNIT/MODULE directory の `config.php` に置く
 * counter module の default config template は `asset/module/counter/config.php` に置く
 * module 側の `config.php` は自動的に読み込まれない
 * counter module の user-defined config は、user が `asset/module/counter/config.php` を `asset/config/counter.php` に copy して作る

## 表示
 * topページからは `<?php OP()->Template('asset:/module/counter/view.php') ?>` で呼び出すと、訪問回数が表示される。
 * 表示は、今日と昨日と今月と今年とトータルでお願いします。
 * 計算しやすいように、各年と各月はtotal.txtに保存しておく。
 * 表示形式は `キー : 数値` の形式でお願いします。 ` : ` で中央揃えにして

## カレンダー表示
 * topページからは `<?php OP()->Template('asset:/module/counter/calendar.php') ?>` で呼び出すと、日別アクセス数をカレンダー表示できる。
 * calendar display は optional feature であり、end user が全く使わない可能性がある。
 * 通常の `countup.php` / `view.php` request で calendar logic を memory に展開しない。
 * calendar logic は `Counter.class.php` には置かず、`Calendar.class.php` の `OP\MODULE\COUNTER\Calendar` に置く。
 * `calendar.php` が要求された時だけ、`__DIR__ . '/Calendar.class.php'` で calendar class を読み込む。
 * `Calendar.class.php` は package behavior を所有するため、CI から隠さない。
 * `Calendar.class.php` は module root の visible `*.class.php` として置き、`OP_CI`、`CI_AllMethods()`、`ci/Calendar.php`、`ci/Calendar/<Method>.php` による split CI file layout に従う。
 * calendar は選択された月の日別 `dd.txt` file だけを読む。
 * calendar は counter の加算を行わず、関係のない月の scan もしない。
 * 表示月は `OP()->Request('counter_year')` と `OP()->Request('counter_month')` で選択する。
 * 表示月が未指定または範囲外の場合は現在の月を表示する。
 * 日曜の day は赤、土曜の day は青で表示する。
 * count の数字が `0` の場合は、薄い灰色で表示する。
