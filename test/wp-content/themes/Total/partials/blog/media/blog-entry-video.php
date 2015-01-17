<?php
/**
 * Blog entry video format media
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

// Get post id if not defined
$post_id = get_the_ID();

// Show featured image for password-protected post
if ( post_password_required( $post_id ) ) {
	get_template_part( 'partials/blog/media/blog-entry', 'thumbnail' );
	return;
}

// Display oembeded video
if ( $video = get_post_meta( $post_id, 'wpex_post_oembed', true ) ) : ?>

	<div class="blog-entry-media clr">
		<div class="blog-entry-video responsive-video-wrap">
			<?php echo wp_oembed_get( $video ); ?>
		</div><!-- .blog-entry-video -->
	</div><!-- .blog-entry-media -->

<?php
// Display video oembed
elseif ( $video = wpex_post_video_url( $post_id ) ) : ?>

	<div class="blog-entry-media clr">
		<div class="blog-entry-video">
			<?php echo apply_filters( 'the_content', $video ); ?>
		</div><!-- .blog-entry-video -->
	</div><!-- .blog-entry-media -->

<?php
// Display post thumbnail as a fallback
else :
	get_template_part( 'partials/blog/media/blog-entry', 'thumbnail' );
endif;