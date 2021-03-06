<?php
/**
 *	Plugin Name: Child pages
 *	Description: A widget that displays your child pages photos
 */
class YT_Childpages_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-childpages-widget yt-widget',
			'description' => __('A widget that displays your child pages photos', 'yeahthemes')
		);
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-childpages-widget',
			//'width'	=> 400
		);
		
		parent::__construct( 
			'yt-childpages-widget', 
			__('(Theme) Child pages', 'yeahthemes'), 
			$widget_ops, 
			$control_ops);
	}

	/**
	 * Display Widget
	 */
	function widget( $args, $instance ) {
		extract( $args );

		global $post;
		// get the page id outside the loop
		if(is_search())	return;
		$page_id = $post->ID;
		$curr_page_id = get_post( $page_id, ARRAY_A );
		$curr_page_title = $curr_page_id['post_title'];
		$curr_page_parent = $post->post_parent;

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );

		//Before widget

		//Display the childpages
		if( $curr_page_parent )
			$children = wp_list_pages("title_li=&sort_column=menu_order&child_of=".$curr_page_parent."&echo=0");
		else
			$children = wp_list_pages("title_li=&sort_column=menu_order&child_of=".$page_id."&echo=0");
			
		if ( $children ) :

			echo $before_widget;
			//Display the widget title if one was input, if not display the parent page title instead.
			//Echo widget title
			if ( $title ):
				echo $before_title . $title . $after_title;
			else :?>
				<?php echo $before_title;
					$parent = get_post($post->post_parent); echo $parent->post_title; ?></h3>
				<?php echo $after_title;
			endif; ?>
			<ul>
				<?php echo $children; ?>
			</ul>

		<?php
	    	//After widget 
			echo $after_widget;
		endif; 

	}
	/**
	 * Widget Settings
	 */
	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => 'Child pages' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>


		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'yeahthemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			<br />
			<?php _e('Leave the Title field blank if you would like to display the parent page Title instead.', 'yeahthemes'); ?>
		</p>

	<?php
	}
	/**
	 * Update Widget
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags for title to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

}