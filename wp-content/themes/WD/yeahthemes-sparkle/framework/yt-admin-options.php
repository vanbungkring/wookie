<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Admin Options
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */
if( !function_exists( 'yt_framework_settings_options' ) ) {
	function yt_framework_settings_options(){
		
		$options = array();
		
		//heading
		$options[] = array( 
			'name' => 'Simple Fields',
			'type' => 'heading',
		);
		
		$options[] = array( 'name' => __('Enable Plain Text Logo','yeahthemes'),
			'desc' => __('Check this to enable a plain text logo rather than an image.','yeahthemes'),
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
		$options[] = array( 
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
		$options[] = array( 
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
		
		//Checkbox
		$options[] = array( 
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
		$options[] = array( 
			'name' => 'WP Colorpicker',
			'desc' => 'Your description about this setting',
			'id' => 'colorpickering',
			'std' => '#7c3',
			'type' => 'colorpicker'
		);
		// Backup Options
		$options[] = array( 
			'name' => __('Backup & Restore','yeahthemes'),
			'type' => 'heading',
			'desc' => __('Backup/Transfer your Theme options data','yeahthemes'),
		);
			
		$options[] = array( 'name' => __('Backup and Restore Options','yeahthemes'),
			'desc' => __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.','yeahthemes'),
			'id' => 'yt_backup',
			'std' => '',
			'type' => 'backup',
			'options' => ''
			
		);
		$options[] = array( 'name' => __('Transfer Theme Options Data','yeahthemes'),
			'id' => 'yt_transfer',
			'std' => '',
			'type' => 'transfer',
			'desc' => __('<br>You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options"','yeahthemes'));
		
		return ( array ) $options;
		
	}
}



add_filter( 'ytfs_options_panel_header_info', 'yt_framework_options_header_menu_link' );

if ( ! function_exists( 'yt_framework_options_header_menu_link' ) ) {
	
	function yt_framework_options_header_menu_link( $list) {
		
		$themedata = wp_get_theme();
		$themename = yt_clean_string( $themedata->Name, '_', '-' );
		
		$list[] = __('Author: ','yeahthemes') . THEMEAUTHOR;
		$list[] = __('Version: ','yeahthemes') . YEAHTHEMES_FRAMEWORK_VERSION;
		
		return $list;
	}

}


add_filter( 'ytfs_option_panel_social_network', 'yt_theme_options_social_network' );