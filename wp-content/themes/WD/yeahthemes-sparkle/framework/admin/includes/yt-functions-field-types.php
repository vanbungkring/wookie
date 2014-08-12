<?php 
/* This file is not called from WordPress. We don't like that. */
! defined( 'ABSPATH' ) and exit;

/**
 * Helper class for fields
 *
 * @package   Yeahthemes
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @since     1.0
 */

if( !class_exists( 'Walker_Category_Checklist' ) )
	require_once ABSPATH . 'wp-admin/includes/template.php';

if( !class_exists( 'YT_Walker_Category_Checklist' )){
	class YT_Walker_Category_Checklist extends Walker_Category_Checklist {

		private $name;
		private $id;

		function __construct( $name = '', $id = '' ){
			$this->name = $name;
			$this->id = $id;
		}

		function start_el( &$output, $cat, $depth = 0, $args = array(), $id = 0 ) {
			extract($args);
			if ( empty($taxonomy) ) 
				$taxonomy = 'category';
			$class = in_array( $cat->term_id, $popular_cats ) ? ' class="popular-category"' : '';
			$id = $this->id . '-' . $cat->term_id;
			$checked = checked( in_array( $cat->term_id, $selected_cats ), true, false );
			$output .= "\n<li id='{$taxonomy}-{$cat->term_id}'$class>"
			. '<label class="selectit"><input value="' 
			. $cat->term_id . '" type="checkbox" name="' . $this->name 
			. '[]" id="in-'. $id . '"' . $checked 
			. disabled( empty( $args['disabled'] ), false, false ) . ' /> ' 
			. esc_html( apply_filters('the_category', $cat->name ) ) 
			. '</label>';
		}
	}
}
 
if( !class_exists( 'YT_Field_Type_Helper' ) ){

class YT_Field_Type_Helper {
	/*
	 * checkbox field
	 *
	 * @param $id
	 * @param $val
	 */
	static function checkbox( $id, $val, $class = '', $label ){
		
		return '<label><input type="checkbox" class="' . esc_attr( $class ) . 'yt-checkbox yt-input" name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" value="1" ' . checked( $val, 1, false ) . ' />' . ( $label ? $label : '' ) . '</label>';
		
	}
	/*
	 * Colorpicker
	 *
	 * @param $id
	 * @param $val
	 * @param $std
	 */
	static function colorpicker( $id, $val, $std ){
		
		$output = '<input data-std="' . esc_attr( $std ) . '" id="' . esc_attr( $id ) . '_colorpicker" class="yt-colorpicker" name="' . esc_attr( $id ) . '" type="text" value="' . yt_valid_hex_color( $val ) . '" />';
		
		return $output;
			
	}
	/*
	 * Text field
	 *
	 * @param $id
	 * @param $val
	 */
	static function text( $id, $val, $class = '', $attr='' ){
		
		return '<input class="yt-input' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" name="' . esc_attr( $id ) . '" id="'. esc_attr( $id ) .'" type="text" value="'. esc_html( $val ) .'"' . ( $attr ? $attr : '' ) . ' />';
		
	}
	/*
	 * Textarea
	 *
	 * @param $id
	 * @param $val
	 * @param $rows
	 */
	static function textarea( $id, $val, $rows = '8'){
		
		return '<textarea class="yt-textarea" name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" rows="' . esc_attr( $rows ) . '">' . esc_textarea( $val ) . '</textarea>';
	
	}
	/*
	 * Select
	 *
	 * @param $id
	 * @param $val
	 * @param $options
	 * @param $class
	 * @param $attr
	 * @param $min
	 * @param $max
	 * @param $step
	 * @param $ext
	 */
	static function select( $id, $val, $options = array(), $class='', $attr = '', $min = 0, $max = 0, $step = 1, $ext = ''){
		
		$output = '';
		$output .= '<div class="yt-select-wrapper' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '"' . ( $attr ? ' ' . $attr : '' ) . '>
		
			<select class="yt-select yt-input" name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '">';
				
			if( $max > 0 && $max > $min && $step ){
				
				for ( $i = $min; $i < $max; $i+=$step ){ 
				
					$i_val = $ext ? $i . $ext : $i;
					$output .= '<option value="' . esc_attr( $i_val ) . '" ' . selected( $val, $i_val, false ) . '>' . esc_attr( $i_val ) . '</option>';	
					
				}
				
			}else{
				
				foreach ($options as $k => $v) {	
						
					$output .= '<option value="' . esc_attr( $k ) . '" ' . selected( $val , $k, false ) . ' >' . esc_attr( $v ) . '</option>';	
					 
				}
				
			}
			
		$output .= '</select>
		
		</div>';
		
		return $output;
	}
	
	/**
	 * Native media library uploader
	 *
	 * @uses get_option()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @param $id
	 * @param $val
	 *
	 * @return string
	 */
	static function media( $id, $val, $title, $media_by = 'url', $attr = '', $input = 'input' ){

		$output = '';
		$media_url = '';
		
		$media_id = $val && $media_by == 'id' ? intval($val) : '';
		$media_url = $val ? $val : '';
		$media_value = ( $media_by == 'url' ) ? esc_url( $media_url ) : esc_attr( $media_id );
			
		$hide_remove_btn = $media_url ? '' : 'yt-hidden';
		
		$media_input = '<input type="text" class="upload yt-input yt-media-input" name="' . esc_attr( $id ) . '" id="'. esc_attr( $id ) .'_upload_url" value="' . $media_value . '" />';
		if( $input != 'input' ){
			$media_input = '<textarea type="text" class="upload yt-textarea yt-media-input" name="' . esc_attr( $id ) . '" id="'. esc_attr( $id ) .'_upload_url">' . esc_textarea( $media_value ) . '</textarea>';
		
		}
		
		$output .= $media_input;	
		$output .= '<div class="yt-button-action">
			<span class="button yt-media-upload-button yt-upload-media yt-button hide-if-no-js" id="upload_' . esc_attr( $id ) . '" data-by="' . ( $media_by && in_array( $media_by, array( 'url', 'id' ) ) ? $media_by : 'url'  ) . '" data-title="' . ( $title ? esc_attr( $title ) : __('Add Media', 'yeahthemes')) . '" data-id="' . esc_attr( $media_id ) . '" '.$attr.'><i class="yt-icon-upload-cloud"></i> ' . __('Upload','yeahthemes') . '</span>
			<span class="button yt-media-remove-button yt-button hide-if-no-js '. esc_attr( $hide_remove_btn ) . '" title="' . esc_attr( $id ) . '">' . __('Remove','yeahthemes') . '</span>
		</div>' . "\n";
		$output .= '<div class="yt-screenshot">';
		
		
		/*parse_str(parse_url($media_url, PHP_URL_QUERY), $args);
		print_r($args);*/
		
		
		$media_att = wp_get_attachment_image_src( $media_id, 'medium' );
		
		$screenshot = $media_by == 'url' ? $media_url : $media_att[0];
		
		$media_type = pathinfo( yt_clean_url( $screenshot ), PATHINFO_EXTENSION );
		
		if( !empty( $screenshot ) && in_array( $media_type, array( 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico' ) ) ){	
		
	    	$output .= '<a class="yt-uploaded-image" href="' . esc_attr( $screenshot ) . '">
				<span class="yt-img-border yt-transparent-bg"><img class="yt-option-image" id="image_' . esc_attr( $id ) . '" src="' . esc_url( $screenshot ) . '" alt="" /></span>
			</a>';
			
		}
			
		$output .= '</div>'; 
	
		return $output;
		
	}
	
}

} //end class_exists

/**
 * Builds the HTML for each of the available option types by calling those
 * function with call_user_func and passing the arguments to the second param.
 *
 * All fields are required!
 *
 * @param     array       $args
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_display_by_type' ) ) {
	
	function yt_display_by_type( $_args = array() ) {
	
		/* allow filters to be executed on the array */
		$_args = apply_filters( 'yt_display_by_type', $_args );
		
		/* build the function name */
		$function_name_by_type = str_replace( '-', '_', 'yt_field_type_' . $_args['type'] );
		
		/* call the function & pass in arguments array */
		if ( function_exists( $function_name_by_type ) ) {
			
			return call_user_func( $function_name_by_type, $_args );
			
		} else {
			 
			return apply_filters('yt_field_type_' . $_args['type'] , $_args );
			
		}
		
	}
	
}

