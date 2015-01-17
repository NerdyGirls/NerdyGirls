<?php
/**
 * Staff entry content template part
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

// Return if disabled for standard entries
if ( ! is_singular( 'staff' ) && ! get_theme_mod( 'staff_entry_details', true ) ) {
		return;
}

// Return if disabled for related entries
if ( is_singular( 'staff' ) && ! get_theme_mod( 'staff_related_excerpts', true ) ) {
	return;
} ?>

<div class="staff-entry-details">
	<?php
	// Match Height div
	if ( wpex_staff_match_height() ) { ?>
	<div class="match-height-content">
	<?php } ?>
	<h2 class="staff-entry-title">
	<?php if ( get_theme_mod( 'staff_links_enable', true ) ) { ?>
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a>
	<?php } else { ?>
		<?php the_title(); ?>
	<?php } ?>
	</h2>
	<div class="staff-entry-excerpt clr">
		<?php
		// Display excerpt
		$args = array(
			'length'	=> get_theme_mod( 'staff_entry_excerpt_length', '20'),
			'readmore'	=> false,
		);
		wpex_excerpt( $args ); ?>
	</div><!-- .staff-entry-excerpt -->
	<?php
	// Displays social links for current staff member
	// @ functions/staff/staff-functions.php
	echo wpex_get_staff_social(); ?>
	<?php
	// Close Match Height div
	if ( wpex_staff_match_height() ) { ?>
	</div>
	<?php } ?>
</div><!-- .staff-entry-details -->