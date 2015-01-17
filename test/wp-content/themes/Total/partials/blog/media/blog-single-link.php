<?php
/**
 * Blog single post link format media
 * Link formats should redirect to the URL defined in the custom fields
 * This template file is used as a fallback only.
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
// Get cropped featured image
$wpex_image = wpex_image( 'array' ); ?>

<div id="post-media" class="clr">
	<?php
	// Image with lightbox link
	if ( get_theme_mod( 'blog_post_image_lightbox' ) ) : ?>
		<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" class="wpex-lightbox <?php wpex_img_animation_classes(); ?>" data-type="image"><img src="<?php echo $wpex_image['url']; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" /></a>
	<?php
	// No lightbox
	else : ?>
		<img src="<?php echo $wpex_image['url']; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
	<?php endif; ?>
	<?php
	// Blog entry caption
	if ( get_theme_mod( 'blog_thumbnail_caption' ) && $caption = wpex_featured_image_caption() ) : ?>
		<div class="post-media-caption clr">
			<?php echo $caption; ?>
		</div>
	<?php endif; ?>
</div><!-- #post-media -->