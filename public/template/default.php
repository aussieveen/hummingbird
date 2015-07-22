<?php

$feed = json_decode($json_feed);
$count = 1;
$anime_event_format = '<div class="anime"><img class="anime-cover" src="%s"><p class = "anime-title">%s</p><ul class="anime-events">%s</ul></div>';
$event_format = '<li class="event-details"><p class="event-text">%s</br><span class="event-timestamp">%s</span></p></li>';
$story_format = '';
foreach ( $feed as $anime ) {
	$media = $anime->media;
	$event_output = '';
	$story_count = 1;
	foreach ( $anime->substories as $substory ) {
		if ( $substory->substory_type === "watched_episode" ) {
			$story_format = "Watched episode %s";
			$story_output = sprintf( $story_format, $substory->episode_number );
		}else if( $substory->substory_type === "watchlist_status_update" ) {
			switch ( $substory->new_status ) {
				case "currently_watching":
					$story_format = "Started watching";
					break;
				case "plan_to_watch":
					$story_format = "Added to my plan to watch list";
					break;
				case "completed":
					$story_format = "Finished watching";
					break;
				case "on_hold":
					$story_format = "On hold";
					break;
				case "dropped":
					$story_format = "Dropped";
					break;
			}
			$story_output = sprintf( $story_format );
		}
		$interval = Hummingbird_Utilities::format_interval( time() - strtotime($substory->created_at), 1 );
		$event_output .= sprintf($event_format, $story_output, $interval . " ago" );
		if ( $story_count === 2 ) {
			break;
		}
		$story_count ++;
	}
	printf( $anime_event_format, $media->cover_image, $media->title, $event_output );
	if ( $count === 5 ) {
		break;
	}
	$count ++;
}

