<?php
/**
 * Custom Login Page Design
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
if ( ! class_exists( 'WPEX_Custom_Login' ) ) {
	class WPEX_Custom_Login {

		/**
		 * Start things up
		 */
		public function __construct() {

			// Add the page to the admin menu
			add_action( 'admin_menu', array( $this, 'add_page' ) );

			// Register page options
			add_action( 'admin_init', array( $this,'register_settings' ) );

			// Load scripts
			add_action( 'admin_enqueue_scripts',array( $this,'scripts' ) );

			// Display notices
			add_action( 'admin_notices', array( $this, 'notices' ) );

			// Output custom CSS for the login
			add_action( 'login_head', array( $this, 'output_css' ) );

			// Alter default login logo url
			add_action( 'login_headerurl', array( $this, 'logo_link' ) );

			// Get registered option
			$this->options = get_theme_mod( 'login_page_design', array(
				'enabled'					=> true,
				'logo'						=> '',
				'logo_height'				=> '',
				'background_color'			=> '',
				'background_img'			=> '',
				'background_style'			=> '',
				'form_background_color'		=> '',
				'form_background_opacity'	=> '',
				'form_text_color'			=> '',
				'form_top'					=> '',
			) );
		}

		/**
		 * Add sub menu page for the custom CSS input
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_page
		 */
		public function add_page() {
			add_submenu_page(
				'wpex-addons',
				__( 'Login Page', 'wpex' ),
				__( 'Login Page', 'wpex' ),
				'administrator',
				'wpex-custom-login-admin',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Load scripts
		 *
		 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
		 */
		public function scripts( $hook ) {
			if ( WPEX_ADMIN_PANEL_HOOK_PREFIX . 'wpex-custom-login-admin' != $hook ) {
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
			// Color Picker
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script(
				'wpex-color-picker-field',
				WPEX_FRAMEWORK_DIR_URI .'addons/assets/admin-fields/color-picker.js',
				array( 'wp-color-picker' ),
				false,
				true
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_setting
		 */
		public function register_settings() {
			register_setting( 'wpex_custom_login', 'login_page_design', array( $this, 'sanitize' ) );
		}

		/**
		 * Displays all messages registered to 'wpex-custom_css-notices'
		 *
		 * @link http://codex.wordpress.org/Function_Reference/settings_errors
		 */
		public function notices() {
			settings_errors( 'wpex_custom_login_notices' );
		}

		/**
		 * Sanitization callback
		 */
		public function sanitize( $options ) {

			// Set theme mod
			if ( $options ) {
				set_theme_mod( 'login_page_design', $options );
			}

			// Display notice
			add_settings_error(
				'wpex_custom_login_notices',
				esc_attr( 'settings_updated' ),
				__( 'Settings saved.', 'wpex' ),
				'updated'
			);

			// Clear options and return
			$options = '';
			return $options;

		}

		/**
		 * Settings page output
		 */
		public function create_admin_page() { ?>
			<div class="wrap">
				<h2 style="padding-right:0;">
					<?php _e( 'Custom Login Page Design', 'wpex' ); ?>
				</h2>
				<?php $theme_mod = $this->options; ?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'wpex_custom_login' );
					do_settings_sections( 'wpex_custom_login' ); ?>
					<table class="form-table wpex-admin-table">
						<tr valign="top">
							<th scope="row"><?php _e( 'Enable', 'wpex' ); ?></th>
							<td>
								<?php $enabled = isset ( $theme_mod['enabled'] ) ? $theme_mod['enabled'] : 'on'; ?>
								<input type="checkbox" name="login_page_design[enabled]" <?php checked( $enabled, 'on' ); ?>> <?php _e( 'Enable the custom Login Screen.', 'wpex' ); ?>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Logo', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['logo'] ) ? $theme_mod['logo'] : ''; ?>
								<input type="text" name="login_page_design[logo]" value="<?php echo $option; ?>">
								<input class="wpex-media-upload-button button-secondary" type="button" value="<?php _e( 'Upload', 'wpex' ); ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Logo Height', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['logo_height'] ) ? $theme_mod['logo_height'] : ''; ?>
								<input type="text" name="login_page_design[logo_height]" value="<?php echo $option; ?>">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Logo URL', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['logo_url'] ) ? $theme_mod['logo_url'] : ''; ?>
								<input type="text" name="login_page_design[logo_url]" value="<?php echo $option; ?>">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Background Color', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['background_color'] ) ? $theme_mod['background_color'] : ''; ?>
								<input id="background_color" type="text" name="login_page_design[background_color]" value="<?php echo $option; ?>" class="wpex-color-field">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Background Image', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['background_img'] ) ? $theme_mod['background_img'] : ''; ?>
								<div class="uploader">
									<input type="text" name="login_page_design[background_img]" value="<?php echo $option; ?>">
									<input class="wpex-media-upload-button button-secondary" type="button" value="<?php _e( 'Upload', 'wpex' ); ?>" />
								</div>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Background Image Style', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['background_style'] ) ? $theme_mod['background_style'] : ''; ?>
								<select name="login_page_design[background_style]">
									<?php
									$bg_styles = array(
										'stretched'	=> __( 'Stretched','wpex' ),
										'repeat'	=> __( 'Repeat','wpex' ),
										'fixed'		=> __( 'Center Fixed','wpex' )
									);
									foreach ( $bg_styles as $key => $val ) { ?>
										<option value="<?php echo $key; ?>" <?php selected( $option, $key, true ); ?>><?php echo $val; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Form Background Color', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['form_background_color'] ) ? $theme_mod['form_background_color'] : ''; ?>
								<input id="form_background_color" type="text" name="login_page_design[form_background_color]" value="<?php echo $option; ?>" class="wpex-color-field">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Form Background Opacity', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['form_background_opacity'] ) ? $theme_mod['form_background_opacity'] : ''; ?>
								<input type="text" name="login_page_design[form_background_opacity]" value="<?php echo $option; ?>">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Form Text Color', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['form_text_color'] ) ? $theme_mod['form_text_color'] : ''; ?>
								<input id="form_text_color" type="text" name="login_page_design[form_text_color]" value="<?php echo $option; ?>" class="wpex-color-field">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Form Top Margin', 'wpex' ); ?></th>
							<td>
								<?php $option = isset( $theme_mod['form_top'] ) ? $theme_mod['form_top'] : '150px'; ?>
								<input type="text" name="login_page_design[form_top]" value="<?php echo $option; ?>">
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			</div><!-- .wrap -->
		<?php }

		/**
		 * RGBA to HEX conversions
		 */
		private function hex2rgba( $color, $opacity = false ) {

			// Define default rgba
			$default = 'rgb(0,0,0)';

			//Return default if no color provided
			if( empty( $color ) ) {
				return $default;
			}

			//Sanitize $color if "#" is provided 
			if ( $color[0] == '#' ) {
				$color = substr( $color, 1 );
			}

			//Check if color has 6 or 3 characters and get values
			if ( strlen( $color ) == 6) {
				$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			} elseif ( strlen( $color ) == 3 ) {
				$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			} else {
				return $default;
			}

			//Convert hexadec to rgb
			$rgb =  array_map( 'hexdec', $hex );

			//Check if opacity is set(rgba or rgb)
			if( $opacity ) {
				if( abs ( $opacity ) > 1 )
					$opacity = 1.0;
				$output = 'rgba('.implode( ",", $rgb ).','.$opacity.')';
			} else {
				$output = 'rgb('.implode( ",", $rgb ).')';
			}

			//Return rgb(a) color string
			return $output;
		}

		/**
		 * Outputs the CSS for the custom login page
		 *
		 * @link http://codex.wordpress.org/Customizing_the_Login_Form
		 */
		public function output_css() {

			// Get settings
			$options = $this->options;

			// Do nothing if disabled
			if ( empty( $options['enabled'] ) ) {
				return;
			}

			// Sanitize data
			$logo						= ! empty( $options['logo'] ) ? $options['logo'] : '';
			$logo_height				= ! empty( $options['logo_height'] ) ? $options['logo_height'] : '84px';
			$background_img				= ! empty( $options['background_img'] ) ? $options['background_img'] : '';
			$background_style			= ! empty( $options['background_style'] ) ? $options['background_style'] : '';
			$background_color			= ! empty( $options['background_color'] ) ? $options['background_color'] : '';
			$form_background_color		= ! empty( $options['form_background_color'] ) ? $options['form_background_color'] : '';
			$form_background_opacity	= ! empty( $options['form_background_opacity'] ) ? $options['form_background_opacity'] : '';
			$form_text_color			= ! empty( $options['form_text_color'] ) ? $options['form_text_color'] : '';
			$form_top					= ! empty( $options['form_top'] ) ? $options['form_top'] : '';

			// Output Styles
			$output = '<style type="text/css">';

				// Logo
				if ( $logo ) {
					$output .='body.login div#login h1 a {';
						$output .='background: url("'. $logo .'") center center no-repeat;';
						$output .='height: '. intval( $logo_height ) .'px;';
						$output .='width: 100%;';
						$output .='display: block;';
						$output .='margin: 0 auto 30px;';
					$output .='}';
				}

				// Background image
				if ( $background_img ) {
					if ( 'stretched' == $background_style ) {
						$output .= 'body.login { background: url('. $background_img .') no-repeat center center fixed; -webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover; }';
					} elseif ( 'repeat' == $background_style ) {
						$output .= 'body.login { background: url('. $background_img .') repeat; }';
					} elseif ( 'fixed' == $background_style ) {
						$output .= 'body.login { background: url('. $background_img .') center top fixed no-repeat; }';
					}
				}

				// Background color
				if ( $background_color ) {
					$output .='body.login { background-color: '. $background_color .'; }';
				}

				// Form Background Color
				if ( $form_background_color ) {
					$form_bg_color_rgba = self::hex2rgba( $form_background_color, $form_background_opacity );
					$output .='.login form { background: none; -webkit-box-shadow: none; box-shadow: none; padding: 0 0 20px; } #backtoblog { display: none; } .login #nav { text-align: center; }';
					$output .='body.login div#login { background: '. $form_background_color .'; background: '. $form_bg_color_rgba .';height:auto;left:50%;margin: 0 0 0 -200px;padding:40px;position:absolute;width:320px; max-width:90%; border-radius: 5px; }';
				}

				// Form top
				if ( $form_top ) {
					$output .= 'body.login div#login { top:'. intval( $form_top ) .'px; }';
				}

				// Text Color
				if ( $form_text_color ) {
					$output .='.login label, .login #nav a, .login #backtoblog a, .login #nav { color: '. $form_text_color .'; }';
					
				}

			$output .='</style>';

			echo $output;

		}

		/**
		 * Custom login page logo URL
		 *
		 * @link http://codex.wordpress.org/Customizing_the_Login_Form
		 */
		public function logo_link( $url ) {
			$options	= $this->options;
			$logo_url	= isset( $options['logo_url']) ? $options['logo_url'] : '';
			if ( $logo_url ) {
				$url = esc_url( $logo_url );
			}
			$url = apply_filters( 'wpex_login_logo_link', $url );
			return $url;
		}

	}
}
new WPEX_Custom_Login();