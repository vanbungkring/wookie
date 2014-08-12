<?php
/* This file is not called from WordPress. We don't like that. */
! defined( 'ABSPATH' ) and exit;

add_action('after_setup_theme','yt_base_options', 1);

if ( !function_exists( 'yt_base_options' ) ) {
	
	function yt_base_options(){
		
		$yt_data = yt_get_options();
		
		/**
		 * Global vars
		 */
		
		$on_off = array(
			'on' => __('ON', 'yeahthemes'), 
			'off' => __('OFF', 'yeahthemes')
		);
		$show_hide = array(
			'show' => __('Show', 'yeahthemes'), 
			'hide' => __('Hide', 'yeahthemes')
		);
		/* Theme Skin */
		$skins = yt_get_option_vars( 'skins' );
		
		//Background Images Reader
		$bg_images_path = yt_get_overwritable_directory( '/images/bg/' ); // change this to where you store your bg images
		$bg_images_url = yt_get_overwritable_directory_uri( '/images/bg/' ) ; // change this to where you store your bg images
		
		
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
		/*-----------------------------------------------------------------------------------*/
		/* The Options Array */
		/*-----------------------------------------------------------------------------------*/
		
		// Set the Options Array
		global $yt_options;
		
		$yt_options = array();
		
		/*Sample Options
		/*---------------------------------------------------------------------------------------------*/
		
		
		
		//heading
		$yt_options[] = array( 
			'name' => 'Simple Fields',
			'type' => 'heading',
		);
		
		//Elite Builder
		$yt_options[] = array( 
			'name' 		=> __( 'Elite Builder', 'yeahthemes'),
			'id' 		=> 'elite_builder',
			'type' 		=> 'elite_builderx',
			'class' => 'yt-widefat-section',
			'settings' => array(
				'hide_label' => true
			)
		);
		
		
		//Text
		$yt_options[] = array( 
			'name' => 'Text',
			'desc' => 'Your description about this setting',
			'id' => 'text',
			'std' => 'Your default text',
			'type' => 'text',
			'settings' => array(
				'sanitize' => 'wp_kses',
				'sanitize_args' => array(
					'strong' => array(),
					'b' => array(),
					'em' => array(),
					'i' => array(),
					'a' => array(
						'href' => array(),
						'title' => array(),
					)
				)
			)
		);
		
		//Textarea
		$yt_options[] = array( 
			'name' => 'Textarea',
			'desc' => 'Your description about this setting',
			'id' => 'textarea',
			'std' => 'Your default textarea content',
			'type' => 'textarea'
		);
		
		
		$yt_options[] = array( 
			'type' => 'info',
			'std' => '<h3>This is a tabs contain controls</h3><p>Something about this notification go here</p>'
		);
		
		
		//Tab
		$yt_options[] = array( 
			'name' => 'Checkbox - colorpicker',
			'type' => 'tab'
		
		);
			
			//Checkbox
			$yt_options[] = array( 
				'name' => 'Checkbox',
				'desc' => 'Your description about this setting',
				'label' => 'Label for this checkbox',
				'id' => 'checkbox',
				'std' => 1,
				'settings' => array(
					'folds' => '0',
				),
				'type' => 'checkbox'
			);		
			
			//Colorpicker
			$yt_options[] = array( 
				'name' => 'WP Colorpicker',
				'desc' => 'Your description about this setting',
				'id' => 'colorpickering',
				'std' => '#7c3',
				'type' => 'colorpicker'
			);
			
		//Tab
		$yt_options[] = array( 
			'name' => 'Select box',
			'type' => 'tab'
		
		);
			
			//Select
			$yt_options[] = array( 
				'name' => 'Select',
				'desc' => 'Your description about this setting',
				'id' => 'select',
				'std' => 'left-sidebar',
				'type' => 'select',
				'options' => array(
					'right-sidebar' => 'Right sidebar',
					'left-sidebar' => 'Left sidebar',
					'middle-sidebar' => 'Middle sidebar'
				)
			);
			
			
			$yt_options[] = array( 
				'name' => 'Select alt ( Unassociated array )',
				'desc' => '',
				'id' => 'select_alt',
				'std' => 'DESC',
				'type' => 'select_alt',
				'options' => array('DESC','ASC')
				
			);
			
			//Select
			$yt_options[] = array( 
				'name' => 'Select',
				'desc' => 'Your description about this setting',
				'id' => 'msxelect',
				'std' => array( 'left-sidebar', 'middle-sidebar' ),
				'type' => 'multiselect',
				'options' => array(
					'right-sidebar' => 'Right sidebar',
					'left-sidebar' => 'Left sidebar',
					'middle-sidebar' => 'Middle sidebar'
				)
			);
		
		//Tab
		$yt_options[] = array( 
			'type' => 'tab_break',
			'settings' => array(
			)
		);
		
		//Assign url
		$url =  YEAHTHEMES_FRAMEWORK_ADMIN_URI . 'assets/images/';	
		
		//image radios
		$yt_options[] = array( 
			'name' => 'Image radios',
			'desc' => 'Your description about this setting',
			'id' => 'image_radio',
			'std' => 'right-sidebar',
			'type' => 'images',
			'settings' =>  array(
				'width' => '45px', 
				'height' => '36px'
			),
			'options' => array(
				'right-sidebar' => $url . '2cr.png',
				'left-sidebar' => $url . '2cl.png')
			
		);
		
		//Toggles
		$yt_options[] = array( 
			'name' => 'Toggles radios',
			'desc' => 'Your description about this setting',
			'id' => 'toggles',
			'std' => 'off',
			'type' => 'toggles',
			//'width' => '70px',
			'size' => 'big',
			'options' => array(
				'on' => 'ON',
				'off' => 'OFF',
			)
		);
		
		
		
		 
		$yt_options[] = array( 
			'type' => 'info',
			'std' => '<h3>This is another tabs contain controls</h3><p>We are allowed to use multiple nested-tabs inside main tab ;)</p>'
		);
		
		
		$yt_options[] = array( 
			'name' => 'Media',
			'type' => 'tab'
		);
		
		
			//Media
			$yt_options[] = array( 
				'name' => 'Upload',
				'desc' => 'Your description about this setting',
				'id' => 'media',
				'std' => 'http://192.168.1.15/framework/wp-content/uploads/2013/10/1368740_10200703834063030_1799028992_n_mixed.jpg',
				'type' => 'media'
			);
		
		//Tab
		$yt_options[] = array( 
			'type' => 'tab',
			'name' => 'Multicheckboxes/Radio',
			
		);
		
			//Multicheck
			$yt_options[] = array( 
				'name' => 'Multicheckboxes',
				'desc' => 'Your description about this setting',
				'id' => 'multicheckboxes',
				'type' => 'multicheck',
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
				
			);
			
			//Multicheck
			$yt_options[] = array( 
				'name' => 'Radio',
				'desc' => 'Your description about this setting',
				'id' => 'radio',
				'type' => 'radio',
				'std' => 'value-3',
				'options' => array(
					'value-1' => 'Value 1',
					'value-2' => 'Value 2',
					'value-3' => 'Value 3',
					'value-4' => 'Value 4'
				)
				
			);
		//Tab
		$yt_options[] = array( 
			'type' => 'tab',
			'name' => 'Tiles',
			
		);
			//Tiles
			$yt_options[] = array( 
				'name' => 'Tiles',
				'desc' => 'Also, you can add more background pattern by copying image to theme folder child-theme-directory/images/bg/',
				'id' => 'tiles_bg',
				'type' => 'tiles',
				'options' => $bg_images
			);
		
		//heading
		$yt_options[] = array( 
			'name' => 'More Fields',
			'type' => 'heading',
		);
		
		//sunheading
		$yt_options[] = array( 
			'name' => 'Advanced fields',
			'type' => 'subheading'
		);
		
		//List of pages				
		$yt_options[] = array( 
			'name' => 'Page in select',
			'desc' => 'This option return the page id for you',
			'id' => 'pages_select',
			'std' => '',
			'type' => 'select',
			'options' => yt_get_page_list()
		);
		
		//Categories
		$yt_options[] = array( 
			'name' => 'Categories in select',
			'desc' => 'This option return the Category id for you',
			'id' => 'categories_select',
			'std' => '',
			'type' => 'select',
			'options' => yt_get_category_list()
			
		);
		
		//Multiple Toggle	
		$yt_options[] = array( 
			'name' => 'Multiple Toggle buttons',
			'desc' => 'Select main content and sidebar alignment.',
			'id' => 'toggles_three',
			'std' => 'left',
			'type' => 'toggles',
			'settings' =>  array(
				'width' => '70px', 
			),
			'class' => 'big',
			'options' => array(
				'left' => 'Left',
				'center' => 'Center',
				'right' => 'Right'
			)
		);
		
		//Taxonomy checkboxes
		$yt_options[] = array( 
			'name' => 'Taxonomy checkboxes',
			'desc' => 'Your description about this setting',
			'id' => 'tax_checkboxes',
			'std' => array(),
			'type' => 'tax_checkboxes',
			'settings' => array(
				'taxonomy' => 'portfolio-type'
			)
		);
							
		//Color blocks
		$yt_options[] = array( 
			'name' => 'Color Block',
			'desc' => '',
			'id' => 'color_blocks',
			'std' => 'default',
			'type' => 'color_blocks',
			'settings' => array(
				'width' => '30px',
				'height' => '30px',
			),
			'options' => $skins
		);
		
		$yt_options[] = array( 
			'name' => 'Gallery',
			'desc' => 'Your description about this setting',
			'id' => 'gallery',
			'std' => '',
			'type' => 'gallery'
		);
		
		//Calendar
		$yt_options[] = array( 
			'name' => 'Calendar',
			'desc' => 'Your description about this setting',
			'id' => 'calendar',
			'std' => '',
			'type' => 'calendar'
		);
		
		//Time
		$yt_options[] = array( 
			'name' => 'Time',
			'desc' => 'Your description about this setting',
			'id' => 'time',
			'std' => '',
			'type' => 'Time'
		);
		
		//Border
		$yt_options[] = array( 
			'name' => 'Border',
			'desc' => 'Your description about this setting',
			'id' => 'border',
			'std' => array(
				'width' => '6',
				'style' => 'solid',
				'color' => '#33b3d3'
			),
			'type' => 'border',
		);
		
		$yt_options[] = array( 'name' => __('Boxed-layout margin','yeahthemes'),
			'desc' => __('default : top: 50px, bottom: 70px','yeahthemes'),
			'id' => 'boxed_layout_margin',
			'std' => array(
				'top' => '0px',
				'right' => '0px',
				'bottom' => '0px'
			),
			'type' => 'margin'
		);
		
		//background options
		$yt_options[] = array( 'name' => __('Body Background options','yeahthemes'),
			'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
			'id' => 'bg_options',
			'std' => array(
				'repeat' => 'no-repeat',
				'position' => 'center top',
				'attachment' => 'local',
				'size' => 'auto',
				'color' => '#CCC',
				'image' => ''
			),
			'type' => 'background_options'
		);
		
		//Typography
		$yt_options[] = array( 
			'name' => 'Typography',
			'desc' => 'Your description about this setting',
			'id' => 'typo',
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
		);
		
		//UI slider				
		$yt_options[] = array( 
			'name' => 'UI Slider',
			'desc' => 'Your description about this setting',
			'id' => 'ui_slider',
			'std' => '20',					
			'type' => 'uislider',
			'settings' => array(
				'min' => 0,
				'max' => 100,
				'step' => 10,
				'unit' => 'px'
			)
		);
		
		//Checkbox toggle
		$yt_options[] = array( 
			'name' => 'Check box toggle',
			'desc' => 'Your description about this setting',
			'id' => 'checkbox_toggle',
			'std' => 0,
			'type' => 'checkbox',
			'class' => 'yt-section-toggle-checkbox',
			'settings' => array(
				'folds' => '0',
				'label' => __('Check this if you want to use background pattern','yeahthemes'),
			),
			
		);
		
		$yt_options[] = array( 
			'name' => 'Background Pattern',
			'desc' => 'Also, you can add more background pattern by copying image to theme folder child-theme-directory/images/bg/',
			'id' => 'tiles',
			'type' => 'tiles',
			'options' => $bg_images,
			'settings' => array(
				'fold' => 'checkbox_toggle',
			)
		);
		
		
		$yt_options[] = array( 'name' => __('Folding Select','yeahthemes'),
			'desc' => __('Change the value to show/hide another fields','yeahthemes'),
			'id' => 'toggle_selector',
			'std' => 'input',
			'type' => 'select',
			'options' => array(
				'input' => 'Text input',
				'textarea' => 'Textarea'
			),
			'settings' => array(
				'folds' => '0',
			)
		);
		
		//Text
		$yt_options[] = array( 
			'name' => 'Text',
			'desc' => 'Your description about this setting',
			'id' => 'plain_logo',
			'std' => 'Your default textx',
			'type' => 'text',
			'settings' => array(
				'fold' => 'toggle_selector',
				'fold_value' => 'input',
				'sanitize' => 'wp_kses',
				'sanitize_args' => array(
					'strong' => array(),
					'b' => array(),
					'em' => array(),
					'i' => array(),
					'a' => array(
						'href' => array(),
						'title' => array(),
					)
				)
			)
		);
		
		//Textarea
		$yt_options[] = array( 
			'name' => 'Textarea',
			'desc' => 'Your description about this setting',
			'id' => 'textarea',
			'std' => 'Your default textarea content',
			'type' => 'textarea',
			'settings' => array(
				'fold' => 'toggle_selector',
				'fold_value' => 'textarea',
			)
		);
		
		//Wide textarea
		$yt_options[] = array( 
			'name' => 'Widefat textarea',
			'desc' => 'Your description about this setting',
			'id' => 'widefat_textarea',
			'std' => '/* Custom CSS */',
			'type' => 'textarea',
			'class' => 'yt-widefat-section',
			'settings' => array(
				'rows' => '20'
			)
		);
		
		
		$yt_options[] = array( 
			'name' => 'Repeatable fields',
			'type' => 'subheading'
		);
		
		//Repeatable fields
		$yt_options[] = array( 
			'name' => 'Repeatable fields',
			'desc' => 'Your description about this setting<br>Click + to expand more options<br>Drag to sort',
			'id' => 'slider',
			'std' => array(
				array(
					'text_input' => 'text input 1z',
					'select_box' => 'yyy',
					'text_area' => 'text_area 1',
					'upload_media' => 'http://google.com'
					
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
					'std' => 'Sample value for text',
					'type' => 'text'
				),
				array(
					'name' => 'Select box',
					'desc' => 'Your description',
					'id' => 'select_box',
					'std' => 'yyy',
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
					'std' => 'Sample value for textarea',
					'type' => 'textarea'
				),
				array(
					'name' => 'Upload media',
					'desc' => 'Your description',
					'id' => 'upload_media',
					
					'std' => 'http://s.wordpress.org/screenshots/3.5/ss1-dashboard.png',
					'type' => 'media'
				),
			)
		
		);
		
		//Sidebar per category			
		
		$yt_options[] = array( 
			'name' => 'Enable Sidebar per Category',
			'desc' => 'You must check this checkbox to apply/change category for sidebar',
			'id' => 'enable_sidebar_per_cat',
			'std' => 0,
			'type' => 'checkbox',
			'class' => 'yt-section-toggle-checkbox',
			'settings' => array(
				'label' => 'Check this to enable Sidebar per Category.',
				'folds' => '0',
			)
		
		);
		
		$yt_options[] = array( 
			'name' => 'Sidebar per Category',
			'desc' => 'Select the sidebar for category, default will be Main Sidebar',
			'id' => 'sidebar_per_cat',
			
			'std' => '',
			'type' => 'category_sidebar',
			'settings' => array(
				'fold' => 'enable_sidebar_per_cat',
			)
		);
		
		
		//sunheading
		$yt_options[] = array( 
			'name' => 'Notification & Separator',
			'type' => 'subheading'
		);
		
		$yt_options[] = array( 
			'type' => 'info',
			'std' => '<h3>Default color: blue</h3><p>Something about this notification go here</p>'
		);
		
		$yt_options[] = array( 
			'type' => 'info',
			'settings' => array(
				'style' => 'light'
			),
			'std' => '<h3>Light</h3><p>Something about this notification go here</p>'
		);
		
		//Separator
		$yt_options[] = array( 
			'name' => 'Click this separator :D',
			'type' => 'separator',
			
		);
		
		$yt_options[] = array( 
			'type' => 'info',
			'settings' => array(
				'style' => 'yellow'
			),
			'std' => '<h3>Yellow</h3><p>Something about this notification go here</p>',
		);
		
		$yt_options[] = array( 
			'type' => 'info',
			'settings' => array(
				'style' => 'red'
			),
			'std' => '<h3>Red</h3><p>Something about this notification go here</p>',
		);
		$yt_options[] = array( 
			'type' => 'info',
			'settings' => array(
				'style' => 'green'
			),
			'std' => '<h3>Green</h3><p>Something about this notification go here</p>',
		);
		
		//===========================================================================
		//Locate the functions from file
		locate_template( '/includes/theme-options.php', true) ; 

		//If function exist, get the options
		if( function_exists( 'yt_theme_options' ) ){
			$yt_options_extended =& $yt_options;
			$yt_options_extended = yt_theme_options();
		}
		
		$yt_options = apply_filters( 'yt_theme_options', $yt_options );
		
		/**
		 * Option: Subscribe & Connect
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_subscribeconnect', array(	
			array( 
				'name' => __('Subscribe & Connect','yeahthemes'),
				'type' => 'heading',
				'settings' => array(
					'icon' => 'subscribeconnect'
				)
			
			)
		) ) );
		/**
		 * Subscribe & Connect - Subscribe
		 */
		 
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_subscribeconnect_subscribe', array(	
		
			array( 
				'name' => __('API','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Configure Subscription API','yeahthemes')
			
			),
			array( 
				'name' => __('MailChimp API key','yeahthemes'),
				'desc' => __('Get your API at <a href="http://admin.mailchimp.com/account/api-key-popup" target="_blank">link</a>','yeahthemes'),
				'id' => 'mailchimp_api',
				'std' => '',
				'type' => 'text'
			
			),
			array( 
				'name' => __('Google API key','yeahthemes'),
				'desc' => __('Get your API at <a href="https://code.google.com/apis/console?hl=en#access" target="_blank">link</a>','yeahthemes'),
				'id' => 'google_api',
				'std' => '',
				'type' => 'text'
			
			),		
			array( 
				'name' => __('Twitter API key','yeahthemes'),
				'type' => 'separator',
				'desc' => __('Your application\'s OAuth settings','yeahthemes'),
			),
			array(
				'name' => '',
				'std' => __('<h3>Create your application and copy the Consumer key & Access token at <a href="https://dev.twitter.com/apps/">Twitter API</a></h3>','yeahthemes'),
				'type' => 'info'
			),
			array(
				'name' => __('Consumer key','yeahthemes'),
				'desc' => __('Enter your Consumer key from <strong>OAuth settings</strong>.','yeahthemes'),
				'id' => 'twitter_consumer_key',
				'std' => '',
				'type' => 'text'
			),
			array(
				'name' => __('Consumer secret','yeahthemes'),
				'desc' => __('Enter your Consumer secret from <strong>OAuth settings</strong>.','yeahthemes'),
				'id' => 'twitter_consumer_secret',
				'std' => '',
				'type' => 'text'
			),
			array(
				'name' => __('Access token','yeahthemes'),
				'desc' => __('Enter your Access token from <strong>Your access token</strong>.','yeahthemes'),
				'id' => 'twitter_access_token',
				'std' => '',
				'type' => 'text'
			),
			array( 
				'name' => __('Access token secret','yeahthemes'),
				'desc' => __('Enter your Access token secret from <strong>Your access token</strong>.','yeahthemes'),
				'id' => 'twitter_access_token_secret',
				'std' => '',
				'type' => 'text'
			)
		) ) );
		/**
		 * Subscribe & Connect - Connect
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_subscribeconnect_connect', array(				
			array( 
				'name' => __('Connect','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Add your Social Networks URLs','yeahthemes')
			),
			array( 
				'name' => __('RSS','yeahthemes'),
				'desc' => __('Add your RSS url. (default is <a href="'.get_bloginfo('rss2_url').'">WordPress site Feed</a>)<br>http://example.com/feed','yeahthemes'),
				'id' => 'scl_rss',
				'std' => get_bloginfo('rss2_url'),
				'type' => 'text',
			),
			array( 
				'name' => __('Email Adress','yeahthemes'),
				'desc' => __('Add an Email address.<br>Eg:your.email@domain.com','yeahthemes'),
				'id' => 'scl_email',
				'std' => '',
				'type' => 'text',
			),
			array( 
				'name' => __('Facebook','yeahthemes'),
				'desc' => __('Add your Facebook url.<br>http://www.facebook.com/username','yeahthemes'),
				'id' => 'scl_facebook',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Twitter','yeahthemes'),
				'desc' => __('Add your Twitter url.<br>https://twitter.com/username','yeahthemes'),
				'id' => 'scl_twitter',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Google Plus','yeahthemes'),
				'desc' => __('Add your Google Plus url.<br>http://plus.google.com/userID','yeahthemes'),
				'id' => 'scl_googleplus',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Youtube','yeahthemes'),
				'desc' => __('Add your Youtube url.<br>http://www.youtube.com/user/username','yeahthemes'),
				'id' => 'scl_youtube',
				'std' => '#',
				'type' => 'text',
			),
		
		) ) );

		/**
		 * Advanced Setting
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings', array(
			array( 
				'name' => __('Advanced Settings','yeahthemes'),
				'type' => 'heading',
				'settings' => array(
					'icon' => 'advancedsettings'
				)
			)
		) ) );
		/**
		 * Advanced Setting - Icons.
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings_icons', array(
			array( 
				'name' => __('Icons','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Add favicon ,Apple/Windows icons','yeahthemes'),
			),
			array( 
				'name' => __('Custom Favicon','yeahthemes'),
				'desc' => __('Upload a 32px x 32px .png/.gif/.ico image that will represent your website\'s favicon.','yeahthemes'),
				'id' => 'favicon',
				'std' => '',
				'type' => 'media'
			),
			array(
				'name' => __('Upload icons for Apple devices','yeahthemes'),
				'type' => 'separator'
			),
			array( 
				'name' => __('Fluid App icon 512x512','yeahthemes'),
				'desc' => __('This is the icon for Fluid App to create a Real Mac App of your website that appears in your Dock. ','yeahthemes'),
				'id' => 'apple_fluid_app',
				'std' => '',
				'type' => 'media'
			),
			array( 
				'name' => __('Apple icon 57x57','yeahthemes'),
				'desc' => __('This is default icon.','yeahthemes'),
				'id' => 'apple_icon_57',
				'std' => '',
				'type' => 'media'
			),
			array( 
				'name' => __('Apple icon 72x72','yeahthemes'),
				'desc' => __('This is icon for iPad.','yeahthemes'),
				'id' => 'apple_icon_72',
				'std' => '',
				'type' => 'media'
			),
			array( 
				'name' => __('Apple icon 114x114 (Retina)','yeahthemes'),
				'desc' => __('This is icon for iPhone Retina.','yeahthemes'),
				'id' => 'apple_icon_114',
				'std' => '',
				'type' => 'media'
			),
			array( 
				'name' => __('Apple icon 144x144 (Retina)','yeahthemes'),
				'desc' => __('This is icon for iPad Retina.','yeahthemes'),
				'id' => 'apple_icon_144',
				'std' => '',
								'type' => 'media'
			),
			array(
				'name' => __('Upload icons for Windows 8 devices (Metro UI)','yeahthemes'),
				'type' => 'separator'
			),
			array( 
				'name' => __('Metro UI icon','yeahthemes'),
				'desc' => __('The size of icon should be 512x512 in pixel and png format.','yeahthemes'),
				'id' => 'window_icon',
				'std' => '',
				'type' => 'media'
			),
			array( 
				'name' => __('Metro UI icon background color','yeahthemes'),
				'desc' => '',
				'id' => 'window_icon_bgcolor',
				'std' => '',
				'type' => 'colorpicker'
			)
		) ) );
		
		/**
		 * Advanced Settings - Login Area.
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings_loginarea', array(
			array( 
				'name' => __('Login Area','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Styling your Admin Login area','yeahthemes'),
			),
			array( 
				'name' => __('Admin Login Logo','yeahthemes'),
				'desc' => __('Upload a custom logo for admin login page. Dimension: 320px*100px, Retina: 640px*200px','yeahthemes'),
				'id' => 'login_logo',
				'std' => '',
				'type' => 'media'
			),
			array( 
				'name' => __('Link Color','yeahthemes'),
				'desc' => __('This color will be used for links','yeahthemes'),
				'id' => 'login_link_color',
				'std' => '',
				'type' => 'colorpicker'
			),
			array( 
				'name' => __('Background options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'login_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto', 
					'color' => '',
					'image' => ''
				),
				'type' => 'background_options'
			),
		
		) ) );
		
		/**
		 * Advanced Settings - Maintenance.
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings_maintenance', array(
			array( 
				'name' => __('Maintenance','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Take your site offline for the Maintenance','yeahthemes'),
			),
			array( 
				'name' => __('Take the Site Offline','yeahthemes'),
				'desc' => __('This will show an offline message. Except for administrators, nobody will be able to access the site','yeahthemes'),
				'id' => 'offline_mode',
				'std' => '0',
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'folds' => '0',
				),
			),
			array( 
				'name' => __('Offline heading','yeahthemes'),
				'desc' => __('Heading of Message','yeahthemes'),
				'id' => 'offline_heading',
				'std' => 'We\'ll be back soon!',
				'type' => 'text',
				'settings' => array(
					'fold' => 'offline_mode',
				),
			),
			array( 
				'name' => __('Offline Message','yeahthemes'),
				'desc' => __('Message context','yeahthemes'),
				'id' => 'offline_about_msg',
				'std' => 'We are busy updating the site for you and will be back shortly!<br>So please, Comeback later !',
				'type' => 'textarea',
				'settings' => array(
					'fold' => 'offline_mode',
				),
			),
			array( 
				'name' => __('Meta Description','yeahthemes'),
				'desc' => __('Define a description of your web page that appear on Search engine','yeahthemes'),
				'id' => 'offline_meta_description',
				'std' => 'Everything you need to create a trendy, uniquely beautiful website without any of coding knowledge.',
				'type' => 'textarea',
				'settings' => array(
					'fold' => 'offline_mode',
				),
			),
			array( 
				'name' => __('Footer','yeahthemes'),
				'desc' => __('Footer infomation ( Email, Social networks, ...)','yeahthemes'),
				'id' => 'offline_footer',
				'std' => 'Copyright 2014. We\'re also on <a href="#">Twitter</a>, <a href="#">Facebook</a>, <a href="#">Google+</a>',
				'type' => 'textarea',
				'settings' => array(
					'fold' => 'offline_mode',
				),
			),
			array( 
				'name' => __('Text Color','yeahthemes'),
				'desc' => __('This color will be used for Maintenance page text','yeahthemes'),
				'id' => 'offline_text_color',
				'std' => '',
				'type' => 'colorpicker'
			),
			array( 
				'name' => __('Link Color','yeahthemes'),
				'desc' => __('This color will be used for links','yeahthemes'),
				'id' => 'offline_link_color',
				'std' => '',
				'type' => 'colorpicker'
			),
			
			array( 
				'name' => __('Background options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'offline_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto', 
					'color' => '',
					'image' => ''
				),
				'type' => 'background_options'
			),
			array( 
				'name' => __('Countdown','yeahthemes'),
				'desc' => '',
				'id' => 'offline_countdown',
				'std' => 'show',
				'type' => 'toggles',
				'options' => $show_hide
				
			),
			array( 
				'name' => __('Countdown Launch Date','yeahthemes'),
				'desc' => __('Select a date from the calendar.','yeahthemes'),
				'id' => 'offline_launch_date',
				'std' => '',
				'type' => 'calendar'
			),
			array( 
				'name' => __('Countdown Launch Time','yeahthemes'),
				'desc' => __('Enter the launch time e.g. 10:30','yeahthemes'),
				'id' => 'offline_launch_time',
				'std' => '10:30',
				'type' => 'time'
			)

		) ) );
		/**
		 * Advanced Settings - Miscs.
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings_miscs', array(
			array( 
				'name' => __('Miscs','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('The other settings','yeahthemes'),
			),
			array( 
				'name' => __('Overwrite default media size automatically','yeahthemes'),
				'desc' => '',
				'id' => 'allow_overwrite_media_size',
				'std' => 1,
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'label' => __('Switch this off if you want to resize media manually','yeahthemes'),
					
				),				
			),
			array( 
				'name' 	=> __('Header Code','yeahthemes'),
				'desc' 	=> __('Your custom tags in header (eg: Custom Meta tags, CSS, etc ...)','yeahthemes'),
				'id' 	=> 'header_code',
				'std' 	=> '',
				'type' 	=> 'textarea',
				'settings' => array(
					'sanitize' => false
				),
			),
			array( 
				'name' 	=> __('Footer Code ','yeahthemes'),
				'desc' 	=> __('Your custom tags in footer(Analytics, custom script etc ...)','yeahthemes'),
				'id' 	=> 'footer_code',
				'std' 	=> '',
				'type' 	=> 'textarea',
				'settings' => array(
					'sanitize' => false
				),
			)

		) ) );
		
		/**
		 * Backup & Restore
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_backuprestore', array(
			array( 
				'name' => __('Backup & Restore','yeahthemes'),
				'type' => 'heading',
				'desc' => __('Backup/Transfer your Theme options data','yeahthemes'),
				'settings' => array(
					'icon' => 'backuprestore'
				)
			),
			array( 
				'name' => __('Backup and Restore Options','yeahthemes'),
				'desc' => __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.','yeahthemes'),
				'std' => '',
				'type' => 'backup',
				'options' => ''
				
			),
			array( 
				'name' => __('Transfer Theme Options Data','yeahthemes'),
				'std' => '',
				'type' => 'transfer',
				'desc' => __('<br>You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options"','yeahthemes')
				
			)
		) ) );
		
		// Backup Options
		
	}	
}

/**
 * Fontfaces Variable for option
 * 
 * @access public
 * @return array
 * @since 1.0
 */