if ( ! function_exists( 'yt_field_val_std' ) ) {
	
	function yt_field_val_std( $val, $std ){
		
		
		if( is_array( $val ) && is_array( $std ) ){
			
			foreach( $val as $k => $v ) {
			
				if ( !isset( $val[$k] ) ) {
				
					$val[$k] = isset( $std[$k] ) && $std[$k] ? $std[$k] : '' ;
				
				}
			
			}
			
		}else{
			
			$val = isset( $val ) ? $val : ( isset( $std ) && $std ? $std : '' );
			
		}
		
		return $val;
		
	}
}

/**
 * Background type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_background_options' ) ) {
  
	function yt_field_type_background_options( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		$output = '';
		
		$stored_value = $_args['value'];
		
		if( empty( $_args['std'] ))
			return;
				
		foreach($_args['std'] as $bgoptions_k => $bgoptions_v ){
			
			$saved_data = isset( $stored_value[$bgoptions_k] ) && $stored_value[$bgoptions_k] ? $stored_value[$bgoptions_k] : $bgoptions_v;
			
			switch( $bgoptions_k ){

				case 'image':

					$output .=  '<div class="yt-clear"></div>
					<fieldset class="yt-section-media bgoptions-image">
						 
						 <legend>' . __('Upload background image', 'yeahthemes') . '</legend>';

						$output .=  yt_field_type_media(
							array(
								'id' => $_args['id'] . '[image]', 
								'value' => $saved_data,
								'std' => '',
								'name' => $_args['id'] . '[image]'
							)
						);

					$output .= '</fieldset>';

				break;
				
				case 'repeat':
				
					$repeat_options = array(
						'no-repeat'=> __( 'No Repeat', 'yeahthemes' ),
						'repeat'=> __( 'Repeat', 'yeahthemes' ),
						'repeat-x'=> __( 'Repeat x', 'yeahthemes' ),
						'repeat-y'=> __( 'Repeat y', 'yeahthemes')
					);	
					
					$output .=  YT_Field_Type_Helper::select( 
						$_args['id'] . '[repeat]', 
						$saved_data, 
						$repeat_options, 
						'bgoptions-repeat tip-tip', 
						'original-title="' . __( 'Background repeat', 'yeahthemes' ) . '"' 
					);
					
				break;
				
				case 'position':
				
					$position_options = array(
						'center top'=>__( 'Center Top', 'yeahthemes' ),
						'center center'=>__( 'Center Center', 'yeahthemes' ),
						'center bottom'=>__( 'Center Bottom', 'yeahthemes' ),
						'left top'=>__( 'Left Top', 'yeahthemes' ),
						'left bottom'=>__( 'Left Bottom', 'yeahthemes' ),
						'right top'=>__( 'Right Top', 'yeahthemes' ),
						'right bottom'=>__( 'Right Bottom', 'yeahthemes' )
					);	
					
					$output .=  YT_Field_Type_Helper::select( 
						$_args['id'] . '[position]', 
						$saved_data, 
						$position_options, 
						'bgoptions-position tip-tip', 
						'original-title="' . __( 'Background position', 'yeahthemes' ) . '"' 
					);
					
				break;
				
				case 'attachment':
				
					$attachment_options = array(
						'scroll'=>__( 'Scroll', 'yeahthemes' ),
						'fixed'=>__( 'Fixed', 'yeahthemes' ),
						'local'=>__( 'Local', 'yeahthemes' )
					);	
					
					$output .=  YT_Field_Type_Helper::select( 
						$_args['id'] . '[attachment]', 
						$saved_data, 
						$attachment_options, 
						'bgoptions-attachment tip-tip', 
						'original-title="' . __( 'Background attachment', 'yeahthemes' ) . '"' 
					);
					
				break;
				
				case 'size':
				
					$size_options = array(
						'auto'=>__('Auto','yeahthemes'),
						'cover'=>__('Cover','yeahthemes'),
						'contain'=>__('Contain','yeahthemes')
					);
					
					$output .=  YT_Field_Type_Helper::select( 
						$_args['id'] . '[size]', 
						$saved_data, 
						$size_options, 
						'bgoptions-size tip-tip', 
						'original-title="' . __( 'Background size', 'yeahthemes' ) . '"' 
					);
					
				break;
				
				case 'color':
				
					$output .= '<div class="yt-colorpicker-wrapper bgoptions-size tip-tip" original-title="' . __('Background color', 'yeahthemes' ) . '">';
						$output .= YT_Field_Type_Helper::colorpicker( $_args['id'] . '[color]', $saved_data, $_args['std']['color'] );
					$output .= '</div>';
					
				break;
			}
		}
		
		return apply_filters( 'yt_field_type_background_options', $output, $_args );
	
	}
	
}
/**
 * Border type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_border' ) ) {
  
	function yt_field_type_border( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		$output = '';
		
		$stored_value = isset( $_args['value'] ) && $_args['value'] ? $_args['value'] : ( isset( $_args['std'] ) && $_args['std'] ? $_args['std'] : array() ) ;
		
		/* Border Width */
		
		$output .= YT_Field_Type_Helper::select( 
			$_args['id'] . '[width]', 
			$stored_value['width'], 
			array(), 
			'tip-tip', 
			'original-title="' . __( 'Border width', 'yeahthemes' ) . '"',
			$min = 0, 
			$max = 21,
			$step = 1,
			$ext = 'px'
		);
		
		/* Border Style */
		$styles = array(
			'none'=> __('None', 'yeahthemes'),
			'solid'=> __('Solid', 'yeahthemes'),
			'dashed'=> __('Dashed', 'yeahthemes'),
			'dotted'=> __('Dotted', 'yeahthemes')
		);
		
		$output .=  YT_Field_Type_Helper::select( 
			$_args['id'] . '[style]', 
			$stored_value['style'], 
			$styles, 
			'border-style tip-tip', 
			'original-title="' . __( 'Border style', 'yeahthemes' ) . '"' 
		);
		
		/* Border Color */
		$output .= '<div class="yt-colorpicker-wrapper border-color tip-tip" original-title="' . __('Border color', 'yeahthemes' ) . '">';
			$output .= YT_Field_Type_Helper::colorpicker( $_args['id'] . '[color]', $stored_value['color'], $_args['std']['color'] );
		$output .= '</div>';
						
		return apply_filters( 'yt_field_type_border', $output, $_args );
	
	}
	
}

