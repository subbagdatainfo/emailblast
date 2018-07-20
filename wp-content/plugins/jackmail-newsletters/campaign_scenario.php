<?php


class Jackmail_Campaign_Scenario extends Jackmail_Campaign_Scenario_Core {

	public function __construct( Jackmail_Core $core ) {

		$this->core = $core;

		add_action( 'admin_footer', array( $this, 'new_wp_user_subscriber_scenario_send_admin_script_call' ) );

		add_action( 'wp_ajax_jackmail_new_wp_user_subscriber_scenario_send', array( $this, 'new_wp_user_subscriber_scenario_send_callback' ) );

		add_action( 'wp_footer', array( $this, 'front_new_wp_user_woocommerce_subscriber_scenario_send_script_call' ) );

		add_action(
			'wp_ajax_jackmail_front_new_wp_user_woocommerce_subscriber_scenario_send',
			array( $this, 'front_new_wp_user_woocommerce_subscriber_scenario_send_callback' )
		);

		add_action( 'jackmail_cron_scenarios', array( $this, 'cron_scenario_call' ) );

		if ( $this->core->is_admin() ) {

			add_action( 'wp_ajax_jackmail_scenario_choice_page', array( $this, 'scenario_choice_page_callback' ) );

			add_action( 'wp_ajax_jackmail_scenario_page', array( $this, 'scenario_page_callback' ) );

			add_action( 'wp_ajax_jackmail_get_scenario', array( $this, 'get_scenario_callback' ) );

			add_action( 'wp_ajax_jackmail_get_scenario_lists_available', array( $this, 'get_scenario_lists_available_callback' ) );
			
			add_action( 'wp_ajax_jackmail_create_scenario', array( $this, 'create_scenario_callback' ) );

			add_action( 'wp_ajax_jackmail_update_scenario', array( $this, 'update_scenario_callback' ) );

			add_action( 'wp_ajax_jackmail_activate_scenario_link_tracking', array( $this, 'activate_scenario_link_tracking_callback' ) );

			add_action( 'wp_ajax_jackmail_deactivate_scenario_link_tracking', array( $this, 'deactivate_scenario_link_tracking_callback' ) );

			add_action( 'wp_ajax_jackmail_scenario_last_step_checker', array( $this, 'scenario_last_step_checker_callback' ) );

			add_action( 'wp_ajax_jackmail_send_scenario_test', array( $this, 'send_scenario_test_callback' ) );

			add_action( 'wp_ajax_jackmail_activate_scenario', array( $this, 'activate_scenario_callback' ) );

			add_action( 'wp_ajax_jackmail_deactivate_scenario', array( $this, 'deactivate_scenario_callback' ) );

		}

	}

	public function scenario_choice_page_callback() {
		$this->core->check_auth();
		$this->core->include_html_file( 'campaign_type_scenario_choice' );
		die;
	}

	public function scenario_page_callback() {
		$this->core->check_auth();
		$scenario_type      = isset( $_POST['choice'] ) ? $this->core->request_text_data( $_POST['choice'] ) : '';
		$warning_check_link = 'unsubscribe';
		if ( $scenario_type === 'widget_double_optin' ) {
			$warning_check_link = $scenario_type;
		}
		$params = array(
			'page_type'          => 'campaign',
			'campaign_type'      => 'scenario',
			'scenario_type'      => $scenario_type,
			'warning_check_link' => $warning_check_link,
			'display_emojis'     => $this->display_emojis()
		);
		$this->core->include_html_file( 'campaign', $params );
		die;
	}

	public function get_scenario_callback() {
		$this->core->check_auth();
		$campaign = null;
		if ( isset( $_POST['id'], $_POST['choice'] ) ) {
			$id_campaign = $this->core->request_text_data( $_POST['id'] );
			$choice      = $this->core->request_text_data( $_POST['choice'] );
			$campaign    = $this->get_scenario( $id_campaign, $choice );
		}
		wp_send_json( $campaign );
		die;
	}

