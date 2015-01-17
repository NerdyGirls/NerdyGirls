<?php
/**
 * Useful functions for the standard posts
 *
 * @package		Total
 * @subpackage	Framework/Blog
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Exclude categories from the blog
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_blog_exclude_categories' ) ) {
	function wpex_blog_exclude_categories( $return = false ) {
		// Don't run in these places
		if ( is_admin() ) {
			return;
		} elseif ( is_search() ) {
			return;
		} elseif ( is_archive() ) {
			return;
		}
		// Get Cat id's to exclude
		if ( $cats = get_theme_mod( 'blog_cats_exclude' ) ) {
			$cats = explode( ',', $cats );
			if ( ! is_array( $cats ) ) {
				return;
			}
		}
		// Return ID's
		if ( $return ) {
			return $cats;
		}
		// Exclude from homepage
		elseif ( is_home() && ! is_singular( 'page' ) ) {
			set_query_var( 'category__not_in', $cats );
		}
		
	}
}

/**
 * Returns the correct blog style
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_blog_style' ) ) {
	function wpex_blog_style() {
		$style = get_theme_mod( 'blog_style', 'large-image-entry-style' );
		if ( is_category() ) {
			$term		= get_query_var( 'cat' );
			$term_data	= get_option( "category_$term" );
			if ( $term_data && ! empty ( $term_data['wpex_term_style'] ) ) {
				$style = $term_data['wpex_term_style'] .'-entry-style';
			}
		}
		return $style;
	}
}

/**
 * Returns the grid style
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_blog_grid_style' ) ) {
	function wpex_blog_grid_style() {
		$style = get_theme_mod( 'blog_grid_style', 'fit-rows' );
		if ( is_category() ) {
			$term		= get_query_var( 'cat' );
			$term_data	= get_option( "category_$term" );
			if ( $term_data && ! empty ( $term_data['wpex_term_grid_style'] ) ) {
				$style = $term_data['wpex_term_grid_style'];
			}
		}
		return $style;
	}
}

/**
 * Checks if it's a fit-rows style grid
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_blog_fit_rows' ) ) {
	function wpex_blog_fit_rows() {
		if ( 'grid-entry-style' == wpex_blog_style() ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Returns the correct pagination style
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_blog_pagination_style' ) ) {
	function wpex_blog_pagination_style() {
		$style = get_theme_mod( 'blog_pagination_style' );
		if ( is_category() ) {
			$term		= get_query_var( 'cat' );
			$term_data	= get_option( "category_$term" );
			if ( $term_data && ! empty ( $term_data['wpex_term_pagination'] ) ) {
				$style = $term_data['wpex_term_pagination'];
			}
		}
		return $style;
	}
}

/**
 * Returns correct style for the blog entry based on theme options or category options
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_blog_entry_style' ) ) {
	function wpex_blog_entry_style() {
		$style = get_theme_mod( 'blog_style', 'large-image-entry-style' );
		if ( is_category() ) {
			$term		= get_query_var( "cat" );
			$term_data	= get_option( "category_$term" );
			if ( ! empty ( $term_data['wpex_term_style'] ) ) {
				$style = $term_data['wpex_term_style'] .'-entry-style';
			}
		}
		return apply_filters( 'wpex_blog_entry_style', $style );
	}
}

/**
 * Returns correct columns for the blog entries
 *
 * @since Total 1.5.3
 */
if ( ! function_exists( 'wpex_blog_entry_columns' ) ) {
	function wpex_blog_entry_columns() {
		$columns = get_theme_mod( 'blog_grid_columns', '2' );
		if ( is_category() ) {
			$term		= get_query_var( 'cat' );
			$term_data	= get_option( "category_$term" );
			if ( empty ( $term_data['wpex_term_grid_cols'] ) ) {
				$columns = $term_data['wpex_term_grid_cols'];
			}
		}
		return apply_filters( 'wpex_blog_entry_columns', $columns );
	}
}


/**
 * Returns correct blog entry classes
 *
 * @since Total 1.1.6
 */
