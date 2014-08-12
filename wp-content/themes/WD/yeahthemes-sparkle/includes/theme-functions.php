<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Theme functions
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeah Includes
 */

 
/**
 * Neccessary setting fields for admin
 */
add_action( 'init', 'yt_site_admin_mega_menu_settings' );

add_filter( 'yt_sidebar_array', 'yt_site_sidebars' );

add_filter( 'wp_nav_menu_objects', 'yt_site_filter_wp_nav_menu_objects', 10, 2 );

/**
 * Sidebars Initialization
 */

if( !function_exists( 'yt_site_sidebars') ) {
	
	function yt_site_sidebars( $sidebars ){
		
		/**
		 * Retrieve Options data
		 */
		$yt_data = yt_get_options();
		
		/* =Sub sidebar */
		$sidebars['sub-sidebar'] = __('Sub Sidebar','yeahthemes');
		
		/* =Footer sidebar */		
		/* get the number of column from theme option */
		$footer_columns = isset( $yt_data['footer_columns'] ) && $yt_data['footer_columns'] ? $yt_data['footer_columns'] : 3; 
		$number_of_sidebar = '';
		
		/* if is numeric number*/
		if( is_numeric( $footer_columns ) ){
			$number_of_sidebar = $footer_columns;
		}
		/* else if is string */
		else{
			
			$footer_col_array = explode('_', $footer_columns );
			$number_of_sidebar = count( ( array ) $footer_col_array );
			
		}
		
		for( $i = 1; $i <= $number_of_sidebar; $i++){
			
			$sidebars['footer-widget-' . $i] = sprintf( __('Footer Widget %s','yeahthemes'), $i );
			
		}
		
		$sidebars = apply_filters( 'yt_site_sidebars', $sidebars );
		
		return $sidebars;
		
	}
}

/*
 * Social sharing
 *
 * @since 1.0
 * @framework 1.0
 */
if( !function_exists( 'yt_site_social_sharing_post') ) {
	
	function yt_site_social_sharing_post( $extra_class = ''){
		
		$extra_class = $extra_class ? ' ' . $extra_class : '';
		$id = get_the_ID();
		$url = get_permalink( $id);
		$title = get_the_title( $id);
		$thumb = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
		
		$i18n = __('Share this on', 'yeahthemes');
		
		$attr = 'data-url="' . esc_url( $url ) . '" data-title="' . esc_attr( $i18n . ' ' . $title ) . '" data-source="' . esc_url( home_url('/') ) . '"';
		
		
		?>
		
		<div class="yt-social-sharing<?php echo $extra_class;?>" <?php echo $attr;?> data-media="<?php echo $thumb;?>">
			
			<strong class="yt-social-sharing-heading hidden-xs hidden-sm hidden-md"><?php _e('Share this','yeahthemes');?></strong>
			<div class="primary-shares">
				<span class="twitter"><?php echo apply_filters('yt_icon_ss_twitter', '<i class="fa fa-twitter"></i>');?> <label class="hidden-xs">Twitter</label></span>
				<span class="facebook"><?php echo apply_filters('yt_icon_ss_facebook', '<i class="fa fa-facebook-square"></i>');?> <label class="hidden-xs">Facebook</label></span>
				<?php do_action('yt_site_social_sharing_primary', $url, $title, $thumb, $i18n );?>
			</div>
			<div class="secondary-shares">
				<span class="google-plus" title="Google+"><?php echo apply_filters('yt_icon_ss_gplus', '<i class="fa fa-google-plus"></i>');?></span>
				<span class="linkedin" title="Linkedin"><?php echo apply_filters('yt_icon_ss_linkedin', '<i class="fa fa-linkedin"></i>');?></span>
				<span class="pinterest" title="Pinterest"><?php echo apply_filters('yt_icon_ss_pinterest', '<i class="fa fa-pinterest"></i>');?></span>
				<span class="tumblr" title="Tumblr"><?php echo apply_filters('yt_icon_ss_tumblr', '<i class="fa fa-tumblr"></i>');?></span>
				<?php do_action('yt_site_social_sharing_secondary', $url, $title, $thumb, $i18n );?>
			</div>
			<span class="show-all-social-services" data-action="show" title="<?php _e('More','yeahthemes');?>"><?php echo apply_filters('yt_icon_ss_expand_collapse', '<i class="fa fa-plus show-services"></i><i class="fa fa-minus hide-services"></i>');?></span>
		</div>
		
		<?php
	}
}