	public function get_scenario_lists_available_callback() {
		$this->core->check_auth();
		$lists = array();
		if ( isset( $_POST['id'], $_POST['send_option'] ) ) {
			$id_campaign = $this->core->request_text_data( $_POST['id'] );
			$send_option = $this->core->request_text_data( $_POST['send_option'] );
			$lists       = $this->get_scenario_lists_available( $id_campaign, $send_option );
		}
		wp_send_json( $lists );
		die;
	}

	public function create_scenario_callback() {
		$this->core->check_auth();
		$result = array(
			'success'             => false,
			'id'                  => '0',
			'content_size'        => false,
			'content_images_size' => false,
			'content_email_json'  => '',
			'content_email_html'  => '',
			'content_email_txt'   => ''
		);
		if ( $this->create_update_scenario_check_isset_posts() ) {
			$id_lists           = $this->core->request_text_data( $_POST['id_lists'] );
			$send_option        = $this->core->request_text_data( $_POST['send_option'] );
			$name               = $this->core->request_text_data( $_POST['name'] );
			$object             = $this->core->request_text_data( $_POST['object'] );
			$sender_name        = $this->core->request_text_data( $_POST['sender_name'] );
			$sender_email       = $this->core->request_text_data( $_POST['sender_email'] );
			$reply_to_name      = $this->core->request_text_data( $_POST['reply_to_name'] );
			$reply_to_email     = $this->core->request_text_data( $_POST['reply_to_email'] );
			$link_tracking      = $this->core->request_text_data( $_POST['link_tracking'] );
			$content_email_json = $this->core->request_stripslashes( $_POST['content_email_json'] );
			$content_email_txt  = $this->core->request_textarea_data( $_POST['content_email_txt'] );
			$data_fields        = $this->create_or_update_scenario_data_fields( $send_option );
			$result             = $this->create_scenario(
				$id_lists, $send_option, $name, $object, $sender_name, $sender_email, $reply_to_name,
				$reply_to_email, $link_tracking, $content_email_json, $content_email_txt, $data_fields
			);
		}
		wp_send_json( $result );
		die;
	}

	public function update_scenario_callback() {
		$result = array(
			'success'             => false,
			'content_size'        => false,
			'content_images_size' => false,
			'content_email_json'  => '',
			'content_email_html'  => '',
			'content_email_txt'   => ''
		);
		if ( isset( $_POST['id'] ) && $this->create_update_scenario_check_isset_posts() ) {
			$id_campaign        = $this->core->request_text_data( $_POST['id'] );
			$id_lists           = $this->core->request_text_data( $_POST['id_lists'] );
			$send_option        = $this->core->request_text_data( $_POST['send_option'] );
			$name               = $this->core->request_text_data( $_POST['name'] );
			$object             = $this->core->request_text_data( $_POST['object'] );
			$sender_name        = $this->core->request_text_data( $_POST['sender_name'] );
			$sender_email       = $this->core->request_text_data( $_POST['sender_email'] );
			$reply_to_name      = $this->core->request_text_data( $_POST['reply_to_name'] );
			$reply_to_email     = $this->core->request_text_data( $_POST['reply_to_email'] );
			$link_tracking      = $this->core->request_text_data( $_POST['link_tracking'] );
			$content_email_json = $this->core->request_stripslashes( $_POST['content_email_json'] );
			$content_email_txt  = $this->core->request_textarea_data( $_POST['content_email_txt'] );
			$data_fields        = $this->create_or_update_scenario_data_fields( $send_option );
			$result             = $this->update_scenario(
				$id_campaign, $id_lists, $send_option, $name, $object, $sender_name, $sender_email, $reply_to_name,
				$reply_to_email, $link_tracking, $content_email_json, $content_email_txt, $data_fields
			);
		}
		wp_send_json( $result );
		die;
	}

	public function activate_scenario_link_tracking_callback() {
		$this->core->check_auth();
		if ( isset( $_POST['id'] ) ) {
			$id_campaign   = $this->core->request_text_data( $_POST['id'] );
			$update_return = $this->activate_scenario_link_tracking( $id_campaign );
			if ( $update_return !== false ) {
				wp_send_json_success();
			}
		}
		wp_send_json_error();
		die;
	}

