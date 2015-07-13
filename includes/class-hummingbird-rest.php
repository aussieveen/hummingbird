<?php

class Rest{

	private $rest_request_format = 'http://myanimlist.net/malappinfo.php?u=%s&status=all';
	private $rest_type_format = '&type=%s';

	public function request( $username, $type = null ){
		$request = sprintf($this->rest_request_format, $username);
		if( null !== $type ){
			//$request .= sprintf($this->rest_type_format, $type);
		}
		
		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $request);
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36' );

	    $result = curl_exec($curl);
	    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    curl_close($curl);

	    var_dump($result);

	}

}

?>