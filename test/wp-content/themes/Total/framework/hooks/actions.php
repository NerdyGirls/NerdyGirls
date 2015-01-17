<?php
/**
 * Adds theme functions to hooks
 *
 * The following functions run certain functions in their corresponding hooks.
 * For example the header logo runs in the wpex_hook_header_inner hook.
 * You can copy and paste any of these functions into your child theme to change the
 * order of the displayed elements or remove any - have fun!
 *
 * @package     Total
 * @subpackage  Framework/Hooks
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.1.0
 */

/**
 * Returns functions for use in the before header hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_header_before_default' ) ) {
	function wpex_hook_header_before_default() {
		$slider_position = wpex_post_slider_position();
		// Toggle Bar
		wpex_toggle_bar_btn();
		// Above top Bar slider
		if ( 'above_topbar' == $slider_position ) {
			wpex_post_slider();
		}
		// Top bar
		wpex_top_bar();
		// Above header slider
		if ( 'above_header' == $slider_position ) {
			wpex_post_slider();
		}
	}
}
add_action( 'wpex_hook_header_before', 'wpex_hook_header_before_default' );

/**
 * Returns functions for use in the inner header hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_header_inner_default' ) ) {
	function wpex_hook_header_inner_default() {
		// Header logo
		wpex_header_logo();
		// Header aside content - used for styles 2/3
		wpex_header_aside();
		// Header menu for header style 1
		if ( 'one' == wpex_get_header_style() ) {
			wpex_header_menu();
		}
		// Mobile menu
		wpex_mobile_menu();
	}
}
add_action( 'wpex_hook_header_inner', 'wpex_hook_header_inner_default' );

/**
 * Returns functions for use in the header bottom hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_header_bottom_default' ) ) {
	function wpex_hook_header_bottom_default() {
		// Header menu for header styles 2 or 3
		$header_style = wpex_get_header_style();
		if ( $header_style == 'two' || $header_style == 'three' ) {
			// Above menu slider
			if ( 'above_menu' == wpex_post_slider_position() ) {
				wpex_post_slider();
			}
			wpex_header_menu();
		}
	}
}
add_action( 'wpex_hook_header_bottom', 'wpex_hook_header_bottom_default' );

/**
 * Returns functions for use in the main top hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_main_top_default' ) ) {
	function wpex_hook_main_top_default() {
		$slider_position = wpex_post_slider_position();
		// Above title slider
		if ( 'above_title' == $slider_position ) {
			wpex_post_slider();
		}
		// Page title/header
		wpex_display_page_header();
		// Below title/header slider
		if ( 'below_title' == $slider_position ) {
			wpex_post_slider();
		}
	}
}
add_action( 'wpex_hook_main_top', 'wpex_hook_main_top_default' );

/**
 * Returns functions for use in the sidebar inner hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_sidebar_inner_default' ) ) {
	function wpex_hook_sidebar_inner_default() {
		// Display dynamic sidebar: see functions/core-functions.php
		dynamic_sidebar( wpex_get_sidebar() );
	}
}
add_action( 'wpex_hook_sidebar_inner', 'wpex_hook_sidebar_inner_default' );

/**
 * Returns functions for use in the before footer hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_footer_before_default' ) ) {
	function wpex_hook_footer_before_default() {
		wpex_footer_callout();
	}
}
add_action( 'wpex_hook_footer_before', 'wpex_hook_footer_before_default' );

/**
 * Returns functions for use in the footer inner hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_footer_inner_default' ) ) {
	function wpex_hook_footer_inner_default() {
		wpex_footer_widgets();
	}
}
add_action( 'wpex_hook_footer_inner', 'wpex_hook_footer_inner_default' );

/**
 * Returns functions for use in the footer afer hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_footer_after_default' ) ) {
	function wpex_hook_footer_after_default() {
		wpex_footer_bottom();
	}
}
add_action( 'wpex_hook_footer_after', 'wpex_hook_footer_after_default' );

/**
 * Returns functions for use in the wrap after hook
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_hook_wrap_after_default' ) ) {
	function wpex_hook_wrap_after_default() {
		wpex_toggle_bar();
	}
}
add_action( 'wpex_hook_wrap_after', 'wpex_hook_wrap_after_default' );