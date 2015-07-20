<?php

/**
 * Created by PhpStorm.
 * User: Simon McWhinnie
 * Date: 20/07/2015
 * Time: 19:29
 */
class Hummingbird_Utilities {

	public static function decode_feed_get_library( $feed ) {
		if ( is_string( $feed ) ) {
			$feed = json_decode( $feed );
		}

		$feed_array = array();

		foreach ( $feed as $item ) {
			$media = $item->media;
			foreach ( $item->substories as $substory ) {
				$timestamp     = $substory->created_at;
				$substory_type = $substory->substory_type;
				$story_array   = array(
					'title'       => $media->title,
					'slug'        => $media->slug,
					'alt_title'   => $media->alternate_title,
					'cover_image' => $media->cover_image,
					'story_type'  => $substory_type,
				);
				switch ( $substory_type ) {
					case "watched_episode":
						$story_array['episode_number'] = $substory->episode_number;
						break;
					case "watchlist_status_update":
						$story_array['new_status'] = $substory->new_status;
						break;
				}
				$feed_array[ $timestamp ] = $story_array;
			}
		}
		ksort( $feed_array, 4 );

		return array_reverse( $feed_array );
	}

}