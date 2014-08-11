<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Admin Scripts
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */

/**
 *Register and load javascript
 *
 * @return wp_enqueue_style and wp_enqueue_script
 *
 **/

if( !function_exists( 'yt_enqueue_scripts' ) ) {
	
	function yt_enqueue_scripts(){

		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( 'bootstrap', 		YEAHTHEMES_FRAMEWORK_CSS_URI . "bootstrap$suffix.css" );
		wp_register_script( 'bootstrap-js',		YEAHTHEMES_FRAMEWORK_JS_URI . "bootstrap$suffix.js", 			array( 'jquery' ), '3.0.3', true);
		
	}
	add_action( 'wp_enqueue_scripts', 'yt_enqueue_scripts' );
}

/**
 * Localize sripts via global object
 *
 * @since 1.0
 */
add_filter( 'yt_yeahthemes_global_object', 'yt_localize_script' );
function yt_localize_script($object){
	
	/*Must have variable for comment form validator*/
	$object['_vars']['commentErrorName'] = __('You forgot to enter your name.<br/>','yeahthemes');
	$object['_vars']['commentErrorEmail'] = __('You forgot to enter an email address.<br/>','yeahthemes');
	$object['_vars']['commentErrorInvalidEmail'] = __('Please, enter a valid email address.<br/>','yeahthemes');
	$object['_vars']['commentErrorInvalidCaptcha'] = __('Enter the captcha<br/>','yeahthemes');
	$object['_vars']['commentErrorComment'] = __('You forgot to enter your comment.<br/>','yeahthemes');
	
	return $object;
}