/**
 * Sidebar per Category type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_category_sidebar' ) ) {
  
	function yt_field_type_category_sidebar( $_args = array() ) {
		
		$output = '';
		
		$stored_value = $_args['value'];
		
		/* Get the category array */				
		$categories = yt_get_category_list(); 
		
		
		//get sidebars
		global $wp_registered_sidebars;
		
		foreach ( ( array ) $categories as $cat_k => $cat_v ) {         
				
			$output .= '<div class="yt-select-wrapper yt-clear">
				<p><span class="yt-clear">' . $cat_v . ' (' . $cat_k . ')</span>';		
			$output .= '<select class="select yt-select" name="' . esc_attr( $_args['id'] ) . '[cat-' . esc_attr( $cat_k ) . ']" id="' . esc_attr( $_args['id'] ) . '[cat-' . esc_attr( $cat_k ) .']">';
			
			foreach( $wp_registered_sidebars as $sidebar_id => $sidebar ){
				
				$output .= '<option value="' . esc_attr( $sidebar_id ) . '" ' . selected( isset( $stored_value['cat-' . $cat_k] ) ? $stored_value['cat-' . $cat_k] : '', $sidebar_id, false) . ' />' . $sidebar['name'] . '</option>';	
				 
			} 
			$output .= '</select></p></div>';
		}
		
		return apply_filters( 'yt_field_type_category_sidebar', $output, $_args );
	}
	
}

/**
 * Calendar input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_calendar' ) ) {
  
	function yt_field_type_calendar( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		/* Value */
		$stored_value = stripslashes( yt_field_val_std( $_args['value'], $_args['std'] ) );
		
		$output = YT_Field_Type_Helper::text( $_args['id'], $stored_value, 'yt-input-calendar' );
		$output .= '<span class="yt-datepicker-image" title="' . __('Pick date', 'yeahthemes') . '"></span>';
		
		return apply_filters( 'yt_field_type_calendar', $output, $_args );
	}
	
}

/**
 * Checkbox input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_checkbox' ) ) {
  
	function yt_field_type_checkbox( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		/* Value */
		
		$stored_value = !isset( $_args['value'] ) ? '' : $_args['value'];
		
		$label = ( isset( $_args['settings']['label'] ) ) ? $_args['settings']['label'] : '';
		
		$fold = '';
		
		if ( array_key_exists( 'folds', isset( $_args['settings'] ) ? $_args['settings'] : array() ) ) {
			
			$fold = 'yt-fold-trigger ';
		
		}
		
		$output = YT_Field_Type_Helper::checkbox( $_args['id'], $stored_value, $fold, $label );
		
		return apply_filters( 'yt_field_type_checkbox', $output, $_args );
		
	}
	
}

/**
 * Color_blocks input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_color_blocks' ) ) {
  
	function yt_field_type_color_blocks( $_args = array() ) {

		$options_keys = array_keys( $_args['options'] );
		
		$std = isset( $_args['value'] ) 
				&& $_args['value'] 
				&& in_array( $_args['value'], $options_keys ) 
			? $_args['value'] 
			: ( isset( $_args['std'] ) 
				&& in_array( $_args['std'], $options_keys ) ? $_args['std'] : $options_keys[0]);
		
		$output = '<div class="yt-radio-color-blocks">';
		
		
		foreach( $_args['options'] as $color => $name ) {
			$output .= '<label data-value="' . esc_attr( $color ) . '" title="' . esc_attr( $name ) . '" style="background-color:' . esc_attr( $color ) . ';' . ( isset ( $_args['settings']['height'] ) && isset( $_args['settings']['width'] ) ? 'width:' . esc_attr( $_args['settings']['width'] ) . ';height:' . esc_attr( $_args['settings']['width'] ) . ';"' : '') . ' class="' . ( $color === $std ? 'active' : '' ) . '"></label>';				
			
		}
		
		$output .= '<input class="yt-input yt-input-hidden" name="' . esc_attr( $_args['id'] ) . '" type="hidden" value="' . esc_attr( $std ) . '" />';
		$output .= '</div>';
		
		return apply_filters( 'yt_field_type_color_blocks', $output, $_args );
		
	}
	
}

/**
 * Colorpicker input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_colorpicker' ) ) {
  
	function yt_field_type_colorpicker( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		$output = YT_Field_Type_Helper::colorpicker( $_args['id'], $_args['value'], $_args['std'] );
		
		return apply_filters( 'yt_field_type_colorpicker', $output, $_args );
		
	}
	
}
/**
 * Image input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_image' ) ) {
  
	function yt_field_type_image( $_args = array() ) {
		
		/* Value */
		$src = $_args['std'];
		
		$output = '<img src="' . esc_url( $src ) . '" ' . ( isset( $_args['settings']['width'] ) && isset( $_args['settings']['height'] ) ? ' style="width:' . esc_attr( $_args['settings']['width'] ) . ';height:' . esc_attr( $_args['settings']['height'] ) . ';">' : '' );

		return apply_filters( 'yt_field_type_image', $output, $_args );
	
	}
	
}

