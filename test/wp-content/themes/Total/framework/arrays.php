<?php
/**
 * Useful arrays
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link 		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

/**
 * Font Weights
 *
 * @since	Total 1.6.2
 * @return	array
 */
if ( ! function_exists( 'wpex_font_weights_array' ) ) {
	function wpex_font_weights_array() {
		return array(
			__( 'Default', 'wpex' )		=> '',
			'100'						=> '100',
			'200'						=> '200',
			'300'						=> '300',
			'400'						=> '400',
			'500'						=> '500',
			'600'						=> '600',
			'700'						=> '700',
			'800'						=> '800',
			'900'						=> '900',
		);
	}
}

/**
 * Text Transform
 *
 * @since	Total 1.6.2
 * @return	array
 */
if ( ! function_exists( 'wpex_text_transform_array' ) ) {
	function wpex_text_transform_array() {
		return array(
			__( 'Default', 'wpex' )		=> '',
			__( 'None', 'wpex' )		=> 'none',
			__( 'Capitalize', 'wpex' )	=> 'capitalize',
			__( 'Uppercase', 'wpex' )	=> 'uppercase',
			__( 'Lowercase', 'wpex' )	=> 'lowercase',
		);
	}
}

/**
 * Border Styles
 *
 * @since	Total 1.6.0
 * @return	array
 */
if ( ! function_exists( 'wpex_border_styles_array' ) ) {
	function wpex_border_styles_array() {
		return array(
			__( 'None', 'wpex' )	=> '',
			__( 'Solid', 'wpex' )	=> 'solid',
			__( 'Dotted', 'wpex' )	=> 'dotted',
			__( 'Dashed', 'wpex' )	=> 'dashed',
		);
	}
}

/**
 * Alignments
 *
 * @since	Total 1.6.0
 * @return	array
 */
if ( ! function_exists( 'wpex_alignments_array' ) ) {
	function wpex_alignments_array() {
		return array(
			__( 'None', 'wpex' )			=> 'none',
			__( 'Align left', 'wpex' )		=> 'left',
			__( 'Align right', 'wpex' )		=> 'right',
			__( 'Align center', 'wpex' )	=> 'center',
		);
	}
}

/**
 * Visibility
 *
 * @since	Total 1.6.0
 * @return	array
 */
if ( ! function_exists( 'wpex_visibility_array' ) ) {
	function wpex_visibility_array() {
		return array(
			__( 'All', 'wpex' )						=> '',
			__( 'Hidden on Phones', 'wpex' )		=> 'hidden-phone',
			__( 'Hidden on Tablets', 'wpex' )		=> 'hidden-tablet',
			__( 'Hidden on Desktop', 'wpex' )		=> 'hidden-desktop',
			__( 'Visible on Desktop Only', 'wpex' )	=> 'visible-desktop',
			__( 'Visible on Phones Only', 'wpex' )	=> 'visible-phone',
			__( 'Visible on Tablets Only', 'wpex' )	=> 'visible-tablet',
		);
	}
}

/**
 * CSS Animations
 *
 * @since	Total 1.6.0
 * @return	array
 */
if ( ! function_exists( 'wpex_css_animations_array' ) ) {
	function wpex_css_animations_array() {
		return array(
			__( 'None', 'wpex' )				=> '',
			__( 'Top to bottom', 'wpex' )		=> 'top-to-bottom',
			__( 'Bottom to top', 'wpex' )		=> 'bottom-to-top',
			__( 'Left to right', 'wpex' )		=> 'left-to-right',
			__( 'Right to left', 'wpex' )		=> 'right-to-left',
			__( 'Appear from center', 'wpex' )	=> 'appear',
		);
	}
}

/**
 * Returns array of Social Options for the Top Bar
 *
 * @since	Total 1.6.0
 * @return	bool
 */
