<?php
/**	op-module-counter:/ci/CounterInitGuidance/ShellCommand.php
 *
 * @created   2026-05-26
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

$args   = ['id -un'];
$result = 'ci-shell-user';
$ci->Set($method, $result, $args);

$args   = ['id -gn'];
$result = 'ci-shell-group';
$ci->Set($method, $result, $args);

$args   = ['unknown'];
$result = null;
$ci->Set($method, $result, $args);