/**
 * Gallery type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_gallery' ) ) {
  
	function yt_field_type_gallery( $_args = array() ) {
		
		/* Value */
		$src = $_args['std'];
		
			
		$image_gallery = $_args['value'];
		$field_name = ( isset( $_args['name'] ) && $_args['name'] ? $_args['name'] : __('Gallery', 'yeahthemes') );
		$add_gallery_title = __('Add images to ', 'yeahthemes') . $field_name;
		$delete_gallery_title = __('Delete all', 'yeahthemes');
		$delete_class = ' yt-hidden';
		
		$output = '<div class="yt-gallery-wrapper">
			<ul class="yt-gallery-list yt-clear">';
				
				if( $image_gallery ){
					
					$delete_class = '';
					//print_r($image_gallery);
					$attachments = array_filter( explode( ',', $image_gallery ) );
					
					//print_r($attachments);
					if( $attachments ){
						
						foreach ( $attachments as $attachment_id ) {
							
							$output .= '<li class="image yt-transparent-bg" data-attachment-id="' . esc_attr( $attachment_id ) . '">
								<span>' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '</span>
								<ul class="yt-gallery-actions">
									<li><a href="#" class="yt-gallery-delete" title="' . __( 'Delete', 'yeahthemes' ) . '"><i class="yt-icon-trash"></i></a></li>
								</ul>
							</li>';
							
						}
						
					}
					
				}
		
		$output .= '</ul>
		
			<input type="hidden" class="yt-gallery-hidden-input" id="' . esc_attr( $_args['id'] ) . '" name="' . esc_attr( $_args['id'] ) . '" value="' . esc_attr( $image_gallery ) . '" />
			<a href="#add-image-gallery" class="yt-gallery-add-image button yt-button hide-if-no-js" title="' . esc_attr( $add_gallery_title ) . '" data-title="' . esc_attr( $add_gallery_title ) . '" data-button="' . __('Add to ', 'yeahthemes') . ( isset( $_args['name'] ) && $_args['name'] ? esc_attr( $_args['name'] ) : __('Gallery', 'yeahthemes') ) . '">' . $add_gallery_title . '</a>
			<a href="#delete-all-gallery" class="yt-gallery-delete-all button yt-button hide-if-no-js' . esc_attr( $delete_class ) . '" title="' . esc_attr( $delete_gallery_title ) . '" data-confirm="' . sprintf( __('You are going to delete all image from %s. Process?', 'yeahthemes'), $field_name ) . '">' . $delete_gallery_title . '</a>
			
		</div>';
		
		return apply_filters( 'yt_field_type_gallery', $output, $_args );
	
	}
	
}
/**
 * Images input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_images' ) ) {
  
	function yt_field_type_images( $_args = array() ) {
		
						
		$stored_value = isset( $_args['value'] ) && $_args['value'] ? $_args['value'] : ( isset( $_args['std'] ) && $_args['std'] ? $_args['std'] : $_args['options'][0] );
		
		$output = '<div class="yt-radio-images">';
		
		foreach ( ( array ) $_args['options'] as $key => $option ){ 
			
			$output .= '<label data-value="' . esc_attr( $key ) . '" class="' . ( $key == $stored_value ? 'active' : '') . '">';
			$output .= '<img src="' . esc_url( $option ) . '" alt="' . esc_attr( $key ) . '" title="' . esc_attr( $key ) . '" class="yt-radio-img-img"' . ( isset( $_args['settings'] ) && isset( $_args['settings']['width'] ) && isset( $_args['settings']['height'] ) ? ' style="width:' . esc_attr( $_args['settings']['width'] ) . ';height:' . esc_attr( $_args['settings']['height'] ) . ';"' : '') . ' />';
			$output .= '</label>';
								
		}
		$output .= '<input type="hidden" class="yt-radio-img-radio yt-hidden yt-input-hidden" value="' . esc_attr( $stored_value ) . '" name="' . esc_attr( $_args['id'] ) . '" />';
			
		$output .= '</div>';

		return apply_filters( 'yt_field_type_images', $output, $_args );
	
	}
	
}
/**
 * image_set input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_image_set' ) ) {
  
	function yt_field_type_image_set( $_args = array() ) {
		
		/* Value */
		$output = '<div class="yt-image-set" id="yt-image-set' . esc_attr( $_args['id'] ) . '">';
		$style = ( isset( $_args['settings']['width'] ) && isset( $_args['settings']['height'] ) ? ' style="width:' . esc_attr( $_args['settings']['width'] ) . ';height:' . esc_attr( $_args['settings']['height'] ) . ';">' : '');
		
		foreach( $_args['options'] as $src ){
			
			$output .= '<img src="' . esc_url( $src ) . '" ' . esc_attr( $style ) . '>';
			
		}
		
		$output .= '</div>';
		
		return apply_filters( 'yt_field_type_image_set', $output, $_args );
	
	}
	
}
/**
 * info input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_info' ) ) {
  
	function yt_field_type_info( $_args = array() ) {
		
		/* Value */
		$output = '';
		
		$info_text = isset( $_args['name'] ) && $_args['name'] ? $_args['name'] : $_args['std'];
		$info_style = isset( $_args['settings']['style'] ) ? $_args['settings']['style'] : '' ;
		$output .= '<div class="yt-notification ' . esc_attr( $info_style ) . '">' . $info_text . '</div>';
		
		return apply_filters( 'yt_field_type_info', $output, $_args );
	
	}
	
}
/**
 * Freestyle
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_html' ) ) {
  
	function yt_field_type_html( $_args = array() ) {
		
		/* Value */
		$output = isset( $_args['name'] ) && $_args['name'] ? $_args['name'] : $_args['std'];
		
		return apply_filters( 'yt_field_type_html', $output, $_args );
	
	}
	
}
/**
 * margin input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_margin' ) ) {
  
	function yt_field_type_margin( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		$output = '';
		$stored_value = $_args['value'];
		
		$margin_arr = array(
			'top' => __( 'Margin top', 'yeahthemes' ),
			'right' => __( 'Margin right', 'yeahthemes' ),
			'bottom' => __( 'Margin bottom', 'yeahthemes' ),
			'left' => __( 'Margin left', 'yeahthemes' ),
		);
						
		foreach( $_args['std'] as $margin_k => $margin_v ){
			
			$saved_data = isset( $stored_value[$margin_k] ) && $stored_value[$margin_k] ? $stored_value[$margin_k] : $margin_v;
			
			if( array_key_exists( $margin_k, $margin_arr ) ){
				
				$output .= YT_Field_Type_Helper::select( 
					$_args['id'] . '[' . $margin_k . ']', 
					$saved_data, 
					array(), 
					'tip-tip', 
					'original-title="' . $margin_arr[$margin_k] . '"',
					$min = 0, 
					$max = 101,
					$step = 5,
					$ext = 'px'
				);
				
			}
			
		}
		
		return apply_filters( 'yt_field_type_margin', $output, $_args );	
	}
	
}
/**
 * Media input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_media' ) ) {
  
	function yt_field_type_media( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		/* Value */
		$output = '';
		$_id = strip_tags( strtolower( $_args['id'] ) );
		$media_val = isset( $_args['value'] ) ? $_args['value'] : $_args['std']; 
		$output .= YT_Field_Type_Helper::media( $_args['id'], $media_val, $_args['name'], ( !empty( $_args['settings']['media_by'] ) ) ? $_args['settings']['media_by'] : 'url',  (!empty( $_args['settings']['attr'] ) ? $_args['settings']['attr'] : ''),  (!empty( $_args['settings']['input'] ) ? $_args['settings']['input'] : 'input')); 
		
		return apply_filters( 'yt_field_type_media', $output, $_args );
	
	}
	
}
/**
 * info input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_multicheck' ) ) {
  
	function yt_field_type_multicheck( $_args = array() ) {
		
		/* Value */
		$output = '';
		
		$stored_value = isset( $_args['value'] ) && is_array( $_args['value'] ) ? $_args['value'] : ( isset( $_args['std'] ) && is_array( $_args['std'] ) ? $_args['std'] : array() );
		
		if( empty( $_args['settings']['is_indexed'] ) ){					
			foreach ( ( array ) $_args['options'] as $key => $option ) {
							
				$yt_key_string = $_args['id'] . '_' . $key;
				$checked = in_array( $key, $stored_value ) ? ' checked="checked"' : '';
				
				$output .= '<div><label><input type="checkbox" class="checkbox yt-input yt-checkbox" name="' . esc_attr( $_args['id'] ) . '[]' . '" id="' . esc_attr( $yt_key_string ) . '" value="' . esc_attr( $key ) . '"' . $checked . ' />' . esc_attr( $option ) . '</label></div>';								
			
			}
		}else{
			foreach ( ( array ) $_args['options'] as $option ) {
							
				$yt_key_string = $_args['id'] . '_' . $option;
				$checked = in_array( $option, $stored_value ) ? ' checked="checked"' : '';
				
				$output .= '<div><label><input type="checkbox" class="checkbox yt-input yt-checkbox" name="' . esc_attr( $_args['id'] ) . '[]' . '" id="' . esc_attr( $yt_key_string ) . '" value="' . esc_attr( $option ) . '"' . $checked . ' />' . esc_attr( $option ) . '</label></div>';								
			
			}
		}
		return apply_filters( 'yt_field_type_multicheck', $output, $_args );
	
	}
	
}
/**
 * select input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_multiselect' ) ) {
  
	function yt_field_type_multiselect( $_args = array() ) {
		
		/* Value */
		$output = '';
		
		$stored_value = isset( $_args['value'] ) && is_array( $_args['value'] ) ? $_args['value'] : ( isset( $_args['std'] ) && is_array( $_args['std'] ) ? $_args['std'] : array() );
		
		//print_r($_args['value']);
		
		$output = '<select class="yt-multiple-select" name="' . esc_attr( $_args['id'] ) . '[]" multiple="multiple">';
		
		foreach( $_args['options'] as $k => $v ){
			
			$output .= '<option value="' . esc_attr( $k ) . '" ' . ( in_array( $k, $stored_value ) ? ' selected="selected" ' : '' ) . '>' . $v . '</option>';										
		
		}
		
		$output .= '</select>';
		
		return apply_filters( 'yt_field_type_multiselect', $output, $_args );
	
	}
	
}
/**
 * Post list type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_post_list' ) ) {
  
	function yt_field_type_post_list( $_args = array() ) {
		
		/* Value */
		$output = '';
		
		$stored_value = isset( $_args['value'] ) ? $_args['value'] : ( isset( $_args['std'] ) ? $_args['std'] : '' );
		
		$items = get_posts( array (
			'post_type'	=> $_args['options'],
			'posts_per_page' => !empty( $_args['settings']['posts_per_page'] ) ? $_args['settings']['posts_per_page'] : 30
		));
		
		$output .= '<select name="' . esc_attr( $_args['id'] ) . '" id="' . esc_attr( $_args['id'] ) . '" class="select yt-select">
			<option value="">' . __( 'Select One' , 'yeahthemes') . '</option>'; // Select One
			
			foreach( $items as $item ) {
				
				$output .= '<option value="' . esc_attr( intval( $item->ID ) ) . '" ' . selected( $_args['value'], $item->ID, false ) . '>' . $item->post_title . ' - (' . $item->post_type . ')' . '</option>';
			
			} 
		
		$output .= '</select>';
		
		return apply_filters( 'yt_field_type_post_list', $output, $_args );
	
	}
	
}

