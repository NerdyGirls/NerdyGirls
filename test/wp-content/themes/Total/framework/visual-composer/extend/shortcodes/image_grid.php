<?php
/**
 * Registers the image grid shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.1
 */

if ( ! function_exists( 'vcex_image_grid_shortcode' ) ) {
	function vcex_image_grid_shortcode( $atts ) {
		
		extract( shortcode_atts( array(
			'unique_id'				=> '',
			'columns'				=> '4',
			'image_ids'				=> '',
			'img_filter'			=> '',
			'grid_style'			=> '',
			'rounded_image'			=> '',
			'thumbnail_link'		=> 'lightbox',
			'custom_links'			=> '',
			'custom_links_target'	=> '_self',
			'img_width'				=> '9999',
			'img_height'			=> '9999',
			'title'					=> 'true',
			'title_type'			=> 'title',
			'img_hover_style'		=> '',
			'img_rendering'			=> '',
			'lightbox_title'		=> '',
			'lightbox_caption'		=> '',
			'is_isotope'			=> false,
		), $atts ) );

		// Start output buffer
		ob_start();
		
			// Define output var
			$output = '';
			
			// Get Attachments
			$images = explode( ",", $image_ids );
			$images = array_combine( $images, $images );

			// Dummy Images
			$dummy_images = false;
			if ( empty( $image_ids ) ) {
				$dummy_images = true;
				$images = array(
					WPEX_VCEX_DIR_URI .'assets/images/dummy1.jpg',
					WPEX_VCEX_DIR_URI .'assets/images/dummy2.jpg',
					WPEX_VCEX_DIR_URI .'assets/images/dummy3.jpg',
					WPEX_VCEX_DIR_URI .'assets/images/dummy4.jpg',
				);
			}

			//Output posts
			if ( $images ) :

				// Custom Links
				if ( 'custom_link' == $thumbnail_link ) {
					$custom_links = explode( ',', $custom_links );
				}

				// Is Isotope var
				if ( 'masonry' == $grid_style || 'no-margins' == $grid_style ) {
					$is_isotope = true;
				}

				// Output script for inline JS for the Visual composer front-end builder
				if ( function_exists( 'vcex_front_end_grid_js' ) && $is_isotope ) {
					vcex_front_end_grid_js( 'isotope' );
				}
			
				// Unique ID
				if ( $unique_id ) {
					$unique_id = ' id="'. $unique_id .'"';
				}

				// Wrap Classes
				$wrap_classes = 'vcex-image-grid wpex-row clr';
				$wrap_classes .= ' grid-style-'. $grid_style;
				if ( $is_isotope ) {
					$wrap_classes .= ' vcex-isotope-grid no-transition';
				}
				if ( 'no-margins' == $grid_style ) {
					$wrap_classes .= ' vcex-no-margin-grid';
				}
				if ( $img_rendering ) {
					$wrap_classes .= ' vcex-image-rendering-'. $img_rendering;
				}
				if ( 'lightbox' == $thumbnail_link ) {
					$wrap_classes .= ' lightbox-group';
				}
				if ( 'yes' == $rounded_image ) {
					$wrap_classes .= ' vcex-rounded-images';
				}

				// Wrap data attributes
				$wrap_data_attributes = '';
				if ( $is_isotope ) {
					$wrap_data_attributes .= ' data-transition-duration="0.0"';
				}

				// Add wrap classes into class
				if ( $wrap_classes ) {
					$wrap_classes = 'class="'. $wrap_classes .'"';
				}

				// Entry Classes
				$entry_classes = 'vcex-image-grid-entry col';
				if ( $is_isotope ) {
					$entry_classes .= ' vcex-isotope-entry';
				}
				if ( 'no-margins' == $grid_style ) {
					$entry_classes .= ' vcex-no-margin-entry';
				}
				if ( $columns ) {
					$entry_classes .= ' span_1_of_'. $columns;
				} ?>

				<div <?php echo $wrap_classes . $unique_id . $wrap_data_attributes; ?>>
					
					<?php
					$count=0;
					// Loop through images
					$count2=-1;
					foreach ( $images as $attachment ) :
					$count++;
						$count2++;

						// Attachment VARS
						$attachment_link	= get_post_meta( $attachment, '_wp_attachment_url', true );
						$attachment_alt		= strip_tags( get_post_meta( $attachment, '_wp_attachment_image_alt', true ) );

						// Title data
						if ( 'false' != $lightbox_title ) {
							if ( 'title' == $lightbox_title ) {
								$data_title = 'data-title="'. strip_tags( get_the_title( $attachment ) ) .'"';
							} else {
								$data_title = 'data-title="'. $attachment_alt .'"';
							}
						} else {
							$data_title = '';
						}

						// Caption data
						$data_caption = '';
						if ( 'false' != $lightbox_caption ) {
							$attachment_caption = get_post_field( 'post_excerpt', $attachment );
							if ( $attachment_caption ) {
								$data_caption = 'data-caption="'. str_replace( '"',"'", $attachment_caption ) .'"';
							}
						}

						// Get and crop image if needed
						if ( $dummy_images ) {
							$cropped_image_url		= $attachment_img_url = $attachment;
							$cropped_image_width	= '';
							$cropped_image_height	= '';
						} else {
							$attachment_img_url		= wp_get_attachment_url( $attachment );
							$img_width				= intval( $img_width );
							$img_height				= intval( $img_height );
							$crop					= $img_height == '9999' ? false : true;
							$cropped_image			= wpex_image_resize( $attachment_img_url, $img_width, $img_height, $crop, 'array' );
							$cropped_image_url		= $cropped_image['url'];
							$cropped_image_width	= $cropped_image['width'];
							$cropped_image_height	= $cropped_image['height'];
						}

						// Hover Classes
						$hover_classes = '';
						if ( $img_filter ) {
							$hover_classes = 'vcex-'. $img_filter;
						}
						if ( $img_hover_style || $img_filter ) {
							$hover_classes .= ' vcex-img-hover-parent vcex-img-hover-'. $img_hover_style;
						} ?>

						<div class="<?php echo $entry_classes; ?> col-<?php echo $count; ?>">
							<figure class="vcex-image-grid-entry-img">
								<?php if ( $img_hover_style || $img_filter ) { ?>
									<div class="<?php echo $hover_classes; ?>">
								<?php } ?>
								<?php
								// Lightbox
								if ( 'lightbox' == $thumbnail_link ) {
									// Define lightbox url
									$lightbox_url		= $attachment_img_url;
									$video_url			= get_post_meta( $attachment, "_video_url", true );
									$data_attributes	= 'data-type="image"';
									if ( $video_url ) {
										$data_attributes	= 'data-type="iframe"';
										$data_attributes	.= 'data-options="thumbnail:\''. $lightbox_url .'\',width:1920,height:1080"';
										$lightbox_url		= $video_url;
									} ?>
									<a href="<?php echo $lightbox_url; ?>" title="<?php echo $attachment_alt; ?>" <?php echo $data_title; ?> <?php echo $data_caption; ?> class="vcex-image-grid-entry-img lightbox-group-item" <?php echo $data_attributes; ?>>
										<img src="<?php echo $cropped_image_url; ?>" alt="<?php echo $attachment_alt; ?>" width="<?php echo $cropped_image_width; ?>" height="<?php echo $cropped_image_height; ?>" />
										<?php
										// Video icon overlay
										if ( $video_url ) { ?>
											<div class="vcex-image-grid-video-overlay rounded"><span class="fa fa-play"></span></div>
										<?php } ?>
									</a><!-- .vcex-image-grid-entry-img -->
								<?php
								}
								// Custom Links
								elseif ( 'custom_link' == $thumbnail_link ) {
									$custom_link = ! empty( $custom_links[$count2] ) ? $custom_links[$count2] : '#';
									if ( '#' == $custom_link ) { ?>
										<img src="<?php echo $cropped_image_url; ?>" alt="<?php echo $attachment_alt; ?>" width="<?php echo $cropped_image_width; ?>" height="<?php echo $cropped_image_height; ?>" />
									<?php } else { ?>
										<a href="<?php echo esc_url( $custom_link ); ?>" title="<?php echo $attachment_alt; ?>" class="vcex-image-grid-entry-img" target="<?php echo $custom_links_target; ?>">
											<img src="<?php echo $cropped_image_url; ?>" alt="<?php echo $attachment_alt; ?>" width="<?php echo $cropped_image_width; ?>" height="<?php echo $cropped_image_height; ?>" />
										</a>
									<?php }
								}
								// Attachment page
								elseif ( 'attachment_page' == $thumbnail_link ) { ?>
									<a href="<?php echo get_attachment_link( $attachment ); ?>" title="<?php echo $attachment_alt; ?>" class="vcex-image-grid-entry-img" target="<?php echo $custom_links_target; ?>">
										<img src="<?php echo $cropped_image_url; ?>" alt="<?php echo $attachment_alt; ?>" width="<?php echo $cropped_image_width; ?>" height="<?php echo $cropped_image_height; ?>" />
									</a>
								<?php }
								// Just the Image
								else { ?>
									<img src="<?php echo $cropped_image_url; ?>" alt="<?php echo $attachment_alt; ?>" width="<?php echo $cropped_image_width; ?>" height="<?php echo $cropped_image_height; ?>" />
								<?php } ?>
								<?php if ( $img_hover_style ) { ?>
								</div><!-- .<?php echo $hover_classes; ?> -->
								<?php } ?>
								<?php
								// Display title
								if ( 'yes' == $title ) {
									// Title
									if ( 'title' == $title_type ) {
										$attachment_title = get_the_title( $attachment );
										if ( $attachment_title ) {
											echo '<figcaption class="vcex-image-grid-entry-title">'. $attachment_title .'</figcaption>';
										}
									}
									// Alt
									elseif ( 'alt' == $title_type && $attachment_alt ) {
										echo '<figcaption class="vcex-image-grid-entry-title">'. $attachment_alt .'</figcaption>';
									}
									// caption
									elseif ( 'caption' == $title_type ) {
										$attachment_caption = get_post_field( 'post_excerpt', $attachment );
										if ( $attachment_caption ) {
											echo '<figcaption class="vcex-image-grid-entry-title">'. $attachment_caption .'</figcaption>';
										}
									}
									// Description
									elseif ( 'description' == $title_type ) {
										$attachment_description = get_post_field( 'post_content', $attachment );
										if ( $attachment_description ) {
											echo '<figcaption class="vcex-image-grid-entry-title">'. apply_filters( 'the_content', $attachment_description ) .'</figcaption>';
										}
									}
								} ?>
							</figure>
						</div>
						
						<?php
						// Clear counter
						if ( $count == $columns ) {
							$count = 0;
						}
					
					// End foreach loop
					endforeach; ?>

				</div>
			
			<?php
			// End has posts check
			endif;
		
			// Reset query
			wp_reset_postdata();

		// Return data
		return ob_get_clean();
		
	}
}
add_shortcode( 'vcex_image_grid', 'vcex_image_grid_shortcode' );

