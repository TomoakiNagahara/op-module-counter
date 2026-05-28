# init.php

`init.php` は、アクセスカウンターが `asset/db/` を使える状態かどうかを確認します。

`countup.php` と `view.php` は、カウンターファイルを読み書きする前に `init.php` を呼び出します。
案内を表示する必要がある場合、`Init.class.php` が `InitGuidance.class.php` に rendering を委譲します。

`init.php` は意図的に薄い entry file です。
再利用する initialization logic は `Init.class.php` が所有します。これにより、module behavior が ONEPIECE Framework の class-based CI flow の対象になります。

確認する内容は次の通りです。

- `asset/db/` が存在するか。
- `asset/db/` がディレクトリか。
- 現在の PHP プロセスから `asset/db/` に書き込めるか。

確認に失敗した場合は、サイト管理者向けの設定手順を表示して `false` を返します。
初期化に失敗した状態では、カウンターは保存先の読み書きを行いません。

guidance renderer は `InitGuidance.class.php` に分離します。
`Init.class.php` は、initialization issue が見つかった後にだけ、その class を読み込みます。
ONEPIECE Framework では不要な memory use は禁忌であるため、通常の成功 request では、ほとんど使われない recovery logic を memory に展開しません。

可能な環境では、設定手順に PHP プロセスの実行ユーザーとグループも表示します。
まず POSIX のプロセス情報を確認し、使えない場合は shell 実行が可能であれば `id -un` / `id -gn` で確認します。
表示する `chown` コマンドには検出したユーザーとグループを入れ、管理者が具体的な所有者変更を実行できるようにします。
