<?php
/**
 * Portfolio single media template part
 *
 * @package		Total
 * @subpackage	Partials/Portfolio
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If single portfolio media is disabled return
if ( ! get_theme_mod( 'portfolio_single_media' ) ) {
	return;
} ?>

<div id="portfolio-single-media" class="clr">
	<?php
	// Display Post Video if defined
	if ( wpex_get_portfolio_featured_video_url() ) : ?>
		<?php wpex_portfolio_post_video(); ?>
	
	<?php
	// Otherwise display post thumbnail
	elseif ( has_post_thumbnail() ) : ?>

		<?php
		// Get thumbnail data
		$wpex_image = wpex_image( 'array', '', true ); ?>

		<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" class="wpex-lightbox">
			<img src="<?php echo $wpex_image['url']; ?>" alt="<?php the_title(); ?>" class="portfolio-single-media-img" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
		</a>

	<?php endif; ?>
</div><!-- .portfolio-entry-media -->