<?php
/**
 * Main footer functions
 *
 * @package		Total
 * @subpackage	Footer Functions
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

/**
 * Conditional check if the footer should display or not
 *
 * @since	Total 1.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_display_footer' ) ) {
	function wpex_display_footer() {
		$return = true;
		$post_id = wpex_get_the_id();
		if ( $post_id && 'on' == get_post_meta( $post_id, 'wpex_disable_footer', true ) ) {
			$return = false;
		}
		$return = apply_filters( 'wpex_display_footer', $return );
		return $return;
		
	}
}

/**
 * Conditional check if the footer widgets should display or not
 *
 * @since	Total 1.54
 * @return	bool
 */
if ( ! function_exists( 'wpex_display_footer_widgets' ) ) {
	function wpex_display_footer_widgets() {
		if( get_theme_mod( 'footer_widgets', true ) ) {
			$return = true;
		} else {
			$return = false;
		}
		$post_id = wpex_get_the_id();
		if ( $post_id && 'on' == get_post_meta( $post_id, 'wpex_disable_footer_widgets', true ) ) {
			$return = false;
		}
		return apply_filters( 'wpex_display_footer_widgets', $return );
	}
}

/**
 * Conditional check if the footer reveal is enabled
 *
 * @since	Total 1.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_footer_reveal_enabled' ) ) {
	function wpex_footer_reveal_enabled( $post_id = '' ) {

		// Disable on mobile
		if ( wp_is_mobile() ) {
			return false;
		}

		// Disable on 404
		if ( is_404() ) {
			return false;
		}

		// Disable on boxed style
		if ( 'boxed' == wpex_main_layout() ) {
			return false;
		}

		// Get post id
		$post_id = $post_id ? $post_id : wpex_get_the_ID();

		// Meta check
		if ( $post_id ) {
			if ( 'on' == get_post_meta( $post_id, 'wpex_footer_reveal', true ) ) {
				return true;
			} elseif ( 'off' == get_post_meta( $post_id, 'wpex_footer_reveal', true ) ) {
				return false;
			}
		}

		// Theme option check
		if ( get_theme_mod( 'footer_reveal', false ) ) {
			return true;
		}

	}
}

/**
 * Gets the footer widgets template part
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_footer_widgets' ) ) {
	function wpex_footer_widgets() {
		get_template_part( 'partials/footer/footer', 'widgets' );
	}
}

/**
 * Gets the footer bottom template part
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_footer_bottom' ) ) {
	function wpex_footer_bottom() {
		get_template_part( 'partials/footer/footer', 'bottom' );
	}
}

/**
 * Conditional check if the footer callout is enabled or disabled
 *
 * @since  Total 1.0.0
 * @return bool
 */
if ( ! function_exists( 'wpex_display_callout' ) ) {
	function wpex_display_callout( $post_id = '' ) {

		// Get current ID
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Check if disabled via custom field
		if ( $post_id && 'on' == get_post_meta( $post_id, 'wpex_disable_footer_callout', true ) ) {
			$return = false;
		}

		// Check if there is custom callout text added for specific ID
		elseif ( $post_id && get_post_meta( $post_id, 'wpex_callout_text', true ) ) {
			$return = true;
		}

		// Chec if disabled via the customizer
		elseif ( ! get_theme_mod( 'callout', true ) ) {
			$return = false;
		}

		// If all else fails return true
		else {
			$return = true;
		}

		// Apply filter for child theming
		$return = apply_filters( 'wpex_callout_enabled', $return );

		// Return bool
		return $return;
		
	}
}

/**
 * Gets the footer callout template part
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_footer_callout' ) ) {
	function wpex_footer_callout() {
		get_template_part( 'partials/footer/footer', 'callout' );	
	}
}

/**
 * Adds scroll to top link to the wp_footer hook
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_scroll_top' ) ) {
	function wpex_scroll_top() {
		if ( get_theme_mod( 'scroll_top', '1' ) ) { ?>
			<a href="#" id="site-scroll-top"><span class="fa fa-chevron-up"></span></a>
		<?php }
	}
}
add_action( 'wp_footer', 'wpex_scroll_top' );