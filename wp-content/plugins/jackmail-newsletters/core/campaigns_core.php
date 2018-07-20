<?php


class Jackmail_Campaigns_Core {

	protected function get_campaigns( $refresh_status ) {
		global $wpdb;
		$campaigns = array();
		if ( $refresh_status ) {
			$this->core->progress_campaigns();
			$this->core->progress_contacts_blacklist();
		}
		$sql         = "
		SELECT 'campaign' AS `type`, `name`, `id`, `preview`, `status`, `status_detail`, `send_option`,
		`updated_date_gmt`, `updated_by`, `send_option_date_begin_gmt`, IF(`content_email_json` != '', 1, 0) AS `json`
		FROM `{$wpdb->prefix}jackmail_campaigns`
		ORDER BY `updated_date_gmt` DESC";
		$campaigns[] = $wpdb->get_results( $sql );
		$sql         = "
		SELECT 'scenario' AS `type`, `name`, `id`, `preview`, `status`, '' AS `status_detail`, `send_option`,
		`updated_date_gmt`, `updated_by`, '0000-00-00 00:00:00' AS `send_option_date_begin_gmt`, 1 AS `json`
		FROM `{$wpdb->prefix}jackmail_scenarios`
		WHERE `send_option` != 'widget_double_optin'
		ORDER BY `updated_date_gmt` DESC";
		$campaigns[] = $wpdb->get_results( $sql );
		$campaigns   = array_merge( $campaigns[0], $campaigns[1] );
		foreach ( $campaigns as $key => $campaign ) {
			$campaigns[ $key ]->preview    = $this->core->content_email_preview_url( $campaign->preview, $campaign->type );
			$campaigns[ $key ]->updated_by = '';
			$user                          = get_user_by( 'id', $campaign->updated_by );
			if ( isset( $user->first_name, $user->last_name ) ) {
				if ( $user->first_name !== '' || $user->last_name !== '' ) {
					$campaigns[ $key ]->updated_by = $user->first_name . ' ' . $user->last_name;
				}
			}
		}
		usort( $campaigns, function ( $a, $b ) {
			return strcmp( $b->updated_date_gmt, $a->updated_date_gmt );
		} );
		return $campaigns;
	}

	protected function delete_campaign( $id_campaign ) {
		global $wpdb;
		$delete_return = $this->core->delete_campaign( array(
			'id' => $id_campaign
		) );
		if ( $delete_return !== false ) {
			$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}jackmail_campaigns_lists_contacts_{$id_campaign}`" );
			return true;
		}
		return false;
	}

	protected function cancel_scheduled_campaign( $id_campaign ) {
		global $wpdb;
		$send_option_date_begin_gmt = gmdate( 'Y-m-d H:i:s', strtotime( $this->core->get_current_time_gmt_sql() ) + 1800 );
		$sql                        = "
		SELECT `send_id`
		FROM `{$wpdb->prefix}jackmail_campaigns`
		WHERE `id` = %s
		AND ( `status` = 'SCHEDULED' OR `status` = 'PROCESS_SCHEDULED' )
		AND `send_option_date_begin_gmt` > %s";
		$campaign                   = $wpdb->get_row( $wpdb->prepare( $sql, $id_campaign, $send_option_date_begin_gmt ) );
		if ( isset( $campaign->send_id ) ) {
			$send_id  = $campaign->send_id;
			$url      = $this->core->get_jackmail_url_api() . 'v2/sends/' . urlencode( $send_id ) . '/cancel';
			$headers  = array(
				'content-type' => 'application/json',
				'token'        => $this->core->get_account_token(),
				'x-auth-token' => $this->core->get_account_token(),
				'accountId'    => $this->core->get_account_id(),
				'userId'       => $this->core->get_user_id()
			);
			$body     = array();
			$timeout  = 30;
			$response = $this->core->remote_post_retry( $url, $headers, $body, $timeout );
			if ( is_array( $response ) ) {
				if ( isset( $response['response'] ) ) {
					if ( isset( $response['response']['code'] ) ) {
						$this->core->progress_campaigns();
						if ( $response['response']['code'] === 202 ) {
							$sql             = "SELECT `status` FROM `{$wpdb->prefix}jackmail_campaigns` WHERE `id` = %s";
							$campaign_status = $wpdb->get_row( $wpdb->prepare( $sql, $id_campaign ) );
							if ( isset( $campaign_status->status ) ) {
								if ( $campaign_status->status === 'USER_ABORTED' ) {
									$update_return = $this->core->status_to_draft_campaign( $id_campaign, $campaign_status->status );
									if ( $update_return !== false ) {
										return true;
									}
								}
							}
						}
					}
				}
			}
		}
		return false;
	}

	protected function duplicate_campaign( $id_campaign ) {
		global $wpdb;
		$sql      = "
		SELECT *
		FROM `{$wpdb->prefix}jackmail_campaigns`
		WHERE `id` = %s";
		$campaign = $wpdb->get_row( $wpdb->prepare( $sql, $id_campaign ) );
		if ( isset( $campaign->id ) ) {
			$current_date_gmt = $this->core->get_current_time_gmt_sql();
			$preview          = $this->core->generate_jackmail_preview_filename();
			if ( $this->core->duplicate_preview( $campaign->preview, $preview ) ) {
				$id_campaign = $this->core->insert_campaign( array(
					'id_lists'             => '[]',
					'fields'               => '[]',
					'name'                 => $campaign->name,
					'object'               => $campaign->object,
					'sender_name'          => $campaign->sender_name,
					'sender_email'         => $campaign->sender_email,
					'reply_to_name'        => $campaign->reply_to_name,
					'reply_to_email'       => $campaign->reply_to_email,
					'link_tracking'        => $campaign->link_tracking,
					'content_email_json'   => $campaign->content_email_json,
					'content_email_html'   => $campaign->content_email_html,
					'content_email_txt'    => $campaign->content_email_txt,
					'content_email_images' => $campaign->content_email_images,
					'preview'              => $preview,
					'created_date_gmt'     => $current_date_gmt,
					'updated_date_gmt'     => $current_date_gmt,
					'updated_by'           => get_current_user_id(),
					'status'               => 'DRAFT',
					'send_option'          => 'NOW'
				) );
				if ( $id_campaign !== false ) {
					if ( $this->core->create_campaign_list_table( $id_campaign ) !== false ) {
						return $id_campaign;
					}
				}
			}
		}
		return false;
	}

	protected function delete_scenario( $id_campaign ) {
		$delete_return = $this->core->delete_scenario( array(
			'id' => $id_campaign
		) );
		if ( $delete_return !== false ) {
			$delete_return = $this->core->delete_scenario_event( array(
				'id' => $id_campaign
			) );
			if ( $delete_return !== false ) {
				return true;
			}
		}
		return false;
	}

	protected function cron_progress_campaigns() {
		$this->core->get_jackmail_update_available();
		$this->core->progress_campaigns();
	}

}