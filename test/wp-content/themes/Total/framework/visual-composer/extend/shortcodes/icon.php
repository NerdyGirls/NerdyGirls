<?php
/**
 * Registers the icon shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_icon_shortcode' ) ) {
	function vcex_icon_shortcode( $atts, $content = NULL ) {
		
		extract( shortcode_atts( array(
			'unique_id'			=> '',
			'icon'				=> '',
			'style'				=> 'circle',
			'float'				=> '',
			'size'				=> '',
			'custom_size'		=> '',
			'color'				=> '#000',
			'padding'			=> '',
			'background'		=> '',
			'border_radius'		=> '',
			'css_animation'		=> '',
			'link_url'			=> '',
			'link_target'		=> '',
			'link_rel'			=> '',
			'link_title'		=> '',
			'el_class'			=> '',
			'height'			=> '',
			'width'				=> '',
		), $atts ) );

		// Turn output buffer on
		ob_start();

			// Return if no icon is defined
			if ( ! $icon ) {
				return '';
			}

			// Add styling
			$add_style = '';
			if ( $custom_size ) {
				$add_style .= 'font-size:'. $custom_size .';';
			}
			if ( $color ) {
				$add_style .= 'color:'. $color .';';
			}
			if ( $padding ) {
				$add_style .= 'padding:'. $padding .';';
			}
			if ( $background ) {
				$add_style .= 'background-color:'. $background .';';
				if ( $border_radius ) {
					$add_style .= 'border-radius:'. $border_radius .';';
				}
			}
			if( $height ) {
				$add_style .= 'height:'. $height .';line-height:'. $height .';';
			}
			if( $width ) {
				$add_style .= 'width:'. $width .';';
			}
			if ( $add_style ) {
				$add_style = 'style="'. $add_style .'"';
			}
			
			// Unique ID
			$unique_id = $unique_id ? ' id="'. $unique_id .'"' : '';
		
			// Icon Classes	
			$classes ='vcex-icon';
			if ( $style ) {
				$classes .= ' vcex-icon-'. $style;
			}
			if ( $size ) {
				$classes .= ' vcex-icon-'. $size;
			}
			if ( $float ) {
				$classes .= ' vcex-icon-float-'. $float;
			}
			if ( $el_class ) {
				$classes .= ' '. $el_class;
			}
			if ( $custom_size ) {
				$classes .= ' custom-size';
			}
			if ( '' != $css_animation ) {
				$classes .= ' wpb_animate_when_almost_visible wpb_'. $css_animation;
			}
			if ( $background ) {
				$classes .= ' has-bg';
			}
			if ( !$background ) {
				$classes .= ' remove-dimensions';
			}
			if( $height || $width ) {
				$classes .= ' remove-padding';
			}
			?>
			
			<div class="<?php echo $classes; ?>" <?php echo $unique_id; ?>>
				<?php
				// Display link
				if ( $link_url ) {
					// Link Classes
					$link_classes = 'vcex-icon-link';
					// Link target
					if ( 'local' == $link_target ) {
						$link_target = '';
						$link_classes .= ' local-scroll-link';
					} elseif ( 'blank' == $link_target ) {
						$link_target = 'target="_blank"';
					} else {
						$link_target = '';
					} ?>
					<a href="<?php echo esc_url( $link_url ); ?>" title="<?php echo $link_title; ?>" rel="<?php echo $link_rel; ?>" <?php echo $link_target; ?> class="<?php echo $link_classes; ?>">
				<?php } ?>
				<span class="fa fa-<?php echo $icon; ?>" <?php echo $add_style; ?>></span>
				<?php if ( $link_url ) { ?></a><?php } ?>
			</div>
		
		<?php
		// Return outbut buffer
		return ob_get_clean();
	}
}
add_shortcode( 'vcex_icon', 'vcex_icon_shortcode' );

if ( ! function_exists( 'vcex_icon_shortcode_vc_map' ) ) {
	function vcex_icon_shortcode_vc_map() {
		vc_map( array(
			'name'					=> __( "Font Icon", 'wpex' ),
			'description'			=> __( "Font Awesome icon", 'wpex' ),
			'base'					=> 'vcex_icon',
			'icon'					=> 'vcex-font-icon',
			'category'				=> WPEX_THEME_BRANDING,
			'admin_enqueue_css'		=> wpex_font_awesome_css_url(),
			'front_enqueue_css'		=> wpex_font_awesome_css_url(),
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Extra class name', 'wpex' ),
					'param_name'	=> 'el_class',
					'description'	=> __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wpex' ),
				),
				array(
					'type'			=> "vcex_icon",
					'heading'		=> __( "Icon", 'wpex' ),
					'param_name'	=> "icon",
					"admin_label"	=> true,
					'value'			=> 'flag',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __("CSS Animation", 'wpex'),
					'param_name'	=> "css_animation",
					"admin_label"	=> true,
					'value'			=> array(
						__( "No", 'wpex' )					=> '',
						__( "Top to bottom", 'wpex' )		=> "top-to-bottom",
						__( "Bottom to top", 'wpex' )		=> "bottom-to-top",
						__( "Left to right", 'wpex' )		=> "left-to-right",
						__( "Right to left", 'wpex' )		=> "right-to-left",
						__( "Appear from center", 'wpex' )	=> "appear"),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Icon Size", 'wpex' ),
					'param_name'	=> "size",
					'value'			=> array(
						__( 'Inherit', 'wpex' )		=> '',
						__( "Extra Large", 'wpex' )	=> "xlarge",
						__( "Large", 'wpex' )		=> "large",
						__( "Normal", 'wpex' )		=> "normal",
						__( "Small", 'wpex')		=> "small",
						__( "Tiny", 'wpex' )		=> "tiny",
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Custom Icon Size", 'wpex' ),
					'param_name'	=> "custom_size",
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Position", 'wpex' ),
					'param_name'	=> "float",
					'value'			=> array(
						__( "Default", 'wpex' )	=> '',
						__( "Center", 'wpex' )	=> "center",
						__( "Left", 'wpex')		=> "left",
						__( "Right", 'wpex' )	=> "right",
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Icon Color', 'wpex' ),
					'param_name'	=> 'color',
					'value'			=> '#000000',
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Background Color', 'wpex' ),
					'param_name'	=> 'background',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Container Height', 'wpex' ),
					'param_name'	=> 'height',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Container Width', 'wpex' ),
					'param_name'	=> 'width',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Container Padding", 'wpex' ),
					'param_name'	=> "padding",
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Container Border Radius', 'wpex' ),
					'param_name'	=> 'border_radius',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Link URL', 'wpex' ),
					'param_name'	=> 'link_url',
					'group'			=> __( 'Link', 'wpex' )
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Link Title', 'wpex' ),
					'param_name'	=> 'link_title',
					'group'			=> __( 'Link', 'wpex' )
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Link Target', 'wpex' ),
					'param_name'	=> "link_target",
					'value'			=> array(
						__( 'Self', 'wpex')		=> '',
						__( 'Blank', 'wpex' )	=> 'blank',
						__( 'Local', 'wpex' )	=> 'local',
					),
					'group'			=> __( 'Link', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Link Rel", 'wpex' ),
					'param_name'	=> "link_rel",
					'value'			=> array(
						__( 'None', 'wpex' )		=> 'none',
						__( 'Nofollow', 'wpex' )	=> 'nofollow',
					),
					'group'			=> __( 'Link', 'wpex' ),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_icon_shortcode_vc_map' );