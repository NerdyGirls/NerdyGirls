<?php
/**
 * Registers the portfolio grid shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( ! function_exists( 'vcex_portfolio_grid_shortcode' ) ) {
	function vcex_portfolio_grid_shortcode( $atts ) {
		
		extract( shortcode_atts( array(
			'unique_id'					=> '',
			'term_slug'					=> '',
			'include_categories'		=> '',
			'exclude_categories'		=> '',
			'posts_per_page'			=> '8',
			'grid_style'				=> 'fit_columns',
			'masonry_layout_mode'		=> '',
			'filter_speed'				=> '',
			'columns'					=> '4',
			'order'						=> 'DESC',
			'orderby'					=> 'date',
			'orderby_meta_key'			=> '',
			'filter'					=> '',
			'center_filter'				=> 'no',
			'thumb_link'				=> 'post',
			'thumb_lightbox_gallery'	=> '',
			'thumb_lightbox_title'		=> '',
			'img_crop'					=> 'true',
			'img_width'					=> '9999',
			'img_height'				=> '9999',
			'img_filter'				=> '',
			'title'						=> 'true',
			'title_link'				=> 'post',
			'excerpt'					=> 'true',
			'excerpt_length'			=> 30,
			'custom_excerpt_trim'		=> '',
			'read_more'					=> '',
			'read_more_text'			=> __( 'read more', 'wpex' ),
			'pagination'				=> '',
			'filter_content'			=> 'false',
			'offset'					=> 0,
			'taxonomy'					=> '',
			'terms'						=> '',
			'img_hover_style'			=> '',
			'img_overlay_disable'		=> '',
			'img_rendering'				=> '',
			'all_text'					=> '',
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
			'equal_heights_grid'		=> '',
			'single_column_style'		=> '',
			'entry_media'				=> '',
		), $atts ) );

		// Turn output buffer on
		ob_start();

			// Don't create custom tax if tax doesn't exist
			if ( taxonomy_exists( 'portfolio_category' ) ) {

				// Include categories
				$include_categories = ( '' != $include_categories ) ? $include_categories : $term_slug;
				$include_categories = ( 'all' == $include_categories ) ? '' : $include_categories;
				$filter_cats_include = '';
				if ( $include_categories ) {
					$include_categories = explode( ',', $include_categories );
					$filter_cats_include = array();
					foreach ( $include_categories as $key ) {
						$key = get_term_by( 'slug', $key, 'portfolio_category' );
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
						$key = get_term_by( 'slug', $key, 'portfolio_category' );
						$filter_cats_exclude[] = $key->term_id;
					}
					$exclude_categories = array(
							'taxonomy'	=> 'portfolio_category',
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
						'taxonomy'	=> 'portfolio_category',
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
			
			// Pagination variables
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
					'post_type'			=> 'portfolio',
					'posts_per_page'	=> $posts_per_page,
					'offset'			=> $offset,
					'order'				=> $order,
					'orderby'			=> $orderby,
					'meta_key'			=> $meta_key,
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

				// Set unique ID
				$unique_id = $unique_id ? $unique_id : 'portfolio-'. rand( 1, 100 );

				// Image hard crop
				if ( '9999' == $img_height ) {
					$img_crop = false;
				} else {
					$img_crop = true;
				}

				// Is Isotope var
				if ( 'true' == $filter || 'masonry' == $grid_style || 'no_margins' == $grid_style ) {
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
				if ( 'true' == $filter && taxonomy_exists( 'portfolio_category' ) ) {
					// Get the terms for the filter
					$terms = get_terms( 'portfolio_category', array(
						'include'	=> $filter_cats_include,
						'exclude'	=> $filter_cats_exclude,
					) );
					// Display filter only if terms exist and there is more then 1
					if ( $terms && count( $terms ) > '1') {
						// Center filter links
						$center_filter = 'yes' == $center_filter ? 'center' : '';
						// All text
						if ( $all_text ) {
							$all_text = $all_text;
						} else {
							$all_text = __( 'All', 'wpex' );
						} ?>
						<ul class="vcex-portfolio-filter filter-<?php echo $unique_id; ?> vcex-filter-links <?php echo $center_filter; ?> clr">
							<li class="active"><a href="#" data-filter="*"><span><?php echo $all_text; ?></span></a></li>
							<?php foreach ($terms as $term ) : ?>
								<li><a href="#" data-filter=".cat-<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a></li>
							<?php endforeach; ?>
						</ul><!-- .vcex-portfolio-filter -->
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
					$heading_style .='margin: '. $content_heading_margin .';';
				}
				if ( $content_heading_size ) {
					$heading_style .='font-size: '. $content_heading_size .';';
				}
				if ( $content_heading_color ) {
					$heading_style .='color: '. $content_heading_color .';';
				}
				if ( $heading_style ) {
					$heading_style = 'style="'. $heading_style .'"';
				}

				// Readmore design
				if ( 'false' != $read_more ) {
					$read_more = 'true'; // Set readmore to true
					$readmore_style = '';
					if ( $readmore_background ) {
						$readmore_style .='background: '. $readmore_background .';';
					}
					if ( $readmore_color ) {
						$readmore_style .='color: '. $readmore_color .';';
					}
					if ( $readmore_style ) {
						$readmore_style = 'style="'. $readmore_style .'"';
					}
				}

				// Set title to true if not false
				if ( 'false' != $title ) {
					$title = 'true';
				}

				// Set excerpt to true if not false
				if ( 'false' != $excerpt ) {
					$excerpt = 'true';
				}
				// Default excerpt length
				if ( '' == $excerpt_length ) {
					$excerpt_length = '30';
				}
				// Set excerpt length to 0 if the excerpt is set to false
				elseif ( 'false' == $excerpt ){
					$excerpt_length = '0';
				}

				// Trim custom Excerpts?
				if ( 'false' == $custom_excerpt_trim ) {
					$custom_excerpt_trim = false;
				} else {
					$custom_excerpt_trim = true;
				}

				// Main wrap classes
				$wrap_classes = 'wpex-row vcex-portfolio-grid clr';

				// Equal heights class
				if ( '1' != $columns && ( 'fit_columns' == $grid_style && 'true' == $equal_heights_grid ) ) {
					$equal_heights_grid = true;
				} else {
					$equal_heights_grid = false;
				}

				// Isotope classes
				if ( $is_isotope ) {
					$wrap_classes .= ' vcex-isotope-grid';
				}

				// No margins grid
				if ( 'no_margins' == $grid_style ) {
					$wrap_classes .= ' vcex-no-margin-grid';
				}

				// Left thumbnail 1 column style
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

				// Data
				$data = '';
				if ( $is_isotope && 'true' == $filter) {
					if ( 'no_margins' != $grid_style && $masonry_layout_mode ) {
						$data .= ' data-layout-mode="'. $masonry_layout_mode .'"';
					}
					if ( $filter_speed ) {
						$data .= ' data-transition-duration="'. $filter_speed .'"';
					}
				}
				if ( 'no_margins' == $grid_style && 'true' != $filter ) {
					$data .= ' data-transition-duration="0.0"';
				} ?>
		
				<div class="<?php echo $wrap_classes; ?>" id="<?php echo $unique_id; ?>"<?php echo $data; ?>>
					<?php
					// Define counter var to clear floats
					$count = $count_all = '';

					// Start loop
					while ( $wpex_query->have_posts() ) :

						// Get post from query
						$wpex_query->the_post();

						// Open match-height-row div for equal heights
						if ( $equal_heights_grid && ! $is_isotope ) {
							if ( 0 == $count ) { ?>
								<div class="match-height-row clr">
							<?php }
							$count_all++;
						}

						// Post Data
						$post_id		= get_the_ID();
						$post_title		= get_the_title();
						$post_title_esc	= esc_attr( the_title_attribute( 'echo=0' ) );
						$post_permalink	= get_permalink( $post_id );

						// Add to the counter var
						$count++;

						// Add classes to the entries
						$entry_classes = 'portfolio-entry col';
						$entry_classes .= ' span_1_of_'. $columns;
						$entry_classes .= ' col-'. $count;
						if ( $is_isotope ) {
							$entry_classes .= ' vcex-isotope-entry';
						}
						if ( 'no_margins' == $grid_style ) {
							$entry_classes .= ' vcex-no-margin-entry';
						}
						if ( taxonomy_exists( 'portfolio_category' ) ) {
							$post_terms = get_the_terms( $post_id, 'portfolio_category' );
							if ( $post_terms ) {
								foreach ( $post_terms as $post_term ) {
									$entry_classes .= ' cat-'. $post_term->term_id;
								}
							}
						} ?>

						<div class="<?php echo $entry_classes; ?>">

							<?php
							// Entry Media
							if ( 'false' != $entry_media ) {
								// Video
								if ( function_exists( 'wpex_get_portfolio_featured_video_url' )
									&& wpex_get_portfolio_featured_video_url() ) { ?>
									<div class="portfolio-entry-media clr">
										<?php wpex_portfolio_post_video(); ?>
									</div>
								<?php }
								// Featured Image
								elseif ( has_post_thumbnail( $post_id ) ) {
									// Get cropped image
									$cropped_img = wpex_image_resize( wp_get_attachment_url( get_post_thumbnail_id() ), intval( $img_width ), intval( $img_height ), $img_crop, 'array' );
									// Filter style
									$img_filter_class = $img_filter ? 'vcex-'. $img_filter : '';
									// Media classes
									$media_classes = $img_filter_class;
									if ( $img_hover_style ) {
										$media_classes .= ' vcex-img-hover-parent vcex-img-hover-'. $img_hover_style;
									}
									if ( $img_rendering ) {
										$media_classes .= ' vcex-image-rendering-'. $img_rendering;
									}
									if ( $overlay_style ) {
										$media_classes .= ' '. wpex_overlay_classname( $overlay_style );
									} ?>
									<div class="portfolio-entry-media <?php echo $media_classes; ?>">
										<?php
										// No link
										if ( 'nowhere' == $thumb_link ) { ?>
											<img src="<?php echo $cropped_img['url']; ?>" alt="<?php echo $post_title; ?>" class="portfolio-entry-img" height="<?php echo $cropped_img['height']; ?>" width="<?php echo $cropped_img['width']; ?>" />
										<?php
										// Lightbox
										} elseif ( 'lightbox' == $thumb_link ) {
											// Display lightbox title
											$data =	 '';
											if ( 'true' == $thumb_lightbox_title 	) {
													$data = ' data-title="'. $post_title_esc .'"';
											}	 ?>
												<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" title="<?php echo $post_title_esc; ?>" class="portfolio-entry-media-link<?php echo $lightbox_single_class; ?>"<?php echo $data; ?>>
												<img src="<?php echo $cropped_img['url']; ?>" alt="<?php echo $post_title_esc; ?>" class="portfolio-entry-img" height="<?php echo $cropped_img['height']; ?>" width="<?php echo $cropped_img['width']; ?>" />
										<?php 
										// Standarad post link
										} else { ?>
												<a href="<?php echo $post_permalink; ?>" title="<?php echo $post_title_esc; ?>" class="portfolio-entry-media-link">
												<img src="<?php echo $cropped_img['url']; ?>" alt="<?php echo $post_title_esc; ?>" class="portfolio-entry-img" height="<?php echo $cropped_img['height']; ?>" width="<?php echo $cropped_img['width']; ?>" />
										<?php } ?>
											
										<?php
										// Close link and output overlay code
										if ( 'nowhere' != $thumb_link ) {
											// Inner Overlay
											if ( $overlay_style ) {
												wpex_overlay( 'inside_link', $overlay_style );
											}
											// Close links
											echo '</a>';
											// Outside Overlay
											if ( $overlay_style ) {
												wpex_overlay( 'outside_link', $overlay_style );
											}
										} ?>
									</div><!-- .portfolio-entry-media -->
								<?php } ?>
							<?php } ?>

							<?php
							// Display content if there is either a title or excerpt
							if ( 'true' == $title || 'true' == $excerpt ) { ?>
								<div class="portfolio-entry-details clr" <?php echo $content_style; ?>>
									<?php
									// Equal height div
									if ( $equal_heights_grid && ! $is_isotope ) { ?>
									<div class="match-height-content">
									<?php }
									// Display the title
									if ( 'false' != $title ) { ?>
										<h2 class="portfolio-entry-title" <?php echo $heading_style; ?>>
											<?php
											// Link title to post
											if ( 'post' == $title_link ) { ?>
												<a href="<?php echo $post_permalink; ?>" title="<?php echo $post_title_esc; ?>" <?php echo $heading_style; ?>><?php echo $post_title; ?></a>
											<?php }
											// Link title to lightbox
											elseif ( 'lightbox' == $title_link ) { ?>
												<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" title="<?php echo $post_title_esc; ?>" class="wpex-lightbox" <?php echo $heading_style; ?>><?php echo $post_title; ?></a>
											<?php }
											// Display title without link
											else {
												echo $post_title;
											} ?>
										</h2>
									<?php }
									// Display excerpt and readmore
									if ( 'true' ==  $excerpt || 'true' == $read_more ) {
										// Get post content
										$post_content = get_the_content(); ?>
										<div class="portfolio-entry-excerpt clr">
											<?php
											// Display full content
											if ( '9999' == $excerpt_length && $post_content ) {
												echo $post_content;
											}
											// Display Excerpt
											elseif ( function_exists( 'wpex_excerpt' ) ) {
												$args = array (
													'post_id'				=> $post_id,
													'length'				=> intval( $excerpt_length ),
													'trim_custom_excerpts'	=> $custom_excerpt_trim,
													'post_content'			=> $post_content,
													'post_excerpt'			=> get_the_excerpt(),
												);
												wpex_excerpt( $args );
											}
											// Return excerpt
											else {
												echo get_the_excerpt();
											}
											// Display Readmore
											if ( 'false' != $read_more ) {
												// Read more string fallback
												if ( '' == $read_more_text ) {
													$read_more_text = __( 'read more', 'wpex' );
												} ?>
												<a href="<?php echo $post_permalink; ?>" title="<?php echo $read_more_text; ?>" rel="bookmark" class="vcex-readmore theme-button" <?php echo $readmore_style; ?>>
													<?php echo $read_more_text; ?> <span class="vcex-readmore-rarr"><?php echo wpex_element( 'rarr' ); ?></span>
												</a>
											<?php } ?>
										</div>
									<?php }
									// Close Equal height div
									if ( $equal_heights_grid && ! $is_isotope ) { ?>
										</div>
									<?php } ?>
								</div><!-- .portfolio-entry-details -->
							<?php } ?>
						</div><!-- .portfolio-entry -->

						<?php
						// Check if counter equal columns
						if ( $count == $columns ) {
							// Close equal height row
							if ( $equal_heights_grid && ! $is_isotope ) {
								echo '</div><!-- .match-height-row -->';
							}
							// Reset counter
							$count = '';
						}
					
					// End post loop
					endwhile;

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

				</div><!-- .vcex-portfolio-grid -->
				
				<?php
				// Paginate Posts
				if ( 'true' == $pagination ) {
					wpex_pagination( $wpex_query );
				}

				// Restore original Post Data
				wp_reset_postdata();

			// End has posts check
			endif;

		// Return outbut buffer
		return ob_get_clean();

	}
}
add_shortcode( 'vcex_portfolio_grid', 'vcex_portfolio_grid_shortcode' );

if ( ! function_exists( 'vcex_portfolio_grid_shortcode_vc_map' ) ) {
	function vcex_portfolio_grid_shortcode_vc_map() {
		$vc_img_rendering_url = 'https://developer.mozilla.org/en-US/docs/Web/CSS/image-rendering';
		vc_map( array(
			'name'					=> __( 'Portfolio Grid', 'wpex' ),
			'description'			=> __( 'Recent portfolio posts grid.', 'wpex' ),
			'base'					=> 'vcex_portfolio_grid',
			'category'				=> WPEX_THEME_BRANDING,
			'icon' 					=> 'vcex-portfolio-grid',
			'params'				=> array(

				// General
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Unique Id', 'wpex' ),
					'param_name'	=> 'unique_id',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Columns', 'wpex' ),
					'param_name'	=> 'columns',
					'value' 		=> array(
						__( 'Six','wpex' )		=> '6',
						__( 'Five','wpex' )		=> '5',
						__( 'Four','wpex' )		=> '4',
						__( 'Three','wpex' )	=> '3',
						__( 'Two','wpex' )		=> '2',
						__( 'One','wpex' )		=> '1',
					),
					'std'			=> '4',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( '1 Column Style', 'wpex' ),
					'param_name'	=> 'single_column_style',
					'value'			=> array(
						__( 'Default', 'wpex')		=> '',
						__( 'Left/Right', 'wpex' )	=> 'left_thumbs',
					),
					'dependency'	=> array(
						'element'	=> 'columns',
						'value'		=> '1',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Grid Style', 'wpex' ),
					'param_name'	=> 'grid_style',
					'value'			=> array(
						__( 'Fit Columns', 'wpex' )	=> 'fit_columns',
						__( 'Masonry', 'wpex' )		=> 'masonry',
						__( 'No Margins', 'wpex' )	=> 'no_margins',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Equal Heights?', 'wpex' ),
					'param_name'	=> 'equal_heights_grid',
					'value'			=> array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex')	=> 'true',
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
					'heading'		=> __( 'Include Categories', 'wpex' ),
					'param_name'	=> 'include_categories',
					'admin_label'	=> true,
					'description'	=> __('Enter the slugs of a categories (comma seperated) to pull posts from or enter "all" to pull recent posts from all categories. Example: category-1, category-2.','wpex'),
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
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Posts Per Page', 'wpex' ),
					'param_name'	=> 'posts_per_page',
					'value'			=> '8',
					'group'			=> __( 'Query', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Pagination', 'wpex' ),
					'param_name'	=> 'pagination',
					'value'			=> array(
						__( 'No', 'wpex')	=> '',
						__( 'Yes', 'wpex' )	=> 'true',
					),
					'group'			=> __( 'Query', 'wpex' ),
				),

				// Filter
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Category Filter', 'wpex' ),
					'param_name'	=> 'filter',
					'value'			=> array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex')	=> 'true',
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
					'param_name'	=> 'filter_speed',
					'description'	=> __( 'Default is "0.4" seconds', 'wpex' ),
					'group'			=> __( 'Filter', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Center Filter Links', 'wpex' ),
					'param_name'	=> 'center_filter',
					'value'			=> array(
						__( 'No', 'wpex' )	=> '',
						__( 'Yes', 'wpex' )	=> 'yes',
					),
					'group'			=> __( 'Filter', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Custom Category Filter "All" Text', 'wpex' ),
					'param_name'	=> 'all_text',
					'group'			=> __( 'Filter', 'wpex' ),
				),

				// Images
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Entry Media?', 'wpex' ),
					'param_name'	=> 'entry_media',
					'value'			=> array(
						__( 'Yes', 'wpex')	=> '',
						__( 'No', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Image', 'wpex' ),
				),
				vcex_overlays_array(),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Image Crop Width', 'wpex' ),
					'param_name'	=> 'img_width',
					'value'			=> '9999',
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Image Crop Height', 'wpex' ),
					'param_name'	=> 'img_height',
					'value'			=> '9999',
					'description'	=> __( 'Custom image cropping height. Enter 9999 for no cropping (just resizing).', 'wpex' ),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Filter', 'wpex' ),
					'param_name'	=> 'img_filter',
					'value'			=> vcex_image_filters(),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "Image Rendering", 'wpex' ),
					'param_name'	=> "img_rendering",
					'value'			=> vcex_image_rendering(),
					'description'	=> sprintf( __( 'Image-rendering CSS property provides a hint to the user agent about how to handle its image rendering. For example when scaling down images they tend to look a bit fuzzy in Firefox, setting image-rendering to crisp-edges can help make the images look better. <a href="%s">Learn more</a>.', 'wpex' ), esc_url( $vc_img_rendering_url ) ),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( "CSS3 Image Hover", 'wpex' ),
					'param_name'	=> "img_hover_style",
					'value'			=> vcex_image_hovers(),
					'description'	=> __("Select your preferred image hover effect. Please note this will only work if the image links to a URL or a large version of itself. Please note these effects may not work in all browsers.", "wpex"),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Image Links To', 'wpex' ),
					'param_name'	=> 'thumb_link',
					'value'			=> array(
						__( 'Post', 'wpex')			=> '',
						__( 'Lightbox', 'wpex' )	=> 'lightbox',
						__( 'Nowhere', 'wpex' )		=> 'nowhere',
					),
					'group'			=> __( 'Image', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Lightbox Gallery', 'wpex' ),
					'param_name'	=> 'thumb_lightbox_gallery',
					'value'			=> array(
						__( 'No', 'wpex')	=> '',
						__( 'Yes', 'wpex' )	=> 'true',
					),
					'group'			=> __( 'Image', 'wpex' ),
					'dependency'	=> array(
						'element'	=> 'thumb_link',
						'value'		=> 'lightbox',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Display Title In Lightbox', 'wpex' ),
					'param_name'	=> 'thumb_lightbox_title',
					'value'			=> array(
						__( 'No', 'wpex')		=> '',
						__( 'Yes', 'wpex' )	=> 'true',
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
					'heading'		=> __( 'Title', 'wpex' ),
					'param_name'	=> 'title',
					'value'			=> array(
						__( 'Yes', 'wpex')	=> '',
						__( 'No', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Title Links To', 'wpex' ),
					'param_name'	=> 'title_link',
					'value'			=> array(
						__( 'Post', 'wpex')		=> '',
						__( 'Lightbox', 'wpex')	=> 'lightbox',
						__( 'Nowhere', 'wpex' )	=> 'nowhere',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Excerpt', 'wpex' ),
					'param_name'	=> 'excerpt',
					'value'			=> array(
						__( 'Yes', 'wpex')	=> '',
						__( 'No', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Custom Excerpt Length', 'wpex' ),
					'param_name'	=> 'excerpt_length',
					'group'			=> __( 'Content', 'wpex' ),
					'std'			=> '30',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Trim Custom Excerpts', 'wpex' ),
					'param_name'	=> 'custom_excerpt_trim',
					'value'			=> array(
						__( 'Yes', 'wpex' )	=> '',
						__( 'No', 'wpex' )	=> 'false',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Read More', 'wpex' ),
					'param_name'	=> 'read_more',
					'std'			=> 'false',
					'value'			=> array(
						__( 'No', 'wpex')	=> 'false',
						__( 'Yes', 'wpex' )	=> '',
					),
					'group'			=> __( 'Content', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Read More Text', 'wpex' ),
					'param_name'	=> 'read_more_text',
					'group'			=> __( 'Content', 'wpex' ),
				),

				// Design
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Title Text Color', 'wpex' ),
					'param_name'	=> 'content_heading_color',
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
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Content Read More Background', 'wpex' ),
					'param_name'	=> 'readmore_background',
					'group'			=> __( 'Design', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Content Read More Color', 'wpex' ),
					'param_name'	=> 'readmore_color',
					'group'			=> __( 'Design', 'wpex' ),
				),

			),
		) );
	}
}
add_action( 'vc_before_init', 'vcex_portfolio_grid_shortcode_vc_map' );