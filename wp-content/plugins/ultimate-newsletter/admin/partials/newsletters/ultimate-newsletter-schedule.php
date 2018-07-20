<?php

/**
 *  Markup the newsletter schedule page of the plugin.
 */ 
?>

<div class="wrap">

	<h2 class="nav-tab-wrapper">
    	<a href="javascript:void(0)" class="nav-tab un-tab-disabled">
			<?php _e( 'Create a New Campaign', 'ultimate-newsletter' ); ?>
        </a>
    	<a href="admin.php?page=ultimate_newsletters&action=edit&tab=templates&id=<?php echo $newsletter_id; ?>" class="nav-tab">
        	<?php _e( 'Design your NewsLetter', 'ultimate-newsletter' ); ?>
        </a>
    	<a href="admin.php?page=ultimate_newsletters&action=edit&tab=schedule&id=<?php echo $newsletter_id; ?>" class="nav-tab nav-tab-active">
        	<?php _e( 'Send', 'ultimate-newsletter' ); ?>
        </a>
	</h2> 
    
	<form method="post">
		<table class="form-table">
            <tbody>
                <tr>
                    <th><label for="un-title"><?php _e( 'Subject', 'ultimate-newsletter' ); ?></label></th>
                    <td><input type="text" name="title" id="un-title" value="<?php echo esc_attr( $title ); ?>" style="width:45%"/></td>
                </tr>
                <tr>
                	<th><label><?php _e( 'Email Groups', 'ultimate-newsletter'); ?></label></th>
                    <td>
                        <div class="un-multi-list">
                            <?php 
                                foreach(  $email_groups_list as $term ) {
                                    $selected = in_array( $term->term_id, $email_groups ) ? ' checked' : '';                              
                                    printf( '<label><input type="checkbox" name="email_groups[]" required="required" value="%d"%s/>%s</label><br>', $term->term_id, $selected, $term->name );
                                }
                            ?>
                        </div>
               		</td>
            	</tr>
                 <tr>
                    <th><label for="un-from-name"><?php _e( 'From Name', 'ultimate-newsletter' ); ?></label></th>
                    <td><input type="text" name="from_name" id="un-from-name" value="<?php echo esc_attr( $from_name ); ?>" style="width:45%" /></td>
                </tr>
                 <tr>
                    <th><label for="un-from-email"><?php _e( 'From Email', 'ultimate-newsletter' ); ?></label></th>
                    <td><input type="text" name="from_email" id="un-from-email" value="<?php echo esc_attr( $from_email ); ?>" style="width:45%" /></td>
                </tr>
                <tr>
                    <th><label for="un-reply-to-name"><?php _e( 'Reply-to Name', 'ultimate-newsletter' ); ?></label></th>
                    <td><input type="text" name="reply_to_name" id="un-reply-to-name" value="<?php echo esc_attr( $reply_to_name ); ?>" style="width:45%" /></td>
                </tr>
                 <tr>
                    <th><label for="un-reply-to-email"><?php _e( 'Reply-to Email', 'ultimate-newsletter' ); ?></label></th>
                    <td><input type="text" name="reply_to_email" id="un-reply-to-email" value="<?php echo esc_attr( $reply_to_email ); ?>" style="width:45%" /></td>
                </tr>
                <tr>
                	<th><?php _e( 'Send a Test Preview', 'ultimate-newsletter' ); ?></th>
                    <td>
                    	<input type="text" id="un-test-email" placeholder="<?php echo $to_email; ?>" style="width:45%" />
        				<input type="button" class="button button-secondary" id="un-test-email-btn" value="<?php _e( 'Test Email', 'ultimate-newsletter' ); ?>" />
        				<span id="un-test-email-response"></span>
                    </td>
                </tr>
           	</tbody>
      	</table>
        <input type="hidden" name="action" value="save" />
        <input type="hidden" name="tab" value="schedule">
        <input type="hidden" name="id" id="un-post-id" value="<?php echo $newsletter_id; ?>">
        <input type="hidden" name="status" id="un-status" value="draft" />
        <p class="submit">
        	<input type="button" class="button button-primary" onclick="document.getElementById('un-status').value='draft'; this.form.submit();" value="<?php _e( 'Save as Draft', 'ultimate-newsletter' ); ?>" />
    	<input type="button" class="button button-primary" onclick="document.getElementById('un-status').value='schedule'; this.form.submit();" value="<?php _e( 'Schedule Newsletter', 'ultimate-newsletter' ); ?>" />
        </p>
	</form>
    
</div>
