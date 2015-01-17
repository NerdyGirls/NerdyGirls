<?php
/**
 * Footer Customizer Options
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
$wp_customize->add_section( 'wpex_callout_general' , array(
	'title'		=> __( 'General', 'wpex' ),
	'priority'	=> 1,
	'panel'		=> 'wpex_callout',
) );

$wp_customize->add_setting( 'callout', array(
	'type'		=> 'theme_mod',
	'default'	=> '1',
) );
$wp_customize->add_control( 'callout', array(
	'label'			=> __( 'Enable', 'wpex' ),
	'section'		=> 'wpex_callout_general',
	'settings'		=> 'callout',
	'priority'		=> 1,
	'type'			=> 'checkbox',
) );

// Visibility
$wp_customize->add_setting( 'callout_visibility', array(
	'type'		=> 'theme_mod',
	'default'	=> 'always-visible',
) );
$wp_customize->add_control( 'callout_visibility', array(
	'label'		=> __( 'Visibility', 'wpex' ),
	'section'	=> 'wpex_callout_general',
	'settings'	=> 'callout_visibility',
	'priority'	=> 2,
	'type'		=> 'select',
	'choices'	=> array(
		'always-visible'	=> __( 'Always Visible', 'wpex' ),
		'hidden-phone'		=> __( 'Hidden on Phones', 'wpex' ),
		'hidden-tablet'		=> __( 'Hidden on Tablets', 'wpex' ),
		'hidden-desktop'	=> __( 'Hidden on Desktop', 'wpex' ),
		'visible-desktop'	=> __( 'Visible on Desktop Only', 'wpex' ),
		'visible-phone'		=> __( 'Visible on Phones Only', 'wpex' ),
		'visible-tablet'	=> __( 'Visible on Tablets Only', 'wpex' ),
	),
) );


/*-----------------------------------------------------------------------------------*/
/*	- Text
/*-----------------------------------------------------------------------------------*/
$wp_customize->add_section( 'wpex_callout_content' , array(
	'title'		=> __( 'Text', 'wpex' ),
	'priority'	=> 2,
	'panel'		=> 'wpex_callout',
) );

$wp_customize->add_setting( 'callout_text', array(
	'type'		=> 'theme_mod',
	'default'	=> 'I am the footer call-to-action block, here you can add some relevant/important information about your company or product. I can be disabled in the theme options.',
) );
$wp_customize->add_control( 'callout_text', array(
	'label'			=> false,
	'section'		=> 'wpex_callout_content',
	'settings'		=> 'callout_text',
	'priority'		=> 1,
	'type'			=> 'textarea',
) );

/*-----------------------------------------------------------------------------------*/
/*	- Link / Button
/*-----------------------------------------------------------------------------------*/
$wp_customize->add_section( 'wpex_callout_button' , array(
	'title'		=> __( 'Button', 'wpex' ),
	'priority'	=> 3,
	'panel'		=> 'wpex_callout',
) );

$wp_customize->add_setting( 'callout_link', array(
	'type'		=> 'theme_mod',
	'default'	=> 'http://www.wpexplorer.com',
) );
$wp_customize->add_control( 'callout_link', array(
	'label'			=> __( 'Link URL', 'wpex' ),
	'section'		=> 'wpex_callout_button',
	'settings'		=> 'callout_link',
	'priority'		=> 1,
	'type'			=> 'text',
) );

$wp_customize->add_setting( 'callout_link_txt', array(
	'type'		=> 'theme_mod',
	'default'	=> 'Get In Touch',
) );
$wp_customize->add_control( 'callout_link_txt', array(
	'label'			=> __( 'Link Text', 'wpex' ),
	'section'		=> 'wpex_callout_button',
	'settings'		=> 'callout_link_txt',
	'priority'		=> 2,
	'type'			=> 'text',
) );

$wp_customize->add_setting( 'callout_button_target', array(
	'type'		=> 'theme_mod',
	'default'	=> 'blank',
) );
$wp_customize->add_control( 'callout_button_target', array(
	'label'			=> __( 'Link Target', 'wpex' ),
	'section'		=> 'wpex_callout_button',
	'settings'		=> 'callout_button_target',
	'priority'		=> 3,
	'type'			=> 'select',
	'choices'		=> array(
		'blank'	=> __( 'Blank', 'wpex' ),
		'self'	=> __( 'Self', 'wpex' ),
	),
) );

$wp_customize->add_setting( 'callout_button_rel', array(
	'type'		=> 'theme_mod',
	'default'	=> 'dofollow',
) );
$wp_customize->add_control( 'callout_button_rel', array(
	'label'			=> __( 'Link Rel', 'wpex' ),
	'section'		=> 'wpex_callout_button',
	'settings'		=> 'callout_button_rel',
	'priority'		=> 4,
	'type'			=> 'select',
	'choices'		=> array(
		''			=> __( 'None', 'wpex' ),
		'nofollow'	=> __( 'Nofollow', 'wpex' ),
	),
) );