if( !function_exists( 'yt_site_post_meta_description') ) {

	function yt_site_post_meta_description(){
		$meta_info = yt_get_options('blog_post_meta_info');
		//Author
		$author_output = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'yeahthemes' ), get_the_author() ) ),
			get_avatar( get_the_author_meta( 'ID' ), 32 ),
			esc_html( get_the_author() )
		);
		
		//Category
		$categories = get_the_category();
		$categories_output = array();
		
		if($categories){
			foreach($categories as $category) {
				$categories_output[] = '<a class="'.$category->slug.'" href="'. get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'yeahthemes' ), $category->name ) ) . '">'.$category->cat_name.'</a>';
			}
		}
		
		//Time
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
			$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_time('M j, Y') ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date( 'M j, Y' ) )
		);
		
		$date_output = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			$time_string
		);
		
		/**
		 * Output
		 */
		if( in_array( 'author',$meta_info ) || in_array( 'category',$meta_info ) || in_array( 'share_buttons',$meta_info ) ):
		echo '<div class="up clearfix">';
			
			echo in_array( 'author',$meta_info ) ? sprintf( __( '<span class="by-author"><span>by</span> %s</span>', 'yeahthemes' ), $author_output	) : '';
			
		
			echo in_array( 'category',$meta_info ) ? sprintf( __( '&nbsp;<span class="in-cat"><span>in</span> %s</span>', 'yeahthemes' ),	join( $categories_output, ', ' ) ) : '';

			if( in_array( 'share_buttons',$meta_info ) && function_exists('yt_site_social_sharing_post') && !is_search() ){
				yt_site_social_sharing_post('pull-right');
			}
		echo '</div>
		<div class="divider meta-divider clearfix"></div>';
		endif;

		if( in_array( 'date',$meta_info ) || in_array( 'comments',$meta_info ) || in_array( 'likes',$meta_info ) || in_array( 'views',$meta_info ) || in_array( 'sizer',$meta_info ) ):
		echo '<div class="down gray-icon clearfix">';
		
		//echo '<div class="pull-left">';
			echo in_array( 'date',$meta_info ) ? sprintf( '<span class="post-meta-info posted-on">' . apply_filters('yt_icon_date_time', '<i class="fa fa-clock-o"></i>') . ' %1$s</span>',
				$time_string
			) : '';
			if( in_array( 'comments',$meta_info ) ){
				echo '<span class="post-meta-info with-cmt">' . apply_filters('yt_icon_comment', '<i class="fa fa-comments"></i>') . ' ';
					comments_popup_link( __( '0 Comments', 'yeahthemes' ), __( '1 Comment', 'yeahthemes' ), __( '% Comments', 'yeahthemes' ));
				echo '</span>';
			}
			
			if( in_array( 'likes',$meta_info ) && function_exists('yt_impressive_like_display') ){
				echo yt_impressive_like_display(get_the_ID(), false, 'post-meta-info');
			}
			
			if( in_array( 'views',$meta_info ) && function_exists('yt_simple_post_views_tracker_display') ){
			echo '<span class="post-meta-info post-views last-child" title="' . sprintf( __( '%d Views', 'yeahthemes') , number_format( yt_simple_post_views_tracker_display( get_the_ID(), false ) ) ) . '">' . apply_filters('yt_icon_postviews', '<i class="fa fa-eye"></i>') . ' ';
				echo number_format( yt_simple_post_views_tracker_display( get_the_ID(), false ) );
			echo '</span>';	
			}

		//echo '</div>';
		
			if( in_array( 'sizer',$meta_info ) && function_exists( 'yt_theme_font_size_changer') && is_single() ) {
				yt_theme_font_size_changer('pull-right');
			}
		echo '</div>';
		endif;
	}
	
	
}

/**
 * Override Default gallery shortcode
 */
//add_filter( 'post_gallery', 'yt_site_gallery_shortcode', 10 , 2);

function yt_site_gallery_shortcode( $output, $attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => 'li',
		'icontag'    => 'span',
		'captiontag' => 'p',
		'columns'    => 3,
		'size'       => 'large',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery'));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= apply_filters( 'yt_gallery_wp_get_attachment_link', wp_get_attachment_link($att_id, $size, true), $id ) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'div';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'span';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'span';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>";
	$size_class = sanitize_html_class( $size );
	$slider_settings = apply_filters( 'yt_wp_default_gallery_settings', array(
		'selector' => '.slides > li',
		'controlNav' => false,
		'pausePlay' => true,
	), 'slider');
	$gallery_div = "<div id='$selector' data-settings='" . esc_attr( json_encode( $slider_settings ) ) . "' class='yeahslider gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
	$output .= '<ul class="slides">';
	$i = 0;
	
	foreach ( $attachments as $id => $attachment ) {
		if ( ! empty( $link ) && 'file' === $link )
			$image_output = apply_filters( 'yt_gallery_wp_get_attachment_link', wp_get_attachment_link( $id, $size, false, false ), $id );
		elseif ( ! empty( $link ) && 'none' === $link )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = apply_filters( 'yt_gallery_wp_get_attachment_link', wp_get_attachment_link( $id, $size, true, false ), $id );

		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) )
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

		$output .= "<{$itemtag} class='gallery-item" . ( $captiontag && trim($attachment->post_excerpt) ? ' wp-caption' : '') . "'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
	}

	$output .= "
			</ul>
		</div>\n";

	return $output;
}


