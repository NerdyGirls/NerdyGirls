<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author		WooThemes
 * @package		WooCommerce/Templates
 * @version		1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;


// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();

/** WPEX CLASSES **/
$classes[] = 'col';
$classes[] = wpex_grid_class( $woocommerce_loop['columns'] );
/** WPEX CLASSES **/

// First column class
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}

// Last column class
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}

// Get WooCommerce entry style from theme options
$wpex_woo_style = get_theme_mod( 'woo_entry_style', 'two' );

/******************************************************
 * Woo Style 2
*****************************************************/
if ( 'two' == $wpex_woo_style ) {

	// Product Classes
	$classes = array_merge( $classes, array( 'product-entry', 'product-entry-style-two' ) ); ?>

	<li <?php post_class( $classes ); ?>>
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
		<div class="product-entry-media clr">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="product-entry-thumb">
				<?php
				/**
				 * woocommerce_before_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10
				 * @hooked woocommerce_template_loop_product_thumbnail - 10
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
				<?php if ( !wpex_woo_product_instock() ) { ?>
					<div class="product-entry-out-of-stock-badge">
						<?php _e( 'Out of Stock', 'wpex' ); ?>
					</div>
				<?php } ?>
			</a>
			<?php
			// Display rating
			if ( get_theme_mod( 'woo_entry_rating' ) && 'no' != get_option( 'woocommerce_enable_review_rating' ) && $product->get_rating_html() ) { ?>
				<div class="product-entry-rating clr">
					<?php wc_get_template( 'loop/rating.php' ); ?>
				</div><!-- .product-entry-rating -->
			<?php } ?>
		</div>
		<div class="product-entry-details clr">
			<h2 class="product-entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>">
					<?php the_title(); ?>
				</a>
			</h2><!-- .product-entry-title -->
			<?php
			// Display Price
			if ( $product->get_price_html() ) { ?>
				<div class="product-entry-price">
					<span class="product-entry-price"><?php echo $product->get_price_html(); ?></span>
				</div><!-- .product-entry-price -->
			<?php } ?>
		</div><!-- .product-entry-details -->
	</li>

<?php
/******************************************************
 * Woo Style 1 - Default WooCommerce style
*****************************************************/
} else {

	$classes = array_merge( $classes, array( 'product-entry', 'style-one' ) ); ?>

	<li <?php post_class( $classes ); ?>>
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="product-entry-thumb">
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
		</a>
		<div class="product-entry-details clr">
			<h2 class="product-entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</h2>
			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div><!-- .product-entry-details -->
	</li>

<?php } ?>