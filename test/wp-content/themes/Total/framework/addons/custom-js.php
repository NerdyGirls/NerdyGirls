<?php
/**
 * Creates the admin panel and custom JS output
 *
 * @package		Total
 * @subpackage	Framework/Addons
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

// Start Class
if ( ! class_exists( 'WPEX_Custom_JS' ) ) {
	class WPEX_Custom_JS {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_page' ) );
			add_action( 'admin_init', array( $this,'register_settings' ) );
			add_action( 'admin_enqueue_scripts',array( $this,'scripts' ) );
			add_action( 'admin_notices', array( $this, 'notices' ) );
			add_action( 'wp_footer' , array( $this, 'output_js' ) );
		}

		/**
		 * Add sub menu page for the custom JS input
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_page
		 */
		public function add_page() {
			add_submenu_page(
				'wpex-addons',
				__( 'Custom JS', 'wpex' ),
				__( 'Custom JS', 'wpex' ),
				'administrator',
				'wpex-custom-js-admin',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Load scripts
		 *
		 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
		 */
		public function scripts( $hook ) {
			// Only load scripts when needed
			if ( $hook != 'addons_page_wpex-custom-js-admin' ) {
				return;
			}
			// Define assets URL
			$dir = WPEX_FRAMEWORK_DIR_URI .'addons/assets/codemirror/';
			// Load JS files and required JS
			wp_enqueue_script(
				'wpex-codemirror',
				$dir .
				'codemirror.js',
				array( 'jquery' )
			);
			wp_enqueue_script(
				'wpex-codemirror',
				$dir .
				'codemirror.js',
				array( 'jquery' )
			);
			wp_enqueue_script(
				'wpex-codemirror-javascript',
				$dir . 'javascript.js',
				array(
					'jquery',
					'wpex-codemirror'
				)
			);
			wp_enqueue_style(
				'wpex-codemirror',
				$dir . 'codemirror.css'
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_setting
		 */
		public function register_settings() {
			register_setting( 'wpex_custom_js', 'wpex_custom_js', array( $this, 'sanitize' ) );
			register_setting( 'wpex_custom_js', 'wpex_custom_js_theme' );
		}

		/**
		 * Displays all messages registered to 'wpex-custom_css-notices'
		 *
		 * @link http://codex.wordpress.org/Function_Reference/settings_errors
		 */
		public function notices() {
			settings_errors( 'wpex_custom_js_notices' );
		}

		/**
		 * Sanitization callback
		 */
		public function sanitize( $option ) {
			add_settings_error(
				'wpex_custom_js_notices',
				esc_attr( 'settings_updated' ),
				__( 'Settings saved.', 'wpex' ),
				'updated'
			);
			return $option;
		}

		/**
		 * Settings page output
		 */
		public function create_admin_page() { ?>
			<div class="wrap">
				<h2 style="padding-right:0;">
					<?php _e( 'Custom JS', 'wpex' ); ?>
				</h2>
				<p><?php _e( 'Use the form below to add custom javascript to tweak your theme. Javascript will be added to the footer of the site. Please do not add any "script" tags, these will be added automatically for you.', 'wpex' ); ?></p>
				<form method="post" action="options.php">
					<?php
					/**
					 * Output nonce, action, and option_page fields for a settings page
					 *
					 * @link http://codex.wordpress.org/Function_Reference/settings_fields
					 */
					$option = get_option( 'wpex_custom_js' );
					settings_fields( 'wpex_custom_js' ); ?>
					<table class="form-table">
						<tr valign="top">
							<td style="padding:0;">
								<textarea rows="40" cols="50" id="wpex_custom_js" style="width:100%;" name="wpex_custom_js"><?php echo $option; ?></textarea>
							</td>
						</tr>
					</table>
					<input type="hidden" name="wpex_custom_js_theme" value="<?php echo get_option( 'wpex_custom_js_theme', 'dark' ); ?>" id="wpex-default-codemirror-theme"></input>
					<?php submit_button(); ?>
				</form>
			</div>
			<script>
				(function($) {
					"use strict";
						window.onload = function() {
							window.editor = CodeMirror.fromTextArea(wpex_custom_js, {
								mode: "javascript",
								lineNumbers: true,
								lineWrapping: true,
								theme: 'default'
							});
						};
				})(jQuery);
			</script>
		<?php }

		/**
		 * Outputs the custom JS to the wp_head
		 */
		public function output_js() {
			if ( $js = get_option( 'wpex_custom_js', false ) ) {
				echo '<!-- Custom JS --><script> (function($) { "use strict"; '. $js .' })(jQuery);</script>';
			}
		}
	}
}
new WPEX_Custom_JS();