<?php
/**
 * Header Output
 *
 * @package		Total
 * @subpackage	Framework/Header
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.5.3
 */

/**
 * Whether the header should display or not
 *
 * @since	Total 1.5.3
 * @return	bool
 */
if ( ! function_exists( 'wpex_display_header' ) ) {
	function wpex_display_header( $return = true ) {
		if ( ( $post_id = wpex_get_the_id() ) && 'on' == get_post_meta( $post_id, 'wpex_disable_header', true ) ) {
			$return = false;
		}
		return apply_filters( 'wpex_display_header', $return );
	}
}

/**
 * Get correct header style
 *
 * @since	Total 1.5.3
 * @return	bool
 */
if ( ! function_exists( 'wpex_get_header_style' ) ) {
	function wpex_get_header_style( $post_id = '' ) {
		$post_id	= $post_id ? $post_id : wpex_get_the_id();
		$style		= get_theme_mod( 'header_style', 'one' );
		if ( $post_id && $meta	= get_post_meta( $post_id, 'wpex_header_style', true ) ) {
			$style	= $meta;
		}
		$style = $style ? $style : 'one';
		apply_filters( 'wpex_header_style', $style );
		return $style;
	}
}

/**
 * Check if the header overlay style is enabled
 *
 * @since	Total 1.5.3
 * @return	bool
 */
if ( ! function_exists( 'wpex_is_overlay_header_enabled' ) ) { 
	function wpex_is_overlay_header_enabled( $post_id = '' ) {

		// Return false if is mobile
		if ( wp_is_mobile() ) {
			return false;
		}

		// Get post ID
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Return true if enabled via the post meta
		if ( $post_id && 'on' == get_post_meta( $post_id, 'wpex_overlay_header', true ) ) {
			return true;
		}

		// Return false if not enabled
		return false;

	}
}

/**
 * Add classes to the header wrap
 *
 * @since	Total 1.5.3
 * @return	bool
 */
if ( ! function_exists( 'wpex_header_classes' ) ) { 
	function wpex_header_classes( $post_id = '' ) {

		// Post id
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Get header style
		$header_style = wpex_get_header_style( $post_id );

		// Fixed Header
		$fixed_header = get_theme_mod( 'fixed_header', true );

		// Setup classes array
		$classes = array();

		// Clearfix class
		$classes['clr'] = 'clr';

		// Main header style
		$classes['header_style'] = 'header-'. $header_style;

		// Sticky Header
		if ( $fixed_header && 'one' == $header_style ) {
			$classes['fixed_scroll'] = 'fixed-scroll';
		}

		// Header Overlay Style
		if ( $post_id && wpex_is_overlay_header_enabled( $post_id ) ) {

			// Remove fixed scroll class
			unset( $classes['fixed_scroll'] );

			// Add overlay header class
			$classes['overlay_header'] = 'overlay-header';

			// Add a fixed class for the overlay-header style only
			if ( $fixed_header ) {
				$classes['fix_on_scroll'] = 'fix-on-scroll';
			}

			// Add overlay header style class
			if ( $meta = get_post_meta( $post_id, 'wpex_overlay_header_style', true ) ) {
				$overlay_style = $meta;
			} else {
				$overlay_style = 'light';	
			}
			$classes['overlay_header_style'] = $overlay_style .'-style';
		}
		
		// Apply filters
		$classes = apply_filters( 'wpex_header_classes', $classes );

		// Echo classes as space seperated classes
		echo implode( ' ', $classes );

	}
}

/**
 * Outputs the header
 * See partials/header/header-layout.php for output code
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_header_layout' ) ) {
	function wpex_header_layout() {
		get_template_part( 'partials/header/header', 'layout' );
	}
}

/**
 * Outputs the header aside content
 * See partials/header/header-layout.php for output code
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_header_aside' ) ) {
	function wpex_header_aside() {
		get_template_part( 'partials/header/header', 'aside' );
	}
}

/**
 * Returns header logo img url
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_header_logo_img' ) ) {
	function wpex_header_logo_img() {

		// Get logo img from admin panel
		$logo_img = get_theme_mod( 'custom_logo' );

		// WPML translation
		$logo_img = wpex_translate_theme_mod( 'custom_logo', $logo_img );

		// Apply filter for child theming
		$logo_img = apply_filters( 'wpex_header_logo_img_url', $logo_img );

		// Sanitize URL
		$logo_img = esc_url( $logo_img );

		// If there is a logo img return it
		if ( $logo_img ) {
			return $logo_img;
		}

	}
}

/**
 * Outputs the header logo HTML
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_header_logo' ) ) {
	function wpex_header_logo() {
		get_template_part( 'partials/header/header', 'logo' );
	}
}

/**
 * Adds js for the retina logo
 *
 * @since Total 1.1.0
 */
if ( ! function_exists( 'wpex_retina_logo' ) ) {
	function wpex_retina_logo() {

		// Get theme options
		$logo_url		= get_theme_mod( 'retina_logo' );
		$logo_height	= get_theme_mod( 'retina_logo_height' );

		// WPML translation
		$logo_url		= wpex_translate_theme_mod( 'retina_logo', $logo_url );
		$logo_height	= wpex_translate_theme_mod( 'retina_logo_height', $logo_height );

		// Output JS for retina logo
		if ( $logo_url && $logo_height) {
			$output = '<!-- Retina Logo --><script type="text/javascript">jQuery(function($){if (window.devicePixelRatio == 2) {$("#site-logo img").attr("src", "'. $logo_url .'");$("#site-logo img").css("height", "'. intval( $logo_height ) .'");}});</script>';
			echo $output;
		}
	}
}
add_action( 'wp_head', 'wpex_retina_logo' );