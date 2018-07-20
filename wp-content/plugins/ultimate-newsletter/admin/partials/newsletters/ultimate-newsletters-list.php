<?php

/**
 *  Markup the newsletters listing page of the plugin.
 */ 
?>

<div class="wrap">
	<h2>
  		<?php _e( 'Newsletters', 'ultimate-newsletter'); ?>
  		<a href="<?php echo admin_url( 'admin.php?page=ultimate_newsletters&action=add' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'ultimate-newsletter' ); ?></a>
        <?php if( ! empty($_POST['s'] ) ) : ?>
        	<span class="subtitle"><?php printf( __( 'Search results for "%s"', 'ultimate-newsletter' ), sanitize_text_field( $_POST['s'] ) ); ?></span>
        <?php endif; ?>
    </h2>
    
	<form method="post" id="ultimate-newsletters-list-form">
		 <?php 
			$ultimate_newsletters_list_table->link_filters();
			$ultimate_newsletters_list_table->search_box( __( 'Search Newsletters', 'ultimate-newsletter' ), 'ultimate-newsletters' );
			$ultimate_newsletters_list_table->display();
        ?>
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
        <input type="hidden" name="orderby" id="un-orderby" value="<?php echo isset( $_POST['orderby'] ) ? esc_attr( $_POST['orderby'] ) : 'post_date' ?>" />
        <input type="hidden" name="order" id="un-order" value="<?php echo isset( $_POST['order'] ) ? esc_attr( $_POST['order'] ) : 'DESC'; ?>" />
   </form>
</div>
