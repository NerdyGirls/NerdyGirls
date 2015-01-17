<?php
/**
 * This file is used for all the styling options in the admin
 * All custom color options are output to the <head> tag
 *
 * @package     Total
 * @subpackage  Framework
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.0.0
 * @version     1.0.1
 */

if ( ! class_exists( 'WPEX_Responsive_Widths_CSS' ) ) {
    
    class WPEX_Responsive_Widths_CSS {

        /**
         * Variable used to check if we are in the admin or the front-end of the site
         *
         * @since   1.6.3
         * @var     $is_admin
         * @access  private
         * @return  bool
         */
        private $cached_css = false;

        /**
         * Main constructor
         *
         * @since Total 1.6.3
         */
        function __construct() {
            if ( ! is_customize_preview() ) {
                $this->cached_css = get_theme_mod( 'responsive_layouts_css', false );
            }
            add_action( 'customize_save_after', array( $this, 'reset_cache' ) );
            add_filter( 'wpex_head_css', array( $this, 'get_css' ), 999 );
        }

        /**
         * Resets the cache so it can be re-generated
         *
         * @since Total 1.6.3
         */
        public function reset_cache() {
            remove_theme_mod( 'responsive_layouts_css' );
        }

        /**
         * Retrieves cached CSS or generates the responsive CSS
         *
         * @since Total 1.6.3
         */
        public function get_css( $output ) {

            // If cached CSS exists and it's not the live customizer return cached CSS
            if ( $this->cached_css ) {
                $output .= '/*RESPONSIVE WIDTHS*/'. $this->cached_css;
                return $output;
            }

            // Vars
            $css = $add_css = '';

            // Get active skin
            if ( function_exists( 'wpex_active_skin' ) ) {
                $active_skin = wpex_active_skin();
            } else {
                $active_skin = 'base';
            }

            // Get current layout
            $main_layout = wpex_main_layout();

            /*-----------------------------------------------------------------------------------*/
            /*  - Desktop Width
            /*-----------------------------------------------------------------------------------*/
            
            // Main Container With
            if ( $width = get_theme_mod( 'main_container_width', false ) ) {
                if ( 'boxed' == $main_layout || 'gaps' == $active_skin ) {
                    $add_css .= '.boxed-main-layout #wrap{
                                    width:'. $width .';
                                    max-width:none;
                                }';
                } else {
                    $add_css .= '.container,
                                .vc_row-fluid.container {
                                    width: '. $width .' !important;
                                    max-width:none;
                                }';
                }
            }
            
            // Left container width
            if ( $width = get_theme_mod( 'left_container_width', false ) ) {
                $add_css .= '.content-area{
                                width:'. $width .';
                                max-width:none;
                            }';
            }

            // Sidebar width
            if ( $width = get_theme_mod( 'sidebar_width', false ) ) {
                $add_css .= '#sidebar{
                                width: '. $width .';
                                max-width:none;
                            }';
            }

            // Add to $css var
            if ( $add_css ) {
                $css .= '@media only screen and (min-width: 1281px){
                            '. $add_css .'
                        }';
                $add_css = '';
            }


            /*-----------------------------------------------------------------------------------*/
            /*  - Tablet Landscape & Small Screen Widths
            /*-----------------------------------------------------------------------------------*/

            // Main Container With
            if ( $width = get_theme_mod( 'tablet_landscape_main_container_width', false ) ) {
                if ( 'boxed' == $main_layout || 'gaps' == $active_skin ) {
                    $add_css .= '.boxed-main-layout #wrap{
                                    width:'. $width .';
                                    max-width:none;
                                }';
                } else {
                    $add_css .= '.container,
                                .vc_row-fluid.container {
                                    width: '. $width .' !important;
                                    max-width:none;
                                }';
                }
            }

            // Left container width
            if ( $width = get_theme_mod( 'tablet_landscape_left_container_width', false ) ) {
                $add_css .= '.content-area{
                                width:'. $width .';
                                max-width:none;
                            }';
            }

            // Sidebar width
            if ( $width = get_theme_mod( 'tablet_landscape_sidebar_width', false )  ) {
                $add_css .= '#sidebar{
                                width: '. $width .';
                                max-width:none;
                            }';
            }

            // Add to $css var
            if ( $add_css ) {
                $css .= '@media only screen and (min-width: 960px) and (max-width: 1280px) {
                            '. $add_css .'
                        }';
                $add_css = '';
            }
            

            /*-----------------------------------------------------------------------------------*/
            /*  - Tablet Widths
            /*-----------------------------------------------------------------------------------*/

            // Main Container With
            if ( $width = get_theme_mod( 'tablet_main_container_width', false ) ) {
                if ( 'boxed' == $main_layout || 'gaps' == $active_skin ) {
                    $add_css .= '.boxed-main-layout #wrap{
                                    width:'. $width .';
                                    max-width:none;
                                }';
                } else {
                    $add_css .= '.container,
                                .vc_row-fluid.container {
                                    width: '. $width .' !important;
                                    max-width:none;
                                }';
                }
            }

            // Left container width
            if ( $width = get_theme_mod( 'tablet_left_container_width', false ) ) {
                $add_css .= '.content-area{
                                width:'. $width .';
                            }';
            }

            // Sidebar width
            if ( $width = get_theme_mod( 'tablet_sidebar_width', false ) ) {
                $add_css .= '#sidebar{
                                width: '. $width .';
                            }';
            }

            // Add to $css var
            if ( $add_css ) {
                $css .= '@media only screen and (min-width: 768px) and (max-width: 959px){
                            '. $add_css .'
                        }';
                $add_css = '';
            }

            /*-----------------------------------------------------------------------------------*/
            /*  - Phone Widths
            /*-----------------------------------------------------------------------------------*/
            
            // Phone Portrait
            if ( $width = get_theme_mod( 'mobile_portrait_main_container_width', false ) ) {
                $css .= '@media only screen and (max-width: 767px) {
                            .container {
                                width: '. $width .' !important; min-width: 0;
                            }
                        }';
            }
            
            // Phone Landscape
            if ( $width = get_theme_mod( 'mobile_landscape_main_container_width', false ) ) {
                $css .= '@media only screen and (min-width: 480px) and (max-width: 767px) {
                            .container {
                                width: '. $width .' !important;
                            }
                        }';
            }
        
            // Return custom CSS
            if ( ! empty( $css ) ) {
                $css = '/*RESPONSIVE WIDTHS*/'. $css;
                $output .= $css;
            }

            // Cache result
            set_theme_mod( 'responsive_layouts_css', $css );

            // Return output css
            return $output;

        }

    }

}
new WPEX_Responsive_Widths_CSS();