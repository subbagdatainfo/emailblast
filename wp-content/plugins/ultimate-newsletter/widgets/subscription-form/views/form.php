<?php

/**
 * This template displays the administration form of the widget.
 */ 
?>

<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'ultimate-newsletter' ); ?>:</label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description', 'ultimate-newsletter' ); ?>:</label> 
	<textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_attr( $instance['description'] ); ?></textarea>
</p> 

<p>
    <label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e( 'Display', 'ultimate-newsletter' ); ?>:</label> 
    <select class="widefat" name="<?php echo $this->get_field_name( 'display' ); ?>" id="<?php echo $this->get_field_id( 'display' ); ?>">
       	<option value="horizontal" <?php selected( $instance['display'], 'horizontal' ); ?> ><?php _e( 'Horizontal', 'ultimate-newsletter' ); ?></option>
       	<option value="vertical" <?php selected( $instance['display'], 'vertical' ); ?>><?php _e( 'Vertical', 'ultimate-newsletter' ); ?></option>
 	</select>
</p>

<div class="un-widget-admin-field-group">
	<p><strong><?php _e( 'Name Field', 'ultimate-newsletter' ); ?></strong></p>
    
	<p>
    	<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'has_name' ); ?>" name="<?php echo $this->get_field_name( 'has_name' ); ?>" value="1" <?php checked( $instance['has_name'], 1 ); ?> >
    	<label for="<?php echo $this->get_field_id( 'has_name' ); ?>"><?php _e( 'Collect Name', 'ultimate-newsletter' ); ?></label> 
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'name_label' ); ?>"><?php _e( 'Label', 'ultimate-newsletter' ); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'name_label' ); ?>" name="<?php echo $this->get_field_name( 'name_label' ); ?>" type="text" value="<?php echo esc_attr( $instance['name_label'] ); ?>">
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'name_placeholder' ); ?>"><?php _e( 'Placeholder Text', 'ultimate-newsletter' ); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'name_placeholder' ); ?>" name="<?php echo $this->get_field_name( 'name_placeholder' ); ?>" type="text" value="<?php echo esc_attr( $instance['name_placeholder'] ); ?>">
	</p>
</div>

<div class="un-widget-admin-field-group">
	<p><strong><?php _e( 'Email Field', 'ultimate-newsletter' ); ?></strong></p>
    
	<p>
		<label for="<?php echo $this->get_field_id( 'email_label' ); ?>"><?php _e( 'Label', 'ultimate-newsletter' ); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'email_label' ); ?>" name="<?php echo $this->get_field_name( 'email_label' ); ?>" type="text" value="<?php echo esc_attr( $instance['email_label'] ); ?>">
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'email_placeholder' ); ?>"><?php _e( 'Placeholder Text', 'ultimate-newsletter' ); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'email_placeholder' ); ?>" name="<?php echo $this->get_field_name( 'email_placeholder' ); ?>" type="text" value="<?php echo esc_attr( $instance['email_placeholder'] ); ?>">
	</p>
</div>

<div class="un-widget-admin-field-group">	
	<p><strong><?php _e( 'Subscribe Button', 'ultimate-newsletter' ); ?></strong></p>
    
	<p>
		<label for="<?php echo $this->get_field_id( 'button_label' ); ?>"><?php _e( 'Label', 'ultimate-newsletter' ); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'button_label' ); ?>" name="<?php echo $this->get_field_name( 'button_label' ); ?>" type="text" value="<?php echo esc_attr( $instance['button_label'] ); ?>">
	</p>   
</div>    

<p>
   <label for="<?php echo $this->get_field_id( 'email_groups' ); ?>"><?php _e( 'Email Groups', 'ultimate-newsletter' ); ?>:</label>
   <div class="un-multi-list">
	<?php 
		$terms = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
		
		foreach ( $terms as $term ) { 
    		$selected = in_array( $term->term_id, $instance['email_groups'] ) ? ' checked' : '';                            
    		printf( '<label><input type="checkbox" name="%s[]" value="%d"%s/>%s</label><br>', $this->get_field_name( 'email_groups' ), $term->term_id, $selected, $term->name );	
    	} 
	?>
	</div>
</p>
      
      