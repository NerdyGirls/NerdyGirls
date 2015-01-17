<?php
/**
 * Used to remove slugs from custom post type URLs
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Return if disabled
if ( ! get_theme_mod( 'remove_posttype_slugs', false ) ) {
	return;
}

if ( ! function_exists( 'wpex_remove_cpt_slug' ) ) {
	function wpex_remove_cpt_slug( $post_link, $post, $leavename ) {

		// Theme post types
		$post_types = array( 'portfolio', 'staff', 'testimonials' );

		// If not part of the theme post types return default post link
		if ( ! in_array( $post->post_type, $post_types ) || 'publish' != $post->post_status ) {
			return $post_link;
		}

		// Loop through post types and remove the current slug
		foreach ( $post_types as $post_type ) {
			if ( 'portfolio' == $post_type ) {
				$slug = get_theme_mod( 'portfolio_slug', 'portfolio-item' );
			}
			if ( 'staff' == $post_type ) {
				$slug = get_theme_mod( 'staff_slug', 'staff-member' );
			}
			if ( 'testimonials' == $post_type ) {
				$slug = get_theme_mod( 'testimonials_slug', 'testimonial' );
			}

			// Remove current slug
			$post_link = str_replace( '/'. $slug .'/', '/', $post_link );

		}

		// Return new post link without slug
		return $post_link;

	}
}
add_filter( 'post_type_link', 'wpex_remove_cpt_slug', 10, 3 );

if ( ! function_exists( 'wpex_parse_request_tricksy' ) ) {
	function wpex_parse_request_tricksy( $query ) {

		// Theme post types
		$post_types = array( 'portfolio', 'staff', 'testimonials' );

		// Only noop the main query
		if ( ! $query->is_main_query() ) {
			return;
		}

		// Only noop our very specific rewrite rule match
		if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
			return;
		}

		// 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
		if ( ! empty( $query->query['name'] ) ) {
			$array = array( 'post', 'page' );
			$array = array_merge( $array, $post_types );
			$query->set( 'post_type', $array );
		}
	}
}
add_action( 'pre_get_posts', 'wpex_parse_request_tricksy' );