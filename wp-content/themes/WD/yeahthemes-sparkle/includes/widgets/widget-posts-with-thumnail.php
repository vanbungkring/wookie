<?php
/**
 *	Plugin Name: Posts with thumnail
 *	Description: Display Posts with thumnail
 */
// This is required to be sure Walker_Category_Checklist class is available
//require_once ABSPATH . 'wp-admin/includes/template.php';

class YT_Posts_With_Thubnail_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-posts-with-thumnail-widget yt-widget',
			'description' => __('Display Posts with thumbnail', 'yeahthemes')
		);
		
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-posts-with-thumnail-widget',
		);
		
		parent::__construct( 
			'yt-posts-with-thumnail-widget', 
			__('(Theme) Posts with thumnail', 'yeahthemes'), 
			$widget_ops, 
			$control_ops);
		
		//Then make sure our options will be added in the footer
		add_action('admin_head', array( $this, 'widget_styles'), 10 );
		add_action('admin_print_footer_scripts', array( $this, 'widget_scripts'));

	}
	function widget_styles(){
		$output = '';

		/*Inline css :P */
		$output = str_replace(array("\r", "\n", "\t"), "", $output);

		echo $output . "\n";
		
	}
	function widget_scripts(){
	}
	
	/**
	 * Display Widget
	 */
	function widget( $args, $instance ) {
		
		// outputs the content of the widget

		extract( $args );
		
		// Our variables from the widget settings
		$title = apply_filters('widget_title', $instance['title'] );
		
		echo $before_widget;
		
		//Echo widget title
		if ( $title )
			echo $before_title . $title . $after_title;
		
		$output = '';
		if( function_exists( 'yt_site_post_list') ){
			echo '<div>';
				yt_site_post_list( $instance );
			echo '</div>';
		}
		?>

		<div></div>
		<?php
		
		echo $after_widget;
	}

	/**
	 * Widget Settings
	 */
	function form($instance) {
		
		// Set up some default widget settings
		$defaults = array(
			'title' => __( 'Popular Posts', 'yeahthemes' ),
			'category' => array(),
			'show_icon' => 0,
			'show_cat'	=> 0,
			'show_date' => 0,
			'order' => 'DESC',
			'orderby' => 'comment_count',
			'time_period' => 'default',
			'style' => 'small',
			'tags' => array(),
			'number' => 5,
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		$number   = isset( $instance['number'] ) ? absint( $instance['number'] ) : 6;
		?>
		
		<p><label><?php _e('Title:', 'yeahthemes'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></label></p>
		
		<p><strong><?php _e('Category:','yeahthemes')?></strong></p>
		<p>
		
		<?php
		
		if( class_exists( 'YT_Walker_Category_Checklist' ) ){
			$walker = new YT_Walker_Category_Checklist(
				$this->get_field_name('category'), $this->get_field_id('category')
			);
			echo '<ul class="yt-scrollable-checklist-wrapper">';
				wp_category_checklist( 0, 0, $instance['category'], FALSE, $walker, FALSE);
			echo '</ul>';
		}else{
			$category_list = yt_get_category_list();
		
			foreach( ( array ) $category_list as $cat_id => $cat_name ){
				
				echo sprintf( '<input type="checkbox" class="checkbox" name="%s[]" id="%s" value="%s" %s/> <label for="%s">%s</label><br>',
					esc_attr( $this->get_field_name('category') ),
					esc_attr( $this->get_field_name('category') . '_' . $cat_id ),
					esc_attr( $cat_id ),
					( is_array( $instance['category'] ) && in_array( $cat_id, $instance['category'] ) ? 'checked="checked"' : '' ),
					esc_attr( $this->get_field_name('category') . '_' . $cat_id ),
					$cat_name
					
				);
				
			}
		}

		?>
		</p>
		<p><em><?php _e('Select categories you wish to show, if no categories selected, show all!','yeahthemes')?></em></p>
		<p>
			<label for="<?php echo $this->get_field_id('tags'); ?>"><strong><?php _e( 'Tagged In (optional):', 'yeahthemes' ); ?></strong></label>
			<ul class="yt-scrollable-checklist-wrapper">

			<?php
				foreach ( yt_get_tag_list() as $tag_id => $tag_name ):
			?>
				<li><label><input type="checkbox" name="<?php echo $this->get_field_name('tags'); ?>[]" value="<?php echo esc_attr( $tag_id );?>"<?php echo is_array( $instance['tags'] ) && in_array( $tag_id, $instance['tags'] ) ? ' checked="checked"' : ''; ?>><?php echo $tag_name; ?></label></li>
			<?php
				endforeach;
			?>

			</ul>
		</p>
		<p><em><?php _e('Specify tags to retrieve posts from.','yeahthemes')?></em></p>
		<p>
			<label><strong><?php _e( 'Thumbnail style:', 'yeahthemes' ); ?></strong></label>
			<select name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>" class="widefat">
				<option value="small"<?php selected( $instance['style'], 'small' ); ?>><?php _e('Default (Small)', 'yeahthemes'); ?></option>
				<option value="large"<?php selected( $instance['style'], 'large' ); ?>><?php _e('Large', 'yeahthemes'); ?></option>
				<option value="mixed"<?php selected( $instance['style'], 'mixed' ); ?>><?php _e('First Large', 'yeahthemes'); ?></option>
				<option value="number"<?php selected( $instance['style'], 'number' ); ?>><?php _e('Number (no thumb)', 'yeahthemes'); ?></option>
				<option value="nothumb"<?php selected( $instance['style'], 'nothumb' ); ?>><?php _e('Title Only', 'yeahthemes'); ?></option>
				<option value="thumb_first"<?php selected( $instance['style'], 'thumb_first' ); ?>><?php _e('First item have thumbnail', 'yeahthemes'); ?></option>
			</select></label>
		</p>
		
		<p>
			<label><strong><?php _e( 'Order:', 'yeahthemes' ); ?></strong>
			<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="widefat">
				<option value="DESC"<?php selected( $instance['order'], 'DESC' ); ?>><?php _e('Descending', 'yeahthemes'); ?></option>
				<option value="ASC"<?php selected( $instance['order'], 'ASC' ); ?>><?php _e('Ascending', 'yeahthemes'); ?></option>
			</select></label>
		</p>
		
		<p>
			<label><strong><?php _e( 'Order by:', 'yeahthemes' ); ?></strong>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				
				<option value="date"<?php selected( $instance['orderby'], 'date' ); ?>><?php _e('Date', 'yeahthemes'); ?></option>
				<option value="title"<?php selected( $instance['orderby'], 'title' ); ?>><?php _e('Title', 'yeahthemes'); ?></option>
				<option value="name"<?php selected( $instance['orderby'], 'name' ); ?>><?php _e( 'Post slug' , 'yeahthemes'); ?></option>
				<option value="author"<?php selected( $instance['orderby'], 'author' ); ?>><?php _e( 'Author' , 'yeahthemes'); ?></option>
				<option value="comment_count"<?php selected( $instance['orderby'], 'comment_count' ); ?>><?php _e( 'Number of comments' , 'yeahthemes'); ?></option>
				<option value="modified"<?php selected( $instance['orderby'], 'modified' ); ?>><?php _e( 'Last modified date' , 'yeahthemes'); ?></option>
				<option value="rand"<?php selected( $instance['orderby'], 'rand' ); ?>><?php _e( 'Random order' , 'yeahthemes'); ?></option>
			
				<?php if( function_exists('yt_simple_post_views_tracker_display') ){ ?>
				<option value="meta_value_num"<?php selected( $instance['orderby'], 'meta_value_num' ); ?>><?php _e('Post views', 'yeahthemes'); ?></option>
				<?php } ?>
			</select></label>
		</p>
		<p>
			<label><strong><?php _e( 'Time period:', 'yeahthemes' ); ?></strong>
			<select name="<?php echo $this->get_field_name('time_period'); ?>" id="<?php echo $this->get_field_id('time_period'); ?>" class="widefat">
				<option value="default"<?php selected( $instance['time_period'], 'default' ); ?>><?php _e('Default', 'yeahthemes'); ?></option>
				<option value="this_week"<?php selected( $instance['time_period'], 'this_week' ); ?>><?php _e('This week', 'yeahthemes'); ?></option>
				<option value="last_week"<?php selected( $instance['time_period'], 'last_week' ); ?>><?php _e('Last week', 'yeahthemes'); ?></option>
				<option value="this_month"<?php selected( $instance['time_period'], 'this_month' ); ?>><?php _e('This Month', 'yeahthemes'); ?></option>
				<option value="last_month"<?php selected( $instance['time_period'], 'last_month' ); ?>><?php _e('Last Month', 'yeahthemes'); ?></option>
				<option value="last_30days"<?php selected( $instance['time_period'], 'last_30days' ); ?>><?php _e('Last 30 days', 'yeahthemes'); ?></option>
			</select></label>
		</p>
		<p><label><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" <?php checked($instance['show_icon'], 'on') ?>/> <strong><?php _e('Show views/comment counter', 'yeahthemes'); ?></strong></label></p>
		
		<p><label><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_cat'); ?>" name="<?php echo $this->get_field_name('show_cat'); ?>" <?php checked($instance['show_cat'], 'on') ?>/> <strong><?php _e('Show category tag', 'yeahthemes'); ?></strong></label></p>
		
		<p><label><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" <?php checked($instance['show_date'], 'on') ?>/> <strong><?php _e('Show post date', 'yeahthemes'); ?></strong></label></p>
		
		<p><label><?php _e( 'Number of posts to show:', 'yeahthemes' ); ?> 
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo $number; ?>" size="3" style="width:60px;" /></label></p>
		<?php
	}

	/**
	 * Update Widget
	 */
	function update( $new_instance, $old_instance ) {
		
		// processes widget options to be saved
		$instance = $old_instance;
	
		$new_instance = wp_parse_args((array) $new_instance, array( 
			'title' => '',
			'order' => 'DESC',
			'orderby' => 'date',
			'time_period' => 'default',
			'number' => 0,
			'category' => array(),
			'tags' => array(),
			'style' => 'small',
			'show_icon' => 0,
			'show_cat' => 0,
			'show_date' => 0
		));
		$instance['title'] = $new_instance['title'];
		//Strip tags for title and name to remove HTML 
		
		
		if ( in_array( $new_instance['order'], array( 'DESC', 'ASC' ) ) ) {
			$instance['order'] = $new_instance['order'];
		} else {
			$instance['order'] = 'DESC';
		}
		
		if ( in_array( $new_instance['orderby'], array( 'meta_value_num', 'date', 'title', 'name', 'author', 'comment_count', 'modified', 'rand' ) ) ) {
			$instance['orderby'] = $new_instance['orderby'];
		} else {
			$instance['orderby'] = 'date';
		}
		if ( in_array( $new_instance['time_period'], array( 'default', 'this_week', 'last_week', 'this_month', 'last_month', 'last_30days') ) ) {
			$instance['time_period'] = $new_instance['time_period'];
		} else {
			$instance['time_period'] = 'default';
		}
		
		$instance['category'] = (array) $new_instance['category'];
		$instance['tags'] = (array) $new_instance['tags'];
		$instance['style'] = $new_instance['style'];
		$instance['number'] = (int) $new_instance['number'] == 0 ? 10 : $new_instance['number'];

		$instance['show_icon'] = $new_instance['show_icon'] ? 'on' : 0;
		$instance['show_cat'] = $new_instance['show_cat'] ? 'on' : 0;
		$instance['show_date'] = $new_instance['show_date'] ? 'on' : 0;
		
		return $instance;
	}
}