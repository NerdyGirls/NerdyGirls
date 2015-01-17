<?php
/**
 * Toggle Bar Panel
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

// Define Section
$wp_customize->add_section( 'wpex_togglebar_general' , array(
	'title'			=> __( 'General', 'wpex' ),
	'priority'		=> 1,
	'panel'			=> 'wpex_togglebar',
) );

// Enable Toggle bar
$wp_customize->add_setting( 'toggle_bar', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'toggle_bar', array(
	'label'		=> __( 'Enable', 'wpex' ),
	'section'	=> 'wpex_togglebar_general',
	'settings'	=> 'toggle_bar',
	'priority'	=> 1,
	'type'		=> 'checkbox',
) );

// Toggle bar content
$wp_customize->add_setting( 'toggle_bar_page', array(
	'type'		=> 'theme_mod',
	'default'	=> '',
) );
$wp_customize->add_control( 'toggle_bar_page', array(
	'label'		=> __( 'Content', 'wpex' ),
	'section'	=> 'wpex_togglebar_general',
	'settings'	=> 'toggle_bar_page',
	'priority'	=> 2,
	'type'		=> 'dropdown-pages',
) );

// Visibility
$wp_customize->add_setting( 'toggle_bar_visibility', array(
	'type'		=> 'theme_mod',
	'default'	=> 'hidden-phone',
) );
$wp_customize->add_control( 'toggle_bar_visibility', array(
	'label'		=> __( 'Visibility', 'wpex' ),
	'section'	=> 'wpex_togglebar_general',
	'settings'	=> 'toggle_bar_visibility',
	'priority'	=> 3,
	'type'		=> 'select',
	'choices'	=> array(
		'always-visible'	=> __( 'Always Visible', 'wpex' ),
		'hidden-phone'		=> __( 'Hidden on Phones', 'wpex' ),
		'hidden-tablet'		=> __( 'Hidden on Tablets', 'wpex' ),
		'hidden-desktop'	=> __( 'Hidden on Desktop', 'wpex' ),
		'visible-desktop'	=> __( 'Visible on Desktop Only', 'wpex' ),
		'visible-phone'		=> __( 'Visible on Phones Only', 'wpex' ),
		'visible-tablet'	=> __( 'Visible on Tablets Only', 'wpex' ),
	)
) );

// Animation
$wp_customize->add_setting( 'toggle_bar_animation', array(
	'type'		=> 'theme_mod',
	'default'	=> 'fade',
) );
$wp_customize->add_control( 'toggle_bar_animation', array(
	'label'		=> __( 'Toggle Bar Animation', 'wpex' ),
	'section'	=> 'wpex_togglebar_general',
	'settings'	=> 'toggle_bar_animation',
	'priority'	=> 4,
	'type'		=> 'select',
	'choices'	=> array(
		'fade'			=> __( 'Fade', 'wpex' ),
		'fade-slide'	=> __( 'Fade & Slide Down', 'wpex' ),
	)
) );