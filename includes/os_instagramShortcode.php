<?php

/*
Wordpress Short tag to view instagram images
(c) Odd Egil Hegge Selnes // post@odde.org
You are free to use this code as you wish - you don't even have to credit me :-)


*/

add_shortcode('instagram', function($atts) {
	$atts = shortcode_atts(
		array(
			'tag' 				=> 'tmpnorge',
			'insta_num_pics' 	=> 12,
			'insta_pic_width'	=> ''
		), $atts
	);
	
	extract($atts);
		
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

			// fancybox - lightbox
			// 1. include jQuery
			echo "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js'></script>";

			// 2. Mousewheel plugin
			echo "<script type='text/javascript' src='$siteUrl/wp-content/plugins/SocialPlugin/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js'></script>";
			
			// 3. Fancy boxx      
			echo "<link rel='stylesheet' href='$siteUrl/wp-content/plugins/SocialPlugin/js/fancybox/source/jquery.fancybox.css?v=2.0.6' type='text/css' media='screen' />";
			echo "<script type='text/javascript' src='$siteUrl/wp-content/plugins/SocialPlugin/js/fancybox/source/jquery.fancybox.pack.js?v=2.0.6'></script>";

			// 5. Attach fancybox when document is loaded
			?>
			<script type="text/javascript">
				$(document).ready(function() {
					$(".fancybox").fancybox();
				});
			</script>
			<?php
			echo "<div id='instagram-feed'>";
			echo "<ul class='instagram-album' data-tag='$tag' data-qty='$count'>";
			foreach($xml->channel->item as $item) {
				echo "<li>";
				echo "<a href='$item->link' class='fancybox' rel='group' title='$item->title'><img src='$item->link' alt='$item->title' /></a>";
				//echo "<span style='font-size: 12px; text-align: center;'>$item->pubDate</span>"; // $item->title
				echo "</li>";
				$i++;
				
				// exits loop when desired number of pictures are displayed... (default is 5) //
				if($i == $insta_num_pics){
					break;
				}// end if 
			}// end foreach			 
			echo "</ul>";
			echo "<div class='clearfloat'></div>";
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
