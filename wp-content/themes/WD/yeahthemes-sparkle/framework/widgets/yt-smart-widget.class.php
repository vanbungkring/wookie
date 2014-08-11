<?php  
/**
 * Smartly Dynamic Widget
 * @since 1.0
 */
class YT_Smart_Widget extends WP_Widget {

	/**
	 * Display Widget
	 */
	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		echo $before_widget;
			$this->yt_display_widgets( $args, $instance );
		echo $after_widget;
	}
	/**
	 * Widget Settings
	 */
	function form( $instance ) {
		global $wp_registered_widgets;
		$instance = wp_parse_args( $instance, array( 
			'widgets'    => ''
		) );
		?>
		<input type="hidden" class="widefat" name="<?php echo $this->get_field_name('widgets') ?>" id="<?php echo $this->get_field_id('widgets') ?>" value="<?php echo htmlentities( $instance['widgets'] ) ?>" >
		<div class="yt-widget-extends" data-setting="#<?php echo $this->get_field_id('widgets') ?>" >
		<p><?php _e('Drag & Drop Widgets Here','yeahthemes') ?></p>
		<?php
		$widgets = explode(':yt-sm-data:', $instance['widgets'] );
		if( !empty( $widgets ) && is_array( $widgets ) ){
			$number = 1;
			foreach ( $widgets as $widget ) {
				if( !empty( $widget ) ) {
					$url = rawurldecode( $widget );
					parse_str( $widget,$nested_widget );
					$this->yt_smart_widgets_form( $nested_widget, $number );
				}
				$number++;
			}
		}
		?>
		</div>
		<?php
	}
	/**
	 * Update Widget
	 */
	function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = $old_instance;

		$instance['widgets'] = $new_instance['widgets'];

		return $instance;
	}

	/******************************************************
	 * Get nested widgets data
	 */
	function yt_get_widgets( $id_base, $number ){
		global $wp_registered_widgets;

		$widget = false;
		foreach ( $wp_registered_widgets as $key => $value ) {
			if( strpos( $key, $id_base ) === 0 ) {
				if( isset( $wp_registered_widgets[ $key ]['callback'][0]) && is_object( $wp_registered_widgets[ $key ]['callback'][0] ) ) {
					$classname = get_class( $wp_registered_widgets[ $key ]['callback'][0] );
					$widget = new $classname;
					$widget->id_base = $id_base;
					$widget->number = $number;
					break;
				}
			}
		}

		return $widget;
	}
	/**
	 * Overwrite this function to display custom structure
	 * Display nested Widgets (front-end)
	 */
	function yt_display_widgets( $args, $instance ){
	}

	/**
	 * Display nested wigets ( form)
	 */
	function yt_smart_widgets_form($nested_widget, $number){

		$instance = !empty($nested_widget['widget-'.$nested_widget['id_base']]) ? array_shift( $nested_widget['widget-'.$nested_widget['id_base']] ) : array();
		$widget = $this->yt_get_widgets( $nested_widget['id_base'], $number );
		if( $widget ) { ?>
			<div id="<?php echo esc_attr($nested_widget['widget-id']); ?>" class="widget">
				<div class="widget-top">
					<div class="widget-title-action">
						<a class="widget-action hide-if-no-js" href="#available-widgets"></a>
						<a class="widget-control-edit hide-if-js" href="<?php echo esc_url( add_query_arg( $query_arg ) ); ?>">
							<span class="edit"><?php _ex( 'Edit', 'widget' ); ?></span>
							<span class="add"><?php _ex( 'Add', 'widget' ); ?></span>
							<span class="screen-reader-text"><?php echo $widget->name; ?></span>
						</a>
					</div>
					<div class="widget-title"><h4><?php echo $widget->name; ?><span class="in-widget-title"></span></h4></div>
				</div>

				<div class="widget-inside">
					<div class="widget-content">
					<?php if( isset($nested_widget['id_base'] ) ) { 
						$widget->form($instance); 
					} else { 
						echo "\t\t<p>" . __('There are no options for this widget.','yeahthemes') . "</p>\n"; 
					} ?>
				</div>
				<input data-yt="true" type="hidden" name="widget-id" class="widget-id" value="<?php echo esc_attr($nested_widget['widget-id']); ?>" />
				<input data-yt="true" type="hidden" name="id_base" class="id_base" value="<?php echo esc_attr($nested_widget['id_base']); ?>" />
				<input data-yt="true" type="hidden" name="widget-width" class="widget-width" value="<?php echo esc_attr($nested_widget['widget-width']); ?>">
				<div class="widget-control-actions">
					<div class="alignleft">
					<a class="widget-control-remove" href="#remove"><?php _e('Delete','yeahthemes'); ?></a> |
					<a class="widget-control-close" href="#close"><?php _e('Close','yeahthemes'); ?></a>
					</div>
					<div class="alignright widget-control-noform">
					<?php submit_button( __( 'Save', 'yeahthemes' ), 'button-primary widget-control-save right', 'savewidget', false, array( 'id' => 'widget-' . esc_attr( $nested_widget['widget-id'] ) . '-savewidget' ) ); ?>
					<span class="spinner"></span>
					</div>
					<br class="clear" />
				</div>
			</div>

			<div class="widget-description"><?php echo ( $widget_description = wp_widget_description($widget_id) ) ? "$widget_description\n" : "$widget_title\n"; ?></div>
		</div>
		<?php
		} 
	}
}

/**
 * Smartly Dynamic Widget scripts
 * @since 1.0
 */
add_action('admin_enqueue_scripts', 'yt_smart_widget_enqueue_scripts', 6 );

if(!function_exists( 'yt_smart_widget_enqueue_scripts' )){

    function yt_smart_widget_enqueue_scripts( $hook ){
		if( 'widgets.php' != $hook )
	        return;
	    wp_enqueue_script( 'yt-smart-widget-script', YEAHTHEMES_FRAMEWORK_ADMIN_URI . '/assets/js/admin-smart-widget.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'admin-widgets' ), '', true );
	
    }
}