if( !function_exists( 'yt_get_option_vars' ) ) {
	function yt_get_option_vars( $type = '' ){
		
		if( empty( $type ) )
			return array();

		if( !in_array( $type, array( 'fontfaces', 'footer_columns', 'skins', 'entrance_animations' ) ) )
			return array();

		/**
		 * Fontfaces
		 */
		if( 'fontfaces' == $type )
			$var = apply_filters( 'yt_option_vars_fontfaces', array(
				'Arial, Helvetica, sans-serif'								=> 'Arial, Helvetica, sans-serif',
				'"Comic Sans MS", cursive' 									=> '"Comic Sans MS", cursive',
				'"Courier New", Courier, monospace'							=> '"Courier New", Courier, monospace',
				'Georgia, "Times New Roman", Times, serif' 					=> 'Georgia, "Times New Roman", Times, serif',
				'"Helvetica Neue", Helvetica, Arial, sans-serif'			=> '"Helvetica Neue", Helvetica, Arial, sans-serif',				
				'"Lucida Console", Monaco, monospace'						=> '"Lucida Console", Monaco, monospace',
				'"Lucida Grande", "Lucida Sans Unicode", sans-serif' 		=> '"Lucida Grande", "Lucida Sans Unicode", sans-serif',
				'"MS Serif", "New York", serif' 							=> '"MS Serif", "New York", serif',				
				'"Palatino Linotype", "Book Antiqua", Palatino, serif' 		=> '"Palatino Linotype", "Book Antiqua", Palatino, serif',				
				'Tahoma, Geneva, sans-serif'								=> 'Tahoma, Geneva, sans-serif',
				'"Times New Roman", Times, serif'							=> '"Times New Roman", Times, serif ',
				'"Trebuchet MS", Arial, Helvetica, sans-serif'				=> '"Trebuchet MS", Arial, Helvetica, sans-serif',				
				'Verdana, Geneva, sans-serif' 								=> 'Verdana, Geneva, sans-serif',
			) );
		
		$url =  YEAHTHEMES_FRAMEWORK_ADMIN_URI . 'assets/images/footer-columns/';

		/**
		 * Footer columns
		 */
		if( 'footer_columns' == $type )
			$var = apply_filters( 'yt_option_vars_footer_columns', array(
				'col-sm-12'	 												=> $url . 'col_12@2x.gif',
				'col-sm-6_col-sm-6' 										=> $url . 'col_6_6@2x.gif',
				'col-sm-4_col-sm-4_col-sm-4' 								=> $url . 'col_4_4_4@2x.gif',
				'col-sm-3_col-sm-3_col-sm-3_col-sm-3' 						=> $url . 'col_3_3_3_3@2x.gif',
				'col-sm-2_col-sm-2_col-sm-2_col-sm-2_col-sm-2_col-sm-2' 	=> $url . 'col_2_2_2_2_2_2@2x.gif',
				'col-sm-4_col-sm-8' 										=> $url . 'col_4_8@2x.gif',
				'col-sm-8_col-sm-4' 										=> $url . 'col_8_4@2x.gif',
				'col-sm-3_col-sm-3_col-sm-6' 								=> $url . 'col_3_3_6@2x.gif',
				'col-sm-3_col-sm-6_col-sm-3' 								=> $url . 'col_3_6_3@2x.gif',
				'col-sm-6_col-sm-3_col-sm-3' 								=> $url . 'col_6_3_3@2x.gif',
				'col-sm-2_col-sm-4_col-sm-6' 								=> $url . 'col_2_4_6@2x.gif',
				'col-sm-2_col-sm-2_col-sm-2_col-sm-6' 						=> $url . 'col_2_2_2_6@2x.gif',
				'col-sm-2_col-sm-2_col-sm-4_col-sm-4' 						=> $url . 'col_2_2_4_4@2x.gif',

				'col-sm-2_col-sm-4_col-sm-2_col-sm-4' 						=> $url . 'col_2_4_2_4@2x.gif',
				'col-sm-2_col-sm-4_col-sm-4_col-sm-2' 						=> $url . 'col_2_4_4_2@2x.gif',
				'col-sm-6_col-sm-2_col-sm-2_col-sm-2' 						=> $url . 'col_6_2_2_2@2x.gif',
				'col-sm-2_col-sm-2_col-sm-2_col-sm-3_col-sm-3' 				=> $url . 'col_2_2_2_3_3@2x.gif',
				'col-sm-3_col-sm-3_col-sm-2_col-sm-2_col-sm-2' 				=> $url . 'col_3_3_2_2_2@2x.gif',
				'col-sm-2_col-sm-3_col-sm-2_col-sm-3_col-sm-2' 				=> $url . 'col_2_3_2_3_2@2x.gif',
			) );
		
		/**
		 * Skins
		 */
		if( 'skins' == $type )
			$var = apply_filters( 'yt_option_vars_skins', array(
				'#33b3d3' => 'light-blue',
				'#D64343' => 'red',
				'#00a3d3' => 'dodger-blue',
				'#516899' => 'dark-blue',
				'#77cc33' => 'lime-green',
				'#7870CC' => 'blue-marguerite',	
				'#66B58F' => 'silver-tree',
				'#f39c12' => 'orange',
				'#7cc576' => 'light-green',
				'#ea4c89' => 'pink',
				'#A252B1' => 'purple',
				'#58cb8e' => 'spring-green',
				'#7257a3' => 'violet',
				'#7A997B' => 'laurel',
				'#2cae8c' => 'turquoise',
				'#69B980' => 'silver-lime',
				'#34495e' => 'wet-asphalt',
				'#9cb265' => 'green-smoke',
				'#9b59b6' => 'amethyst',
				'#95a5a6' => 'concrete',
				'#27ae60' => 'nephritis',
				'#e74c3c' => 'alizarin',
				'#ee6a4c' => 'burnt-sienna',
				'#2980b9' => 'belize-hole',
				'#2c3e50' => 'midnight-blue',
				'#16a085' => 'green-sea',
				'#766CE4' => 'medium-purple',
				'#E07798' => 'deep-blush'
			) );

		/**
		 * Entrance
		 */
		if( 'entrance_animations' == $type )
			$var = apply_filters( 'yt_option_vars_css3_entrance_animations', array(
				'bounceIn',
				'bounceInDown',
				'bounceInUp',
				'bounceInLeft',
				'bounceInRight',
				'fadeIn',
				'fadeInUp',
				'fadeInDown',
				'fadeInLeft',
				'fadeInRight',
				'fadeInZoom',
				'flipIn',
				'flipInX',
				'flipInY',
				'lightSpeedIn',
				'pageTop',
				'pageBottom',
				'pageLeft',
				'pageRight',
				'rollIn',
				'rotateIn',
				'rotateInDownLeft',
				'rotateInDownRight',
				'rotateInUpLeft',
				'rotateInUpRight',
				'slideInDown',
				'slideInLeft',
				'slideInRight',
				'slideInRight',
				'zoomIn',
				'zoomOut'

			));

		return ( array ) $var;
	}
}
