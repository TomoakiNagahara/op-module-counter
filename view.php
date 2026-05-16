<?php
/** op-module-counter:/view.php
 *
 * @created   2026-05-16
 * @license   Apache-2.0
 * @package   op-module-counter
 * @copyright (C) 2026 Tomoaki Nagahara
 */

/** declare
 *
 */
declare(strict_types=1);

/** namespace
 *
 */
namespace OP;

require_once __DIR__ . '/function.php';

$counts = MODULE\COUNTER\Counts();
?>
<dl class="op-module-counter">
	<dt>今日</dt>
	<dd><?= number_format($counts['today']) ?></dd>
	<dt>昨日</dt>
	<dd><?= number_format($counts['yesterday']) ?></dd>
	<dt>今月</dt>
	<dd><?= number_format($counts['month']) ?></dd>
	<dt>今年</dt>
	<dd><?= number_format($counts['year']) ?></dd>
	<dt>トータル</dt>
	<dd><?= number_format($counts['total']) ?></dd>
</dl>
