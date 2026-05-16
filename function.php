<?php
/** op-module-counter:/function.php
 *
 * @created   2026-05-16
 * @license   Apache-2.0
 * @package   op-module-counter
 * @copyright (C) 2026 Tomoaki Nagahara
 */

/** declare
 *
 */
declare(strict_types=1);

/** namespace
 *
 */
namespace OP\MODULE\COUNTER;

/** Return the current counter target domain.
 *
 * @return string
 */
function Domain() : string
{
	$host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
	$host = trim(explode(',', $host)[0]);
	$host = preg_replace('/:\d+$/', '', $host);
	$host = strtolower($host);

	$domain = preg_replace('/[^a-z0-9._-]/', '_', $host);
	$domain = trim($domain, '._-');

	return $domain ?: 'unknown-host';
}

/** Return the counter storage root.
 *
 * @return string
 */
function StorageRoot() : string
{
	return _ROOT_ASSET_ . 'db/counter/' . Domain() . '/';
}

/** Return counter file paths for a date.
 *
 * @param  \DateTimeImmutable $date
 * @return array<string,string>
 */
function Paths(\DateTimeImmutable $date) : array
{
	$root  = StorageRoot();
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

/** Increment today's counter files.
 *
 * @return array<string,int>
 */
function Increment() : array
{
	$counts = [];

	foreach( Paths(new \DateTimeImmutable('today')) as $key => $path ){
		$counts[$key] = IncrementFile($path);
	}

	return $counts;
}

/** Read display counters.
 *
 * @return array<string,int>
 */
function Counts() : array
{
	$today     = new \DateTimeImmutable('today');
	$yesterday = $today->modify('-1 day');

	$today_paths     = Paths($today);
	$yesterday_paths = Paths($yesterday);

	return [
		'today'     => ReadFile($today_paths['day']),
		'yesterday' => ReadFile($yesterday_paths['day']),
		'month'     => ReadFile($today_paths['month']),
		'year'      => ReadFile($today_paths['year']),
		'total'     => ReadFile($today_paths['total']),
	];
}

/** Increment one counter file with an exclusive lock.
 *
 * @param  string $path
 * @return int
 */
function IncrementFile(string $path) : int
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

/** Read one counter file with a shared lock.
 *
 * @param  string $path
 * @return int
 */
function ReadFile(string $path) : int
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
