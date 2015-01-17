<?php
/**
 * Custom Sidebars
 *
 * @package		Total
 * @subpackage	Framework/Addons
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Custom_Sidebars' ) ) {
	class WPEX_Custom_Sidebars {

		/**
		 * Array of enabled widget_areas
		 *
		 * @since 1.6.0
		 */
		protected $widget_areas	= array();
		protected $orig			= array();

		/**
		 * Start things up
		 */
		public function __construct( $widget_areas = array() ) {
			add_action( 'init', array( $this, 'register_sidebars' ) , 1000 );
			add_action( 'admin_print_scripts', array( $this, 'add_widget_box' ) );
			add_action( 'load-widgets.php', array( $this, 'add_widget_area' ), 100 );
			add_action( 'load-widgets.php', array( $this, 'scripts' ), 100 );
			add_action( 'wp_ajax_wpex_delete_widget_area', array( $this, 'wpex_delete_widget_area' ) ); 
		}

		/**
		 * Add the widget box inside a script
		 *
		 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
		 */
		public function add_widget_box() {
			$nonce = wp_create_nonce ( 'delete-wpex-widget_area-nonce' ); ?>
			  <script type="text/html" id="wpex-add-widget-template">
				<div id="wpex-add-widget" class="widgets-holder-wrap">
				 <div class="">
				  <input type="hidden" name="wpex-nonce" value="<?php echo $nonce ?>" />
				  <div class="sidebar-name">
				   <h3><?php echo __( 'Create Widget Area', 'wpex' ); ?> <span class="spinner"></span></h3>
				  </div>
				  <div class="sidebar-description">
					<form id="addWidgetAreaForm" action="" method="post">
					  <div class="widget-content">
						<input id="wpex-add-widget-input" name="wpex-add-widget-input" type="text" class="regular-text" title="<?php echo __( 'Name', 'wpex' ); ?>" placeholder="<?php echo __( 'Name', 'wpex' ); ?>" />
					  </div>
					  <div class="widget-control-actions">
						<div class="aligncenter">
						  <input class="addWidgetArea-button button-primary" type="submit" value="<?php echo __('Create Widget Area', 'wpex'); ?>" />
						</div>
						<br class="clear">
					  </div>
					</form>
				  </div>
				 </div>
				</div>
			  </script>
			<?php
		}        


		/**
		 * Create new Widget Area
		 *
		 * @since 1.6.0
		 */
		public function add_widget_area() {
			if ( ! empty( $_POST['wpex-add-widget-input'] ) ) {
				$this->widget_areas = $this->get_widget_areas();
				array_push( $this->widget_areas, $this->check_widget_area_name( $_POST['wpex-add-widget-input'] ) );
				$this->save_widget_areas();
				wp_redirect( admin_url( 'widgets.php' ) );
				die();
			}
		}

		/**
		 * Before we create a new widget_area, verify it doesn't already exist. If it does, append a number to the name.
		 *
		 * @since 1.6.0
		 */
		public function check_widget_area_name( $name ) {
			if ( empty( $GLOBALS['wp_registered_widget_areas'] ) ) {
				return $name;
			}

			$taken = array();
			foreach ( $GLOBALS['wp_registered_widget_areas'] as $widget_area ) {
				$taken[] = $widget_area['name'];
			}

			$taken = array_merge( $taken, $this->widget_areas );

			if ( in_array( $name, $taken ) ) {
				$counter  = substr($name, -1);  
				$new_name = "";
				  
				if ( !is_numeric( $counter ) ) {
					$new_name = $name . " 1";
				} else {
					$new_name = substr($name, 0, -1) . ((int) $counter + 1);
				}

				$name = $this->check_widget_area_name($new_name);
			}
			echo $name;
			exit();
			return $name;
		}

		public function save_widget_areas() {
			set_theme_mod( 'widget_areas', array_unique( $this->widget_areas ) );
		}

		/**
		 * Register and display the custom widget_area areas we have set.
		 *
		 * @since 1.6.0
		 */
		public function register_sidebars() {

			// Get widget areas
			if ( empty( $this->widget_areas ) ) {
				$this->widget_areas = $this->get_widget_areas();
			}

			// Original widget areas is empty
			$this->orig = array();

			// Save widget areas
			if ( ! empty( $this->orig ) && $this->orig != $this->widget_areas ) {
				$this->widget_areas = array_unique( array_merge( $this->widget_areas, $this->orig ) );
				$this->save_widget_areas();
			}

			// Get tag element from theme mod for the sidebar widget title
			$tag = get_theme_mod( 'sidebar_headings', 'div' ) ? get_theme_mod( 'sidebar_headings', 'div' ) : 'div';
				 
			// If widget areas are defined add a sidebar area for each
			if ( is_array( $this->widget_areas ) ) {
				foreach ( array_unique( $this->widget_areas ) as $widget_area ) {
					$args = array(
						'id'			=> sanitize_key( $widget_area ),
						'name'			=> $widget_area,
						'class'			=> 'wpex-custom',
						'before_widget'	=> '<div class="sidebar-box %2$s clr">',
						'after_widget'	=> '</div>',
						'before_title'	=> '<'. $tag .' class="widget-title">',
						'after_title'	=> '</'. $tag .'>',
					);
					register_sidebar( $args );
				}
			}
		}

		/**
		 * Return the widget_areas array.
		 *
		 * @since 1.6.0
		 */
		public function get_widget_areas() {

			// If the single instance hasn't been set, set it now.
			if ( ! empty( $this->widget_areas ) ) {
				return $this->widget_areas;
			}

			// Get widget areas saved in theem mod
			$widget_areas = get_theme_mod( 'widget_areas' );

			// If theme mod isn't empty set to class widget area var
			if ( ! empty( $widget_areas ) ) {
				$this->widget_areas = array_unique( array_merge( $this->widget_areas, $widget_areas ) );
			}

			// Return widget areas
			return $this->widget_areas;
		}

		/**
		 * Before we create a new widget_area, verify it doesn't already exist. If it does, append a number to the name.
		 *
		 * @since 1.6.0
		 */
		public function wpex_delete_widget_area() {
			// Check_ajax_referer('delete-wpex-widget_area-nonce');
			if ( ! empty( $_REQUEST['name'] ) ) {
				$name = strip_tags( ( stripslashes( $_REQUEST['name'] ) ) );
				$this->widget_areas = $this->get_widget_areas();
				$key = array_search($name, $this->widget_areas );
				if ( $key >= 0 ) {
					unset($this->widget_areas[$key]);
					$this->save_widget_areas();
				}
				echo "widget_area-deleted";
			}
			die();
		}

		/**
		 * Enqueue CSS/JS for the customizer controls
		 *
		 * @since 1.6.0
		 */
		public function scripts(){

			// Define assets directory
			$assets_dir = WPEX_FRAMEWORK_DIR_URI .'addons/assets/widget-areas/';

			// Load scripts
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_script(
				'wpex-widget_areas-js',
				$assets_dir.'widget_areas.js', 
				array('jquery'),
				time(),
				true
			);
			wp_enqueue_style(
				'wpex-widget_areas-css',
				$assets_dir .'widget_areas.css', 
				time(),
				true
			);
			$widgets = array();
			if ( ! empty( $this->widget_areas ) ) {
				foreach ( $this->widget_areas as $widget ) {
					$widgets[$widget] = 1;
				}
			}

			// Localize script
			wp_localize_script(
				'wpex-widget_areas-js',
				'wpexWidgetAreasLocalize',
				array(
					'count'		=> count( $this->orig ),
					'delete'	=> __( 'Delete', 'wpex' ),
					'confirm'	=> __( 'Confirm', 'wpex' ),
					'cancel'	=> __( 'Cancel', 'wpex' ),
				)
			);
		}
	
	}
}
new WPEX_Custom_Sidebars();