/**
 * Number input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_number' ) ) {
  
	function yt_field_type_number( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		/* Value */
		$value = stripslashes( yt_field_val_std( $_args['value'], $_args['std'] ) );
		
		$output = '<input class="yt-input' . esc_attr( ( $_args['class'] ? ' ' . $_args['class'] : '' ) ) . '" name="' . esc_attr( $_args['id'] ) . '" id="'. esc_attr( $_args['id'] ) .'" type="number" value="'. intval( $value ) .'" ' . ( !empty( $_args['settings']['attr'] ) ? $_args['settings']['attr'] : '' ) . '/>';
		
		return apply_filters( 'yt_field_type_number', $output, $_args );
	
	}
	
}
/**
 * Radio input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_radio' ) ) {
  
	function yt_field_type_radio( $_args = array() ) {
		
		$output = '';
		
		$std = isset( $_args['std'] ) && $_args['std'] ? $_args['std'] : $_args['options'][0];
		$stored_value = isset( $_args['value'] ) && $_args['value'] ? $_args['value'] : $std;
		 
		foreach( ( array ) $_args['options'] as $option => $name ) {
			
			$output .= '<div><label class="radio"><input type="radio" class="yt-input yt-radio" name="' . esc_attr( $_args['id'] ) . '" value="' . esc_attr( $option ) . '" ' . checked( $stored_value, $option, false ) . ' />' . $name . '</label></div>';				
		
		}
		
		return apply_filters( 'yt_field_type_radio', $output, $_args );
		
	}
	
}
/**
 * *Repeatable_field type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
function yt_field_type_repeatable_field_helper( $_args = array(), $rpt_fields = array(), $rpt_value = array(), $i = 0 ){

	$output = '<li>
		<div class="yt-repeatable-field-block collapsed">';
			
			$output .= '';
			
			foreach ($rpt_fields as $rpt_cfield ) {
				
				//print_r($rpt_cfield);
				$rpt_cvalue = $rpt_value && isset( $rpt_value[$i][$rpt_cfield['id']] ) ? $rpt_value[$i][$rpt_cfield['id']] : $rpt_cfield['std'];
				$rpt_cvalue = yt_field_val_std( $rpt_cvalue, $rpt_cfield['std'] );
				$rpt_cid = $_args['id'] .'[' . $i . '][' . $rpt_cfield['id'] . ']';
				$rpt_desc = isset( $rpt_cfield['desc'] ) && $rpt_cfield['desc'] ? '<em>' . $rpt_cfield['desc'] . '</em>' : '';
				$rpt_cname = $rpt_cfield['name'] ? '<span>' . $rpt_cfield['name'] . '</span>' : '';
				switch( $rpt_cfield['type'] ){
					
					case 'text':
						$output .= '<div>';
							$output .= $rpt_cname . YT_Field_Type_Helper::text( $rpt_cid, $rpt_cvalue);
							$output .= $rpt_desc;
						$output .= '</div>';
					break;
					
					case 'textarea':
						$output .= '<div>';
							$rpt_crows  = isset( $rpt_cfield['settings']['rows'] ) && is_numeric( $rpt_cfield['settings']['rows'] ) ? $rpt_cfield['settings']['rows'] : '8';
							$output .= $rpt_cname . YT_Field_Type_Helper::textarea( $rpt_cid, $rpt_cvalue, $rpt_crows);
							$output .= $rpt_desc;
						$output .= '</div>';
					break;
					
					case 'media':
						$output .= '<div>';
							$output .= $rpt_cname . YT_Field_Type_Helper::media( $rpt_cid, $rpt_cvalue, $rpt_cfield['name'], ( !empty( $rpt_cfield['settings']['media_by'] ) ) ? $rpt_cfield['settings']['media_by'] : 'url',  (!empty( $rpt_cfield['settings']['attr'] ) ? $rpt_cfield['settings']['attr'] : ''),  (!empty( $rpt_cfield['settings']['input'] ) ? $rpt_cfield['settings']['input'] : 'input'));
							$output .= $rpt_desc;
						$output .= '</div>';
					break;
					
					case 'select':
						$output .= '<div>';
							$output .= $rpt_cname . YT_Field_Type_Helper::select( $rpt_cid, $rpt_cvalue, $rpt_cfield['options']);
							$output .= $rpt_desc;
						$output .= '</div>';
					break;
					
				}
			}
		$output .= '
			<div class="yt-repeatable-field-heading">
				<span class="yt-repeatable-controls yt-repeatable-expand" title="' . __( 'Collapse/Expand','yeahthemes' ) . '">+</span>
				<span class="yt-repeatable-controls yt-repeatable-delete" title="' . __( 'Delete','yeahthemes' ) . '">x</span>
			</div>
		</div>
		
		
	</li>';

	return $output;

}
if ( ! function_exists( 'yt_field_type_repeatable_field' ) ) {
  
	function yt_field_type_repeatable_field( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		$rpt_fields = $_args['options'];
		$rpt_value = $_args['value'] ? $_args['value'] : ( is_array( $_args['std'] ) ? $_args['std'] : array() );
		$row_count = $rpt_value ? count($rpt_value) : 1;
		$output = '<div class="yt-repeatable-fields-wrapper" data-clone="' . esc_attr( yt_field_type_repeatable_field_helper( $_args, $rpt_fields, ( is_array( $_args['std'] ) ? $_args['std'] : array() ) ) ) . '">
			<ul data-collapsed="true">';
		//print_r($rpt_fields);
		
		
		for( $i = 0; $i < $row_count; $i++ ) {
			
			$output .= yt_field_type_repeatable_field_helper( $_args, $rpt_fields, $rpt_value, $i );

			/*$output .= '<li>
				<div class="yt-repeatable-field-block collapsed">';
					
					$output .= '';
					
					foreach ($rpt_fields as $rpt_cfield ) {
						
						//print_r($rpt_cfield);
						$rpt_cvalue = $rpt_value && isset( $rpt_value[$i][$rpt_cfield['id']] ) ? $rpt_value[$i][$rpt_cfield['id']] : $rpt_cfield['std'];
						$rpt_cvalue = yt_field_val_std( $rpt_cvalue, $rpt_cfield['std'] );
						$rpt_cid = $_args['id'] .'[' . $i . '][' . $rpt_cfield['id'] . ']';
						$rpt_desc = isset( $rpt_cfield['desc'] ) && $rpt_cfield['desc'] ? '<em>' . $rpt_cfield['desc'] . '</em>' : '';
						$rpt_cname = $rpt_cfield['name'] ? '<span>' . $rpt_cfield['name'] . '</span>' : '';
						switch( $rpt_cfield['type'] ){
							
							case 'text':
								$output .= '<div>';
									$output .= $rpt_cname . YT_Field_Type_Helper::text( $rpt_cid, $rpt_cvalue);
									$output .= $rpt_desc;
								$output .= '</div>';
							break;
							
							case 'textarea':
								$output .= '<div>';
									$rpt_crows  = isset( $rpt_cfield['settings']['rows'] ) && is_numeric( $rpt_cfield['settings']['rows'] ) ? $rpt_cfield['settings']['rows'] : '8';
									$output .= $rpt_cname . YT_Field_Type_Helper::textarea( $rpt_cid, $rpt_cvalue, $rpt_crows);
									$output .= $rpt_desc;
								$output .= '</div>';
							break;
							
							case 'media':
								$output .= '<div>';
									$output .= $rpt_cname . YT_Field_Type_Helper::media( $rpt_cid, $rpt_cvalue, $rpt_cfield['name'], ( !empty( $rpt_cfield['settings']['media_by'] ) ) ? $rpt_cfield['settings']['media_by'] : 'url',  (!empty( $rpt_cfield['settings']['attr'] ) ? $rpt_cfield['settings']['attr'] : ''),  (!empty( $rpt_cfield['settings']['input'] ) ? $rpt_cfield['settings']['input'] : 'input'));
									$output .= $rpt_desc;
								$output .= '</div>';
							break;
							
							case 'select':
								$output .= '<div>';
									$output .= $rpt_cname . YT_Field_Type_Helper::select( $rpt_cid, $rpt_cvalue, $rpt_cfield['options']);
									$output .= $rpt_desc;
								$output .= '</div>';
							break;
							
						}
					}
				$output .= '
					<div class="yt-repeatable-field-heading">
						<span class="yt-repeatable-controls yt-repeatable-expand" title="' . __( 'Collapse/Expand','yeahthemes' ) . '">+</span>
						<span class="yt-repeatable-controls yt-repeatable-delete" title="' . __( 'Delete','yeahthemes' ) . '">x</span>
					</div>
				</div>
				
				
			</li>';*/
		}
		$output .= '</ul>
		<span class="button yt-button yt-button-add-more">' . __( 'Add more', 'yeahthemes') . '</span>
		<span class="button yt-button yt-button-expand-collapse-all">' . __( 'Expand/Collapse all', 'yeahthemes') . '</span>
		</div>';
		
		return apply_filters( 'yt_field_type_repeatable_field', $output, $_args );
	
	}
	
}
/**
 * Select type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_select' ) ) {
  
	function yt_field_type_select( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		/* Value */
		$value = !empty( $_args['value'] ) ? $_args['value'] : $_args['std'];
		
		$output = YT_Field_Type_Helper::select( $_args['id'], $value , $_args['options']);
		
		return apply_filters( 'yt_field_type_select', $output, $_args );
	
	}
	
}
/**
 * Select alt type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_select_alt' ) ) {
  
	function yt_field_type_select_alt( $_args = array() ) {
		
		$output = '<div class="yt-select-wrapper">';
		$output .= '<select class="select yt-input" name="' . esc_attr( $_args['id'] ) . '" id="' . esc_attr( $_args['id'] ) . '">';
		
		foreach ( $_args['options'] as $option ) {
						
			$output .= '<option id="' . esc_attr( $option ) . '" value="' . esc_attr( $option ) . '" ' . selected( $_args['value'], $option, false ) . ' />' . $option . '</option>';	 
			
		} 
		
		$output .= '</select></div>';
		
		return apply_filters( 'yt_field_type_select_alt', $output, $_args );
	
	}
	
}
/**
 * tax_checkboxes type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_tax_checkboxes' ) ) {
  
	function yt_field_type_tax_checkboxes( $_args = array() ) {
		
		/* Value */
		$stored_value = isset( $_args['value'] ) && is_array( $_args['value'] ) ? $_args['value'] : ( isset( $_args['std'] ) && is_array( $_args['std'] ) ? $_args['std'] : array() );
		$output = '';
		
		if( isset( $_args['settings']['taxonomy'] ) && $_args['settings']['taxonomy'] && taxonomy_exists( $_args['settings']['taxonomy'] ) ){
			
			$taxonomy = get_terms( $_args['settings']['taxonomy'], 'hide_empty=0' );
						
			foreach($taxonomy as $term ){
				
				$checked = in_array( $term->slug, $stored_value ) ? ' checked="checked" ' : '';
				
				$output .= '<div><label><input class="yt-input yt-checkbox" type="checkbox" name="' . esc_attr( $_args['id'] )  . '[]" value="' . esc_attr( $term->slug ) . '" ' . $checked . '/>' . $term-> name . '</label></div>'; 
			}
		
		}else{
			
			$output .= __('Oops! Taxonomy is not exist', 'yeahthemes');
				
		}
		
		return apply_filters( 'yt_field_type_tax_checkboxes', $output, $_args );
	
	}
	
}
/**
 * Text input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_text' ) ) {
  
	function yt_field_type_text( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		/* Value */
		$value = stripslashes( yt_field_val_std( $_args['value'], $_args['std'] ) );
		
		$output = YT_Field_Type_Helper::text( $_args['id'], $value, '', ( !empty( $_args['settings']['attr'] ) ? $_args['settings']['attr'] : '' ) );
		
		return apply_filters( 'yt_field_type_text', $output, $_args );
	
	}
	
}

