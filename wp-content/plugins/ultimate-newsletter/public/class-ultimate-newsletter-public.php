<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link          https://yendif.com
 * @since         1.0.0
 *
 * @package       ultimate-newsletter
 * @subpackage    ultimate-newsletter/public
 */

// Exit if accessed directly
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Ultimate_Newsletter_Public Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_Public {
	
	/**
	 * Get things going.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		add_shortcode( 'ultimate_newsletter', array( $this, 'run_shortcode_ultimate_newsletter' ) );
		add_shortcode( 'un_confirmation', array( $this, 'run_shortcode_confirmation' ) );
		add_shortcode( 'un_subscriber_profile', array( $this, 'run_shortcode_profile' ) );
		add_shortcode( 'un_unsubscribe', array( $this, 'run_shortcode_unsubscribe' ) );
		
	}

	/**
	 * Add rewrite rules.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function add_rewrites() { 

		$general_settings = get_option( 'un_general_settings' );
		$signup_settings  = get_option( 'un_signup_confirmation_settings' );
		
		$url = home_url();
		
		// Actions Page
		$id = $general_settings['actions_page'];
		if( $id > 0 ) {
			$link = str_replace( $url, '', get_permalink( $id ) );			
			$link = trim( $link, '/' );		
			
			add_rewrite_rule( "$link/([^/]+)/([0-9]{1,})/([^/]+)/([^/]+)/?$", 'index.php?page_id='.$id.'&una=$matches[1]&unid=$matches[2]&unt=$matches[3]&unr=$matches[4]', 'top' );
			add_rewrite_rule( "$link/([^/]+)/([0-9]{1,})/([^/]+)/?$", 'index.php?page_id='.$id.'&una=$matches[1]&unid=$matches[2]&unt=$matches[3]', 'top' );
		}
		
		// Subscriber Profile Page
		$id = $general_settings['subscriber_profile_page'];
		if( $id > 0 ) {
			$link = str_replace( $url, '', get_permalink( $id ) );			
			$link = trim( $link, '/' );		
			
			add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$id.'&unt=$matches[1]', 'top' );
		}
		
		// Unsubscribe Page
		$id = $general_settings['unsubscribe_page'];
		if( $id > 0 ) {
			$link = str_replace( $url, '', get_permalink( $id ) );			
			$link = trim( $link, '/' );		
			
			add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$id.'&unt=$matches[1]', 'top' );
		}
		
		// Confirmation Page
		$id = $signup_settings['confirmation_page'];
		if( $id > 0 ) {
			$link = str_replace( $url, '', get_permalink( $id ) );			
			$link = trim( $link, '/' );		
			
			add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$id.'&unt=$matches[1]', 'top' );
		}
	
		// Rewrite tags
		add_rewrite_tag( '%una%', '([^/]+)' );	
		add_rewrite_tag( '%unid%', '([0-9]{1,})' );	
		add_rewrite_tag( '%unt%', '([^/]+)' );				
		add_rewrite_tag( '%unr%', '([^/]+)' );
	
	}
	
	/**
	 * Flush rewrite rules when it's necessary.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	 public function maybe_flush_rules() {

		$rewrite_rules = get_option( 'rewrite_rules' );
				
		if( $rewrite_rules ) {
		
			global $wp_rewrite;
			
			foreach( $rewrite_rules as $rule => $rewrite ) {
				$rewrite_rules_array[$rule]['rewrite'] = $rewrite;
			}
			$rewrite_rules_array = array_reverse( $rewrite_rules_array, true );
		
			$maybe_missing = $wp_rewrite->rewrite_rules();
			$missing_rules = false;		
		
			foreach( $maybe_missing as $rule => $rewrite ) {
				if( ! array_key_exists( $rule, $rewrite_rules_array ) ) {
					$missing_rules = true;
					break;
				}
			}
		
			if( true === $missing_rules ) {
				flush_rewrite_rules();
			}
		
		}
	
	}
	
	/**
	 * Manage form submissions.
	 *
	 * @since    1.0.0
	 */
	public function manage_form_submissions() {
		
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['ultimate_newsletter_nonce'] ) ) {
		
			$subscriber_id = un_get_subscriber_id();
			
			if( $subscriber_id > 0 ) {
			
				// Update subscriber profile
				if( wp_verify_nonce( $_POST['ultimate_newsletter_nonce'], 'un_save_subscriber' ) ) {
			
					$name = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
				
					$args = array(
						'ID'          => $subscriber_id,
						'post_type'   => 'un_subscribers',
						'post_title'  => $name,
						'post_status' => 'publish'
					);
					wp_update_post( $args );
				
					wp_set_object_terms( $subscriber_id, '', 'un_email_groups' );				
					$email_groups = ! empty( $_POST['email_groups'] ) ? $_POST['email_groups'] : '';
					if( ! empty( $email_groups ) ) {
						foreach( $email_groups as $email_group ) {
							wp_set_object_terms( $subscriber_id, (int) $email_group, 'un_email_groups', true );
						}
					}
		
					$old_status = get_post_meta( $subscriber_id, 'status', true );
					
					$new_status = ! empty( $_POST['unsubscribe'] ) ? 'unsubscribed' : 'subscribed';
					
					update_post_meta( $subscriber_id, 'status', $new_status );
					
					if( 'subscribed' == $old_status && 'unsubscribed' == $new_status ) {
						un_admin_notify_user_unsubscribed( $subscriber_id );
					} else if( 'unsubscribed' == $old_status && 'subscribed' == $new_status ) {
						un_admin_notify_user_subscribed( $subscriber_id );
					}
					$subscribed = get_post_meta( $subscriber_id, 'status', true );
					
					set_transient( 'un_subscribers_update', 'update', 30 );
					
					$rediect_url = un_get_subscriber_profile_page_link( $subscriber_id );	
					
								
				}

				// Unsubscribe
				if( wp_verify_nonce( $_POST['ultimate_newsletter_nonce'], 'un_confirmed_unsubscribe' ) ) {
					
					update_post_meta( $subscriber_id, 'status', 'unsubscribed' );
					un_admin_notify_user_unsubscribed( $subscriber_id );
					$rediect_url = un_get_unsubscribe_page_link( $subscriber_id );	
			
				}
			
				// Redirect						
				wp_redirect( $rediect_url );
				exit;
				
			}
			
		}

	}
	
	/**
	 * Parse Request.
	 *
	 * @since    1.0.0
	 */
	public function parse_request( $wp ) {	
		
    	if( array_key_exists( 'una', $wp->query_vars ) && array_key_exists( 'unid', $wp->query_vars ) && array_key_exists( 'unt', $wp->query_vars ) ) {
		
			$newsletter_id = (int) $wp->query_vars['unid'];
			$post_exists = is_string( get_post_status( $newsletter_id ) );

			if( $post_exists ) {
			
				$subscriber_id = un_get_subscriber_id( $wp->query_vars['unt'] );
			
				$staus   = get_post_meta( $newsletter_id, 'status', true );
				$opened  = (array) get_post_meta( $newsletter_id, 'opened', true );
				$clicked = (array) get_post_meta( $newsletter_id, 'clicked', true );
			
				if( 'op' == $wp->query_vars['una'] ) {
			
					if( 'draft' != $staus ) {
					
						// Calculate Open Rate
						$opened[] = strval( $subscriber_id );
						$opened = array_filter( $opened );
					
						update_post_meta( $newsletter_id, 'opened', array_unique( $opened ) );
					
					}
					
					// Full URI to the image
					$image = ULTIMATE_NEWSLETTER_PLUGIN_URL.'public/images/logo.gif';

					// Get the filesize of the image for headers
					$relative_file_path = $_SERVER['DOCUMENT_ROOT'].wp_make_link_relative( $image );	
					$filesize = filesize( $relative_file_path );

					// Now actually output the image requested (intentionally disregarding if the database was affected)
					header( 'Pragma: public' );
					header( 'Expires: 0' );
					header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
					header( 'Cache-Control: private', false );
					header( 'Content-Disposition: attachment; filename="logo.gif"' );
					header( 'Content-Transfer-Encoding: binary' );
					header( 'Content-Length: '.$filesize );
					readfile( $image );
					exit;
			
				} else if( 'vi' == $wp->query_vars['una'] ) {
				
					echo un_prepare_newsletter_content( $newsletter_id, $subscriber_id );
					exit;
				
				} else if( 'cl' == $wp->query_vars['una'] ) {
			
					if( 'draft' != $staus ) {
					
						// Calculate Click Rate
						$clicked[] = strval( $subscriber_id );
						$clicked = array_filter( $clicked );
					
						update_post_meta( $newsletter_id, 'clicked', array_unique( $clicked ) );
					
						// Calculate Open Rate (There is a chance for open rate fail. Let us re-calculate this now.)
						$opened[] = strval( $subscriber_id );
						$opened = array_filter( $opened );
					
						update_post_meta( $newsletter_id, 'opened', array_unique( $opened ) );
					
					}
					
					// Redirect to actual link
					if( array_key_exists( 'unr', $wp->query_vars ) ) {
						$redirect_url = esc_url( un_base64_decode( $wp->query_vars['unr'] ) );
					} else {
						$redirect_url = home_url();
					}
					
					wp_redirect( $redirect_url );
					exit;
			
				}				
			
			}
					
    	}
		
	}
	
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( ULTIMATE_NEWSLETTER_PLUGIN_SLUG, ULTIMATE_NEWSLETTER_PLUGIN_URL.'public/css/ultimate-newsletter-public.css', array(), ULTIMATE_NEWSLETTER_PLUGIN_VERSION, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( ULTIMATE_NEWSLETTER_PLUGIN_SLUG, ULTIMATE_NEWSLETTER_PLUGIN_URL . 'public/js/ultimate-newsletter-public.js', array( 'jquery' ), ULTIMATE_NEWSLETTER_PLUGIN_VERSION, false );
		
		wp_localize_script( ULTIMATE_NEWSLETTER_PLUGIN_SLUG, 'un', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			) 
		);

	}


	/**
	 * Process the shortcode [ultimate_newsletter].
	 *
	 * @since    1.0.0
	 */
	public function run_shortcode_ultimate_newsletter() {
	
		return ''; 
				
	}	
	
	/**
	 * Process the shortcode [un_confirmation].
	 *
	 * @since    1.0.0
	 */
	public function run_shortcode_confirmation() {
	
		$subscriber_id = un_get_subscriber_id();

		if( $subscriber_id > 0 ) {

			$old_status = get_post_meta( $subscriber_id, 'status', true );	
			
			if( 'subscribed' == $old_status ) {
			
				$message = __( 'Thank you! You already have an active subscription.', 'ultimate-newsletter' );
				
			} else {
			
				update_post_meta( $subscriber_id, 'status', 'subscribed' );	
				un_admin_notify_user_subscribed( $subscriber_id );
				
				$message = __( 'Thank you for confirming your subscription!', 'ultimate-newsletter' );
				
			}
			
		} else {
		
			$message = __( "Sorry, you don't have permission to do this action", 'ultimate-newsletter' );
			
		}

		return $message; 
				
	}	
	
	/**
	 * Process the shortcode [un_profile].
	 *
	 * @since    1.0.0
	 */
	public function run_shortcode_profile() {	
		
		// Load dependencies
		wp_enqueue_style( ULTIMATE_NEWSLETTER_PLUGIN_SLUG );
		
		// ...
		$subscriber_id = un_get_subscriber_id();
		
		if( $subscriber_id > 0 ) {
		
			$email_groups_list = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
			
			$title = get_the_title( $subscriber_id );
			
			$email = get_post_meta( $subscriber_id, 'email', true ); 
			$token = get_post_meta( $subscriber_id, 'token', true );
			$status = get_post_meta( $subscriber_id, 'status', true );  
			$email_groups = wp_get_object_terms( $subscriber_id, 'un_email_groups', array( 'fields' => 'ids' ) );
			
			if( 'update' == get_transient( 'un_subscribers_update' )  ) {
				$update = __( 'Successfully updated your subscription.', 'ultimate-newsletter' );
				delete_transient( 'un_subscribers_update' );
			}
			ob_start();
			include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'public/partials/ultimate-newsletter-subscriber-profile.php';
			$message = ob_get_clean();
			
		} else {

			$message = __( "Sorry, you don't have permission to do this action", 'ultimate-newsletter' );
			
		}	
			
		return $message; 
				
	}	
	
	/**
	 * Process the shortcode [un_unsubscribe].
	 *
	 * @since    1.0.0
	 */
	public function run_shortcode_unsubscribe() {
	
		$subscriber_id = un_get_subscriber_id();
		
		if( $subscriber_id > 0 ) {

			$status = get_post_meta( $subscriber_id, 'status', true );
			
			if( 'unsubscribed' == $status ) {
				
				$message = __( "You've been unsubscribed from our newsletters.", 'ultimate-newsletter' );
			
			} else {
			
				$token  = get_post_meta( $subscriber_id, 'token', true );
				
				ob_start();
				include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'public/partials/ultimate-newsletter-unsubscribe-confirm.php';
				$message = ob_get_clean();
				 
			}
			
		
		} else {
		
			$message = __( "Sorry, you don't have permission to do this action", 'ultimate-newsletter' );
			
		}
			
			
		return $message; 
		
	}
	
}
