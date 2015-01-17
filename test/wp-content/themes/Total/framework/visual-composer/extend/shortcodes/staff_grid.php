<?php
/**
 * Registers the bullets shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( ! function_exists( 'vcex_staff_grid_shortcode' ) ) {
	function vcex_staff_grid_shortcode( $atts ) {	
		extract( shortcode_atts( array(
			'unique_id'					=> '',
			'term_slug'					=> '',
			'include_categories'		=> '',
			'exclude_categories'		=> '',
			'posts_per_page'			=> '12',
			'grid_style'				=> 'fit_columns',
			'masonry_layout_mode'		=> '',
			'filter_speed'				=> '',
			'masonry_layout_mode'		=> '',
			'equal_heights_grid'		=> '',
			'columns'					=> '',
			'order'						=> 'DESC',
			'orderby'					=> 'date',
			'orderby_meta_key'			=> '',
			'filter'					=> '',
			'center_filter'				=> '',
			'img_crop'					=> 'true',
			'img_width'					=> '9999',
			'img_height'				=> '9999',
			'thumb_link'				=> 'post',
			'thumb_lightbox_gallery'	=> '',
			'thumb_lightbox_title'		=> '',
			'img_filter'				=> '',
			'title'						=> 'true',
			'title_link'				=> 'post',
			'excerpt'					=> 'true',
			'excerpt_length'			=> '15',
			'custom_excerpt_trim'		=> '',
			'read_more'					=> '',
			'read_more_text'			=> __( 'read more', 'wpex' ),
			'pagination'				=> 'false',
			'filter_content'			=> 'false',
			'social_links'				=> 'true',
			'offset'					=> 0,
			'taxonomy'					=> '',
			'terms'						=> '',
			'img_hover_style'			=> '',
			'img_rendering'				=> '',
			'all_text'					=> __( 'All', 'wpex' ),
			'overlay_style'				=> '',
			'content_heading_margin'	=> '',
			'content_background'		=> '',
			'content_margin'			=> '',
			'content_font_size'			=> '',
			'content_padding'			=> '',
			'content_border'			=> '',
			'content_color'				=> '',
			'content_opacity'			=> '',
			'content_heading_color'		=> '',
			'content_heading_size'		=> '',
			'content_alignment'			=> '',
			'readmore_background'		=> '',
			'readmore_color'			=> '',
			'position'					=> '',
			'position_size'				=> '',
			'position_margin'			=> '',
			'position_color'			=> '',
			'single_column_style'		=> '',
		), $atts ) );
		
		// Turn output buffer on
		ob_start();

		// Don't create custom tax if tax doesn't exist
		if ( taxonomy_exists( 'staff_category' ) ) {
			
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
				if ( ! empty( $exclude_categories ) && is_array( $exclude_categories ) ) {
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
			if ( ! empty( $include_categories ) && is_array( $include_categories ) ) {
				$include_categories = array(
					'taxonomy'	=> 'staff_category',
					'field'		=> 'slug',
					'terms'		=> $include_categories,
					'operator'	=> 'IN',
				);
			} else {
				$include_categories = '';
			}

		}

		// Meta key for orderby
		if ( $orderby_meta_key && ( 'meta_value_num' == $orderby || 'meta_value' == $orderby ) ) {
			$meta_key = $orderby_meta_key;
		} else {
			$meta_key = NULL;
		}

		// Pagination var
		$paged			= NULL;
		$no_found_rows	= true;
		if ( 'true' == $pagination ) {
			global $paged;
			$paged			= get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$no_found_rows	= false;
		}
		
		// The Query
		global $post;
		$wpex_query	= NULL;
		$wpex_query	= new WP_Query (
			array(
				'post_type'			=> 'staff',
				'posts_per_page'	=> $posts_per_page,
				'offset'			=> $offset,
				'order'				=> $order,
				'orderby'			=> $orderby,
				'filter_content'	=> $filter_content,
				'paged'				=> $paged,
				'meta_key'			=> $meta_key,
				'tax_query'			=> array(
					'relation'		=> 'AND',
					$include_categories,
					$exclude_categories,
				),
				'no_found_rows'		=> $no_found_rows,
			)
		);

		// Output posts
		if ( $wpex_query->posts ) :
		
			// Main Vars
			$unique_id = $unique_id ? $unique_id : 'staff-'. rand( 1, 100 );
			
			// Image hard crop
			if ( '9999' == $img_height ) {
				$img_crop = false;
			} else {
				$img_crop = true;
			}

			// Equal heights class
			if ( '1' != $columns && ( 'fit_columns' == $grid_style && 'true' == $equal_heights_grid ) ) {
				$equal_heights_grid = true;
			} else {
				$equal_heights_grid = false;
			}

			// Is Isotope var
			if ( 'true' == $filter  || 'masonry' == $grid_style ) {
				 $is_isotope = true;
			} else {
				 $is_isotope = false;
			}

			// No need for masonry if not enough columns and filter is disabled
			if ( 'true' != $filter && 'masonry' == $grid_style ) {
				$post_count = count( $wpex_query->posts );
				if ( $post_count <= $columns ) {
					 $is_isotope = false;
				}
			}

			// Output script for inline JS for the Visual composer front-end builder
			if (  $is_isotope ) {
				vcex_front_end_grid_js( 'isotope' );
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
				$content_style = 'style="'. $content_style .'"';
			}

			// Heading Design
			$heading_style = '';
			if ( $content_heading_margin ) {
				$heading_style .='margin:'. $content_heading_margin .';';
			}
			if ( $content_heading_size ) {
				$heading_style .='font-size:'. $content_heading_size .';';
			}
			if ( $content_heading_color ) {
				$heading_style .='color:'. $content_heading_color .';';
			}
			if ( $heading_style ) {
				$heading_style = 'style="'. $heading_style .'"';
			}

			// Position design
			if ( 'true' == $position ) {
				$position_style = '';
				if ( $position_size ) {
					$position_style .='font-size:'. $position_size .';';
				}
				if ( $position_margin ) {
					$position_style .='margin:'. $position_margin .';';
				}
				if ( $position_color ) {
					$position_style .='color:'. $position_color .';';
				}
				if ( $position_style ) {
					$position_style = 'style="'. $position_style .'"';
				}
			}

			// Readmore design
			if ( 'true' == $read_more ) {
				$readmore_style = '';
				if ( $readmore_background ) {
					$readmore_style .='background:'. $readmore_background .';';
				}
				if ( $readmore_color ) {
					$readmore_style .='color:'. $readmore_color .';';
				}
				if ( $readmore_style ) {
					$readmore_style = 'style="'. $readmore_style .'"';
				}
			}

			// Display filter links
			if ( $filter == 'true' && taxonomy_exists( 'staff_category' ) ) {
				$terms = get_terms( 'staff_category', array(
					'include'	=> $filter_cats_include,
					'exclude'	=> $filter_cats_exclude,
				) );
				if ( $terms && count( $terms ) > '1') {
					$center_filter = 'yes' == $center_filter ? 'center' : ''; ?>
					<ul class="vcex-staff-filter filter-<?php echo $unique_id; ?> vcex-filter-links <?php echo $center_filter; ?> clr">
						<li class="active"><a href="#" data-filter="*"><span><?php echo $all_text; ?></span></a></li>
						<?php foreach ($terms as $term ) : ?>
							<li><a href="#" data-filter=".cat-<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a></li>
						<?php endforeach; ?>
					</ul><!-- .vcex-staff-filter -->
				<?php } ?>
			<?php }

			// Overlays
			if ( function_exists( 'wpex_overlay_classname' ) ) {
				$overlay_classnames = wpex_overlay_classname( $overlay_style );
			} else {
				$overlay_classnames = '';
			}

			// Image Filter class
			$img_filter_class = $img_filter ? 'vcex-'. $img_filter : '';

			// Image hover styles
			$img_hover_style_class = $img_hover_style ? 'vcex-img-hover-parent vcex-img-hover-'. $img_hover_style : '';

			// Wrap classes
			$wrap_classes = 'wpex-row vcex-staff-grid clr';
			if (  $is_isotope ) {
				$wrap_classes .= ' vcex-isotope-grid';
			}
			if ( 'left_thumbs' == $single_column_style ) {
				$wrap_classes .= ' left-thumbs';
			}

			// Lightbox classes
			if ( 'true' == $thumb_lightbox_gallery ) {
				$wrap_classes .= ' lightbox-group';
				$lightbox_single_class = ' lightbox-group-item';
			} else {
				$lightbox_single_class = ' wpex-lightbox';
			}

			// Data Attributes
			$data = '';
			if (  $is_isotope && 'true' == $filter) {
				if ( 'no_margins' != $grid_style && $masonry_layout_mode ) {
					$data .= ' data-layout-mode="'. $masonry_layout_mode .'"';
				}
				if ( $filter_speed ) {
					$data .= ' data-transition-duration="'. $filter_speed .'"';
				}
			} ?>

			<div class="<?php echo $wrap_classes; ?>" id="<?php echo $unique_id; ?>"<?php echo $data; ?>>
				<?php
				// Define counter var to clear floats
				$count = $count_all = '';
				// Start loop
				foreach ( $wpex_query->posts as $post ) : setup_postdata( $post );
					// Open match-height-row div for equal heights
					if ( $equal_heights_grid && !  $is_isotope ) {
						if ( 0 == $count ) { ?>
							<div class="match-height-row clr">
						<?php }
						$count_all++;
					}
					// Post Data
					$post_id		= $post->ID;
					$post_title		= get_the_title( $post_id );
					$post_title_esc	= esc_attr( the_title_attribute( 'echo=0' ) );
					$post_permalink	= get_permalink( $post_id );
					// Add to the counter var
					$count++;
					// Add classes to the entries
					$entry_classes = 'staff-entry col';
					$entry_classes .= ' span_1_of_'. $columns;
					if (  $is_isotope ) {
						$entry_classes .= ' vcex-isotope-entry';
					}
					if ( $img_rendering ) {
						$entry_classes .= ' vcex-image-rendering-'. $img_rendering;
					}
					if ( $img_rendering ) {
						$entry_classes .= ' vcex-image-rendering-'. $img_rendering;
					}
					$entry_classes .= ' col-'. $count;
					// Categories
					if ( taxonomy_exists( 'staff_category' ) ) {
						$post_terms = get_the_terms( $post, 'staff_category' );
						if ( $post_terms ) {
							foreach ( $post_terms as $post_term ) {
								$entry_classes .= ' cat-'. $post_term->term_id;
							}
						}
					} ?>
					<div id="#post-<?php the_ID(); ?>" class="<?php echo $entry_classes; ?>">
						<?php
						//Featured Image
						if ( has_post_thumbnail() ) {
							// Full Image URL
							$full_img_url = wp_get_attachment_url( get_post_thumbnail_id() );
							// Categories
							if ( taxonomy_exists( 'staff_category' ) ) {
								$post_terms = get_the_terms( $post, 'staff_category' );
								if ( $post_terms ) {
									foreach ( $post_terms as $post_term ) {
										$entry_classes .= ' cat-'. $post_term->term_id;
									}
								}
							} ?>
							<div class="staff-entry-media clr <?php echo $img_filter_class; ?> <?php echo $img_hover_style_class; ?> <?php echo $overlay_classnames; ?>">
								<?php if ( 'post' == $thumb_link || 'lightbox' == $thumb_link ) {
									// Link to post
									if ( 'post' == $thumb_link ) { ?>
										<a href="<?php echo $post_permalink; ?>" title="<?php echo $post_title_esc; ?>" class="staff-entry-media-link">
									<?php }
									// Lightbox link
									elseif ( $thumb_link == 'lightbox' ) {
										// Display lightbox title
										$data = '';
										if ( 'true' == $thumb_lightbox_title ) {
											$data = ' data-title="'. $post_title_esc .'"';
										} ?>
										<a href="<?php echo $full_img_url; ?>" title="<?php echo $post_title_esc; ?>" class="staff-entry-media-link<?php echo $lightbox_single_class; ?>"<?php echo $data; ?>>
									<?php }
									}
									// Get cropped image array and display image
									$cropped_img = wpex_image_resize(
										$full_img_url,
										intval( $img_width ),
										intval( $img_height ),
										$img_crop,
										'array'
									); ?>
									<img src="<?php echo $cropped_img['url']; ?>" alt="<?php the_title(); ?>" class="staff-entry-img" height="<?php echo $cropped_img['height']; ?>" width="<?php echo $cropped_img['width']; ?>" />
								<?php if ( 'post' == $thumb_link || 'lightbox' == $thumb_link ) {
									// Inner Overlay
									if ( function_exists( 'wpex_overlay' ) ) {
										wpex_overlay( 'inside_link', $overlay_style );
									} ?>
									</a>
								<?php }
								// Outside Overlay
								if ( function_exists( 'wpex_overlay' ) ) {
									wpex_overlay( 'outside_link', $overlay_style );
								} ?>
							</div><!-- .staff-media -->
						<?php } ?>
						<?php if ( 'true' == $title || 'true' == $excerpt || 'true' == $read_more || 'true' == $position ) { ?>
							<div class="staff-entry-details clr" <?php echo $content_style; ?>>
								<?php
								// Equal height div
								if ( $equal_heights_grid && ! $is_isotope ) { ?>
								<div class="match-height-content">
								<?php }
								// Display the title
								if ( 'true' == $title ) { ?>
									<h2 class="staff-entry-title" <?php echo $heading_style; ?>>
										<?php if ( 'post' == $title_link ) { ?>
											<a href="<?php echo $post_permalink; ?>" title="<?php echo $post_title_esc; ?>" <?php echo $heading_style; ?>><?php the_title(); ?></a>
										<?php } else { ?>
											<?php the_title(); ?>
										<?php } ?>
									</h2><!-- .staff-entry-title -->
								<?php }
								// Display staff member position
								if ( 'true' == $position && '' != get_post_meta( $post_id, 'wpex_staff_position', true ) ) { ?>
									<div class="staff-entry-position" <?php echo $position_style; ?>>
										<?php echo get_post_meta( $post_id, 'wpex_staff_position', true ); ?>
									</div><!-- .staff-entry-position -->
								<?php }
								// Display the excerpt
								if ('true' ==  $excerpt ) { ?>
									<div class="staff-entry-excerpt clr">
										<?php
										// Dusplay full content
										if ( '9999' == $excerpt_length ) {
											the_content();
										}
										// Custom Excerpt
										else {
											$trim_custom_excerpts = 'true' == $custom_excerpt_trim ? true : false;
											$excerpt_array = array (
												'length'				=> intval( $excerpt_length ),
												'trim_custom_excerpts'	=> $trim_custom_excerpts
											);
											vcex_excerpt( $excerpt_array );
										} ?>
									</div><!-- .staff-entry-excerpt -->
								<?php }
								// Display social links
								if ( function_exists( 'wpex_get_staff_social' ) && 'true' == $social_links ) {
									echo wpex_get_staff_social();
								}
								// Read more button
								if ( 'true' == $read_more ) { ?>
									<a href="<?php echo get_permalink(); ?>" title="<?php $read_more_text; ?>" rel="bookmark" class="vcex-readmore theme-button" <?php echo $readmore_style; ?>>
										<?php echo $read_more_text; ?> <span class="vcex-readmore-rarr"><?php echo wpex_element( 'rarr' ); ?></span>
									</a>
								<?php }
								// Close Equal height div
								if ( $equal_heights_grid && ! $is_isotope ) { ?>
								</div>
								<?php } ?>
							</div><!-- .staff-entry-details -->
						<?php } ?>
					</div><!-- .staff-entry -->
					<?php
					// Reset counter
					if ( $count == $columns ) {
						// Close equal height row
						if ( $equal_heights_grid && ! $is_isotope ) {
							echo '</div><!-- .match-height-row -->';
						}
						// Reset counter
						$count = '';
					}
					// End foreach
					endforeach;
					// Make sure match-height-row is closed
					if ( $equal_heights_grid && ! $is_isotope ) {
						if ( '4' == $columns && ( $count_all % 4 != 0 ) ) {
							echo '</div><!-- .match-height-row -->';
						}
						if ( '3' == $columns && ( $count_all % 3 != 0 ) ) {
							echo '</div><!-- .match-height-row -->';
						}
						if ( '2' == $columns && ( $count_all % 2 != 0 ) ) {
							echo '</div><!-- .match-height-row -->';
						}
					} ?>
				</div><!-- .vcex-staff-grid -->
					
				<?php
				// Paginate Posts
				if ( 'true' == $pagination ) {
					wpex_pagination( $wpex_query );
				}
			
			// End has posts check
			endif;

			// Reset the WP query
			$wpex_query = NULL;
			wp_reset_postdata();

		// Return outbut buffer
		return ob_get_clean();

	}

}
add_shortcode( "vcex_staff_grid", "vcex_staff_grid_shortcode" );

if ( ! function_exists( 'vcex_staff_grid_shortcode_vc_map' ) ) {
	function vcex_staff_grid_shortcode_vc_map() {
		vc_map( array(
			"name"					=> __( "Staff Grid", 'wpex' ),
			"description"			=> __( "Recent staff posts grid", 'wpex' ),
			"base"					=> "vcex_staff_grid",
			'category'				=> WPEX_THEME_BRANDING,
			"icon" 					=> "vcex-staff-grid",
			"params"				=> array(

				// General
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Unique Id", 'wpex' ),
					'param_name'	=> "unique_id",
					'value'			=> '',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Columns", 'wpex' ),
					'param_name'	=> "columns",
					'value' 		=> array(
						__( 'Six','wpex' )		=> '6',
						__( 'Five','wpex' )		=> '5',
						__( 'Four','wpex' )		=> '4',
						__( 'Three','wpex' )	=> '3',
						__( 'Two','wpex' )		=> '2',
						__( 'One','wpex' )		=> '1',
					),
					"std"			=> '3',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "1 Column Style", 'wpex' ),
					'param_name'	=> "single_column_style",
					'value'			=> array(
						__( "Default", 'wpex')		=> '',
						__( "Left/Right", 'wpex' )	=> 'left_thumbs',
					),
					'dependency'	=> array(
						'element'	=> 'columns',
						'value'		=> '1',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Grid Style", 'wpex' ),
					'param_name'	=> "grid_style",
					'value'			=> array(
						__( "Fit Columns", 'wpex')	=> "fit_columns",
						__( "Masonry", 'wpex' )		=> "masonry",
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Equal Heights?", 'wpex' ),
					'param_name'	=> "equal_heights_grid",
					'value'			=> array(
						__( "No", 'wpex' )	=> '',
						__( "Yes", 'wpex')	=> 'true',
					),
					'dependency'	=> array(
						'element'	=> 'grid_style',
						'value'		=> 'fit_columns',
					),
					'description'	=> __( 'Adds equal heights for the entry content so "boxes" on the same row are the same height. You must have equal sized images for this to work efficiently.', 'wpex' ),
				),

				// Query
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Include Categories", 'wpex' ),
					'param_name'	=> "include_categories",
					"admin_label"	=> true,
					'value'			=> '',
					"description"	=> __('Enter the slugs of a categories (comma seperated) to pull posts from or enter "all" to pull recent posts from all categories. Example: category-1, category-2.','wpex'),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Exclude Categories", 'wpex' ),
					'param_name'	=> "exclude_categories",
					"admin_label"	=> true,
					'value'			=> '',
					"description"	=> __('Enter the slugs of a categories (comma seperated) to exclude. Example: category-1, category-2.','wpex'),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Order', 'wpex' ),
					'param_name'	=> 'order',
					'value'			=> array(
						__( 'DESC', 'wpex' )	=> 'DESC',
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
					'heading'		=> __( "Orderby: Meta Key", 'wpex' ),
					'param_name'	=> "orderby_meta_key",
					'value'			=> '',
					'group'			=> __( 'Query', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'orderby',
						'value'		=> array( 'meta_value_num', 'meta_value' ),
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Posts Per Page", 'wpex' ),
					'param_name'	=> "posts_per_page",
					'value'			=> "12",
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Pagination", 'wpex' ),
					'param_name'	=> "pagination",
					'value'			=> array(
						__( "No", 'wpex' )	=> "false",
						__( "Yes", 'wpex')	=> 'true',
					),
					"description"	=> __("Paginate posts? Important: Pagination will not work on your homepage because of how WordPress works",'wpex'),
					'group'			=> __( 'Query', 'wpex' ),
				),
				
				// Filter
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Category Filter", 'wpex' ),
					'param_name'	=> "filter",
					'value'			=> array(
						__( "No", 'wpex' )	=> '',
						__( "Yes", 'wpex')	=> 'true',
					),
					'group'			=> __( 'Filter', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Layout Mode', 'wpex' ),
					'param_name'	=> 'masonry_layout_mode',
					'value'			=> array(
						__( 'Masonry', 'wpex' )		=> 'masonry',
						__( 'Fit Rows', 'wpex' )	=> 'fitRows',
					),
					'group'			=> __( 'Filter', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Custom Filter Speed', 'wpex' ),
					'param_name'	=> "filter_speed",
					'description'	=> __( 'Default is "0.4" seconds', 'wpex' ),
					'group'			=> __( 'Filter', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					"class"			=> '',
					'heading'		=> __( "Center Filter Links", 'wpex' ),
					'param_name'	=> "center_filter",
					'value'			=> array(
						__( 'No', 'wpex' )	=> 'no',
						__( 'Yes', 'wpex' )	=> 'yes',
					),
					'group'			=> __( 'Filter', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					"class"			=> '',
					'heading'		=> __( 'Custom Category Filter "All" Text', 'wpex' ),
					'param_name'	=> "all_text",
					'value'			=> __( 'All', 'wpex' ),
					'group'			=> __( 'Filter', 'wpex' ),
				),

				// Image
				vcex_overlays_array(),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Image Crop Width", 'wpex' ),
					'param_name'	=> "img_width",
					'value'			=> "9999",
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Image Crop Height", 'wpex' ),
					'param_name'	=> "img_height",
					'value'			=> "9999",
					"description"	=> __( "Custom image cropping height. Enter 9999 for no cropping.", 'wpex' ),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Image Filter", 'wpex' ),
					'param_name'	=> "img_filter",
					'value'			=> vcex_image_filters(),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "CSS3 Image Hover", 'wpex' ),
					'param_name'	=> "img_hover_style",
					'value'			=> vcex_image_hovers(),
					"description"	=> __("Select your preferred image hover effect. Please note this will only work if the image links to a URL or a large version of itself. Please note these effects may not work in all browsers.", 'wpex'),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Image Rendering", 'wpex' ),
					'param_name'	=> "img_rendering",
					'value'			=> vcex_image_rendering(),
					"description"	=> sprintf( __( 'Image-rendering CSS property provides a hint to the user agent about how to handle its image rendering. For example when scaling down images they tend to look a bit fuzzy in Firefox, setting image-rendering to crisp-edges can help make the images look better. <a href="%s">Learn more</a>.', 'wpex' ), 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering' ),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Image Links To", 'wpex' ),
					'param_name'	=> "thumb_link",
					'value'			=> array(
						__( "Post", 'wpex')			=> "post",
						__( "Lightbox", 'wpex' )	=> "lightbox",
						__( "Nowhere", 'wpex' )		=> "nowhere",
					),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Display Lightbox Gallery", 'wpex' ),
					'param_name'	=> "thumb_lightbox_gallery",
					'value'			=> array(
						__( "No", "wpex")	=> '',
						__( "Yes", "wpex" )	=> "true",
					),
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'thumb_link',
						'value'		=> 'lightbox',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Display Title In Lightbox", 'wpex' ),
					'param_name'	=> "thumb_lightbox_title",
					'value'			=> array(
						__( "No", "wpex")		=> '',
						__( "Yes", "wpex" )	=> "true",
					),
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'thumb_link',
						'value'		=> 'lightbox',
					),
				),

				// Content
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Title", 'wpex' ),
					'param_name'	=> "title",
					'value'			=> array(
						__( "Yes", 'wpex' )	=> 'true',
						__( "No", 'wpex' )	=> "false",
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Title Links To", 'wpex' ),
					'param_name'	=> "title_link",
					'value'			=> array(
						__( "Post", 'wpex')		=> "post",
						__( "Lightbox", 'wpex')	=> "lightbox",
						__( "Nowhere", 'wpex' )	=> "nowhere",
					),
					'group'			=> __( 'Content', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> "title",
						'value'		=> array( 'true' ),
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Position", 'wpex' ),
					'param_name'	=> "position",
					'value'			=> array(
						__( "No", 'wpex' )	=> "false",
						__( "Yes", 'wpex' )	=> 'true',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Excerpt", 'wpex' ),
					'param_name'	=> "excerpt",
					'value'			=> array(
						__( "Yes", 'wpex')	=> 'true',
						__( "No", 'wpex' )	=> "false",
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Excerpt Length", 'wpex' ),
					'param_name'	=> "excerpt_length",
					'value'			=> "30",
					'group'			=> __( 'Content', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> "excerpt",
						'value'		=> array( 'true' ),
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Trim Custom Excerpts", 'wpex' ),
					'param_name'	=> "custom_excerpt_trim",
					'value'			=> array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex' )	=> 'true',
					),
					'group'			=> __( 'Content', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> "excerpt",
						'value'		=> array( 'true' ),
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Read More', 'wpex' ),
					'param_name'	=> 'read_more',
					'value'			=> array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex')	=> 'true',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Read More Text', 'wpex' ),
					'param_name'	=> 'read_more_text',
					'value'			=> __('view post', 'wpex' ),
					'group'			=> __( 'Content', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'read_more',
						'value'		=> array( 'true' ),
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Social Links", 'wpex' ),
					'param_name'	=> "social_links",
					'value'			=> array(
						__( "Yes", 'wpex')	=> 'true',
						__( "No", 'wpex' )	=> "false",
					),
					'group'			=> __( 'Content', 'wpex' ),
				),

				// Design
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Title Font size", 'wpex' ),
					'param_name'	=> "content_heading_size",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					'heading'		=> __( "Title Font Color", 'wpex' ),
					'param_name'	=> "content_heading_color",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Title Margin", 'wpex' ),
					'param_name'	=> "content_heading_margin",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Position Font size", 'wpex' ),
					'param_name'	=> "position_size",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					'heading'		=> __( "Position Font Color", 'wpex' ),
					'param_name'	=> "position_color",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Position Margin", 'wpex' ),
					'param_name'	=> "position_margin",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					'heading'		=> __( "Content Background", 'wpex' ),
					'param_name'	=> "content_background",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					'heading'		=> __( "Content Text Color", 'wpex' ),
					'param_name'	=> "content_color",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Content Alignment', 'wpex' ),
					'param_name'	=> "content_alignment",
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
					'heading'		=> __( "Content Font Size", 'wpex' ),
					'param_name'	=> "content_font_size",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Content Margin", 'wpex' ),
					'param_name'	=> "content_margin",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Content Padding", 'wpex' ),
					'param_name'	=> "content_padding",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Content Opacity", 'wpex' ),
					'param_name'	=> "content_opacity",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Content Border", 'wpex' ),
					'param_name'	=> "content_border",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					'heading'		=> __( "Content Read More Background", 'wpex' ),
					'param_name'	=> "readmore_background",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					'heading'		=> __( "Content Read More Color", 'wpex' ),
					'param_name'	=> "readmore_color",
					'group'			=> __( 'Design', 'wpex' ),
				),

			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_staff_grid_shortcode_vc_map' );