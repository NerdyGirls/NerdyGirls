<?php
/**
 * The template for displaying Portfolio Tag archives
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
					<div id="portfolio-entries" class="<?php echo wpex_get_portfolio_wrap_classes(); ?>">
						<?php
						$wpex_count = 0;
						if ( wpex_portfolio_match_height() ) :
							$wpex_total_posts = 0;
						endif;
						// Loop through the posts
						while ( have_posts() ) : the_post();
							// Get entry columns number
							$wpex_entry_columns = get_theme_mod( 'portfolio_entry_columns', '4' );
							// Add extra rows for equal heights grid
							if ( wpex_portfolio_match_height() ) :
								if ( 0 == $wpex_count ) { ?>
									<div class="match-height-row clr">
								<?php }
								$wpex_total_posts++;
							endif;
							$wpex_count++;
							// Get the correct template file for this post type
							get_template_part( 'partials/portfolio/portfolio', 'entry' );
							// Clear counter/floats
							if ( $wpex_count == $wpex_entry_columns ) :
								// Close row for the fit rows style blog
								if ( wpex_portfolio_match_height() ) {
									echo '</div><!-- .match-height-row -->';
								}
								$wpex_count=0;
							endif;
						endwhile;
						// Make sure row is closed for the fit rows style blog
						if ( wpex_portfolio_match_height() ) :
							if ( '4' == $wpex_entry_columns && ( $wpex_total_posts % 4 != 0 ) ) {
								echo '</div><!-- .match-height-row -->';
							}
							if ( '3' == $wpex_entry_columns && ( $wpex_total_posts % 3 != 0 ) ) {
								echo '</div><!-- .match-height-row -->';
							}
							if ( '2' == $wpex_entry_columns && ( $wpex_total_posts % 2 != 0 ) ) {
								echo '</div><!-- .match-height-row -->';
							}
						endif; ?>
					</div><!-- #portfolio-entries -->
					<div class="clr"></div>
					<?php wpex_pagination(); ?>
				</div><!-- #content -->
			</section><!-- #primary -->
		<?php endif; ?>
		<?php get_sidebar(); ?>
	</div><!-- #content-wrap -->

<?php get_footer(); ?>