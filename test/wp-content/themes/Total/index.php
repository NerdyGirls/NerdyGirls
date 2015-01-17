<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 * @version		1.1.0
 */

get_header(); ?>

	<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class(); ?>">
		<section id="primary" class="content-area clr">
			<div id="content" class="site-content" role="main">
				<?php
				// Display term description
				if ( term_description()
					&& get_theme_mod( 'category_descriptions', true )
					&& 'above_loop' == get_theme_mod( 'category_description_position', 'under_title' ) ) : ?>
					<div class="category-description clr">
						<?php echo term_description(); ?>
					</div><!-- #category-description -->
				<?php endif; ?>
				<?php
				// Display posts if there are in fact posts to display
				if ( have_posts() ) :
					/*-----------------------------------------------------------------------------------*/
					/*	- Standard Post Type Posts (BLOG)
					/*	- See framework/conditionals.php
					/*-----------------------------------------------------------------------------------*/
					if ( wpex_is_blog_query() ) :
						// Some useful vars
						$wpex_entry_columns = wpex_blog_entry_columns(); ?>
						<div id="blog-entries" class="clr <?php wpex_blog_wrap_classes(); ?>">
							<?php
							// Start counters to create rows
							$wpex_total_posts = $wpex_count = 0;
							// Loop through posts
							while ( have_posts() ) : the_post();
								// Add row for the fit rows style blog
								if ( 0 == $wpex_count && wpex_blog_fit_rows() ) { ?>
									<div class="blog-row clr">
								<?php }
								// Add to counters
								$wpex_count++;
								$wpex_total_posts++;
								 // Get correct entry template part
								get_template_part( 'partials/blog/blog-entry', 'layout' );
								// Reset counter
								if ( $wpex_entry_columns == $wpex_count ) {
									// Close row for the fit rows style blog
									if ( 'grid-entry-style' == wpex_blog_fit_rows() ) {
										echo '</div><!-- .row -->';
									}
									$wpex_count=0;
								}
							// End loop
							endwhile;
							// Make sure row is closed for the fit rows style blog
							if ( 'grid-entry-style' == wpex_blog_fit_rows() ) {
								if ( '4' == $wpex_entry_columns && ( $wpex_total_posts % 4 != 0 ) ) {
									echo '</div><!-- .row -->';
								}
								if ( '3' == $wpex_entry_columns && ( $wpex_total_posts % 3 != 0 ) ) {
									echo '</div><!-- .row -->';
								}
								if ( '2' == $wpex_entry_columns && ( $wpex_total_posts % 2 != 0 ) ) {
									echo '</div><!-- .row -->';
								}
							} ?>
						</div><!-- #blog-entries -->
						<?php wpex_blog_pagination(); ?>
					<?php
					/*-----------------------------------------------------------------------------------*/
					/*	- Custom post type archives display
					/*	- All non standard post types use the content-other.php template file
					/*-----------------------------------------------------------------------------------*/
					else : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', 'other' ); ?>
							<?php endwhile; ?>
						<?php wpex_pagination(); ?>
					<?php endif; ?>
				<?php
				// Show message because there aren't any posts
				else : ?>
					<article class="clr"><?php _e( 'No Posts found.', 'wpex' ); ?></article>
				<?php endif; ?>
			</div><!-- #content -->
		</section><!-- #primary -->
		<?php get_sidebar(); ?>
    </div><!-- .container -->
    
<?php get_footer(); ?>