<?php
/**	op-module-counter:/ci/Init/DbRootIssues.php
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

$method = basename(__FILE__);
$method = explode('.', $method)[0];

/* @var $ci \OP\UNIT\CI\CI_Config */

$args   = [false, false, false];
$result = ['`asset/db/` does not exist.'];
$ci->Set($method, $result, $args);

$args   = [true, false, false];
$result = ['`asset/db/` exists but is not a directory.'];
$ci->Set($method, $result, $args);

$args   = [true, true, false];
$result = ['`asset/db/` is not writable by the current PHP process.'];
$ci->Set($method, $result, $args);

$args   = [true, true, true];
$result = [];
$ci->Set($method, $result, $args);
