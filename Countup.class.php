<?php
/**	op-module-counter:/Countup.class.php
 *
 * @created   2026-05-28
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

/**	include
 *
 */
require_once __DIR__ . '/Common.class.php';

/**	Countup
 *
 * This class owns counter increment behavior.
 *
 * @created   2026-05-28
 * @license   Apache-2.0
 * @package   op-module-counter
 * @author    Codex CLI
 * @copyright (C) 2026 Tomoaki Nagahara
 */
class Countup
{
	use \OP\OP_CI;

	/**	Return the deterministic methods inspected by module CI.
	 *
	 * @return array
	 */
	function CI_AllMethods() : array
	{
		return [];
	} // CI_AllMethods

	/**	Increment today's counter files.
	 *
	 * @return bool
	 */
	function Increment() : bool
	{
		if( OP()->Session()->Get( $this->SessionKey() ) ){
			//	Already counted.
			return true;
		}

		if( OP()->isAdmin() ){
			if( $this->Common()->IsAdminSkipEnabled() ){
				return true;
			}
		}

		//	Why foreach?
		foreach( $this->Common()->Paths(new \DateTimeImmutable('today')) as $path ){
			$this->IncrementFile($path);
		}

		//	Set count marker.
		OP()->Session()->Set($this->SessionKey(), true);

		return true;
	} // Increment

	/**	Return the session key for the current counter domain.
	 *
	 * @return string
	 */
	private function SessionKey() : string
	{
		return 'counter_counted_' . $this->Common()->Domain();
	} // SessionKey

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
	} // IncrementFile

	/**	Return shared counter helpers.
	 *
	 * @return Common
	 */
	private function Common() : Common
	{
		static $common;

		if(!$common ){
			$common = new Common();
		}

		return $common;
	} // Common
}
