(function($) {
	"use strict";

	// Select & insert image
	var _custom_media		= true,
	_orig_send_attachment	= wp.media.editor.send.attachment;

	$('.wpex-media-upload-button').click(function(e) {
		var send_attachment_bkp	= wp.media.editor.send.attachment,
			button				= $(this),
			id					= button.prev();
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$( id ).val( attachment.url );
				var preview = button.parent().find('.wpex-media-live-preview img');
				if ( preview.length ) {
					preview.attr( 'src', attachment.url );
				}
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
		wp.media.editor.open( button );
		return false;
	} );

	$( '.add_media' ).on('click', function() {
		_custom_media = false;
	} );

} ) ( jQuery );