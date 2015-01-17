( function( $ ) {
	"use strict";

	// Tabs
	$( 'div#wpex-metabox ul.wp-tab-bar a').click( function() {
		var lis = $( '#wpex-metabox ul.wp-tab-bar li' ),
			data = $( this ).data( 'tab' ),
			tabs = $( '#wpex-metabox div.wp-tab-panel');
		$( lis ).removeClass( 'wp-tab-active' );
		$( tabs ).hide();
		$( data ).show();
		$( this ).parent( 'li' ).addClass( 'wp-tab-active' );
		return false;
	} );

	// Color picker
	$('div#wpex-metabox .wpex-mb-color-field').wpColorPicker();

	// Media uploader
	var _custom_media		= true,
	_orig_send_attachment	= wp.media.editor.send.attachment;

	$('div#wpex-metabox .wpex-mb-uploader').click(function(e) {
		var send_attachment_bkp	= wp.media.editor.send.attachment,
			button				= $(this),
			id					= button.prev();
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$( id ).val( attachment.url );
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
		wp.media.editor.open( button );
		return false;
	} );

	$( 'div#wpex-metabox .add_media' ).on('click', function() {
		_custom_media = false;
	} );

	// Reset
	$( 'div#wpex-metabox div.wpex-mb-reset a.wpex-reset-btn' ).click( function() {
		var confirm = $( 'div.wpex-mb-reset div.wpex-reset-checkbox' ),
			txt = confirm.is(':visible') ? wpexMetabox.reset : wpexMetabox.cancel;
		$( this ).text( txt );
		$( 'div.wpex-mb-reset div.wpex-reset-checkbox input' ).attr('checked', false);
		confirm.toggle();
	});

	$( document ).ready( function() {

		// Show hide title options
		( function wpexTitleSettings() {
			var field					= $( 'div#wpex-metabox select#wpex_post_title_style' ),
				value					= field.val(),
				backgroundImageElements	= $( '#wpex_post_title_background_color_tr, #wpex_post_title_background_redux_tr,#wpex_post_title_height_tr,#wpex_post_title_background_overlay_tr,#wpex_post_title_background_overlay_opacity_tr'),
				solidColorElements		= $( '#wpex_post_title_background_color_tr');
			if ( value === 'background-image' ) {
				backgroundImageElements.show();
			} else if ( value === 'solid-color' ) {
				solidColorElements.show();
			}
			field.change(function () {
				backgroundImageElements.hide();
				if ( $(this).val() == 'background-image' ) {
					backgroundImageElements.show();
				}
				else if ( $(this).val() === 'solid-color' ) {
					solidColorElements.show();
				}
			} );
		} ) ();

	} );

} ) ( jQuery );  