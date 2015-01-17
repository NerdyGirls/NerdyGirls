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

// Get first image
$attachment_id	= get_post_thumbnail_id();
$attachment_url	= wp_get_attachment_url( $attachment_id );
$alt			= strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );
$width			= get_theme_mod( 'woo_entry_width', '9999' );
$height			= get_theme_mod( 'woo_entry_height', '9999' );
$crop			=  ( $height == '9999' ) ? false : true;

// Get Second Image in Gallery
$attachment_ids			= $product->get_gallery_attachment_ids();
$attachment_ids[]		= $attachment_id; // Add featured image to the array
$secondary_img_id_url	= '';

if ( !empty( $attachment_ids ) ) {
	$attachment_ids = array_unique( $attachment_ids ); // remove duplicate images
	if ( count( $attachment_ids ) > '1' ) {
		if ( $attachment_ids['0'] !== $attachment_id ) {
			$secondary_img_id = $attachment_ids['0'];
		} elseif ( $attachment_ids['1'] !== $attachment_id ) {
			$secondary_img_id = $attachment_ids['1'];
		}
		$secondary_img_id_url = wp_get_attachment_url( $secondary_img_id );
	}
}
			
// Return thumbnail
if ( $secondary_img_id_url ) { ?>
	<div class="woo-entry-image-swap">
		<img src="<?php echo wpex_image_resize( $attachment_url,  $width, $height, $crop ); ?>" alt="<?php echo $alt; ?>" class="woo-entry-image-main" />
		<img src="<?php echo wpex_image_resize( $secondary_img_id_url,  $width, $height, $crop ); ?>" alt="<?php echo $alt; ?>" class="woo-entry-image-secondary" />
	</div>
<?php } else { ?>
	<img src="<?php echo wpex_image_resize( $attachment_url,  $width, $height, $crop ); ?>" alt="<?php echo $alt; ?>" class="woo-entry-image-main" />
<?php }