if ( ! function_exists( 'wpex_blog_entry_classes' ) ) {
	function wpex_blog_entry_classes() {

		// Define classes array
		$classes = array();

		// Entry Style
		$entry_style = wpex_blog_entry_style();

		// Core classes
		$classes[] = 'blog-entry';
		$classes[] = 'clr';

		// Masonry classes
		if ( 'masonry' == wpex_blog_grid_style() ) {
			$classes[] = 'isotope-entry';
		}

		// Add columns for grid style entries
		if ( $entry_style == 'grid-entry-style' ) {
			$classes[] = 'col';
			$classes[] = wpex_grid_class( wpex_blog_entry_columns() );
		}

		// No Featured Image Class, don't add if oembed or self hosted meta are defined
		if ( ! has_post_thumbnail()
			&& '' == get_post_meta( get_the_ID(), 'wpex_post_self_hosted_shortcode', true )
			&& '' == get_post_meta( get_the_ID(), 'wpex_post_oembed', true ) ) {
			$classes[] = 'no-featured-image';
		}

		// Blog entry style
		$classes[] = $entry_style;

		// Apply filters to entry post class for child theming
		$classes = apply_filters( 'wpex_blog_entry_classes', $classes );

		// Rturn classes array
		return $classes;
	}
}

/**
 * Check if author avatar is enabled or not for blog entries
 *
 * @since Total 1.0
 * @return bool
 */
if ( ! function_exists( 'wpex_post_entry_author_avatar_enabled' ) ) {
	function wpex_post_entry_author_avatar_enabled() {
		if ( get_theme_mod( 'blog_entry_author_avatar' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Returns post video URL
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_post_video_url' ) ) {
	function wpex_post_video_url( $post_id = '' ) {
		// Get post id
		$post_id = $post_id ? $post_id : get_the_ID();
		// Oembed
		if ( get_post_meta( $post_id, 'wpex_post_oembed', true ) ) {
			return esc_url( get_post_meta( $post_id, 'wpex_post_oembed', true ) );
		}
		// Self Hosted redux
		$video = get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode_redux', true );
		if ( is_array( $video ) && ! empty( $video['url'] ) ) {
			return $video['url'];
		}
		// Self Hosted old - Thunder theme compatibility
		else {
			return get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode', true );
		}
	}
}

/**
 * Returns post audio URL
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_post_audio_url' ) ) {
	function wpex_post_audio_url( $post_id ) {
		// Get post ID
		$post_id = $post_id ? $post_id : get_the_ID();
		// Oembed
		if ( $meta = get_post_meta( $post_id, 'wpex_post_oembed', true ) ) {
			return $meta;
		}
		// Self Hosted redux
		$audio = get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode_redux', true );
		if ( is_array( $audio ) && ! empty( $audio['url'] ) ) {
			return $audio['url'];
		}
		// Self Hosted old - Thunder theme compatibility
		else {
			return get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode', true );
		}
	}
}

/**
 * Adds main classes to blog post entries
 *
 * @since Total 1.1.6
 */
if ( ! function_exists( 'wpex_blog_wrap_classes' ) ) {
	function wpex_blog_wrap_classes( $classes=false ) {
		
		// Return custom class if set
		if ( $classes ) {
			return $classes;
		}
		
		// Admin defaults
		$style		= wpex_blog_style();
		$classes	= array();
			
		// Isotope classes
		if ( $style == 'grid-entry-style' ) {
			$classes[] = 'wpex-row ';
			if ( 'masonry' == wpex_blog_grid_style() ) {
				$classes[] = 'blog-masonry-grid ';
			} else {
				if ( 'infinite_scroll' == wpex_blog_pagination_style() ) {
					$classes[] = 'blog-masonry-grid ';
				} else {
					$classes[] = 'blog-grid ';
				}
			}
		}
		
		// Add some margin when author is enabled
		if ( $style == 'grid-entry-style' && get_theme_mod( 'blog_entry_author_avatar' ) ) {
			$classes[] = 'grid-w-avatars ';
		}
		
		// Infinite scroll classes
		if ( 'infinite_scroll' == wpex_blog_pagination_style() ) {
			$classes[] = 'infinite-scroll-wrap ';
		}
		
		// Add filter for child theming
		$classes = apply_filters( 'wpex_blog_wrap_classes', $classes );

		// Return classes
		if ( is_array( $classes ) ) {
			echo implode( ' ', $classes );
		}
		
	}
}

/**
 * Animation classes that are added to the blog entry media
 *
 * @since Total 1.1.6
 */
if ( ! function_exists( 'wpex_img_animation_classes' ) ) {
	function wpex_img_animation_classes() {
		global $post;
		if ( 'post' != get_post_type( $post->ID ) ) {
			return;
		}
		if ( get_theme_mod( 'blog_entry_image_hover_animation' ) ) {
			echo 'wpex-img-hover-parent wpex-img-hover-'. get_theme_mod( 'blog_entry_image_hover_animation' );
		}
	}
}