<?php
/**
 * Menu Customizer Options
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
$wp_customize->add_section( 'wpex_menu_general' , array(
	'title'		=> __( 'General', 'wpex' ),
	'priority'	=> 1,
	'panel'		=> 'wpex_menu',
) );

// Top Dropdown Icons
$wp_customize->add_setting( 'menu_arrow_down', array(
	'type'		=> 'theme_mod',
	'default'	=> true,
) );
$wp_customize->add_control( 'menu_arrow_down', array(
	'label'		=> __( 'Top Level Dropdown Icon', 'wpex' ),
	'section'	=> 'wpex_menu_general',
	'settings'	=> 'menu_arrow_down',
	'priority'	=> 1,
	'type'		=> 'checkbox',
) );

// Second+ Level Dropdown Icon
$wp_customize->add_setting( 'menu_arrow_side', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'menu_arrow_side', array(
	'label'		=> __( 'Second+ Level Dropdown Icon', 'wpex' ),
	'section'	=> 'wpex_menu_general',
	'settings'	=> 'menu_arrow_side',
	'priority'	=> 2,
	'type'		=> 'checkbox',
) );

// Dropdown Top Border
$wp_customize->add_setting( 'menu_dropdown_top_border', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'menu_dropdown_top_border', array(
	'label'		=> __( 'Dropdown Top Border', 'wpex' ),
	'section'	=> 'wpex_menu_general',
	'settings'	=> 'menu_dropdown_top_border',
	'priority'	=> 3,
	'type'		=> 'checkbox',
) );

/*-----------------------------------------------------------------------------------*/
/*	- Mobile
/*-----------------------------------------------------------------------------------*/

// Define Section
$wp_customize->add_section( 'wpex_menu_mobile' , array(
	'title'		=> __( 'Mobile', 'wpex' ),
	'priority'	=> 2,
	'panel'		=> 'wpex_menu',
) );

// Mobile Menu Style
$wp_customize->add_setting( 'mobile_menu_style', array(
	'type'		=> 'theme_mod',
	'default'	=> 'sidr',
) );
$wp_customize->add_control( 'mobile_menu_style', array(
	'label'		=> __( 'Mobile Menu Style', 'wpex' ),
	'section'	=> 'wpex_menu_mobile',
	'settings'	=> 'mobile_menu_style',
	'priority'	=> 1,
	'type'		=> 'select',
	'choices'	=> array(
		'sidr'		=> __( 'Sidebar','wpex' ),
		'toggle'	=> __( 'Toggle','wpex' )
	),
) );

// Sidr Direction
$wp_customize->add_setting( 'mobile_menu_sidr_direction', array(
	'type'		=> 'theme_mod',
	'default'	=> 'left',
) );
$wp_customize->add_control( 'mobile_menu_sidr_direction', array(
	'label'		=> __( 'Sidebar Mobile Menu Direction', 'wpex' ),
	'section'	=> 'wpex_menu_mobile',
	'settings'	=> 'mobile_menu_sidr_direction',
	'priority'	=> 1,
	'type'		=> 'select',
	'choices'	=> array(
		'left'	=> __( 'Left','wpex' ),
		'right'	=> __( 'Right','wpex' ),
	),
) );