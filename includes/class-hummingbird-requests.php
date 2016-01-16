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
            'username' => true,
            'name' => 'Feed'
        ),
        'library' => array(
	        'slug' => 'library',
	        'url' => '/users/%s/library',
	        'username' => true,
	        'name' => 'Library'
        )
    );

	/**
	 * @return array
	 */
	public static function get_requests() {
		return self::$requests;
	}

	public function make_request( $request_slug, $transient_id, $expiration, $username = '' ){
		$rest = new Rest();
		$requests = self::get_requests();
		if ( ! isset( $requests[ $request_slug ] ) ) {
			return false;
		}
		if ( ! isset( $username ) && $requests[ $request_slug ]['username'] ) {
			return false;
		}
		$result = $rest->get( $requests[$request_slug] , $username );
		if( $result['httpCode'] != 200 ){
			return false;
		}
		set_transient( $transient_id, $result, $expiration );
		return $result;
	}
}