<?php
/**
 * Registers the staff carousel shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( !function_exists( 'vcex_staff_carousel_shortcode' ) ) {
	function vcex_staff_carousel_shortcode($atts) {
		
		extract( shortcode_atts( array(
			'unique_id'					=> '',
			'classes'					=> '',
			'style'						=> 'default',
			'term_slug'					=> '',
			'include_categories'		=> '',
			'exclude_categories'		=> '',
			'count'						=> '8',
			'center'					=> 'false',
			'timeout_duration'			=> '5000',
			'items'						=> '4',
			'items_margin'				=> '15',
			'infinite_loop'				=> 'true',
			'items_scroll'				=> '1',
			'auto_play'					=> 'false',
			'arrows'					=> 'true',
			'order'						=> 'DESC',
			'orderby'					=> 'date',
			'orderby_meta_key'			=> '',
			'thumbnail_link'			=> '',
			'img_width'					=> '9999',
			'img_height'				=> '9999',
			'title'						=> 'true',
			'excerpt'					=> 'true',
			'excerpt_length'			=> '30',
			'custom_excerpt_trim'		=> 'true',
			'social'					=> '',
			'taxonomy'					=> '',
			'terms'						=> '',
			'img_hover_style'			=> '',
			'img_rendering'				=> '',
			'overlay_style'				=> '',
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

		// Global post
		global $post;
		
		// Include categories
		$include_categories = ( '' != $include_categories ) ? $include_categories : $term_slug;
		$include_categories = ( 'all' == $include_categories ) ? '' : $include_categories;
		$filter_cats_include = '';
		if ( $include_categories ) {
			$include_categories = explode( ',', $include_categories );
			$filter_cats_include = array();
			foreach ( $include_categories as $key ) {
				$key = get_term_by( 'slug', $key, 'staff_category' );
				$filter_cats_include[] = $key->term_id;
			}
		}

		// Exclude categories
		$filter_cats_exclude = '';
		if ( $exclude_categories ) {
			$exclude_categories = explode( ',', $exclude_categories );
			if( ! empty( $exclude_categories ) && is_array( $exclude_categories ) ) {
			$filter_cats_exclude = array();
			foreach ( $exclude_categories as $key ) {
				$key = get_term_by( 'slug', $key, 'staff_category' );
				$filter_cats_exclude[] = $key->term_id;
			}
			$exclude_categories = array(
					'taxonomy'	=> 'staff_category',
					'field'		=> 'slug',
					'terms'		=> $exclude_categories,
					'operator'	=> 'NOT IN',
				);
			} else {
				$exclude_categories = '';
			}
		}
		
		// Start Tax Query
		if( ! empty( $include_categories ) && is_array( $include_categories ) ) {
			$include_categories = array(
				'taxonomy'	=> 'staff_category',
				'field'		=> 'slug',
				'terms'		=> $include_categories,
				'operator'	=> 'IN',
			);
		} else {
			$include_categories = '';
		}

		// Meta key for orderby
		if( $orderby_meta_key && ( 'meta_value_num' == $orderby || 'meta_value' == $orderby ) ) {
			$meta_key = $orderby_meta_key;
		} else {
			$meta_key = NULL;
		}
		
		// The Query
		$wpex_query = new WP_Query(
			array(
				'post_type' 		=> 'staff',
				'posts_per_page'	=> $count,
				'order'				=> $order,
				'orderby'			=> $orderby,
				'no_found_rows'		=> true,
				'meta_key'			=> $meta_key,
				'tax_query'			=> array(
					'relation'		=> 'AND',
					$include_categories,
					$exclude_categories,
				),
			)
		);

		//Output posts
		if( $wpex_query->posts ) :

			// Output js for front-end editor
			vcex_front_end_carousel_js();
			
			// Give caroufredsel a unique name
			$rand_num = rand( 1, 100 );
			$unique_carousel_id = 'carousel-'. $rand_num;

			// Prevent auto play in visual composer
			if ( wpex_is_front_end_composer() ) {
				$auto_play = 'false';
			}

			// Overlay Style
			if ( empty( $overlay_style ) ) {
				$overlay_style = 'none';
			} else {
				$overlay_style = $overlay_style;
			}
		
			// Item Margin
			if( 'no-margins' == $style ) {
				$items_margin = '0';
			}

			// Items to scroll fallback for old setting
			if( 'page' == $items_scroll ) {
				$items_scroll = $items;
			}
			
			// Unique ID
			if( $unique_id ) {
				$unique_id = ' id="'. $unique_id .'"';
			}

			// Main Classes
			$main_classes = 'wpex-carousel wpex-carousel-staff clr owl-carousel clr';
			if ( $style ) {
				$main_classes .= ' wpex-carousel-'. $style;
			}
			if( $classes ) {
				$main_classes .= ' '. $classes;
			}

			// Entry media classes
			$media_classes = 'wpex-carousel-entry-media clr';
			if( $img_hover_style ) {
				$media_classes .= ' vcex-img-hover-parent vcex-img-hover-'. $img_hover_style;
			}
			if( $img_rendering ) {
				$media_classes .= ' vcex-image-rendering-'. $img_rendering;
			}
			if( $overlay_style ) {
				$media_classes .= ' '. wpex_overlay_classname( $overlay_style );
			}

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
			}

			// Title design
			$heading_style = '';
			if ( $content_heading_margin ) {
				$heading_style .='margin: '. $content_heading_margin .';';
			}
			if( $content_heading_transform ) {
				$heading_style .='text-transform: '. $content_heading_transform .';';
			}
			if ( $content_heading_weight ) {
				$heading_style .='font-weight: '. $content_heading_weight .';';
			}
			if ( $content_heading_size ) {
				$heading_style .='font-size: '. $content_heading_size .';';
			}
			if ( $heading_style ) {
				$heading_style = ' style="'. $heading_style .'"';
			}

			// Heading color
			if ( $content_heading_color ) {
				$content_heading_color =' style="color: '. $content_heading_color .';"';
			} ?>

			<div class="<?php echo $main_classes; ?>"<?php echo $unique_id; ?> data-items="<?php echo $items; ?>" data-slideby="<?php echo $items_scroll; ?>" data-nav="<?php echo $arrows; ?>" data-autoplay="<?php echo $auto_play; ?>" data-loop="<?php echo $infinite_loop; ?>" data-autoplay-timeout="<?php echo $timeout_duration ?>" data-center="<?php echo $center; ?>" data-margin="<?php echo intval( $items_margin ); ?>" data-items-tablet="<?php echo $tablet_items; ?>" data-items-mobile-landscape="<?php echo $mobile_landscape_items; ?>" data-items-mobile-portrait="<?php echo $mobile_portrait_items; ?>">
				<?php
				// Loop through posts
				foreach ( $wpex_query->posts as $post ) : setup_postdata( $post );
				
					// Post VARS
					$postid = $post->ID;
					$featured_img_url = wp_get_attachment_url( get_post_thumbnail_id( $postid ) );
					$permalink = get_permalink( $postid );
					$post_title = esc_attr( the_title_attribute( 'echo=0' ) );
					
					// Crop featured images if necessary
					if( '9999' == $img_height ) {
						$img_crop = false;
					} else {
						$img_crop = true;
					}
					$featured_img = wpex_image_resize( $featured_img_url, $img_width, $img_height, $img_crop, 'array' ); ?>
		
					<div class="wpex-carousel-slide">
						<?php
						// Media Wrap
						if( has_post_thumbnail() ) { ?>
							<div class="<?php echo $media_classes; ?>">
								<?php
								// No links
								if ( 'none' == $thumbnail_link) { ?>
									<img src="<?php echo $featured_img['url']; ?>" alt="<?php echo $post_title; ?>" width="<?php echo $featured_img['width']; ?>" height="<?php echo $featured_img['height']; ?>" />
								<?php }
								// Lightbox
								elseif ( 'lightbox' == $thumbnail_link ) { ?>
									<a href="<?php echo $featured_img_url; ?>" title="<?php echo $post_title; ?>" class="wpex-carousel-entry-img wpex-lightbox">
										<img src="<?php echo $featured_img['url']; ?>" alt="<?php echo $post_title; ?>" width="<?php echo $featured_img['width']; ?>" height="<?php echo $featured_img['height']; ?>" />
								<?php }
								// Link to post
								else { ?>
									<a href="<?php echo $permalink; ?>" title="<?php echo $post_title; ?>" class="wpex-carousel-entry-img">
										<img src="<?php echo $featured_img['url']; ?>" alt="<?php echo $post_title; ?>" width="<?php echo $featured_img['width']; ?>" height="<?php echo $featured_img['height']; ?>" />
								<?php } ?>
								<?php
								// Overlay & close link
								if ( 'none' != $thumbnail_link ) {
									// Inner Overlay
									if( $overlay_style ) {
										wpex_overlay( 'inside_link', $overlay_style );
									}
									// Close link
									echo '</a><!-- .wpex-carousel-entry-img -->';
									// Outside Overlay
									if( $overlay_style ) {
										wpex_overlay( 'outside_link', $overlay_style );
									}
								} ?>
							</div><!-- .wpex-carousel-entry-media -->
						<?php } ?>

						<?php
						// Title
						if ( 'true' == $title || 'true' == $excerpt || 'true' == $social ) { ?>
							<div class="wpex-carousel-entry-details clr"<?php echo $content_style; ?>>
								<?php
								// Title
								if ( 'true' == $title && $post_title ) { ?>
									<div class="wpex-carousel-entry-title"<?php echo $heading_style; ?>>
										<a href="<?php echo $permalink; ?>" title="<?php echo $post_title; ?>"<?php echo $content_heading_color; ?>><?php echo $post_title; ?></a>
									</div>
								<?php }
								// Excerpt
								if ( 'true' == $excerpt ) {
									if( 'true' == $custom_excerpt_trim ) {
										$custom_excerpt_trim = true;
									} else {
										$custom_excerpt_trim = false;
									}
									$excerpt_array = array (
										'length'				=> intval( $excerpt_length ),
										'readmore'				=> false,
										'trim_custom_excerpts'	=> $custom_excerpt_trim
									);
									// Generate excerpt
									$get_excerpt = vcex_get_excerpt( $excerpt_array );
									if( $get_excerpt ) { ?>
										<div class="wpex-carousel-entry-excerpt clr">
											<?php echo $get_excerpt; ?>
										</div><!-- .wpex-carousel-entry-excerpt -->
									<?php } ?>
								<?php }
								// Display social links
								if ( 'true' == $social ) {
									echo wpex_get_staff_social( get_the_ID() );
								} ?>
							</div><!-- .wpex-carousel-entry-details -->
						<?php } ?>
					</div><!-- .wpex-carousel-slide -->
				<?php
				// End foreach loop
				endforeach; ?>
			</div><!-- .wpex-carousel -->
	
		<?php
		endif; // End has posts check

		// Set things back to normal
		wp_reset_postdata();

		// Return outbut buffer
		return ob_get_clean();
		
	}
}
add_shortcode( 'vcex_staff_carousel', 'vcex_staff_carousel_shortcode' );

if ( ! function_exists( 'vcex_staff_carousel_shortcode_vc_map' ) ) {
	function vcex_staff_carousel_shortcode_vc_map() {
		vc_map( array(
			'name'					=> __( 'Staff Carousel', 'wpex' ),
			'description'			=> __( 'Recent staff posts carousel.', 'wpex' ),
			'base'					=> 'vcex_staff_carousel',
			'category'				=> WPEX_THEME_BRANDING,
			'icon' 					=> 'vcex-staff-carousel',
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Unique Id', 'wpex' ),
					'param_name'	=> 'unique_id',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Custom Classes', 'wpex' ),
					'param_name'	=> 'classes',
					'value'			=> '',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Items To Display', 'wpex' ),
					'param_name'	=> 'items',
					'value'			=> '4',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Items To Scrollby', 'wpex' ),
					'param_name'	=> 'items_scroll',
					'value'			=> '1',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Margin Between Items', 'wpex' ),
					'param_name'	=> 'items_margin',
					'value'			=> '15',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Auto Play', 'wpex' ),
					'param_name'	=> 'auto_play',
					'value'			=> array(
						__( 'True', 'wpex' )	=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Infinite Loop', 'wpex' ),
					'param_name'	=> 'infinite_loop',
					'value'			=> array(
						__( 'True', 'wpex' )	=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Center Item', 'wpex' ),
					'param_name'	=> 'center',
					'value'			=> array(
						__( 'False', 'wpex' )	=> 'false',
						__( 'True', 'wpex' )	=> 'true',
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Timeout Duration (in milliseconds)', 'wpex' ),
					'param_name'	=> 'timeout_duration',
					'value'			=> '5000',
					'dependency'	=> Array(
						'element'	=> 'auto_play',
						'value'		=> 'true'
					),
				),
				

				// Query
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Include Categories', 'wpex' ),
					'param_name'	=> 'include_categories',
					'admin_label'	=> true,
					'description'	=> __( 'Enter the slugs of a categories (comma seperated) to pull posts from or enter "all" to pull recent posts from all categories. Example: category-1, category-2.', 'wpex' ),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Exclude Categories', 'wpex' ),
					'param_name'	=> 'exclude_categories',
					'admin_label'	=> true,
					'description'	=> __('Enter the slugs of a categories (comma seperated) to exclude. Example: category-1, category-2.','wpex'),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Post Count', 'wpex' ),
					'param_name'	=> 'count',
					'value'			=> '8',
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Order', 'wpex' ),
					'param_name'	=> 'order',
					'value'			=> array(
						__( 'DESC', 'wpex')	=> 'DESC',
						__( 'ASC', 'wpex' )	=> 'ASC',
					),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Order By', 'wpex' ),
					'param_name'	=> 'orderby',
					'value'			=> array(
						__( 'Date', 'wpex')					=> 'date',
						__( 'Name', 'wpex' )				=> 'name',
						__( 'Modified', 'wpex')				=> 'modified',
						__( 'Author', 'wpex' )				=> 'author',
						__( 'Random', 'wpex')				=> 'rand',
						__( 'Comment Count', 'wpex' )		=> 'comment_count',
						__( 'Meta Key Value', 'wpex' )		=> 'meta_value',
						__( 'Meta Key Value Num', 'wpex' )	=> 'meta_value_num',
					),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Orderby: Meta Key', 'wpex' ),
					'param_name'	=> 'orderby_meta_key',
					'group'			=> __( 'Query', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'orderby',
						'value'		=> array( 'meta_value_num', 'meta_value' ),
					),
				),

				// Design
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Style', 'wpex' ),
					'param_name'	=> 'style',
					'value'			=> array(
						__( 'Default', 'wpex')		=> 'default',
						__( 'No Margins', 'wpex' )	=> 'no-margins',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Arrows?', 'wpex' ),
					'param_name'	=> 'arrows',
					'value'			=> array(
						__( 'True', 'wpex' )	=> 'true',
						__( 'False', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Title Font size', 'wpex' ),
					'param_name'	=> 'content_heading_size',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Title Margin', 'wpex' ),
					'param_name'	=> 'content_heading_margin',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Title Font Weight', 'wpex' ),
					'param_name'	=> 'content_heading_weight',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Title Text Transform', 'wpex' ),
					'param_name'	=> 'content_heading_transform',
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
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Title Text Color', 'wpex' ),
					'param_name'	=> 'content_heading_color',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Content Background', 'wpex' ),
					'param_name'	=> 'content_background',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Content Text Color', 'wpex' ),
					'param_name'	=> 'content_color',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Content Alignment', 'wpex' ),
					'param_name'	=> 'content_alignment',
					'value'			=> array(
						__( 'Default', 'wpex' )	=> '',
						__( 'Left', 'wpex' )	=> 'left',
						__( 'Right', 'wpex' )	=> 'right',
						__( 'Center', 'wpex')	=> 'center',
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Content Font Size', 'wpex' ),
					'param_name'	=> 'content_font_size',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Content Margin', 'wpex' ),
					'param_name'	=> 'content_margin',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Content Padding', 'wpex' ),
					'param_name'	=> 'content_padding',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Content Opacity', 'wpex' ),
					'param_name'	=> 'content_opacity',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Content Border', 'wpex' ),
					'param_name'	=> 'content_border',
					'group'			=> __( 'Design', 'wpex' ),
				),
				
				// Image
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Links To', 'wpex' ),
					'param_name'	=> 'thumbnail_link',
					'value'			=> array(
						__( 'Default', 'wpex')		=> '',
						__( 'Post', 'wpex')			=> 'post',
						__( 'Lightbox', 'wpex' )	=> 'lightbox',
						__( 'None', 'wpex' )		=> 'none',
					),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Image Width', 'wpex' ),
					'param_name'	=> 'img_width',
					'value'			=> '9999',
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Image Height', 'wpex' ),
					'param_name'	=> 'img_height',
					'value'			=> '9999',
					'description'	=> __( 'Enter a height in pixels. Set to "9999" to disable vertical cropping and keep image proportions.', 'wpex' ),
					'group'			=> __( 'Image', 'wpex' ),
				),
				vcex_overlays_array(),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'CSS3 Image Hover', 'wpex' ),
					'param_name'	=> 'img_hover_style',
					'value'			=> vcex_image_hovers(),
					'description'	=> __( 'Select your preferred image hover effect. Please note this will only work if the image links to a URL or a large version of itself. Please note these effects may not work in all browsers.', 'wpex' ),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Rendering', 'wpex' ),
					'param_name'	=> 'img_rendering',
					'value'			=> vcex_image_rendering(),
					'description'	=> sprintf( __( 'Image-rendering CSS property provides a hint to the user agent about how to handle its image rendering. For example when scaling down images they tend to look a bit fuzzy in Firefox, setting image-rendering to crisp-edges can help make the images look better. <a href="%s">Learn more</a>.', 'wpex' ), 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering' ),
					'group'			=> __( 'Image', 'wpex' ),
				),

				// Content
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Title', 'wpex' ),
					'param_name'	=> 'title',
					'value'			=> array(
						__( 'Yes', 'wpex')	=> 'true',
						__( 'No', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Excerpt', 'wpex' ),
					'param_name'	=> 'excerpt',
					'value'			=> array(
						__( 'Yes', 'wpex')	=> 'true',
						__( 'No', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Excerpt Length', 'wpex' ),
					'param_name'	=> 'excerpt_length',
					'value'			=> '30',
					'dependency'	=> Array(
						'element'	=> 'excerpt',
						'value'		=> 'true'
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Trim Custom Excerpts', 'wpex' ),
					'param_name'	=> 'custom_excerpt_trim',
					'value'			=> array(
						__( 'Yes', 'wpex' )	=> 'true',
						__( 'No', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Content', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'excerpt',
						'value'		=> array( 'true' ),
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Social Links', 'wpex' ),
					'param_name'	=> 'social',
					'value'			=> array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex')	=> 'true',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),

				// Responsive Settings
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Tablet (768px-659px): Items To Display', 'wpex' ),
					'param_name'	=> 'tablet_items',
					'value'			=> '3',
					'group'			=> __( 'Responsive', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Mobile Landscape (480px-767px): Items To Display', 'wpex' ),
					'param_name'	=> 'mobile_landscape_items',
					'value'			=> '2',
					'group'			=> __( 'Responsive', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Mobile Portrait (0-479px): Items To Display', 'wpex' ),
					'param_name'	=> 'mobile_portrait_items',
					'value'			=> '1',
					'group'			=> __( 'Responsive', 'wpex' ),
				),
			),
		) );
	}
}
add_action( 'vc_before_init', 'vcex_staff_carousel_shortcode_vc_map' );