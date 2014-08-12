<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Browser Detector
 *
 * @package   Yeahthemes
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @since     1.0
 */
class YT_Browser_Detector { 
    /** 
     *   Figure out what browser is used, its version and the platform it is 
     *   running on. 
	 *
     */ 
	
	
	/*	Usage: 
		$browser = YT_Browser_Detector::detect(); 
		echo 'You browser is '.$browser['name'].' version '.$browser['version'].' running on '.$browser['platform'];
	*/
	
	public static function detect() { 
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']); 
		

        // Identify the browser. Check Opera and Safari first in case of spoof. Let Google Chrome be identified as Safari. 
        if (preg_match('/opera/', $userAgent)) { 
            $name = 'opera'; 
        } 
        elseif (preg_match('/webkit/', $userAgent)) { 
            $name = 'webkit'; 
        } 
        elseif (preg_match('/msie/', $userAgent)) { 
            $name = 'msie'; 
        } 
        elseif (preg_match('/mozilla/', $userAgent) && !preg_match('/compatible/', $userAgent)) { 
            $name = 'mozilla'; 
        } 
        else { 
            $name = 'unrecognized'; 
        } 

        // What version? 
        if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches)) { 
            $version = $matches[1]; 
        } 
        else { 
            $version = 'unknown'; 
        } 

        // Running on what platform? 
        if (preg_match('/linux/', $userAgent)) { 
            $platform = 'linux'; 
        } 
        elseif (preg_match('/macintosh|mac os x/', $userAgent)) { 
            $platform = 'mac'; 
        } 
        elseif (preg_match('/windows|win32/', $userAgent)) { 
            $platform = 'windows'; 
        } 
        else { 
            $platform = 'unrecognized'; 
        } 

        return array( 
            'name'      => $name, 
            'version'   => $version, 
            'platform'  => $platform, 
            'userAgent' => $userAgent ,
        ); 
    } 
}
/**
 * CSS Parser
 *
 * @package   Yeahthemes
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @since     1.0
 */
class YT_CSS_Parser {
	
	protected $cssstr;
	/**
	* Constructor function for PHP5
	*
	*/
	public function __construct()  	{
	   $this->css = array();
	   $this->cssstr = "";
	}

/**
    * Parses an entire CSS file
    *
    * @param mixed $filename CSS File to parse
    */
	public function parse_file($file_name)
	{
		$fh = yt_file_open( $file_name, "r") or die( "Error opening file $file_name" );
		$css_str = yt_file_read($fh, filesize($file_name));
		yt_file_close($fh);
		return( $this->parse_css( $css_str ) );
	}

	/**
    * Parses a CSS string
    *
    * @param string $css_str CSS to parse
    */
	public function parse_css($css_str)
	{
		$this->cssstr = $css_str;
		$this->result = array();

		
		$css_str = trim(preg_replace('/\s*\{[^}]*\}/', '', $css_str));
		
		preg_match_all('/(?<=\.)(.*?)(?=\:before)/',$css_str,$match);
		$this->result = isset($match[0]) ? $match[0] : '';
		
		return $this->result;
		
	}
	
	public function get_string_between($string, $start, $end){
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}


}
/**
 * Create custom menu nav item fields
 *
 * @package   Yeahthemes
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @since     1.0
 */
class YT_Walker_Nav_Menu_Fields{
	var $_options = array();
	
	var $_menu_fields = array();
	
	var $_nav_class = '';
	
	function __construct( $options, $nav_class ){
		
		if( !is_array( $options ) && empty( $options ) && !class_exists( $nav_class ) )
			return;
			
		$this->_options = array_map( array( $this, 'normalize' ) , $options );
		$this->_menu_fields = apply_filters( 'yt_admin_nav_menu_field_types', array( 'text', 'textarea', 'select', 'checkbox' ) );
		$this->_nav_class = $nav_class;
		//print_r($this->_options);
		/*Start the awesomeness*/
		$this->init();
	}
	
