<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Theme template hooks
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeah Includes
 */


/**
 * News Mega menu
 */
if( 'ajax' === yt_get_options( 'main_megamenu_request_type' ) ){ 
	add_action( 'yt_ajax_yt-site-mega-menu', 'yt_site_ajax_mega_menu_by_category' );
	add_action( 'yt_ajax_nopriv_yt-site-mega-menu', 'yt_site_ajax_mega_menu_by_category' );
}else{

	add_filter( 'yt_mega_menu_content', 'yt_site_usual_mega_menu_by_category', 10, 5 );
}


/**
 * Ajax Mobile Menu
 */ 
add_action( 'yt_ajax_yt-site-mobile-menu', 'yt_site_ajax_mobile_menu' );
add_action( 'yt_ajax_nopriv_yt-site-mobile-menu', 'yt_site_ajax_mobile_menu' );

add_filter( 'wp_nav_menu_items', 'yt_site_custom_menu_item', 10, 2 );

if( !is_admin() ){

/*Class for site section*/
add_filter( 'yt_wrapper_class','yt_site_wrapper_class');
add_filter( 'yt_header_class','yt_site_header_class');
add_filter( 'yt_primary_class','yt_site_primary_class');
add_filter( 'yt_secondary_class','yt_site_secondary_class');
add_filter( 'yt_tertiary_class','yt_site_tertiary_class');
//add_filter( 'yt_content_class','yt_site_content_class');
add_filter( 'yt_footer_class','yt_site_footer_class');

/**
 * THEME HOOKS
 *
 * Adds specific class to first/last menu item
 */


add_filter( 'yt_walker_nav_menu_description', 'yt_site_nav_menu_description_icon', 10, 2 );


/**
 * Viewport Meta
 */
add_action( 'wp_head', 'yt_theme_meta_viewport', 1 );

/**
 * Header
 */

//add_action( 'yt_header_start','yt_site_top_bar_menu'); 
add_action( 'yt_header_start','yt_site_start_header_banner', 10); 
add_action( 'yt_header_end','yt_site_end_header_banner', 11); 

/**
 * Logo
 */
add_action( 'yt_inside_header','yt_site_branding', 15); 

/**
 * Nav & search
 */
add_action( 'yt_inside_header','yt_site_primary_nav', 15); 

/**
 * Archive title
 */ 
add_action( 'yt_primary_start','yt_site_archive_header', 1.1);

/**
 * Site Hero bricks
 */
add_action( 'yt_before_primary','yt_site_hero_banner', 1);

/**
 * Primary content
 */
add_action( 'yt_before_primary','yt_site_start_single_row', 1);
add_action( 'yt_after_primary','yt_site_end_single_row', 15);

	/**
	 * Overwite default post meta desciption
	 */
	add_filter( 'yt_theme_default_post_meta_description', '__return_false');
	add_action( 'yt_theme_post_meta_description', 'yt_site_post_meta_description');

/**
 * Sidebar and sub sidebar
 */
add_action( 'yt_after_primary','yt_site_secondary_content', 1);
add_action( 'yt_after_primary','yt_site_tertiary_content', 2);

/**
 * Site Footer
 */
add_action( 'yt_inside_footer', 'yt_site_footer', 10);

/**
 * Single Post stuff
 */

add_action( 'yt_after_loop_singular_post','yt_site_single_post_author', 5);
add_action( 'yt_after_loop_singular_post','yt_site_single_post_dir_nav', 10);
add_action( 'yt_after_loop_singular_post','yt_site_single_post_related_articles', 10);
add_action( 'yt_after_loop_singular_post','yt_site_single_post_you_might_also_like', 20);
add_action( 'yt_after_loop_singular_post','yt_site_single_post_comment', 20);


/**
 * Site Ads
 */

add_action( 'yt_after_loop_singular_post','yt_site_single_post_ads_750x90', 11);
/**
 * Head ads
 */
add_action( 'yt_after_header','yt_site_head_ads', 20);
add_action( 'yt_footer_start','yt_site_foot_ads', 20);

}else{

}