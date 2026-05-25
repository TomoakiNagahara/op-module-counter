# AGENTS.ja.md

このファイルは、`asset/module/counter/` を編集する AI コーディングエージェント向けの作業ガイドです。

カウンターモジュールを変更する前に、このファイルを読んでください。また、回答や編集の前に毎回 `docs/dictation.md` を読んでください。ユーザーの最新指示がそこに追記されている可能性があります。

## 必須の確認事項

変更前に次のドキュメントを読んでください。

- `docs/dictation.md`
- `asset/docs/core/op-function.md`
- request value を扱う場合は `asset/docs/core/op-request.md`
- admin behavior を扱う場合は `asset/docs/core/is-admin.md`
- 変更場所を判断する場合は `asset/docs/CUSTOMIZATION_MAP.md`

`OP()` はそのまま使ってください。global function の `OP()` はどの namespace からでも呼べるため、`\OP\OP()` と書かず、`use function OP\OP` で import もしないでください。

この rule の背景になった過去のミスと原因は、`asset/docs/agent-mistake/op-function.md` を読んでください。

## モジュールの目的

このモジュールは、ファイルベースのアクセスカウンターです。

top page から次のように呼び出します。

```php
<?php OP()->Template('asset:/module/counter/save.php') ?>
<?php OP()->Template('asset:/module/counter/view.php') ?>
```

`save.php` はカウンターを加算します。
`view.php` はカウンターを表示します。

保存と表示は、必ず別ファイルのままにしてください。

## 保存仕様

データベースは使わないでください。

テキストファイルを次の場所に保存します。

```text
asset/db/counter/<domain>/yyyy/mm/dd.txt
```

domain は `$_SERVER['SERVER_NAME']` の configured server name です。subdomain ごとに別々にカウントする必要があります。

表示時にすべての日別ファイルを走査しなくてよいように、集計済みの total file を維持してください。

```text
asset/db/counter/<domain>/total.txt
asset/db/counter/<domain>/yyyy/total.txt
asset/db/counter/<domain>/yyyy/mm/total.txt
asset/db/counter/<domain>/yyyy/mm/dd.txt
```

書き込みには file lock を使ってください。

## 初期化

初期化チェックは `init.php` にまとめてください。

`save.php` と `view.php` は、カウンターファイルを読み書きする前に必ず `init.php` を呼び出してください。

`init.php` は、カウンターファイルを読み書きする前に、counter initialization logic を呼び出します。
再利用する counter logic は standalone function file ではなく `Counter.class.php` に置いてください。ONEPIECE Framework CI は `OP_CI` を使う class file を検査するためです。
`init.php`、`save.php`、`view.php` は薄い entry file として保ってください。

`Counter.class.php` は、`asset/db/` が存在するか、directory か、現在の PHP process から書き込めるかを確認します。

保存先が準備できていない場合は、`init.phtml` を通して英語の案内を表示してください。可能であれば、具体的な setup command と検出した PHP process の user / group を含めてください。
その guidance rendering と PHP process owner detection は、initialization failure 後にだけ読み込む `CounterInitGuidance.class.php` に置いてください。ほとんど使われない error-handling logic を `Counter.class.php` に置かないでください。ONEPIECE Framework では不要な memory use は禁忌です。

## Config

default module config は、この module の `config.php` に置きます。
user-defined application config は `asset/config/counter.php` に置きます。
任意の local-only override は `asset/config/_counter.php` を使えます。

module 側の `config.php` は template であり、自動では読み込まれません。
counter config を有効化または変更したい user は、それを `asset/config/counter.php` に copy してください。
admin skip behavior は `skip => 'admin'` で制御します。
admin access を skip した場合は、常に `D()` message を出してください。

## カウント条件

標準では全ての request をカウントしてください。

`OP()->Config('counter')['skip'] === 'admin'` の場合だけ、`OP()->isAdmin()` をチェックしてください。

その config value が設定され、かつ `OP()->isAdmin()` が `true` の場合は加算しません。

## 表示仕様

表示する値は次の通りです。

- Today
- Yesterday
- This month
- This year
- Total

表示形式は次の形にしてください。

```text
Key : Number
```

` : ` の separator を中央列として揃えてください。

## ソースコードとテンプレートのルール

ソースコード、コメント、ユーザーに表示される message は英語で書いてください。

`.php` ファイル内に HTML markup を書かないでください。

HTML markup は `.phtml` template file に置き、`OP()->Template()` で表示してください。

`save.php` と `view.php` は controller 的な入口ファイルとして保ってください。

表示テンプレートには `.phtml` を使ってください。

再利用する module behavior を `function.php` に追加しないでください。再利用する behavior は `Counter.class.php` に置いてください。
ONEPIECE Framework の分割 CI layout を使ってください。CI から呼ばれる loader は `ci/Counter.php` で、各 method の CI は `ci/Counter/<Method>.php` に分けます。
CI file を追加・変更する前に `asset/docs/cicd/ci-file-layout.md` を読んでください。

`.phtml` ファイルにも PHPDoc を追加してください。

`.phtml` ファイル内で使う変数には、Eclipse 互換の `/* @var $name type */` 形式で型ヒントを追加してください。PHTML template では PHPStan 形式の array shape は避けてください。

すべての PHP / PHTML file で、PHPDoc は Eclipse 互換にしてください。`array{...}` や `array<string,int>` のような PHPStan / Psalm 固有の array shape や generic array syntax は避けてください。

request value を扱う場合は、適切な箇所で `OP()->Request()` を使ってください。

明確な理由がない限り、raw `$_GET`, `$_POST`, `$_REQUEST`, `$_COOKIE`, `$_SESSION`, `$_SERVER` は避けてください。現在の domain detection は、configured server name information が必要な数少ない例外です。

debug に `var_dump()` や `print_r()` を使わないでください。

## PHP コメント形式

複数行コメントでは、`/**` の直後に tab を置いてください。

正しい例:

```php
/**	Comment
 *
 */
```

誤った例:

```php
/** Comment
 *
 */
```

PHPDoc file header を追加する場合は、次のようにしてください。

```php
* @author    Codex CLI
```

## ドキュメントルール

挙動を変更した場合は、対応する module documentation も更新してください。

可能な限り、同名の documentation を使ってください。

- `docs/config.md` と `docs/config.ja.md`
- `docs/init.md` と `docs/init.ja.md`
- `docs/save.md` と `docs/save.ja.md`
- `docs/view.md` と `docs/view.ja.md`

英語ドキュメントが canonical です。ユーザーが英語ドキュメントの正確性を確認できるように、日本語訳も必要です。

## 検証

PHP を変更した場合は、変更した PHP / PHTML file の syntax check を実行してください。

counter behavior について、少なくとも次を確認してください。

- normal access で加算される
- counter config が `skip => 'admin'` を設定していない場合は admin access でも加算される
- counter config が `skip => 'admin'` を設定している場合は admin access では加算されない
- 未初期化の `asset/db/` では setup guidance が表示される
- 初期化済み storage では counter values が表示される
- PHP file に表示 HTML が含まれていない
- PHP / PHTML の user-visible text が英語のままである

可能な場合は、次を実行してください。

```sh
./cicd cd=0
```

project は既存の submodule warning を出すことがありますが、exit code を確認してください。
