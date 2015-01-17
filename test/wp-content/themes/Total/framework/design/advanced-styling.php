<?php
/**
 * Advanced Styling options that require extra checks
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 * @version		1.0.1
 */

if ( ! function_exists( 'wpex_advanced_styling' ) ) {
	function wpex_advanced_styling( $output ) {

		// Get post id
		$post_id = wpex_get_the_id();

		// Define main vars	
		$css			= '';
		$add_css		= '';
		//$main_layout	= wpex_main_layout( $post_id );

		/*-----------------------------------------------------------------------------------*/
		/*	- Fixed Header Height
		/*-----------------------------------------------------------------------------------*/
		$header_height = '';
		if ( 'one' == get_theme_mod( 'header_style', 'one' ) && ! wpex_is_overlay_header_enabled( $post_id ) ) {
			$header_top_padding		= intval( get_theme_mod( 'header_top_padding' ) );
			$header_bottom_padding	= intval( get_theme_mod( 'header_bottom_padding' ) );
			$header_height			= intval( get_theme_mod( 'header_height' ) );
			if ( $header_height && '0' != $header_height && 'auto' != $header_height ) {
				if ( $header_top_padding || $header_bottom_padding ) {
					$header_height_plus_padding = $header_height + $header_top_padding + $header_bottom_padding;
				} else {
					$header_height_plus_padding = $header_height + '60';
				}
				$css .= '.header-one #site-header {
							height: '. $header_height .'px;
						}

						.header-one #site-navigation-wrap,
						.navbar-style-one .dropdown-menu > li > a {
						 	height:'. $header_height_plus_padding .'px
						}

						.navbar-style-one .dropdown-menu > li > a {
							line-height:'. $header_height_plus_padding .'px
						}

						.header-one #site-logo,
						.header-one #site-logo a{
							height:'. $header_height .'px;line-height:'. $header_height .'px
						}';
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Logo
		/*-----------------------------------------------------------------------------------*/
		// Reset $add_css var
		$add_css = '';

		// Logo top/bottom margins only if custom header height is empty
		if ( ! $header_height ) {

			// Logo top margin
			$margin = intval( get_theme_mod( 'logo_top_margin' ) );
			if ( '' != $margin && '0' != $margin ) {
				if ( $header_height && '0' != $header_height && 'auto' != $header_height && get_theme_mod( 'custom_logo', false, 'url' ) ) {
					$add_css .= 'padding-top: '. $margin .'px;';
				} else {
					$add_css .= 'margin-top: '. $margin .'px;';
				}
			}
			
			// Logo bottom margin
			$margin = intval( get_theme_mod( 'logo_bottom_margin' ) );
			if ( '' != $margin && '0' != $margin) {
				if ( $header_height && '0' != $header_height && 'auto' != $header_height && get_theme_mod( 'custom_logo', false, 'url' ) ) {
					$add_css .= 'padding-bottom: '. $margin .'px;';
				} else {
					$add_css .= 'margin-bottom: '. $margin .'px;';
				}
			}

		}

		// #site-logo css
		if ( $add_css ) {
			$css .= '#site-logo {'. $add_css .'}';
			$add_css = '';
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Logo Max Widths
		/*-----------------------------------------------------------------------------------*/

		// Desktop
		if ( $width = get_theme_mod( 'logo_max_width' ) ) {
			$css .= '@media only screen and (min-width: 960px) {
						#site-logo {
							max-width: '. $width .';
						}
					}';
		}

		// Tablet Portrait
		if ( $width = get_theme_mod( 'logo_max_width_tablet_portrait' ) ) {
			$css .= '@media only screen and (min-width: 768px) and (max-width: 959px) {
						#site-logo {
							max-width: '. $width .';
						}
					}';
		}

		// Phone
		if ( $width = get_theme_mod( 'logo_max_width_phone' ) ) {
			$css .= '@media only screen and (max-width: 767px) {
						#site-logo {
							max-width: '. $width .';
						}
					}';
		}


		/*-----------------------------------------------------------------------------------*/
		/*	- Other
		/*-----------------------------------------------------------------------------------*/

		// Fix for Fonts In the Visual Composer
		$css .='.wpb_row
				.fa:before {
					box-sizing:content-box!important;
					-moz-box-sizing:content-box!important;
					-webkit-box-sizing:content-box!important;
				}';

		// Remove header border if custom color is set
		if ( get_theme_mod( 'header_background' ) ) {
			$css .='.is-sticky #site-header{border-color:transparent;}';
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Output CSS
		/*-----------------------------------------------------------------------------------*/
		if ( ! empty( $css ) ) {
			$css = '/*ADVANCED STYLING*/'. $css;
			$output .= $css;
		}

		// Return output
		return $output;
		
	}
}
add_filter( 'wpex_head_css', 'wpex_advanced_styling' );