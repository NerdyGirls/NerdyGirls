<?php
/**
 * Favicons
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
if ( ! class_exists( 'WPEX_Favicons' ) ) {
	class WPEX_Favicons {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_page' ) );
			add_action( 'admin_init', array( $this,'register_page_options' ) );
			add_action( 'admin_enqueue_scripts',array( $this,'scripts' ) );
			add_action( 'admin_notices', array( $this, 'notices' ) );
			add_action( 'wp_head', array( $this, 'output_favicons' ) );
		}

		/**
		 * Add sub menu page for the custom CSS input
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_page
		 */
		public function add_page() {
			add_submenu_page(
				'wpex-addons',
				__( 'Favicons', 'wpex' ),
				__( 'Favicons', 'wpex' ),
				'administrator',
				'wpex-favicons',
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
			if( WPEX_ADMIN_PANEL_HOOK_PREFIX . 'wpex-favicons' != $hook ) {
				return;
			}

			// Media Uploader
			wp_enqueue_media();
			wp_enqueue_script(
				'wpex-media-uploader-field',
				WPEX_FRAMEWORK_DIR_URI .'addons/assets/admin-fields/media-uploader.js',
				array( 'media-upload' ),
				false,
				true
			);

			// CSS
			wp_enqueue_style(
				'wpex-admin',
				WPEX_FRAMEWORK_DIR_URI .'addons/assets/admin-fields/admin.css'
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

			// Register Setting
			register_setting( 'wpex_favicons', 'wpex_favicons', array( $this, 'sanitize' ) );

			// Add main section to our options page
			add_settings_section( 'wpex_favicons_main', false, array( $this, 'section_main_callback' ), 'wpex-favicons' );

			// Favicon
			add_settings_field(
				'wpex_favicon',
				__( 'Favicon', 'wpex' ),
				array( $this, 'favicon_callback' ),
				'wpex-favicons',
				'wpex_favicons_main'
			);

			// iPhone
			add_settings_field(
				'wpex_iphone_icon',
				__( 'Apple iPhone Icon ', 'wpex' ),
				array( $this, 'iphone_icon_callback' ),
				'wpex-favicons',
				'wpex_favicons_main'
			);

			// Ipad
			add_settings_field(
				'wpex_ipad_icon',
				__( 'Apple iPad Icon ', 'wpex' ),
				array( $this, 'ipad_icon_callback' ),
				'wpex-favicons',
				'wpex_favicons_main'
			);

			// iPhone Retina
			add_settings_field(
				'wpex_iphone_icon_retina',
				__( 'Apple iPhone Retina Icon ', 'wpex' ),
				array( $this, 'iphone_icon_retina_callback' ),
				'wpex-favicons',
				'wpex_favicons_main'
			);

			// iPad Retina
			add_settings_field(
				'wpex_ipad_icon_retina',
				__( 'Apple iPad Retina Icon ', 'wpex' ),
				array( $this, 'ipad_icon_retina_callback' ),
				'wpex-favicons',
				'wpex_favicons_main'
			);

		}

		/**
		 * Displays all messages registered to 'wpex-custom_css-notices'
		 *
		 * @link http://codex.wordpress.org/Function_Reference/settings_errors
		 */
		public function notices() {
			settings_errors( 'wpex_favicons_notices' );
		}

		/**
		 * Sanitization callback
		 */
		public function sanitize( $options ) {

			// Set all options to theme_mods
			if ( is_array( $options ) && ! empty( $options ) ) {
				foreach ( $options as $key => $value ) {
					set_theme_mod( $key, $value );
				}
			}

			// Add notice
			add_settings_error(
				'wpex_favicons_notices',
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

		// Favicon
		public function favicon_callback() {
			$val	= get_theme_mod( 'favicon' );
			$output	= '<input type="text" name="wpex_favicons[favicon]" value="'. $val .'" class="wpex-image-input">';
			$output .= ' <input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="'. __( 'Upload', 'wpex' ) .'" />';
			$output .= '<p class="description">32x32</p>';
			$output .= '<div class="wpex-media-live-preview"><img src="'. $val .'" /></div>';
			echo $output;
		}

		// iPhone
		public function iphone_icon_callback() {
			$val	= get_theme_mod( 'iphone_icon' );
			$output	= '<input type="text" name="wpex_favicons[iphone_icon]" value="'. $val .'">';
			$output .= ' <input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="'. __( 'Upload', 'wpex' ) .'" />';
			$output .= '<p class="description">57x57</p>';
			$output .= '<div class="wpex-media-live-preview"><img src="'. $val .'" /></div>';
			echo $output;
		}

		// iPad
		public function ipad_icon_callback() {
			$val	= get_theme_mod( 'ipad_icon' );
			$output	= '<input type="text" name="wpex_favicons[ipad_icon]" value="'. $val .'">';
			$output .= ' <input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="'. __( 'Upload', 'wpex' ) .'" />';
			$output .= '<p class="description">76x76</p>';
			$output .= '<div class="wpex-media-live-preview"><img src="'. $val .'" /></div>';
			echo $output;
		}

		// iPhone Retina
		public function iphone_icon_retina_callback() {
			$val	= get_theme_mod( 'iphone_icon_retina' );
			$output	= '<input type="text" name="wpex_favicons[iphone_icon_retina]" value="'. $val .'">';
			$output .= ' <input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="'. __( 'Upload', 'wpex' ) .'" />';
			$output .= '<p class="description">120x120</p>';
			$output .= '<div class="wpex-media-live-preview"><img src="'. $val .'" /></div>';
			echo $output;
		}

		// iPad Retina
		public function ipad_icon_retina_callback() {
			$val	= get_theme_mod( 'ipad_icon_retina' );
			$output	= '<input type="text" name="wpex_favicons[ipad_icon_retina]" value="'. $val .'">';
			$output .= ' <input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="'. __( 'Upload', 'wpex' ) .'" />';
			$output .= '<p class="description">152x152</p>';
			$output .= '<div class="wpex-media-live-preview"><img src="'. $val .'" /></div>';
			echo $output;
		}

		/**
		 * Settings page output
		 */
		public function create_admin_page() { ?>
			<div class="wrap">
				<h2 style="padding-right:0;">
					<?php _e( 'Favicons', 'wpex' ); ?>
				</h2>
				<form method="post" action="options.php">
					<?php settings_fields( 'wpex_favicons' ); ?>
					<?php do_settings_sections( 'wpex-favicons' ); ?>
					<?php submit_button(); ?>
				</form>
			</div><!-- .wrap -->
		<?php }

		/**
		 * Settings page output
		 */
		public function output_favicons() {

			$output = '';

			// Favicon - Standard
			if ( $icon = get_theme_mod( 'favicon' ) ) {
				$output .= '<link rel="shortcut icon" href="'. $icon .'">';
			}

			// Apple iPhone Icon - 57px
			if ( $icon = get_theme_mod( 'iphone_icon' ) ) {
				$output .= '<link rel="apple-touch-icon-precomposed" href="'. $icon .'">';
			}

			// Apple iPad Icon - 76px
			if ( $icon = get_theme_mod( 'ipad_icon' ) ) {
				$output .= '<link rel="apple-touch-icon-precomposed" sizes="76x76" href="'. $icon .'">';
			}

			// Apple iPhone Retina Icon - 120px
			if ( $icon = get_theme_mod( 'iphone_icon_retina' ) ) {
				$output .= '<link rel="apple-touch-icon-precomposed" sizes="120x120" href="'. $icon .'">';
			}

			// Apple iPad Retina Icon - 114px
			if ( $icon = get_theme_mod( 'ipad_icon_retina' ) ) {
				$output .= '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="'. $icon .'">';
			}

			// Output favicons into the WP_Head
			echo $output;

		}
	}
}
new WPEX_Favicons();