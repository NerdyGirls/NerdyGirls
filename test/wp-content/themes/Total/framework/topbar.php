<?php
/**
 * Custom Top Bar Functions
 *
 * @package		Total
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Checks if the top bar should display or not
 *
 * @since Total 1.6.0
 */
if ( ! function_exists( 'wpex_is_top_bar_enabled' ) ) {
	function wpex_is_top_bar_enabled( $return = true ) {
		if ( ! get_theme_mod( 'top_bar', true ) ) {
			$return = false;
		} elseif ( ( $post_id = wpex_get_the_id() ) && 'on' == get_post_meta( $post_id, 'wpex_disable_top_bar', true ) ) {
			$return = false;
		} elseif( wpex_is_overlay_header_enabled() ) {
			$return = false;
		} else {
			$return = true;
		}
		$return = apply_filters( 'wpex_is_top_bar_enabled', $return );
		return $return;
	}
}

/**
 * Outputs the topbar with all it's content and main wraps
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_top_bar' ) ) {
	function wpex_top_bar() {
		get_template_part( 'partials/topbar/topbar', 'layout' );
	}
}