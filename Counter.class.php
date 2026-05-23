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
	 * Runtime methods touch request state, config, files, or debug output.
	 * CI_AllMethods() keeps class-based CI focused on deterministic helpers
	 * that can be verified without mutating counter storage.
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
			require_once(__DIR__ . '/CounterInitGuidance.class.php');
			(new COUNTER\CounterInitGuidance())->DisplayInitGuidance($issues);
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
		if(!$this->IsAdminSkipEnabled() ){
			return true;
		}

		if(!OP()->isAdmin() ){
			return true;
		}

		D('Access counter skipped increment because counter config skips admin access.');

		return false;
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

	/**	Return whether admin access should be skipped.
	 *
	 * @return bool
	 */
	private function IsAdminSkipEnabled() : bool
	{
		if(!$this->HasConfigFile() ){
			return false;
		}

		return ($this->Config()['skip'] ?? null) === 'admin';
	}

	/**	Return counter module config.
	 *
	 * @return array
	 */
	private function Config() : array
	{
		return OP()->Config('counter');
	}

	/**	Return whether counter application config exists.
	 *
	 * @return bool
	 */
	private function HasConfigFile() : bool
	{
		return is_file(OP()->Path('asset:/config/counter.php')) or is_file(OP()->Path('asset:/config/_counter.php'));
	}

	/**	Return the current counter target domain.
	 *
	 * @return string
	 */
	private function Domain() : string
	{
		$host = $_SERVER['SERVER_NAME'] ?? 'localhost';

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
		return OP()->Path('asset:/db/');
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
