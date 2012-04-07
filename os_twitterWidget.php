<?php

/*
 * Twitter widget.
 */
class os_twitterWidget extends WP_Widget {
    
    function __construct() {
        $params = array(
            'description'   => 'Simple widget that displays twitter feeds.',
            'name'          => 'OS Twitter Widget'
        );
        
        parent::__construct('os_twitterWidget', '', $params);

    } // end function
    
    public function form($userInput) {
        
        extract($userInput);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title');?>">Title: </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if(isset($title)){ echo esc_attr($title); } ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('username');?>">Twitter username: </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('username');?>" name="<?php echo $this->get_field_name('username');?>" value="<?php if(isset($username)){ echo esc_attr($username); } ?>" />
        </p>    
        
        <p>
            <label for="<?php echo $this->get_field_id('tweet_count');?>">How many Tweets to displpay: </label>
            <input type="number" class="widefat" style="width: 35px" id="<?php echo $this->get_field_id('tweet_count');?>" name="<?php echo $this->get_field_name('tweet_count'); ?>" min="1" max="10" value="<?php echo !empty($tweet_count) ? $tweet_count : 5; ?>" />
        </p>
        
        <?php
    } // end function
    
    public function widget($args, $userInput) {
        
        extract($args);
        extract($userInput);
        
        if(empty($title)){
            $title = "My recent Tweets!";
        }// end if
        
        $data = $this->twitter($tweet_count, $username);
        
        if(false !== $data && isset($data->tweets)){
            echo $before_widget;
                echo $before_title;
                    echo $title;
                echo $after_title;
                
                // here comes the tweets
                echo '<ul class="os_tweets"><li>' . implode('</li><li>', $data->tweets) . '</li></ul>';
                
            echo $after_widget;
        }// end if
    } // end function
    
    private function twitter($tweet_count, $username){
        if(empty($username)){
            return false;
        }// end if
        
        $tweets = get_transient('os_recent_tweet');
        
        if(!$tweets || $tweets->username !== $username || $tweets->tweet_count !== $tweet_count){
            return $this->fetch_tweets($tweet_count, $username);   
        }// end if
        
        return $tweets;
    }// end function
    
    private function fetch_tweets($tweet_count, $username){
        $tweets = wp_remote_get("http://twitter.com/statuses/user_timeline/$username.json");
        $tweets = json_decode($tweets['body']);
        
        // if some problem occurs....
        if(isset($tweets->error)){
            return false;
        }// end if
        
        $data = new stdClass();
        $data->username = $username;
        $data->tweet_count = $tweet_count;
        $data->tweets = array();
        
        foreach($tweets as $tweet){
            if($tweet_count-- == 0){
                break;
            }// end if
            $data->tweets[] = $this->filter_tweet($tweet->text);
        }// end foreach
        
        set_transient('os_recent_tweet', $data, 60 * 5);
        
    }// end function
    
    private function filter_tweet($tweet){
        $tweet = preg_replace('/(http[^\s]+)/im', '<a href="$1">$1</a>',  $tweet); 
	$tweet = preg_replace('/@([^\s]+)/', '<a href="http://twitter.com/$1">@$1</a>', $tweet);   
	
        return $tweet;
    }// enfd function
    
} // end class

add_action('widgets_init', function(){
    register_widget('os_twitterWidget');
});

?>
