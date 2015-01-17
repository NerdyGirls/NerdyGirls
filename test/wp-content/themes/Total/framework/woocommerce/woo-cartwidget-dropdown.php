<?php
/**
 * Add Menu Cart to menu
 *
 * Code elegantly lifted from: http://wordpress.org/plugins/woocommerce-menu-bar-cart/
 * Edited by WPExplorer
 *
 * @package		Total
 * @subpackage	Framework/WooCommerce
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Get Header Style
if ( ! function_exists( 'wpex_get_header_style' ) ) {
	return;
} else {
	$wpex_header_style = wpex_get_header_style();
}

if ( 'one' == $wpex_header_style ) {
	add_action( 'wpex_hook_header_inner', 'wpex_cart_widget_dropdown' );
}

if ( 'two' == $wpex_header_style || 'three' == $wpex_header_style ) {
	add_action( 'wpex_hook_main_menu_bottom', 'wpex_cart_widget_dropdown' );
}

if ( ! function_exists( 'wpex_cart_widget_dropdown' ) ) {
	function wpex_cart_widget_dropdown() {
		
		// If disabled bail
		if ( ! get_theme_mod( 'woo_menu_icon', true ) ) {
			return;
		}
		
		// Return if it isn't the corrent style
		if ( 'drop-down' != get_theme_mod( 'woo_menu_icon_style', 'drop-down' ) ) {
			return;
		}
		
		// Not needed on cart or checkout
		if ( is_cart() || is_checkout() ) {
			return;
		}

		// Globals & vars
		global $woocommerce;
		$shop_page_url			= get_permalink( woocommerce_get_page_id( 'shop' ) );
		$cart_contents_count	= $woocommerce->cart->cart_contents_count; ?>
		
		<div id="current-shop-items-dropdown" class="clr">
			<div id="current-shop-items-inner" class="clr">
				<?php
				// Display WooCommerce cart
				the_widget( 'WC_Widget_Cart', 'title= ' ); ?>
			</div><!-- #current-shop-items-inner -->
		</div><!-- #current-shop-items-dropdown -->
		
	<?php
	}
}