//add_filter( 'jp_carousel_force_enable', function(){return true;});
//add_filter( 'yt_gallery_wp_get_attachment_link', 'yt_site_add_data_to_gallery_images' ,10, 2);
 
if ( ! function_exists( 'yt_site_add_data_to_gallery_images' ) ) {
	function yt_site_add_data_to_gallery_images( $html, $attachment_id ) {
		
		//if ( $this->first_run ) // not in a gallery
			//return $html;

		$attachment_id   = intval( $attachment_id );
		$orig_file       = wp_get_attachment_image_src( $attachment_id, 'full' );
		$orig_file       = isset( $orig_file[0] ) ? $orig_file[0] : wp_get_attachment_url( $attachment_id );
		$meta            = wp_get_attachment_metadata( $attachment_id );
		$size            = isset( $meta['width'] ) ? intval( $meta['width'] ) . ',' . intval( $meta['height'] ) : '';
		$img_meta        = ( ! empty( $meta['image_meta'] ) ) ? (array) $meta['image_meta'] : array();
		$comments_opened = intval( comments_open( $attachment_id ) );

		/*
		 * Note: Cannot generate a filename from the width and height wp_get_attachment_image_src() returns because
		 * it takes the $content_width global variable themes can set in consideration, therefore returning sizes
		 * which when used to generate a filename will likely result in a 404 on the image.
		 * $content_width has no filter we could temporarily de-register, run wp_get_attachment_image_src(), then
		 * re-register. So using returned file URL instead, which we can define the sizes from through filename
		 * parsing in the JS, as this is a failsafe file reference.
		 *
		 * EG with Twenty Eleven activated:
		 * array(4) { [0]=> string(82) "http://vanillawpinstall.blah/wp-content/uploads/2012/06/IMG_3534-1024x764.jpg" [1]=> int(584) [2]=> int(435) [3]=> bool(true) }
		 *
		 * EG with Twenty Ten activated:
		 * array(4) { [0]=> string(82) "http://vanillawpinstall.blah/wp-content/uploads/2012/06/IMG_3534-1024x764.jpg" [1]=> int(640) [2]=> int(477) [3]=> bool(true) }
		 */

		$medium_file_info = wp_get_attachment_image_src( $attachment_id, 'medium' );
		$medium_file      = isset( $medium_file_info[0] ) ? $medium_file_info[0] : '';

		$large_file_info  = wp_get_attachment_image_src( $attachment_id, 'large' );
		$large_file       = isset( $large_file_info[0] ) ? $large_file_info[0] : '';

		$attachment       = get_post( $attachment_id );
		$attachment_title = wptexturize( $attachment->post_title );
		$attachment_desc  = wpautop( wptexturize( $attachment->post_content ) );

		// Not yet providing geo-data, need to "fuzzify" for privacy
		if ( ! empty( $img_meta ) ) {
			foreach ( $img_meta as $k => $v ) {
				if ( 'latitude' == $k || 'longitude' == $k )
					unset( $img_meta[$k] );
			}
		}

		$img_meta = json_encode( array_map( 'strval', $img_meta ) );

		$html = str_replace(
			'<img ',
			sprintf(
				'<img data-attachment-id="%1$d" data-orig-file="%2$s" data-orig-size="%3$s" data-comments-opened="%4$s" data-image-meta="%5$s" data-image-title="%6$s" data-image-description="%7$s" data-medium-file="%8$s" data-large-file="%9$s" ',
				$attachment_id,
				esc_attr( $orig_file ),
				$size,
				$comments_opened,
				esc_attr( $img_meta ),
				esc_attr( $attachment_title ),
				esc_attr( $attachment_desc ),
				esc_attr( $medium_file ),
				esc_attr( $large_file )
			),
			$html
		);

		$html = apply_filters( 'jp_carousel_add_data_to_images', $html, $attachment_id );

		return $html;
	}
}

/**
 * YT_Walker_Nav_Mega_Menu_By_Category copied from YT_Walker_Nav_Menu
 * Mega menu news by Category with icon (description)
 *
 * @package Includes
 * @since 1.0.0
 */
class YT_Walker_Nav_Mega_Menu_By_Category extends YT_Walker_Nav_Menu{
	
