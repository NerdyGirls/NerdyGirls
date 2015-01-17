<?php
/**
 * Header Customizer Options
 *
 * @package		Total
 * @subpackage	Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

/*-----------------------------------------------------------------------------------*/
/*	- General
/*-----------------------------------------------------------------------------------*/

// Define Section
$wp_customize->add_section( 'wpex_header_general' , array(
	'title'		=> __( 'General', 'wpex' ),
	'priority'	=> 1,
	'panel'		=> 'wpex_header',
) );

// Style
$wp_customize->add_setting( 'header_style', array(
	'type'		=> 'theme_mod',
	'default'	=> 'one',
) );
$wp_customize->add_control( 'header_style', array(
	'label'		=> __( 'Header Style', 'wpex' ),
	'section'	=> 'wpex_header_general',
	'settings'	=> 'header_style',
	'priority'	=> 1,
	'type'		=> 'select',
	'choices'	=> array(
		'one'	=> __( 'One','wpex' ),
		'two'	=> __( 'Two','wpex' ),
		'three'	=> __( 'Three','wpex' )
	),
) );

// Custom Height
$wp_customize->add_setting( 'header_height', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'header_height', array(
	'label'			=> __( 'Custom Header Height', 'wpex' ),
	'section'		=> 'wpex_header_general',
	'settings'		=> 'header_height',
	'priority'		=> 2,
	'type'			=> 'text',
	'description'	=> __( 'Use this setting to define a fixed header height (Header Style One Only. Use this option ONLY if you want the navigation drop-downs to fall right under the header. Remove the default height (leave this field empty) if you want the header to auto expand depending on your logo height.', 'wpex' )
) );

/*-----------------------------------------------------------------------------------*/
/*	- Fixed On Scroll
/*-----------------------------------------------------------------------------------*/

// Define Section
$wp_customize->add_section( 'wpex_header_fixed' , array(
	'title'			=> __( 'Fixed Header', 'wpex' ),
	'priority'		=> 2,
	'panel'			=> 'wpex_header',
) );

// Fixed Header
$wp_customize->add_setting( 'fixed_header', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'fixed_header', array(
	'label'			=> __( 'Fixed Header on Scroll', 'wpex' ),
	'section'		=> 'wpex_header_fixed',
	'settings'		=> 'fixed_header',
	'priority'		=> 1,
	'type'			=> 'checkbox',
	'description'	=> __( 'For some header styles the entire header will be fixed for others only the menu.', 'wpex' )
) );

// Shrink Fixed Header
$wp_customize->add_setting( 'shink_fixed_header', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'shink_fixed_header', array(
	'label'		=> __( 'Shrink Fixed Header', 'wpex' ),
	'section'	=> 'wpex_header_fixed',
	'settings'	=> 'shink_fixed_header',
	'priority'	=> 2,
	'type'		=> 'checkbox',
) );

// Sticky header on mobile
$wp_customize->add_setting( 'fixed_header_mobile', array(
	'type'		=> 'theme_mod',
	'default'	=> false,
) );
$wp_customize->add_control( 'fixed_header_mobile', array(
	'label'			=> __( 'Fixed Header On Mobile', 'wpex' ),
	'section'		=> 'wpex_header_fixed',
	'settings'		=> 'fixed_header_mobile',
	'priority'		=> 3,
	'type'			=> 'checkbox',
	'description'	=> __( 'For header style one only', 'wpex' ),
) );

/*-----------------------------------------------------------------------------------*/
/*	- Search
/*-----------------------------------------------------------------------------------*/

// Define Section
$wp_customize->add_section( 'wpex_header_search' , array(
	'title'			=> __( 'Search', 'wpex' ),
	'priority'		=> 3,
	'panel'			=> 'wpex_header',
) );

// Enable Search
$wp_customize->add_setting( 'main_search', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'main_search', array(
	'label'		=> __( 'Header Search', 'wpex' ),
	'section'	=> 'wpex_header_search',
	'settings'	=> 'main_search',
	'priority'	=> 1,
	'type'		=> 'checkbox',
) );

// Search Style
$wp_customize->add_setting( 'main_search_toggle_style', array(
	'type'			=> 'theme_mod',
	'default'		=> 'drop_down',
) );
$wp_customize->add_control( 'main_search_toggle_style', array(
	'label'		=> __( 'Header Search Style', 'wpex' ), 
	'section'	=> 'wpex_header_search',
	'settings'	=> 'main_search_toggle_style',
	'priority'	=> 1,
	'type'		=> 'select',
	'choices'	=> array(
		'drop_down'			=> __( 'Drop Down','wpex' ),
		'overlay'			=> __( 'Site Overlay','wpex' ),
		'header_replace'	=> __( 'Header Replace','wpex' )
	),
) );


/*-----------------------------------------------------------------------------------*/
/*	- Logo
/*-----------------------------------------------------------------------------------*/

