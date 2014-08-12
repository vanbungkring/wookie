<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Admin Init
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */

/**
 * Global Object
 *
 * for use in frontend and backend
 * @return void
 *
 **/

add_action ( 'wp_print_scripts', 'yt_yeahthemes_global_object', 1 );

if( !function_exists( 'yt_yeahthemes_global_object' ) ) {
	function yt_yeahthemes_global_object(){
		
		global $post, $wp_query;
		
		$post_id = 0;
		
		if( !$wp_query->is_404 && !$wp_query->is_search && !$wp_query->is_archive ){
			$post_id = isset( $post->ID ) ? $post->ID : 0;
		}
		
		if( 'posts' === get_option( 'show_on_front' ) ){
			$post_id = 0;
		}
		
		if( $wp_query->is_home && get_option( 'page_for_posts' ) ){
			$post_id = get_option( 'page_for_posts' );	
		}

		$base_url = set_url_scheme( home_url( '/' ) );

		$ajaxurl = add_query_arg( array( 'yt_ajaxify' => 1 ), $base_url );
		
		$yeahthemes = array();
		$yeahthemes['_vars']['currentPostID'] = $post_id;
		$yeahthemes['_vars']['ajaxurl'] = $ajaxurl;
		$yeahthemes['_vars']['nonce'] = wp_create_nonce('yeahthemes_frontend_nonce');
		
		$yeahthemes = apply_filters('yt_yeahthemes_global_object', $yeahthemes );
		
		$output = 'var Yeahthemes = {}';
		
		if(!is_admin()){
			$output = 'var Yeahthemes = ' . json_encode( $yeahthemes ) . ';';
		}
		echo '<script type="text/javascript">/* <![CDATA[ */' . $output  .'/* ]]> */</script>' . "\n";
	}
}

/**
 * Load functions
 *
 */
$yt_framework_includes = apply_filters( 'yt_locate_template_framework_includes',
	array(
	
		'framework/yt-admin-variables.php',
		'framework/yt-admin-scripts.php',
		'framework/yt-admin-hooks.php',
		'framework/yt-admin-config.php',
		'framework/yt-admin-helpers.php',
		'framework/yt-admin-functions.php',
		'framework/yt-admin-options.php',
		//'yeahthemes-builder/builder-init.php',
		
		'framework/front-end/bootstrap-templates.php',
		
		// Classes
		'framework/classes/class.yeahthemes.php',
		'framework/classes/class.yeathemes-walkers.php',
		'framework/extended/class.wp-help-pointers.php',
		
		//'extended/index.php',
		
	)
);
			
foreach ( $yt_framework_includes as $include ) { 
	locate_template( $include, true ); 
}


/**
 * Register Widget
 *
 * @since 1.0
 * @framework 1.0
 */
 
$yt_framework_widgets = apply_filters( 'yt_locate_template_widgets_includes',array(
	'framework/widgets/widget-ads-125.php',
	'framework/widgets/widget-ads-full.php',
	'framework/widgets/widget-flickr.php',
	'framework/widgets/widget-facebook-likebox.php',
	'framework/widgets/widget-categorys-descendants.php',
	'framework/widgets/widget-childpages.php',
	'framework/widgets/widget-twitter-profile.php',
	'framework/widgets/widget-twitter-timelines.php',
	'framework/widgets/widget-mailchimp.php',
	'framework/widgets/yt-smart-widget.class.php',
	'framework/widgets/widget-smart-tabby-widget.php'
));

if( !empty( $yt_framework_widgets ) ){	
	foreach( $yt_framework_widgets as $widget){
		locate_template( $widget, true );	
	}
}

function yt_init_framework_widgets() {
	$widget_list = apply_filters( 'yt_framework_widgets_init', array(
		'YT_Ads125_Widget',
		'YT_AdsFull_Widget',
		'YT_Flickr_Widget',
		'YT_Facebook_Likebox_Widget',
		'YT_Category_Descendants_Widget',
		'YT_Childpages_Widget',
		'YT_Twitter_Profiles_Widget',
		'YT_Twitter_Timelines_Widget',
		'YT_Smart_Tabbed_Widget',
		'YT_Mailchimp_Subscription_Form_Widget'
	));
	
	if( empty( $widget_list ) )
		return;
		
	foreach( $widget_list as $widget ){
		register_widget( $widget );
	}
}

add_action( 'widgets_init', 'yt_init_framework_widgets' );
