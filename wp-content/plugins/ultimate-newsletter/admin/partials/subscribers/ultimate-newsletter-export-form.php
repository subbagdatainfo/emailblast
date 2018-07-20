<?php

/**
 *  Markup the subscriber export form of the plugin.
 */ 
?>

<div class="wrap">

	<h1><?php _e( 'Export Subscribers', 'ultimate-newsletter' ); ?></h1>
    
  	<form method="post" id="un-subscribers-export-form">
    	<table class="form-table">
      		<tbody>
            	<tr class="un-export-emailgroups">
          			<th scope="row"><label><?php _e( 'Email Groups', 'ultimate-newsletter'); ?></label></th>
          			<td>
                    	<div class="un-multi-list">
              				<?php
                            	foreach( $email_groups as $term ) {
            						printf( '<label><input type="checkbox" name="email_groups[]" class="un-email-groups" value="%d" checked="checked" />%s</label><br>', $term->term_id, $term->name );
        						} 
							?>
            			</div>
                    </td>
        		</tr>
        		<tr>
          			<th scope="row"><label><?php _e( 'Format', 'ultimate-newsletter' ); ?></label></th>
          			<td>
            			<label><input type="radio" checked="checked" /><?php _e( 'CSV file', 'ultimate-newsletter' ); ?></label>
            		</td>
				</tr>
               	<tr>
               		<th scope="row">
                     	<label for="un-export-confirmed-only"><?php _e( 'Export confirmed subscribers only?', 'ultimate-newsletter' ); ?></label>
                     </th>
                     <td>
                     	<input type="checkbox" name="confirmed_only" id="un-export-confirmed-only" checked="checked" />
                     </td>
                </tr>
                <tr class="un-export-fields-group">
					<th scope="row"><label><?php _e( 'List of fields to export', 'ultimate-newsletter' ); ?></label></th>
					<td>
						<label for="un-export-email">
                        	<input type="checkbox" name="include_email" id="un-export-email" class="un-export-fields" value="email" checked="checked" />
							<?php _e( 'Email', 'ultimate-newsletter' ); ?>
                    	</label>
                        
                        <label for="un-export-name">
                        	<input type="checkbox" name="include_name" id="un-export-name" class="un-export-fields" value="name" checked="checked" />
							<?php _e( 'First name', 'ultimate-newsletter' ); ?>
                       	</label>
                        
                        <label for="un-export-date">
                        	<input type="checkbox" name="include_date" id="un-export-date" class="un-export-fields" value="date" checked="checked" />
							<?php _e( 'Subscription date', 'ultimate-newsletter' ); ?>
                      	</label>
                        
                        <label for="un-export-status">
                        	<input type="checkbox" name="include_status" id="un-export-status" class="un-export-fields" value="status" checked="checked" />
							<?php _e( 'Status', 'ultimate-newsletter' ); ?>
                       	</label>			
                  	</td>
		    	</tr>
      		</tbody>
    	</table>    	
        <?php wp_nonce_field( 'un_export_subscribers', 'un_subscriber_nonce' ); ?>
        <input type="hidden" name="page" value="un_subscribers" />
        <input type="hidden" name="action" value="export-data" />
        <p class="submit">
        	<input type="submit" name="submit" id="un-export-submit" class="button button-primary" value="<?php _e( 'Export Subscribers', 'ultimate-newsletter' ); ?>" />
            <input type="button" onclick="location.href='<?php echo admin_url( 'admin.php?page=un_subscribers' ); ?>'" class="button button-secondary" value="<?php _e( 'Cancel', 'ultimate-newsletter' ); ?>" />
        </p>
	</form>
    
</div>
