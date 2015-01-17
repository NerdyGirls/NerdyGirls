<?php
/**
 * Shortcodes in the TinyMCE
 *
 * @since Total 1.3.6
 */

if ( get_theme_mod( 'shortcodes_tinymce', true ) ) {
	require_once( WPEX_FRAMEWORK_DIR .'shortcodes/tinymce.php' );
}

/**
 * Allow shortcodes in widgets
 *
 * @since Total 1.3.3
 */
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Fixes spacing issues with shortcodes
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_fix_shortcodes' ) ) {
	function wpex_fix_shortcodes( $content ){
		$array = array (
			'<p>['		=> '[', 
			']</p>'		=> ']', 
			']<br />'	=> ']'
		);
		$content = strtr( $content, $array) ;
		return $content;
	}
}
add_filter( 'the_content', 'wpex_fix_shortcodes' );

/**
 * Year shortcode
 *
 * @since Total 1.0.0
 */
if( ! function_exists( 'wpex_year_shortcode' ) ) {
	function wpex_year_shortcode() {
		return date('Y');
	}
}
add_shortcode( 'current_year', 'wpex_year_shortcode' );

/**
 * WPML Shortcode
 *
 * [wpml_translate lang=es]Hola[/wpml_translate]
 * [wpml_translate lang=en]Hello[/wpml_translate]
 *
 * @since Total 1.2.1
 */
if( ! function_exists( 'wpex_wpml_lang_translate_shortcode' ) ) {
	function wpex_wpml_lang_translate_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'lang'	=> '',
		), $atts ) );
		$lang_active = ICL_LANGUAGE_CODE;
		if( $lang == $lang_active ) {
			return do_shortcode($content);
		}
	}
}
add_shortcode( 'wpml_translate', 'wpex_wpml_lang_translate_shortcode' );

/**
 * Font Awesome Shortcode
 *
 * @since Total 1.3.2
 */
if( ! function_exists( 'wpex_font_awesome_shortcode' ) ) {
	function wpex_font_awesome_shortcode( $atts ) {
		extract( shortcode_atts( array (
			'icon'			=> '',
			'margin_right'	=> '',
			'margin_left'	=> '',
			'margin_top'	=> '',
			'margin_bottom'	=> '',
			'color'			=> '',
			'size'			=> '',
		), $atts ) );
		$style = array();
		if ( $color ) {
			$style[] = 'color: #'. str_replace( '#', '', $color ) .';';
		}
		if ( $margin_left ) {
			$style[] = 'margin-left: '. intval( $margin_left ) .'px;';
		}
		if ( $margin_right ) {
			$style[] = 'margin-right: '. intval( $margin_right ) .'px;';
		}
		if ( $margin_top ) {
			$style[] = 'margin-top: '. intval( $margin_top ) .'px;';
		}
		if ( $margin_bottom ) {
			$style[] = 'margin-bottom: '. intval( $margin_bottom ) .'px;';
		}
		if ( $size ) {
			$style[] = 'font-size: '. intval( $size ) .'px;';
		}
		$style = implode('', $style);
		if ( $style ) {
			$style = wp_kses( $style, array() );
			$style = ' style="' . esc_attr( $style) . '"';
		}
		$output = '<i class="fa fa-'. $icon .'" '. $style .'></i>';
		return $output;
	}
}
add_shortcode( 'font_awesome', 'wpex_font_awesome_shortcode' );

/**
 * Login Link
 *
 * @since Total 1.3.2
 */
if( ! function_exists( 'wpex_wp_login_url_shortcode' ) ) {
	function wpex_wp_login_url_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'login_url'			=> '',
			'text'				=> __( 'Login', 'wpex' ),
			'logout_text'		=> __( 'Log Out', 'wpex' ),
			'target'			=> 'blank',
			'logout_redirect'	=> '',
		), $atts ) );
		if ( 'blank' == $target ) {
			$target = 'target="_blank"';
		} else {
			$target = '';
		}
		if ( ! $login_url ) {
			$login_url = wp_login_url();
		}
		if ( ! $logout_redirect ) {
			$permalink = get_permalink();
			if ( $permalink ) {
				$logout_redirect = $permalink;
			} else {
				$logout_redirect = home_url();
			}
		}
		if ( is_user_logged_in() ) {
			return '<a href="'. wp_logout_url( $logout_redirect ) .'" title="'. $logout_text .'" class="wpex-logout" rel="nofollow">'. $logout_text .'</a>';
		} else {
			return '<a href="'. $login_url .'" title="'. $text .'" class="wpex-login" rel="nofollow" '. $target .'>'. $text .'</a>';
		}
	}
}
add_shortcode( 'wp_login_url', 'wpex_wp_login_url_shortcode' );

/**
 * WPML Language Switcher
 *
 * @since Total 1.3.6
 */
if( ! function_exists( 'wpex_wpml_lang_switcher_shortcode' ) ) {
	function wpex_wpml_lang_switcher_shortcode() {
		do_action( 'icl_language_selector' );
	}
}
add_shortcode( 'wpml_lang_selector', 'wpex_wpml_lang_switcher_shortcode' );

/**
 * Polylang Language Switcher
 *
 * @since Total 1.4.0
 */
if( ! function_exists( 'wpex_polylang_switcher' ) ) {
	function wpex_polylang_switcher( $atts ) {
		extract( shortcode_atts( array(
			'dropdown'		=> 'false',
			'show_flags'	=> 'true',
			'show_names'	=> 'false',
			'classes'		=> '',
		), $atts ) );
		if ( function_exists( 'pll_the_languages' ) ) {
			// Args
			$dropdown = 'true' == $dropdown ? true : false;
			$show_flags = 'true' == $show_flags ? true : false;
			$show_names = 'true' == $show_names ? true : false;
			if ( $dropdown ) {
				$show_flags = $show_names = false;
			}
			// Classes
			$classes = 'polylang-switcher-shortcode clr';
			if ( $show_names && !$dropdown ) {
				$classes .= ' flags-and-names';
			}
			// Display Switcher
			if ( ! $dropdown ) {
				echo '<ul class="'. $classes .'">';
			}
				// Display the switcher
				pll_the_languages( array(
					'dropdown'		=> $dropdown,
					'show_flags'	=> $show_flags,
					'show_names'	=> $show_names
				) );
			if ( ! $dropdown ) {
				echo '</ul>';
			}
		}
	}
}
add_shortcode( 'polylang_switcher', 'wpex_polylang_switcher' );