<?php

/**
 *  Markup the newsletter templates page of the plugin.
 */ 
?>

<div class="wrap">

	<!-- Tabs -->
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
    
    <!-- Templates -->
    <form method="post">
     	<input type="hidden" name="action" value="save" />
     	<input type="hidden" name="tab" value="templates">
     	<input type="hidden" name="id" value="<?php echo $newsletter_id; ?>">
     	<input type="hidden" name="template" id="un-template" value="blank" />
    	<div class="un-templates">
    		<div class="un-templates-row">
            
        		<?php foreach( $templates as $index => $template ) : ?>
            
                	<?php if( 'new' == $template['title'] ) { ?>
						<div class="un-template-item un-add-new-template" onclick="alert('<?php _e( 'Coming soon', 'ultimate-newsletter' ); ?>');">
            				<div class="un-template-thumbnail">
								<div class="un-dashicons"></div>
             				</div> 
            			</div>
                	<?php } else { ?>
        				<div class="un-template-item<?php if( $template['name'] == $active_template ) echo ' active'; ?>">
            				<div class="un-template-thumbnail">
                        		<img src="<?php echo $template['image']; ?>" />
                				<div class="un-template-caption">
                            		<?php if( $template['name'] == $active_template ) { ?>
                                		<div class="un-template-name un-pull-left"><strong><?php _e( 'Active', 'ultimate-newsletter' ); ?></strong>: <?php echo $template['title']; ?></div>
                                    	<a type="button" class="button button-primary un-pull-right" href="admin.php?page=ultimate_newsletters&action=edit&tab=templates&id=<?php echo $newsletter_id; ?>"><?php _e( 'Customize', 'ultimate-newsletter' ); ?></a>
                                	<?php } else { ?>
                                		<div class="un-template-name un-pull-left"><?php echo $template['title']; ?></div>
                                		<button type="button" class="button un-pull-right" onclick="document.getElementById('un-template').value='<?php echo $template['name']; ?>'; this.form.submit();"><?php _e( 'Activate', 'ultimate-newsletter' ); ?></button>
                                	<?php } ?>
                    				<div class="un-clearfix"></div>
                				</div>
                			</div>
            			</div>
        			<?php } ?>
             
            	<?php endforeach; ?>
                
                <div class="un-clearfix"></div>
    		</div>
    	</div>
    </form>   
    
</div>
