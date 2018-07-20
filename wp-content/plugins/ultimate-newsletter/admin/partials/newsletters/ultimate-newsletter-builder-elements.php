<?php

/**
 *  Markup the builder elements.
 */ 
?>

<!-- Text Block -->
<div id="unb-text-block" style="display: none">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="unTextBlock" style="min-width:100%;">
        <tbody class="unTextBlockOuter">	
            <tr>
                <td valign="top" class="unTextBlockInner" style="padding-top:9px;">
                    <!--[if mso]>
                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                    <tr>
                    <![endif]-->
                    
                    <!--[if mso]>
                    <td valign="top" width="600" style="width:600px;">
                    <![endif]-->
                    <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="unTextContentContainer">
                        <tbody>
                            <tr>
                                <td valign="top" class="unTextContent" style="padding-top:0; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                                    <p>This is a text block.</p>
                                </td>
                            </tr>
                       </tbody>
                    </table>
                    <!--[if mso]>
                    </td>
                    <![endif]-->
                    
                    <!--[if mso]>
                    </tr>
                    </table>
                    <![endif]-->
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Image Block -->
<div id="unb-image-block" style="display: none;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="unImageBlock" style="min-width:100%;">
        <tbody class="unImageBlockOuter">
            <tr>
                <td valign="top" style="padding:9px" class="unImageBlockInner">
                    <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="unImageContentContainer" style="min-width:100%;">
                        <tbody>
                            <tr>
                                <td class="unImageContent" valign="top" style="padding-right:9px; padding-left:9px; padding-top:0; padding-bottom:0; text-align:center;">
                                    <a href="#">
                                        <img src="<?php echo ULTIMATE_NEWSLETTER_PLUGIN_URL; ?>public/images/image-placeholder.png" align="center" alt="Image" class="unImage" />
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Button Block -->
<div id="unb-button-block" style="display: none;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="unButtonBlock" style="min-width:100%;">
        <tbody class="unButtonBlockOuter">
            <tr>
                <td style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top" align="center" class="unButtonBlockInner">
                    <table border="0" cellpadding="0" cellspacing="0" class="unButtonContentContainer" style="border-collapse: separate !important;">
                        <tbody>
                            <tr>
                                <td align="center" valign="middle" class="unButtonContent" style="padding:15px; line-height:100%">
                                    <a href="#" class="unButton" target="_blank" title="Buy Now" style="text-decoration:none;">Buy Now</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Divider Block -->
<div id="unb-divider-block" style="display: none;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="unDividerBlock" style="min-width:100%;">
        <tbody class="unDividerBlockOuter">
            <tr>
                <td class="unDividerBlockInner" style="min-width:100%; padding:10px 18px 25px;">
                    <table class="unDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <span></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Follow Block -->
<div id="unb-follow-block" style="display: none;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="unFollowBlock" style="min-width:100%;">
        <tbody class="unFollowBlockOuter">
            <tr>
                <td align="center" valign="top" style="padding:9px" class="unFollowBlockInner">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="unFollowContentContainer" style="min-width:100%;">
                        <tbody>
                            <tr>
                                <td align="center" style="padding-left:9px; padding-right:9px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="unFollowContent">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top" style="padding-top:9px; padding-right:9px; padding-left:9px;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" valign="top">
                                                                    [UN_FOLLOW_CONTENT]
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Follow Content -->
<div id="unb-follow-content" style="display: none;">
    <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
        <tbody>
            <tr>
                <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="unFollowContentItemContainer">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="unFollowContentItem">
                        <tbody>
                            <tr>
                                <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" width="24" class="unFollowIconContent">
                                                    <a href="" target="parent"> <img src="" style="display:block;" height="24" width="24" class="unImage" /> </a>
                                                </td>
                                                <td align="left" valign="middle" class="unFollowTextContent" style="padding-left:5px;">
                                                    <a href="" target="" ></a>
                                                 </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Follow Block Content -->
