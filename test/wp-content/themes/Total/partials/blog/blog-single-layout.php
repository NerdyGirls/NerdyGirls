<?php
/**
 * Single blog post layout
 *
 * @package		Total
 * @subpackage	Partials/Blog
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

// Get single blog layout blocks
$post_id			= get_the_ID();
$post_format		= get_post_format( $post_id );
$password_required	= post_password_required( $post_id );

/*-----------------------------------------------------------------------------------*/
/*	- Blog post layout
/*  - All blog elements can be re-ordered via the WP Customizer so don't edit this
/*	- file unless you really have to.
/*-----------------------------------------------------------------------------------*/

// Quote format is completely different
if ( 'quote' == $post_format ) :
	get_template_part( 'partials/blog/blog-single', 'quote' );
	return;

// Blog Single Post Composer
else :

	// Get layout blocks
	$defaults		= 'featured_media,title_meta,post_series,the_content,post_tags,social_share,author_bio,related_posts,comments';
	$layout_blocks	= get_theme_mod( 'blog_single_composer', $defaults );
	$layout_blocks	= apply_filters( 'wpex_single_blog_post_blocks', $layout_blocks );
	$layout_blocks	= ! is_array( $layout_blocks ) ? explode( ',', $layout_blocks ) : $layout_blocks;

	// Loop through blocks
	foreach ( $layout_blocks as $wpex_block ) :

		// Post title
		if ( 'title_meta' == $wpex_block ) {

			// Display title
			if ( 'custom_text' == get_theme_mod( 'blog_single_header', 'custom_text' ) ) { ?>
				<h1 class="single-post-title"><?php the_title(); ?></h1>
			<?php }

			// Display post meta
			get_template_part( 'partials/meta/meta', 'blog' );

		}

		// Featured Media - featured image, video, gallery, etc
		elseif ( 'featured_media' == $wpex_block ) {
			if ( ! $password_required && ! get_post_meta( $post_id, 'wpex_post_media_position', true ) ) {
				$post_format = $post_format ? $post_format : 'thumbnail';
				get_template_part( 'partials/blog/media/blog-single', $post_format );
			}
		}

		// Post Series
		elseif ( 'post_series' == $wpex_block ) {
			get_template_part( 'partials/blog/blog-single', 'series' );
		}

		// Get post content for all formats except quote && link
		elseif ( 'the_content' == $wpex_block && 'quote' != $post_format ) { ?>

			<div class="entry clr">
				<?php the_content(); ?>
			</div>

			<?php
			// Link pages when using <!--nextpage-->
			wp_link_pages( array(
				'before'		=> '<div class="page-links clr">',
				'after'			=> '</div>',
				'link_before'	=> '<span>',
				'link_after'	=> '</span>'
			) );

		}

		// Post Tags
		elseif ( 'post_tags' == $wpex_block && ! $password_required ) {
			the_tags( '<div class="post-tags clr">','','</div>' );
		}

		// Social sharing links
		elseif ( 'social_share' == $wpex_block
			&& get_theme_mod( 'blog_social_share', true )
			&& ! $password_required ) {
				wpex_social_share( $post_id );
		}

		// Author bio
		elseif ( 'author_bio' == $wpex_block
			&& get_the_author_meta( 'description' )
			&& 'hide' != get_post_meta( $post_id, 'wpex_post_author', true )
			&& ! $password_required ) {
				get_template_part( 'author-bio' );
		}

		// Displays related posts
		elseif ( 'related_posts' == $wpex_block ) {
			get_template_part( 'partials/blog/blog-single', 'related' );
		}

		// Get the post comments & comment_form
		elseif ( 'comments' == $wpex_block ) {
			comments_template();
		}

	endforeach;

endif;