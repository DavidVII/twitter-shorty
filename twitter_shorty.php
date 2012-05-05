<?php
/*
Plugin Name: Twitter Shorty
Plugin URI: https://github.com/DavidVII/twitter-shorty
Description: This is a simple WordPress plugin that allows you to add an unstyled twitter feed to any page on your WordPress site via shortcode. This plugin is still in development and is not ready for production.
Version: 0.1
Author: David Becerra
Author URI: http://redeyedesigner.com
*/

add_shortcode( 'twitter', function( $atts, $content ) {
	$atts = shortcode_atts(
		array(
			'username' => 'DavidVII',
			'content' => !empty($content) ? $content : 'Follow me on Twitter!',
			'show_tweets' => false,
			'reset_timer' => 60,
			'total_tweets' => 5
		), $atts
	);

	extract($atts);

	if ($show_tweets) {
		$tweets = fetch_tweets($total_tweets, $username, $reset_timer);
	}

	return "$tweets <p><a href='http://twitter.com/$username'>$content</a></p>";
});


function fetch_tweets($total_tweets, $username, $reset_timer) {
	global $id;
	// For debugging reasons I leave this here to start from scratch...
	// delete_post_meta($id, 'tsc_tweets'); die();
	$recent_tweets = get_post_meta($id, 'tsc_tweets');
	reset_data($recent_tweets, $reset_timer);
	
	// If there is no cache, then fetch new tweets and cache it.
	if( empty($recent_tweets) ) {
		$tweets = curl("https://twitter.com/statuses/user_timeline/$username.json");

		$data = array();
		foreach($tweets as $tweet) {
			if ( $total_tweets-- === 0 ) break;
			$data[] = $tweet->text;
		}

		$recent_tweets = array( (int)date('i', time()) );
		$recent_tweets[] = '<ul class="ts_tweetWrap"><li>' . implode('</li><li>', $data) . '</li></ul>';

		cache($recent_tweets);
	}

	return isset($recent_tweets[0][1]) ? $recent_tweets[0][1] : $recent_tweets[1];
}

function curl($url) {
	$c = curl_init($url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($c, CURLOPT_TIMEOUT, 5);

	return json_decode( curl_exec($c) );
}


function cache($recent_tweets) {
	global $id;
	add_post_meta($id, 'tsc_tweets', $recent_tweets, true);
}

function reset_data($recent_tweets, $reset_timer) {
	global $id;
	if( isset($recent_tweets[0][0]) ) {
		$delay = $recent_tweets[0][0] + (int)$reset_timer;
		if( $delay >= 60 ) $delay -= 60;
		if( $delay <= (int)date('i', time() )) {
			delete_post_meta($id, 'tsc_tweets');
		}
	}
}
