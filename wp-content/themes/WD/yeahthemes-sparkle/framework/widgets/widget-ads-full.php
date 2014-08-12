<?php
/**
 *	Plugin Name: Ads Full
 *	Description: Full width Advertising
 */
class YT_AdsFull_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-adsfull-widget yt-widget',
			'description' => __('Full width advertisements', 'yeahthemes')
		);
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-adsfull-widget',
			//'width'	=> 400
		);
		
		parent::__construct( 
			'yt-adsfull-widget', 
			__('(Theme) Ads Full', 'yeahthemes'), 
			$widget_ops, 
			$control_ops);
	
		//Then make sure our options will be added in the footer
	     add_action('admin_head', array( $this, 'widget_styles'), 10 );
	     add_action('admin_print_footer_scripts', array( $this, 'widget_scripts'));
	}
	function widget_styles(){
		$output = '<style>
			.yt-widget-ads-full-image-preview{
				max-width:100%;
				margin-top:15px;
			}
		</style>';

		/*Inline css :P */
		$output = str_replace(array("\r", "\n", "\t"), "", $output);

		echo $output . "\n";
		
	}
	function widget_scripts(){

		$output ='';

		$output = str_replace(array("\r", "\n", "\t"), "", $output);

		echo $output . "\n";
	}
	/**
	 * Display Widget
	 */
	function widget( $args, $instance ) {
	
		// outputs the content of the widget
		
		extract( $args );
		// Our variables from the widget settings
		$title = apply_filters('widget_title', $instance['title'] );
		
		$adscode 	= !empty( $instance['adscode'] ) ? $instance['adscode'] : '';
		$image 		= !empty( $instance['image'] ) ? $instance['image'] : '';
		$url 		= !empty( $instance['url'] ) ? $instance['url'] : '';
		$alt 		= !empty( $instance['alt'] ) ? $instance['alt'] : '';

		if( !empty($instance['single_only']) && !is_single() )
			return;

		echo $before_widget;	
		
		//Echo widget title
		if ( $title ){ echo $before_title . $title . $after_title; }
		$output = '';

		$output .= '<div class="yt-ads-spacefull-content">';
			if( $adscode || $image ){
				if( !empty( $adscode ) )
					$output .= $adscode;
				else
					$output .= !empty( $url ) 
					? sprintf( '<a href="%s" title="%s" target="_blank"><img src="%s" alt="%s"></a>', esc_url( $url ), esc_attr( $alt ), esc_url( $image ), esc_attr( $alt ) ) 
					: sprintf( '<img src="%s" alt="%s">', esc_url( $image ), esc_attr( $alt ) ) ;
			}
		$output .= '</div>';
		echo $output;
		echo $after_widget;
	}

	/**
	 * Widget Settings
	 */
	function form($instance) {
		
		// Set up some default widget settings
		$defaults = array(
			'title' => __('Advertisements','yeahthemes'),
			'single_only' => 0,
			'image' => '',
			'url' => '',
			'alt' => '',
			'adscode' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
	    
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','yeahthemes')?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
	    	<input id="<?php echo $this->get_field_id( 'single_only' ); ?>" name="<?php echo $this->get_field_name( 'single_only' ); ?>" type="checkbox" <?php checked( $instance['single_only'], 'on' ); ?>/>
	    	<label for="<?php echo $this->get_field_id('single_only'); ?>"><?php _e( 'Show on single article only', 'yeahthemes' ); ?></label>
	    </p>

	    <p><em><?php _e('if the Ads code is empty, display image','yeahthemes')?></em></p>
	    
	    <p>
			<label for="<?php echo $this->get_field_id( 'adscode' ); ?>"><?php _e('Ads code:','yeahthemes')?></label>
			<textarea rows="5" name="<?php echo $this->get_field_name('adscode'); ?>" class="widefat" id="<?php echo $this->get_field_id('adscode'); ?>"><?php echo !empty( $instance['adscode'] ) ? esc_textarea( $instance['adscode'] ) : '';?></textarea>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e('Image:','yeahthemes')?></label>
			<input id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo !empty( $instance['image'] ) ? esc_url( $instance['image'] ) : '';?>" />
			<?php echo !empty( $instance['image'] ) ? sprintf('<img src="%s" class="yt-widget-ads-full-image-preview">', $instance['image'] ) : '';?>
		</p>
	    <p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e('URL:','yeahthemes')?></label>
			<input id="<?php echo $this->get_field_id( 'url' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo !empty( $instance['url'] ) ? esc_url( $instance['url'] ) : '';?>" />
		</p>
	    <p>
			<label for="<?php echo $this->get_field_id( 'alt' ); ?>"><?php _e('Alt text:','yeahthemes')?></label>
			<input id="<?php echo $this->get_field_id( 'alt' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'alt' ); ?>" type="text" value="<?php echo !empty( $instance['alt'] ) ? esc_attr( $instance['alt'] ) : '';?>" />
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
		$instance['single_only'] = $new_instance['single_only'] ? 'on' : 0;
		$instance['adscode'] = $new_instance['adscode'];
		$instance['image'] = esc_url( $new_instance['image'] );
		$instance['url'] = esc_url( $new_instance['url'] );
		$instance['alt'] = esc_attr( $new_instance['alt'] );
		
		return $instance;
	}
}