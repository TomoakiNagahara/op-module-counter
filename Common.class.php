<?php
/**	op-module-counter:/Common.class.php
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

/**	Common
 *
 * Shared counter module helpers.
 *
 * @created   2026-05-28
 * @license   Apache-2.0
 * @package   op-module-counter
 * @author    Codex CLI
 * @copyright (C) 2026 Tomoaki Nagahara
 */
class Common
{
	use \OP\OP_CI;

	/**	Return the deterministic methods inspected by module CI.
	 *
	 * @return array
	 */
	function CI_AllMethods() : array
	{
		return [
			'NormalizeDomain',
		];
	} // CI_AllMethods

	/**	Return the database storage root.
	 *
	 * @return string
	 */
	function DbRoot() : string
	{
		return OP()->Path('asset:/db/');
	} // DbRoot

	/**	Return the counter storage root.
	 *
	 * @return string
	 */
	function StorageRoot() : string
	{
		return $this->DbRoot() . 'counter/' . $this->Domain() . '/';
	} // StorageRoot

	/**	Return counter file paths for a date.
	 *
	 * @param  \DateTimeImmutable $date
	 * @return array
	 */
	function Paths(\DateTimeImmutable $date) : array
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
	} // Paths

	/**	Return the current counter target domain.
	 *
	 * @return string
	 */
	function Domain() : string
	{
		$host = $_SERVER['SERVER_NAME'] ?? 'localhost';

		return $this->NormalizeDomain($host);
	} // Domain

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
	} // NormalizeDomain

	/**	Return whether admin access should be skipped.
	 *
	 * @return bool
	 */
	function IsAdminSkipEnabled() : bool
	{
		if(!$this->HasConfigFile() ){
			return false;
		}

		return ($this->Config()['skip'] ?? null) === 'admin';
	} // IsAdminSkipEnabled

	/**	Return whether counter application config exists.
	 *
	 * @return bool
	 */
	function HasConfigFile() : bool
	{
		return is_file(OP()->Path('asset:/config/counter.php')) or is_file(OP()->Path('asset:/config/_counter.php'));
	} // HasConfigFile

	/**	Return counter module config.
	 *
	 * @return array
	 */
	private function Config() : array
	{
		return OP()->Config('counter');
	} // Config
}
