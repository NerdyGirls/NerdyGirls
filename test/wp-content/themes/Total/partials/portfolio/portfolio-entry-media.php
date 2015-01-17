<?php
/**
 * Portfolio entry content template part
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

// If the portfolio post has a video display it
if ( wpex_get_portfolio_featured_video_url() ) : ?>

	<?php wpex_portfolio_post_video(); ?>

<?php
// Otherwise display thumbnail
elseif( has_post_thumbnail() ) : ?>
	
	<?php
	// Get post data
	$esc_title		= esc_attr( the_title_attribute( 'echo=0' ) );

	// Get thumbnail data
	$wpex_image 	= wpex_image( 'array' );
	$image_url		= $wpex_image['url'];
	$image_width	= $wpex_image['width'];
	$image_height	= $wpex_image['height']; ?>

	<div class="portfolio-entry-media <?php echo wpex_overlay_classname(); ?>">
		<a href="<?php the_permalink(); ?>" title="<?php echo $esc_title; ?>" class="portfolio-entry-media-link">
			<img src="<?php echo $image_url; ?>" title="<?php echo $esc_title; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" class="portfolio-entry-img" />
			<?php
			// Display inner overlay
			wpex_overlay( 'inside_link' ); ?>
		</a>
		<?php
		// Display outer overlay
		wpex_overlay( 'outside_link' ); ?>
	</div><!-- .portfolio-entry-media -->

<?php endif; ?>