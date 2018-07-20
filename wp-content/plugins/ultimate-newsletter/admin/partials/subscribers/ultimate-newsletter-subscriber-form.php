<?php

/**
 *  Markup the subscriber form of the plugin.
 */ 
?>

<div class="wrap">

	<h1>
		<?php echo ( $subscriber_id > 0 ) ? __( 'Edit Subscriber', 'ultimate-newsletter' ) : __( 'Add New Subscriber', 'ultimate-newsletter' ); ?>
    </h1>
    
  	<form method="post" id="un-subscriber-form">
    	<table class="form-table">
      		<tbody>
        		<tr>
          			<th scope="row"><label for="un-name"><?php _e( 'Name', 'ultimate-newsletter' ); ?></label></th>
          			<td><input type="text" name="name" id="un-name" value="<?php echo esc_attr( $title ); ?>" style="width:45%" /></td>
        		</tr>
        		<tr>
          			<th scope="row"><label for="un-email"><?php _e( 'Email', 'ultimate-newsletter' ); ?></label></th>
          			<td><input type="text" name="email" id="un-email" value="<?php echo esc_attr( $email ); ?>" required="required" style="width:45%" /></td>
        		</tr>
        		<tr>
          			<th scope="row"><label for="un-email-groups"><?php _e( 'Email Groups', 'ultimate-newsletter'); ?></label></th>
          			<td>
                    	<div class="un-multi-list">
              				<?php
                            	foreach( $email_groups_list as $term ) {
            						$selected = in_array( $term->term_id, $email_groups ) ? ' checked' : '';       			                       
            						printf( '<label><input type="checkbox" name="email_groups[]" value="%d"%s/>%s</label><br>', $term->term_id, $selected, $term->name );
        						} 
							?>
            			</div>
                    </td>
        		</tr>
        		<tr>
          			<th scope="row"><label><?php _e( 'Status', 'ultimate-newsletter' ); ?></label></th>
          			<td>
                    	<label for="un-status-subscribed">
              				<input type="radio" name="status" id="un-status-subscribed" value="subscribed" <?php checked( $status, 'subscribed' ); ?>/>
              				<?php _e( 'Subscribed', 'ultimate-newsletter' ) ?>
              			</label>
            			
                        <label for="un-status-unconfirmed">
              				<input type="radio" name="status" id="un-status-unconfirmed" value="unconfirmed" <?php echo checked( $status, 'unconfirmed'); ?>/>
              				<?php _e( 'Unconfirmed', 'ultimate-newsletter' ) ?>
              			</label>
            			
            			<label for="un-status-unsubscribed">
              				<input type="radio" name="status" id="un-status-unsubscribed" value="unsubscribed" <?php checked( $status, 'unsubscribed' ); ?> />
              				<?php _e( 'Unsubscribed', 'ultimate-newsletter' ) ?>
              			</label>
            		</td>
				</tr>
      		</tbody>
    	</table>    	
        <?php wp_nonce_field( 'un_save_subscriber', 'un_subscriber_nonce' ); ?>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="hidden" name="page" value="un_subscribers">
        <input type="hidden" name="action" value="save">
    	<input type="hidden" name="id" value="<?php echo $subscriber_id; ?>">
        <?php
			$button_text = ( $subscriber_id > 0 ) ? __( 'Save Changes', 'ultimate-newsletter' ) : __( 'Add Subscriber', 'ultimate-newsletter' );
    		submit_button( $button_text, 'primary', 'save' );
		?>
	</form>
    
</div>
