<?php

/**
 *  Markup the newsletter creation page of the plugin.
 */ 
?>

<div class="wrap">

	<h2 class="nav-tab-wrapper">
    	<a href="admin.php?page=ultimate_newsletters&action=add" class="nav-tab nav-tab-active">
			<?php _e( 'Create a New Campaign', 'ultimate-newsletter' ); ?>
        </a>
    	<a href="javacript:void(0)" class="nav-tab un-tab-disabled">
        	<?php _e( 'Design your NewsLetter', 'ultimate-newsletter' ); ?>
        </a>
    	<a href="javacript:void(0)" class="nav-tab un-tab-disabled">
        	<?php _e( 'Send', 'ultimate-newsletter' ); ?>
        </a>        
	</h2> 
    
	<form method="post">
		<table class="form-table">
            <tbody>
                <tr>
                    <th><label for="title"><?php _e( 'Subject', 'ultimate-newsletter' ); ?></label></th>
                    <td><input type="text" name="title" id="title" style="width:45%"/></td>
                </tr>
                <tr>
                	<th> <?php _e( 'Email Groups', 'ultimate-newsletter'); ?></th>
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
          	</tbody>
      	</table>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="tab" value="create">
        <?php submit_button( __( 'Save & Continue', 'ultimate-newsletter' ), 'primary', 'save' );	?>
	</form>
    
</div>