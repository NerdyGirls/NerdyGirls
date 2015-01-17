<?php
/**
 * Loads all functions for the Visual Composer
 *
 * @package     Total
 * @subpackage  Framework/Visual Composer
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.6.0
 * @version     1.0.1
 */

// Retun if the Visual Composer plugin isn't active
if ( ! WPEX_VC_ACTIVE ) {
    return;
}

// Define paths
define( 'WPEX_VCEX_DIR', get_template_directory() .'/framework/visual-composer/extend/' );
define( 'WPEX_VCEX_DIR_URI', get_template_directory_uri() .'/framework/visual-composer/extend/' );

// WPEX Visual Composer Class used to tweak VC functions and defaults
if ( ! class_exists( 'WPEX_Visual_Composer' ) ) {
    class WPEX_Visual_Composer {

        /**
         * This variable tells the class whether we should make alterations to the Visual Composer or not
         *
         * @since 1.6.0
         */
        private $is_edit_vc_enabled = true;

        /**
         * Start things up
         *
         * @since 1.6.0
         */
        public function __construct() {

            // Apply filters so you can disable modifications via a child theme
            $this->is_edit_vc_enabled = apply_filters( 'wpex_edit_visual_composer', $this->is_edit_vc_enabled );

            // Remove elements
            $this->remove_elements();

            // Extend the Visual Composer if enabled
            if ( get_theme_mod( 'extend_visual_composer', true ) ) {
                require_once( WPEX_VCEX_DIR .'extend.php' );
            }

            // Start things up
            $this->init();

            // Admin Init
            add_action( 'admin_init', array( $this, 'admin_init' ) );

            // Include custom Shortcodes
            add_action( 'vc_before_init', array( $this, 'map_shortcodes' ) );

            // Tweak scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

            // Enque scripts for the admin
            add_action( 'admin_enqueue_scripts',  array( $this, 'admin_scripts' ) );

            // Display notice if the Visual Composer Extension plugin is still enabled
            if ( function_exists( 'visual_composer_extension_run' ) ) {
                add_action( 'admin_notices', array( $this, 'remove_vc_extension_notice' ) );
            }

            // Remove metaboxes
            add_action( 'do_meta_boxes', array( $this, 'remove_metaboxes' ) );

            // Alter default post types
            if ( function_exists( 'vc_set_default_editor_post_types' ) ) {
                vc_set_default_editor_post_types( array( 'page', 'portfolio', 'staff' ) );
            }

        }

        /**
         * Run on init
         *
         * @since 1.6.0
         */
        public function init() {
            
            // Set the visual composer to run in theme mod
            if ( function_exists( 'vc_set_as_theme' ) && get_theme_mod( 'visual_composer_theme_mode', true ) ) {
                vc_set_as_theme( $disable_updater = true );
            }

        }

        /**
         * Run on admin-init
         *
         * @since 1.6.0
         */
        public function admin_init() {
            
            // Remove parameters
            $this->remove_params();

            // Add Params
            if ( $this->is_edit_vc_enabled && function_exists( 'vc_add_param' ) ) {
                require_once( WPEX_VCEX_DIR .'add-params.php' );
            }

            // Add new shortcode params
            $this->add_shortcode_params();

        }

        /**
         * Map custom shortcodes
         *
         * @since 1.6.0
         */
        public function map_shortcodes() {
            
            // Do nothing yet

        }

        /**
         * Scripts
         *
         * @since 1.6.0
         */
        public function scripts() {
            
            // Remove scripts while in the customizer to prevent the bug with the jQuery UI
            if ( is_customize_preview() ) {
                wp_deregister_script( 'wpb_composer_front_js' );
                wp_dequeue_script( 'wpb_composer_front_js' );
            }

        }

        /**
         * Admin Scripts
         *
         * @since 1.6.0
         */
        public function admin_scripts() {
            
            // Make sure we can edit the visual composer
            if ( ! $this->is_edit_vc_enabled ) {
                return;
            }

            // Load custom admin scripts
            wp_enqueue_style( 'vcex-admin-css', WPEX_VCEX_DIR_URI .'assets/admin.css' );

        }

        /**
         * Display notice if the Visual Composer Extension plugin is still enabled
         *
         * @since 1.6.0
         */
        public function remove_vc_extension_notice() { ?>
            <div class="error">
                <h4><?php _e( 'IMPORTANT NOTICE', 'wpex' ); ?></h4>
                <p><?php _e( 'The Visual Composer Extension Plugin (not WPBakery VC but the extension created by WPExplorer) for this theme is now built-in, please de-activate and if you want delete the plugin.', 'wpex' ); ?>
                <br /><br />
                <a href="<?php echo admin_url( 'plugins.php?plugin_status=active' ); ?>" class="button button-primary" target="_blank"><?php _e( 'Deactivate', 'wpex' ); ?> &rarr;</a></p>
                <p></p>
            </div>
        <?php }

        /**
         * Remove metaboxes
         *
         * @link    http://codex.wordpress.org/Function_Reference/do_meta_boxes
         * @since   1.6.0
         */
        public function remove_metaboxes() {

            // Make sure we can edit the visual composer
            if ( ! $this->is_edit_vc_enabled ) {
                return;
            }

            // Loop through post types and remove params
            $post_types = get_post_types( '', 'names' ); 
            foreach ( $post_types as $post_type ) {
                remove_meta_box( 'vc_teaser',  $post_type, 'side' );
            }

        }

        /**
         * Remove modules
         *
         * @link http://kb.wpbakery.com/index.php?title=Vc_remove_element
         * @since 1.6.0
         */
        public function remove_elements() {

            // Make sure we can edit the visual composer
            if ( ! $this->is_edit_vc_enabled ) {
                return;
            }

            // Array of elements to remove
            $elements = array(
                'vc_teaser_grid',
                'vc_posts_grid',
                'vc_posts_slider',
                'vc_carousel',
                'vc_wp_tagcloud',
                'vc_wp_archives',
                'vc_wp_calendar',
                'vc_wp_pages',
                'vc_wp_links',
                'vc_wp_posts',
                'vc_separator',
                'vc_gallery',
                'vc_wp_categories',
                'vc_wp_rss',
                'vc_wp_text',
                'vc_wp_meta',
                'vc_wp_recentcomments',
                'vc_images_carousel',
                'layerslider_vc'
            );

            // Add filter for child theme tweaking
            $elements = apply_filters( 'wpex_vc_remove_elements', $elements );

            // Loop through and remove default Visual Composer Elements until fully tested and they work well
            foreach ( $elements as $element ) {
                vc_remove_element( $element );
            }

        }

        /**
         * Remove params
         *
         * @link    http://kb.wpbakery.com/index.php?title=Vc_remove_param
         * @since   1.6.0
         */
        public function remove_params() {

            // Make sure we can edit the visual composer
            if ( ! $this->is_edit_vc_enabled ) {
                return;
            }

            // Array of params to remove
            $params = array(

                // Rows
                'vc_row'            => array(
                    'font_color',
                    'padding',
                    'bg_color',
                    'bg_image',
                    'bg_image_repeat',
                    'margin_bottom',
                    'css',
                ),

                // Row Inner
                'vc_row_inner'      => array(
                    'css',
                ),

                // Single Image
                'vc_single_image'   => array(
                    'alignment'
                ),

                // Seperator w/ Text
                'vc_text_separator' => array(
                    'color',
                    'el_width',
                    'accent_color',
                ),

                // Columns
                'vc_column'         => array(
                    'css',
                    'font_color',
                ),

                // Column Inner
                'vc_column_inner'   => array(
                    'css',
                ),

            );

            // Add filter for child theme tweaking
            $params = apply_filters( 'wpex_vc_remove_params', $params );

            // Loop through and remove default Visual Composer params
            foreach ( $params as $key => $val ) {
                if ( ! is_array( $val ) ) {
                    return;
                }
                foreach ( $val as $remove_param ) {
                    vc_remove_param( $key, $remove_param );
                }
            }

        }

        /**
         * Remove params
         *
         * @link    http://kb.wpbakery.com/index.php?title=Vc_remove_param
         * @since   1.6.0
         */
        public function add_shortcode_params() {

            // Return nothing if function doesn't exist
            if ( ! function_exists( 'add_shortcode_param' ) ) {
                return;
            }

            // Add custom Font Awesome icon param
            add_shortcode_param(
                'vcex_icon',
                array( $this, 'font_awesome_icon_param' ),
                WPEX_VCEX_DIR_URI .'assets/icon-type.js'
            );

        }

        /**
         * Custom Font Awesome Icons param
         *
         * @link    http://kb.wpbakery.com/index.php?title=Vc_remove_param
         * @since   1.6.0
         */
        public function font_awesome_icon_param( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $return = '<div class="my_param_block">
                <div class="vcex-font-awesome-icon-preview"></div>
                <input placeholder="' . __( "Type in an icon name or select one from below", 'wpex' ) . '" name="' . $settings['param_name'] . '"'
            . ' data-param-name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'].' '.$settings['type'].'_field" type="text" value="'. $value .'" ' . $dependency . ' style="width: 100%; vertical-align: top; margin-bottom: 10px" />';
            $return .= '<div class="vcex-font-awesome-icon-select-window">
                        <span class="fa fa-times" style="color:red;" data-name="clear"></span>';
                            $icons = wpex_get_awesome_icons();
                            foreach ( $icons as $icon ) {
                                if ( '' != $icon ) {
                                    if ( $value == $icon ) {
                                        $active = 'active';
                                    } else {
                                        $active = '';
                                    }
                                    $return .= '<span class="fa fa-'. $icon .' '. $active .'" data-name="'. $icon .'"></span>';
                                }
                            }
            $return .= '</div></div><div style="clear:both;"></div>';
            return $return;
        }

    }
}

// Start up class
new WPEX_Visual_Composer();