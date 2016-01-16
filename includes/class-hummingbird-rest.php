<?php

class Rest{

	private $base_rest_request_format = 'http://hummingbird.me/api/v1/';

	public function get( Array $request_details, $username = null ){

		$request = $this->base_rest_request_format . sprintf( $request_details['url'], $username );

		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $request);
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36' );

	    $json = curl_exec($curl);
	    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    curl_close($curl);

	    return array(
		    'json' => $json,
		    'httpCode' => $httpCode);

	}

}

?>