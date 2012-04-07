<?php
add_shortcode('twitter', function($atts, $content){
   $atts = shortcode_atts(
		array(
			'username' 			=> 'oddedodd',
			'content'  			=> !empty($content) ? $content: 'Follow me on Twitter!',
			'show_tweets' 		=> false,
			'tweet_reset_time' 	=> 10,
			'num_tweets'		=> 5
		), $atts
	);
	extract($atts);
    
	if($show_tweets){    
		$tweets = fetch_tweets($num_tweets, $username, $tweet_reset_time);
	}// end if
	
   	return "$tweets <p><a href='http://twitter.com/$username'>$content</a></p>";   

});

function fetch_tweets($num_tweets, $username, $tweet_reset_time){   
	global $id;
	$recent_tweets = get_post_meta($id, 'os_recent_tweets');
	reset_cache($recent_tweets, $tweet_reset_time);    
	$tweets = curl("http://twitter.com/statuses/user_timeline/$username.json");          
	
	$data = array();
	foreach($tweets as $tweet){
		if($num_tweets === 0){
			break;
		}// end if
		$data[] = filter_tweet($tweet->text);    
		// $data[] = $tweet->text;   
        $num_tweets--;
	}// end foreach
	
    $recent_tweets = array((int)date('i', time()));
	$recent_tweets[] = '<ul class="os_tweets"><li>' . implode('</li><li>', $data) . '</li></ul>';
	
	cache($recent_tweets);  
 
	return $recent_tweets[1];
}             

function curl($url){
	$c = curl_init($url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 3);   
	curl_setopt($c, CURLOPT_TIMEOUT, 5);
	
	return json_decode(curl_exec($c));   
}

function cache($reccent_tweets){
	global $id;
	add_post_meta($id, 'os_recent_tweets', $recent_tweets, true);
}                                                 

function reset_cache($recent_tweets, $tweet_reset_time){
	global $id;             
	
   	if(isset($recent_tweets[0][1])){
		$delay = $recent_tweets[0][0] + (int)$tweet_reset_time;
		if($delay >= 60){
			$delay -= 60;
		}// end if
		if($delay <= (int)date('i', time())){     
			delete_post_meta($id, 'os_recent_tweets');
		}// end if
	}// end if
	
}

function filter_tweet($tweet){
	$tweet = preg_replace('/(http[^\s]+)/im', '<a href="$1">$1</a>',  $tweet); 
	$tweet = preg_replace('/@([^\s]+)/', '<a href="http://twitter.com/$1">@$1</a>', $tweet);   
	return $tweet;
}// end function
?>