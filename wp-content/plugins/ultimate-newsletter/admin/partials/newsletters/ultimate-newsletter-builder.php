<?php

/**
 *  Markup the newsletter builder(Drag & Drop) page of the plugin.
 */ 
?>

<div class="wrap">

  	<h2 class="nav-tab-wrapper">
    	<a href="javascript:void(0)" class="nav-tab un-tab-disabled"><?php _e( 'Create a New Campaign', 'ultimate-newsletter' ); ?></a>
        <a href="admin.php?page=ultimate_newsletters&action=edit&tab=templates&id=<?php echo $newsletter_id; ?>" class="nav-tab nav-tab-active"><?php _e( 'Design your NewsLetter', 'ultimate-newsletter' ); ?></a>
        <a href="admin.php?page=ultimate_newsletters&action=edit&tab=schedule&id=<?php echo $newsletter_id; ?>" class="nav-tab"><?php _e( 'Send', 'ultimate-newsletter' ); ?></a>
    </h2>
  	
    <div id="un-builder">
    	<!-- Left col -->
    	<div id="unb-template" class="unb-left-col un-pull-left"> <?php if( "" != $post_content ) echo $post_content; ?> </div>
    	
        <!-- Right col -->
    	<div id="unb-options" class="unb-right-col un-pull-right">
      		<!-- Tabs -->
      		<div class="nav-tab-wrapper">
            	<a href="#unb-options-tab-content" class="nav-tab nav-tab-active unb-nav-tab"><?php _e( 'Content', 'ultimate-newsletter' ); ?></a>
                <a href="#unb-options-tab-design" class="nav-tab unb-nav-tab"><?php _e( 'Design', 'ultimate-newsletter' ); ?></a>
                <a href="admin.php?page=ultimate_newsletters&action=edit&tab=templates&id=<?php echo $newsletter_id; ?>&builder=0" class="nav-tab"><?php _e( 'Change Template', 'ultimate-newsletter' ); ?></a>
            </div>
            
      		<!-- Content Tab -->
      		<div id="unb-options-tab-content" class="unb-options-tab-content">
        		<!-- Options -->
        		<div id="unb-cpanel">
          			<div class="unb-cpanel-icon" id="unb-text">
                    	<a href="javascript:void(0)"> <i class="fa fa-font"></i> <span><?php _e( 'TEXT', 'ultimate-newsletter' ); ?></span> </a>
                     </div>
          			<div class="unb-cpanel-icon" id="unb-image">
                    	<a href="javascript:void(0)"> <i class="fa fa-image"></i> <span> <?php _e( 'IMAGE', 'ultimate-newsletter' ); ?></span> </a> 
            		</div>
          			<div class="unb-cpanel-icon" id="unb-button">
                    	<a href="javascript:void(0)"> <i class="fa fa-minus-square-o"></i> <span><?php _e( 'BUTTON', 'ultimate-newsletter' ); ?></span> </a>
                    </div>
          			<div class="unb-cpanel-icon" id="unb-divider">
                    	<a href="javascript:void(0)"> <i class="fa fa-minus"></i> <span><?php _e( 'DIVIDER', 'ultimate-newsletter' ); ?></span> </a>
                    </div>
          			<div class="unb-cpanel-icon" id="unb-follow">
                    	<a href="javascript:void(0)"> <i class="fa fa-users"></i> <span><?php _e( 'SOCIAL', 'ultimate-newsletter' ); ?></span> </a>
                    </div>
          			<div class="un-clearfix"></div>
        		</div>
        		
                <!-- Forms -->
        		<div id="unb-options-content">
          			<!-- Text -->
          			<div id="unb-options-text" class="unb-options-group">
            			<h2><?php _e( 'Text Editor', 'ultimate-newsletter' ); ?></h2>
            			<hr />
            			<?php
                       		$args = array(
                           		'textarea_rows' => 20,
                           		'editor_height' => 100,
								'media_buttons' => false,
								'quicktags'     => false,
								'tinymce'       => array(
									'theme_advanced_disable' => 'fullscreen',
									'toolbar1'               => 'bold, italic, underline, bullist, numlist, alignleft, alignright, aligncenter, alignjustify, link, unlink, forecolor, backcolor, undo, redo, fontselect, fontsizeselect, formatselect',
								)
                         	);
								
                         	wp_editor( '', 'unb-options-text-editor', $args );
                    	?>
          			</div>
          			
                    <!-- Image -->
          			<div id="unb-options-image" class="unb-options-group">
            			<h2><?php _e( 'Image', 'ultimate-newsletter' ); ?></h2>
            			<hr />
            			<p class="hide-if-no-js">
                    		<a href="javascript:;" id="unb-options-image-upload" class="unb-options-image-trigger button-primary">
								<?php _e( 'Upload Image', 'ultimate-newsletter' ); ?>
              				</a>
                    	</p>
                    
            			<p>
              				<label for="unb-options-image-url"><?php _e( 'URL', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-image-url" class="unb-options-image-trigger un-input-large" />
            			</p>
            		
                    	<p>
              				<label for="unb-options-image-alt"><?php _e( 'Alternate Text', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-image-alt" class="unb-options-image-trigger un-input-large" />
            			</p>
                    
            			<p>
                  			<label for="unb-options-image-position"><?php _e( 'Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-image-position" class="unb-options-image-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            		
                    	<p>
              				<label for="unb-options-image-link"><?php _e( 'Link Image to a webpage ( optional )', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-image-link" class="unb-options-image-trigger un-input-large" placeholder="http://mysite.com/" />
            			</p>
                    
            			<p>
              				<label for="unb-options-image-margin">
              					<input type="checkbox" role="checkbox"  id="unb-options-image-margin" class="unb-options-image-trigger un-input-large" value="true" />
              					<?php _e( 'Margins', 'ultimate-newsletter' ); ?>(<?php _e( 'Edge To Edge', 'ultimate-newsletter' ); ?>)
                        	</label>
            			</p>
          			</div>
                    
          			<!-- Button -->
          			<div id="unb-options-button" class="unb-options-group">
            			<h2><?php _e( 'Button', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
                        <p>
              				<label for="unb-button-text"><?php _e( 'Button Text', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-button-text" class="unb-options-button-trigger un-input-large" />
            			</p>
            			
                        <p>
              				<label for="unb-options-button-link"><?php _e( 'Link this button to', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-button-link" class="unb-options-button-trigger un-input-large"  placeholder="http://mysite.com/" />
            			</p>
            			
                        <h2><?php _e( 'Button Style', 'ultimate-newsletter' ); ?></h2>
                        <hr />
            			
                        <p>
              				<label><?php _e( 'Border', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-button-bdrstyle" class="unb-options-button-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-button-bdr" class="unb-options-button-trigger un-input-mini" placeholder="1px" />
              				<input type="text" id="unb-options-button-bdrcolor" class="unb-options-button-trigger un-color-field" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Border radius', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-button-radius" class="unb-options-button-trigger un-input-large" placeholder="0" />
            			</p>
            			
                        <p>
              				<label for="unb-options-button-bgcolor"><?php _e( 'Background Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-button-bgcolor" class="unb-options-button-trigger un-color-field" />
            			</p>
                        
            			<h2><?php _e( 'Text Style', 'ultimate-newsletter' ); ?></h2>
                        <hr />
            			
                        <p>
              				<label for="unb-options-button-txtcolor"><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" value="#bada55" id="unb-options-button-txtcolor" class="unb-options-button-trigger un-color-field" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-button-fontfamily" class="unb-options-button-trigger un-input-large">
                				<option value="Arial"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-button-fontsize" class="unb-options-button-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-button-fontweight" class="unb-options-button-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Letter Spacing', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-button-letterspacing" class="unb-options-button-trigger un-input-large">
                				<option value="0"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<?php for( $i = -5; $i <= 5; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Padding', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-button-pad" class="unb-options-button-trigger un-input-large" placeholder="0px" />
            			</p>
                        
            			<h2><?php _e( 'Settings', 'ultimate-newsletter' ); ?></h2>
                        <hr />
            			
                        <p>
              				<label for="unb-options-button-position"><?php _e( 'Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-button-position" class="unb-options-button-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label for="unb-options-button-pos-size"><?php _e( 'Width', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-button-pos-size" class="unb-options-button-trigger un-input-large">
                				<option value="fit" selected="selected"><?php _e( 'Fit size', 'ultimate-newsletter' ); ?></option>
                				<option value="full"><?php _e( 'Full width', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
          			</div>
                    
          			<!-- Divider -->
          			<div id="unb-options-divider" class="unb-options-group">
            			<h2><?php _e( 'Divider', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
                        <p>
              				<label><?php _e( 'Padding Top', 'ultimate-newsletter' ); ?></label>
              				<input type="text"  id="unb-options-divider-padtop" class="unb-options-divider-trigger un-input-large" placeholder="1px" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Padding Bottom', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-divider-padbottom" class="unb-options-divider-trigger un-input-large" placeholder="1px" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Border Top', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-divider-bdrtopstyle" class="unb-options-divider-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-divider-bdrtopwid" class="unb-options-divider-trigger un-input-mini" placeholder="1px" />
              				<input type="text" value="#bada55" id="unb-options-divider-bdrtopcolor" class="unb-options-divider-trigger un-color-field" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Background Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-divider-bgcolor" class="unb-options-divider-trigger un-color-field" />
            			</p>
          			</div>
                    
          			<!-- Social -->
          			<div id="unb-options-follow" class="unb-options-group">
            			<h2><?php _e( 'Facebook', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
                        <p>
              				<label for="unb-options-follow-facebook-link"><?php _e( 'URL', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-facebook-link" class="unb-options-follow-trigger unb-link un-input-large"/>
           			 	</p>
            			
                        <p>
              				<label for="unb-options-follow-facebook-alt"><?php _e( 'Alternate Text', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-facebook-alt" class="unb-options-follow-trigger unb-text un-input-large"/>
            			</p>
                        
            			<h2><?php _e( 'Twitter', 'ultimate-newsletter' ); ?></h2>
                        <hr />
            			
                        <p>
              				<label for="unb-options-follow-twitter-link"><?php _e( 'URL', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-twitter-link" class="unb-options-follow-trigger unb-link un-input-large" />
            			</p>
                        
            			<p>
              				<label for="unb-options-follow-twitter-alt"><?php _e( 'Alternate Text', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-twitter-alt" class="unb-options-follow-trigger unb-text un-input-large"/>
            			</p>
                        
            			<h2><?php _e( 'Google+', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
                        <p>
              				<label for="unb-options-follow-gplus-link"><?php _e( 'URL', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-gplus-link" class="unb-options-follow-trigger unb-link un-input-large"/>
            			</p>
            			
                        <p>
              				<label for="unb-options-follow-gplus-alt"><?php _e( 'Alternate Text', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-gplus-alt" class="unb-options-follow-trigger unb-text un-input-large"/>
            			</p>
                        
            			<h2><?php _e( 'LinkedIn', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
                        <p>
              				<label for="unb-options-follow-linkedin-link"><?php _e( 'URL', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-linkedin-link" class="unb-options-follow-trigger unb-link un-input-large"/>
            			</p>
                        
            			<p>
              				<label for="unb-options-follow-linkedin-alt"><?php _e( 'Alternate Text', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-linkedin-alt" class="unb-options-follow-trigger unb-text un-input-large"/>
            			</p>
                        
            			<h2><?php _e( 'Style', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
                        <p>
              				<label><?php _e( 'Background Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-follow-bgcolor" class="unb-options-follow-trigger un-color-field" />
            			</p>
                        
            			<p>
                        	<label><?php _e( 'Border', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-follow-bdrtopstyle" class="unb-options-follow-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-follow-bdrtopwid" class="unb-options-follow-trigger un-input-mini" placeholder="1px" />
              				<input type="text" value="#ffffff" id="unb-options-follow-bdrtopcolor" class="unb-options-follow-trigger un-color-field" />
            			</p>
            			
                        <h2><?php _e( 'Text Style', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
                        <p>
             	 			<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" value="#ffffff" id="unb-options-follow-txtcolor" class="unb-options-follow-trigger un-color-field" />
            			</p>
                    	
                        <p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-follow-fontfamily" class="unb-options-follow-trigger un-input-large">
                				<option value="Arial" selected="selected"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-follow-fontsize" class="unb-options-follow-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i.'px'; ?></option>
               					<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
                  			<select id="unb-options-follow-fontweight" class="unb-options-follow-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Text decoration', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-follow-txtdecoration" class="unb-options-follow-trigger un-input-large">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="underline"><?php _e( 'Underline', 'ultimate-newsletter' ); ?></option>
                				<option value="line-through"><?php _e( 'Line-Through', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<h2><?php _e( 'Settings', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
                        <p>
              				<label><?php _e( 'Display', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-follow-type" class="unb-options-follow-trigger un-input-large">
                				<option value="icon_only"><?php _e( 'Icon only', 'ultimate-newsletter' ); ?></option>
                				<option value="text_only"><?php _e( 'Text only', 'ultimate-newsletter' ); ?></option>
                				<option value="both"><?php _e( 'Both icon and text', 'ultimate-newsletter' ); ?></option>
             	 			</select>
            			</p>
                        
           				<p>
              				<label for="unb-options-follow-position"><?php _e( 'Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-follow-position" class="unb-options-follow-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label for="unb-options-follow-poswidth"><?php _e( 'Width', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-follow-poswidth" class="unb-options-follow-trigger un-input-large">
                				<option value="fit"><?php _e( 'Fit size', 'ultimate-newsletter' ); ?></option>
                				<option value="full" selected="selected"><?php _e( 'Full width', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
          			</div>
          			
                    <p></p>
                    <button type='button' class='button-primary unb-options-save' style="display: none">
          				<?php _e( 'Save and Exit', 'ultimate-newsletter' ); ?>
          			</button>
                    <button type='button' class='button-primary unb-options-cancel' style="display: none">
          				<?php _e( 'Cancel', 'ultimate-newsletter' ); ?>
          			</button>
        		</div>
      		</div>
            
      		<!-- Design Tab -->
      		<div id="unb-options-tab-design" class="unb-options-tab-content un-hide">
        		<!-- Page Design -->
        		<div id="unb-options-pagedesign" class="unb-options-accordion-item">
          			<h2>
                    	<a href="#" class="unb-options-accordion-trigger">
                        	<div class="un-pull-left">
              					<?php _e( 'Page Design', 'ultimate-newsletter' ); ?>
            				</div>
            				<div class="dashicons dashicons-plus un-pull-right"></div>
            				<div class="un-clearfix"></div>
            			</a>
          			</h2>
                    
          			<hr />
          
          			<div class="unb-options-accordion-inner un-hide">
            		
                    	<p>
              				<label><?php _e( 'Background Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-pagedesign-bgcolor" class="un-color-field unb-options-pagedesign-trigger" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Border Top', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-bdrtopstyle" class="un-select-mini unb-options-pagedesign-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-pagedesign-bdrtopwid" class="unb-options-pagedesign-trigger un-input-mini" placeholder="1px" />
              				<input type="text" id="unb-options-pagedesign-bdrtopcolor" class="un-color-field unb-options-pagedesign-trigger" />
            			</p>
                        
            			<h2><?php _e( 'Header 1', 'ultimate-newsletter' ); ?></h2>
            			<hr />
            			
                        <p>
              				<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-pagedesign-h1-txtcolor" class="un-color-field unb-options-headings-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h1-fontfamily" class="un-select-max unb-options-headings-trigger un-input-large">
                				<option value="Arial" selected="selected"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
             				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h1-fontsize" class="unb-options-headings-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Style', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h1-fontstyle" class="unb-options-headings-trigger un-input-large">
                				<option value="normal"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="italic"><?php _e( 'Italic', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
                		<p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h1-fontweight" class="unb-options-headings-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
             				 </select>
            			</p>
                        
            			<p>
                        	<label><?php _e( 'Line Height', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h1-lineheight" class="unb-options-headings-trigger un-input-large">
                				<option value="100%"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="32.5px"><?php _e( 'Slight', 'ultimate-newsletter' ); ?></option>
                				<option value="150%"><?php _e( '1 1/2 Spacing', 'ultimate-newsletter' ); ?></option>
                				<option value="200%"><?php _e( 'Double Space', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Letter Spacing', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h1-letterspacing" class="unb-options-headings-trigger un-input-large">
                				<option value="0"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<?php for( $i = -5; $i <= 5; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
                				<?php endfor; ?>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Text Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h1-textalign" class="unb-options-headings-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<h2><?php _e( 'Header 2', 'ultimate-newsletter' ); ?></h2>
                        <hr />
            			
                        <p>
              				<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" value="" id="unb-options-pagedesign-h2-txtcolor" class="un-color-field unb-options-headings-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h2-fontfamily" class="unb-options-headings-trigger un-input-large">
                				<option value="Arial" selected="selected"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h2-fontsize" class="unb-options-headings-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i.'px'; ?>"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Style', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h2-fontstyle" class="unb-options-headings-trigger un-input-large">
                				<option value="normal"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="italic"><?php _e( 'Italic', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h2-fontweight" class="unb-options-headings-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
             		 		</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Line Height', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h2-lineheight" class="unb-options-headings-trigger un-input-large">
                				<option value="100%"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="32.5px"><?php _e( 'Slight', 'ultimate-newsletter' ); ?></option>
                				<option value="150%"><?php _e( '1 1/2 Spacing', 'ultimate-newsletter' ); ?></option>
                				<option value="200%"><?php _e( 'Double Space', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Letter Spacing', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h2-letterspacing" class="unb-options-headings-trigger un-input-large">
                				<option value="0"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<?php for( $i = -5; $i <= 5; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Text Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h2-textalign" class="unb-options-headings-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<h2><?php _e( 'Header 3', 'ultimate-newsletter' ); ?></h2>
            			<hr />
            			
                        <p>
              				<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-pagedesign-h3-txtcolor" class="un-color-field unb-options-headings-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h3-fontfamily" class="unb-options-headings-trigger un-input-large">
                				<option value="Arial" selected="selected"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h3-fontsize" class="unb-options-headings-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i.'px'; ?>"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Style', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h3-fontstyle" class="unb-options-headings-trigger un-input-large">
                				<option value="normal"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="italic"><?php _e( 'Italic', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
                  			<select id="unb-options-pagedesign-h3-fontweight" class="unb-options-headings-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Line Height', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h3-lineheight" class="unb-options-headings-trigger un-input-large">
                				<option value="100%"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="32.5px"><?php _e( 'Slight', 'ultimate-newsletter' ); ?></option>
                				<option value="150%"><?php _e( '1 1/2 Spacing', 'ultimate-newsletter' ); ?></option>
                				<option value="200%"><?php _e( 'Double Space', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Letter Spacing', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h3-letterspacing" class="unb-options-headings-trigger un-input-large">
               			 		<option value="0"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<?php for( $i = -5; $i <= 5; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
                				<?php endfor; ?>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Text Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h3-textalign" class="unb-options-headings-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<h2><?php _e( 'Header 4', 'ultimate-newsletter' ); ?></h2>
            			<hr />
                        
            			<p>
             				<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" value="" id="unb-options-pagedesign-h4-txtcolor" class="un-color-field unb-options-headings-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h4-fontfamily" class="unb-options-headings-trigger un-input-large">
                				<option value="Arial" selected="selected"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h4-fontsize" class="unb-options-headings-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i.'px'; ?>"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Style', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h4-fontstyle" class="unb-options-headings-trigger un-input-large">
                				<option value="normal"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="italic"><?php _e( 'Italic', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h4-fontweight" class="unb-options-headings-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Line Height', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h4-lineheight" class="unb-options-headings-trigger un-input-large">
               					<option value="100%"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="32.5px"><?php _e( 'Slight', 'ultimate-newsletter' ); ?></option>
                				<option value="150%"><?php _e( '1 1/2 Spacing', 'ultimate-newsletter' ); ?></option>
                				<option value="200%"><?php _e( 'Double Space', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Letter Spacing', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h4-letterspacing" class="unb-options-headings-trigger un-input-large">
                				<option value="0"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<?php for( $i = -5; $i <= 5; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Text Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-pagedesign-h4-textalign" class="unb-options-headings-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
          			</div>
        		</div>
                
        		<!--  Preheader Design  -->
        		<div id="unb-options-preheaderdesign" class="unb-options-accordion-item">
          			<h2>
                    	<a href="#" class="unb-options-accordion-trigger">
            				<div class="un-pull-left"><?php _e( 'Preheader', 'ultimate-newsletter' ); ?></div>
            				<div class="dashicons dashicons-plus un-pull-right"></div>
            				<div class="un-clearfix"></div>
            			</a>
                  	</h2>
          			
                    <hr />
          			
                    <div class="unb-options-accordion-inner un-hide">
                        <p>
              				<label><?php _e( 'Background Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-preheaderdesign-bgcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Border Top', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-preheaderdesign-bdrtopstyle" class="unb-options-phbf-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-preheaderdesign-bdrtopwid" class="unb-options-phbf-trigger un-input-mini"  placeholder="1px" />
              				<input type="text" id="unb-options-preheaderdesign-bdrtopcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Border Bottom', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-preheaderdesign-bdrbottomstyle" class="unb-options-phbf-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-preheaderdesign-bdrbottomwid" class="unb-options-phbf-trigger un-input-mini"  placeholder="1px" />
              				<input type="text" value="" id="unb-options-preheaderdesign-bdrbottomcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Padding Top', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-preheaderdesign-padtop" class="unb-options-phbf-trigger un-input-large" placeholder="1px" />
                         </p>
                         
            			<p>
              				<label><?php _e( 'Padding Bottom', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-preheaderdesign-padbottom" class="unb-options-phbf-trigger un-input-large" placeholder="1px" />
              			</p>
                        
            			<p>
              				<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-preheaderdesign-txtcolor" class="un-color-field unb-options-phbftxt-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-preheaderdesign-fontfamily" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="Arial" selected="selected"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-preheaderdesign-fontsize" class="unb-options-phbftxt-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i; ?>px"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Line Height', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-preheaderdesign-lineheight" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="100%"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="32.5px"><?php _e( 'Slight', 'ultimate-newsletter' ); ?></option>
                				<option value="18px"><?php _e( '1 1/2 Spacing', 'ultimate-newsletter' ); ?></option>
                				<option value="200%"><?php _e( 'Double Space', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Text Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-preheaderdesign-textalign" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                            	<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Link Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-preheaderdesign-linkcolor" class="un-color-field unb-options-phbflink-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-preheaderdesign-fontweight" class="unb-options-phbflink-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
                        	<label><?php _e( 'Text decoration', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-preheaderdesign-txtdecoration" class="unb-options-phbflink-trigger un-input-large">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="underline"><?php _e( 'Underline', 'ultimate-newsletter' ); ?></option>
                				<option value="line-through"><?php _e( 'Line-Through', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
          			</div>
        		</div>
                
        		<!--Header-->
        		<div id="unb-options-headerdesign" class="unb-options-accordion-item">
          			<h2>
                    	<a href="#" class="unb-options-accordion-trigger">
            				<div class="un-pull-left"><?php _e( 'Header', 'ultimate-newsletter' ); ?></div>
            				<div class="dashicons dashicons-plus un-pull-right"></div>
            				<div class="un-clearfix"></div>
            			</a>
                    </h2>
          			
                    <hr />
                    
          			<div class="unb-options-accordion-inner un-hide">
            			
                        <p>
              				<label><?php _e( 'Background Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" value="" id="unb-options-headerdesign-bgcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Border Top', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-headerdesign-bdrtopstyle" class="unb-options-phbf-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-headerdesign-bdrtopwid" class="unb-options-phbf-trigger un-input-mini" placeholder="1px" />
              				<input type="text" id="unb-options-headerdesign-bdrtopcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Border Bottom', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-headerdesign-bdrbottomstyle" class="unb-options-phbf-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-headerdesign-bdrbottomwid" class="unb-options-phbf-trigger un-input-mini" placeholder="1px" />
              				<input type="text" value="" id="unb-options-headerdesign-bdrbottomcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Padding Top', 'ultimate-newsletter' ); ?></label>
              				<input type="text" class="unb-options-phbf-trigger un-input-large" id="unb-options-headerdesign-padtop" placeholder="0px" />
                        </p>
                        
            			<p>
              				<label><?php _e( 'Padding Bottom', 'ultimate-newsletter' ); ?></label>
              				<input type="text" class="unb-options-phbf-trigger un-input-large" id="unb-options-headerdesign-padbottom" placeholder="0px" />
              			</p>
                        
            			<p>
              				<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-headerdesign-txtcolor" class="un-color-field unb-options-phbftxt-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-headerdesign-fontfamily" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="Arial"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
             				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-headerdesign-fontsize" class="unb-options-phbftxt-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i.'px'; ?>"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
             			 	</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Line Height', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-headerdesign-lineheight" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="100%"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="32.5px"><?php _e( 'Slight', 'ultimate-newsletter' ); ?></option>
                				<option value="150%"><?php _e( '1 1/2 Spacing', 'ultimate-newsletter' ); ?></option>
                				<option value="200%"><?php _e( 'Double Space', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Text Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-headerdesign-textalign" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Link Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-headerdesign-linkcolor" class="un-color-field unb-options-phbflink-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-headerdesign-fontweight" class="unb-options-phbflink-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Text Decoration', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-headerdesign-txtdecoration" class="unb-options-phbflink-trigger un-input-large">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="underline"><?php _e( 'Underline', 'ultimate-newsletter' ); ?></option>
                				<option value="line-through"><?php _e( 'Line-Through', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
          			</div>
        		</div>
                
        		<!--  Body Design-->
        		<div id="unb-options-bodydesign" class="unb-options-accordion-item">
          			<h2>
                    	<a href="#" class="unb-options-accordion-trigger">
            				<div class="un-pull-left"><?php _e( 'Body', 'ultimate-newsletter' ); ?></div>
            				<div class="dashicons dashicons-plus un-pull-right"></div>
            				<div class="un-clearfix"></div>
            			</a>
                   	</h2>
          			
                    <hr />
          			
                    <div class="unb-options-accordion-inner un-hide">
            			<p>
              				<label><?php _e( 'Background Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-bodydesign-bgcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Border Top', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-bodydesign-bdrtopstyle" class="unb-options-phbf-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-bodydesign-bdrtopwid" class="unb-options-phbf-trigger un-input-mini" placeholder="1px" />
              				<input type="text" id="unb-options-bodydesign-bdrtopcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Border Bottom', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-bodydesign-bdrbottomstyle" class="unb-options-phbf-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-bodydesign-bdrbottomwid" class="unb-options-phbf-trigger un-input-mini" placeholder="1px" />
             				<input type="text" id="unb-options-bodydesign-bdrbottomcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Padding Top', 'ultimate-newsletter' ); ?></label>
              				<input type="text" class="unb-options-phbf-trigger un-input-large" id="unb-options-bodydesign-padtop" placeholder="0px" />
              			</p>
            			
                        <p>
              				<label><?php _e( 'Padding Bottom', 'ultimate-newsletter' ); ?></label>
              				<input type="text" class="unb-options-phbf-trigger un-input-large" id="unb-options-bodydesign-padbottom" placeholder="0px" />
              			</p>
            			
                        <p>
              				<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" value="#fff" id="unb-options-bodydesign-txtcolor" class="un-color-field unb-options-phbftxt-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-bodydesign-fontfamily" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="Arial" selected="selected"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-bodydesign-fontsize" class="unb-options-phbftxt-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i.'px'; ?>"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Line Height', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-bodydesign-lineheight" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="100%"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="32.5px"><?php _e( 'Slight', 'ultimate-newsletter' ); ?></option>
                				<option value="150%"><?php _e( '1 1/2 Spacing', 'ultimate-newsletter' ); ?></option>
                				<option value="200%"><?php _e( 'Double Space', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
                    	<p>
              				<label><?php _e( 'Text Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-bodydesign-textalign" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
             				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Link Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-bodydesign-linkcolor" class="un-color-field unb-options-phbflink-trigger" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-bodydesign-fontweight" class="unb-options-phbflink-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
              				</select>
                        </p>
                        
            			<p>
              				<label><?php _e( 'Text decoration', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-bodydesign-txtdecoration" class="unb-options-phbflink-trigger un-input-large">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="underline"><?php _e( 'Underline', 'ultimate-newsletter' ); ?></option>
                				<option value="line-through"><?php _e( 'Line-Through', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
          			</div>
        		</div>
                
        		<!---Footer Design-->
        		<div id="unb-options-footerdesign" class="unb-options-accordion-item">
          			<h2>
                    	<a href="#" class="unb-options-accordion-trigger">
            				<div class="un-pull-left"><?php _e( 'Footer Design', 'ultimate-newsletter' ); ?></div>
            				<div class="dashicons dashicons-plus un-pull-right"></div>
            				<div class="un-clearfix"></div>
            			</a>
                   	</h2>
          			
                    <hr />
          			
                    <div class="unb-options-accordion-inner un-hide">
            			<p>
              				<label><?php _e( 'Background Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-footerdesign-bgcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
            			
                        <p>
              				<label><?php _e( 'Border Top', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-footerdesign-bdrtopstyle" class="unb-options-phbf-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-footerdesign-bdrtopwid" class="unb-options-phbf-trigger un-input-mini"  placeholder="1px" />
              				<input type="text" id="unb-options-footerdesign-bdrtopcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Border Bottom', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-footerdesign-bdrbottomstyle" class="unb-options-phbf-trigger un-input-mini">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                            	<option value="solid"><?php _e( 'Solid', 'ultimate-newsletter' ); ?></option>
                				<option value="dotted"><?php _e( 'Dotted', 'ultimate-newsletter' ); ?></option>
                				<option value="dashed"><?php _e( 'Dashed', 'ultimate-newsletter' ); ?></option>
              				</select>
              				<input type="text" id="unb-options-footerdesign-bdrbottomwid" class="unb-options-phbf-trigger un-input-mini"  placeholder="1px" />
              				<input type="text" id="unb-options-footerdesign-bdrbottomcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Padding Top', 'ultimate-newsletter' ); ?></label>
              				<input type="text" class="unb-options-phbf-trigger un-input-large" id="unb-options-footerdesign-padtop" placeholder="0px" />
              			</p>
                        
            			<p>
             	 			<label><?php _e( 'Padding Bottom', 'ultimate-newsletter' ); ?></label>
              				<input type="text" class="unb-options-phbf-trigger un-input-large" id="unb-options-footerdesign-padbottom" placeholder="0px" />
              			</p>
                        
            			<p>
              				<label><?php _e( 'Text Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-footerdesign-txtcolor" class="un-color-field unb-options-phbf-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Family', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-footerdesign-fontfamily" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="Arial" selected="selected"><?php _e( 'Arial', 'ultimate-newsletter' ); ?></option>
                				<option value='"Comic Sans MS"'><?php _e( 'Comic Sans MS', 'ultimate-newsletter' ); ?></option>
                				<option value='"Courier New"'><?php _e( 'Courier New', 'ultimate-newsletter' ); ?></option>
                				<option value="Georgia"><?php _e( 'Georgia', 'ultimate-newsletter' ); ?></option>
                				<option value="Helvetica"><?php _e( 'Helvetica', 'ultimate-newsletter' ); ?></option>
                				<option value="Tahoma"><?php _e( 'Tahoma', 'ultimate-newsletter' ); ?></option>
                				<option  value='"Times New Roman"'><?php _e( 'Times New Roman', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
            			
                        <p>
              				<label><?php _e( 'Font Size', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-footerdesign-fontsize" class="unb-options-phbftxt-trigger un-input-large">
                				<?php for( $i = 9; $i <= 72; $i++ ) : ?>
                					<option value="<?php echo $i.'px'; ?>"><?php echo $i.'px'; ?></option>
                				<?php endfor; ?>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Line Height', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-footerdesign-lineheight" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="100%"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="32.5px"><?php _e( 'Slight', 'ultimate-newsletter' ); ?></option>
                				<option value="150%"><?php _e( '1 1/2 Spacing', 'ultimate-newsletter' ); ?></option>
                				<option value="200%"><?php _e( 'Double Space', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Text Align', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-footerdesign-textalign" class="unb-options-phbftxt-trigger un-input-large">
                				<option value="left"><?php _e( 'Left', 'ultimate-newsletter' ); ?></option>
                				<option value="center"><?php _e( 'Center', 'ultimate-newsletter' ); ?></option>
                				<option value="right"><?php _e( 'Right', 'ultimate-newsletter' ); ?></option>
                				<option value="justify"><?php _e( 'Justify', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Link Color', 'ultimate-newsletter' ); ?></label>
              				<input type="text" id="unb-options-footerdesign-linkcolor" class="un-color-field unb-options-phbflink-trigger" />
            			</p>
                        
            			<p>
              				<label><?php _e( 'Font Weight', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-footerdesign-fontweight" class="unb-options-phbflink-trigger un-input-large">
                				<option value="400"><?php _e( 'Normal', 'ultimate-newsletter' ); ?></option>
                				<option value="bold"><?php _e( 'Bold', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
                        
            			<p>
              				<label><?php _e( 'Text decoration', 'ultimate-newsletter' ); ?></label>
              				<select id="unb-options-footerdesign-txtdecoration" class="unb-options-phbflink-trigger un-input-large">
                				<option value="none"><?php _e( 'None', 'ultimate-newsletter' ); ?></option>
                				<option value="underline"><?php _e( 'Underline', 'ultimate-newsletter' ); ?></option>
                				<option value="line-through"><?php _e( 'Line-Through', 'ultimate-newsletter' ); ?></option>
              				</select>
            			</p>
          			</div>
        		</div>
                
      		</div>
    	</div>
        
    	<div class="un-clearfix"></div>
  	</div>
    
  	<form method="post" id="un-builder-form" action="<?php echo admin_url( 'admin.php?page=ultimate_newsletters' ); ?>">
    	<input type="hidden" name="action" value="save" />
    	<input type="hidden" name="tab" value="templates" />
        <input type="hidden" id="un-template-slug" value="<?php echo $active_template; ?>" />
    	<input type="hidden" name="id" id="un-post-id" value="<?php echo $newsletter_id; ?>" />
    	<input type="hidden" name="post_content" id="un-post-content" value="" />
    	<?php submit_button( __( 'Save & Continue', 'ultimate-newsletter' ), 'primary', 'save' );  ?>
  	</form>
</div>