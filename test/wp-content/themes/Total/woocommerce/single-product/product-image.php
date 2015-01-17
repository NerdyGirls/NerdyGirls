<?php
/**
 * Single Product Image
 *
 * @author		WooThemes
 * @package		WooCommerce/Templates
 * @version		2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

// Get first image
$attachment_id	= get_post_thumbnail_id();
$attachment_url	= wp_get_attachment_url( $attachment_id );
$alt			= strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );
$width			= get_theme_mod( 'woo_post_image_width', '9999' );
$height			= get_theme_mod( 'woo_post_image_height', '9999' );
$crop			= ( $height == '9999' ) ? false : true;

// Get gallery images
$attachments = $product->get_gallery_attachment_ids();
array_unshift( $attachments, $attachment_id );
$attachments = array_unique( $attachments ); ?>

<div class="images clr">
	<?php
	// Flexslider
	if ( $attachments && count( $attachments ) > 1 && ! $product->has_child() ) { ?>
		<div class="woocommerce-single-product-slider-wrap clr">
			<div class="woocommerce-single-product-slider flexslider-container">
				<div class="flexslider">
					<ul class="slides lightbox-group">
						<?php
						// Loop through each product image
						foreach ( $attachments as $attachment ) {
							// Get image alt tag
							$attachment_alt = strip_tags( get_post_meta( $attachment, '_wp_attachment_image_alt', true ) ); ?>
							<li class="slide" data-thumb="<?php echo wpex_image_resize( wp_get_attachment_url( $attachment ), '100', '100', true ); ?>">
								<a href="<?php echo wp_get_attachment_url( $attachment ); ?>" title="<?php echo $attachment_alt; ?>" data-title="<?php echo $attachment_alt; ?>" data-type="image" class="lightbox-group-item">
									<img src="<?php echo wpex_image_resize( wp_get_attachment_url( $attachment ), $width,  $height, $crop ); ?>" alt="<?php echo $attachment_alt; ?>" />
								</a>
							</li>
						<?php } ?>
					</ul><!-- .slides -->
				</div><!-- .flexslider -->
			</div><!-- .woocommerce-single-product-slider -->
		</div><!-- .woocommerce-single-product-slider-wrap -->
	<?php }
	// No gallery images found
	else {
		// Display single featured image
		if ( has_post_thumbnail() ) {

			$image_title	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_link		= wp_get_attachment_url( get_post_thumbnail_id() );
			$image			= '<img src="'. wpex_image_resize( $attachment_url, $width, $height, $crop ) .'" alt="'. get_the_title() .'" />';

			if ( $product->has_child() ) {
				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="woocommerce-main-image">%s</div>', $image ), $post->ID );
			} else {
				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image wpex-lightbox" title="%s" >%s</a>', $image_link, $image_title, $image ), $post->ID );
			}

			// Display variation thumbnails
			if ( $product->has_child() ) { ?>
				<div class="product-variation-thumbs clr lightbox-group">
					<?php foreach ( $attachments as $attachment ) {
						$attachment_alt = strip_tags( get_post_meta( $attachment, '_wp_attachment_image_alt', true ) ); ?>
							<a href="<?php echo wp_get_attachment_url( $attachment ); ?>" title="<?php echo $attachment_alt; ?>" data-title="<?php echo $attachment_alt; ?>" data-type="image" class="lightbox-group-item"><img src="<?php echo wpex_image_resize( wp_get_attachment_url( $attachment ), '100', '100', true ); ?>" alt="<?php echo $attachment_alt; ?>" /></a>
					<?php } ?>
				</div><!-- .product-variation-thumbs -->
			<?php }
		} else {
			// Display woocommerce placeholder image
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ), $post->ID );
		} ?>
	<?php } ?>
</div>