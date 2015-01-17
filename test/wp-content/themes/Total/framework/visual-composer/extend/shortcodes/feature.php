<?php
/**
 * Registers the feature shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_feature_box_shortcode' ) ) {
	function vcex_feature_box_shortcode( $atts, $content = NULL ) {
		
		extract( shortcode_atts( array(
			'unique_id'					=> '',
			'classes'					=> '',
			'heading'					=> '',
			'equal_heights'				=> '',
			'padding'					=> '',
			'border'					=> '',
			'background'				=> '',
			'heading_type'				=> 'h2',
			'heading_color'				=> '',
			'heading_size'				=> '',
			'heading_margin'			=> '',
			'heading_weight'			=> '',
			'heading_letter_spacing'	=> '',
			'heading_transform'			=> '',
			'tablet_widths'				=> '',
			'phone_widths'				=> '',
			'style'						=> 'left-content-right-image',
			'text_align'				=> '',
			'image_lightbox'			=> '',
			'image'						=> '',
			'img_width'					=> '9999',
			'img_height'				=> '9999',
			'img_border_radius'			=> '',
			'video'						=> '',
			'heading_url'				=> '',
			'content_background'		=> '',
			'css_animation'				=> '',
			'img_filter'				=> '',
			'image_url'					=> '',
			'img_hover_style'			=> '',
			'img_rendering'				=> '',
			'content_font_size'			=> '',
			'content_padding'			=> '',
			'content_color'				=> '',
			'content_font_weight'		=> '',
			'img_style'					=> '',
			'content_width'				=> '',
			'media_width'				=> '',
		), $atts ) );

		// Turn output buffer on
		ob_start();

			// Load equal height for the frontend
			if ( function_exists( 'wpex_is_front_end_composer' ) && wpex_is_front_end_composer() && $equal_heights ) { ?>
				<script>
					(function($) {
						"use strict";
						if ($.fn.matchHeight != undefined) {
							$('.match-height-feature-row .match-height-feature').matchHeight();
						}
					})(jQuery);
				</script>
			<?php } 

			// If video url is set disable equal heights
			if ( $video ) {
				$equal_heights = false;
			}

			// ID
			if ( $unique_id ) {
				$unique_id = ' id="'. $unique_id .'"';
			}

			// Add style
			$inline_style = '';
			if( $padding ) {
				$inline_style .= 'padding:'. $padding .';';
			}
			if( $background ) {
				$inline_style .= 'background:'. $background .';';
			}
			if( $border ) {
				$inline_style .= 'border:'. $border .';';
			}
			if( $inline_style ) {
				$inline_style = ' style="'. $inline_style .'"';
			}

			// Classes
			$add_classes = 'vcex-feature-box clr';
			if ( '' != $css_animation ) {
				$add_classes .= ' wpb_animate_when_almost_visible wpb_'. $css_animation .'';
			}
			if( $style ) {
				$add_classes .= ' '. $style;
			}
			if ( $equal_heights ) {
				$add_classes .= ' match-height-feature-row';
			}
			if ( $tablet_widths ) {
				$add_classes .= ' tablet-fullwidth-columns';
			}
			if ( $phone_widths ) {
				$add_classes .= ' phone-fullwidth-columns';
			}
			if( $text_align ) {
				$add_classes .= ' vcex-text-align-'. $text_align;
			}
			if ( $classes ) {
				$add_classes .= ' '. $classes;
			} ?>

			<div class="<?php echo $add_classes; ?>"<?php echo $unique_id; ?><?php echo $inline_style; ?>>
				<?php
				// Image/Video check
				if ( $image || $video ) {
					// Add classes
					$add_classes = 'vcex-feature-box-media clr';
					if ( $equal_heights ) {
						$add_classes .= ' match-height-feature';
					}
					// Media style
					$inline_style = '';
					if ( $media_width ) {
						$inline_style .= 'width:'. $media_width.';';
					}
					if ( $inline_style ) {
						$inline_style = 'style="'. $inline_style  .'"';
					} ?>
					<div class="<?php echo $add_classes; ?>" <?php echo $inline_style; ?>>
						<?php
						/*** Video ***/
						if ( $video ) { ?>
							<div class="vcex-video-wrap">
								<?php echo wp_oembed_get( esc_url( $video ) ); ?>
							</div><!-- .vcex-feature-box-media -->
						<?php }
						/*** Image ***/
						elseif ( $image ) {
							// Get image
							$image_img_url = wp_get_attachment_url( $image );
							$image_img = wp_get_attachment_url( $image );
							$image_alt = strip_tags( get_post_meta($image, '_wp_attachment_image_alt', true) );
							$img_crop = $img_height >= '9999' ? false : true;
							// Image inline CSS
							$inline_style = '';
							if ( $img_border_radius ) {
								$inline_style .='border-radius:'. $img_border_radius .';';
							}
							if ( $inline_style ) {
								$inline_style = 'style="'. $inline_style .'"';
							}
							// Image classes
							$add_classes = 'vcex-feature-box-image';
							if ( $equal_heights ) {
								$add_classes .= ' fade-in-image';
							}
							if ( $img_filter ) {
								$add_classes .= ' vcex-'. $img_filter;
							}
							if ( $img_hover_style && 'true' != $equal_heights ) {
								$add_classes .= ' vcex-img-hover-parent vcex-img-hover-'. $img_hover_style;
							}
							if ( $img_rendering ) {
								$add_classes .= ' vcex-image-rendering-'. $img_rendering;
							}
							// Image URL
							if ( $image_url || 'image' == $image_lightbox ) {
								// Standard URL
								$link = vc_build_link( $image_url );
								$a_href = isset( $link['url'] ) ? $link['url'] : '';
								$a_title = isset( $link['title'] ) ? $link['title'] : '';
								$a_target = isset( $link['target'] ) ? $link['target'] : '';
								// Image lightbox
								$data_attributes = '';
								if ( $image_lightbox ) {
									if ( 'image' == $image_lightbox ) {
										$a_href = $image_img_url;
										$add_classes .= ' wpex-lightbox';
										$data_attributes .= 'data-type="image"';
									} elseif ( 'url' == $image_lightbox ) {
										$add_classes .= ' wpex-lightbox';
										$data_attributes .= 'data-type="iframe"';
										$data_attributes .= 'data-options="width:1920,height:1080"';
									} elseif ( 'auto-detect' == $image_lightbox ) {
										$add_classes .= ' wpex-lightbox-autodetect';
									} elseif ( 'video_embed' == $image_lightbox ) {
										$add_classes .= ' wpex-lightbox';
										$data_attributes .= 'data-type="iframe"';
										$data_attributes .= 'data-options="width:1920,height:1080"';
									} elseif ( 'html5' == $image_lightbox ) {
										$poster = wp_get_attachment_image_src( $img_id, 'large');
										$poster = $poster[0];
										$add_classes .= ' wpex-lightbox';
										$data_attributes .= 'data-type="video"';
										$data_attributes .= 'data-options="width:848, height:480, html5video: { webm: \''. $lightbox_video_html5_webm .'\', poster: \''. $poster .'\' }"';
									} elseif ( 'quicktime' == $image_lightbox ) {
										$add_classes .= ' wpex-lightbox';
										$data_attributes .= 'data-type="video"';
										$data_attributes .= 'data-options="width:1920,height:1080"';
									}
								}
							}
							if ( isset( $a_href ) && $a_href ) { ?>
								<a href="<?php echo esc_url( $a_href ); ?>" title="<?php echo $a_title; ?>" target="<?php echo $a_target; ?>" class="vcex-feature-box-image-link <?php echo $add_classes; ?>" <?php echo $inline_style; ?> <?php echo $data_attributes; ?>>
							<?php } else { ?>
								<div class="<?php echo $add_classes; ?>" <?php echo $inline_style; ?>>
							<?php } ?>
							<img src="<?php echo wpex_image_resize( $image_img_url, intval( $img_width ),  intval( $img_height ), $img_crop ); ?>" alt="<?php echo $image_alt; ?>" <?php echo $inline_style; ?> />
							<?php if ( isset( $a_href ) && $a_href ) { ?>
								</a><!-- .vcex-feature-box-image -->
							<?php } else { ?>
								</div><!-- .vcex-feature-box-image -->
							<?php } ?>
							<?php } // End video check ?>
						</div><!-- .vcex-feature-box-media -->
					<?php } // $video or $image check ?>
					<?php
					// Content area
					if ( $content || $heading ) {
					/*** Content Area ***/
					$add_classes = 'vcex-feature-box-content clr';
					if ( $equal_heights ) {
						$add_classes .=' match-height-feature';
					}
					$inline_style = '';
					if ( $content_width ) {
						$inline_style .= 'width:'. $content_width.';';
					}
					if ( $content_background ) {
						$inline_style .= 'background:'. $content_background.';';
					}
					if ( $inline_style ) {
						$inline_style = 'style="'. $inline_style .'"';
					} ?>
					<div class="<?php echo $add_classes; ?>" <?php echo $inline_style; ?>>
						<?php if ( $content_padding ) { ?>
						<div class="vcex-feature-box-padding-container clr" style="padding:<?php echo $content_padding; ?>;">
						<?php } ?>
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
							}

							// Heading URL
							$a_href = '';
							if ( $heading_url && '||' != $heading_url ) {
								$link = vc_build_link( $heading_url );
								$a_href = isset( $link['url'] ) ? $link['url'] : '';
								$a_title = isset( $link['title'] ) ? $link['title'] : '';
								$a_target = isset( $link['target'] ) ? $link['target'] : '';
							}
							if ( isset( $a_href ) && $a_href ) { ?>
								<a href="<?php echo esc_url( $a_href ); ?>" title="<?php echo $a_title; ?>" target="<?php echo $a_target; ?>" class="vcex-feature-box-heading-link">
							<?php } ?> 
							<<?php echo $heading_type; ?> class="vcex-feature-box-heading" <?php echo $inline_style; ?>>
								<?php
								// Display heading
								echo $heading; ?>
							</<?php echo $heading_type; ?>>
							<?php if ( isset( $a_href ) && $a_href ) { ?>
							</a>
							<?php } ?>
						<?php
						}
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
							<div class="vcex-feature-box-text clr" <?php echo $inline_style; ?>>
								<?php echo apply_filters(  'the_content', $content ); ?>
							</div><!-- .vcex-feature-box-text -->
						<?php } ?>
						<?php if ( $content_padding ) { ?>
						</div><!-- .vcex-feature-box-padding-container -->
						<?php } ?>
					</div><!-- .vcex-feature-box-content -->
				<?php } ?>
			</div><!-- .vcex-feature -->
		
		<?php
		// Return outbut buffer
		return ob_get_clean();
	}
}
add_shortcode( 'vcex_feature_box', 'vcex_feature_box_shortcode' );