	var $megamenu_checker = false;
	var $widget_checker = false;
	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$extra_class = 'sub-menu';
		if( $this->megamenu_checker && 0 == $depth )
			$extra_class = 'row';
		
		
		/*if( $depth == 1 && $this->megamenu_checker )
			$extra_class = '';
*/			
		$indent = str_repeat("\t", $depth);
		
		$sub_menu_wrapper = "\n$indent<ul class=\"$extra_class\">\n";
		
		
		if( $depth == 1 && $this->widget_checker ){
			$sub_menu_wrapper = '';
		}
		
		$output .= $sub_menu_wrapper;
	}
	

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$sub_menu_wrapper = "$indent</ul>\n";
		
		if( $depth == 1 && $this->widget_checker ){
			$sub_menu_wrapper = '';
			$this->widget_checker = false;
		}
		$output .= $sub_menu_wrapper;
		
		/**
		 * Increase level of submenu
		 * @since 1.0.1
		 */
		if( $depth > 3 ){
			$this->megamenu_checker = false;
		}
	}
	

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$data_cat = '';
		//print_r($item);
		/*Assigns data-dats*/
		if( $depth == 0 ){
			
			/*Append Parent menu item object_id to data-cats*/
			if( $item->object == 'category' ){
				//$data_cat .= $item->object_id . ','; 
			}
			
			if( !empty( $item->data_cat ) ){
				$data_cat .= $item->data_cat;
			}
			
			/*Check if is Default mega menu*/
			if( !empty( $item->mega_menu ) && 'default' == $item->mega_menu ){
				$this->megamenu_checker = true;
			}
		
			if( empty( $item->mega_menu ) ){
				$this->megamenu_checker = false;
			}

		}
		
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		
		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		
		
		if( $depth == 1 && $this->megamenu_checker ){
			$classes = array();
			$classes[] = $item->mega_menu_columns;
		}
		
		if( $depth == 2 && $this->megamenu_checker ){
			//$classes = array();
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		
		$data_cat_output = '';
		if( $depth == 0 && !empty( $item->mega_menu ) && 'news' == $item->mega_menu && $data_cat ){
			$data_cat_output = ' data-cats="' . esc_attr( $data_cat ) . '"';
		}
		$menu_item_wrapper = $indent . '<li' . $id . $value . $class_names . $data_cat_output . '>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		$atts['description']   = ! empty( $item->description ) ? $item->description : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) && 'description' !== $attr) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		
		//print_r($item);

		$item_output = $args->before;
		$item_output .= !empty( $atts['href'] ) ? '<a'. $attributes .'>' : '';
		
		$submenus = array();
		//Menu parent sign
		if( 'indicator' == $this->description_type ){
			$submenus = get_posts( array( 
				'post_type' => 'nav_menu_item', 
				'numberposts' => 1, 
				'meta_query' => array( 
					array( 
						'key' => '_menu_item_menu_item_parent', 
						'value' => $item->ID, 
						'fields' => 'ids' ) 
					) 
				) 
			);
		}
		/*Menu indicator*/
		$description = wp_parse_args( 
			apply_filters( 'yt_walker_nav_menu_description', array(), $atts ),
			array(
				'before' => '',
				'after' => '',
				'parent' => '',
				'children' => '',
			)
		);
		/*Menu indicator position*/
		$position = apply_filters( 'yt_walker_nav_menu_description_position' ,'before', $submenus, $depth );
		
		/*Prepend*/
		if( $position == 'after' ){
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		}
		
		if( 'indicator' == $this->description_type ){
			$item_output .= ! empty( $submenus ) ? ( 0 == $depth ? $description['parent'] : $description['children'] ) : '';
		}else{
			$item_output .= $description['before'] . ( 0 == $depth ? $description['parent'] : $description['children'] ) . $description['after'];
		}
		
		/*Append*/
		if( $position == 'before' ){
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		}
		
		
       $item_output .= !empty( $atts['href'] ) ? '</a>' : '';
	   /*Begin Mega Menu container*/
	   if( $depth == 0 && !empty( $item->mega_menu ) ){
			$item_output .= '<div class="full-width-wrapper mega-menu-container">
				<div class="container">';
				
			$item_output .= apply_filters( 'yt_mega_menu_content', '', $item->mega_menu, $data_cat, $depth, $atts );
	   }
		$item_output .= $args->after;
		
		
		
		$depth_title = apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		if( $depth == 1 && $this->megamenu_checker && empty( $atts['href'] ) ){
			$depth_title = '<span>' . apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ) . '</span>';
		}
		if( $depth == 1 && $this->megamenu_checker && !empty( $item->sidebar ) ){
			
			$depth_title = yt_get_ob_content( 'dynamic_sidebar', $item->sidebar );
			$this->widget_checker = true;
		
		}
		/*Remove child if its parent is a sidebar*/
		if( $depth == 2 && $this->widget_checker ){
			$menu_item_wrapper = '';
			$depth_title = '';
		}
		
		$output .= $menu_item_wrapper . $depth_title;
	}
	
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		
		$data_cat = '';
		if( $depth == 0 ){
			/*End Mega Menu container*/
			if(!empty( $item->mega_menu )/* && !empty( $item->data_cat ) */){
				
				//do_action( 'yt_mega_menu_end', $item->mega_menu, $data_cat, $depth );
				
				$output .= '</div>
					</div>';
			}
		}
		$menu_item_wrapper = "</li>\n";
		
		if( $depth == 2 && $this->widget_checker ){
			$menu_item_wrapper = '';
		}
		
		$output .= $menu_item_wrapper;
	}
}

