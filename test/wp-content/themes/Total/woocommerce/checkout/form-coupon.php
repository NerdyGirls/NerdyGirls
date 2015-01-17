<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if coupons not enabled
if ( ! WC()->cart->coupons_enabled() ) {
	return;
} ?>

<h3><?php _e( 'Coupon', 'wpex' ); ?></h3>

<form class="checkout_coupon" method="post" style="display:none">
	<p class="form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
	</p>
	<p class="form-row form-row-last">
		<input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />
	</p>
	<div class="clear"></div>
</form>