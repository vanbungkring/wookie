<?php
/**
 *	Plugin Name:  MailChimp Subscription form
 *	Description: Advertising blocks
 */
class YT_Mailchimp_Subscription_Form_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-mailchimp-subscription-form-widget yt-widget',
			'description' => __('Display Mailchimp subscription form with Social Networks counter', 'yeahthemes')
		);
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-mailchimp-subscription-form-widget',
			//'width'	=> 400
		);
		
		parent::__construct( 
			'yt-mailchimp-subscription-form-widget', 
			__('(Theme) Mailchimp Suscription form', 'yeahthemes'), 
			$widget_ops, 
			$control_ops);
	
		//Then make sure our options will be added in the footer
		add_action('admin_head', array( $this, 'widget_styles'), 10 );
		add_action('admin_print_footer_scripts', array( $this, 'widget_scripts'));
		add_action( 'yt_ajax_yt-mailchimp-add-member', array( $this, 'ajax_subscribe') );
		add_action( 'yt_ajax_nopriv_yt-mailchimp-add-member', array( $this, 'ajax_subscribe') );
		add_filter( 'yt_refresh_transient_list_after_updating_theme_options', array( $this, 'delete_transient') );
	}

	function widget_styles(){
		$output = '<style></style>';


		/*Inline css :P */
		$output = str_replace(array("\r", "\n", "\t"), "", $output);

		echo $output . "\n";
		
	}

	function widget_scripts(){

		$output ='
		<script type=\'text/javascript\'></script>';

		$output = str_replace(array("\r", "\n", "\t"), "", $output);

		echo $output . "\n";
	}

	function delete_transient( $transients ){
		$transients[] = 'yt_mailchimp_widget_list_transient';
		return $transients;
	}

	function ajax_subscribe(){

		check_ajax_referer( 'yt-mailchimp-subscribe', 'nonce' );
		$apis = yt_get_3rd_party_api_keys();
		$mailchimp_api_key = $apis['mailchimp'];
		$email = strtolower($_POST['email']);
		$checking = trim($_POST['checking']) === '';
		$list =  trim($_POST['list']);
		$fname =  trim($_POST['fname']);
		$lname =  trim($_POST['lname']);
		$list_id;
		/*Honey pot checker*/

		if( !$checking ){
			_e( 'There was an error submitting the form.', 'yeahthemes');
			die(-1);
		}

		if( !$list ){
			/* Display this msg for administrator only*/
			if( current_user_can( 'edit_theme_options' ) )
				printf(__('No list found, please go back to <a href="%s">Widgets</a>  choose one for this widget.', 'yeahthemes'), admin_url( 'widgets.php' ) );
			else
				_e('There was something wrong with subscribe function, please contact an Administrator ', 'yeahthemes');

			die(-1);
		}else{
			$list_id = yt_decode( $list );
		}
		/*If email is empty*/
		if( empty( $email ) ){
			echo '<span class="text-danger">' . __( 'Please enter your email address.', 'yeahthemes' ) . '</span>';
		    die(-1);
		}

		/*If email is invalid*/
		if( !filter_var( $email, FILTER_VALIDATE_EMAIL) ) {
		    echo '<span class="text-danger">' . __( 'Please enter a valid email address', 'yeahthemes' ) . '</span>';
		    die(-1);
		} else{

			
			if ( !class_exists( 'MailChimp' ))
				load_template( YEAHTHEMES_FRAMEWORK_DIR . 'extended/class.mailchimp-api.php' );

			$MailChimp = new MailChimp( $mailchimp_api_key);
			
			$result = $MailChimp->call('lists/subscribe', array(
                'id'                => $list_id,
                'email'             => array('email'=> $email ),
                'merge_vars'        => array( 'FNAME'=> $fname, 'LNAME'=> $lname),
                'double_optin'      => false,
                'update_existing'   => false,
                'replace_interests' => false,
                'send_welcome'      => true,
            ));

            //print_r( $result);

			if( !empty( $result->status ) && 'error' == $result->status && !empty( $result->status ) ) {
				$error_msg = '';
				if( 'List_AlreadySubscribed' == $result->name)
					$error_msg = __('Oops! This email address is already subscribed!', 'yeahthemes');
				elseif( 'Email_NotExists' == $result->name )
					$error_msg = __('Email address does not exist', 'yeahthemes');
				elseif( 'List_DoesNotExist' == $result->name )
					$error_msg = current_user_can( 'edit_theme_options' ) ? __('List does not exist, please choose a valid list.', 'yeahthemes') : __( 'There was something wrong on adding your email to our system, please contact an Administrator', 'yeahthemes');
				// An error ocurred, return error message	
				echo '<span class="text-danger">' . __('Error: ', 'yeahthemes') . $error_msg .'</span>';
	    		
			}else{
				echo '<span class="text-success">' . __( 'Boom! You have been added to the list, thank you for subscribing! Please check our email for the confirmation.', 'yeahthemes' ) . '</span>';
				die(1);
			}

		}

		die();

	}
	/**
	 * Display Widget
	 */
	function widget( $args, $instance ) {
	
		// outputs the content of the widget
		
		extract( $args );
		// Our variables from the widget settings
		$title = apply_filters('widget_title', $instance['title'] );
		
		echo $before_widget;		
		
		//Echo widget title
		if ( $title ){ echo $before_title . $title . $after_title; }
		$apis = yt_get_3rd_party_api_keys();
		//var_dump( $apis ) ;die();

		//die();
		?>

		<div class="yt-mailchimp-subscription-form-content">
			<?php echo !empty( $instance['description'] ) ? wpautop( $instance['description'] ) : ''; ?>
			<form method="post" class="yt-mailchimp-subscribe-form" action="javascript:void(0)">
				<div class="form-group hidden">
					<?php wp_nonce_field( 'yt-mailchimp-subscribe', 'yt_mailchimp_subscribe_nonce' );?>
					<input type="hidden" name="yt_mailchimp_subscribe_list" value="<?php echo esc_attr( yt_encode( $instance['list'] ) );?>">
			    	<input type="text" name="yt_mailchimp_subscribe_check" class="form-control"  value="" placeholder="<?php _e('If you want to submit this form, do not enter anything in this field', 'yeahthemes');?>">
				</div>
				<?php if( $instance['show_name'] ):?>
				<div class="row form-group">
					<div class="col-xs-6">
						<input name="yt_mailchimp_subscribe_fname" type="text" class="form-control" placeholder="<?php _e('First Name', 'yeahthemes');?>">
					</div>
					<div class="col-xs-6">
						<input name="yt_mailchimp_subscribe_lname" type="text" class="form-control" placeholder="<?php _e('Last Name', 'yeahthemes');?>">
					</div>
				</div>
				<?php endif;?>
				<div class="input-group form-group">
			      <input type="email" name="yt_mailchimp_subscribe_email" class="form-control" placeholder="<?php _e( 'Enter your E-mail...', 'yeahthemes' );?>">
			      <span class="input-group-btn">
			        <input class="btn btn-default" type="submit"><?php _e('Submit', 'yeahthemes');?></button>
			      </span>
			    </div><!-- /input-group -->	

			</form>
			<div class="yt-mailchimp-subscription-result"></div>

		</div>
		<?php
		echo $after_widget;
	}

	/**
	 * Widget Settings
	 */
	function form( $instance ) {
		
		// Set up some default widget settings
		$defaults = array(
			'title' => __('Subscribe!','yeahthemes'),
			'list' => '',
			'description' => '',
			'show_name' => 0
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$apis = yt_get_3rd_party_api_keys();
		$mailchimp_api_key = $apis['mailchimp'];

	    ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','yeahthemes')?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			
			<?php
			if( !empty( $mailchimp_api_key ) ):
			?>
			<label for="<?php echo $this->get_field_id('list'); ?>"><strong><?php _e( 'Choose a list:', 'yeahthemes' ); ?></strong></label>
			<select name="<?php echo $this->get_field_name('list'); ?>" id="<?php echo $this->get_field_id('list'); ?>" class="widefat">
				<?php
					
					if ( !class_exists( 'MailChimp' ))
						load_template( YEAHTHEMES_FRAMEWORK_DIR . 'extended/class.mailchimp-api.php' );

					$mc_lists = array();
				
					//we *could* support paging, but few users have that many lists (and shouldn't)
					
					$mc_lists_transient = get_transient( 'yt_mailchimp_widget_list_transient' );
					if ( empty( $mc_lists_transient ) ){
						$MailChimp = new MailChimp( $mailchimp_api_key);
					
						$result = $MailChimp->call('lists/list');
						if( !empty( $result->data )){
							foreach ( $result->data as $list ) {
								$mc_lists[$list->id] = $list->name;
							}
						}
					   // we have a transient return/assign the results
					   set_transient('yt_mailchimp_widget_list_transient', $mc_lists, HOUR_IN_SECONDS );
					} else {
					   // Handle the false if you want
						$mc_lists = $mc_lists_transient;
					}

					if( !empty( $mc_lists ) ){
						foreach ( $mc_lists as $list_id => $list_name ) {
							if( !empty( $list_id  )):
							?>
							<option value="<?php echo esc_attr( $list_id );?>"<?php selected( $instance['list'], $list_id ); ?>><?php echo $list_name;?></option>
							<?php
							endif;
						}
					}else{
						?>
						<option value=""><?php _e('No list found!', 'yeahthemes'); ?></option>
						<?php
					}
				?>
			</select>
			<?php
				else:
				?>
				<em><?php echo sprintf( __('Mailchimp API key not found, enter it from <a href="%s">Theme Options</a>->Subscribe & Connect->API','yeahthemes'), admin_url( 'admin.php?page=yt-theme-options' ) );?></em>
				<?php
				endif;
				?>
		</p>
		<p>
	    	<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e('Description:','yeahthemes')?></label>
	    	<textarea rows="5" name="<?php echo $this->get_field_name('description'); ?>" class="widefat" id="<?php echo $this->get_field_id('description'); ?>"><?php echo !empty( $instance['description'] ) ? esc_textarea( $instance['description'] ) : '';?></textarea>
	    </p>

	    <p>
	    	<input id="<?php echo $this->get_field_id( 'show_name' ); ?>" name="<?php echo $this->get_field_name( 'show_name' ); ?>" type="checkbox" <?php checked( $instance['show_name'], 'on' ); ?>/>
	    	<label for="<?php echo $this->get_field_id('show_name'); ?>"><?php _e( 'Show name field', 'yeahthemes' ); ?></label>
	    </p>
	  

		<?php
	}

	/**
	 * Update Widget
	 */
	function update( $new_instance, $old_instance ) {
		
		// processes widget options to be saved
		$instance = $old_instance;

		//Strip tags for title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['list'] = $new_instance['list'];
		$instance['description'] = $new_instance['description'];
		$instance['show_name'] = $new_instance['show_name'] ? 'on' : 0 ;
		
		return $instance;
	}
}
