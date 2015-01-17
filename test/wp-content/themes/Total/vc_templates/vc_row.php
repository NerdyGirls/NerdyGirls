<?php
$output = $el_class = $add_bg_style = $skin_style = '';
extract( shortcode_atts( array(
	'id'					=> '',
	'el_class'				=> '',
	'css_animation'			=> '',
	'visibility'			=> '',
	'tablet_fullwidth_cols'	=> '',
	'center_row'			=> '',
	'min_height'			=> '',
	'style'					=> '',
	'bg_color'				=> '',
	'bg_image'				=> '',
	'bg_style'				=> '',
	'border_color'			=> '',
	'border_style'			=> '',
	'border_width'			=> '',
	'margin_top'			=> '',
	'margin_bottom'			=> '',
	'margin_left'			=> '',
	'margin_right'			=> '',
	'padding_top'			=> '',
	'padding_bottom'		=> '',
	'padding_left'			=> '',
	'padding_right'			=> '',
	'border'				=> '',
	'video_bg'				=> '',
	'video_bg_mp4'			=> '',
	'video_bg_ogv'			=> '',
	'video_bg_webm'			=> '',
	'video_bg_overlay'		=> 'dashed-overlay',
	'parallax_speed'		=> '',
	'parallax_direction'	=> '',
	'parallax_style'		=> '',
	'parallax_mobile'		=> false,
	'css'					=> '',
	'no_margins'			=> '',
	'column_spacing'		=> '',
), $atts ) );

// Disable on mobile completely
if ( 'visible-desktop' == $visibility && wp_is_mobile() ) {
	return;
}

// Load VC js
wp_enqueue_script( 'wpb_composer_front_js' );

// Get extra classes
$el_class = $this->getExtraClass( $el_class );

// Column sizes
if ( $column_spacing ) {
	$el_class .= ' column-padding-'. $column_spacing;
}

// No margins class
if ( 'true' == $no_margins ) {
	$el_class .= ' no-margins';
}

// Full Width Columns on tablet class
if ( 'yes' == $tablet_fullwidth_cols ) {
	$el_class .= ' tablet-fullwidth-columns';
}

// Custom Row ID
if ( $id ) {
	$id = 'id="'. $id .'"';
}

// Prevent center row when not full-screen
$wpex_post_id = wpex_get_the_ID();
if ( $wpex_post_id && 'full-screen' != wpex_get_post_layout_class( $wpex_post_id ) ) {
	$center_row = false;
}

// Animation
$css_animation_class = $css_animation !=='' ? 'wpb_animate_when_almost_visible wpb_'. $css_animation .'' : '';

