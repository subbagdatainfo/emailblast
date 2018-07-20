(function( $ ) {
	'use strict';

	// On Document Ready
	$(function() {
			   
		// Handle required fields
		$( ".un-required" ).on( 'blur', function( e ) {
						
			if( 0 === $( this ).val().length ) {
				$( this ).addClass( 'un-field-error' );
			} else {
				$( this ).removeClass( 'un-field-error' );
			};
																									 
		});
		
		// Handle form submits
		$( '.un-subscription-form' ).on( 'submit', function( e ) {
															
			e.preventDefault();
			e.stopImmediatePropagation();
			
			var $this = $( this );			
			$this.find( '.un-response' ).addClass( 'un-spinner' ).removeClass( 'un-text-success un-text-error' ).html( '' );
			
			var error = 0;

			var data = {
				'action'       : 'un_add_subscriber',
				'email'        : $this.find( '.un-email' ).val(),
				'email_groups' : $this.find( '.un-email-groups' ).val()
			};
			
			if( '' == data.email ) {
				error = 1;
				$this.find( '.un-email' ).addClass( 'un-field-error' );
			}
			
			if( $( this ).find( '.un-name' ).length ) {
				data.name = $this.find( '.un-name' ).val();
				
				if( '' == data.name ) {
					error = 1;
					$this.find( '.un-name' ).addClass( 'un-field-error' );
				}
			}
			
			if( error ) return false;			
			
			$this.find( "input[type='submit']" ).attr( "disabled", "disabled" );

			$.post( un.ajax_url, data, function( response ) {
						
				$this.find( '.un-response' ).removeClass( 'un-spinner' );
				$this.find( "input[type='submit']" ).attr( "disabled", false );
				
				if( 1 == response.error ) {					
					$this.find( '.un-response' ).removeClass( 'un-text-success' ).addClass( 'un-text-error' ).html( response.message );
				} else {
					$this.find( '.un-name' ).val( '' );
					$this.find( '.un-email' ).val( '' );
					$this.find( '.un-response' ).removeClass( 'un-text-error' ).addClass( 'un-text-success' ).html( response.message );
				};
				
			}, 'json' );
			
		});   
			   
	});
	
})( jQuery );
