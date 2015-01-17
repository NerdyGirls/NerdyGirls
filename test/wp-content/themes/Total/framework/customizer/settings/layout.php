<?php
/**
 * Layout Panel
 *
 * @package		Total
 * @subpackage	Framework/Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

/*-----------------------------------------------------------------------------------*/
/*	- General
/*-----------------------------------------------------------------------------------*/

// Define General Section
$wp_customize->add_section( 'wpex_layout_general' , array(
	'title'		=> __( 'General', 'wpex' ),
	'priority'	=> 1,
	'panel'		=> 'wpex_layout',
) );

// Layout Style
$wp_customize->add_setting( 'main_layout_style', array(
	'type'		=> 'theme_mod',
	'default'	=> 'full-width',
	'transport'	=> 'postMessage',
) );
$wp_customize->add_control( 'main_layout_style', array(
	'label'		=> __( 'Layout Style', 'wpex' ),
	'section'	=> 'wpex_layout_general',
	'settings'	=> 'main_layout_style',
	'priority'	=> 1,
	'type'		=> 'select',
	'choices'	=> array(
		'full-width'	=> __( 'Full Width','wpex' ),
		'boxed'			=> __( 'Boxed','wpex' )
	),
) );

// Enable Responsiveness
$wp_customize->add_setting( 'responsive', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'responsive', array(
	'label'			=> __( 'Responsiveness', 'wpex' ),
	'section'		=> 'wpex_layout_general',
	'settings'		=> 'responsive',
	'priority'		=> 2,
	'type'			=> 'checkbox',
	'description'	=> __( 'If you are using the Visual Composer plugin, make sure to enable/disable the responsive settings at Settings->Visual composer as well.', 'wpex' ),
) );

/*-----------------------------------------------------------------------------------*/
/*	- Boxed Layout
/*-----------------------------------------------------------------------------------*/

// Define Boxed Section
$wp_customize->add_section( 'wpex_layout_boxed' , array(
	'title'			=> __( 'Boxed Layout', 'wpex' ),
	'priority'		=> 2,
	'panel'			=> 'wpex_layout',
) );

// Boxed Layout DropShadow
$wp_customize->add_setting( 'boxed_dropdshadow', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
	'transport'	=> 'postMessage',
) );
$wp_customize->add_control( 'boxed_dropdshadow', array(
	'label'		=> __( 'Boxed Layout Drop-Shadow', 'wpex' ),
	'section'	=> 'wpex_layout_boxed',
	'settings'	=> 'boxed_dropdshadow',
	'priority'	=> 1,
	'type'		=> 'checkbox',
) );


/*-----------------------------------------------------------------------------------*/
/*	- Desktop Widths
/*-----------------------------------------------------------------------------------*/

// Define Desktop Layout Section
$wp_customize->add_section( 'wpex_layout_desktop_widths' , array(
	'title'			=> __( 'Desktop Widths', 'wpex' ),
	'priority'		=> 3,
	'panel'			=> 'wpex_layout',
	'description'	=> __( 'For screens greater than or equal to 1281px. Accepts both pixels or percentage values.', 'wpex' )
) );

// Main Container Width
$wp_customize->add_setting( 'main_container_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'main_container_width', array(
	'label'			=> __( 'Main Container Width', 'wpex' ),
	'section'		=> 'wpex_layout_desktop_widths',
	'settings'		=> 'main_container_width',
	'priority'		=> 1,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 980px',
) );

// Content Width
$wp_customize->add_setting( 'left_container_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'left_container_width', array(
	'label'			=> __( 'Content Width', 'wpex' ),
	'section'		=> 'wpex_layout_desktop_widths',
	'settings'		=> 'left_container_width',
	'priority'		=> 2,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 680px',
) );

// Sidebar Width
$wp_customize->add_setting( 'sidebar_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'sidebar_width', array(
	'label'			=> __( 'Sidebar Width', 'wpex' ),
	'section'		=> 'wpex_layout_desktop_widths',
	'settings'		=> 'sidebar_width',
	'priority'		=> 3,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 250px',
) );


/*-----------------------------------------------------------------------------------*/
/*	- Medium Screen Widths
/*-----------------------------------------------------------------------------------*/

