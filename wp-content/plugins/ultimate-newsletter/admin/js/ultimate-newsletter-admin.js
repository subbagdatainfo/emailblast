(function( $ ) {
	'use strict';
	
	/**
 	 * Add values to the fields.
 	 *
 	 * @since    1.0.0
 	 */
	function unb_set_field_values( value, id, style ) {
		
		if( typeof value !== 'undefined' ) {
			
			if( /family/.test( style ) ) {
				value = value.replace( /"/g, "" );
			}
			
			$( '#'+id ).val( value );

			if( /color/.test( style ) ) {
				$( '#'+id ).iris( 'color', value );
			}
				
		}
		
	}

	/**
 	 * Get query string value from the URL.
 	 *
 	 * @since    1.0.0
 	 */
	function un_get_query_string( name, url ) {
    	
		if( ! url ) url = window.location.href;
    	name = name.replace(/[\[\]]/g, "\\$&");
		
    	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
       	var results = regex.exec( url );
    	
		if( ! results ) return null;
    	if( ! results[2] ) return '';
    	return decodeURIComponent( results[2].replace(/\+/g, " ") );
		
	}
	
	/**
 	 * Displays the media uploader for selecting a media.
 	 *
 	 * @since    1.0.0
 	 */
	function unb_render_media_uploader( media ) {	
	
		var file_frame;
	 
		/**
		 * If an instance of file_frame already exists, then we can open it
		 * rather than creating a new instance.
		 */
		if ( undefined !== file_frame ) {
	 
			file_frame.open();
			return;
	 
		}
	 
		/**
		 * If we're this far, then an instance does not exist, so we need to
		 * create our own.
		 *
		 * Here, use the wp.media library to define the settings of the Media
		 * Uploader. We're opting to use the 'post' frame which is a template
		 * defined in WordPress core and are initializing the file frame
		 * with the 'insert' state.
		 *
		 * We're also not allowing the user to select more than one image.
		 */
		file_frame = wp.media.frames.file_frame = wp.media({
			frame    : 'post',
			state    : 'insert',
			multiple : false
		});
	 
		/**
		 * Setup an event handler for what to do when an image has been
		 * selected.
		 *
		 * Since we're using the 'view' state when initializing
		 * the file_frame, we need to make sure that the handler is attached
		 * to the insert event.
		 */
		file_frame.on( 'insert', function() {
	 
			// Read the JSON data returned from the Media Uploader
			var json = file_frame.state().get( 'selection' ).first().toJSON();
			
			// First, make sure that we have the URL of the media to display
			if( 0 > $.trim( json.url.length ) ) {
				return;
			}
			
			// Set the properties
			if( 'csv' == media ) {
				
				if( 'csv' == json.subtype || 'text/csv' == json.mime ) {
					$( '#un-import-row-csv' ).removeClass( 'un-invalid' );
					$( '#un-csv-upload-error' ).hide();
					$( '#un-import-csv' ).val( json.url );	
				} else {
					$( '#un-import-row-csv' ).addClass( 'un-invalid' );
					$( '#un-csv-upload-error' ).show();
					$( '#un-import-csv' ).val( '' );	
				}
				
			} else {
				
				$( '#unb-options-image-url' ).val( json.url );
				$( '#unb-template .active' ).find( '.unImageContent' ).find( 'img' ).attr( 'src', json.url );
				$( '#unb-options-image-alt' ).val( json.caption || json.title );
			
			}
			
	 
		});
	 
		// Now display the actual file_frame
		file_frame.open();
	 
	}
	
	/**
 	 * Reset builer options window.
 	 *
 	 * @since    1.0.0
 	 */
	function unb_reset_options_window() {
		
		$( "#unb-options-content" ).hide();
		$( '#unb-cpanel' ).show();
		$( '.unb-content-wrapper.active' ).removeClass( 'active' );
			
	}
	
	
	/**
	 * On Document Ready
	 */
	$(function() {	 		
		
		// [Settings] Send test email.
		$( '#un-settings-test-email-btn' ).on( 'click', function( e ) {
																 
			e.preventDefault();
			e.stopImmediatePropagation();
			
			var $this = $( this );
			
			var data = {
				'action' : 'un_settings_send_test_email',
				'email'  : $( '#un-settings-test-email' ).val(),
			};
			
			$( '#un-settings-test-email-response' ).html( '' ).addClass( 'spinner' );
					
			$.post( ajaxurl, data, function( response ) {
											
				$( '#un-settings-test-email-response' ).removeClass( 'spinner' );
				
				if( 1 == response.error ) {					
					$( '#un-settings-test-email-response' ).css( 'color', 'red' ).html( response.message );
				} else {
					$( '#un-settings-test-email-response' ).css( 'color', 'green' ).html( response.message );
				};
				
			}, 'json' );
			
		});	
		
		// [Subscribers] Update Orderby, Order values.
		$( '#un-subscribers-list-form th.sortable' ).each(function() {
																   
			var $a = $( this ).find( 'a' );

			var active_orderby = $( '#un-orderby' ).val();
			var active_order   = $( '#un-order' ).val();
			
			var orderby = un_get_query_string( 'orderby', $a.attr( 'href' ) );
			var order   = un_get_query_string( 'order', $a.attr( 'href' ) );
			
			if( active_orderby == orderby ) {
				$( this ).removeClass( 'asc desc' ).addClass( active_order );
				order = ( 'asc' == active_order ) ? 'desc' : 'asc';
			}	
			
			$a.attr({
				'href'         : '#',
				'data-orderby' : orderby,
				'data-order'   : order
			}).on( 'click', function( e ) {
				e.preventDefault();
				
				$( '#un-orderby' ).val( $( this ).data( 'orderby' ) );
				$( '#un-order' ).val( $( this ).data( 'order' ) );
				
				$( '#un-subscribers-list-form' ).submit();
			});
			
		});
		
		// [Newsletters] Update Orderby, Order values.
		$( '#ultimate-newsletters-list-form th.sortable' ).each( function() {
																	 
			var $a = $( this ).find( 'a' );

			var active_orderby = $( '#un-orderby' ).val();
			var active_order   = $( '#un-order' ).val();
			
			var orderby = un_get_query_string( 'orderby', $a.attr( 'href' ) );
			var order   = un_get_query_string( 'order', $a.attr( 'href' ) );
			
			if( active_orderby == orderby ) {
				$( this ).removeClass( 'asc desc' ).addClass( active_order );
				order = ( 'asc' == active_order ) ? 'desc' : 'asc';
			}	
			
			$a.attr({
				'href'         : '#',
				'data-orderby' : orderby,
				'data-order'   : order
			}).on( 'click', function( e ) {
				e.preventDefault();
				
				$( '#un-orderby' ).val( $( this ).data( 'orderby' ) );
				$( '#un-order' ).val( $( this ).data( 'order' ) );
				
				$( '#ultimate-newsletters-list-form ').submit();
			});
			
		});
		
		// [Newsletters] Send test email.
		$( '#un-test-email-btn' ).on( 'click', function( e ) {
																		 
			e.preventDefault();
			e.stopImmediatePropagation();
			
			var $this = $( this );

			var data = {
				'action' : 'un_send_test_email',
				'id'	 : $( '#un-post-id' ).val(),
				'title'	 : $( '#un-title' ).val(),
				'email'	 : $( '#un-test-email' ).val()
			};
			
			$( '#un-test-email-response' ).html( '' ).addClass( 'spinner' );
			
			$.post( ajaxurl, data, function( response ) {
											
				$( '#un-test-email-response' ).removeClass( 'spinner' );
				
				if( 1 == response.error ) {					
					$( '#un-test-email-response' ).css( 'color', 'red' ).html( response.message );
				} else {
					$( '#un-test-email-response' ).css( 'color', 'green' ).html( response.message );
				};
				
			}, 'json' );
			
		});
		
		// Color Picker
		$('.un-color-field').wpColorPicker();	
		
		/*********************************************************************************
		** Builder [START] **
		*********************************************************************************/	
		// WP Color Picker
		var selector = {
			"pagedesign"	  : "unBodyTable",
			"preheaderdesign" : "unTemplatePreheader",
			"headerdesign"    : "unTemplateHeader",
			"bodydesign"   	  : "unTemplateBody",
			"footerdesign"	  : "unTemplateFooter",
		};
		
		var colors = {
			"bgcolor"		  : "background-color",
			"bdrtopcolor"     : "border-top-color",
			"bdrbottomcolor"  : "border-bottom-color",
			"txtcolor"		  : "color",
			"linkcolor"       : "color",
		}
		
		$( '.un-color-field' ).wpColorPicker({	
											 
			change: function( event, ui ) {
				
				var value = ui.color.toString();
				var id = $(this).attr('id').split('-');
				
				if( 'pagedesign' == id[2] || 'preheaderdesign' == id[2] || 'headerdesign' == id[2] || 'bodydesign' == id[2] || 'footerdesign' == id[2] ) {
					
					var  activeid = selector[ id[2] ];
					
					if( 'h1' == id[3] || 'h2' == id[3] || 'h3' == id[3] || 'h4' == id[3] ) {
					
						$( '#'+activeid ).find( id[3] ).css( 'color', value );
						
					} else if( 'txtcolor' == id[3] ) {
						
						$( '#'+activeid ).find( ".unTextContent, .unTextContent p" ).css( 'color', value );
						
					} else if( 'linkcolor' == id[3] ) {
						
						$( '#'+activeid ).find( ".unTextContent a, .unTextContent p a" ).css( 'color', value );
						
					} else {
						
						var  style = colors[ id[3] ];
						$( '#unTemplate' ).css( 'background-color', $( '#unb-options-pagedesign-bgcolor' ).val() );
						$( '#'+activeid ).css( style , value );
					}
					
				} else if( 'button' == id[2] ) {
				
					if( 'txtcolor' == id[3] ) {
						
						$( '.unb-content-wrapper.active' ).find( '.unButtonContent a' ).css( 'color', value );
						
					} else if( 'bdrcolor' == id[3] ) {
						
						$( '.unb-content-wrapper.active' ).find( '.unButtonContentContainer' ).css( 'border-color', value );
						
					} else if( 'bgcolor' == id[3] ) {
						
						$( '.unb-content-wrapper.active' ).find( '.unButtonContentContainer' ).css( 'background-color', value );
					}
					
				} else if( 'follow' == id[2] ) {
				
					if( 'txtcolor' == id[3] ) {
						
						$( '.unb-content-wrapper.active' ).find( '.unFollowTextContent a' ).css( 'color', value );
						
					} else if( 'bdrtopcolor' == id[3] ) {
						
						$( '.unb-content-wrapper.active' ).find( '.unFollowContent' ).css( 'border-color' , value );
						
					} else if( 'bgcolor' == id[3] ) {
						
						$( '.unb-content-wrapper.active' ).find( '.unFollowContent' ).css( 'background-color', value );	
					}
					
				} else if( 'divider' == id[2] ) {
				
					if( 'bgcolor' == id[3] ) {
						
						$( '.unb-content-wrapper.active' ).find( '.unDividerBlock' ).css( 'background-color', value );
						
					} else if( 'bdrtopcolor' == id[3] ) {
						
						$( '.unb-content-wrapper.active' ).find( '.unDividerContent' ).css( 'border-top-color' , value );
						
					}
				}
			}
			
		});
		
		// Fill empty sections eith an empty container element
		$( ".unPreheaderContainer, .unHeaderContainer, .unBodyContainer, .unFooterContainer" ).each(function() {
																											
			$( this ).prepend( '<div class="unb-sort-placeholder" style="display: none;">'+un.sort_placeholder_text+'</div>' );
			
		});
		
		// Wrap content elements using builder tools
		$( "#unb-template .unTextBlock, #unb-template .unImageBlock, #unb-template .unButtonBlock, #unb-template .unFollowBlock, #unb-template .unDividerBlock ").each(function() {
			
			$( this ).wrap( '<div class="unb-content-wrapper" style="display: block;">'+$( "#unb-toolset-container" ).html()+'</div>' ).wrap( '<div class="unb-content-container"></div>' );
						
		});
		
		// Set active element
		$( '#unb-template' ).on( 'mouseenter', '.unb-content-wrapper', function( e ) {
			$( this ).addClass( 'hover' );
		}).on( 'mouseleave', '.unb-content-wrapper', function( e ) {
			$( this ).removeClass( 'hover');
		});	
		
		// Make elements sortable
		$( '.unTemplateContainer' ).sortable({
			handle : '.unb-toolset-button-handle',
    		cursor : 'move',
			items : '.unb-content-wrapper, .unb-sort-placeholder',
			connectWith : 'table',
			placeholder : 'unb-sort-placeholder',
			over : function( event, ui ) {
				
				$( '.unTemplateContainer' ).css( { "border": "2px solid black", "line-height": '3em' } );
				
				if( $( '.unb-content-wrapper', this ).length ) {
					ui.placeholder.html( un.sort_placeholder_text );
				} else {
					ui.placeholder.html( '' );
				}
				
			},
			stop : function( event, ui ) {
				
				if( $( ui.item ).hasClass( 'unb-cpanel-icon' ) ) {				
						
					var elem = $( '.unb-cpanel-icon.active' ).attr('id');
					elem = elem.replace( 'unb-', '' );
					
					var content = ( 'follow' == elem ) ? $( '#unb-follow-block-content' ).html() : $( '#unb-'+elem+'-block' ).html();
					var html = $( "#unb-toolset-container" ).html()+'<div class="unb-content-container">'+content+'</div>';
					
					$( ui.item ).removeAttr( 'class style' ).addClass( 'unb-content-wrapper' ).css( 'display', 'block' ).html( html ).find( '.unb-toolset-button-edit' ).trigger( 'click' );
					
				}
			
			},
			receive : function( event, ui ) {
				
                // Hide empty message on receiver
                $( '.unb-sort-placeholder', this ).hide();

                // Show empty message on sender if applicable
                if( 0 == $( '.unb-content-wrapper', ui.sender ).length ) {
                    $( '.unb-sort-placeholder', ui.sender ).show();
                } else {
                	$( '.unb-sort-placeholder', ui.sender ).hide();
            	}    
				 
            },
			out : function(event,ui){
			
				$('.unTemplateContainer' ).css( "border", "" );
			
			}
		});
		
		// Make elements draggable
		$( '.unb-cpanel-icon' ).draggable({
			connectToSortable : '.unTemplateContainer',
			helper : 'clone',
			scope  : "unb-sort-placeholder",
			start  : function() {
				$( '.unb-cpanel-icon.active' ).removeClass( 'active' );
				$( this ).addClass( "active" );
			}
		});
		
		// Make elements droppable
		$( ".unTemplateContainer" ).droppable({
    		accept : ".unb-cpanel-icon"
		});
		
		// Tab
		$( '.nav-tab-wrapper a.unb-nav-tab', '#un-builder' ).on( 'click', function( e ) {
																	   
			e.preventDefault();
		
			$( this ).parent().find( 'a' ).removeClass( 'nav-tab-active' );
			$( this ).addClass( 'nav-tab-active' );
			
			$( '.unb-options-tab-content' ).addClass( 'un-hide' );			
			var target_elem = $( this ).attr( 'href' );
			$( target_elem ).removeClass( 'un-hide' );
			
		});
		
		// Accordion	
		$( ".unb-options-accordion-trigger" ).on( "click", function( e ) {
			
			e.preventDefault();
			
			var expanded = $( this ).hasClass( 'unb-accordion-active' );

			if( expanded ) {
				
				$( this ).removeClass( 'unb-accordion-active' );
				$( this ).find( '.dashicons' ).removeClass( 'dashicons-minus' ).addClass( 'dashicons-plus' );
				$( this ).closest( '.unb-options-accordion-item' ).find( '.unb-options-accordion-inner' ).addClass( 'un-hide' );			
				
			} else {
				
				$( '.unb-options-accordion-trigger' ).removeClass( 'unb-accordion-active' ).find( '.dashicons' ).removeClass( 'dashicons-minus' ).addClass( 'dashicons-plus' );
				$( this ).addClass( 'unb-accordion-active' ).find( '.dashicons' ).removeClass( 'dashicons-plus' ).addClass( 'dashicons-minus' );

				$( '.unb-options-accordion-inner' ).addClass( 'un-hide' );	
				$( this ).closest( '.unb-options-accordion-item' ).find( '.unb-options-accordion-inner' ).removeClass( 'un-hide' );	
				
			}			
			
    	});
		
		// [Page design] set background color, border top styles
		var pagedesign = {
			'background-color' : 'bgcolor',
			'border-top-width' : 'bdrtopwid',
			'border-top-style' : 'bdrtopstyle',
			'border-top-color' : 'bdrtopcolor'
		};
		
		for( var style in pagedesign ) {
			
			var value = $( '#unBodyTable' ).css( style );
			var id    = 'unb-options-pagedesign-' + pagedesign[ style ];
					
			unb_set_field_values( value, id, style );
			
		}
		
		$( '.unb-options-pagedesign-trigger' ).on( 'change, click', function() {
			
			for( var style in pagedesign ) {
			
				var id    = 'unb-options-pagedesign-' + pagedesign[ style ];
				var value = $( '#'+id ).val();
				
				if( value == '' ) $( '#unBodyTable' ).css( style, '' );
				
				if( '' == $( '#unb-options-pagedesign-bdrtopcolor' ).val() ) {
			
					$( '#unb-options-pagedesign-bdrtopstyle' ).val('none');
					$( '#unb-options-pagedesign-bdrtopwid' ).val('0px');
					$( '#unb-options-pagedesign-bdrtopcolor' ).val('#808080');
				
				};
				
				if( value )	$( '#unBodyTable' ).css( style, value );
			}
			$( '#unTemplate' ).css( 'background-color', $( '#unb-options-pagedesign-bgcolor' ).val() );
			
		});
		
		// [Page design] header styles
		var headings = ['h1', 'h2', 'h3', 'h4'];
		var prop_h = {
			'color'       	 : 'txtcolor',
			'font-family' 	 : 'fontfamily',
			'font-size'   	 : 'fontsize',
			'font-style'  	 : 'fontstyle',
			'font-weight'  	 : 'fontweight',
			'line-height'  	 : 'lineheight',
			'letter-spacing' : 'letterspacing',
			'text-align'  	 : 'textalign'
		};
		
		for( var style in prop_h ) {
			
			for( var i = 0; i < headings.length; i++ ) {
				
				var elem  = headings[i];
				var value = $( '#unBodyTable' ).find( elem + ':first' ).css( style );
				
				if( typeof value == 'undefined' ) {
					$( '#unBodyTable' ).append( '<'+elem+' style="display:none;">...</'+elem+'>' );
					value = $( '#unBodyTable' ).find( elem + ':first' ).css( style );
					
					$( '#unBodyTable' ).find( elem ).remove();
				}
				
				var id    = 'unb-options-pagedesign-' + elem + '-' + prop_h[ style ];
					
				unb_set_field_values( value, id, style );
				
			}
			
		}
		
		$( '.unb-options-headings-trigger' ).on( 'change click', function() {
																	  
			for( var style in prop_h ) {
				
				for( var i = 0; i < headings.length; i++ ) {
					
					var elem  = headings[i];
					var id    = 'unb-options-pagedesign-' + elem + '-' + prop_h[ style ];
					var value = $( '#'+id ).val();
					
					if( value == '' ) $( '#unBodyTable' ).find( elem ).css( style, '' );
					if( value )	$( '#unBodyTable' ).find( elem ).css( style, value );
				}
			
			}
			
		});
		
		// [Preheader, Header, Body, Footer] set background color, padding top, padding bottom styles
		var sections = {
			'unTemplatePreheader' : 'preheaderdesign',
			'unTemplateHeader'    : 'headerdesign',
			'unTemplateBody'      : 'bodydesign',
			'unTemplateFooter'    : 'footerdesign'
		};
		
		var phbf = {
			'background-color' 	   : 'bgcolor',
			'border-top-width'	   : 'bdrtopwid',
			'border-top-style'	   : 'bdrtopstyle',
			'border-top-color'	   : 'bdrtopcolor',
			'border-bottom-width'  : 'bdrbottomwid',
			'border-bottom-style'  : 'bdrbottomstyle',
			'border-bottom-color'  : 'bdrbottomcolor',
			'padding-top'          : 'padtop',
			'padding-bottom'       : 'padbottom'
		}
		
		for( var style in phbf ) {
			
			for( var key in sections ) {
				
				var value = $( '#'+key ).css( style );
				
				var id    = 'unb-options-' + sections[ key ] + '-' + phbf[ style ];
					
				unb_set_field_values( value, id, style );
				
			}
			
		}
		
		$( '.unb-options-phbf-trigger' ).on( 'change click', function() {
																	  
			for( var style in phbf ) {
				
				for( var key in sections ) {
					
					var id    = 'unb-options-' + sections[ key ] + '-' + phbf[ style ];
					var value = $( '#'+id ).val();
					
					if( '' == value ) $( '#'+key ).css( style, '' );
					var selector = id.split('-');
				
					if( value )	$( '#'+key ).css( style, value );
					
					if( '' == $('#unb-options-'+selector[2]+'-bdrtopcolor').val() ) {
						
						$( '#unb-options-'+selector[2]+'-bdrtopstyle' ).val('none');
						$( '#unb-options-'+selector[2]+'-bdrtopwid' ).val('0px');
						$( '#unb-options-'+selector[2]+'-bdrtopcolor' ).val('#808080');
				
					} else if( '' == $('#unb-options-'+selector[2]+'-bdrbottomcolor').val() ) {
						
						$( '#unb-options-'+selector[2]+'-bdrbottomstyle' ).val('none');
						$( '#unb-options-'+selector[2]+'-bdrbottomwid' ).val('0px');
						$( '#unb-options-'+selector[2]+'-bdrbottomcolor' ).val('#808080');
						
					};
				}
			
			}
			
		});
		
		
		// [Preheader, Header, Body, Footer] text styles
		var phbf_t = {
			'color'       : 'txtcolor',
			'font-family' : 'fontfamily',
			'font-size'   : 'fontsize',
			'line-height' : 'lineheight',
			'text-align'  : 'textalign'
		}
		
		for( var style in phbf_t ) {
			
			for( var key in sections ) {
				
				var value = $( '#'+key ).find('.unTextContent:first').css( style );	
				
				if( typeof value == 'undefined' ) {
					$( '#'+key ).append( '<div class="unTextContent" style="display:none;"></div>' );
					value = $( '#'+key ).find('.unTextContent:first').css( style );
					
					$( '#'+key ).find('div.unTextContent').remove();
				}
				
				var id = 'unb-options-' + sections[ key ] + '-' + phbf_t[ style ];
				
				unb_set_field_values( value, id, style );
				
			}
			
		}
		
		$( '.unb-options-phbftxt-trigger' ).on( 'change click', function() {
																	  
			for( var style in phbf_t ) {
				
				for( var key in sections ) {
					
					var id    = 'unb-options-' + sections[ key ] + '-' + phbf_t[ style ];
					var value = $( '#'+id ).val();
					
					$( '#'+key ).find( ".unTextContent, .unTextContent p" ).css( style, value || '' );
					
				}
			
			}
			
		});
		
		// [Preheader, Header, Body, Footer] link styles
		var phbf_l = {
			'color'           : 'linkcolor',
			'font-weight'     : 'fontweight',
			'text-decoration' : 'txtdecoration'
		}
		
		for( var style in phbf_l ) {
			
			for( var key in sections ) {
				
				var value = $( '#'+key ).find( '.unTextContent:first' ).find( 'a:first' ).css( style );
				
				if( typeof value == 'undefined' ) {
					$( '#'+key ).append( '<div class="unTextContent" style="display:none;"><a href="#">...</a></div>' );
					value = $( '#'+key ).find( '.unTextContent:first' ).find( 'a:first' ).css( style );
					
					$( '#'+key ).find( 'div.unTextContent' ).remove();
				}
				
				var id = 'unb-options-' + sections[ key ] + '-' + phbf_l[ style ];
					
				unb_set_field_values( value, id, style );
				
			}
			
		}
		
		$( '.unb-options-phbflink-trigger' ).on( 'change click', function() {
																   
			for( var style in phbf_l ) {
			
				for( var key in sections ) {
				
					var id    = 'unb-options-' + sections[ key ] + '-' + phbf_l[ style ];
					var value = $( '#'+id ).val();
					
					if( '' == value ) $( '#'+key ).find( ".unTextContent a, .unTextContent p a" ).css( style, '' );
					if( value )	$( '#'+key ).find( ".unTextContent a, .unTextContent p a" ).css( style, value );
				
				}
			
			}	
		
		});
		
		// [Tab-content] Upload Image
        $( '#unb-options-image-upload' ).on( 'click', function( e ) {
 
            e.preventDefault(); 
            unb_render_media_uploader( 'image' );
 
        });
		
		// [Tab-content]
		$( '#unb-template' ).on( 'click', '.unb-toolset-button-edit', function( event ) { 
			
			$("html, body").animate({ scrollTop: 0 }, "fast");
			
			$("#unb-options-tab-content").removeClass( 'un-hide' );
			$("#unb-options-tab-design").addClass( 'un-hide' );
			
			var tabid = $("#unb-options").find(".nav-tab-wrapper").find('a').attr('href');
			
			$("#unb-options").find(".nav-tab-wrapper").find('a:eq(1)').removeClass('nav-tab-active');
			$("#unb-options").find(".nav-tab-wrapper").find('a:eq(0)').addClass('nav-tab-active');
			
			$( "#unb-cpanel, .unb-options-group" ).hide();
			$( '.unb-content-wrapper.active' ).removeClass( 'active' );
			
			$( "#unb-options-content, .unb-options-save, .unb-options-cancel" ).show();
			
			var $active_elem = $( this ).closest( '.unb-content-wrapper' );			
			$active_elem.addClass( "active" );   
			
			var $content_elem = $active_elem.find( 'table:first' );
			
			// If Text Block
			if( $content_elem.hasClass( 'unTextBlock' ) ) {
				
				// Copy content from template
				var content = $content_elem.find( '.unTextContent' ).html();
				tinyMCE.get( 'unb-options-text-editor' ).setContent( content );
				
				$( "#unb-options-text" ).show();
				
				// Copy content from editor
				tinyMCE.get( 'unb-options-text-editor' ).on( 'change', function() {

					var html = $( '#unb-text-block' ).html();
					
					$( '#unb-temp-elem' ).html( html );
					
					var content = tinyMCE.get( 'unb-options-text-editor' ).getContent();
					$( '#unb-temp-elem' ).find( '.unTextContent' ).html( content );
					
					$( '.unb-content-wrapper.active' ).find( '.unTextBlock' ).replaceWith( $( '#unb-temp-elem' ).html() );
					$( '#unb-temp-elem' ).html( '' );
			
					tinymce.triggerSave();
			
				});
				
			};
			
			// If Image Block
			if( $content_elem.hasClass( 'unImageBlock' ) ) {
				
				// Copy image from template
				var $img_element = $content_elem.find( '.unImageContent' ).find( "img" );

				$( '#unb-options-image-url' ).val( $img_element.attr('src') );
				$( "#unb-options-image-alt" ).val( $img_element.attr('alt') );
				$( "#unb-options-image-position" ).val( $img_element.attr('align') );		
				
				$( '#unb-options-image-link' ).val( $content_elem.find( '.unImageContent a' ).attr( 'href' ) );
				
				var edgeTo = $content_elem.find( '.unImageBlockInner' ).css( 'padding' );				
				if( edgeTo == '0px' ){
					$( "#unb-options-image-margin" ).prop( "checked", true );
				} else {
					$( "#unb-options-image-margin" ).prop( "checked", false );
				};
				
				$( "#unb-options-image" ).show();
				
			};
			
			// If Button Block
			if( $content_elem.hasClass( 'unButtonBlock' ) ) {
				
				// Copy button from template	
				$( "#unb-options-button-fontweight" ).val( $content_elem.find( '.unButton' ).css( 'font-weight' ) );
				$( "#unb-options-button-letterspacing" ).val( $content_elem.find( '.unButton' ).css( 'letter-spacing' ) );
				
				$( "#unb-options-button-fontfamily" ).val( $content_elem.find( '.unButtonContent' ).css( 'font-family' ) );
				$( "#unb-options-button-fontsize" ).val( $content_elem.find( '.unButtonContent' ).css( 'font-size' ) );								
				$( "#unb-options-button-pad" ).val( $content_elem.find( '.unButtonContent' ).css( 'padding' ) );
				
				$( "#unb-options-button-text" ).val( $content_elem.find( '.unButtonContent a' ).text() );
				$( '#unb-options-button-link' ).val( $content_elem.find( '.unButtonContent a' ).attr( 'href' ) );
				
				var txtcolor = $content_elem.find( '.unButtonContent a' ).css( 'color' );
				$( '#unb-options-button-txtcolor' ).val( txtcolor ).iris( 'color', txtcolor );
				
				$( '#unb-options-button-bdrstyle' ).val( $content_elem.find( '.unButtonContentContainer' ).css( 'border-style' ) );
				$( '#unb-options-button-bdr' ).val( $content_elem.find( '.unButtonContentContainer' ).css( 'border-width' ) );
				
				var bdrcolor = $content_elem.find( '.unButtonContentContainer' ).css( 'border-color' );
				$( '#unb-options-button-bdrcolor' ).val( bdrcolor ).iris( 'color', bdrcolor );

				$( "#unb-options-button-radius" ).val( $content_elem.find( '.unButtonContentContainer' ).css( 'border-radius' ) );
				
				var bgcolor = $content_elem.find( '.unButtonContentContainer' ).css( 'background-color' );
				$( '#unb-options-button-bgcolor' ).val( bgcolor ).iris( 'color', bgcolor );
				
				$( "#unb-options-button-position" ).val( $content_elem.find( '.unButtonBlockInner' ).attr( 'align' ) );
				
				if( $content_elem.find( '.unButtonContentContainer' ).attr( 'width' ) == '100%' ) {
					$( '#unb-options-button-pos-size' ).val( 'full' );
				} else {
					$( '#unb-options-button-pos-size' ).val( 'fit' );
				}
				
				$( "#unb-options-button" ).show();
				
			};
			
			// If Divider Block
			if(  $content_elem.hasClass( 'unDividerBlock' ) ) {
				
				var bgcolor = $('.unb-content-wrapper.active').find( ".unDividerBlock" ).css( 'background-color' );
				if( bgcolor == 'rgba(0, 0, 0, 0)' ) {
        			$( '#unb-options-divider-bgcolor' ).val( '' ).iris( 'color', '' );
    			} else {
					var divbgcolor = $('.unb-content-wrapper.active').find( ".unDividerBlock" ).css( 'background-color' );
					$( '#unb-options-divider-bgcolor' ).val( divbgcolor ).iris( 'color', divbgcolor );
				}
				
				$( "#unb-options-divider-padtop" ).val( $content_elem.find( '.unDividerBlockInner' ).css( 'padding-top' ) );
				$( "#unb-options-divider-padbottom" ).val( $content_elem.find( '.unDividerBlockInner' ).css( 'padding-bottom' ) );
				$( "#unb-options-divider-bdrtopstyle" ).val( $content_elem.find( '.unDividerContent' ).css( 'border-top-style' ) );
				$( "#unb-options-divider-bdrtopwid" ).val( $content_elem.find( '.unDividerContent' ).css( 'border-top-width' ) );
				
				var brdtop_color = $content_elem.find( '.unDividerContent' ).css( 'border-top-color' );
				$( "#unb-options-divider-bdrtopcolor" ).val( brdtop_color ).iris( 'color',brdtop_color );
				
				$( "#unb-options-divider" ).show();
				
			};
			
			// If Social Follow
			if(  $content_elem.hasClass( 'unFollowBlock' ) ) {
				
				$content_elem.find('.unFollowContentItem').each(function( e ) {
																		 
					var href = $( this ).find( 'a:first' ).attr( 'href' );	
					var alt  = $( this ).find( '.unFollowTextContent a' ).text();
					
					var option = '';
	
					if( /facebook/.test( href ) ) {
						option = 'facebook';
					} else if( /twitter/.test( href ) ) {
						option = 'twitter';
					} else if( /google/.test( href ) ) {
						option = 'gplus';
					} else if( /linkedin/.test( href ) ) {
						option = 'linkedin';
					}
					
					$( "#unb-options-follow-"+option+"-link" ).val( href );
					$( "#unb-options-follow-"+option+"-alt" ).val( alt );
					
																		 
				});
				
				var bgcolor = $content_elem.find( ".unFollowContent" ).css( 'background-color' );
				if( bgcolor == 'rgba(0, 0, 0, 0)' ) {
        			$( '#unb-options-follow-bgcolor' ).val( '' ).iris( 'color', '' );
    			} else {
					var sfbgcolor = $content_elem.find( ".unFollowContent" ).css( 'background-color' );
					$( '#unb-options-follow-bgcolor' ).val( sfbgcolor ).iris( 'color', sfbgcolor );
				}
				
				$( '#unb-options-follow-bdrtopstyle' ).val( $content_elem.find( '.unFollowContent' ).css( 'border-style' ) );
				$( '#unb-options-follow-bdrtopwid' ).val( $content_elem.find( '.unFollowContent' ).css( 'border-width' ) );
				
				var bdrcolor = $content_elem.find( '.unFollowContent' ).css( 'border-color' );
				$( '#unb-options-follow-bdrtopcolor' ).val(bdrcolor).iris( 'color', bdrcolor );
							
				var text = $content_elem.find( '.unFollowTextContent' ).length;
				var icon = $content_elem.find( '.unFollowIconContent' ).length;
				
				if( text > 0 ) {
					
					var txcolor = $content_elem.find( ".unFollowTextContent a" ).css( 'color' );
					$( "#unb-options-follow-txtcolor" ).val( txcolor ).iris( 'color', txcolor );
					$( "#unb-options-follow-fontfamily" ).val( $content_elem.find( ".unFollowTextContent a" ).css( 'font-family' ) );
					$( "#unb-options-follow-fontsize" ).val( $content_elem.find( ".unFollowTextContent a" ).css( 'font-size' ) );
					$( "#unb-options-follow-fontweight" ).val( $content_elem.find( ".unFollowTextContent a" ).css( 'font-weight' ) );
					$( "#unb-options-follow-txtdecoration" ).val( $content_elem.find( ".unFollowTextContent a" ).css( 'text-decoration' ) );
					
				}
				
				if( text > 0 && icon > 0 ) {
					$( '#unb-options-follow-type' ).val( 'both' );
				} else if( text > 0 ) {
					$( '#unb-options-follow-type' ).val( 'text_only' );
				} else if( icon > 0 ) {
					$( '#unb-options-follow-type' ).val( 'icon_only' );
				}
				
				$( '#unb-options-follow-position' ).val( $content_elem.find( ".unFollowContentContainer" ).attr( 'align' ) );
				
				$content_elem.find( '.unFollowContent' ).attr( 'width' ) == '100%' ? $( '#unb-options-follow-poswidth' ).val( 'full' ) : $( '#unb-options-follow-poswidth' ).val( 'fit' );
				
				$( "#unb-options-follow" ).show();
				
			};
			
		});
		
		// Copy image from editor to template
		$( '.unb-options-image-trigger' ).on( 'blur change', function() {
			
			var html = $( '#unb-image-block' ).html();
			
			$( '#unb-temp-elem' ).html( html );
				
			$( '#unb-temp-elem' ).find( '.unImageContent' ).find( 'img' ).attr({
				src    : $( '#unb-options-image-url' ).val(),
				alt    : $( '#unb-options-image-alt' ).val(),
				align  : $( '#unb-options-image-position' ).val(),
			});
			
			$( '#unb-temp-elem' ).find( '.unImageContent a' ).attr({
				href    : $( '#unb-options-image-link' ).val(),
				target  : '_blank',
				title  	: $( '#unb-options-image-alt' ).val()
			});
			
			$( '#unb-temp-elem' ).find( '.unImageContent a' ).attr( 'href', $( '#unb-options-image-link' ).val() );
				
			if( $( '#unb-options-image-margin' ).is( ':checked' ) ) {
				
				$( '#unb-temp-elem' ).find( ".unImageBlockInner, .unImageContent" ).css({
					'margin'  : '0px',
					'padding' : '0px'
				});
					
			} 
				
			$( '.unb-content-wrapper.active' ).find( '.unImageBlock' ).replaceWith( $( '#unb-temp-elem' ).html() );

			$( '#unb-temp-elem' ).html( '' );
										
		});
		
		// Copy button from editor to template
		$( '.unb-options-button-trigger' ).on( 'blur change click', function() {

			var html = $( '#unb-button-block' ).html();
			
			$( '#unb-temp-elem' ).html( html );
			
			$( '#unb-temp-elem' ).find( ".unButton" ).css({ 
				"font-weight"    : $( "#unb-options-button-fontweight" ).val(),
				"letter-spacing" : $( "#unb-options-button-letterspacing" ).val()
			});
			
			$( '#unb-temp-elem' ).find( ".unButtonContent" ).css({
				"font-family" : $( '#unb-options-button-fontfamily' ).val(),
				"font-size"   : $( "#unb-options-button-fontsize" ).val(),
				"padding"     : $( "#unb-options-button-pad" ).val()
			});
			
			$( '#unb-temp-elem' ).find( '.unButtonContent a' ).text( $( '#unb-options-button-text' ).val() ).attr( 'href', $( '#unb-options-button-link' ).val() ).css( 'color', $( '#unb-options-button-txtcolor' ).val() );
			
			if( '' == $( '#unb-options-button-bdrcolor' ).val() ) {
			
				$( '#unb-options-button-bdrstyle' ).val('none');
				$( '#unb-options-button-bdr' ).val('0px');
				$( '#unb-options-button-bdrcolor' ).val('#808080');
				
			};
			
			$( '#unb-temp-elem' ).find( '.unButtonContentContainer' ).css({
				'background-color' : $( '#unb-options-button-bgcolor' ).val(),
				"border-style"     : $( '#unb-options-button-bdrstyle' ).val(),
				"border-width"     : $( '#unb-options-button-bdr' ).val(),
				"border-color"     : $( '#unb-options-button-bdrcolor' ).val(),
				"border-radius"    : $( '#unb-options-button-radius' ).val()
			});

			if( $( '#unb-options-button-pos-size option:selected' ).val() == 'full' ) {
				$( '#unb-temp-elem' ).find( ".unButtonContentContainer" ).attr( "width","100%" );
				$( '#unb-temp-elem' ).find( ".unButtonBlockInner" ).css( "padding", 0 );
			} else {
				$( '#unb-temp-elem' ).find( ".unButtonContentContainer" ).removeAttr( "width" );
				$( '#unb-temp-elem' ).find( ".unButtonBlockInner" ).attr( "align", $( "#unb-options-button-position" ).val() );
			}
			
			$( '.unb-content-wrapper.active' ).find( '.unButtonBlock' ).replaceWith( $( '#unb-temp-elem' ).html() );
			
			$( '#unb-temp-elem' ).html( '' );
										
		});
		
		// Copy divider from editor to template
		$( '.unb-options-divider-trigger' ).on( 'blur change', function() {
				
			var html = $( '#unb-divider-block' ).html();
			
			$( '#unb-temp-elem' ).html( html );
				
			$( '#unb-temp-elem' ).find( ".unDividerBlockInner" ).css({
				"padding-top"    : $( "#unb-options-divider-padtop" ).val(),
				"padding-bottom" : $( "#unb-options-divider-padbottom" ).val()
			});
			
			if( '' == $( '#unb-options-divider-bdrtopcolor' ).val() ) {
			
				$( '#unb-options-divider-bdrtopstyle' ).val('none');
				$( '#unb-options-divider-bdrtopwid' ).val('0px');
				$( '#unb-options-divider-bdrtopcolor' ).val('#eeeeee');
				
			};
			
			$( '#unb-temp-elem' ).find( '.unDividerContent' ).css({
				"border-top-style" : $( '#unb-options-divider-bdrtopstyle' ).val(),
				"border-top-width" : $( '#unb-options-divider-bdrtopwid' ).val(),
				"border-top-color" : $( '#unb-options-divider-bdrtopcolor' ).val()
			});
				
			$( '#unb-temp-elem' ).find( ".unDividerBlock" ).css( 'background-color', $( '#unb-options-divider-bgcolor' ).val() );
				
			$( '.unb-content-wrapper.active' ).find( '.unDividerBlock' ).replaceWith( $( '#unb-temp-elem' ).html() );
			
			$( '#unb-temp-elem' ).html( '' );
										
		});
		
		// Copy follow from editor to template
		$( '.unb-options-follow-trigger' ).on( 'blur change click', function() {
				
			// Get the list of enabled social options
			var social_options = [];
			
			if( $( '#unb-options-follow-facebook-link' ).val() ) {
				social_options.push('facebook');
			}
			
			if( $( '#unb-options-follow-twitter-link' ).val() ) {
				social_options.push('twitter');
			}
			
			if( $( '#unb-options-follow-gplus-link' ).val() ) {
				social_options.push('gplus');
			}
			
			if( $( '#unb-options-follow-linkedin-link' ).val() ) {
				social_options.push('linkedin');
			}
			
			var display = $( '#unb-options-follow-type' ).val();
		
			var html_string = '';
			
			for( var i = 0; i < social_options.length; i++ ) {
				
				var option = social_options[i];
				var href   = $( '#unb-options-follow-'+option+'-link' ).val();
				var alt    = $( '#unb-options-follow-'+option+'-alt' ).val();
				var html   = $( '#unb-follow-content' ).html();
				
				$( '#unb-temp-elem' ).html( html );				
				
				$( '#unb-temp-elem' ).find( '.unFollowContentItem table tr a' ).attr( 'href', href );
				
				$( '#unb-temp-elem ').find( ".unFollowTextContent a" ).css({
					"color"    		  : $( "#unb-options-follow-txtcolor" ).val(),
					"font-family" 	  : $( "#unb-options-follow-fontfamily" ).val(),
					"font-size" 	  : $( '#unb-options-follow-fontsize' ).val(),
					"font-weight" 	  : $( '#unb-options-follow-fontweight' ).val(),
					"text-decoration" : $( "#unb-options-follow-txtdecoration" ).val()
				});
				
				if( 'both' == display || 'icon_only' == display ) {					
					$( '#unb-temp-elem' ).find( '.unFollowIconContent' ).find( 'img' ).attr( 'src', un.plugin_url+'public/images/'+option+'.png' );
				}

				if( 'both' == display || 'text_only' == display ) {
					if( '' == alt ) alt = href; 
					$( '#unb-temp-elem' ).find( '.unFollowTextContent' ).find( 'a' ).text( alt );
				}
				
				if( 'text_only' == display ) $( '#unb-temp-elem' ).find( '.unFollowIconContent' ).remove();				
				if( 'icon_only' == display ) $( '#unb-temp-elem' ).find( '.unFollowTextContent' ).remove();
				
				html_string += $( '#unb-temp-elem' ).html();
				
				// Empty the temp element
				$( '#unb-temp-elem' ).html( '' );
				
			}
			
			// Add Final HTML output to the main active element		
			var html = $( '#unb-follow-block' ).html();
			html = html.replace( '[UN_FOLLOW_CONTENT]', html_string );
			
			$( '#unb-temp-elem' ).html( html );
			
			if( '' == $( '#unb-options-follow-bdrtopcolor' ).val() ) {
			
				$( '#unb-options-follow-bdrtopstyle' ).val('none');
				$( '#unb-options-follow-bdrtopwid' ).val('0px');
				$( '#unb-options-follow-bdrtopcolor' ).val('#808080');
				
			};
			
			$( '#unb-temp-elem' ).find( '.unFollowContent' ).css({
				'background-color' : $( '#unb-options-follow-bgcolor' ).val(),
				"border-style"     : $( '#unb-options-follow-bdrtopstyle' ).val(),
				"border-width"     : $( '#unb-options-follow-bdrtopwid' ).val(),
				"border-color"     : $( '#unb-options-follow-bdrtopcolor' ).val()
			});
			
			if( 'full' == $( '#unb-options-follow-poswidth option:selected' ).val() ) {
				$( '#unb-temp-elem' ).find( ".unFollowContent" ).attr( "width","100%" ).css( "min-width", "100%" );
			} else {
				$( '#unb-temp-elem' ).find( ".unFollowContent" ).removeAttr( "width" ).css( "min-width", "" );
			};
			
			$( '#unb-temp-elem' ).find( ".unFollowContentContainer" ).attr( "align", $( "#unb-options-follow-position" ).val() );
			$( '#unb-temp-elem' ).find( ".unFollowContentContainer" ).find( 'table, td' ).attr( "align", $( "#unb-options-follow-position" ).val() );
			
			$( '.unb-content-wrapper.active' ).find( '.unFollowBlock' ).replaceWith( $( '#unb-temp-elem' ).html() );

			$( '#unb-temp-elem' ).html( '' );
				
		});
		
		// [Tab-content] Save options
		$( ".unb-options-save, .unb-options-cancel" ).on( 'click', function() {
									
			unb_reset_options_window();
		
		});	
		
		// Clone
		$( '#unb-template' ).on( 'click', '.unb-toolset-button-copy', function( event ) { 
			
			$( ".unb-content-wrapper.hover" ).clone( true ).removeClass( 'hover' ).insertAfter( ".unb-content-wrapper.hover" );
			
			unb_reset_options_window();
			
		});
		
		// Delete
		$( '#unb-template' ).on( 'click', '.unb-toolset-button-delete', function( event ) { 
						
			var $empty_elem   = $( this ).closest( '.unTemplateContainer' ).find( ".unb-sort-placeholder" ),
				$content_elem = $( this ).closest( '.unTemplateContainer' ).find( ".unb-content-wrapper" ).length;
				
			$( '.unb-content-wrapper.hover' ).remove();
			
			( $content_elem > 1 ) ? $empty_elem.hide() : $empty_elem.show();
			
			unb_reset_options_window();
			
		});
		
		// Submit Builder form
		$( "#un-builder-form" ).submit(function(){
										   
			$( '.unb-content-wrapper' ).each(function() {
				var content = $( this ).find( '.unb-content-container' ).html();
				$( this ).replaceWith( content );
				$('.unb-sort-placeholder').remove();
				
			});
			
			var html = $( "#unTemplate" ).prop('outerHTML');
			$( "#un-post-content" ).val( html ); 
			
			return true;
			
		});
		
		/*********************************************************************************
		** Builder [END] **
		*********************************************************************************/
		
		$( 'input[name="email_groups[]"' ).on( 'click', function( event ) { 
			
			var $cbx_group = $( 'input:checkbox[name="email_groups[]"]' );
			$cbx_group.prop( 'required', true );
			if( $cbx_group.is( ":checked" ) ) {
  				$cbx_group.prop( 'required', false );
			}
			
		});
									
		// Show/Hide CSV import tool based on the "Type"
		$( 'input[type="radio"][name="type"]', '#un-subscribers-import-form' ).on( 'change', function() {
		
			if( 'csv' == this.value ) {
				$( '#un-import-row-editor' ).hide();
				$( '#un-import-row-csv' ).show();
				$( '#un-import-action' ).val( 'import-list-data' );
				$( '#un-import-submit' ).val( un.continue_i18n );
			} else {
				$( '#un-import-row-csv' ).hide();
				$( '#un-import-row-editor' ).show();
				$( '#un-import-action' ).val( 'import-save-data' );
				$( '#un-import-submit' ).val( un.import_subscribers_i18n );
			}
																									  
		});
		
		// Upload CSV
        $( '#un-csv-upload' ).on( 'click', function( e ) {
 
            e.preventDefault(); 
            unb_render_media_uploader( 'csv' );
 
        });
		
		// Validate import data field
		$( '#un-import-editor' ).on( 'blur', function() {
		
			if( '' != $( '#un-import-editor' ).val() ) {
				$( '#un-import-row-editor').removeClass( 'un-invalid' );
			} else {
				$( '#un-import-row-editor' ).addClass( 'un-invalid' );
			}
			
		});
		
		// Validate email groups
		$( '.un-email-groups' ).on( 'change', function() { 
						
			var email_groups_found = false;
			
			$( '.un-email-groups' ).each(function() {
				if( this.checked ) {
					email_groups_found = true;
					return false;
				}
			});
			
			if( ! email_groups_found ) {
				$( '.un-import-emailgroups, .un-export-emailgroups' ).addClass( 'un-invalid' );
			} else {
				$( '.un-import-emailgroups, .un-export-emailgroups' ).removeClass( 'un-invalid' );
			}
				
		});
		
		// Validate email column
		$( '.un-import-column' ).on( 'change', function() { 
						
			var email_column_found = false;
			
			$( '.un-import-column' ).each(function() {
				if( 'email' == $( this ).val() ) {
					email_column_found = true;
					return false;
				}
			});
			
			if( ! email_column_found ) {
				$( '.un-import-column' ).addClass( 'un-invalid' );
			} else {
				$( '.un-import-column' ).removeClass( 'un-invalid' );
			}
				
		});
		
		// On Import form submit
		$( '#un-subscribers-import-form' ).on( 'submit', function () {
								
			// Make sure there is data to import
			var type = $( 'input[name="type"]:checked' ).val();
			var data_found = false;
			
			if( 'csv' == type ) {
				
				if( '' != $( '#un-import-csv' ).val() ) {
					data_found = true;
				} else {
					$( '#un-import-row-csv' ).addClass( 'un-invalid' );
				}
				
			} else {
				
				if( '' != $( '#un-import-editor' ).val() ) {
					data_found = true;
				}else {
					$( '#un-import-row-editor' ).addClass( 'un-invalid' );
				}
				
			}
			
			if( ! data_found ) {				
				alert( un.required_import_data_i18n );
				return false;
			}
			
			// Make sure that atleast one email group selected
			var email_groups_found = false;
			
			$( '.un-email-groups' ).each(function() { 
				if( this.checked ) {
					email_groups_found = true;
					return false;
				}
			});
			
			if( ! email_groups_found ) {
				$( '.un-import-emailgroups, .un-export-emailgroups' ).addClass( 'un-invalid' );
				alert( un.required_email_groups_i18n );
				return false;
			}			
																   
		});
		
		$( '#un-subscribers-import-list-form' ).on( 'submit', function () {
																   
			// Make sure an email column is added
			var email_column_found = false;
			
			$( '.un-import-column' ).each(function() { 
				if( 'email' == $( this ).val() ) {
					email_column_found = true;
					return false;
				}
			});
			
			if( ! email_column_found ) {
				$( '.un-import-column' ).addClass( 'un-invalid' );
				alert( un.required_email_column_i18n );
				return false;
			}
																   
		});
		
		// Validate export fields
		$( '.un-export-fields' ).on( 'change', function() { 
						
			var export_fields_found = false;
			
			$( '.un-export-fields' ).each(function() { 
				if( this.checked ) {
					export_fields_found = true;
					return false;
				}
			});
			
			if( ! export_fields_found ) {
				$( '.un-export-fields-group' ).addClass( 'un-invalid' );
			} else {
				$( '.un-export-fields-group' ).removeClass( 'un-invalid' );
			}
				
		});
		
		// On Export form submit
		$( '#un-subscribers-export-form' ).on( 'submit', function () {
																   
			// Make sure that atleast one email group selected
			var email_groups_found = false;
			
			$( '.un-email-groups' ).each(function() { 
				if( this.checked ) {
					email_groups_found = true;
					return false;
				}
			});
			
			if( ! email_groups_found ) {
				$( '.un-import-emailgroups, .un-export-emailgroups' ).addClass( 'un-invalid' );
				alert( un.required_email_groups_i18n );
				return false;
			}
			
			// Make sure that atleast one field is selected to export
			var export_fields_found = false;
			
			$( '.un-export-fields' ).each(function() { 
				if( this.checked ) {
					export_fields_found = true;
					return false;
				}
			});
			
			if( ! export_fields_found ) {
				$( '.un-export-fields-group' ).addClass( 'un-invalid' );
				alert( un.required_export_fields_i18n );
				return false;
			}
																   
		});
		
	});

})( jQuery );