<?php
/**
 * The template used for single portfolio posts.
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
				<div id="content" class="site-content clr" role="main">
					<?php
					// Display portfolio single media if enabled in the admin
					get_template_part( 'partials/portfolio/portfolio-single', 'media' ); ?>
					<article class="entry clr">
						<?php the_content(); ?>
					</article><!-- .entry clr -->
					<?php
					// Social Sharing links
					if ( function_exists( 'wpex_social_share' ) && get_theme_mod( 'social_share_portfolio', false ) ) :
						wpex_social_share( get_the_ID() );
					endif; ?>
					<?php
					// Get comments & comment form if enabled for portfoliop posts
					if ( get_theme_mod( 'portfolio_comments' ) ) : ?>
						<div id="portfolio-post-comments" class="clr">
							<?php comments_template(); ?>
						</div><!-- #portfolio-post-comments -->
						<?php endif ?>
					<?php
					// Related Portfolio Items
					get_template_part( 'partials/portfolio/portfolio-single', 'related' ); ?>
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
		if ( get_theme_mod( 'portfolio_next_prev', true ) ) :
			get_template_part( 'partials/next-prev', get_post_type() );
		endif ?>
	<?php endwhile; ?>
<?php get_footer();?>