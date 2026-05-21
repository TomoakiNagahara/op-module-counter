<?php
/**	op-module-counter:/ci/Counter/IsOne.php
 *
 * @created   2026-05-21
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
$args   = [1];
$result = true;
$ci->Set($method, $result, $args);

//	...
$args   = ['1'];
$result = true;
$ci->Set($method, $result, $args);

//	...
$args   = ['true'];
$result = false;
$ci->Set($method, $result, $args);

//	...
$args   = [0];
$result = false;
$ci->Set($method, $result, $args);

//	...
$args   = [null];
$result = false;
$ci->Set($method, $result, $args);
