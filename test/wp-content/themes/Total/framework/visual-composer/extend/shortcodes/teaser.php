<?php
/**
 * Registers the teaser shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( ! function_exists('vcex_teaser_shortcode')) {
	function vcex_teaser_shortcode( $atts, $content = NULL ) {
		
		extract( shortcode_atts( array(
			'unique_id'					=> '',
			'heading'					=> '',
			'heading_type'				=> 'h2',
			'heading_color'				=> '',
			'heading_size'				=> '',
			'heading_margin'			=> '',
			'heading_weight'			=> '',
			'heading_letter_spacing'	=> '',
			'heading_transform'			=> '',
			'style'						=> 'one',
			'text_align'				=> '',
			'image'						=> '',
			'img_width'					=> '9999',
			'img_height'				=> '9999',
			'video'						=> '',
			'url'						=> '',
			'content_background'		=> '',
			'url_target'				=> '',
			'url_rel'					=> '',
			'css_animation'				=> '',
			'img_filter'				=> '',
			'img_hover_style'			=> '',
			'img_rendering'				=> '',
			'content_font_size'			=> '',
			'content_margin'			=> '',
			'content_padding'			=> '',
			'content_color'				=> '',
			'content_font_weight'		=> '',
			'background'				=> '',
			'border_color'				=> '',
			'padding'					=> '',
			'border_radius'				=> '',
			'img_style'					=> '',
		), $atts ) );

		// Turn output buffer on
		ob_start();

			// Unique ID
			if ( $unique_id ) {
				$unique_id = 'id="'. $unique_id .'"';
			}

			// Classes
			$add_classes = 'vcex-teaser';
			if ( '' != $css_animation ) {
				$add_classes .= ' wpb_animate_when_almost_visible wpb_'. $css_animation .'';
			}
			if( $style ) {
				$add_classes .= ' vcex-teaser-'. $style;
			}
			if( $text_align ) {
				$add_classes .= ' vcex-text-align-'. $text_align;
			}
			// Main style
			$inline_style = '';
			if ( $padding && 'two' == $style ) {
				$inline_style .= 'padding:'. $padding.';';
			}
			if ( $background && 'two' == $style ) {
				$inline_style .= 'background:'. $background.';';
			}
			if ( $border_color && 'two' == $style ) {
				$inline_style .= 'border-color:'. $border_color.';';
			}
			if ( $border_radius && 'two' == $style ) {
				$inline_style .= 'border-radius:'. $border_radius.';';
			}
			if ( $inline_style ) {
				$inline_style = 'style="'. $inline_style .'"';
			} ?>

			<div class="<?php echo $add_classes; ?>" <?php echo $unique_id; ?> <?php echo $inline_style; ?>>
				<?php
				/*** Video ***/
				if ( $video ) { ?>
					<div class="vcex-teaser-media vcex-video-wrap">
						<?php echo wp_oembed_get( $video ); ?>
					</div><!-- .vcex-teaser-media -->
				<?php }
				/*** Link ***/
				if ( $url ) {
					// Link classes
					$add_classes = 'vcex-teaser-link';
					// Link Target
					if ( 'local' == $url_target ) {
						$url_target = '';
						$add_classes .= ' local-scroll-link';
					} elseif ( 'blank' == $url_target ) {
						$url_target = 'target="_blank"';
					} else {
						$url_target = '';
					} ?>
					<a href="<?php echo esc_url( $url ); ?>" title="<?php echo $heading; ?>" target="<?php echo $url_target; ?>" rel="<?php echo $url_rel; ?>" class="<?php echo $add_classes; ?>">
				<?php }
				/*** Image ***/
				if ( $image ) {
					$image_img_url = wp_get_attachment_url( $image );
					$image_img = wp_get_attachment_url( $image );
					$image_alt = strip_tags( get_post_meta($image, '_wp_attachment_image_alt', true) );
					$img_crop = $img_height >= '9999' ? false : true;
					$add_classes = 'vcex-teaser-media';
					if ( $img_filter ) {
						$add_classes .= ' vcex-'. $img_filter;
					}
					if ( $img_hover_style ) {
						$add_classes .= ' vcex-img-hover-parent vcex-img-hover-'. $img_hover_style;
					}
					if ( $img_rendering ) {
						$add_classes .= ' vcex-image-rendering-'. $img_rendering;
					}
					if ( 'stretch' == $img_style ) {
						$add_classes .= ' stretch-image';
					} ?>
					<figure class="<?php echo $add_classes; ?>">
						<img src="<?php echo wpex_image_resize( $image_img_url, intval( $img_width ),  intval( $img_height ), $img_crop ); ?>" alt="<?php echo $image_alt; ?>" />
					</figure>
				<?php }
				if ( $content || $heading ) {
					/*** Content Area ***/
					$inline_style = '';
					if ( $content_margin ) {
						$inline_style .= 'margin:'. $content_margin.';';
					}
					if ( $content_padding ) {
						$inline_style .= 'padding:'. $content_padding.';';
					}
					if ( $background && 'three' == $style && '' == $content_background ) {
						$inline_style .= 'background:'. $background.';';
					}
					if ( $content_background ) {
						$inline_style .= 'background:'. $content_background.';';
					}
					if ( $border_color && ( 'three' == $style || 'four' == $style ) ) {
						$inline_style .= 'border-color:'. $border_color.';';
					}
					if ( $border_radius && ( 'three' == $style || 'four' == $style ) ) {
						$inline_style .= 'border-radius:'. $border_radius.';';
					}
					if ( $inline_style ) {
						$inline_style = 'style="'. $inline_style .'"';
					} ?>
					<div class="vcex-teaser-content clr" <?php echo $inline_style; ?>>
						<?php
						/*** Heading ***/
						if ( $heading ) {
							// Heading style
							$inline_style = '';
							if ( $heading_color ) {
								$inline_style .= 'color:'. $heading_color .';';
							}
							if ( $heading_size ) {
								$inline_style .= 'font-size:'. $heading_size .';';
							}
							if ( $heading_margin ) {
								$inline_style .= 'margin:'. $heading_margin .';';
							}
							if ( $heading_weight ) {
								$inline_style .= 'font-weight:'. $heading_weight .';';
							}
							if ( $heading_letter_spacing ) {
								$inline_style .= 'letter-spacing:'. $heading_letter_spacing .';';
							}
							if ( $heading_transform ) {
								$inline_style .= 'text-transform:'. $heading_transform .';';
							}
							if ( $inline_style ) {
								$inline_style = 'style="'. $inline_style  .'"';
							} ?>
							<<?php echo $heading_type; ?> class="vcex-teaser-heading" <?php echo $inline_style; ?>>
								<?php
								// Display heading
								echo $heading; ?>
							</<?php echo $heading_type; ?>>
						<?php
						}
						// Close URL tag
						if ( $url ) { ?>
							</a>
						<?php }
						/*** Text ***/
						if ( $content ) {
							$inline_style = '';
							if ( $content_font_size ) {
								$inline_style .= 'font-size:'. $content_font_size.';';
							}
							if ( $content_color ) {
								$inline_style .= 'color:'. $content_color.';';
							}
							if ( $content_font_weight ) {
								$inline_style .= 'font-weight:'. $content_font_weight.';';
							}
							if ( $inline_style ) {
								$inline_style = 'style="'. $inline_style .'"';
							} ?>
							<div class="vcex-teaser-text clr" <?php echo $inline_style; ?>>
								<?php echo do_shortcode( $content ); ?>
							</div><!-- .vcex-teaser-text -->
						<?php } ?>
					</div><!-- .vcex-teaser-content -->
				<?php } ?>
			</div><!-- .vcex-teaser -->
		
		<?php
		// Return outbut buffer
		return ob_get_clean();
	}
}
add_shortcode( 'vcex_teaser', 'vcex_teaser_shortcode' );

