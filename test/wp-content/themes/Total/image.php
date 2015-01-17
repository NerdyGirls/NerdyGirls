<?php
/**
 * The template for displaying image attachments.
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

	<div class="container clr">
		<section id="primary" class="content-area full-width">
			<div id="content" class="site-content" role="main">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
					<?php echo wp_get_attachment_image( get_the_ID(), 'full' ); ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="entry clr">
							<?php the_content(); ?>
						</div><!-- .entry -->
					<?php endwhile ?>
				</article><!-- #post -->
			</div><!-- #content -->
		</section><!-- #primary -->
	</div><!-- .container -->

<?php get_footer(); ?>