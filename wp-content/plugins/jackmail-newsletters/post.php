<?php


class Jackmail_Post extends Jackmail_Post_Core {

	public function __construct( Jackmail_Core $core ) {

		$this->core = $core;

		if ( $this->core->is_admin() ) {

			add_action( 'add_meta_boxes', array( $this, 'jackmail_post_box_call' ) );

			add_action( 'wp_insert_post', array( $this, 'post_jackmail_scenario_exclude_call' ), 20 );

			add_action( 'wp_ajax_jackmail_create_campaign_with_post', array( $this, 'create_campaign_with_post_callback' ) );

		}

	}

	public function jackmail_post_box_call() {
		$this->jackmail_post_box();
	}

	public function post_jackmail_scenario_exclude_call( $post_id ) {
		if ( isset( $_POST['post_type'] ) ) {
			$post_type = $this->core->request_text_data( $_POST['post_type'] );
			$this->post_jackmail_scenario_exclude( $post_id, $post_type );
		}
	}

	public function create_campaign_with_post_callback() {
		$this->core->check_auth();
		$result = array(
			'success' => false,
			'id'      => '0'
		);
		if ( isset( $_POST['post_id'] ) ) {
			$post_id = $this->core->request_text_data( $_POST['post_id'] );
			$result  = $this->create_campaign_with_post( $post_id );
		}
		wp_send_json( $result );
		die;
	}

}