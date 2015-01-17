<?php
/**
 * Light Skin Class
 *
 * @package Total
 * @author Alexander Clarke
 * @copyright Copyright (c) 2014, Symple Workz LLC
 * @link http://www.wpexplorer.com
 * @since Total 1.51
 */

if ( !class_exists( "Total_Light_Skin" ) ) {

	class Total_Light_Skin {

		// Constructor
		function __construct() {
			add_action( 'wp_enqueue_scripts', array( &$this, 'load_styles' ), 11 );
		}

		// Load Styles
		public function load_styles() {
			wp_enqueue_style( 'light-skin', WPEX_SKIN_DIR_URI .'classes/light/css/light-style.css', array( 'wpex-style' ), '1.0', 'all' );
		}

	}

}
$wpex_skin_class = new Total_Light_Skin();