<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Default Metaboxes
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */

add_filter( 'yt_meta_boxes', 'yt_main_metaboxes_controls' );

if( !function_exists( 'yt_main_metaboxes_controls' ) ){

	function yt_main_metaboxes_controls( $meta_boxes ) {
		
		$shortname = 'yt_';
		
		$_show_hide = array(
			'show' => __( 'Show', 'yeahthemes'),
			'hide' => __( 'Hide', 'yeahthemes'),
		);
		$_false_true = array(
			'' => __( 'False', 'yeahthemes'),
			1 => __( 'True', 'yeahthemes'),
		);
		
		$sidebar_list = yt_get_registered_sidebars();
		$sidebar_list['none'] = __( 'None', 'yeahthemes');
		
		
		$_global_settings = array();
		$_page_settings = array();
		$_page_query_posts = array();
		$_page_elite_builder = array();
		
		$_global_settings = apply_filters( 'yt_main_metabox_global_settings', array(
			// array( 
			// 	'name' 		=> __( 'Content Settings', 'yeahthemes'),
			// 	'desc' 		=> __( 'Select content source that will be displayed from.', 'yeahthemes'),
			// 	'id' 		=> $shortname . 'page_content_settings',
			// 	'std' 		=> '',
			// 	'type' 		=> 'select',
			// 	'options' 	=> array(
			// 		'default'				=> __( 'Default Editor', 'yeahthemes'),
			// 		'editor-queryposts' 	=> __( 'Editor + Query Posts', 'yeahthemes')
			// 	),
			// )
		), $shortname );
		
		
		$sidebar_image_uri = YEAHTHEMES_FRAMEWORK_ADMIN_URI . 'assets/images/';
		
		
		/*Tab: Page Settings */
		
		$_page_settings 	= apply_filters( 'yt_main_metabox_page_settings', array(
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
				'options' 	=> array(
					'default' 			=> $sidebar_image_uri . 'coldefault.png',
					'left-sidebar' 		=> $sidebar_image_uri . '2cl.png',
					'fullwidth' 		=> $sidebar_image_uri . '1col.png',
					'right-sidebar' 	=> $sidebar_image_uri . '2cr.png',
					
				),
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
				'options'	=> array_merge( yt_get_registered_sidebars(), array( 'none' => 'None' ) ),
				'settings' => array(
				)
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
				'name' 		=> __( 'Page Sub-title', 'yeahthemes' ),
				'desc'		=> __( 'The Sub title that display right below page title.', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_subtitle',
				'std' 		=> '',
				'type' 		=> 'text',
				'settings' => array(
					'fold' 		=> $shortname . 'page_title',
				)
			),
			array( 
				'name' 		=> __( 'Page Content', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_content',
				'std' 		=> 'show',
				'type' 		=> 'toggles',
				'options'	=> $_show_hide
			),
			array( 
				'name' 		=> __( 'Background', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_background',
				'std' 		=> 1,
				'type' 		=> 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'folds' 	=> '0',
					'label' 	=> __( 'Enable/Disable Background', 'yeahthemes' ),
				)
			),
			array( 
				'name' 		=> __('Background image','yeahthemes'),
				'desc' 		=> '',
				'id' 		=> $shortname . 'page_background_image',
				'std' 		=> '',
				'type' 		=> 'media',
				'settings' => array(
					'fold'		=> $shortname . 'page_background',
				)
			),
			array( 
				'name' 		=> __('Background options','yeahthemes'),
				'desc' 		=> __('Default : no-repeat - center top - local - auto', 'yeahthemes'),
				'id' 		=> $shortname . 'page_background_options',
				'std' 		=> array(
					'repeat' 		=> 'no-repeat',
					'position' 	=> 'center top',
					'attachment' 	=> 'local',
					'size' 			=> 'cover',
					'color' 		=> ''
				),
				'type' 		=> 'background_options',
				'settings' => array(
					'fold' 			=> $shortname . 'page_background',
				)
			)
		), $shortname );
		
		
		/*Tab: Query Posts */
		$_query_posts 		= apply_filters( 'yt_main_metabox_query_posts', array(
			array( 
				'type' => 'tab',
				'name' => __( 'Query Posts', 'yeahthemes'),
			),
			array( 
				'name' 		=> __( 'Query Posts', 'yeahthemes' ),
				'desc'		=> __( 'Displaying posts using a custom query, toggle the above field on to apply the following configs to this page', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_queryposts_mode',
				'std' 		=> 0,
				'type' 		=> 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'folds' 	=> '0',
					'label' 	=> __( 'Enable/Disable', 'yeahthemes' ),
				)
			),
			array( 
				'name' 		=> __( 'Category', 'yeahthemes' ),
				'desc' 		=> __( 'Select a category you wish to display posts from (No category selected, retrieve all)', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_queryposts_category',
				'type' 		=> 'category_checklist',
				'class' 	=> 'yt-inline-input',
				'std' 		=> array(),
				
			),
			array(
				'name' 		=> __( 'Order', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_queryposts_order',
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
				'id' 		=> $shortname . 'page_queryposts_orderby',
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
				'name' 		=> __( 'Posts per page', 'yeahthemes' ),
				'desc'		=> sprintf(__( 'Default: %s', 'yeahthemes' ), get_option('posts_per_page') ),
				'id' 		=> $shortname . 'page_queryposts_postsperpage',
				'std' 		=> get_option('posts_per_page'),
				'type' 		=> 'number',
				'settings' 	=> array(
					'attr'	=> ' style="' . esc_attr( 'width:50px;' ) . '"'
				)
			),
			array(
				'name' 		=> __( 'Tagged In', 'yeahthemes' ),
				'desc'		=> __( 'Specify tags to retrieve posts from. Hold cmd/ctrl + click to select/deselect multiple tags', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_queryposts_tagin',
				'std' 		=> '',
				'type' 		=> 'multiselect',
				'options' 	=> yt_get_tag_list()
			),
			array(
				'name' 		=> __( 'Exclude post format', 'yeahthemes' ),
				'desc'		=> __( 'Specify post formats you don\'t want to retrieve.', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_queryposts_excludeformat',
				'std' 		=> '',
				'type' 		=> 'multicheck',
				'class' 	=> 'yt-inline-input',
				'options' 	=> yt_get_supported_post_formats(),
				'settings' => array(
					'is_indexed' => 1
				)
			),
			array(
				'name' 		=> __( 'Sticky posts', 'yeahthemes' ) ,
				'desc'		=> '',
				'id' 		=> $shortname . 'page_queryposts_ignorestickyposts',
				'std' 		=> '',
				'type' 		=> 'checkbox',
				'settings' 	=> array(
					'label' => __( 'Check to ignore ', 'yeahthemes' ),
				)
			),

		), $shortname );
		
		/*Merger $_query_posts & $_query_posts */
		$_metabox_controls = array_merge( $_global_settings, $_page_settings, $_query_posts );
		
		$_metabox_controls = apply_filters( 'yt_main_metabox_page_controls', $_metabox_controls, $shortname );
		
		$meta_boxes[] = array(
			'id'         => 'yt_main_metabox_page_controls',
			'title'      => apply_filters( 'yt_main_metabox_page_controls_panel_title', __( 'Yeahthemes Settings Panel', 'yeahthemes') ),
			'pages'      => array( 'page', ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields'     => $_metabox_controls
		);
		
		//print_r($meta_boxes);
		
		return $meta_boxes;
		
	}

}