<?php
/**	op-module-counter:/calendar.php
 *
 * @created   2026-05-28
 * @license   Apache-2.0
 * @package   op-module-counter
 * @author    Codex CLI
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

$year  = OP()->Request('counter_year');
$month = OP()->Request('counter_month');

require_once __DIR__ . '/Calendar.class.php';

$calendar = (new MODULE\COUNTER\Calendar())->Calendar(
	$year  === null ? null : (int)$year,
	$month === null ? null : (int)$month
);

OP()->Template('calendar.phtml', [
	'calendar' => $calendar,
]);
