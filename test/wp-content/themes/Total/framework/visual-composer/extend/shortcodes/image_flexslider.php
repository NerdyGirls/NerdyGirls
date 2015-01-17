<?php
/**
 * Registers the image slider shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_image_flexslider_shortcode' ) ) {
	function vcex_image_flexslider_shortcode($atts) {
		
		// Define shortcode params
		extract( shortcode_atts( array(
			'unique_id'				=> '',
			'image_ids'				=> '',
			'animation'				=> 'slide',
			'slideshow'				=> 'true',
			'randomize'				=> 'false',
			'direction'				=> 'horizontal',
			'slideshow_speed'		=> '7000',
			'animation_speed'		=> '600',
			'control_nav'			=> 'true',
			'direction_nav'			=> 'true',
			'pause_on_hover'		=> 'true',
			'smooth_height'			=> 'false',
			'thumbnail_link'		=> 'lightbox',
			'custom_links'			=> '',
			'custom_links_target'	=> '_self',
			'img_width'				=> '9999',
			'img_height'			=> '9999',
			'caption'				=> 'true',
			'img_rendering'			=> '',
			'control_thumbs'		=> 'false',
		), $atts ) );

		// Turn output buffer on
		ob_start();

		// Get Attachments
		$attachments = explode(",",$image_ids);
		$attachments = array_combine($attachments,$attachments);
		
		// Dummy images when no images are defined
		$dummy_images = NULL;
		if ( empty($image_ids) ) {
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

			// Set output var
			$output = '';

			// Control Thumbnails
			if ( 'true' == $control_thumbs ) {
				$control_nav = 'thumbnails';
			} else {
				$control_nav = $control_nav;
			}

			// Flexslider Data
			$flexslider_data = 'data-animation="'. $animation .'"';
			$flexslider_data .= ' data-slideshow="'. $slideshow .'"';
			$flexslider_data .= ' data-randomize="'. $randomize .'"';
			$flexslider_data .= ' data-direction="'. $direction .'"';
			$flexslider_data .= ' data-slideshow-speed="'. $slideshow_speed .'"';
			$flexslider_data .= ' data-animation-speed="'. $animation_speed .'"';
			$flexslider_data .= ' data-direction-nav="'. $direction_nav .'"';
			$flexslider_data .= ' data-pause="'. $pause_on_hover .'"';
			$flexslider_data .= ' data-smooth-height="'. $smooth_height .'"';
			$flexslider_data .= ' data-control-nav="'. $control_nav .'"';
		
			// Custom Links
			if ( $thumbnail_link == 'custom_link' ) {
				$custom_links = explode( ',', $custom_links);
			}

			// Main Classes
			$classes = 'vcex-flexslider-wrap vcex-img-flexslider flexslider-container clr';
			if ( 'lightbox' == $thumbnail_link ) {
				$classes .= ' lightbox-group';
			}

			// Unique ID
			$unique_id = $unique_id ? ' id="'. $unique_id .'"' : NULL; ?>

			<div class="<?php echo $classes; ?>"<?php echo $unique_id; ?>>
				<div class="vcex-flexslider flexslider" <?php echo $flexslider_data; ?>>
					<ul class="slides">
					<?php
					// Loop through attachments
					$count=-1;
					foreach ( $attachments as $attachment ) :
						$count++;
					
						// Attachment VARS
						$attachment_link	= get_post_meta( $attachment, '_wp_attachment_url', true );
						$attachment_img_url	= wp_get_attachment_url( $attachment );
						$attachment_alt		= strip_tags( get_post_meta( $attachment, '_wp_attachment_image_alt', true) );
						$attachment_caption	= esc_attr( get_post_field( 'post_excerpt', $attachment ) );
						
						// Get and crop image if needed
						if ( $dummy_images ) {
							$attachment_img = $attachment;
						} else {
							$attachment_img	= wp_get_attachment_url( $attachment );
							$img_width		= intval($img_width);
							$img_height		= intval($img_height);
							$crop			= $img_height == '9999' ? false : true;
							$attachment_img	= wpex_image_resize( $attachment_img, $img_width, $img_height, $crop );
						}

						// Image rendering
						if ( $img_rendering ) {
							$img_rendering = ' vcex-image-rendering-'. $img_rendering;
						}
						
						// Image output
						$image_output = '<img src="'. $attachment_img .'" alt="'. $attachment_alt .'" />';

						// Thumb Data attr
						if ( 'true' == $control_thumbs ) {
							$data_thumb = 'data-thumb="'. wpex_image_resize( $attachment_img_url, 100, 100, true ) .'"';
						} else {
							$data_thumb = '';
						} ?>
		
						<li class="vcex-flexslider-slide slide <?php echo $img_rendering; ?>" <?php echo $data_thumb; ?>>
							<div class="vcex-flexslider-entry-media">
								<?php
								// Lightbox links
								if ( 'lightbox' == $thumbnail_link ) { ?>
									<a href="<?php echo $attachment_img_url; ?>" title="<?php echo $attachment_caption; ?>" class="vcex-flexslider-entry-img lightbox-group-item">
										<?php echo $image_output; ?>
									</a>
								<?php
								// Custom links
								} elseif ( 'custom_link' == $thumbnail_link ) {
									$custom_link = !empty($custom_links[$count]) ? $custom_links[$count] : '#';
									if ( '#' == $custom_link ) { ?>
										<?php echo $image_output; ?>
									<?php } else { ?>
										<a href="<?php echo esc_url( $custom_link ); ?>" title="<?php echo $attachment_caption; ?>" class="vcex-flexslider-entry-img" target="<?php echo $custom_links_target; ?>">
											<?php echo $image_output; ?>
										</a>
									<?php }
								} else {
									// Plain Image
									echo $image_output;
								}
								// Image Caption
								if ( 'true' == $caption && $attachment_caption ) { ?>
									<div class="vcex-img-flexslider-caption clr">
										<?php echo $attachment_caption; ?>
									</div><!-- .vcex-flexslider-entry-caption -->
								<?php } ?>
							</div><!-- .vcex-flexslider-entry-media -->
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
add_shortcode( 'vcex_image_flexslider', 'vcex_image_flexslider_shortcode' );

if ( ! function_exists( 'vcex_image_flexslider_shortcode_vc_map' ) ) {
	function vcex_image_flexslider_shortcode_vc_map() {
		$vc_img_rendering_url = 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering';
		vc_map( array(
			'name'					=> __( 'Image FlexSlider', 'wpex' ),
			'description'			=> __( 'Custom image slider.', 'wpex' ),
			'base'					=> 'vcex_image_flexslider',
			'category'				=> WPEX_THEME_BRANDING,
			'icon' 					=> 'vcex-image-flexslider',
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'class'			=> '',
					'heading'		=> __( 'Unique Id', 'wpex' ),
					'param_name'	=> 'unique_id',
					'value'			=> '',
					'description'	=> __( 'You can enter a unique ID here for styling purposes.', 'wpex' ),
				),
				array(
					'type'			=> 'attach_images',
					'admin_label'	=> true,
					'class'			=> '',
					'heading'		=> __( 'Attach Images', 'wpex' ),
					'param_name'	=> 'image_ids',
					//'description'	=> __( 'Select your slider images.', 'wpex' ),
					'group'			=> __( 'Gallery', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'class'			=> '',
					'heading'		=> __( 'Image Link', 'wpex' ),
					'param_name'	=> 'thumbnail_link',
					'value'			=> array(
						__( 'None', 'wpex' )			=> 'none',
						__( 'Lightbox', 'wpex' )		=> 'lightbox',
						__( 'Custom Links', 'wpex' )	=> 'custom_link',
					),
					//'description'	=> __( 'Where should the slider images link to?', 'wpex' ),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'			=> 'exploded_textarea',
					'heading'		=> __('Custom links', 'wpex'),
					'param_name'	=> 'custom_links',
					'description'	=> __( 'Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol.', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'thumbnail_link',
						'value'		=> array( 'custom_link' )
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading' 		=> __('Custom link target', 'wpex'),
					'param_name' 	=> 'custom_links_target',
					//'description'	=> __('Select where to open custom links.', 'wpex'),
					'dependency'	=> Array(
						'element' => 'thumbnail_link',
						'value' => array('custom_link'
					) ),
					'value'			=> array(
						__('Same window', 'wpex') => '_self',
						__('New window', 'wpex') => '_blank'
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'class'			=> '',
					'heading'		=> __( 'Image Crop Width', 'wpex' ),
					'param_name'	=> 'img_width',
					'value'			=> '9999',
					//'description'	=> __( 'Enter a width in pixels.', 'wpex' ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'class'			=> '',
					'heading'		=> __( 'Image Crop Height', 'wpex' ),
					'param_name'	=> 'img_height',
					'value'			=> '9999',
					'description'	=> __( 'Enter a height in pixels. Set to "9999" to disable vertical cropping and keep image proportions.', 'wpex' ),
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
					'class'			=> '',
					'heading'		=> __( 'Display Caption', 'wpex' ),
					'param_name'	=> 'caption',
					'value'			=> array(
						__( 'True', 'wpex')		=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
					//'description'	=> __( 'If set true it will display your image caption.', 'wpex' ),
					'group'			=> __( 'Caption', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'class'			=> '',
					'heading'		=> __( 'Animation', 'wpex' ),
					'param_name'	=> 'animation',
					'value'			=> array(
						__( 'Slide', 'wpex')	=> 'slide',
						__( 'Fade', 'wpex' )	=> 'fade',
					),
					//'description'	=> __( 'Select your animation style.', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'class'			=> '',
					'heading'		=> __( 'Slideshow', 'wpex' ),
					'param_name'	=> 'slideshow',
					'value'			=> array(
						__( 'True', 'wpex')		=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
					'description'	=> __( 'Enable automatic slideshow? Disabled in front-end composer to prevent page "jumping".', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'class'			=> '',
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
					'heading'		=> __( 'Control Nav', 'wpex' ),
					'param_name'	=> 'control_nav',
					'value'			=> array(
						__( 'True', 'wpex')		=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
					'description'	=> __( 'Display the control navigation? These are the white "dots" at the top of the slider.', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'admin_label'	=> true,
					'class'			=> '',
					'heading'		=> __( 'Navigation Thumbnails', 'wpex' ),
					'param_name'	=> 'control_thumbs',
					'description'	=> __( 'Enable Thumbnail Navigation?', 'wpex' ),
					'value'			=> array(
						__( 'No', 'wpex' )	=> 'false',
						__( 'Yes', 'wpex' )	=> 'true',
					),
				),
				array(
					'type'			=> 'dropdown',
					'class'			=> '',
					'heading'		=> __( 'Direction Nav', 'wpex' ),
					'param_name'	=> 'direction_nav',
					'value'			=> array(
						__( 'True', 'wpex')		=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
					'description'	=> __( 'Display the next and previous arrows?', 'wpex' ),
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
					'class'			=> '',
					'heading'		=> __( 'Slideshow Speed', 'wpex' ),
					'param_name'	=> 'slideshow_speed',
					'value'			=> '7000',
					'description'	=> __( 'Enter your desired slideshow speed in milliseconds. Default is 7000.', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'class'			=> '',
					'heading'		=> __( 'Animation Speed', 'wpex' ),
					'param_name'	=> 'animation_speed',
					'value'			=> '600',
					'description'	=> __( 'Enter your desired animation speed in milliseconds. Default is 600.', 'wpex' ),
				),
			)
			
		) );
	}
}
add_action( 'vc_before_init', 'vcex_image_flexslider_shortcode_vc_map' );