<?php
/**
 * Blog single post audio format media
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
	// Audio embed
	if ( $audio = get_post_meta( $post_id, 'wpex_post_oembed', true ) ) : ?>
		<div class="blog-post-audio clr wpex-fitvids">
			<?php echo wp_oembed_get( $audio ); ?>
		</div>
	<?php
	// Self hosted audio
	elseif ( $audio = audio( $post_id ) ) : ?>
		<div class="blog-post-audio clr"><?php echo apply_filters( 'the_content', $audio ); ?></div>
	<?php
	// Featured Image
	elseif( get_theme_mod( 'blog_single_thumbnail', true ) && has_post_thumbnail( $post_id ) ) :
		$wpex_image = wpex_image( 'array' ); ?>
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="blog-entry-img-link">
			<img src="<?php echo $wpex_image['url']; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
		</a>
	<?php endif; ?>
</div><!-- #post-media -->