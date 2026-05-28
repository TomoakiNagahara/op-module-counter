# Counter.class.php

`Counter.class.php` は、テキストファイル形式アクセスカウンターの再利用可能な logic を所有します。

entry file は分離したままです。

- `save.php` は counter を加算します。
- `view.php` は counter values を表示します。

再利用する behavior は `function.php` ではなく、この class に置きます。ONEPIECE Framework CI は `OP_CI` を使う module class file を検査するためです。

この class は次を扱います。

- admin counting rules
- `OP()->Config('counter')` を通した counter config access
- daily, monthly, yearly, total の counter path
- counter write の file locking
- display values の file read

shared path と domain helper は `Common.class.php` から取得します。

initialization logic は、この class には置きません。
`init.php` は `Init.class.php` を呼び出し、`Init.class.php` が initialization 失敗後にだけ `InitGuidance.class.php` を読み込みます。
これにより、counter runtime behavior と initialization recovery behavior を分離します。

`ci/Counter.php` は対応する CI loader です。
各 deterministic method の CI configuration は `ci/Counter/` の下に method ごとの file として置きます。
`CI_AllMethods()` は、`IsOne()` のような deterministic helper method だけを意図的に列挙します。
`ShouldCount()`、`Increment()`、`Counts()` のような runtime method は request state、config、debug output、date、file storage に依存するため、class-level deterministic CI target には含めません。

framework-wide な CI file layout rule は `asset/docs/cicd/ci-file-layout.md` を参照してください。
