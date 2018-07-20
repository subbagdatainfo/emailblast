<?php if ( defined( 'ABSPATH' ) ) { ?>
<?php
$title                          = $params['title'];
$fields                         = $params['fields'];
$double_optin                   = $params['double_optin'];
$double_optin_confirmation_type = $params['double_optin_confirmation_type'];
$double_optin_confirmation_url  = $params['double_optin_confirmation_url'];
$gdpr                           = $params['gdpr'];
$gdpr_content                   = $params['gdpr_content'];
$lists                          = $params['lists'];
$lists_details                  = $params['lists_details'];
$js_lists_details               = $params['js_lists_details'];
$id                             = $params['id'];
$id_list                        = $params['id_list'];
$double_optin_scenario_link     = $params['double_optin_scenario_link'];
$emailbuilder_installed         = $params['emailbuilder_installed'];
?>
<div id="jackmail_widget_content_<?php esc_attr_e( $id ) ?>">
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ) ?>_<?php esc_attr_e( $id ) ?>">
			<?php _e( 'Title:', 'jackmail-newsletters' ) ?>
		</label>
		<input autocomplete="off" id="<?php echo $this->get_field_id( 'title' ) ?>_<?php esc_attr_e( $id ) ?>"
		       class="widefat" name="<?php echo $this->get_field_name( 'title' ) ?>"
		       type="text" value="<?php esc_attr_e( $title ) ?>"/>
	</p>
	<p>
		<label for="jackmail_widget_list_<?php echo $id ?>"><?php _e( 'List:', 'jackmail-newsletters' ) ?></label>
		<select autocomplete="off" class="widefat" id="jackmail_widget_list_<?php echo $id ?>" name="<?php echo $this->get_field_name( 'id_list' ) ?>"
		        onkeyup="jackmail_widget_select_list( '<?php esc_attr_e( $id ) ?>', false, '<?php esc_attr_e( $fields ) ?>', jackmail_lists_details )"
		        onchange="jackmail_widget_select_list( '<?php esc_attr_e( $id ) ?>', false, '<?php esc_attr_e( $fields ) ?>', jackmail_lists_details )">
			<option value=""><?php _e( 'Select a list', 'jackmail-newsletters' ) ?></option>
			<?php
			foreach ( $lists as $list ) { ?>
				<option value="<?php esc_attr_e( $list->id ) ?>"><?php echo htmlentities( $list->name ) ?></option>
			<?php } ?>
		</select>
	</p>
	<p id="jackmail_widget_fields_<?php echo $id ?>_container">
		<input autocomplete="off" type="hidden" id="jackmail_widget_fields_<?php esc_attr_e( $id ) ?>"
		       name="<?php echo $this->get_field_name( 'fields' ) ?>"/>
		<label><?php _e( 'Fields:', 'jackmail-newsletters' ) ?></label>
		<br/>
		<?php
		foreach ( $lists_details as $list_detail ) {
		?>
		<span id="jackmail_widget_field_<?php esc_attr_e( $list_detail['id'] ) ?>_<?php esc_attr_e( $id ) ?>_container">
			<?php
			$all_fields = $list_detail['all_fields'];
			foreach ( $all_fields as $key => $field ) {
				$id_html = $list_detail['id'] . '_' . ( $key + 1 ) . '_' . $id;
			?>
			<span id="jackmail_widget_field_<?php esc_attr_e( $id_html ) ?>_container">
				<input autocomplete="off" type="checkbox" id="jackmail_widget_field_<?php esc_attr_e( $id_html ) ?>_checkbox"
				       onchange="jackmail_widget_select_list_field( '<?php esc_attr_e( $id ) ?>', <?php echo( $key + 1 ) ?>, jackmail_lists_details )"/>
				<label id="jackmail_widget_field_<?php esc_attr_e( $id_html ) ?>" for="jackmail_widget_field_<?php esc_attr_e( $id_html ) ?>_checkbox">
					<?php echo htmlentities( ucfirst( mb_strtolower( $field ) ) ) ?>
				</label>
			</span>
			<br/>
			<?php } ?>
		</span>
		<?php } ?>
	</p>
	<p>
		<?php if ( $emailbuilder_installed ) { ?>
		<span style="margin-bottom:5px;display:block">
			<input type="checkbox" autocomplete="off"
			       onclick="jackmail_widget_select_double_optin( '<?php esc_attr_e( $id ) ?>' )"
			       id="jackmail_double_optin_<?php esc_attr_e( $id ) ?>"
			       name="<?php echo $this->get_field_name( 'double_optin' ) ?>"/>
			<label for="jackmail_double_optin_<?php esc_attr_e( $id ) ?>">
				<?php _e( 'Double optin', 'jackmail-newsletters' ) ?>
			</label>
		</span>
		<span id="jackmail_double_optin_configuration_block_<?php esc_attr_e( $id ) ?>">
			<span style="margin-bottom:5px;display:block">
				<a href="<?php echo $double_optin_scenario_link ?>">
					<?php _e( 'Edit email content', 'jackmail-newsletters' ) ?>
				</a>
			</span>
			<select style="margin-bottom:5px;display:block"
			        autocomplete="off" class="widefat" id="jackmail_widget_confirmation_type_<?php esc_attr_e( $id ) ?>"
			        name="<?php echo $this->get_field_name( 'double_optin_confirmation_type' ) ?>"
			        onkeyup="jackmail_widget_select_confirmation_type( '<?php esc_attr_e( $id ) ?>' )"
			        onchange="jackmail_widget_select_confirmation_type( '<?php esc_attr_e( $id ) ?>' )">
				<option value="default"><?php _e( 'Default confirmation message', 'jackmail-newsletters' ) ?></option>
				<option value="url"><?php _e( 'Customized Url', 'jackmail-newsletters' ) ?></option>
			</select>
			<input style="margin-bottom:5px;display:block"
			       autocomplete="off" id="jackmail_double_optin_confirmation_url_<?php esc_attr_e( $id ) ?>"
			       class="widefat" name="<?php echo $this->get_field_name( 'double_optin_confirmation_url' ) ?>"
			       placeholder="Url"
			       type="text" value="<?php esc_attr_e( $double_optin_confirmation_url ) ?>"/>
		</span>
		<?php } else { ?>
		<span><?php _e( 'Double optin:', 'jackmail-newsletters' ) ?></span>
		<br/>
		<span>
			<?php _e( 'The double optin feature requires Emailbuilder installation in', 'jackmail-newsletters' ) ?>
			<a href="admin.php?page=jackmail_settings#/settings">"<?php _e( 'Settings', 'jackmail-newsletters' ) ?>"</a>.
		</span>
		<?php } ?>
	</p>
	<script>
		var jackmail_lists_details = <?php echo $js_lists_details ?>;
		jackmail_widget_set_data(
			'<?php esc_attr_e( $id ) ?>',
			'<?php esc_attr_e( $fields ) ?>',
			'<?php esc_attr_e( $id_list ) ?>',
			jackmail_lists_details,
			'<?php esc_attr_e( $double_optin ) ?>',
			'<?php esc_attr_e( $double_optin_confirmation_type ) ?>',
			'<?php esc_attr_e( $gdpr ) ?>'
		);
	</script>
</div>
<?php } ?>