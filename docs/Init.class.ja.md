# Init.class.php

`Init.class.php` は、counter の initialization check を所有します。

`init.php` は entry file に限定します。
`Init.class.php` を読み込み、`OP\MODULE\COUNTER\Init` を作成して `Init()` を呼び出します。

この class は `asset/db/` について次を確認します。

- 存在するか
- directory か
- 現在の PHP process から writable か

`asset/db/` path は `Common.class.php` から取得します。

initialization に失敗した場合、`Init` は `InitGuidance.class.php` を on demand で読み込み、recovery guidance rendering を委譲します。
これにより entry file を薄く保ち、initialization が実際に失敗するまで recovery guidance を memory に展開しません。

`Init.class.php` は visible な module class file なので、class-based CI に対応している必要があります。
deterministic な decision method は `ci/Init.php` と `ci/Init/DbRootIssues.php` で検査します。
