<?php
/**
 * The Template for displaying all non-theme post type content
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.0
 */
?>

<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class(); ?>">
	<section id="primary" class="content-area clr">
		<div id="content" class="site-content clr" role="main">
			<?php
			// Display featured image
			if ( has_post_thumbnail() ) :
				$wpex_image = wpex_image( 'array' ); ?>
				<div id="post-media" class="clr">
					<img src="<?php echo $wpex_image['url']; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
				</div><!-- #post-media -->
			<?php endif; ?>
			<article class="entry clr">
				<?php the_content(); ?>
			</article><!-- .entry -->
			<?php
			// Link pages when using <!--nextpage-->
			wp_link_pages( array(
				'before'		=> '<div class="page-links clr">',
				'after'			=> '</div>',
				'link_before'	=> '<span>',
				'link_after'	=> '</span>',
			) );
			// Social sharing links
			// wpex_social_share();
			// Get the post comments & comment_form
			comments_template(); ?>
		</div><!-- #content -->
	</section><!-- #primary -->
	<?php
	// Get site sidebar
	get_sidebar(); ?>
</div><!-- .container -->
<?php
/**
 * Displays the next and previous links
 * You can create a new file called next-prev-{post_type}.php to override it
 * for any post type.
 *
 * @since 1.6.0
 */
get_template_part( 'partials/next-prev', get_post_type() ); ?>