<?php
/**
 * The file that hold helper functions
 *
 * @link          https://yendif.com
 * @since         1.0.0
 *
 * @package       ultimate-newsletter
 * @subpackage    ultimate-newsletter/includes
 */
 
/**
 * Base64 Encode URL.
 *
 * @since    1.2.0
 *
 * @param    string    $url    URL to be encoded.
 * @retun    string            Encoded string.
 */
function un_base64_encode( $url ) {

	 return str_replace( array( '+', '/' ), array( '-', '_' ), base64_encode( $url ) );
	 
}

/**
 * Base64 Decode URL.
 *
 * @since    1.2.0
 *
 * @param    string    $string    String to be decoded.
 * @retun    string               Decoded URL.
 */
function un_base64_decode( $string ) {

	return base64_decode( str_replace( array( '-', '_' ), array( '+', '/' ), $string ) );
	
}

/**
 * Register custom taxonomy "un_email_groups".
 *
 * @since    1.0.0
 */
function un_register_taxonomy_email_groups() {

	$labels = array(
        'name' 				 => _x( 'Email Groups','Taxonomy General Name', 'ultimate_newsletters' ),
		'singular_name'      => _x( 'Email Group', 'Taxonomy Singular Name', 'ultimate_newsletters' ),
		'menu_name'          => __( 'Email Groups', 'ultimate_newsletters' ),
		'all_items'          => __( 'All Email Groups', 'ultimate_newsletters' ),
		'new_item_name' 	 => __( 'New Email Group', 'ultimate_newsletters' ),
        'add_new_item' 		 => __( 'Add New Email Group', 'ultimate_newsletters' ),
		'edit_item'          => __( 'Edit Email Group', 'ultimate_newsletters' ),
		'update_item'        => __( 'Update Email Group', 'ultimate_newsletters' ),
        'view_item'          => __( 'View Email Group', 'ultimate_newsletters' ),
        'search_items'       => __( 'Search Email Groups', 'ultimate_newsletters' ),
        'not_found'          => __( 'No Email Groups found.', 'ultimate_newsletters' ),
        'not_found_in_trash' => __( 'No Email Groups found in Trash.', 'ultimate_newsletters' ),
		'popular_items'      => NULL,
  	);
		
	$args =  array(
    	'labels' 		     => $labels,
		'hierarchical' 		 => false,
        'public'             => true,
		'show_ui' 			 => true,
        'show-admin-column'  => true,
	    'show_in_nav-menus'  => true,			
        'show_tagcloud' 	 => false,
		'show_in_menu' 		 => 'edit.php?page=ultimate_newsletters',
 	);

	register_taxonomy( 'un_email_groups', array( 'ultimate_newsletters', 'un_subscribers' ), $args );
		
}

/**
 * Generate subscriber token.
 *
 * @since    1.0.0
 *
 * @return   string    $token    Subscriber token.
 */
function un_generate_subscriber_token() {

	return wp_generate_password( 32, false );	

}

/**
 * Get subscriber ID from the query.
 *
 * @since    1.0.0
 *
 * @param    string    $token            Subscriber Token.
 * @return   int       $subscriber_id    Subscriber ID.
 */
function un_get_subscriber_id( $token = '' ) {
	
	$subscriber_id = 0;
	$check_user    = 0;
	
	if( empty( $token ) ) {
	
		if( isset( $_POST['unt'] ) ) {
			$token = trim( $_POST['unt'] );
		} else if( '' != get_query_var('unt') ) {
			$token = get_query_var('unt');
		} else {
			$check_user = 1;
		}
		
	}

	// Get subscrber id from token
	if( ! empty( $token ) ) {
			
		$args = array(
   			'post_type' 	 => 'un_subscribers',
   			'post_status' 	 => 'any',
   			'posts_per_page' => 1,
			'meta_key' 		 => 'token',
   			'meta_value' 	 => sanitize_text_field( $token ),
			'fields'         => 'ids'
		);
			
		$posts = get_posts( $args );
			
		if( count( $posts ) ) {
			$subscriber_id = (int) $posts[0];
		}
			
	}
		
	// Get subscrber id from user data
	if( 1 == $check_user && 0 == $subscriber_id && is_user_logged_in() ) {
			
		$args = array(
  			'post_type' 	 => 'un_subscribers',
   			'post_status' 	 => 'any',
   			'posts_per_page' => 1,
			'meta_key' 		 => 'user_id',
   			'meta_value' 	 => get_current_user_id(),
			'fields'         => 'ids'
		);
		
		$posts = get_posts( $args );
		
		if( count( $posts ) ) $subscriber_id = (int) $posts[0];	
		
	}
	
	return $subscriber_id;
			
}

