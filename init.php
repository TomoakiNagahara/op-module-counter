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
namespace OP\MODULE\COUNTER;

/**	include
 *
 */
require_once __DIR__ . '/function.php';

/**	Check whether the counter can use asset/db/.
 *
 * @return bool
 */
function Init() : bool
{
	$issues = InitIssues();

	if( $issues ){
		DisplayInitGuidance($issues);
		return false;
	}

	return true;
}

/**	Return initialization issues.
 *
 * @return array
 */
function InitIssues() : array
{
	$issues = [];
	$db_root = DbRoot();

	if(!file_exists($db_root) ){
		$issues[] = '`asset/db/` does not exist.';
		return $issues;
	}

	if(!is_dir($db_root) ){
		$issues[] = '`asset/db/` exists but is not a directory.';
		return $issues;
	}

	if(!is_writable($db_root) ){
		$issues[] = '`asset/db/` is not writable by the current PHP process.';
	}

	return $issues;
}

/**	Display recovery guidance for asset/db/.
 *
 * @param  array $issues
 * @return void
 */
function DisplayInitGuidance(array $issues) : void
{
	$process = PhpProcessOwner();
	$user    = $process['user'];
	$group   = $process['group'];
	$owner   = ($user and $group) ? "{$user}:{$group}" : null;
	$chown   = $owner ? "chown -R {$owner} asset/db" : 'chown -R <php-user>:<php-group> asset/db';

	OP()->Template('init.phtml', [
		'issues'  => $issues,
		'process' => $process,
		'chown'   => $chown,
	]);
}

/**	Return the current PHP process owner.
 *
 * @return array
 */
function PhpProcessOwner() : array
{
	$uid = \function_exists('posix_geteuid') ? \posix_geteuid() : null;
	$gid = \function_exists('posix_getegid') ? \posix_getegid() : null;

	$user  = null;
	$group = null;

	if( $uid !== null and \function_exists('posix_getpwuid') ){
		$info = \posix_getpwuid($uid);
		$user = $info['name'] ?? null;
	}

	if( $gid !== null and \function_exists('posix_getgrgid') ){
		$info = \posix_getgrgid($gid);
		$group = $info['name'] ?? null;
	}

	if(!$user ){
		$user = ShellCommand('id -un');
	}

	if(!$group ){
		$group = ShellCommand('id -gn');
	}

	return [
		'user'        => $user,
		'group'       => $group,
		'user_label'  => Label($user,  'uid', $uid),
		'group_label' => Label($group, 'gid', $gid),
	];
}

/**	Return a shell command result if shell execution is available.
 *
 * @param  string $command
 * @return string|null
 */
function ShellCommand(string $command) : ?string
{
	if(!\function_exists('shell_exec') ){
		return null;
	}

	$disabled = \ini_get('disable_functions') ?: '';
	if( \in_array('shell_exec', \array_map('trim', \explode(',', $disabled)), true) ){
		return null;
	}

	$result = \shell_exec($command . ' 2>/dev/null');
	$result = \is_string($result) ? \trim($result) : '';

	return $result !== '' ? $result : null;
}

/**	Return a user or group display label.
 *
 * @param  string|null $name
 * @param  string      $id_name
 * @param  int|null    $id
 * @return string
 */
function Label(?string $name, string $id_name, ?int $id) : string
{
	if(!$name ){
		return 'unknown';
	}

	return $id === null ? $name : "{$name} ({$id_name}: {$id})";
}
