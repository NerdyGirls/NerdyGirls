<?php
/**
 * Blog single post video format media
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

// Get and store post id
$post_id = get_the_ID() ?>

<div id="post-media" class="clr">
	<?php
	// Embeded video
	if ( $video = get_post_meta( $post_id, 'wpex_post_oembed', true ) ) : ?>
		<div id="blog-post-video" class="responsive-video-wrap"><?php echo wp_oembed_get( $video ); ?></div>
	<?php
	// Self hosted
	elseif ( $video = wpex_post_video_url( $post_id ) ) : ?>
		<div id="blog-post-video"><?php echo apply_filters( 'the_content', $video ); ?></div>
	<?php
	// Featured Image fallback
	elseif ( has_post_thumbnail() ) :
		$wpex_image = wpex_image( 'array' ); ?>
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="blog-entry-img-link"><img src="<?php echo $wpex_image['url']; ?>" alt="<?php echo the_title(); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" /></a>
	<?php endif; ?>
</div><!-- #post-media -->