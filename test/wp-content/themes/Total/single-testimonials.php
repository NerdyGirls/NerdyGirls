<?php
/**
 * The template used for single testimonial posts.
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class(); ?>">

			<section id="primary" class="content-area clr">
				<div id="content" class="clr site-content" role="main">
					<article class="clr">
						<div class="entry-content entry clr">
							<?php if ( 'blockquote' == get_theme_mod( 'testimonial_post_style', 'blockquote' ) ) : ?>
								<?php get_template_part( 'partials/testimonials/testimonials', 'entry' ); ?>
							<?php else : ?>
								<?php the_content(); ?>
							<?php endif; ?>
						</div><!-- .entry-content -->
					</article><!-- #post -->
					<?php
					/**
					 * Displays comments if enabled
					 *
					 * @since 1.0.0
					 */
					if ( get_theme_mod( 'testimonials_comments' ) && comments_open() ) : ?>
						<section id="testimonials-post-comments" class="clr">
							<?php comments_template(); ?>
						</section><!-- #testimonials-post-comments -->
					<?php endif; ?>
				</div><!-- #content -->
			</section><!-- #primary -->

			<?php get_sidebar(); ?>

		</div><!-- .container -->

		<?php
		/**
		 * Displays the next and previous links
		 * You can create a new file called next-prev-{post_type}.php to override it
		 * for any post type.
		 *
		 * @since 1.6.0
		 */
		if ( get_theme_mod( 'testimonials_next_prev', true ) ) :
			get_template_part( 'partials/next-prev', get_post_type() );
		endif ?>

	<?php endwhile; ?>

<?php get_footer();?>