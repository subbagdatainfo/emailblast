<?php if ( defined( 'ABSPATH' ) ) { ?>
<?php
$url                            = $params['url'];
$action_submit                  = $params['action_submit'];
$action_confirm                 = $params['action_confirm'];
$widget_id                      = $params['widget_id'];
$before_widget                  = $params['before_widget'];
$title                          = $params['title'];
$after_widget                   = $params['after_widget'];
$fields                         = $params['fields'];
$id                             = $params['id'];
$list_fields                    = $params['list_fields'];
$double_optin                   = $params['double_optin'];
$double_optin_confirmation_type = $params['double_optin_confirmation_type'];
$double_optin_confirmation_url  = $params['double_optin_confirmation_url'];
$gdpr                           = $params['gdpr'];
$gdpr_content                   = $params['gdpr_content'];
$confirm_id_list                = $params['confirm_data']['id_list'];
$confirm_email                  = $params['confirm_data']['email'];
$confirm_fields                 = $params['confirm_data']['fields'];
echo $before_widget;
echo $title;
?>
<p id="jackmail_widget_confirmation_<?php esc_attr_e( $id ) ?>"></p>
<p>
	<label for="jackmail_widget_email_<?php esc_attr_e( $id ) ?>"><?php _e( 'Email', 'jackmail-newsletters' ) ?></label>
	<input id="jackmail_widget_email_<?php esc_attr_e( $id ) ?>" name="jackmail_widget_email" type="text" autocomplete="off"/>
</p>
<?php
foreach ( $list_fields as $key => $field ) {
	$i = $key + 1;
	if ( in_array( $i, $fields ) ) {
	?>
<p>
	<label for="jackmail_widget_field<?php echo $i ?>_<?php esc_attr_e( $id ) ?>">
		<?php echo htmlentities( ucfirst( mb_strtolower( $field ) ) ) ?>
	</label>
	<input id="jackmail_widget_field<?php echo $i ?>_<?php esc_attr_e( $id ) ?>"
	       name="jackmail_widget_field<?php echo $i ?>"
	       type="text" autocomplete="off"/>
</p>
	<?php
	}
}
?>
<p>
	<input id="jackmail_widget_submit_<?php esc_attr_e( $id ) ?>"
	       onclick="submit_jackmail_widget_form_<?php esc_attr_e( $id ) ?>()"
	       type="button" value="<?php esc_attr_e( 'OK', 'jackmail-newsletters' ) ?>"/>
</p>
<div id="jackmail_widget_container_form_<?php esc_attr_e( $id ) ?>"></div>
<script type="text/javascript">
	function submit_jackmail_widget_form_<?php esc_attr_e( $id ) ?>() {
		var fields = [];
		<?php
		foreach ( $list_fields as $key => $field ) {
			$i = $key + 1;
			if ( in_array( $i, $fields ) ) {
		?>
		fields.push( {
			'field': '<?php esc_attr_e( $field ) ?>',
			'value': document.getElementById( 'jackmail_widget_field<?php echo $i ?>_<?php esc_attr_e( $id ) ?>' ).value
		} );
		<?php
			}
		}
		?>
		var data = {
			action: '<?php esc_attr_e( $action_submit ) ?>',
			nonce: '<?php esc_attr_e( wp_create_nonce( $action_submit . get_option( 'jackmail_front_nonce' ) ) ) ?>',
			jackmail_widget_id: '<?php esc_attr_e( $widget_id ) ?>',
			jackmail_widget_email: document.getElementById( 'jackmail_widget_email_<?php esc_attr_e( $id ) ?>' ).value,
			jackmail_widget_fields: JSON.stringify( fields )
		};
		document.getElementById( 'jackmail_widget_submit_<?php esc_attr_e( $id ) ?>' ).disabled = true;
		query_jackmail_widget_form_<?php esc_attr_e( $id ) ?>(
			'<?php esc_attr_e( $url ) ?>',
			data,
			function( data ) {
				data = JSON.parse( data );
				document.getElementById( 'jackmail_widget_email_<?php esc_attr_e( $id ) ?>' ).value = '';
				<?php
				foreach ( $list_fields as $key => $field ) {
					$i = $key + 1;
					if ( in_array( $i, $fields ) ) {
				?>
				document.getElementById( 'jackmail_widget_field<?php echo $i ?>_<?php esc_attr_e( $id ) ?>' ).value = '';
				<?php
					}
				}
				?>
				document.getElementById( 'jackmail_widget_confirmation_<?php esc_attr_e( $id ) ?>' ).innerHTML = data.message;
				alert( data.message );
				document.getElementById( 'jackmail_widget_submit_<?php esc_attr_e( $id ) ?>' ).disabled = false;
			}
		);
	}
	function query_jackmail_widget_form_<?php esc_attr_e( $id ) ?>( url, data, success ) {
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
	<?php if ( $confirm_id_list !== '' && $confirm_email !== '' && $confirm_fields !== '' ) { ?>
	function confirm_jackmail_widget_form_<?php esc_attr_e( $id ) ?>() {
		var jackmail_widget_fields = <?php echo $confirm_fields ?>;
		var data = {
			action: '<?php esc_attr_e( $action_confirm ) ?>',
			nonce: '<?php esc_attr_e( wp_create_nonce( $action_confirm . get_option( 'jackmail_front_nonce' ) ) ) ?>',
			jackmail_widget_id_list: '<?php esc_attr_e( $confirm_id_list ) ?>',
			jackmail_widget_email: '<?php esc_attr_e( $confirm_email ) ?>',
			jackmail_widget_fields: JSON.stringify( jackmail_widget_fields )
		};
		query_jackmail_widget_form_<?php esc_attr_e( $id ) ?>(
			'<?php esc_attr_e( $url ) ?>',
			data,
			function( data ) {
				data = JSON.parse( data );
				document.getElementById( 'jackmail_widget_confirmation_<?php esc_attr_e( $id ) ?>' ).innerHTML = data.message;
				<?php if ( $double_optin_confirmation_type === 'url' && $double_optin_confirmation_url !== '' ) { ?>
				if ( data.success === false ) {
					alert( data.message );
				} else {
					window.location.href = '<?php esc_attr_e( $double_optin_confirmation_url ) ?>';
				}
				<?php } else { ?>
				alert( data.message );
				<?php } ?>
			}
		);
	}
	setTimeout( function() {
		confirm_jackmail_widget_form_<?php esc_attr_e( $id ) ?>();
	} );
	<?php } ?>
</script>
<?php
echo $after_widget;
?>
<?php } ?>