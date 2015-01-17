<?php
/**
 * Blog entry gallery format media
 *
 * @package		Total
 * @subpackage	Partials/Blog/Media
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

// Return if there isn't any thumbnail
if ( ! has_post_thumbnail() ) {
	return;
}

// Get post id
$post_id = get_the_ID();

// Get attachments
$attachments = wpex_get_gallery_ids( $post_id );

// Return standard entry style if password protected or there aren't any attachments
if ( post_password_required( $post_id ) || empty( $attachments ) ) {
	get_template_part( 'partials/blog/media/blog-entry', 'thumbnail' );
	return;
} ?>

<div class="blog-entry-media clr">
	<div class="gallery-format-post-slider-wrap clr">
		<div class="gallery-format-post-slider flexslider-container">
				<div class="flexslider">
					<ul class="slides <?php if ( wpex_gallery_is_lightbox_enabled() == 'on' ) echo 'lightbox-group'; ?>">
						<?php foreach ( $attachments as $attachment ) {
							// Get image alt tag
							$attachment_alt = strip_tags( get_post_meta( $attachment, '_wp_attachment_image_alt', true ) ); ?>
							<li class="slide" data-thumb="<?php echo wpex_image_resize( wp_get_attachment_url( $attachment ), '100', '100', true ); ?>">
								<?php
								// Display image with lightbox
								if ( wpex_gallery_is_lightbox_enabled() == 'on' ) {
									$lightbox_url = wpex_image_resize( wp_get_attachment_url( $attachment ), 1500, 9999, false ); ?>
									<a href="<?php echo $lightbox_url; ?>" title="<?php echo $attachment_alt; ?>" data-title="<?php echo $attachment_alt; ?>" data-type="image" class="lightbox-group-item">
										<img src="<?php echo wpex_image( 'url', $attachment ); ?>" alt="<?php echo $attachment_alt; ?>" />
									</a>
								<?php } else {
									// Lightbox is disabled, only show image ?>
									<img src="<?php echo wpex_image( 'url', $attachment ); ?>" alt="<?php echo $attachment_alt; ?>" />
								<?php } ?>
							</li>
						<?php } ?>
					</ul><!-- .slides -->
				</div><!-- .flexslider -->
		</div><!-- .flexslider-container -->
	</div><!-- .gallery-format-post-slider-wrap -->
</div><!-- .blog-entry-media -->