// Is parallax allowed?
$parallax_class = '';
if ( wpex_is_front_end_composer() ) {
	$parallax_allowed = false;
} else {
	$parallax_allowed = true;
	if ( $bg_image ) {
		if ( 'parallax-advanced' == $bg_style || 'parallax'  == $bg_style ) {
			$parallax_class = 'row-with-parallax';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/*	- Background Image
/*-----------------------------------------------------------------------------------*/
if ( $bg_image && empty( $video_bg ) ) {
	$bg_img_url = wp_get_attachment_url( $bg_image );
	$bg_style = $bg_style ? $bg_style : 'stretch';
} else {
	$bg_img_url = NULL;
}
if ( $bg_style && $bg_image ) {
	$bg_style_class = 'vcex-background-'. $bg_style;
} else {
	$bg_style_class = '';
}

/*-----------------------------------------------------------------------------------*/
/*	- Advanced Parallax
/*-----------------------------------------------------------------------------------*/
if ( $parallax_allowed ) {

	$parallax_data_attr = $parallax_style_attr = '';

	// Disable parallax on mobile
	if ( wp_is_mobile() && 'on' != $parallax_mobile ) {
		$parallax_allowed = false;
	}

	// Create parallax data attributes and style
	if ( 'parallax-advanced' == $bg_style && $bg_image ) {

		// Define advanced parallax style
		if ( $parallax_style ) {
			$parallax_style = $parallax_style;
		} else {
			$parallax_style = 'fixed-repeat';
		}

		// Parallax Direction
		if ( $parallax_direction ) {
			$parallax_direction = $parallax_direction;
		} else {
			$parallax_direction = 'up';
		}

		// Parallax Speed
		if ( $parallax_speed ) {
			$parallax_speed = $parallax_speed;
		} else {
			$parallax_speed = '0.5';
		}

		// Set parallax data attributes
		if ( $parallax_allowed ) {
			$parallax_data_attr = 'data-direction="'. $parallax_direction .'" data-velocity="-'. abs( $parallax_speed ) .'"';
		}

		// Add parallax styles
		$parallax_style_attr = 'style="background-image: url('. $bg_img_url .');"';

	}

} elseif ( $bg_img_url && 'yes' != $video_bg && 'parallax-advanced' == $bg_style ) {
	$add_bg_style = 'background-image: url('. $bg_img_url .');';
}

/*-----------------------------------------------------------------------------------*/
/*	- Outter Row Style
/*-----------------------------------------------------------------------------------*/
$vc_row_style = '';

if ( $margin_top ) {
	$margin_top		= wpex_sanitize_data( $margin_top, 'px-pct' );
	$vc_row_style	.= 'margin-top:'. $margin_top .';';
}

if ( $margin_bottom ) {
	$margin_bottom	= wpex_sanitize_data( $margin_bottom, 'px-pct' );
	$vc_row_style	.= 'margin-bottom:'. $margin_bottom .';';
}

if ( $margin_left ) {
	$margin_left	= wpex_sanitize_data( $margin_left, 'px-pct' );
	$vc_row_style	.= 'margin-left:'. $margin_left .';';
}

if ( $margin_right ) {
	$margin_right	= wpex_sanitize_data( $margin_right, 'px-pct' );
	$vc_row_style	.= 'margin-right:'. $margin_right .';';
}

if ( $vc_row_style ) {
	$vc_row_style = ' style="' . $vc_row_style . '"';
}

/*-----------------------------------------------------------------------------------*/
/*	- Inner Row Style
/*-----------------------------------------------------------------------------------*/
$vc_inner_style = '';

if ( $min_height ) {
	$min_height		= wpex_sanitize_data( $min_height, 'px' );
	$vc_inner_style	.= 'min-height:'. $min_height .';';
}

if ( $bg_img_url && 'yes' != $video_bg && 'parallax-advanced' != $bg_style ) {
	$vc_inner_style	.= 'background-image: url('. $bg_img_url .');';
}

if ( $add_bg_style ) {
	$vc_inner_style	.= $add_bg_style;
}

if ( $bg_color && 'yes' != $video_bg ) {
	$vc_inner_style	.= 'background-color:'. $bg_color .';';
} 

if ( $border_color && $border_style && $border_width ) {
	$vc_inner_style	.= 'border-color:'. $border_color .';';
	$vc_inner_style	.= 'border-style:'. $border_style .';';
	$vc_inner_style	.= 'border-width:'. $border_width .';';
}

if ( $padding_top ) {
	$padding_top		= wpex_sanitize_data( $padding_top, 'px-pct' );
	$vc_inner_style		.= 'padding-top:'. $padding_top .';';
}

if ( $padding_bottom ) {
	$padding_bottom	= wpex_sanitize_data( $padding_bottom, 'px-pct' );
	$vc_inner_style	.= 'padding-bottom:'. $padding_bottom .';';
}

if ( $padding_left ) {
	$padding_left	= wpex_sanitize_data( $padding_left, 'px-pct' );
	$vc_inner_style	.= 'padding-left:'. $padding_left .';';
}

if ( $padding_right ) {
	$padding_right	= wpex_sanitize_data( $padding_right, 'px-pct' );
	$vc_inner_style	.= 'padding-right:'. $padding_right .';';
}

if ( $vc_inner_style ) {
	$vc_inner_style = ' style="'. $vc_inner_style .'"';
}

/*-----------------------------------------------------------------------------------*/
/*	- Main VC Classes
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_row wpb_row '. ( $this->settings('base')==='vc_row_inner' ? 'vc_inner ' : '' ) . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
} else {
	$css_class =  apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row '.get_row_css_class().$el_class, $this->settings['base']);
}

// Skin Style
if ( $style ) {
	$skin_style = ' vcex-skin-'. $skin_style;
}

/*-----------------------------------------------------------------------------------*/
/*	- Output the row
/*-----------------------------------------------------------------------------------*/
$output .= '<div '. $id .' class="'. $css_class .' '. $css_animation_class . $visibility .' '. $parallax_class .'" '. $vc_row_style .'>';

	// Open wrap for video bgs
	if ( 'yes' == $video_bg ) {
		$output .= '<div class="vcex-video-bg-wrap clr '. $visibility .'">';
	}

	// Open background area div
	if ( $bg_image || $bg_color ) {

		// Add classes to the background area div
		$add_classes = 'vcex-row-bg-container clr';
		if ( $bg_style_class ) {
			$add_classes .= ' '. $bg_style_class;
		}
		if ( $skin_style ) {
			$add_classes .= ' '. $skin_style;
		}
		if ( $visibility ) {
			$add_classes .= ' '. $visibility;
		}
		if ( $el_class ) {
			$add_classes .= ' '. $el_class;
		}

		$output .= '<div class="'. $add_classes .'" '. $vc_inner_style .'>';

	}

	// Simple div
	elseif ( $skin_style || $el_class || $vc_inner_style ) {
		$output .= '<div class="clr'. $skin_style .'" '. $vc_inner_style .'>';
	}

		// Center the row
		if ( 'yes' == $center_row ) {
			$output .= '<div class="container clr"><div class="center-row-inner clr">';
		}
		
			// Main Output
			$output .= wpb_js_remove_wpautop($content);

		// Center the row
		if ( 'yes' == $center_row ) {
			$output .= '</div></div>';
		}

		// Advanced Parallax Background
		if ( 'parallax-advanced' == $bg_style && $bg_img_url ) {
			$output .= '<div class="vcex-parallax-div '. $parallax_style .'" '. $parallax_style_attr .' '. $parallax_data_attr .'></div>';
		}

	// Close background area div
	if ( $bg_image || $bg_color || $style || $el_class || $vc_inner_style ) {
		$output .= '</div>';
	}

	/*-----------------------------------------------------------------------------------*/
	/*	- Video Background
	/*-----------------------------------------------------------------------------------*/
	if ( 'yes' == $video_bg ) {
		$output .= '<video class="vcex-video-bg" poster="'. $bg_image .'" preload="auto" autoplay="true" loop="loop" muted volume="0">';
			if ( $video_bg_webm !== '' ) {
				$output .= '<source src="'. $video_bg_webm .'" type="video/webm"/>';
			}
			if ( $video_bg_ogv !== '' ) {
				$output .= '<source src="'. $video_bg_ogv .'" type="video/ogg ogv" />';
			}
			if ( $video_bg_mp4 !== '' ) {
				$output .= '<source src="'. $video_bg_mp4 .'" type="video/mp4"/>';
			}
		$output .= '</video>';
		if ( $video_bg_overlay && $video_bg_overlay !== 'none' ) {
			$output .= '<span class="vcex-video-bg-overlay '. $video_bg_overlay .'-overlay"></span>';
		}
	}
	
	// Close video bg wrap
	if ( 'yes' == $video_bg ) {
		$output .= '</div>';
	}

/*-----------------------------------------------------------------------------------*/
/*	- Close Row & return output
/*-----------------------------------------------------------------------------------*/
$output .= '</div>';
echo $output;