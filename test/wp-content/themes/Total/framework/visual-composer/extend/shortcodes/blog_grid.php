<?php
/**
 * Registers the blog grid shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists( 'vcex_blog_grid_shortcode' ) ) {
	function vcex_blog_grid_shortcode($atts) {
		
		extract( shortcode_atts( array(
			'unique_id'				=> '',
			'term_slug'				=> '',
			'include_categories'	=> '',
			'exclude_categories'	=> '',
			'posts_per_page'		=> '4',
			'grid_style'			=> 'fit_columns',
			'columns'				=> '3',
			'order'					=> 'DESC',
			'orderby'				=> 'date',
			'filter'				=> 'false',
			'masonry_layout_mode'	=> '',
			'filter_speed'			=> '',
			'center_filter'			=> '',
			'thumbnail_link'		=> 'post',
			'entry_media'			=> "true",
			'img_width'				=> '9999',
			'img_height'			=> '9999',
			'thumb_link'			=> 'post',
			'img_filter'			=> '',
			'title'					=> 'true',
			'date'					=> 'true',
			'excerpt'				=> 'true',
			'excerpt_length'		=> '15',
			'custom_excerpt_trim'	=> 'true',
			'read_more'				=> 'false',
			'read_more_text'		=> __( 'read more', 'wpex' ),
			'pagination'			=> 'false',
			'filter_content'		=> 'false',
			'offset'				=> 0,
			'taxonomy'				=> '',
			'terms'					=> '',
			'all_text'				=> __( 'All', 'wpex' ),
			'featured_video'		=> 'true',
			'url_target'			=> '_self',
			'date_color'			=> '',
			'content_heading_margin'=> '',
			'content_background'	=> '',
			'content_margin'		=> '',
			'content_font_size'		=> '',
			'content_padding'		=> '',
			'content_border'		=> '',
			'content_color'			=> '',
			'content_opacity'		=> '',
			'content_heading_color'	=> '',
			'content_heading_size'	=> '',
			'content_alignment'		=> '',
			'readmore_background'	=> '',
			'readmore_color'		=> '',
			'equal_heights_grid'	=> '',
			'single_column_style'	=> '',
		), $atts ) );

		// Turn output buffer on
		ob_start();
			
			// Get global $post var
			global $post;
				
			// Include categories
			$include_categories = ( '' != $include_categories ) ? $include_categories : $term_slug;
			$include_categories = ( 'all' == $include_categories ) ? '' : $include_categories;
			$filter_cats_include = '';
			if ( $include_categories ) {
				$include_categories = explode( ',', $include_categories );
				$filter_cats_include = array();
				foreach ( $include_categories as $key ) {
					$key = get_term_by( 'slug', $key, 'category' );
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
					$key = get_term_by( 'slug', $key, 'category' );
					$filter_cats_exclude[] = $key->term_id;
				}
				$exclude_categories = array(
					'taxonomy'	=> 'category',
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
					'taxonomy'	=> 'category',
					'field'		=> 'slug',
					'terms'		=> $include_categories,
					'operator'	=> 'IN',
				);
			} else {
				$include_categories = '';
			}
			
			// Pagination var
			$paged			= NULL;
			$no_found_rows	= true;
			if ( 'true' == $pagination ) {
				if ( get_query_var( 'paged' ) ) {
					$paged = get_query_var( 'paged' );
				} elseif ( get_query_var( 'page' ) ) {
					$paged = get_query_var( 'page' );
				} else {
					$paged = 1;
				}
				$no_found_rows	= false;
			}
			
			// The Query
			$wpex_query = new WP_Query(
				array(
					'post_type'			=> 'post',
					'posts_per_page'	=> $posts_per_page,
					'offset'			=> $offset,
					'order'				=> $order,
					'orderby'			=> $orderby,
					'filter_content'	=> $filter_content,
					'paged'				=> $paged,
					'tax_query'			=> array(
						'relation'		=> 'AND',
						$include_categories,
						$exclude_categories,
					),
					'no_found_rows'		=> $no_found_rows
				)
			);

			//Output posts
			if ( $wpex_query->posts ) :

			// Custom excerpts trim
			$custom_excerpt_trim = ( 'true' == $custom_excerpt_trim ) ? true : false;
			
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
			if ( 'true' == $filter || 'masonry' == $grid_style ) {
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
			if ( $is_isotope ) {
				vcex_front_end_grid_js( 'isotope' );
			}

			// Display filter links
			if ( $filter == 'true' ) {
				$terms = get_terms( 'category', array(
					'include'	=> $filter_cats_include,
					'exclude'	=> $filter_cats_exclude,
				) );
				if ( $terms && count( $terms ) > '1') {
					$center_filter = 'yes' == $center_filter ? 'center' : ''; ?>
					<ul class="vcex-blog-filter vcex-filter-links <?php echo $center_filter; ?> clr">
						<li class="active"><a href="#" data-filter="*"><span><?php echo $all_text; ?></span></a></li>
						<?php foreach ( $terms as $term ) { ?>
							<li><a href="#" data-filter=".cat-<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a></li>
						<?php } ?>
					</ul><!-- .vcex-blog-filter -->
				<?php } ?>
			<?php }

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

			// Date design
			$date_style = '';
			if ( 'true' == $date ) {
				if ( $date_color ) {
					$date_style .='color:'. $date_color .';';
				}
				if ( $date_style ) {
					$date_style = 'style="'. $date_style .'"';
				}
			}

			// Wrap classes
			$wrap_classes = 'wpex-row vcex-blog-grid clr';
			if ( $is_isotope ) {
					$wrap_classes .= ' vcex-isotope-grid';
				}
			if ( 'left_thumbs' == $single_column_style ) {
				$wrap_classes .= ' left-thumbs';
			}

			// Unique ID
			if ( $unique_id ) {
				$unique_id = ' id="'. $unique_id .'"';
			}

			// Data
			$data = '';
			if ( $is_isotope && 'true' == $filter) {
				if ( $masonry_layout_mode ) {
					$data .= ' data-layout-mode="'. $masonry_layout_mode .'"';
				}
				if ( $filter_speed ) {
					$data .= ' data-transition-duration="'. $filter_speed .'"';
				}
			} ?>
	
			<div class="<?php echo $wrap_classes; ?>"<?php echo $unique_id . $data; ?>>
				<?php
				// Define counter var to clear floats
				$count = $count_all = '';
				// Loop through posts
				foreach ( $wpex_query->posts as $post ) : setup_postdata( $post );
					// Open match-height-row div for equal heights
					if ( $equal_heights_grid && ! $is_isotope ) {
						if ( 0 == $count ) { ?>
							<div class="match-height-row clr">
						<?php }
						$count_all++;
					}
					// Post Vars
					$post_id		= $post->ID;
					$format			= get_post_format( $post_id );
					$post_title		= get_the_title();
					$post_title_esc	= esc_attr( the_title_attribute( 'echo=0' ) );
					$link			= get_permalink();
					// Counter
					$count++;
					// Link format tweaks
					if ( 'link' == $format ) {
						$link		= wpex_permalink();
						$url_target	= '_blank';
					}
					// Get video
					if ( 'video' == $format ) {
						$video_url = get_post_meta( $post_id, 'wpex_post_oembed', true );
					}
					// Entry Classes
					$entry_classes = 'vcex-blog-entry col';
					$entry_classes .= ' col-'. $count;
					$entry_classes .= ' span_1_of_'. $columns;
					if ( $is_isotope ) {
						$entry_classes .= ' vcex-isotope-entry';
					}
					// Create a list of terms to add as classes to the entry
					if ( $post_terms = get_the_terms( $post, 'category' ) ) {
						foreach ( $post_terms as $post_term ) {
							$entry_classes .= ' cat-'. $post_term->term_id;
						}
					}
					if ( "false" == $entry_media ) {
						$entry_classes .= ' vcex-blog-no-media-entry';
					} ?>
					<article id="#post-<?php the_ID(); ?>" class="<?php echo $entry_classes; ?>">
						<?php if ( "true" == $entry_media ) { ?>
							<?php $img_filter_class = $img_filter ? 'vcex-'. $img_filter : ''; ?>
							<?php if ( "video" == $format && 'true' == $featured_video && $video_url ) { ?>
								<div class="vcex-blog-entry-media">
									<div class="vcex-video-wrap">
										<?php echo wp_oembed_get( $video_url ); ?>
									</div>
								</div><!-- .vcex-blog-entry-media -->
							<?php } elseif ( has_post_thumbnail() ) { ?>
								<div class="vcex-blog-entry-media <?php echo $img_filter_class; ?>">
									<?php if ( $thumb_link == 'post' ||  $thumb_link == 'lightbox' ) { ?>
										<?php
										// Post links
										if ( $thumb_link == 'post' ) { ?>
											<a href="<?php echo $link; ?>" title="<?php echo $post_title_esc; ?>" target="<?php echo $url_target; ?>">
										<?php } ?>
										<?php
										// Lightbox Links
										if ( $thumb_link == 'lightbox' ) { ?>
											<?php
											// Video Lightbox
											if ( 'video' == $format ) { ?>
												<a href="<?php echo $video_url; ?>" title="<?php echo $post_title_esc; ?>" class="wpex-lightbox-video">
											<?php }
											// Image lightbox
											else { ?>
												<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" title="<?php echo $post_title_esc; ?>" class="wpex-lightbox">
											<?php } ?>
										<?php } ?>
									<?php }
										// Get cropped image array and display image
										$cropped_img = wpex_image_resize( wp_get_attachment_url( get_post_thumbnail_id() ), intval($img_width), intval($img_height), $img_crop, 'array' ); ?>
										<img src="<?php echo $cropped_img['url']; ?>" alt="<?php the_title(); ?>" class="vcex-blog-entry-img" height="<?php echo $cropped_img['height']; ?>" width="<?php echo $cropped_img['width']; ?>" />
									<?php if ( $thumb_link == 'post' ||  $thumb_link == 'lightbox' ) { ?>
										</a>
									<?php } ?>
								</div><!-- .blog-entry-media -->
							<?php } ?>
						<?php } ?>
						<?php
						// Display entry details if the title or excerpt are enabled
						if ( 'true' == $title || 'true' == $excerpt ) { ?>
							<div class="vcex-blog-entry-details clr" <?php echo $content_style; ?>>
								<?php
								// Equal height div
								if ( $equal_heights_grid && ! $is_isotope ) { ?>
								<div class="match-height-content">
								<?php }
								// Post Title
								if ( $title == 'true' ) { ?>
									<h2 class="vcex-blog-entry-title" <?php echo $heading_style; ?>>
										<a href="<?php echo $link; ?>" title="<?php echo $post_title_esc; ?>" target="<?php echo $url_target; ?>" <?php echo $heading_style; ?>>
											<?php the_title(); ?>
										</a>
									</h2>
								<?php }
								// Post Date
								if ( $date == 'true' ) { ?>
									<div class="vcex-blog-entry-date" <?php echo $date_style; ?>><?php echo get_the_date(); ?></div>
								<?php }
								// Excerpt
								if ('true' ==  $excerpt || 'true' == $read_more ) {
									if ( 'true' != $excerpt ){
										$excerpt_length = '0';
									} ?>
									<div class="vcex-blog-entry-excerpt clr">
										<?php
										// Show full content
										if ( '9999' == $excerpt_length  ) {
											the_content();
										}
										// Display custom excerpt
										else {
											// Custom Excerpt
											$excerpt_array = array (
												'post_id'				=> $post_id,
												'length'				=> intval( $excerpt_length ),
												'trim_custom_excerpts'	=> $custom_excerpt_trim,
											);
											vcex_excerpt( $excerpt_array );
										}
										// Read more
										if ( 'true' == $read_more ) {
											if ( 'link' == $format ) {
												$read_more_text = __( 'Visit Website', 'wpex' );
											} ?>
											<a href="<?php echo $link; ?>" title="<?php echo $read_more_text; ?>" rel="bookmark" class="vcex-readmore theme-button" target="<?php echo $url_target; ?>" <?php echo $readmore_style; ?>>
												<?php echo $read_more_text; ?> <span class="vcex-readmore-rarr"><?php echo wpex_element( 'rarr' ); ?></span>
											</a>
										<?php } ?>
									</div>
								<?php }
								// Close Equal height div
								if ( $equal_heights_grid && ! $is_isotope ) { ?>
								</div>
								<?php } ?>
							</div><!-- .blog-entry-details -->
						<?php } ?>
					</article><!-- .blog-entry -->
				<?php
				// Reset counter
				if ( $count == $columns ) {
					// Close equal height row
					if ( $equal_heights_grid && ! $is_isotope ) {
						echo '</div><!-- .match-height-row -->';
					}
					$count = '0';
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
			</div><!-- .vcex-blog-grid -->
			
			<?php
			// Paginate Posts
			if ( 'true' == $pagination ) {
				wpex_pagination( $wpex_query );
			}
			
			// End has posts check
			endif;

			// Reset the WP query
			wp_reset_postdata();

		// Return outbut buffer
		return ob_get_clean();
		
	}
}
add_shortcode( 'vcex_blog_grid', 'vcex_blog_grid_shortcode' );

/**
 * Adds the blog grid shortcode to the Visual Composer
 *
 * @since Total 1.41
 */
