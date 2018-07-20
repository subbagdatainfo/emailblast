<?php

/**
 *  Markup the subscribers listing page of the plugin.
 */ 
?>

<div class="wrap">
    <h2>
		<?php _e( 'Subscribers', 'ultimate-newsletter' ); ?>
      	<a href="<?php echo admin_url( 'admin.php?page=un_subscribers&action=add' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'ultimate-newsletter' ); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=un_subscribers&action=import' ); ?>" class="add-new-h2"><?php _e( 'Import Subscribers', 'ultimate-newsletter' ); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=un_subscribers&action=export' ); ?>" class="add-new-h2"><?php _e( 'Export Subscribers', 'ultimate-newsletter' ); ?></a>
        <?php if( ! empty( $_POST['s'] ) ) : ?>
        	<span class="subtitle"><?php printf( __( 'Search results for "%s"', 'ultimate-newsletter' ), sanitize_text_field( $_POST['s'] ) ); ?></span>
        <?php endif; ?>
    </h2>
  
  	<form method="post" id="un-subscribers-list-form">    	
        <?php 
			$un_subscribers_list_table->link_filters();
			$un_subscribers_list_table->search_box( __( 'Search Subscribers', 'ultimate-newsletter' ), 'un-subscribers' );
    		$un_subscribers_list_table->display();
        ?>
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
        <input type="hidden" name="orderby" id="un-orderby" value="<?php echo isset( $_POST['orderby'] ) ? esc_attr( $_POST['orderby'] ) : 'date' ?>" />
        <input type="hidden" name="order" id="un-order" value="<?php echo isset( $_POST['order'] ) ? esc_attr( $_POST['order'] ) : 'DESC'; ?>" />
  	</form>
</div>
