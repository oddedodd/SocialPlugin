<?php
error_reporting(E_ALL);
/*       
Plugin Name: OS Social Plugin
Plugin URI: http://dev.odde.org
Description: Simple Plugin for social media
Version: 1.0
Author: Odd Egil Hegge Selnes
Author URI: http://dev.odde.org
*/

// require the twitter shortcode stuff
require 'includes/os_twitterShortcode.php';

// require the shortcode //
require 'includes/os_shortcodes.php';

// require the twitter widget
require 'includes/os_twitterWidget.php';
     
// require the facebook widget
require 'includes/os_facebookWidget.php';

// INSTAGRAM SHORTCODE 
require 'includes/os_instagramShortcode.php';

?>