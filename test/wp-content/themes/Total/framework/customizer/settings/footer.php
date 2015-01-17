<?php
/**
 * Footer Customizer Options
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
$wp_customize->add_section( 'wpex_footer_general' , array(
	'title'		=> __( 'General', 'wpex' ),
	'priority'	=> 1,
	'panel'		=> 'wpex_footer',
) );

$wp_customize->add_setting( 'footer_reveal', array(
	'type'		=> 'theme_mod',
) );
$wp_customize->add_control( 'footer_reveal', array(
	'label'			=> __( 'Footer Reveal', 'wpex' ),
	'section'		=> 'wpex_footer_general',
	'settings'		=> 'footer_reveal',
	'priority'		=> 1,
	'type'			=> 'checkbox',
	'description'	=> __( 'Enable the footer reveal style. The footer will be placed in a fixed postion and display on scroll. This setting is for the "Full-Width" layout only and desktops only.', 'wpex' ),
) );

$wp_customize->add_setting( 'footer_widgets', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'footer_widgets', array(
	'label'		=> __( 'Footer Widgets', 'wpex' ),
	'section'	=> 'wpex_footer_general',
	'settings'	=> 'footer_widgets',
	'priority'	=> 2,
	'type'		=> 'checkbox',
) );

$wp_customize->add_setting( 'footer_headings', array(
	'type'		=> 'theme_mod',
	'default'	=> 'div',
) );
$wp_customize->add_control( 'footer_headings', array(
	'label'		=> __( 'Footer Widget Title Headings', 'wpex' ),
	'section'	=> 'wpex_footer_general',
	'settings'	=> 'footer_headings',
	'priority'	=> 3,
	'type'		=> 'select',
	'choices'	=> array(
		'h2'	=> 'h2',
		'h3'	=> 'h3',
		'h4'	=> 'h4',
		'h5'	=> 'h5',
		'h6'	=> 'h6',
		'span'	=> 'span',
		'div'	=> 'div',
	),
) );

$wp_customize->add_setting( 'footer_widgets_columns', array(
	'type'		=> 'theme_mod',
	'default'	=> '4',
) );
$wp_customize->add_control( 'footer_widgets_columns', array(
	'label'		=> __( 'Footer Widgets', 'wpex' ),
	'section'	=> 'wpex_footer_general',
	'settings'	=> 'footer_widgets_columns',
	'priority'	=> 4,
	'type'		=> 'select',
	'choices'	=> array(
		'4'	=> '4',
		'3'	=> '3',
		'2'	=> '2',
		'1'	=> '1',
	),
) );

$wp_customize->add_setting( 'footer_bottom', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'footer_bottom', array(
	'label'		=> __( 'Bottom Footer Area', 'wpex' ),
	'section'	=> 'wpex_footer_general',
	'settings'	=> 'footer_bottom',
	'priority'	=> 5,
	'type'		=> 'checkbox',
) );

$wp_customize->add_setting( 'footer_copyright_text', array(
	'type'		=> 'theme_mod',
	'default'	=> 'Copyright <a href="http://sympleworkz.com">Symple Workz LLC.</a> - All Rights Reserved',
) );
$wp_customize->add_control( 'footer_copyright_text', array(
	'label'		=> __( 'Copyright', 'wpex' ),
	'section'	=> 'wpex_footer_general',
	'settings'	=> 'footer_copyright_text',
	'priority'	=> 6,
	'type'		=> 'textarea',
) );

$wp_customize->add_setting( 'scroll_top', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'scroll_top', array(
	'label'		=> __( 'Scroll Up Button', 'wpex' ),
	'section'	=> 'wpex_footer_general',
	'settings'	=> 'scroll_top',
	'priority'	=> 7,
	'type'		=> 'checkbox',
) );