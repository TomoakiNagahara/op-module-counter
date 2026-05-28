<?php
/**	op-module-counter:/ci/InitGuidance/GetGroupNameByGid.php
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

$args   = [1000];
$result = 'ci-group';
$ci->Set($method, $result, $args);

$args   = [1001];
$result = null;
$ci->Set($method, $result, $args);
