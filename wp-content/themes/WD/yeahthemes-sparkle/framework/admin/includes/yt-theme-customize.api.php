<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Theme Customizer
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeah Includes
 */
 
 
 
if( class_exists( 'WP_Customize_Control' ) ){
	
	class YT_Customize_Controls extends WP_Customize_Control {
		
		/**
		 * @access public
		 * @var    string
		 */
		public $type = 'textarea';
		
		/**
		 * @access public
		 * @var    array
		 */
		public $description;
		
		/**
		 * @access public
		 * @var    array
		 */
		public $dimension = array();
		
		/**
		 * @access public
		 * @var    array
		 */
		public $options = array();
		
		/**
		 * Render the control's content.
		 * 
		 * Allows the content to be overriden without having to rewrite the wrapper.
		 * 
		 * @return  void
		 */
		public function render_content() { 
		
			$options = $this->choices;
			$dimension_w = isset( $this->dimension['width'] ) ? $this->dimension['width'] : '';
			$dimension_h = isset( $this->dimension['height'] ) ? $this->dimension['height'] : '';
			
			if( !in_array( $this->type, array( 'toggles' ) ) ){
				
				echo '<label>';	
			
			}
			
			switch( $this->type ){
				
				/*
				 * Textarea control
				 */
				case 'textarea':
				?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
				<?php
				break;
				
				/*
				 * Textarea control
				 */
				case 'number':
				?>
					<span class="customize-control-title" ><?php echo esc_html( $this->label ); ?></span>
					<input type="numver" style="width:50%;" value="<?php echo intval( $this->value() ); ?>" <?php $this->link(); ?>>
				<?php
				break;
				
				/*
				 * Textarea control
				 */
				case 'checkbox':
				?>
					<label><input type="checkbox" value="1" <?php checked( $this->value(), 1, false );?> <?php $this->link(); ?>><strong><?php echo $this->label;?></strong></label>
				<?php
				break;
				
				/*
				 * Select control
				 */
				case 'select':
				?>
					<span class="customize-control-title" ><?php echo esc_html( $this->label ); ?></span>
					<select <?php $this->link(); ?>>
						<?php
						foreach( $options as $k => $v ){
							echo '<option value="' . esc_attr( $k ) . '" ' .  selected( $this->value(), $k, false) . '>' . $v . '</option>';
						}
						?>
					</select>
				<?php
				break;
				
				/*
				 * Select alt control
				 */
				case 'select_alt':
				?>
					<span class="customize-control-title" ><?php echo esc_html( $this->label ); ?></span>
					<select <?php $this->link(); ?>>
						<?php
						foreach( $options as $k){
							echo '<option value="' . esc_attr( $k ) . '" ' .  selected( $this->value(), $k, false) . '>' . $k . '</option>';
						}
						?>
					</select>
				<?php
				break;
				
				/*
				 * Toggle control
				 */
				case 'toggles':
				?>
					<span class="customize-control-title" ><?php echo esc_html( $this->label ); ?></span>
					
					<div class="yt-toggles-control-wrapper clear">
						<?php
						foreach( $options as $k => $v ){
							?>
							<label class="button<?php echo checked( $this->value(), $k, false) ? ' button-primary' : '';?>">
								<input name="<?php echo esc_attr( $this->id );?>" type="radio" value="<?php echo esc_attr( $k );?>" <?php $this->link(); checked( $this->value(), $k);?>><?php echo $v; ?>
							</label>
							<?php
						}
						?>
					</div>
				<?php
				break;
				
				/*
				 * Color Block control
				 */
				case 'color_blocks':
				?>
					<span class="customize-control-title" ><?php echo esc_html( $this->label ); ?></span>
					
					<div class="yt-color-blocks-control-wrapper clear">
						<?php
						foreach( $options as $color => $name ){
							?>
							<label<?php echo checked( $this->value(), $color, false) ? ' class="active"' : '';?> style="background-color:<?php echo esc_attr( $color );?>;<?php echo ( $dimension_w && $dimension_h ) ? esc_attr( 'width:' . $dimension_w . ';height:' . $dimension_h . ';') : '' ?> ">
								<input name="<?php echo esc_attr( $this->id );?>" type="radio" value="<?php echo $color;?>" <?php $this->link(); checked( $this->value(), $color);?>>
							</label>
							<?php
						}
						?>
					</div>
				<?php
				break;
				
				/*
				 * Images control
				 */
				case 'images':
				?>
					<span class="customize-control-title" ><?php echo esc_html( $this->label ); ?></span>
					
					<div class="yt-images-radio-control-wrapper clear">
						<?php
						foreach( $options as $k => $v ){
							?>
							<label<?php echo checked( $this->value(), $k, false) ? ' class="active"' : '';?>>
								<input name="<?php echo esc_attr( $this->id );?>" type="radio" value="<?php echo esc_attr( $k );?>" <?php $this->link(); checked( $this->value(), $k);?>>
								<img src="<?php echo esc_attr( $v );?>" style="<?php echo $dimension_w && $dimension_h ? esc_attr( 'width:' . $dimension_w . ';height:' . $dimension_h . ';' ) : ''; ?>">
							</label>
							<?php
						}
						?>
					</div>
				<?php
				break;
				
				/*
				 * Tiles control
				 */
				case 'tiles':
				?>
					<span class="customize-control-title" ><?php echo esc_html( $this->label ); ?></span>
					
					<div class="yt-tiles-radio-control-wrapper clear">
						<?php
						foreach( $options as $k => $v ){
							?>
							<label<?php echo checked( $this->value(), $v, false) ? ' class="active"' : '';?>>
								<input name="<?php echo esc_attr( $this->id );?>" type="radio" value="<?php echo esc_attr( $v );?>" <?php $this->link(); checked( $this->value(), $v);?>>
								<div style="background:url(<?php echo esc_url( $v ); ?>)"></div>
							</label>
							<?php
						}
						?>
					</div>
				<?php
				break;
			}
			
			
			/*
			 * Descriptions
			 */
			if( isset( $this->description ) && $this->description){
				echo '<span class="customize-control-desc">' . esc_html( $this->description ) . '</span>';
			}
			
			if( !in_array( $this->type, array( 'toggles' ) ) ){
				
				echo '</label>';
			
			}
			
			
		}
	}
}


