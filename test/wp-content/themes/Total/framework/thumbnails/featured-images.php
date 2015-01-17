<?php
/**
 * Creates a function for your featured image sizes which can be altered via your child theme
 *
 * @package		Total
 * @subpackage	Framework/Thumbnails
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
*/

if ( ! function_exists( 'wpex_image' ) ) {
	function wpex_image( $return = 'url', $custom_id = '', $custom_query = false ) {

		/*-----------------------------------------------------------------------------------*/
		/*	- Main Vars
		/*-----------------------------------------------------------------------------------*/

		global $post;
		$post_id   = $post->ID;
		$post_type = get_post_type( $post_id );
		if( $custom_id ) {
			$attachment_id = $custom_id;
		} else {
			$attachment_id = get_post_thumbnail_id( $post_id );
		}
		$attachment_url      = wp_get_attachment_url( $attachment_id );
		$post_layout         = get_post_meta ( $post_id, 'wpex_post_layout', true );
		$post_media_position = get_post_meta ( $post_id, 'wpex_post_media_position', true );
		
		$width  = 9999;
		$height = 9999;
		$crop   = false;

		/*-----------------------------------------------------------------------------------*/
		/*	- Get correct dimensions based on customizer/meta options
		/*-----------------------------------------------------------------------------------*/

		// Only run the following code if image resizing isn't disabled
		
		if ( get_option( 'wpex_image_resizing', '1' ) ) {
		
			// Pages
			if ( is_singular( 'page' ) ) {
				$width  = get_theme_mod( 'page_image_width', '9999' );
				$height = get_theme_mod( 'page_image_height', '9999' );
			}

			// Standard Post Type
			if ( 'post' == $post_type ) {
				// Singular
				if ( is_singular( 'post' ) ) {
					// Related
					if ( $custom_query ) {
						$width  = get_theme_mod( 'blog_related_image_width', '9999' );
						$height = get_theme_mod( 'blog_related_image_height', '9999' );
					}
					// Post Media
					else {
						if ( $post_layout == 'full-width' || $post_media_position || 'full-width' == get_theme_mod( 'blog_single_layout' ) ) {
							$width = get_theme_mod( 'blog_post_full_image_width', '9999' );
						} else {
							$width = get_theme_mod( 'blog_post_image_width', '9999' );
						}
						if ( 'full-width' == get_theme_mod( 'blog_single_layout' ) || 'full-width' == $post_layout || $post_media_position ) {
							$height = get_theme_mod( 'blog_post_full_image_height', '9999' );
						} else {
							$height = get_theme_mod( 'blog_post_image_height', '9999' );
						}
					}
				// Entries
				} else {
					// Categories
					if ( is_category() ) {
						// Get term data
						$term = get_query_var('cat');
						$term_data = get_option("category_$term");
						// Width
						if ( isset( $term_data['wpex_term_image_width'] ) ) {
							if ( '' != $term_data['wpex_term_image_width']) {
								$width = $term_data['wpex_term_image_width'];
							} else {
								$width = get_theme_mod( 'blog_entry_image_width', '9999' );
							}
						} else {
							$width = get_theme_mod( 'blog_entry_image_width', '9999' );
						}
						// height
						if ( isset($term_data['wpex_term_image_height']) ) {
							if ( $term_data['wpex_term_image_height'] !== '' ) {
								$height = $term_data['wpex_term_image_height'];
							} else {
								$height = get_theme_mod( 'blog_entry_image_height', '9999' );
							}
						} else {
							$height = get_theme_mod( 'blog_entry_image_height', '9999' );
						}
					// Blog Posts
					} else {
						$width  = get_theme_mod( 'blog_entry_image_width', '9999' );
						$height = get_theme_mod( 'blog_entry_image_height', '9999' );
					}
				}
			}

			// Staff Post Type
			elseif ( 'staff' == $post_type ) {
				$width  = get_theme_mod( 'staff_entry_image_width', '9999' );
				$height = get_theme_mod( 'staff_entry_image_height', '9999' );
			}

			/** Portfolio Post Type **/
			elseif ( 'portfolio' == $post_type ) {
				if ( is_singular() && $custom_query ) {
					$width  = get_theme_mod( 'portfolio_post_image_width', '9999' );
					$height = get_theme_mod( 'portfolio_post_image_height', '9999' );
				} else {
					$width  = get_theme_mod( 'portfolio_entry_image_width', '9999' );
					$height = get_theme_mod( 'portfolio_entry_image_height', '9999' );
				}
			}

			// Testimonials Post Type
			elseif ( 'testimonials' == $post_type ) {
				$width  = get_theme_mod( 'testimonials_entry_image_width', '50' );
				$height = get_theme_mod( 'testimonials_entry_image_height', '50' );
			}

			// Search
			if ( is_search() ) {
				if( 'default' == wpex_search_results_style() ) {
					$width  = '100';
					$height = '100';
				} else {
					$width  = get_theme_mod( 'blog_entry_image_width', '9999' );
					$height = get_theme_mod( 'blog_entry_image_height', '9999' );
				}
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Return
		/*-----------------------------------------------------------------------------------*/

		// Width
		if( $width ) {
			$width = intval( $width );
		} else {
			$width = '9999';
		}
		$width = apply_filters( 'wpex_image_width', $width );

		// Height
		if( $height ) {
			$height = intval( $height );
		} else {
			$height = '9999';
		}
		$height = apply_filters( 'wpex_image_height', $height );

		// Crop
		if( $height == '9999') {
			$crop = false;
		} else {
			$crop = true;
		}

		// Run aq function
		$resized_array = wpex_image_resize( $attachment_url, $width, $height, $crop, 'array' );

		// Return data
		if ( 'url' == $return ) {
			return $resized_array['url'];
		} elseif ( 'array' == $return ) {
			return $resized_array;
		}

	}
}