	public function deactivate_scenario_link_tracking_callback() {
		$this->core->check_auth();
		if ( isset( $_POST['id'] ) ) {
			$id_campaign   = $this->core->request_text_data( $_POST['id'] );
			$update_return = $this->deactivate_scenario_link_tracking( $id_campaign );
			if ( $update_return !== false ) {
				wp_send_json_success();
			}
		}
		wp_send_json_error();
		die;
	}

	public function scenario_last_step_checker_callback() {
		$this->core->check_auth();
		$json = $this->scenario_last_step_checker();
		wp_send_json( $json );
		die;
	}

	public function send_scenario_test_callback() {
		$this->core->check_auth();
		$result = array(
			'success' => false,
			'message' => 'ERROR'
		);
		if ( isset( $_POST['id'], $_POST['test_recipient'] ) ) {
			$id_campaign    = $this->core->request_text_data( $_POST['id'] );
			$test_recipient = $this->core->request_text_data( $_POST['test_recipient'] );
			$result         = $this->send_scenario_test( $id_campaign, $test_recipient );
		}
		wp_send_json( $result );
		die;
	}

	public function activate_scenario_callback() {
		$this->core->check_auth();
		$json = array(
			'success' => false,
			'message' => ''
		);
		if ( isset( $_POST['id'] ) ) {
			$id_campaign = $this->core->request_text_data( $_POST['id'] );
			$json        = $this->activate_scenario( $id_campaign );
		}
		wp_send_json( $json );
		die;
	}

	public function deactivate_scenario_callback() {
		$this->core->check_auth();
		if ( isset( $_POST['id'] ) ) {
			$id_campaign   = $this->core->request_text_data( $_POST['id'] );
			$update_return = $this->deactivate_scenario( $id_campaign );
			if ( $update_return !== false ) {
				wp_send_json_success();
			}
		}
		wp_send_json_error();
		die;
	}

	

	public function new_wp_user_subscriber_scenario_send_admin_script_call() {
		if ( function_exists( 'get_current_screen' ) ) {
			$current_screen = get_current_screen();
			if ( isset( $current_screen->base ) ) {
				if ( $this->core->is_admin() && $current_screen->base === 'users' ) {
					if ( isset( $_GET['id'] ) ) {
						$user_id = (int) $this->core->request_text_data( $_GET['id'] );
						$this->new_wp_user_subscriber_scenario_send_script( $user_id );
					}
				}
			}
		}
	}

	public function new_wp_user_subscriber_scenario_send_callback() {
		$this->core->check_auth();
		if ( isset( $_POST['user_id'] ) ) {
			$user_id = (int) $this->core->request_text_data( $_POST['user_id'] );
			$this->new_wp_user_subscriber_scenario_send( $user_id );
		}
		wp_send_json_success();
		die;
	}

	public function front_new_wp_user_woocommerce_subscriber_scenario_send_script_call() {
		if ( function_exists( 'get_query_var' ) ) {
			if ( get_query_var( 'pagename' ) === 'checkout' ) {
				global $post;
				if ( isset( $post->post_name, $post->post_content, $post->post_title ) ) {
					if ( $post->post_name === 'checkout' && $post->post_content === '[woocommerce_checkout]'
					     && $post->post_title === 'Checkout' ) {
						$this->front_new_wp_user_woocommerce_subscriber_scenario_send_script();
					}
				}
			}
		}
	}

	public function front_new_wp_user_woocommerce_subscriber_scenario_send_callback() {
		$this->core->check_front();
		if ( isset( $_POST['email'], $_POST['order_number'] ) ) {
			$email        = $this->core->request_text_data( $_POST['email'] );
			$order_number = (int) $this->core->request_text_data( $_POST['order_number'] );
			if ( is_email( $email ) && $order_number > 0 ) {
				$this->front_new_wp_user_woocommerce_subscriber_scenario_send( $email );
			}
		}
		wp_send_json_success();
		die;
	}

	public function cron_scenario_call() {
		$this->cron_scenario();
	}

}