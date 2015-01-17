<?php
/**
 * The Template for displaying standard post type content
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.0
 */
?>

<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class( get_the_ID() ); ?>">
	<?php
	// Display the post media above the post (this is a meta option)
	if ( 'above' == get_post_meta( get_the_ID(), 'wpex_post_media_position', true ) && ! post_password_required() ) :
		$post_format = get_post_format() ? get_post_format() : 'thumbnail';
		get_template_part( 'partials/blog/media/blog-single', $post_format );
	endif; ?>
	<section id="primary" class="content-area clr">
		<div id="content" class="site-content clr" role="main">
			<article class="single-blog-article clr">
				<?php
				// Get the single blog post layout template part
				get_template_part( 'partials/blog/blog', 'single-layout' ); ?>
			</article><!-- .entry -->
		</div><!-- #content -->
	</section><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .container -->
<?php
/**
 * Displays the next and previous links
 * You can create a new file called next-prev-blog.php to override it
 * for any post type.
 *
 * @since 1.6.0
 */
if ( get_theme_mod( 'blog_next_prev', true ) ) :
	get_template_part( 'partials/next-prev', 'blog' );
endif ?>