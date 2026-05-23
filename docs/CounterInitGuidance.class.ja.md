# CounterInitGuidance.class.php

`CounterInitGuidance.class.php` は、counter module の initialization failure guidance を所有します。

この class は意図的に `Counter.class.php` から分離しています。
通常の counter request では、`asset/db/` が存在しない、directory ではない、または writable ではない場合だけ使う recovery logic を読み込むべきではありません。

この class は `OP\MODULE\COUNTER` namespace を使います。
counter の main class は `OP\MODULE\Counter` として展開されますが、helper class は他の MODULE class と衝突しないように module subnamespace に置きます。

ONEPIECE Framework では不要な memory use は禁忌です。
そのため、`Counter::Init()` は initialization issue が見つかった後にだけ、この class を読み込みます。

この class は次を扱います。

- `init.phtml` の rendering
- 現在の PHP process user / group の検出
- POSIX process function が使えない場合の `id -un` / `id -gn` fallback
- site operator に表示する具体的な `chown` command の組み立て
