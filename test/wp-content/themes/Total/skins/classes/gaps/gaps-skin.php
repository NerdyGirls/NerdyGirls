<?php
/**
 * Gaps Skin Class
 *
 * @package     Total
 * @subpackage  Skins
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.3.0
 */

if ( ! class_exists( 'Total_Gaps_Skin' ) ) {
    
    class Total_Gaps_Skin {

        /**
         * Main constructor
         *
         * @since Total 1.3.0
         */
        function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ), 11 );
            add_action( 'body_class', array( $this, 'body_classes' ) );
        }

        /**
         * Load custom stylesheet for this skin
         *
         * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
         * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_style
         * @since   Total 1.3.0
         */
        public function load_styles() {
            wp_enqueue_style(
                'gaps-skin',                                            // Handle
                WPEX_SKIN_DIR_URI .'classes/gaps/css/gaps-style.css',   // Stylesheet URL
                array( 'wpex-style' ),                                  // Dependencies
                '1.0',                                                  // Version number
                'all'                                                   // Media
            );
        }

        /**
         * Add boxed layout class to the body classes
         *
         * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
         * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_style
         * @since   Total 1.3.0
         */
        public function body_classes( $classes ) {
                
            $classes[] = 'boxed-main-layout';

            return $classes;

        }

    }

}
new Total_Gaps_Skin();

/**
 * Override core functions
 *
 * @since Total 1.5.0
 */

// Remove nav from the header
if ( ! function_exists( 'wpex_hook_header_bottom_default' ) ) {
    function wpex_hook_header_bottom_default() {
        return false;
    }
}

// Add menu for header styles 2 or 3 before the main content
if ( ! function_exists( 'wpex_hook_main_before_default' ) ) {
    function wpex_hook_main_before_default() {
        $header_style = wpex_get_header_style();
        if ( $header_style == 'two' || $header_style == 'three' ) {
            // Above menu slider
            if ( 'above_menu' == wpex_post_slider_position() ) {
                wpex_post_slider();
            }
            wpex_header_menu();
        }
    }
}
add_action( 'wpex_hook_main_before', 'wpex_hook_main_before_default' );