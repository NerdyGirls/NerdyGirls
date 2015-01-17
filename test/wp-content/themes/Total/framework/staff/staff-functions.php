<?php
/**
 * Useful global functions for the staff
 *
 * @package		Total
 * @subpackage	Framework/Staff
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Returns correct classes for the staff wrap
 *
 * @since Total 1.5.3
 * @return var $classes
 */
if ( ! function_exists( 'wpex_get_staff_wrap_classes' ) ) {
	function wpex_get_staff_wrap_classes() {
		$classes = array( 'wpex-row', 'clr' );
		$grid_style = get_theme_mod( 'staff_archive_grid_style', 'fit-rows' ) ? get_theme_mod( 'staff_archive_grid_style', 'fit-rows' ) : 'fit-rows';
		$classes[] = 'staff-'. $grid_style;
		return implode( " ",$classes );
	}
}

/**
 * Returns correct classes for the staff grid
 *
 * @since Total 1.5.2
 */
if ( ! function_exists( 'wpex_staff_column_class' ) ) {
	function wpex_staff_column_class( $query ) {
		if ( 'related' == $query ) {
			return wpex_grid_class( get_theme_mod( 'staff_related_columns', '3' ) );
		} else {
			return wpex_grid_class( get_theme_mod( 'staff_entry_columns', '3' ) );
		}
	}
}

/**
 * Checks if match heights are enabled for the staff
 *
 * @since Total 1.5.3
 * @return bool
 */
if ( ! function_exists( 'wpex_staff_match_height' ) ) {
	function wpex_staff_match_height() {
		$grid_style = get_theme_mod( 'staff_archive_grid_style', 'fit-rows' ) ? get_theme_mod( 'staff_archive_grid_style', 'fit-rows' ) : 'fit-rows';
		$columns    = get_theme_mod( 'staff_entry_columns', '4' ) ? get_theme_mod( 'staff_entry_columns', '4' ) : '4';
		if ( 'fit-rows' == $grid_style && get_theme_mod( 'staff_archive_grid_equal_heights' ) && $columns > '1' ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Staff Overlay
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_get_staff_overlay' ) ) {
	function wpex_get_staff_overlay( $id = NULL ) {
		$post_id  = $id ? $id : get_the_ID();
		$position = get_post_meta( get_the_ID(), 'wpex_staff_position', true );
		if ( ! $position ) {
			return;
		} ?>
		<div class="staff-entry-position">
			<span><?php echo $position; ?></span>
		</div><!-- .staff-entry-position -->
		<?php
	}
}

/**
 * Outputs the staff social options
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_get_staff_social' ) ) {
	function wpex_get_staff_social( $atts = NULL ) {
		extract( shortcode_atts( array(
			'link_target'	=> 'blank',
		),
		$atts ) );
		global $post;
		if ( ! $post ) {
			return;
		}
		// Get social profiles array
		$profiles = wpex_staff_social_array();
		ob_start();
		// Do not display if disabled for the archives
		if ( is_tax() && ! get_theme_mod( 'staff_entry_social', '1' ) ) {
			return;
		} ?>
			<div class="staff-social clr">
				<?php
				// Loop through social options
				foreach ( $profiles as $profile ) {
					$url = get_post_meta( $post->ID, $profile['meta'], true );
					// Escape URL for all items except skype
					if ( 'wpex_staff_skype' != $profile['meta'] ) {
						$url = esc_url( $url );
					}
					if ( '' != $url ) { ?>
						<a href="<?php echo $url; ?>" title="<?php echo $profile['label']; ?>" class="staff-<?php echo $profile['key']; ?> tooltip-up" target="_<?php echo $link_target; ?>">
							<span class="<?php echo $profile['icon_class']; ?>"></span>
						</a>
					<?php }
				} ?>
			</div><!-- .staff-social -->
		<?php
		return ob_get_clean();
	}
}
add_shortcode( 'staff_social', 'wpex_get_staff_social' );