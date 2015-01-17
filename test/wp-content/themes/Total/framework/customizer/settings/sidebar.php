<?php
/**
 * Sidebar Customizer Options
 *
 * @package		Total
 * @subpackage	Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

/*-----------------------------------------------------------------------------------*/
/*	- General Section
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_sidebar_general'] = array(
	'title'		=> __( 'General', 'wpex' ),
	'panel'		=> 'wpex_sidebar',
	'settings'	=> array(
		array(
			'id'		=> 'sidebar_headings',
			'default'	=> 'div',
			'control'	=> array (
				'label'		=>	__( 'Sidebar Widget Title Headings', 'wpex' ),
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
			),
		),
		array(
			'id'		=> 'widget_icons',
			'default'	=> true,
			'control'	=> array (
				'label'	=>	__( 'Widget Icons', 'wpex' ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'Certain widgets include little icons such as the recent posts widget. Here you can toggle the icons on or off.', 'wpex' ),
			),
		),
	),
);