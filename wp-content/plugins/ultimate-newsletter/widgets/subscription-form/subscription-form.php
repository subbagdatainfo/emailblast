<?php

/**
 * Widget: Newsletter Subscription Form.
 *
 * @link          https://yendif.com
 * @since         1.0.0
 *
 * @package       ultimate-newsletter
 * @subpackage    ultimate-newsletter/widgets
 */

// Exit if accessed directly
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Ultimate_Newsletter_Widget_Subscription_Form
 *
 * @since    1.0.0
 */
 
class Ultimate_Newsletter_Widget_Subscription_Form extends WP_Widget {

	/**
 	 * Get things started.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $widget_details = array(
            'classname'	  => 'ultimate-newsletter-widget-subscription-form',
            'description' => __( 'An Ultimate Newsletter Subscription Form', 'ultimate-newsletter' )
        );
		
        parent::__construct( 'ultimate-newsletter-widget-subscription-form', __( 'Newsletter Subscription Form', 'ultimate-newsletter' ), $widget_details );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ), 11 );
		add_action( 'wp_ajax_un_add_subscriber', array( $this, 'add_subscriber' ) );
 		add_action( 'wp_ajax_nopriv_un_add_subscriber', array( $this, 'add_subscriber' ) );
		
    }
	
	/**
 	 * Enqueue styles and scripts.
     *
     * @since    1.0.0
     */
	public function enqueue_styles_scripts() {
	
		if( is_active_widget( false, false, $this->id_base, true ) ) {
		
			wp_enqueue_style( ULTIMATE_NEWSLETTER_PLUGIN_SLUG );
			wp_enqueue_script( ULTIMATE_NEWSLETTER_PLUGIN_SLUG );
			
		}
		
	}
	
	/**
	 * Display the content of the widget.
	 *
	 * @since    1.0.0
	 *
	 * @param    array    $args
	 * @param    array    $instance
	 */
    public function widget( $args, $instance ) {
	
		$display           = isset( $instance['display'] ) ? $instance['display']: 'vertical';
		$has_name          = isset( $instance['has_name'] ) ? (int) $instance['has_name'] : 0;
		$name_label        = isset( $instance['name_label'] ) ? $instance['name_label'] : '';
		$name_placeholder  = isset( $instance['name_placeholder'] ) ? $instance['name_placeholder'] : '';
		$email_label       = isset( $instance['email_label'] ) ? $instance['email_label'] : '';
		$email_placeholder = isset( $instance['email_placeholder'] ) ? $instance['email_placeholder'] : '';
		$button_label      = isset( $instance['button_label'] ) ? $instance['button_label'] : __( 'Subscribe', 'ultimate-newsletter' );
		$email_groups      = isset( $instance['email_groups'] ) ? $instance['email_groups'] : array();
		$widget_id         = $args['widget_id'].'-wrapper';
		
		echo $args['before_widget'];
		
		if( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		
		if( ! empty( $instance['description'] ) ) {
			echo '<p>'.$instance['description'].'</p>';			 
		}
		
		include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'widgets/subscription-form/views/widget.php';	
		
		echo $args['after_widget'];
		
	}	
	
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @since     1.0.0
	 * @access    public
	 *
	 * @param	  array	   $new_instance    The new instance of values to be generated via the update.
	 * @param	  array    $old_instance    The previous instance of values before the update.
	 */
    public function update( $new_instance, $old_instance ) {  
		
		$instance = array();
		
		$instance['title']             = isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['description']       = isset( $new_instance['description'] ) ? strip_tags( $new_instance['description'] ) : '';
		$instance['display']           = isset( $new_instance['display'] ) ?  strip_tags( $new_instance['display'] ) : 'vertical';
		$instance['has_name']          = isset( $new_instance['has_name'] ) ? 1 : 0;
		$instance['name_label']        = isset( $new_instance['name_label'] ) ? strip_tags( $new_instance['name_label'] ) : '';
		$instance['name_placeholder']  = isset( $new_instance['name_placeholder'] ) ? strip_tags( $new_instance['name_placeholder'] ) : '';
		$instance['email_label']       = isset( $new_instance['email_label'] ) ? strip_tags( $new_instance['email_label'] ) : '';
		$instance['email_placeholder'] = isset( $new_instance['email_placeholder'] ) ? strip_tags( $new_instance['email_placeholder'] ) : '';
		$instance['button_label']      = isset( $new_instance['button_label'] ) ? strip_tags( $new_instance['button_label'] ) : '';
		$instance['email_groups']      = isset( $new_instance['email_groups'] ) ? array_map( 'esc_attr', $new_instance['email_groups'] ) : array(); 

        return $instance;
		
    }
	
	/**
	 * Display the options form on admin.
	 *
	 * @since    1.0.0
	 *
	 * @param    array    $instance    The widget options
	 *
	 */
    public function form( $instance ) {
	
		// Define the array of defaults
		$defaults = array(
			'title'             => __( 'Newsletter', 'ultimate-newsletter' ),
			'description'       => __( "Simply subscribe to our newsletters and we will be in touch. Don't worry, we won't spam you and of course you may unsubscribe at any time.", 'ultimate-newsletter' ),
			'has_name'          => 1,
			'display'           => 'vertical',
			'name_label'        => __( 'Name', 'ultimate-newsletter' ),
			'name_placeholder'  => __( 'Enter Your Name', 'ultimate-newsletter' ),
			'email_label'       => __( 'Enter Your Email', 'ultimate-newsletter' ),
			'email_placeholder' => __( 'joe@example.com', 'ultimate-newsletter' ),
			'button_label'      => __( 'Subscribe', 'ultimate-newsletter' ),
			'email_groups'      => array(),
		);
		
		// Parse incoming $instance into an array and merge it with $defaults
		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);
			
		// Display the admin form		
		include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'widgets/subscription-form/views/form.php';
		
    }
	
