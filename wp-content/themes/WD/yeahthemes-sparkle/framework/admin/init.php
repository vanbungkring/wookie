<?php
/* This file is not called from WordPress. We don't like that. */
! defined( 'ABSPATH' ) and exit;

/**
 * @title:			Yeahthemes Options Framework Init
 * @description:	Yeahthemes Options Framework Initialization
 * @version: 		1.0
 * @author:		Yeahthemes
 * @author URI:	http://Yeahthemes.com
 * @license:		(c) Yeahthemes
 *
 * @package:		yeahthemes
 * @subpackage:	framework/admin
 * @since 1.0
 */

/**
 * Definitions
 *
 * @since 1.0
 */
$themedata;
$themedata = wp_get_theme();
$theme_stylesheet = get_option( 'stylesheet' );
$theme_dir_slug = preg_replace( '/[^a-zA-Z0-9-_]/', '', strtolower( trim( str_replace( '-', '_', $theme_stylesheet  ) ) ) );

//echo preg_replace( '/[^a-zA-Z0-9-_]/', '', strtolower( trim( str_replace( '_', '-', 'fhdjsafh32423+++++_____++++__---d-as-d=-das_'  ) ) ) );
define( 'YEAHTHEMES_FRAMEWORK_URI', 		YEAHTHEMES_URI . 'framework/' );
define( 'YEAHTHEMES_FRAMEWORK_DIR', 		YEAHTHEMES_DIR . 'framework/' );
define( 'YEAHTHEMES_FRAMEWORK_CSS_URI', 	YEAHTHEMES_FRAMEWORK_URI . 'css/' );
define( 'YEAHTHEMES_FRAMEWORK_JS_URI', 		YEAHTHEMES_FRAMEWORK_URI . 'js/' );
define( 'YEAHTHEMES_FRAMEWORK_VERSION', '1.0.0' );
define( 'YEAHTHEMES_FRAMEWORK_ADMIN_DIR', 	YEAHTHEMES_FRAMEWORK_DIR . 'admin/' );
define( 'YEAHTHEMES_FRAMEWORK_ADMIN_URI', 	YEAHTHEMES_FRAMEWORK_URI . 'admin/' );

define( 'YEAHTHEMES_INCLUDES_URI', 			YEAHTHEMES_URI . 'includes/' );
define( 'YEAHTHEMES_INCLUDES_DIR', 			YEAHTHEMES_DIR . 'includes/' );
define( 'YEAHTHEMES_INCLUDES_IMG_URI', 		YEAHTHEMES_INCLUDES_URI . 'images/' );
define( 'YEAHTHEMES_INCLUDES_CSS_URI', 		YEAHTHEMES_INCLUDES_URI . 'css/' );
define( 'YEAHTHEMES_INCLUDES_JS_URI', 		YEAHTHEMES_INCLUDES_URI . 'js/' );
define( 'THEMENAME', $themedata->Name );
define( 'THEMESLUG', preg_replace( '/[^a-zA-Z0-9-_]/', '', strtolower( trim( str_replace( '_', '-', $themedata->Name  ) ) ) )  );
define( 'THEMEVERSION', $themedata->Version  );
define( 'THEMEAUTHOR', $themedata->Author );
		
define( 'YEAHTHEMES_WPML_OPTIONS_CONTEXT', ucfirst( THEMENAME ) . ' Theme Options' );
global $sitepress;	

/*Define theme option key constant*/
if( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE !== $sitepress->get_default_language() ) {
	
	define( 'YEAHTHEMES_THEME_OPTIONS', 'yeahthemes_theme_options_' . $theme_dir_slug . '_' . ICL_LANGUAGE_CODE );
	
}else{
	
	define( 'YEAHTHEMES_THEME_OPTIONS', 'yeahthemes_theme_options_' . $theme_dir_slug );
	
}

/**
 * Load Files
 *
 * @since 1.0.0
 */
 

$yt_admin_framework_includes = apply_filters( 'yt_admin_framework_includes',
	array(
		'yt-conditional-tags.php',
		'yt-functions.php',
		'yt-functions-options.php',
		'yt-functions-admin.php',
		'yt-functions-field-types.php',
		'yt-options-settings.api.php',
		'yt-theme-customize.api.php',
		'yt-meta-box-api.php',
		'yt-default-metaboxes.php',
		'yt-metaboxes-example.php',
		
	)
);
			
foreach ( $yt_admin_framework_includes as $include ) { 

	require_once( 'includes/' . $include );
	 
}
/**
 * Initialize Theme Options, Framework Settings, Theme customizer
 */
add_action( 'after_setup_theme', 'yt_admin_options_init', 3 );