<div id="unb-follow-block-content" style="display: none;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="unFollowBlock" style="min-width:100%;">
  		<tbody class="unFollowBlockOuter">
    		<tr>
      			<td align="center" valign="top" style="padding:9px" class="unFollowBlockInner">
            		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="unFollowContentContainer" style="min-width:100%;">
          				<tbody>
            				<tr>
              					<td align="center" style="padding-left:9px; padding-right:9px;">
                            		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="unFollowContent">
                  						<tbody>
                    						<tr>
                      							<td align="center" valign="top" style="padding-top:9px; padding-right:9px; padding-left:9px;">
                                            		<table align="center" border="0" cellpadding="0" cellspacing="0">
                          								<tbody>
                            								<tr>
                              									<td align="center" valign="top">
                                                            		<!--[if mso]>
                                                                		<table align="center" border="0" cellspacing="0" cellpadding="0">
                                                                		<tr>
                                                                	<![endif]-->
                                                                											
                                                                    <!-- START FACEBOOK // -->
                                                                    <!--[if mso]>
																		<td align="center" valign="top">
																	<![endif]-->
																	<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
  																		<tbody>
    																		<tr>
      																			<td valign="top" style="padding-right:10px; padding-bottom:9px;" class="unFollowContentItemContainer">
            																		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="unFollowContentItem">
          																				<tbody>
            																				<tr>
              																					<td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                                	<table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                  																						<tbody>
                    																						<tr>
                      																							<td align="center" valign="middle" width="24" class="unFollowIconContent">
                                                                                                                	<a href="https://facebook.com/" target="_blank"> <img src="<?php echo ULTIMATE_NEWSLETTER_PLUGIN_URL; ?>public/images/facebook.png" style="display:block;" height="24" width="24" class="unImage" /> </a>
                                                                                                              	</td>
                      																							<td align="left" valign="middle" class="unFollowTextContent" style="padding-left:5px;">
                                                                                                                	<a href="https://facebook.com/" target="_blank" >Facebook</a>
                                             																	</td>
                    																						</tr>
                  																						</tbody>
                																					</table>
                          																		</td>
            																				</tr>
          																				</tbody>
        																			</table>
         																		</td>
    																		</tr>
  																		</tbody>
																	</table>
																	<!--[if mso]>
																		</td>
																	<![endif]-->
                                                                    
                                                                    <!-- // END FACEBOOK -->
                                                                                                            
                                                                    <!-- START TWITTER // -->
                                                                    <!--[if mso]>
																		<td align="center" valign="top">
																	<![endif]-->
																	<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
  																		<tbody>
    																		<tr>
      																			<td valign="top" style="padding-right:10px; padding-bottom:9px;" class="unFollowContentItemContainer">
                                                                                	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="unFollowContentItem">
          																				<tbody>
            																				<tr>
              																					<td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                                	<table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                  																						<tbody>
                    																						<tr>
                      																							<td align="center" valign="middle" width="24" class="unFollowIconContent">
                                                                                                                	<a href="https://twitter.com/" target="_blank"> <img src="<?php echo ULTIMATE_NEWSLETTER_PLUGIN_URL; ?>public/images/twitter.png" style="display:block;" height="24" width="24" class="unImage" /> </a>
                                            																	</td>
                      																							<td align="left" valign="middle" class="unFollowTextContent" style="padding-left:5px;">
                                                                                                                	<a href="https://twitter.com/" target="_blank" >Twitter</a>
                                             																	</td>
                    																						</tr>
                  																						</tbody>
                																					</table>
                          																		</td>
            																				</tr>
          																				</tbody>
        																			</table>
         																		</td>
    																		</tr>
  																		</tbody>
																	</table>
																	<!--[if mso]>
																		</td>
																	<![endif]-->
                                                                    <!-- // END TWITTER -->
                                                                                                            
                                                                	<!-- START GPLUS // -->
                                                                    <!--[if mso]>
																		<td align="center" valign="top">
																	<![endif]-->
																	<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
  																		<tbody>
    																		<tr>
      																			<td valign="top" style="padding-right:10px; padding-bottom:9px;" class="unFollowContentItemContainer">
                                                                                	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="unFollowContentItem">
          																				<tbody>
            																				<tr>
              																					<td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                                	<table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                  																						<tbody>
                    																						<tr>
                      																							<td align="center" valign="middle" width="24" class="unFollowIconContent">
                                                                                                                	<a href="https://mail.google.com/" target="_blank"> <img src="<?php echo ULTIMATE_NEWSLETTER_PLUGIN_URL; ?>public/images/gplus.png" style="display:block;" height="24" width="24" class="unImage" /> </a>
                                            																	</td>
                      																							<td align="left" valign="middle" class="unFollowTextContent" style="padding-left:5px;">
                                                                                                                	<a href="https://mail.google.com/" target="_blank" >Google+</a>
                                             																	</td>
                    																						</tr>
                  																						</tbody>
                																					</table>
                          																		</td>
            																				</tr>
          																				</tbody>
        																			</table>
         																		</td>
    																		</tr>
  																		</tbody>
																	</table>
																	<!--[if mso]>
																		</td>
																	<![endif]-->
                                                                    <!-- // END GPLUS -->
                                                                
                                                                	<!--[if mso]>
                														</tr>
                														</table>
                													<![endif]-->
                                                            	</td>
                            								</tr>
                          								</tbody>
                        							</table>
                                          		</td>
                    						</tr>
                  						</tbody>
                					</table>
                         		</td>
            				</tr>
          				</tbody>
        			</table>
         		</td>
    		</tr>
  		</tbody>
	</table>
</div>

<!-- Builder Toolset -->
<div id="unb-toolset-container">
	<div class="unb-toolset">
    	<div class="unb-toolset-buttons-left">
    		<span class="handle fa fa-arrows unb-toolset-button-handle"></span>
		</div> 
		<div class="unb-toolset-buttons-right">
        	<span class="unb-toolset-button-edit fa fa-edit"></span>
        	<span class="unb-toolset-button-copy fa fa-copy"></span>
        	<span class="unb-toolset-button-delete fa fa-trash"></span>
		</div>
	</div>
</div>

<!-- Temp Container -->
<div id="unb-temp-elem" style="display:none;"></div>