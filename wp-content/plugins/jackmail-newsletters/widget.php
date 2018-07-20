<?php

class Jackmail_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'jackmail_widget', 'Jackmail',
			array(
				'description'                 => __( 'Create your form and collect submit into your Jackmail contacts list.', 'jackmail-newsletters' ),
				'customize_selective_refresh' => true
			)
		);

		add_action( 'widgets_init', array( $this, 'jackmail_widget' ) );

	}

	public function jackmail_widget() {
		register_widget( 'Jackmail_Widget' );
	}

	private function get_lists() {
		global $wpdb;
		$sql   = "
		SELECT `id`, `name`, `fields`
		FROM `{$wpdb->prefix}jackmail_lists`
		WHERE `type` = ''
		ORDER BY `created_date_gmt` DESC";
		$lists = $wpdb->get_results( $sql );
		return $lists;
	}

	private function get_json_js( Array $array ) {
		$core = new Jackmail_Core();
		return $core->json_encode( $array );
	}

	public function form( $instance ) {
		$core          = new Jackmail_Core();
		$lists         = $this->get_lists();
		$lists_details = array();
		foreach ( $lists as $key => $list ) {
			$fields_explode  = $core->explode_fields( $list->fields );
			$lists_details[] = array(
				'id'         => $list->id,
				'all_fields' => $fields_explode
			);
		}
		$params_configuration = $this->get_configuration( $instance );
		$params               = array(
			'lists'                      => $lists,
			'lists_details'              => $lists_details,
			'js_lists_details'           => $this->get_json_js( $lists_details ),
			'id'                         => $core->get_current_timestamp() . mt_rand( 1000000, 9999999 ),
			'double_optin_scenario_link' => 'admin.php?page=jackmail_scenario#/scenario/widget_double_optin/' . $core->get_widget_double_optin_scenario() . '/create',
			'emailbuilder_installed'     => $core->emailbuilder_installed()
		);
		$params               = array_merge( $params, $params_configuration );
		include plugin_dir_path( __FILE__ ) . 'html/widget_back.php';
	}

	public function update( $instance, $old_instance ) {
		$update            = $this->get_configuration( $instance );
		$update['id_list'] = isset( $instance['id_list'] ) ? $instance['id_list'] : '';
		$update['title']   = isset( $instance['title'] ) ? $instance['title'] : '';
		$update['fields']  = isset( $instance['fields'] ) ? $instance['fields'] : '[]';
		return $update;
	}

	private function get_configuration( $instance ) {
		$double_optin                   = '0';
		$double_optin_confirmation_type = 'default';
		$double_optin_confirmation_url  = '';
		$gdpr                           = '0';
		$gdpr_content                   = '';
		if ( isset( $instance['double_optin'], $instance['double_optin_confirmation_type'], $instance['double_optin_confirmation_url'] ) ) {
			if ( $instance['double_optin'] === 'on' || $instance['double_optin'] === '1' ) {
				$double_optin                   = '1';
				$double_optin_confirmation_type = $instance['double_optin_confirmation_type'];
				if ( $double_optin_confirmation_type === 'url' ) {
					$double_optin_confirmation_url = $instance['double_optin_confirmation_url'];
				}
			}
		}
		if ( isset( $instance['gdpr'], $instance['gdpr_content'] ) ) {
			if ( $instance['gdpr'] === 'on' || $instance['gdpr'] === '1' ) {
				$gdpr         = '1';
				$gdpr_content = $instance['gdpr_content'];
			}
		}
		return array(
			'id_list'                        => isset( $instance['id_list'] ) ? $instance['id_list'] : '',
			'title'                          => isset( $instance['title'] ) ? $instance['title'] : '',
			'fields'                         => isset( $instance['fields'] ) ? $instance['fields'] : '[]',
			'double_optin'                   => $double_optin,
			'double_optin_confirmation_type' => $double_optin_confirmation_type,
			'double_optin_confirmation_url'  => $double_optin_confirmation_url,
			'gdpr'                           => $gdpr,
			'gdpr_content'                   => strip_tags( $gdpr_content, '<b><a>' )
		);
	}

	public function widget( $args, $instance ) {
		$core = new Jackmail_Core();
		global $wpdb;
		if ( ! isset( $instance['double_optin'] )
		     || ! isset( $instance['double_optin_confirmation_type'] )
		     || ! isset( $instance['double_optin_confirmation_url'] ) ) {
			$instance['double_optin']                   = '0';
			$instance['double_optin_confirmation_type'] = 'default';
			$instance['double_optin_confirmation_url']  = '';
		}
		if ( ! isset( $instance['gdpr'] ) || ! isset( $instance['gdpr_content'] ) ) {
			$instance['gdpr']         = '0';
			$instance['gdpr_content'] = '';
		}
		if ( isset( $instance['id_list'], $instance['title'], $instance['fields'],
			$instance['double_optin'], $instance['double_optin_confirmation_type'], $instance['double_optin_confirmation_url'] ) ) {
			if ( isset( $args['before_widget'], $args['before_title'], $args['after_title'], $args['after_widget'], $args['widget_id'] ) ) {
				$id_list      = $instance['id_list'];
				$widget_id    = substr( $args['widget_id'], 16 );
				$confirm_data = array(
					'id_list' => '',
					'email'   => '',
					'fields'  => ''
				);
				if ( isset( $_GET['jackmail_widget_confirm'] ) ) {
					$data = $core->request_text_data( $_GET['jackmail_widget_confirm'] );
					$data = str_rot13( base64_decode( $data ) );
					$data = json_decode( $data, true );
					if ( isset( $data['widget_id'], $data['id_list'], $data['email'], $data['fields'], $data['rand'] ) ) {
						$confirm_widget_id = $core->request_text_data( $data['widget_id'] );
						if ( $widget_id === $confirm_widget_id ) {
							$check_json = json_decode( $data['fields'], true );
							if ( $check_json || is_array( $check_json ) ) {
								$confirm_data = array(
									'id_list' => $core->request_text_data( $data['id_list'] ),
									'email'   => strtolower( $core->request_email_data( $data['email'] ) ),
									'fields'  => $data['fields']
								);
							}
						}
					}
				}
				$sql  = "
				SELECT `fields`
				FROM `{$wpdb->prefix}jackmail_lists`
				WHERE `id` = %s";
				$list = $wpdb->get_row( $wpdb->prepare( $sql, $id_list ) );
				if ( isset( $list->fields ) ) {
					$params = array(
						'url'                            => admin_url( 'admin-ajax.php' ),
						'action_submit'                  => 'jackmail_front_widget_submitted',
						'action_confirm'                 => 'jackmail_front_widget_confirmed',
						'widget_id'                      => $widget_id,
						'before_widget'                  => $args['before_widget'],
						'title'                          => $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'],
						'after_widget'                   => $args['after_widget'],
						'fields'                         => $core->explode_fields( $instance['fields'] ),
						'double_optin'                   => $instance['double_optin'],
						'double_optin_confirmation_type' => $instance['double_optin_confirmation_type'],
						'double_optin_confirmation_url'  => $instance['double_optin_confirmation_url'],
						'gdpr'                           => $instance['gdpr'],
						'gdpr_content'                   => strip_tags( $instance['gdpr_content'], '<b><a>' ),
						'id'                             => $core->get_current_timestamp() . mt_rand( 1000000, 9999999 ),
						'list_fields'                    => $core->explode_fields( $list->fields ),
						'confirm_data'                   => $confirm_data
					);
					include plugin_dir_path( __FILE__ ) . 'html/widget_front.php';
				}
			}
		}
	}

}

