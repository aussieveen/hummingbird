<?php

/**
 * Created by PhpStorm.
 * User: Simon McWhinnie
 * Date: 13/07/2015
 * Time: 20:00
 */
class Requests {

	private $base_rest_request_format = 'http://hummingbird.me/api/v1/';

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

	public function make_request( $request_slug, $transient_id, $expiration, $username = '', $optional = '' ){

		$rest = new Rest();
		$requests = self::get_requests();

		if ( ! isset( $requests[ $request_slug ] ) ) {
			return false;
		}
		if ( ! isset( $username ) && $requests[ $request_slug ]['username'] ) {
			return false;
		}
		$optional = "?status=currently-watching";

		$request = $this->base_rest_request_format . sprintf( $requests[$request_slug]['url'], $username );

		if( $optional ){
			$request .= $optional;
		}



		$result = $rest->get( $request );
		if( $result['httpCode'] != 200 ){
			return false;
		}
		set_transient( $transient_id, $result, $expiration );
		return $result;
	}
}