/**
 * Textarea type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_textarea' ) ) {
  
	function yt_field_type_textarea( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		/* Rows */
		$rows = isset( $_args['settings']['rows'] ) && is_numeric( $_args['settings']['rows'] ) ? $_args['settings']['rows'] : '8';
		
		/* Value */
		$value = yt_field_val_std( $_args['value'], $_args['std'] );	
		
		$output = YT_Field_Type_Helper::textarea( $_args['id'], $value, $rows );
		
		return apply_filters( 'yt_field_type_textarea', $output, $_args );
	
	}
	
}
/**
 * Tiles input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_tiles' ) ) {
  
	function yt_field_type_tiles( $_args = array() ) {
		
		$std = isset( $_args['std'] ) && $_args['std'] ? $_args['std'] : ( is_array( $_args['options'] ) &&  isset( $_args['options'][0] ) ? $_args['options'][0] : '' );
		$stored_value = isset( $_args['value'] ) && $_args['value'] ? $_args['value'] : $std;
		
		//print_r($value['options']);
		
		$output = '<div class="yt-radio-images">';
		
		foreach ( ( array ) $_args['options'] as $key => $option) { 
		
			$output .= '<label data-value="' . esc_url( $option ) . '" class="' . esc_attr( $option == $stored_value ? 'active' : '' ) . '">';
			$output .= '<div class="yt-radio-tile-img" style="background: url(' . esc_url( $option ) . ')" title="' . basename( $option ) . '"></div>';
			$output .= '</label>';	
						
		}
		$output .= '<input type="hidden" class="checkbox yt-radio-tile-radio yt-hidden yt-input-hidden" value="' . esc_url( $stored_value ) . '" name="' . esc_attr( $_args['id'] ) . '" />';
			
		$output .= '</div>';
		
		return apply_filters( 'yt_field_type_tiles', $output, $_args );
	
	}
	
}
/**
 * Time input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_time' ) ) {
  
	function yt_field_type_time( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		/* Value */
		$value = stripslashes( yt_field_val_std( $_args['value'], $_args['std'] ) );
		
		$output = YT_Field_Type_Helper::text( $_args['id'], $value, 'yt-input-time'  );
		
		return apply_filters( 'yt_field_type_time', $output, $_args );
	
	}
	
}
/**
 * Toggles input type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_toggles' ) ) {
  
	function yt_field_type_toggles( $_args = array() ) {
		
		$std = isset( $_args['value'] ) && $_args['value'] ? $_args['value'] : $_args['std'];
		
		$output = '<div class="yt-radio-toggles ' . ( isset( $_args['class'] ) ? esc_attr( $_args['class'] ) : '') . '">';
		
		
		$total = !empty( $_args['options'] ) ? count( $_args['options'] ) : 0;
		$count = 1;

		$std = !in_array( $std, array_keys( $_args['options'] )) ? $_args['std'] : $std;
		
		foreach( ( array ) $_args['options'] as $option => $name ) {
			
			$output .= '<label data-value="' . esc_attr( $option ) . '"' . ( isset( $_args['settings']['width'] ) ? ' style="width:' . esc_attr( $_args['settings']['width'] ) . '"' : '') . ' class="' . ( $std == $option ? 'button-primary' : 'button' ) . ' ' . ( $count == $total ? 'last-child' : '' ) . '">' . $name . '</label>';				
			$count++;
		}
		
		$output .= '<input class="yt-input yt-input-hidden" name="' . esc_attr( $_args['id'] ) . '" type="hidden" value="' . esc_attr( $std ) . '" />';
		
		$output .= '</div>';
	
		return apply_filters( 'yt_field_type_toggles', $output, $_args );
	}
	
}

/**
 * Typography type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_typography' ) ) {
  
	function yt_field_type_typography( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		static $fontfaces;
		
		$fontfaces = yt_get_option_vars( 'fontfaces' );
		
		$output = '';
		$stored_value = $_args['value'];
		
		foreach( $_args['std'] as $typo_k => $typo_v ){
			/* Saved val */
			$saved_data = isset( $stored_value[$typo_k] ) && $stored_value[$typo_k] ? $stored_value[$typo_k] : $typo_v;
			
			switch( $typo_k ){
				
				/* face */
				case 'face':
				
					$output .= '<div class="yt-select-wrapper typography-face" original-title="'.__('Font family','yeahthemes').'" data-att="font-family">
						<select class="yt-typography yt-typography-face select" name="' . esc_attr( $_args['id'] ) . '[face]" id="' . esc_attr( $_args['id'] ) . '_face">';
					
					foreach ( $fontfaces as $key => $face ) {
						
						if( strpos( $key, 'optgroup-label' ) !== false ){
							
							$output .= '<optgroup label="' . esc_attr( $face ) . '"></optgroup>';
							
						}else{
							//Match string before ' ('
							$data_val = preg_match('/(.*)\s+\(/s', $face, $matches );
							
							$data_val = !empty( $matches ) && !empty( $matches[1] ) ? $matches[1] : $face;
							
							$data_font = 'built-in-font';
							
							if( strpos( $key, 'googlefont-' ) !== false ){
								
								$data_font = 'google-font';
								
							}elseif( strpos( $key, 'customfont-' ) !== false) {
							
								$data_font = 'custom-font';
							
							}
							
							
							$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $saved_data, $key, false ) . ' data-val="' . esc_attr( $data_val ) . '" data-font="' . esc_attr( $data_font ) . '">' . $face . '</option>';
							
						}
							
					}
					
					$output .= '</select>
					</div>';
					
				break;
				
				/* size */
				case 'size':
									
					$output .= YT_Field_Type_Helper::select( 
						$_args['id'] . '[size]', 
						$saved_data, 
						array(), 
						'tip-tip', 
						'original-title="' . __( 'Font size', 'yeahthemes' ) . '" data-att="font-size"',
						$min = 10, 
						$max = 81,
						$step = 1,
						$ext = 'px'
					);
					
				break;
				
				case 'weight':
								
					$weights = array(
						'normal'=> __('Normal','yeahthemes'),
						'100'=>'100 (Lighter)',
						'200'=>'200',
						'300'=>'300',
						'400'=>__('400 (Regular)','yeahthemes'),
						'500'=>'500',
						'600'=>'600',
						'700'=>__('700 (Bold)','yeahthemes'),
						'800'=>'800',
						'900'=>'900',
						'inherit'=> __('Inherit','yeahthemes'),
					);
					
					$output .=  YT_Field_Type_Helper::select( 
						$_args['id'] . '[weight]', 
						$saved_data, 
						$weights, 
						'typography-weight tip-tip', 
						'original-title="' . __( 'Font Weight', 'yeahthemes' ) . '" data-att="font-weight"' 
					);
					
				break;
				
				case 'style':
				
					$styles = array(
						'normal'=> __('Normal','yeahthemes'),
						'italic'=> __('Italic','yeahthemes'),
						'oblique'=> __('Oblique','yeahthemes'),
						'inherit'=> __('Inherit','yeahthemes')
					);
					
					$output .=  YT_Field_Type_Helper::select( 
						$_args['id'] . '[style]', 
						$saved_data, 
						$styles, 
						'typography-style tip-tip', 
						'original-title="' . __( 'Font style', 'yeahthemes' ) . '" data-att="font-style"' 
					);
					
				break;
				
				case 'transform':
				
					$transforms = array(
						'none'=> __('None','yeahthemes'),
						'capitalize'=> __('Capitalize','yeahthemes'),
						'uppercase'=> __('Uppercase','yeahthemes'),
						'lowercase'=> __('Lowercase','yeahthemes'),
						'inherit'=> __('Inherit','yeahthemes')
					);
					
					$output .=  YT_Field_Type_Helper::select( 
						$_args['id'] . '[transform]', 
						$saved_data, 
						$transforms, 
						'typography-transform tip-tip', 
						'original-title="' . __( 'Text transform', 'yeahthemes' ) . '" data-att="text-transform"' 
					);
					
				break;
				
				case 'height':
				
					$output .= YT_Field_Type_Helper::select( 
						$_args['id'] . '[height]', 
						$saved_data, 
						array(), 
						'tip-tip', 
						'original-title="' . __( 'Line height', 'yeahthemes' ) . '" data-att="line-height"',
						$min = .3, 
						$max = 2,
						$step = 0.05,
						$ext = ''
					);
					
				break;
				
				case 'letterspacing':
					
					$output .= YT_Field_Type_Helper::select( 
						$_args['id'] . '[letterspacing]', 
						$saved_data, 
						array(), 
						'tip-tip', 
						'original-title="' . __( 'Letter Spacing', 'yeahthemes' ) . '" data-att="letter-spacing"',
						$min = -15, 
						$max = 31,
						$step = 1,
						$ext = 'px'
					);
				break;
				
				case 'marginbottom':
					$output .= YT_Field_Type_Helper::select( 
						$_args['id'] . '[marginbottom]', 
						$saved_data, 
						array(), 
						'tip-tip', 
						'original-title="' . __( 'Margin Bottom', 'yeahthemes' ) . '"',
						$min = 5, 
						$max = 80,
						$step = 5,
						$ext = 'px'
					);
				break;
				
				case 'color':
					$output .= '<div class="yt-colorpicker-wrapper typography-size tip-tip" original-title="' . __('Font color', 'yeahthemes' ) . '" data-att="color">';
						$output .= YT_Field_Type_Helper::colorpicker( $_args['id'] . '[color]', $saved_data, $_args['std']['color'] );
					$output .= '</div>';
				break;
			}
			
		
		}
		
		$output .= '<span class="yt-typography-preview button yt-button"><i class="yt-icon-eye"></i> ' . __( 'Preview', 'yeahthemes') . '</span>
		<div class="yt-clear"></div>
		<div class="yt-typography-preview-area yt-hidden yt-clear" contenteditable="true">' . _x('Grumpy wizards make toxic brew for the evil Queen and Jack. 1234567890', 'Typography preview text', 'yeahthemes') . '</div>';
		
		return apply_filters( 'yt_field_type_typography', $output, $_args );
	
	}
	
}

