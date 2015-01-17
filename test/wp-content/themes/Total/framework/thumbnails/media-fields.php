<?php
/**
 * Adds new fields for the media items
 *
 *
 * @package Total
 * @subpackage Functions
 * @author Alexander Clarke
 * @copyright Copyright (c) 2014, Symple Workz LLC
 * @link http://www.wpexplorer.com
 * @since Total 1.0
 */

/**
 * Adds new custom fields to image media
 *
 * @since Total 1.53
 */
if ( !function_exists( 'wpex_custom_attachment_fields' ) ) {
	function wpex_custom_attachment_fields( $form_fields, $post ) {
		$form_fields["wpex_video_url"] = array(
			"label"	=> __( "Video URL", "wpex" ),
			"input"	=> "text",
			"value"	=> get_post_meta( $post->ID, "_video_url", true ),
		);
	 
	   return $form_fields;
	}
}
add_filter( "attachment_fields_to_edit", "wpex_custom_attachment_fields", null, 2 );

/**
 * Save new attachment fields
 *
 * @since Total 1.53
 */
if ( !function_exists( 'wpex_custom_attachment_fields_to_save' ) ) {
	function wpex_custom_attachment_fields_to_save( $post, $attachment ) {
		if( isset( $attachment['wpex_video_url'] ) ){
			update_post_meta( $post['ID'], '_video_url', $attachment['wpex_video_url'] );
		}
		return $post;
	}
}
add_filter( "attachment_fields_to_save", "wpex_custom_attachment_fields_to_save", null , 2 );