/**
 * Extended from YT_Walker_Nav_Menu_Fields to add custom css for menu level
 *
 * @package Includes
 * @since 1.0.0
 */
class YT_Site_Walker_Nav_Menu_Fields extends YT_Walker_Nav_Menu_Fields{
	
	public function admin_print_scripts(){
		parent::admin_print_scripts();
		global $pagenow, $typenow;
	
		if ( empty( $typenow ) && !empty( $_GET['post'] ) ) {
			$post = get_post( $_GET['post'] );
			$typenow = $post->post_type;
		}
		
		if ( (is_admin() && $pagenow == 'nav-menus.php' ) ) {
			$css = '<style type="text/css">
				/*Custom css for menu nav*/
				.yt-custom-menu-fields-wrapper span.description{
					display:block;
				}
				.field-mega_menu,
				.field-mega_menu_columns,
				.field-sidebar {
					display: none;
				}
				.menu-item-depth-0 .field-mega_menu,
				.menu-item-depth-1 .field-mega_menu_columns,
				.menu-item-depth-1 .field-sidebar {
					display: block;
				}
				.yt-custom-menu-fields-wrapper select{
					width:190px;
					clear:right;
				}
			</style>';
 			$css = str_replace(array("\r", "\n", "\t"), "", $css);
			printf( $css . "\n" );
		}
	}
}

/**
 * Init custom field for nav menu item
 *
 * @package Includes
 * @since 1.0.0
 */

if( !function_exists( 'yt_site_admin_mega_menu_settings') ) {
	
	function yt_site_admin_mega_menu_settings(){
		
		new YT_Site_Walker_Nav_Menu_Fields(array(
			array( 
				'name' => __( 'Mega menu', 'yeahthemes'),
				'id' => 'mega_menu',
				'desc' => __( 'Enable Mega menu and choose style (Default or News by category).', 'yeahthemes'),
				'type' => 'select',
				'options' => array(
					'' => __( 'Off', 'yeahthemes'),
					'default' => __( 'Default', 'yeahthemes'),
					'news' => __( 'News by category', 'yeahthemes'),
				),
				
			),
			array( 
				'name' => __( 'Mega menu Columns', 'yeahthemes'),
				'id' => 'mega_menu_columns',
				'desc' => __( 'Choose column size (Only apply to Default Mega menu)', 'yeahthemes'),
				'type' => 'select',
				'std' => 'yt-col-1-4',
				'options' => array(
					'col-md-1' => 'col-md-1',
					'col-md-2' => 'col-md-2',
					'col-md-3' => 'col-md-3',
					'col-md-4' => 'col-md-4',
					'col-md-5' => 'col-md-5',
					'col-md-6' => 'col-md-6',
					'col-md-7' => 'col-md-7',
					'col-md-8' => 'col-md-8',
					'col-md-9' => 'col-md-9',
					'col-md-10' => 'col-md-10',
					'col-md-11' => 'col-md-11',
					'col-md-12' => 'col-md-12',
					'yt-col-1-2' => __( '1/2', 'yeahthemes'),
					'yt-col-1-3' => __( '1/3', 'yeahthemes'),
					'yt-col-1-4' => __( '1/4', 'yeahthemes'),
					'yt-col-1-5' => __( '1/5', 'yeahthemes'),
					'yt-col-1-6' => __( '1/6', 'yeahthemes'),
					'yt-col-2-3' => __( '2/3', 'yeahthemes'),
					'yt-col-2-5' => __( '2/5', 'yeahthemes'),
					'yt-col-3-4' => __( '3/4', 'yeahthemes'),
					'yt-col-3-5' => __( '3/5', 'yeahthemes'),
					'yt-col-4-5' => __( '4/5', 'yeahthemes'),
					'yt-col-5-6' => __( '5/6', 'yeahthemes'),
				),
			),
			array( 
				'name' => __( 'Display this menu as a sidebar', 'yeahthemes'),
				'id' => 'sidebar',
				'desc' => ''/*__( 'Add your sidebar to megamenu ( Only apply to Default mega menu)', 'yeahthemes')*/,
				'type' => 'select',
				'options' => array_merge( array( '' => __('Select a sidebar', 'yeahthemes')), yt_get_registered_sidebars() )
			)
			
		), 'YT_Walker_Nav_Menu_Edit');
	}
}
/**
 * Modify wp_nav_menu_objects to add child categories
 *
 * @package Includes
 * @since 1.0.0
 */

