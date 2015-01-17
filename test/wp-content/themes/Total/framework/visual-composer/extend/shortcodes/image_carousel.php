<?php
/**
 * Registers the image carousel shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_image_carousel_shortcode' ) ) {

	function vcex_image_carousel_shortcode($atts) {
		
		// Define shortcode params
		extract( shortcode_atts( array(
			'unique_id'					=> '',
			'classes'					=> '',
			'style'						=> '',
			'image_ids'					=> '',
			'center'					=> 'false',
			'timeout_duration'			=> '5000',
			'items'						=> '4',
			'items_margin'				=> '15',
			'infinite_loop'				=> 'true',
			'items_scroll'				=> '1',
			'auto_play'					=> 'false',
			'arrows'					=> 'true',
			'thumbnail_link'			=> '',
			'gallery_lightbox'			=> '',
			'custom_links'				=> '',
			'custom_links_target'		=> '',
			'img_width'					=> '9999',
			'img_height'				=> '9999',
			'title'						=> 'false',
			'title_type'				=> '',
			'img_filter'				=> '',
			'rounded_image'				=> '',
			'img_hover_style'			=> '',
			'img_rendering'				=> '',
			'caption'					=> 'false',
			'content_background'		=> '',
			'content_heading_margin'	=> '',
			'content_heading_weight'	=> '',
			'content_heading_transform'	=> '',
			'content_margin'			=> '',
			'content_font_size'			=> '',
			'content_padding'			=> '',
			'content_border'			=> '',
			'content_color'				=> '',
			'content_opacity'			=> '',
			'content_heading_color'		=> '',
			'content_heading_size'		=> '',
			'content_alignment'			=> '',
			'tablet_items'				=> '3',
			'mobile_landscape_items'	=> '2',
			'mobile_portrait_items'		=> '1',

		), $atts ) );
		
		// Turn output buffer on
		ob_start();

		// Get Attachments
		$images = explode( ',', $image_ids );
		$images = array_combine( $images, $images );

		// Dummy Images
		$dummy_images = NULL;
		if ( empty( $image_ids ) ) {
			$dummy_images = true;
			$images = array(
				WPEX_VCEX_DIR_URI .'assets/images/dummy1.jpg',
				WPEX_VCEX_DIR_URI .'assets/images/dummy2.jpg',
				WPEX_VCEX_DIR_URI .'assets/images/dummy3.jpg',
				WPEX_VCEX_DIR_URI .'assets/images/dummy4.jpg',
				WPEX_VCEX_DIR_URI .'assets/images/dummy5.jpg',
				WPEX_VCEX_DIR_URI .'assets/images/dummy6.jpg',
			);
		}
		
		// Classes
		$img_classes = array();
		if ( $rounded_image == 'yes' ) {
			$img_classes[] = 'vcex-rounded-images';
		}
		if ( $img_filter ) {
			$img_classes[] = 'vcex-'. $img_filter;
		}
		if ( $img_hover_style ) {
			$img_classes[] = ' vcex-img-hover-parent vcex-img-hover-'. $img_hover_style;
		}
		$img_classes = implode(' ', $img_classes);
		
		// Custom Links
		if ( 'custom_link' == $thumbnail_link ) {
			$custom_links = explode( ',', $custom_links);
		}
		
		// Display carousel if there are images
		if ( $images ) {

			// Output js for front-end editor
			vcex_front_end_carousel_js();
		
			// Give caroufredsel a unique name
			$rand_num = rand( 1, 100 );
			$unique_carousel_id = 'carousel-'. $rand_num;

			// Prevent auto play in visual composer
			if ( wpex_is_front_end_composer() ) {
				$auto_play = 'false';
			}

			// Image sizes
			$img_width	= intval( $img_width );
			$img_height	= intval( $img_height );

			// Item Margin
			if ( 'no-margins' == $style ) {
				$items_margin = '0';
			}

			// Items to scroll fallback for old setting
			if ( 'page' == $items_scroll ) {
				$items_scroll = $items;
			}
			
			// Unique ID
			if ( $unique_id ) {
				$unique_id = ' id="'. $unique_id .'"';
			}

			// Main Classes
			$wrap_classes = 'wpex-carousel wpex-carousel-images clr owl-carousel';
			if ( $style ) {
				$wrap_classes .= ' '. $style;
			}
			if ( $gallery_lightbox ) {
				$wrap_classes .= ' lightbox-group';
			}
			if ( $img_rendering ) {
				$wrap_classes = ' vcex-image-rendering-'. $img_rendering;
			}
			if ( $classes ) {
				$wrap_classes .= ' '. $classes;
			}

			// Lightbox class
			if ( $gallery_lightbox ) {
				$lightbox_class = 'lightbox-group-item';
			} else {
				$lightbox_class = 'wpex-lightbox';
			} ?>

			<div class="<?php echo $wrap_classes; ?>"<?php echo $unique_id; ?> data-items="<?php echo $items; ?>" data-slideby="<?php echo $items_scroll; ?>" data-nav="<?php echo $arrows; ?>" data-autoplay="<?php echo $auto_play; ?>" data-loop="<?php echo $infinite_loop; ?>" data-autoplay-timeout="<?php echo $timeout_duration ?>" data-center="<?php echo $center; ?>" data-margin="<?php echo intval( $items_margin ); ?>" data-items-tablet="<?php echo $tablet_items; ?>" data-items-mobile-landscape="<?php echo $mobile_landscape_items; ?>" data-items-mobile-portrait="<?php echo $mobile_portrait_items; ?>">
				<?php
				// Loop through images
				$count=-1;
				foreach ( $images as $attachment ) :
				$count++;
				
					// Attachment VARS
					$attachment_link	= get_post_meta( $attachment, '_wp_attachment_url', true );
					$attachment_img_url	= wp_get_attachment_url( $attachment );
					$attachment_alt		= strip_tags( get_post_meta( $attachment, '_wp_attachment_image_alt', true ) );
					$attachment_caption	= get_post_field( 'post_excerpt', $attachment );

					$attachment_title = get_the_title( $attachment );
					if ( 'alt' == $title_type ) {
						$attachment_title = $attachment_alt;
					}
					
					// Get and crop image if needed
					if ( $dummy_images ) {
						$attachment_img = $attachment;
					} else {
						$attachment_img	= wp_get_attachment_url( $attachment );
						if ( '9999' == $img_height ) {
							$crop = false;
						} else {
							$crop = true;
						}
						$attachment_img = wpex_image_resize( $attachment_img, $img_width, $img_height, $crop );
					}
					
					// Image output
					$image_output = '<img src="'. $attachment_img .'" alt="'. $attachment_alt .'" />'; ?>
		
					<div class="wpex-carousel-slide">
						<div class="wpex-carousel-entry-media clr <?php echo $img_classes; ?>">
							<?php
							// Lightbox
							if ( 'lightbox' == $thumbnail_link ) { ?>
								<a href="<?php echo $attachment_img_url; ?>" title="<?php echo $attachment_alt; ?>" class="wpex-carousel-entry-img <?php echo $lightbox_class; ?>">
									<?php echo $image_output; ?>
								</a><!-- .wpex-carousel-entry-img -->
							<?php }
							// Custom Link
							elseif ( 'custom_link' == $thumbnail_link ) {
								$custom_link = !empty( $custom_links[$count] ) ? $custom_links[$count] : '#';
								if ( $custom_link == '#' ) {
									echo $image_output;
								} else { ?>
									<a href="<?php echo esc_url( $custom_link ); ?>" title="<?php echo $attachment_alt; ?>" class="wpex-carousel-entry-img" target="<?php echo $custom_links_target; ?>">
										<?php echo $image_output; ?>
									</a>
								<?php }
							} else {
								echo $image_output;
							} ?>
						</div><!-- .wpex-carousel-entry-media -->

						<?php
						// Open details
						if ( 'yes' == $title || 'yes' == $caption ) {
							if ( $attachment_title || $attachment_caption ) {
							// Content Design
							$content_style = '';
							if ( $content_background ) {
								$content_style .= 'background:'. $content_background .';';
							}
							if ( $content_padding ) {
								$content_style .= 'padding:'. $content_padding .';';
							}
							if ( $content_margin ) {
								$content_style .= 'margin:'. $content_margin .';';
							}
							if ( $content_border ) {
								$content_style .= 'border:'. $content_border .';';
							}
							if ( $content_font_size ) {
								$content_style .= 'font-size:'. $content_font_size .';';
							}
							if ( $content_color ) {
								$content_style .= 'color:'. $content_color .';';
							}
							if ( $content_opacity ) {
								$content_style .= 'opacity:'. $content_opacity .';';
							}
							if ( $content_alignment ) {
								$content_style .= 'text-align:'. $content_alignment .';';
							}
							if ( $content_style ) {
								$content_style = ' style="'. $content_style .'"';
							} ?>
							<div class="wpex-carousel-entry-details clr"<?php echo $content_style; ?>>
						<?php }
							// Display title
							if ( 'yes' == $title && $attachment_title ) {
								// Title design
								$heading_style = '';
								if ( $content_heading_margin ) {
									$heading_style .='margin: '. $content_heading_margin .';';
								}
								if ( $content_heading_transform ) {
									$heading_style .='text-transform: '. $content_heading_transform .';';
								}
								if ( $content_heading_weight ) {
									$heading_style .='font-weight: '. $content_heading_weight .';';
								}
								if ( $content_heading_size ) {
									$heading_style .='font-size: '. $content_heading_size .';';
								}
								if ( $content_heading_color ) {
									$heading_style .='color: '. $content_heading_color .';';
								}
								if ( $heading_style ) {
									$heading_style = ' style="'. $heading_style .'"';
								} ?>
								<div class="wpex-carousel-entry-title"<?php echo $heading_style; ?>><?php echo $attachment_title; ?></div>
							<?php }
							// Display caption
							if ( 'yes' == $caption && $attachment_caption ) { ?>
								<div class="wpex-carousel-entry-excerpt"><?php echo $attachment_caption; ?></div>
							<?php }
							// Close details
							if ( 'yes' == $title || 'yes' == $caption ) {
								if ( $attachment_title || $attachment_caption ) { ?>
									</div>
								<?php }
							}
						} ?>
					</div><!-- .wpex-carousel-slide -->
				
				<?php
				// End foreach loop
				endforeach; ?>
			</div><!-- .wpex-carousel -->
	
		<?php
		} // End has images check

		// Return outbut buffer
		return ob_get_clean();
		
	}
}
add_shortcode( 'vcex_image_carousel', 'vcex_image_carousel_shortcode' );

if ( ! function_exists( 'vcex_image_carousel_shortcode_vc_map' ) ) {
	function vcex_image_carousel_shortcode_vc_map() {
	$vc_img_rendering_url = 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering';
		vc_map( array(
			"name"					=> __( "Image Carousel", 'wpex' ),
			'description'			=> __( "Image based jQuery carousel.", 'wpex' ),
			"base"					=> "vcex_image_carousel",
			'category'				=> WPEX_THEME_BRANDING,
			"icon" 					=> "vcex-image-carousel",
			"params"				=> array(

				// Gallery
				array(
					'type'			=> "attach_images",
					"admin_label"	=> true,
					'heading'		=> __( "Attach Images", 'wpex' ),
					'param_name'	=> "image_ids",
					'group'			=> __( 'Gallery', 'wpex' ),
				),

				// General
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Unique Id", 'wpex' ),
					'param_name'	=> "unique_id",
					'value'			=> '',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Custom Classes", 'wpex' ),
					'param_name'	=> "classes",
					'value'			=> '',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Items To Display", 'wpex' ),
					'param_name'	=> "items",
					'value'			=> "4",
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Items To Scrollby", 'wpex' ),
					'param_name'	=> 'items_scroll',
					'value'			=> '',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Margin Between Items", 'wpex' ),
					'param_name'	=> 'items_margin',
					'value'			=> '15',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Auto Play", 'wpex' ),
					'param_name'	=> "auto_play",
					'value'			=> array(
						__( 'True', 'wpex' )	=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Infinite Loop", 'wpex' ),
					'param_name'	=> "infinite_loop",
					'value'			=> array(
						__( 'True', 'wpex' )	=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Center Item", 'wpex' ),
					'param_name'	=> "center",
					'value'			=> array(
						__( 'False', 'wpex' )	=> 'false',
						__( 'True', 'wpex' )	=> 'true',
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Timeout Duration (in milliseconds)', 'wpex' ),
					'param_name'	=> "timeout_duration",
					'value'			=> "5000",
					'dependency'	=> Array(
						'element'	=> "auto_play",
						'value'		=> 'true'
					),
				),

				// Design
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Style", 'wpex' ),
					'param_name'	=> "style",
					'value'			=> array(
						__( "Default", 'wpex' )		=> "",
						__( "No Margins", 'wpex' )	=> "no-margins",
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Display Arrows?", 'wpex' ),
					'param_name'	=> "arrows",
					'value'			=> array(
						__( 'True', 'wpex' )	=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Title?', 'wpex' ),
					'param_name'	=> "title",
					'value'			=> Array(
						__( 'True', 'wpex' )	=> 'yes',
						__( 'False', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Title Based On Image", 'wpex' ),
					'param_name'	=> "title_type",
					'value'			=> array(
						__( 'Title', 'wpex' )		=> 'title',
						__( 'Alt', 'wpex' )			=> 'alt',
					),
					'group'			=> __( 'Design', 'wpex' ),
					"dependency"	=> Array(
						'element'	=> 'title',
						'value'		=> array( 'yes' )
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Caption', 'wpex' ),
					'param_name'	=> 'caption',
					'value'			=> Array(
						__( 'True', 'wpex' )	=> 'yes',
						__( 'False', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"heading"		=> __( "Title Font size", 'wpex' ),
					'param_name'	=> "content_heading_size",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"heading"		=> __( "Title Margin", 'wpex' ),
					'param_name'	=> "content_heading_margin",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"heading"		=> __( "Title Font Weight", 'wpex' ),
					'param_name'	=> "content_heading_weight",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Title Text Transform", 'wpex' ),
					'param_name'	=> "content_heading_transform",
					'value'			=> array(
						__( 'Default', 'wpex' )		=> '',
						__( 'None', 'wpex' )		=> 'none',
						__( 'Capitalize', 'wpex' )	=> 'capitalize',
						__( 'Uppercase', 'wpex' )	=> 'uppercase',
						__( 'Lowercase', 'wpex' )	=> 'lowercase',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Title Text Color", 'wpex' ),
					'param_name'	=> "content_heading_color",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Content Background", 'wpex' ),
					'param_name'	=> "content_background",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Content Text Color", 'wpex' ),
					'param_name'	=> "content_color",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Content Alignment', 'wpex' ),
					'param_name'	=> "content_alignment",
					'value'			=> array(
						__( "Default", "wpex" )	=> '',
						__( "Left", "wpex" )	=> "left",
						__( "Right", "wpex" )	=> "right",
						__( "Center", "wpex")	=> "center",
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"heading"		=> __( "Content Font Size", 'wpex' ),
					'param_name'	=> "content_font_size",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"heading"		=> __( "Content Margin", 'wpex' ),
					'param_name'	=> "content_margin",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"heading"		=> __( "Content Padding", 'wpex' ),
					'param_name'	=> "content_padding",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"heading"		=> __( "Content Opacity", 'wpex' ),
					'param_name'	=> "content_opacity",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"heading"		=> __( "Content Border", 'wpex' ),
					'param_name'	=> "content_border",
					'group'			=> __( 'Design', 'wpex' ),
				),

				// Links
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Image Link", 'wpex' ),
					'param_name'	=> "thumbnail_link",
					'value'			=> array(
						__( "None", 'wpex' )			=> "none",
						__( "Lightbox", 'wpex' )		=> "lightbox",
						__( "Custom Links", 'wpex' )	=> "custom_link",
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				/*array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Gallery Lightbox", 'wpex' ),
					'param_name'	=> "gallery_lightbox",
					'value'			=> array(
						__( "False", 'wpex' )	=> '',
						__( "True", 'wpex' )	=> true,
					),
					'dependency'	=> Array(
						'element'	=> "thumbnail_link",
						'value'		=> array( 'lightbox' )
					),
					'group'			=> __( 'Links', 'wpex' ),
				),*/
				array(
					'type'			=> 'exploded_textarea',
					'heading'		=> __( 'Custom links', 'wpex' ),
					'param_name'	=> 'custom_links',
					'description'	=> __( 'Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol. And don\'t forget to include the http:// at the front.', 'wpex'),
					'dependency'	=> Array(
						'element'	=> "thumbnail_link",
						'value'		=> array( 'custom_link' )
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'				=> 'dropdown',
					'heading'			=> __( 'Custom link target', 'wpex' ),
					'param_name'		=> "custom_links_target",
					'description'		=> __( 'Select where to open custom links.', 'wpex'),
					'dependency'		=> Array(
						'element'	=> "thumbnail_link",
						'value'		=> 'custom_link',
					),
					'value'				=> array(
							__( "Same window", 'wpex' )	=> "_self",
							__( "New window", 'wpex' )	=> "_blank"
						),
					'group'			=> __( 'Links', 'wpex' ),
				),

				// Image Settings
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Image Crop Width", 'wpex' ),
					'param_name'	=> "img_width",
					'value'			=> "500",
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Image Crop Height", 'wpex' ),
					'param_name'	=> "img_height",
					'value'			=> "500",
					'description'	=> __( 'Enter a height in pixels. Set to "9999" to disable vertical cropping and keep image proportions.', 'wpex' ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'checkbox',
					'heading'		=> __( "Rounded Image?", 'wpex' ),
					'param_name'	=> 'rounded_image',
					'value'			=> Array(
						__("Yes please.", 'wpex' )	=> 'yes'
					),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Image Rendering", 'wpex' ),
					'param_name'	=> "img_rendering",
					'value'			=> vcex_image_rendering(),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Image Filter", 'wpex' ),
					'param_name'	=> "img_filter",
					'value'			=> vcex_image_filters(),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "CSS3 Image Hover", 'wpex' ),
					'param_name'	=> "img_hover_style",
					'value'			=> vcex_image_hovers(),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),

				// Responsive Settings
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Tablet (768px-659px): Items To Display", 'wpex' ),
					'param_name'	=> "tablet_items",
					'value'			=> "3",
					'group'			=> __( 'Responsive', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Mobile Landscape (480px-767px): Items To Display", 'wpex' ),
					'param_name'	=> "mobile_landscape_items",
					'value'			=> "2",
					'group'			=> __( 'Responsive', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Mobile Portrait (0-479px): Items To Display", 'wpex' ),
					'param_name'	=> "mobile_portrait_items",
					'value'			=> "1",
					'group'			=> __( 'Responsive', 'wpex' ),
				),

			),

		) );
	}
}
add_action( 'vc_before_init', 'vcex_image_carousel_shortcode_vc_map' );