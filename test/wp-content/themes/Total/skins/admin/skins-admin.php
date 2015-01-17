<?php
/**
 * Creates the admin panel for the customizer
 *
 * @package		Total
 * @subpackage	Skins
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.5.3
 */

// Only Needed in the admin
if ( ! is_admin() ) {
	return;
}

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Creates a beautiful admin panel for selecting your theme skin
 *
 * @since Total 1.6.0
 */
if ( ! class_exists( 'WPEX_Skins_Admin' ) ) {
	class WPEX_Skins_Admin {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_notices', array( $this, 'notices' ) );
		}

		/**
		 * Add sub menu page
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_page
		 */
		function add_page() {
			add_submenu_page(
				'wpex-addons',
				__( 'Theme Skins', 'wpex' ),
				__( 'Theme Skins', 'wpex' ),
				'administrator',
				'wpex-skins-admin',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Load CSS and JS for the skins admin panel
		 *
		 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
		 */
		function scripts( $hook ) {

			// Only load scrips when needed
			if ( WPEX_ADMIN_PANEL_HOOK_PREFIX .'wpex-skins-admin' != $hook ) {
				return;
			}

			wp_enqueue_style(
				'scripts', WPEX_SKIN_DIR_URI . 'admin/assets/skins-admin.css'
			);

			wp_enqueue_script(
				'wpex_skins_admin_js', WPEX_SKIN_DIR_URI . 'admin/assets/skins-admin.js',
				array( 'jquery' ),
				'1.0',
				true
			);

		}
		
		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_setting
		 */
		function register_settings() {
			register_setting(
				'wpex_skins_options',
				'theme_skin'
			);
			register_setting(
				'wpex_skins_options',
				'wpex_set_theme_defaults',
				array( $this, 'sanitize' )
			);
		}

		/**
		 * Displays all messages registered to 'wpex-skins-notices'
		 *
		 * @link http://codex.wordpress.org/Function_Reference/settings_errors
		 */
		function notices() {
			settings_errors( 'wpex-skins-notices' );
		}

		/**
		 * Sanitization callback
		 */
		function sanitize( $options ) {
			$skin = isset ( $options['skin'] ) ? $options['skin'] : 'base';
			if( isset( $options['toggle'] ) && 'on' == $options['toggle'] ) {
				$confirm	= isset( $options['confirm'] ) ? true : false;
				// Clear theme mods and set defaults
				if ( 'on' == $confirm && $skin ) {
					// Get menu locations
					$locations 	= get_theme_mod( 'nav_menu_locations' );
					$save_menus	= array();
					foreach( $locations as $key => $val ) {
						$save_menus[$key] = $val;
					}
					// Get sidebars
					$widget_areas = get_theme_mod( 'widget_areas' );
					// Remove all mods
					remove_theme_mods();
					// Re-add the menus
					set_theme_mod( 'nav_menu_locations', array_map( 'absint', $save_menus ) );
					set_theme_mod( 'widget_areas', $widget_areas );
					// Base
					if ( 'base' == $options['skin'] ) {
						set_theme_mod( 'header_height', '40px' );
						set_theme_mod( 'menu_dropdown_top_border', '1' );
					}
					// Neat
					elseif ( 'neat' == $options['skin'] ) {
						set_theme_mod( 'body_typography', array(
							'font-family'	=> 'Open Sans',
						) );
						set_theme_mod( 'headings_typography', array(
							'font-family'	=> 'Roboto Slab',
						) );
						set_theme_mod( 'menu_font_size', '14px' );
						set_theme_mod( 'menu_font_size', '14px' );
						set_theme_mod( 'footer_widget_title_font_size', '16px' );
						set_theme_mod( 'header_height', '' );
						set_theme_mod( 'menu_dropdown_top_border', '' );
						set_theme_mod( 'top_bar_content', '[font_awesome icon="check" size="16px" margin_right="8px"]Call Today For A Consultation: 1-800-987-6543' );
					}
					// Agent
					elseif ( 'agent' == $options['skin'] ) {
					}
					// Flat
					elseif ( 'flat' == $options['skin'] ) {
						set_theme_mod( 'header_style', 'three' );
						set_theme_mod( 'menu_font_family', 'Lato' );
						set_theme_mod( 'body_font_family', 'Roboto Slab' );
						set_theme_mod( 'main_search_toggle_style', 'overlay' );
						set_theme_mod( 'woo_menu_icon_style', 'overlay' );
					}
					$error_msg = __( 'Skin and recommended settings successfully updated.', 'wpex' );
				} else {
					$error_msg = __( 'Skin updated and settings kept intact.', 'wpex' );
				}
			} else {
				$error_msg = __( 'Skin successfully updated.', 'wpex' );
			}

			// Update theme mod with skin value
			if ( $skin ) {
				set_theme_mod( 'theme_skin', $skin );
			}

			// Display message
			add_settings_error(
				'wpex-skins-notices',
				esc_attr( 'settings_updated' ),
				$error_msg,
				'updated'
			);

			// Clear options
			$options = '';

		}

		/**
		 * Settings page output
		 */
		function create_admin_page() { ?>
			<div class="wrap wpex-skins-admin">
				<h2><?php _e( 'Theme Skins', 'wpex' ); ?></h2>
				<?php
				// Current skin from site_theme option
				$option = get_theme_mod( 'theme_skin', 'base' );

				// Get fallback from redux
				if( ! $option ) {
					$data	= get_option( 'wpex_options' );
					$option	= isset( $data['site_theme'] ) ? $data['site_theme'] : 'base';
				} ?>
				<form method="post" action="options.php">
					<?php settings_fields( 'wpex_skins_options' ); ?>
					<div class="wpex-skins-select theme-browser" id="theme_skin">
						<?php
						// Get and loop through skins
						$skins = wpex_skins();
						foreach ( $skins as $key => $optionue ) {
						$checked = $active = '';
						if ( '' != $option && ( $option == $key ) ) {
							$checked	= 'checked';
							$active		= 'active';
						} ?>
						<div class="wpex-skin <?php echo $active; ?> theme">
							<input type="radio" id="wpex-skin-<?php echo $key; ?>" name="theme_skin" value="<?php echo $key; ?>" <?php echo $checked; ?> class="wpex-skin-radio" />
							<div class="theme-screenshot">
								<img src="<?php echo $optionue['screenshot'] ?>" alt="<?php _e( 'Screenshot', 'wpex' ); ?>" />
							</div>
							<h3 class="theme-name">
								<?php if( 'active' == $active ) {
									echo '<strong>'. __( 'Active', 'wpex' ). ':</strong> ';
								} ?>
								<?php echo $optionue[ 'name' ]; ?>
							</h3>
						</div>
						<?php } ?>
					</div>
					<p style="margin: 0 0 30px;display:none;">
						<input type="hidden" name="wpex_set_theme_defaults[skin]" id="wpex-hidden-skin-val" value="<?php echo $option; ?>">
						<label>
							<input type="checkbox" name="wpex_set_theme_defaults[toggle]" style="margin-right:10px;" id="wpex-delete-mods"><?php _e( 'Delete all theme options and load recommended settings for this skin?', 'wpex' ); ?>
						</label>
						<label id="wpex-delete-mods-confirm" style="display:none;margin-top:20px;margin-left:20px;">
							<input type="checkbox" name="wpex_set_theme_defaults[confirm]"style="margin-right:10px"><?php _e( 'Please Confirm', 'wpex' ); ?>
						</label>
					</p>
					<?php submit_button(); ?>
				</form>
			</div>
		<?php }
	}
}
new WPEX_Skins_Admin();