/**
 * Ui Slider type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_uislider' ) ) {
  
	function yt_field_type_uislider( $_args = array() ) {
		
		
		/* Value */
		$value = ( $_args['value'] || $_args['value'] == '0') ? $_args['value'] : $_args['std'];
		
		$min = isset( $_args['settings']['min'] ) ? $_args['settings']['min'] : 0;
		$max = isset( $_args['settings']['max'] ) ? $_args['settings']['max'] : 100;
		$step = isset( $_args['settings']['step'] ) ? $_args['settings']['step'] : 1;
		$unit = isset( $_args['settings']['unit'] ) ? $_args['settings']['unit'] : '';
		
		$output = '<div id="' . esc_attr( $_args['id'] ) . '-slider" class="yt-ui-slider" data-min="' . intval( $min ) . '" data-max="' . intval( $max ) . '" data-step="' . intval( $step ) . '" data-value="' . intval( $value ) . '"></div>
					<input type="text" readonly class="ui-slider-textfield yt-input-uislider" name="' . esc_attr( $_args['id'] ) . '" id="' . esc_attr( $_args['id'] ) . '" value="' . intval( $value ) . '" /> ' . $unit;
					
		return apply_filters( 'yt_field_type_uislider', $output, $_args );
	
	}
	
}

/**
 * oEmbed type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if( is_admin() ){
	add_action( 'wp_ajax_yt_field_type_oembed_get_embed', 'yt_wp_ajax_get_embed' );
}

if ( ! function_exists( 'yt_field_type_oembed' ) ) {
  
	function yt_field_type_oembed( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
		
		/* Value */
		$value = stripslashes( yt_field_val_std( $_args['value'], $_args['std'] ) );
		
		$hide_remove_btn = $value ? '' : 'yt-hidden';
		
		$output = '<input class="yt-input yt-media-input" name="' . $_args['id'] . '" id="' . $_args['id'] . '" type="text" value="' . esc_url( $value ) . '">';
		$output .= '<div class="yt-button-action">
			<span class="button yt-media-upload-button yt-upload-media yt-button hide-if-no-js" id="upload_' . esc_attr( $_args['id'] ) . '" data-by="url" data-title="' . __('Add Media', 'yeahthemes') . '" data-id=""><i class="yt-icon-upload-cloud"></i> ' . __('Upload','yeahthemes') . '</span>
			<span class="yt-oembed-preview button yt-button"><i class="yt-icon-eye"></i> ' . __( 'Preview', 'yeahthemes') . '</span>
		<span class="button yt-oembed-remove-button yt-button hide-if-no-js '. esc_attr( $hide_remove_btn ) . '">' . __('Remove','yeahthemes') . '</span>
			<span class="spinner"></span>
		</div>';
		
		$output .= '<div class="yt-oembed-preview-area">' . ( $value ? yt_get_embed( $value ) : '' ) . '</div>';
					
		return apply_filters( 'yt_field_type_oembed', $output, $_args );
	
	}
	
}

