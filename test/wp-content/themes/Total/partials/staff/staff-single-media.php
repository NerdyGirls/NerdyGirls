<?php
/**
 * Staff single media template part
 *
 * @package		Total
 * @subpackage	Partials/Staff
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
if ( ! get_theme_mod( 'staff_single_media' ) ) {
	return;
}

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="staff-single-media" class="clr">
	<?php
	// Get thumbnail data
	$wpex_image = wpex_image( 'array', '', true ); ?>
		<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" class="wpex-lightbox">
			<img src="<?php echo $wpex_image['url']; ?>" alt="<?php the_title(); ?>" class="staff-single-media-img" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
		</a>
</div><!-- .staff-entry-media -->