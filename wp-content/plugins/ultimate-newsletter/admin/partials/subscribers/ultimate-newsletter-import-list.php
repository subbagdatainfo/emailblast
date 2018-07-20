<?php

/**
 *  Markup the subscribers import list page of the plugin.
 */ 
?>

<div class="wrap">

	<h1><?php _e( 'Import Subscribers', 'ultimate-newsletter' ); ?></h1>
    
  	<form method="post" id="un-subscribers-import-list-form">    	
        <?php 
			$un_import_list_table = new Ultimate_Newsletter_Import_List_Table();
			$un_import_list_table->prepare_items();
    		$un_import_list_table->display();
        ?>
        <table class="form-table">
        	<tr class="un-import-emailgroups">
         		<th scope="row"><label><?php _e( 'Email Groups', 'ultimate-newsletter' ); ?></label></th>
                <td>
                    <div class="un-multi-list">
                        <?php
                            foreach( $email_groups as $term ) {
								$checked = in_array( $term->term_id, $selected_email_groups ) ? ' checked="checked"' : '';
                                printf( '<label><input type="checkbox" name="email_groups[]" class="un-email-groups" value="%d"%s/>%s</label><br>', $term->term_id, $checked, $term->name );
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
        </table>
        <?php wp_nonce_field( 'un_import_subscribers', 'un_subscriber_nonce' ); ?>
        <input type="hidden" name="csv" value="<?php echo esc_attr( $csv ); ?>" />
        <input type="hidden" name="page" value="un_subscribers" />
        <input type="hidden" name="action" value="import-save-data" />
        <p class="submit">
        	<input type="submit" name="submit" id="un-import-submit" class="button button-primary" value="<?php _e( 'Import Subscribers', 'ultimate-newsletter' ); ?>" />
            <input type="button" onclick="location.href='<?php echo admin_url( 'admin.php?page=un_subscribers' ); ?>'" class="button button-secondary" value="<?php _e( 'Cancel', 'ultimate-newsletter' ); ?>" />
        </p>
  	</form>
    
</div>
