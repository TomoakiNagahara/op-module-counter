# Counter.class.php

`Counter.class.php` は、テキストファイル形式アクセスカウンターの再利用可能な logic を所有します。

entry file は分離したままです。

- `init.php` は initialization logic を呼び出します。
- `save.php` は counter を加算します。
- `view.php` は counter values を表示します。

再利用する behavior は `function.php` ではなく、この class に置きます。ONEPIECE Framework CI は `OP_CI` を使う module class file を検査するためです。

この class は次を扱います。

- `asset/db/` の initialization check
- admin counting rules
- domain normalization
- daily, monthly, yearly, total の counter path
- counter write の file locking
- display values の file read

`ci/Counter.php` は対応する CI loader です。
各 deterministic method の CI configuration は `ci/Counter/` の下に method ごとの file として置きます。

framework-wide な CI file layout rule は `asset/docs/cicd/ci-file-layout.md` を参照してください。
