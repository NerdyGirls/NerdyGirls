<?php
/**
 * Registers the list item shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_list_item_shortcode' ) ) {
	function vcex_list_item_shortcode( $atts, $content = NULL ) {

		extract( shortcode_atts( array(
			'style'					=> '',
			'classes'				=> '',
			'icon'					=> '',
			'color'					=> '',
			'font_color'			=> '',
			'font_size'				=> '',
			'icon_size'				=> '',
			'text_align'			=> '',
			'margin_right'			=> '',
			'css_animation'			=> '',
			'font_size'				=> '',
			'url'					=> '',
			'url_target'			=> 'self',
			'icon_background'		=> '',
			'icon_border_radius'	=> '',
			'icon_width'			=> '',
			'icon_height'			=> '',
		),
		$atts ) );

		// Turn output buffer on
		ob_start();

		// Default icon
		if ( $icon ) {
			$icon = $icon;
		} else {
			$icon = 'star-o';
		}

		// Main Styles
		$inline_style = '';
		
		if( $font_size ) {
			$inline_style .= 'font-size:'. $font_size .';';
		}
		
		if ( $font_color ) {
			$inline_style .= 'color: '. $font_color .';';
		}

		if ( $inline_style ) {
			$inline_style = ' style="' . esc_attr($inline_style) . '"';
		}

		// Classes
		$add_classes = 'vcex-list_item';
		if ( $classes ) {
			$add_classes .= ' '. $classes;
		}
		if ( '' != $css_animation ) {
			$add_classes .= ' wpb_animate_when_almost_visible wpb_'. $css_animation;
		}
		if ( $text_align ) {
			$add_classes .= ' '. $text_align;
		} ?>
		

		<div class="<?php echo $add_classes; ?>" <?php echo $inline_style; ?>>
			<?php
			// Close URL
			if ( $url ) {
				$inline_style = '';
				if ( $font_color ) {
					$inline_style .= 'color: '. $font_color .';';
				}
				if ( $inline_style ) {
					$inline_style = ' style="' . esc_attr($inline_style) . '"';
				} ?>
				<a href="<?php echo esc_url( $url ); ?>" title="<?php echo wp_strip_all_tags( $content ); ?>" target="_<?php echo $url_target; ?>" <?php echo $inline_style; ?>>
			<?php }
			// Icon classes
			$icon_classes = 'fa fa-'. $icon;
			// Icon inline style
			$inline_style = '';
			if ( $icon_background ) {
				$inline_style .= 'background:'. $icon_background .';';
			}
			if ( $icon_width ) {
				$inline_style .= 'width:'. intval( $icon_width ) .'px;';
				$icon_classes .= ' textcenter';
			}
			if ( $icon_border_radius ) {
				$inline_style .= 'border-radius:'. $icon_border_radius .';';
			}
			if ( $icon_height ) {
				$inline_style .= 'height:'. intval( $icon_height ) .'px;line-height:'. intval( $icon_height ) .'px;';
			}
			if ( $margin_right ) {
				$inline_style .= 'margin-right:'. intval( $margin_right ) .'px;';
			}
			if ( $icon_size ) {
				$inline_style .= 'font-size:'. intval( $icon_size ) .'px;';
			}
			if ( $color ) {
				$inline_style .= 'color: '. $color .';';
			}
			if ( $inline_style ) {
				$inline_style = ' style="' . esc_attr($inline_style) . '"';
			} ?>
			<span class="<?php echo $icon_classes; ?>" <?php echo $inline_style; ?>></span><?php echo do_shortcode( $content ); ?>
			<?php
			// Close URL
			if ( $url ) { ?>
				</a>
			<?php } ?>
		</div><!-- .vcex-list_item -->

		<?php
		// Return outbut buffer
		return ob_get_clean();
	}
}
add_shortcode('vcex_list_item', 'vcex_list_item_shortcode');

if ( ! function_exists( 'vcex_list_item_shortcode_vc_map' ) ) {
	function vcex_list_item_shortcode_vc_map() {
		vc_map( array(
			"name"					=> __( "List Item", 'wpex' ),
			"description"			=> __( "Font Icon list item", 'wpex' ),
			"base"					=> "vcex_list_item",
			'admin_enqueue_css'		=> wpex_font_awesome_css_url(),
			'front_enqueue_css'		=> wpex_font_awesome_css_url(),
			"icon" 					=> "vcex-list-item",
			'category'				=> WPEX_THEME_BRANDING,
			"params"				=> array(

				// General
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'CSS Animation', 'wpex' ),
					'param_name'	=> 'css_animation',
					'value'			=> array(
						__( 'No', 'wpex')					=> '',
						__( 'Top to bottom', 'wpex' )		=> 'top-to-bottom',
						__( 'Bottom to top', 'wpex' )		=> 'bottom-to-top',
						__( 'Left to right', 'wpex' )		=> 'left-to-right',
						__( 'Right to left', 'wpex' )		=> 'right-to-left',
						__( 'Appear from center', 'wpex' )	=> 'appear',
					),
				),
				array(
					'type'			=> "textfield",
					'heading'		=> __( 'Classes', 'wpex' ),
					'param_name'	=> 'classes',
					'value'			=> '',
				),
				array(
					'type'			=> "textfield",
					'heading'		=> __( 'Content', 'wpex' ),
					'param_name'	=> 'content',
					'value'			=> __( 'This is a pretty list item', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					'heading'		=> __( "Font Color", 'wpex' ),
					'param_name'	=> "font_color",
					'value'			=> '',
				),
				array(
					'type'			=> "textfield",
					'heading'		=> __( "Custom Font Size", 'wpex' ),
					'param_name'	=> "font_size",
					'value'			=> '',
				),
				array(
					'type'			=> "dropdown",
					'heading'		=> __( "Text Align", 'wpex' ),
					'param_name'	=> "text_align",
					'value'			=> array(
						__( 'Default', 'wpex' )	=> '',
						__( 'Left', 'wpex' )		=> 'textleft',
						__( 'Center', 'wpex' )		=> 'textcenter',
						__( 'Right', 'wpex' )		=> 'textright',
					),
				),

				// Icon
				array(
					'type'			=> "vcex_icon",
					'heading'		=> __( "Icon", 'wpex' ),
					'param_name'	=> "icon",
					"admin_label"	=> true,
					'value'			=> '',
					'group'			=> __( "Icon", 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					'heading'		=> __( "Icon Right Margin", 'wpex' ),
					'param_name'	=> "margin_right",
					'value'			=> '',
					'group'			=> __( "Icon", 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					'heading'		=> __( "Icon Color", 'wpex' ),
					'param_name'	=> "color",
					'value'			=> '',
					'group'			=> __( "Icon", 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Icon Background", 'wpex' ),
					'param_name'	=> "icon_background",
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Icon Border Radius", 'wpex' ),
					'param_name'	=> "icon_border_radius",
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Icon Size In Pixels", 'wpex' ),
					'param_name'	=> "icon_size",
					'value'			=> '',
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Icon Width", 'wpex' ),
					'param_name'	=> "icon_width",
					'value'			=> '',
					'group'			=> __( 'Icon', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Icon Height", 'wpex' ),
					'param_name'	=> "icon_height",
					'value'			=> '',
					'group'			=> __( 'Icon', 'wpex' ),
				),
				
				// Link
				array(
					'type'			=> "textfield",
					'heading'		=> __( "Link", 'wpex' ),
					'param_name'	=> "url",
					'value'			=> '',
					'group'			=> __( "Link", 'wpex' ),
				),
				array(
					'type'			=> "dropdown",
					'heading'		=> __( "Link Target", 'wpex' ),
					'param_name'	=> "url_target",
					'value'			=> array(
						__( "Self", 'wpex')		=> "self",
						__( "Blank", 'wpex' )	=> "blank",
					),
					'group'			=> __( "Link", 'wpex' ),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_list_item_shortcode_vc_map' );