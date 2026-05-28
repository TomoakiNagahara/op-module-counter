<?php
/**	op-module-counter:/ci/InitGuidance/Label.php
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

$args   = ['ci-user', 'uid', 1000];
$result = 'ci-user (uid: 1000)';
$ci->Set($method, $result, $args);

$args   = ['ci-user', 'uid', null];
$result = 'ci-user';
$ci->Set($method, $result, $args);

$args   = [null, 'uid', 1000];
$result = 'unknown';
$ci->Set($method, $result, $args);
