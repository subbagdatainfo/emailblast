<?php

/**
 * Fired during plugin activation
 *
 * @link          https://yendif.com
 * @since         1.0.0
 *
 * @package       ultimate-newsletter
 * @subpackage    ultimate-newsletter/includes
 */

// Exit if accessed directly
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Ultimate_Newsletter_Activator Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_Activator {

	/**
	 * Called when plugin activated.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {	
		
		// Insert general settings
		if( ! get_option( 'un_general_settings' ) ) {
		
			$default_values = array(
				'from_name'                  => get_bloginfo('admin_name'),
				'from_email'                 => get_bloginfo('admin_email'),
				'reply_to_name'              => get_bloginfo('admin_name'),
				'reply_to_email'             => get_bloginfo('admin_email'),
				'admin_email'                => get_bloginfo('admin_email'),
				'notify_admin_when'          => array( 'subscribed', 'unsubscribed' ),
				'actions_page'               => self::get_actions_page(),
				'link_to_browser_version'    => __( 'Display problems? [link]View this newsletter in your browser.[/link]', 'ultimate-newsletter' ),
				'link_to_subscriber_profile' => __( '[link]Edit your subscription[/link]', 'ultimate-newsletter' ),
				'subscriber_profile_page'    => self::get_subscriber_profile_page(),
				'link_to_unsubscribe_page'   => __( '[link]Unsubscribe[/link]', 'ultimate-newsletter' ),
				'unsubscribe_page'           =>  self::get_unsubscribe_page()
			);
			
			add_option( 'un_general_settings', $default_values );
			
		}
		
		// Insert email settings
		if( ! get_option( 'un_email_settings' ) ) {
		
			$default_values = array(
				'engine' => 'wordpress'
			);
			
			add_option( 'un_email_settings', $default_values );
		
		}
		
		// Insert SMTP settings
		if( ! get_option( 'un_email_smtp_settings' ) ) {
		
			$default_values = array(
				'host'           => '',
				'username'       => '',
				'password'       => '',
				'port'           => '',
				'encryption'     => '',
				'authentication' => 1,
				'web_api'        => 1
			);
			
			add_option( 'un_email_smtp_settings', $default_values );
		
		}
		
		// Insert email throttling settings
		if( ! get_option( 'un_email_throttling_settings' ) ) {
		
			$default_values = array(
				'quantity' => 70,
				'interval' => '1h'
			);
			
			add_option( 'un_email_throttling_settings', $default_values );
		
		}
		
		// Insert signup confirmation settings
		if( ! get_option( 'un_signup_confirmation_settings' ) ) {
		
			$default_values = array( 
				'enabled'           => 1,
				'subject'           => __( 'Thank You For Subscribing', 'ultimate-newsletter' ),
				'body'              => __( "Dear [username],\n\nThank you for signing up to our newsletter.\n\nPlease click on this [confirm_link]link[/confirm_link] to activate your subscription.\n\nKind Regards", 'ultimate-newsletter' ),
				'thank_you_message' => __( 'Thank You for signing up. You will receive a confirmation mail shortly.', 'ultimate-newsletter' ),
				'confirmation_page' => self::get_confirmation_page()
			);
			add_option( 'un_signup_confirmation_settings', $default_values );
		
		}

		// Insert subscribers from the WordPress users list
		if( ! get_option( 'un_version' ) ) {
		
			self::insert_subscribers();
			self::insert_first_newsletter();
			
		}
		
		// Add/Update plugin version
		update_option( 'un_version', ULTIMATE_NEWSLETTER_PLUGIN_VERSION );
		
	}
	
	/**
	 * Add page to track newsletter open and click rates.
	 *
	 * @since    1.0.0
	 *
	 * @return   int    $post_id    Page ID.   
	 */
	public static function get_actions_page() {

		// Insert subscriber profile page
		$post_id = wp_insert_post(array(
			'post_title'     => __( 'Ultimate Newsletter', 'ultimate-newsletter' ),
			'post_type' 	 => 'page',
			'post_name'	 	 => sanitize_title( current_time('mysql') ),
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_content'   => '[ultimate_newsletter]',
			'post_status'    => 'publish',
			'post_author'    => get_current_user_id()
		));			

		return $post_id;
		
	}
	
	/**
	 * Add subscriber profile page.
	 *
	 * @since    1.0.0
	 *
	 * @return   int    $post_id    Page ID.   
	 */
	public static function get_subscriber_profile_page() {

		// Insert subscriber profile page
		$post_id = wp_insert_post(array(
			'post_title'     => __( 'Subscriber Profile', 'ultimate-newsletter' ),
			'post_type' 	 => 'page',
			'post_name'	 	 => 'subscriber-profile',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_content'   => '[un_subscriber_profile]',
			'post_status'    => 'publish',
			'post_author'    => get_current_user_id()
		));			

		return $post_id;
		
	}
	
	/**
	 * Add unsubscribe page.
	 *
	 * @since    1.0.0
	 *
	 * @return   int    $post_id    Page ID. 
	 */
	public static function get_unsubscribe_page() {

		// Insert unsubscribe page
		$post_id = wp_insert_post(array(
			'post_title'     => __( 'Unsubscribe', 'ultimate-newsletter' ),
			'post_type' 	 => 'page',
			'post_name'	 	 => 'unsubscribe',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_content'   => '[un_unsubscribe]',
			'post_status'    => 'publish',
			'post_author'    => get_current_user_id()
		));			

		return $post_id;
		
	}
	
	/**
	 * Add confirmation page.
	 *
	 * @since    1.0.0
	 *
	 * @return   int    $post_id    Page ID. 
	 */
	public static function get_confirmation_page() {

		// Insert confirmation page
		$post_id = wp_insert_post(array(
			'post_title'     => __( 'Confirmation', 'ultimate-newsletter' ),
			'post_type' 	 => 'page',
			'post_name'	 	 => 'confirmation',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_content'   => '[un_confirmation]',
			'post_status'    => 'publish',
			'post_author'    => get_current_user_id()
		));			

		return $post_id;
		
	}
	
	/**
	 * Add pre-registered users as subscribers.
	 *
	 * @since    1.0.0
	 */
	public static function insert_subscribers() {
	
		// Register cunstom taxonomy "un_email_groups"
		un_register_taxonomy_email_groups();
			
		// Insert term "WordPress Users" if not exist
		if( ! get_option( 'un_subscriber_wp_users' ) ) {
		
       		$wordpress_users = wp_insert_term(
        		__( 'Wordpress Users', 'ultimate-newsletter' ),
        		'un_email_groups',
        		array(
          			'description' => __( 'All your site members are added to this group dynamically by the plugin.', 'ultimate-newsletter' ),
           			'slug'        => 'wordpress-users'
        		)
			);
				
			update_option( 'un_subscriber_wp_users', $wordpress_users['term_id'] );	
			
		}
		   
		// Get WordPress users
		$args  = array(
			'blog_id' => get_current_blog_id(),
		);
		$users = get_users( $args );
			
		// Loop through each user
       	foreach( $users as $user ) {
		
			// Add this user as a subscriber
			$user_info 	 = get_userdata( $user->ID );
			
			$name  		 = $user_info->display_name;
			$email	 	 = $user_info->user_email;
			$token       = un_generate_subscriber_token();
			$status	     = 'subscribed';
			$email_group = (int) get_option( 'un_subscriber_wp_users' );

			$post = array(
				'post_type'	  => 'un_subscribers',
				'post_title'  => $name,
				'post_status' => 'publish'
			);
				
			$post_id = wp_insert_post( $post );
		
			update_post_meta( $post_id, 'user_id', $user->ID );
			update_post_meta( $post_id, 'email', $email );			
			update_post_meta( $post_id, 'token', $token );
			update_post_meta( $post_id, 'status', $status );			
			wp_set_object_terms( $post_id, $email_group, 'un_email_groups' );
			
			update_user_meta( $user->ID, 'un_subscriber_id', $post_id );
			
	   	}
		
	}
	
	/**
	 * Insert first newsletter.
	 *
	 * @since    1.0.0
	 */
	public static function insert_first_newsletter() {
	
		include_once ULTIMATE_NEWSLETTER_PLUGIN_DIR.'includes/libraries/emogrifier/class-emogrifier.php';
		
		$general_settings = get_option( 'un_general_settings' );
		$email_group = get_option( 'un_subscriber_wp_users' );
		
		$template = 'one-column';
		
		$post_content = file_get_contents( ULTIMATE_NEWSLETTER_PLUGIN_DIR . "templates/$template/template.html" );
		$post_content = str_replace( '[UN_PLUGIN_DIR]', ULTIMATE_NEWSLETTER_PLUGIN_URL, $post_content );
		
		$css     	  = file_get_contents( ULTIMATE_NEWSLETTER_PLUGIN_DIR . "templates/$template/style.css" );

		$emogrifier = new Emogrifier();
		$emogrifier->setHtml( $post_content );
		$emogrifier->setCss( $css );
		$post_content = $emogrifier->emogrifyBodyContent();
					
		// Insert Newsletter
		$args = array(
			'post_type'    => 'ultimate_newsletters',
			'post_content' => $post_content,
			'post_title'   => __( 'My First Newsletter', 'ultimate-newsletter' ),
			'post_status'  => 'publish'
		);
		
		$newsletter_id = wp_insert_post( $args );	
		
		wp_set_object_terms( $newsletter_id, (int) $email_group, 'un_email_groups', true );
		update_post_meta( $newsletter_id, 'template', $template );
		update_post_meta( $newsletter_id, 'from_name', $general_settings['from_name'] );	
		update_post_meta( $newsletter_id, 'from_email', $general_settings['from_email'] );
		update_post_meta( $newsletter_id, 'reply_to_name', $general_settings['reply_to_name'] );
		update_post_meta( $newsletter_id, 'reply_to_email', $general_settings['reply_to_email'] );
		update_post_meta( $newsletter_id, 'status', 'draft' );
		
	}
	
}