if ( ! function_exists( 'wpex_topbar_social_options' ) ) {
	function wpex_topbar_social_options() {
		$social_options = array(
			'twitter'		=> array(
				'label' 		=> 'Twitter',
				'icon_class'	=> 'fa fa-twitter',
			),
			'facebook'		=> array(
				'label' 		=> 'Facebook',
				'icon_class'	=> 'fa fa-facebook',
			),
			'googleplus'	=> array(
				'label' 		=> 'Google Plus',
				'icon_class'	=> 'fa fa-google-plus',
			),
			'pinterest'		=> array(
				'label' 		=> 'Pinterest',
				'icon_class'	=> 'fa fa-pinterest',
			),
			'dribbble'		=> array(
				'label' 		=> 'Dribbble',
				'icon_class'	=> 'fa fa-dribbble',
			),
			'vk'			=> array(
				'label' 		=> 'Vk',
				'icon_class'	=> 'fa fa-vk',
			),
			'instagram'		=> array(
				'label' 		=> 'Instragram',
				'icon_class'	=> 'fa fa-instagram',
			),
			'linkedin'		=> array(
				'label' 		=> 'LinkedIn',
				'icon_class'	=> 'fa fa-linkedin',
			),
			'tumblr'		=> array(
				'label' 		=> 'Tumblr',
				'icon_class'	=> 'fa fa-tumblr',
			),
			'github'		=> array(
				'label' 		=> 'Github',
				'icon_class'	=> 'fa fa-github-alt',
			),
			'flickr'		=> array(
				'label' 		=> 'Flickr',
				'icon_class'	=> 'fa fa-flickr',
			),
			'skype'			=> array(
				'label' 		=> 'Skype',
				'icon_class'	=> 'fa fa-skype',
			),
			'youtube'		=> array(
				'label' 		=> 'Youtube',
				'icon_class'	=> 'fa fa-youtube',
			),
			'vimeo'			=> array(
				'label' 		=> 'Vimeo',
				'icon_class'	=> 'fa fa-vimeo-square',
			),
			'vine'			=> array(
				'label' 		=> 'Vine',
				'icon_class'	=> 'fa fa-vine',
			),
			'xing'			=> array(
				'label' 		=> 'Xing',
				'icon_class'	=> 'fa fa-xing',
			),
			'yelp'			=> array(
				'label' 		=> 'Yelp',
				'icon_class'	=> 'fa fa-yelp',
			),

			'rss'			=> array(
				'label' 		=> __( 'RSS', 'wpex' ),
				'icon_class'	=> 'fa fa-rss',
			),
			'email'			=> array(
				'label' 		=> __( 'Email', 'wpex' ),
				'icon_class'	=> 'fa fa-envelope',
			),
		);
		return apply_filters ( 'wpex_topbar_social_options', $social_options );
	}
}

/**
 * Array of social profiles for staff members
 *
 * @since Total 1.5.4
 */
function wpex_staff_social_array() {
	$array = array(
		array (
			'key'			=> 'twitter',
			'meta'			=> 'wpex_staff_twitter',
			'icon_class'	=> 'fa fa-twitter',
			'label'			=> 'Twitter',
		),
		array (
			'key'			=> 'facebook',
			'meta'			=> 'wpex_staff_facebook',
			'icon_class'	=> 'fa fa-facebook',
			'label'			=> 'Facebook',
		),
		array (
			'key'			=> 'google_plus',
			'meta'			=> 'wpex_staff_google-plus',
			'icon_class'	=> 'fa fa-google-plus',
			'label'			=> 'Google Plus',
		),
		array (
			'key'			=> 'linkedin',
			'meta'			=> 'wpex_staff_linkedin',
			'icon_class'	=> 'fa fa-linkedin',
			'label'			=> 'Linkedin',
		),
		array (
			'key'			=> 'dribbble',
			'meta'			=> 'wpex_staff_dribbble',
			'icon_class'	=> 'fa fa-dribbble',
			'label'			=> 'Dribbble',
		),
		array (
			'key'			=> 'skype',
			'meta'			=> 'wpex_staff_skype',
			'icon_class'	=> 'fa fa-skype',
			'label'			=> 'Skype',
		),
		array (
			'key'			=> 'vk',
			'meta'			=> 'wpex_staff_vk',
			'icon_class'	=> 'fa fa-vk',
			'label'			=> 'VK',
		),
		array (
			'key'			=> 'email',
			'meta'			=> 'wpex_staff_email',
			'icon_class'	=> 'fa fa-envelope',
			'label'			=> __( 'Email', 'wpex' ),
		),
		array (
			'key'			=> 'website',
			'meta'			=> 'wpex_staff_website',
			'icon_class'	=> 'fa fa-external-link-square',
			'label'			=> __( 'Website', 'wpex' ),
		),
	);
	return apply_filters( 'wpex_staff_social_array', $array );
}

/**
 * Creates an array for adding the staff social options to the redux meta options
 * See framework/redux/meta-config.php
 *
 * @since	Total 1.5.4
 * @return	array
 */
if ( ! function_exists( 'wpex_staff_social_meta_array' ) ) {
	function wpex_staff_social_meta_array() {
		$profiles = wpex_staff_social_array();
		$array = array();
		foreach ( $profiles as $profile ) {
			$array[] = array(
					'title'		=> '<span class="'. $profile['icon_class'] .'"></span>' . $profile['label'],
					'id'		=> $profile['meta'],
					'type'		=> 'text',
					'std'		=> '',
			);
		}
		return $array;
	}
}