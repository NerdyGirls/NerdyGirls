<?php
/**
 * Staff entry media template part
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

// Exit if post thumbnail not defined
if ( ! has_post_thumbnail() ) {
	return;
} ?>

<?php
// Get thumbnail data
$wpex_image = wpex_image( 'array' ); ?>

<div class="staff-entry-media <?php echo wpex_overlay_classname(); ?> clr">
	<?php
	// Open link
	if ( get_theme_mod( 'staff_links_enable', true ) ) { ?>
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
	<?php } ?>
		<img src="<?php echo $wpex_image['url']; ?>" alt="<?php the_title(); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
		<?php
		// Inside overlay style
		wpex_overlay( 'inside_link' ); ?>
	<?php
	// Close link
	if ( get_theme_mod( 'staff_links_enable', true ) ) echo '</a>'; ?>
	<?php
	// Outer overlay style
	wpex_overlay( 'outside_link' ); ?>
</div><!-- .staff-entry-media -->