if ( ! function_exists( 'vcex_blog_grid_shortcode_vcmap' ) ) {
	function vcex_blog_grid_shortcode_vcmap() {
		vc_map( array(
			"name"					=> __( "Blog Grid", 'wpex' ),
			"description"			=> __( "Recent blog posts grid", 'wpex' ),
			"base"					=> "vcex_blog_grid",
			"icon"					=> "vcex-blog-grid",
			"category"				=> WPEX_THEME_BRANDING,
			"params"				=> array(

				// General
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Unique Id", 'wpex' ),
					"param_name"	=> "unique_id",
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Columns", 'wpex' ),
					"param_name"	=> "columns",
					"value" 		=> array(
						__( 'Six','wpex' )		=> '6',
						__( 'Five','wpex' )		=> '5',
						__( 'Four','wpex' )		=> '4',
						__( 'Three','wpex' )	=> '3',
						__( 'Two','wpex' )		=> '2',
						__( 'One','wpex' )		=> '1',
					),
					"std"			=> '4',
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "1 Column Style", 'wpex' ),
					"param_name"	=> "single_column_style",
					"value"			=> array(
						__( "Default", "wpex")		=> '',
						__( "Left/Right", "wpex" )	=> 'left_thumbs',
					),
					'dependency'	=> array(
						'element'	=> 'columns',
						'value'		=> '1',
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Grid Style", 'wpex' ),
					"param_name"	=> "grid_style",
					"value"			=> array(
						__( "Fit Columns", "wpex")	=> "fit_columns",
						__( "Masonry", "wpex" )		=> "masonry",
					),
				),
				array(
					'type'			=> "dropdown",
					"heading"		=> __( "Equal Heights?", 'wpex' ),
					'param_name'	=> "equal_heights_grid",
					'value'			=> array(
						__( "No", "wpex" )	=> "",
						__( "Yes", "wpex")	=> "true",
					),
					'dependency'	=> array(
						'element'	=> 'grid_style',
						'value'		=> 'fit_columns',
					),
					'description'	=> __( 'Adds equal heights for the portfolio entry content so "boxes" on the same row are the same height. You must have equal sized images for this to work efficiently.', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Display Featured Videos?", 'wpex' ),
					"param_name"	=> "featured_video",
					"value"			=> array(
						__( "True", "wpex")		=> "true",
						__( "False", "wpex" )	=> "false",
					),
					"description"	=> __( "Display your featured videos on the video post format posts (this will only work if the featured media option is enabled above).", "wpex" ),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Post Link Target", 'wpex' ),
					"param_name"	=> "url_target",
					"value"		=> array(
						__("Self", "wpex")	=> "_self",
						__("Blank", "wpex")	=> "_blank",
					),
				),

				// Query
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Include Categories", 'wpex' ),
					"param_name"	=> "include_categories",
					"admin_label"	=> true,
					"description"	=> __('Enter the slugs of a categories (comma seperated) to pull posts from or enter "all" to pull recent posts from all categories. Example: category-1, category-2.','wpex'),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Exclude Categories", 'wpex' ),
					"param_name"	=> "exclude_categories",
					"admin_label"	=> true,
					"description"	=> __('Enter the slugs of a categories (comma seperated) to exclude. Example: category-1, category-2.','wpex'),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
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
					"heading"		=> __( "Order By", 'wpex' ),
					"param_name"	=> "orderby",
					"value"			=> array(
						__( "Date", "wpex")				=> "date",
						__( "Name", "wpex" )			=> "name",
						__( "Modified", "wpex")			=> "modified",
						__( "Author", "wpex" )			=> "author",
						__( "Random", "wpex")			=> "rand",
						__( "Comment Count", "wpex" )	=> "comment_count",
					),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Pagination", 'wpex' ),
					"param_name"	=> "pagination",
					"value"			=> array(
						__( "False", "wpex")	=> "false",
						__( "True", "wpex" )	=> "true",
					),
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Posts Per Page", 'wpex' ),
					"param_name"	=> "posts_per_page",
					"value"			=> "4",
					'group'			=> __( 'Query', 'wpex' ),
				),

				// Filter
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Category Filter", 'wpex' ),
					"param_name"	=> "filter",
					"value"			=> array(
						__( "No", "wpex" )	=> "",
						__( "Yes", "wpex")	=> "true",
					),
					'group'			=> __( 'Filter', 'wpex' ),
				),
				array(
					'type'			=> "dropdown",
					'heading'		=> __( "Layout Mode", 'wpex' ),
					'param_name'	=> "masonry_layout_mode",
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
					"type"			=> "dropdown",
					"heading"		=> __( "Center Filter Links", 'wpex' ),
					"param_name"	=> "center_filter",
					"value"			=> array(
						__( 'No', 'wpex' )	=> 'no',
						__( 'Yes', 'wpex' )	=> 'yes',
					),
					'group'			=> __( 'Filter', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( 'Custom Category Filter "All" Text', 'wpex' ),
					"param_name"	=> "all_text",
					"value"			=> __( 'All', 'wpex' ),
					'group'			=> __( 'Filter', 'wpex' ),
				),

				// Image
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Entry Media", 'wpex' ),
					"param_name"	=> "entry_media",
					"value"			=> array(
						__( "Yes", "wpex")	=> "true",
						__( "No", "wpex" )	=> "false",
					),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Image Width", 'wpex' ),
					"param_name"	=> "img_width",
					"value"			=> "9999",
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Image Height", 'wpex' ),
					"param_name"	=> "img_height",
					"value"			=> "9999",
					"description"	=> __( "Custom image cropping height. Enter 9999 for no cropping.", 'wpex' ),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Image Filter", 'wpex' ),
					"param_name"	=> "img_filter",
					"value"			=> vcex_image_filters(),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Image Links To", 'wpex' ),
					"param_name"	=> "thumb_link",
					"value"			=> array(
						__( "Post", "wpex")			=> "post",
						__( "Lightbox", "wpex" )	=> "lightbox",
						__( "Nowhere", "wpex" )		=> "nowhere",
					),
					'group'			=> __( 'Image', 'wpex' ),
				),

				// Content
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Title", 'wpex' ),
					"param_name"	=> "title",
					"value"			=> array(
						__( "Yes", "wpex")	=> "true",
						__( "No", "wpex" )	=> "false",
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Title Font size", 'wpex' ),
					'param_name'	=> "content_heading_size",
					'group'			=> __( 'Content', 'wpex' ),
					"dependency"	=> Array(
						'element'	=> "title",
						'value'		=> array( 'true' ),
					),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Title Margin", 'wpex' ),
					'param_name'	=> "content_heading_margin",
					'group'			=> __( 'Content', 'wpex' ),
					"dependency"	=> Array(
						'element'	=> "title",
						'value'		=> array( 'true' ),
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Date", 'wpex' ),
					"param_name"	=> "date",
					"value"			=> array(
						__( "Yes", "wpex")	=> "true",
						__( "No", "wpex" )	=> "false",
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Date Color", 'wpex' ),
					'param_name'	=> "date_color",
					'group'			=> __( 'Content', 'wpex' ),
					"dependency"	=> Array(
						'element'	=> "date",
						'value'		=> array( 'true' ),
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Excerpt", 'wpex' ),
					"param_name"	=> "excerpt",
					"value"			=> array(
						__( "Yes", "wpex")	=> "true",
						__( "No", "wpex" )	=> "false",
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Excerpt Length", 'wpex' ),
					"param_name"	=> "excerpt_length",
					"value"			=> "15",
					'group'			=> __( 'Content', 'wpex' ),
					"dependency"	=> Array(
						'element'	=> "excerpt",
						'value'		=> array( 'true' ),
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Trim Custom Excerpts", 'wpex' ),
					"param_name"	=> "custom_excerpt_trim",
					"value"			=> array(
						__( 'Yes', 'wpex' )	=> 'true',
						__( 'No', 'wpex' )	=> '',
					),
					'group'			=> __( 'Content', 'wpex' ),
					"dependency"	=> Array(
						'element'	=> "excerpt",
						'value'		=> array( 'true' ),
					),
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Read More", 'wpex' ),
					"param_name"	=> "read_more",
					"value"			=> array(
						__( "Yes", "wpex")	=> "true",
						__( "No", "wpex" )	=> "false",
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Read More Text", 'wpex' ),
					"param_name"	=> "read_more_text",
					"value"			=> __( 'read more', 'wpex' ),
					'group'			=> __( 'Content', 'wpex' ),
					"dependency"	=> Array(
						'element'	=> "readmore",
						'value'		=> array( 'true' ),
					),
				),

				// Design
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Content Background", 'wpex' ),
					'param_name'	=> "content_background",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Heading Text Color", 'wpex' ),
					'param_name'	=> "content_heading_color",
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
						__( "Default", "wpex" )	=> "",
						__( "Left", "wpex" )	=> "left",
						__( "Right", "wpex" )	=> "right",
						__( "Center", "wpex")	=> "center",
					),
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Content Font Size", 'wpex' ),
					'param_name'	=> "content_font_size",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Content Margin", 'wpex' ),
					'param_name'	=> "content_margin",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Content Padding", 'wpex' ),
					'param_name'	=> "content_padding",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Content Opacity", 'wpex' ),
					'param_name'	=> "content_opacity",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "textfield",
					"heading"		=> __( "Content Border", 'wpex' ),
					'param_name'	=> "content_border",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Content Read More Background", 'wpex' ),
					'param_name'	=> "readmore_background",
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> "colorpicker",
					"heading"		=> __( "Content Read More Color", 'wpex' ),
					'param_name'	=> "readmore_color",
					'group'			=> __( 'Design', 'wpex' ),
				),

			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_blog_grid_shortcode_vcmap' );