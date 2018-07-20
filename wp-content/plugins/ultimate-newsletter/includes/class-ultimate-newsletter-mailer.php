<?php

/**
 * Mailer
 *
 * @link          https://yendif.com/
 * @since         1.1.0
 *
 * @package       ultimate-newsletter
 * @subpackage    ultimate-newsletter/includes
 */

// Exit if accessed directly
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Ultimate_Newsletter_Mailer Class
 *
 * @since    1.1.0
 */

class Ultimate_Newsletter_Mailer {

	/**
	 * Array that holds email parameters.
	 *
	 * @since    1.2.0
	 * @var      array
	 */
	private static $params = array();

	/**
 	 * Send mail.
 	 *
 	 * @since    1.1.0
	 *
	 * @param    string|array    $to               Array or comma-separated list of email addresses to send message.
	 * @param    string          $subject          Email subject.
	 * @param    string          $message          Message contents.
	 * @param	 int             $newsletter_id    Newsletter ID.
	 * @return   bool                              Whether the email contents were sent successfully.
	 */
	public static function send_mail( $to, $subject, $message, $newsletter_id = 0 ) {
		
		$general_settings = get_option( 'un_general_settings' );
		$email_settings   = get_option( 'un_email_settings' );
		$smtp_settings    = get_option( 'un_email_smtp_settings' );

		$email = get_option( 'admin_email' );
		$name  = get_option( 'blogname');
		
		$params = array();

		// From Email
		if( ! empty( $general_settings['from_email'] ) ) {
			$params['from_email'] = $general_settings['from_email'];
		} else {
			$params['from_email'] = $email;
		}
	
		if( $newsletter_id > 0 ) {
			$newsletter_from_email = get_post_meta( $newsletter_id, 'from_email', true );
			if( ! empty( $newsletter_from_email ) ) $params['from_email'] = $newsletter_from_email;
		}
		
		// From Name
		if( ! empty( $general_settings['from_name'] ) ) {
			$params['from_name'] = $general_settings['from_name'];
		} else {
			$params['from_name'] = $name;
		}
	
		if( $newsletter_id > 0 ) {
			$newsletter_from_name = get_post_meta( $newsletter_id, 'from_name', true );
			if( ! empty( $newsletter_from_name ) ) $params['from_name'] = $newsletter_from_name;
		}

		// Reply to Email
		if( ! empty( $general_settings['reply_to_email'] ) ) {
			$params['reply_to_email'] = $general_settings['reply_to_email'];
		} else {
			$params['reply_to_email'] = $from_email;
		}
	
		if( $newsletter_id > 0 ) {
			$newsletter_reply_to_email = get_post_meta( $newsletter_id, 'reply_to_email', true );
			if( ! empty( $newsletter_reply_to_email ) ) $params['reply_to_email'] = $newsletter_reply_to_email;
		}
		
		// Reply to Name
		if( ! empty( $general_settings['reply_to_name'] ) ) {
			$params['reply_to_name'] = $general_settings['reply_to_name'];
		} else {
			$params['reply_to_name'] = $from_name;
		}
	
		if( $newsletter_id > 0 ) {
			$newsletter_reply_to_name = get_post_meta( $newsletter_id, 'reply_to_name', true );
			if( ! empty( $newsletter_reply_to_name ) ) $params['reply_to_name'] = $newsletter_reply_to_name;
		}
		
		// Email Sender
		$params['engine'] = $email_settings['engine'];
		
		if( 'smtp' == $params['engine'] ) {
			if( strpos( $smtp_settings['host'], 'smtp.sendgrid' ) !== false && ! empty( $smtp_settings['web_api'] ) ) {
    			$params['engine'] = 'sendgrid';
			}
		}
	
		// Set Vars
		$params['smtp_settings'] = $smtp_settings;
		$params['to'] = $to;
		$params['subject'] = html_entity_decode( $subject );
		$params['message'] = $message;
		
		self::$params = $params;
	
		// Send Email
		switch( $params['engine'] ) {
			case 'sendgrid' :
				$response = self::sendgrid();
				break;
			default :
				$response = self::phpmailer();
		}
		
		// Return the response
		return $response;
		
	}
	
