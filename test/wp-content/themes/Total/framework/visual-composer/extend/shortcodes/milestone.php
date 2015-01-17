<?php
/**
 * Registers the milestone shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( !function_exists( 'vcex_milestone_shortcode' ) ) {
	function vcex_milestone_shortcode( $atts, $content = NULL ) {

		extract( shortcode_atts( array(
			'speed'					=> '2500',
			'interval'				=> '50',
			'animated'				=> 'yes',
			'number'				=> '',
			'before'				=> '',
			'after'					=> '',
			'number_size'			=> '54',
			'number_weight'			=> '',
			'number_color'			=> '#bcbcbc',
			'number_bottom_margin'	=> '',
			'caption'				=> '',
			'caption_size'			=> '16',
			'caption_color'			=> '54',
			'caption_font'			=> '',
			'url'					=> '',
			'url_rel'				=> 'nofollow',
			'url_target'			=> 'blank',
		), $atts ) );
		
		// Extra classes
		$classes = 'vcex-milestone vcex-clearfix';
		
		// Load required scripts
		if ( $animated == 'yes' && !wp_is_mobile() ) {
			$classes .= ' vcex-animated-milestone';
		}

		// Number classes
		$number_classes = '';
		if ( $number_weight ) {
			$number_classes .= ' font-weight-'. $number_weight;
		}

		// Caption classes
		$caption_classes = '';
		if ( $caption_font ) {
			$caption_classes .= ' font-weight-'. $caption_font;
		}
		
		// NUmber Style
		$number_style = '';
		if ( $number_color ) {
			$number_style .= 'color: '. $number_color .';';
		}
		if ( $number_size ) {
			$number_style .= 'font-size: '. intval( $number_size ) .'px;';
		}
		if ( $number_bottom_margin ) {
			$number_style .= 'margin-bottom: '. intval( $number_bottom_margin ) .'px;';
		}
		if ( $number_style ) {
			$number_style = 'style="' . esc_attr($number_style) . '"';
		}
		
		// Caption Style
		$caption_style = '';
		if ( $caption_color ) {
			$caption_style .= 'color: '. $caption_color .';';
		}
		if ( $caption_size ) {
			$caption_style .= 'font-size: '. intval( $caption_size ) .'px;';
		}
		if ( $caption_style ) {
			$caption_style = 'style="' . esc_attr( $caption_style ) . '"';
		}
		
		// Display MileStone
		$output = '<div class="'. $classes .'">';
			$output .= '<div class="vcex-milestone-number" '. $number_style .'>';
				if ( '' != $number ) {
					if ( $before ) {
						$output .= '<span class="vcex-milestone-before">'. $before .'<span>';
					}
					$output .= '<span class="vcex-milestone-time '. $number_classes .'" data-from="0" data-to="'. intval( $number ) .'" data-speed="'. $speed .'" data-refresh-interval="'. $interval .'">';
						$output .= ' '. $number;
					$output .= '</span>';
					if ( $after ) {
						$output .= '<span class="vcex-milestone-after">'. $after .'<span>';
					}
				} else {
					$output .= __( 'Please enter a number!', 'wpex' );
				}
			$output .= '</div>';
			if ( '' != $caption ) {
				if ( $url ) {
					$output .= '<a href="'. esc_url( $url ) .'" rel="'. $url_rel .'" target="_'. $url_target .'" class="vcex-milestone-caption '. $caption_classes .'" '. $caption_style .'>';
				} else {
					$output .= '<div class="vcex-milestone-caption '. $caption_classes .'" '. $caption_style .'>';
				}
				$output .= $caption;
				if ( $url ) {
					$output .= '</a>';
				} else {
					$output .= '</div>';
				}
			}
		$output .= '</div>';
		
		return $output;
	}
}
add_shortcode( 'vcex_milestone', 'vcex_milestone_shortcode' );

if ( ! function_exists( 'vcex_milestone_shortcode_vc_map' ) ) {
	function vcex_milestone_shortcode_vc_map() {
		vc_map( array(
			"name"					=> __( "Milestone", 'wpex' ),
			"description"			=> __( "Animated counter", 'wpex' ),
			"base"					=> "vcex_milestone",
			"icon" 					=> "vcex-milestone",
			'category'				=> WPEX_THEME_BRANDING,
			"params"				=> array(
				array(
					"type"			=> "textfield",
					"admin_label"	=> true,
					"class"			=> "vcex-animated-counter-number",
					"heading"		=> __( "Number", 'wpex' ),
					"param_name"	=> "number",
					"value"			=> "45",
					"description"	=> __( 'Your Milestone.', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Number Before", 'wpex' ),
					"param_name"	=> "before",
					"description"	=> __('Enter content before your milestone (such as $).','wpex'),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Number After", 'wpex' ),
					"param_name"	=> "after",
					"description"	=> __('Enter content after your milestone (such as %).','wpex'),
				),
				array(
					"type"			=> "colorpicker",
					"heading"		=> __( "Number Color", 'wpex' ),
					"param_name"	=> "number_color",
					"value"			=> "#bcbcbc",
					//"description"	=> __('Select a custom color for your milestone number.','wpex'),
					'group'			=> __( "Design", 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Number Font Size", 'wpex' ),
					"param_name"	=> "number_size",
					"value"			=> "54px",
					//"description"	=> __('Enter a custom font size for your milestone. Please enter px or em.','wpex'),
					'group'			=> __( "Design", 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Number Font Weight", 'wpex' ),
					"param_name"	=> "number_weight",
					'group'			=> __( "Design", 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Number Bottom Margin", 'wpex' ),
					"param_name"	=> "number_bottom_margin",
					'group'			=> __( "Design", 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "vcex-animated-counter-caption",
					"heading"		=> __( "Caption", 'wpex' ),
					"param_name"	=> "caption",
					"value"			=> "Awards Won",
					"admin_label"	=> true,
					"description"	=> __('Your milestone caption displays underneath the number.','wpex'),
				),
				array(
					"type"			=> "colorpicker",
					"heading"		=> __( "Caption Color", 'wpex' ),
					"param_name"	=> "caption_color",
					"value"			=> "#898989",
					//"description"	=> __('Select a custom caption color.','wpex'),
					'group'			=> __( "Design", 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Caption Font Size", 'wpex' ),
					"param_name"	=> "caption_size",
					"value"			=> "16px",
					//"description"	=> __('Enter your caption font size. Please enter px or em.','wpex'),
					'group'			=> __( "Design", 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Caption Font Weight", 'wpex' ),
					"param_name"	=> "caption_font",
					'group'			=> __( "Design", 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "URL", 'wpex' ),
					"param_name"	=> "url",
					//"description"	=> __('A custom URL to link your milestone to.','wpex'),
					'group'			=> __( "Link", 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "URL Target", 'wpex' ),
					"param_name"	=> "url_target",
					"value"			=> array(
						__( "Self", "wpex")		=> "self",
						__( "Blank", "wpex" )	=> "blank",
					),
					"dependency"	=> Array(
						'element'	=> "url",
						'not_empty'	=> true
					),
					//"description"	=> __( 'Your link target. Choose self to open the link in the same browser tab and blank to open in a new tab.', 'wpex' ),
					'group'			=> __( "Link", 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "URl Rel", 'wpex' ),
					"param_name"	=> "url_rel",
					"value"			=> array(
						__( "None", "wpex")			=> "none",
						__( "Nofollow", "wpex" )	=> "nofollow",
					),
					"dependency"	=> Array(
						'element'	=> "url",
						'not_empty'	=> true
					),
					//"description"	=> __( 'Select a rel attribute for your link.', 'wpex' ),
					'group'			=> __( "Link", 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "vcex-animated-counter-number",
					"heading"		=> __( "Speed", 'wpex' ),
					"param_name"	=> "speed",
					"value"			=> "2500",
					"description"	=> __('The number of milliseconds it should take to finish counting.','wpex'),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Refresh Interval", 'wpex' ),
					"param_name"	=> "interval",
					"value"			=> "50",
					"description"	=> __('The number of milliseconds to wait between refreshing the counter.','wpex'),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_milestone_shortcode_vc_map' );