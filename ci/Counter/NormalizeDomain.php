<?php
/**	op-module-counter:/ci/Counter/NormalizeDomain.php
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
$args   = ['Example.COM:443'];
$result = 'example.com';
$ci->Set($method, $result, $args);

//	...
$args   = ['sub.example.com, proxy.local'];
$result = 'sub.example.com';
$ci->Set($method, $result, $args);

//	...
$args   = ['../'];
$result = 'unknown-host';
$ci->Set($method, $result, $args);
