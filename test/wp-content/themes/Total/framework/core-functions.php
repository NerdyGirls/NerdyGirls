<?php
/**
 * Core theme functions - muy importante!! Do not ever edit this file, if you need to make
 * adjustments, please use a child theme. If you aren't sure how, please ask!
 *
 * @package		Total
 * @subpackage	Framework/Core
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link 		http://www.wpexplorer.com
 * @since		Total 1.3.3
 */

/**
 * Returns theme custom post types
 *
 * @since	Total 1.3.3
 * @return	array $post_types
 */
function wpex_theme_post_types() {
	$post_types	= array( 'portfolio', 'staff', 'testimonials' );
	$post_types	= array_combine( $post_types, $post_types );
	return apply_filters( 'wpex_theme_post_types', $post_types );
}

/**
 * Was used to retrieve redux theme options
 * Returns theme mod since 1.6.0
 *
 * @since	Total 1.3.3
 * @return	array $post_types
 */
function wpex_option( $id, $fallback = false, $param = false ) {
	return get_theme_mod( $id, $fallback );
}

/**
 * Get's the current ID, this function is needed to properly support WooCommerce
 *
 * @since Total 1.5.4
 */
function wpex_get_the_id() {
	// If singular get_the_ID
	if ( is_singular() ) {
		return get_the_ID();
	}
	// Get ID of WooCommerce product archive
	elseif ( is_post_type_archive( 'product' ) && class_exists( 'Woocommerce' ) && function_exists( 'wc_get_page_id' ) ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( isset( $shop_id ) ) {
			return wc_get_page_id( 'shop' );
		}
	}
	// Posts page
	elseif( is_home() && $page_for_posts = get_option( 'page_for_posts' ) ) {
		return $page_for_posts;
	}
	// Return nothing
	else {
		return NULL;
	}
}

/**
 * Returns the correct main layout class
 *
 * @since	Total 1.5.0
 * @return	bool
 */
function wpex_main_layout( $post_id = '' ) {

	// Get default theme val
	$layout = get_theme_mod( 'main_layout_style', 'full-width' );

	// Check post meta
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_main_layout', true ) ) {
		$layout = $meta;
	}

	// Return correct layout & apply filter
	return apply_filters( 'wpex_main_layout', $layout );

}

/**
 * The source for the sidr mobile menu
 *
 * @since	Total 1.5.1
 * @return	string
 */
function wpex_mobile_menu_source() {
	$array = array();
	$array['sidrclose'] = '#sidr-close';
	if ( has_nav_menu( 'mobile_menu_alt' ) ) {
		$array['nav'] = '#mobile-menu-alternative';
	} else {
		$array['nav'] = '#site-navigation';
	}
	$array['search'] = '#mobile-menu-search';
	$array = apply_filters( 'wpex_mobile_menu_source', $array );
	return implode( ', ', $array );
}

/**
 * Defines your default search results page style
 *
 * @since	Total 1.5.4
 * @return	string
 */
function wpex_search_results_style() {
	$style = apply_filters( 'wpex_search_results_style', 'default' );
	return $style;
}

/**
 * Returns the post URL
 *
 * @since	Total 1.5.4
 * @return	string
 */
function wpex_permalink( $post_id = NULL ) {
	$post_id	= $post_id ? $post_id : get_the_ID();
	$permalink	= get_permalink( $post_id );
	$format		= get_post_format( $post_id );
	if ( 'link' == $format ) {
		if ( $meta = get_post_meta( $post_id, 'wpex_post_link', true ) ) {
			$permalink = esc_url( $meta );
		}
	}
	$permalink = apply_filters( 'wpex_permalink', $permalink );
	return $permalink;
}

/**
 * Returns the correct sidebar ID
 *
 * @since 1.0.0
 */
function wpex_get_sidebar( $sidebar = 'sidebar' ) {

	// Pages
	if ( is_page() && get_theme_mod( 'pages_custom_sidebar', true ) ) {
		if ( ! is_page_template( 'templates/blog.php' ) ) {
			$sidebar = 'pages_sidebar';
		}
	}

	// Portfolio
	elseif( is_singular( 'portfolio' ) || wpex_is_portfolio_tax() ) {
		if ( get_theme_mod( 'portfolio_custom_sidebar', true ) ) {
			$sidebar = 'portfolio_sidebar';
		}
	}

	// Staff
	elseif( is_singular( 'staff' ) || wpex_is_staff_tax() ) {
		if ( get_theme_mod( 'staff_custom_sidebar', true ) ) {
			$sidebar = 'staff_sidebar';
		}
	}

	// Testimonials
	elseif( is_singular( 'testimonials' ) || wpex_is_testimonials_tax() ) {
		if ( get_theme_mod( 'testimonials_custom_sidebar', true ) ) {
			$sidebar = 'testimonials_sidebar';
		}
	}

	// Search
	elseif ( is_search() && get_theme_mod( 'search_custom_sidebar', true ) ) {
		$sidebar = 'search_sidebar';
	}

	// WooCommerce
	elseif ( class_exists( 'Woocommerce' ) && get_theme_mod( 'woo_custom_sidebar', true ) && is_woocommerce() ) {
		$sidebar = 'woo_sidebar';
	}
	
	// bbPress
	elseif ( function_exists( 'is_bbpress' ) && is_bbpress() && get_theme_mod( 'bbpress_custom_sidebar', true ) ) {
		$sidebar = 'bbpress_sidebar';
	}

	// Check meta option as fallback
	if ( $post_id = wpex_get_the_id() ) {
		if ( $meta = get_post_meta( $post_id, 'sidebar', true ) ) {
			$sidebar = $meta;
		}
	}
	
	// Add filter for tweaking the sidebar display via child theme's
	$sidebar = apply_filters( 'wpex_get_sidebar', $sidebar );

	// Return the correct sidebar
	return $sidebar;
	
}

