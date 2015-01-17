<?php
/**
 * Alter the default WordPress password protection form so it can be easily styled
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0
 */

if ( ! function_exists( 'wpex_password_form' ) ) {
	function wpex_password_form() {
		global $post;
		$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
		$classes = 'password-protection-box clr';
		if ( 'full-screen' == wpex_get_post_layout_class() ) {
			$classes .= ' container';
		}
		$output = '<div class="'. $classes .'"><form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
		<h2>' . __( 'Password Protected', 'wpex' ) . '</h2>
		<p>'. __( 'This content is password protected. To view it please enter your password below:', 'wpex' ) .'</p>
		<input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" placeholder="'. __( 'Password', 'wpex' ) .'" /><input type="submit" name="Submit" value="' . esc_attr__( 'Submit', 'wpex' ) . '" />
		</form></div>';
		return $output;
	}
}
add_filter( 'the_password_form', 'wpex_password_form' );