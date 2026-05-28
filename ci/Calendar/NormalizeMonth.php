<?php
/**	op-module-counter:/ci/Calendar/NormalizeMonth.php
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

//	...
$method = basename(__FILE__);
$method = explode('.', $method)[0];

/* @var $ci \OP\UNIT\CI\CI_Config */

//	...
$args   = [5, 1];
$result = 5;
$ci->Set($method, $result, $args);

//	...
$args   = [null, 1];
$result = 1;
$ci->Set($method, $result, $args);

//	...
$args   = [0, 1];
$result = 1;
$ci->Set($method, $result, $args);

//	...
$args   = [13, 1];
$result = 1;
$ci->Set($method, $result, $args);
