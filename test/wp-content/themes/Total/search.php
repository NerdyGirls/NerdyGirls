<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

get_header(); ?>

	<div class="container clr">
		<section id="primary" class="content-area clr">
			<div id="content" class="site-content" role="main">
			<?php if ( have_posts() ) : ?>
				<div id="search-entries" class="clr">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php
						// Display blog style search results
						if ( 'blog' == wpex_search_results_style() ) : ?>
							<?php get_template_part( 'partials/blog/blog-entry', 'layout' ); ?>
						<?php
						// Display custom style for search entries
						else : ?>
							<?php get_template_part( 'partials/search/search', 'entry' ); ?>
						<?php endif; ?>
					<?php endwhile; ?>
				</div><!-- #search-entries -->
				<?php wpex_pagination(); ?>
			<?php else : ?>
				<div id="search-no-results" class="clr">
				<?php
				// Display message if there aren't any posts
				_e( 'Sorry, no results were found for this query.', 'wpex' ); ?>
				</div><!-- #search-no-results -->
			<?php endif; ?>
			</div><!-- #content -->
		</section><!-- #primary -->
		<?php get_sidebar(); ?>
	</div><!-- .container -->

<?php get_footer(); ?>