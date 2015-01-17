<?php
/**
 * Used for your site wide breadcrumbs
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 * @version		1.0.0
 */

/**
 * Returns correct breadcrumbs function
 *
 * @since Total 1.5.2
 */
if ( ! function_exists( 'wpex_breadcrumbs_enabled' ) ) {
	function wpex_breadcrumbs_enabled( $post_id = '' ) {

		// Check if disabled by theme options
		if ( ! get_theme_mod( 'breadcrumbs', true ) ) {
			return false;
		}

		// Check if disabled by meta options
		if ( $post_id && 'on' == get_post_meta( $post_id, 'wpex_disable_breadcrumbs', true ) ) {
			return false;
		}

		// Return true
		return true;

	}
}

/**
 * Returns correct breadcrumbs function
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_display_breadcrumbs' ) ) {
	function wpex_display_breadcrumbs( $post_id = '' ) {

		// Get post id
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Check if breadcrumbs are disabled
		if ( ! wpex_breadcrumbs_enabled( $post_id ) ) {
			return;
		}
		
		// Yoast breadcrumbs
		$yoast_options = get_option( 'wpseo_internallinks' );
		if ( function_exists( 'yoast_breadcrumb' ) && $yoast_options[ 'breadcrumbs-enable' ] === true ) {
			return yoast_breadcrumb( '<nav class="site-breadcrumbs clr">', '</nav>' );
		}

		// Built-in breadcrumbs
		else {
			echo wpex_breadcrumbs( $post_id );
		}
		
	}
}

/**
 * Breadcrumbs class
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_breadcrumbs' ) ) {
	function wpex_breadcrumbs( $post_id = '' ) {
		
		// Globals
		global $wp_query, $wp_rewrite;

		// Check if things are enabled
		$woocommerce_is_active = WPEX_WOOCOMMERCE_ACTIVE;

		// Get post id
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Define main variables
		$breadcrumb			= '';
		$trail				= array();
		$path				= '';
		$item_type_scope	= 'itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"';

		// Default arguments
		$args = array(
			'separator'			=> wpex_element( 'angle_right' ),
			'front_page'		=> false,
			'echo'				=> false,
			'show_posts_page'	=> true,
		);

		// Extract args for easy variable naming.
		extract( $args );

		/*-----------------------------------------------------------------------------------*/
		/*	- If not on the front page of the site, link to the home page
		/*-----------------------------------------------------------------------------------*/
		if ( ! is_front_page() ) {
			if ( $home_title = get_theme_mod( 'breadcrumbs_home_title' ) ) {
				$home_title = wpex_translate_theme_mod( 'breadcrumbs_home_title', $home_title );
			} else {
				$home_title = __( 'Home', 'wpex' );
			}
			$trail[] = '<span '. $item_type_scope .'><a href="' . home_url() . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home" class="trail-begin"><span itemprop="title">' . $home_title . '</span></a></span>';
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Front Page
		/*-----------------------------------------------------------------------------------*/
		if ( is_front_page() ) {
			if ( ! $front_page ) {
				$trail = false;
			} elseif ( $show_home ) {
				$trail['trail_end'] = "{$show_home}";
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Homepage or posts page
		/*-----------------------------------------------------------------------------------*/
		elseif ( is_home() ) {
			$home_page			= get_page( $wp_query->get_queried_object_id() );
			$trail				= array_merge( $trail, wpex_breadcrumbs_get_parents( $home_page->post_parent, '' ) );
			$trail['trail_end']	= get_the_title( $home_page->ID );
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Singular: Page, Post, Attachment...etc
		/*-----------------------------------------------------------------------------------*/
		elseif ( is_singular() ) {
			
			// Get singular vars
			$post		= $wp_query->get_queried_object();
			$post_id	= absint( $wp_query->get_queried_object_id() );
			$post_type	= $post->post_type;
			$parent		= $post->post_parent;
			
			// If a custom post type, check if there are any pages in its hierarchy based on the slug.
			if ( ! in_array( $post_type, array( 'page', 'post', 'product', 'portfolio', 'staff', 'testimonials' ) ) ) {

				$post_type_object = get_post_type_object( $post_type );
				
				// Add $front to the path
				if ( 'post' == $post_type || 'attachment' == $post_type || ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front ) ) {
					$path .= trailingslashit( $wp_rewrite->front );
				}

				// Add slug to $path
				if ( ! empty( $post_type_object->rewrite['slug'] ) ) {
					$path .= $post_type_object->rewrite['slug'];
				}

				// If $path exists check for parents
				if ( ! empty( $path ) ) {
					$trail = array_merge( $trail, wpex_breadcrumbs_get_parents( '', $path ) );
				}

				// If archive page exists add to trail
				if ( ! empty( $post_type_object->has_archive ) && function_exists( 'get_post_type_archive_link' ) ) {
					if ( ! is_singular( 'product' ) ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-type-archive"><a href="' . get_post_type_archive_link( $post_type ) . '" title="' . esc_attr( $post_type_object->labels->name ) . '"><span itemprop="title">' . $post_type_object->labels->name . '</span></a></span>';
					}
				}
			}
			
			// Add shop page to cart
			if ( is_singular( 'page' ) && $woocommerce_is_active ) {
				if ( is_cart() || is_checkout() ) {
					// Get shop page
					if ( function_exists( 'wc_get_page_id' ) ) {
						$shop_id		= wc_get_page_id( 'shop' );
						$shop_page_url	= get_permalink( $shop_id );
						$shop_title		= get_the_title( $shop_id );
						if ( function_exists( 'icl_object_id' ) ) {
							$shop_title = get_the_title( icl_object_id( $shop_id, 'page' ) );
						}
						$shop_title		= apply_filters( 'wpex_bcrums_shop_title', $shop_title );
					}

					// Shop page
					if ( $shop_id && $shop_title ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-type-archive"><a href="' . get_permalink( $shop_id ) . '" title="' . esc_attr( $shop_title ) . '"><span itemprop="title">' . $shop_title . '</span></a></span>';
					}
				}
			}

			// Add cart to checkout
			if ( $woocommerce_is_active && function_exists( 'is_checkout' ) && is_checkout() ) {
				$cart_id	= wc_get_page_id( 'cart' );
				$cart_title	= get_the_title( $cart_id );
				if ( $cart_id ) {
					$trail[] = '<span '. $item_type_scope .' class="trail-type-archive"><a href="' . get_permalink( $cart_id ) . '" title="' . esc_attr( $cart_title ) . '"><span itemprop="title">' . $cart_title . '</span></a></span>';
				}
			}

			// Standard Posts
			if ( 'post' == $post_type ) {

				// Main Blog URL
				if ( $blog_page	= get_theme_mod( 'blog_page' ) ) {
					$blog_url	= get_permalink( $blog_page );
					$blog_name	= get_the_title( $blog_page );
					if ( function_exists( 'icl_object_id' ) ) {
						$blog_name = get_the_title( icl_object_id( $blog_page, 'page' ) );
					}
					$trail[] = '<span '. $item_type_scope .' class="trail-blog-url"><a href="'. $blog_url .'" title="'. $blog_name .'" itemprop="url"><span itemprop="title">'. $blog_name .'</span></a></span>';
				}

				// 1st Category
				if ( get_theme_mod( 'breadcrumbs_blog_cat', '1' ) ) {
					$terms = get_the_terms( $post_id, 'category');
					if ( $terms ) {
						$term = reset($terms);
						$trail[] = '<span '. $item_type_scope .' class="trail-blog-cat"><a href="'. get_term_link($term) .'" itemprop="url" title="'. $term->name .'"><span itemprop="title">'. $term->name .'</span></a></span>';
					}
				}
			}

			// Tribe Events
			if ( 'tribe_events' == $post_type && function_exists( 'tribe_get_events_link' ) ) {
				$trail[] = '<span '. $item_type_scope .' class="trail-portfolio-url"><a href="'. tribe_get_events_link() .'" title="'. __( 'All Events', 'wpex' ) .'"><span itemprop="title">'. __( 'All Events', 'wpex' ) .'</span></a></span>';
			}
			
			//  Main Portfolio
			if ( $post_type == 'portfolio' ) {

				if ( $portfolio_page = get_theme_mod( 'portfolio_page' ) ) {
					$portfolio_url	= get_permalink( $portfolio_page );
					$portfolio_name	= get_the_title( $portfolio_page );
					if ( function_exists( 'icl_object_id' ) ) {
						$portfolio_name = get_the_title( icl_object_id( $portfolio_page, 'page' ) );
					}
					if ( $portfolio_url ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-portfolio-url"> <a href="'. $portfolio_url .'" title="'. $portfolio_name .'"><span itemprop="title">'. $portfolio_name .'</span></a></span>';
					}
				}

				// Portfolio Categories
				if ( get_theme_mod( 'breadcrumbs_portfolio_cat', true )
					&& taxonomy_exists( 'portfolio_category' )
					&& function_exists( 'wpex_list_post_terms' ) ) {
					if ( $terms = wpex_list_post_terms( $taxonomy = 'portfolio_category', $show_links = true, $echo = false ) ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-post-categories">' . $terms .'</span>';
					}
				}
			}
			
			//  Main Staff
			if ( $post_type == 'staff' ) {

				// Display staff page
				if ( $staff_page = get_theme_mod( 'staff_page' ) ) {
					$staff_url	= get_permalink( $staff_page );
					$staff_name	= get_the_title( $staff_page );
					if ( function_exists( 'icl_object_id' ) ) {
						$staff_name = get_the_title( icl_object_id( $staff_page, 'page' ) );
					}
					if ( $staff_url ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-staff-url"><a href="'. $staff_url .'" title="'. $staff_name .'"><span itemprop="title">'. $staff_name .'</span></a></span>';
					}
				}

				// Staff Categories
				if ( get_theme_mod( 'breadcrumbs_staff_cat', true )
					&& taxonomy_exists( 'staff_category' )
					&& function_exists( 'wpex_list_post_terms' ) ) {
					if ( $terms = wpex_list_post_terms( $taxonomy = 'staff_category', $show_links = true, $echo = false ) ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-post-categories">' . $terms .'</span>';
					}
				}
			}
			
			//  Main Testimonials
			if ( $post_type == 'testimonials' ) {

				// Display testimonials page
				if ( $testimonials_page = get_theme_mod( 'testimonials_page' ) ) {
					$testimonials_url	= get_permalink( $testimonials_page );
					$testimonials_name	= get_the_title( $testimonials_page );
					if ( function_exists( 'icl_object_id' ) ) {
						$testimonials_name = get_the_title( icl_object_id( $testimonials_page, 'page' ) );
					}
					if ( $testimonials_url ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-testimonials-url"><a href="'. $testimonials_url .'" title="'. $testimonials_name .'"><span itemprop="title">'. $testimonials_name .'</span></a></span>';
					}
				}

				// Testimonials Categories
				if ( get_theme_mod( 'breadcrumbs_testimonials_cat', true )
					&& taxonomy_exists( 'testimonials_category' )
					&& function_exists( 'wpex_list_post_terms' ) ) {
					if ( $terms = wpex_list_post_terms( $taxonomy = 'testimonials_category', $show_links = true, $echo = false ) ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-post-categories">' . $terms .'</span>';
					}
				}
			}

			// Products
			if ( is_singular( 'product' ) && $woocommerce_is_active ) {
				
				// Globals
				global $woocommerce;

				// Get shop page
				if ( $woocommerce_is_active && function_exists( 'wc_get_page_id' ) ) {
					$shop_id		= wc_get_page_id( 'shop' );
					$shop_page_url	= get_permalink( $shop_id );
					$shop_title		= get_the_title( $shop_id );
					if ( function_exists( 'icl_object_id' ) ) {
						$shop_title = get_the_title( icl_object_id( $shop_id, 'page' ) );
					}
					$shop_title		= apply_filters( 'wpex_bcrums_shop_title', $shop_title );
				}

				// Shop page
				if ( $shop_id && $shop_title ) {
					$trail[] = '<span '. $item_type_scope .'><a href="' . get_permalink( $shop_id ) . '" title="' . esc_attr( $shop_title ) . '"><span itemprop="title">' . $shop_title . '</span></a></span>';
				}

				// Categories
				if ( function_exists( 'wpex_list_post_terms' ) ) {
					if ( $terms = wpex_list_post_terms( $taxonomy = 'product_cat', $show_links = true, $echo = false ) ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-post-categories">' . $terms .'</span>';
					}
				}

				// Cart page
				if ( sizeof( $woocommerce->cart->cart_contents ) > 0 ) {
					$cart_id = wc_get_page_id( 'cart' );
					$cart_title = get_the_title( $cart_id );
					if ( $cart_id ) {
						$trail[] = '<span '. $item_type_scope .'><a href="' . get_permalink( $cart_id ) . '" title="' . esc_attr( $cart_title ) . '"><span itemprop="title">' . $cart_title . '</span></a></span>';
					}
				}
			}

			// If the post type path returns nothing and there is a parent, get its parents.
			if ( empty( $path ) && 0 !== $parent || 'attachment' == $post_type ) {
				$trail = array_merge( $trail, wpex_breadcrumbs_get_parents( $parent, '' ) );
			}


			// End trail with post title
			$post_title = get_the_title( $post_id );
			if ( ! empty( $post_title ) ) {
				if ( $trim_title = get_theme_mod( 'breadcrumbs_title_trim', '4' ) ) {
					$post_title = wp_trim_words( $post_title, $trim_title );
					$trail['trail_end'] = $post_title;
				} else {
					$trail['trail_end'] = $post_title;
				}
			}

		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Archives
		/*-----------------------------------------------------------------------------------*/
		elseif ( is_archive() ) {

			// Add cart to shop
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				global $woocommerce;
				if ( sizeof( $woocommerce->cart->cart_contents ) > 0 ) {
					$cart_id	= wc_get_page_id( 'cart' );
					$cart_title	= get_the_title( $cart_id );
					if ( $cart_id ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-type-archive"><a href="' . get_permalink( $cart_id ) . '" title="' . esc_attr( $cart_title ) . '"><span itemprop="title">' . $cart_title . '</span></a></span>';
					}
				}
			}
			
			// Topics
			if ( is_post_type_archive( 'topic' ) ) {
				$forums_link	= get_post_type_archive_link('forum');
				$forum_obj		= get_post_type_object( 'forum' );
				$forum_name		= $forum_obj->labels->name;
				if ( $forums_link ) {
					$trail[] = '<span '. $item_type_scope .'><a href="'. $forums_link .'" title="'. $forum_name .'">'. $forum_name .'</a></span>';
				}
			}

			/*-----------------------------------------------------------------------------------*/
			/*	- Taxonomies
			/*-----------------------------------------------------------------------------------*/
			if ( is_tax() || is_category() || is_tag() ) {

				// Get some taxonomy variables
				$term		= $wp_query->get_queried_object();
				$taxonomy	= get_taxonomy( $term->taxonomy );
				
				// Link to main portfolio page
				if ( function_exists( 'wpex_is_portfolio_tax' ) && wpex_is_portfolio_tax() && $portfolio_page = get_theme_mod( 'portfolio_page' ) ) {
					$portfolio_url	= get_permalink( $portfolio_page );
					$portfolio_name	= get_the_title( $portfolio_page );
					if ( function_exists( 'icl_object_id' ) ) {
						$portfolio_name = get_the_title( icl_object_id( $portfolio_page, 'page' ) );
					}
					if ( $portfolio_url ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-portfolio-url"><a href="'. $portfolio_url .'" title="'. $portfolio_name .'"><span itemprop="title">'. $portfolio_name .'</span></a></span>';
					}
				}
				
				// Link to main staff page
				if ( function_exists( 'wpex_is_staff_tax' ) && wpex_is_staff_tax() && $staff_page = get_theme_mod( 'staff_page' ) ) {
					$staff_url	= get_permalink( $staff_page );
					$staff_name	= get_the_title( $staff_page );
					if ( function_exists( 'icl_object_id' ) ) {
						$staff_name = get_the_title( icl_object_id( $staff_page, 'page' ) );
					}
					if ( $staff_url ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-staff-url"><a href="'. $staff_url .'" title="'. $staff_name .'"><span itemprop="title">'. $staff_name .'</span></a></span>';
					}
				}
				
				
				// Testimonials Tax
				if ( function_exists( 'wpex_is_testimonials_tax' ) && wpex_is_testimonials_tax() && $testimonials_page = get_theme_mod( 'testimonials_page' ) ) {
					$testimonials_url	= get_permalink( $testimonials_page );
					$testimonials_name	= get_the_title( $testimonials_page );
					if ( function_exists( 'icl_object_id' ) ) {
						$testimonials_name = get_the_title( icl_object_id( $testimonials_page, 'page' ) );
					}
					if ( $testimonials_url ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-testimonials-url"><a href="'. $testimonials_url .'" title="'. $testimonials_name .'"><span itemprop="title">'. $testimonials_name .'</span></a></span>';
					}
				}

				// Woo Product Tax
				if ( function_exists( 'wpex_is_woo_tax' ) && wpex_is_woo_tax() ) {
					// Get shop page
					if ( $woocommerce_is_active && function_exists( 'wc_get_page_id' ) ) {
						$shop_id		= wc_get_page_id( 'shop' );
						$shop_page_url	= get_permalink( $shop_id );
						$shop_title		= get_the_title( $shop_id );
						if ( function_exists( 'icl_object_id' ) ) {
							$shop_title = get_the_title( icl_object_id( $shop_id, 'page' ) );
						}
						$shop_title		= apply_filters( 'wpex_bcrums_shop_title', $shop_title );
					}
					if ( $shop_page_url && $shop_title ) {
						$trail[] = '<span '. $item_type_scope .' class="trail-shop"><a href="'. $shop_page_url .'" title="'. $shop_title .'" itemprop="url"><span itemprop="title">'. $shop_title .'</span></a></span>';
					}
				}

				// Display main blog page on Categories & archives
				if ( is_category() || is_tag() ) {
					if ( $blog_page	= get_theme_mod( 'blog_page' ) ) {
						$blog_url	= get_permalink( $blog_page );
						$blog_name	= get_the_title( $blog_page );
						if ( function_exists( 'icl_object_id' ) ) {
							$blog_name = get_the_title( icl_object_id( $blog_page, 'page' ) );
						}
						$trail[] = '<span '. $item_type_scope .' class="trail-blog-url"><a href="'. $blog_url .'" title="'. $blog_name .'" itemprop="url"><span itemprop="title">'. $blog_name .'</span></a></span>';
					}
				}

				// Get the path to the term archive. Use this to determine if a page is present with it.
				if ( is_category() ) {
					$path = get_option( 'category_base' );
				} elseif ( is_tag() ) {
					$path = get_option( 'tag_base' );
				} else {
					if ( $taxonomy->rewrite['with_front'] && $wp_rewrite->front ) {
						$path = trailingslashit( $wp_rewrite->front );
					}
					$path .= $taxonomy->rewrite['slug'];
				}

				// Get parent pages if they exist
				if ( $path ) {
					$trail = array_merge( $trail, wpex_breadcrumbs_get_parents( '', $path ) );
				}

				// Add term parents
				if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent ) {
					$trail = array_merge( $trail, wpex_breadcrumbs_get_term_parents( $term->parent, $term->taxonomy ) );
				}

				// Add term name to trail end
				$trail['trail_end'] = $term->name;
			}

			// Post type archive
			elseif ( is_post_type_archive() ) {

				// Get post type object
				$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );

				// Add $front to $path
				if ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front )
					$path .= trailingslashit( $wp_rewrite->front );

				// Add slug to 4path
				if ( ! empty( $post_type_object->rewrite['archive'] ) )
					$path .= $post_type_object->rewrite['archive'];

				// If patch exists check for parents
				if ( ! empty( $path ) )
					$trail = array_merge( $trail, wpex_breadcrumbs_get_parents( '', $path ) );

				// Add post type name to trail end
				$trail['trail_end'] = $post_type_object->labels->name;
			}

			// Author archive
			elseif ( is_author() ) {

				/* If $front has been set, add it to $path. */
				if ( ! empty( $wp_rewrite->front ) )
					$path .= trailingslashit( $wp_rewrite->front );

				/* If an $author_base exists, add it to $path. */
				if ( ! empty( $wp_rewrite->author_base ) )
					$path .= $wp_rewrite->author_base;

				/* If $path exists, check for parent pages. */
				if ( ! empty( $path ) )
					$trail = array_merge( $trail, wpex_breadcrumbs_get_parents( '', $path ) );

				/* Add the author's display name to the trail end. */
				$trail['trail_end'] = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
			}

			// Time archive
			elseif ( is_time() ) {

				if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
					$trail['trail_end'] = get_the_time( __( 'g:i a', 'wpex' ) );

				elseif ( get_query_var( 'minute' ) )
					$trail['trail_end'] = sprintf( __( 'Minute %1$s', 'wpex' ), get_the_time( __( 'i', 'wpex' ) ) );

				elseif ( get_query_var( 'hour' ) )
					$trail['trail_end'] = get_the_time( __( 'g a', 'wpex' ) );
			}

			// Date archive
			elseif ( is_date() ) {

				// If $front is set check for parents
				if ( $wp_rewrite->front )
					$trail = array_merge( $trail, wpex_breadcrumbs_get_parents( '', $wp_rewrite->front ) );

				if ( is_day() ) {
					$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'wpex' ) ) . '">' . get_the_time( __( 'Y', 'wpex' ) ) . '</a>';
					$trail[] = '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" title="' . get_the_time( esc_attr__( 'F', 'wpex' ) ) . '">' . get_the_time( __( 'F', 'wpex' ) ) . '</a>';
					$trail['trail_end'] = get_the_time( __( 'j', 'wpex' ) );
				}

				elseif ( get_query_var( 'w' ) ) {
					$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'wpex' ) ) . '">' . get_the_time( __( 'Y', 'wpex' ) ) . '</a>';
					$trail['trail_end'] = sprintf( __( 'Week %1$s', 'wpex' ), get_the_time( esc_attr__( 'W', 'wpex' ) ) );
				}

				elseif ( is_month() ) {
					$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'wpex' ) ) . '">' . get_the_time( __( 'Y', 'wpex' ) ) . '</a>';
					$trail['trail_end'] = get_the_time( __( 'F', 'wpex' ) );
				}

				elseif ( is_year() ) {
					$trail['trail_end'] = get_the_time( __( 'Y', 'wpex' ) );
				}
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Search
		/*-----------------------------------------------------------------------------------*/
		elseif ( is_search() ) {
			$trail['trail_end'] = sprintf( __( 'Search results for &quot;%1$s&quot;', 'wpex' ), esc_attr( get_search_query() ) );
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- 404
		/*-----------------------------------------------------------------------------------*/
		elseif ( is_404() ) {
			$trail['trail_end'] = get_theme_mod( 'error_page_title' ) ? get_theme_mod( 'error_page_title' ) : __( '404 Not Found', 'wpex' );
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Tribe Calendar Month
		/*-----------------------------------------------------------------------------------*/
		elseif ( function_exists( 'tribe_is_month' ) && tribe_is_month() ) {
			$trail['trail_end'] = __( 'Events Calendar', 'wpex' );
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Create and return the breadcrumbs
		/*-----------------------------------------------------------------------------------*/
		if ( $trail && is_array( $trail ) ) {

			$classes = 'site-breadcrumbs clr';
			if ( $breadcrumbs_position = get_theme_mod( 'breadcrumbs_position' ) ) {
				$classes .= ' position-'. $breadcrumbs_position;
			}

			// Open Breadcrumbs
			$breadcrumb = '<nav class="'. $classes .'"><div class="breadcrumb-trail">';

			// Seperator HTML
			$separator = '<span class="sep"> ' . $separator . ' </span>';

			// Join all trail items into a string
			$breadcrumb .= implode( $separator, $trail );

			// Close breadcrumbs
			$breadcrumb .= '</div></nav>';

		}

		// Return the breadcrumbs trail
		return $breadcrumb;

	}
}

/**
 * Breadcrumbs Parent links
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_breadcrumbs_get_parents' ) ) {
	function wpex_breadcrumbs_get_parents( $post_id = '', $path = '' ) {

		// Set up an empty trail array.
		$trail = array();

		// Return if it's a theme post type page
		if ( is_singular( 'staff' ) || is_singular( 'portfolio' ) || is_singular( 'testimonials' ) ) {
			return $trail;
		}

		// If neither a post ID nor path set, return an empty array.
		if ( empty( $post_id ) && empty( $path ) ) {
			return $trail;
		}

		// If the post ID is empty, use the path to get the ID.
		if ( empty( $post_id ) ) {

			// Get parent post by the path.
			$parent_page = get_page_by_path( $path );


			if ( empty( $parent_page ) ) {
				// search on page name (single word)
				$parent_page = get_page_by_title ( $path );
			}

			if ( empty( $parent_page ) ) {
				// search on page title (multiple words)
				$parent_page = get_page_by_title ( str_replace( array('-', '_'), ' ', $path ) );
			}

			// If a parent post is found, set the $post_id variable to it.
			if ( ! empty( $parent_page ) ) {
				$post_id = $parent_page->ID;
			}
		}

		// If a post ID and path is set, search for a post by the given path.
		if ( $post_id == 0 && ! empty( $path ) ) {

			// Separate post names into separate paths by '/'.
			$path = trim( $path, '/' );
			preg_match_all( "/\/.*?\z/", $path, $matches );

			// If matches are found for the path.
			if ( isset( $matches ) ) {

				// Reverse the array of matches to search for posts in the proper order.
				$matches = array_reverse( $matches );

				// Loop through each of the path matches.
				foreach ( $matches as $match ) {

					// If a match is found.
					if ( isset( $match[0] ) ) {

						// Get the parent post by the given path.
						$path = str_replace( $match[0], '', $path );
						$parent_page = get_page_by_path( trim( $path, '/' ) );

						// If a parent post is found, set the $post_id and break out of the loop.
						if ( ! empty( $parent_page ) && $parent_page->ID > 0 ) {
							$post_id = $parent_page->ID;
							break;
						}
					}
				}
			}
		}

		// While there's a post ID, add the post link to the $parents array. */
		while ( $post_id ) {

			// Get the post by ID.
			$page = get_page( $post_id );

			// Add the formatted post link to the array of parents.
			$parents[]  = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . get_the_title( $post_id ) . '</a>';

			// Set the parent post's parent to the post ID.
			$post_id = $page->post_parent;
		}

		// If we have parent posts, reverse the array to put them in the proper order for the trail.
		if ( isset( $parents ) ) {
			$trail = array_reverse( $parents );
		}

		// Return the trail of parent posts.
		return $trail;

	}
}

/**
 * Breadcrumbs Term Parents
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_breadcrumbs_get_term_parents' ) ) {
	function wpex_breadcrumbs_get_term_parents( $parent_id = '', $taxonomy = '' ) {

		// Set up some default arrays.
		$trail		= array();
		$parents	= array();

		// If no term parent ID or taxonomy is given, return an empty array.
		if ( empty( $parent_id ) || empty( $taxonomy ) ) {
			return $trail;
		}

		// While there is a parent ID, add the parent term link to the $parents array.
		while ( $parent_id ) {

			// Get the parent term.
			$parent = get_term( $parent_id, $taxonomy );

			// Add the formatted term link to the array of parent terms.
			$parents[] = '<a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( $parent->name ) . '">' . $parent->name . '</a>';

			// Set the parent term's parent as the parent ID.
			$parent_id = $parent->parent;
		}

		// If we have parent terms, reverse the array to put them in the proper order for the trail.
		if ( ! empty( $parents ) ) {
			$trail = array_reverse( $parents );
		}

		// Return the trail of parent terms.
		return $trail;

	}
}