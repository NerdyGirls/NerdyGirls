<?php
/**
 * Header Menu Functions
 *
 * @package		Total
 * @subpackage	Framework/Menu
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Custom menu walker
 *
 * @since Total 1.3.0
 */
if ( ! class_exists( 'WPEX_Dropdown_Walker_Nav_Menu' ) ) {
	class WPEX_Dropdown_Walker_Nav_Menu extends Walker_Nav_Menu {
		function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
			$id_field = $this->db_fields['id'];
			// Down Arrows
			if( ! empty( $children_elements[$element->$id_field] ) && ( $depth == 0 ) ) {
				$element->classes[] = 'dropdown';
				if ( get_theme_mod( 'menu_arrow_down', true ) ) {
					$element->title .= ' <span class="nav-arrow fa fa-angle-down"></span>';
				}
			}
			// Right/Left Arrows
			if( ! empty( $children_elements[$element->$id_field] ) && ( $depth > 0 ) ) {
				$element->classes[] = 'dropdown';
				if ( get_theme_mod( 'menu_arrow_side', true ) ) {
					if( is_rtl() ) {
						$element->title .= '<span class="nav-arrow fa fa-angle-left"></span>';
					} else {
						$element->title .= '<span class="nav-arrow fa fa-angle-right"></span>';
					}
				}
			}
			Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}
	}
}

/**
 * Checks for custom menus
 *
 * @since Total 1.3.0
 */
if ( ! function_exists( 'wpex_custom_menu' ) ) {
	function wpex_custom_menu( $menu = false ) {
		if( $post_id = wpex_get_the_id() ) {
			if ( ( $meta = get_post_meta( $post_id, 'wpex_custom_menu', true ) ) && 'default' != $meta ) {
				$menu = $meta;
			}
		}
		return apply_filters( 'wpex_custom_menu', $menu );
	}
}

/**
 * Outputs the main header menu
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_header_menu' ) ) {
	function wpex_header_menu() {
		get_template_part( 'partials/header/header', 'menu' );
	}
}

/**
 * Outputs the responsive/mobile menu (icons) for the header
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_mobile_menu' ) ) {
	function wpex_mobile_menu( $style = '' ) {
		
		// If responsive is disabled, bail
		if( ! get_theme_mod( 'responsive', '1' ) ) {
			return;
		}
		
		// Vars
		$mobile_menu_open_button_text = '<span class="fa fa-bars"></span>';
		$mobile_menu_open_button_text = apply_filters( 'wpex_mobile_menu_open_button_text', $mobile_menu_open_button_text ); ?>

		<?php
		// Sidr closing div
		if( 'sidr' == get_theme_mod( 'mobile_menu_style', 'sidr' ) ) { ?>
			<div id="sidr-close"><a href="#sidr-close" class="toggle-sidr-close"></a></div>
		<?php } ?>

		<div id="mobile-menu" class="clr hidden">
			<a href="#mobile-menu" class="mobile-menu-toggle"><?php echo $mobile_menu_open_button_text; ?></a>
			<?php
			// Output icons if the mobile_menu region has a menu defined
			if ( has_nav_menu( 'mobile_menu' ) ) {
				if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'mobile_menu' ] ) ) {
					$menu = wp_get_nav_menu_object( $locations[ 'mobile_menu' ] );
					if ( !empty( $menu ) ) {
						$menu_items = wp_get_nav_menu_items( $menu->term_id );
						foreach ( $menu_items as $key => $menu_item ) {
							// Make sure it's a font-awesome icon
							if( in_array( $menu_item->title, wpex_get_awesome_icons() ) ) {
								$url = $menu_item->url;
								$attr_title = $menu_item->attr_title; ?>
								<a href="<?php echo $url; ?>" title="<?php echo $attr_title; ?>" class="mobile-menu-extra-icons mobile-menu-<?php echo $menu_item->title; ?>">
									<span class="fa fa-<?php echo $menu_item->title; ?>"></span>
								</a>
						<?php }
						}
					}
				}
			} ?>
		</div><!-- #mobile-menu -->
		
	<?php
	}
}

/**
 * Mobile Menu alternative
 *
 * @since Total 1.3.0
 */
if ( ! function_exists( 'wpex_mobile_menu_alt' ) ) {
	function wpex_mobile_menu_alt() {
		// If responsive is disabled, bail
		if( ! get_theme_mod( 'responsive', '1' ) ) {
			return;
		}
		// If mobile_menu_alt menu is defined output the menu
		if ( has_nav_menu( 'mobile_menu_alt' ) ) { ?>
			<div id="mobile-menu-alternative" class="hidden">
				<?php wp_nav_menu( array(
					'theme_location'	=> 'mobile_menu_alt',
					'menu_class'		=> 'dropdown-menu',
					'fallback_cb'		=> false,
				) ); ?>
			</div><!-- #mobile-menu-alternative -->
		<?php }	
	}
}
add_action( 'wp_footer', 'wpex_mobile_menu_alt' );