<?php

/*
Wordpress Short tag to view instagram images
(c) Odd Egil Hegge Selnes // post@odde.org
You are free to use this code as you wish - you don't even have to credit me :-)

17. May 2012 - Hooray!
*/

add_shortcode('instagram', function($atts) {
	$atts = shortcode_atts(
		array(
			'tag' 				=> 'tmpnorge',
			'insta_num_pics' 	=> 5,
			'insta_pic_width'	=> ''
		), $atts
	);
	
	extract($atts);
	// includes som custom styles... //
	
	
	// includes the wordpress class if it's not already available... //
	if(!class_exists('WP_Http')){
		include_once( ABSPATH . WPINC. '/class-http.php' );
	}// end if 
	
	// fetching the instagram feed...
	$request	= NEW WP_Http;
	$rss		= $request->request("http://instagr.am/tags/$tag/feed/recent.rss");
	
	if($rss['response']['code'] == 200){
		$i = 0; 
		// displays images, titles and when they were published.... //
		$xml = new SimpleXMLElement($rss['body']);
 		
		if($count = count($xml->channel->item)) {
			// including a stylesheet - go crazy styling this.... :-) //
			$siteUrl = get_bloginfo('url');
			echo "<link rel='stylesheet' href='$siteUrl/wp-content/plugins/SocialPlugin/css/instagram.css' />";       
			
			echo "<div id='instagram-feed'>";
			echo "<ul class='instagram-feed' data-tag='$tag' data-qty='$count'>";
			foreach($xml->channel->item as $item) {
				echo "<li><img src='$item->link' alt='$item->title' width='$insta_pic_width' /></li>";
				echo "<li>$item->pubDate</li>";
				echo "<li>$item->title</li>";
				echo "<li>&nbsp;</li>";
				$i++;
				
				// exits loop when desired number of pictures are displayed... (default is 5) //
				if($i == $insta_num_pics){
					break;
				}// end if 
			}// end foreach							 
			echo "</ul>";
			echo "</div>"; 
		} else {
			echo "<!-- No results found for feed $tag -->";
		}// end if
	} else {
		// if the instagram servers does not return a feed with images... //
		echo "not ok! <br />";
		echo "Code: ".$rss["response"]["code"]." - please try a diffetent tag...";
	} // end if

});// end shorttag

?>
