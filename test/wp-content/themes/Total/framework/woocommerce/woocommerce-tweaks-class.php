<?php
/**
 * Perform all main WooCommerce edits for this theme
 *
 * @package		Total
 * @subpackage	Framework/WooCommerce
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

if ( ! class_exists( 'WPEX_WooCommerce_Tweaks' ) ) {
	class WPEX_WooCommerce_Tweaks {

		/**
		 * Start things up
		 */
		public function __construct() {

			// Remove default CSS
			add_filter( 'woocommerce_enqueue_styles', '__return_false' );

			// Remove WooCommerce meta generator
			remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

			// Make edits to WooCommerce scripts
			add_action( 'scripts', array( $this, 'scripts' ), 99 );

			// Alter default WooCommerce image sizes
			add_action( 'after_switch_theme', array( $this, 'image_sizes' ) );

			// Remove shop title
			add_filter( 'woocommerce_show_page_title', array( $this, 'remove_title' ) );

			// Change onsale text
			add_filter( 'woocommerce_sale_flash', array( $this, 'onsale_text' ), 10, 3 );

			// Change products per page for the shop
			add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '. get_theme_mod( 'woo_shop_posts_per_page', '12' ) .';' ), 20 );

			// Remove category descriptions, these are added already by the theme
			remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

			// Change products per row
			add_filter( 'loop_shop_columns', array( $this, 'loop_shop_columns' ) );

			// Alter upsells display
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			add_action( 'woocommerce_after_single_product_summary', array( $this, 'upsell_display' ), 15 );

			// Related product arguments
			add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_product_args' ) );

			// Alter cross-sells display
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			add_action( 'woocommerce_cart_collaterals', array( $this, 'cross_sell_display' ) );

			// Alter WooCommerce category thumbnail
			remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
			add_action( 'woocommerce_before_subcategory_title', array( $this, 'subcategory_thumbnail' ), 10 );

			// Remove loop product thumbnail function and add our own that pulls from template parts
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_thumbnail' ), 10 );

			// Remove coupon from checkout
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

			// Remove single meta
			if ( ! get_theme_mod( 'woo_product_meta', 'on' ) ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			}

			// Remove upsells if set to 0
			if ( '0' == get_theme_mod( 'woocommerce_upsells_count', '4' ) ) {
				remove_action( 'woocommerce_after_single_product_summary', 'wpex_woocommerce_output_upsells', 15 );
			}

			// Remove related products if count is set to 0
			if ( '0' == get_theme_mod( 'woocommerce_related_count', '4' ) ) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			}

			// Remove crossells if set to 0
			if ( '0' == get_theme_mod( 'woocommerce_cross_sells_count', '4' ) ) {
				remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			}

			// Remove result count if disabled
			if ( ! get_theme_mod( 'woo_shop_result_count', true ) ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			}

			// Remove orderby if disabled
			if ( ! get_theme_mod( 'woo_shop_sort', true ) ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			}

			// Tweak the pagination arguments
			add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );

		}

		/**
		 * Edit Scripts
		 */
		public function scripts() {

			// Remove prettyPhoto CSS
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );

			// Remove prettyPhoto Init
			wp_dequeue_script( 'prettyPhoto-init' );

		}

		/**
		 * Edit Default image sizes
		 */
		public function image_sizes() {
			
			update_option( 'shop_catalog_image_size', array(
				'width' 	=> '9999',
				'height'	=> '9999',
				'crop'		=> 0
			) );

			update_option( 'shop_single_image_size', array(
				'width' 	=> '9999',
				'height'	=> '9999',
				'crop'		=> 0
			) );

			update_option( 'shop_thumbnail_image_size', array(
				'width' 	=> '100',
				'height'	=> '100',
				'crop'		=> 1
			) );

		}

		/**
		 * Remove shop title
		 */
		public function remove_title() {
			return false;
		}

		/**
		 * Change onsale text
		 */
		public function onsale_text( $text, $post, $_product ) {
			return '<span class="onsale">'. __( 'Sale', 'wpex' ) .'</span>';
		}

		/**
		 * Change products per row
		 */
		public function loop_shop_columns() {
			return get_theme_mod( 'woocommerce_shop_columns', '4' );
		}

		/**
		 * Change products per row
		 */
		public function upsell_display() {
			woocommerce_upsell_display(
				get_theme_mod( 'woocommerce_upsells_count', '4' ),
				get_theme_mod( 'woocommerce_upsells_columns', '4' )
			);
		}

		/**
		 * Change products per row
		 */
		public function cross_sell_display() {
			woocommerce_cross_sell_display(
				get_theme_mod( 'woocommerce_cross_sells_count', '4' ),
				get_theme_mod( 'woocommerce_cross_sells_columns', '4' )
			);
		}

		/**
		 * Change products per row
		 */
		public function subcategory_thumbnail( $category ) {
			$title			= get_the_title();
			$thumbnail_id	= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
			$attachment_url	= wp_get_attachment_url( $thumbnail_id );
			$width			= get_theme_mod( 'woo_cat_entry_width', '9999' );
			$height			= get_theme_mod( 'woo_cat_entry_height', '9999' );
			$crop			= ( $height == '9999' ) ? false : true;
			$attachment_url = wpex_image_resize( $attachment_url,  $width, $height, $crop );
			$attachment_url = $attachment_url ? $attachment_url : get_template_directory_uri() .'/images/dummy-image.jpg';
			echo '<img src="'. $attachment_url .'" alt="'. $title .'" />';
		}

		/**
		 * Alter the related product arguments
		 */
		public function related_product_args() {
			global $product, $orderby, $related;
			$args = array(
				'posts_per_page'	=> get_theme_mod( 'woocommerce_related_count', '4' ),
				'columns'			=> get_theme_mod( 'woocommerce_related_columns', '4' ),
			);
			return $args;
		}

		/**
		 * Returns our product thumbnail from our template parts based on selected style in theme mods
		 */
		public function loop_product_thumbnail() {
			$style = get_theme_mod( 'woo_product_entry_style', 'image-swap' );
			if ( function_exists( 'wc_get_template' ) ) {
				wc_get_template(  'loop/thumbnail/'. $style .'.php' );
			}
		}

		/**
		 * Tweaks pagination arguments
		 */
		public function pagination_args( $args ) {
			$prev_arrow = is_rtl() ? 'fa fa-angle-right' : 'fa fa-angle-left';
			$next_arrow = is_rtl() ? 'fa fa-angle-left' : 'fa fa-angle-right';
			$args['prev_text']	= '<i class="'. $prev_arrow .'"></i>';
			$args['next_text']	= '<i class="'. $next_arrow .'"></i>';
			return $args;
		}

	}
}
new WPEX_WooCommerce_Tweaks();