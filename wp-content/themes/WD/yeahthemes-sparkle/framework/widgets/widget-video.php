<?php
/*
	Plugin Name: Video Widget
	Description: A widget that display fluid videos.
*/

/*Widget class.
/*---------------------------------------------------------------------------------------------*/
class yt_Video_Widget extends WP_Widget {

/*Widget Setup
/*---------------------------------------------------------------------------------------------*/
function yt_Video_Widget() {
	
	// Widget settings
	$widget_ops = array( 'classname' => 'yt-video-widget yt-widget', 'description' => __("A wiget allowed you to add a fluid video.", 'framework') );

	// Widget control settings
	$control_ops = array('id_base' => 'yt-video-widget' );

	// Create the widget
	$this->WP_Widget( 'yt-video-widget', __('(Theme) Fluid Video', 'framework'), $widget_ops, $control_ops );
}

/*Display Widget
/*---------------------------------------------------------------------------------------------*/
function widget( $args, $instance ) {
	extract( $args );

	// Our variables from the widget settings
	$title = apply_filters('widget_title', $instance['title'] );
	$embeded_code = $instance['embeded_code'];
	$desc = $instance['desc'];

	echo $before_widget;

	//Echo widget title
	if ( $title )
		echo $before_title . $title . $after_title;

	//If a category was selected, display it.
	?>
 		<div class="fluid-video margin-bottom-15">
        	<?php echo $embeded_code;?>
        </div>
        
        <?php echo $desc;?>
        
	<?php

	//After widget
	echo $after_widget;
}

/*Widget Settings (Displays the widget settings controls on the widget panel)
/*---------------------------------------------------------------------------------------------*/
function form( $instance ) {

	/* Set up some default widget settings. */
	$defaults = array( 'title' => __('Video', 'framework'), 'embeded_code' =>'', 'desc' => __('Place some short description about the video here.','framework') );
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'framework') ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
    </p>
	
    <p>
        <label for="<?php echo $this->get_field_id( 'embeded_code' ); ?>"><?php _e('Embed Code:', 'framework') ?></label>
        <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id( 'embeded_code' ); ?>" name="<?php echo $this->get_field_name( 'embeded_code' ); ?>" ><?php echo stripslashes(htmlspecialchars(( $instance['embeded_code'] ), ENT_QUOTES)); ?></textarea>
    	<em><?php _e('Paste the embeded code of the video is hosted on such as Youtube, Vimeo, Blip.tv, Viddler, Kickstarter. the video will be automatically resized to fit the container!','framework');?></em>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'desc' ); ?>"><?php _e('Short Description:', 'framework') ?></label>
        <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>" ><?php echo stripslashes(htmlspecialchars(( $instance['desc'] ), ENT_QUOTES)); ?></textarea>
    </p>
		
<?php
}

/*Update Widget
/*---------------------------------------------------------------------------------------------*/
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	//Strip tags for title and name to remove HTML
	$instance['title'] = strip_tags( $new_instance['title'] );
	
	//No need to strip tags for categories.
	$instance['category_id'] = $new_instance['category_id'];
	$instance['desc'] = strip_tags( $new_instance['desc'] , '<a><p><b><strong>');
	$instance['embeded_code'] = $new_instance['embeded_code'];

	return $instance;
}
}