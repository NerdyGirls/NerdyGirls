<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
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
	<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class(); ?>">
		<section id="primary" class="content-area clr">
			<div id="content" class="clr site-content" role="main">
				<?php while ( have_posts() ) : the_post(); ?>
					<article class="clr">
						<?php
						// Display featured image if one has been set
						if ( has_post_thumbnail() && get_theme_mod( 'page_featured_image' ) ) : ?>
							<div id="page-featured-img" class="clr">
								<?php the_post_thumbnail( 'full' ); ?>
							</div><!-- #page-featured-img -->
						<?php endif; ?>
						<div class="entry-content entry clr">
							<?php the_content(); ?>
							<?php wp_link_pages( array(
								'before'		=> '<div class="page-links clr">',
								'after'			=> '</div>',
								'link_before'	=> '<span>',
								'link_after'	=> '</span>'
							) ); ?>
						</div><!-- .entry-content -->
						<?php
						// Display social sharing links
						if ( function_exists( 'wpex_social_share' ) && get_theme_mod( 'social_share_pages' ) ) : ?>
							<?php wpex_social_share( get_the_ID() ); ?>
						<?php endif; ?>
					</article><!-- #post -->
					<?php
					// Display comments template if enabled in the admin
					if ( get_theme_mod( 'page_comments' ) ) : ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			</div><!-- #content -->
		</section><!-- #primary -->
		<?php
		// Get sidebar if needed
		get_sidebar(); ?>
	</div><!-- #content-wrap -->
<?php
// Get site footer
get_footer(); ?>