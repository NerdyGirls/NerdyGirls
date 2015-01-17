<?php
/**
 * Adds the staff social shortcode to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( ! function_exists( 'vcex_staff_social_vc_map' ) ) {
	function vcex_staff_social_vc_map() {
		vc_map( array(
			"name"			=> __( "Staff Social Links", 'wpex' ),
			"description"	=> __( "Single staff social links.", 'wpex' ),
			"base"			=> "staff_social",
			'category'		=> WPEX_THEME_BRANDING,
			"icon"			=> "vcex-staff-social",
			"params"		=> array(
				array(
					"type"			=> "dropdown",
					"class"			=> "",
					"heading"		=> __( "Link Target", 'wpex' ),
					"param_name"	=> "link_target",
					"value"			=> array(
						__( "Self", "wpex")		=> "self",
						__( "Blank", "wpex" )	=> "blank",
					),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_staff_social_vc_map' );