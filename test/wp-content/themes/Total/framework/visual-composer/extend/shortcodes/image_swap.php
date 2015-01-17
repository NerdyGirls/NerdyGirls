<?php
/**
 * Registers the image swap shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_image_swap_shortcode' ) ) {
	function vcex_image_swap_shortcode($atts) {
		
		// Define shortcode params
		extract( shortcode_atts( array(
				'unique_id'			=> '',
				'primary_image'		=> 'true',
				'secondary_image'	=> '',
				'border_radius'		=> '',
				'link'				=> '',
				'link_title'		=> '',
				'link_target'		=> '_self',
				'link_tooltip'		=> '',
				'img_width'			=> '9999',
				'img_height'		=> '9999',
				'img_rendering'		=> '',
			), $atts ) );

			$img_crop = $img_width >= '9999' ? false : true;

			// Primary Image
			$primary_image = wp_get_attachment_url( $primary_image );
			$primary_image_alt = strip_tags( get_post_meta( $primary_image, '_wp_attachment_image_alt', true ) );
			if ( function_exists( 'wpex_image_resize' ) ) {
				$primary_image = wpex_image_resize( $primary_image, intval($img_width),  intval($img_height), $img_crop );
			}

			// Secondary Image
			$secondary_image = wp_get_attachment_url( $secondary_image );
			$secondary_image_alt = strip_tags( get_post_meta( $secondary_image, '_wp_attachment_image_alt', true ) );
			if ( function_exists( 'wpex_image_resize' ) ) {
				$secondary_image = wpex_image_resize( $secondary_image, intval($img_width),  intval($img_height), $img_crop );
			}

			// Extra classes
			$classes = 'vcex-image-swap clr';
			if ( '' != $border_radius ) {
				$border_radius = 'style="border-radius:'. $border_radius .';"';
			}
			if ( $img_rendering ) {
				$classes .= ' vcex-image-rendering-'. $img_rendering;
			}

			// Output
			ob_start(); ?>
				<div class="<?php echo $classes; ?>">
					<?php if ( $link ) { ?>
						<a href="<?php echo esc_url($link); ?>" title="<?php echo $link_title; ?>" target="<?php echo $link_target; ?>" class="vcex-image-swap-link <?php if ( 'yes' == $link_tooltip ) echo 'tooltip-up'; ?>">
					<?php } ?>
						<img src="<?php echo $primary_image; ?>" alt="<?php echo $primary_image_alt; ?>" class="vcex-image-swap-primary" <?php echo $border_radius; ?> />
						<img src="<?php echo $secondary_image; ?>" alt="<?php echo $secondary_image_alt; ?>" class="vcex-image-swap-secondary" <?php echo $border_radius; ?> />
					<?php if ( $link ) { ?>
						</a>
					<?php } ?>
				</div>
		<?php
		return ob_get_clean();
	}
}
add_shortcode( "vcex_image_swap", "vcex_image_swap_shortcode" );

if ( ! function_exists( 'vcex_image_swap_shortcode_vc_map' ) ) {
	function vcex_image_swap_shortcode_vc_map() {
		$vc_img_rendering_url = 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering';
		vc_map( array(
			'name'					=> __( 'Image Swap', 'wpex' ),
			'description'			=> __( 'Double Image Hover Effect', 'wpex' ),
			'base'					=> 'vcex_image_swap',
			'icon' 					=> 'vcex-image-swap',
			'category'				=> WPEX_THEME_BRANDING,
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Unique Id', 'wpex' ),
					'param_name'	=> 'unique_id',
					'value'			=> '',
				),
				array(
					'type'			=> 'attach_image',
					'heading'		=> __( 'Primary Image', 'wpex' ),
					'param_name'	=> 'primary_image',
				),
				array(
					'type'			=> 'attach_image',
					'heading'		=> __( 'Secondary Image', 'wpex' ),
					'param_name'	=> 'secondary_image',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Image Crop Width', 'wpex' ),
					'param_name'	=> 'img_width',
					'value'			=> '9999',
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Image Crop Height', 'wpex' ),
					'param_name'	=> 'img_height',
					'value'			=> '9999',
					'description'	=> __( 'Enter a height in pixels.Images must be the same size for the swap to work correctly.', 'wpex' ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Border Radius', 'wpex' ),
					'param_name'	=> 'border_radius',
					'value'			=> '4px',
						'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Rendering', 'wpex' ),
					'param_name'	=> 'img_rendering',
					'value'			=> vcex_image_rendering(),
					'description'	=> sprintf( __( 'Image-rendering CSS property provides a hint to the user agent about how to handle its image rendering. For example when scaling down images they tend to look a bit fuzzy in Firefox, setting image-rendering to crisp-edges can help make the images look better. <a href="%s">Learn more</a>.', 'wpex' ), esc_url( $vc_img_rendering_url ) ),
						'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Link', 'wpex' ),
					'param_name'	=> 'link',
					'group'			=> __( 'Link', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Link Title', 'wpex' ),
					'param_name'	=> 'link_title',
					'group'			=> __( 'Link', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Link Target', 'wpex' ),
					'param_name'	=> 'link_target',
					'value'			=> array(
						__('Same window', 'wpex' )	=> '_self',
						__('New window', 'wpex' )	=> '_blank'
					),
					'group'			=> __( 'Link', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Enable Tooltip?', 'wpex' ),
					'param_name'	=> 'link_tooltip',
					'value'			=> array(
						__( 'No', 'wpex' )	=> 'no',
						__( 'Yes', 'wpex' )	=> 'yes'
					),
					'group'			=> __( 'Link', 'wpex' ),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_image_swap_shortcode_vc_map' );