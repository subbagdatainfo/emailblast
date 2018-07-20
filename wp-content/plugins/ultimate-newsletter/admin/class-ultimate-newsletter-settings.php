<?php

/**
 * Settings.
 *
 * @link          https://yendif.com
 * @since         1.0.0
 *
 * @package       ultimate-newsletter
 * @subpackage    ultimate-newsletter/admin
 */

// Exit if accessed directly
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Ultimate_Newsletter_Settings Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_Settings {

	/**
	 * Add the settings submenu.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {

		add_submenu_page(
			'ultimate_newsletters', 
			__( 'Settings', 'ultimate-newsletter' ), 
			__( 'Settings', 'ultimate-newsletter' ), 
			'manage_options', 
			'un_settings',
			array( $this, 'display_settings' )
		); 
		
	}	
	
	/**
	 * Display settings.
	 *
	 * @since    1.0.0
	 */	
	public function display_settings() {
	
		$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : 'general';		
		include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/settings/ultimate-newsletter-admin-settings.php';
		
	}
	
	/**
	 * Register settings.
	 *
	 * @since    1.0.0
	 */
	function admin_init() {
	
        $tabs = array( 'general', 'email', 'signup' );
		
		foreach( $tabs as $tab ) {
			call_user_func( array( $this, 'register_'.$tab.'_settings' ), 'un_'.$tab.'_settings' );
		}
		
    }
	
	/**
	 * Register general settings.
	 *
	 * @since    1.0.0
	 */
	function register_general_settings( $page_hook ) {
		
		// Section : un_general_settings_section
		add_settings_section(
			'un_general_settings_section',
        	__( 'General Settings', 'ultimate-newsletter' ),
        	array( $this, 'section_callback' ),
        	$page_hook
    	);
		
		add_settings_field(
			'un_general_settings[from_name]',
			__( 'From Name', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'from_name',
				'description' => __( 'Having a name is so much better than someone@unknown.com.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_general_settings[from_email]',
			__( 'From Email', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'from_email',
				'description' => __( 'Enter a valid e-mail address here. eg. newsletter@mydomain.com.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_general_settings[reply_to_name]',
			__( 'Reply-to Name', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'reply_to_name',
				'description' => __( 'Give people a name to reply to.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_general_settings[reply_to_email]',
			__( 'Reply-to Email', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'reply_to_email',
				'description' => __( 'Your subscribers may want to talk to you, so let them.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_general_settings[admin_email]',
			__( 'Admin Notification Email', 'ultimate-newsletter' ),
			array( $this,'callback_text'),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'admin_email',
				'description' => __( 'The email address you would like Ultimate Newsletter plugin to keep in contact with you.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_general_settings[notify_admin_when]',
			__( 'Notify Admin when', 'ultimate-newsletter' ),
			array( $this, 'callback_multicheck' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'notify_admin_when',
				'options'     => array(
					'subscribed'   => __( 'Someone Subscribed', 'ultimate-newsletter' ),
					'unsubscribed' => __( 'Someone Unsubscribed', 'ultimate-newsletter' )
				),
				'description' => __( 'What would you like to be notified about?', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field( 
			'un_general_settings[actions_page]',
    		__( 'Actions Page', 'ultimate-newsletter' ),
    		array( $this, 'callback_select' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'actions_page',
				'description' => __( "The page used to track newsletter open, click rates and other general newsletter actions. [ultimate_newsletter] shortcode must be on this page.", 'ultimate-newsletter' ),
				'options'     => $this->get_pages()		
			)	
		);
		
		add_settings_field(
			'un_general_settings[link_to_browser_version]',
			__( 'Link to Browser Version', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'link_to_browser_version',
				'description' => __( "Displays at the top of your newsletters. Don't forget to include the link tag, ie: [link]The link[/link].", 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_general_settings[link_to_subscriber_profile]',
			__( 'Link to Subscriber Profile', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'link_to_subscriber_profile',
				'description' => __( 'Add a link in the footer of all your newsletters so subscribers can edit their profile and lists. See your own subscriber profile page.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field( 
			'un_general_settings[subscriber_profile_page]',
    		__( 'Subscriber Profile Page', 'ultimate-newsletter' ),
    		array( $this, 'callback_select' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'subscriber_profile_page',
				'description' => __( "Select the page to display the subscriber's profile. [un_subscriber_profile] shortcode must be on this page.", 'ultimate-newsletter' ),
				'options'     => $this->get_pages()		
			)	
		);
		
		add_settings_field(
			'un_general_settings[link_to_unsubscribe_page]',
			__( 'Link to Unsubscribe page', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'link_to_unsubscribe_page',
				'description' => __( 'This changes the label for the unsubscribe link in the footer of your newsletters.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field( 
			'un_general_settings[unsubscribe_page]',
    		__( 'Unsubscribe Page', 'ultimate-newsletter' ),
    		array( $this, 'callback_select' ),
    		$page_hook,
    		'un_general_settings_section',
			array(
				'option_name' => 'un_general_settings',
				'field_name'  => 'unsubscribe_page',
				'description' => __( 'A subscriber is directed to a page of your choice after clicking on the unsubscribe link, at the bottom of a newsletter. [un_unsubscribe] shortcode must be on this page.', 'ultimate-newsletter' ),
				'options'     => $this->get_pages()		
			)	
		);		
		
		register_setting(
			$page_hook,
    		'un_general_settings',
    		array( $this, 'sanitize_options' )
		);
		
	}
	
	/**
	 * Register email settings.
	 *
	 * @since    1.0.0
	 */
	function register_email_settings( $page_hook ) {
		
		// Section : un_email_settings_section
		add_settings_section(
			'un_email_settings_section',
        	__( 'How To Send Your Mail?', 'ultimate-newsletter' ),
        	array( $this, 'section_callback' ),
        	$page_hook
    	);
		
		add_settings_field( 
			'un_email_settings[engine]',
    		__( 'Send Using', 'ultimate-newsletter' ),
    		array( $this, 'callback_radio' ),
    		$page_hook,
    		'un_email_settings_section',
			array(
				'option_name' => 'un_email_settings',
				'field_name'  => 'engine',
				'options'     => array(
					'wordpress'   => __( 'WordPress', 'ultimate-newsletter' ),
					'smtp'        => __( 'SMTP', 'ultimate-newsletter' )
				)
			)
		);
		
		register_setting(
			$page_hook,
    		'un_email_settings',
    		array( $this, 'sanitize_options' )
		);	
		
		// Section : un_email_smtp_settings_section
		add_settings_section(
			'un_email_smtp_settings_section',
        	__( 'SMTP Server Settings', 'ultimate-newsletter' ),
        	array( $this, 'section_callback' ),
        	$page_hook
    	);
		
		add_settings_field(
			'un_email_smtp_settings[host]',
			__( 'Host', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_email_smtp_settings_section',
			array(
				'option_name' => 'un_email_smtp_settings',
				'field_name'  => 'host',
				'description' => __( "Enter your host's URL.", 'ultimate-newsletter' )
			)
		);

		add_settings_field(
			'un_email_smtp_settings[username]',
			__( 'Username', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_email_smtp_settings_section',
			array(
				'option_name' => 'un_email_smtp_settings',
				'field_name'  => 'username',
				'description' => __( 'The username for your SMTP Account.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_email_smtp_settings[password]',
			__( 'Password', 'ultimate-newsletter' ),
			array( $this, 'callback_password' ),
    		$page_hook,
    		'un_email_smtp_settings_section',
			array(
				'option_name' => 'un_email_smtp_settings',
				'field_name'  => 'password',
				'description' => __( 'Your SMTP Password.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_email_smtp_settings[port]',
			__( 'Port', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_email_smtp_settings_section',
			array(
				'option_name' => 'un_email_smtp_settings',
				'field_name'  => 'port',
				'description' => __( 'And Finally your port number. We recommend 465 or 587 if possible.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_email_smtp_settings[encryption]',
			__( 'Encryption', 'ultimate-newsletter' ),
			array( $this, 'callback_select' ),
    		$page_hook,
    		'un_email_smtp_settings_section',
			array(
				'option_name' => 'un_email_smtp_settings',
				'field_name'  => 'encryption',
				'options'     => array(
					''    => '--/--',
					'ssl' => __( 'SSL', 'ultimate-newsletter' ),
					'tls' => __( 'TLS', 'ultimate-newsletter' )
				),
			)
		);
		
		add_settings_field( 
			'un_email_smtp_settings[authentication]',
    		__( 'Authentication', 'ultimate-newsletter' ),
    		array( $this, 'callback_select' ),
    		$page_hook,
    		'un_email_smtp_settings_section',
			array(
				'option_name' => 'un_email_smtp_settings',
				'field_name'  => 'authentication',
				'options'     => array(
					1 => __( 'Yes', 'ultimate-newsletter' ),
					0 => __( 'No', 'ultimate-newsletter' )
				),
				'description' => __( 'Leave this option to Yes. Only a tiny portion of SMTP services ask Authentication to be turned off.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field( 
			'un_email_smtp_settings[web_api]',
    		__( 'Use Web API (if applicable)', 'ultimate-newsletter' ),
    		array( $this, 'callback_select' ),
    		$page_hook,
    		'un_email_smtp_settings_section',
			array(
				'option_name' => 'un_email_smtp_settings',
				'field_name'  => 'web_api',
				'options'     => array(
					1 => __( 'Yes', 'ultimate-newsletter' ),
					0 => __( 'No', 'ultimate-newsletter' )
				),
				'description' => __( 'Leave this option to Yes. If you use "SendGrid", we will use their Web API to send emails.', 'ultimate-newsletter' )
			)
		);
		
		register_setting(
			$page_hook,
    		'un_email_smtp_settings',
    		array( $this,'sanitize_options')
		);	
		
		// Section : un_email_throttling_settings_section
		add_settings_section(
			'un_email_throttling_settings_section',
        	__( 'Email Throttling Settings', 'ultimate-newsletter' ),
        	array( $this, 'section_callback' ),
        	$page_hook
    	);
		
		add_settings_field( 
			'un_email_throttling_settings[]',
    		__( 'Send...', 'ultimate-newsletter' ),
    		array( $this, 'callback_email_throttling' ),
    		$page_hook,
    		'un_email_throttling_settings_section'
		);
		
		register_setting(
			$page_hook,
    		'un_email_throttling_settings',
    		array( $this, 'sanitize_options' )
		);	
		
	}
	
	/**
	 * Register signup confirmation settings.
	 *
	 * @since    1.0.0
	 */
	function register_signup_settings( $page_hook ) {
	
		// Section : un_signup_confirmation_settings_section
		add_settings_section(
			'un_signup_confirmation_settings_section',
			__( 'Signup Confirmation', 'ultimate-newsletter' ),
			array( $this, 'section_callback' ),
			$page_hook
		);
		
		add_settings_field( 
			'un_signup_confirmation_settings[enabled]',
    		__( 'Enable Signup Confirmation', 'ultimate-newsletter' ),
    		array( $this, 'callback_checkbox' ),
    		$page_hook,
    		'un_signup_confirmation_settings_section',
			array(
				'option_name' => 'un_signup_confirmation_settings',
				'field_name'  => 'enabled',
				'field_label' => __( 'Prevent people from being subscribed to your list unwillingly, this option ensures you to keep a clean list.', 'ultimate-newsletter' ),
			)
		);
		
		add_settings_field(
			'un_signup_confirmation_settings[subject]',
			__( 'Email Subject', 'ultimate-newsletter' ),
			array( $this, 'callback_text' ),
    		$page_hook,
    		'un_signup_confirmation_settings_section',
			array(
				'option_name' => 'un_signup_confirmation_settings',
				'field_name'  => 'subject',
				'description' => __( 'This is the subject text that gets sent to a new subscriber to confirm their subscription.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_signup_confirmation_settings[body]',
			__( 'Email Content', 'ultimate-newsletter' ),
			array( $this, 'callback_wysiwyg' ),
    		$page_hook,
    		'un_signup_confirmation_settings_section',
			array(
				'option_name' => 'un_signup_confirmation_settings',
				'field_name'  => 'body',
				'description' => __( "Don't forget to include: [confirm_link]Confirm your subscription.[/confirm_link].", 'ultimate-newsletter' )
			)
		);
		
		add_settings_field(
			'un_signup_confirmation_settings[thank_you_message]',
			__( 'Thank You Message', 'ultimate-newsletter' ),
			array( $this, 'callback_textarea' ),
    		$page_hook,
    		'un_signup_confirmation_settings_section',
			array(
				'option_name' => 'un_signup_confirmation_settings',
				'field_name'  => 'thank_you_message',
				'description' => __( 'This is the message the user will see as they signup to your newsletters on your site.', 'ultimate-newsletter' )
			)
		);
		
		add_settings_field( 
			'un_signup_confirmation_settings[confirmation_page]',
    		__( 'Confirmation Page', 'ultimate-newsletter' ),
    		array( $this, 'callback_select' ),
    		$page_hook,
    		'un_signup_confirmation_settings_section',
			array(
				'option_name' => 'un_signup_confirmation_settings',
				'field_name'  => 'confirmation_page',
				'options'     => $this->get_pages(),
				'description' => __( 'When subscribers click on the activation link, they are redirected to a page of your choice. [un_confirmation] shortcode must be on this page.', 'ultimate-newsletter' )	
			)
		);
		
		register_setting(
			$page_hook,
    		'un_signup_confirmation_settings',
    		array( $this, 'sanitize_options' )
		);	
		
	}	
	
	/**
	 * Displays description of each sections.
	 *
	 * @since    1.0.0
	 *
	 * @params	 array    $args    settings section args.
	 */
	public function section_callback( $args ) {		
		
    }
	
	/**
	 * Displays a text field with the field description for a settings field.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @params	 array    $args    settings field args.
 	 */	
	public function callback_password( $args ) {
		
		// Get the field name from the $args array
		$id = $args['option_name'].'_'.$args['field_name'];
		$name = $args['option_name'].'['.$args['field_name'].']';
		
		// Get the value of this setting
		$values = get_option( $args['option_name'], array() );
		$value = isset( $values[ $args['field_name'] ] ) ? esc_attr( $values[ $args['field_name'] ] ) : '';
	
		// Echo proper textarea
		echo '<input type="password" id="'.$id.'" name="'.$name.'" size="50" value="'.$value.'" />';
		
		// Echo the field description (only if applicable)
		if( isset( $args['description'] ) ) {
			echo '<p class="description">'.$args['description'].'</p>';
		}
		
	}
	
	/**
	 * Displays a text field with the field description for a settings field.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @params	 array    $args    settings field args.
 	 */	
	public function callback_text( $args ) {
	
		// Get the field name from the $args array
		$id = $args['option_name'].'_'.$args['field_name'];
		$name = $args['option_name'].'['.$args['field_name'].']';
		
		// Get the value of this setting
		$values = get_option( $args['option_name'], array() );
		$value = isset( $values[ $args['field_name'] ] ) ? esc_attr( $values[ $args['field_name'] ] ) : '';
	
		// Echo proper textarea
		echo '<input type="text" id="'.$id.'" name="'.$name.'" size="50" value="'.$value.'" />';
		
		// Echo the field description (only if applicable)
		if( isset( $args['description'] ) ) {
			echo '<p class="description">'.$args['description'].'</p>';
		}
		
	}
	
	/**
	 * Displays a textarea with the field description for a settings field.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @params	 array    $args    settings field args.
 	 */	
	public function callback_textarea( $args ) {
	
		// Get the field name from the $args array
		$id = $args['option_name'].'_'.$args['field_name'];
		$name = $args['option_name'].'['.$args['field_name'].']';
		
		// Get the value of this setting
		$values = get_option( $args['option_name'], array() );
		$value = isset( $values[ $args['field_name'] ] ) ? esc_textarea( $values[ $args['field_name'] ] ) : '';
	
		// Echo proper textarea
		echo '<textarea id="'.$id.'" name="'.$name.'" rows="6" cols="60">'.$value.'</textarea>';
	
		// Echo the field description (only if applicable)
		if( isset( $args['description'] ) ) {
			echo '<p class="description">'.$args['description'].'</p>';
		}
		
	}
	/**
	 * Displays a rich text textarea with the field description for a settings field.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @params	 array    $args    settings field args.
 	 */	
	public function callback_wysiwyg( $args ) {
	
		// Get the field name from the $args array
		$id = $args['option_name'].'_'.$args['field_name'];
		$name = $args['option_name'].'['.$args['field_name'].']';
		
		// Get the value of this settings
		$values = get_option( $args['option_name'], array() );
		$value = isset( $values[ $args['field_name'] ] ) ? $values[ $args['field_name'] ] : '';
		
		// Echo wordpress editor
		wp_editor(
			$value,
			$id,
			array(
				'textarea_name' => $name,
				'media_buttons' => false,
				'quicktags'     => true,
				'editor_height' => 250
			)
	  	);
		
		// Echo the field description (only if applicable)
		if( isset( $args['description'] ) ) {
			echo '<p class="description">'.$args['description'].'</p>';
		}
		
	}
	/**
	 * Displays a checkbox with the field description for a settings field.
	 *
	 * @since	 1.0.0
	 * @access   public
	 *
	 * @params	 array    $args    settings field args.
 	 */	
	public function callback_checkbox( $args ) {
	
		// Get the field name from the $args array
		$id = $args['option_name'].'_'.$args['field_name'];
		$name = $args['option_name'].'['.$args['field_name'].']';
		
		// Get the value of this setting
		$values = get_option( $args['option_name'], array() );
		$checked = ( isset( $values[ $args['field_name'] ] ) && $values[ $args['field_name'] ] == 1 ) ? ' checked="checked"' : '';
		
		// Echo proper input type="checkbox"
		echo '<label for="'.$id.'">';
		echo '<input type="checkbox" id="'.$id.'" name="'.$name.'" value="1"'.$checked.'/>';
		echo $args['field_label'];
		echo '</label>';
		
		// Echo the field description (only if applicable)
		if( isset( $args['description'] ) ) {
			echo '<p class="description">'.$args['description'].'</p>';
		}
		
	}
	/**
	 * Displays multiple checkboxes with the field description for a settings field.
	 *
	 * @since	 1.0.0
	 * @access   public
	 *
	 * @params	 array    $args    settings field args.
 	 */	
	public function callback_multicheck( $args ) {
	
		// Get the field id & name from the $args array
		$id = $args['option_name'].'_'.$args['field_name'];
		$name = $args['option_name'].'['.$args['field_name'].']';
		
		// Get the values of this setting
		$values = get_option( $args['option_name'], array() );
		$values = isset( $values[ $args['field_name'] ] ) ? (array) $values[ $args['field_name'] ] : array();	

		// Echo proper input type="checkbox"
		foreach( $args['options'] as $value => $label ) {
			$checked = in_array( $value, $values ) ? ' checked="checked"' : '';
		
			echo '<p>';
			echo '<label for="'.$id.'_'.$value.'">';
			echo '<input type="checkbox" id="'.$id.'_'.$value.'" name="'.$name.'[]" value="'.$value.'"'.$checked.'/>';
			echo $label;
			echo '</label>';
			echo '</p>';
		}
		// Echo the field description (only if applicable)
		if( isset( $args['description'] ) ) {
			echo '<p class="description">'.$args['description'].'</p>';
		}
		
	}
	
	/**
	 * Displays a radio button group with the field description for a settings field.
	 *
	 * @since	 1.0.0
	 * @access   public
	 *
	 * @params	 array    $args    settings field args.
 	 */	
	public function callback_radio( $args ) {
	
		// Get the field id & name from the $args array
		$id = $args['option_name'].'_'.$args['field_name'];
		$name = $args['option_name'].'['.$args['field_name'].']';
		
		// Get the values of this setting
		$values = get_option( $args['option_name'], array() );
		$checked = isset( $values[ $args['field_name'] ] ) ? $values[ $args['field_name'] ] : '';	

		// Echo proper input type="radio"
		foreach( $args['options'] as $key => $label ) {
			echo '<p>';
			echo "<label for='".$id."_".$key."'>";
			echo "<input type='radio' id='".$id."_".$key."' name='".$name."' value='".$key."'".checked( $checked, $key, false )."/>";
			echo $label;
			echo "</label>";
			echo "</p>";
		}
		
		// Echo the field description (only if applicable)
		if( isset( $args['description'] ) ) {
			echo '<p class="description">'.$args['description'].'</p>';
		}
		
	}

	/**
	 * Displays a selectbox with the field description for a settings field.
	 *
	 * @since	 1.0.0
	 * @access   public
	 *
	 * @params	 array    $args    settings field args.
 	 */	
	public function callback_select( $args ) {
	
		// Get the field id & name from the $args array
		$id = $args['option_name'].'_'.$args['field_name'];
		$name = $args['option_name'].'['.$args['field_name'].']';
		
		// Get the values of this setting
		$values = get_option( $args['option_name'], array() );
		$selected = isset( $values[ $args['field_name'] ] ) ? $values[ $args['field_name'] ] : '';	
	
		// Echo proper selectbox
		echo '<select id="'.$id.'" name="'.$name.'">'; 
		foreach( $args['options'] as $value => $label ) { 
			
			echo '<option value="'.$value.'"'.selected( $selected, $value, false ).'>'.$label.'</option>'; 
		} 
		echo '</select>';
		
		// Echo the field description from the $args array
		if( isset( $args['description'] ) ) {
			echo '<p class="description">'.$args['description'].'</p>';
		}
		
	}
	
	/**
	 * Display fields related to email throttling.
	 *
	 * @since    1.0.0
	 * @access   public
 	 */	
	public function callback_email_throttling() {

		$values = get_option( 'un_email_throttling_settings', array() );
		
		// Add textbox
		$value = ! empty( $values['quantity'] ) ? (int) $values['quantity'] : 0;
        $text_element  = '<input type="text" size="3" style="text-align:center;" id="un_email_throttling_settings[quantity]" name="un_email_throttling_settings[quantity]" value="'.$value.'"/>';

		//Add select
		$value = ! empty( $values['interval'] ) ? sanitize_text_field( $values['interval'] ) : '';

		$options = array(
			'1m'  => __( 'Every 1 minute', 'ultimate-newsletter' ),
			'2m'  => __( 'Every 2 minutes', 'ultimate-newsletter' ),
			'5m'  => __( 'Every 5 minutes', 'ultimate-newsletter' ),
			'10m' => __( 'Every 10 minutes', 'ultimate-newsletter' ),
			'15m' => __( 'Every 15 minute', 'ultimate-newsletter' ),
			'30m' => __( 'Every 30 minutes', 'ultimate-newsletter' ),
			'1h'  => __( 'Every 1 hour', 'ultimate-newsletter' ),
			'2h'  => __( 'Every 2 hours', 'ultimate-newsletter' )
		);
		
		$select_element = '<select name="un_email_throttling_settings[interval]" id="un_email_throttling_settings[interval]">';
        foreach( $options as $key => $label ) {
            $select_element .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
        }
        $select_element .= '</select>';
		
		echo $text_element . ' ' . __( 'emails', 'ultimate-newsletter' ) . ' ' . $select_element;
        echo '<p class="description">'.__( 'Your web host has limits. We suggest 70 emails per hour to be safe.', 'ultimate-newsletter' ).'</p>';	
		
    }

	
	/**
	 * Sanitize settings.
	 *
	 * @since    1.0.0
	 */
	public function sanitize_options( $input ) {

		$output = array();
	
		if( ! empty( $input ) ) {
		
			foreach( $input as $key => $value ) {
			
				switch( $key ) {
					// Sanitize text field
					case 'from_name'                  :					
					case 'reply_to_name'  			  :
					case 'link_to_browser_version'    :
					case 'link_to_subscriber_profile' :
					case 'link_to_unsubscribe_page'   :
					case 'host'                       :
					case 'username'                   :
					case 'password'                   :
					case 'subject'                    :									
						$output[ $key ] = sanitize_text_field( $input[ $key ] );
						break;
					// Sanitize text field[integer]
					case 'port'	    :
					case 'quantity'	:
						$output[ $key ] = (int) $input[ $key ];
						break;
					// Sanitize text field[email]
					case 'from_email'     :
					case 'reply_to_email' :
					case 'admin_email'    :
						$output[ $key ] = sanitize_email( $input[ $key ] );
						break;
					// Sanitize textarea[plain]
					case 'thank_you_message' :
						$output[ $key ] = esc_textarea( $input[ $key ] );
						break;
					// Sanitize wordpress editor field
					case 'body' :
						$output[ $key ] = wp_kses_post( $input[ $key ] );
						break;
					// Sanitize checkbox
					case 'enabled':
						$output[ $key ] = (int) $input[ $key ];
						break;
					// Sanitize multi-checkbox
					case 'notify_admin_when' :
						$output[ $key ] = array_map( 'esc_attr', $input[ $key ] );
						break;
					// Sanitize select or radio field
					case 'actions_page'            :					
					case 'subscriber_profile_page' :
					case 'unsubscribe_page' 	   :
					case 'engine'          		   :
					case 'encryption'          	   :
					case 'authentication'          :
					case 'web_api'                 :
					case 'interval'                :
					case 'confirmation_page'       :
						$output[ $key ] = sanitize_key( $input[ $key ] );
						break;
					// Default sanitize method
					default :
						$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );	
				}			
	
			}
		
		}
		
		return $output;
		
    }	
	
	/**
 	 * Get the list of WP Pages.
 	 *	
 	 * @since    1.0.0
 	 * @return   array     $pages    Array of WP pages.
 	 */
	public function get_pages() {
	
		$pages = get_pages();

        $options = array();
		$options[-1] = __( 'Select Page', 'ultimate-newsletter' );
		
        if( $pages ) {
            foreach( $pages as $page ) {
                $options[ $page->ID ] = $page->post_title;
            }
        }

        return $options;
		
	}	
	
	/**
	 * Send Test Email.
	 *
	 * @since    1.0.0
	 */
	public function send_test_email() {
		
		$response = array( 'error' => 0, 'message' => '' );

		if( isset( $_POST['email'] ) ) {
			$to = sanitize_email( $_POST['email'] );
		} else {
			$general_settings = get_option( 'un_general_settings' );
			$to = ! empty( $general_settings['admin_email'] ) ? $general_settings['admin_email'] : '';
		}
		
		if( ! empty( $to ) ) {
		
			$site = get_option( 'blogname');
			
			$subject = '['.$site.'] '.__(  'Testing Ultimate Newsletter email settings', 'ultimate-newsletter' );
			$message = __( 'Hello, this is a test email.', 'ultimate-newsletter' );

			if( Ultimate_Newsletter_Mailer::send_mail( $to, $subject, $message ) ) {
			
				$response['error']   = 0;
				$response['message'] = __( 'Email sent Successfully. There is nothing wrong with your settings.',  'ultimate-newsletter' );
				
			} else {
			
				$response['error']   = 1;
				$response['message'] = __( 'Sorry, something went wrong. Edit your settings and try again.',  'ultimate-newsletter' );
				
			}
			
		} else {
			
			$response['error']   = 1;
			$response['message'] = __( 'Sorry, you must provide an Email address',  'ultimate-newsletter' );
		
		}
		
		echo wp_json_encode( $response );
		wp_die();
		
	}

}
