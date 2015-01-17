<?php
/**
 * Neat Skin Class
 *
 * @package     Total
 * @subpackage  Skins
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.3.0
 */


if ( ! class_exists( 'Total_Neat_Skin' ) ) {
    
    class Total_Neat_Skin {

        /**
         * Main constructor
         *
         * @since Total 1.3.0
         */
        function __construct() {
            add_action( 'wp_enqueue_scripts', array( &$this, 'load_styles' ), 11 );
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
                'neat-skin',                                           	// Handle
                WPEX_SKIN_DIR_URI .'classes/neat/css/neat-style.css',	// Stylesheet URL
                array( 'wpex-style' ),                                  // Dependencies
                '1.0',                                                  // Version number
                'all'                                                   // Media
            );
        }

    }

}
new Total_Neat_Skin();