if ( ! function_exists( 'vcex_feature_box_shortcode_vc_map' ) ) {
	function vcex_feature_box_shortcode_vc_map() {
		$vc_img_rendering_url = 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering';
		vc_map( array(
			"name"					=> __( "Feature Box", 'wpex' ),
			"description"			=> __( "A feature content box (left/right).", 'wpex' ),
			"base"					=> "vcex_feature_box",
			'category'				=> WPEX_THEME_BRANDING,
			"icon"					=> "vcex-feature-box",
			"params"				=> array(
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
					"type"			=> 'dropdown',
					"heading"		=> __( "Style", "wpex" ),
					"param_name"	=> "style",
					"value"			=> array(
						__( "Left Image - Right Content", "wpex" )	=> "left-image-right-content",
						__( "Left Content - Right Image", "wpex" )	=> "left-content-right-image",
					),
				),
				array(
					"type"			=> 'dropdown',
					"heading"		=> __( "CSS Animation", "wpex" ),
					"param_name"	=> "css_animation",
					"value"			=> array(
						__( "No", "wpex" )					=> '',
						__( "Top to bottom", "wpex" )			=> "top-to-bottom",
						__( "Bottom to top", "wpex" )			=> "bottom-to-top",
						__( "Left to right", "wpex" )			=> "left-to-right",
						__( "Right to left", "wpex" )			=> "right-to-left",
						__( "Appear from center", "wpex" )	=> "appear" ),
				),
				array(
					"type"			=> 'dropdown',
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
					"type"			=> 'textfield',
					"heading"		=> __( "Padding", "wpex" ),
					"param_name"	=> "padding",
				),
				array(
					"type"			=> 'colorpicker',
					"heading"		=> __( "Background", "wpex" ),
					"param_name"	=> "background",
				),
				array(
					"type"			=> 'textfield',
					"heading"		=> __( "Border", "wpex" ),
					"param_name"	=> "border",
				),

				// Widths
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Content Width", "wpex" ),
					"param_name"	=> "content_width",
					"value"			=> '50%',
					'group'			=> __( 'Widths', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Image Width", "wpex" ),
					"param_name"	=> "media_width",
					"value"			=> '50%',
					'group'			=> __( 'Widths', 'wpex' ),
				),
				array(
					"type"			=> 'dropdown',
					"heading"		=> __( "Tablet Widths", "wpex" ),
					"param_name"	=> "tablet_widths",
					"value"			=> array(
						__( "Inherit", "wpex" )		=> "",
						__( "Full-Width", "wpex" )	=> "fullwidth",
					),
					'group'			=> __( 'Widths', 'wpex' ),
				),
				array(
					"type"			=> 'dropdown',
					"heading"		=> __( "Phone Widths", "wpex" ),
					"param_name"	=> "phone_widths",
					"value"			=> array(
						__( "Inherit", "wpex" )		=> "",
						__( "Full-Width", "wpex" )	=> "fullwidth",
					),
					'group'			=> __( 'Widths', 'wpex' ),
				),

				// Heading
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Heading", 'wpex' ),
					"param_name"	=> "heading",
					"value"			=> "Sample Heading",
					'group'			=> __( 'Heading', 'wpex' ),
				),
				array(
					"type"			=> 'dropdown',
					"heading"		=> __( "Heading Type", 'wpex' ),
					"param_name"	=> "heading_type",
					 "value"		=> array(
						__( "h2", "wpex" )	=> "h2",
						__( "h3", "wpex" )	=> "h3",
						__( "h4", "wpex" )	=> "h4",
						__( "h5", "wpex" )	=> "h5",
						__( "div", "wpex" )	=> "div",
					),
					'group'			=> __( 'Heading', 'wpex' ),
				),
				array(
					"type"			=> "vc_link",
					"heading"		=> __( "Heading URL", 'wpex' ),
					"param_name"	=> "heading_url",
					"value"			=> "",
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
				
				// Content
				array(
					"type"			=> "textarea_html",
					"holder"		=> "div",
					"heading"		=> __( "Content", 'wpex' ),
					"param_name"	=> "content",
					'value'			=> __( 'Don\'t forget to change this dummy text in your page editor for this lovely feature box.', 'wpex' ),
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

				// Image
				array(
					"type"			=> "attach_image",
					"heading"		=> __( "Image", "wpex" ),
					"param_name"	=> "image",
					"value"			=> "",
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					"heading"		=> __( "Equal Heights?", 'wpex' ),
					'param_name'	=> "equal_heights",
					'value'			=> array(
						__( "No", "wpex" )	=> "",
						__( "Yes", "wpex" )	=> "true",
					),
					'description'	=> __( 'Keeps the image column the same height as your content.', 'wpex' ),
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> "vc_link",
					"heading"		=> __( "Image URL", 'wpex' ),
					"param_name"	=> "image_url",
					"value"			=> "",
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Lightbox Type', 'wpex' ),
					'param_name'	=> "image_lightbox",
					'value'			=> array(
						__( 'None', 'wpex' )						=> '',
						__( 'Self (Image)', 'wpex' )				=> 'image',
						__( 'URL', 'wpex' )							=> 'url',
						__( 'Auto Detect', 'wpex' )					=> 'auto-detect',
						__( 'Video/Youtube Embed Code', 'wpex' )	=> "video_embed",
						__( 'HTML5', 'wpex' )						=> "html5",
						__( 'Quicktime', 'wpex' )					=> "quicktime",
					),
					'group'			=> __( 'Image', 'wpex' ),
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
					'group'			=> __( 'Image', 'wpex' ),
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
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Border Radius", 'wpex' ),
					"param_name"	=> "img_border_radius",
					"value"			=> "",
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> 'dropdown',
					"heading"		=> __( "Image Filter", 'wpex' ),
					"param_name"	=> "img_filter",
					"value"			=> vcex_image_filters(),
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),
				array(
					"type"			=> 'dropdown',
					"heading"		=> __( "CSS3 Image Hover", 'wpex' ),
					"param_name"	=> "img_hover_style",
					"value"			=> vcex_image_hovers(),
					"description"	=> __( "Select your preferred image hover effect. Please note this will only work if the image links to a URL or a large version of itself. Please note these effects may not work in all browsers.", "wpex" ),
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
					'dependency'	=> array(
						'element'	=> 'equal_heights',
						'value'		=> '',
					),
				),
				array(
					"type"			=> 'dropdown',
					"heading"		=> __( "Image Rendering", 'wpex' ),
					"param_name"	=> "img_rendering",
					"value"			=> vcex_image_rendering(),
					"description"	=> sprintf( __( 'Image-rendering CSS property provides a hint to the user agent about how to handle its image rendering. For example when scaling down images they tend to look a bit fuzzy in Firefox, setting image-rendering to crisp-edges can help make the images look better. <a href="%s">Learn more</a>.', 'wpex' ), esc_url( $vc_img_rendering_url ) ),
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'image',
						'not_empty'	=> true,
					),
				),

				// Video
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Video link", "wpex" ),
					"param_name"	=> "video",
					"description"	=> sprintf(__('Enter a video link for a video based feature box. More about supported formats at %s.', "wpex" ), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>'),
					'group'			=> __( 'Video', 'wpex' ),
				),

			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_feature_box_shortcode_vc_map' );