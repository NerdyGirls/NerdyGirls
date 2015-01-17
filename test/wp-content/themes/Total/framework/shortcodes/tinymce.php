<?php
/**
 * Add TinyMCE button for Total Shortcodes
 *
 * @package		Total
 * @subpackage	Framework/Core
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link 		http://www.wpexplorer.com
 * @since		Total 1.3.6
 */

// Adds button to mce
if ( ! function_exists( 'total_shortcodes_add_mce_button' ) ) {
	function total_shortcodes_add_mce_button() {
		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
			return;
		}
		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', 'total_shortcodes_add_tinymce_plugin' );
			add_filter( 'mce_buttons', 'total_shortcodes_register_mce_button' );
		}
	}
}
add_action('admin_head', 'total_shortcodes_add_mce_button');

// Loads js for the Button
if ( ! function_exists( 'total_shortcodes_add_tinymce_plugin' ) ) {
	function total_shortcodes_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['total_shortcodes_mce_button'] = WPEX_FRAMEWORK_DIR_URI .'shortcodes/tinymce.js';
		return $plugin_array;
	}
}

// Registers new button
if ( ! function_exists( 'total_shortcodes_register_mce_button' ) ) {
	function total_shortcodes_register_mce_button( $buttons ) {
		array_push( $buttons, 'total_shortcodes_mce_button' );
		return $buttons;
	}
}