if( !function_exists( 'yt_site_filter_wp_nav_menu_objects') ) {
	function yt_site_filter_wp_nav_menu_objects( $sorted_menu_items, $args ){
		
		if($args->theme_location !== 'primary')
			return $sorted_menu_items;
		$menu_parent = array();
		$menu_tree = array();
		$menu_items_with_children = array();
		foreach ( $sorted_menu_items as $menu_item ) {
			if( $menu_item->menu_item_parent == 0 ){
				$menu_parent[] = $menu_item->ID;
				
				if( !empty( $menu_item->mega_menu ) ){
					$menu_item->classes[] = 'mega-menu-dropdown';
					$menu_item->classes[] = 'mega-menu-dropdown-' . $menu_item->mega_menu;
				}
					
			}elseif( in_array( $menu_item->menu_item_parent, $menu_parent) && $menu_item->object == 'category' ){
				$menu_tree[$menu_item->menu_item_parent][] = $menu_item->object_id;
			}
			
			if ( $menu_item->menu_item_parent )
				$menu_items_with_children[ $menu_item->menu_item_parent ] = true;
					
		}
		foreach ( $sorted_menu_items as $menu_item ) {
			//echo $menu_item->ID . "\n";
			if( $menu_item->menu_item_parent == 0 ){
				$menu_item->data_cat = !empty( $menu_tree[$menu_item->ID] ) ? join(',', $menu_tree[$menu_item->ID] ) : '';
			}
			if ( empty( $menu_item->mega_menu ) && $menu_items_with_children && isset( $menu_items_with_children[ $menu_item->ID ] ) )
				$menu_item->classes[] = 'default-dropdown';
		}
		
		$new_items = array();
		
		for ( $i = 1; $i < count( $sorted_menu_items ) + 1; $i++ ){
			//is lvl0
			if( empty( $sorted_menu_items[$i]->menu_item_parent ) ){
			   $new_items = array_merge( $new_items, yt_site_filter_wp_nav_menu_objects_helper( $sorted_menu_items[$i], $sorted_menu_items ) );
			}
		} 
		// var_dump($new_items); die();
		if( $args->theme_location == 'primary' )
			return $new_items;
			
		//print_r($x_parent);
		//print_r($x_tree);
		// print_r($sorted_menu_items);
		
		return $sorted_menu_items;
	}
}

if( !function_exists( 'yt_site_filter_wp_nav_menu_objects_helper') ) {
	function yt_site_filter_wp_nav_menu_objects_helper( $parent, $items ){
		$rtn = array();
		$rtn[] = $parent;
		if( !empty( $parent->mega_menu ) && $parent->mega_menu == 'news' ) return $rtn;
		for ( $i=1; $i < count( $items ) + 1; $i++ ){
			if( $items[$i]->menu_item_parent && $items[$i]->menu_item_parent == $parent->ID ){
				$rtn = array_merge( $rtn, yt_site_filter_wp_nav_menu_objects_helper( $items[$i], $items ));
			}
		}
		return $rtn;
	}
}


