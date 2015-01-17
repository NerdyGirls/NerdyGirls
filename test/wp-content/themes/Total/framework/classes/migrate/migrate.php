<?php
/**
 * Migrates old Redux options to the Theme Customizer
 *
 * @package		Total
 * @subpackage	Classes
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

// Return if class exists
if ( ! class_exists( 'WPEX_Migrate_Redux' ) ) {
	return;
}

// Return if migration is complete
if ( get_option( 'wpex_customizer_migration_complete' ) ) {
	return;
}

// The migration class
class WPEX_Migrate_Redux {

	/**
	 * Redux options
	 *
	 * @return array
	 */
	private $redux_options = array();

	/**
     * Start things up
     *
     * @since 1.6.0
     */
    public function __construct() {

		// Get old redux options
		$this->redux_options = get_option( 'wpex_options' );

		// If there aren't any options (first time installs ) set customizer to complete
		if ( empty( $this->redux_options) ) {
			update_option( 'wpex_customizer_migration_complete', 'completed' );
		}

		// Otherwise lets run the migration function
		else {
			$this->migrate_settings();
			$this->migrate_admin_login();
		}

		// Save option to prevent migration from running again
		update_option( 'wpex_customizer_migration_complete', 'completed' );

		// Clear the customizer cache
		remove_theme_mod( 'wpex_customizer_css_cache' );
		remove_theme_mod( 'wpex_customizer_typography_cache' );

    }

    /**
	 * Migrate main settings
	 *
	 * @return array
	 */
    public function migrate_settings() {

    	// Make sure vc extensions are enabled
    	set_theme_mod( 'extend_visual_composer', true );

		// Migrate theme skin over
		if ( $skin = get_option( 'theme_skin' ) ) {
			set_theme_mod( 'theme_skin', $skin );
		}

		// Move the custom sidebars over
		if ( $sidebars = get_theme_mod( 'redux-widget-areas' ) ) {
			set_theme_mod( 'widget_areas', $sidebars );
		}

		// Get options
		$options = get_option( 'wpex_options' );

		// Get old sections & loop through theme to set new theme mods
		$sections = $this->redux_array();

		if ( $sections ) {
			foreach ( $sections as $section ) {
				$fields = $section['fields'];
				foreach( $fields as $field ) {

					// Main Vars
					$id		= isset( $field['id'] ) ? $field['id'] : '';
					$type	= isset( $field['type'] ) ? $field['type'] : '';
					$mode	= isset( $field['mode'] ) ? $field['mode'] : '';
					$val	= isset( $options[$id] ) ? $options[$id] : '';

					// Alter values
					if ( 'vc_row_bottom_margin' == $id ) {
						if ( ! $val ) {
							$val = '0px';
						}
					}

					// Text
					if( in_array( $type, array( 'text', 'select', 'textarea', 'color', 'button_set', 'editor' ) ) ) {
						if ( 'site_theme' == $id ) {
							update_option( 'site_theme', $val );
						} elseif ( 'blog_cats_exclude' == $id ) {
							if ( $val ) {
								$val = implode( ',', $val );
								set_theme_mod( $id, $val );
							}
						} elseif ( 'custom_admin_login' == $id || 'admin_login_logo' == $id || 'admin_login_logo_height' == $id || 'admin_login_logo_url' == $id || 'admin_login_background_color' == $id || 'admin_login_background_img' == $id || 'admin_login_background_style' == $id || 'admin_login_form_background_color' == $id || 'admin_login_form_background_opacity' == $id || 'admin_login_form_text_color' == $id || 'admin_login_form_top' == $id) {
							// Do nothing
						} else {
							set_theme_mod( $id, $val );
						}
					}

					// Padding
					elseif( 'spacing' == $type && 'padding' == $mode ) {

						if ( $val ) {
							$top	= ! empty( $val['padding-top'] ) ? $val['padding-top'] : '0';
							$bottom	= ! empty( $val['padding-bottom'] ) ? $val['padding-bottom'] : '0';
							$right	= ! empty( $val['padding-right'] ) ? $val['padding-right'] : '0';
							$left	= ! empty( $val['padding-left'] ) ? $val['padding-left'] : '0';
							$val	= $top .' '. $right .' '. $bottom .' '. $left;
							set_theme_mod( $id, $val );
						}

					}
					// Sorter
					elseif( 'sorter' == $type ) {
						$blocks = $val;
						if ( isset( $blocks['enabled'] ) ) {
							$blocks = $blocks['enabled'];
							unset($blocks['placebo']);
							$blocks = array_keys( $blocks );
							$blocks = implode( ',', $blocks );
							set_theme_mod( $id, $blocks );
						} else {
							set_theme_mod( $id, '' );
						}
					}

					// Link Color
					elseif( 'link_color' == $type ) {
						$regular	= isset( $val['regular'] ) ? $val['regular'] : '';
						$hover		= isset( $val['hover'] ) ? $val['hover'] : '';
						$active		= isset( $val['active'] ) ? $val['active'] : '';
						if ( $regular ) {
							set_theme_mod( $id, $regular );
						}
						if ( $hover ) {
							set_theme_mod( $id .'_hover', $hover );
						}
						if ( $active ) {
							set_theme_mod( $id .'_active', $active );
						}
					}

					// Switch
					elseif( 'switch' == $type ) {
						if( '0' == $val ) {
							$val = false;
						} else {
							$val = true;
						}
						set_theme_mod( $id, $val );
					}

					// Image
					elseif( 'media' == $type ) {
						$val = isset( $val['url'] ) ? $val['url'] : '';
						set_theme_mod( $id, $val );
					}

					// Image Select
					elseif ( 'image_select' == $type ) {
						if( '1' == $val ) {
							$val = false;
						}
						set_theme_mod( $id, $val );
					}

					// Gradient
					elseif ( 'color_gradient' == $type ) {
						$from	= isset ( $val['from'] ) ? $val['from'] : '';
						$to		= isset ( $val['to'] ) ? $val['to'] : '';
						set_theme_mod( $id, $from );
					}

					// Social
					elseif( 'sortable' == $type && 'top_bar_social_options' == $id ) {
						$array = array();
						if ( is_array( $val ) ) {
						foreach ( $val as $key => $value ) {
							if( 'github-alt' == $key ) {
								$key = 'github';
							} elseif( 'vimeo-square' == $key ) {
								$key = 'vimeo';
							} elseif( 'google-plus' == $key ) {
								$key = 'googleplus';
							}
							$array[$key] = $value;
							set_theme_mod( 'top_bar_social_profiles', $array );
						}
					}

					// Font
					} elseif( 'typography' == $type && 'load_custom_font_1' != $id ) {

						// Get Font
						$font = $val;

						// Remove "font" from ID
						$id = str_replace( '_font', '', $id );
						$id = str_replace( '_typography', '', $id );

						// Standardize id's
						if ( 'breadcrumbs_typography' == $id ) {
							$id = 'breadcrumbs_font';
						} elseif ( 'sidebar_widget_title_typography' == $id ) {
							$id = 'sidebar_widget_title_font';
						} elseif ( 'footer_widget_title_typography' == $id ) {
							$id = 'footer_widget_title_font';
						}

						// Get Font Options
						$family			= isset( $font['font-family'] ) ? $font['font-family'] : '';
						$size			= isset( $font['font-size'] ) ? intval( $font['font-size'] ) : '';
						$weight			= isset( $font['font-weight'] ) ? $font['font-weight'] : '';
						$style			= isset( $font['font-style'] ) ? $font['font-style'] : '';
						$color			= isset( $font['color'] ) ? $font['color'] : '';
						$letter_spacing	= isset( $font['letter-spacing'] ) ? intval( $font['letter-spacing'] ) : '';
						$line_height	= isset( $font['line-height'] ) ? intval( $font['line-height'] ) : '';

						// Create array to update theme mod
						$array = array();

						// Update theme mods
						if( $family == 'inherit' ) {
							$array['font-family'] = '';
						} elseif( $family ) {
							$array['font-family'] = $family;
						}

						if( $size == 'inherit' ) {
							$array['font-size'] = '';
						} elseif( $size) {
							$array['font-size'] = $size;
						}

						if( $weight == 'inherit' ) {
							$array['font-weight'] = '';
						} elseif( $weight ) {
							$array['font-weight'] = $weight;
						}

						if( $style == 'inherit' ) {
							$array['font-style'] = '';
						} elseif( $style ) {
							$array['font-style'] = $style;
						}

						if( $color == 'inherit' ) {
							$array['color'] = '';
						} elseif( $color ) {
							$array['color'] = $color;
						}

						if( $letter_spacing == 'inherit' ) {
							$array['letter-spacing'] = '';
						} elseif( $letter_spacing ) {
							$array['letter-spacing'] = $letter_spacing;
						}

						set_theme_mod( $id .'_typography', $array );

					}

					// Alter Id's
					if ( 'footer_col' == $id ) {
						remove_theme_mod( 'footer_col' );
						set_theme_mod( 'footer_widgets_columns', $val );
					} elseif( 'footer_copyright' == $id ) {
						remove_theme_mod( 'footer_copyright' );
						set_theme_mod( 'footer_bottom', $val );
					} elseif( 'extend_visual_composer_extension' == $id ) {
						remove_theme_mod( 'extend_visual_composer_extension' );
						set_theme_mod( 'extend_visual_composer', $val );
					} elseif( 'post_series' == $id ) {
						remove_theme_mod( 'post_series' );
						set_theme_mod( 'post_series_enable', $val );
					} elseif( 'testimonial_entry_image_height' == $id ) {
						remove_theme_mod( 'testimonial_entry_image_height' );
						set_theme_mod( 'testimonials_entry_image_height', $val );
					} elseif( 'testimonial_entry_image_width' == $id ) {
						remove_theme_mod( 'testimonial_entry_image_width' );
						set_theme_mod( 'testimonials_entry_image_width', $val );
					} elseif( 'footer_reveal' == $id ) {
						set_theme_mod( 'footer_reveal', false );
					}

				}
			}
		}


    }

    /**
	 * Migrate the admin login settings
	 *
	 * @return array
	 */
    public function migrate_admin_login() {

    	$options = get_option( 'wpex_options' );
		$admin_login_options = array(
			'custom_admin_login',
			'admin_login_logo',
			'admin_login_logo_height',
			'admin_login_logo_url',
			'admin_login_background_color',
			'admin_login_background_img',
			'admin_login_background_style',
			'admin_login_form_background_color',
			'admin_login_form_background_opacity',
			'admin_login_form_text_color',
			'admin_login_form_top',
		);
		$array = array();
		foreach ( $admin_login_options as $id ) {
			$val = $options[$id];
			if ( 'custom_admin_login' == $id ) {
				$id = 'enabled';
			}
			if ( 'admin_login_background_img' == $id || 'admin_login_logo' == $id ) {
				if ( ! empty ( $val['url'] ) ) {
					$val = $val['url'];
				} else {
					$val = '';
				}
			}
			$id = str_replace( 'admin_login_', '', $id );
			$array[$id] = $val;
		}
		set_theme_mod( 'login_page_design', $array );

    }


    /**
	 * Holds the array of old redux settings
	 *
	 * @return array
	 */
    public function redux_array() {

	    $sections[] = array(
			'id'			=> 'general',
			'title'			=> __( 'General', 'wpex' ),
			'header'		=> __( 'Welcome to the Simple Options Framework Demo', 'wpex' ),
			'icon'			=> 'el-icon-cog el-icon-small',
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'theme_branding',
					'url'		=> true,
					'type'		=> 'text', 
					'title'		=> __( 'Theme Branding', 'wpex' ),
					'default'	=> 'Total',
				),
				array(
					'id'		=> 'logo_icon',
					'type'		=> 'select',
					'title'		=> __( 'Text Logo Icon', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'logo_icon_right_margin',
					'type'		=> 'text',
					'title'		=> __( 'Text Logo Icon Right Margin', 'wpex' ),
				),
				array(
					'id'				=> 'logo_icon_color',
					'type'				=> 'color',
					'transparent'		=> false,
					'title'				=> __( 'Text Logo Icon Color', 'wpex' ),
					'target_element'	=> '#site-logo .fa',
					'target_style'		=> 'color',
				),
				array(
					'id'		=> 'custom_logo',
					'url'		=> true,
					'type'		=> 'media',
					'title'		=> __( 'Logo', 'wpex' ),
					'read-only'	=> false,
					'default'	=> array( 'url'	=> get_template_directory_uri() .'/images/logo/logo.png' ),
				),
				array(
					'id'		=> 'retina_logo',
					'url'		=> true,
					'type'		=> 'media', 
					'title'		=> __( 'Retina Logo', 'wpex' ),
					'default'	=> array( 'url'	=> get_template_directory_uri() .'/images/logo/logo-retina.png' ),
				),
				array(
					'id'		=> 'retina_logo_height',
					'type'		=> 'text', 
					'default'	=> '40px',
					'title'		=> __( 'Standard Logo Height', 'wpex' ),
				),

				// Favicons
				array(
					'id'	=> 'favicon',
					'url'			=> true,
					'type'		=> 'media', 
					'title'		=> __( 'Favicon', 'wpex' ),
					'default'	=> array( 'url'	=> get_template_directory_uri() .'/images/favicons/favicon.png' ),
				),
				array(
					'id'		=> 'iphone_icon',
					'url'		=> true,
					'type'		=> 'media', 
					'title'		=> __( 'Apple iPhone Icon ', 'wpex' ),
					'default'	=> array(
						'url'	=> get_template_directory_uri() .'/images/favicons/apple-touch-icon.png'
					),
				),
				array(
					'id'		=> 'ipad_icon',
					'url'		=> true,
					'type'		=> 'media', 
					'title'		=> __( 'Apple iPad Icon ', 'wpex' ),
					'default'	=> array(
						'url'	=> get_template_directory_uri() .'/images/favicons/apple-touch-icon-76x76.png'
					),
				),
				array(
					'id'		=> 'iphone_icon_retina',
					'url'		=> true,
					'type'		=> 'media', 
					'title'		=> __( 'Apple iPhone Retina Icon ', 'wpex' ),
					'default'	=> array(
						'url'	=> get_template_directory_uri() .'/images/favicons/apple-touch-icon-120x120.png'
					),
				),
				array(
					'id'		=> 'ipad_icon_retina',
					'url'		=> true,
					'type'		=> 'media', 
					'title'		=> __( 'Apple iPad Retina Icon ', 'wpex' ),
					'default'	=> array(
						'url'	=> get_template_directory_uri() .'/images/favicons/apple-touch-icon-152x152.png'
					),
				),
				array(
					'id'		=> 'tracking',
					'type'		=> 'textarea',
					'title'		=> __( 'Tracking Code', 'wpex' ),
					'default'	=> ""
				),
			),
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Layout
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'layout',
			'title'			=> __( 'Layout', 'wpex' ),
			'icon'			=> 'el-icon-website',
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'main_layout_style',
					'type'		=> 'select',
					'title'		=> __( 'Layout Style', 'wpex' ), 
					'options'	=> array(
						'full-width'	=> __( 'Full Width','wpex' ),
						'boxed'			=> __( 'Boxed','wpex' )
					),
					'default'	=> 'full-width',
				),
				array(
					'id'		=> 'boxed_dropdshadow',
					'type'		=> 'switch',
					'title'		=> __( 'Boxed Layout Drop-Shadow', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'boxed_padding',
					'type'		=> 'text',
					'title'		=> __( 'Boxed Layout Padding', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'main_container_width',
					'type'		=> 'text',
					'title'		=> __( 'Main Container Width', 'wpex' ),
					'default'	=> '980px',
				),
				array(
					'id'		=> 'left_container_width',
					'type'		=> 'text',
					'title'		=> __( 'Left Container Width', 'wpex' ),
					'default'	=> '680px',
				),
				array(
					'id'		=> 'sidebar_width',
					'type'		=> 'text',
					'title'		=> __( 'Sidebar Width', 'wpex' ),
					'default'	=> '250px',
				),
			),
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Responsive
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'responsive',
			'title'			=> __( 'Responsive', 'wpex' ),
			'icon'			=> 'el-icon-resize-small',
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'responsive',
					'type'		=> 'switch',
					'title'		=> __( 'Responsive', 'wpex' ),
					'subtitle'	=> __( 'Enable this option to make your theme compatible with smart phones and tablets.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'mobile_menu_style',
					'type'		=> 'select',
					'title'		=> __( 'Moble Menu Style', 'wpex' ),
					'subtitle'	=> __( 'Select your style.', 'wpex' ),
					'default'	=> 'sidr',
					'options'	=> array(
						'sidr'		=> __( 'Sidebar','wpex' ),
						'toggle'	=> __( 'Toggle','wpex' )
					),
				),
				array(
					'id'		=> 'mobile_menu_sidr_direction',
					'type'		=> 'select',
					'title'		=> __( 'Sidebar Menu Direction', 'wpex' ),
					'subtitle'	=> __( 'Select which way the sidebar style mobile menu should open from.', 'wpex' ),
					'default'	=> 'left',
					'options'	=> array(
						'left'	=> __( 'Left','wpex' ),
						'right'	=> __( 'Right','wpex' )
					),
					'required'			=> array( 'mobile_menu_style', 'equals', array( 'sidr' ) ),
				),
				
				// Tablet Landscape
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Tablet Landscape & Small Desktops (960px - 1280px)', 'wpex' ),
				),
				array(
					'id'		=> 'tablet_landscape_main_container_width',
					'type'		=> 'text',
					'title'		=> __( 'Main Container Width', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom main container width in pixels. Keep in mind the iPad tablet width is only 1024px.', 'wpex' ),
					'default'	=> '980px',
				),
				array(
					'id'		=> 'tablet_landscape_left_container_width',
					'type'		=> 'text',
					'title'		=> __( 'Left Content Width', 'wpex' ),
					'subtitle'	=> __( 'Enter your width in pixels or percentage for your left container.', 'wpex' ),
					'default'	=> '680px',
				),
				array(
					'id'		=> 'tablet_landscape_sidebar_width',
					'type'		=> 'text',
					'title'		=> __( 'Sidebar Width', 'wpex' ),
					'subtitle'	=> __( 'Enter your width in pixels or percentage for your sidebar.', 'wpex' ),
					'default'	=> '250px',
				),


				// Tablet Portrait
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Tablet Portrait (768px - 959px)', 'wpex' ),
				),
				array(
					'id'		=> 'tablet_main_container_width',
					'type'		=> 'text',
					'title'		=> __( 'Main Container Width', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom main container width in pixels.', 'wpex' ),
					'default'	=> '700px',
				),
				array(
					'id'		=> 'tablet_left_container_width',
					'type'		=> 'text',
					'title'		=> __( 'Left Content Width', 'wpex' ),
					'subtitle'	=> __( 'Enter your width in pixels or percentage for your left container.', 'wpex' ),
					'default'	=> '100%',
				),
				array(
					'id'		=> 'tablet_sidebar_width',
					'type'		=> 'text',
					'title'		=> __( 'Sidebar Width', 'wpex' ),
					'subtitle'	=> __( 'Enter your width in pixels or percentage for your sidebar.', 'wpex' ),
					'default'	=> '100%',
				),

				// Mobile
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Phone Size (0 - 767px)', 'wpex' ),
				),
				array(
					'id'		=> 'mobile_landscape_main_container_width',
					'type'		=> 'text',
					'title'		=> __( 'Landscape: Main Container Width', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom main container width in pixels.', 'wpex' ),
					'default'	=> "90%",
				),
				array(
					'id'		=> 'mobile_portrait_main_container_width',
					'type'		=> 'text',
					'title'		=> __( 'Portrait: Main Container Width', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom main container width in pixels.', 'wpex' ),
					'default'	=> '90%',
				),
			),
		);


		/*-----------------------------------------------------------------------------------*/
		/*	- Background
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'background',
			'title'			=> __( 'Background', 'wpex' ),
			'icon'			=> 'el-icon-picture',
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'			=> 'background_color',
					'type'			=> 'color',
					'title'			=> __( 'Background Color', 'wpex' ),
					'default'		=> '',
					'subtitle'		=> __( 'Select your custom background color.', 'wpex' ),
				),
				array(
					'id'		=> 'background_image_toggle',
					'type'		=> 'switch', 
					'title'		=> __( 'Background Image', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'background_image',
					'url'		=> true,
					'type'		=> 'media', 
					'required'	=> array( 'background_image_toggle', 'equals', '1' ),
					'title'		=> __( 'Custom Background Image', 'wpex' ),
					'default'	=> '',
					'subtitle'	=> __( 'Upload a custom background for your site.', 'wpex' ),
				),
				array(
					'id'		=> 'background_style',
					'type'		=> 'select',
					'title'		=> __( 'Background Image Style', 'wpex' ), 
					'required'	=> array('background_image_toggle','equals','1'),
					'subtitle'	=> __( 'Select your preferred background style.', 'wpex' ),
					'options'	=> array(
						'stretched'	=> __( 'Stretched','wpex' ),
						'repeat'	=> __( 'Repeat','wpex' ),
						'fixed'		=> __( 'Center Fixed','wpex' )
					),
					'default'	=> 'stretched'
				),
				array(
					'id'		=> 'background_pattern_toggle',
					'type'		=> 'switch', 
					'title'		=> __( 'Background Pattern', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'	=> 'background_pattern',
					'type'	=> 'image_select', 
					'title'	=> __( 'Pattern', 'wpex' ),
				),
			),
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Typography
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'		=> 'typography',
			'title'		=> __( 'Typography', 'wpex' ),
			'icon'		=> 'el-icon-font',
			'fields'	=> array(
				array(
					'id'				=> 'body_font',
					'type'				=> 'typography',
					'title'				=> __( 'Body', 'wpex' ),
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'		=> '',
						'font-size'			=> '',
						'font-weight'		=> '',
						'line-height'		=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'headings_font',
					'type'				=> 'typography', 
					'title'				=> __( 'Main Headings', 'wpex' ),
					'units'				=> 'px',
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'		=> '',
						'font-weight'		=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'logo_font',
					'type'				=> 'typography',
					'title'				=> __( 'Logo', 'wpex' ),
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'		=> '',
						'font-size'			=> '',
						'font-weight'		=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'menu_font',
					'type'				=> 'typography', 
					'title'				=> __( 'Menu', 'wpex' ),
					'units'				=> 'px',
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'		=> '', 
						'font-size'			=> '',
						'font-weight'		=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'menu_dropdown_font',
					'type'				=> 'typography', 
					'title'				=> __( 'Menu Dropdowns', 'wpex' ),
					'units'				=> 'px',
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'		=> '', 
						'font-size'			=> '',
						'font-weight'		=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'page_header_font',
					'type'				=> 'typography', 
					'title'				=> __( 'Page Title', 'wpex' ),
					'units'				=> 'px',
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'	=> '', 
						'font-size'		=> '',
						'font-weight'	=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'breadcrumbs_typography',
					'type'				=> 'typography', 
					'title'				=> __( 'Breadcrumbs', 'wpex' ),
					'units'				=> 'px',
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'	=> '', 
						'font-size'		=> '',
						'font-weight'	=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'sidebar_widget_title_typography',
					'type'				=> 'typography', 
					'title'				=> __( 'Sidebar Widget Title', 'wpex' ),
					'units'				=> 'px',
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'		=> '', 
						'font-size'			=> '',
						'font-weight'		=> '',
						'line-height'		=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'footer_widget_title_typography',
					'type'				=> 'typography', 
					'title'				=> __( 'Footer Widget Title', 'wpex' ),
					'units'				=> 'px',
					'subtitle'			=> __( 'Select your custom typography options.', 'wpex' ),
					'default'			=> array(
						'font-family'	=> '', 
						'font-size'		=> '',
						'font-weight'	=> '',
						'line-height'	=> '',
						'letter-spacing'	=> '',
					),
				),
				array(
					'id'				=> 'load_custom_font_1',
					'type'				=> 'typography', 
				),
			),
		);


		/*-----------------------------------------------------------------------------------*/
		/*	- Styling
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'		=> 'styling',
			'icon'		=> 'el-icon-brush',
			'title'		=> __( 'Styling', 'wpex' ),
			'customizer'	=> false,
			'fields'	=> array(
				array(
					'id'		=> 'custom_styling',
					'type'		=> 'switch', 
					'title'		=> __( 'Custom Styling', 'wpex' ),
					'subtitle'	=> __( 'Use this option to toggle the custom styling options below on or off. Great for testing purposes.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
			)
		);

		/*** Styling Site Header ***/
		$sections[] = array(
			'id'			=> 'styling_site_header',
			'title'			=> __( 'Styling: Site Header', 'wpex' ),
			'customizer'	=> false,
			'subsection'	=> true,
			'fields'		=> array(
				array(
					'id'					=> 'header_background',
					'type'					=> 'color',

				),
				array(
					'id'				=> 'logo_color',
					'type'				=> 'color',
				),
				array(
					'id'				=> 'logo_hover_color',
					'type'				=> 'color',
				),
				array(
					'id'					=> 'search_button_background',
					'type'					=> 'color_gradient',
				),
				array(
					'id'				=> 'search_button_color',
					'type'				=> 'color',
				),
			),
		);

		/*** Styling Navigation ***/
		$sections[] = array(
			'id'			=> 'styling_navigation',
			'title'			=> __( 'Styling: Navigation', 'wpex' ),
			'customizer'	=> false,
			'subsection'	=> true,
			'fields'		=> array(
				array(
					'id'				=> 'menu_background',
					'type'				=> 'color',
				),
				array(
					'id'				=> 'menu_borders',
					'type'				=> 'color',
				),
				array(
					'id'				=> 'menu_link_color',
					'type'				=> 'link_color',
				),
				array(
					'id'				=> 'menu_link_hover_background',
					'type'				=> 'color',
				),
				array(
					'id'				=> 'menu_link_active_background',
					'type'				=> 'color',
				),
				array(
					'id'				=> 'dropdown_menu_background',
					'type'				=> 'color',
				),
				array(
					'id'				=> 'dropdown_menu_borders',
					'type'				=> 'color',
				),
				array(
					'id'					=> 'dropdown_menu_link_color',
					'type'					=> 'link_color',
				),
				array(
					'id'				=> 'dropdown_menu_link_hover_bg',
					'type'				=> 'color_gradient',

				),
				array(
					'id'				=> 'mega_menu_title',
					'type'				=> 'color',
				),
			),
		);

		/*** Styling Mobile Menu ***/
		$sections[] = array(
			'id'			=> 'styling_mobile_menu',
			'title'			=> __( 'Styling: Mobile Menu', 'wpex' ),
			'customizer'	=> false,
			'subsection'	=> true,
			'fields'		=> array(
				array(
					'id' => 'mobile_menu_icon_background',
					'type' => 'link_color',
				),
				array(
					'id' => 'mobile_menu_icon_border',
					'type'	=> 'link_color',
				),
				array(
					'id' => 'mobile_menu_icon_color',
					'type' => 'link_color',
				),
				array(
					'id' => 'mobile_menu_icon_size',
					'type' => 'text',
				),
				array(
					'id' => 'mobile_menu_sidr_background',
					'type' => 'color',
				),
				array(
					'id' => 'mobile_menu_sidr_borders',
					'type' => 'color',
				),
				array(
					'id' => 'mobile_menu_links',
					'type' => 'link_color',
				),
				array(
					'id' => 'mobile_menu_sidr_search_bg',
					'type' => 'color',
				),
				array(
					'id' => 'mobile_menu_sidr_search_color',
					'type' => 'color',
				),
			),
		);

		/*** Styling Page Header ***/
		$sections[] = array(
			'id'			=> 'styling_page_header',
			'title'			=> __( 'Styling: Page Header', 'wpex' ),
			'customizer'	=> false,
			'subsection'	=> true,
			'fields'		=> array(
				array(
					'id' => 'page_header_background',
					'type' => 'color',
				),
				array(
					'id' => 'page_header_title_color',
					'type' => 'color',
				),
				array(
					'id' => 'page_header_top_border',
					'type' => 'color',
				),
				array(
					'id' => 'page_header_bottom_border',
					'type' => 'color',
				),
				array(
					'id' => 'breadcrumbs_text_color',
					'type' => 'color',
				),
				array(
					'id' => 'breadcrumbs_seperator_color',
					'type' => 'color',
				),
				array(
					'id' => 'breadcrumbs_link_color',
					'type' => 'link_color',
				),
			)
		);

		/*** Styling Sidebar ***/
		$sections[] = array(
			'id'			=> 'styling_sidebar',
			'title'			=> __( 'Styling: Sidebar', 'wpex' ),
			'customizer'	=> false,
			'subsection'	=> true,
			'fields'		=> array(
				array(
					'id'				=> 'sidebar_background',
					'type'				=> 'color',
					'title'				=> __( 'Sidebar Background', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#sidebar',
					'target_style'		=> 'background-color',
				),
				array(
					'id'		=> 'sidebar_padding',
					'type'		=> 'spacing',
					'output'	=> false,
					'mode'		=> 'padding',
					'units'		=> 'px',
					'title'		=> __( 'Sidebar Padding', 'wpex' ),
					'subtitle'	=> __( 'Select your custom sidebar padding', 'wpex' ),
					'default'	=> array(
						'padding-top'		=> '',
						'padding-right'		=> '',
						'padding-bottom'	=> '',
						'padding-left'		=> ''
					),
				),
				array(
					'id'			=> 'sidebar_border',
					'type'			=> 'border',
					'title'			=> __( 'Sidebar border', 'wpex' ), 
					'subtitle'		=> __( 'Select your border style.', 'wpex' ),
					'default'		=> '',
					'all'			=> false,
					'output'		=> false,
					'default'		=> array(
						'border-color'	=> '',
						'border-style'	=> 'solid',
						'border-top'	=> '',
						'border-right'	=> '',
						'border-bottom'	=> '',
						'border-left'	=> ''
					),
				),
				array(
					'id'				=> 'sidebar_headings_color',
					'type'				=> 'color',
					'transparent'		=> false,
					'title'				=> __( 'Sidebar Headings Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'target_element'	=> '#sidebar .widget-title, #sidebar .widget-title a',
					'target_style'		=> 'color',
				),
				array(
					'id'				=> 'sidebar_text_color',
					'type'				=> 'color',
					'transparent'		=> false,
					'title'				=> __( 'Sidebar Text Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'target_element'	=> '#sidebar, #sidebar p, #sidebar .text_widget',
					'target_style'		=> 'color',
				),
				array(
					'id'			=> 'sidebar_link_color',
					'type'			=> 'link_color',
					'title'			=> __( 'Sidebar Link Color', 'wpex' ),
					'subtitle'		=> __( 'Select your custom hex color.', 'wpex' ),
					'default'		=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'target_element'		=> '#sidebar a',
					'target_element_hover'	=> '#sidebar a:hover',
					'target_element_active'	=> '#sidebar a:active',
					'target_style'			=> 'color',
				),
				array(
					'id'				=> 'sidebar_borders_color',
					'type'				=> 'color',
					'transparent'		=> false,
					'title'				=> __( 'Sidebar Inner Borders Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'target_element'	=> '.wpex-widget-recent-posts-li,.widget_categories li,.widget_recent_entries li,.widget_archive li,.widget_recent_comments li,.widget_product_categories li,.widget_layered_nav li,.widget-recent-posts-icons li,.wpex-widget-recent-posts-li:first-child, .widget_categories li:first-child, .widget_recent_entries li:first-child, .widget_archive li:first-child, .widget_recent_comments li:first-child, .widget_product_categories li:first-child, .widget_layered_nav li:first-child, .widget-recent-posts-icons li:first-child,.wpex-recent-comments-widget li,.wpex-recent-comments-widget li:first-child',
					'target_style'		=> 'border-color',
				),
			),
		);

		/*** Styling Footer ***/
		$sections[] = array(
			'id'			=> 'styling_footer',
			'title'			=> __( 'Styling: Footer', 'wpex' ),
			'customizer'	=> false,
			'subsection'	=> true,
			'fields'		=> array(
				array(
					'id'				=> 'footer_background',
					'type'				=> 'color',
					'title'				=> __( 'Footer Background', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer',
					'target_style'		=> 'background-color',
				),
				array(
					'id'			=> 'footer_border',
					'type'			=> 'border',
					'title'			=> __( 'Footer border', 'wpex' ), 
					'subtitle'		=> __( 'Select your border style.', 'wpex' ),
					'default'		=> '',
					'all'			=> false,
					'output'		=> false,
				),
				array(
					'id'				=> 'footer_color',
					'type'				=> 'color',
					'title'				=> __( 'Footer Color', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer, #footer p',
					'target_style'		=> 'color',
				),
				array(
					'id'				=> 'footer_headings_color',
					'type'				=> 'color',
					'title'				=> __( 'Footer Headings Color', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer .widget-title',
					'target_style'		=> 'color',
				),
				array(
					'id'				=> 'footer_borders',
					'type'				=> 'color',
					'title'				=> __( 'Footer Borders', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer li, #footer #wp-calendar thead th, #footer #wp-calendar tbody td',
					'target_style'		=> 'border-color',
				),
				array(
					'id'			=> 'footer_link_color',
					'type'			=> 'link_color',
					'title'			=> __( 'Footer Link Color', 'wpex' ),
					'subtitle'		=> __( 'Select your custom hex color.', 'wpex' ),
					'default'		=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'target_element'		=> '#footer a',
					'target_element_hover'	=> '#footer a:hover',
					'target_element_active'	=> '#footer a:active',
					'target_style'			=> 'color',
				),
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Bottom Footer', 'wpex' ),
				),
				array(
					'id'				=> 'bottom_footer_background',
					'type'				=> 'color',
					'title'				=> __( 'Bottom Footer Background', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer-bottom',
					'target_style'		=> 'background-color',
				),
				array(
					'id'			=> 'bottom_footer_border',
					'type'			=> 'border',
					'title'			=> __( 'Bottom Footer Border', 'wpex' ), 
					'subtitle'		=> __( 'Select your border style.', 'wpex' ),
					'default'		=> '',
					'all'			=> false,
					'output'		=> false,
				),
				array(
					'id'				=> 'bottom_footer_color',
					'type'				=> 'color',
					'title'				=> __( 'Bottom Footer Color', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer-bottom, #footer-bottom p',
					'target_style'		=> 'color',
				),
				array(
					'id'			=> 'bottom_footer_link_color',
					'type'			=> 'link_color',
					'title'			=> __( 'Bottom Footer Link Color', 'wpex' ),
					'subtitle'		=> __( 'Select your custom hex color.', 'wpex' ),
					'default'		=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'target_element'		=> '#footer-bottom a',
					'target_element_hover'	=> '#footer-bottom a:hover',
					'target_element_active'	=> '#footer-bottom a:active',
					'target_style'			=> 'color',
				),
			),
		);

		/*** Styling Buttons ***/
		$sections[] = array(
			'id'			=> 'styling_buttons_links',
			'title'			=> __( 'Styling: Buttons & Links', 'wpex' ),
			'customizer'	=> false,
			'subsection'	=> true,
			'fields'		=> array(
				array(
					'id'				=> 'link_color',
					'type'				=> 'color',
					'title'				=> __( 'Links Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> 'body a, h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover',
					'target_style'		=> 'color',
				),
				array(
					'id'				=> 'theme_button_bg',
					'type'				=> 'color_gradient',
					'title'				=> __( 'Theme Button Background', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '.edit-post-link a, #commentform #submit, .wpcf7 .wpcf7-submit, .theme-minimal-graphical #comments .comment-reply-link, .theme-button, .readmore-link, #current-shop-items .buttons a, .woocommerce .button, .page-numbers a:hover, .page-numbers.current, .page-numbers.current:hover, input[type="submit"], button',
					'default'			=> array(
						'from'	=> '',
						'to'	=> ''
					),
				),
				array(
					'id'				=> 'theme_button_color',
					'type'				=> 'color',
					'title'				=> __( 'Theme Button Color', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '.edit-post-link a, #commentform #submit, .wpcf7 .wpcf7-submit, .theme-minimal-graphical #comments .comment-reply-link, .theme-button, .readmore-link, #current-shop-items .buttons a, .woocommerce .button, input[type="submit"], button',
					'target_style'		=> 'color',
				),
				array(
					'id'				=> 'theme_button_hover_bg',
					'type'				=> 'color_gradient',
					'title'				=> __( 'Theme Button Hover Background', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '.edit-post-link a:hover, #commentform #submit:hover, .wpcf7 .wpcf7-submit:hover, .theme-minimal-graphical #comments .comment-reply-link:hover, .theme-button:hover, .readmore-link:hover, #current-shop-items .buttons a:hover, .woocommerce .button:hover, input[type="submit"]:hover, button:hover',
					'default'			=> array(
						'from'	=> '',
						'to'	=> ''
					),
				),
				array(
					'id'				=> 'theme_button_hover_color',
					'type'				=> 'color',
					'title'				=> __( 'Theme Button Hover Color', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'theme_customizer'	=> false,
					'target_element'	=> '.edit-post-link a:hover, #commentform #submit:hover, .wpcf7 .wpcf7-submit:hover, #comments .comment-reply-link:hover, .theme-button:hover, .readmore-link:hover, #current-shop-items .buttons a:hover, .woocommerce .button:hover, input[type="submit"]:hover, button:hover, .vcex-filter-links a:hover',
					'target_style'		=> 'color',
				),
			),
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Togglebar
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'toggle_bar',
			'title'			=> __( 'Toggle Bar', 'wpex' ),
			'fields'		=> array(
				array(
					'id'		=> 'toggle_bar',
					'type'		=> 'switch', 
					'title'		=> __( 'Toggle Bar', 'wpex' ),
					'default'	=> false,
				),
				array(
					'id'		=> 'toggle_bar_page',
					'type'		=> 'select',
					'title'		=> __( 'Toggle Bar Content', 'wpex' ),
				),
				array(
					'id'	=> 'toggle_bar_visibility',
					'type'	=> 'select',
					'title'	=> __( 'Toggle Bar Visibility', 'wpex' ), 
				),
				array(
					'id'		=> 'toggle_bar_animation',
					'type'		=> 'select',
					'title'		=> __( 'Toggle Bar Animation', 'wpex' ),
					'subtitle'	=> __( 'Select your animation style.', 'wpex' ),
					'default'	=> 'fade',
					'options'	=> array(
						'fade'			=> __( 'Fade', 'wpex' ),
						'fade-slide'	=> __( 'Fade & Slide Down', 'wpex' ),
					),
					'required'	=> array( 'toggle_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'toggle_bar_btn_bg',
					'type'				=> 'color',
					'title'				=> __( 'Toggle Bar Button Background', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '.toggle-bar-btn',
					'target_style'		=> array( 'border-top-color', 'border-right-color' ),
					'required'	=> array( 'toggle_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'toggle_bar_btn_color',
					'type'				=> 'color',
					'title'				=> __( 'Toggle Bar Button Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '.toggle-bar-btn span.fa',
					'target_style'		=> 'color',
					'required'	=> array( 'toggle_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'toggle_bar_btn_hover_bg',
					'type'				=> 'color',
					'title'				=> __( 'Toggle Bar Button Hover Background', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '.toggle-bar-btn:hover',
					'target_style'		=> array( 'border-top-color', 'border-right-color' ),
					'required'	=> array( 'toggle_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'toggle_bar_btn_hover_color',
					'type'				=> 'color',
					'title'				=> __( 'Toggle Bar Button Hover Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '.toggle-bar-btn:hover span.fa',
					'target_style'		=> 'color',
					'required'	=> array( 'toggle_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'toggle_bar_bg',
					'type'				=> 'color',
					'title'				=> __( 'Toggle Bar Background', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '#toggle-bar-btn',
					'target_style'		=> 'background-color',
					'required'	=> array( 'toggle_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'toggle_bar_color',
					'type'				=> 'color',
					'title'				=> __( 'Toggle Bar Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '#toggle-bar-wrap, #toggle-bar-wrap strong',
					'target_style'		=> 'color',
					'required'	=> array( 'toggle_bar', 'equals', '1' ),
				),
			)
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Top Bar
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'top_bar',
			'title'			=> __( 'Top Bar', 'wpex' ),
			'icon_class'	=> 'el-icon-arrow-up',
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'top_bar',
					'type'		=> 'switch', 
					'title'		=> __( 'Top Bar', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'top_bar_style',
					'type'		=> 'select',
					'title'		=> __( 'Top Bar Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred top bar style.', 'wpex' ),
					'options'	=> array(
						'one'	=> __( 'Left Content & Right Social', 'wpex' ),
						'two'	=> __( 'Left Social & Right Content', 'wpex' ),
						'three'	=> __( 'Centered Content & Social', 'wpex' ),
					),
					'default'	=> 'one',
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
				array(
					'id'		=> 'top_bar_visibility',
					'type'		=> 'select',
					'title'		=> __( 'Top Bar Visibility', 'wpex' ), 
					'subtitle'	=> __( 'Select your visibility.', 'wpex' ),
					'default'	=> 'always-visible',
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'top_bar_content',
					'type'				=> 'editor',
					'title'				=> __( 'Top Bar: Content', 'wpex' ), 
					'subtitle'			=> __( 'Enter your custom content for your top bar. Shortcodes are Allowed.', 'wpex' ),
					'default'			=> '[font_awesome icon="phone" margin_right="5px" color="#000"] 1-800-987-654 [font_awesome icon="envelope" margin_right="5px" margin_left="20px" color="#000"] admin@total.com [font_awesome icon="user" margin_right="5px" margin_left="20px" color="#000"] [wp_login_url text="User Login" logout_text="Logout"]',
					'required'			=> array( 'top_bar', 'equals', '1' ),
					'editor_options'	=> '',
					'args'				=> array(
						'teeny'	=> false
					),
				),

				/** Top Bar => Social **/
				array(
					'id'		=> 'top_bar_social',
					'type'		=> 'switch', 
					'title'		=> __( 'Top Bar Social', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'top_bar_social_alt',
					'type'				=> 'editor',
					'title'				=> __( 'Social Alternative', 'wpex' ), 
					'subtitle'			=> __( 'Add some alternative text, code, shortcodes where your Social icons would normally go.', 'wpex' ),
					'default'			=> '',
					'required'			=> array( 'top_bar', 'equals', '1' ),
					'editor_options'	=> '',
					'required'			=> array(
						array( 'top_bar_social', '!=','1' ),
						array( 'top_bar', 'equals', '1' )
					),
					'args'				=> array( 'teeny' => false )
				),
				array(
					'id'		=> 'top_bar_social_target',
					'type'		=> 'select',
					'title'		=> __( 'Top Bar Social Link Target', 'wpex' ),
					'subtitle'	=> __( 'Select to open the social links in a new or the same window.', 'wpex' ),
					'options'	=> array(
						'blank'	=> __( 'New Window', 'wpex' ),
						'self'	=> __( 'Same Window', 'wpex' )
					),
					'default'	=> 'blank',
					'required'	=> array(
						array( 'top_bar_social', 'equals', '1' ),
						array( 'top_bar', 'equals', '1' ),
					),
				),
				array(
					'id'		=> 'top_bar_social_style',
					'type'		=> 'select',
					'title'		=> __( 'Top Bar Social Style', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred social link style.', 'wpex' ),
					'options'	=> array(
						'font_icons'	=> __( 'Font Icons', 'wpex' ),
						'colored-icons'	=> __( 'Colored Image Icons', 'wpex' )
					),
					'default'	=> 'font_icons',
					'required'	=> array(
						array( 'top_bar_social', 'equals', '1' ),
						array( 'top_bar', 'equals', '1' ),
					),
				),
				array(
					'id'		=> 'top_bar_social_options',
					'type'		=> 'sortable',
					'title'		=> __( 'Top Bar Social Options', 'wpex' ),
				),

				/** Top Bar => Styling **/
				array(
					'id'				=> 'top_bar_bg',
					'type'				=> 'color',
					'title'				=> __( 'Top Bar Background', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#top-bar-wrap',
					'target_style'		=> 'background-color',
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'top_bar_border',
					'type'				=> 'color',
					'title'				=> __( 'Top Bar Bottom Border', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#top-bar-wrap',
					'target_style'		=> 'border-color',
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'top_bar_text',
					'type'				=> 'color',
					'title'				=> __( 'Top Bar Text Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#top-bar-wrap, #top-bar-content strong',
					'target_style'		=> 'color',
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
				array(
					'id'					=> 'top_bar_link_color',
					'type'					=> 'link_color',
					'title'					=> __( 'Top bar Link Color', 'wpex' ),
					'subtitle'				=> __( 'Select your custom hex color.', 'wpex' ),
					'default'				=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'target_element'		=> '#top-bar-content a, #top-bar-social-alt a',
					'target_element_hover'	=> '#top-bar-content a:hover, #top-bar-social-alt a:hover',
					'target_element_active'	=> '#top-bar-content a:active, #top-bar-social-alt a:active',
					'target_style'			=> 'color',
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'top_bar_social_color',
					'type'				=> 'color',
					'title'				=> __( 'Top Bar Social Links Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#top-bar-social a',
					'target_style'		=> 'color',
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
				array(
					'id'				=> 'top_bar_social_hover_color',
					'type'				=> 'color',
					'title'				=> __( 'Top Bar Social Links Hover Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#top-bar-social a:hover',
					'target_style'		=> 'color',
					'required'	=> array( 'top_bar', 'equals', '1' ),
				),
			),
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Header
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'header',
			'title'			=> __( 'Header', 'wpex' ),
			'icon'			=> 'el-icon-screen',
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'header_style',
					'type'		=> 'select',
					'title'		=> __( 'Header Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your default header style.', 'wpex' ),
					'options'	=> array(
						'one'	=> __( 'One','wpex' ),
						'two'	=> __( 'Two','wpex' ),
						'three'	=> __( 'Three','wpex' )
					),
					'default'	=> 'one',
				),
				array(
					'id'		=> 'fixed_header',
					'type'		=> 'switch',
					'title'		=> __( 'Fixed Header on Scroll', 'wpex' ),
					'subtitle'	=> __( 'Toggle the fixed header when the user scrolls down the site on or off. Please note that for certain header (two and three) styles only the navigation will become fixed.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'shink_fixed_header',
					'type'		=> 'switch',
					'title'		=> __( 'Shrink Fixed Header', 'wpex' ),
					'subtitle'	=> __( 'Shrink your fixed header on scroll', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'fixed_header_opacity',
					'type'		=> 'text',
					'title'		=> __( 'Fixed Header Opacity', 'wpex' ),
					'subtitle'	=> __( 'Enter an opacity for the fixed header. Default is 0.95.', 'wpex' ),
					'default'	=> '0.95',
				),
				array(
					'id'		=> 'main_search',
					'type'		=> 'switch', 
					'title'		=> __( 'Header Search', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'main_search_toggle_style',
					'type'		=> 'select',
					'title'		=> __( 'Header Search Toggle Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your default header search style.', 'wpex' ),
					'options'	=> array(
						'drop_down'			=> __( 'Drop Down','wpex' ),
						'overlay'			=> __( 'Site Overlay','wpex' ),
						'header_replace'	=> __( 'Header Replace','wpex' )
					),
					'default'	=> 'drop_down',
				),
				array(
					'id'					=> 'search_dropdown_top_border',
					'type'					=> 'color',
					'title'					=> __( 'Header Search Toggle Top Border Color', 'wpex' ), 
					'subtitle'				=> __( 'Select your custom hex color.', 'wpex' ),
					'default'				=> '',
					'transparent'			=> false,
					'target_element'		=> '#searchform-dropdown',
					'target_style'			=> 'border-top-color',
					'theme_customizer'		=> false,
				),
				array(
					'id'		=> 'main_search_overlay_top_margin',
					'type'		=> 'text',
					'title'		=> __( 'Header Search Overlay Top Margin', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom top margin for the search overlay. The default is 120px.', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'header_height',
					'type'		=> 'text',
					'title'		=> __( 'Custom Header Height', 'wpex' ),
					'subtitle'	=> __( 'Use this setting to define a fixed header height (Header Style One Only. Use this option ONLY if your want the navigation drop-downs to fall right under the header. Remove the default height (leave this field empty) if you want the header to auto expand depending on your logo height.', 'wpex' ),
					'default'	=> '40px',
				),
				array(
					'id'		=> 'header_top_padding',
					'type'		=> 'text',
					'title'		=> __( 'Header Top Padding', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom header top padding in pixels. Ignored if the custom header height field is NOT empty.', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'header_bottom_padding',
					'type'		=> 'text',
					'title'		=> __( 'Header Bottom Padding', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom header top padding in pixels. Ignored if the custom header height field is NOT empty', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'logo_max_width',
					'type'		=> 'text',
					'title'		=> __( 'Logo Max Width', 'wpex' ),
					'subtitle'	=> __( 'Define a maximum width for your logo image if it is too big. Can be in pixels or percentage, but percentage is better for responsiveness.', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'logo_top_margin',
					'type'		=> 'text',
					'title'		=> __( 'Logo Top Margin', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom logo top margin.', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'logo_bottom_margin',
					'type'		=> 'text',
					'title'		=> __( 'Logo Bottom Margin', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom logo top margin.', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'				=> 'header_aside',
					'type'				=> 'editor',
					'title'				=> __( 'Header Aside Content', 'wpex' ),
					'subtitle'			=> __( 'Enter your custom header aside content for header style 2.', 'wpex' ),
					'default'			=> '',
					'editor_options'	=> '',
					'args'				=> array('teeny' => false)
				),

				/** Header => Menu **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Header: Menu', 'wpex' ),
				),
				array(
					'id'		=> 'menu_arrow_down',
					'type'		=> 'switch', 
					'title'		=> __( 'Top Level Dropdown Icon', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'menu_arrow_side',
					'type'		=> 'switch', 
					'title'		=> __( 'Second+ Level Dropdown Icon', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'menu_dropdown_top_border',
					'type'		=> 'switch', 
					'title'		=> __( 'Dropdown Top Border', 'wpex' ),
					'subtitle'	=> __( 'Set this option to "on" if you want to have a thick colorfull border at the top of your drop-down menu.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'				=> 'menu_dropdown_top_border_color',
					'type'				=> 'color',
					'title'				=> __( 'Dropdown Top Border Color', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> 'body #site-navigation-wrap.nav-dropdown-top-border .dropdown-menu > li > ul, #searchform-dropdown, #current-shop-items-dropdown',
					'target_style'		=> 'border-top-color',
					'required'			=> array( 'menu_dropdown_top_border', 'equals', '1' ),
				),

				/** Header => Other **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Header: Other', 'wpex' ),
				),
				array(
					'id'		=> 'page_header_style',
					'type'		=> 'select',
					'title'		=> __( 'Page Header Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your default page header style. This can be altered alter on a per-post basis.', 'wpex' ),
					'options'	=> array(
						'default'			=> __( 'Default','wpex' ),
						'centered'			=> __( 'Centered', 'wpex' ),
						'centered-minimal'	=> __( 'Centered Minimal', 'wpex' ),
					),
					'default'	=> 'default',
				),

			),
		);


		/*-----------------------------------------------------------------------------------*/
		/*	- Portfolio
		/*-----------------------------------------------------------------------------------*/
		$sections['portfolio'] = array(
			'id'			=> 'portfolio',
			'icon'			=> 'el-icon-briefcase',
			'title'			=> __( 'Portfolio', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'portfolio_enable',
					'type'		=> 'switch', 
					'title'		=> __( 'Portfolio Post Type', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_page',
					'type'		=> 'select',
					'data'		=> 'pages',
					'title'		=> __( 'Portfolio Page', 'wpex' ),
					'subtitle'	=> __( 'Select your main portfolio page. This is used for your breadcrumbs.', 'wpex' ),
					'default'	=> '',
				),

				/** Portfolio => Archives **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Portfolio: Archives', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_archive_layout',
					'type'		=> 'select',
					'title'		=> __( 'Portfolio Archives Layout', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred layout for your single posts. This setting can be overwritten on a per post basis via the meta options.', 'wpex' ),
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'full-width',
				),
				array(
					'id'		=> 'portfolio_archive_grid_style',
					'type'		=> 'select',
					'title'		=> __( 'Portfolio Archives Grid Style', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred grid style.', 'wpex' ),
					'options'	=> array(
						'fit-rows'		=> __( 'Fit Rows','wpex' ),
						'masonry'		=> __( 'Masonry','wpex' ),
						'no-margins'	=> __( 'No Margins','wpex' )
					),
					'default'	=> 'fit-rows',
				),
				array(
					'id'		=> 'portfolio_archive_grid_equal_heights',
					'type'		=> 'switch',
					'title'		=> __( 'Portfolio Archives Grid Equal Heights', 'wpex' ), 
					'subtitle'	=> __( 'Adds equal heights for the entry content so "boxes" on the same row are the same height. You must have equal sized images for this to work efficiently.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'portfolio_archive_grid_style', 'equals', 'fit-rows' ),
				),
				array(
					'id'		=> 'portfolio_entry_columns',
					'type'		=> 'select',
					'title'		=> __( 'Portfolio Archive Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select your default column structure for your category and tag archives.', 'wpex' ),
					'options'	=> array(
						'1'	=> '1',
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '4',
				),
				array(
					'id'		=> 'portfolio_archive_posts_per_page',
					'type'		=> 'text', 
					'title'		=> __( 'Portfolio Archives Posts Per Page', 'wpex' ),
					'subtitle'	=> __( 'How many posts do you wish to display on your archives before pagination?', 'wpex' ),
					'default'	=> '12',
				),
				array(
					'id'		=> 'portfolio_entry_overlay_style',
					'type'		=> 'select', 
					'title'		=> __( 'Portfolio Entry Image Overlay', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred overlay style.', 'wpex' ),
					'default'	=> 'none',
				),
				array(
					'id'		=> 'portfolio_entry_details',
					'type'		=> 'switch', 
					'title'		=> __( 'Portfolio Entry Details', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_entry_excerpt_length',
					'type'		=> 'text', 
					'title'		=> __( 'Portfolio Entry Excerpt Length', 'wpex' ),
					'subtitle'	=> __( 'How many words do you want to show for your entry excerpts?', 'wpex' ),
					'default'	=> '20',
					'required'	=> array( 'portfolio_entry_details', 'equals', '1' ),
				),

				/** Portfolio => Single **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Portfolio: Single Post', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_single_layout',
					'type'		=> 'select',
					'title'		=> __( 'Portfolio Single Post Layout', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred layout for your single posts. This setting can be overwritten on a per post basis via the meta options.', 'wpex' ),
					'options'	=> array(
						'right-sidebar'		=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'		=> __( 'Left Sidebar','wpex' ),
						'full-width'		=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'full-width',
				),
				array(
					'id'		=> 'portfolio_single_media',
					'type'		=> 'switch', 
					'title'		=> __( 'Auto Post Media', 'wpex' ),
					'subtitle'	=> __( 'Set this option to "on" if you want to automatically display your featured image or featured video at the top of posts.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_comments',
					'type'		=> 'switch', 
					'title'		=> __( 'Portfolio Comments', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_next_prev',
					'type'		=> 'switch', 
					'title'		=> __( 'Portfolio Next/Prev Links', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_related',
					'type'		=> 'switch', 
					'title'		=> __( 'Portfolio Related', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_related_columns',
					'type'		=> 'select',
					'title'		=> __( 'Portfolio Related Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select your default column structure.', 'wpex' ),
					'options'	=> array(
						'1'	=> '1',
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '4',
					'required'	=> array( 'portfolio_related', 'equals', '1' ),
				),
				array(
					'id'		=> 'portfolio_related_count',
					'type'		=> 'text',
					'title'		=> __( 'Portfolio Related Count', 'wpex' ),
					'subtitle'	=> __( 'Enter the number of related items to display.', 'wpex' ),
					'default'	=> '4',
					'required'	=> array( 'portfolio_related', 'equals', '1' ),
				),
				array(
					'id'		=> 'portfolio_related_title',
					'type'		=> 'text',
					'title'		=> __( 'Portfolio Related Title', 'wpex' ),
					'subtitle'	=> __( 'Enter a custom string for your related portfolio items title.', 'wpex' ),
					'default'	=> '',
					'required'	=> array( 'portfolio_related', 'equals', '1' ),
				),
				array(
					'id'		=> 'portfolio_related_excerpts',
					'type'		=> 'switch',
					'title'		=> __( 'Portfolio Related Entry Content', 'wpex' ),
					'subtitle'	=> __( 'Display The Title & Excerpt for related items?', 'wpex' ),
					'default'	=> '1',
					'required'	=> array( 'portfolio_related', 'equals', '1' ),
				),

				/** Portfolio => Branding **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Portfolio: Branding', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_admin_icon',
					'type'		=> 'select',
					'title'		=> __( 'Portfolio Admin Icon', 'wpex' ),
					'subtitle'	=> __( 'Select your custom Dashicon for this post type.', 'wpex' ). '<br /><br /><a href="http://melchoyce.github.io/dashicons/" target="_blank">'. __( 'Learn More','wpex' ) .' &rarr;</a>',
					'default'	=> 'portfolio',
				),
				array(
					'id'		=> 'portfolio_labels',
					'type'		=> 'text',
					'title'		=> __( 'Portfolio Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to rename your portfolio custom post type.', 'wpex' ),
					'default'	=> 'Portfolio',
				),
				array(
					'id'		=> 'portfolio_slug',
					'type'		=> 'text',
					'title'		=> __( 'Portfolio Slug', 'wpex' ),
					'subtitle'	=> __( 'Changes the default slug for this post type. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'portfolio-item',
				),
				array(
					'id'		=> 'portfolio_cat_labels',
					'type'		=> 'text',
					'title'		=> __( 'Portfolio Category Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default labels for this taxonomy.', 'wpex' ),
					'default'	=> __( 'Portfolio Categories', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_cat_slug',
					'type'		=> 'text',
					'title'		=> __( 'Portfolio Category Slug', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default slug for this taxonomy. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'portfolio-category',
				),
				array(
					'id'		=> 'portfolio_tag_labels',
					'type'		=> 'text',
					'title'		=> __( 'Portfolio Tag Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default labels for this taxonomy.', 'wpex' ),
					'default'	=> __( 'Portfolio Tags', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_tag_slug',
					'type'		=> 'text',
					'title'		=> __( 'Portfolio Tag Slug', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default slug for this taxonomy. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'portfolio-tag',
				),

				/** Portfolio => Other **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Portfolio: Other', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_custom_sidebar',
					'type'		=> 'switch', 
					'title'		=> __( 'Custom Portfolio Sidebar', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'breadcrumbs_portfolio_cat',
					'type'		=> 'switch', 
					'title'		=> __( 'Portfolio Category In Breadcrumbs', 'wpex' ),
					'subtitle'	=>__( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'portfolio_search',
					'type'		=> 'switch', 
					'title'		=> __( 'Portfolio in Search?', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

			),
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Staff
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'staff',
			'icon'			=> 'el-icon-user',
			'title'			=> __( 'Staff', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'staff_enable',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff Post Type', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'staff_page',
					'type'		=> 'select',
					'data'		=> 'pages',
					'title'		=> __( 'Staff Page', 'wpex' ),
					'subtitle'	=> __( 'Select your main staff page. This is used for your breadcrumbs.', 'wpex' ),
					'default'	=> '',
				),

				/** Staff => Archives **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Staff: Archives', 'wpex' ),
				),
				array(
					'id'		=> 'staff_archive_layout',
					'type'		=> 'select',
					'title'		=> __( 'Staff Archives Layout', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred layout for your posts. This setting can be overwritten on a per post basis via the meta options.', 'wpex' ),
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'full-width',
				),
				array(
					'id'		=> 'staff_archive_grid_style',
					'type'		=> 'select',
					'title'		=> __( 'Staff Archives Grid Style', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred grid style.', 'wpex' ),
					'options'	=> array(
						'fit-rows'		=> __( 'Fit Rows','wpex' ),
						'masonry'		=> __( 'Masonry','wpex' ),
						'no-margins'	=> __( 'No Margins','wpex' )
					),
					'default'	=> 'fit-rows',
				),
				array(
					'id'		=> 'staff_archive_grid_equal_heights',
					'type'		=> 'switch',
					'title'		=> __( 'Staff Archives Grid Equal Heights', 'wpex' ), 
					'subtitle'	=> __( 'Adds equal heights for the entry content so "boxes" on the same row are the same height. You must have equal sized images for this to work efficiently.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'staff_archive_grid_style', 'equals', 'fit-rows' ),
				),
				array(
					'id'		=> 'staff_entry_columns',
					'type'		=> 'select',
					'title'		=> __( 'Staff Archive Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select your default column structure for your category and tag archives.', 'wpex' ),
					'options'	=> array(
						'1'	=> '1',
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '4',
				),
				array(
					'id'		=> 'staff_archive_posts_per_page',
					'type'		=> 'text', 
					'title'		=> __( 'Staff Archives Posts Per Page', 'wpex' ),
					'subtitle'	=> __( 'How many posts do you wish to display on your archives before pagination?', 'wpex' ),
					'default'	=> '12',
				),
				array(
					'id'		=> 'staff_entry_overlay_style',
					'type'		=> 'select', 
					'title'		=> __( 'Staff Entry Image Overlay', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred overlay style.', 'wpex' ),
					'default'	=> 'none',
				),
				array(
					'id'		=> 'staff_entry_details',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff Entry Details', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'staff_entry_excerpt_length',
					'type'		=> 'text', 
					'title'		=> __( 'Staff Entry Excerpt Length', 'wpex' ),
					'subtitle'	=> __( 'How many words do you want to show for your entry excerpts?', 'wpex' ),
					'default'	=> '20',
					'required'	=> array( 'staff_entry_details', 'equals', '1' ),
				),
				array(
					'id'		=> 'staff_entry_social',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff Entry Social Links', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				/** Staff => Single Post **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Staff: Single Post', 'wpex' ),
				),
				array(
					'id'		=> 'staff_single_layout',
					'type'		=> 'select',
					'title'		=> __( 'Staff Single Post Layout', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred layout for your single posts. This setting can be overwritten on a per post basis via the meta options.', 'wpex' ),
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'right-sidebar',
				),
				array(
					'id'		=> 'staff_single_media',
					'type'		=> 'switch', 
					'title'		=> __( 'Auto Post Media', 'wpex' ),
					'subtitle'	=> __( 'Set this option to "on" if you want to automatically display your featured image or featured video at the top of posts.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'staff_comments',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff Comments', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'staff_next_prev',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff Next/Prev Links', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'staff_related',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff Related', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'staff_related_columns',
					'type'		=> 'select',
					'title'		=> __( 'Staff Related Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select your default column structure.', 'wpex' ),
					'options'	=> array(
						'1'	=> '1',
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '3',
					'required'	=> array( 'staff_related', 'equals', '1' ),
				),
				array(
					'id'		=> 'staff_related_count',
					'type'		=> 'text',
					'title'		=> __( 'Staff Related Count', 'wpex' ),
					'subtitle'	=> __( 'Enter the number of related items to display', 'wpex' ),
					'default'	=> '3',
					'required'	=> array( 'staff_related', 'equals', '1' ),
				),
				array(
					'id'		=> 'staff_related_title',
					'type'		=> 'text',
					'title'		=> __( 'Staff Related Title', 'wpex' ),
					'subtitle'	=> __( 'Enter a custom string for your related staff items title.', 'wpex' ),
					'default'	=> '',
					'required'	=> array( 'staff_related', 'equals', '1' ),
				),
				array(
					'id'		=> 'staff_related_excerpts',
					'type'		=> 'switch',
					'title'		=> __( 'Staff Related Entry Details', 'wpex' ),
					'subtitle'	=> __( 'Display The Title & Excerpt for related items?', 'wpex' ),
					'default'	=> '1',
					'required'	=> array( 'staff_related', 'equals', '1' ),
				),

				/** Staff => Branding **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Staff: Branding', 'wpex' ),
				),
				array(
					'id'		=> 'staff_admin_icon',
					'type'		=> 'select', 
					'title'		=> __( 'Staff Admin Icon', 'wpex' ),
					'subtitle'	=> __( 'Select your custom Dashicon for this post type.', 'wpex' ). '<br /><br /><a href="http://melchoyce.github.io/dashicons/" target="_blank">'. __( 'Learn More','wpex' ) .' &rarr;</a>',
					'default'	=> 'groups',
				),
				array(
					'id'		=> 'staff_labels',
					'type'		=> 'text',
					'title'		=> __( 'Staff Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to rename your staff custom post type.', 'wpex' ),
					'default'	=> 'Staff',
				),
				array(
					'id'		=> 'staff_slug',
					'type'		=> 'text',
					'title'		=> __( 'Staff Slug', 'wpex' ),
					'subtitle'	=> __( 'Changes the default slug for this post type. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'staff-member',
				),
				array(
					'id'		=> 'staff_cat_labels',
					'type'		=> 'text',
					'title'		=> __( 'Staff Category Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default labels for this taxonomy.', 'wpex' ),
					'default'	=> __( 'Staff Categories', 'wpex' ),
				),
				array(
					'id'		=> 'staff_cat_slug',
					'type'		=> 'text',
					'title'		=> __( 'Staff Category Slug', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default slug for this taxonomy. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'staff-category',
				),
				array(
					'id'		=> 'staff_tag_labels',
					'type'		=> 'text',
					'title'		=> __( 'Staff Tag Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default labels for this taxonomy.', 'wpex' ),
					'default'	=> __( 'Staff Tags', 'wpex' ),
				),
				array(
					'id'		=> 'staff_tag_slug',
					'type'		=> 'text',
					'title'		=> __( 'Staff Tag Slug', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default slug for this taxonomy. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'staff-tag',
				),

				/** Staff => Other **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Staff: Other', 'wpex' ),
				),

				array(
					'id'		=> 'staff_custom_sidebar',
					'type'		=> 'switch', 
					'title'		=> __( 'Custom Staff Sidebar', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				array(
					'id'		=> 'breadcrumbs_staff_cat',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff Category In Breadcrumbs', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'staff_enable', 'equals', '1' ),
				),

				array(
					'id'		=> 'staff_search',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff in Search?', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

			)
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Testimonials
		/*-----------------------------------------------------------------------------------*/
		$sections['testimonials'] = array(
			'id'			=> 'testimonials',
			'icon'			=> 'el-icon-quotes',
			'title'			=> __( 'Testimonials', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(

				array(
					'id'		=> 'testimonials_enable',
					'type'		=> 'switch', 
					'title'		=> __( 'Testimonials Post Type', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				array(
					'id'		=> 'testimonials_page',
					'type'		=> 'select',
					'data'		=> 'pages',
					'title'		=> __( 'Testimonials Page', 'wpex' ),
					'subtitle'	=> __( 'Select your main testimonials page. This is used for your breadcrumbs.', 'wpex' ),
					'default'	=> '',
				),

				/** Testimonials => Archives **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Testimonials: Archives', 'wpex' ),
				),


				array(
					'id'		=> 'testimonials_archive_layout',
					'type'		=> 'select',
					'title'		=> __( 'Testimonials Archives Layout', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred layout for your single posts. This setting can be overwritten on a per post basis via the meta options.', 'wpex' ),
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'full-width',
				),

				array(
					'id'		=> 'testimonials_entry_columns',
					'type'		=> 'select',
					'title'		=> __( 'Testimonials Archive Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select your default column structure for your category and tag archives.', 'wpex' ),
					'options'	=> array(
						'1'	=> '1',
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '3',
				),

				array(
					'id'		=> 'testimonials_archive_posts_per_page',
					'type'		=> 'text', 
					'title'		=> __( 'Testimonials Archives Posts Per Page', 'wpex' ),
					'subtitle'	=> __( 'How many posts do you wish to display on your archives before pagination?', 'wpex' ),
					'default'	=> '12',
				),

				/** Testimonials => Single Post **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Testimonials: Single Post', 'wpex' ),
				),

				array(
					'id'		=> 'testimonial_post_style',
					'type'		=> 'select', 
					'title'		=> __( 'Testimonial Post Style', 'wpex' ),
					'subtitle'	=> __( 'Select your style', 'wpex' ),
					'default'	=> 'blockquote',
					'options'	=> array (
						'blockquote'	=> __( 'Blockquote', 'wpex' ),
						'standard'		=> __( 'Standard', 'wpex' ),
					)
				),

				array(
					'id'		=> 'testimonials_single_layout',
					'type'		=> 'select',
					'title'		=> __( 'Testimonials Single Post Layout', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred layout for your single posts. This setting can be overwritten on a per post basis via the meta options.', 'wpex' ),
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'full-width',
				),

				array(
					'id'		=> 'testimonials_comments',
					'type'		=> 'switch', 
					'title'		=> __( 'Testimonials Comments', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),


				/** Testimonials => Branding **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Testimonials: Branding', 'wpex' ),
				),

				array(
					'id'		=> 'testimonials_admin_icon',
					'type'		=> 'select', 
					'title'		=> __( 'Testimonials Admin Icon', 'wpex' ),
					'subtitle'	=> __( 'Select your custom dashicon for this post type.', 'wpex' ). '<br /><br /><a href="http://melchoyce.github.io/dashicons/" target="_blank">'. __( 'Learn More','wpex' ) .' &rarr;</a>',
					'default'	=> 'format-status',
				),

				array(
					'id'		=> 'testimonials_labels',
					'type'		=> 'text',
					'title'		=> __( 'Testimonials Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to rename your testimonials custom post type.', 'wpex' ),
					'default'	=> 'Testimonials',
				),

				array(
					'id'		=> 'testimonials_slug',
					'type'		=> 'text',
					'title'		=> __( 'Testimonials Slug', 'wpex' ),
					'subtitle'	=> __( 'Changes the default slug for this post type. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'testimonial',
				),

				array(
					'id'		=> 'testimonials_cat_labels',
					'type'		=> 'text',
					'title'		=> __( 'Testimonials Category Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default labels for this taxonomy.', 'wpex' ),
					'default'	=> __( 'Testimonials Categories', 'wpex' ),
				),

				array(
					'id'		=> 'testimonials_cat_slug',
					'type'		=> 'text',
					'title'		=> __( 'Testimonials Category Slug', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default slug for this taxonomy. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'testimonials-category',
				),


				/** Testimonials => Other **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Testimonials: Other', 'wpex' ),
				),

				array(
					'id'		=> 'testimonials_search',
					'type'		=> 'switch', 
					'title'		=> __( 'Testimonials in Search?', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				array(
					'id'		=> 'testimonial_custom_sidebar',
					'type'		=> 'switch', 
					'title'		=> __( 'Custom Testimonials Sidebar', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				array(
					'id'		=> 'breadcrumbs_testimonials_cat',
					'type'		=> 'switch', 
					'title'		=> __( 'Testimonials Category In Breadcrumbs', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

			),

		);

		
		/*-----------------------------------------------------------------------------------*/
		/*	- WooCommerce
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'woocommerce',
			'icon'			=> 'el-icon-shopping-cart',
			'title'			=> __( 'WooCommerce', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'woo_menu_icon',
					'type'		=> 'switch', 
					'title'		=> __( 'Menu Cart', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				array(
					'id'		=> 'woo_menu_icon_amount',
					'type'		=> 'switch', 
					'title'		=> __( 'Menu Cart: Amount', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array('woo_menu_icon','equals','1'),
				),

				array(
					'id'		=> 'woo_menu_icon_style',
					'type'		=> 'select',
					'title'		=> __( 'Menu Cart: Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your default WooCommerce menu icon style.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'overlay'		=> __( 'Open Cart Overlay','wpex' ),
						'drop-down'		=> __( 'Drop-Down','wpex' ),
						'store'			=> __( 'Go To Store','wpex' ),
						'custom-link'	=> __( 'Custom Link','wpex' ),
					),
					'default'	=> 'overlay',
					'required'	=> array( 'woo_menu_icon', 'equals', '1' ),
				),

				array(
					'id'		=> 'woo_menu_icon_custom_link',
					'type'		=> 'text',
					'title'		=> __( 'Menu Cart: Custom Link', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom link for the menu cart icon.', 'wpex' ),
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'woo_menu_icon_style', 'equals', 'custom-link' ),
				),

				array(
					'id'		=> 'woo_shop_overlay_top_margin',
					'type'		=> 'text',
					'title'		=> __( 'Cart Overlay Top Margin', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom top margin for the WooCommerce cart overlay. The default is 120px.', 'wpex' ),
					'default'	=> '',
					'required'	=> array('woo_menu_icon_style','equals','overlay'),
				),

				array(
					'id'		=> 'woo_custom_sidebar',
					'type'		=> 'switch', 
					'title'		=> __( 'Custom WooCommerce Sidebar', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				/** WooCommerce => Archives **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'WooCommerce: Archives', 'wpex' ),
				),
				array(
					'id'		=> 'woo_shop_slider',
					'type'		=> 'text',
					'title'		=> __( 'Shop Slider', 'wpex' ),
					'desc'		=> '',
					'subtitle'	=> __( 'Insert your slider shortcode for your products archive.', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'woo_shop_posts_per_page',
					'type'		=> 'text',
					'title'		=> __( 'Shop Posts Per Page', 'wpex' ),
					'desc'		=> '',
					'subtitle'	=> __( 'How many items to display per page on your main shop archive and product category archives.', 'wpex' ),
					'default'	=> '12',
				),
				array(
					'id'		=> 'woo_shop_layout',
					'type'		=> 'select',
					'title'		=> __( 'Shop Layout', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred layout for your WooCommmerce Shop.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'full-width',
				),
				array(
					'id'		=> 'woocommerce_shop_columns',
					'type'		=> 'select',
					'title'		=> __( 'Shop Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select how many columns you want.', 'wpex' ),
					'options'	=> array(
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '4',
				),
				array(
					'id'		=> 'woo_category_description_position',
					'type'		=> 'select',
					'title'		=> __( 'Category Description Position', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred location.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'under_title'	=> __( 'Under Title', 'wpex' ),
						'above_loop'	=> __( 'Above Loop', 'wpex' ),
					),
					'default'	=> 'under_title',
				),
				array(
					'id'		=> 'woo_shop_title',
					'type'		=> 'switch', 
					'title'		=> __( 'Shop Title', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'woo_shop_sort',
					'type'		=> 'switch', 
					'title'		=> __( 'Shop Sort', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'woo_shop_result_count',
					'type'		=> 'switch', 
					'title'		=> __( 'Shop Result Count', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'woo_entry_style',
					'type'		=> 'select',
					'title'		=> __( 'Product Entry Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred style for your WooCommmerce product entries.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'two'		=> __( 'Default','wpex' ),
						'one'		=> __( 'Alternative','wpex' ),
					),
					'default'	=> 'two',
				),
				array(
					'id'		=> 'woo_entry_rating',
					'type'		=> 'switch', 
					'title'		=> __( 'Product Entry Ratings', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'woo_entry_style', 'equals', 'two' ),
				),
				array(
					'id'		=> 'woo_product_entry_style',
					'type'		=> 'select',
					'title'		=> __( 'Product Entry Media', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred style for your WooCommmerce product entry media.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'featured-image'	=> __( 'Featured Image','wpex' ),
						'image-swap'		=> __( 'Image Swap','wpex' ),
						'gallery-slider'	=> __( 'Gallery Slider','wpex' ),
					),
					'default'	=> 'image-swap',
				),

				/** WooCommerce => Single Product **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'WooCommerce: Single Product', 'wpex' ),
				),

				array(
					'id'		=> 'woo_shop_single_title',
					'type'		=> 'text',
					'title'		=> __( 'Single Product Shop Title', 'wpex' ),
					'desc'		=> '',
					'subtitle'	=> __( 'Enter your custom shop title for single products.', 'wpex' ),
					'default'	=> __( 'Products', 'wpex' ),
				),

				array(
					'id'		=> 'woo_product_layout',
					'type'		=> 'select',
					'title'		=> __( 'Product Post Layout', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred layout for your WooCommmerce products.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'left-sidebar'
				),

				array(
					'id'		=> 'woocommerce_upsells_count',
					'type'		=> 'text',
					'title'		=> __( 'Up-Sells Count', 'wpex' ), 
					'subtitle'	=> __( 'Enter the ammount of up-sell items to display on product pages.', 'wpex' ),
					'default'	=> '3',
				),

				array(
					'id'		=> 'woocommerce_upsells_columns',
					'type'		=> 'select',
					'title'		=> __( 'Up-Sells Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select how many columns you want.', 'wpex' ),
					'options'	=> array(
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '3',
				),

				array(
					'id'		=> 'woocommerce_related_count',
					'type'		=> 'text',
					'title'		=> __( 'Related Items Count', 'wpex' ), 
					'subtitle'	=> __( 'Enter the ammount of related items to display on product pages. Enter "0" to disable.', 'wpex' ),
					'default'	=> '3',
				),

				array(
					'id'		=> 'woocommerce_related_columns',
					'type'		=> 'select',
					'title'		=> __( 'Related Products Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select how many columns you want.', 'wpex' ),
					'options'	=> array(
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '3',
				),

				array(
					'id'		=> 'woo_product_meta',
					'type'		=> 'switch', 
					'title'		=> __( 'Product Meta', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				array(
					'id'		=> 'woo_product_tabs_headings',
					'type'		=> 'switch', 
					'title'		=> __( 'Product Tabs: Headings', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				array(
					'id'		=> 'woo_next_prev',
					'type'		=> 'switch', 
					'title'		=> __( 'Products Next/Prev Links', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),

				/** WooCommerce => Cart **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'WooCommerce: Cart', 'wpex' ),
				),
				array(
					'id'		=> 'woocommerce_cross_sells_count',
					'type'		=> 'text',
					'title'		=> __( 'Cross-Sells Count', 'wpex' ), 
					'subtitle'	=> __( 'Enter the ammount of up-sell items to display on product pages.', 'wpex' ),
					'default'	=> '4',
				),

				array(
					'id'		=> 'woocommerce_cross_sells_columns',
					'type'		=> 'select',
					'title'		=> __( 'Cross-Sells Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select how many columns you want.', 'wpex' ),
					'options'	=> array(
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '4',
				),


				/** WooCommerce => Styling **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'WooCommerce: Styling', 'wpex' ),
				),

				array(
					'id'					=> 'shop_button_background',
					'type'					=> 'color_gradient',
					'title'					=> __( 'Menu Shop Button Background', 'wpex' ),
					'subtitle'				=> __( 'Select your custom hex color.', 'wpex' ),
					'default'				=> '',
					'default'				=> array(
						'from'	=> '',
						'to'	=> ''
					),
					'transparent'			=> false,
					'target_element'		=> '.header-one .dropdown-menu .wcmenucart, .header-one .dropdown-menu .wcmenucart:hover, .header-one .dropdown-menu .wcmenucart:active',
					'theme_customizer'		=> false,
				),

				array(
					'id'					=> 'shop_button_color',
					'type'					=> 'color',
					'title'					=> __( 'Menu Shop Button Color', 'wpex' ), 
					'subtitle'				=> __( 'Select your custom hex color.', 'wpex' ),
					'default'				=> '',
					'transparent'			=> false,
					'target_element'		=> '.header-one .dropdown-menu .wcmenucart, .header-one .dropdown-menu .wcmenucart:hover, .header-one .dropdown-menu .wcmenucart:active',
					'target_style'			=> 'color',
					'theme_customizer'		=> false,
				),

				array(
					'id'				=> 'onsale_bg',
					'type'				=> 'color_gradient',
					'title'				=> __( 'On Sale Background', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> 'ul.products li.product .onsale, .single-product .onsale',
					'default'			=> array(
						'from'	=> '',
						'to'	=> ''
					),
				),

				array(
					'id'					=> 'woo_product_title_link_color',
					'type'					=> 'link_color',
					'title'					=> __( 'Product Entry Title Color', 'wpex' ),
					'subtitle'				=> __( 'Select your custom hex color.', 'wpex' ),
					'default'				=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'target_element'		=> 'body .product-entry .product-entry-title a, .related.products .product-entry-title a',
					'target_element_hover'	=> 'body .product-entry .product-entry-title a:hover, body .product-entry .product-entry-title:hover a, .related.products .product-entry-title:hover a, .related.products .product-entry-title a:hover',
					'target_element_active'	=> 'body .product-entry .product-entry-title a:active, .related.products .product-entry-title a:active',
					'target_style'			=> 'color',
				),

				array(
					'id'				=> 'woo_single_price_color',
					'type'				=> 'color',
					'title'				=> __( 'Single Product Price Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> 'div.product p.price',
					'target_style'		=> 'color',
					'default'			=> ''
				),

				array(
					'id'				=> 'woo_stars_color',
					'type'				=> 'color',
					'title'				=> __( 'Star Ratings Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> '.star-rating span',
					'target_style'		=> 'color',
					'default'			=> ''
				),

				array(
					'id'				=> 'woo_single_tabs_active_border_color',
					'type'				=> 'color',
					'title'				=> __( 'Product Tabs Active Border Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'target_element'	=> 'div.product .woocommerce-tabs ul.tabs li.active a',
					'target_style'		=> 'border-color',
					'default'			=> ''
				),

			),
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Blog
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'blog',
			'icon'			=> 'el-icon-edit',
			'title'			=> __( 'Blog', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'blog_page',
					'type'		=> 'select',
					'data'		=> 'pages',
					'title'		=> __( 'Blog Page', 'wpex' ),
					'subtitle'	=> __( 'Select your main blog page. This is used for your breadcrumbs.', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'blog_cats_exclude',
					'type'		=> 'select',
					'data'		=> 'categories',
					'multi'		=> true,
					'title'		=> __( 'Exclude Categories From Blog', 'wpex' ), 
					'subtitle'	=> __( 'Use this option to exclude categories from your main blog template and/or your index (if using the homepage as a blog)', 'wpex' ),
				),

				/** Blog => Archives **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Blog: Archives', 'wpex' ),
				),
				array(
					'id'		=> 'blog_style',
					'type'		=> 'select',
					'title'		=> __( 'Blog Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred blog style.', 'wpex' ),
					'options'	=> array(
						'large-image-entry-style'	=> __( 'Large Image','wpex' ),
						'thumbnail-entry-style'		=> __( 'Thumbnail','wpex' ),
						'grid-entry-style'			=> __( 'Grid','wpex' )
					),
					'default'	=> 'large-image-entry-style',
				),
				array(
					'id'		=> 'blog_grid_columns',
					'type'		=> 'select',
					'title'		=> __( 'Grid Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select how many columns you want.', 'wpex' ),
					'options'	=> array(
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4'
					),
					'default'	=> '2',
					'required'	=> array( 'blog_style', 'equals', 'grid-entry-style' ),
				),
				array(
					'id'		=> 'blog_grid_style',
					'type'		=> 'select',
					'title'		=> __( 'Blog Grid Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your style.', 'wpex' ),
					'options'	=> array(
						'fit-rows'	=> __( 'Fit Rows', 'wpex' ),
						'masonry'	=> __( 'Masonry', 'wpex' ),
					),
					'default'	=> 'fit-rows',
					'required'	=> array( 'blog_style', 'equals', 'grid-entry-style' ),
				),
				array(
					'id'		=> 'blog_archives_layout',
					'type'		=> 'select',
					'title'		=> __( 'Blog Archives Layout', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred layout for your main blog page, categories and tags.', 'wpex' ),
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'right-sidebar'
				),
				array(
					'id'		=> 'blog_pagination_style',
					'type'		=> 'select',
					'title'		=> __( 'Pagination Style', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred pagination style for the blog.', 'wpex' ),
					'options'	=> array(
						'standard'			=> __( 'Standard','wpex' ),
						'infinite_scroll'	=> __( 'Infinite Scroll','wpex' ),
						'next_prev'			=> __( 'Next/Prev','wpex' )
					),
					'default'	=> 'standard'
				),
				array(
					'id'		=> 'category_descriptions',
					'type'		=> 'switch', 
					'title'		=> __( 'Category Descriptions', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'blog_entry_composer',
					'type'		=> 'sorter',
					'title'		=> __( 'Entry Elements', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred order for the blog entries. Please keep in mind this doesn\'t work for all blog entry styles.', 'wpex' ),
					'compiler'	=> 'false',
					'options'	=> array(
						'enabled'	=> array(
							'featured_media'	=> __( 'Featured Media','wpex' ),
							'title_meta'		=> __( 'Title & Meta','wpex' ),
							'excerpt_content'	=> __( 'Excerpt','wpex' ),
							'readmore'			=> __( 'Read More','wpex' ),
						),
						'disabled'	=> array(),
					),
					'required'	=> array( 'blog_style', '!=', 'thumbnail-entry-style' ),
				),
				array(
					'id'		=> 'blog_entry_image_lightbox',
					'type'		=> 'switch',
					'title'		=> __( 'Entry Image Lightbox', 'wpex' ), 
					'subtitle'	=> __( 'Enable lightbox support for standard post type entry images.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'	=> 'blog_entry_image_hover_animation',
					'type'	=> 'select',
					'title'	=> __( 'Entry Image Hover Animation', 'wpex' ), 
				),
				array(
					'id'		=> 'blog_exceprt',
					'type'		=> 'switch', 
					'title'		=> __( 'Entry Auto Excerpts', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'blog_excerpt_length',
					'type'		=> 'text',
					'title'		=> __( 'Entry Excerpt length', 'wpex' ),
					'desc'		=> '',
					'subtitle'	=> __( 'How many words do you want to show for your blog entry excerpts?', 'wpex' ),
					'default'	=> '40',
					'required'	=> array( 'blog_exceprt', 'equals', '1' ),
				),
				array(
					'id'		=> 'blog_entry_readmore',
					'type'		=> 'switch', 
					'title'		=> __( 'Entry Read More Button', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'blog_style', 'equals', 'thumbnail-entry-style' ),
				),
				array(
					'id'		=> 'blog_entry_readmore_text',
					'type'		=> 'text', 
					'title'		=> __( 'Entry Read More Text', 'wpex' ),
					'subtitle'	=> __( 'Your custom entry read more button text, default is "Continue Reading".', 'wpex' ),
					'default'	=> '',
				),
				array(
					'id'		=> 'blog_entry_author_avatar',
					'type'		=> 'switch', 
					'title'		=> __( 'Entry Author Avatar', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> 0,
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'blog_style', 'equals', 'large-image-entry-style' ),
				),

				/** Blog => Single Post **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Blog: Single Post', 'wpex' ),
				),
				array(
					'id'		=> 'blog_single_layout',
					'type'		=> 'select',
					'title'		=> __( 'Post Layout', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred layout for your single posts. This setting can be overwritten on a per post basis via the meta options.', 'wpex' ),
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'right-sidebar'
				),
				array(
					'id'		=> 'blog_single_header',
					'type'		=> 'select',
					'title'		=> __( 'Post Header Displays', 'wpex' ),
					'subtitle'	=> __( 'Select what you want to display in the main header for single blog posts. If you select the title then it will not display with the meta within the post content, if you select custom text then the title will display with the meta.', 'wpex' ),
					'options'	=> array(
						'custom_text'	=> __( 'Custom Text','wpex' ),
						'post_title'	=> __( 'Post Title','wpex' ),
					),
					'default'	=> 'custom_text',
				),
				array(
					'id'		=> 'blog_single_header_custom_text',
					'type'		=> 'text', 
					'title'		=> __( 'Post Header Custom Text', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom text for the header on single posts.', 'wpex' ),
					'default'	=> __( 'Blog', 'wpex' ),
					'required'	=> array( 'blog_single_header', '=', 'custom_text' ),
				),
				array(
					'id'		=> 'blog_single_composer',
					'type'		=> 'sorter',
					'title'		=> __( 'Post Elements', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred order for the blog posts.', 'wpex' ),
					'compiler'	=> 'false',
					'options'	=> array(
						'enabled'	=> array(
							'featured_media'	=> __( 'Featured Media','wpex' ),
							'title_meta'		=> __( 'Title & Meta','wpex' ),
							'post_series'		=> __( 'Post Series','wpex' ),
							'the_content'		=> __( 'Content','wpex' ),
							'social_share'		=> __( 'Social Share','wpex' ),
							'author_bio'		=> __( 'Author Bio','wpex' ),
							'related_posts'		=> __( 'Related Posts','wpex' ),
							'comments'			=> __( 'Comments','wpex' ),
						),
						'disabled'	=> array(
							'post_tags'	=> __( 'Post Tags','wpex' ),
						),
					),
				),
				array(
					'id'		=> 'blog_post_image_lightbox',
					'type'		=> 'switch',
					'title'		=> __( 'Featured Image Lightbox', 'wpex' ), 
					'subtitle'	=> __( 'Enable lightbox support for the featured image on standard posts.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'blog_thumbnail_caption',
					'type'		=> 'switch', 
					'title'		=> __( 'Featured Image Caption', 'wpex' ),
					'subtitle'	=> __( 'Toggle the display of the featured image caption for single blog posts on or off (for standard post format only).', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'blog_related_title',
					'type'		=> 'text', 
					'title'		=> __( 'Post Related Articles Title', 'wpex' ),
					'subtitle'	=> __( 'Enter a custom title for the related articles section.', 'wpex' ),
					'default'	=> 'Related Posts',
				),
				array(
					'id'		=> 'blog_related_columns',
					'type'		=> 'select',
					'title'		=> __( 'Post Related Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select how many columns you want.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'4'	=> '4',
						'3'	=> '3',
						'2'	=> '2',
					),
					'default'	=> '3',
				),
				array(
					'id'		=> 'blog_related_count',
					'type'		=> 'text', 
					'title'		=> __( 'Post Related Articles Count', 'wpex' ),
					'subtitle'	=> __( 'Enter the number of related items to display.', 'wpex' ),
					'default'	=> '3',
				),
				array(
					'id'		=> 'blog_related_excerpt',
					'type'		=> 'switch', 
					'title'		=> __( 'Post Related Articles Excerpt', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'blog_related_excerpt_length',
					'type'		=> 'text', 
					'title'		=> __( 'Post Related Articles Excerpt Length', 'wpex' ),
					'subtitle'	=> __( 'How many words to display for the related articles excerpt?', 'wpex' ),
					'default'	=> '15',
				),

				/** Blog => Other **/
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Blog: Other', 'wpex' ),
				),
				array(
					'id'		=> 'category_description_position',
					'type'		=> 'select',
					'title'		=> __( 'Category Description Position', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred location.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'under_title'	=> __( 'Under Title', 'wpex' ),
						'above_loop'	=> __( 'Above Loop', 'wpex' ),
					),
					'default'	=> 'under_title',
				),
				array(
					'id'		=> 'breadcrumbs_blog_cat',
					'type'		=> 'switch', 
					'title'		=> __( 'Category In Breadcrumbs', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'post_series',
					'type'		=> 'switch',
					'title'		=> __( 'Post Series', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'post_series_labels',
					'type'		=> 'text',
					'title'		=> __( 'Post Series Labels', 'wpex' ),
					'subtitle'	=> __( 'Use this field to rename your post series taxonomy.', 'wpex' ),
					'default'	=> __( 'Post Series', 'wpex' ),
				),
				array(
					'id'		=> 'post_series_slug',
					'type'		=> 'text',
					'title'		=> __( 'Post Series Slug', 'wpex' ),
					'subtitle'	=> __( 'Use this field to alter the default slug for this taxonomy. After changing this field go to "Settings->Permalinks" and resave your settings to prevent 404 errors.', 'wpex' ),
					'default'	=> 'post-series',
				),
			),

		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Images
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'images',
			'icon'			=> 'el-icon-camera',
			'title'			=> __( 'Image Cropping', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'image_resizing',
					'type'		=> 'switch', 
					'title'		=> __( 'Image Cropping', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'retina',
					'type'		=> 'switch', 
					'title'		=> __( 'Retina Support', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> 0,
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array( 
					"title"		=> __( 'Blog Entry: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "blog_entry_image_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Blog Entry: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "blog_entry_image_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array( 
					"title"		=> __( 'Blog Post: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "blog_post_image_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Blog Post: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "blog_post_image_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array( 
					"title"		=> __( 'Blog Full-Width Post: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "blog_post_full_image_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Blog Full-Width Post: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "blog_post_full_image_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array( 
					"title"		=> __( 'Blog Related Posts: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "blog_related_image_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),

				array(
					"title"		=> __( 'Blog Related Posts: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "blog_related_image_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Portfolio Archive Entry: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "portfolio_entry_image_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Portfolio Archive Entry: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "portfolio_entry_image_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array( 
					"title"		=> __( 'Staff Archive Entry: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "staff_entry_image_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Staff Archive Entry: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "staff_entry_image_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array( 
					"title"		=> __( 'Testimonial Archive Entry: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "testimonial_entry_image_width",
					'default'	=> '45',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Testimonial Archive Entry: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "testimonial_entry_image_height",
					'default'	=> '45',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'WooCommerce Entry: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "woo_entry_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'WooCommerce Entry: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "woo_entry_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'WooCommerce Post: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "woo_post_image_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'WooCommerce Post: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "woo_post_image_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'WooCommerce Category Entry: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "woo_cat_entry_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'WooCommerce Category Entry: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "woo_cat_entry_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Custom WP Gallery: Image Width', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom width in pixels.', 'wpex' ),
					"id"		=> "gallery_image_width",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
				array(
					"title"		=> __( 'Custom WP Gallery: Image Height', 'wpex' ),
					"subtitle"	=> __( 'Enter your custom height in pixels. Enter 9999 to keep your image proportions.', 'wpex' ),
					"id"		=> "gallery_image_height",
					'default'	=> '9999',
					"type"		=> "text",
					'required'	=> array( 'image_resizing', 'equals', '1' ),
				),
			)
		);

		$sections[] = array(
			'id'			=> 'error_page',
			'icon'			=> 'el-icon-error',
			'title'			=> __( '404 Page', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'error_page_redirect',
					'type'		=> 'switch', 
					'title'		=> __( 'Redirect 404', 'wpex' ),
					'subtitle'	=> __( 'Toggle on to redirect all 404 errors to your homepage. Some people think this is good for SEO.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'error_page_title',
					'type'		=> 'text', 
					'title'		=> __( '404 Page Title', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom title for the 404 page.', 'wpex' ),
					'default'	=> '',
					'required'	=> array( 'error_page_redirect', '!=', '1' ),
				),
				array(
					'id'		=> 'error_page_text',
					'type'		=> 'editor',
					'title'		=> __( '404 Page Content', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom content for the 404 page.', 'wpex' ),
					'default'	=> '',
					'required'	=> array( 'error_page_redirect', '!=', '1' ),
					'teeny'		=> false,
				),
				array(
					'id'		=> 'error_page_styling',
					'type'		=> 'switch', 
					'title'		=> __( '404 Page Default Styling', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'error_page_redirect', '!=', '1' ),
				),
			),
		);

		$sections[] = array(
			'id'			=> 'footer',
			'icon'			=> 'el-icon-bookmark',
			'title'			=> __( 'Footer', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'footer_reveal',
					'type'		=> 'switch', 
					'title'		=> __( 'Footer Reveal', 'wpex' ),
					'subtitle'	=> __( 'Enable the footer reveal style. The footer will be placed in a fixed postion and display on scroll. This setting is for the "Full-Width" layout only and desktops only.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Footer Callout', 'wpex' ),
				),
				array(
					'id'		=> 'callout',
					'type'		=> 'switch', 
					'title'		=> __( 'Footer Callout', 'wpex' ),
					'default'	=> true,
				),
				array(
					'id'	=> 'callout_visibility',
					'type'	=> 'select',
					'title'	=> __( 'Callout Visibility', 'wpex' ), 
				),
				array(
					'id'		=> 'callout_text',
					'type'		=> 'editor',
					'title'		=> __( 'Footer Callout: Content', 'wpex' ), 
					'default'	=> 'I am the footer call-to-action block, here you can add some relevant/important information about your company or product. I can be disabled in the theme options.',
				),
				array(
					'id'		=> 'callout_link',
					'type'		=> 'text',
					'title'		=> __( 'Footer Callout: Link', 'wpex' ), 
					'default'	=> 'http://www.wpexplorer.com',
				),
				array(
					'id'		=> 'callout_link_txt',
					'type'		=> 'text',
					'title'		=> __( 'Footer Callout: Link Text', 'wpex' ), 
					'default'	=> 'Get In Touch',
				),
				array(
					'id'				=> 'footer_callout_bg',
					'type'				=> 'color',
					'title'				=> __( 'Footer Callout: Background', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer-callout-wrap',
					'target_style'		=> 'background-color',
					'required'			=> array( 'callout', 'equals', '1' ),
				),
				array(
					'id'				=> 'footer_callout_border',
					'type'				=> 'color',
					'title'				=> __( 'Footer Callout: Border Color', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer-callout-wrap',
					'target_style'		=> 'border-top-color',
					'required'			=> array( 'callout', 'equals', '1' ),
				),
				array(
					'id'				=> 'footer_callout_color',
					'type'				=> 'color',
					'title'				=> __( 'Footer Callout: Color', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> '#footer-callout-wrap',
					'target_style'		=> 'color',
					'required'			=> array( 'callout', 'equals', '1' ),
				),
				array(
					'id'					=> 'footer_callout_link_color',
					'type'					=> 'link_color',
					'title'					=> __( 'Footer Callout: Content Link Color', 'wpex' ),
					'subtitle'				=> __( 'Select your custom hex color.', 'wpex' ),
					'default'				=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'target_element'		=> '.footer-callout-content a',
					'target_element_hover'	=> '.footer-callout-content a:hover',
					'target_element_active'	=> '.footer-callout-content a:active',
					'target_style'			=> 'color',
					'required'				=> array( 'callout', 'equals', '1' ),
				),
				array(
					'id'				=> 'footer_callout_button_bg',
					'type'				=> 'color_gradient',
					'title'				=> __( 'Footer Callout: Button Background', 'wpex' ), 
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> array(
						'from'	=> '',
						'to'	=> ''
					),
					'transparent'		=> false,
					'target_element'	=> '#footer-callout .theme-button',
					'required'			=> array( 'callout', 'equals', '1' ),
				),
				array(
					'id'				=> 'footer_callout_button_color',
					'type'				=> 'color',
					'title'				=> __( 'Footer Callout: Button Text Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'default'			=> '',
					'target_element'	=> '#footer-callout .theme-button',
					'required'			=> array( 'callout', 'equals', '1' ),
					'target_style'		=> 'color',
				),
				array(
					'id'				=> 'footer_callout_button_hover_bg',
					'type'				=> 'color_gradient',
					'title'				=> __( 'Footer Callout: Button Hover Background', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'default'			=> array(
						'from'	=> '',
						'to'	=> ''
					),
					'transparent'		=> false,
					'target_element'	=> '#footer-callout .theme-button:hover',
					'required'			=> array( 'callout', 'equals', '1' ),
				),
				array(
					'id'				=> 'footer_callout_button_hover_color',
					'type'				=> 'color',
					'title'				=> __( 'Footer Callout: Button Hover Text Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color.', 'wpex' ),
					'transparent'		=> false,
					'default'			=> '',
					'target_element'	=> '#footer-callout .theme-button:hover',
					'required'			=> array( 'callout', 'equals', '1' ),
					'target_style'		=> 'color',
				),
				array(
					'id'			=> 'callout_button_target',
					'type'			=> 'select',
					'title'			=> __( 'Footer Callout: Button Target', 'wpex' ),
					'subtitle'		=> __( 'Select your footer callout button link target window.', 'wpex' ),
					'options'		=> array(
						'blank'	=> __( 'New Window', 'wpex' ),
						'self'	=> __( 'Same Window', 'wpex' )
					),
					'default'		=> 'blank',
					'required'		=> array('callout','equals','1'),
				),
				array(
					'id'		=> 'callout_button_rel',
					'type'		=> 'select',
					'title'		=> __( 'Footer Callout: Button Rel', 'wpex' ),
					'subtitle'	=> __( 'Select your footer callout button link rel value.', 'wpex' ),
					'options'	=> array(
						'dofollow'	=> 'dofollow',
						'nofollow'	=> 'nofollow'
					),
					'default'	=> 'dofollow',
					'required'	=> array('callout','equals','1'),
				),
				array(
					'id'		=> 'callout_button_border_radius',
					'type'		=> 'text',
					'title'		=> __( 'Footer Callout: Button Border Radius', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom border radius for the callout button in px.', 'wpex' ),
					'required'	=> array('callout','equals','1'),
				),
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Footer Widgets', 'wpex' ),
				),
				array(
					'id'		=> 'footer_widgets',
					'type'		=> 'switch', 
					'title'		=> __( 'Footer Widgets', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'footer_col',
					'type'		=> 'select',
					'title'		=> __( 'Footer Widget Columns', 'wpex' ), 
					'subtitle'	=> __( 'Select how many columns you want.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'4'	=> '4',
						'3'	=> '3',
						'2'	=> '2',
						'1'	=> '1',
					),
					'default'	=> '4',
					'required'	=> array('footer_widgets','equals','1'),
				),
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Bottom Footer Area', 'wpex' ),
				),
				array(
					'id'		=> 'footer_copyright',
					'type'		=> 'switch', 
					'title'		=> __( 'Bottom Footer Area', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'				=> 'footer_copyright_text',
					'type'				=> 'editor',
					'title'				=> __( 'Copyright', 'wpex' ), 
					'subtitle'			=> __( 'Enter your custom copyright text.', 'wpex' ),
					'default'			=> 'Copyright 2013 - All Rights Reserved',
					'required'			=> array('footer_copyright','equals','1'),
					'editor_options'	=> '',
					'args'				=> array('teeny' => false)
				),
				array(
					'id'	=> 'multi-info',
					'type'	=> 'info',
					'title'	=> false,
					'desc'	=> __( 'Scroll Up Button', 'wpex' ),
				),
				array(
					'id'		=> 'scroll_top',
					'type'		=> 'switch', 
					'title'		=> __( 'Scroll Up Button', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'scroll_top_border_radius',
					'type'		=> 'text',
					'title'		=> __( 'Scroll Up Button Border Radius', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom border radius for the scroll top button. Default is 35px.', 'wpex' ),
					'required'	=> array('scroll_top','equals','1'),
				),
				array(
					'id'			=> 'scroll_top_bg',
					'type'			=> 'link_color',
					'title'			=> __( 'Scroll Up Button Background', 'wpex' ),
					'subtitle'		=> __( 'Select your custom hex color.', 'wpex' ),
					'default'		=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'required'				=> array('scroll_top','equals','1'),
					'target_element'		=> '#site-scroll-top',
					'target_element_hover'	=> '#site-scroll-top:hover',
					'target_element_active'	=> '#site-scroll-top:active',
					'target_style'			=> 'background',
				),
				array(
					'id'					=> 'scroll_top_border',
					'type'					=> 'link_color',
					'title'					=> __( 'Scroll Up Button Border', 'wpex' ),
					'subtitle'				=> __( 'Select your custom hex color.', 'wpex' ),
					'default'				=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'required'				=> array('scroll_top','equals','1'),
					'target_element'		=> '#site-scroll-top',
					'target_element_hover'	=> '#site-scroll-top:hover',
					'target_element_active'	=> '#site-scroll-top:active',
					'target_style'			=> 'border-color',
				),
				array(
					'id'					=> 'scroll_top_color',
					'type'					=> 'link_color',
					'title'					=> __( 'Scroll Up Button Color', 'wpex' ),
					'subtitle'				=> __( 'Select your custom hex color.', 'wpex' ),
					'default'				=> array(
						'regular'	=> '',
						'hover'		=> '',
						'active'	=> '',
					),
					'required'				=> array('scroll_top','equals','1'),
					'target_element'		=> '#site-scroll-top',
					'target_element_hover'	=> '#site-scroll-top:hover',
					'target_element_active'	=> '#site-scroll-top:active',
					'target_style'			=> 'color',
				),
			)
		);

		/*-----------------------------------------------------------------------------------*/
		/*	- Visual Composer
		/*-----------------------------------------------------------------------------------*/
		$sections[] = array(
			'id'			=> 'visual_composer',
			'icon'			=> 'el-icon-puzzle',
			'customizer'	=> false,
			'title'			=> __( 'Visual Composer', 'wpex' ),
			'fields'		=> array(
				array(
					'id'		=> 'visual_composer_theme_mode',
					'type'		=> 'switch',
					'title'		=> __( 'Run Visual Composer In Theme Mode', 'wpex' ),
					'subtitle'	=> __( 'Please keep this option enabled unless you have purchased a full copy of the Visual Composer plugin directly from the author.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'extend_visual_composer_extension',
					'type'		=> 'switch',
					'title'		=> __( 'Extend The Visual Composer?', 'wpex' ),
					'subtitle'	=> __( 'This theme includes many extensions (more modules) for the Visual Composer plugin. If you do not wish to use any disable them here.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'vc_row_bottom_margin',
					'type'		=> 'text',
					'title'		=> __( 'Visual Composer Bottom Column Margin', 'wpex' ),
					'subtitle'	=> __( 'Enter a default bottom margin for all Visual Composer columns to help speed up development.', 'wpex' ),
					'default'	=> '40px',
				),
				array(
					'id'				=> 'vcex_text_separator_two_border_color',
					'type'				=> 'color',
					'title'				=> __( 'Seperator With Text Border Color', 'wpex' ),
					'subtitle'			=> __( 'Select your custom hex color for the seperator with text module style 2.', 'wpex' ),
					'default'			=> '',
					'transparent'		=> false,
					'target_element'	=> 'body .vc_text_separator_two span',
					'target_style'		=> 'border-color',
					'theme_customizer'	=> false,
				),
				array(
					'id'	=> 'vcex_text_tab_two_bottom_border',
					'type'	=> 'color',
					'title'	=> __( 'Tabs Alternative 2 Border Color', 'wpex' ),
				),
				array(
					'id'	=> 'vcex_carousel_arrows',
					'type'	=> 'color',
					'title'	=> __( 'Carousel Arrows Highlight Color', 'wpex' ),
				),
				array(
					'id'	=> 'vcex_pricing_featured_default',
					'type'	=> 'color',
					'title'	=> __( 'Featured Pricing Table Color', 'wpex' ),
				),
				array(
					'id'	=> 'vcex_grid_filter_active_color',
					'type'	=> 'color',
					'title'	=> __( 'Grid Filter: Active Link Color', 'wpex' ),
				),
				array(
					'id'	=> 'vcex_grid_filter_active_bg',
					'type'	=> 'color',
					'title'	=> __( 'Grid Filter: Active Link Background', 'wpex' ),
				),
				array(
					'id'	=> 'vcex_grid_filter_active_border',
					'type'	=> 'color',
					'title'	=> __( 'Grid Filter: Active Link Border', 'wpex' ),

				),
				array(
					'id'	=> 'vcex_recent_news_date_bg',
					'type'	=> 'color',
					'title'	=> __( 'Recent News Date: Background', 'wpex' ),
				),
				array(
					'id'	=> 'vcex_recent_news_date_color',
					'type'	=> 'color',
					'title'	=> __( 'Recent News Date: Color', 'wpex' ),
				),
				array(
					'id'	=> 'vcex_icon_box_hover_color',
					'type'	=> 'color',
					'title'	=> __( 'Icon Box Hover Color', 'wpex' ),
				),
			),
		);

		$sections[] = array(
			'id'			=> 'admin_login',
			'icon'			=> 'el-icon-lock',
			'title'			=> __( 'Admin Login', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'	=> 'custom_admin_login',
					'type'	=> 'switch',
				),
				array(
					'id'	=> 'admin_login_logo',
					'type'	=> 'media',
				),
				array(
					'id'	=> 'admin_login_logo_height',
					'type'	=> 'text',
				),
				array(
					'id'	=> 'admin_login_logo_url',
					'type'	=> 'text',
				),
				array(
					'id'	=> 'admin_login_background_color',
					'type'	=> 'color',
				),
				array(
					'id'	=> 'admin_login_background_img',
					'type'	=> 'media',
				),
				array(
					'id'	=> 'admin_login_background_style',
					'type'	=> 'select',
				),
				array(
					'id'	=> 'admin_login_form_background_color',
					'type'	=> 'color',
				),
				array(
					'id'	=> 'admin_login_form_background_opacity',
					'type'	=> 'text',
				),
				array(
					'id'	=> 'admin_login_form_text_color',
					'type'	=> 'color',
				),
				array(
					'id'	=> 'admin_login_form_top',
					'type'	=> 'text',
				),
			),
		);

		$sections[] = array(
			'id'			=> 'social',
			'icon'			=> 'el-icon-twitter',
			'title'			=> __( 'Social Sharing', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'	=> 'social_share_position',
					'type'	=> 'select',
				),
				array(
					'id'		=> 'social_share_heading',
					'type'		=> 'text',
					'title'		=> __( 'Social Sharing Heading', 'wpex' ), 
					'subtitle'	=> __( 'Your custom text for the social sharing heading. For mobile and horizontal social sharing.', 'wpex' ),
					'default'	=> __( 'Please Share This', 'wpex' ),
				),
				array(
					'id'		=> 'social_share_style',
					'type'		=> 'select',
					'title'		=> __( 'Social Sharing Style', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'minimal'	=> __( 'Minimal','wpex' ),
						'flat'		=> __( 'Flat','wpex' ),
						'three-d'	=> __( '3D','wpex' ),
					),
					'default'	=> 'minimal'
				),
				array(
					'id'		=> 'social_share_blog_posts',
					'type'		=> 'switch', 
					'title'		=> __( 'Blog Posts: Social Share', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'social_share_blog_entries',
					'type'		=> 'switch', 
					'title'		=> __( 'Blog Entries: Social Share', 'wpex' ),
					'subtitle'	=> __( 'Toggle the social sharing icons on your blog entries on or off. Note: They will only display on the Large Image style blog entries and for the vertical social position.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
					'required'	=> array( 'social_share_position', 'equals', 'vertical' ),
				),
				array(
					'id'		=> 'social_share_pages',
					'type'		=> 'switch', 
					'title'		=> __( 'Pages: Social Share', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'social_share_portfolio',
					'type'		=> 'switch', 
					'title'		=> __( 'Portfolio: Social Share', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'social_share_staff',
					'type'		=> 'switch', 
					'title'		=> __( 'Staff: Social Share', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'social_share_woo',
					'type'		=> 'switch', 
					'title'		=> __( 'WooCommerce: Social Share', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'	=> 'social_share_sites',
					'type'	=> 'checkbox',
					'title'	=> __( 'Social Sharing Links', 'wpex' ), 
				),
			),
		);

		$sections[] = array(
			'id'			=> 'lightbox',
			'icon'			=> 'el-icon-zoom-in',
			'customizer'	=> false,
			'title'			=> __( 'Lightbox', 'wpex' ),
			'fields'		=> array(
				array(
					'id'		=> 'lightbox_skin',
					'type'		=> 'select', 
					'tiles'		=> false,
					'title'		=> __( 'Lightbox Skin', 'wpex' ),
					'subtitle'	=> __( 'Select your lightbox skin.', 'wpex' ),
					'default'	=> 'dark',
					'options'	=> array(
						'dark'			=> __( 'Dark', 'wpex' ),
						'light'			=> __( 'Light', 'wpex' ),
						'mac'			=> __( 'Mac', 'wpex' ),
						'metro-black'	=> __( 'Metro Black', 'wpex' ),
						'metro-white'	=> __( 'Metro White', 'wpex' ),
						'parade'		=> __( 'Parade', 'wpex' ),
						'smooth'		=> __( 'Smooth', 'wpex' ),
					),
				),
				array(
					'id'		=> 'lightbox_thumbnails',
					'type'		=> 'switch', 
					'title'		=> __( 'Lightbox Gallery Thumbnails', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'lightbox_arrows',
					'type'		=> 'switch', 
					'title'		=> __( 'Lightbox Gallery Arrows', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'lightbox_mousewheel',
					'type'		=> 'switch', 
					'title'		=> __( 'Lightbox Mousewheel', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'lightbox_titles',
					'type'		=> 'switch', 
					'title'		=> __( 'Lightbox Titles', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'lightbox_fullscreen',
					'type'		=> 'switch', 
					'title'		=> __( 'Lightbox Fullscreen Button', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
			),
		);

		$sections[] = array(
			'id'			=> 'seo',
			'icon'			=> 'el-icon-search',
			'title'			=> __( 'SEO', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'sidebar_headings',
					'type'		=> 'select',
					'title'		=> __( 'Sidebar Widget Title Headings', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred heading type.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'h2'	=> 'h2',
						'h3'	=> 'h3',
						'h4'	=> 'h4',
						'h5'	=> 'h5',
						'h6'	=> 'h6',
						'span'	=> 'span',
						'div'	=> 'div',
					),
					'default'	=> 'div'
				),
				array(
					'id'		=> 'footer_headings',
					'type'		=> 'select',
					'title'		=> __( 'Footer Widget Title Headings', 'wpex' ), 
					'subtitle'	=> __( 'Select your preferred heading type.', 'wpex' ),
					'options'	=> array(
						'h2'	=> 'h2',
						'h3'	=> 'h3',
						'h4'	=> 'h4',
						'h5'	=> 'h5',
						'h6'	=> 'h6',
						'span'	=> 'span',
						'div'	=> 'div',
					),
					'default'	=> 'div'
				),
				array(
					'id'		=> 'breadcrumbs',
					'type'		=> 'switch', 
					'title'		=> __( 'Breadcrumbs', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'				=> 'breadcrumbs_position',
					'type'				=> 'select', 
					'title'				=> __( 'Breadcrumbs: Position', 'wpex' ),
					'subtitle'			=> __( 'Select your preferred breadcrumbs style.', 'wpex' ),
					'options'			=> array(
						'default'		=> __( 'Absolute Right', 'wpex' ),
						'under-title'	=> __( 'Under Title', 'wpex' ),
					),
					'default'	=> 'default',
					'required'	=> array('breadcrumbs','equals','1'),
				),
				array(
					'id'		=> 'breadcrumbs_home_title',
					'type'		=> 'text', 
					'title'		=> __( 'Breadcrumbs: Custom Home Title', 'wpex' ),
					'subtitle'	=> __( 'Enter your custom breadcrumbs home title. You can enter HTML if you want to display an icon instead (just like adding icons to your menu using FontAwesome).', 'wpex' ),
					'default'	=> '',
					'required'	=> array( 'breadcrumbs', 'equals', '1' ),
				),
				array(
					'id'		=> 'breadcrumbs_title_trim',
					'type'		=> 'text', 
					'title'		=> __( 'Breadcrumbs: Title Trim Length', 'wpex' ),
					'subtitle'	=> __( 'Enter the max number of words to display for your breadcrumbs post title.', 'wpex' ),
					'default'	=> '4',
					'required'	=> array('breadcrumbs','equals','1'),
				),
				array(
					'id'		=> 'remove_posttype_slugs',
					'type'		=> 'switch',
					'title'		=> __( 'Remove Custom Post Type Slugs (Experimental)', 'wpex' ),
					'subtitle'	=> __( 'Toggle the slug on/off for your custom post types (portfolio, staff, testimonials). Custom Post Types in WordPress by default should have a slug to prevent conflicts, you can use this setting to disable them, but be careful.', 'wpex' ),
					'default'	=> '',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
			),
		);

		$sections[] = array(
			'id'			=> 'other',
			'icon'			=> 'el-icon-wrench',
			'title'			=> __( 'Other', 'wpex' ),
			'customizer'	=> false,
			'fields'		=> array(
				array(
					'id'		=> 'page_single_layout',
					'type'		=> 'select',
					'title'		=> __( 'Page Layout', 'wpex' ),
					'subtitle'	=> __( 'Select your preferred layout for your pages. This setting can be overwritten on a per page basis via the meta options.', 'wpex' ),
					'desc'		=> '',
					'options'	=> array(
						'right-sidebar'	=> __( 'Right Sidebar','wpex' ),
						'left-sidebar'	=> __( 'Left Sidebar','wpex' ),
						'full-width'	=> __( 'No Sidebar','wpex' ),
					),
					'default'	=> 'right-sidebar',
				),
				array(
					'id'		=> 'pages_custom_sidebar',
					'type'		=> 'switch', 
					'title'		=> __( 'Custom Pages Sidebar', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'search_custom_sidebar',
					'type'		=> 'switch', 
					'title'		=> __( 'Custom Search Results Sidebar', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'shortcodes_tinymce',
					'type'		=> 'switch', 
					'title'		=> __( 'Shortcodes TinyMCE Button', 'wpex' ),
					'subtitle'	=> __( 'Toggle the built-in TinyMCE Shortcodes button that contains some useful shortcodes.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'custom_wp_gallery',
					'type'		=> 'switch', 
					'title'		=> __( 'Custom WordPress Gallery Output', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'blog_dash_thumbs',
					'type'		=> 'switch', 
					'title'		=> __( 'Dashboard Featured Images', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'page_comments',
					'type'		=> 'switch', 
					'title'		=> __( 'Comments on Pages', 'wpex' ),
					'subtitle'	=> __( 'Toggle this option on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'widget_icons',
					'type'		=> 'switch', 
					'title'		=> __( 'Widget Icons', 'wpex' ),
					'subtitle'	=> __( 'Certain widgets include little icons such as the recent posts widget. Here you can toggle the icons on or off.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'page_featured_image',
					'type'		=> 'switch', 
					'title'		=> __( 'Automatically Display Featured Images For Pages', 'wpex' ),
					'subtitle'	=> __( 'Set to "on" if you want the featured images for pages to display automatically at the top of the page.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'trim_custom_excerpts',
					'type'		=> 'switch', 
					'title'		=> __( 'Trim custom excerpts', 'wpex' ),
					'subtitle'	=> __( 'Set to "on" if you want custom excerpts to be trimed for your entries to the excerpt length defined. If set to "off" custom excerpts will always output in full.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'search_posts_per_page',
					'type'		=> 'text', 
					'title'		=> __( 'Search Posts Per Page', 'wpex' ),
					'subtitle'	=> __( 'How many posts do you wish to display on your search page before pagination?', 'wpex' ),
					'default'	=> '10',
				),
				array(
					'id'		=> 'posts_meta_options',
					'type'		=> 'checkbox',
					'title'		=> __( 'Meta Options', 'wpex' ), 
					'subtitle'	=> __( 'Select the items to include in the post meta.', 'wpex' ),
					'options'	=> array(
						'date'		=> 'Date',
						'category'	=> 'Category',
						'comments'	=> 'Comments',
						'author'	=> 'Author',
					),
					'default'	=> array(
						'date'		=> '1',
						'category'	=> '1',
						'comments'	=> '1',
						'author'	=> false,
					),
				),
			),
		);

		$sections[] = array(
			'id'			=> 'optimizations',
			'icon'			=> 'el-icon-tasks',
			'title'			=> __( 'Optimizations', 'wpex' ),
			'fields'		=> array(
				array(
					'id'		=> 'minify_js',
					'type'		=> 'switch', 
					'title'		=> __( 'Minify JS', 'wpex' ),
					'subtitle'	=> __( 'This theme makes use of a lot of js scripts, use this function to load a single minified file with all the required code. Disable for testing purposes.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'remove_scripts_version',
					'type'		=> 'switch', 
					'title'		=> __( 'Remove Version Parameter From JS & CSS Files', 'wpex' ),
					'subtitle'	=> __( 'Most scripts and style-sheets called by WordPress include a query string identifying the version. This can cause issues with caching and such, which will result in less than optimal load times. You can toggle this setting on to remove the query string from such strings.', 'wpex' ),
					'default'	=> '1',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'jpeg_100',
					'type'		=> 'switch', 
					'title'		=> __( 'JPEG 100% Quality', 'wpex' ),
					'subtitle'	=> __( 'By default images cropped with WordPress are resized/cropped at 90% quality. Enable this setting to set all JPEGs to 100% quality.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'remove_jetpack_devicepx',
					'type'		=> 'switch', 
					'title'		=> __( 'Remove Jetpack devicepx script', 'wpex' ),
					'subtitle'	=> __( 'Toggle the jetpack devicepx script on/off. The file is used to optionally load retina/HiDPI versions of files (Gravatars etc) which are known to support it, for devices that run at a higher resolution. But can be disabled to prevent the extra js call.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'cleanup_wp_head',
					'type'		=> 'checkbox',
					'title'		=> __( 'Cleanup WP Head', 'wpex' ),
					'subtitle'	=> __( 'Select the items to REMOVE from the wp_head hook to clean things up.', 'wpex' ),
					'options'	=> array(
						'feed_links'						=> 'General Feed Links',
						'feed_links_extra'					=> 'Extra Feed Links',
						'rsd_link'							=> 'EditURI/RSD link',
						'wlwmanifest_link'					=> 'Windows Live Writer Manifest',
						'index_rel_link'					=> 'Index Link',
						'parent_post_rel_link'				=> 'Parent Post Rel',
						'start_post_rel_link'				=> 'Start Post Rel',
						'adjacent_posts_rel_link_wp_head'	=> 'Adjecent Posts Rel',
						'wp_generator'						=> 'WordPress Generator (WP Version)',
					),
					'default'	=> array(
						'feed_links_extra'					=> '1',
						'feed_links'						=> '1',
						'rsd_link'							=> '1',
						'wlwmanifest_link'					=> '1',
						'index_rel_link'					=> '1',
						'parent_post_rel_link'				=> '1',
						'start_post_rel_link'				=> '1',
						'adjacent_posts_rel_link_wp_head'	=> '1',
						'wp_generator'						=> '1',
					),
				),
			),
		);
		$sections[] = array(
			'id'			=> 'custom_css',
			'icon'			=> 'el-icon-css',
			'title'			=> __( 'Custom CSS', 'wpex' ),
			'fields'		=> array(
				array(
					'id'		=> 'custom_css',
					'type'		=> 'textarea',
					'rows'		=> 50,
					'theme'		=> '',
					'title'		=> __( 'Design Edits', 'wpex' ),
					'subtitle'	=> __( 'Quickly add some CSS to your theme to make design adjustments by adding it to this block. It is a much better solution then manually editing style.css<br /><br />This field is provided for your convinience, but please if you are making a lot of changes use a child theme as you should!', 'wpex' ),
					'default'	=> '',
				),
			),
		);
		$sections[] = array(
			'id'			=> 'updates',
			'icon'			=> 'el-icon-retweet',
			'title'			=> __( 'Theme Updates', 'wpex' ),
			'fields'		=> array(
				array(
					'id'		=> 'enable_auto_updates',
					'type'		=> 'switch', 
					'title'		=> __( 'Enable Auto Updates', 'wpex' ),
					'subtitle'	=> __( 'You can toggle the automatic updates for your theme on or off.', 'wpex' ),
					'default'	=> '0',
					'on'		=> __( 'On', 'wpex' ),
					'off'		=> __( 'Off', 'wpex' ),
				),
				array(
					'id'		=> 'envato_license_key',
					'type'		=> 'text',
				),
			),
		);
		return $sections;

    }

}
new WPEX_Migrate_Redux;