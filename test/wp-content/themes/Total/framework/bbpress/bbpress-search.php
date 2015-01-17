<?php
/**
 * Adds bbPress post types to the standard search
 *
 * @package WordPress
 * @subpackage Total
 * @since Total 1.0
 */


/*
=== Permalinks Seem to break with this...WTF? ====
Allow Forum Posts in Search
if ( ! function_exists( 'wpex_bbpress_add_to_main_search' ) ) {
	function wpex_bbpress_add_to_main_search() {
		$post_type['exclude_from_search'] = false;
		return $post_type;
	}
}
add_filter( 'bbp_register_forum_post_type', 'wpex_bbpress_add_to_main_search' ); */