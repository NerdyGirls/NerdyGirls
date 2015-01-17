<?php
/**
 * Skin loader function & helpers
 *
 * @package     Total
 * @subpackage  Skins
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.5.4
 */

/**
 * Skins Loader Class
 *
 * @since Total 1.6.3
 */
if ( ! class_exists( 'WPEX_Skin_Loader' ) ) {
    class WPEX_Skin_Loader {

        /**
         * Returns the current skin name
         *
         * @since   1.6.3
         * @var     $current_skin
         * @access  private
         * @return  string
         */
        private $current_skin = 'base';

        /**
         * Start things up
         *
         * @since 1.6.3
         */
        function __construct() {
            
            // Get the current skin
            $current_skin = $this->current_skin();

            // Load skin if needed
            if ( $current_skin && 'base' != $current_skin ) {
                $this->load_skin( $current_skin );
            }

            // Include skins admin panel functions
            require_once( WPEX_SKIN_DIR . 'admin/skins-admin.php' );

        }

        /**
         * Array of available skins
         *
         * @since 1.6.3
         */
        public function skins_array() {

            $skins = array(
                'base'  => array (
                    'core'          => true,
                    'name'          => __( 'Base', 'wpex' ),
                    'screenshot'    => WPEX_SKIN_DIR_URI .'classes/base/screenshot.jpg',
                ),
                'agent' => array(
                    'core'          => true,
                    'name'          => __( 'Agent', 'wpex' ),
                    'class'         => WPEX_SKIN_DIR .'classes/agent/agent-skin.php',
                    'screenshot'    => WPEX_SKIN_DIR_URI .'classes/agent/screenshot.jpg',
                ),
                'neat'  => array(
                    'core'          => true,
                    'name'          => __( 'Neat', 'wpex' ),
                    'class'         => WPEX_SKIN_DIR .'classes/neat/neat-skin.php',
                    'screenshot'    => WPEX_SKIN_DIR_URI .'classes/neat/screenshot.jpg',
                ),
                'flat'  => array(
                    'core'          => true,
                    'name'          => __( 'Flat', 'wpex' ),
                    'class'         => WPEX_SKIN_DIR .'classes/flat/flat-skin.php',
                    'screenshot'    => WPEX_SKIN_DIR_URI .'classes/flat/screenshot.jpg',
                ),
                'gaps'  => array(
                    'core'          => true,
                    'name'          => __( 'Gaps', 'wpex' ),
                    'class'         => WPEX_SKIN_DIR .'classes/gaps/gaps-skin.php',
                    'screenshot'    => WPEX_SKIN_DIR_URI .'classes/gaps/screenshot.jpg',
                ),
                'minimal-graphical' => array(
                    'core'          => true,
                    'name'          => __( 'Minimal Graphical', 'wpex' ),
                    'class'         => WPEX_SKIN_DIR .'classes/minimal-graphical/minimal-graphical-skin.php',
                    'screenshot'    => WPEX_SKIN_DIR_URI .'classes/minimal-graphical/screenshot.jpg',
                ),
            );

            // Add filter so you can create more skins via child themes or plugins
            $skins = apply_filters( 'wpex_skins', $skins );

            // Return skins
            return $skins;
            
        }

        /**
         * Returns the current skin
         *
         * @since 1.6.3
         */
        public function current_skin() {

            // Get skin from theme mod
            $skin = get_theme_mod( 'theme_skin', 'base' );

            // Sanitize
            if ( ! $skin ) {
                $skin = 'base';
            }

            // Return current skin
            return $skin;
            
        }

        /**
         * Returns the correct class file for the current skin
         *
         * @since 1.6.3
         */
        public function current_skin_file( $active_skin ) {

            // Nothing needed for the base skin or an empty skin
            if ( 'base' == $active_skin || ! $active_skin ) {
                return;
            }

            // Get currect skin class to load later
            $skins              = $this->skins_array();
            $active_skin_array  = wp_array_slice_assoc( $skins, array( $active_skin ) );
            if ( is_array( $active_skin_array ) ) {
                $is_core    = ! empty( $active_skin_array[$active_skin]['core'] ) ? true : false;
                $class_file = ! empty( $active_skin_array[$active_skin]['class'] ) ? $active_skin_array[$active_skin]['class'] : false;
            }

            // Return class file if one exists
            if ( $is_core && $class_file ) {
                return $class_file;
            }
            
        }


        /**
         * Load the active skin
         *
         * @since 1.6.3
         */
        public function load_skin( $current_skin ) {

            // Get skin file
            $file = $this->current_skin_file( $current_skin );

            // Load the file if it exists
            if ( $file ) {
                require_once( $file );
            }
            
        }


    }

}
new WPEX_Skin_Loader();

/**
 * Helper function that returns skins array
 *
 * @since Total 1.6.3
 */
if ( ! function_exists( 'wpex_skins' ) ) {
    function wpex_skins() {
        $class = new WPEX_Skin_Loader();
        return $class->skins_array();
    }
}

/**
 * Helper function that returns active skin name
 *
 * @since Total 1.6.3
 */
if ( ! function_exists( 'wpex_active_skin' ) ) {
    function wpex_active_skin() {
        $class = new WPEX_Skin_Loader();
        return $class->current_skin();
    }
}