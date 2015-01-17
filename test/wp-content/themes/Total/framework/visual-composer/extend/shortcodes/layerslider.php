<?php
/**
 * Registers the layerslider shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_layerslider_shortcode_vc_map' ) ) {
	function vcex_layerslider_shortcode_vc_map() {
		vc_map( array(
			'name'					=> __( 'LayerSlider', 'wpex' ),
			'description'			=> __( 'Insert a LayerSlider slider via ID', 'wpex' ),
			'base'					=> 'layerslider',
			'category'				=> WPEX_THEME_BRANDING,
			'icon'					=> 'vcex-layerslider',
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'holder'		=> 'div',
					'heading'		=> __( 'Enter your slider ID', 'wpex' ),
					'param_name'	=> 'id',
					'std'			=> '1',
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_layerslider_shortcode_vc_map' );