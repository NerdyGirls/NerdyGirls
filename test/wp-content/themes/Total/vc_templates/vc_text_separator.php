<?php
$output = $title = $title_align = $el_class = '';
extract(shortcode_atts(array(
	'title'				=> __("Title", "js_composer"),
	'element_type'		=> 'div',
	'title_align'		=> 'separator_align_center',
	'el_class'			=> '',
	'font_size'			=> '',
	'font_weight'		=> '',
	'style'				=> 'one',
	'margin_bottom'		=> '',
	'span_background'	=> '',
	'span_color'		=> '',
	'border_color'		=> '',
), $atts));
$el_class = $this->getExtraClass($el_class);


// Main Style
$main_style = array();

	if ( $font_size ) {
		$main_style[] = 'font-size: '. $font_size .';';
	}
	
	if ( $font_weight ) {
		$main_style[] = 'font-weight: '. $font_weight .';';
	}
	
	if ( $margin_bottom ) {
		$main_style[] = 'margin-bottom: '. $margin_bottom .';';
	}

$main_style = implode('', $main_style);

if ( $main_style ) {
	$main_style = wp_kses( $main_style, array() );
	$main_style = ' style="' . esc_attr($main_style) . '"';
}

// Span Style
$span_style = array();

	if ( $span_background ) {
		$span_style[] = 'background: '. $span_background .';';
	}
	
	if ( $span_color ) {
		$span_style[] = 'color: '. $span_color .';';
	}

	if ( $border_color ) {
		$span_style[] = 'border-color: '. $border_color .';';
	}

$span_style = implode('', $span_style);

if ( $span_style ) {
	$span_style = wp_kses( $span_style, array() );
	$span_style = ' style="' . esc_attr($span_style) . '"';
}


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_text_separator wpb_content_element '.$title_align.$el_class, $this->settings['base']);
$output .= '<'. $element_type .' class="'.$css_class.' vc_text_separator_'. $style .'" '. $main_style .'><span '. $span_style .'>'.$title.'</span></'. $element_type .'>'.$this->endBlockComment('separator')."\n";

echo $output;