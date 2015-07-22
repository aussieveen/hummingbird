<?php

/**
 * Created by PhpStorm.
 * User: Simon McWhinnie
 * Date: 20/07/2015
 * Time: 19:29
 */
class Hummingbird_Utilities {

	public static function format_interval($interval, $granularity = 2) {
		$units = array('1 year|@count years' => 31536000, '1 week|@count weeks' => 604800, '1 day|@count days' => 86400, '1 hour|@count hours' => 3600, '1 min|@count min' => 60, '1 sec|@count sec' => 1);
		$output = '';
		foreach ($units as $key => $value) {
			$key = explode('|', $key);
			if ($interval >= $value) {
				$floor = floor($interval / $value);
				$output .= ($output ? ' ' : '') . ($floor == 1 ? $key[0] : str_replace('@count', $floor, $key[1]));
				$interval %= $value;
				$granularity--;
			}

			if ($granularity == 0) {
				break;
			}
		}
		return $output ? $output : '0 sec';
	}

}