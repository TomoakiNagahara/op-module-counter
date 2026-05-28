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

/**	include
 *
 */
require_once __DIR__ . '/Common.class.php';

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
		];
	} // CI_AllMethods

	/**	Read display counters.
	 *
	 * @return array
	 */
	function Counts() : array
	{
		$today     = new \DateTimeImmutable('today');
		$yesterday = $today->modify('-1 day');

		$today_paths     = $this->Common()->Paths($today);
		$yesterday_paths = $this->Common()->Paths($yesterday);

		return [
			'today'     => $this->ReadFile($today_paths['day']),
			'yesterday' => $this->ReadFile($yesterday_paths['day']),
			'month'     => $this->ReadFile($today_paths['month']),
			'year'      => $this->ReadFile($today_paths['year']),
			'total'     => $this->ReadFile($today_paths['total']),
		];
	} // Counts

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
	} // IsOne

	/**	Return shared counter helpers.
	 *
	 * @return COUNTER\Common
	 */
	private function Common() : COUNTER\Common
	{
		static $common;

		if(!$common ){
			$common = new COUNTER\Common();
		}

		return $common;
	} // Common

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
	} // ReadFile
}
