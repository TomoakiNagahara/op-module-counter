<?php
/**	op-module-counter:/ci/CounterInitGuidance/BuildChownCommand.php
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

$args   = [[
	'user'  => 'ci-user',
	'group' => 'ci-group',
]];
$result = 'chown -R ci-user:ci-group asset/db';
$ci->Set($method, $result, $args);

$args   = [[
	'user'  => null,
	'group' => 'ci-group',
]];
$result = 'chown -R <php-user>:<php-group> asset/db';
$ci->Set($method, $result, $args);
