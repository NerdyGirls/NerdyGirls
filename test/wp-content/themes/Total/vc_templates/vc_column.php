<?php
$output = $el_class = $width = '';
extract(shortcode_atts(array(
	'el_class'			=> '',
	'visibility'		=> '',
	'width'				=> '1/1',
	'css_animation'		=> '',
	'typo_style'		=> '',
	'style'				=> '',
	'drop_shadow'		=> '',
	'bg_color'			=> '',
	'bg_image'			=> '',
	'bg_style'			=> '',
	'border_color'		=> '',
	'border_style'		=> '',
	'border_width'		=> '',
	'margin_top'		=> '',
	'margin_bottom'		=> '',
	'padding_top'		=> '',
	'padding_bottom'	=> '',
	'padding_left'		=> '',
	'padding_right'		=> '',
	'border'			=> '',
	'css'				=> '',
	'offset'			=> '',
), $atts ) );

// Add extra classes
$el_class = $this->getExtraClass($el_class);

// Core: width
$width = wpb_translateColumnWidthToSpan($width);
if ( function_exists( 'vc_column_offset_class_merge' ) ) {
	$width = vc_column_offset_class_merge($offset, $width);
}

// Animation class
if( '' != $css_animation ) {
	$css_animation = ' wpb_animate_when_almost_visible wpb_'. $css_animation;
}

$el_class .= ' wpb_column clr column_container'. $css_animation;

/**
	Extra Parent Classes
**/
$parent_classes = '';
if ( '' != $style && 'no-spacing' == $style ) {
	$parent_classes .= ' '. $style .'-column';
}

/**
	Inner Classes
**/
$col_inner_classes = '';
if ( $bg_image ) {
	if ( $bg_style ) {
		$bg_style = $bg_style;
	} else {
		$bg_style = 'stretch';
	}
	$col_inner_classes .= ' vcex-background-'. $bg_style;
}
if ( $typo_style ) {
	$col_inner_classes .= 'vcex-skin-'. $typo_style;
}
if ( '' != $style && 'default' != $style && 'no-spacing' != $style ) {
	$col_inner_classes .= ' '. $style .'-column';
}

if ( $drop_shadow == 'yes' ) {
	$col_inner_classes .= ' column-dropshadow';
}

/**
	Inner Style
**/

$outter_style = '';

if ( $margin_top ) {
	$outter_style .= 'margin-top: ' . intval( $margin_top ) . 'px;';
}
	
if ( $margin_bottom ) {
	$outter_style .= 'margin-bottom: ' . intval( $margin_bottom ) . 'px;';
}

if( $outter_style ) {
	$outter_style = ' style="'. $outter_style .'"';
}


/**
	Inner Style
**/

$inner_style = '';

if ( $bg_image ) {
	$img_url = wp_get_attachment_url( $bg_image );
	$inner_style .= 'background-image: url('. $img_url .');';
}

if ( $bg_color ) {
	$inner_style .= 'background-color: '. $bg_color .';';
} 

if ( $border_color ) {
	$inner_style .= 'border-color: '. $border_color .';';
}

if ( $border_style && $border_color ) {
	$inner_style .= 'border-style: '. $border_style .';';
}

if ( $border_width && $border_color ) {
	$inner_style .= 'border-width: '. $border_width .';';
}

if ( $padding_top ) {
	$inner_style .= 'padding-top: ' . intval( $padding_top ) . 'px;';
}

if ( $padding_bottom ) {
	$inner_style .= 'padding-bottom: ' . intval( $padding_bottom ) . 'px;';
}

if ( $padding_left ) {
	$inner_style .= 'padding-left: ' . intval( $padding_left ) . 'px;';
}

if ( $padding_right ) {
	$inner_style .= 'padding-right: ' . intval( $padding_right ) . 'px;';
}

if ( $inner_style ) {
	$inner_style = ' style="'. $inner_style .'"';
}

/**
	Output
**/

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $width . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$output .= "\n\t".'<div class="'. $css_class .' '. $parent_classes .' '. $visibility .'"'. $outter_style .'>';
	if( $col_inner_classes || $inner_style ) {
		$output .= '<div class="clr '. $col_inner_classes .'"'. $inner_style .'>';
	}
		$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
	if( $col_inner_classes || $inner_style ) {
		$output .= '</div>';
	}
$output .= "\n\t".'</div> '.$this->endBlockComment( $el_class ) . "\n";
echo $output;