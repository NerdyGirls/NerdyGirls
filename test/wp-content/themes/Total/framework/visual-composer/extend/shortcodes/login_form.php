<?php
/**
 * Registers the login form shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.1
 */

if ( !function_exists('vcex_login_form_shortcode') ) {
	function vcex_login_form_shortcode( $atts, $content=NULL ) {
		
		// Shortcode params
		extract( shortcode_atts( array(
			'unique_id'		=> '',
			'redirect'		=> '',
		),
		$atts ) );

		// Enable output buffer
		ob_start();

			// If user is logged return text
			if ( is_user_logged_in() && !wpex_is_front_end_composer() ) {
				return $content;
			} else {

				// Redirection URL
				if ( $redirect == '' ) {
					$redirect = site_url( $_SERVER['REQUEST_URI'] );
				}
				
				// Form args
				$args = array(
					'echo'				=> true,
					'redirect'			=> $redirect,
					'form_id'			=> 'vcex-loginform',
					'label_username'	=> __( 'Username', 'wpex' ),
					'label_password'	=> __( 'Password', 'wpex' ),
					'label_remember'	=> __( 'Remember Me', 'wpex' ),
					'label_log_in'		=> __( 'Log In', 'wpex' ),
					'remember'			=> true,
					'value_username'	=> NULL,
					'value_remember'	=> false,
				); ?>

				<div class="vcex-login-form clr">
					<?php wp_login_form($args); ?>
				</div><!-- .vcex-login-form -->

			<?php } ?>

		<?php
		// Return content
		return ob_get_clean();
		
	}
}
add_shortcode( 'vcex_login_form', 'vcex_login_form_shortcode' );

if ( ! function_exists( 'vcex_login_form_shortcode_vc_map' ) ) {
	function vcex_login_form_shortcode_vc_map() {
		vc_map( array(
			"name"					=> __( "Login Form", 'wpex' ),
			"description"			=> __( "Adds a WordPress login form", 'wpex' ),
			"base"					=> "vcex_login_form",
			'category'				=> WPEX_THEME_BRANDING,
			"icon"					=> "vcex-login-form",
			"params"				=> array(
				array(
					"type"			=> "textfield",
					"class"			=> "",
					"heading"		=> __( "Redirect", 'wpex' ),
					"param_name"	=> "redirect",
					"value"			=> "",
					"description"	=> __( "Enter a URL to redirect the user after they successfully log in. Leave blank to redirect to the current page.","wpex"),
				),
				array(
					"type"			=> "textarea_html",
					"heading"		=> __( "Logged in Content", 'wpex' ),
					"param_name"	=> "content",
					"value"			=> __('You are currently logged in','wpex'),
					"description"	=> __( "The content to displayed for logged in users.","wpex"),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_login_form_shortcode_vc_map' );