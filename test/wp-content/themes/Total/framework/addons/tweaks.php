<?php
/**
 * Used for the main Add Ons dashboard menu and page
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
if ( ! class_exists( 'WPEX_Tweaks_Admin' ) ) {
	class WPEX_Tweaks_Admin {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
			add_action( 'admin_menu', array( $this, 'add_menu_subpage' ) );
			add_action( 'admin_init', array( $this,'register_settings' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'init', array( $this, 'init' ) );
		}

		/**
		 * Registers a new menu page
		 *
		 * @link	http://codex.wordpress.org/Function_Reference/add_menu_page
		 * @since	Total 1.6.0
		 */
		function add_menu_page() {
			add_menu_page(
				__( 'Theme Panel', 'wpex' ),	// page title
				__( 'Theme Panel', 'wpex' ),	// menu title
				'manage_options',				// capability
				'wpex-addons',					// menu_slug
				'',								// function
				'dashicons-admin-generic',		// admin icon
				null							// position
			);
		}

		/**
		 * Registers a new submenu page
		 *
		 * @link	http://codex.wordpress.org/Function_Reference/add_submenu_page
		 * @since	Total 1.6.0
		 */
		function add_menu_subpage(){
			add_submenu_page(
				'wpex-addons',
				__( 'Tweaks', 'wpex' ),
				__( 'Tweaks', 'wpex' ),
				'manage_options',
				'wpex-addons',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_setting
		 */
		function register_settings() {
			register_setting( 'wpex_tweaks', 'wpex_tweaks', array( $this, 'admin_sanitize' ) ); 
		}

		/**
		 * Displays all messages registered to 'wpex-custom_css-notices'
		 *
		 * @link http://codex.wordpress.org/Function_Reference/settings_errors
		 */
		function admin_notices() {
			settings_errors( 'wpex_tweaks_notices' );
		}

		/**
		 * Main Sanitization callback
		 */
		function admin_sanitize( $options ) {

			// Get the option array and for each one save as a theme_mod
			if ( is_array( $options ) && ! empty( $options ) ) {
				foreach( $options as $key => $value ) {
					if ( ! empty( $value ) ) {
						set_theme_mod( $key, $value );
					} else {
						remove_theme_mod( $key, $value );
					}
				}
			}

			// Save checkboxes
			$checkboxes = array( 'minify_js', 'remove_scripts_version', 'cleanup_head', 'image_resizing', 'remove_posttype_slugs', 'post_series_enable', 'custom_404_enable', 'custom_admin_login_enable', 'custom_css_enable', 'widget_areas_enable', 'skins_enable', 'visual_composer_theme_mode', 'extend_visual_composer', 'favicons_enable', 'trim_custom_excerpts', 'retina' );

			// Add post type settings to checkboxes array
			$post_types	= wpex_theme_post_types();
			foreach ( $post_types as $post_type ) {
				$checkboxes[] = $post_type .'_enable';
			}

			// Loop through all options and check if isset and if so save mod
			foreach ( $checkboxes as $checkbox ) {

				// Set theme mod to on
				if ( isset( $options[$checkbox] ) ) {
					set_theme_mod( $checkbox, true );
				}

				// If not checked set thememod to false
				else {
					set_theme_mod( $checkbox, false );
				}
			}
			
			// Display notice after saving
			add_settings_error(
				'wpex_tweaks_notices',
				esc_attr( 'settings_updated' ),
				__( 'Settings saved.', 'wpex' ),
				'updated'
			);

			// Return options, save in options table just incase
			return $options;

		}

		/**
		 * Settings page output
		 */
		function create_admin_page() { ?>
			<div class="wrap">
				<h2><?php _e( 'Theme Panel', 'wpex' ); ?></h2>
				<form method="post" action="options.php">
					<?php settings_fields( 'wpex_tweaks' ); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e( 'Enable Addons', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label><input type="checkbox" name="wpex_tweaks[favicons_enable]" <?php checked( get_theme_mod( 'favicons_enable', true ) ); ?>> <?php _e( 'Favicons', 'wpex' ); ?></label>
									<br />
									<label><input type="checkbox" name="wpex_tweaks[custom_404_enable]" <?php checked( get_theme_mod( 'custom_404_enable', true ) ); ?>> <?php _e( '404 Page', 'wpex' ); ?></label>
									<br />
									<label><input type="checkbox" name="wpex_tweaks[custom_admin_login_enable]" <?php checked( get_theme_mod( 'custom_admin_login_enable', true ) ); ?>> <?php _e( 'Login Page', 'wpex' ); ?></label>
									<br />
									<label><input type="checkbox" name="wpex_tweaks[custom_css_enable]" <?php checked( get_theme_mod( 'custom_css_enable', true ) ); ?>> <?php _e( 'Custom CSS', 'wpex' ); ?></label>
									<br />
									<label><input type="checkbox" name="wpex_tweaks[widget_areas_enable]" <?php checked( get_theme_mod( 'widget_areas_enable', true ) ); ?>> <?php _e( 'Widget Areas', 'wpex' ); ?></label>
									<br />
									<label><input type="checkbox" name="wpex_tweaks[skins_enable]" <?php checked( get_theme_mod( 'skins_enable', true ) ); ?>> <?php _e( 'Skins', 'wpex' ); ?></label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Minify Javascript', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label><input type="checkbox" name="wpex_tweaks[minify_js]"<?php checked( get_theme_mod( 'minify_js', true ) ); ?>> <?php _e( 'Minify and load all theme related javascript in one single, minified file.', 'wpex' ); ?></label>
									<p class="description"><?php _e( 'Improves site speed but best to disable whenever you are troubleshooting an error.', 'wpex' ); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Remove Script File Versions', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label><input type="checkbox" name="wpex_tweaks[remove_scripts_version]" <?php checked( get_theme_mod( 'remove_scripts_version', true ) ); ?>> <?php _e( 'Check to remove the scripts version numbers.', 'wpex' ); ?></label>
									<p class="description"><?php _e( 'Most scripts and style-sheets called by WordPress include a query string identifying the version. This can cause issues with caching. You can toggle this setting on to remove the query string from such strings.', 'wpex' ); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Cleanup WP Head', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label><input type="checkbox" name="wpex_tweaks[cleanup_head]" <?php checked( get_theme_mod ( 'cleanup_head', true ) ); ?>> <?php _e( 'Remove code from the wp_head hook to clean things up.', 'wpex' ); ?></label>
									<p class="description"><?php _e( 'Advanced option, use if you know what you are doing.', 'wpex' ); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Image Resizing', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpex_tweaks[image_resizing]" <?php checked( get_theme_mod( 'image_resizing', true ) ); ?>> <?php _e( 'Enable the built-in image cropping and resizing functions for featured images.', 'wpex' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Trim Custom Excerpts', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpex_tweaks[trim_custom_excerpts]" <?php checked( get_theme_mod( 'trim_custom_excerpts', true ) ); ?>> <?php _e( 'Check to trim custom excerpts for the wpex_excerpt function.', 'wpex' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Retina', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpex_tweaks[retina]" <?php checked( get_theme_mod( 'retina' ), true ); ?>> <?php _e( 'Enable retina support for your site (via retina.js).', 'wpex' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Enable Post Types', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<?php
									// Display post types options
									$post_types	= wpex_theme_post_types();
									foreach ( $post_types as $post_type ) {
										if ( post_type_exists( $post_type ) ) {
											$obj	= get_post_type_object( $post_type );
											$name	= $obj->labels->name;
										} else {
											$name = ucfirst( $post_type );
										} ?>
										<label><input type="checkbox" name="wpex_tweaks[<?php echo $post_type; ?>_enable]" <?php checked( get_theme_mod( $post_type . '_enable', true ) ); ?>> <?php echo $name; ?></label>
										<br />
									<?php } ?>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Remove Post Type Slugs', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label><input type="checkbox" name="wpex_tweaks[remove_posttype_slugs]" <?php checked( get_theme_mod( 'remove_posttype_slugs', false ) ); ?>> <?php _e( 'Remove Custom Post Type Slugs (Experimental)', 'wpex' ); ?></label>
									<p class="description"><?php _e( 'Toggle the slug on/off for your custom post types (portfolio, staff, testimonials). Custom Post Types in WordPress by default should have a slug to prevent conflicts, you can use this setting to disable them, but be careful.', 'wpex' ); ?> <?php _e( 'Please make sure to re-save your WordPress permalinks settings whenever changing this option.', 'wpex' ); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Post Series', 'wpex' ); ?></th>
							<td>
							<label><input type="checkbox" name="wpex_tweaks[post_series_enable]" <?php checked( get_theme_mod( 'post_series_enable', true ) ); ?>> <?php _e( 'Check to enable.', 'wpex' ); ?></label>
							</td>
						</tr>
						<?php if ( get_theme_mod( 'post_series_enable', true ) ) { ?>
							<tr valign="top">
								<th scope="row"><?php _e( 'Post Series Labels', 'wpex' ); ?></th>
								<td>
								<input type="text" name="wpex_tweaks[post_series_labels]" value="<?php echo get_theme_mod( 'post_series_labels', __( 'Post Series', 'wpex' ) ); ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( 'Post Series Slug', 'wpex' ); ?></th>
								<td>
								<input type="text" name="wpex_tweaks[post_series_slug]" value="<?php echo get_theme_mod( 'post_series_slug', 'post-series' ); ?>">
								</td>
							</tr>
						<?php } ?>
						<tr valign="top">
							<th scope="row"><?php _e( 'Visual Composer', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<label><input type="checkbox" name="wpex_tweaks[visual_composer_theme_mode]" <?php checked( get_theme_mod( 'visual_composer_theme_mode', true ) ); ?>> <?php _e( ' Run Visual Composer In Theme Mode', 'wpex' ); ?></label><p class="description"><?php _e( 'Please keep this option enabled unless you have purchased a full copy of the Visual Composer plugin directly from the author.', 'wpex' ); ?></p>
									<br />
									<label><input type="checkbox" name="wpex_tweaks[extend_visual_composer]" <?php checked( get_theme_mod( 'extend_visual_composer', true ) ); ?>> <?php _e( ' Extend The Visual Composer?', 'wpex' ); ?></label><p class="description"><?php _e( 'This theme includes many extensions (more modules) for the Visual Composer plugin. If you do not wish to use any disable them here.', 'wpex' ); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Theme Branding', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<input type="text" name="wpex_tweaks[theme_branding]" value="<?php echo get_theme_mod( 'theme_branding', 'Total' ); ?>">
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Analytics Tracking Code', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<textarea type="text" name="wpex_tweaks[tracking]" rows="5" style="min-width:25%"><?php echo get_theme_mod( 'tracking', false ); ?></textarea><p class="description"><?php _e( 'Enter your entire tracking code (javascript).', 'wpex' ); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Item Purchase Code', 'wpex' ); ?></th>
							<td>
								<fieldset>
									<input type="text" name="wpex_tweaks[envato_license_key]" value="<?php echo get_theme_mod( 'envato_license_key', '' ); ?>"><p class="description"><?php _e( 'Enter your Envato license key here if you wish to receive auto updates for your theme.', 'wpex' ); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			</div><!-- .wrap -->
		<?php }

		/**
		 * Tweaks to run on init
		 */
		function init() {

			// Clean up the WP header
			if( get_theme_mod( 'wpex_cleanup_head', true ) ) {
				remove_action( 'wp_head', 'feed_links_extra' );
				remove_action( 'wp_head', 'feed_links' );
				remove_action( 'wp_head', 'rsd_link' );
				remove_action( 'wp_head', 'wlwmanifest_link' );
				remove_action( 'wp_head', 'index_rel_link' );
				remove_action( 'wp_head', 'parent_post_rel_link', 10 );
				remove_action( 'wp_head', 'start_post_rel_link', 10 );
				remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
				remove_action( 'wp_head', 'wp_generator' );
			}
		}
	}
}
new WPEX_Tweaks_Admin();