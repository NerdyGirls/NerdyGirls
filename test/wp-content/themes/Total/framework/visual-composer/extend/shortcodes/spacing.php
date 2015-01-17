<?php
/**
 * Registers the skillbar shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( ! function_exists('vcex_spacing_shortcode') ) {
	function vcex_spacing_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'size'			=> '20px',
			'class'			=> '',
			'visibility'	=> '',
		),
		$atts ) );

		// Core class
		$classes = 'vcex-spacing';

		// Custom Class
		if ( $class ) {
			$classes .= ' '. $class;
		}

		// Visiblity Class
		if ( $visibility ) {
			$classes .= ' '. $visibility;
		}

		// Front-end composer class
		if ( wpex_is_front_end_composer() ) {
			$classes .= ' vc-spacing-shortcode';
		}

		// Return spacing HTML
		return '<div class="'. $classes .'" style="height: '. $size .'"></div>';

	}
}
add_shortcode( 'vcex_spacing', 'vcex_spacing_shortcode' );

if ( ! function_exists( 'vcex_spacing_shortcode_vc_map' ) ) {
	function vcex_spacing_shortcode_vc_map() {
		vc_map( array(
			'name'					=> __( 'Spacing', 'wpex' ),
			'description'			=> __( 'Adds spacing anywhere you need it.', 'wpex' ),
			'base'					=> 'vcex_spacing',
			'category'				=> WPEX_THEME_BRANDING,
			'icon'					=> 'vcex-spacing',
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Spacing', 'wpex' ),
					'param_name'	=> 'size',
					'value'			=> '30px',
				),
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Classname', 'wpex' ),
					'param_name'	=> 'class',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Visibility', 'wpex' ),
					'param_name'	=> 'visibility',
					'value'			=> wpex_visibility_array(),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_spacing_shortcode_vc_map' );