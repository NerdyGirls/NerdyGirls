<?php
/**
 * Add more buttons to the MCE editor
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only needed in the admin
if( ! is_admin() ) {
	return;
}

// Enable font size buttons in the editor
if ( ! function_exists( 'wpex_mce_buttons' ) ) {
	function wpex_mce_buttons( $buttons ) {
		array_unshift( $buttons, 'fontselect' );
		array_unshift( $buttons, 'fontsizeselect' );
		return $buttons;
	}
}
add_filter( 'mce_buttons_2', 'wpex_mce_buttons' );

// Customize mce editor font sizes
if ( ! function_exists( 'wpex_customize_text_sizes' ) ) {
	function wpex_customize_text_sizes( $initArray ){
		$initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
		return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'wpex_customize_text_sizes' );

// Add "Styles" / "Formats" (3.9+) drop-down
if ( ! function_exists( 'wpex_style_select' ) ) {
	function wpex_style_select( $buttons ) {
		array_push( $buttons, 'styleselect' );
		return $buttons;
	}
}
add_filter( 'mce_buttons', 'wpex_style_select' );

// Add "Styles" drop-down content or classes 
if ( ! function_exists( 'wpex_styles_dropdown' ) ) {
	function wpex_styles_dropdown( $settings ) {

		// New items
		$items = array(
			array(
				'title'		=> __( 'Theme Button', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'theme-button',
			),
			array(
				'title'		=> __( 'Highlight', 'wpex' ),
				'inline'	=> 'span',
				'classes'	=> 'text-highlight',
			),
			array(
				'title'		=> __( 'Thin Font', 'wpex' ),
				'inline'	=> 'span',
				'classes'	=> 'thin-font'
			),
			array(
				'title'		=> __( 'White Text', 'wpex' ),
				'inline'	=> 'span',
				'classes'	=> 'white-text'
			),
		);

		$items = apply_filters( 'wpex_tiny_mce_formats_items', $items );

		$color_buttons = array(
			array(
				'title'		=> __( 'Blue', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button blue',
			),
			array(
				'title'		=> __( 'Black', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button black',
			),
			array(
				'title'		=> __( 'Red', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button red',
			),
			array(
				'title'		=> __( 'Orange', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button orange',
			),
			array(
				'title'		=> __( 'Green', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button green',
			),
			array(
				'title'		=> __( 'Gold', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button gold',
			),
			array(
				'title'		=> __( 'Teal', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button teal',
			),
			array(
				'title'		=> __( 'Purple', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button purple',
			),
			array(
				'title'		=> __( 'Pink', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button pink',
			),
			array(
				'title'		=> __( 'Brown', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button brown',
			),
			array(
				'title'		=> __( 'Rosy', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button rosy',
			),
			array(
				'title'		=> __( 'White', 'wpex' ),
				'selector'	=> 'a',
				'classes'	=> 'color-button white',
			),
		);

		// Create array of formats
		$new_formats = array(
			// Total Buttons
			array(
				'title'	=> WPEX_THEME_BRANDING .' '. __( 'Styles', 'wpex' ),
				'items'	=> $items,
			),
			array(
				'title'	=>  __( 'Color Buttons', 'wpex' ),
				'items'	=> $color_buttons,
			),
		);

		// Merge Formats
		$settings['style_formats_merge'] = true;

		// Add new formats
		$settings['style_formats'] = json_encode( $new_formats );

		// Return New Settings
		return $settings;

	}
}
add_filter('tiny_mce_before_init', 'wpex_styles_dropdown');