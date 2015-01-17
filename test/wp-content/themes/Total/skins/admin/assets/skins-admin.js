(function($) {
	"use strict";
	
	$( '.wpex-skin' ).click( function() {
		$( '.wpex-skin' ).removeClass( 'active' );
		$(this).addClass( 'active' );
		var radio = $(this).find( '.wpex-skin-radio' );
		radio.prop("checked", true);
		$( '#wpex-hidden-skin-val' ).val( radio.val() );
		event.preventDefault();
	} );

	$( '#wpex-delete-mods' ).change( function() {
		if( this.checked ) {
			$( '#wpex-delete-mods-confirm' ).show();
		} else {
			$( '#wpex-delete-mods-confirm' ).hide();
		}
	} );

} ) ( jQuery );