<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Admin Functions
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */


if( ! function_exists( 'yt_remove_wpautop' ) ) {
	function yt_remove_wpautop( $content ) {
		$content = do_shortcode( shortcode_unautop( $content ) );
		$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
		return $content;
	} 
}


/**
 * Set default term for custom post types
 *
 * @access public
 * @since 1.0
 */
add_action( 'save_post', 'yt_set_default_object_terms', 100, 2 );

if( !function_exists( 'yt_set_default_object_terms' ) ) {
	
	function yt_set_default_object_terms( $post_id, $post ) {
		
		$defaults = apply_filters( 'yt_default_object_terms_array' , array() );
		
		if( empty( $defaults ) ) 
			return;
		/* 
		$defaults = array(
			'portfolio-type' => array( 'uncategorized' => __('Uncategorized','framework'))
		);
		*/
		if ( 'publish' === $post->post_status ) {
			
			$taxonomies = get_object_taxonomies( $post->post_type );
			
			foreach ( (array) $taxonomies as $taxonomy ) {
				
				$terms = wp_get_post_terms( $post_id, $taxonomy );
				
				if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
					
					wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
					
				}
			}
		}
	}
}

/**
 * Dashboards custom post type statistics
 *
 * @access public
 * @since 1.0
 */
add_action('right_now_content_table_end', 'yt_dashboard_stt_counts');

if( !function_exists( 'yt_dashboard_stt_counts' ) ) {
	function yt_dashboard_stt_counts() {
		
		if( !is_admin() )
			return;
		// Usage
		/*
		$post_type = array(
			'portfolio' => array( 'Project', 'Projects')
		);
		*/
	
		$post_types = apply_filters( 'yt_dashboard_stt_posttypes', array() );
		
		if( empty( $post_types ) )
			return;
		
		foreach($post_types as $post_type){
			
			
			$num_post_type = wp_count_posts( $post_type );
			$num = number_format_i18n( $num_post_type->publish );
			$text = _n( $post_type[0], $post_type[1], intval( $num_post_type->publish ) );
			if ( current_user_can( 'edit_posts' ) ) {
				$num = "<a href='edit.php?post_type=portfolio'>$num</a>";
				$text = "<a href='edit.php?post_type=portfolio'>$text</a>";
			}
			echo '<tr>';
			echo '<td class="first b b-portfolio">' . $num . '</td>';
			echo '<td class="t portfolio">' . $text . '</td>';
			echo '</tr>';

		}
	
	}
}

/**
 * Get Post format meta
 *
 * @return array
 * @access public
 * @since 1.0
 */
if( !function_exists( 'yt_get_post_formats_meta' ) ) {
	function yt_get_post_formats_meta(  $post_id ){
		
		if( empty( $post_id ) && !is_numeric( $post_id ) ){
			return;
		}
		if ( !current_theme_supports( 'post-formats' ) ) {
			return;
		}
		/*Allow filtering for theme migration*/ 
		$ouput = apply_filters( 'yt_post_formats_meta_data', array(
			'_thumbnail_id' 				=> get_post_meta( $post_id, '_thumbnail_id', true ),
			'_format_image' 				=> get_post_meta( $post_id, '_format_image', true ),
			'_format_url' 					=> get_post_meta( $post_id, '_format_url', true ),
			'_format_gallery' 				=> get_post_meta( $post_id, '_format_gallery', true ),
			'_format_audio_embed'			=> get_post_meta( $post_id, '_format_audio_embed', true ),
			'_format_video_embed' 			=> get_post_meta( $post_id, '_format_video_embed', true ),
			'_format_quote_source_name' 	=> get_post_meta( $post_id, '_format_quote_source_name', true ),
			'_format_quote_source_url' 		=> get_post_meta( $post_id, '_format_quote_source_url', true ),
			'_format_link_url' 				=> get_post_meta( $post_id, '_format_link_url', true )
		), $post_id );
		
		return $ouput;
		
	}
}