	/**
 	 * Send mail through PHPMailer.
 	 *
 	 * @since    1.2.0
	 *
	 * @return   bool    Whether the email contents were sent successfully.
	 */
	public static function phpmailer() {
	
		$params = self::$params;
		
		$reply_to_email = $params['reply_to_email'];
		$reply_to_name  = $params['reply_to_name'];
		
		$headers = array(
  			"Reply-To: {$reply_to_name} <{$reply_to_email}>"
		);
		
		// Add actions & filters
		add_action( 'phpmailer_init', array( __CLASS__, 'phpmailer_init' ) );
		
		add_filter( 'wp_mail_from', array( __CLASS__, 'mail_from' ) );
		add_filter( 'wp_mail_from_name',  array( __CLASS__, 'mail_from_name' ) );
		add_filter( 'wp_mail_content_type', array( __CLASS__, 'mail_content_type' ) );
		
		// Send email
		$response = wp_mail( $params['to'], $params['subject'], $params['message'], $headers );
		
		// Remove filters
		remove_filter( 'wp_mail_from', array( __CLASS__, 'mail_from' ) );
		remove_filter( 'wp_mail_from_name',  array( __CLASS__, 'mail_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( __CLASS__, 'mail_content_type' ) );
		
		// Return the response
		return $response;
	
	}
	
	/**
 	 * Send mail through SendGrid Web API.
 	 *
 	 * @since    1.2.0
	 *
	 * @return   bool    Whether the email contents were sent successfully.
	 */
	public static function sendgrid() {
		
		$params = self::$params;
		
		$smtp_settings = $params['smtp_settings'];
		
		$query = array(
			'api_user' => $smtp_settings['username'],
			'api_key'  => $smtp_settings['password'],
			'to'       => $params['to'],
			'replyto'  => $params['reply_to_email'],
			'from'     => $params['from_email'],
			'fromname' => $params['from_name'],
			'subject'  => $params['subject'],
			'html'     => $params['message']
		 );
		
		$request = 'https://api.sendgrid.com/api/mail.send.json';
		
		// Generate curl request
		$session = curl_init( $request );
		
		// Tell curl to use HTTP POST
		curl_setopt( $session, CURLOPT_POST, true );
		
		// Tell curl that this is the body of the POST
		curl_setopt( $session, CURLOPT_POSTFIELDS, http_build_query( $query ) );
		
		// Tell curl not to return headers, but do return the response
		curl_setopt( $session, CURLOPT_HEADER, false );
		curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
		
		// Disable verification for misconfigured hosts :(
        curl_setopt( $session, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $session, CURLOPT_SSL_VERIFYPEER, false );
		
		// obtain response
		$response = curl_exec( $session );
		curl_close( $session );

		// Return the response
		$response = json_decode( $response );
		
		if( isset( $response->message ) && 'success' == $response->message ) {
        	return true;
       	} else {
        	return false;
    	}
	
	}
	
	/**
 	 * Sets the from email value.
	 *
	 * @since    1.1.0
 	 *
 	 * @params	 string    $from_email    Default email address.
 	 * @return	 string    $from_email    Current email address.              
 	 */
	public static function mail_from( $from_email ) {

		return self::$params['from_email'];

	}
	
	/**
 	 * Sets the from name value.
	 *
	 * @since    1.1.0
 	 *
 	 * @params	 string    $from_name    Default from name.
 	 * @return	 string    $from_name    Current from name.              
 	 */
	public static function mail_from_name( $from_name ) {

		return self::$params['from_name'];

	}

	/**
 	 * Set the email content type.
 	 *
 	 * @since    1.0.0
 	 *
 	 * @params	 string    $content_type    Default content type.
 	 * @return	 string                     'text/html'
  	 */
	public static function mail_content_type( $content_type ) {
	
		return 'text/html';
	
	}
	
	/**
	 * Establishing an SMTP connection using PHPMailer.
	 *
	 * @since    1.1.0
	 *
	 * @param    array    $phpmailer    The PHPMailer instance.
	 */
	public static function phpmailer_init( $phpmailer ) {

		$params = self::$params;
		
		if( 'smtp' == $params['engine'] ) {
		 
		 	$smtp_settings = $params['smtp_settings'];
			
    		$phpmailer->isSMTP(); // Set mailer to use SMTP
			$phpmailer->SMTPSecure = $smtp_settings['encryption']; // Choose SSL or TLS, if necessary for your server
    		$phpmailer->Host = $smtp_settings['host']; // The SMTP mail host
			$phpmailer->Port = $smtp_settings['port']; // The SMTP server port number
			
			if( ! empty( $smtp_settings['authentication'] ) ) {
    			$phpmailer->SMTPAuth = true; // Force it to use Username and Password to authenticate
    			$phpmailer->Username = $smtp_settings['username']; // SMTP authentication username
    			$phpmailer->Password = $smtp_settings['password']; // SMTP authentication password
			}
	
		}
		
		// Always remove self at the end
    	remove_action( 'phpmailer_init', array( __CLASS__, 'phpmailer_init' ) );
	
	} 

}
