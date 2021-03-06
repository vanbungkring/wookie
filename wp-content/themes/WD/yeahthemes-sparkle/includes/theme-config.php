<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Theme config
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeah Includes
 */
 

/**********************************************************************************************************
 * 
 * Do not edit this file.
 * To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder.
 * 
***********************************************************************************************************/


/**
 * yeahthemes functions and definitions
 *
 * @package yeahthemes
 */


/**
 * Init Category custom fields
 */
$GLOBALS['yt_category_fields'] = new YT_Category_Custom_Fields();


/**
 * Disable theme customizer
 *
 * @package yeahthemes
 */		
add_filter( 'yt_support_theme_customizer', '__return_false' );

/*---------------------------------------------------------------------------------------------------------*
 * Setup
 *---------------------------------------------------------------------------------------------------------*/
add_action( 'after_setup_theme', 'yt_theme_setup' );

if ( ! function_exists( 'yt_theme_setup' ) ) {
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
	function yt_theme_setup() {

		$yt_data = yt_get_options();
	
		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on yeahthemes, use a find and replace
		 * to change 'yeahthemes' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'yeahthemes', get_template_directory() . '/languages' );
	
		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );
	
		/**
		 * Enable support for Post Thumbnails on posts and pages
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		if( !empty( $yt_data['allow_overwrite_media_size'] ) ){

			set_post_thumbnail_size( 710, 434 , true);

			update_option( 'thumbnail_size_w', 150 );
			update_option( 'thumbnail_size_h', 150 );
			if(false === get_option('thumbnail_crop')) {
			    add_option('thumbnail_crop', 1);
			} else {
			    update_option('thumbnail_crop', 1);
			}

			update_option( 'medium_size_w', 320 );
			update_option( 'medium_size_h', 190 );
			if(false === get_option('medium_crop')) {
			    add_option('medium_crop', 1);
			} else {
			    update_option('medium_crop', 1);
			}
		
			
		}

		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary' => __( 'Main Menu', 'yeahthemes' ),
			'mobile' => __( 'Mobile Menu', 'yeahthemes' ),
			'top' => __( 'Top Menu', 'yeahthemes' ),
		) );
		/**
		 * Enable support for Post Formats
		 */
		add_theme_support( 'post-formats', array( 'image', 'gallery', 'audio', 'video', 'quote', 'link', ) );
	
		/**
		 * Setup the WordPress core custom background feature.
		 */
		
		/**
		 * Jetpack Infinite Scroll Compatibility
		 */
		add_theme_support( 'infinite-scroll', array(
			'container' => 'content',
			'footer'    => false,
			'footer_callback'    => false,
			'wrapper' => false,
			'posts_per_page' => get_option('posts_per_page')
		) );

		
		add_filter( 'use_default_gallery_style', '__return_false' );

		add_filter( 'wp_page_menu_args', 'yt_page_menu_args' );

		add_filter( 'yt_pagination_nav_paginate_links_args', create_function( '$args', '$args["mid_size"] = 1; return $args;') );

		add_filter('yt_theme_meta_viewport_conditions', '__return_true' );

		add_filter( 'yt_admin_top_menu_bar_support_url', create_function( '', 'return "http://yeahthemes.com/support/forum/wordpress-themes-support-center/sparkle/";') );
		add_filter( 'yt_admin_top_menu_bar_document_url', create_function( '', 'return "http://yeahthemes.com/assets/sparkle/documentation";') );



		
	}
}
/**
 * Register plugins
 */
include_once( get_template_directory() . '/framework/extended/class.tgm-plugin-activation.php');

