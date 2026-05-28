<?php
/**	op-module-counter:/Init.class.php
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

/**	Init
 *
 * This class owns counter initialization checks.
 * The entry file init.php only loads and calls this class.
 *
 * @created   2026-05-28
 * @license   Apache-2.0
 * @package   op-module-counter
 * @author    Codex CLI
 * @copyright (C) 2026 Tomoaki Nagahara
 */
class Init
{
	use \OP\OP_CI;

	/**	Return the deterministic methods inspected by module CI.
	 *
	 * @return array
	 */
	function CI_AllMethods() : array
	{
		return [
			'DbRootIssues',
		];
	}

	/**	Check whether the counter can use asset/db/.
	 *
	 * @return bool
	 */
	function Init() : bool
	{
		$issues = $this->Issues();

		if( $issues ){
			require_once(__DIR__ . '/InitGuidance.class.php');
			(new InitGuidance())->DisplayInitGuidance($issues);
			return false;
		}

		return true;
	}

	/**	Return initialization issues.
	 *
	 * @return array
	 */
	private function Issues() : array
	{
		$db_root = $this->Common()->DbRoot();

		return $this->DbRootIssues(
			\file_exists($db_root),
			\is_dir($db_root),
			\is_writable($db_root)
		);
	}

	/**	Return database root issues.
	 *
	 * @param  bool $exists
	 * @param  bool $is_dir
	 * @param  bool $is_writable
	 * @return array
	 */
	private function DbRootIssues(bool $exists, bool $is_dir, bool $is_writable) : array
	{
		if(!$exists ){
			return ['`asset/db/` does not exist.'];
		}

		if(!$is_dir ){
			return ['`asset/db/` exists but is not a directory.'];
		}

		if(!$is_writable ){
			return ['`asset/db/` is not writable by the current PHP process.'];
		}

		return [];
	}

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
