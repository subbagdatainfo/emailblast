<?php

/**
 *  Markup the newsletter editor(Blank Template) page of the plugin.
 */ 
?>

<div class="wrap">

	<h2 class="nav-tab-wrapper">
    	<a href="javascript:void(0)" class="nav-tab un-tab-disabled">
			<?php _e( 'Create a New Campaign', 'ultimate-newsletter' ); ?>
        </a>
    	<a href="admin.php?page=ultimate_newsletters&action=edit&tab=templates&id=<?php echo $newsletter_id; ?>" class="nav-tab nav-tab-active">
        	<?php _e( 'Design your NewsLetter', 'ultimate-newsletter' ); ?>
        </a>
    	<a href="admin.php?page=ultimate_newsletters&action=edit&tab=schedule&id=<?php echo $newsletter_id; ?>" class="nav-tab">
        	<?php _e( 'Send', 'ultimate-newsletter' ); ?>
        </a>
	</h2> 

	<p></p>
    
	<form method="post">
		<?php
        	$args = array(
           		'textarea_rows' => '',
                'editor_height' => 250,
             );
			 
        	wp_editor( $post_content, 'post_content', $args );
     	?>
        
        <input type="hidden" name="action" value="save" />
        <input type="hidden" name="tab" value="templates" />
        <input type="hidden" name="id" id="un-post-id" value="<?php echo $newsletter_id; ?>" /> 
        <p class="submit">        	  
        	<input type="submit" name="save" id="save" class="button button-primary" value="<?php _e( 'Save & Continue', 'ultimate-newsletter' ); ?>"> 
            <a href="<?php echo admin_url( 'admin.php?page=ultimate_newsletters&action=edit&tab=templates&id='.$newsletter_id.'&builder=0' ); ?>" class="button" style="float: right;"><?php _e( 'Change Template', 'ultimate-newsletter' ); ?></a>   
        </p> 
	</form>
    
</div>