// Define Logo Section
$wp_customize->add_section( 'wpex_header_logo' , array(
	'title'		=> __( 'Logo', 'wpex' ),
	'priority'	=> 4,
	'panel'		=> 'wpex_header',
) );

// Logo Icon
$wp_customize->add_setting( 'logo_icon', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'logo_icon', array(
	'label'		=> __( 'Text Logo Icon', 'wpex' ),
	'section'	=> 'wpex_header_logo',
	'settings'	=> 'logo_icon',
	'priority'	=> 1,
	'type'		=> 'select',
	'choices'	=> wpex_get_awesome_icons(),
) );

// Logo Image
$wp_customize->add_setting( 'custom_logo', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,  'custom_logo', array(
	'label'		=> __( 'Image Logo', 'wpex' ),
	'section'	=> 'wpex_header_logo',
	'settings'	=> 'custom_logo',
	'priority'	=> 4,
) ) );

// Retina Logo Image
$wp_customize->add_setting( 'retina_logo', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,  'retina_logo', array(
	'label'		=> __( 'Retina Image Logo', 'wpex' ),
	'section'	=> 'wpex_header_logo',
	'settings'	=> 'retina_logo',
	'priority'	=> 5,
) ) );

// Standard Logo height
$wp_customize->add_setting( 'retina_logo_height', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'retina_logo_height', array(
	'label'			=> __( 'Standard Retina Logo Height', 'wpex' ),
	'section'		=> 'wpex_header_logo',
	'settings'		=> 'retina_logo_height',
	'priority'		=> 6,
	'type'			=> 'text',
	'description'	=> __( 'Enter the height in pixels of your standard logo size in order to mantain proportions for your retina logo.', 'wpex' ),
) );

// Desktop Logo max Width
$wp_customize->add_setting( 'logo_max_width', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'logo_max_width', array(
	'label'			=> __( 'Logo Max Width: Desktop', 'wpex' ),
	'section'		=> 'wpex_header_logo',
	'settings'		=> 'logo_max_width',
	'priority'		=> 7,
	'type'			=> 'text',
	'description'	=> __( 'Screens 960px wide and greater.', 'wpex' ),
) );

// Tablet Portrait Logo max Width
$wp_customize->add_setting( 'logo_max_width_tablet_portrait', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'logo_max_width_tablet_portrait', array(
	'label'			=> __( 'Logo Max Width: Tablet Portrait', 'wpex' ),
	'section'		=> 'wpex_header_logo',
	'settings'		=> 'logo_max_width_tablet_portrait',
	'priority'		=> 8,
	'type'			=> 'text',
	'description'	=> __( 'Screens 768px-959px wide.', 'wpex' ),
) );

// Phone Portrait Logo max Width
$wp_customize->add_setting( 'logo_max_width_phone', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'logo_max_width_phone', array(
	'label'			=> __( 'Logo Max Width: Phone', 'wpex' ),
	'section'		=> 'wpex_header_logo',
	'settings'		=> 'logo_max_width_phone',
	'priority'		=> 9,
	'type'			=> 'text',
	'description'	=> __( 'Screens smaller then 767px wide.', 'wpex' ),
) );

// Logo Top Margin
$wp_customize->add_setting( 'logo_top_margin', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'logo_top_margin', array(
	'label'			=> __( 'Logo Top Margin', 'wpex' ),
	'section'		=> 'wpex_header_logo',
	'settings'		=> 'logo_top_margin',
	'priority'		=> 10,
	'type'			=> 'text',
	'description'	=> __( 'Will only work with the "Custom Header Height" option is left empty', 'wpex' ),
) );

// Logo Bottom Margin
$wp_customize->add_setting( 'logo_bottom_margin', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'logo_bottom_margin', array(
	'label'			=> __( 'Logo Bottom Margin', 'wpex' ),
	'section'		=> 'wpex_header_logo',
	'settings'		=> 'logo_bottom_margin',
	'priority'		=> 11,
	'type'			=> 'text',
	'description'	=> __( 'Will only work with the "Custom Header Height" option is left empty', 'wpex' ),
) );

/*-----------------------------------------------------------------------------------*/
/*	- Aside
/*-----------------------------------------------------------------------------------*/

// Define Section
$wp_customize->add_section( 'wpex_header_aside' , array(
	'title'			=> __( 'Aside Content', 'wpex' ),
	'priority'		=> 5,
	'panel'			=> 'wpex_header',
	'description'	=> __( 'The "aside" content for the header is displayed in various header styles, but not all of them.'),
) );

// Header aside content
$wp_customize->add_setting( 'header_aside', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'header_aside', array(
	'label'			=> __( 'Header Aside Content', 'wpex' ),
	'section'		=> 'wpex_header_aside',
	'settings'		=> 'header_aside',
	'priority'		=> 1,
	'type'			=> 'textarea',
) );