<?php
/**
 * Adds all Typography options to the Customizer and outputs the custom CSS for them
 * 
 * @package		Total
 * @subpackage	Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

if ( ! class_exists( 'WPEX_Theme_Customizer_Typography' ) ) {
	class WPEX_Theme_Customizer_Typography {

		/*-----------------------------------------------------------------------------------*/
		/*	- Constructor
		/*-----------------------------------------------------------------------------------*/
		public function __construct() {
			add_action( 'customize_register', array( $this , 'register' ) );
			add_action( 'customize_save_after', array( $this, 'reset_cache' ) );
			add_action( 'wp_head', array( $this, 'load_fonts' ) );
			add_action( 'wp_head', array( $this, 'output_css' ) );
			add_filter( 'tiny_mce_before_init', array( $this, 'mce_fonts' ) );
			add_action( 'after_setup_theme', array( $this, 'mce_scripts' ) );
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Array of elements for typography options
		/*-----------------------------------------------------------------------------------*/
		public function elements() {
			$array = array(
				'body'	=> array(
					'label'		=>	__( 'Body', 'wpex' ),
					'target'	=>	'body',
				),
				'logo'	=> array(
					'label'		=> __( 'Logo', 'wpex' ),
					'target'	=> '#site-logo a',
				),
				'top_menu'	=> array(
					'label'		=> __( 'Top Bar', 'wpex' ),
					'target'	=> '#top-bar-content',
				),
				'menu'	=> array(
					'label'		=> __( 'Main Menu', 'wpex' ),
					'target'	=> '#site-navigation .dropdown-menu a',
				),
				'menu_dropdown'	=> array(
					'label'		=> __( 'Main Menu: Dropdowns', 'wpex' ),
					'target'	=> '#site-navigation .dropdown-menu ul a',
				),
				'page_title'	=> array(
					'label'		=> __( 'Page Title', 'wpex' ),
					'target'	=> '.page-header-title',
				),
				'blog_post_title'	=> array(
					'label'			=> __( 'Blog Post Title', 'wpex' ),
					'target'		=> '.blog-entry-title,.single-post-title',
				),
				'breadcrumbs'	=> array(
					'label'		=> __( 'Breadcrumbs', 'wpex' ),
					'target'	=> '.site-breadcrumbs',
				),
				'headings'	=> array(
					'label'		=> __( 'Headings', 'wpex' ),
					'target'	=> 'h1,h2,h3,h4,h5,h6,.theme-heading,.heading-typography,.widget-title,.wpex-widget-recent-posts-title,.comment-reply-title',
					'settings'	=> array( 'font-family', 'font-weight', 'letter-spacing', 'font-style', 'letter-spacing', 'text-transform' )
				),
				'sidebar_widget_title'	=> array(
					'label'		=> __( 'Sidebar Widget Heading', 'wpex' ),
					'target'	=> '.sidebar-box .widget-title',
				),
				'entry_h2'		=> array(
					'label'		=> __( 'Post H2', 'wpex' ),
					'target'	=> '.entry h2'
				),
				'entry_h3'		=> array(
					'label'		=> __( 'Post H3', 'wpex' ),
					'target'	=> '.entry h3'
				),
				'footer_widget_title'	=> array(
					'label'		=> __( 'Footer Widget Heading', 'wpex' ),
					'target'	=> '.footer-widget .widget-title',
				),
				'copyright'	=> array(
					'label'		=> __( 'Copyright', 'wpex' ),
					'target'	=> '#copyright',
				),
				'footer_menu'	=> array(
					'label'		=> __( 'Footer Menu', 'wpex' ),
					'target'	=> '#footer-bottom-menu',
				),
				'load_custom_font_1'	=> array(
					'label'				=> __( 'Load Custom Font', 'wpex' ),
					'settings'			=> array( 'font-family' ),
				),
			);
			return $array;
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Register Typography Panel and Sections
		/*-----------------------------------------------------------------------------------*/
		public function register ( $wp_customize ) {

			// Get enabled customizer panels
			$enabled_panels = array( 'typography' => true );
			$enabled_panels	= get_option( 'wpex_customizer_panels', $enabled_panels );
			if ( empty( $enabled_panels['typography'] ) ) {
				return;
			}

			// Add General Panel
			$wp_customize->add_panel( 'wpex_typography', array(
				'priority'		=> 146,
				'capability'	=> 'edit_theme_options',
				'title'			=> __( 'Typography', 'wpex' ),
			) );

			// Get elements
			$elements = $this->elements();

			// Lopp through elements
			$count = '0';
			foreach( $elements as $element => $array ) {
				$count++;

				// Set vars
				$label = isset ( $array['label'] ) ? $array['label'] : '';
				if ( ! isset ( $array['settings'] ) ) {
					$settings = array(
						'font-family',
						'font-weight',
						'font-style',
						'text-transform',
						'font-size',
						'line-height',
						'letter-spacing',
					);
				} else {
					$settings = $array['settings'];
				}

				if ( $label ) {

					// Define Section
					$wp_customize->add_section( 'wpex_typography_'. $element , array(
						'title'		=> $label,
						'priority'	=> $count,
						'panel'		=> 'wpex_typography',
					) );

					// Font Family
					if ( in_array( 'font-family', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[font-family]', array(
							'type'		=> 'theme_mod',
							'transport'	=> 'refresh',
						) );
						$wp_customize->add_control(
							new WPEX_Fonts_Dropdown_Custom_Control(
								$wp_customize,
								$element .'_typography[font-family]',
								array(
									'label'			=> __( 'Font Family', 'wpex' ),
									'section'		=> 'wpex_typography_'. $element,
									'settings'		=> $element .'_typography[font-family]',
									'priority'		=> 1,
									'description'	=> __( 'To prevent bugs with the customizer make sure to change your family first before tweaking the design.', 'wpex' ),
								)
							)
						);
					}

					// Font Weight
					if ( in_array( 'font-weight', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[font-weight]', array(
							'type'			=> 'theme_mod',
							'transport'		=> 'postMessage',
							'description'	=> __( 'Note: Not all Fonts support every font weight style.', 'wpex' ),
						) );
						$wp_customize->add_control( $element .'_typography[font-weight]', array(
							'label'			=> __( 'Font Weight', 'wpex' ),
							'section'		=> 'wpex_typography_'. $element,
							'settings'		=> $element .'_typography[font-weight]',
							'priority'		=> 2,
							'type'			=> 'select',
							'choices'	=> array (
								''		=> __( 'Default', 'wpex' ),
								'300'	=> __( 'Book: 300', 'wpex' ),
								'400'	=> __( 'Normal: 400', 'wpex' ),
								'600'	=> __( 'Semibold: 600', 'wpex' ),
								'700'	=> __( 'Bold: 700', 'wpex' ),
								'800'	=> __( 'Extra Bold: 800', 'wpex' ),
							),
							'description'	=> __( 'Important: Not all fonts support every font-weight.', 'wpex' ),
						) );
					}

					// Font Style
					if ( in_array( 'font-style', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[font-style]', array(
							'type'		=> 'theme_mod',
							'transport'	=> 'postMessage',
						) );
						$wp_customize->add_control( $element .'_typography[font-style]', array(
							'label'		=> __( 'Font Style', 'wpex' ),
							'section'	=> 'wpex_typography_'. $element,
							'settings'	=> $element .'_typography[font-style]',
							'priority'	=> 3,
							'type'		=> 'select',
							'choices'	=> array (
								''			=> __( 'Default', 'wpex' ),
								'normal'	=> __( 'Normal', 'wpex' ),
								'italic'	=> __( 'Italic', 'wpex' ),
							),
						) );
					}

					// Text-Transform
					if ( in_array( 'text-transform', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[text-transform]', array(
							'type'		=> 'theme_mod',
							'transport'	=> 'postMessage',
						) );
						$wp_customize->add_control( $element .'_typography[text-transform]', array(
							'label'		=> __( 'Text Transform', 'wpex' ),
							'section'	=> 'wpex_typography_'. $element,
							'settings'	=> $element .'_typography[text-transform]',
							'priority'	=> 4,
							'type'		=> 'select',
							'choices'	=> array (
								''				=> __( 'Default', 'wpex' ),
								'capitalize'	=> __( 'Capitalize', 'wpex' ),
								'lowercase'		=> __( 'Lowercase', 'wpex' ),
								'uppercase'		=> __( 'Uppercase', 'wpex' ),
							),
						) );
					}

					// Font Size
					if ( in_array( 'font-size', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[font-size]', array(
							'type'		=> 'theme_mod',
							'transport'	=> 'postMessage',
						) );
						$wp_customize->add_control( $element .'_typography[font-size]', array(
							'label'			=> __( 'Font Size', 'wpex' ),
							'section'		=> 'wpex_typography_'. $element,
							'settings'		=> $element .'_typography[font-size]',
							'priority'		=> 5,
							'type'			=> 'text',
							'description'	=> __( 'Value in pixels.', 'wpex' ),
						) );
					}

					// Font Color
					$wp_customize->add_setting( $element .'_typography[color]', array(
						'type'		=> 'theme_mod',
						'default'	=> '',
					) );
					$wp_customize->add_control(
						new WP_Customize_Color_Control(
							$wp_customize,
							$element .'_typography_color',
							array(
								'label'		=> __( 'Font Color', 'wpex' ),
								'section'	=> 'wpex_typography_'. $element,
								'settings'	=> $element .'_typography[color]',
								'priority'	=> 6,
							)
						)
					);

					// Line Height
					if ( in_array( 'line-height', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[line-height]', array(
							'type'		=> 'theme_mod',
							'transport'	=> 'postMessage',
						) );
						$wp_customize->add_control( $element .'_typography[line-height]',
							array(
								'label'		=> __( 'Line Height', 'wpex' ),
								'section'	=> 'wpex_typography_'. $element,
								'settings'	=> $element .'_typography[line-height]',
								'priority'	=> 7,
								'type'		=> 'text',
						) );
					}

					// Letter Spacing
					if ( in_array( 'letter-spacing', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[letter-spacing]', array(
							'type'		=> 'theme_mod',
							'transport'	=> 'postMessage',
						) );
						$wp_customize->add_control(
							new WPEX_Customize_Sliderui_Control(
								$wp_customize,
								$element .'_typography_letter_spacing',
								array(
									'label'		=> __( 'Letter Spacing', 'wpex' ),
									'section'	=> 'wpex_typography_'. $element,
									'settings'	=> $element .'_typography[letter-spacing]',
									'priority'	=> 8,
									'type'		=> 'wpex_slider_ui',
									'choices'	=> array(
										'min'	=> 0,
										'max'	=> 20,
										'step'	=> 1,
									),
								)
							)
						);
					}

				}
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Reset Cache after customizer save
		/*-----------------------------------------------------------------------------------*/
		public function reset_cache() {
			remove_theme_mod( 'wpex_customizer_typography_cache' );
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Output Custom CSS
		/*-----------------------------------------------------------------------------------*/
		public function loop( $return = 'css' ) {
			// Get typography data cache
			$data = get_theme_mod( 'wpex_customizer_typography_cache', false );
			// If theme mod cache empty or is live customizer loop through elements and set output
			if ( empty( $data ) || is_customize_preview() ) {
				// Define Vars
				$css			= '';
				$load_scripts	= '';
				$fonts			= array();
				$scripts		= array();
				$scripts_output = '';
				$elements		= $this->elements();
				// Loop through each elements that need typography styling applied to them
				foreach( $elements as $element => $array ) {
					// Attributes to loop through
					if ( ! empty( $array['settings'] ) ) {
						$attributes = $array['settings'];
					} else {
						$attributes = array( 'font-family', 'font-weight', 'font-style', 'font-size', 'color', 'line-height', 'letter-spacing', 'text-transform' );
					}
					$add_css	= '';
					$target		= isset( $array['target'] ) ? $array['target'] : '';
					$get_mod	= get_theme_mod( $element .'_typography' );
					foreach ( $attributes as $attribute ) {
						$val = isset ( $get_mod[$attribute] ) ? $get_mod[$attribute] : '';
						if ( $val ) {
							// Convert font-size to px
							if ( 'font-size' == $attribute || 'letter-spacing' == $attribute ) {
								$val = intval( $get_mod[$attribute] ) .'px';
							}
							// Add quotes around font-family && font family to scripts array
							if ( 'font-family' == $attribute ) {
								$fonts[]	= $val;
								$val		= $val;
							}
							// Add custom CSS
							$add_css .= $attribute .':'. $val .';';
						}
					}
					if ( $add_css ) {
						$css .= $target .'{'. $add_css .'}';
					} 
				}
				if ( $css || $fonts ) {
					// Only load 1 of each font
					if ( ! empty( $fonts ) ) {
						array_unique( $fonts );
					}
					// Get Google Scripts to load on the front end
					if ( ! empty ( $fonts ) ) {
						$google_fonts	= wpex_google_fonts_array();
						// Loop through fonts and create Google Font Link
						foreach ( $fonts as $font ) {
							if ( in_array( $font, $google_fonts ) ) {
								$scripts[] = 'https://fonts.googleapis.com/css?family='.str_replace(' ', '%20', $font ) .'';
							}
						}
						// If scripts need to be loaded create the link tags
						if ( ! empty( $scripts ) ) {
							$scripts_output = '<!-- Load Google Fonts -->';
							foreach ( $scripts as $script ) {
								$scripts_output .= '<link href="'. $script .':300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic" rel="stylesheet" type="text/css">';
							}
						}
					}
				}
			}
			// Set cache or get cache if not in customizer
			if ( ! is_customize_preview() ) {
				// Get Cache vars
				if ( $data ) {
					$css			= isset( $data['css'] ) ? $data['css'] : '';
					$fonts			= isset( $data['fonts'] ) ? $data['fonts'] : '';
					$scripts		= isset( $data['scripts'] ) ? $data['scripts'] : '';
					$scripts_output	= isset( $data['scripts_output'] ) ? $data['scripts_output'] : '';
				}
				// Set Cache
				else {
					set_theme_mod( 'wpex_customizer_typography_cache', array (
						'css'				=> $css,
						'fonts'				=> $fonts,
						'scripts'			=> $scripts,
						'scripts_output'	=> $scripts_output,
					) );
				}
			}
			// Return CSS
			if ( 'css' == $return && $css ) {
				$css = '<!-- Typography CSS --><style type="text/css">'. $css .'</style>';
				return $css;
			}
			// Return Fonts Array
			if ( 'fonts' == $return && ! empty( $fonts ) ) {
				return $fonts;
			}
			// Return Scripts Array
			if ( 'scripts' == $return && ! empty( $scripts ) ) {
				return $scripts;
			}
			// Return Scripts Output
			if ( 'scripts_output' == $return && $scripts_output ) {
				return $scripts_output;
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Output Custom CSS
		/*-----------------------------------------------------------------------------------*/
		public function output_css() {
			echo $this->loop( 'css' );
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Load Google Fonts
		/*-----------------------------------------------------------------------------------*/
		public function load_fonts() {
			echo $this->loop( 'scripts_output' );
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Add Loaded Fonts to TinyMCE dropdown
		/*-----------------------------------------------------------------------------------*/
		public function mce_fonts( $initArray ) {
			$fonts = $this->loop( 'fonts' );
			$fonts_array = array();
			if ( is_array( $fonts ) && ! empty( $fonts ) ) {
				foreach ( $fonts as $font ) {
					$fonts_array[] = $font .'=' . $font;
				}
				$fonts = implode( ';', $fonts_array );
				// Add Fonts To MCE
				if ( $fonts ) {
					$initArray['font_formats'] = $fonts .';Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
				}
			}
			return $initArray;
		}

		/*-----------------------------------------------------------------------------------*/
		/*	-  Adds loaded Google fonts scripts to the backend for use in the editor
		/*-----------------------------------------------------------------------------------*/
		public function mce_scripts() {
			$scripts = $this->loop( 'scripts' );
			if ( ! empty( $scripts ) && is_array( $scripts ) ) {
				foreach ( $scripts as $script ) {
					add_editor_style( str_replace( ',', '%2C', $script .':300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic' ) );
				}
			}
		}
	}
}
new WPEX_Theme_Customizer_Typography();