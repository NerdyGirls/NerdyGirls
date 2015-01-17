<?php
/**
 * Recommends plugins for use with the theme via the TGMA Script
 *
 * @package     Total
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.0.0
 */

if ( ! function_exists( 'wpex_register_required_plugins' ) ) {
	function wpex_register_required_plugins() {

		$plugins_dir = get_template_directory_uri() .'/plugins/';

		$plugins = array(
			array(
				'name'				=> 'WPBakery Visual Composer',
				'slug'				=> 'js_composer', 
				'source'			=> $plugins_dir .'js_composer.zip',
				'required'			=> false,
				'force_activation'	=> false,
			),
			array(
				'name'				=> 'Templatera',
				'slug'				=> 'templatera', 
				'source'			=> $plugins_dir .'templatera.zip',
				'required'			=> false,
				'force_activation'	=> false,
			),
			array(
				'name'				=> 'Revolution Slider',
				'slug'				=> 'revslider',
				'source'			=> $plugins_dir .'revslider.zip',
				'required'			=> false,
				'force_activation'	=> false,
			),
			array(
				'name'				=> 'Contact Form 7',
				'slug'				=> 'contact-form-7', 
				'required'			=> false,
				'force_activation'	=> false,
			),
			array(
				'name'				=> 'WooCommerce',
				'slug'				=> 'woocommerce', 
				'required'			=> false,
				'force_activation'	=> false,
			),	
		);

		$plugins = apply_filters( 'wpex_recommended_plugins', $plugins );

		// Config settings
		$config = array(
			'domain'			=> 'wpex',
			'default_path'		=> '',
			'parent_menu_slug'	=> 'themes.php',
			'parent_url_slug'	=> 'themes.php',
			'menu'				=> 'install-required-plugins',
			'has_notices'		=> true,
			'is_automatic'		=> false,
			'message'			=> '',
			'strings'			=> array(
				'page_title'								=> __( 'Install Required Plugins', 'wpex' ),
				'menu_title'								=> __( 'Install Plugins', 'wpex' ),
				'installing'								=> __( 'Installing Plugin: %s', 'wpex' ),
				'oops'										=> __( 'Something went wrong with the plugin API.', 'wpex' ),
				'notice_can_install_required'				=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
				'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ),
				'notice_cannot_install'						=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
				'notice_can_activate_required'				=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
				'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
				'notice_cannot_activate'					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
				'notice_ask_to_update'						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
				'notice_cannot_update'						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
				'install_link'								=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
				'activate_link'								=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
				'return'									=> __( 'Return to Required Plugins Installer', 'wpex' ),
				'plugin_activated'							=> __( 'Plugin activated successfully.', 'wpex' ),
				'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'wpex' ),
				'nag_type'									=> 'updated'
			)
		);

		tgmpa( $plugins, $config );
	
	}
}
add_action( 'tgmpa_register', 'wpex_register_required_plugins' );