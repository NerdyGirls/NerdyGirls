<?php
/**
 * Adds classes to the body tag for various page/post layout styles
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! function_exists( 'wpex_body_classes' ) ) {
	function wpex_body_classes( $classes ) {

		// Get post ID
		$post_id = wpex_get_the_id();

		// Define main layout style
		$main_layout = wpex_main_layout( $post_id );
		
		// WPExplorer class
		$classes[] = 'wpex-theme';

		// Responsive
		if ( get_theme_mod( 'responsive', 'on' ) ) {
			$classes[] = 'wpex-responsive';
		}
		
		// Add skin to body classes
		if ( function_exists( 'wpex_active_skin') && wpex_active_skin() ) {
			$classes[] = 'theme-'. wpex_active_skin();
		}

		// Check if the Visual Composer is being used on this page
		if ( function_exists( 'wpex_post_has_composer' ) && wpex_post_has_composer( $post_id ) ) {
			$classes[] = 'has-composer';
		}
		
		// Meta Options
		if ( $post_id ) {

			// No header margin
			if ( 'on' == get_post_meta( $post_id, 'wpex_disable_header_margin', true ) ) {
				$classes[] = 'no-header-margin';
			}

			// Slider
			if ( wpex_post_slider_shortcode( $post_id ) ) {
				$classes[] = 'page-with-slider';
			}

			// Title with Background Image
			if ( 'background-image' == get_post_meta( $post_id, 'wpex_post_title_style', true ) ) {
				$classes[] = 'page-with-background-title';
			}

			// Disabled header
			if ( ! wpex_is_page_header_enabled( $post_id ) ) {
				$classes[] = 'page-header-disabled';
			}
			
		}
		
		// Layout Style
		$classes[] = $main_layout .'-main-layout';

		// Boxed Layout dropshadow
		if( 'boxed' == $main_layout && get_theme_mod( 'boxed_dropdshadow' ) ) {
			$classes[] = 'wrap-boxshadow';
		}

		// Content layout
		if ( function_exists( 'wpex_get_post_layout_class' ) ) {
			$classes[] = 'content-'. wpex_get_post_layout_class( $post_id );
		}

		// Single Post cagegories
		if ( is_singular( 'post' ) ) {
			$cats = get_the_category( $post_id );
			foreach ( $cats as $cat ) {
				$classes[] = 'post-in-category-'. $cat->category_nicename;
			}
		}

		// Breadcrumbs
		if ( function_exists( 'wpex_breadcrumbs_enabled' )
			&& wpex_breadcrumbs_enabled()
			&& 'default' == get_theme_mod( 'breadcrumbs_position', 'default' ) ) {
			$classes[] = 'has-breadcrumbs';
		}

		// Shrink fixed header
		if ( get_theme_mod( 'shink_fixed_header', '1' ) && 'one' == get_theme_mod( 'header_style', 'one' ) ) {
			$classes[] = 'shrink-fixed-header';
		}
		
		// WooCommerce
		if ( class_exists( 'Woocommerce' ) && is_shop() ) {
			if ( get_theme_mod( 'woo_shop_slider' ) ) {
				$classes[] = 'page-with-slider';
			}
			if ( ! get_theme_mod( 'woo_shop_title', '1' ) ) {
				$classes[] = 'page-without-title';
			}
		}

		// Widget Icons
		if ( get_theme_mod( 'widget_icons', 'on' ) ) {
			$classes[] = 'sidebar-widget-icons';
		}

		// Mobile
		if ( wp_is_mobile() ) {
			$classes[] = 'is-mobile';
		}

		// Overlay header style
		if ( function_exists( 'wpex_is_overlay_header_enabled' ) && wpex_is_overlay_header_enabled( $post_id ) ) {
			$classes[] = 'has-overlay-header';
		}

		// Footer reveal
		if( function_exists( 'wpex_footer_reveal_enabled' ) && wpex_footer_reveal_enabled( $post_id ) ) {
			$classes[] = 'footer-has-reveal';
		}
		
		return $classes;
	}
}
add_filter( 'body_class', 'wpex_body_classes' );