	public function init(){
		
		add_action( 'wp_update_nav_menu_item', array( $this, 'admin_custom_nav_update' ),10, 3);	
		
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'admin_setup_custom_nav_menu_item' ) );
		
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'admin_nav_menu_walker_edit' ),10,2 );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		
		add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ) );
		
		add_action( 'yt_admin_nav_menu_fields', array( $this, 'admin_nav_custom_field_generator' ) ); 
	}
	/**
	 * Helper: Fields normalizer
	 */
	static function normalize( $field ){
		
		$default = array(
			'id' => '',
			'name' 		=> '',
			'desc' 		=> '',
			'std' 		=> '',
			'type' 		=> '',
			'options' 	=> array(),
			'size'	=> 'wide'
		);

		$field = wp_parse_args( $field, $default );

		return $field;
		
	}
	/**
	 * Helper: Fields normalizer
	 */
	public function admin_scripts(){
		
	}
	
	public function admin_print_scripts(){
		
	}
	
	/**
	 * Saves new field to postmeta for navigation
	 */
	public function admin_custom_nav_update( $menu_id, $menu_item_db_id, $args ) {
		
		foreach( ( array ) $this->_options as $option ){
			if ( !$option['id'] && !in_array( $option['type'], $this->_menu_fields ) ) 
				return;
			
			$menu_item_id = 'menu-item-' . $option['id']; 
			$meta_key = '_' . str_replace( '-', '_' , $menu_item_id );
			
			//print_r($_REQUEST[$menu_item_id]);
			//exit();
			if ( isset( $_REQUEST[$menu_item_id] ) && is_array( $_REQUEST[$menu_item_id] ) ) {
				
				$meta_value = !empty( $_REQUEST[$menu_item_id][$menu_item_db_id] ) ? $_REQUEST[$menu_item_id][$menu_item_db_id] : '';
				
				update_post_meta( $menu_item_db_id, $meta_key, $meta_value );
				
			}
		
		}
	}
	/**
	 * Adds value of new field to $item object that will be passed to YT_Walker_Nav_Menu_Edit
	 */
	public function admin_setup_custom_nav_menu_item( $menu_item ) {
		foreach( ( array ) $this->_options as $option ){
			if ( $option['id'] && in_array( $option['type'], $this->_menu_fields ) ){
				
				$menu_item_id = 'menu-item-' . $option['id'];
				$meta_key = '_' . str_replace( '-', '_' , $menu_item_id );
				
				$item_key = str_replace( '-', '_' , $option['id'] );
				
				$menu_item->$item_key = get_post_meta( $menu_item->ID, $meta_key, true );
			}
		}
		return $menu_item;
	}
	/**
	 * Filter the Walker class used when adding nav menu items.
	 *
	 * @param string $class   The walker class to use. Default 'Walker_Nav_Menu_Edit'.
	 * @param int    $menu_id The menu id, derived from $_POST['menu'].
	 */
	public function admin_nav_menu_walker_edit( $walker, $menu_id) {
		
		if( $this->_nav_class && class_exists( $this->_nav_class ) )
			return $this->_nav_class;
		
		return $walker;
	}
	
	public function admin_nav_custom_field_generator( $item ){
		
		
		if( !is_array( $this->_options ) && empty( $this->_options ) && !class_exists( $this->_nav_class ) )
			return;
		$output = '<div class="yt-custom-menu-fields-wrapper">';
		foreach( ( array ) $this->_options as $option ){
			if( in_array( $option['type'], $this->_menu_fields ) ){
				
				$id = $option['id'];
				$item_key = str_replace( '-', '_' , $id );
				$std = $option['std'];
				$options = $option['options'];
				$size = !empty( $option['size'] ) && 'thin' == $option['size'] ? ' description-' . $option['size'] : ' description-wide';
				
				$output .= '<p class="field-' . esc_attr( $option['id'] ) . ' description' . $size . '">';
					$output .= '<label for="edit-menu-item-' . $id . '-' . esc_attr( $item->ID ) . '">';
					$output .= !empty( $option['name'] ) ? $option['name'] . '<br />' : '';
					
					$id_attr = 'edit-menu-item-' . $id . '-' . esc_attr( $item->ID );
					$name_attr = 'menu-item-' . $id . '[' . $item->ID . ']';
					$val_attr = isset( $item->$item_key ) ? $item->$item_key : $std;
					
					switch( $option['type'] ){
						/**
						 * Text
						 */
						case 'text':
							$output .= '<input type="text" id="' . esc_attr( $id_attr ) . '" class="widefat code edit-menu-item-' . $id . '" name="' . esc_attr( $name_attr ) .'" value="' . esc_attr( $val_attr ) . '" />';
						break;
						/**
						 * Textarea
						 */
						case 'textarea':
							$output .= '<textarea id="' . esc_attr( $id_attr ) . '" class="widefat edit-menu-item-' . $id . '" rows="3" cols="20" name="' . esc_attr( $name_attr ) .'">' . esc_html( $val_attr ) . '</textarea>';
						break;
						/**
						 * Select
						 */
						case 'select':

							$output .= '<select id="' . esc_attr( $id_attr ) . '" class="widefat edit-menu-item-' . $id . '" name="' . esc_attr( $name_attr ) .'">';
						
							foreach( $options as $k => $v) {
								$output .= '<option value="' . esc_attr( $k ) . '"' . selected( $k, $val_attr, false )  . '>' . $v . '</option>';
							}
							
							$output .= '</select>';
						break;
						/**
						 * Checkbox
						 */
						case 'checkbox':
							$output .= '<input type="checkbox" id="' . esc_attr( $id_attr ) . '" value="1" name="' . esc_attr( $name_attr ) .'" ' . checked( $val_attr, 1, false ) . ' />';
							$output .= !empty( $option['desc'] ) ? $option['desc'] : '';
						break;
						
						default:
							$output .= '';
					}
					
					/*Description*/
					$output .= !empty( $option['desc'] ) && $option['type'] !== 'checkbox' ? '<span class="description">' . $option['desc'] . '</span>' : '';
					$output .= '</label>';
				$output .= '</p>';
				
			}
		}
		$output .= '</div>';
		echo $output;
		
	}
}

