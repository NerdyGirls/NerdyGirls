<?php
/**
 * All page header functions
 *
 * @package		Total
 * @subpackage	Functions
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 * @version 	1.0.1
 */

/**
 * Get current page header style
 * Needs to be added first because it's used in multiple functions
 *
 * @since Total 1.5.4
 */
if ( ! function_exists( 'wpex_page_header_style' ) ) {
	function wpex_page_header_style( $post_id = '' ) {
		if ( $meta = get_post_meta( $post_id, 'wpex_post_title_style', true ) ) {
			$style = $meta;
		} else {
			$style = get_theme_mod( 'page_header_style' );
		}
		if ( 'default' == $style ) {
			$style = '';
		}
		$style = apply_filters( 'wpex_page_header_style', $style );
		return $style;
	}
}

/**
 * Checks if the page header (title) should display
 *
 * @since Total 1.5.2
 * @return bool
 */
if ( ! function_exists( 'wpex_is_page_header_enabled' ) ) {
	function wpex_is_page_header_enabled( $post_id = NULL ) {

		// Get post ID if needed
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Return true by default
		$return	= true;

		// Disabled for author archives
		if ( is_author() ) {
			$return	= false;
		}

		// Return if disabled on the store via the admin panel
		elseif ( is_post_type_archive( 'product' ) ) {
			if ( ! get_theme_mod( 'woo_shop_title', true ) ) {
				$return	= false;
			}
		}

		// Return if page header is disabled via custom field
		elseif ( $post_id ) {

			// Get title style
			$title_style = wpex_page_header_style( $post_id );

			// Return if page header is disabled and there isn't a page header background defined
			if ( 'on' == get_post_meta( $post_id, 'wpex_disable_title', true ) && 'background-image' != $title_style ) {
				$return	= false;
			}

		}

		// Apply filters
		$return	= apply_filters( 'wpex_display_page_header', $return );

		// Return bool
		return $return;
	}
}

/**
 * Returns the page header template part
 *
 * @since Total 1.5.2
 * @return bool
 */
if ( ! function_exists( 'wpex_display_page_header' ) ) {
	function wpex_display_page_header( $post_id = NULL ) {
		$post_id = $post_id ? $post_id : wpex_get_the_id();
		if ( wpex_is_page_header_enabled( $post_id ) ) {
			get_template_part( 'partials/page', 'header' );
		}
	}
}

