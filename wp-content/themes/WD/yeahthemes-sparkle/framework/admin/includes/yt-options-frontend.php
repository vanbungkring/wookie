<?php 
	
	$heading_title = $this->_current_lang ? $this->_heading . ' (' . strtoupper( $this->_current_lang ) . ')' : $this->_heading ;
	$default_data = !is_serialized( $this->_option_defaults ) ? maybe_serialize( $this->_option_defaults ) : $this->_option_defaults;
	
?>

<div class="wrap yt-core-ui yt-theme-options-framework-wrapper" id="yt-container">
	<span class="yt-preloader yt-ajax-loader" id="yt-main-spinner"></span>
	<h2 class="hidden"><?php echo $heading_title; ?></h2>
	<div id="yt-popup-save" class="yt-save-popup">
		<i class="yt-icon-save"></i>
		<?php _e('Options Updated','yeahthemes'); ?>
	</div>
	
	<div id="yt-popup-reset" class="yt-save-popup">
		<i class="yt-icon-reset"></i>
		<?php _e('Options Reset','yeahthemes'); ?>
	</div>
	
	<div id="yt-popup-fail" class="yt-save-popup">
		<i class="yt-icon-fail"></i>
		<?php _e('Error!','yeahthemes'); ?>
	</div>
	<?php wp_nonce_field('yt-options-ajaxify-saving-data','yt_options_ajaxify_data_nonce'); ?>
	<input type="hidden" id="yt-options-option-key" name="yt_option_key" value="<?php echo $this->_option_name; ?>" />
	<input type="hidden" id="yt-options-prefix" name="yt_option_prefix" value="<?php echo $this->_prefix; ?>" />
	<input type="hidden" id="yt-options-option-default" name="yt_option_default_data" value="<?php echo self::helper_encode( $default_data );?>" />
	
	<form id="yt-form" class="yt-options-panel-form" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>" enctype="multipart/form-data" >
		<div id="yt-header" class="yt-clear">
			<div class="yt-logo">
				<h2><?php echo $heading_title; ?></h2>
				<ul>
					<?php 
						
						$info_list = apply_filters( $this->_prefix . 'options_panel_header_info', array() );
						
						if( !empty( $info_list ) ){
							
							foreach( $info_list as $list ){
								
								echo '<li>' . $list . '</li>';
							
							}
								
						}
					?>
				</ul>
			</div>
			<div id="js-warning" class="hidden">
				<?php _e('Warning- This options panel will not work properly without javascript!','yeahthemes' )?>
			</div>
			<div id="yt-lets-get-social">
			
				<?php 
				
					$social_list = apply_filters( $this->_prefix . 'option_panel_social_network', array() );
					
					if( !empty( $social_list ) ){
						
						echo sprintf( '<span>%s</span>', __('Get social with us!', 'yeahthemes') );
						
						echo '<ul>';
						
							foreach( $social_list as $list ){
								
								echo '<li>' . $list . '</li>';
							
							}
													
						echo '</ul>';
					}
				?>
				
				
			</div>
		</div>
		
		<div class="yt-info-bar yt-clear">
			<ul class="yt-info-bar-left alignleft">
				<li><span class="button button-large yt-button" title="<?php _e( 'Expand', 'yeahthemes' ); ?>" id="yt-expand-options-panel"><i class="yt-icon-resize-full-alt"></i></span></li>
			</ul>
			
			<ul class="alignright">
				<li><button type="button" class="button button-primary button-large yt-save-theme-options yt-save"><i class="yt-icon-floppy"></i> <span><?php _e( 'Save All Changes', 'yeahthemes' ); ?></span></button></li>
				<li><button type="button" class="button button-large reset-button yt-save-theme-options yt-save-and-refresh"><?php _e( 'Save and Refresh', 'yeahthemes' );?></button></li>
			</ul>
		</div>
		<!--.info-bar-->
		
		<div id="yt-main" class="yt-clear">
			<div id="yt-nav">
				<ul>
					<?php echo $this->_option_menus; ?>
				</ul>
			</div>
			<div id="yt-content"> <?php echo $this->_option_fields; /* Settings */ ?> </div>
			<div class="clear"></div>
		</div>
		
		<div id="yt-foot" class="yt-info-bar yt-clear">
			<ul class="yt-info-bar-left alignleft">
				<li><button id="yt-reset" type="button" class="button button-large submit-button reset-button"><i class="yt-icon-arrows-cw"></i> <span><?php _e( 'Options Reset', 'yeahthemes' ); ?></span></button></li>
			</ul>
			
			<ul class="alignright">
				<li><button type="button" class="button button-primary button-large yt-save-theme-options yt-save"><i class="yt-icon-floppy"></i> <span><?php _e( 'Save All Changes', 'yeahthemes' ); ?></span></button></li>
				<li><button type="button" class="button button-large reset-button yt-save-theme-options yt-save-and-refresh"><?php _e( 'Save and Refresh', 'yeahthemes' );?></button></li>
			</ul>
		</div>
		<!--.save_bar-->
		
	</form>
	<div class="yt-clear"></div>
</div>
<!--wrap-->