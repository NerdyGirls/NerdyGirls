<?php
/**
 * Template for the Lightbox + View Text overlay style
 *
 * @package		Total
 * @subpackage	Partials/Overlays
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

// Only used for outside position
if ( 'outside_link' != $position ) {
	return;
} ?>

<div class="overlay-view-lightbox-text">
	<div class="overlay-view-lightbox-text-inner clr">
		<div class="overlay-view-lightbox-text-buttons clr">
			<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>" class="wpex-lightbox" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>"><?php _e( 'Zoom', 'wpex' ); ?><span class="fa fa-search"></span></a>
			<a href="<?php the_permalink(); ?>" class="view-post" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>"><?php _e( 'View', 'wpex' ); ?><span class="fa fa-arrow-right"></span></a>
		</div>
	</div>
</div>