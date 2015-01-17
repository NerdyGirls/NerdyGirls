<?php
/**
 * Custom functions for use with Visual Composer Modules
 *
 * @package		Total
 * @subpackage	Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.0
 */

/**
 * Outputs custom excerpt for VC extensions
 *
 * @since Total 1.4.0
 */
if ( ! function_exists( 'vcex_excerpt' ) ) {
	function vcex_excerpt( $array = array() ) {

		// Globals
		global $post;

		// Set main function vars
		$output					= '';
		$post_id				= isset( $array['post_id'] ) ? $array['post_id'] : $post->ID;
		$length					= isset( $array['length'] ) ? $array['length'] : '30';
		$readmore				= isset( $array['readmore'] ) ? $array['readmore'] : false;
		$read_more_text			= isset( $array['read_more_text'] ) ? $array['read_more_text'] : __( 'view post', 'wpex' );
		$post_id				= isset( $array['post_id'] ) ? $array['post_id'] : '';
		$trim_custom_excerpts	= isset( $array['trim_custom_excerpts'] ) ? $array['trim_custom_excerpts'] : '30';
		$more					= isset( $array['more'] ) ? $array['more'] : '&hellip;';

		// Get post data
		$custom_excerpt	= $post->post_excerpt;
		$post_content	= get_the_content( $post_id );

		// Display password protected error
		if ( post_password_required( $post_id ) ) {
			$password_protected_excerpt = __( 'This is a password protected post.', 'wpex' );
			$password_protected_excerpt = apply_filters( 'wpex_password_protected_excerpt', $password_protected_excerpt );
			echo '<p>'. $password_protected_excerpt .'</p>'; return;
		}
		// Return The Excerpt
		if ( '0' != $length ) {
			// Custom Excerpt
			if ( $custom_excerpt ) {
				if ( '-1' == $length || ! $trim_custom_excerpts ) {
					$output	= $custom_excerpt;
				} else {
					$output	= '<p>'. wp_trim_words( $custom_excerpt, $length, $more ) .'</p>';
				}
			} else {
				// Return the content
				if ( '-1' ==  $length ) {
					return apply_filters( 'the_content', $post_content );
				}
				// Check if text shortcode in post
				if ( strpos( $post_content, '[vc_column_text]' ) ) {
					$pattern = '{\[vc_column_text\](.*?)\[/vc_column_text\]}is';
					preg_match( $pattern, $post_content, $match );
					if( isset( $match[1] ) ) {
						//$excerpt = str_replace('[vc_column_text]', '', $match[0] );
						//$excerpt = str_replace('[/vc_column_text]', '', $excerpt );
						$excerpt	= wp_trim_words( $match[1], $length, $more );
					} else {
						$content	= strip_shortcodes( $post_content );
						$excerpt	= wp_trim_words( $content, $length, $more );
					}
				} else {
					$content	= strip_shortcodes( $post_content );
					$excerpt	= wp_trim_words( $content, $length, $more );
				}
				// Output Excerpt
				$output .= '<p>'. $excerpt .'</p>';
			}

			// Readmore link
			if ( $readmore ) {
				$readmore_link = '<a href="'. get_permalink( $post_id ) .'" title="'.$read_more_text .'" rel="bookmark" class="vcex-readmore theme-button">'. $read_more_text .' <span class="vcex-readmore-rarr">&rarr;</span></a>';
				$output .= apply_filters( 'vcex_readmore_link', $readmore_link );
			}
			
			// Output
			echo $output;
		}
	}
}

/**
 * Get custom excerpt for VC extensions
 *
 * @since Total 1.4
 */
