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
		$anime_format = '<div class="anime"><img class="anime-cover" src="%s"><p class = "anime-title">%s</p></div>';
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
		print('<h2>Currently Watching</h2><div class = "hummingbird_shortcode" id = "currently_watching">');
		foreach($json_feed as $library_entry){
			$anime = $library_entry->anime;
			printf( $anime_format, $anime->cover_image, $anime->title );
		}
		print('</div>');
	}
}

add_shortcode( 'hummingbird', array( 'Hummingbird_Shortcode', 'render_shortcode' ) );