/**
 * Returns the correct title to display
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_page_title' ) ) {
	function wpex_page_title( $post_id = '' ) {
		
		// Get post ID
		$post_id = $post_id ? $post_id : wpex_get_the_id();
		
		// Homepage - display blog description if not a static page
		if ( is_front_page() && ! is_singular( 'page' ) ) {
			
			if ( get_bloginfo( 'description' ) ) {
				$title = get_bloginfo( 'description' );
			} else {
				return __( 'Recent Posts', 'wpex' );
			}

		// Homepage posts page
		} elseif ( is_home() && ! is_singular( 'page' ) ) {

			$title = get_the_title( get_option( 'page_for_posts', true ) );
			
		// Archives
		} elseif ( is_archive() ) {
			
			// Daily archive title
			if ( is_day() ) {
				$title = sprintf( __( 'Daily Archives: %s', 'wpex' ), get_the_date() );
			
			// Monthly archive title
			} elseif ( is_month() ) {
				$title = sprintf( __( 'Monthly Archives: %s', 'wpex' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'wpex' ) ) );
				
			// Yearly archive title
			} elseif ( is_year() ) {
				$title = sprintf( __( 'Yearly Archives: %s', 'wpex' ), get_the_date( _x( 'Y', 'yearly archives date format', 'wpex' ) ) );

			// Post Type archive title
			} elseif ( is_post_type_archive() ) {

				if ( is_post_type_archive( 'product' ) ) {
					if ( class_exists( 'Woocommerce' ) && function_exists( 'wc_get_page_id' ) ) {
						$title = get_the_title( wc_get_page_id( 'shop' ) );
					} else {
						$title = __( 'Shop', 'wpex' );
					}
				} else {
					$title = post_type_archive_title( '', false );
				}
			
			// Standard term title
			} else {
				$title = single_term_title( '', false );
				// Fix for bbPress and other plugins that are archives but use
				if ( ! $title ) {
					global $post;
					$title = get_the_title( $post_id );
				}
			}
		// Search
		} elseif ( is_search() ) {
			global $wp_query;
			$title = '<span id="search-results-count">'. $wp_query->found_posts .'</span> '. __( 'Search Results Found', 'wpex' );
		
		// 404 Page
		} elseif ( is_404() ) {

			$title = get_theme_mod( 'error_page_title', __( '404: Page Not Found', 'wpex' ) );
			$title = wpex_translate_theme_mod( 'error_page_title', $title );

		// All else
		} elseif ( $post_id ) {

			// Singular products
			if ( is_singular( 'testimonials' ) ) {
				$obj	= get_post_type_object( 'testimonials' );
				$title	= $obj->labels->singular_name;
			}

			// Singular products
			elseif ( is_singular( 'product' ) ) {
				$title = get_theme_mod( 'woo_shop_single_title', __( 'Store', 'wpex' ) );
				$title = $title ? $title : __( 'Store', 'wpex' );
			}

			// Single posts
			elseif ( is_singular( 'post' ) ) {
				
				// Display custom text for blog post header
				if ( 'custom_text' == get_theme_mod( 'blog_single_header', 'custom_text' ) ) {
					$title = get_theme_mod( 'blog_single_header_custom_text', __( 'Blog', 'wpex' ) );
				}
				
				// Display post title for single posts
				else {
					$title = get_the_title( $post_id );
				}
			}
			
			// All other
			else {
				$title = get_the_title( $post_id );
			}
		}

		// Tribe Events Calendar Plugin title
		if ( function_exists( 'tribe_is_month' ) ) {
			if ( tribe_is_month() ) {
				$title = __( 'Events Calendar', 'wpex' );
			} elseif ( function_exists( 'tribe_is_event' ) && function_exists( 'tribe_is_day' ) && tribe_is_event() && !tribe_is_day() && !is_single() ) {
				$title = __( 'Events List', 'wpex' );
			} elseif ( function_exists( 'tribe_is_event' ) && function_exists( 'tribe_is_day' ) && tribe_is_event() && ! tribe_is_day() && is_single() ) {
				$title = __( 'Single Event', 'wpex' );
			} elseif ( function_exists( 'tribe_is_day' ) && tribe_is_day() ) {
				$title = __( 'Single Day', 'wpex' );
			}
		}

		// Backup
		$title = $title ? $title : get_the_title();

		// Return title and apply filters
		return apply_filters( 'wpex_title', $title );
		
	}
}

/**
 * Returns the page subheading
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_post_subheading' ) ) {
	function wpex_post_subheading( $post_id = '' ) {

		// Define output var
		$output = $subheading = '';

		// Get post ID
		$post_id = $post_id ? $post_id : wpex_get_the_id();
		
		// Posts & Pages
		if ( $post_id ) {

			// Display staff position if enabled instead of default subheading
			if ( is_singular( 'staff' )
				&& get_theme_mod( 'staff_subheading_position', '1' )
				&& '' != get_post_meta( $post_id, 'wpex_staff_position', true ) ) {
					$subheading = get_post_meta( $post_id, 'wpex_staff_position', true );
			}

			// Get subheading
			elseif ( '' != get_post_meta( $post_id, 'wpex_post_subheading', true ) ) {
				$subheading = get_post_meta( $post_id, 'wpex_post_subheading', true );
			}

		}

		// Categories
		elseif ( is_category()
			&& get_theme_mod( 'category_descriptions', '1' )
			&& 'under_title' == get_theme_mod( 'category_description_position', 'under_title' )
			&& term_description() ) {
				$subheading = term_description();
		}

		// WooCommerce
		elseif ( wpex_is_woo_tax() ) {
			if ( 'under_title' == get_theme_mod( 'woo_category_description_position', 'under_title' ) && term_description() ){
				$subheading = term_description();
			}
		}
		
		// All other Taxonomies
		elseif ( is_tax() && term_description() ){
			$subheading = term_description();
		}

		// Apply filters
		$subheading = apply_filters( 'wpex_post_subheading', $subheading );

		// If $description exists return
		if ( $subheading ) { ?>
			<div class="clr page-subheading">
				<?php echo do_shortcode( $subheading ); ?>
			</div>
		<?php }
		
	}
}

/**
 * Get page header background image URL
 *
 * @since Total 1.5.4
 */