if( !function_exists( 'yt_site_post_list') ) {
	function yt_site_post_list( $instance = array() ){
		

		global $post;

		$cat_ids = array();

		if( !empty( $instance['category'] ) && is_array( $instance['category'] ) ){
			$cat_ids = $instance['category'];
		}else{
			$cat_ids = array_keys( yt_get_category_list() );
		}
		?>
		<ul class="post-list post-list-with-thumbnail<?php echo esc_attr( 'number' == $instance['style'] ? ' number-style' : '' );?> secondary-2-primary vertical">
		<?php
			$args = array( 
				'posts_per_page' 	=> isset( $instance['number'] ) ? absint( $instance['number'] ) : 10,
				'cat'				=> join(',', $cat_ids ),
				'tag__in'			=> !empty( $instance['tags'] ) && is_array( $instance['tags'] ) ? $instance['tags'] : array(),
				'post_type' 		=> array( 'post' ),
				'order'				=> $instance['order'],
 				'orderby' 			=> $instance['orderby'],
			);

			if( 'meta_value_num' == $instance['orderby'] ){
				$args['meta_key'] = apply_filters( 'yt_simple_post_views_tracker_meta_key', '_post_views' );
				$args['meta_value_num'] = '0';
				$args['meta_compare'] = '>';
			}

			if(!empty( $GLOBALS['yt_listed_posts'] ) && apply_filters( 'yt_avoid_duplicated_posts', false ) ){
				$args['post__not_in'] = $GLOBALS['yt_listed_posts'];
			}

			if( is_singular('post' ) ){
				$args['post__not_in'][] = get_the_ID();
			}
			/*Date Parameters*/
			if( 'default' !== $instance['time_period'] ){
				
				$this_year = date('Y');
				$this_month = date('m');
				$this_week = date('W');

				if( 'this_week' == $instance['time_period'] ){
					$args['date_query'] = array(
						array(
							'year' => $this_year,
							'week' => $this_week,
						),
					);
				}elseif( 'last_week' == $instance['time_period'] ){

					if ( $this_week != 1 )
						$lastweek = $this_week - 1;
					else
						$lastweek = 52;

					if ($lastweek == 52)
						$this_year = $this_year - 1;

					$args['date_query'] = array(
						array(
							'year' => $this_year,
							'week' => $lastweek,
						),
					);
				}elseif( 'this_month' == $instance['time_period'] ){

					$args['date_query'] = array(
						array(
							'year' => $this_year,
							'month' => $this_month,
						),
					);
				}elseif( 'last_month' == $instance['time_period'] ){
					if ( $this_month != 1 )
						$this_month = $this_month - 1;
					else
						$this_month = 12;

					if ($this_month == 12)
						$this_year = $this_year - 1;

					$args['date_query'] = array(
						array(
							'year' => $this_year,
							'month' => $this_month,
						),
					);

					//yt_pretty_print( $args['date_query'] ); die();
				}elseif( 'last_30days' == $instance['time_period'] ){
					$args['date_query'] = array(
						array(
							'after'     => date('F j, Y', strtotime('today - 30 days')),
							'before'    => date('F j, Y'),
							'inclusive' => true,
						),
					);
				}
			}

			if( !empty( $instance['exclude_format'] ) && $instance['exclude_format'] ){
				$exclude_format_temp = array();
				foreach( $instance['exclude_format'] as $format ){
					$exclude_format_temp[] = "post-format-$format";
				}

				$args['tax_query'] = array(
				    array(
				      'taxonomy' 	=> 'post_format',
				      'field' 		=> 'slug',
				      'terms' 		=> $exclude_format_temp,
				      'operator' 	=> 'NOT IN'
				    )
				);
			}

			
			$temp_post = $post;
			
			$myposts = get_posts( apply_filters( 'yt_posts_with_thumnail_widget_query', $args ) );

			$image_size = $instance['style'];

			static $count = 0;
			//print_r($args);
			foreach ( $myposts as $post ) : 
				setup_postdata( $post );

				
				$count++;

				$GLOBALS['yt_listed_posts'][] = get_the_ID();
				$categories = get_the_category();
				$cat_tag 			= '';
			
				if( !empty( $instance['show_cat'] ) && !empty( $categories[0] ) && apply_filters( 'yt_posts_with_thumnail_widget_cat', true ) ){
					$category 	= $categories[0];
					$cat_tag 	.= '<span class="cat-tag ' . esc_attr( $category->slug ) . '">'.$category->cat_name.'</span>';
					
				}
			?>
				<li data-id="<?php the_ID(); ?>"<?php echo 'large' == $instance['style'] || ( in_array( $instance['style'], array( 'thumb_first', 'mixed' ) ) && 1 == $count ) ? ' class="post-with-large-thumbnail"' : '';?>>
					<article <?php post_class( );?>>
						<?php if( in_array( $instance['style'], array( 'small','nothumb', 'number') ) || ( ( in_array( $instance['style'], array( 'mixed', 'thumb_first') ) ) && 1 !== $count ) ){?>
						<span class="entry-meta clearfix">
							<?php if( !empty( $instance['show_date'] ) ){?>
							<time class="entry-date published pull-left" datetime="<?php echo esc_attr( get_the_time('c') ); ?>">
								<?php echo is_singular( 'post' ) && !empty( $temp_post->ID ) && $post->ID == $temp_post->ID ? __('Reading Now','yeahthemes') : get_the_time('M j, Y');?>
							</time>
							<?php }?>
							<?php

							if( !empty( $instance['show_icon'] ) ){
								if( 'meta_value_num' == $instance['orderby'] && function_exists('yt_simple_post_views_tracker_display') ){
								echo '<span class="small gray-icon post-views pull-right" title="' . sprintf( __( '%d Views', 'yeahthemes') , number_format( yt_simple_post_views_tracker_display( get_the_ID(), false ) ) ) . '">' . apply_filters('yt_icon_postviews', '<i class="fa fa-eye"></i>') . ' ';
									number_format( yt_simple_post_views_tracker_display( get_the_ID() ) );
								echo '</span>';	
								}else{
								echo '<span class="small gray-icon with-cmt pull-right">' . apply_filters('yt_icon_comment', '<i class="fa fa-comments"></i>') . ' ';
									comments_number( __( '0', 'yeahthemes' ), __( '1', 'yeahthemes' ), __( '%', 'yeahthemes' ));
								echo '</span>';
								}
							}
							
							?>
						</span>
						<?php
						}

						if( !in_array( $instance['style'], array( 'number', 'nothumb') ) && has_post_thumbnail() && get_the_post_thumbnail() ) :?>
							<?php if( ( 'thumb_first' == $instance['style'] && 1 == $count ) || in_array( $instance['style'], array( 'small', 'large', 'mixed' ) )):?>
							<div class="post-thumb<?php echo 'thumb_first' == $instance['style'] && 1 == $count || 'large' == $instance['style'] || ( 'mixed' == $instance['style'] && 1 == $count ) ? ' large' : '';?>">
								<?php echo $cat_tag;?>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
							</div>
						<?php 
							endif;
						endif;?>
						
						<?php 

						if( 'large' == $instance['style'] || ( in_array($instance['style'] , array( 'thumb_first', 'mixed') ) && 1 == $count ) ){?>
							<span class="entry-meta clearfix">
								<?php if( !empty( $instance['show_date'] ) ){?>
								<time class="entry-date published pull-left" datetime="<?php echo esc_attr( get_the_time('c') ); ?>">
									<?php echo is_singular( 'post' ) && !empty( $temp_post->ID ) && $post->ID == $temp_post->ID ? __('Reading Now','yeahthemes') : get_the_time('M j, Y');?>
								</time>
								<?php }?>
								<?php

								if( !empty( $instance['show_icon'] ) ){
									if( 'meta_value_num' == $instance['orderby'] && function_exists('yt_simple_post_views_tracker_display') ){
									echo '<span class="small gray-icon post-views pull-right" title="' . sprintf( __( '%d Views', 'yeahthemes') , number_format( yt_simple_post_views_tracker_display( get_the_ID(), false ) ) ) . '">' . apply_filters('yt_icon_postviews', '<i class="fa fa-eye"></i>') . ' ';
										echo number_format( yt_simple_post_views_tracker_display( get_the_ID(), false ) ) ;
									echo '</span>';	
									}else{
									echo '<span class="small gray-icon with-cmt pull-right">' . apply_filters('yt_icon_comment', '<i class="fa fa-comments"></i>') . ' ';
										comments_number( __( '0', 'yeahthemes' ), __( '1', 'yeahthemes' ), __( '%', 'yeahthemes' ));
									echo '</span>';
									}
								}
								
								?>
							</span>
						<?php
						}

						if( ( in_array($instance['style'] , array( 'thumb_first', 'mixed') ) ) && 1 == $count){?>
							<h2 class="secondary-2-primary">
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>">
								<strong><?php the_title(); ?></strong>
							</a>
							</h2>
						<?php
							if( 'large' !== $instance['style'] ){
								$excerpt = get_the_excerpt();
								$excerpt_length = !empty( $instance['excerpt_length'] ) ? absint( $excerpt_length ) : 20;
								$excerpt_length = $excerpt_length ? $excerpt_length : 20;
								$trimmed_excerpt = wp_trim_words( $excerpt, $excerpt_length, '...');
								echo sprintf('<span>%s</span>', $trimmed_excerpt );

							}
						}else{
						?>
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>">
								<?php echo  'number' == $instance['style'] ? '<span class="gray-2-secondary number">' . ( $count < 10 ? '0'. $count : $count ) . '</span>' : '' ;?>
								<strong><?php the_title(); ?></strong>
							</a>
						<?php
						}?>
					</article>
				</li>
			<?php
			endforeach;
			$count = 0;
			wp_reset_postdata();
			
			$post = $temp_post;
			//var_dump( $GLOBALS['yt_listed_posts']);
		?>
			
		
		</ul>
		<?php
	}
}

add_filter( 'nav_menu_css_class', 'yt_main_menu_class', 10, 3 );
function yt_main_menu_class( $classes, $item, $args){
	if( $item->object == 'category'){
		$current_cat = get_category($item->object_id);
		
		$classes[] = $current_cat->slug;
	}

	return $classes;
}