<?php
/**
 * Registers the button shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists('vcex_button_shortcode') ) {
	function vcex_button_shortcode( $atts, $content = null ) {
		
		extract( shortcode_atts( array(
			'unique_id'					=> '',
			'layout'					=> '',
			'style'						=> 'flat',
			'color'						=> 'blue',
			'custom_color'				=> '',
			'custom_background'			=> '',
			'custom_hover_background'	=> '',
			'custom_hover_color'		=> '',
			'url'						=> '',
			'title'						=> __('Visit Site', 'wpex' ),
			'target'					=> '',
			'size'						=> 'normal',
			'font_weight'				=> '',
			'text_transform'			=> '',
			'font_size'					=> '',
			'letter_spacing'			=> '',
			'font_padding'				=> '',
			'align'						=> 'alignleft',
			'rel'						=> '',
			'border_radius'				=> '',
			'class'						=> '',
			'icon_left'					=> '',
			'icon_right'				=> '',
			'css_animation'				=> '',
			'icon_left_padding'			=> '',
			'icon_right_padding'		=> '',
			'lightbox'					=> '',
			'lightbox_type'				=> '',
			'data_attributes'			=> '',
			'classes'					=> '',
		), $atts ) );

		ob_start();

		// Unique ID
		if ( $unique_id ) {
			$unique_id = 'id="'. $unique_id .'"';
		}
		
		// Rel
		$rel = ( 'none' != $rel ) ? 'rel="'. $rel .'"' : NULL;

		// Button Classes
		if ( $classes ) {
			$classes .= ' ';
		}
		$classes .= 'vcex-button';
		if ( $layout ) {
			$classes .= ' '. $layout;
		}
		$classes .= ' '. $style;
		$classes .= ' align-'. $align;
		if ( $size ) {
			$classes .= ' '. $size;
		}
		if ( $text_transform ) {
			$classes .= ' text-transform-'. $text_transform;
		}
		if ( $color ) {
			$classes .= ' '. $color;
		}
		if ( $class ) {
			$classes .= ' '. $class;
		}
		if ( $css_animation ) {
			$classes .= ' wpb_animate_when_almost_visible wpb_'. $css_animation .'';
		}
		if ( 'local' == $target ) {
			$classes .= ' local-scroll-link';
		}

		// Lightbox classes and data attributes
		if ( '' != $lightbox ) {
			if ( 'image' == $lightbox_type ) {
				$classes .= ' wpex-lightbox';
				$data_attributes .= 'data-type="image"';
			} elseif ( 'video_embed' == $lightbox_type ) {
				$classes .= ' wpex-lightbox';
				$data_attributes .= 'data-type="iframe"';
				$data_attributes .= 'data-options="width:1920,height:1080"';
			} elseif ( 'html5' == $lightbox_type ) {
				$poster = wp_get_attachment_image_src( $img_id, 'large');
				$poster = $poster[0];
				$classes .= ' wpex-lightbox';
				$data_attributes .= 'data-type="video"';
				$data_attributes .= 'data-options="width:848, height:480, html5video: { webm: \''. $lightbox_video_html5_webm .'\', poster: \''. $poster .'\' }"';
			} elseif ( 'quicktime' == $lightbox_type ) {
				$classes .= ' wpex-lightbox';
				$data_attributes .= 'data-type="video"';
				$data_attributes .= 'data-options="width:1920,height:1080"';
			} else {
				$classes .= ' wpex-lightbox-autodetect';
			}
		}

		// Wrap classes
		$wrap_classes = '';
		if ( 'center' == $align ) {
			$wrap_classes = 'textcenter ';
		}
		if ( 'block' == $layout ){
			$wrap_classes .= 'vcex-button-block-wrap';
		}
		if ( 'expanded' == $layout ){
			$wrap_classes .= 'vcex-button-expanded-wrap';
			$classes .= ' expanded';
		}

		// Original styles
		$original_color = '#fff';
		
		// Custom Style
		$inline_style = '';
		if ( $custom_background && in_array( $style, array( 'flat', 'graphical', 'three-d' ) ) ) {
			$inline_style .= 'background:'. $custom_background .';';
		}
		if ( $custom_color ) {
			$inline_style .= 'color:'. $custom_color .';';
			$original_color = $custom_color;
			if ( 'outline' == $style ) {
				$inline_style .= 'border-color:'. $custom_color .';';
			}
		}
		if ( $letter_spacing ) {
			$inline_style .= 'letter-spacing:'. $letter_spacing .';';
		}
		if ( $font_size ) {
			$inline_style .= 'font-size:'. $font_size .';';
		}
		if ( $font_weight ) {
			$inline_style .= 'font-weight:'. $font_weight .';';
		}
		if ( $border_radius ) {
			$inline_style .= 'border-radius:'. $border_radius .';';
		}
		if ( $font_padding ) {
			$inline_style .= 'padding:'. $font_padding .';';
		}
		if ( $inline_style ) {
			$inline_style = 'style="'. $inline_style . '"';
		}

		// Data attributes
		if ( $custom_hover_background && in_array( $style, array( 'flat', 'graphical', 'three-d' ) ) ) {
			$data_attributes .= 'data-hover-background="'. $custom_hover_background .'"';
		}
		if ( $custom_hover_color && in_array( $style, array( 'flat', 'graphical', 'three-d' ) ) ) {
			$data_attributes .= 'data-hover-color="'. $custom_hover_color .'"';
		}
		if ( $data_attributes ) {
			$classes .= ' wpex-data-hover';
		}

		// Link Target
		if ( 'blank' == $target ) {
			$target = 'target="_'. $target .'"';
		} else {
			$target = '';
		}

		// Load inline js for data hover
		if ( $custom_hover_background || $custom_hover_color ) {
			vcex_data_hover_js();
		}
		
		// Display Button
		if ( $wrap_classes ) { ?>
			<div class="<?php echo $wrap_classes; ?> clr">
		<?php } ?>
		<a href="<?php echo esc_url( $url ); ?>" class="<?php echo $classes; ?>" title="<?php echo $title; ?>" <?php echo $inline_style; ?> <?php echo $rel; ?> <?php echo $data_attributes; ?> <?php echo $unique_id; ?> <?php echo $target; ?>>
			<span class="vcex-button-inner">
				<?php
				// Left Icon
				if ( $icon_left && 'none' !== $icon_left ) {
					if ( $icon_left_padding ) {
						$icon_left_padding = 'style="padding-right: '. $icon_left_padding .';"';
					} ?>
					<span class="vcex-button-icon-left fa fa-<?php echo $icon_left; ?>" <?php echo $icon_left_padding; ?>></span>
				<?php }
				// The button text
				echo $content; ?>
				<?php
				// Right Icon
				if ( $icon_right && 'none' != $icon_right ) {
					if ( $icon_right_padding ) {
						$icon_right_padding = 'style="padding-left: '. $icon_right_padding .';"';
					} ?>
					<span class="vcex-button-icon-right fa fa-<?php echo $icon_right; ?>" <?php echo $icon_right_padding; ?>></span>
				<?php } ?>
			</span>
		</a>
		<?php if ( $wrap_classes ) { ?>
			</div>
		<?php }

		return ob_get_clean();

	}
}
add_shortcode( 'vcex_button', 'vcex_button_shortcode' );

if ( ! function_exists( 'vcex_button_shortcode_vc_map' ) ) {
	function vcex_button_shortcode_vc_map() {
		$font_awesome_css = wpex_font_awesome_css_url();
		vc_map( array(
			'name'					=> __( 'Total Button', 'wpex' ),
			'description'			=> __( 'Eye catching button', 'wpex' ),
			'base'					=> 'vcex_button',
			'category'				=> WPEX_THEME_BRANDING,
			'icon'					=> 'vcex-total-button',
			'admin_enqueue_css'		=> $font_awesome_css,
			'front_enqueue_css'		=> $font_awesome_css,
			'params'				=> array(

				// General
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Unique ID', 'wpex' ),
					'param_name'	=> 'unique_id',
				),
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Classes', 'wpex' ),
					'param_name'	=> 'classes',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'URL', 'wpex' ),
					'param_name'	=> 'url',
					'value'			=> 'http://www.google.com/',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Text', 'wpex' ),
					'param_name'	=> 'content',
					'admin_label'	=> true,
					'std'			=> 'Button Text',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Link Title', 'wpex' ),
					'param_name'	=> 'title',
					'value'			=> 'Visit Site',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Link Target', 'wpex' ),
					'param_name'	=> 'target',
					'value'			=> array(
						__( 'Self', 'wpex' )		=> '',
						__( 'Blank', 'wpex' )	=> 'blank',
						__( 'Local', 'wpex' )	=> 'local',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Link Rel', 'wpex' ),
					'param_name'	=> 'rel',
					'value'			=> array(
						__( 'None', 'wpex' )		=> 'none',
						__( 'Nofollow', 'wpex' )	=> 'nofollow',
					),
				),

				// Design
				array(
					'type'			=> 'dropdown',
					'heading'		=> __('CSS Animation', 'wpex' ),
					'param_name'	=> 'css_animation',
					'value'			=> array(
					__('No', 'wpex' )						=> '',
						__('Top to bottom', 'wpex' )		=> 'top-to-bottom',
						__('Bottom to top', 'wpex' )		=> 'bottom-to-top',
						__('Left to right', 'wpex' )		=> 'left-to-right',
						__('Right to left', 'wpex' )		=> 'right-to-left',
						__('Appear from center', 'wpex' )	=> 'appear'),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Style', 'wpex' ),
					'param_name'	=> 'style',
					'value'			=> array(
						__( 'Flat', 'wpex' )		=> 'flat',
						__( 'Graphical', 'wpex' )	=> 'graphical',
						__( 'Clean', 'wpex' )		=> 'clean',
						__( '3D', 'wpex' )			=> 'three-d',
						__( 'Outline', 'wpex' )		=> 'outline',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __('Layout', 'wpex' ),
					'param_name'	=> 'layout',
					'value'			=> array(
						__( 'Inline', 'wpex' )						=> '',
						__( 'Block', 'wpex' )						=> 'block',
						__( 'Expanded (fit container)', 'wpex' )	=> 'expanded',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Align', 'wpex' ),
					'param_name'	=> 'align',
					'value'			=> array(
						__( 'Left', 'wpex' )	=> 'left',
						__( 'Right', 'wpex' )	=> 'right',
						__( 'Center', 'wpex' )	=> 'center',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Background', 'wpex' ),
					'param_name'	=> 'color',
					'value'			=> array(
						__( 'Black', 'wpex' )	=> 'black',
						__( 'Blue', 'wpex' )	=> 'blue',
						__( 'Brown', 'wpex' )	=> 'brown',
						__( 'Grey', 'wpex' )	=> 'grey',
						__( 'Green', 'wpex' )	=> 'green',
						__( 'Gold', 'wpex' )	=> 'gold',
						__( 'Orange', 'wpex' )	=> 'orange',
						__( 'Pink', 'wpex' )	=> 'pink',
						__( 'Purple', 'wpex' )	=> 'purple',
						__( 'Red', 'wpex' ) 	=> 'red',
						__( 'Rosy', 'wpex' )	=> 'rosy',
						__( 'Teal', 'wpex' )	=> 'teal',
						__( 'White', 'wpex' )	=> 'white',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Background', 'wpex' ),
					'param_name'	=> 'custom_background',
					'group'			=> __( 'Design', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'style',
						'value'		=> array( 'flat', 'graphical', 'three-d' ),
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Background: Hover', 'wpex' ),
					'param_name'	=> 'custom_hover_background',
					'group'			=> __( 'Design', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'style',
						'value'		=> array( 'flat', 'graphical', 'three-d' ),
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Color', 'wpex' ),
					'param_name'	=> 'custom_color',
					'group'			=> __( 'Design', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'style',
						'value'		=> array( 'flat', 'graphical', 'three-d', 'clean', 'outline' ),
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Color: Hover', 'wpex' ),
					'param_name'	=> 'custom_hover_color',
					'group'			=> __( 'Design', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'style',
						'value'		=> array( 'flat' ),
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Size', 'wpex' ),
					'param_name'	=> 'size',
					'value'			=> array(
						__( 'Small', 'wpex' )	=> 'small',
						__( 'Medium', 'wpex' )	=> 'medium',
						__( 'Large', 'wpex' )	=> 'large',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Font Size', 'wpex' ),
					'param_name'	=> 'font_size',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Text Transform', 'wpex' ),
					'param_name'	=> 'text_transform',
					'group'			=> __( 'Design', 'wpex' ),
					'value'			=> array(
						__( 'Default', 'wpex' )		=> '',
						__( 'None', 'wpex' )		=> 'none',
						__( 'Capitalize', 'wpex' )	=> 'capitalize',
						__( 'Uppercase', 'wpex' )	=> 'uppercase',
						__( 'Lowercase', 'wpex' )	=> 'lowercase',
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Padding', 'wpex' ),
					'param_name'	=> 'font_padding',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Font Weight', 'wpex' ),
					'param_name'	=> 'font_weight',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Letter Spacing', 'wpex' ),
					'param_name'	=> 'letter_spacing',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Border Radius', 'wpex' ),
					'param_name'	=> 'border_radius',
					'group'			=> __( 'Design', 'wpex' ),
				),

				// Lightbox
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Lightbox', 'wpex' ),
					'param_name'	=> 'lightbox',
					'value'			=> Array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex' )	=> 'true',
					),
					'group'			=> __( 'Lightbox', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Type', 'wpex' ),
					'param_name'	=> 'lightbox_type',
					'value'			=> array(
						__( 'Auto Detect (slow)', 'wpex' )			=> '',
						__( 'Image', 'wpex' )						=> 'image',
						__( 'Video/Youtube Embed Code', 'wpex' )	=> 'video_embed',
						__( 'HTML5', 'wpex' )						=> 'html5',
						__( 'Quicktime', 'wpex' )					=> 'quicktime',
					),
					'description'	=> __( 'Auto detect depends on the iLightbox API, so by choosing your type it speeds things up and you also allows for HTTPS support.', 'wpex' ),
					'group'			=> __( 'Lightbox', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'lightbox',
						'value'		=> 'true',
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'HTML5 Webm URL', 'wpex' ),
					'param_name'	=> 'lightbox_video_html5_webm',
					'description'	=> __( 'Enter the URL to a video, SWF file, flash file or a website URL to open in lightbox.', 'wpex' ),
					'group'			=> __( 'Lightbox', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'lightbox_type',
						'value'		=> 'html5',
					),
				),

				//Icons
				array(
					'type'			=> 'vcex_icon',
					'heading'		=> __( 'Left Icon', 'wpex' ),
					'param_name'	=> 'icon_left',
					'group'			=> __( 'Icons', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Left Icon: Right Padding', 'wpex' ),
					'param_name'	=> 'icon_left_padding',
					'group'			=> __( 'Icons', 'wpex' ),
				),
				array(
					'type'			=> 'vcex_icon',
					'heading'		=> __( 'Right Icon', 'wpex' ),
					'param_name'	=> 'icon_right',
					'group'			=> __( 'Icons', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Right Icon: Left Padding', 'wpex' ),
					'param_name'	=> 'icon_right_padding',
					'group'			=> __( 'Icons', 'wpex' ),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_button_shortcode_vc_map' );