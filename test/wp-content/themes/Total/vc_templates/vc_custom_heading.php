<?php
// Get shortcode atts
extract( shortcode_atts( array(
	'enqueue_font_style'	=> '',
), $atts ) );
// Set variables
$output = $text = $google_fonts = $font_container = $el_class = $css = $google_fonts_data = $font_container_data = '';
// Get shortcode attributes
extract( $this->getAttributes( $atts ) );
// Get styles
extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );
// Enqueue the Google Font if not disabled
if( 'false' != $enqueue_font_style ) {
	// Get Google font subsets
	$settings = get_option( 'wpb_js_google_fonts_subsets' );
	$subsets  = '';
	// Add subsets to Google Font
	if ( is_array( $settings ) && ! empty( $settings ) ) {
		$subsets = '&subset=' . implode( ',', $settings );
	}
	wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
}
// Output the heading code
$output .= '<div class="' . $css_class . '" >';
$output .= '<' . $font_container_data['values']['tag'] . ' style="' . implode( ';', $styles ) . '">';
$output .= $text;
$output .= '</' . $font_container_data['values']['tag'] . '>';
$output .= '</div>';
echo $output;