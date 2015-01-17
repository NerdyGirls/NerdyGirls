<?php
/**
 * General Panel
 *
 * @package		Total
 * @subpackage	Framework/Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

/*-----------------------------------------------------------------------------------*/
/*	- Skins - Coming Soon!!
/*-----------------------------------------------------------------------------------

if ( function_exists( 'wpex_skins' ) ) {

	$skins		= wpex_skins();
	$choices	= array();

	foreach ( $skins as $key => $val ) {
		if ( isset ( $val['name'] ) ) {
			$choices[$key] = $val['name'];
		}
	}

	if ( $choices ) {
		$this->sections['wpex_skins'] = array(
			'title'		=> __( 'Skin', 'wpex' ),
			'panel'		=> 'wpex_general',
			'settings'	=> array(
				array(
					'id'		=> 'theme_skin',
					'default'	=> true,
					'control'	=> array (
						'label'		=>	__( 'Theme Skin', 'wpex' ),
						'type'		=> 'select',
						'choices'	=> $choices,
					),
				),
			),
		);
	}

}

/*-----------------------------------------------------------------------------------*/
/*	- Background
/*-----------------------------------------------------------------------------------*/
$patterns_url = get_template_directory_uri() .'/images/patterns/';
$this->sections['wpex_background_background'] = array(
	'title'		=> __( 'Site Background', 'wpex' ),
	'panel'		=> 'wpex_general',
	'desc'		=> __( 'Here you can alter the global site background. It is highly recommended that you first set the site layout to "Boxed" at "Layout->General"', 'wpex' ),
	'settings'	=> array(
		array(
			'id'		=> 'background_color',
			'control'	=> array (
				'label'		=>	__( 'Background Color', 'wpex' ),
				'type'		=> 'color',
				'object'	=> 'WP_Customize_Color_Control',
			),
		),
		array(
			'id'		=> 'background_image',
			'control'	=> array (
				'label'		=>	__( 'Custom Background Image', 'wpex' ),
				'type'		=> 'image',
				'object'	=> 'WP_Customize_Image_Control',
			),
		),
		array(
			'id'		=> 'background_style',
			'default'	=> 'stretched',
			'control'	=> array (
				'label'		=>	__( 'Background Image Style', 'wpex' ),
				'type'		=> 'image',
				'type'		=> 'select',
				'choices'	=> array(
					'stretched'	=> __( 'Stretched', 'wpex' ),
					'repeat'	=> __( 'Repeat', 'wpex' ),
					'fixed'		=> __( 'Center Fixed', 'wpex' ),
				),
			),
		),
		array(
			'id'		=> 'background_pattern',
			'control'	=> array (
				'label'		=>	__( 'Background Pattern', 'wpex' ),
				'type'		=> 'image',
				'type'		=> 'select',
				'choices'	=> array(
					''									=> __( 'None', 'wpex' ),
					$patterns_url .'dark_wood.png'		=> __( 'Dark Wood', 'wpex' ),
					$patterns_url .'diagmonds.png'		=> __( 'Diamonds', 'wpex' ),
					$patterns_url .'grilled.png'		=> __( 'Grilled', 'wpex' ),
					$patterns_url .'lined_paper.png'	=> __( 'Lined Paper', 'wpex' ),
					$patterns_url .'old_wall.png'		=> __( 'Old Wall', 'wpex' ),
					$patterns_url .'ricepaper2.png'		=> __( 'Rice Paper', 'wpex' ),
					$patterns_url .'tree_bark.png'		=> __( 'Tree Bark', 'wpex' ),
					$patterns_url .'triangular.png'		=> __( 'Triangular', 'wpex' ),
					$patterns_url .'white_plaster.png'	=> __( 'White Plaster', 'wpex' ),
					$patterns_url .'wild_flowers.png'	=> __( 'Wild Flowers', 'wpex' ),
					$patterns_url .'wood_pattern.png'	=> __( 'Wood Pattern', 'wpex' ),
				),
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/*	- Social Sharing Section
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_social_sharing'] = array(
	'title'		=> __( 'Social Sharing', 'wpex' ),
	'panel'		=> 'wpex_general',
	'settings'	=> array(
		array(
			'id'		=> 'social_share_heading',
			'default'	=> __( 'Please Share This', 'wpex' ),
			'transport'	=> 'postMessage',
			'control'	=> array (
				'label'	=>	__( 'Heading', 'wpex' ),
				'type'	=> 'text',
				'desc'	=> __( 'Used for the horizontal style', 'wpex' ),
			),
		),
		array(
			'id'		=> 'social_share_position',
			'default'	=> 'horizontal',
			'control'	=> array (
				'label'	=>	__( 'Position', 'wpex' ),
				'type'	=> 'select',
				'choices'	=> array(
					'horizontal'	=> __( 'Horizontal', 'wpex' ),
					'vertical'		=> __( 'Vertical', 'wpex' ),
				),
			),
		),
		array(
			'id'		=> 'social_share_style',
			'default'	=> 'minimal',
			'control'	=> array (
				'label'	=>	__( 'Style', 'wpex' ),
				'type'	=> 'select',
				'choices'	=> array(
					'minimal'	=> __( 'Minimal','wpex' ),
					'flat'		=> __( 'Flat','wpex' ),
					'three-d'	=> __( '3D','wpex' ),
				),
			),
		),
		array(
			'id'		=> 'social_share_sites',
			'default'	=> array( 'twitter', 'facebook', 'google_plus', 'pinterest', 'linkedin' ),
			'control'	=> array (
				'label'		=>	__( 'Sites', 'wpex' ),
				'type'		=> 'multiple-select',
				'object'	=> 'WPEX_Customize_Control_Multiple_Select',
				'choices'		=> array(
					'twitter'		=> __( 'Twitter', 'wpex' ),
					'facebook'		=> __( 'Facebook', 'wpex' ),
					'google_plus'	=> __( 'Google Plus', 'wpex' ),
					'pinterest'		=> __( 'Pinterest', 'wpex' ),
					'linkedin'		=> __( 'LinkedIn', 'wpex' ),
				),
			),
		),
		array(
			'id'		=> 'social_share_blog_entries',
			'default'	=> false,
			'control'	=> array (
				'label'	=>	__( 'Blog Entries: Social Share', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'social_share_blog_posts',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Blog Posts: Social Share', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'social_share_pages',
			'default'	=> false,
			'control'	=> array (
				'label'	=>	__( 'Pages: Social Share', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/*	- Lightbox
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_lightbox'] = array(
	'title'		=> __( 'Lightbox', 'wpex' ),
	'panel'		=> 'wpex_general',
	'settings'	=> array(
		array(
			'id'		=> 'lightbox_skin',
			'default'	=> 'dark',
			'control'	=> array (
				'label'	=>	__( 'Skin', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'dark'			=> __( 'Dark', 'wpex' ),
					'light'			=> __( 'Light', 'wpex' ),
					'mac'			=> __( 'Mac', 'wpex' ),
					'metro-black'	=> __( 'Metro Black', 'wpex' ),
					'metro-white'	=> __( 'Metro White', 'wpex' ),
					'parade'		=> __( 'Parade', 'wpex' ),
					'smooth'		=> __( 'Smooth', 'wpex' ),
				),
			),
		),
		array(
			'id'		=> 'lightbox_thumbnails',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Gallery Thumbnails', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'lightbox_arrows',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Gallery Arrows', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'lightbox_mousewheel',
			'default'	=> false,
			'control'	=> array (
				'label'	=>	__( 'Gallery Mousewheel Scroll', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'lightbox_titles',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Titles', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'lightbox_fullscreen',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Fullscreen Button', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/*	- Breadcrumbs
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_breadcrumbs'] = array(
	'title'		=> __( 'Breadcrumbs', 'wpex' ),
	'panel'		=> 'wpex_general',
	'settings'	=> array(
		array(
			'id'		=> 'breadcrumbs',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Breadcrumbs', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'breadcrumbs_position',
			'control'	=> array (
				'label'		=>	__( 'Position', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					''				=> __( 'Absolute Right', 'wpex' ),
					'under-title'	=> __( 'Under Title', 'wpex' ),
				),
			),
		),
		array(
			'id'		=> 'breadcrumbs_home_title',
			'transport'	=> 'postMessage',
			'control'	=> array (
				'label'	=>	__( 'Custom Home Title', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'		=> 'breadcrumbs_title_trim',
			'default'	=> '4',
			'control'	=> array (
				'label'	=>	__( 'Breadcrumbs: Title Trim Length', 'wpex' ),
				'type'	=> 'text',
				'desc'	=> __( 'Enter the max number of words to display for your breadcrumbs post title', 'wpex' ),
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/*	- Page Title
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_page_header'] = array(
	'title'		=> __( 'Page Title', 'wpex' ),
	'panel'		=> 'wpex_general',
	'desc'		=> __( 'This is the area above posts and pages with the title and breadcrumbs', 'wpex' ),
	'settings'	=> array(
		array(
			'id'		=> 'page_header_style',
			'default'	=> '',
			'control'	=> array (
				'label'		=>	__( 'Page Header Style', 'wpex' ),
				'type'		=> 'image',
				'type'		=> 'select',
				'choices'	=> array(
					''					=> __( 'Default','wpex' ),
					'centered'			=> __( 'Centered', 'wpex' ),
					'centered-minimal'	=> __( 'Centered Minimal', 'wpex' ),
				),
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/*	- Pages
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_pages'] = array(
	'title'		=> __( 'Pages', 'wpex' ),
	'panel'		=> 'wpex_general',
	'settings'	=> array(
		array(
			'id'		=> 'page_single_layout',
			'default'	=> true,
			'default'	=> 'right-sidebar',
			'control'	=> array (
				'label'	=>	__( 'Layout', 'wpex' ),
				'type'		=> 'select',
				'choices'	=> array(
					'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
					'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
					'full-width'	=> __( 'No Sidebar','wpex' ),
				),
			),
		),
		array(
			'id'		=> 'pages_custom_sidebar',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Custom Sidebar', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'page_comments',
			'control'	=> array (
				'label'	=>	__( 'Comments on Pages', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'page_featured_image',
			'control'	=> array (
				'label'	=>	__( 'Display Featured Images', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/*	- Search
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_search'] = array(
	'title'		=> __( 'Search', 'wpex' ),
	'panel'		=> 'wpex_general',
	'settings'	=> array(
		array(
			'id'		=> 'search_custom_sidebar',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Custom Sidebar', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'search_posts_per_page',
			'default'	=> '10',
			'control'	=> array (
				'label'	=>	__( 'Posts Per Page', 'wpex' ),
				'type'	=> 'text',
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/*	- WP Gallery
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_wp_gallery'] = array(
	'title'		=> __( 'WP Gallery', 'wpex' ),
	'panel'		=> 'wpex_general',
	'settings'	=> array(
		array(
			'id'		=> 'custom_wp_gallery',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Custom WP Gallery Output', 'wpex' ),
				'type'	=> 'checkbox',
			),
		),
		array(
			'id'		=> 'gallery_image_width',
			'default'	=>'9999',
			'control'	=> array (
				'label'	=>	__( 'Image: Height', 'wpex' ),
				'type'	=> 'text',
			),
		),
		array(
			'id'		=> 'gallery_image_height',
			'default'	=> '9999',
			'control'	=> array (
				'label'	=>	__( 'Image: Width', 'wpex' ),
				'type'	=> 'text',
			),
		),
	),
);