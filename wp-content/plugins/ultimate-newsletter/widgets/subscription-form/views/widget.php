<?php

/**
 * This template displays the public facing side of the widget.
 */
?>

<form id="<?php echo $widget_id; ?>" class="un-subscription-form un-<?php echo $display; ?>">
	<?php if( $has_name ) : ?>
    	<div class="un-form-group">
        	<?php if( $name_label ) echo '<label class="un-label">'.$name_label.'<span class="un-required-star">*</span></label>'; ?>
            <input type="text" name="name" class="un-name un-required" placeholder="<?php echo $name_placeholder; ?>" />
        </div>
	<?php endif; ?>
        
    <div class="un-form-group">
    	<?php if( $email_label ) echo '<label class="un-label">'.$email_label.'<span class="un-required-star">*</span></label>'; ?>
        <input type="text" name="email" class="un-email un-required" placeholder="<?php echo $email_placeholder; ?>" />
   	</div>
        
    <div class="un-form-group un-response"></div>
        
    <input type="hidden" name="email_groups" class="un-email-groups" value="<?php echo implode( ',', $email_groups ); ?>">
    <input type="submit" class="btn-primary" value="<?php echo $button_label; ?>" data-id="<?php echo $widget_id ?>" />
</form>