function yt_site_register_required_plugins(){
	/**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => true,
        ),
        array(
            'name'      => 'bbPress',
            'slug'      => 'bbpress',
            'required'  => false,
        ),
        array(
            'name'      => 'Liveblog',
            'slug'      => 'liveblog',
            'required'  => false,
        ),
        array(
            'name'      => 'WooCommerce',
            'slug'      => 'woocommerce',
            'required'  => false,
        ),
        array(
            'name'      => 'WP Review',
            'slug'      => 'wp-review',
            'required'  => false,
        ),
        

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'yeahthemes' ),
            'menu_title'                      => __( 'Install Plugins', 'yeahthemes' ),
            'installing'                      => __( 'Installing Plugin: %s', 'yeahthemes' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'yeahthemes' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'yeahthemes' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'yeahthemes' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'yeahthemes' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'yeahthemes' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'yeahthemes' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'yeahthemes' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'yeahthemes' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'yeahthemes' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'yeahthemes' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'yeahthemes' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'yeahthemes' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'yeahthemes' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'yeahthemes' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'yt_site_register_required_plugins' );

/**
 * Change nonce life to 1 hour
 */
//add_filter('nonce_life', 'yt_change_nonce_hourly');
function yt_site_change_nonce_hourly( $nonce_life ) {
    return 60*60;
}
/**
 * Change the smallest size of default Tagcloud args
 *
 * @since 1.0
 */

add_filter( 'widget_tag_cloud_args', 'yt_site_widget_tag_cloud_args', 10, 2 );

if ( ! function_exists( 'yt_site_widget_tag_cloud_args' ) ) {
	function yt_site_widget_tag_cloud_args( $args ) {

		$args['smallest'] = 14;
		$args['largest'] = 28;
		$args['unit']    = 'px';

		return $args;  
	}

}

add_filter( 'infinite_scroll_archive_supported', 'yt_site_infinite_scroll_archive_supported', 19,1 );
/**
 * Infinite scroll supported archive
 *
 * @since 1.0
 */
function yt_site_infinite_scroll_archive_supported( $supported ) {

	global $wp_query;

	
	$supported = current_theme_supports( 'infinite-scroll' ) && ( ( is_home() || is_archive() ) && !yt_is_bbpress() && !yt_is_woocommerce() );

    return $supported;
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */

if ( ! function_exists( 'yt_site_page_menu_args' ) ) {
	function yt_site_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}
}


/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0
 */
add_filter( 'yt_frontend_body_class', 'yt_site_body_classes', 10 );

if ( ! function_exists( 'yt_site_body_classes' ) ) {
	function yt_site_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author
		if( 'hide' == yt_get_options('blog_post_format_icon') ){
			$classes[] = 'hide-post-format-icon';
		}
		if( yt_get_options( 'site_boxed_layout_mode' ) )
			$classes[] = 'boxed-layout';

		if( yt_get_options( 'site_width_large_mode' ) )
			$classes[] = 'large-display-layout';
		
		if( yt_get_options( 'header_scrollfix_mainmenu' ) )
			$classes[] = 'scroll-fix-header';

		return $classes;
	}
}

/**
 * Replaces the excerpt "more" text by a link
 *
 * @since 1.0
 */
add_filter( 'the_content_more_link', 'yt_site_new_excerpt_more_content' );

if ( ! function_exists( 'yt_site_new_excerpt_more_content' ) ) {
	function yt_site_new_excerpt_more_content($more) {
		
		global $post;

		if( 'hide' === yt_get_options('blog_readmore_button'))
			return '';
		
		if( is_search() ){	
			return '';
		
		}else{
			
			return '<a class="more-tag btn btn-default btn-lg margin-top-15" href="'. get_permalink($post->ID) . '"> ' . __('Read More...','yeahthemes') . '</a>';
		}
	}
}