if ( ! function_exists( 'vcex_image_grid_shortcode_vc_map' ) ) {
	function vcex_image_grid_shortcode_vc_map() {
		vc_map( array(
			'name'					=> __( 'Image Grid', 'wpex' ),
			'description'			=> __( 'Responsive image gallery', 'wpex' ),
			'base'					=> 'vcex_image_grid',
			'icon' 					=> 'vcex-image-grid',
			'category'				=> WPEX_THEME_BRANDING,
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Unique Id', 'wpex' ),
					'param_name'	=> 'unique_id',
					'value'			=> '',
				),
				array(
					'type'			=> 'attach_images',
					'admin_label'	=> true,
					'heading'		=> __( 'Attach Images', 'wpex' ),
					'param_name'	=> 'image_ids',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Grid Style', 'wpex' ),
					'param_name'	=> 'grid_style',
					'value'			=> array(
						__( 'Fit Rows', 'wpex' )	=> 'default',
						__( 'Masonry', 'wpex' )		=> 'masonry',
						__( 'No Margins', 'wpex' )	=> 'no-margins',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Columns', 'wpex' ),
					'param_name'	=> 'columns',
					'std'			=> '4',
					'value' 		=> array(
						__( 'Six', 'wpex' )		=> '6',
						__( 'Five', 'wpex' )	=> '5',
						__( 'Four', 'wpex' )	=> '4',
						__( 'Three', 'wpex' )	=> '3',
						__( 'Two', 'wpex' )		=> '2',
						__( 'One', 'wpex' )		=> '1',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Title', 'wpex' ),
					'param_name'	=> 'title',
					'value'			=> array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex' )	=> 'yes'
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Title Based On Image', 'wpex' ),
					'param_name'	=> 'title_type',
					'value'			=> array(
						__( 'Title', 'wpex' )		=> 'title',
						__( 'Alt', 'wpex' )			=> 'alt',
						__( 'Caption', 'wpex' )		=> 'caption',
						__( 'Description', 'wpex' )	=> 'description',
					),
					'group'			=> __( 'Design', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'title',
						'value'		=> array( 'yes' )
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Link', 'wpex' ),
					'param_name'	=> 'thumbnail_link',
					'value'			=> array(
						__( 'None', 'wpex' )			=> 'none',
						__( 'Lightbox', 'wpex' )		=> 'lightbox',
						__( 'Attachment Page', 'wpex' )	=> 'attachment_page',
						__( 'Custom Links', 'wpex' )	=> 'custom_link',
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Lightbox Title', 'wpex' ),
					'param_name'	=> 'lightbox_title',
					'value'			=> array(
						__( 'Alt', 'wpex' )		=> '',
						__( 'Title', 'wpex' )	=> 'title',
						__( 'None', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Links', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'thumbnail_link',
						'value'		=> array( 'lightbox' ),
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Lightbox Caption', 'wpex' ),
					'param_name'	=> 'lightbox_caption',
					'value'			=> array(
						__( 'Enable', 'wpex' )		=> 'true',
						__( 'Disable', 'wpex' )		=> 'false',
					),
					'group'			=> __( 'Links', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'thumbnail_link',
						'value'		=> array( 'lightbox' ),
					),
				),
				array(
					'type'			=> 'exploded_textarea',
					'heading'		=> __( 'Custom links', 'wpex' ),
					'param_name'	=> 'custom_links',
					'description'	=> __( 'Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol. And don\'t forget to include the http:// at the front.', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'thumbnail_link',
						'value'		=> array( 'custom_link' )
					),
					'group'			=> __( 'Links', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Link Target', 'wpex' ),
					'param_name'	=> 'custom_links_target',
					'group'			=> __( 'Links', 'wpex' ),
					'value'			=> array(
						__( 'Same window', 'wpex' )	=> '_self',
						__( 'New window', 'wpex' )	=> '_blank'
					),
					'dependency'	=> Array(
						'element'	=> 'thumbnail_link',
						'value'		=> array( 'custom_link', 'attachment_page' ),
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Image Crop Width', 'wpex' ),
					'param_name'	=> 'img_width',
					'value'			=> '9999',
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Image Crop Height', 'wpex' ),
					'param_name'	=> 'img_height',
					'value'			=> '9999',
					'description'	=> __( 'Enter a height in pixels. Set to "9999" to disable vertical cropping and keep image proportions.', 'wpex' ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Rounded Image?', 'wpex' ),
					'param_name'	=> 'rounded_image',
					'value'			=> array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex' )	=> 'yes'
					),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Filter', 'wpex' ),
					'param_name'	=> 'img_filter',
					'value'			=> vcex_image_filters(),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Rendering', 'wpex' ),
					'param_name'	=> 'img_rendering',
					'value'			=> vcex_image_rendering(),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'CSS3 Image Hover', 'wpex' ),
					'param_name'	=> 'img_hover_style',
					'value'			=> vcex_image_hovers(),
					'description'	=> __('Select your preferred image hover effect. Please note this will only work if the image links to a URL or a large version of itself. Please note these effects may not work in all browsers.', 'wpex' ),
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_image_grid_shortcode_vc_map' );