if ( ! function_exists( 'wpex_page_header_background_image' ) ) {
	function wpex_page_header_background_image( $post_id = '' ) {

		// Get post id
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Get background image
		$bg_img = get_post_meta( $post_id, 'wpex_post_title_background_redux', true );

		// Sanitize data
		if ( $bg_img ) {
			if( is_array( $bg_img ) && ! empty( $bg_img['url'] ) ) {
				$bg_img = $bg_img['url'];
			}
		} else {
			$bg_img = get_post_meta( $post_id, 'wpex_post_title_background', true );
		}

		// Return URL
		if ( $bg_img ) {
			return $bg_img;
		} else {
			return;
		}
	}
}

/**
 * Outputs Custom CSS for the page title
 *
 * @since Total 1.5.3
 */
if ( !function_exists( 'wpex_page_header_overlay' ) ) {
	function wpex_page_header_overlay( $post_id = '' ) {

		// Get post ID
		$post_id = $post_id ? $post_id : wpex_get_the_id();

		// Return if ID not defined
		if ( ! $post_id ) {
			return;
		}

		// Get page header title style
		$style = get_post_meta( $post_id, 'wpex_post_title_style', true );

		// Only needed for the background-image style so return otherwise
		if ( 'background-image' != $style ) {
			return;
		}

		// Get opacity and overlay style
		$overlay	= get_post_meta( $post_id, 'wpex_post_title_background_overlay', true );
		$opacity	= get_post_meta( $post_id, 'wpex_post_title_background_overlay_opacity', true );

		// Check that overlay style isn't set to none
		if ( $overlay && 'none' != $overlay ) {
			// Add opacity style if opacity is defined
			if ( $opacity ) {
				$opacity = 'style="opacity:'. get_post_meta( $post_id, 'wpex_post_title_background_overlay_opacity', true ) .'"';
			}
			// Echo the span for the background overlay
			echo '<span class="background-image-page-header-overlay style-'. get_post_meta( $post_id, 'wpex_post_title_background_overlay', true ) .'" '. $opacity .'></span>';
		}
	}
}

/**
 * Outputs Custom CSS for the page title
 *
 * @since Total 1.53
 */
if ( ! function_exists( 'wpex_page_header_css' ) ) {
	function wpex_page_header_css( $output ) {

		// Get post ID
		$post_id = wpex_get_the_id();

		// Return if ID not defined
		if ( ! $post_id ) {
			return $output;
		}

		// Define var
		$css = $bg_img = '';
		$title_style = wpex_page_header_style( $post_id );

		// Background Color
		if ( $title_style == 'solid-color' || $title_style == 'background-image' ) {
			$bg_color = get_post_meta( $post_id, 'wpex_post_title_background_color', true );
			if ( $bg_color ) {
				$css .='background-color: '. $bg_color .';';
			}
		}

		// Background image
		if ( $title_style == 'background-image' ) {
			$bg_img = wpex_page_header_background_image( $post_id );
			if ( $bg_img ) {
				$css .= 'background-image: url('. $bg_img .' );background-position:50% 0;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;';
			}
		}

		// Custom height
		$title_height = get_post_meta( $post_id, 'wpex_post_title_height', true );
		if ( $title_height ) {
			$title_height = $title_height;
		} else {
			$title_height = '400';
		}
		if ( $title_height && $bg_img ) {
			$css .= 'height:'. intval( $title_height ) .'px;';
		}

		// Apply all css to the page-header class
		if ( ! empty( $css ) ) {
			$css = '.page-header { '. $css .' }';
		}

		// Overlay Color
		$overlay_color = get_post_meta( $post_id, 'wpex_post_title_background_overlay', true );
		if ( 'bg_color' == $overlay_color && $title_style == 'background-image' && isset( $bg_color ) ) {
			$css .= '.background-image-page-header-overlay { background-color: '. $bg_color .'; }';
		}

		// If css var isn't empty add to custom css output
		if ( ! empty( $css ) ) {
			$output .= $css;
		}

		// Return output
		return $output;

	}
}
add_filter( 'wpex_head_css', 'wpex_page_header_css' );