if ( ! function_exists( 'vcex_get_excerpt' ) ) {
	function vcex_get_excerpt( $array = array() ) {

		// Globals
		global $post;

		// Set main function vars
		$output					= '';
		$post_id				= isset( $array['post_id'] ) ? $array['post_id'] : $post->ID;
		$length					= isset( $array['length'] ) ? $array['length'] : '30';
		$readmore				= isset( $array['readmore'] ) ? $array['readmore'] : false;
		$read_more_text			= isset( $array['read_more_text'] ) ? $array['read_more_text'] : __( 'view post', 'wpex' );
		$post_id				= isset( $array['post_id'] ) ? $array['post_id'] : '';
		$trim_custom_excerpts	= isset( $array['trim_custom_excerpts'] ) ? $array['trim_custom_excerpts'] : '30';
		$more					= isset( $array['more'] ) ? $array['more'] : '&hellip;';

		// Get post data
		$custom_excerpt	= $post->post_excerpt;
		$post_content	= get_the_content( $post_id );

		// Display password protected error
		if ( post_password_required( $post_id ) ) {
			$password_protected_excerpt = __( 'This is a password protected post.', 'wpex' );
			$password_protected_excerpt = apply_filters( 'wpex_password_protected_excerpt', $password_protected_excerpt );
			echo '<p>'. $password_protected_excerpt .'</p>'; return;
		}
		// Return The Excerpt
		if ( '0' != $length ) {
			// Custom Excerpt
			if ( $custom_excerpt ) {
				if ( '-1' == $length || ! $trim_custom_excerpts ) {
					$output	= $custom_excerpt;
				} else {
					$output	= '<p>'. wp_trim_words( $custom_excerpt, $length, $more ) .'</p>';
				}
			} else {
				// Return the content
				if ( '-1' ==  $length ) {
					return apply_filters( 'the_content', $post_content );
				}
				// Check if text shortcode in post
				if ( strpos( $post_content, '[vc_column_text]' ) ) {
					$pattern = '{\[vc_column_text\](.*?)\[/vc_column_text\]}is';
					preg_match( $pattern, $post_content, $match );
					if( isset( $match[1] ) ) {
						//$excerpt = str_replace('[vc_column_text]', '', $match[0] );
						//$excerpt = str_replace('[/vc_column_text]', '', $excerpt );
						$excerpt	= wp_trim_words( $match[1], $length, $more );
					} else {
						$content	= strip_shortcodes( $post_content );
						$excerpt	= wp_trim_words( $content, $length, $more );
					}
				} else {
					$content	= strip_shortcodes( $post_content );
					$excerpt	= wp_trim_words( $content, $length, $more );
				}
				// Output Excerpt
				$output .= '<p>'. $excerpt .'</p>';
			}

			// Readmore link
			if ( $readmore ) {
				$readmore_link = '<a href="'. get_permalink( $post_id ) .'" title="'.$read_more_text .'" rel="bookmark" class="vcex-readmore theme-button">'. $read_more_text .' <span class="vcex-readmore-rarr">&rarr;</span></a>';
				$output .= apply_filters( 'vcex_readmore_link', $readmore_link );
			}
			
			// Output
			return $output;
		}
	}
}

/**
 * Image filter styles VC extensions
 *
 * @since Total 1.4.0
 */
if ( ! function_exists( 'vcex_image_filters' ) ) {
	function vcex_image_filters() {
		$filters = array (
			__( 'None', 'wpex' )		=> '',
			__( 'Grayscale', 'wpex' )	=> 'grayscale',
		);
		return apply_filters( 'vcex_image_filters', $filters );
	}
}

/**
 * Image hover styles VC extensions
 *
 * @since Total 1.4.0
 */
if ( ! function_exists( 'vcex_image_hovers' ) ) {
	function vcex_image_hovers() {
		$hovers = array (
			__( 'None','wpex' )				=> '',
			__( 'Grow','wpex' )				=> 'grow',
			__( 'Shrink','wpex' )			=> 'shrink',
			__( 'Side Pan','wpex' )			=> 'side-pan',
			__( 'Vertical Pan','wpex' )		=> 'vertical-pan',
			__( 'Tilt','wpex' )				=> 'tilt',
			__( 'Normal - Blurr','wpex' )	=> 'blurr',
			__( 'Blurr - Normal','wpex' )	=> 'blurr-invert',
			__( 'Sepia','wpex' )			=> 'sepia',
			__( 'Fade Out','wpex' )			=> 'fade-out',
			__( 'Fade In','wpex' )			=> 'fade-in',
		);
		return apply_filters( 'vcex_image_hovers', $hovers );
	}
}

/**
 * Image rendering VC extensions
 *
 * @since Total 1.4.0
 */
if ( ! function_exists( 'vcex_image_rendering' ) ) {
	function vcex_image_rendering() {
		$render = array (
			__( 'Auto','wpex' )			=> '',
			__( 'Crisp Edges','wpex' )	=> 'crisp-edges',
		);
		return apply_filters( 'vcex_image_rendering', $render );
	}
}

/**
 * Overlays VC extensions
 *
 * @since Total 1.4.0
 */
if ( ! function_exists( 'vcex_overlays_array' ) ) {
	function vcex_overlays_array( $style = 'default' ) {
		if ( ! function_exists( 'wpex_overlay_styles_array' ) ) {
			return;
		}
		$overlays = wpex_overlay_styles_array( $style );
		if ( ! is_array( $overlays ) ) {
			return;
		}
		$overlays = array_flip( $overlays );
		return array(
			"type"			=> "dropdown",
			"heading"		=> __( "Image Overlay Style", 'wpex' ),
			"param_name"	=> "overlay_style",
			"value"			=> $overlays,
			'group'			=> __( 'Image', 'wpex' ),
		);
	}
}