add_filter('excerpt_more', 'yt_site_new_excerpt_more');
if ( ! function_exists( 'yt_site_new_excerpt_more' ) ) {
	function yt_site_new_excerpt_more($more) {
		return '';
	}
}
add_filter( 'excerpt_length', 'yt_site_excerpt_length', 999 );
/**
 * Excerpt length
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_excerpt_length' ) ) {
	function yt_site_excerpt_length( $length ) {
		$length = yt_get_options( 'custom_excerpt_length' ) ? yt_get_options( 'custom_excerpt_length' ) : 30;
		return (int) $length;
	}
}

add_action( 'pre_get_posts', 'yt_site_pre_get_posts');
/**
 * Modify main query
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_pre_get_posts' ) ) {
	function yt_site_pre_get_posts($query){

		//////////////Exclude Categories
		$exclude_cats = yt_get_options( 'mainblog_exclude_cats' );
		
		//print_r($exclude_cats);
		//$exclude_cats = $exclude_cats ? join( ",", $exclude_cats ) : '';
		
		
		/*if(is_array($exclude_cats)){
			$exclude_cats_temp = array();
			foreach ( $exclude_cats as $cat) {
				if($cat)
					$exclude_cats_temp[] = '-'.$cat;
			}
			$exclude_cats = join( ",", $exclude_cats_temp );	
		}*/

		$exclude_cats = array_filter( ( array ) $exclude_cats );

		if( !empty( $exclude_cats  ) ){
			//array_walk( $exclude_cats , create_function('&$value, $key', '$value = "-$value";') );
			 
			//echo $exclude_cats;
			if ( $query->is_home && $query->is_main_query() ) {
				$query->set( 'category__not_in', $exclude_cats );
			}
		}

		//yt_pretty_print( $query ); die();
		
		if( $query->is_search ){
			 
		}
		if( $query->is_home && $query->is_main_query() && get_option('page_for_posts' ) && get_post_meta( get_option('page_for_posts' ), 'yt_page_herobanner_excludeme_from_mainquery', true ) && function_exists( 'yt_site_hero_banner_ids' ) ){
			$exclude_ids = yt_site_hero_banner_ids();
			if( !empty( $exclude_ids ) )
				$query->set( 'post__not_in', $exclude_ids );
			
			$GLOBALS['yt_listed_posts']	= $exclude_ids;
		}

		if( $query->is_home && $query->is_main_query() && get_option('page_for_posts' ) ){

			$post_id = get_option('page_for_posts' );
			$query_post_mode = get_post_meta( $post_id, 'yt_page_queryposts_mode', true); 
			if( $query_post_mode ){
				
				$post_cats 			= get_post_meta( $post_id, 'yt_page_queryposts_category', true);
				$posts_per_page 	= get_post_meta( $post_id, 'yt_page_queryposts_postsperpage', true);
				$posts_order 		= get_post_meta( $post_id, 'yt_page_queryposts_order', true);
				$posts_orderby 		= get_post_meta( $post_id, 'yt_page_queryposts_orderby', true);
				$posts_excludeformat = get_post_meta( $post_id, 'yt_page_queryposts_excludeformat', true);
				$posts_tagin 		= get_post_meta( $post_id, 'yt_page_queryposts_tagin', true);
				$posts_ignorestickyposts = get_post_meta( $post_id, 'yt_page_queryposts_ignorestickyposts', true);

				// Cat
				if( $post_cats )
					$query->set( 'category__in', $post_cats );

				// Posts per page
				if( $posts_per_page && is_numeric( $posts_per_page ) )
					$query->set( 'posts_per_page', $posts_per_page );

				// Order by
				if( $posts_orderby )
					$query->set( 'orderby', $posts_orderby );

				// Order
				if( $posts_order )
					$query->set( 'order', $posts_order );

				if( !empty( $posts_tagin ) && is_array( $posts_tagin ) )
					$query->set( 'tag__in', $posts_tagin );

				// Exclude post format
				if( !empty( $posts_excludeformat ) && is_array( $posts_excludeformat ) ){
					$exclude_format_temp = array();
					foreach( $posts_excludeformat as $format ){
						$exclude_format_temp[] = "post-format-$format";
					}

					$query->set( 'tax_query', array(
					    array(
					      'taxonomy' 	=> 'post_format',
					      'field' 		=> 'slug',
					      'terms' 		=> $exclude_format_temp,
					      'operator' 	=> 'NOT IN'
					    )
					) );
				}
				
				// Sticky posts
				if( $posts_ignorestickyposts )
					$query->set( 'ignore_sticky_posts', 1 );
			}
				
			
			//yt_pretty_print( $query ); die();
		}
		
	}
}

add_filter( 'yt_theme_secondary_queryposts_query', 'yt_site_secondary_query_pre_get_posts' );

