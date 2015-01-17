<?php
/**
 * Blog entry layout
 *
 * @package		Total
 * @subpackage	Partials/Blog
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get post ID
$post_id		= get_the_ID();
$post_format	= get_post_format( $post_id );
$entry_style	= wpex_blog_entry_style();

// Quote format is completely different
if ( 'quote' == $post_format ) :
	get_template_part( 'partials/blog/blog-entry', 'quote' );
	return;
endif;

// Add classes to the blog entry post class - see framework/blog/blog-functions
$classes = wpex_blog_entry_classes(); ?>

<article id="post-<?php echo $post_id; ?>" <?php post_class( $classes ); ?>>
	<div class="blog-entry-inner clr">

		<?php
		// Thumbnail entry style does not support entry builder, not possible
		if ( 'thumbnail-entry-style' == $entry_style ) : ?>
			<?php
			// Get entry media
			get_template_part( 'partials/blog/media/blog-entry', $post_format ); ?>
			<div class="blog-entry-content clr">
				<?php get_template_part( 'partials/blog/blog-entry', 'header' ); ?>
				<?php get_template_part( 'partials/blog/blog-entry', 'content' ); ?>
				<?php get_template_part( 'partials/blog/blog-entry', 'readmore' ); ?>
			</div><!-- blog-entry-content -->
		<?php

		// Entry layout builder
		else :

			// Get blocks from theme options
			$blocks = get_theme_mod( 'blog_entry_composer', 'featured_media,title_meta,excerpt_content,readmore' );
			
			// Apply filters
			$blocks = apply_filters( 'wpex_single_blog_entry_blocks', $blocks );

			// Return if empty
			if ( empty ( $blocks ) ) {
				return;
			}
			
			// Convert blocks to array so we can loop through them
			if ( ! is_array( $blocks ) ) {
				$blocks = explode( ',', $blocks );
			}
			
			// Loop through composer blocks and output layout
			foreach ( $blocks as $block ) :

				// Featured media
				if ( 'featured_media' == $block ) {
					get_template_part( 'partials/blog/media/blog-entry', $post_format );
				}

				// Title and Meta
				elseif ( 'title_meta' == $block ) {
					get_template_part( 'partials/blog/blog-entry', 'header' );
				}

				// Excerpt content
				elseif ( 'excerpt_content' == $block ) {
					get_template_part( 'partials/blog/blog-entry', 'content' );
				}

				// Readmore
				elseif ( 'readmore' == $block ) {	
					get_template_part( 'partials/blog/blog-entry', 'readmore' );
				}

			endforeach;
		endif;

		// Social sharing
		if ( get_theme_mod( 'social_share_blog_entries', false ) ) :
			wpex_social_share( $post_id );
		endif; ?>

	</div><!-- .blog-entry-inner -->
</article><!-- .blog-entry -->