<?php
/**
 * Registers the post type slider shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( ! function_exists( 'vcex_post_type_flexslider_shortcode' ) ) {
	function vcex_post_type_flexslider_shortcode( $atts ) {

		extract( shortcode_atts( array(
			'unique_id'				=> '',
			'post_types'			=> 'post',
			'tax_query'				=> '',
			'tax_query_taxonomy'	=> '',
			'tax_query_terms'		=> '',
			'posts_per_page'		=> '4',
			'order'					=> 'DESC',
			'orderby'				=> 'date',
			'filter_content'		=> 'false',
			'offset'				=> 0,
			'animation'				=> 'slide',
			'slideshow'				=> 'true',
			'randomize'				=> 'false',
			'direction'				=> 'horizontal',
			'slideshow_speed'		=> '7000',
			'animation_speed'		=> '600',
			'control_nav'			=> 'true',
			'direction_nav'			=> 'true',
			'pause_on_hover'		=> 'true',
			'smooth_height'			=> 'true',
			'img_width'				=> '9999',
			'img_height'			=> '9999',
			'caption'				=> true,
			'caption_location'		=> 'over-slider',
			'control_thumbs'		=> 'false',
			'title'					=> 'true',
			'excerpt'				=> 'true',
			'excerpt_length'		=> '40',
		), $atts ) );

		// Turn output buffer on
		ob_start();

			// Get global $post var
			global $post;

			// Post types
			$post_types = $post_types ? $post_types : 'post';
			$post_types = explode( ',', $post_types );

			// Tax Query
			if( '' != $tax_query && '' != $tax_query_taxonomy && '' != $tax_query_terms ) {
				$tax_query_terms = explode( ',', $tax_query_terms);
				$tax_query = array(
					array(
						'taxonomy'	=> $tax_query_taxonomy,
						'field'		=> 'slug',
						'terms'		=> $tax_query_terms,
					),
				);
			} else {
				$tax_query = '';
			}

			// Build new query
			$vcex_query = new WP_Query( array(
				'post_type'			=> $post_types,
				'posts_per_page'	=> $posts_per_page,
				'offset'			=> $offset,
				'order'				=> $order,
				'orderby'			=> $orderby,
				'filter_content'	=> $filter_content,
				'meta_query'		=> array( array (
					'key'	=> '_thumbnail_id'
				) ),
				'no_found_rows'		=> true,
				'tax_query'			=> $tax_query,
			) );

			//Output posts
			if( $vcex_query->posts ) :

				// Output script for inline JS for the Visual composer front-end builder
				if ( function_exists( 'vcex_front_end_slider_js' ) ) {
					vcex_front_end_slider_js();
				}

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

				// Main Vars
				$unique_id = $unique_id ? ' id="'. $unique_id .'"' : NULL;
				$img_crop = $img_height == '9999' ? false : true; ?>

				<div class="vcex-flexslider-wrap clr vcex-img-flexslider vcex-posttypes-flexslider"<?php echo $unique_id; ?>>
					<div class="vcex-flexslider flexslider" <?php echo $flexslider_data; ?>>
						<ul class="slides">
							<?php
							// Loop through posts
							foreach ( $vcex_query->posts as $post ) : setup_postdata( $post );
								$img_url = wp_get_attachment_url( get_post_thumbnail_id() );
								// Thumb Data attr
								if ( 'true' == $control_thumbs ) {
									$data_thumb = 'data-thumb="'. wpex_image_resize( $img_url, 100, 100, true ) .'"';
								} else {
									$data_thumb = '';
								} ?>
								<li class="vcex-flexslider-slide slide" <?php echo $data_thumb; ?>>
									<div class="vcex-flexslider-entry-media">
										<?php if ( has_post_thumbnail() ) {
											$cropped_img = wpex_image_resize( $img_url, intval( $img_width ), intval( $img_height ), $img_crop, 'array' );
											if ( $cropped_img && is_array( $cropped_img ) ) { ?>
												<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
													<img src="<?php echo $cropped_img['url']; ?>" alt="<?php the_title(); ?>" class="vcex-post-type-entry-img" height="<?php echo $cropped_img['height']; ?>" width="<?php echo $cropped_img['width']; ?>" />
												</a>
											<?php } ?>
										<?php }
										// WooComerce Price
										if ( class_exists( 'Woocommerce' ) && 'product' == get_post_type() ) { ?>
											<div class="slider-woocommerce-price">
												<?php
												$product = get_product( get_the_ID() );
												echo $product->get_price_html(); ?>
											</div><!-- .slider-woocommerce-price -->
										<?php } ?>
										<?php if ( 'true' == $caption ) { ?>
											<div class="vcex-img-flexslider-caption clr <?php echo $caption_location; ?>">
												<?php
												// Display title
												if ( 'true' == $title ) { ?>
													<div class="title clr">
														<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
														<?php if ( 'staff' == get_post_type() && get_post_meta( get_the_ID(), 'wpex_staff_position', true ) ) { ?>
															<div class="staff-position">
																<?php echo get_post_meta( get_the_ID(), 'wpex_staff_position', true ); ?>
															</div>
														<?php } ?>
													</div>
												<?php }
												// Display excerpt
												if ( 'true' == $excerpt ) { ?>
													<div class="excerpt clr">
														<?php
														$excerpt_array = array (
															'length'	=> intval( $excerpt_length )
														);
														vcex_excerpt( $excerpt_array ); ?>
													</div><!-- .excerpt -->
												<?php } ?>
											</div><!-- .vcex-img-flexslider-caption -->
										<?php } ?>
									</div><!-- .vcex-flexslider-entry-media -->
								</li><!-- .vcex-posttypes-slide -->
							<?php endforeach; ?>
						</ul><!-- .slides -->
					</div><!-- .flexslider -->
				</div><!-- .vcex-posttypes-flexslider -->
				<!-- Be safe and clear the floats -->
				<div class="vcex-clear-floats"></div>

			<?php
			// End has posts check
			endif;

			// Reset the WP query
			wp_reset_postdata();

		// Return outbut buffer
		return ob_get_clean();

	}
}
add_shortcode( 'vcex_post_type_flexslider', 'vcex_post_type_flexslider_shortcode' );

if ( ! function_exists( 'vcex_post_type_flexslider_vc_map' ) ) {
	function vcex_post_type_flexslider_vc_map() {
		vc_map( array(
			'name'					=> __( "Post Types Slider", 'wpex' ),
			'description'			=> __( "Recent posts slider.", 'wpex' ),
			'base'					=> "vcex_post_type_flexslider",
			'category'				=> WPEX_THEME_BRANDING,
			'icon' 					=> "vcex-post-type-slider",
			'params'				=> array(

				// General
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Unique Id", 'wpex' ),
					"param_name"	=> "unique_id",
					"value"			=> "",
					'description'	=> __( "You can enter a unique ID here for styling purposes.", 'wpex' ),
				),

				// Query
				array(
					'type'			=> 'posttypes',
					'heading'		=> __( 'Post types', 'wpex' ),
					'param_name'	=> 'post_types',
					'description' 	=> __( 'Select post types to populate posts from.', 'wpex' ),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Filter by Taxonomy", 'wpex' ),
					'param_name'	=> "tax_query",
					'value'			=> array(
						__( "No", "wpex" )	=> '',
						__( "Yes", "wpex")	=> "true",
					),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Taxonomy Name', 'wpex' ),
					'param_name'	=> "tax_query_taxonomy",
					'dependency'	=> array(
						'element'	=> 'tax_query',
						'value'		=> 'true',
					),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> "exploded_textarea",
					'heading'		=> __( 'Terms', 'wpex' ),
					'param_name'	=> "tax_query_terms",
					'dependency'	=> array(
						'element'	=> 'tax_query',
						'value'		=> 'true',
					),
					'group'			=> __( 'Query', 'wpex' ),
					'description'	=> __( 'Enter the slugs of the terms to include. Divide terms with linebreaks (Enter).', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Order", 'wpex' ),
					"param_name"	=> "order",
					"value"			=> array(
						__( "DESC", "wpex")	=> "DESC",
						__( "ASC", "wpex" )	=> "ASC",
					),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Order By", 'wpex' ),
					"param_name"	=> "orderby",
					"value"			=> array(
						__( "Date", "wpex")				=> "date",
						__( "Name", "wpex" )			=> 'name',
						__( "Modified", "wpex")			=> "modified",
						__( "Author", "wpex" )			=> "author",
						__( "Random", "wpex")			=> "rand",
						__( "Comment Count", "wpex" )	=> "comment_count",
					),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Posts Count", 'wpex' ),
					"param_name"	=> "posts_per_page",
					"value"			=> "4",
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Animation", 'wpex' ),
					"param_name"	=> "animation",
					"value"			=> array(
						__( "Slide", "wpex")	=> "slide",
						__( "Fade", "wpex" )	=> "fade",
					),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Slideshow", 'wpex' ),
					"param_name"	=> "slideshow",
					"value"			=> array(
						__( "True", "wpex")		=> "true",
						__( "False", "wpex" )	=> "false",
					),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Randomize", 'wpex' ),
					"param_name"	=> "randomize",
					"value"			=> array(
						__( "False", "wpex" )	=> "false",
						__( "True", "wpex")		=> "true",
					),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Control Nav", 'wpex' ),
					"param_name"	=> "control_nav",
					"value"			=> array(
						__( "True", "wpex")		=> "true",
						__( "False", "wpex" )	=> "false",
					),
				),
				array(
					'type'			=> 'dropdown',
					'admin_label'	=> true,
					'class'			=> '',
					'heading'		=> __( 'Navigation Thumbnails', 'wpex' ),
					'param_name'	=> 'control_thumbs',
					'value'			=> array(
						__( 'No', 'wpex' )	=> 'false',
						__( 'Yes', 'wpex' )	=> 'true',
					),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Direction Nav", 'wpex' ),
					"param_name"	=> "direction_nav",
					"value"			=> array(
						__( "True", "wpex")		=> "true",
						__( "False", "wpex" )	=> "false",
					),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Slideshow Speed", 'wpex' ),
					"param_name"	=> "slideshow_speed",
					"value"			=> "7000",
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Animation Speed", 'wpex' ),
					"param_name"	=> "animation_speed",
					"value"			=> "600",
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Image Width", 'wpex' ),
					"param_name"	=> "img_width",
					"value"			=> "9999",
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Image Height", 'wpex' ),
					"param_name"	=> "img_height",
					"value"			=> "9999",
					'group'			=> __( 'Image Settings', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Caption", 'wpex' ),
					"param_name"	=> "caption",
					"value"			=> array(
						__( "Yes", "wpex")		=> "true",
						__( "No", "wpex" )	=> "false",
					),
					'group'			=> __( 'Caption', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Caption Location", 'wpex' ),
					"param_name"	=> "caption_location",
					"value"			=> array(
						__( "Over Image", "wpex")	=> "over-image",
						__( "Under Image", "wpex" )	=> "under-image",
					),
					'group'			=> __( 'Caption', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Title", 'wpex' ),
					"param_name"	=> "title",
					"value"			=> array(
						__( "Yes", "wpex")	=> "true",
						__( "No", "wpex" )	=> "false",
					),
					'group'			=> __( 'Caption', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Excerpt", 'wpex' ),
					"param_name"	=> "excerpt",
					"value"			=> array(
						__( "Yes", "wpex")		=> "true",
						__( "No", "wpex" )	=> "false",
					),
					'group'			=> __( 'Caption', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Excerpt Length", 'wpex' ),
					"param_name"	=> "excerpt_length",
					"value"			=> "40",
					'group'			=> __( 'Caption', 'wpex' ),
				),
			),
			
		) );
	}
}
add_action( 'vc_before_init', 'vcex_post_type_flexslider_vc_map' );