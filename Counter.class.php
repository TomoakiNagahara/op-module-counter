<?php
/**	op-module-counter:/Counter.class.php
 *
 * @created   2026-05-21
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
namespace OP\MODULE;

/**	Counter
 *
 * @created   2026-05-21
 * @license   Apache-2.0
 * @package   op-module-counter
 * @author    Codex CLI
 * @copyright (C) 2026 Tomoaki Nagahara
 */
class Counter
{
	use \OP\OP_CORE, \OP\OP_CI;

	/**	Return the deterministic methods inspected by module CI.
	 *
	 * @return array
	 */
	function CI_AllMethods() : array
	{
		return [
			'IsOne',
			'NormalizeDomain',
		];
	}

	/**	Check whether the counter can use asset/db/.
	 *
	 * @return bool
	 */
	function Init() : bool
	{
		$issues = $this->InitIssues();

		if( $issues ){
			$this->DisplayInitGuidance($issues);
			return false;
		}

		return true;
	}

	/**	Return whether the current request should increment the counter.
	 *
	 * @return bool
	 */
	function ShouldCount() : bool
	{
		if(!OP()->isAdmin() ){
			return true;
		}

		return $this->IsOne(OP()->Request('admin'));
	}

	/**	Increment today's counter files.
	 *
	 * @return array
	 */
	function Increment() : array
	{
		$counts = [];

		foreach( $this->Paths(new \DateTimeImmutable('today')) as $key => $path ){
			$counts[$key] = $this->IncrementFile($path);
		}

		return $counts;
	}

	/**	Read display counters.
	 *
	 * @return array
	 */
	function Counts() : array
	{
		$today     = new \DateTimeImmutable('today');
		$yesterday = $today->modify('-1 day');

		$today_paths     = $this->Paths($today);
		$yesterday_paths = $this->Paths($yesterday);

		return [
			'today'     => $this->ReadFile($today_paths['day']),
			'yesterday' => $this->ReadFile($yesterday_paths['day']),
			'month'     => $this->ReadFile($today_paths['month']),
			'year'      => $this->ReadFile($today_paths['year']),
			'total'     => $this->ReadFile($today_paths['total']),
		];
	}

	/**	Return whether a request value is one.
	 *
	 * @param  mixed $value
	 * @return bool
	 */
	function IsOne(mixed $value) : bool
	{
		if( \is_int($value) or \is_float($value) ){
			return $value == 1;
		}

		if( \is_string($value) ){
			return \trim($value) === '1';
		}

		return false;
	}

	/**	Normalize a host name into a storage-safe domain key.
	 *
	 * @param  string|null $host
	 * @return string
	 */
	function NormalizeDomain(?string $host) : string
	{
		$host = $host ?: 'localhost';
		$host = trim(explode(',', $host)[0]);
		$host = preg_replace('/:\d+$/', '', $host);
		$host = strtolower($host);

		$domain = preg_replace('/[^a-z0-9._-]/', '_', $host);
		$domain = trim($domain, '._-');

		return $domain ?: 'unknown-host';
	}

	/**	Return the current counter target domain.
	 *
	 * @return string
	 */
	private function Domain() : string
	{
		$host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';

		return $this->NormalizeDomain($host);
	}

	/**	Return the counter storage root.
	 *
	 * @return string
	 */
	private function StorageRoot() : string
	{
		return $this->DbRoot() . 'counter/' . $this->Domain() . '/';
	}

	/**	Return the database storage root.
	 *
	 * @return string
	 */
	private function DbRoot() : string
	{
		return _ROOT_ASSET_ . 'db/';
	}

	/**	Return counter file paths for a date.
	 *
	 * @param  \DateTimeImmutable $date
	 * @return array
	 */
	private function Paths(\DateTimeImmutable $date) : array
	{
		$root  = $this->StorageRoot();
		$year  = $date->format('Y');
		$month = $date->format('m');
		$day   = $date->format('d');

		return [
			'total' => $root . 'total.txt',
			'year'  => $root . $year . '/total.txt',
			'month' => $root . $year . '/' . $month . '/total.txt',
			'day'   => $root . $year . '/' . $month . '/' . $day . '.txt',
		];
	}

	/**	Return initialization issues.
	 *
	 * @return array
	 */
	private function InitIssues() : array
	{
		$issues = [];
		$db_root = $this->DbRoot();

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
	private function DisplayInitGuidance(array $issues) : void
	{
		$process = $this->PhpProcessOwner();
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
	private function PhpProcessOwner() : array
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

	/**	Return a shell command result if shell execution is available.
	 *
	 * @param  string $command
	 * @return string|null
	 */
	private function ShellCommand(string $command) : ?string
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
	private function Label(?string $name, string $id_name, ?int $id) : string
	{
		if(!$name ){
			return 'unknown';
		}

		return $id === null ? $name : "{$name} ({$id_name}: {$id})";
	}

	/**	Increment one counter file with an exclusive lock.
	 *
	 * @param  string $path
	 * @return int
	 */
	private function IncrementFile(string $path) : int
	{
		$directory = dirname($path);

		if(!is_dir($directory) ){
			mkdir($directory, 0775, true);
		}

		$file = fopen($path, 'c+');
		if(!$file ){
			throw new \RuntimeException("Failed to open counter file: {$path}");
		}

		try {
			if(!flock($file, LOCK_EX) ){
				throw new \RuntimeException("Failed to lock counter file: {$path}");
			}

			rewind($file);
			$count = (int)trim(stream_get_contents($file));
			$count++;

			rewind($file);
			ftruncate($file, 0);
			fwrite($file, (string)$count . "\n");
			fflush($file);
			flock($file, LOCK_UN);
		} finally {
			fclose($file);
		}

		return $count;
	}

	/**	Read one counter file with a shared lock.
	 *
	 * @param  string $path
	 * @return int
	 */
	private function ReadFile(string $path) : int
	{
		if(!is_file($path) ){
			return 0;
		}

		$file = fopen($path, 'r');
		if(!$file ){
			return 0;
		}

		try {
			if( flock($file, LOCK_SH) ){
				$count = (int)trim(stream_get_contents($file));
				flock($file, LOCK_UN);
			}else{
				$count = 0;
			}
		} finally {
			fclose($file);
		}

		return $count;
	}
}