/**
 * Generate permalink for the unsubscribe page.
 *
 * @since    1.0.0
 *
 * @param    string    $action           Current action.
 * @param    int       $newsletter_id    Newsletter ID.
 * @param    int       $subscriber_id    Subscriber ID.
 * @param    string    $return           Return page link.
 * @return   string    $link             Actions page URL.
 */
function un_get_actions_page_link( $action, $newsletter_id, $subscriber_id, $return = '' ) {
	
	$general_settings = get_option( 'un_general_settings' );
	
	$link = '';
	
	if( $general_settings['actions_page'] > 0 ) {
	
		$link   = get_permalink( $general_settings['actions_page'] );
		$token  = get_post_meta( $subscriber_id, 'token', true );
		$return = un_base64_encode( $return );
	
		if( '' != get_option( 'permalink_structure' ) ) {
		
    		$link = user_trailingslashit( trailingslashit( $link ) . "$action/$newsletter_id/$token/$return" );
			
  		} else {
		
			$url_params = array(
				'una'  => $action,
				'unid' => $newsletter_id,
				'unt'  => $token
			);
			
			if( '' != $return ) $url_params['unr'] = $return;
			
    		$link = add_query_arg( $url_params, $link );
			
  		}
		
	}
  
	return $link;
	
}
	
/**
 * Generate permalink for the confirmation page.
 *
 * @since    1.0.0
 *
 * @param    int       $subscriber_id    Subscriber ID.
 * @return   string    $link             Confirmation page URL.
 */
function un_get_confirmation_page_link( $subscriber_id ) {
	
	$signup_settings = get_option( 'un_signup_confirmation_settings' );

	if( $signup_settings['confirmation_page'] > 0 ) {
		$link = get_permalink( $signup_settings['confirmation_page'] );
		$token = get_post_meta( $subscriber_id, 'token', true );
	
		if( '' != get_option( 'permalink_structure' ) ) {
    		$link = user_trailingslashit( trailingslashit( $link ) . $token );
  		} else {
    		$link = add_query_arg( 'unt', $token, $link );
  		}
	}
  
	return $link;
	
}

/**
 * Generate permalink for the subscriber profile page.
 *
 * @since    1.0.0
 *
 * @param    int       $subscriber_id    Subscriber ID.
 * @return   string    $link             Subscriber profile page URL.
 */
function un_get_subscriber_profile_page_link( $subscriber_id ) {
	
	$general_settings = get_option( 'un_general_settings' );

	if( $general_settings['subscriber_profile_page'] > 0 ) {
		$link = get_permalink( $general_settings['subscriber_profile_page'] );
		$token = get_post_meta( $subscriber_id, 'token', true );
	
		if( '' != get_option( 'permalink_structure' ) ) {
    		$link = user_trailingslashit( trailingslashit( $link ) . $token );
  		} else {
    		$link = add_query_arg( 'unt', $token, $link );
  		}
	}
  
	return $link;
	
}

/**
 * Generate permalink for the unsubscribe page.
 *
 * @since    1.0.0
 *
 * @param    int       $subscriber_id    Subscriber ID.
 * @return   string    $link             Subscriber profile page URL.
 */
function un_get_unsubscribe_page_link( $subscriber_id ) {
	
	$general_settings = get_option( 'un_general_settings' );

	if( $general_settings['unsubscribe_page'] > 0 ) {
		$link = get_permalink( $general_settings['unsubscribe_page'] );
		$token = get_post_meta( $subscriber_id, 'token', true );
	
		if( '' != get_option( 'permalink_structure' ) ) {
    		$link = user_trailingslashit( trailingslashit( $link ) . $token );
  		} else {
    		$link = add_query_arg( 'unt', $token, $link );
  		}
	}
  
	return $link;
	
}

/**
 * Send confirmation email to the subscriber.
 *
 * @since    1.0.0
 *
 * @params	 int    $subscriber_id    Subscriber ID.
 */