function yt_site_secondary_query_pre_get_posts( $query_args ){
	global $post, $wp_query;

	if( $wp_query->is_page )
		return $query_args;

	if( $wp_query->is_home )
		return $query_args;

	$prevent_duplicated = get_post_meta( $post->ID, 'yt_page_herobanner_excludeme_from_mainquery', true );
	$query_mode = get_post_meta( $post->ID, 'yt_page_queryposts_mode', true );

	if( $query_mode && $prevent_duplicated && function_exists( 'yt_site_hero_banner_ids' ) )
		$query_args['post__not_in'] = ( array ) yt_site_hero_banner_ids();

	return $query_args;

}
add_filter( 'infinite_scroll_js_settings', 'yt_site_infinite_scroll_js_settings');
/**
 * Infinite scroll settings
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_infinite_scroll_js_settings' ) ) {
	function yt_site_infinite_scroll_js_settings( $settings){
		$settings['text'] = __('Load more...', 'yeahthemes');
		return $settings;
	};
}
add_filter( 'yt_the_post_format_gallery_settings', 'yt_site_post_gallery_settings',10, 2 );
add_filter( 'yt_site_hero_banner_carousel_settings', 'yt_site_post_gallery_settings',10, 2 );
add_filter( 'yt_wp_default_gallery_settings', 'yt_site_post_gallery_settings',10, 2 );
add_filter( 'yt_theme_shortcode_slider_settings', 'yt_site_post_gallery_settings',10, 2 );

/**
 * Add mor parameters yeah slider
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_post_gallery_settings' ) ) {
	function yt_site_post_gallery_settings( $settings, $type ) {
		$settings['prevText'] = sprintf( '<i class="fa fa-arrow-left"></i> <span>%s</span>', __('Previous', 'yeahthemes') );
		$settings['nextText'] = sprintf( '<i class="fa fa-arrow-right"></i> <span>%s</span>', __('Next', 'yeahthemes') );
		$settings['playText'] = sprintf( '<i class="fa fa-play"></i> <span>%s</span>', __('Play', 'yeahthemes') );
		$settings['pauseText'] = sprintf( '<i class="fa fa-pause"></i> <span>%s</span>', __('Pause', 'yeahthemes') );
		$settings['smoothHeight'] = true;
		
		// if( 'slider' == $type ){
		// 	$settings['css3Effect'] = 'flipIn';
		// 	$settings['animation'] = 'fade';
		// }
		return $settings;
	}
}
add_filter( 'yt_option_vars_footer_columns', 'yt_site_theme_options_vars_footer_columns' ); //Theme option footer columns
/**
 * Custom Footer Columns
 *
 * @since 1.0
 */
function yt_site_theme_options_vars_footer_columns( $columns ) {

	$url = YEAHTHEMES_INCLUDES_IMG_URI;

	$columns['yt-col-1-5_yt-col-1-5_yt-col-1-5_yt-col-1-5_yt-col-1-5'] 	= $url . 'ytcol-1_5-1_5-1_5-1_5-1_5@2x.gif';
	$columns['yt-col-1-5_yt-col-1-5_yt-col-2-5_yt-col-1-5'] 			= $url . 'ytcol-1_5-1_5-2_5-1_5@2x.gif';
	$columns['yt-col-1-5_yt-col-2-5_yt-col-1-5_yt-col-1-5'] 			= $url . 'ytcol-1_5-2_5-1_5-1_5@2x.gif';
	$columns['yt-col-2-5_yt-col-1-5_yt-col-1-5_yt-col-1-5'] 			= $url . 'ytcol-2_5-1_5-1_5-1_5@2x.gif';
	$columns['yt-col-1-5_yt-col-1-5_yt-col-1-5_yt-col-1-5-2-5'] 		= $url . 'ytcol-1_5-1_5-1_5-2_5@2x.gif';
	$columns['yt-col-3-5_yt-col-1-5_yt-col-1-5'] 						= $url . 'ytcol-3_5-1_5-1_5@2x.gif';
	$columns['yt-col-2-5_yt-col-2-5_yt-col-1-5'] 						= $url . 'ytcol-2_5-2_5-1_5@2x.gif';
	$columns['yt-col-3-5_yt-col-2-5'] 									= $url . 'ytcol-3_5-2_5@2x.gif';

	return $columns;
}

/**
 * Ads Manager
 *
 * @since 1.0
 */

add_filter( 'yt_theme_options_advancedsettings_maintenance', 'yt_site_theme_options_ads_manager' );

