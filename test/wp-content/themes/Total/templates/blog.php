<?php
/**
 * Template Name: Blog
 *
 * @package		Total
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since 		Total 1.0.0
 */

get_header(); ?>

	<?php if ( has_post_thumbnail() && get_theme_mod( 'page_featured_image' ) ) : ?>
		<div id="page-featured-img" class="clr">
			<?php the_post_thumbnail(); ?>
		</div><!-- #page-featured-img -->
	<?php endif; ?>

	<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class(); ?>">
		<section id="primary" class="content-area clr">
			<div id="content" class="site-content clr" role="main">
				<?php
				// Display blog post content
				while ( have_posts() ) : the_post(); ?>
					<div class="entry-content entry clr">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
				<?php endwhile; ?>
				<?php
				global $post, $paged, $more;
				$more = 0;
				if ( get_query_var( 'paged' ) ) {
					$paged = get_query_var( 'paged' );
				} else if ( get_query_var( 'page' ) ) {
					$paged = get_query_var( 'page' );
				} else {
					$paged = 1;
				}
				// Query posts
				$wp_query = new WP_Query(
					array(
						'post_type'			=> 'post',
						'paged'				=> $paged,
						'category__not_in'	=> wpex_blog_exclude_categories( true ),
					)
				);
				if( $wp_query->posts ) : ?>
					<div id="blog-entries" class="clr <?php wpex_blog_wrap_classes(); ?>">
						<?php
						// Start counters to create rows
						$wpex_total_posts	= 0;
						$wpex_count			= 0;
						// Loop through posts
						foreach( $wp_query->posts as $post ) : setup_postdata( $post );
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
							if ( wpex_blog_entry_columns() == $wpex_count ) {
								// Close row for the fit rows style blog
								if ( 'grid-entry-style' == wpex_blog_fit_rows() ) {
									echo '</div><!-- .row -->';
								}
								$wpex_count=0;
							}
						// End loop
						endforeach;
						// Make sure row is closed for the fit rows style blog
						if ( 'grid-entry-style' == wpex_blog_fit_rows() ) {
							if ( '4' == wpex_blog_entry_columns() && ( $wpex_total_posts % 4 != 0 ) ) {
								echo '</div><!-- .row -->';
							}
							if ( '3' == wpex_blog_entry_columns() && ( $wpex_total_posts % 3 != 0 ) ) {
								echo '</div><!-- .row -->';
							}
							if ( '2' == wpex_blog_entry_columns() && ( $wpex_total_posts % 2 != 0 ) ) {
								echo '</div><!-- .row -->';
							}
						} ?>
					</div><!-- #blog-entries -->
					<?php
					// Display pagination - see function/pagination.php
					wpex_blog_pagination();
				// End if $wp_query->posts
				endif;
				// Reset the custom query data
				wp_reset_postdata(); wp_reset_query(); ?>
			</div><!-- #content -->
		</section><!-- #primary -->
		<?php get_sidebar(); ?>
	</div><!-- #content-wrap -->

<?php get_footer(); ?>