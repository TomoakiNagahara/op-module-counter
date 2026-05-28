<?php
/**	op-module-counter:/ci/InitGuidance/PhpProcessOwner.php
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

$args   = null;
$result = [
	'user'        => 'ci-user',
	'group'       => 'ci-group',
	'user_label'  => 'ci-user (uid: 1000)',
	'group_label' => 'ci-group (gid: 1000)',
];
$ci->Set($method, $result, $args);
