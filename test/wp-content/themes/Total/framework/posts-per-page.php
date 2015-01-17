<?php
/**
 * This file filters the default WP pagination where needed
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Not needed in the admin
if ( is_admin() ) {
	return;
}

$wpex_posts_per_page = get_option( 'posts_per_page' );

add_action( 'init', 'wpex_modify_posts_per_page', 0 );

if ( ! function_exists( 'wpex_modify_posts_per_page' ) ) {
	function wpex_modify_posts_per_page() {
		add_filter( 'option_posts_per_page', 'wpex_posts_per_page' );
	}
}

if ( ! function_exists ( 'wpex_posts_per_page' ) ) {
	function wpex_posts_per_page( $value ) {
		
		// Search pagination
		if ( is_search() ) {
			return get_theme_mod( 'search_posts_per_page', '10' );
		}
		
		// Portfolio Category
		elseif ( is_tax( 'portfolio_category' ) ) {
			return get_theme_mod( 'portfolio_archive_posts_per_page', '12' );
		}

		// Portfolio Tag
		elseif ( is_tax( 'portfolio_tag' ) ) {
			return get_theme_mod( 'portfolio_archive_posts_per_page', '12' );
		}

		// Portfolio Archive
		elseif ( is_post_type_archive( 'portfolio' ) ) {
			return get_theme_mod( 'portfolio_archive_posts_per_page', '12' );
		}

		// Staff Category
		elseif ( is_tax( 'staff_category' ) ) {
			return get_theme_mod( 'staff_archive_posts_per_page', '12' );
		}

		// Staff Tag
		elseif ( is_tax( 'staff_tag' ) ) {
			return get_theme_mod( 'staff_archive_posts_per_page', '12' );
		}

		// Staff Archive
		elseif ( is_post_type_archive( 'staff' ) ) {
			return get_theme_mod( 'staff_archive_posts_per_page', '12' );
		}
				
		// Testimonials Category
		elseif ( is_tax( 'testimonials_category' ) ) {
			return get_theme_mod( 'testimonials_archive_posts_per_page', '12' );
		}

		// Testimonials Tag
		elseif ( is_tax( 'testimonials_tag' ) ) {
			return get_theme_mod( 'testimonials_archive_posts_per_page', '12' );
		}

		// Testimonials Archive
		elseif ( is_post_type_archive( 'testimonials' ) ) {
			return get_theme_mod( 'testimonials_archive_posts_per_page', '12' );
		}

		// Global posts per page
		global $wpex_posts_per_page;
		
		// Category pagination
		$terms = get_terms( 'category' );
		if ( ! empty( $terms ) ){
			foreach ( $terms as $term ) {
				if ( is_category( $term->slug ) ) {
					$term_id = $term->term_id;
					$term_data = get_option("category_$term_id");
					if ( $term_data ) {
						if ( isset( $term_data['wpex_term_posts_per_page'] ) && '' != $term_data['wpex_term_posts_per_page'] ) {
							return $term_data['wpex_term_posts_per_page'];
						} else {
							return $wpex_posts_per_page;
						}
					} else {
						return $wpex_posts_per_page;
					}
				}
			}
		}
		
		// Everything else
		return $wpex_posts_per_page;
	}
}