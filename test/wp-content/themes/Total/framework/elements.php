<?php
/**
 * HTML elements outputted with PHP to make theming/RTL easier
 *
 * @package		Total
 * @subpackage	functions
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.3.3
 */

if ( ! function_exists( 'wpex_element' ) ) {
	function wpex_element( $element ) {
		switch ( $element ) {

		// Rarr
		case 'rarr':
			if( is_rtl() ) {
				return '&larr;';
			} else {
				return '&rarr;';
			}
		break;

		// Angle Right
		case 'angle_right' :
			if( is_rtl() ) {
				return '<span class="fa fa-angle-left"></span>';
			} else {
				return '<span class="fa fa-angle-right"></span>';
			}
		break;

		}
	}
}