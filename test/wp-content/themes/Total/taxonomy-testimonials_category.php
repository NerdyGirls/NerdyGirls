<?php
/**
 * The template for displaying Testimonials Tags
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

get_header(); ?>

	<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class(); ?>">
		<?php if ( have_posts( ) ) : ?>
			<section id="primary" class="content-area clr">
				<div id="content" class="site-content clr" role="main">
					<div id="testimonials-entries" class="wpex-row clr">
						<?php
						$wpex_count=0;
						// Loop through the posts
						while ( have_posts() ) : the_post();
							// Get the correct template file for this post type
							get_template_part( 'partials/testimonials/testimonials', 'entry' );
						endwhile; ?>
					</div><!-- #testimonials-entries -->
					<?php wpex_pagination(); ?>
				</div><!-- #content -->
			</section><!-- #primary -->
		<?php endif; ?>
		<?php get_sidebar(); ?>
	</div><!-- #content-wrap -->

<?php get_footer(); ?>