function un_send_subscriber_confirmation_email( $subscriber_id ) {
	
	$signup_settings = get_option( 'un_signup_confirmation_settings' );

	$to = get_post_meta( $subscriber_id, 'email', true );
	
	$subject = $signup_settings['subject'];
	
	$name  = get_the_title( $subscriber_id );
	$body = str_replace( '[username]', $name, $signup_settings['body'] );	
	$body = str_replace( '[confirm_link]', '<a href="'.un_get_confirmation_page_link( $subscriber_id ).'">', $body );
	$body = str_replace( '[/confirm_link]', '</a>', $body );
				
	Ultimate_Newsletter_Mailer::send_mail( $to, $subject, nl2br( $body ) );
	
}

/**
 * Notify admin when someone subscribed.
 *
 * @since    1.0.0
 *
 * @params	 int    $subcriber_id    Subscriber ID.
 */
function un_admin_notify_user_subscribed( $subcriber_id ) {
	
	$general_settings = get_option( 'un_general_settings' );

	if( isset( $general_settings['notify_admin_when'] ) && in_array( 'subscribed', $general_settings['notify_admin_when'] ) ) {
	
		$site_name        = get_bloginfo( 'name' );
		$subscriber_name  = get_the_title( $subcriber_id );
		$subscriber_email = get_post_meta( $subcriber_id, 'email', true );
	
		$placeholders = array(
			'{site_name}'        => $site_name,
			'{subscriber_name}'  => $subscriber_name,
			'{subscriber_email}' => $subscriber_email
		);
		
		$to = $general_settings['admin_email'];
		
		$subject = __( '[{site_name}] "{subscriber_name}" subscribed to your newsletters', 'ultimate-newsletter' );
		$subject = strtr( $subject, $placeholders );
	
		$message = __( 'Dear Administrator,<br /><br />The following user has subscribed to your newsletters.<br />User: {subscriber_name}<br />Email: {subscriber_email}<br /><br />Please do not respond to this message. It is automatically generated and is for information purposes only.', 'ultimate-newsletter' );
		$message = strtr( $message, $placeholders );
				
		Ultimate_Newsletter_Mailer::send_mail( $to, $subject, $message );
	
	}
	
}

/**
 * Notify admin when someone unsubscribed.
 *
 * @since    1.0.0
 *
 * @params	 int    $subcriber_id    Subscriber ID.
 */
function un_admin_notify_user_unsubscribed( $subcriber_id ) {
	
	$general_settings = get_option( 'un_general_settings' );

	if( isset( $general_settings['notify_admin_when'] ) && in_array( 'unsubscribed', $general_settings['notify_admin_when'] ) ) {
	
		$site_name        = get_bloginfo( 'name' );
		$subscriber_name  = get_the_title( $subcriber_id );
		$subscriber_email = get_post_meta( $subcriber_id, 'email', true );
	
		$placeholders = array(
			'{site_name}'        => $site_name,
			'{subscriber_name}'  => $subscriber_name,
			'{subscriber_email}' => $subscriber_email
		);
		
		$to = $general_settings['admin_email'];
	
		$subject = __( '[{site_name}] "{subscriber_name}" unsubscribed from your newsletters', 'ultimate-newsletter' );
		$subject = strtr( $subject, $placeholders );
	
		$message = __( 'Dear Administrator,<br /><br />The following user has been unsubscribed from your newsletters.<br />User: {subscriber_name}<br />Email: {subscriber_email}<br /><br />Please do not respond to this message. It is automatically generated and is for information purposes only.', 'ultimate-newsletter' );
		$message = strtr( $message, $placeholders );
				
		Ultimate_Newsletter_Mailer::send_mail( $to, $subject, $message );
	
	}
	
}

/**
 * Prepare e-mail body content.
 *
 * @since    1.0.0
 *
 * @param    int       $newsletter_id     E-mail subject.
 * @param    int       $subscriber_id     Raw body content.
 * @return   string    $body              Modified body content.
 */