if( !function_exists( 'yt_admin_options_init' ) ) {
	function yt_admin_options_init(){

		

		if ( class_exists( 'YT_Options_Framework' ) ) {

			global $yt_options;
			
			$themename = wp_get_theme()->get('Name');
			
			/*echo $yt_options;
			print_r($yt_options);*/
			//$yt_settings_page = new YT_Options_Framework();
			
			$GLOBALS['yt_theme_options'] = new YT_Options_Framework_Theme_Options();
			
			/*option key that store the option data
			 * 
			 * The name of the constant. Only Alphanumeric and underscore (_) are allowed 
			 * Recommended to use with prefix
			 */
			$option_name = YEAHTHEMES_THEME_OPTIONS;
			
			/*option array you want to generate*/
			$option_fields = $yt_options;
			
			/**
			 * option type
			 *
			 * if you use for theme options, set it to 'theme_mod', 
			 * the option data will be store in theme mod using you theme_mods_$theme_slug as key
			 * this is very important for wp customize
			 */
			
			$admin_menu = array(
				/**
				 * Administration Menus location
				 * http://codex.wordpress.org/Adding_Administration_Menus
				 */									
				'page_title' 	=> __('Yeahthemes Theme options','yeahthemes'),		//$page_title
				'menu_title' 	=> THEMENAME, 				//$menu_title
				'capability' 	=> 'edit_theme_options',								//$capability
				'menu_slug' 	=> 'yt-theme-options',								//$menu_slug
				'icon_url'		=> null,
				'position'	=> '50.6'
			);
			
			$config = array(
				'prefix' => 'ytto_',
				'heading' => $themename,
				'option_name' => $option_name,
				'option_fields' => $yt_options,
				'admin_bar' => true,
				'admin_bar_title' => __('Theme Options', 'yeahthemes'),
				'menu_function' => 'add_menu_page',
				'menu_args' => $admin_menu 
			);
			
			$GLOBALS['yt_theme_options']->init( $config );
			
			
		
		}
		
		/**
		 * Init Theme customizer
		 */
		if ( class_exists( 'YT_Theme_Customize' ) && apply_filters( 'yt_support_theme_customizer', true ) ) {
			
			
			$GLOBALS['yt_theme_customize'] = new YT_Theme_Customize();
			
			$GLOBALS['yt_theme_customize']->init( YEAHTHEMES_THEME_OPTIONS, $yt_options );
		
		}
		
	}

}

/**
 * Class YT_Options_Framework_Theme_Options
 * @since 1.0.0
 */

class YT_Options_Framework_Theme_Options extends YT_Options_Framework{
	
	
	/**
	 * Init
	 */
	public function init( $args = array() ){
		
		parent::init( $args );
		
		$this->extended_action_hooks();
		
	}
	
	/**
	 * Overwite parent theme option links on admin menu bar
	 * @since 1.0.0
	 */
	public function admin_bar_menu_shortcuts() {
		
		$this->admin_bar_add_root_menu( __('( Y )', 'yeahthemes') , $this->_menu_slug );
		$this->admin_bar_add_sub_menu( __('Documentation', 'yeahthemes'), apply_filters( 'yt_admin_top_menu_bar_document_url', '' ), 'yt-theme-documentation', $this->_menu_slug );
		$this->admin_bar_add_sub_menu( __('Support forum', 'yeahthemes'), apply_filters( 'yt_admin_top_menu_bar_support_url', '' ), 'yt-theme-support', $this->_menu_slug );
	}
	/**
	 * Extended action hooks
	 */
	function extended_action_hooks(){
		
		add_action('admin_menu', array( &$this, 'register_submenu_page' ));
		
		/* Redirect to theme options page after activating*/
		global $pagenow;
		
		if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ){
			
			wp_redirect( admin_url( 'admin.php?page=' . $this->_menu_slug ));
			exit;
			
		}
	}
	
	/**
	 * Register menu page
	 */
	function register_submenu_page() {
		
		//add_submenu_page
		
		global $yt_options_page;
		
		$yt_options_page = add_submenu_page( 
			$this->_menu_slug, 
			$this->_heading  . ' ' . $this->_admin_bar_title, 
			__( 'Theme Options', 'yeahthemes' ), 
			'edit_theme_options', 
			'yt-theme-options', 
			array( &$this, 'load_template')
		); 
		// add_submenu_page( 
		// 	$this->_menu_slug, 
		// 	__('Documentation', 'yeahthemes'), 
		// 	__('Documentation', 'yeahthemes'), 
		// 	'manage_options', 
		// 	'yt-theme-doc', 
		// 	array( &$this, 'document_page_callback')
		// ); 


	    global $submenu;

	    $submenu[$this->_menu_slug][] = array( __('Documentation', 'yeahthemes'), 'manage_options', apply_filters( 'yt_admin_top_menu_bar_document_url', '' ));
	    $submenu[$this->_menu_slug][] = array( __('Support forum', 'yeahthemes'), 'manage_options', apply_filters( 'yt_admin_top_menu_bar_support_url', '' ));
	
		add_action("load-$yt_options_page", array( &$this, 'screen_options_help' ));
	
	}	
	/**
	 * Support page callback
	 */
	function document_page_callback() {
		
		/*echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>My Custom Submenu Page</h2>';
		echo '</div>';*/
		
	
	}
	/**
	 * Help screen
	 */
	function screen_options_help() {

		global $yt_options_page, $current_screen;
	 
		if ($current_screen->id != $yt_options_page)
			return;


		$help_contents = apply_filters( 'yt_theme_options_screen_help_contents', array() );

		if( !empty( $help_contents ) ){
			foreach ( $help_contents as $tab ) {
				if( is_array( $tab ) && !empty( $tab['id'] ) && !empty( $tab['title'] ) )
					$current_screen->add_help_tab( $tab );
			}
		}

	}
	
}
//add_action('admin_enqueue_scripts', 'myHelpPointers');

function myHelpPointers() {
	
	$screen = get_current_screen();

	//echo $screen->id;

   	//First we define our pointers 
   	$pointers = array(
		array(
			'id' => 'xyz123',   // unique id for this pointer
			'screen' => 'dashboard', // this is the page hook we want our pointer to show on
			'target' => '#toplevel_page_yt-theme-options', // the css selector for the pointer to be tied to, best to use ID's
			'title' => 'Theme Options',
			'content' => __( 'You can configuring options, skins, typography here', 'yeahthemmes'),
			'position' => array( 
			   'edge' => 'left', //top, bottom, left, right
			   'align' => 'middle' //top, bottom, left, right, middle
			   )
			)
		// more as needed
		);
   //Now we instantiate the class and pass our pointer array to the constructor 
	if( class_exists('WP_Help_Pointer')){
		$myPointers = new WP_Help_Pointer($pointers);
	}
}