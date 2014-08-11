<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;



/**
 * Sample Functions
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */

//add_filter( 'yt_meta_boxes', 'yt_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function yt_sample_metaboxes( $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$shortname = 'yt_';
	$url =  YEAHTHEMES_FRAMEWORK_ADMIN_URI . 'assets/images/';	
	
	//Background Images Reader
	$bg_images_path = (!is_child_theme() ? get_template_directory() : get_stylesheet_directory()) . '/images/bg/'; // change this to where you store your bg images
	$bg_images_url = (!is_child_theme() ? get_template_directory_uri() : get_stylesheet_directory_uri()).'/images/bg/'; // change this to where you store your bg images
	
	
	$bg_images = array();
	
	if ( is_dir($bg_images_path) ) {
		if ($bg_images_dir = opendir($bg_images_path) ) { 
			while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
				if(stristr($bg_images_file, '.png') !== false || stristr($bg_images_file, '.jpg') !== false) {
					$bg_images[] = $bg_images_url . $bg_images_file;
				}
			}    
		}
	}
	
	$meta_boxes[] = array(
		'id'         => 'test_xmetabox',
		'title'      => 'Test Metabox',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			
			//Text
			array( 
				'name' => 'Text',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'text',
				'std' => 'Your default text',
				'type' => 'text',
				'settings' => array(
					
					'sanitize' => 'esc_url',
				)
			),
			
			//Textarea
			array( 
				'name' => 'Textarea',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'textarea',
				'std' => 'Your default textarea content',
				'type' => 'textarea'
			),
			
			//Checkbox
			array( 
				'name' => 'Checkbox',
				'desc' => 'Your description about this setting',
				'label' => 'Label for this checkbox',
				'id' => $shortname . 'checkbox',
				'std' => 1,
				'type' => 'checkbox',
				'settings' => array(
					
					'folds' => '0',
				)
			),
			
			array( 
				'type' => 'tab',
				'name' => 'Colorpicker',
				
			),
			
			//Colorpicker
			array( 
				'name' => 'WP Colorpicker',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'colorpickering',
				'std' => '#7c3',
				'type' => 'colorpicker'
			),
			
			array( 
				'type' => 'tab',
				'name' => 'Select',
				
			),
			//Select
			array( 
				'name' => 'Select',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'select',
				'std' => 'left-sidebar',
				'type' => 'select',
				'options' => array(
					'right-sidebar' => 'Right sidebar',
					'left-sidebar' => 'Left sidebar',
					'middle-sidebar' => 'Middle sidebar'
				),
				'settings' => array(
					'folds' => '0',
				)
			),
			
			
			array( 
				'name' => 'Select alt ( Unassociated array )',
				'desc' => '',
				'id' => $shortname . 'select_alt',
				'std' => 'DESC',
				'type' => 'select_alt',
				'options' => array('DESC','ASC'),
				'settings' => array(
					'fold' => $shortname . 'select',
					'fold_value' => 'right-sidebar',
				)
				
			),
			
			//Select
			array( 
				'name' => 'Select',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'msxelect',
				'std' => array( 'left-sidebar', 'middle-sidebar' ),
				'type' => 'multiselect',
				'options' => array(
					'right-sidebar' => 'Right sidebar',
					'left-sidebar' => 'Left sidebar',
					'middle-sidebar' => 'Middle sidebar'
				),
				'settings' => array(
					'fold' => $shortname . 'select',
					'fold_value' => 'middle-sidebar,right-sidebar',
				)
			),
			
			array( 
				'type' => 'tab_break',
				'name' => 'Select',
				
			),
			
			//image radios
			array( 
				'name' => 'Image radios',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'image_radio',
				'std' => 'right-sidebar',
				'type' => 'images',
				'options' => array(
					'right-sidebar' => $url . '2cr.png',
					'left-sidebar' => $url . '2cl.png'
				),
				'settings' =>  array(
					'width' => '45px', 
					'height' => '36px',
					'fold' => $shortname . 'select',
					'fold_value' => 'right-sidebar,left-sidebar',
				)
				
			),
			
			array( 
				'type' => 'tab',
				'name' => 'Toggles/Media',
				
			),
			//Toggles
			array( 
				'name' => 'Toggles radios',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'toggles',
				'std' => 'off',
				'type' => 'toggles',
				'options' => array(
					'on' => 'ON',
					'off' => 'OFF',
				),
				'settings' => array(
					'fold' => $shortname . 'select',
					'fold_value' => 'middle-sidebar',
				)
			),
			
			//Media
			array( 
				'name' => 'Upload',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'media',
				'std' => '',
				'type' => 'media'
			),
			
			array( 
				'type' => 'tab',
				'name' => 'Multicheckboxes',
				
			),
			//Multicheck
			array( 
				'name' => 'Multicheckboxes',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'multicheckboxes',
				'type' => 'multicheck',
				'class' => 'yt-inline-input',
				'std' => array(
					'value-1',
					'value-3'
				),
				'options' => array(
					'value-1' => 'Value 1',
					'value-2' => 'Value 2',
					'value-3' => 'Value 3',
					'value-4' => 'Value 4'
				)
				
			),
			
			array( 
				'type' => 'tab_break',
			),
			
			//Multicheck
			array( 
				'name' => 'Radio',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'radio',
				'type' => 'radio',
				'std' => 'value-3',
				'class' => 'yt-inline-input',
				'options' => array(
					'value-1' => 'Value 1',
					'value-2' => 'Value 2',
					'value-3' => 'Value 3',
					'value-4' => 'Value 4'
				)
				
			),
			
			//List of pages				
			array( 
				'name' => 'Page in select',
				'desc' => 'This option return the page id for you',
				'id' => $shortname . 'pages_select',
				'std' => '',
				'type' => 'select',
				'options' => yt_get_page_list()
			),
			
			//Categories
			array( 
				'name' => 'Categories in select',
				'desc' => 'This option return the Category id for you',
				'id' => $shortname . 'categories_select',
				'std' => '',
				'type' => 'select',
				'options' => yt_get_category_list()
				
			),
			
			//Multiple Toggle	
			array( 
				'name' => 'Multiple Toggle buttons',
				'desc' => 'Select main content and sidebar alignment.',
				'id' => $shortname . 'toggles_three',
				'std' => 'left',
				'type' => 'toggles',
				'class' => 'big',
				'options' => array(
					'left' => 'Left',
					'center' => 'Center',
					'right' => 'Right'
				),
				'settings' => array( 'width' => '70px' )
			),
			
			//Taxonomy checkboxes
			array( 
				'name' => 'Taxonomy checkboxes',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'tax_checkboxes',
				'std' => array(),
				'type' => 'tax_checkboxes',
				'class' => 'yt-inline-input',
				'settings' => array(
					'taxonomy' => 'portfolio-type'
				)
			),
								
			//Color blocks
			array( 
				'name' => 'Color Block',
				'desc' => '',
				'id' => $shortname . 'color_blocks',
				'std' => 'default',
				'type' => 'color_blocks',
				'settings' => array(
					'width' => '30px',
					'height' => '30px',
				),
				'options' => yt_get_option_vars( 'skins' )
			),
			
			
			array( 
				'name' => 'Gallery',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'gallery',
				'std' => '',
				'type' => 'gallery'
			),
			//Calendar
			array( 
				'name' => 'Calendar',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'calendar',
				'std' => '',
				'type' => 'calendar'
			),
			
			//Time
			array( 
				'name' => 'Time',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'time',
				'std' => '',
				'type' => 'time'
			),
			
			//Border
			array( 
				'name' => 'Border',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'border',
				'std' => array(
					'width' => '6',
					'style' => 'solid',
					'color' => '#33b3d3'
				),
				'type' => 'border',
			),
			
			array( 'name' => __('Boxed-layout margin','yeahthemes'),
				'desc' => __('default : top: 50px, bottom: 70px','yeahthemes'),
				'id' => $shortname . 'boxed_layout_margin',
				'std' => array(
					'top' => '0px',
					'right' => '0px',
					'bottom' => '0px'
				),
				'type' => 'margin'
			),
		
			//background options
			array( 'name' => __('Body Background options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => $shortname . 'bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto',
					'color' => '#CCC'
				),
				'type' => 'background_options'
			),
			
			//Typography
			array( 
				'name' => 'Typography',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'typo',
				'std' => array(
					'face' => 'Helvetica',
					'size' => '30px',
					'weight' => 'normal',
					'style' => 'normal',
					'height' => '40px',
					'letterspacing' => '0px',
					'transform' => 'none',
					'marginbottom' => '35px',
					'color' => ''
				),
				'type' => 'typography'
			),
			
			//UI slider				
			array( 
				'name' => 'UI Slider',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'ui_slider',
				'std' => '20',					
				'type' => 'uislider',
				'settings' => array( 
					'min' => '0',
					'max' => '100',
					'step' => '10',
					'unit' => 'em', 
				),
			),
			
			//Checkbox toggle
			array( 
				'name' => 'Check box toggle',
				'desc' => '',
				'id' => $shortname . 'checkbox_toggle',
				'std' => 1,
				
				'type' => 'checkbox',
				'label' => __('Check this if you want to use background pattern','yeahthemes'),
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'folds' => '0',
					'label' => 'Your description about this setting',
				)
			),
			
			array( 
				'name' => 'Background Pattern',
				'desc' => 'Also, you can add more background pattern by copying image to theme folder child-theme-directory/images/bg/',
				'id' => $shortname . 'tiles',
				'type' => 'tiles',
				'options' => $bg_images,
				'settings' => array(
					'fold' => $shortname . 'checkbox_toggle',
				)
			),
			
			//Repeatable fields
			array( 
				'name' => 'Repeatable fields',
				'desc' => 'Your description about this setting<br>Click + to expand more options<br>Drag to sort',
				'id' => $shortname . 'slider',
				'std' => array(
					array(
						'text_input' => 'text input 1',
						'select_box' => 'select_box 1',
						'text_area' => 'text_area 1',
						'upload_media' => 'http://google.com',
						
						
					),
					array(
						'text_input' => 'text input 2',
						'select_box' => 'select_box 2',
						'text_area' => 'text_area 2',
						'upload_media' => 'http://google.com'
						
					)
				),
				'type' => 'repeatable_field',
				'options' => array(
					array(
						'name' => __('Text input','yeahthemes'),
						'desc' => 'Your description',
						'id' => 'text_input',
						'std' => '',
						'type' => 'text',
					),
					array(
						'name' => 'Select box',
						'desc' => 'Your description',
						'id' => 'select_box',
						'std' => '',
						'type' => 'select',
						'options' => array(
							'xxx'=> 'XXX',
							'yyy' => 'YYY',
						)
					),
					array(
						'name' => 'Text area',
						'desc' => 'Your description',
						'id' => 'text_area',
						'std' => '',
						'type' => 'textarea'
					),
					array(
						'name' => 'Upload media',
						'desc' => 'Your description',
						'id' => 'upload_media',
						'std' => '',
						'type' => 'media'
					),
				)
			
			),
			
			array( 
				'type' => 'info',
				'std' => '<h3>Default color: blue</h3><p>Something about this notification go here</p>'
			),
			
			array( 
				'type' => 'info',
				'settings' => array(
					'style' => 'light'
				),
				'std' => '<h3>Light</h3><p>Something about this notification go here</p>'
			),
			
			//Separator
			array( 
				'name' => 'Click this separator :D',
				'type' => 'separator'
			),
			
			array( 
				'type' => 'info',
				'settings' => array(
					'style' => 'yellow'
				),
				'std' => '<h3>Yellow</h3><p>Something about this notification go here</p>',
				
			),
			
			array( 
				'type' => 'info',
				'settings' => array(
					'style' => 'red'
				),
				'std' => '<h3>Red</h3><p>Something about this notification go here</p>',
				'data-folds' => $shortname . 'separator_info',
			),
			array( 
				'type' => 'info',
				'settings' => array(
					'style' => 'green'
				),
				'std' => '<h3>Green</h3><p>Something about this notification go here</p>',
			),
			
			//Wide textarea
			array( 
				'name' => 'Widefat textarea',
				'desc' => 'Your description about this setting',
				'id' => $shortname . 'widefat_textarea',
				'std' => '/* Custom CSS */',
				'type' => 'wysiwyg',
				'class' => 'yt-widefat-section',
				'settings' => array(
					'rows' => '20',
					'hide_label' => true
				)
			),
			array(
				'id'            => 'map_address',
				'name'          => __( 'Address', 'rwmb' ),
				'type'          => 'text',
				'std'           => __( 'Hanoi, Vietnam', 'rwmb' ),
			),
			array(
				'id'            => 'loc',
				'name'          => __( 'Location', 'rwmb' ),
				'desc' => 'Your description about this setting',
				'type'          => 'gmap',
				'class' 			=> 'yt-widefat-section',
				'std'           => '-6.233406,-35.049906,15',     // 'latitude,longitude[,zoom]' (zoom is optional)
				'settings'=> array(
					'address' => 'map_address',
				)                     // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
			),
			array(
				'id'       => 'oembed_sample',
				'name'     => __( 'oEmbed(s)', 'rwmb' ),
				'type'     => 'oembed',
				'std'      => __( 'Hanoi, Vietnam', 'rwmb' ),
				'settings' => array(
					'sanitize' => 'esc_url',
				)
			),
			
		),
		
	);
	
	
	// Add other metaboxes as needed

	return $meta_boxes;
}