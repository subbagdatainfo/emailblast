<?php

/**
 * Markup the subscriber profile form.
 */
?>

 <?php if( isset( $update ) ) : ?>
	<?php printf( '<div class="un-notice-update"><p>%s</p></div>', $update ); 
    	 delete_transient( 'un_subscribers_update' );
	?>
 <?php endif; ?>
  
<form method="post" id="un-subscriber-profile" class="un-vertical">
	<div class="un-form-group">
    	<label class="un-label" for="un-name"><?php _e( 'Name', 'ultimate-newsletter' ); ?></label>
    	<div class="un-controls">
    		<input type="text" name="name" id="un-name" class="un-required" value="<?php echo esc_attr( $title ); ?>" />
    	</div>
  	</div>
  
  	<div class="un-form-group">
    	<label class="un-label"><?php _e( 'Email', 'ultimate-newsletter' ); ?></label>
    	<div class="un-controls">
    		<input type="text" value="<?php echo esc_attr( $email ); ?>" disabled="disabled" />
    	</div>
  	</div>
  
  	<div class="un-form-group">
    	<label class="un-label" for="un-email"><?php _e( 'Subscribe to our following lists', 'ultimate-newsletter'); ?></label>
 		<div class="un-controls un-multi-list">
    		<?php
            	foreach( $email_groups_list as $term ) {
                	$selected = in_array( $term->term_id, $email_groups ) ? ' checked' : '';       			                       
                	printf( '<label><input type="checkbox" name="email_groups[]" value="%d"%s/>%s</label><br>', $term->term_id, $selected, $term->name );
            	} 
        	?>
  		</div>
  	</div>
  
  	<div class="un-form-group">
    	<?php if( 'unconfirmed' == $status ) : ?>
        	<label class="un-label">
				<?php _e( 'Your subscription is still pending email verification. Kindly check your inbox to confirm your subscription.', 'ultimate-newsletter' ); ?>
            </label>
		<?php else : ?>
        	<label class="un-label">
				<?php _e( 'Unsubscribe from our newsletters?', 'ultimate-newsletter' ); ?>
            	<input type="checkbox" name="unsubscribe" value="1"<?php checked( $status, 'unsubscribed' ); ?> />
        	</label>
        <?php endif; ?>
  	</div>
  
  	<input type="hidden" name="unt" value= <?php echo $token; ?> >
  	<?php wp_nonce_field( 'un_save_subscriber', 'ultimate_newsletter_nonce' ); ?>
  	<input type="submit" value="<?php _e( 'Save Changes', 'ultimate-newsletter' ) ?>" />
</form>
