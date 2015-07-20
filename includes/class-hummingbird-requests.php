<?php

/**
 * Created by PhpStorm.
 * User: Simon McWhinnie
 * Date: 13/07/2015
 * Time: 20:00
 */
class Requests {

    private static $requests = array(
        'feed' => array(
            'slug' => 'feed',
            'url' => '/users/%s/feed',
            'success_response' => 200,
            'username' => true,
            'name' => 'Feed'
        )
    );

	/**
	 * @return array
	 */
	public static function get_requests() {
		return self::$requests;
	}
}