// Define Medium Screen Layout Section
$wp_customize->add_section( 'wpex_layout_medium_widths' , array(
	'title'			=> __( 'Medium Screens Widths', 'wpex' ),
	'priority'		=> 4,
	'panel'			=> 'wpex_layout',
	'description'	=> __( 'For screens between 960px - 1280px. Such as landscape tablets and small monitors/laptops. Accepts both pixels or percentage values.', 'wpex' )
) );

// Main Container Width
$wp_customize->add_setting( 'tablet_landscape_main_container_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'tablet_landscape_main_container_width', array(
	'label'			=> __( 'Main Container Width', 'wpex' ),
	'section'		=> 'wpex_layout_medium_widths',
	'settings'		=> 'tablet_landscape_main_container_width',
	'priority'		=> 1,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 980px',
) );

// Content Width
$wp_customize->add_setting( 'tablet_landscape_left_container_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'tablet_landscape_left_container_width', array(
	'label'			=> __( 'Content Width', 'wpex' ),
	'section'		=> 'wpex_layout_medium_widths',
	'settings'		=> 'tablet_landscape_left_container_width',
	'priority'		=> 2,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 680px',
) );

// Sidebar Width
$wp_customize->add_setting( 'tablet_landscape_sidebar_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'tablet_landscape_sidebar_width', array(
	'label'			=> __( 'Sidebar Width', 'wpex' ),
	'section'		=> 'wpex_layout_medium_widths',
	'settings'		=> 'tablet_landscape_sidebar_width',
	'priority'		=> 3,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 250px',
) );

/*-----------------------------------------------------------------------------------*/
/*	- Tablet Portrait Widths
/*-----------------------------------------------------------------------------------*/

// Define Tablet Layout Section
$wp_customize->add_section( 'wpex_layout_tablet_widths' , array(
	'title'			=> __( 'Tablet Widths', 'wpex' ),
	'priority'		=> 5,
	'panel'			=> 'wpex_layout',
	'description'	=> __( 'For screens between 768px - 959px. Such as portrait tablet. Accepts both pixels or percentage values.', 'wpex' )
) );

// Main Container Width
$wp_customize->add_setting( 'tablet_main_container_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'tablet_main_container_width', array(
	'label'			=> __( 'Main Container Width', 'wpex' ),
	'section'		=> 'wpex_layout_tablet_widths',
	'settings'		=> 'tablet_main_container_width',
	'priority'		=> 1,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 700px',
) );

// Content Width
$wp_customize->add_setting( 'tablet_left_container_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'tablet_left_container_width', array(
	'label'			=> __( 'Content Width', 'wpex' ),
	'section'		=> 'wpex_layout_tablet_widths',
	'settings'		=> 'tablet_left_container_width',
	'priority'		=> 2,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 100%',
) );

// Sidebar Width
$wp_customize->add_setting( 'tablet_sidebar_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'tablet_sidebar_width', array(
	'label'			=> __( 'Sidebar Width', 'wpex' ),
	'section'		=> 'wpex_layout_tablet_widths',
	'settings'		=> 'tablet_sidebar_width',
	'priority'		=> 3,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 100%',
) );

/*-----------------------------------------------------------------------------------*/
/*	- Mobile Phone Widths
/*-----------------------------------------------------------------------------------*/

// Define Mobile Phone Layout Section
$wp_customize->add_section( 'wpex_layout_phone_widths' , array(
	'title'			=> __( 'Mobile Phone Widths', 'wpex' ),
	'priority'		=> 6,
	'panel'			=> 'wpex_layout',
	'description'	=> __( 'For screens between 0 - 767px. Accepts both pixels or percentage values.', 'wpex' )
) );

// Landscape Width
$wp_customize->add_setting( 'mobile_landscape_main_container_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'mobile_landscape_main_container_width', array(
	'label'			=>  __( 'Landscape: Main Container Width', 'wpex' ),
	'section'		=> 'wpex_layout_phone_widths',
	'settings'		=> 'mobile_landscape_main_container_width',
	'priority'		=> 1,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 90%',
) );

// Portrait Width
$wp_customize->add_setting( 'mobile_portrait_main_container_width', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'mobile_portrait_main_container_width', array(
	'label'			=>  __( 'Portrait: Main Container Width', 'wpex' ),
	'section'		=> 'wpex_layout_phone_widths',
	'settings'		=> 'mobile_portrait_main_container_width',
	'priority'		=> 1,
	'type'			=> 'text',
	'description'	=> _x( 'Default:', 'wpex', 'Customizer' ) .' 90%',
) );