<?php
/**
 * Togglebar output
 *
 * @package		Total
 * @subpackage	Partials/Togglebar
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

// Return if toggle bar isn't active on this page
if ( ! wpex_toggle_bar_active() ) {
	return;
}

// Classes for the togglebar wrap
$classes = 'clr';
if ( $animation = get_theme_mod( 'toggle_bar_animation', 'fade' ) ) {
	$classes .= ' toggle-bar-'. $animation;
}
if ( $visibility = get_theme_mod( 'toggle_bar_visibility', 'always-visible' ) ) {
	$classes .= ' '. $visibility;
}

// Get toggle bar page content based on ID
$id = get_theme_mod( 'toggle_bar_page' );
$id	= apply_filters( 'wpex_toggle_bar_page_id', $id );

// Get WPML Page ID
if ( function_exists( 'icl_object_id' ) ) {
	$id = icl_object_id( $id, 'page', false, ICL_LANGUAGE_CODE );
} ?>

<?php
// Output the togglebar content
if ( $id ) : ?>
	<div id="toggle-bar-wrap" class="<?php echo $classes; ?>">
		<div id="toggle-bar" class="clr container">
			<div class="entry clr">
				<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $id ) ); ?>
			</div><!-- .entry -->
		</div><!-- #toggle-bar -->
	</div><!-- #toggle-bar-wrap -->
<?php endif; ?>