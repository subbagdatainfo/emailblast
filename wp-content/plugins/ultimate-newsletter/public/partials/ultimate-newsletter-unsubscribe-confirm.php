<?php

/**
 * Markup the unsubscribe confirmation form.
 */
?>

<form method="post" id="un-unsubscribe-confirm">
  <p><?php _e( "We are sorry to see you go. Are you sure want to unsubscribe from our newsletters?", 'ultimate-newsletter' ); ?></p>
  
  <input type="hidden" name="unt" value= <?php echo $token; ?> >
  <?php wp_nonce_field( 'un_confirmed_unsubscribe', 'ultimate_newsletter_nonce' ); ?>
  <input type="submit" value="<?php _e( 'Confirm', 'ultimate-newsletter' ) ?>" />
</form>
