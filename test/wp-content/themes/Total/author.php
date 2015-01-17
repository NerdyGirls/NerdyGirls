<?php
/**
 * The template for displaying Author archive pages.
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


// Get site header
get_header(); ?>

	<?php
	// If this author has posts display content area
	if ( have_posts() ) :
		the_post(); ?>

		<header class="page-header archive-header">
			<div class="container clr">
				<h1 class="page-header-title archive-title"><?php printf( __( 'All Posts By %s', 'wpex' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
				<div id="page-header-description" class="clr">
					<?php _e( 'This author has written', 'wpex' ); ?> <?php echo $wp_query->found_posts; ?> <?php _e( 'articles', 'wpex' ); ?>
				</div><!-- #page-header-description -->
				<?php wpex_display_breadcrumbs(); // see functions/breadcrumbs.php ?>
			</div><!-- .container -->
		</header><!-- .archive-header -->

		<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class(); ?>">
			<section id="primary" class="content-area clr">
				<div id="content" class="site-content" role="main">
					<?php rewind_posts(); ?>
					<div id="blog-entries" class="clr <?php wpex_blog_wrap_classes(); ?>">
						<?php
						// Start counters to create rows
						$wpex_total_posts=0;
						$wpex_count=0;
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
							if ( wpex_blog_entry_columns() == $wpex_count ) {
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
					// Display post pagination
					wpex_blog_pagination(); ?>
				</div><!-- #content -->
			</section><!-- #primary -->
			<?php
			// Get site footer
			get_sidebar(); ?>
		</div><!-- #content-wrap -->

	<?php endif; ?>

<?php
// Get site footer
get_footer(); ?>