/**
 * Custom fields for Category
 *
 * @package   Yeahthemes
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @since     1.0
 */
class YT_Category_Custom_Fields{
	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_print_styles', array( $this, 'admin_print_styles' ), 10 );
		add_action( 'admin_print_footer_scripts', array( $this, 'admin_print_footer_scripts' ), 10 );
		// Add form
		add_action( 'category_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'category_edit_form_fields', array( $this, 'edit_category_fields' ), 10 );
		add_action( 'created_category', array( $this, 'save_category_fields' ) );
		add_action( 'edited_category', array( $this, 'save_category_fields' ) );
	}
	/**
	 * Style
	 *
	 * @access public
	 * @return void
	 */
	function admin_enqueue_scripts(){
		global $hook_suffix, $pagenow, $current_screen;
		
		if( 'edit-tags.php' !== $pagenow && 'edit-category' !== $current_screen->id )
    		return;
   		wp_enqueue_style( 'wp-color-picker' );
	}

	function admin_print_styles(){
		global $hook_suffix, $pagenow, $current_screen;

    	if( 'edit-tags.php' !== $pagenow && 'edit-category' !== $current_screen->id )
    		return;

		$output = '<style type="text/css">
		.wp-picker-container .button{
			width: auto;
		}
		</style>';
		$output = str_replace(array("\r", "\n", "\t"), "", $output);

		echo $output . "\n";
	}
	/**
	 * Scripts
	 *
	 * @access public
	 * @return void
	 */
	function admin_print_footer_scripts() {
    	global $hook_suffix, $pagenow, $current_screen;

    	if( 'edit-tags.php' !== $pagenow && 'edit-category' !== $current_screen->id )
    		return;

    	$output = '<script type="text/javascript">
		/* <![CDATA[ */
		;(function ($) {
			$(\'.yt-color-picker\').each(function(index, element) {
				var $el = $(this);			
				var myOptions = {
					
					defaultColor: $el.data(\'std\'),
					change: function(event, ui){},
					clear: function() {},
					hide: true,
					palettes: true
				};
				 
				$el.wpColorPicker(myOptions);

			});
		})(jQuery);
		/* ]]> */
		</script>';

		$output = str_replace(array("\r", "\n", "\t"), "", $output);

		echo $output . "\n";
	}

	/**
	 * Category thumbnail fields.
	 *
	 * @access public
	 * @return void
	 */
	public function add_category_fields() {
		?>
		
		<div class="form-field">
			<label for="display_type"><?php _e( 'Accent color', 'yeahthemes' ); ?></label>
			<input data-std="" id="category_color" class="yt-color-picker" name="category_meta[color]" type="text" value="" />
		</div>
		
		<?php
	}

	/**
	 * Edit category thumbnail field.
	 *
	 * @access public
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_category_fields( $term ) {
		$t_id = $term->term_id;
    	$cat_meta = get_option( "category_$t_id");
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Accent color', 'yeahthemes' ); ?></label></th>
			<td>
				<input data-std="" id="category_color" class="yt-color-picker" name="category_meta[color]" type="text" value="<?php echo isset( $cat_meta['color'] ) ? esc_attr( $cat_meta['color'] ) : '';?>" size="7" />
			</td>
		</tr>
		
		<?php
	}

	/**
	 * save_category_fields function.
	 *
	 * @access public
	 * @param mixed $term_id Term ID being saved
	 * @return void
	 */
	public function save_category_fields( $term_id ) {
		
		if ( isset( $_POST['category_meta'] ) ) {
			$cat_meta = get_option( "category_$term_id") ? get_option( "category_$term_id") : array();

	        $cat_keys = array_keys($_POST['category_meta']);
			//print_r($cat_keys); die();
            foreach ($cat_keys as $key){
	            if (isset($_POST['category_meta'][$key])){
	                $cat_meta[$key] = $_POST['category_meta'][$key];
	            }
	        }
	        //save the option array
	        update_option( "category_$term_id", $cat_meta );
    	}
	}
	/**
	 * get_category_meta function.
	 *
	 * @access public
	 * @param mixed $term_id Term ID being saved
	 * @param mixed $key
	 * @return mixed
	 */
	public function get_category_meta( $term_id, $key ){
		if( !$term_id )
			return;
		
		$cat_meta = get_option( "category_$term_id");

		return isset( $key ) ? $cat_meta[$key] : '';
	}

}