function yt_site_theme_options_ads_manager( $options ){
	$options = array_merge( $options, apply_filters( 'yt_theme_options_ads_manager', array(
		array(
			'name' => __('Ads Manager','yeahthemes'),
			'type' => 'subheading',
			'customize' => 1,
			'customize_name' => __('Ads Manager','yeahthemes'),
			'settings' => array(
				'sanitize' => false
			),
		),

		/*After header*/
		array( 
			'name' => __('After Header (750x90)','yeahthemes'),
			'desc' => __('This ads will be displayed after header (site menu)<br>Add your Ads code or use the following custom html structure:','yeahthemes') . '<br>' . esc_attr( '<a href="#" target="_blank"><img src="http://localhost/demos/rita/750x90.jpg"></a>' ),
			'id' => 'site_ads_after_header',
			'std' => '',
			'type' => 'textarea',
			'class' => 'yt-tabifiable-textarea',
			'customize' => 1,
			'settings' => array(
				'sanitize' => false
			),

		),
		/*Before footer*/
		array( 
			'name' => __('Before footer (750x90)','yeahthemes'),
			'desc' => __('This ads will be displayed before footer widgets section','yeahthemes') . '<br>Add your Ads code or use the following custom html structure:' . esc_attr( '<a href="#" target="_blank"><img src="http://localhost/demos/rita/750x90.jpg"></a>' ),
			'id' => 'site_ads_before_footer',
			'std' => '',
			'type' => 'textarea',
			'class' => 'yt-tabifiable-textarea',
			'customize' => 1,
			'settings' => array(
				'sanitize' => false
			),

		),

		/*After single post*/
		array( 
			'name' => __('After single post (750x90)','yeahthemes'),
			'desc' => __('This will be displayed right below the single post content.<br>Add your Ads code or use the following custom html structure:','yeahthemes') . '<br>' . esc_attr( '<a href="#" target="_blank"><img src="http://localhost/demos/rita/750x90.jpg"></a>' ),
			'id' => 'site_ads_after_single_post',
			'std' => '<a href="#" target="_blank"><img src="http://localhost/demos/rita/750x90.jpg"></a>',
			'type' => 'textarea',
			'class' => 'yt-tabifiable-textarea',
			'customize' => 1,
			'settings' => array(
				'sanitize' => false
			),

		)
	) ) );
	return $options;
}
add_filter( 'yt_main_metabox_page_settings', 'yt_site_main_metabox_page_settings', 10 , 2 ); //Page settings metabox
/**
 * Filter Main metabox tab: page settings
 *
 * @since 1.0
 */