/**
 * Map type.
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */

if ( ! function_exists( 'yt_field_type_gmap' ) ) {
  
	function yt_field_type_gmap( $_args = array() ) {
		
		if( !class_exists( 'YT_Field_Type_Helper' ) )
			return;
			
			
		if( !wp_script_is( 'gmap-api', 'enqueued' ) && is_admin() ){
			wp_enqueue_script( 'gmap-api', 'https://maps.google.com/maps/api/js?sensor=false', array('jquery'), '', true );
			wp_enqueue_script( 'yt-gmap-field', 		YEAHTHEMES_FRAMEWORK_ADMIN_URI . 'assets/js/gmap.js', array( 'jquery-ui-autocomplete', 'gmap-api'), '', true);
		}
		
		/* Value */
		$value = stripslashes( yt_field_val_std( $_args['value'], $_args['std'] ) );
		
		$address = isset( $_args['settings']['address'] ) ? $_args['settings']['address'] : false;

		$output = '<div class="yt-map-field">';

		$output .= '<div class="yt-map-canvas" style="width:100%;height:350px;"'.( isset( $_args['std'] ) ? " data-default-loc=\"{$_args['std']}\"" : '' ).'></div>
			<input type="hidden" name="' . esc_attr( $_args['id'] ) . '" class="yt-map-coordinate" value="' . esc_attr( $value ) . '">';

		if ( $address )
		{
			$output .= sprintf(
				'<br><button class="button yt-button yt-map-goto-address" value="%s">%s</button>',
				is_array( $address ) ? implode( ',', $address ) : $address,
				__( 'Find Address', 'yeahthemes' )
			);
		}

		$output .= '</div>';
					
		return apply_filters( 'yt_field_type_gmap', $output, $_args );
	
	}
	
}
/**
 * Category checklist
 *
 * See @yt_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'yt_field_type_category_checklist' ) ) {
  
	function yt_field_type_category_checklist( $_args = array() ) {

		$walker = new YT_Walker_Category_Checklist(
			$_args['id'], $_args['id']
		);

		ob_start();
		echo '<ul class="yt-scrollable-checklist-wrapper">';
			wp_category_checklist( 0, 0, $_args['value'], FALSE, $walker, FALSE);
		echo '</ul>';

		$output = ob_get_contents();
		ob_end_clean();

		return apply_filters( 'yt_field_type_category_checklist', $output, $_args );
	}
}