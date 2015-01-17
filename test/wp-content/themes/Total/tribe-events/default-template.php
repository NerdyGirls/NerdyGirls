<?php
/**
 * Default Page Template for "The Events Calendar Plugin"
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.3.8
 */

get_header(); ?>

	<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class( get_the_ID() ); ?>">
		<section id="primary" class="content-area clr">
			<div id="content" class="clr site-content" role="main">
				<article class="clr">
					<div id="tribe-events-pg-template">
						<?php tribe_events_before_html(); ?>
						<?php tribe_get_view(); ?>
						<?php tribe_events_after_html(); ?>
					</div> <!-- #tribe-events-pg-template -->
				</article><!-- #post -->
			</div><!-- #content -->
		</section><!-- #primary -->
		<?php get_sidebar(); ?>
	</div><!-- #content-wrap -->

<?php get_footer(); ?>