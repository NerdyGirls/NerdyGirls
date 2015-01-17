<?php
/**
 * WPML Functions
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link 		http://www.wpexplorer.com
 * @since		Total 1.6.3
 * @version		1.0.0
 */

// Functions only needed if WPML is enabled
if ( ! class_exists( 'SitePress' ) ) {
	return;
}

/**
 * Registers theme_mod strings into WPML
 *
 * @since 1.6.3
 */
if ( ! function_exists( 'wpex_icl_register_strings' ) ) {
	function wpex_icl_register_strings() {
		
		// Return if function doesn't exist
		if ( ! function_exists( 'icl_register_string' ) ) {
			return;
		}

		// Array of strings
		$strings = array(
			'custom_logo'				=> false,
			'retina_logo'				=> false,
			'retina_logo_height'		=> false,
			'error_page_title'			=> '404: Page Not Found',
			'error_page_text'			=> false,
			'top_bar_content'			=> '[font_awesome icon="phone" margin_right="5px" color="#000"] 1-800-987-654 [font_awesome icon="envelope" margin_right="5px" margin_left="20px" color="#000"] admin@total.com [font_awesome icon="user" margin_right="5px" margin_left="20px" color="#000"] [wp_login_url text="User Login" logout_text="Logout"]',
			'top_bar_social_alt'		=> false,
			'header_aside'				=> false,
			'breadcrumbs_home_title'	=> false,
			'blog_entry_readmore_text'	=> 'Read More',
			'social_share_heading'		=> 'Please Share This',
			'portfolio_related_title'	=> 'Related Projects',
			'staff_related_title'		=> 'Related Staff',
			'blog_related_title'		=> 'Related Posts',
			'callout_text'				=> 'I am the footer call-to-action block, here you can add some relevant/important information about your company or product. I can be disabled in the theme options.',
			'callout_link'				=> 'http://www.wpexplorer.com',
			'callout_link_txt'			=> 'Get In Touch',
			'footer_copyright_text'		=> 'Copyright <a href="http://wpexplorer-themes.com/total/" target="_blank" title="Total WordPress Theme">Total WordPress Theme</a> - All Rights Reserved',
			'woo_shop_single_title'		=> false,
		);

		// Register strings
		foreach( $strings as $string => $default ) {
			icl_register_string( 'Theme Mod', $string, get_theme_mod( $string, $default ) );
		}

	}
}
add_action( 'admin_init', 'wpex_icl_register_strings' );

/**
 * Fix for when users have the Language URL Option on "different domains"
 * which causes cropped images to fail
 *
 * @since	1.6.3
 * @link	https://wpml.org/forums/topic/image-crop-not-working-for-secondary-language/
 */
if ( ! function_exists( 'wpex_wpml_convert_baseurl' ) ) {
	function wpex_wpml_convert_baseurl ( $upload ) {
		global $sitepress;
		if ( $sitepress ) {
			$upload["baseurl"] = $sitepress->convert_url( $upload["baseurl"] );
			return $upload;
		}
	}
}
add_filter( 'upload_dir', 'wpex_wpml_convert_baseurl' );