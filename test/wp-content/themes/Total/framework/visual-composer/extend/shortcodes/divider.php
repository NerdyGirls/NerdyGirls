<?php
/**
 * Registers the divider shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_divider_shortcode' ) ) {
	function vcex_divider_shortcode( $atts ) {

		extract( shortcode_atts( array(
			'style'					=> 'solid',
			'margin_top'			=> '',
			'margin_bottom'			=> '',
			'class'					=> '',
			'icon'					=> 'None',
			'icon_color'			=> '#222',
			'icon_bg'				=> '',
			'icon_size'				=> '14px',
			'icon_padding'			=> '',
			'icon_border_radius'	=> '',
			'unique_id'				=> '',
			'width'					=> '100%',
			'height'				=> '1px',
			'color'					=> '',
		),
		$atts ) );

		// Main Style
		$add_style = '';
		if ( $width && '100%' != $width ) {
			$add_style .= 'width: '. $width .';';
		}
		if( $margin_bottom ) {
			$add_style .= 'margin-bottom:'. intval( $margin_bottom ) .'px;';
		}
		if ( $margin_top ) {
			$add_style .= 'margin-top:'. intval( $margin_top ) .'px;';
		}
		if ( 'solid' == $style ) {
			if ( $height ) {
				$add_style .= 'border-top-width: '. $height .';';
			}
			if ( $color ) {
				$add_style .= 'border-top-color: '. $color .';';
			}
		}
		if ( $add_style ) {
			$add_style = ' style="' . $add_style . '"';
		}
		
		// Icon Style
		$icon_style = '';
		
		if ( $icon ) {
		
			if( $icon_size ) {
				$icon_style .= 'font-size: '. $icon_size .';';
			}

			if( $icon_border_radius ) {
				$icon_style .= 'border-radius: '. $icon_border_radius .';';
			}
			
			if ( $icon_color && $icon_color !== '#000' ) {
				$icon_style .= 'color: '. $icon_color .';';
			}

			if ( $icon_bg ) {
				$icon_style .= 'background-color: '. $icon_bg .';';
			}

			if ( $icon_padding ) {
				$icon_style .= 'padding: '. $icon_padding .';';
			}
		
		}

		if ( $icon_style ) {
			$icon_style = ' style="' . $icon_style . '"';
		}
		
		// Output
		if ( $icon && 'None' != $icon && 'none' !== $icon ) {
		$output = '<div class="vcex-divider-with-icon '. $style .' '. $class .'" '.$add_style.'><span class="fa fa-'. $icon .'" '. $icon_style .'></span></div>';
		} else {
			$output = '<hr class="vcex-divider '. $style .' '. $class .'" '.$add_style.' />';
		}
		
		return $output;
	}
}
add_shortcode( 'vcex_divider', 'vcex_divider_shortcode' );

if ( ! function_exists( 'vcex_divider_shortcode_vc_map' ) ) {
	function vcex_divider_shortcode_vc_map() {
		vc_map( array(
			"name"					=> __( "Divider", 'wpex' ),
			"description"			=> __( "Line seperator", 'wpex' ),
			"base"					=> "vcex_divider",
			"icon" 					=> "vcex-divider",
			'category'				=> WPEX_THEME_BRANDING,
			'admin_enqueue_css'		=> wpex_font_awesome_css_url(),
			'front_enqueue_css'		=> wpex_font_awesome_css_url(),
			"params"				=> array(
				array(
					'type'			=> "dropdown",
					'admin_label'	=> true,
					'heading'		=> __( "Style", 'wpex' ),
					'param_name'	=> "style",
					"value"			=> array(
						__( "Solid", "wpex")	=> "solid",
						__( "Dashed", "wpex" )	=> "dashed",
						__( "Dotted", "wpex" )	=> "dotted",
						__( "Double", "wpex" )	=> "double",
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Width", 'wpex' ),
					'param_name'	=> "width",
					"value"			=> "100%",
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Height", 'wpex' ),
					'param_name'	=> "height",
					"value"			=> "1px",
					"dependency"	=> Array(
						'element'	=> "style",
						'value'		=> array( 'solid' ),
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Color", 'wpex' ),
					'param_name'	=> "color",
					"value"			=> "",
					"dependency"	=> Array(
						'element'	=> "style",
						'value'		=> array( 'solid' ),
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Margin Top", 'wpex' ),
					'param_name'	=> "margin_top",
					'default'		=> '20px',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Margin Bottom", 'wpex' ),
					'param_name'	=> "margin_bottom",
					'default'		=> '20px',
				),
				array(
					'type'			=> "vcex_icon",
					'heading'		=> __( "Icon", 'wpex' ),
					'param_name'	=> "icon",
					'admin_label'	=> true,
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Icon Color", 'wpex' ),
					'param_name'	=> "icon_color",
					"value"			=> "#000",
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Icon Background", 'wpex' ),
					'param_name'	=> "icon_bg",
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Icon Size", 'wpex' ),
					'param_name'	=> "icon_size",
					"value"			=> "14px",
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Icon Padding", 'wpex' ),
					'param_name'	=> "icon_padding",
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Icon Border Radius", 'wpex' ),
					'param_name'	=> "icon_border_radius",
					'group'			=> __( 'Icon', 'wpex' ),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_divider_shortcode_vc_map' );