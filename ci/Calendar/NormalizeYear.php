<?php
/**	op-module-counter:/ci/Calendar/NormalizeYear.php
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
$args   = [2026, 2000];
$result = 2026;
$ci->Set($method, $result, $args);

//	...
$args   = [null, 2000];
$result = 2000;
$ci->Set($method, $result, $args);

//	...
$args   = [1969, 2000];
$result = 2000;
$ci->Set($method, $result, $args);

//	...
$args   = [10000, 2000];
$result = 2000;
$ci->Set($method, $result, $args);
