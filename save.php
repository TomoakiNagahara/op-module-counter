<?php
/** op-module-counter:/save.php
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

return MODULE\COUNTER\Increment();
