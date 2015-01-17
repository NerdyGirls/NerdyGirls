<?php
/**
 * Display page slider based on meta option
 *
 * @package		Total
 * @subpackage	Framework/Sliders
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Gets slider position based on wpex_post_slider_shortcode_position custom field
 *
 * @since Total 1.5.1
 */
if ( ! function_exists( 'wpex_post_slider_position' ) ) {
	function wpex_post_slider_position() {
		$slider_position = '';
		if ( $post_id = wpex_get_the_id() ) {
			$slider_position = get_post_meta( $post_id, 'wpex_post_slider_shortcode_position', true );
		}
		$slider_position = $slider_position ? $slider_position : 'below_title';
		$slider_position = apply_filters( 'wpex_post_slider_position', $slider_position );
		return $slider_position;
	}
}

/**
 * Returns correct post slider
 *
 * @since Total 1.6.0
 */
if ( ! function_exists( 'wpex_post_slider_shortcode' ) ) {
	function wpex_post_slider_shortcode( $post_id = '' ) {
		if ( $slider = get_post_meta( $post_id, 'wpex_post_slider_shortcode', true ) ) {
			$slider = $slider;
		} elseif( get_post_meta( $post_id, 'wpex_page_slider_shortcode', true ) ) {
			$slider = get_post_meta( $post_id, 'wpex_page_slider_shortcode', true );
		}
		$slider = apply_filters( 'wpex_post_slider_shortcode', $slider );
		return $slider;
	}
}

/**
 * Outputs page/post slider based on the wpex_post_slider_shortcode custom field
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_post_slider' ) ) {
	function wpex_post_slider( $post_id = '', $postion = '' ) {

		// Get post ID
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Return if no post ID
		if( ! $post_id ) {
			return;
		}

		// Get the Slider shortcode
		$slider = wpex_post_slider_shortcode( $post_id );

		// Return if there isn't a slider defined
		if ( ! $slider ) {
			return;
		}

		// Disable on mobile
		if ( 'on' == get_post_meta( $post_id, 'wpex_disable_post_slider_mobile', true ) && wp_is_mobile() ) {
			return;
		}

		// Get slider alternative
		$slider_alt = get_post_meta( $post_id, 'wpex_post_slider_mobile_alt', true );

		// Check if alider alternative for mobile custom field has a value
		if ( $slider_alt ) {

			// Cleanup validation for old Redux system
			if ( is_array( $slider_alt ) && ! empty( $slider_alt['url'] ) ) {
				$slider_alt	= $slider_alt['url'];
			}

			// Mobile slider alternative link
			$slider_alt_url	= get_post_meta( $post_id, 'wpex_post_slider_mobile_alt_url', true );

			// Mobile slider alternative link target
			if ( $slider_alt_target = get_post_meta( $post_id, 'wpex_post_slider_mobile_alt_url_target', true ) ) {
				$slider_alt_target = 'target="_'. $slider_alt_target .'"';
			}
		}

		// Otherwise set all vars to empty
		else {
			$slider_alt = $slider_alt_url = $slider_alt_target = NULL;;
		}

		// Get post slider bottom margin
		$margin = get_post_meta( $post_id, 'wpex_post_slider_bottom_margin', true );
		
		// Display Slider
		if ( '' != $slider ) { ?>
			<div class="page-slider clr">
				<?php
				// Mobile slider
				if ( wp_is_mobile() && $slider_alt ) {
					if ( $slider_alt_url ) { ?>
						<a href="<?php echo esc_url( $slider_alt_url ); ?>" title=""<?php echo $slider_alt_target; ?>>
							<img src="<?php echo $slider_alt; ?>" class="page-slider-mobile-alt" alt="<?php echo the_title(); ?>" />
						</a>
					<?php } else { ?>
						<img src="<?php echo $slider_alt; ?>" class="page-slider-mobile-alt" alt="<?php echo the_title(); ?>" />
					<?php } ?>
				<?php }
				// Desktop slider
				else {
					echo do_shortcode( $slider );
				} ?>
			</div><!-- .page-slider -->
			<?php if ( $margin ) { ?>
				<div style="height:<?php echo intval( $margin ); ?>px;"></div>
			<?php } ?>
		<?php }

	}
}