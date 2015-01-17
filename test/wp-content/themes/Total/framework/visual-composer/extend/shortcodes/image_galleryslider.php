<?php
/**
 * Registers the image gallery slider shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_image_galleryslider_shortcode' ) ) {
	function vcex_image_galleryslider_shortcode( $atts ) {
		
		extract( shortcode_atts( array(
			'unique_id'				=> '',
			'image_ids'				=> '',
			'animation'				=> 'fade',
			'slideshow'				=> 'true',
			'randomize'				=> 'false',
			'direction'				=> 'horizontal',
			'slideshow_speed'		=> '7000',
			'animation_speed'		=> '600',
			'pause_on_hover'		=> 'true',
			'smooth_height'			=> 'false',
			'thumbnail_link'		=> 'lightbox',
			'custom_links'			=> '',
			'custom_links_target'	=> '_self',
			'img_width'				=> '9999',
			'img_height'			=> '9999',
			'img_thumb_width'		=> '200',
			'img_thumb_height'		=> '200',
			'caption'				=> 'true',
			'img_rendering'			=> '',
		), $atts ) );

		// Turn output buffer on
		ob_start();

		// Get Attachments
		$attachments = explode(",",$image_ids);
		$attachments = array_combine($attachments,$attachments);

		// Dummy images when no images are defined
		$dummy_images = NULL;
		if ( empty( $image_ids ) ) {
			$dummy_images = true;
			$attachments = array(
				WPEX_VCEX_DIR_URI .'assets/images/dummy2.jpg',
				WPEX_VCEX_DIR_URI .'assets/images/dummy3.jpg',
			);
		}

		//Output images
		if( $attachments ) :

			// Output script for inline JS for the Visual composer front-end builder
			if ( function_exists( 'vcex_front_end_slider_js' ) ) {
				vcex_front_end_slider_js();
			}

			// Output var
			$output = '';

			// Unique ID
			$unique_id = $unique_id ? ' id="'. $unique_id .'"' : NULL;
		
			// Custom Links
			if ( $thumbnail_link == 'custom_link' ) {
				$custom_links = explode( ',', $custom_links);
			}

			// Image rendering
			if ( $img_rendering ) {
				$img_rendering = ' vcex-image-rendering-'. $img_rendering;
			}

			if ( 'fade_anim' == $animation ) {
				$animation = 'fade';
			}

			// Flexslider Data
			$flexslider_data = 'data-animation="'. $animation .'"';
			$flexslider_data .= ' data-slideshow="'. $slideshow .'"';
			$flexslider_data .= ' data-randomize="'. $randomize .'"';
			$flexslider_data .= ' data-direction="'. $direction .'"';
			$flexslider_data .= ' data-slideshow-speed="'. $slideshow_speed .'"';
			$flexslider_data .= ' data-animation-speed="'. $animation_speed .'"';
			$flexslider_data .= ' data-direction-nav="false"';
			$flexslider_data .= ' data-pause="'. $pause_on_hover .'"';
			$flexslider_data .= ' data-smooth-height="'. $smooth_height .'"';
			$flexslider_data .= ' data-control-nav="thumbnails"'; ?>

			<div class="vcex-flexslider-wrap clr vcex-img-galleryslider <?php echo $img_rendering; ?>" <?php echo $unique_id; ?>>
				<div class="vcex-galleryslider flexslider lighbox-group" <?php echo $flexslider_data; ?>>
					<ul class="slides">
						<?php
						// Loop through attachments
						$count=-1;
						foreach ( $attachments as $attachment ) :
						$count++;
						
							// Attachment VARS
							$attachment_link	= get_post_meta( $attachment, '_wp_attachment_url', true );
							$attachment_img_url	= wp_get_attachment_url( $attachment );
							$attachment_alt		= strip_tags( get_post_meta($attachment, '_wp_attachment_image_alt', true) );
							$attachment_caption	= esc_attr( get_post_field( 'post_excerpt', $attachment ) );

							// Get and crop image if needed
							if ( $dummy_images ) {
								$attachment_img	= $attachment;
								$img_thumb		= wpex_image_resize( $attachment, $img_thumb_width, $img_thumb_height, true );
							} else {
								$thumbnail_hard_crop	= $img_height == '9999' ? false : true;
								$img_width				= intval( $img_width );
								$img_height				= intval( $img_height );
								$attachment_img			= wpex_image_resize( $attachment_img_url, $img_width, $img_height, $thumbnail_hard_crop );
								$img_thumb				= wpex_image_resize( $attachment_img_url, $img_thumb_width, $img_thumb_height, true );
							}

							$image_output = '<img src="'. $attachment_img. '" alt="'. $attachment_alt .'" />'; ?>

							<li class="vcex-galleryslider-slide slide" data-thumb="<?php echo $img_thumb; ?>">
								<div class="vcex-galleryslider-entry-media">
									<?php
									// Lightbox
									if ( 'lightbox' == $thumbnail_link ) { ?>
										<a href="<?php echo $attachment_img_url; ?>" title="<?php echo $attachment_alt; ?>" class="vcex-galleryslider-entry-img lightbox-group-item">
											<?php echo $image_output; ?>
										</a>
									<?php
									// Custom Links
									} elseif ( 'custom_link' == $thumbnail_link ) {
										$custom_link = ! empty( $custom_links[$count] ) ? $custom_links[$count] : '#';
										if ( '#' == $custom_link ) {
											echo $image_output;
										} else { ?>
											<a href="<?php echo esc_url( $custom_link ); ?>" title="<?php echo $attachment_alt; ?>" class="vcex-galleryslider-entry-img" target="<?php echo $custom_links_target; ?>">
												<?php echo $image_output; ?>
											</a>
										<?php }
									} else {
										//Plain Image
										echo $image_output;
									}
									// Image caption
									if ( $caption == 'true' && $attachment_caption ) { ?>
										<div class="vcex-galleryslider-entry-title"><?php echo $attachment_caption; ?></div>
									<?php } ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="vcex-clear-floats"></div>
		
			<?php
			endif; // End has posts check

		// Return outbut buffer
		return ob_get_clean();
		
	}
}
add_shortcode( 'vcex_image_galleryslider', 'vcex_image_galleryslider_shortcode' );

if ( ! function_exists( 'vcex_image_galleryslider_shortcode_vc_map' ) ) {
	function vcex_image_galleryslider_shortcode_vc_map() {
		$vc_img_rendering_url = 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering';
		vc_map( array(
			'name'					=> __( 'Gallery Slider', 'wpex' ),
			'description'			=> __( 'Image slider with thumbnail navigation', 'wpex' ),
			'base'					=> 'vcex_image_galleryslider',
			'category'				=> WPEX_THEME_BRANDING,
			'icon' 					=> 'vcex-image-gallery-slider',
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Unique Id', 'wpex' ),
					'param_name'	=> 'unique_id',
					'value'			=> '',
					'description'	=> __( 'You can enter a unique ID here for styling purposes.', 'wpex' ),
				),
				array(
					'type'			=> 'attach_images',
					'admin_label'	=> true,
					'heading'		=> __( 'Attach Images', 'wpex' ),
					'param_name'	=> 'image_ids',
					'group'			=> __( 'Gallery', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Link', 'wpex' ),
					'param_name'	=> 'thumbnail_link',
					'value'			=> array(
						__( 'None', 'wpex' )			=> 'none',
						__( 'Lightbox', 'wpex' )		=> 'lightbox',
						__( 'Custom Links', 'wpex' )	=> 'custom_link',
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'			=> 'exploded_textarea',
					'heading'		=> __('Custom links', 'wpex'),
					'param_name'	=> 'custom_links',
					'description'	=> __('Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol.', 'wpex'),
					'dependency'	=> Array(
						'element'	=> 'thumbnail_link',
						'value'		=> array( 'custom_link' )
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __('Custom link target', 'wpex'),
					'param_name'	=> 'custom_links_target',
					'dependency'	=> Array('element' => 'thumbnail_link', 'value' => array('custom_link')),
					'value'			=> array(
						__('Same window', 'wpex')	=> '_self',
						__('New window', 'wpex')	=> '_blank'
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Main Image Crop Width', 'wpex' ),
					'param_name'	=> 'img_width',
					'value'			=> '9999',
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Main Image Crop Height', 'wpex' ),
					'param_name'	=> 'img_height',
					'value'			=> '9999',
					'description'	=> __( 'Enter a height in pixels. Set to "9999" to disable vertical cropping and keep image proportions. Do not leave this setting blank, if you do and your images are not proportional the slider will not function perfectly', 'wpex' ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Thumbnail Image Crop Width', 'wpex' ),
					'param_name'	=> 'img_thumb_width',
					'value'			=> '200',
					'description'	=> __( 'Enter a width in pixels for your thumbnail image width. This won\'t increase the grid, its only used so you can alter the cropping to your preferred proportions.', 'wpex' ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Thumbnail Image Crop Height', 'wpex' ),
					'param_name'	=> 'img_thumb_height',
					'value'			=> '200',
					'description'	=> __( 'Enter a width in pixels for your thumbnail image height.', 'wpex' ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Rendering', 'wpex' ),
					'param_name'	=> 'img_rendering',
					'value'			=> vcex_image_rendering(),
					'description'	=> sprintf( __( 'Image-rendering CSS property provides a hint to the user agent about how to handle its image rendering. For example when scaling down images they tend to look a bit fuzzy in Firefox, setting image-rendering to crisp-edges can help make the images look better. <a href="%s">Learn more</a>.', 'wpex' ), esc_url( $vc_img_rendering_url ) ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Caption', 'wpex' ),
					'param_name'	=> 'caption',
					'value'			=> array(
						__( 'True', 'wpex')		=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
					'description'	=> __( 'Display your image captions in the slider?', 'wpex' ),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Animation', 'wpex' ),
					'param_name'	=> 'animation',
					'value'			=> array(
						__( 'Slide', 'wpex' )	=> 'slide',
						__( 'Fade', 'wpex' )	=> 'fade_anim',
					),
					'description'	=> __( 'Select your animation style. Fade animation may be a bit buggy on this slider style.', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Slideshow', 'wpex' ),
					'param_name'	=> 'slideshow',
					'value'			=> array(
						__( 'True', 'wpex')		=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
					'description'	=> __( 'Enable automatic slideshow?', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Randomize', 'wpex' ),
					'param_name'	=> 'randomize',
					'value'			=> array(
						__( 'False', 'wpex' )	=> 'false',
						__( 'True', 'wpex')		=> 'true',
					),
					'description'	=> __( 'Randomize image order display on page load?', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'class'			=> '',
					'heading'		=> __( 'Smooth Height', 'wpex' ),
					'param_name'	=> 'smooth_height',
					'value'			=> array(
						__( 'True', 'wpex' )	=> 'true',
						__( 'False', 'wpex')	=> 'false',
					),
					'description'	=> __( 'Smooth animation for slides of different heights. A bit buggy with the fade animation but works great with the slide animation.', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Slideshow Speed', 'wpex' ),
					'param_name'	=> 'slideshow_speed',
					'value'			=> '7000',
					'description'	=> __( 'Enter your desired slideshow speed in milliseconds. Default is 7000.', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Animation Speed', 'wpex' ),
					'param_name'	=> 'animation_speed',
					'value'			=> '600',
					'description'	=> __( 'Enter your desired animation speed in milliseconds. Default is 600.', 'wpex' ),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_image_galleryslider_shortcode_vc_map' );