<?php
/**
 *	Plugin Name: Facebook Likebox
 *	Description: Display your Facebook fan page
 */
	
class YT_Facebook_Likebox_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-facebook-likebox-widget yt-widget',
			'description' => __('Display your Facebook fan page', 'yeahthemes')
		);
		
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-facebook-likebox-widget',
		);
		
		parent::__construct( 
			'yt-facebook-likebox-widget', 
			__('(Theme) Facebook Fan page', 'yeahthemes'), 
			$widget_ops, 
			$control_ops);
	
	}


	/**
	 * Display Widget
	 */
	function widget( $args, $instance ) {
		
		// outputs the content of the widget
		
		extract( $args );
		// Our variables from the widget settings
		$title = apply_filters('widget_title', $instance['title'] );
		$url = $instance['url'];
		
		
		$stream = ''; 
		if ( array_key_exists( 'stream', $instance ) ) {
			$stream = !empty( $instance['stream'] ) && $instance['stream'] == 'on' ? 'true' : 'false';
			
		}
		$height = $instance['height'] ? $instance['height'] : 590 ;
		$color = $instance['color'];
		
		echo $before_widget;
		
		//Echo widget title
		if ( $title ){echo $before_title . $title . $after_title;}
		
		if($url){
			
		?>
		<div class="yt-fb-likebox">
	    <iframe src="//www.facebook.com/plugins/likebox.php?href=<?php echo esc_url( $url );?>&amp;width&amp;height=<?php echo $height;?>&amp;colorscheme=<?php echo $color;?>&amp;show_faces=true&amp;header=false&amp;stream=<?php echo $stream;?>&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:<?php echo $height;?>px;" allowTransparency="true"></iframe>
	    </div>
	    <?php

		}else{
			_e('Oops!, You need to input your Facebook page URL','yeahthemes');
		}
		
		echo $after_widget;
	}
	/**
	 * Widget Settings
	 */
	function form($instance) {
		
		// Set up some default widget settings
		$defaults = array(
			'title' => __('Find us on Facebook','yeahthemes'),
			'url' => 'http://www.facebook.com/Yeahthemes',
			'stream' => 0,
			'height' => 290,
			'color' => 'light'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
	    
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','yeahthemes')?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e('Facebook Page Url:','yeahthemes')?></label>
			<input id="<?php echo $this->get_field_id( 'url' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo $instance['url']; ?>" />
		</p>  	
	    <p>
	    	<input id="<?php echo $this->get_field_id( 'stream' ); ?>" name="<?php echo $this->get_field_name( 'stream' ); ?>" type="checkbox" <?php checked( $instance['stream'], 'on' ); ?>/>
	    	<label for="<?php echo $this->get_field_id('stream'); ?>"><?php _e( 'Show stream', 'yeahthemes' ); ?></label>
	    </p>
	    <p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height:','yeahthemes')?></label>
			<input  step="10" min="290" max="1000" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="number" value="<?php echo esc_attr( $instance['height'] ); ?>" style="width:4em;"/>
		</p>
	    <p><em><?php _e('Heght should be 590 if "Show stream" option is enabled (default: 290)','yeahthemes')?></em></p>	
	    	
	    <p>
			<label for="<?php echo $this->get_field_id( 'color' ); ?>"><?php _e('Color:','yeahthemes')?></label>
			<select id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>">
	            <option <?php if ( 'light' == $instance['color'] ) echo 'selected="selected"'; ?>><?php _e('light','yeahthemes')?></option>
				<option <?php if ( 'dark' == $instance['color'] ) echo 'selected="selected"'; ?>><?php _e('dark','yeahthemes')?></option>
	        </select>
		</p>
	    
		
		<?php
	}

	/**
	 * Update Widget
	 */
	function update( $new_instance, $old_instance ) {
		
		// processes widget options to be saved
		$instance = $old_instance;

		//Strip tags for title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );	
		$instance['url'] 	= esc_url( $new_instance['url'] );
		$instance['stream'] = $new_instance['stream'] ? 'on' : 0;
		$instance['height'] = intval( $new_instance['height'] ) <= 0 ? 290 : intval( $new_instance['height'] );
		$instance['color'] 	= $new_instance['color'];
		
		return $instance;
	}
}


?>