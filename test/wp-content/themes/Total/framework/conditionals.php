<?php
/**
 * Conditonal functions
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link 		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.1
 */

/**
 * Returns true if the current Query is a query related to standard blog posts
 *
 * @since	Total 1.6.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_is_blog_query' ) ) {
	function wpex_is_blog_query() {
		if ( is_home() ) {
			return true;
		} elseif ( is_category() ) {
			return true;
		} elseif ( is_tag() ) {
			return true;
		} elseif ( is_date() ) {
			return true;
		}
	}
}

/**
 * Check if currently in front-end composer
 *
 * @since	Total 1.5
 * @return	bool
 */
if ( ! function_exists( 'wpex_is_front_end_composer' ) ) {
	function wpex_is_front_end_composer() {
		if ( ! function_exists( 'vc_is_inline' ) ) {
			return false;
		} elseif ( vc_is_inline() ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Check to see if the Visual Composer is enabled on a specific page
 *
 * @since	Total 1.6.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_post_has_composer' ) ) {
	function wpex_post_has_composer( $post_id = '' ) {
		if ( ! $post_id ) {
			return false;
		}
		$post_content	= get_post_field( 'post_content', $post_id );
		if( $post_content && strpos( $post_content, 'vc_row' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Checks if on a theme portfolio category page
 *
 * @since	Total 1.6.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_is_portfolio_tax' ) ) {
	function wpex_is_portfolio_tax() {
		if ( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_tag' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Checks if on a theme staff category page
 *
 * @since	Total 1.6.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_is_staff_tax' ) ) {
	function wpex_is_staff_tax() {
		if ( is_tax( 'staff_category' ) || is_tax( 'staff_tag' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Checks if on a theme testimonials category page
 *
 * @since	Total 1.6.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_is_testimonials_tax' ) ) {
	function wpex_is_testimonials_tax() {
		if ( is_tax( 'testimonials_category' ) || is_tax( 'testimonials_tag' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Checks if on the WooCommerce shop page
 *
 * @since	Total 1.6.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_is_woo_shop' ) ) {
	function wpex_is_woo_shop() {
		if ( ! WPEX_WOOCOMMERCE_ACTIVE ) {
			return false;
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			return true;
		}
	}
}

/**
 * Checks if on a WooCommerce tax
 *
 * @since	Total 1.6.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_is_woo_tax' ) ) {
	function wpex_is_woo_tax() {
		if ( ! WPEX_WOOCOMMERCE_ACTIVE ) {
			return false;
		} elseif ( ! is_tax() ) {
			return false;
		} elseif ( function_exists( 'is_product_category' ) && function_exists( 'is_product_tag' ) ) {
			if ( is_product_category() || is_product_tag() ) {
				return true;
			}
		}
	}
}

/**
 * Checks if on singular WooCommerce shop post
 *
 * @since Total 1.6.0
 * @return bool
 */
if ( ! function_exists( 'wpex_is_woo_single' ) ) {
	function wpex_is_woo_single() {
		if ( ! WPEX_WOOCOMMERCE_ACTIVE ) {
			return false;
		} elseif ( is_woocommerce() && is_singular( 'product' ) ) {
			return true;
		}
	}
}

/**
 * Check if product is in stock
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_woo_product_instock' ) ) {
	function wpex_woo_product_instock( $post_id = '' ) {
		global $post;
		$post_id		= $post_id ? $post_id : $post->ID;
		$stock_status	= get_post_meta( $post_id, '_stock_status', true );
		if ( 'instock' == $stock_status ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Check if current user has social profiles defined
 * Returns true upon the first meta found
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_author_has_social' ) ) {
	function wpex_author_has_social() {
		global $post;
		$post_author = $post->post_author;
		if ( get_the_author_meta( 'wpex_twitter', $post_author ) ) {
			return true;
		} elseif ( get_the_author_meta( 'wpex_facebook', $post_author ) ) {
			return true;
		} elseif ( get_the_author_meta( 'wpex_googleplus', $post_author ) ) {
			return true;
		} elseif ( get_the_author_meta( 'wpex_linkedin', $post_author ) ) {
			return true;
		} elseif ( get_the_author_meta( 'wpex_pinterest', $post_author ) ) {
			return true;
		} elseif ( get_the_author_meta( 'wpex_instagram', $post_author ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Check if a post has categories
 * This function is used for the next/prev function
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_post_has_terms' ) ) {
	function wpex_post_has_terms( $post_id = '', $post_type = 'post' ) {

		// Post data
		$post_id	= $post_id ? $post_id : get_the_ID();
		$post_type	= get_post_type( $post_id );

		// Standard Posts
		if ( $post_type == 'post' ) {
			$terms = wp_get_post_terms( $post_id, 'category');
			if ( ! empty( $terms ) ) {
				if ( '1' == count( $terms ) ) {
					if ( $terms[0]->count > '1' ) {
						return true;
					}
				} else {
					return true;
				}
			}
		}

		// Portfolio
		elseif ( 'portfolio' == $post_type ) {
			$terms = wp_get_post_terms( $post_id, 'portfolio_category');
			if ( ! empty( $terms ) ) {
				if ( '1' == count( $terms ) ) {
					if ( $terms[0]->count > '1' ) {
						return true;
					}
				} else {
					return true;
				}
			}
		}

		// Staff
		elseif ( 'staff' == $post_type ) {
			$terms = wp_get_post_terms( $post_id, 'staff_category');
			if ( ! empty( $terms ) ) {
				if ( '1' == count( $terms ) ) {
					if ( $terms[0]->count > '1' ) {
						return true;
					}
				} else {
					return true;
				}
			}
		}

		// Testimonials
		elseif ( 'testimonials' == $post_type ) {
			$terms = wp_get_post_terms( $post_id, 'testimonials_category');
			if ( ! empty( $terms ) ) {
				if ( '1' == count( $terms ) ) {
					if ( $terms[0]->count > '1' ) {
						return true;
					}
				} else {
					return true;
				}
			}
		}

	}
}