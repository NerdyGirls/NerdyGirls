<?php
/**
 * Registers the bullets shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if( ! function_exists('vcex_bullets_shortcode') ) {
	function vcex_bullets_shortcode( $atts, $content = NULL ) {
		extract( shortcode_atts( array(
			'style'	=> ''
		),
		$atts ) );
		return '<div class="vcex-bullets vcex-bullets-' . $style . '">' . do_shortcode( $content ) . '</div>';
	}
}
add_shortcode( 'vcex_bullets', 'vcex_bullets_shortcode' );

if ( ! function_exists( 'vcex_bullets_shortcode_vc_map' ) ) {
	function vcex_bullets_shortcode_vc_map() {
		vc_map( array(
			'name'			=> __( 'Bullets', 'wpex' ),
			'description'	=> __( 'Styled bulleted lists', 'wpex' ),
			'base'			=> 'vcex_bullets',
			'category'		=> WPEX_THEME_BRANDING,
			'icon'			=> 'vcex-bullets',
			'params'		=> array(
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Style', 'wpex' ),
					'param_name'	=> 'style',
					'admin_label'	=> true,
					'value'			=> array(
						__( 'Check', 'wpex')	=> 'check',
						__( 'Blue', 'wpex' )	=> 'blue',
						__( 'Gray', 'wpex' )	=> 'gray',
						__( 'Purple', 'wpex' )	=> 'purple',
						__( 'Red', 'wpex' )		=> 'red',
					),
				),
				array(
					'type'			=> 'textarea_html',
					'heading'		=> __( 'Insert Unordered List', 'wpex' ),
					'param_name'	=> 'content',
					'value'			=> '<ul><li>List 1</li><li>List 2</li><li>List 3</li><li>List 4</li></ul>',
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_bullets_shortcode_vc_map' );