<?php
/**
 * Core search functions
 *
 * @package		Total
 * @subpackage	Framework/Search
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Check if search icon should be in the nav
 *
 * @since	Total 1.0.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_search_in_menu' ) ) {
	function wpex_search_in_menu() {
		if ( ! get_theme_mod( 'main_search', true ) ) {
			return false;
		} elseif ( 'two' == wpex_get_header_style() ) {
			return false;
		} else {
			return true;
		}
	}
}

/**
 * Get Correct header search style
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_header_search_style' ) ) {
	function wpex_header_search_style() {
		if ( ! wpex_search_in_menu() ) {
			return;
		} else {
			return get_theme_mod( 'main_search_toggle_style', 'drop_down' );
		}
	}
}

/**
 * Adds the search icon to the menu items
 *
 * @since	Total 1.0.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_add_search_to_menu' ) ) {
	function wpex_add_search_to_menu ( $items, $args ) {

		// Get Search toggle style
		$toggle_style = wpex_header_search_style();

		if ( ! $toggle_style ) {
			return $items;
		}
		
		// Add class to search icon based on search toggle style
		if ( 'overlay' == $toggle_style ) {
			$class = ' search-overlay-toggle';
		} elseif ( 'drop_down' == $toggle_style ) {
			$class = ' search-dropdown-toggle';
		} elseif ( 'header_replace' == $toggle_style ) {
			$class = ' search-header-replace-toggle';
		} else {
			$class = '';
		}

		// It's all cool so display search icon in the main_menu
		if ( 'main_menu' == $args->theme_location ) {
			$items .= '<li class="search-toggle-li"><a href="#" class="site-search-toggle'. $class .'"><span class="fa fa-search"></span></a></li>';
		}
		
		// Return nav $items
		return $items;

	}
}
add_filter( 'wp_nav_menu_items', 'wpex_add_search_to_menu', 11, 2 );

/**
 * Adds a hidden searchbox in the footer for use with the mobile menu
 *
 * @since Total 1.5.1
 */
if ( ! function_exists( 'wpex_mobile_searchform' ) ) {
	function wpex_mobile_searchform() {
		// Make sure the mobile search is enabled for the sidr nav other wise return
		if ( function_exists( 'wpex_mobile_menu_source' ) ) {
			$sidr_elements = wpex_mobile_menu_source();
			if ( isset( $sidr_elements ) && is_array( $sidr_elements ) ) {
				if ( ! isset( $sidr_elements['search'] ) ) {
					return;
				}
			}
		}
		// Output the search
		$placeholder = apply_filters( 'wpex_mobile_searchform_placeholder', __( 'Search', 'wpex' ) );
		// Add Classes
		$classes = 'clr hidden';
		if ( 'toggle' == get_theme_mod( 'mobile_menu_style' ) ) {
			$classes .= ' container';
		} ?>
		<div id="mobile-menu-search" class="<?php echo $classes; ?>">
			<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search" class="mobile-menu-searchform">
				<input type="search" name="s" autocomplete="off" placeholder="<?php echo $placeholder; ?>" />
			</form>
		</div>
	<?php }
}
add_filter( 'wp_footer', 'wpex_mobile_searchform' );

/**
 * Search Dropdown
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_search_dropdown' ) ) {
	function wpex_search_dropdown() {
		if ( 'drop_down' != wpex_header_search_style() ) {
			return;
		}
		$placeholder = apply_filters( 'wpex_search_placeholder_text', __( 'search', 'wpex' ) ); ?>
		<div id="searchform-dropdown" class="header-searchform-wrap clr">
			<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search" class="header-searchform"><input type="search" name="s" autocomplete="off" placeholder="<?php echo $placeholder; ?>" /></form>
		</div>
	<?php
	}
}
if ( 'one' == wpex_get_header_style() ) {
	add_action( 'wpex_hook_header_inner', 'wpex_search_dropdown' );
} else {
	add_action( 'wpex_hook_main_menu_bottom', 'wpex_search_dropdown' );
}

/**
 * Search Header Replace
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_search_header_replace' ) ) {
	function wpex_search_header_replace() {
		if ( 'header_replace' != wpex_header_search_style() ) {
			return;
		}
		$placeholder = apply_filters( 'wpex_search_placeholder_text', __( 'Type then hit enter to search...', 'wpex' ) ); ?>
			<div id="searchform-header-replace" class="clr header-searchform-wrap">
				<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search" class="header-searchform"><input type="search" name="s" autocomplete="off" placeholder="<?php _e( 'Type then hit enter to search...', 'wpex' ); ?>" /></form>
				<span id="searchform-header-replace-close" class="fa fa-times"></span>
			</div>
		<?php
	}
}
add_action( 'wpex_hook_header_inner', 'wpex_search_header_replace' );

/**
 * Search Overlay
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_search_overlay' ) ) {
	function wpex_search_overlay() {
		if ( 'overlay' != wpex_header_search_style() ) {
			return;
		}
		$placeholder = apply_filters( 'wpex_search_placeholder_text', __( 'Search', 'wpex' ) ); ?>
		<section id="searchform-overlay" class="header-searchform-wrap clr">
			<div id="searchform-overlay-title"><?php echo $placeholder; ?></div>
			<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search" class="header-searchform">
				<input type="search" name="s" autocomplete="off" />
			</form>
		</section>
	<?php }
}
add_action( 'wp_footer', 'wpex_search_overlay' );