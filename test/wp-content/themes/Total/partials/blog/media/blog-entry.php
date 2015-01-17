<?php
/**
 * Blog entry standard format media
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
$image = wpex_image( 'array' ); ?>

<div class="blog-entry-media clr">

	<?php
	// Lightbox style entry
	if ( get_theme_mod( 'blog_entry_image_lightbox' ) ) :
		$lightbox_url = wpex_image_resize( wp_get_attachment_url( get_post_thumbnail_id() ), 9999, 9999, false ); ?>
		<a href="<?php echo $lightbox_url; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="blog-entry-media-link <?php wpex_img_animation_classes(); ?> wpex-lightbox" data-type="image">
			<img src="<?php echo $image['url']; ?>" alt="<?php echo the_title(); ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />
		</a>
	<?php
	// Standard link to post
	else : ?>
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="blog-entry-media-link <?php wpex_img_animation_classes(); ?>">
			<img src="<?php echo $image['url']; ?>" alt="<?php echo the_title(); ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />
		</a>
	<?php endif; ?>

</div><!-- .blog-entry-media -->