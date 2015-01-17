<?php
/**
 * Image Swap style thumbnail
 *
 * @package		Total
 * @subpackage	Templates/WooCommerce
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if no featured image
if ( ! has_post_thumbnail() ) {
	return;
}

//Globals
global $product;

// Main vars
$output = '';
$enable_woo_entry_sliders = get_theme_mod( 'enable_woo_entry_sliders', 'on' );

// Get first image
$attachment_id	= get_post_thumbnail_id();
$attachment_url	= wp_get_attachment_url( $attachment_id );
$alt			= strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );
$width			= get_theme_mod( 'woo_entry_width', '9999' );
$height			= get_theme_mod( 'woo_entry_height', '9999' );
$crop			=  ( $height == '9999' ) ? false : true;
$cropped_image	= wpex_image_resize( $attachment_url,  $width, $height, $crop );

if ( $cropped_image ) { ?>
	<img src="<?php echo $cropped_image; ?>" alt="<?php echo $alt; ?>" class="woo-entry-image-main" />
<?php } ?>