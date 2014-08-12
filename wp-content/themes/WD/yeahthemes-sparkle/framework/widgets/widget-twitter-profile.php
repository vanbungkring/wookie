<?php
/**
 *	Plugin Name: Twitter Profile
 *	Description: Display your Twitter Profile.
 */
class YT_Twitter_Profiles_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-twitter-profile-widget yt-widget',
			'description' => __('Display your Twitter Profile.', 'yeahthemes')
		);
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-twitter-profile-widget',
			//'width'	=> 400
		);
		
		parent::__construct( 
			'yt-twitter-profile-widget', 
			__('(Theme) Twitter Profile', 'yeahthemes'), 
			$widget_ops, 
			$control_ops);
	}
	/**
	 * Display Widget
	 */	
	function widget( $args, $instance ) {
		
		// outputs the content of the widget
		global $wpdb;
		extract( $args );
		// Our variables from the widget settings
		$title = apply_filters('widget_title', $instance['title'] );
		$username = $instance['username'];
		
		echo $before_widget;
		
		//Echo widget title
		if ( $title ){echo $before_title . $title . $after_title;}
			
		$response_data = yt_twitter_user_profile( $username );		
		//print_r( $response_data);
		if( !is_wp_error( $response_data ) && !empty( $response_data ) ):
			 
		?>
	    <div class="yt-twitter-profile-wrapper">
	    	<div class="yt-twitter-profile-header">
	            <a href="https://twitter.com/<?php echo esc_attr( $response_data['screen_name'] );?>" title="<?php esc_attr( sprintf( __('%s on Twitter', 'yeahthemes' ), $response_data['screen_name'] ) );?>"><img width="48" height="48" src="<?php echo str_replace('_normal','', $response_data['profile_image_url']);?>" alt="<?php echo esc_attr( $response_data['name'] );?>"></a>
	            <h4><?php echo $response_data['name'];?></h4>
	            <a href="https://twitter.com/<?php echo esc_attr( $response_data['screen_name'] );?>" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false"><?php _e('Follow @','framework')?><?php echo $response_data['screen_name'];?></a>
	        </div>
	        <div class="yt-twitter-profile-body"><?php echo $response_data['description'];?></div>
	        <div class="yt-twitter-profile-footer">
	            <span class="status-count"><strong><?php echo $response_data['statuses_count'];?></strong> <?php _e('Tweets','framework');?></span>
	            <span class="friends-count"><strong><?php echo $response_data['friends_count'];?></strong> <?php _e('Following','framework');?></span>
	            <span class="followers-count"><strong><?php echo $response_data['followers_count'];?></strong> <?php _e('Followers','framework');?></span>
	        </div>
	    </div>
		<?php
		endif;

		echo $after_widget;
	}

	/**
	 * Widget Settings
	 */	 
	function form($instance) {
		
		// Set up some default widget settings
		$defaults = array(
			'title' => 'My Profile',
			'username' => 'Yeahthemes'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','framework')?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Username:','framework')?></label>
			<input id="<?php echo $this->get_field_id( 'username' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo esc_attr( $instance['username'] ); ?>" />
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
		$instance['username'] = strip_tags( $new_instance['username'] );
		
		return $instance;
	}
}