class Jackmail_Widget_Event extends Jackmail_Campaign_Scenario_Core {

	public function __construct( Jackmail_Core $core ) {

		$this->core = $core;

		add_action( 'wp_ajax_jackmail_front_widget_submitted', array( $this, 'front_widget_submitted_callback' ) );

		add_action( 'wp_ajax_nopriv_jackmail_front_widget_submitted', array( $this, 'front_widget_submitted_callback' ) );

		add_action( 'wp_ajax_jackmail_front_widget_confirmed', array( $this, 'front_widget_confirmed_callback' ) );

		add_action( 'wp_ajax_nopriv_jackmail_front_widget_confirmed', array( $this, 'front_widget_confirmed_callback' ) );

		add_action( 'wp_footer', array( $this, 'front_widget_confirmation_script' ) );

	}

	public function front_widget_submitted_callback() {
		$this->core->check_front();
		global $wpdb;
		$json = array(
			'success' => false,
			'message' => __( 'Error: Email was not saved.', 'jackmail-newsletters' )
		);
		if ( isset( $_POST['jackmail_widget_id'], $_POST['jackmail_widget_email'], $_POST['jackmail_widget_fields'] ) ) {
			$widget_id_post = $this->core->request_text_data( $_POST['jackmail_widget_id'] );
			$email_post     = strtolower( $this->core->request_email_data( $_POST['jackmail_widget_email'] ) );
			$fields_post    = $this->core->request_text_data( $_POST['jackmail_widget_fields'] );
			$widgets        = get_option( 'widget_jackmail_widget' );
			if ( isset( $widgets[ $widget_id_post ] ) ) {
				$widget_configuration = $widgets[ $widget_id_post ];
				if ( ! isset( $widget_configuration['double_optin'] ) ) {
					$widget_configuration['double_optin'] = '0';
				}
				if ( isset( $widget_configuration['id_list'], $widget_configuration['title'],
					$widget_configuration['fields'], $widget_configuration['double_optin'] ) ) {
					if ( is_email( $email_post ) ) {
						if ( $widget_configuration['double_optin'] === '0' || ! $this->core->emailbuilder_installed() ) {
							$result = $this->insert_widget_data( $widget_configuration['id_list'], $email_post, $fields_post );
							if ( $result ) {
								$json = array(
									'success' => true,
									'message' => __( 'Email saved.', 'jackmail-newsletters' )
								);
							}
						} else {
							$table_list_contacts = "{$wpdb->prefix}jackmail_lists_contacts_{$widget_configuration['id_list']}";
							if ( $this->core->check_table_exists( $table_list_contacts, false ) ) {
								$sql  = "
								SELECT `email`
								FROM `{$table_list_contacts}`
								WHERE `email` = %s";
								$data = $wpdb->get_row( $wpdb->prepare( $sql, $email_post ) );
								if ( isset( $data->email ) ) {
									$json = array(
										'success' => true,
										'message' => __( 'Email saved.', 'jackmail-newsletters' )
									);
								} else {
									$sql  = "
									SELECT *
									FROM `{$wpdb->prefix}jackmail_scenarios`
									WHERE `send_option` = 'widget_double_optin'";
									$data = $wpdb->get_row( $sql );
									if ( isset( $data->id ) ) {
										$id_campaign          = $data->id;
										$campaign_id          = $data->campaign_id;
										$object               = $data->object;
										$sender_name          = $data->sender_name;
										$sender_email         = $data->sender_email;
										$reply_to_name        = $data->reply_to_name;
										$reply_to_email       = $data->reply_to_email;
										$content_email_json   = $data->content_email_json;
										$content_email_txt    = $data->content_email_txt;
										$content_email_images = $data->content_email_images;
										$link_tracking        = $data->link_tracking;
										if ( is_email( $email_post ) ) {
											$link_params        = array(
												'widget_id' => $widget_id_post,
												'email'     => $email_post,
												'fields'    => $fields_post,
												'id_list'   => $widget_configuration['id_list'],
												'rand'      => rand( 0, 10000 )
											);
											$link_params        = base64_encode( str_rot13( json_encode( $link_params ) ) );
											$link               = get_home_url() . '?jackmail_widget_confirm=' . $link_params;
											$content_email_json = str_replace( '((WIDGET_DOUBLE_OPTIN))', $link, $content_email_json );
											$campaign_result    = $this->generate_scenario(
												$id_campaign, 'unit_scenario', $campaign_id, $widget_configuration['id_list'],
												$object, $sender_name, $sender_email, $reply_to_name, $reply_to_email,
												$content_email_json, $content_email_txt, $content_email_images,
												array(), $link_tracking, '0', '1', $email_post
											);
											if ( $campaign_result['message'] === 'OK' ) {
												$json = array(
													'success' => true,
													'message' => __( 'You will receive an email to confirm your subscription.', 'jackmail-newsletters' )
												);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		wp_send_json( $json );
		die;
	}

	public function front_widget_confirmed_callback() {
		$this->core->check_front();
		$json = array(
			'success' => false,
			'message' => __( 'Error: Email was not saved.', 'jackmail-newsletters' )
		);
		if ( isset( $_POST['jackmail_widget_id_list'], $_POST['jackmail_widget_email'], $_POST['jackmail_widget_fields'] ) ) {
			$id_list = $this->core->request_text_data( $_POST['jackmail_widget_id_list'] );
			$email   = $this->core->request_email_data( $_POST['jackmail_widget_email'] );
			$fields  = $this->core->request_text_data( $_POST['jackmail_widget_fields'] );
			$result  = $this->insert_widget_data( $id_list, $email, $fields );
			if ( $result ) {
				$json = array(
					'success' => true,
					'message' => __( 'Email saved.', 'jackmail-newsletters' )
				);
			}
		}
		wp_send_json( $json );
		die;
	}

	public function front_widget_confirmation_script() {
		if ( isset( $_GET['jackmail_widget_confirm'] ) ) {
			$data = $this->core->request_text_data( $_GET['jackmail_widget_confirm'] );
			$data = str_rot13( base64_decode( $data ) );
			$data = json_decode( $data, true );
			if ( isset( $data['test'], $data['rand'] ) ) {
				?>
				<script>
					setTimeout( function() {
						alert( '<?php esc_attr_e( 'Your subscription to our list has been confirmed [Test]', 'jackmail-newsletters' ) ?>' );
					} );
				</script>
				<?php
			}
		}
	}

	private function insert_widget_data( $id_list, $email, $fields_values ) {
		global $wpdb;
		$fields_values       = $this->core->explode_data( $fields_values );
		$header_fields       = array();
		$values_fields       = array();
		$values              = array();
		$header_fields[]     = '`email`';
		$values_fields[]     = '%s';
		$values[]            = strtolower( $email );
		$table_lists         = "{$wpdb->prefix}jackmail_lists";
		$table_list_contacts = "{$wpdb->prefix}jackmail_lists_contacts_{$id_list}";
		if ( $this->core->check_table_exists( $table_list_contacts, false ) ) {
			$sql         = "
			SELECT `fields`
			FROM {$table_lists}
			WHERE `id` = %s";
			$list_fields = $wpdb->get_row( $wpdb->prepare( $sql, $id_list ) );
			if ( isset( $list_fields->fields ) ) {
				$list_fields = $this->core->explode_data( $list_fields->fields );
				if ( is_array( $list_fields ) ) {
					$columns = $this->core->get_table_columns( $table_list_contacts, false );
					foreach ( $fields_values as $field ) {
						if ( isset( $field['field'], $field['value'] ) ) {
							$index = array_search( $field['field'], $list_fields );
							if ( $index !== false ) {
								$i = $index + 1;
								if ( in_array( 'field' . $i, $columns ) ) {
									$header_fields[] = '`field' . $i . '`';
									$values_fields[] = '%s';
									$values[]        = $field['value'];
								}
							}
						}
					}
					$header_fields = implode( ', ', $header_fields );
					$values_fields = implode( ', ', $values_fields );
					$sql           = "INSERT IGNORE INTO `{$table_list_contacts}` ( {$header_fields} ) VALUES ( {$values_fields} )";
					$return        = $wpdb->query( $wpdb->prepare( $sql, $values ) );
					if ( $return > 0 ) {
						$this->send_scenario_welcome_new_list_subscriber( $id_list, $email );
					}
					$update_return = $this->core->updated_list_contact( $id_list );
					if ( $update_return !== false ) {
						return true;
					}
				}
			}
		}
		return false;
	}

}