function un_prepare_newsletter_content( $newsletter_id, $subscriber_id ) {

	$settings = get_option( 'un_general_settings' );

	$post     = get_post( $newsletter_id );
	$subject  = $post->post_title;
	$html     = $post->post_content;
	$template = get_post_meta( $newsletter_id, 'template', true );
	
	if( 'blank' == $template ) {
		$css  = file_get_contents( ULTIMATE_NEWSLETTER_PLUGIN_DIR . "public/css/ultimate-newsletter-template-blank.css" );
		$html = nl2br( $html );
	} else {
		$css  = file_get_contents( ULTIMATE_NEWSLETTER_PLUGIN_DIR . "templates/$template/style.css" );
	}
	
	ob_start();
	?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<!doctype html>
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
			<!--[if gte mso 15]>
			<xml>
				<o:OfficeDocumentSettings>
				<o:AllowPNG/>
				<o:PixelsPerInch>96</o:PixelsPerInch>
				</o:OfficeDocumentSettings>
			</xml>
			<![endif]-->
			<meta charset="UTF-8">
        	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        	<meta name="viewport" content="width=device-width, initial-scale=1">
			<title><?php echo $subject; ?></title>
            <style type="text/css">
				<?php echo $css; ?>
			</style>
		</head>
        <?php
			// Convert style tags into inline styles
			if( ! class_exists('Emogrifier') ) {
				include_once ULTIMATE_NEWSLETTER_PLUGIN_DIR.'includes/libraries/emogrifier/class-emogrifier.php';
			}
			
			$emogrifier = new Emogrifier();
			$emogrifier->setHtml( $html );
			$emogrifier->setCss( $css );
			$body_content = $emogrifier->emogrifyBodyContent();
				
			// Add link to browser version
			$browser_version = str_replace( array( '[link]', '[/link]' ), array( '<a href="'.un_get_actions_page_link( 'vi', $newsletter_id, $subscriber_id ).'">', '</a>' ), $settings['link_to_browser_version'] );
			$body_content = str_replace( '[UN_LINK_TO_BROWSER_VERSION]', $browser_version, $body_content );
				
			// Add subscriber profile page link
			$subscriber_profile = str_replace( array( '[link]', '[/link]' ), array( '<a href="'.un_get_subscriber_profile_page_link( $subscriber_id ).'">', '</a>' ), $settings['link_to_subscriber_profile'] );
			$body_content = str_replace( '[UN_SUBSCRIBER_PROFILE]', $subscriber_profile, $body_content );
				
			// Add unsubscribe page link
			$unsubscribe_page = str_replace( array( '[link]', '[/link]' ), array( '<a href="'.un_get_unsubscribe_page_link( $subscriber_id ).'">', '</a>' ), $settings['link_to_unsubscribe_page'] );
			$body_content = str_replace( '[UN_UNSUBSCRIBE]', $unsubscribe_page, $body_content );
				
			// Modify URLs to track click rate
			$pattern = "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/";
			$body_content = preg_replace_callback( $pattern, function( $matches ) use( $newsletter_id, $subscriber_id ) {
				$replace_url = un_get_actions_page_link( 'cl', $newsletter_id, $subscriber_id, $matches[0] );
				return esc_url( $replace_url );
        	}, $body_content );
				
			// Append Blank Image to track open rate, Add <body> elements
			$image_url = un_get_actions_page_link( 'op', $newsletter_id, $subscriber_id );
			
			if( 'blank' != $template ) {
				$body_content = preg_replace( '/<div/', '<body', $body_content, 1 );
				$body_content = preg_replace( '~</div>(?!.*%1</div>)~', '<img alt="" src="'.esc_url( $image_url ).'" width="1" height="1" border="0" /></body>', $body_content, 1 );
			} else {
				$body_content = '<body>'.$body_content.'<img alt="" src="'.esc_url( $image_url ).'" width="1" height="1" border="0" /></body>';
			}
			
			// ...
			echo $body_content;
		?> 
	</html>
    <?php
	return ob_get_clean();

}

/**
 * Remove the current newsletter from cron queue.
 *
 * @since    1.0.0
 *
 * @param    int    $newsletter_id    Newsletter ID.
 */
function un_remove_from_cron_queue(  $newsletter_id ) {

	$newsletter_ids = get_option( 'un_cron_queue', array() );

	if( ( $key = array_search( $newsletter_id, $newsletter_ids ) ) !== false ) {
   		unset( $newsletter_ids[ $key ] );
	}
	
	$newsletter_ids = array_values( $newsletter_ids );
			
	update_option( 'un_cron_queue', $newsletter_ids );
	
}