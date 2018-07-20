<?php

/**
 *  Markup the subscriber import form of the plugin.
 */ 
?>

<div class="wrap">

	<h1><?php _e( 'Import Subscribers', 'ultimate-newsletter' ); ?></h1>
    
  	<form method="post" id="un-subscribers-import-form">
    	<table class="form-table">
      		<tbody>
        		<tr>
          			<th scope="row"><label><?php _e( 'How do you want to import?', 'ultimate-newsletter' ); ?></label></th>
          			<td>
            			<label for="un-import-type-editor">
							<input type="radio" name="type" id="un-import-type-editor" value="editor" checked="checked" />
                            <?php _e( 'Copy paste in a text box', 'ultimate-newsletter' ); ?> 
                        </label>
                        
				    	<label for="un-import-type-csv">
							<input type="radio" name="type" id="un-import-type-csv" value="csv" />
                            <?php _e( 'Upload a CSV file', 'ultimate-newsletter' ); ?> 
                        </label>
            		</td>
				</tr>
               	<tr id="un-import-row-editor">
                	 <th scope="row">
                     	<label><?php _e( 'Then paste your list here', 'ultimate-newsletter' ); ?></label>
		    			<p class="description">
                            &middot; <?php _e( 'This needs to be in CSV style or a simple paste from Gmail, Hotmail or Yahoo.', 'ultimate-newsletter' ); ?>
                        </p>
                        <p class="description"> 
                         	&middot; <?php _e( 'Each item in one line.', 'ultimate-newsletter' ); ?>
                        </p>
                     </th>
                     <td>
                     	<textarea name="editor" id="un-import-editor" style="width: 70%; height: 300px;" placeholder="<?php _e( 'Email address, First name', 'ultimate-newsletter' ); ?>"></textarea>
                     </td>
                </tr>
                <tr id="un-import-row-csv" style="display: none;">
                	<th scope="row">
                    	<label><?php _e( 'Upload a file', 'ultimate-newsletter' ); ?></label>
                        <p class="description">
                            <?php _e( 'This needs to be in CSV style.', 'ultimate-newsletter' ); ?>
                        </p>
                    </th>
                    <td>
                    	<input type="text" name="csv" id="un-import-csv" style="width: 50%;" />                        
                    	<a href="javascript:;" id="un-csv-upload" class="button-secondary"><?php _e( 'Upload CSV', 'ultimate-newsletter' ); ?></a>
                        <p id="un-csv-upload-error" style="display: none;"><?php _e( 'Invalid CSV file', 'ultimate-newsletter'  ); ?></p>
                	</td>
                </tr>
                <tr class="un-import-emailgroups">
                    <th scope="row"><label><?php _e( 'Email Groups', 'ultimate-newsletter'); ?></label></th>
                    <td>
                        <div class="un-multi-list">
                            <?php
                                foreach( $email_groups as $term ) {
                                    printf( '<label><input type="checkbox" name="email_groups[]" class="un-email-groups" value="%d"/>%s</label><br>', $term->term_id, $term->name );
                                } 
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
          			<th scope="row"><label><?php _e( 'Status', 'ultimate-newsletter' ); ?></label></th>
          			<td>
                    	<label for="un-status-subscribed">
              				<input type="radio" name="status" id="un-status-subscribed" value="subscribed" checked="checked" />
              				<?php _e( 'Subscribed', 'ultimate-newsletter' ) ?>
              			</label>
            			
                        <label for="un-status-unconfirmed">
              				<input type="radio" name="status" id="un-status-unconfirmed" value="unconfirmed" />
              				<?php _e( 'Unconfirmed', 'ultimate-newsletter' ) ?>
              			</label>
            			
            			<label for="un-status-unsubscribed">
              				<input type="radio" name="status" id="un-status-unsubscribed" value="unsubscribed" />
              				<?php _e( 'Unsubscribed', 'ultimate-newsletter' ) ?>
              			</label>
            		</td>
				</tr>
      		</tbody>
    	</table>    	
        <?php wp_nonce_field( 'un_import_subscribers', 'un_subscriber_nonce' ); ?>
        <input type="hidden" name="page" value="un_subscribers" />
        <input type="hidden" name="action" id="un-import-action" value="import-save-data" />
        <p class="submit">
        	<input type="submit" name="submit" id="un-import-submit" class="button button-primary" value="<?php _e( 'Import Subscribers', 'ultimate-newsletter' ); ?>" />
            <input type="button" onclick="location.href='<?php echo admin_url( 'admin.php?page=un_subscribers' ); ?>'" class="button button-secondary" value="<?php _e( 'Cancel', 'ultimate-newsletter' ); ?>" />
        </p>
	</form>
    
</div>