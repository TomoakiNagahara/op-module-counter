<?php
/**	op-module-counter:/view.php
 *
 * @created   2026-05-16
 * @license   Apache-2.0
 * @package   op-module-counter
 * @copyright (C) 2026 Tomoaki Nagahara
 */

/**	declare
 *
 */
declare(strict_types=1);

/**	namespace
 *
 */
namespace OP;

$initialized = require __DIR__ . '/init.php';

if(!$initialized ){
	return false;
}

$counter = new MODULE\Counter();
$counts  = $counter->Counts();

OP()->Template('view.phtml', [
	'counts' => $counts,
]);
