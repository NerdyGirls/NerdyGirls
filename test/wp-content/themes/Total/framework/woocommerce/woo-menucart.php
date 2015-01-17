<?php
/**
 * Add Menu Cart to menu
 *
 * @package		Total
 * @subpackage	Framework/WooCommerce
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

add_filter( 'wp_nav_menu_items', 'wpex_add_itemcart_to_menu' , 10, 2 );
add_filter( 'add_to_cart_fragments', 'wpex_wcmenucart_add_to_cart_fragment' );
		
/**
 * Add the WooCommerce cart item to th enav
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_add_itemcart_to_menu' ) ) {
	function wpex_add_itemcart_to_menu( $items, $args ) {
		
		// Add to main menu only
		if ( 'main_menu' == $args->theme_location ) {

			// Get style from theme mod
			$style = get_theme_mod( 'woo_menu_icon_style', 'drop-down' );
			
			// Add class for the toggle
			if ( 'drop-down' == $style ) {
				$class = 'wcmenucart-toggle-dropdown';	
			} elseif ( 'overlay' == $style ) {
				$class = 'wcmenucart-toggle-overlay';
			} elseif ( 'store' == $style ) {
				$class = '';
			} elseif ( 'custom-link' == $style ) {
				$class = '';
			} else {
				$class = '';
			}
			
			// Remove toggle class for the cart or checkout
			if ( is_cart() || is_checkout() ) {
				$class = '';
			}
			
			// Add cart link to menu items
			$items .= '<li class="'. $class .' woo-menu-icon">' . wpex_wcmenucart_menu_item() .'</li>';
		}
		
		// Return menu items
		return $items;
	}
	
}

/**
 * WooFragments update the shop menu icon when the cart is updated via ajax
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_wcmenucart_add_to_cart_fragment' ) ) {
	function wpex_wcmenucart_add_to_cart_fragment( $fragments ) {
		$fragments['.wcmenucart'] = wpex_wcmenucart_menu_item();
		return $fragments;
	}
}

/**
 * Creates the WooCommerce link for the navbar
 *
 * @since Total 1.0
 */
if ( ! function_exists( 'wpex_wcmenucart_menu_item' ) ) {
	function wpex_wcmenucart_menu_item() {
		
		// Vars
		global $woocommerce;

		// URL
		if ( 'custom-link' == get_theme_mod( 'woo_menu_icon_style', 'drop-down' )
			&& $custom_link = get_theme_mod( 'woo_menu_icon_custom_link' ) ) {
			$url = esc_url( $custom_link );
		} else {
			$url = get_permalink( woocommerce_get_page_id( 'shop' ) );
		}
		
		// Cart total
		if ( get_theme_mod( 'woo_menu_icon_amount' ) ) {
			$cart_total = $woocommerce->cart->get_cart_total();
		} else {
			$cart_total = '';
		}

		ob_start(); ?>
			<a href="<?php echo $url; ?>" class="wcmenucart" title="<?php _e('Your Cart','wpex'); ?>">
				<span class="wcmenucart-count"><span class="fa fa-shopping-cart"></span><?php echo $cart_total; ?></span>
			</a>
		<?php
		return ob_get_clean();
	}
}