<?php
/**
 * Toggle Bar Output
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Checks if the toggle bar is enabled
 *
 * @since	Total 1.0.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_toggle_bar_active' ) ) {
	function wpex_toggle_bar_active( $return = true ) {
		if ( wpex_is_front_end_composer() ) {
			$return = false;
		} elseif ( ! get_theme_mod( 'toggle_bar' ) ) {
			$return = false;
		} elseif ( ! get_theme_mod( 'toggle_bar_page' ) ) {
			$return = false;
		} elseif ( ( $post_id = wpex_get_the_id() ) && 'on' == get_post_meta( $post_id, 'wpex_disable_toggle_bar', true ) ) {
			$return = false;
		} else {
			$return = true;
		}
		$return = apply_filters( 'wpex_toggle_bar_active', $return );
		return $return;
	}
}

/**
 * Gets the correct template part for the togglebar
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_toggle_bar' ) ) {
	function wpex_toggle_bar() {
		get_template_part( 'partials/togglebar/togglebar', 'layout' );
	}
}

/**
 * Outputs the toggle bar button
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_toggle_bar_btn' ) ) {
	function wpex_toggle_bar_btn() {
		if ( ! wpex_toggle_bar_active() ) {
			return;
		}
		echo '<a href="#" class="toggle-bar-btn fade-toggle '. get_theme_mod( 'toggle_bar_visibility' ) .'"><span class="fa fa-plus"></span></a>';
	}
}