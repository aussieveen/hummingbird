<?php

/**
 * Created by PhpStorm
 * User: Simon McWhinnie
 * Date: 27/12/2015
 * Time: 21:33
 */
class Hummingbird_Shortcode {

	public function render_shortcode( $atts, $content = ""){
		$transient_id = 'hummingbird_shortcode_library';
		$options_name = 'hummingbird_option_name';
		$feed = get_transient( $transient_id );
		if(!$feed){
			$request = new Requests();
			$options = get_option( $options_name );
			$feed = $request->make_request( 'library' , $transient_id, $options['hb_cache_timer'] * 60, $options['hb_username'] );
		}
		if(!$feed){
			return;
		}
		$json_feed = json_decode( $feed['json'] );
		var_dump($json_feed);


	}
}

add_shortcode( 'hummingbird', array( 'Hummingbird_Shortcode', 'render_shortcode' ) );