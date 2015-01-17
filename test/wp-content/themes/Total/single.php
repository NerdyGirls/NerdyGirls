<?php
/**
 * The Template for displaying all single posts.
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */ ?>

<?php
// Redirect link format if custom link defined
if ( get_post_meta( get_the_ID(), 'wpex_post_link', true ) ) : ?>
	<?php wp_redirect( wpex_permalink(), 301 ); ?>
<?php endif; ?>

<?php get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( 'post' == get_post_type() ) : ?>

			<?php
			// Standard post template file
			get_template_part( 'single', 'standard' ); ?>

		<?php else : ?>

			<?php
			// 3rd party post type template
			get_template_part( 'single', 'other' ); ?>

		<?php endif; ?>

	<?php endwhile; ?>

<?php get_footer(); ?>