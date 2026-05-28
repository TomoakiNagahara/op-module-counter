<?php
/**	op-module-counter:/Calendar.class.php
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

/**	Calendar
 *
 * Optional calendar-display logic is isolated from the main Counter class so
 * normal save/view requests do not load code that many sites may never use.
 *
 * @created   2026-05-28
 * @license   Apache-2.0
 * @package   op-module-counter
 * @author    Codex CLI
 * @copyright (C) 2026 Tomoaki Nagahara
 */
class Calendar
{
	use \OP\OP_CI;

	/**	Return the deterministic methods inspected by module CI.
	 *
	 * @return array
	 */
	function CI_AllMethods() : array
	{
		return [
			'NormalizeYear',
			'NormalizeMonth',
		];
	}

	/**	Read daily counters for one calendar month.
	 *
	 * @param  int|null $year
	 * @param  int|null $month
	 * @return array
	 */
	function Calendar(?int $year=null, ?int $month=null) : array
	{
		$today = new \DateTimeImmutable('today');

		$year = $this->NormalizeYear($year, (int)$today->format('Y'));
		$month = $this->NormalizeMonth($month, (int)$today->format('n'));

		$first = new \DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month));
		$last_day = (int)$first->format('t');
		$weekday = (int)$first->format('w');
		$weeks = [];
		$week = [];

		for( $i = 0; $i < $weekday; $i++ ){
			$week[] = null;
		}

		for( $day = 1; $day <= $last_day; $day++ ){
			$date = $first->setDate($year, $month, $day);
			$week[] = [
				'day'      => $day,
				'date'     => $date->format('Y-m-d'),
				'count'    => $this->ReadFile($this->DailyPath($date)),
				'is_today' => $date->format('Y-m-d') === $today->format('Y-m-d'),
			];

			if( count($week) === 7 ){
				$weeks[] = $week;
				$week = [];
			}
		}

		if( $week ){
			while( count($week) < 7 ){
				$week[] = null;
			}
			$weeks[] = $week;
		}

		$previous = $first->modify('-1 month');
		$next = $first->modify('+1 month');

		return [
			'year'     => (int)$first->format('Y'),
			'month'    => (int)$first->format('n'),
			'label'    => $first->format('F Y'),
			'weeks'    => $weeks,
			'previous' => [
				'year'  => (int)$previous->format('Y'),
				'month' => (int)$previous->format('n'),
			],
			'next'     => [
				'year'  => (int)$next->format('Y'),
				'month' => (int)$next->format('n'),
			],
		];
	}

	/**	Normalize the requested calendar year.
	 *
	 * @param  int|null $year
	 * @param  int      $fallback
	 * @return int
	 */
	function NormalizeYear(?int $year, int $fallback) : int
	{
		if(!$year or $year < 1970 or $year > 9999 ){
			return $fallback;
		}

		return $year;
	}

	/**	Normalize the requested calendar month.
	 *
	 * @param  int|null $month
	 * @param  int      $fallback
	 * @return int
	 */
	function NormalizeMonth(?int $month, int $fallback) : int
	{
		if(!$month or $month < 1 or $month > 12 ){
			return $fallback;
		}

		return $month;
	}

	/**	Return the daily counter file path.
	 *
	 * @param  \DateTimeImmutable $date
	 * @return string
	 */
	private function DailyPath(\DateTimeImmutable $date) : string
	{
		return $this->Common()->StorageRoot() . $date->format('Y/m/d') . '.txt';
	}

	/**	Return shared counter helpers.
	 *
	 * @return Common
	 */
	private function Common() : Common
	{
		//	...
		static $common;

		//	...
		if(!$common ){
			$common = new Common();
		}

		//	...
		return $common;
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
