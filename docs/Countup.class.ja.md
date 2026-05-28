# Countup.class.php

`Countup.class.php` は、counter increment behavior を所有します。

`countup.php` は、`init.php` が成功した後にこの class を読み込みます。
countup request では `Counter.class.php` を読み込みません。

この class は次を扱います。

- current request を count するかどうかの判定
- 同一 session 内ですでに count 済みの access の skip
- daily、monthly、yearly、total counter file の加算
- 成功した countup の framework session への記録
- `flock()` を使った counter file write

admin skip config と counter path は `Common.class.php` から取得します。
session state は `OP()->Session()` で保存し、module は raw `$_SESSION` を使いません。

`Countup.class.php` は visible な module class file なので、`OP_CI` を使う必要があります。
runtime behavior は session state、request admin state、config、date、file storage に依存するため、現時点では deterministic な method-level CI target はありません。
