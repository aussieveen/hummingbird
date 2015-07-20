<pre><?php

	$stories = Hummingbird_Utilities::decode_feed_get_library( $json_feed );
	$count   = 1;
	echo "<ul>";
	foreach ( $stories as $story ) {
		if ( $story['story_type'] === "watched_episode" ) {
			$format = "Watched episode %s of %s";
			$output = sprintf( $format, $story['episode_number'], $story['title'] );
		}
		if ( $story['story_type'] === "watchlist_status_update" ) {
			switch ( $story['new_status'] ) {
				case "currently_watching":
					$format = "Started watching %s";
					break;
				case "plan_to_watch":
					$format = "Added %s to my plan to watch list";
					break;
				case "completed":
					$format = "Finished watching %s";
					break;
				case "on_hold":
					$format = "%s is on hold";
					break;
				case "dropped":
					$format = "Dropped %s";
					break;
			}
			$output = sprintf( $format, $story['title'] );
		}
		printf( "<li>%s</li>", $output );
		if ( $count === 5 ) {
			break;
		}
		$count ++;
	}
	echo "</ul>";
	?>
</pre>