if( !function_exists( 'yt_site_main_metabox_page_settings' ) ){
	function yt_site_main_metabox_page_settings( $settings, $shortname ) {

		$framework_image_uri = YEAHTHEMES_FRAMEWORK_ADMIN_URI . 'assets/images/';
		
		$sidebar_list = yt_get_registered_sidebars();
		$sidebar_list['none'] = __( 'None', 'yeahthemes');

		$_show_hide = array(
			'show' => __( 'Show', 'yeahthemes'),
			'hide' => __( 'Hide', 'yeahthemes'),
		);
		
		/*Tab: Page Settings */
		
		$settings = array(
			array( 
				'type' => 'tab',
				'name' => __( 'Page Settings', 'yeahthemes'),
			),
			array( 
				'name' 		=> __( 'Sidebar layout', 'yeahthemes' ),
				'desc' 		=> __( 'Default sidebar will be retrieved from Theme Options', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_sidebar_layout',
				'std' 		=> 'default',
				'type' 		=> 'images',
				'options' 	=> apply_filters( 'yt_main_metabox_page_settings_option_page_layout' ,array(
					'default' 			=> $framework_image_uri . 'coldefault@2x.png',
					'left-sidebar' 		=> $framework_image_uri . '2cl@2x.png',
					'fullwidth' 		=> $framework_image_uri . '1col@2x.png',
					'right-sidebar' 	=> $framework_image_uri . '2cr@2x.png',
					
				) ),
				'settings' =>  array(
					'width' 	=> '45px', 
					'height' 	=> '36px'
				)
				
			),
			array( 
				'name' 		=> __( 'Default sidebar', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_default_sidebar',
				'type' 		=> 'select',
				'std'		=> 'main-sidebar',
				'options'	=> $sidebar_list,
				'settings' => array()
			),
			array( 
				'name' 		=> __( 'Sub sidebar', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_sub_sidebar',
				'type' 		=> 'select',
				'std'		=> 'sub-sidebar',
				'options'	=> $sidebar_list,
				'settings' => array()
			),
			array( 
				'name' 		=> __( 'Page Title', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_title',
				'std' 		=> 'show',
				'type' 		=> 'toggles',
				'options'	=> $_show_hide
			),
			array( 
				'name' 		=> __( 'Page Content', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_content',
				'std' 		=> 'show',
				'type' 		=> 'toggles',
				'options'	=> $_show_hide
			),

		);
		
		return $settings;
		
	}
}
add_filter( 'yt_main_metabox_page_controls', 'yt_site_main_metabox_hero_banner', 10 , 2 ); //Hero banner metabox
/**
 * Site hero metabox field
 *
 * @since 1.0
 */
if( !function_exists( 'yt_site_main_metabox_hero_banner' ) ){
	function yt_site_main_metabox_hero_banner( $settings, $shortname ) {

		
		$image_uri = YEAHTHEMES_CINCLUDES_IMG_URI ;

		$settings = array_merge( $settings, apply_filters( 'yt_main_metabox_hero_banner', array( 
			
			array( 
				'type' => 'tab',
				'name' => __( 'Hero Banner', 'yeahthemes'),
			),

			array( 
				'name' 		=> __( 'Hero Banner', 'yeahthemes' ),
				'desc'		=> __( 'Toggle the above field on to apply the following configs to this page\'s Hero', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_herobanner_mode',
				'std' 		=> 0,
				'type' 		=> 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'folds' 	=> '0',
					'label' 	=> __( 'Enable/Disable', 'yeahthemes' ),
				)
			),
			array( 
				'name' 		=> __( 'Hero Banner layout', 'yeahthemes' ),
				'desc' 		=> __( 'Choose your Hero banner layout you wish to use on this page', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_herobanner_layout',
				'std' 		=> 'default',
				'type' 		=> 'images',
				'options' 	=> array(
					'default' 			=> $image_uri . 'brick_default.png',
					'symmetry_brick' 	=> $image_uri . 'brick_1221.png',
					'carousel' 			=> $image_uri . 'brick_carousel.png',
					
				),
				'settings' =>  array(
					'width' 	=> '119px', 
					'height' 	=> '59px'
				)
				
			),
			array(
				'name' 		=> __( 'Hero Banner style', 'yeahthemes' ),
				'desc'		=> __( 'Choose your Hero banner layout', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_herobanner_style',
				'std' 		=> 'date',
				'type' 		=> 'select',
				'options' 	=> array(
					'default' 		=> __( 'Default (Dark gradient)', 'yeahthemes' ),
					'color' => __( 'Overlay color by Category', 'yeahthemes' ),
					'mixed' 		=> __( 'Mixed Gradient (It\'s nice :) ) ', 'yeahthemes' ),
				),
				'settings' => array(
					'folds' => '1',
				)
			),
			array( 
				'name' 		=> __( 'Random color', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_herobanner_style_random',
				'std' 		=> 0,
				'type' 		=> 'checkbox',
				'settings' => array(
					'fold' => $shortname . 'page_herobanner_style',
					'fold_value' => 'mixed',
					'label' 	=> __( 'Check this to use randomized overlay effect', 'yeahthemes' ),
				)
			),
			array(
				'name' 		=> __( 'Effect of Appearance', 'yeahthemes' ),
				'desc'		=> __( 'Choose your Hero banner Appearance Effect', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_herobanner_effect',
				'std' 		=> 'zoomIn',
				'type' 		=> 'select_alt',
				'options' 	=> yt_get_option_vars( 'entrance_animations' )
			),

			array( 
				'name' 		=> __( 'Category', 'yeahthemes' ),
				'desc' 		=> __( 'Select a category you wish to display posts from (No category selected, retrieve all)', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_herobanner_category',
				'type' 		=> 'category_checklist',
				'class' 	=> 'yt-inline-input',
				'std' 		=> array(),
				
			),
			array(
				'name' 		=> __( 'Order', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_herobanner_order',
				'std' 		=> 'DESC',
				'type' 		=> 'select',
				'options' 	=> array(
					'DESC' 		=> __( 'Descending', 'yeahthemes' ),
					'ASC' 		=> __( 'Ascending', 'yeahthemes' ),
				)
			),
			array(
				'name' 		=> __( 'Order by', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_herobanner_orderby',
				'std' 		=> 'date',
				'type' 		=> 'select',
				'options' 	=> array(
					'date' 		=> __( 'Date', 'yeahthemes' ),
					'title' 	=> __( 'Title', 'yeahthemes' ),
					'name' 		=> __( 'Post slug', 'yeahthemes' ),
					'author' 	=> __( 'Author', 'yeahthemes' ),
					'modified' => __( 'Last modified date', 'yeahthemes' ),
					'comment_count' => __( 'Number of comments', 'yeahthemes' ),
					'rand' 		=> __( 'Random order', 'yeahthemes' ),
					'meta_value_num' => __( 'Post Views', 'yeahthemes' ),
				)
			),
			array(
				'name' 		=> __( 'Number of posts', 'yeahthemes' ),
				'desc'		=> sprintf(__( 'Default: %s. (this option only apply to carousel layout)', 'yeahthemes' ), 8 ),
				'id' 		=> $shortname . 'page_herobanner_postsperpage',
				'std' 		=> '8',
				'type' 		=> 'number',
				'class' => 'yt-section-text-number',
				'settings' 	=> array(
					'attr'	=> ' style="' . esc_attr( 'width:60px;' ) . '"',
					
				)
			),
			
			array(
				'name' 		=> __( 'Post In', 'yeahthemes' ),
				'desc'		=> __( 'Specify posts to retrieve. Hold cmd/ctrl + click to select multiple posts', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_herobanner_postin',
				'std' 		=> '',
				'type' 		=> 'multiselect',
				'options' 	=> yt_get_post_list( array('post') )
			),
			array(
				'name' 		=> __( 'Tagged In', 'yeahthemes' ),
				'desc'		=> __( 'Specify tags to retrieve posts from. Hold cmd/ctrl + click to select/deselect multiple tags', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_herobanner_tagin',
				'std' 		=> '',
				'type' 		=> 'multiselect',
				'options' 	=> yt_get_tag_list()
			),
			array(
				'name' 		=> __( 'Exclude post format', 'yeahthemes' ),
				'desc'		=> __( 'Specify post formats you don\'t want to retrieve.', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_herobanner_excludeformat',
				'std' 		=> '',
				'type' 		=> 'multicheck',
				'class' 	=> 'yt-inline-input',
				'options' 	=> yt_get_supported_post_formats(),
				'settings' => array(
					'is_indexed' => 1
				)
			),

			array(
				'name' 		=> __( 'Exclude hero posts', 'yeahthemes' ) ,
				'desc'		=> '',
				'id' 		=> $shortname . 'page_herobanner_excludeme_from_mainquery',
				'std' 		=> 0,
				'type' 		=> 'checkbox',
				'settings' 	=> array(
					'label' => __( 'Prevent duplicating posts on main archive posts', 'yeahthemes' ),
				)
			),
			

		) ) );

		return $settings;
	}
}

add_filter( 'yt_theme_options_option_general_site_layout', 'yt_site_supported_layout' ); // for theme options
add_filter( 'yt_main_metabox_page_settings_option_page_layout', 'yt_site_supported_layout' ); // for metabox
/**
 * Add more type of layout for theme options and metabox
 *
 * @since 1.0
 */
if( !function_exists( 'yt_site_supported_layout' ) ){
	function yt_site_supported_layout( $options){

		$include_image_uri = YEAHTHEMES_CINCLUDES_IMG_URI ;
		$options['double-sidebars'] = $include_image_uri . '3cr@2x.png';
		return $options;
	}
}





/*
 * WP-Review color
 *
 * @since 1.0.1
 * @framework 1.0
 */
if( function_exists( 'wp_review_render_meta_box_item')){

	add_filter( 'wp_review_default_colors', 'yt_site_wp_review_default_colors' );

	function yt_site_wp_review_default_colors( $defaultColors ){

		$yt_data = yt_get_options();
		$primary_color = $yt_data['primary_color'] ? $yt_data['primary_color'] : ( $yt_data['builtin_skins'] ? $yt_data['builtin_skins'] : '#2cae8c');
		$primary_color = apply_filters( 'yt_site_styling_primary_color', $primary_color );

		$secondary_color = $yt_data['secondary_color'] ? $yt_data['secondary_color'] : '#363b3f';

		$defaultColors = array(
	    	'color' => $primary_color,
	    	'fontcolor' => '#7777777',
	    	'bgcolor1' => '#ffffff',
	    	'bgcolor2' => '#ffffff',
	    	'bordercolor' => '#eeeeee'
	    );

	    return $defaultColors;
	}
}