<?php
/**
 * Loads the required files to extend the Visual Composer plugin by WPBackery
 * Adds new modules such as Portfolio Grid, Image Caroursel, Bullets, Lists, Divider...and more!
 *
 * @package		Total
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.0
 */

// Visual Composer extension directory
$wpex_vcex_dir = WPEX_VCEX_DIR;

/**
 * Custom functions for use with VC extended shortcodes
 *
 * @since Total 1.4.0
 */
require_once( $wpex_vcex_dir . 'functions.php' );

/**
 * Front-end builder JS
 *
 * @since Total 1.4.0
 */
require_once( $wpex_vcex_dir . 'frontend-inline-js.php' );

/**
 * All custom shortcodes for use with the Visual Composer Extension
 *
 * @since Total 1.4.0
 */
require_once( $wpex_vcex_dir . 'shortcodes/spacing.php' );
require_once( $wpex_vcex_dir . 'shortcodes/divider.php' );
require_once( $wpex_vcex_dir . 'shortcodes/icon_box.php' );
require_once( $wpex_vcex_dir . 'shortcodes/teaser.php' );
require_once( $wpex_vcex_dir . 'shortcodes/feature.php' );
require_once( $wpex_vcex_dir . 'shortcodes/callout.php' );
require_once( $wpex_vcex_dir . 'shortcodes/list_item.php' );
require_once( $wpex_vcex_dir . 'shortcodes/bullets.php' );
require_once( $wpex_vcex_dir . 'shortcodes/button.php' );
require_once( $wpex_vcex_dir . 'shortcodes/pricing.php' );
require_once( $wpex_vcex_dir . 'shortcodes/skillbar.php' );
require_once( $wpex_vcex_dir . 'shortcodes/icon.php' );
require_once( $wpex_vcex_dir . 'shortcodes/milestone.php' );
require_once( $wpex_vcex_dir . 'shortcodes/image_swap.php' );
require_once( $wpex_vcex_dir . 'shortcodes/image_galleryslider.php' );
require_once( $wpex_vcex_dir . 'shortcodes/image_flexslider.php' );
require_once( $wpex_vcex_dir . 'shortcodes/image_carousel.php' );
require_once( $wpex_vcex_dir . 'shortcodes/image_grid.php' );
require_once( $wpex_vcex_dir . 'shortcodes/recent_news.php' );
require_once( $wpex_vcex_dir . 'shortcodes/blog_grid.php' );
require_once( $wpex_vcex_dir . 'shortcodes/blog_carousel.php' );
require_once( $wpex_vcex_dir . 'shortcodes/post_type_grid.php' );
require_once( $wpex_vcex_dir . 'shortcodes/post_type_slider.php' );
require_once( $wpex_vcex_dir . 'shortcodes/navbar.php' );

if ( WPEX_TESTIMONIALS_IS_ACTIVE ) {
	require_once( $wpex_vcex_dir . 'shortcodes/testimonials_grid.php' );
	require_once( $wpex_vcex_dir . 'shortcodes/testimonials_slider.php' );
}

if ( WPEX_PORTFOLIO_IS_ACTIVE ) {
	require_once( $wpex_vcex_dir . 'shortcodes/portfolio_grid.php' );
	require_once( $wpex_vcex_dir . 'shortcodes/portfolio_carousel.php' );
}

if ( WPEX_STAFF_IS_ACTIVE ) {
	require_once( $wpex_vcex_dir . 'shortcodes/staff_grid.php' );
	require_once( $wpex_vcex_dir . 'shortcodes/staff_carousel.php' );
	require_once( $wpex_vcex_dir . 'shortcodes/staff_social.php' );
}

require_once( $wpex_vcex_dir . 'shortcodes/login_form.php' );
require_once( $wpex_vcex_dir . 'shortcodes/newsletter_form.php' );

// Layerslider module
if ( WPEX_LAYERSLIDER_ACTIVE ) {
	require_once( $wpex_vcex_dir . 'shortcodes/layerslider.php' );
}

// WooCommerce Shortcodes
if ( WPEX_WOOCOMMERCE_ACTIVE ) {
	require_once( $wpex_vcex_dir . 'shortcodes/woocommerce_carousel.php' );
}