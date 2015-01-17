<?php
/**
 * Creates the admin panel and custom CSS output
 *
 * @package		Total
 * @subpackage	Framework/Addons
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Custom_CSS' ) ) {
	class WPEX_Custom_CSS {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_page' ) );
			add_action( 'admin_bar_menu', array( $this, 'adminbar_menu' ), 999 );
			add_action( 'admin_init', array( $this,'register_settings' ) );
			add_action( 'admin_enqueue_scripts',array( $this,'scripts' ) );
			add_action( 'admin_notices', array( $this, 'notices' ) );
			add_action( 'wpex_head_css' , array( $this, 'output_css' ), 20 );
		}

		/**
		 * Add sub menu page for the custom CSS input
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_page
		 */
		public function add_page() {
			add_submenu_page(
				'wpex-addons',
				__( 'Custom CSS', 'wpex' ),
				__( 'Custom CSS', 'wpex' ),
				'administrator',
				'wpex-custom-css-admin',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Add custom CSS to the adminbar since it will be used frequently
		 *
		 * @link http://codex.wordpress.org/Class_Reference/WP_Admin_Bar/add_node
		 */
		public function adminbar_menu( $wp_admin_bar ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$url	= admin_url( 'admin.php?page=wpex-custom-css-admin' );
			$args	= array(
				'id'	=> 'wpex_custom_css',
				'title'	=> __( 'Custom CSS', 'wpex' ),
				'href'	=> $url,
				'meta'	=> array(
					'class'	=> 'wpex-custom-css',
				)
			);
			$wp_admin_bar->add_node( $args );
		}

		/**
		 * Load scripts
		 *
		 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
		 */
		public function scripts( $hook ) {

			// Only load scripts when needed
			if ( WPEX_ADMIN_PANEL_HOOK_PREFIX . 'wpex-custom-css-admin' != $hook ) {
				return;
			}

			// Define assets URL
			$dir = WPEX_FRAMEWORK_DIR_URI .'addons/assets/codemirror/';

			// Load JS files and required CSS
			wp_enqueue_script(
				'wpex-codemirror',
				$dir .
				'codemirror.js',
				array( 'jquery' )
			);
			wp_enqueue_script(
				'wpex-codemirror-css',
				$dir . 'css.js',
				array(
					'jquery',
					'wpex-codemirror'
				)
			);
			wp_enqueue_script(
				'wpex-codemirror-css-link',
				$dir . 'css-lint.js',
				array(
					'jquery',
					'wpex-codemirror',
					'wpex-codemirror-css'
				)
			);
			wp_enqueue_style(
				'wpex-codemirror',
				$dir . 'codemirror.css'
			);

			// Load correct skin type based on theme option
			if ( 'dark' == get_option( 'wpex_custom_css_theme', 'dark' ) ) {
				wp_enqueue_style( 'wpex-codemirror-theme', $dir . 'theme-dark.css' );
			} else {
				wp_enqueue_style( 'wpex-codemirror-theme', $dir . 'theme-light.css' );
			}
			
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_setting
		 */
		public function register_settings() {
			register_setting( 'wpex_custom_css', 'wpex_custom_css', array( $this, 'sanitize' ) );
			register_setting( 'wpex_custom_css', 'wpex_custom_css_theme' );
		}

		/**
		 * Displays all messages registered to 'wpex-custom_css-notices'
		 *
		 * @link http://codex.wordpress.org/Function_Reference/settings_errors
		 */
		public function notices() {
			settings_errors( 'wpex_custom_css_notices' );
		}

		/**
		 * Sanitization callback
		 */
		public function sanitize( $option ) {

			// Set option to theme mod
			set_theme_mod( 'custom_css', $option );

			// Return notice
			add_settings_error(
				'wpex_custom_css_notices',
				esc_attr( 'settings_updated' ),
				__( 'Settings saved.', 'wpex' ),
				'updated'
			);

			// Lets save the custom CSS into a standard option as well for backup
			return $option;
		}

		/**
		 * Settings page output
		 */
		public function create_admin_page() { ?>
			<div class="wrap">
				<h2 style="padding-right:0;">
					<?php _e( 'Custom CSS', 'wpex' ); ?>
				</h2>
				<p><?php _e( 'Use the form below to add custom CSS to tweak your theme design.', 'wpex' ); ?></p>
				<div style="margin:10px 0 20px;"><a href="#" class="button-secondary wpex-custom-css-toggle-skin"><?php _e( 'Toggle Skin', 'wpex' ); ?></a></div>
				<form method="post" action="options.php">
					<?php settings_fields( 'wpex_custom_css' ); ?>
					<table class="form-table">
						<tr valign="top">
							<td style="padding:0;">
								<textarea rows="40" cols="50" id="wpex_custom_css" style="width:100%;" name="wpex_custom_css"><?php echo get_theme_mod( 'custom_css', false ); ?></textarea>
							</td>
						</tr>
					</table>
					<input type="hidden" name="wpex_custom_css_theme" value="<?php echo get_option( 'wpex_custom_css_theme', 'dark' ); ?>" id="wpex-default-codemirror-theme"></input>
					<?php submit_button(); ?>
				</form>
			</div>
			<script>
				( function( $ ) {
					"use strict";
					window.onload = function() {
						window.editor = CodeMirror.fromTextArea(wpex_custom_css, {
							mode			: "css",
							lineNumbers		: true,
							lineWrapping	: true,
							theme			: 'wpex',
							lint			: true
						} );
					};
					<?php $dir = WPEX_FRAMEWORK_DIR_URI .'addons/assets/codemirror/'; ?>
					$( '.wpex-custom-css-toggle-skin' ).click(function() {
						var hiddenField = $( '#wpex-default-codemirror-theme' );
						if ( hiddenField.val() == 'dark' ) {
							hiddenField.val( 'light' );
							$( '#wpex-codemirror-theme-css' ).attr( 'href','<?php echo $dir; ?>theme-light.css' );
						} else {
							hiddenField.val( 'dark' );
							$( '#wpex-codemirror-theme-css' ).attr( 'href','<?php echo $dir; ?>theme-dark.css' );
						}
						return false;
					} );
				} ) ( jQuery );
			</script>
		<?php }

		/**
		 * Outputs the custom CSS to the wp_head
		 */
		public function output_css( $output ) {
			if ( $css = get_theme_mod( 'custom_css', false ) ) {
				$output .= '/*CUSTOM CSS*/'. $css;
			}
			return $output;
		}
	}
}
new WPEX_Custom_CSS();