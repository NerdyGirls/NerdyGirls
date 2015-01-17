<?php
/**
 * Useful global functions for the portfolio
 *
 * @package Total
 * @subpackage Portfolio Functions
 * @author Alexander Clarke
 * @copyright Copyright (c) 2014, Symple Workz LLC
 * @link http://www.wpexplorer.com
 * @since Total 1.0
 */

/**
 * Returns correct classes for the portfolio wrap
 *
 * @since Total 1.5.3
 * @return var $classes
 */
if ( ! function_exists( 'wpex_get_portfolio_wrap_classes' ) ) {
	function wpex_get_portfolio_wrap_classes() {
		$classes	= array( 'wpex-row', 'clr' );
		$theme_mod	= get_theme_mod( 'portfolio_archive_grid_style', 'fit-rows' ) ? get_theme_mod( 'portfolio_archive_grid_style', 'fit-rows' ) : 'fit-rows';
		$classes[]	= 'portfolio-'. $theme_mod;
		return implode( " ",$classes );
	}
}

/**
 * Checks if match heights are enabled for the portfolio
 *
 * @since Total 1.5.3
 * @return bool
 */
if ( ! function_exists( 'wpex_portfolio_match_height' ) ) {
	function wpex_portfolio_match_height() {
		$grid_style = get_theme_mod( 'portfolio_archive_grid_style', 'fit-rows' ) ? get_theme_mod( 'portfolio_archive_grid_style', 'fit-rows' ) : 'fit-rows';
		$columns    = get_theme_mod( 'portfolio_entry_columns', '4' ) ? get_theme_mod( 'portfolio_entry_columns', '4' ) : '4';
		if ( 'fit-rows' == $grid_style && get_theme_mod( 'portfolio_archive_grid_equal_heights' ) && $columns > '1' ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Returns correct classes for the portfolio grid
 *
 * @since Total 1.52
 */
if ( ! function_exists( 'wpex_portfolio_column_class' ) ) {
	function wpex_portfolio_column_class( $query ) {
		if ( 'related' == $query ) {
			$columns = get_theme_mod( 'portfolio_related_columns', '4' );
		} else {
			$columns = get_theme_mod( 'portfolio_entry_columns', '4' );
		}
		return wpex_grid_class( $columns );
	}
}

/**
 * Returns portfolio featured video url
 *
 * @since Total 1.52
 */
if ( ! function_exists( 'wpex_get_portfolio_featured_video_url' ) ) {
	function wpex_get_portfolio_featured_video_url( $post_id = '' ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$meta = get_post_meta( $post_id, 'wpex_post_video', true );
		if ( $meta ) {
			return $meta;
		} else {
			return false;
		}
	}
}

/**
 * Displays the portfolio featured video
 *
 * @since Total 1.52
 */
if ( ! function_exists( 'wpex_portfolio_post_video' ) ) {
	function wpex_portfolio_post_video( $post_id = '' ) {
		$video_url = wpex_get_portfolio_featured_video_url();
		if ( empty( $video_url ) ) {
			return;
		}
		$embed_code = wp_oembed_get( $video_url );
		if ( '' != $video_url && !is_wp_error( $embed_code ) ) {
			echo '<div class="portfolio-featured-video responsive-video-wrap clr">'. $embed_code .'</div>';
		}
	}
}