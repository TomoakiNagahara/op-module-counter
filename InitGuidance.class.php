<?php
/**	op-module-counter:/InitGuidance.class.php
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
namespace OP\MODULE\COUNTER;

/**	InitGuidance
 *
 * This class is loaded only when counter initialization fails.
 * Keeping recovery guidance outside Counter avoids loading rarely used
 * error-handling code during normal counter requests.
 *
 * @created   2026-05-26
 * @license   Apache-2.0
 * @package   op-module-counter
 * @author    Codex CLI
 * @copyright (C) 2026 Tomoaki Nagahara
 */
class InitGuidance
{
	use \OP\OP_CI;

	/**	Return the deterministic methods inspected by module CI.
	 *
	 * @return array
	 */
	function CI_AllMethods() : array
	{
		return [
			'BuildChownCommand',
			'GetGroupNameByGid',
			'GetPosixGid',
			'GetPosixUid',
			'GetUserNameByUid',
			'Label',
			'PhpProcessOwner',
			'ShellCommand',
		];
	}

	/**	Display recovery guidance for asset/db/.
	 *
	 * @param  array $issues
	 * @return void
	 */
	function DisplayInitGuidance(array $issues) : void
	{
		$process = $this->PhpProcessOwner();
		$chown   = $this->BuildChownCommand($process);

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
	private function PhpProcessOwner() : array
	{
		$uid = $this->GetPosixUid();
		$gid = $this->GetPosixGid();

		$user  = $this->GetUserNameByUid($uid);
		$group = $this->GetGroupNameByGid($gid);

		if(!$user ){
			$user = $this->ShellCommand('id -un');
		}

		if(!$group ){
			$group = $this->ShellCommand('id -gn');
		}

		return [
			'user'        => $user,
			'group'       => $group,
			'user_label'  => $this->Label($user,  'uid', $uid),
			'group_label' => $this->Label($group, 'gid', $gid),
		];
	}

	/**	Return the effective POSIX uid.
	 *
	 * @return int|null
	 */
	private function GetPosixUid() : ?int
	{
		if( OP()->isCI() ){
			return 1000;
		}

		return \function_exists('posix_geteuid') ? \posix_geteuid() : null;
	}

	/**	Return the effective POSIX gid.
	 *
	 * @return int|null
	 */
	private function GetPosixGid() : ?int
	{
		if( OP()->isCI() ){
			return 1000;
		}

		return \function_exists('posix_getegid') ? \posix_getegid() : null;
	}

	/**	Return a user name for a uid.
	 *
	 * @param  int|null $uid
	 * @return string|null
	 */
	private function GetUserNameByUid(?int $uid) : ?string
	{
		if( OP()->isCI() ){
			return $uid === 1000 ? 'ci-user' : null;
		}

		if( $uid === null or !\function_exists('posix_getpwuid') ){
			return null;
		}

		$info = \posix_getpwuid($uid);

		return $info['name'] ?? null;
	}

	/**	Return a group name for a gid.
	 *
	 * @param  int|null $gid
	 * @return string|null
	 */
	private function GetGroupNameByGid(?int $gid) : ?string
	{
		if( OP()->isCI() ){
			return $gid === 1000 ? 'ci-group' : null;
		}

		if( $gid === null or !\function_exists('posix_getgrgid') ){
			return null;
		}

		$info = \posix_getgrgid($gid);

		return $info['name'] ?? null;
	}

	/**	Return a shell command result if shell execution is available.
	 *
	 * @param  string $command
	 * @return string|null
	 */
	private function ShellCommand(string $command) : ?string
	{
		if( OP()->isCI() ){
			return match($command){
				'id -un' => 'ci-shell-user',
				'id -gn' => 'ci-shell-group',
				default  => null,
			};
		}

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

	/**	Build the chown command shown to the site operator.
	 *
	 * @param  array $process
	 * @return string
	 */
	private function BuildChownCommand(array $process) : string
	{
		$user  = $process['user']  ?? null;
		$group = $process['group'] ?? null;
		$owner = ($user and $group) ? "{$user}:{$group}" : null;

		return $owner ? "chown -R {$owner} asset/db" : 'chown -R <php-user>:<php-group> asset/db';
	}

	/**	Return a user or group display label.
	 *
	 * @param  string|null $name
	 * @param  string      $id_name
	 * @param  int|null    $id
	 * @return string
	 */
	private function Label(?string $name, string $id_name, ?int $id) : string
	{
		if(!$name ){
			return 'unknown';
		}

		return $id === null ? $name : "{$name} ({$id_name}: {$id})";
	}
}
