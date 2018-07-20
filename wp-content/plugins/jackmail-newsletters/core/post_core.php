<?php


class Jackmail_Post_Core extends Jackmail_Campaign_Core {

	protected function jackmail_post_box() {
		add_meta_box( 'meta-box-id', __( 'Jackmail', 'jackmail-newsletters' ), function ( $post ) {
			$current_screen = get_current_screen();
			if ( isset( $current_screen->action, $current_screen->base, $post->ID ) ) {
				if ( $current_screen->action === '' && $current_screen->base === 'post' ) {
					$action  = 'jackmail_create_campaign_with_post';
					$post_id = $post->ID;
					?>
					<input type="button" onclick="jackmail_new_campaign()"
					       id="jackmail_create_campaign_button"
					       value="<?php esc_attr_e( 'Create a campaign', 'jackmail-newsletters' ) ?>"/>
					<script>
						function query_jackmail_new_campaign( url, data, success ) {
							var params = Object.keys( data ).map(
								function( k ) {
									return encodeURIComponent( k ) + '=' + encodeURIComponent( data[ k ] );
								}
							).join( '&' );
							var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject( 'Microsoft.XMLHTTP' );
							xhr.open( 'POST', url );
							xhr.onreadystatechange = function() {
								if ( xhr.readyState > 3 && xhr.status === 200 ) {
									success( xhr.responseText );
								}
							};
							xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
							xhr.send( params );
							return xhr;
						}

						function jackmail_new_campaign() {
							var data = {
								action: '<?php esc_attr_e( $action ) ?>',
								nonce: '<?php esc_attr_e( wp_create_nonce( $action . get_option( 'jackmail_nonce' ) ) ) ?>',
								key: '<?php esc_attr_e( $this->core->get_jackmail_key() ) ?>',
								post_id: '<?php esc_attr_e( $post_id ) ?>'
							};
							document.getElementById( 'jackmail_create_campaign_button' ).disabled = true;
							query_jackmail_new_campaign(
								'<?php esc_attr_e( admin_url( 'admin-ajax.php' ) ) ?>',
								data,
								function( data ) {
									data = JSON.parse( data );
									window.location.href = 'admin.php?page=jackmail_campaign#/campaign/' + data.id + '/contacts';
								}
							);
						}
					</script>
					<?php
				} else {
					echo '<p>' . __( 'Please save your post before your create a campaign.', 'jackmail-newsletters' ) . '</p>';
				}
				$exclude = get_post_meta( $post->ID, 'jackmail_scenario_exclude', true );
				?>
				<p>
					<input type="checkbox"<?php if ( $exclude === '1' ) { ?> checked="checked"<?php } ?> name="jackmail_scenario_exclude" id="jackmail_scenario_exclude" autocomplete="off"/>
					<label for="jackmail_scenario_exclude"><?php _e( 'Exclude from automated newsletter.', 'jackmail-newsletters' ) ?></label>
				</p>
				<?php
			}
		}, 'post' );
	}

	protected function post_jackmail_scenario_exclude( $post_id, $post_type ) {
		if ( $post_type !== 'page' ) {
			if ( isset( $_POST['jackmail_scenario_exclude'] ) ) {
				update_post_meta( $post_id, 'jackmail_scenario_exclude', '1' );
			} else {
				delete_post_meta( $post_id, 'jackmail_scenario_exclude' );
			}
		}
	}

	protected function create_campaign_with_post( $post_id ) {
		$content_email_json = '';
		$json               = $this->get_post_or_page_or_custom_post_full_content( $post_id );
		if ( isset ( $json['title'], $json['description'], $json['link'] ) ) {
			$link_tracking      = get_option( 'jackmail_link_tracking' );
			$content_email_json = $this->core->get_gallery_template_json( '1809', $link_tracking );
			$content_email_json = str_replace( '((TITLE))', $json['title'], $content_email_json );
			$content_email_json = str_replace( '((DESCRIPTION))', $json['description'], $content_email_json );
			$content_email_json = str_replace( '((LINK))', $json['link'], $content_email_json );
		}
		return $this->create_campaign_with_data( $content_email_json );
	}

}