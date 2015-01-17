<?php
/**
 * Returns the correct main layout class for the current post/page/archive/etc
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! function_exists( 'wpex_get_post_layout_class' ) ) {
	function wpex_get_post_layout_class( $post_id = '' ) {

		// Define variables
		$class      = 'right-sidebar';
		$post_id	= $post_id ? $post_id : wpex_get_the_id();

		// First check meta then run through all template parts
		if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_post_layout', true ) ) {
			$class = $meta;
		}

		// Singular Page
		elseif ( is_singular( 'page' ) ) {
			// Blog template
			if ( is_page_template( 'templates/blog.php' ) ) {
				$class = get_theme_mod( 'blog_archives_layout', 'right-sidebar' );
			}
			// All other pages
			else {
				$class = get_theme_mod( 'page_single_layout', 'right-sidebar' );
			}
		}

		// Singular Post
		elseif ( is_singular( 'post' ) ) {
			$class = get_theme_mod( 'blog_single_layout', 'right-sidebar' );
		}

		// Singular Portfolio
		elseif ( is_singular( 'portfolio' ) ) {
			$class = get_theme_mod( 'portfolio_single_layout', 'right-sidebar' );
		}

		// Singular Staff
		elseif ( is_singular( 'staff' ) ) {
			$class = get_theme_mod( 'staff_single_layout', 'right-sidebar' );
		}

		// Singular Testimonials
		elseif ( is_singular( 'testimonials' ) ) {
			$class = get_theme_mod( 'testimonials_single_layout', 'full-width' );
		}

		// WooCoomerce shop
		elseif( wpex_is_woo_shop() ) {
			$class = get_theme_mod( 'woo_shop_layout', 'full-width' );
		}

		// WooCommerce tax
		elseif ( wpex_is_woo_tax() ) {
			$class = get_theme_mod( 'woo_shop_layout', 'full-width' );
		}

		// WooCommerce single
		elseif ( wpex_is_woo_single() ) {
			$class = get_theme_mod( 'woo_product_layout', 'full-width' );
		}
		
		// Portfolio tax
		elseif ( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_tag' ) ) {
			$class = get_theme_mod( 'portfolio_archive_layout', 'full-width' );
		}
		
		// Staff tax
		elseif ( is_tax( 'staff_category' ) || is_tax( 'staff_tag' ) ) {
			$class = get_theme_mod( 'staff_archive_layout', 'full-width' );
		}
		
		// Testimonials tax
		elseif ( is_tax( 'testimonials_category' ) || is_tax( 'testimonials_tag' ) ) {
			$class = get_theme_mod( 'testimonials_archive_layout', 'full-width' );
		}

		// Home
		elseif ( is_home() ) {
			$class = get_theme_mod( 'blog_archives_layout', 'right-sidebar' );
		}

		// Standard Categories
		elseif ( is_category() ) {
			$class      = get_theme_mod( 'blog_archives_layout', 'right-sidebar' );
			$term		= get_query_var( 'cat' );
			$term_data	= get_option( "category_$term" );
			if ( $term_data ) {
				if( ! empty( $term_data['wpex_term_layout'] ) ) {
					$class = $term_data['wpex_term_layout'];
				}
			}
		}

		// Author
		elseif ( is_author() ) {
			$class = get_theme_mod( 'blog_archives_layout', 'right-sidebar' );
		}

		// Archives
		elseif ( is_archive() ) {
			$class = get_theme_mod( 'blog_archives_layout', 'right-sidebar' );
		}

		// Tribe Events
		elseif ( function_exists( 'tribe_is_month' ) ) {
			if( tribe_is_month() ) {
				$class = 'full-width';
			} elseif ( function_exists( 'tribe_is_event' ) && function_exists( 'tribe_is_day' ) && tribe_is_event() && !tribe_is_day() && !is_single() ) {
				$class = 'full-width';
			} elseif ( function_exists( 'tribe_is_day' ) && tribe_is_day() ) {
				$class = 'full-width';
			}
			if ( is_singular( 'tribe_events' ) && $meta = get_post_meta( $post_id, 'wpex_post_layout', true ) ) {
				$class = $meta;
			} else {
				$class = 'full-width';
			}
		}

		// Fallback so class is never empty
		if ( empty( $class ) ) {
			$class = 'right-sidebar';
		}

		// Apply filters for child theme editing
		$class = apply_filters( 'wpex_post_layout_class', $class );
		
		// Return correct classname
		return $class;
		
	}
}