# Calendar.class.php

`Calendar.class.php` は、counter module の optional な calendar-display logic を所有します。

この class は、`countup.php`、`view.php`、`Counter.class.php` からは読み込まれません。
`calendar.php` が、calendar display を要求された時だけ読み込みます。

これは ONEPIECE Framework の memory guideline に従うためです。end user がまったく使わない可能性がある optional feature は、通常の成功 request で code を memory に展開しません。

この file は、ONEPIECE Framework CI が検査できるように、module root の visible な `*.class.php` class file として置きます。
runtime memory saving は CI collector から隠すことではなく、通常の `countup.php` と `view.php` request で require しないことで実現します。

`Calendar` は、選択された月の日別 `dd.txt` file を読み、`calendar.phtml` が render する array を作ります。
counter の加算は行いません。
shared storage path と domain helper は `Common.class.php` から取得します。

この class は、他 module の helper class と衝突しないように `OP\MODULE\COUNTER` subnamespace に属します。