	/**
 	 * Add subscriber
     *
     * @since    1.0.0
     */
	public function add_subscriber() {
		
		$signup_settings = get_option( 'un_signup_confirmation_settings' );
		
		$response = array( 'error' => 0, 'message' => $signup_settings['thank_you_message'] );
		
		$email        = ! empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$name         = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$token        = un_generate_subscriber_token();
		$status       = empty( $signup_settings['enabled'] ) ? 'subscribed' : 'unconfirmed';
		$email_groups = ! empty( $_POST['email_groups'] ) ? explode( ',', $_POST['email_groups'] ) : '';
		$email_groups = (array) $email_groups;
		$email_groups = array_filter( $email_groups );
		
		if( empty( $email ) ) {
			$response['error']   = 1;
			$response['message'] = __( 'Invalid Email ID.',  'ultimate-newsletter' );
		}
		
		if( ! empty( $email ) ) {
		
			if( empty( $name ) ) {
				$email_parts = explode( '@', $email );
				$name = $email_parts[0];
			}
			
		}

		if( empty( $email_groups ) ) {
			$response['error']   = 1;
			$response['message'] = __( 'Sorry, there is no email-group associated to this form. Kindly report to the site administrator.',  'ultimate-newsletter' );
		}
		
		if( 0 == $response['error'] ) {
		
			$args = array(
    			'post_type' 	 => 'un_subscribers',
    			'post_status' 	 => 'any',
    			'posts_per_page' => 1,
				'meta_key' 		 => 'email',
    			'meta_value' 	 => $email,
				'fields'         => 'ids'
			);
		
			$posts = get_posts( $args );
			
			if( count( $posts ) ) {
			
				$subscriber_id = (int) $posts[0];
				
				$existing_email_groups = wp_get_object_terms( $subscriber_id, 'un_email_groups', array( 'fields' => 'ids' ) );
				if( ! is_wp_error( $existing_email_groups ) && ! empty( $existing_email_groups ) ) {
					$email_groups = array_merge( $existing_email_groups, $email_groups );
					$email_groups = array_unique( $email_groups );
				}
				
				wp_set_object_terms( $subscriber_id, '', 'un_email_groups' );	
				foreach( $email_groups as $email_group ) {
					wp_set_object_terms( $subscriber_id, (int) $email_group, 'un_email_groups', true );
				}
				
				$status = get_post_meta( $subscriber_id, 'status', true );
				if( 'unsubscribed' == $status ) un_send_subscriber_confirmation_email( $subscriber_id );
				
			} else {
			
		 		$args = array(
					'post_type'	  => 'un_subscribers',
					'post_title'  => $name,
					'post_status' => 'publish'					
				);	
			
				$subscriber_id = wp_insert_post( $args );	
			
				update_post_meta( $subscriber_id, 'user_id', 0 );	
				update_post_meta( $subscriber_id, 'email', $email );
				update_post_meta( $subscriber_id, 'token', $token );
				update_post_meta( $subscriber_id, 'status', $status );
				
				if( ! empty( $email_groups ) ) {
					foreach( $email_groups as $email_group ) {
						wp_set_object_terms( $subscriber_id, (int) $email_group, 'un_email_groups', true );
					}
				}
				
				if( 'unconfirmed' == $status ) un_send_subscriber_confirmation_email( $subscriber_id );
				if( 'subscribed' == $status ) un_admin_notify_user_subscribed( $subscriber_id );
				
			}
				
		}
		
		echo wp_json_encode( $response );
		exit;
	
	}
			
}

add_action( 'widgets_init', create_function( '', 'register_widget("Ultimate_Newsletter_Widget_Subscription_Form");' ) );