<?php

/*
 * Shortcodes for tweet and like ....
 * 
 */

add_shortcode('tweet_this', function(){
        // shortcode to post current post/page to your twitter account
        $os_tweet_str = '<a href="https://twitter.com/share" class="twitter-share-button" data-lang="no">Tweet</a>';
        $os_tweet_str .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
        
        $os_return_var = '<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
                          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
        
        
        // returning the button!
        return $os_return_var;
    } // end function
);
?>
