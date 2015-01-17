<?php
/**
 * WooCommerce customizer options
 *
 * @package		Total
 * @subpackage	Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

if ( ! WPEX_WOOCOMMERCE_ACTIVE ) {
	return;
}

/*-----------------------------------------------------------------------------------*/
/*	- General
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_woocommerce_general'] = array(
	'title'		=> __( 'General', 'wpex' ),
	'panel'		=> 'wpex_woocommerce',
	'settings'	=> array(
		array(
			'id'		=> 'woo_custom_sidebar',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Custom WooCommerce Sidebar', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'woo_menu_icon',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Menu Cart', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'woo_menu_icon_amount',
			'default'	=> false,
			'transport'	=> 'postMessage',
			'control'	=> array (
				'label'	=>	__( 'Menu Cart: Amount', 'wpex' ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'You must save your options and refresh your live site to preview changes to this setting.', 'wpex' ),
			),
		),
		array(
			'id'		=> 'woo_menu_icon_style',
			'default'	=> 'drop-down',
			'control'	=> array (
				'label'	=>	__( 'Menu Cart: Style', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'drop-down'		=> __( 'Drop-Down','wpex' ),
					'overlay'		=> __( 'Open Cart Overlay','wpex' ),
					'store'			=> __( 'Go To Store','wpex' ),
					'custom-link'	=> __( 'Custom Link','wpex' ),
				),
			),
		),
		array(
			'id'		=> 'woo_menu_icon_custom_link',
			'control'	=> array (
				'label'	=>	__( 'Menu Cart: Custom Link', 'wpex' ),
				'type'	=> 'text',
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/*	- Archives
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_woocommerce_archives'] = array(
	'title'		=> __( 'Archives', 'wpex' ),
	'panel'		=> 'wpex_woocommerce',
	'settings'	=> array(
		array(
			'id'		=> 'woo_shop_title',
			'default'	=> 'on',
			'control'	=> array (
				'label'	=> __( 'Shop Title', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'woo_shop_slider',
			'control'	=> array (
				'label'	=> __( 'Shop Slider', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'		=> 'woo_shop_posts_per_page',
			'default'	=> '12',
			'control'	=> array (
				'label'	=> __( 'Shop Posts Per Page', 'wpex' ),
				'type'	=> 'text',
				'desc'	=> __( 'You must save your options and refresh your live site to preview changes to this setting.', 'wpex' ),
			),
		),
		array(
			'id'			=> 'woo_shop_layout',
			'default'		=> 'full-width',
			'control'		=> array (
				'label'		=> __( 'Layout', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'full-width'	=> __( 'No Sidebar','wpex' ),
					'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
					'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
				),
			),
		),
		array(
			'id'			=> 'woocommerce_shop_columns',
			'default'		=> '4',
			'control'		=> array (
				'label'		=> __( 'Shop Columns', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'2'	=> '2',
					'3'	=> '3',
					'4'	=> '4',
					'5'	=> '5',
					'6'	=> '6',
				),

			),
		),
		array(
			'id'			=> 'woo_category_description_position',
			'default'		=> 'under_title',
			'control'		=> array (
				'label'		=> __( 'Category Description Position', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'under_title'	=> __( 'Under Title', 'wpex' ),
					'above_loop'	=> __( 'Above Loop', 'wpex' ),
				),

			),
		),
		array(
			'id'		=> 'woo_shop_sort',
			'default'	=> 'on',
			'control'	=> array (
				'label'	=> __( 'Shop Sort', 'wpex' ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'You must save your options and refresh your live site to preview changes to this setting.', 'wpex' ),
			),
		),
		array(
			'id'		=> 'woo_shop_result_count',
			'default'	=> 'on',
			'control'	=> array (
				'label'	=> __( 'Shop Result Count', 'wpex' ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'You must save your options and refresh your live site to preview changes to this setting.', 'wpex' ),
			),
		),
		array(
			'id'		=> 'woo_entry_rating',
			'control'	=> array (
				'label'	=> __( 'Product Entry Ratings', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'			=> 'woo_entry_style',
			'default'		=> 'two',
			'control'		=> array (
				'label'		=> __( 'Product Entry Style', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'two'	=> __( 'Theme Style','wpex' ),
					'one'	=> __( 'WooCommerce Un-Edited','wpex' ),
				),
			),
		),
		array(
			'id'			=> 'woo_product_entry_style',
			'default'		=> 'image-swap',
			'control'		=> array (
				'label'		=> __( 'Product Entry Media', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'featured-image'	=> __( 'Featured Image','wpex' ),
					'image-swap'		=> __( 'Image Swap','wpex' ),
					'gallery-slider'	=> __( 'Gallery Slider','wpex' ),
				),
			),
		),
	)
);


/*-----------------------------------------------------------------------------------*/
/*	- Single
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_woocommerce_single'] = array(
	'title'		=> __( 'Single', 'wpex' ),
	'panel'		=> 'wpex_woocommerce',
	'settings'	=> array(
		array(
			'id'		=> 'woo_shop_single_title',
			'default'	=> __( 'Store', 'wpex' ),
			'control'	=> array (
				'label'	=> __( 'Page Header Title', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'			=> 'woo_product_layout',
			'default'		=> 'full-width',
			'control'		=> array (
				'label'		=> __( 'Layout', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'full-width'	=> __( 'No Sidebar','wpex' ),
					'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
					'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
				),
			),
		),
		array(
			'id'		=> 'woocommerce_upsells_count',
			'default'	=> '4',
			'control'	=> array (
				'label'	=> __( 'Up-Sells Count', 'wpex' ), 
				'type'	=> 'text',
			),
		),
		array(
			'id'			=> 'woocommerce_upsells_columns',
			'default'		=> '4',
			'control'		=> array (
				'label'		=> __( 'Up-Sells Columns', 'wpex' ), 
				'type'		=> 'select',
				'choices'	=> array(
					'2'	=> '2',
					'3'	=> '3',
					'4'	=> '4',
					'5'	=> '5',
					'6'	=> '6'
				),
			),
		),
		array(
			'id'		=> 'woocommerce_related_count',
			'default'	=> '4',
			'control'	=> array (
				'label'	=> __( 'Related Items Count', 'wpex' ), 
				'type'	=> 'text',
			),
		),
		array(
			'id'			=> 'woocommerce_related_columns',
			'default'		=> '4',
			'control'		=> array (
				'label'		=> __( 'Related Products Columns', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'2'	=> '2',
					'3'	=> '3',
					'4'	=> '4',
					'5'	=> '5',
					'6'	=> '6'
				),
			),
		),
		array(
			'id'		=> 'woo_product_meta',
			'default'	=> 'on',
			'control'	=> array (
				'label'	=> __( 'Product Meta', 'wpex' ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'You must save your options and refresh your live site to preview changes to this setting.', 'wpex' ),
			),
		),
		array(
			'id'		=> 'woo_next_prev',
			'default'	=> 'on',
			'control'	=> array (
				'label'	=> __( 'Next & Previous Links', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/*	- Single
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_woocommerce_cart'] = array(
	'title'		=> __( 'Cart', 'wpex' ),
	'panel'		=> 'wpex_woocommerce',
	'settings'	=> array(
		array(
			'id'		=> 'woocommerce_cross_sells_count',
			'default'	=> '4',
			'control'	=> array (
				'label'	=> __( 'Cross-Sells Count', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'			=> 'woocommerce_cross_sells_columns',
			'default'		=> '4',
			'control'		=> array (
				'label'		=> __( 'Cross-Sells Columns', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'2'	=> '2',
					'3'	=> '3',
					'4'	=> '4',
					'5'	=> '5',
					'6'	=> '6'
				),
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/*	- Image Cropping
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_woocommerce_img_cropping'] = array(
	'title'	=> __( 'Image Cropping', 'wpex' ),
	'panel'	=> 'wpex_woocommerce',
	'desc'	=> __( 'This theme uses a built-in image resizing function based on the WordPress wp_get_image_editor() function so you can quickly alter the image sizes on your site without having to regenerate the thumbnails. This means every time you alter the values a new image is created and stored on your server, so think about it carefully before changing your values.', 'wpex' ),
	'settings'	=> array(
		array(
			'id'		=> 'woo_entry_width',
			'default'	=> '9999',
			'control'	=> array (
				'label'	=> __( 'Entry: Width', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'		=> 'woo_entry_height',
			'default'	=> '9999',
			'control'	=> array (
				'label'	=> __( 'Entry: Height', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'		=> 'woo_post_image_width',
			'default'	=> '9999',
			'control'	=> array (
				'label'	=> __( 'Post: Width', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'		=> 'woo_post_image_height',
			'default'	=> '9999',
			'control'	=> array (
				'label'	=> __( 'Post: Height', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'		=> 'woo_cat_entry_width',
			'default'	=> '9999',
			'control'	=> array (
				'label'	=> __( ' Category: Width', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'		=> 'woo_cat_entry_height',
			'default'	=> '9999',
			'control'	=> array (
				'label'	=> __( ' Category: Height', 'wpex' ),
				'type'	=> 'text',
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/*	- Extras - These options hook into other sections
/*-----------------------------------------------------------------------------------*/

// Social Sharing
$wp_customize->add_setting( 'social_share_woo', array(
	'type'		=> 'theme_mod',
	'default'	=> false,
) );
$wp_customize->add_control( 'social_share_woo', array(
	'label'		=>  __( 'WooCommerce: Social Share', 'wpex' ),
	'section'	=> 'wpex_social_sharing',
	'settings'	=> 'social_share_woo',
	'priority'	=> 10,
	'type'		=> 'checkbox',
) );