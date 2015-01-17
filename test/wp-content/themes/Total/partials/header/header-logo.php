<?php
/**
 * Header aside content used in Header Style Two by default
 *
 * @package		Total
 * @subpackage	Partials/Header
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Post ID
$post_id = wpex_get_the_ID();

// Get header style to add to the logo classes
$header_style = wpex_get_header_style();

// Logo Classes
$classes = 'header-'. $header_style .'-logo';

// Set url for the logo with filter so it can be altered via a child theme
$site_url = esc_url( home_url( '/' ) );
$logo_url = apply_filters( 'wpex_logo_url', $site_url );

// Get logo img
$logo_img = wpex_header_logo_img();

// Get overlay image url
if ( wpex_is_overlay_header_enabled( $post_id ) && $overlay_logo = get_post_meta( $post_id, 'wpex_overlay_header_logo', true ) ) {

	// Redux fallback until post is saved
	if ( is_array( $overlay_logo ) ) {
		if ( ! empty( $overlay_logo['url'] ) ) {
			$overlay_logo = $overlay_logo['url'];
		} else {
			$overlay_logo = false;
		}
	}

	if ( $overlay_logo ) {
		$classes .= ' has-overlay-logo';
	}

}

// Get title for the logo based on the blogname & apply filters for easy customization
$logo_title	= get_bloginfo( 'name' );
$logo_title	= apply_filters( 'wpex_logo_title', $logo_title );

// If a logo url does not exist, return nothing
if ( ! $logo_url ) {
	return;
} ?>
<div id="site-logo" class="<?php echo $classes; ?>">
	<?php
	// Display main logo
	if ( '' != $logo_img ) { ?>
		<a href="<?php echo esc_url( $logo_url ); ?>" title="<?php echo $logo_title; ?>" rel="home" class="main-logo">
			<img src="<?php echo esc_url( $logo_img ); ?>" alt="<?php echo $logo_title; ?>" />
		</a>
		<?php
		// Display alternative logo for overlay header
		if ( isset( $overlay_logo ) && $overlay_logo ) {
			// Get retina overlay logo version ?>
			<a href="<?php echo esc_url( $logo_url ); ?>" title="<?php echo $logo_title; ?>" rel="home" class="overlay-header-logo">
				<img src="<?php echo esc_url( $overlay_logo ); ?>" alt="<?php echo $logo_title; ?>" />
			</a>
		<?php } ?>
	<?php
	// Display text style logo
	} else {
		$output = '';
		$icon = get_theme_mod( 'logo_icon' );
		if ( '' != $icon && 'none' != $icon ) {
			// Output the favicon
			$output .= '<span class="fa fa-'. $icon .'"></span>';
		}
		$output .= $logo_title; ?>
		<a href="<?php echo $logo_url; ?>" title="<?php echo $logo_title; ?>" rel="home"><?php echo $output; ?></a>
	<?php } ?>
</div><!-- #site-logo -->