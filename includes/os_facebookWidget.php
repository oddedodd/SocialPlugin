<?php

/*
 * Facebook widget
 * 
 */

Class os_facebookWidget extends WP_Widget {
    
    function __construct() {
        $options = array(
            'description'   => 'Simple Facebook likebox widget',
            'name'          => 'OS Facebook Widget'
        );
        
        parent::__construct('os_facebookWidget', '', $options);
        
        }// end function
    

        public function form($userInput) {
            
            extract($userInput);
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if(isset($title)){ echo esc_attr($title); } ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('username'); ?>">Facebook username: </label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php if(isset($username)){ echo esc_attr($username); } ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('width'); ?>">Width: </label>
                <input type="number" class="widefat" style="width: 40px" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php if(isset($width)){ echo esc_attr($width); } ?>" /> px
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('height'); ?>">Height: </label>
                <input type="number" class="widefat" style="width: 40px" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php if(isset($height)){ echo esc_attr($height); } ?>" /> px
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('color_scheme'); ?>">Color scheme: </label>
                <select class="widefat" id="<?php echo $this->get_field_id('color_scheme'); ?>" name="<?php echo $this->get_field_name('color_scheme'); ?>">
                    <option value="light" <?php selected($color_scheme,"light") ?>>light</option>
                    <option value="dark" <?php selected($color_scheme,"dark"); ?>>dark</option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('os_border_color'); ?>">Border color: </label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('os_border_color'); ?>" name="<?php echo $this->get_field_name('os_border_color'); ?>" value="<?php if(isset($os_border_color)){ echo esc_attr($os_border_color); } ?>" />
            </p>
            <p class="os_widget_info">Only use hexcode without the leading #</p>
            <p>
                <label for="<?php echo $this->get_field_id('show_faces'); ?>">Show faces: </label>
                <input type="checkbox" id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" <?php if(isset($show_faces)){ echo "checked"; } ?> />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('show_stream'); ?>">Show stream: </label>
                <input type="checkbox" id="<?php echo $this->get_field_id('show_stream'); ?>" name="<?php echo $this->get_field_name('show_stream'); ?>" <?php if(isset($show_stream)){ echo "checked"; } ?> />
            </p>
            <p>
                <label for="">Show header: </label>
                <input type="checkbox" id="<?php echo $this->get_field_id('show_header'); ?>" name="<?php echo $this->get_field_name('show_header'); ?>" <?php if(isset($show_header)){ echo "checked"; } ?> />
            </p>
            <?php
            
        }// end function
        
        public function widget($args, $userInput){
            extract($args);
            extract($userInput);
            
            if(isset($show_faces) == "on"){
                $show_faces = "true";
            } else {
                $show_faces = "false";
            } // end if
            
            if(isset($show_stream) == "on"){
                $show_stream = "true";
            } else {
                $show_stream = "false"; 
            } // end if
            
            if(isset($show_header) == "on"){
                $show_header = "true";
            } else {
                $show_header = "false";
            } // end if
            
            if(empty($title)){
                $title = "Follow me on Facebook";
            }// end if
            
            $facebook_iframe = "<iframe src='//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F$username&amp;width=$width&amp;height=$height&amp;colorscheme=$color_scheme&amp;show_faces=$show_faces&amp;border_color=%23".$os_border_color."&amp;stream=$show_stream&amp;header=$show_header' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:".$width."px; height:".$height."px;' allowTransparency='true'></iframe>";
            
            echo $before_widget;
                echo $before_title;
                    echo $title;
                echo $after_title;
                // the facebook iframe
                echo $facebook_iframe;
            echo $after_widget;
            
       }// end function
        
} // end class

add_action('widgets_init', function(){
   register_widget('os_facebookWidget'); 
});
?>