if ( ! function_exists( 'vcex_teaser_shortcode_vc_map' ) ) {
	function vcex_teaser_shortcode_vc_map() {
		$vc_img_rendering_url = 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering';
		vc_map( array(
			"name"					=> __( "Teaser Box", 'wpex' ),
			"description"			=> __( "A teaser content box", 'wpex' ),
			"base"					=> "vcex_teaser",
			'category'				=> WPEX_THEME_BRANDING,
			"icon"					=> "vcex-teaser",
			"params"				=> array(
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Unique ID', 'wpex' ),
					'param_name'	=> 'unique_id',
					'value'			=> ''
				),
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Classes', 'wpex' ),
					'param_name'	=> 'classes',
					'value'			=> ''
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __("Style", "wpex"),
					"param_name"	=> "style",
					"value"			=> array(
						__( "Plain", "wpex")	=> "one",
						__( "Boxed 1", "wpex" )	=> "two",
						__( "Boxed 2", "wpex" )	=> "three",
						__( "Outline", "wpex" )	=> "four",
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __("CSS Animation", "wpex"),
					"param_name"	=> "css_animation",
					"value"			=> array(
						__("No", "wpex")					=> '',
						__("Top to bottom", "wpex")			=> "top-to-bottom",
						__("Bottom to top", "wpex")			=> "bottom-to-top",
						__("Left to right", "wpex")			=> "left-to-right",
						__("Right to left", "wpex")			=> "right-to-left",
						__("Appear from center", "wpex")	=> "appear"),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Alignment", "wpex" ),
					"param_name"	=> "text_align",
					"value"			=> array(
						__( "Default", "wpex" )	=> "",
						__( "Center", "wpex" )	=> "center",
						__( "Left", "wpex" )	=> "left",
						__( "Right", "wpex" )	=> "right",
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Padding", 'wpex' ),
					'param_name'	=> "padding",
					'dependency'	=> array(
						'element'	=> 'style',
						'value'		=> array( 'two' ),
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Background Color", 'wpex' ),
					'param_name'	=> "background",
					'dependency'	=> array(
						'element'	=> 'style',
						'value'		=> array( 'two', 'three' ),
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Border Color", 'wpex' ),
					'param_name'	=> "border_color",
					'dependency'	=> array(
						'element'	=> 'style',
						'value'		=> array( 'two', 'three', 'four' ),
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Border Radius", 'wpex' ),
					'param_name'	=> "border_radius",
					'dependency'	=> array(
						'element'	=> 'style',
						'value'		=> array( 'two', 'three', 'four' ),
					),
				),
				array(
					"type"			=> "textarea_html",
					"class"			=> "",
					"holder"		=> "div",
					"heading"		=> __( "Content", 'wpex' ),
					"param_name"	=> "content",
					'value'			=> __( 'Don\'t forget to change this dummy text in your page editor for this lovely teaser box.', 'wpex' ),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Content Margin", 'wpex' ),
					'param_name'	=> "content_margin",
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Content Padding", 'wpex' ),
					'param_name'	=> "content_padding",
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Content Font Size", 'wpex' ),
					'param_name'	=> "content_font_size",
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Content Font Weight", 'wpex' ),
					'param_name'	=> "content_font_weight",
					'group'			=> __( 'Content', 'wpex' ),
					"value"			=> '',
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Content Font Color", 'wpex' ),
					'param_name'	=> "content_color",
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Content Background", 'wpex' ),
					'param_name'	=> "content_background",
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Heading", 'wpex' ),
					"param_name"	=> "heading",
					"value"			=> "Sample Heading",
					'group'			=> __( 'Heading', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Heading Type", 'wpex' ),
					"param_name"	=> "heading_type",
					 "value"		=> array(
						__("h2", "wpex")	=> "h2",
						__("h3", "wpex")	=> "h3",
						__("h4", "wpex")	=> "h4",
						__("h5", "wpex")	=> "h5",
					),
					'group'			=> __( 'Heading', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Heading Color", 'wpex' ),
					'param_name'	=> "heading_color",
					'group'			=> __( 'Heading', 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Heading Font Size", 'wpex' ),
					'param_name'	=> "heading_size",
					'group'			=> __( 'Heading', 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Heading Margin", 'wpex' ),
					'param_name'	=> "heading_margin",
					'group'			=> __( 'Heading', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Heading Font Weight", 'wpex' ),
					'param_name'	=> "heading_weight",
					'group'			=> __( 'Heading', 'wpex' ),
					"value"			=> '',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Heading Letter Spacing", 'wpex' ),
					'param_name'	=> "heading_letter_spacing",
					'group'			=> __( 'Heading', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Heading Text Transform", 'wpex' ),
					'param_name'	=> "heading_transform",
					'group'			=> __( 'Heading', 'wpex' ),
					'value'			=> array(
						__( 'Default', 'wpex' )		=> '',
						__( 'None', 'wpex' )		=> 'none',
						__( 'Capitalize', 'wpex' )	=> 'capitalize',
						__( 'Uppercase', 'wpex' )	=> 'uppercase',
						__( 'Lowercase', 'wpex' )	=> 'lowercase',
					),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "URL", 'wpex' ),
					"param_name"	=> "url",
					"value"			=> "",
					'group'			=> __( 'URL', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "URL Target", 'wpex' ),
					"param_name"	=> "url_target",
					"value"			=> array(
						__( "Self", "wpex" )	=> "",
						__( "Blank", "wpex" )	=> "_blank",
						__( "Local", 'wpex' )	=> "local",
					),
					'group'			=> __( 'URL', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "URL Rel", 'wpex' ),
					"param_name"	=> "url_rel",
					"value"		=> array(
						__( "None", "wpex" )		=> "",
						__( "Nofollow", "wpex" )	=> "nofollow",
					),
					'group'			=> __( 'URL', 'wpex' ),
				),
				array(
					"type"			=> "attach_image",
					"heading"		=> __("Image", "wpex"),
					"param_name"	=> "image",
					"value"			=> "",
					'group'			=> __( 'Media', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __("Video link", "wpex"),
					"param_name"	=> "video",
					"description"	=> sprintf(__('Enter a video link for a video based teaser box. More about supported formats at %s.', "wpex"), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>'),
					'group'			=> __( 'Media', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Image Style", 'wpex' ),
					"param_name"	=> "img_style",
					"value"			=> array(
						__( "Default", "wpex" )	=> "",
						__( "Stretch", "wpex" )	=> "stretch",
					),
					'group'			=> __( 'Media', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Image Width", 'wpex' ),
					"param_name"	=> "img_width",
					"value"			=> "9999",
					'group'			=> __( 'Media', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Image Height", 'wpex' ),
					"param_name"	=> "img_height",
					"value"			=> "9999",
					"description"	=> __( "Custom image cropping height. Enter 9999 for no cropping.", 'wpex' ),
					'group'			=> __( 'Media', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Image Filter", 'wpex' ),
					"param_name"	=> "img_filter",
					"value"			=> vcex_image_filters(),
					'group'			=> __( 'Media', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "CSS3 Image Hover", 'wpex' ),
					"param_name"	=> "img_hover_style",
					"value"			=> vcex_image_hovers(),
					"description"	=> __("Select your preferred image hover effect. Please note this will only work if the image links to a URL or a large version of itself. Please note these effects may not work in all browsers.", "wpex"),
					'group'			=> __( 'Media', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Image Rendering", 'wpex' ),
					"param_name"	=> "img_rendering",
					"value"			=> vcex_image_rendering(),
					"description"	=> sprintf( __( 'Image-rendering CSS property provides a hint to the user agent about how to handle its image rendering. For example when scaling down images they tend to look a bit fuzzy in Firefox, setting image-rendering to crisp-edges can help make the images look better. <a href="%s">Learn more</a>.', 'wpex' ), esc_url( $vc_img_rendering_url ) ),
					'group'			=> __( 'Media', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_teaser_shortcode_vc_map' );