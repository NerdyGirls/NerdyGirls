<?php
/**
 * Custom 404 Page Design
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

// Only needed in the admin
if ( ! is_admin() ) {
	return;
}

// Start Class
if ( ! class_exists( 'WPEX_Custom_Error_Page' ) ) {
	class WPEX_Custom_Error_Page {

		/**
		 * Start things up
		 */
		public function __construct() {

			// Add the page to the admin menu
			add_action( 'admin_menu', array( $this, 'add_page' ) );

			// Register page options
			add_action( 'admin_init', array( $this,'register_page_options' ) );

			// Display notices on form submission
			add_action( 'admin_notices', array( $this, 'notices' ) );

		}

		/**
		 * Add sub menu page for the custom CSS input
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_page
		 */
		public function add_page() {
			add_submenu_page(
				'wpex-addons',
				__( '404 Page', 'wpex' ),
				__( '404 Page', 'wpex' ),
				'administrator',
				'wpex-custom-error-page-admin',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Function that will register admin page options.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_setting
		 * @link http://codex.wordpress.org/Function_Reference/add_settings_section
		 * @link http://codex.wordpress.org/Function_Reference/add_settings_field
		 */
		public function register_page_options() {

			// Register settings
			register_setting( 'wpex_error_page', 'error_page', array( $this, 'sanitize' ) );

			// Add main section to our options page
			add_settings_section( 'wpex_error_page_main', false, array( $this, 'section_main_callback' ), 'wpex-custom-error-page-admin' );

			// Redirect field
			add_settings_field(
				'redirect',
				__( 'Redirect 404\'s', 'wpex' ),
				array( $this, 'redirect_field_callback' ),
				'wpex-custom-error-page-admin',
				'wpex_error_page_main'
			);

			// Title field
			add_settings_field(
				'error_page_title',
				__( '404 Page Title', 'wpex' ),
				array( $this, 'title_field_callback' ),
				'wpex-custom-error-page-admin',
				'wpex_error_page_main'
			);

			// Content field
			add_settings_field(
				'error_page_text',
				__( '404 Page Content', 'wpex' ),
				array( $this, 'content_field_callback' ),
				'wpex-custom-error-page-admin',
				'wpex_error_page_main'
			);

		}

		/**
		 * Displays all messages registered to 'wpex-custom_css-notices'
		 *
		 * @link http://codex.wordpress.org/Function_Reference/settings_errors
		 */
		public function notices() {
			settings_errors( 'wpex_custom_error_page_notices' );
		}

		/**
		 * Sanitization callback
		 */
		public function sanitize( $options ) {

			// Set options to theme mods
			if ( isset ( $options['redirect'] ) ) {
				set_theme_mod( 'error_page_redirect', true );
			} else {
				remove_theme_mod( 'error_page_redirect' );
			}
			set_theme_mod( 'error_page_title', $options['title'] );
			set_theme_mod( 'error_page_text', $options['text'] );

			// Add notice
			add_settings_error(
				'wpex_custom_error_page_notices',
				esc_attr( 'settings_updated' ),
				__( 'Settings saved.', 'wpex' ),
				'updated'
			);

			// Set options to nothing since we are storing in the theme mods
			$options = '';
			return $options;
		}

		/**
		 * Main Settings section callback
		 */
		public function section_main_callback( $options ) {
			// Leave blank
		}

		/**
		 * Fields callback functions
		 */

		// Redirect field
		public function redirect_field_callback() {
			$val	= get_theme_mod( 'error_page_redirect' );
			$output	= '<input type="checkbox" name="error_page[redirect]" value="'. $val .'" '. checked( $val, true, false ) .'> ';
			$output	.= __( 'Automatically 301 redirect all 404 errors to your homepage.', 'wpex' );
			echo $output;
		}

		// Title field
		public function title_field_callback() {
			$val	= get_theme_mod( 'error_page_title' );
			$output	= '<input type="text" name="error_page[title]" value="'. $val .'">';
			echo $output;
		}

		// Content field
		public function content_field_callback() {
			wp_editor( get_theme_mod( 'error_page_text' ), 'error_page_text', array(
				'textarea_name'	=> 'error_page[text]'
			) );
		}

		/**
		 * Settings page output
		 */
		public function create_admin_page() { ?>
			<div class="wrap">
				<h2 style="padding-right:0;">
					<?php _e( '404 Error Page', 'wpex' ); ?>
				</h2>
				<form method="post" action="options.php">
					<?php settings_fields( 'wpex_error_page' ); ?>
					<?php do_settings_sections( 'wpex-custom-error-page-admin' ); ?>
					<?php submit_button(); ?>
				</form>
			</div><!-- .wrap -->
		<?php }
	}
}
new WPEX_Custom_Error_Page();