if( !class_exists( 'YT_Theme_Customize' ) ){
	
	class YT_Theme_Customize{
		
		/**
		 * Option key
		 */
		public $_option_key;
		
		/**
		 * Option array
		 */
		public $_option_array = array();
		
		/**
		 * PHP5 constructor method.
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		function __construct() {
			
		}
		
		/**
		 * Init function
		 *
		 * @param     string   	$option_key
		 * @param     arrray   	$option_array
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function init( $option_key, $option_array){

			//if( !is_admin() ) return;
			
			$this->_option_key = $option_key;
			
			$this->_option_array = $option_array;
			
			$this->hooks();			
		}
		
		/**
		 * Action hooks
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function hooks() {

			
				add_action( 'customize_save_after', array( $this, 'customize_save_after'), 100);
				add_action( 'customize_register', array( $this, 'auto_register_theme_customize_fields') );
				
				add_action( 'customize_controls_print_styles', array( $this, 'theme_customize_frame_print_css' ), 10);
				add_action( 'customize_controls_print_footer_scripts', array( $this, 'theme_customize_frame_print_js' ), 10);
			
		}
		
		/**
		 * Re-update the theme options to make the changes to the WPML string tramslation
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */

		public function customize_save_after( $wp_customize ) {
			
			$data = yt_get_options();
			do_action( 'yt_customize_after_saving', $data );
		}
		
		/**
		 * Registering fields for theme customize automatically based on $yt_options
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */

		public function auto_register_theme_customize_fields( $wp_customize ) {
			
			/**
			 * This is optional, but if you want to reuse some of the defaults
			 * or values you already have built in the options panel, you
			 * can load them into $options for easy reference
			 */
			
			if( !is_array( $this->_option_array ) || !$this->_option_key ) return;
			
			//print_r($yt_data);
			
			$section_id;
			$section_name;
			$sub_section_id;
			
			$option_ids = array();
			
			$priority =  200;

			$transportMsgFields = array();
			
			//print_r($option_array);
			
			foreach( ( array ) $this->_option_array as $option ){
				
				$priority++;
				
				if( isset( $option['customize'] ) && $option['customize'] ){
					
					if( in_array( $option['type'], array( 'heading', 'subheading', 'separator' ) ) ){
						
						
						if( $option['type'] === 'heading' ){
							
							$section_id = yt_clean_string( $option['name'], '-', '_' );
							$section_name = $option['name'];
						
						}	
						
						if( $option['type'] === 'subheading' || $option['type'] === 'separator' ){
							
							$section_id = $section_id . yt_clean_string( $option['name'], '-', '_' );
							$section_name = $section_name . ' - ' . $option['name'];

							if( isset( $option['customize_name'] )){

							
								$section_id = yt_clean_string( $option['customize_name'], '-', '_' );
								$section_name = $option['customize_name'];

							}
						
						}
						
						$wp_customize->add_section( $section_id , array(
							'title' => $section_name,
							'priority' => $priority,
						) );
						
					}else{
						
						$option_id = $option['id'];
						$option_name = $option['name'];
						$option_type = $option['type'];
						$option_std = isset( $option['std'] ) ? $option['std'] : '';
						$option_options = isset( $option['options'] ) ? $option['options'] : '';
						$option_desc = isset( $option['desc'] ) ? $option['desc'] : '';
						
						$option_dimension = array();
						if( isset( $option['settings']['width'] ) )
							$option_dimension['width'] = $option['settings']['width'];

						if( isset( $option['settings']['height'] ) )
							$option_dimension['height'] = $option['settings']['height'];


						$option_transport = !empty( $option['transport'] ) && in_array( $option['transport'] , array( 'postMessage', 'refresh') ) ? $option['transport'] : 'refresh';
						
						$option_ids[] = $option_id;
						
						$option_key = $this->_option_key . '[' . $option_id . ']';
						
						/*
						 * Add setting
						 */
						$wp_customize->add_setting( $option_key, array(
							'type' => 'option',
							'default' => $option_std,
							'capability' => 'edit_theme_options',
							'transport' => $option_transport
						) );

						/*Push field using transport msg to array*/
						if( 'postMessage' == $option_transport ){
							$transportMsgFields[] = $option_key;
							//echo $option_id;
						}
						
						/*
						 * Add Controls
						 */
						 
						
						/*
						 * colorpicker
						 */
						if( $option_type === 'colorpicker' ){
						
							$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $option_id, array(
								'label' => $option_name ,
								'section' => $section_id ,
								'settings' => $option_key,
								'priority'   => $priority
							) ) );	
							
						}
						
						/*
						 * media
						 */
						elseif( $option_type === 'media' ){
							
							$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $option_id, array(
								'label' => $option_name ,
								'section' => $section_id ,
								'settings' => $option_key,
								'priority'   => $priority
							) ) );
							
						}
						
						/*
						 * textarea, select
						 */
						elseif( in_array( $option_type, array( 'checkbox', 'textarea', 'select', 'select_alt', 'toggles', 'images', 'tiles', 'color_blocks', 'number'  ) )  ){
							
							

							$wp_customize->add_control( new YT_Customize_Controls( $wp_customize, $option_id, array(
								'label' => $option_name ,
								'section' => $section_id ,
								'settings' => $option_key,
								'type' => $option_type,
								'choices' => $option_options,
								'description' => $option_desc,
								'dimension' => $option_dimension,
								'priority'   => $priority
							) ) );
							
						}
						/*
						 * Default: text
						 */
						else{
							
							$wp_customize->add_control( $option_id, array(
								'label' => $option_name ,
								'section' => $section_id,
								'settings' => $option_key,
								'type' => $option_type,
								'priority'   => $priority
							) );
							
						}
						
					}
				
				}
				
			}
			
			
			/*Exclude section from customize*/
			$exclude_sections = apply_filters( 'yt_customize_excluded_sections', array( 'title_tagline' ) );
			
			if( !empty( $exclude_sections ) ){
				
				foreach( (array) $exclude_sections as $exclude_section ){
					
					$wp_customize->remove_section( $exclude_section );
				
				}
			
			}
			/**
			 * Let's make some stuff use live preview JS
			 */

			
			$transportMsgFields = apply_filters( 'yt_customize_transport_message_fields', $transportMsgFields );
			//$transportMsgFields[] = 'blogname';
			//print_r($transportMsgFields);
			if( !empty( $transportMsgFields )){
				foreach ( ( array ) $transportMsgFields as $setting_id ) {
					 $wp_customize->get_setting( $setting_id )->transport = 'postMessage';
				}
			}

			if ( $wp_customize->is_preview()){
				add_action( 'wp_head' , array( $this, 'theme_customize_css' ) );
				add_action( 'wp_footer', array( $this, 'theme_customize_js'), 21);
			}
		}

		/**
		 * Customize in action
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */

		public function theme_customize_css() {
			do_action( 'yt_theme_customize_css', $this->_option_key );
		}
		
		/**
		 * Customize in action
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function theme_customize_js() {
			$key = $this->_option_key;
			?>
			
			<script type="text/javascript">
			( function( $ ){
				// wp.customize('blogname',function( value ) {
			 //        value.bind(function(to) {
			 //            $('.plain-text-logo a').html(to);
			 //        });
			 //    });
			} )( jQuery );
			</script>
			<?php 

			do_action( 'yt_theme_customize_js', $this->_option_key );

			/**
			 * Must set the option transport as "postMessage" 
			 * Access key by using this syntax: "$this->_option_key[plain_logo_text]"
			 */
		} 
		
		/**
		 * Print css for custom controllers
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function theme_customize_frame_print_css() {
		
			wp_enqueue_style('yt-customize', 		YEAHTHEMES_FRAMEWORK_ADMIN_URI . 'assets/css/admin-customize.css');
			
		}
		
		/**
		 * Print javascript for custom controllers
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function theme_customize_frame_print_js() {
		
			?>
			
			<script type="text/javascript">
			( function( $ ){
				
				/**
				 * Toggles
				 */
				$('.yt-toggles-control-wrapper > label').on('click', function(){
					$(this).siblings('label').removeClass('button-primary');
					$(this).addClass('button-primary').children('[type=radio]').trigger('click');
					e.preventDefault();
				});
				
				/**
				 * Color blocks
				 */
				$('.yt-color-blocks-control-wrapper > label, .yt-images-radio-control-wrapper label, .yt-tiles-radio-control-wrapper label').on('click',function(){
					$(this).siblings('label').removeClass('active');
					$(this).addClass('active').children('[type=radio]').trigger('click');
					e.preventDefault();
				});
				
				
				
			} )( jQuery );
			</script>
			<?php
		}

	}
}

