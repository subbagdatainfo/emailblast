<?php

/**
 *  Markup the settings page of the plugin.
 */ 
?>
	
<div class="wrap un-settings">
	
    <?php settings_errors(); ?>
	
    <h2 class="nav-tab-wrapper">
    	<a href="admin.php?page=un_settings" class="nav-tab<?php echo $active_tab == 'general' ? ' nav-tab-active' : ''; ?>">
			<?php _e( 'General Settings', 'ultimate-newsletter' ); ?>
        </a>
    	<a href="admin.php?page=un_settings&tab=email" class="nav-tab<?php echo $active_tab == 'email' ? ' nav-tab-active' : ''; ?>">
        	<?php _e( 'How To Send Your Email ?', 'ultimate-newsletter' ); ?>
        </a>
    	<a href="admin.php?page=un_settings&tab=signup" class="nav-tab<?php echo $active_tab == 'signup' ? ' nav-tab-active' : ''; ?>">
        	<?php _e( 'Signup Confirmation', 'ultimate-newsletter' ); ?>
        </a>
	</h2>
    
	<form method="post" action="options.php">
    	<?php
			settings_fields( 'un_'.$active_tab.'_settings' );
			do_settings_sections( 'un_'.$active_tab.'_settings' );
		?>
        		
		<?php if( $active_tab == 'email' ) : ?>
    		<h2><?php _e( 'Test your Email settings', 'ultimate-newsletter' ); ?></h2>
			<table class="form-table">
  				<tbody>
    				<tr>
      					<th scope="row" style="font-weight: normal;">
                        	<?php _e( "This is to test your mail settings. If you don't receive a mail, your settings maybe incorrect. Please also check your SPAM folder.", 'ultimate-newsletter' ); ?>
                        </th>
      					<td>
                        	<?php 
								$general_settings = get_option( 'un_general_settings' );
								$to = ! empty( $general_settings['admin_email'] ) ? $general_settings['admin_email'] : '';
							?>
                        	<input type="text" id="un-settings-test-email" value="<?php echo $to; ?>" />
                            <input type="button" class="button button-secondary" id="un-settings-test-email-btn" value="<?php _e( 'Test email', 'ultimate-newsletter' ); ?>" />
                            <span id="un-settings-test-email-response"></span>
                        </td>
    				</tr>
  				</tbody>
			</table>
		<?php endif; ?>
        
		<?php submit_button(); ?>
    </form>
    
 </div>