/**
 * Returns the correct classname for any specific column grid
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_grid_class' ) ) {
	function wpex_grid_class( $col ) {
		if ( $col == '1' ) {
			return 'span_1_of_1';
		} elseif ( $col == '2' ) {
			return 'span_1_of_2';
		} elseif ( $col == '3' ) {
			return 'span_1_of_3';
		} elseif ( $col == '4' ) {
			return 'span_1_of_4';
		} elseif ( $col == '5' ) {
			return 'span_1_of_5';
		} elseif ( $col == '6' ) {
			return 'span_1_of_6';
		} elseif ( $col == '7' ) {
			return 'span_1_of_7';
		} elseif ( $col == '8' ) {
			return 'span_1_of_8';
		} elseif ( $col == '9' ) {
			return 'span_1_of_9';
		} elseif ( $col == '10' ) {
			return 'span_1_of_10';
		} elseif ( $col == '11' ) {
			return 'span_1_of_11';
		} elseif ( $col == '12' ) {
			return 'span_1_of_12';
		} else {
			return 'span_1_of_4';
		}
	}
}

/**
 * Returns the 1st taxonomy of any taxonomy
 *
 * @since	Total 1.3.3
 * @return	string
 */
if ( ! function_exists( 'wpex_get_first_term' ) ) {
	function wpex_get_first_term( $post_id, $taxonomy = 'category' ) {
		if ( ! $post_id ) {
			return;
		}
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}
		$terms = wp_get_post_terms( $post_id, $taxonomy );
		if ( ! empty( $terms ) ) { ?>
			<span><?php echo $terms[0]->name; ?></span>
		<?php
		}
	}
}

/**
 * List categories for specific taxonomy
 * 
 * @link	http://codex.wordpress.org/Function_Reference/wp_get_post_terms
 * @since	Total 1.6.3
 */
if ( ! function_exists( 'wpex_list_post_terms' ) ) {
	function wpex_list_post_terms( $taxonomy = 'category', $show_links = true, $echo = true ) {

		// Get terms
		$list_terms	= array();
		$terms		= wp_get_post_terms( get_the_ID(), $taxonomy );

		// Return if no terms are found
		if ( ! $terms ) {
			return;
		}

		// Loop through terms
		foreach ( $terms as $term ) {
			$permalink		= get_term_link( $term->term_id, $taxonomy );
			if ( $show_links ) {
				$list_terms[]	= '<a href="'. $permalink .'" title="'. $term->name .'">'. $term->name .'</a>';
			} else {
				$list_terms[]	= $term->name;
			}
		}

		// Turn into comma seperated string
		if ( $list_terms && is_array( $list_terms ) ) {
			$list_terms = implode( ', ', $list_terms );
		} else {
			return;
		}

		// Echo terms
		if ( $echo ) {
			echo $list_terms;
		} else {
			return $list_terms;
		}

	}
}

/**
 * Sanitize Visual Composer data
 * 
 * @since	Total 1.6.3
 */
if ( ! function_exists( 'wpex_sanitize_data' ) ) {
	function wpex_sanitize_data( $data = NULL, $type = NULL ) {

		// Data and type are required
		if ( ! $data && ! $type ) {
			return;
		}

		// Return px
		elseif ( 'px' == $type ) {
			return intval( $data ) . 'px';
		}

		// Sanitize Hex Color : 3 or 6 hex digits, or the empty string.
		elseif ( 'hex_color' == $type ) {
			if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $data ) ) {
				return $data;
			} else {
				return null;
			}
		}

		// Return px or percent
		elseif ( 'px-pct' == $type ) {
			if ( strpos( $data, '%' ) ) {
				return $data;
			} elseif ( $data = intval( $data ) ) {
				return $data .'px';
			}
		}

	}
}

/**
 * Sanitize Visual Composer data
 * 
 * @since	Total 1.6.3
 */
if ( ! function_exists( 'wpex_minify_css' ) ) {
	function wpex_minify_css( $css ) {

		// Normalize whitespace
		$css = preg_replace( '/\s+/', ' ', $css );

		// Remove ; before }
		$css = preg_replace( '/;(?=\s*})/', '', $css );

		// Remove space after , : ; { } */ >
		$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

		// Remove space before , ; { }
		$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

		// Strips leading 0 on decimal values (converts 0.5px into .5px)
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

		// Strips units if value is 0 (converts 0px to 0)
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

		// Return minified CSS
		return trim( $css );
		
	}
}

/**
 * Provides translation support for plugins such as WPML
 * 
 * @since	Total 1.6.3
 */
if ( ! function_exists( 'wpex_translate_theme_mod' ) ) {
	function wpex_translate_theme_mod( $id, $content ) {

		// Return false if no content is found
		if ( ! $content ) {
			return false;
		}

		// WPML translation
		if ( function_exists( 'icl_t' ) && $id ) {
			$content = icl_t( 'Theme Mod', $id, $content );
		}

		// Polylang Translation
		if ( function_exists( 'pll__' ) && $id ) {
			$content = pll__( $content );
		}

		// Return the content
		return $content;
	
	}
}