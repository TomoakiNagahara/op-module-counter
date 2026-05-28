<?php
/**	op-module-counter:/init.php
 *
 * @created   2026-05-16
 * @license   Apache-2.0
 * @package   op-module-counter
 * @author    Codex CLI
 * @copyright Tomoaki Nagahara
 */

/**	declare
 *
 */
declare(strict_types=1);

/**	namespace
 *
 */
namespace OP;

/**	include
 *
 */
require_once __DIR__ . '/Init.class.php';

return (new MODULE\COUNTER\Init())->Init();
