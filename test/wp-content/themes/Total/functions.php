<?php
/**
 * Total functions and definitions.
 * Text Domain: wpex
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * Total is a very powerful theme and virtually anything can be customized
 * via a child theme. If you need any help altering a function, just let us know!
 * Customizations aren't included for free but if it's a simple task I'll be sure to help :)
 * 
 * @package     Total
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.0.0
 */

/*-----------------------------------------------------------------------------------*/
/*  - Define Constants
/*-----------------------------------------------------------------------------------*/

// Assets Paths
define( 'WPEX_JS_DIR_URI', get_template_directory_uri() .'/js/' );
define( 'WPEX_CSS_DIR_UIR', get_template_directory_uri() .'/css/' );

// Skins Paths
define( 'WPEX_SKIN_DIR', get_template_directory() .'/skins/' );
define( 'WPEX_SKIN_DIR_URI', get_template_directory_uri() .'/skins/' );

// Framework Paths
define( 'WPEX_FRAMEWORK_DIR', get_template_directory() .'/framework/' );
define( 'WPEX_FRAMEWORK_DIR_URI', get_template_directory_uri() .'/framework/' );

// Admin Panel Hook
define( 'WPEX_ADMIN_PANEL_HOOK_PREFIX', 'theme-panel_page_' );

// Check if plugins are active
define( 'WPEX_VC_ACTIVE', class_exists( 'Vc_Manager' ) );
define( 'WPEX_BBPRESS_ACTIVE', class_exists( 'bbPress' ) );
define( 'WPEX_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
define( 'WPEX_REV_SLIDER_ACTIVE', class_exists( 'RevSlider' ) );
define( 'WPEX_LAYERSLIDER_ACTIVE', function_exists( 'lsSliders' ) );

// Active post types
define( 'WPEX_PORTFOLIO_IS_ACTIVE', get_theme_mod( 'portfolio_enable', true ) );
define( 'WPEX_STAFF_IS_ACTIVE', get_theme_mod( 'staff_enable', true ) );
define( 'WPEX_TESTIMONIALS_IS_ACTIVE', get_theme_mod( 'testimonials_enable', true ) );

// Define branding constant based on theme options
define( 'WPEX_THEME_BRANDING', get_theme_mod( 'theme_branding', 'Total' ) );

/**
 * Defines the site content width
 *
 * @since Total 1.0.0
 */
if ( ! isset( $content_width ) ) {
    $content_width = 980;
}

/*-----------------------------------------------------------------------------------*
/*  - Main Theme Setup Class
/*  - Perform basic setup, registration, and init actions for the theme
/*  - Loads all theme Classes and functions
/*  - Loads all core back-end and front-end scripts
/*  - Makes any necessary alterations to core filters
/*-----------------------------------------------------------------------------------*/

/**
 * Main Theme Class
 *
 * @since Total 1.6.0
 */
class WPEX_Theme_Setup {

    /**
     * Variable used to check if we are in the admin or the front-end of the site
     *
     * @since   1.6.3
     * @var     $is_admin
     * @access  private
     * @return  bool
     */
    private $is_admin = false;

    /**
     * Checks if responsiveness is enabled for the theme
     *
     * @since   1.6.3
     * @var     $is_responsive_enabled
     * @access  private
     * @return  bool
     */
    private $is_responsive_enabled = true;

    /**
     * Checks the defined lightbox skin for this theme
     *
     * @since   1.6.3
     * @var     $lightbox_skin
     * @access  private
     * @return  string
     */
    private $lightbox_skin = 'dark';

    /**
     * Start things up
     *
     * @since 1.6.0
     */
    public function __construct() {

        // Setup class variables
        $this->is_admin                 = is_admin();
        $this->is_responsive_enabled    = get_theme_mod( 'responsive', true );
        $this->lightbox_skin            = get_theme_mod( 'lightbox_skin', 'dark' );

        // Sanitize variables
        if ( ! $this->lightbox_skin ) {
            $this->lightbox_skin = 'dark';
        }

        // Apply filters to variables
        $this->lightbox_skin = apply_filters( 'wpex_lightbox_skin', $this->lightbox_skin );

        // Run class functions
        add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
        add_action( 'init', array( $this, 'include_files' ), 0 );
        add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ) );
        add_action( 'wp_head', array( $this, 'meta_viewport' ), 1 );
        add_action( 'wp_head', array( $this, 'wp_head' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
        add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
        add_action( 'wpex_gallery_metabox_dir_uri', array( $this, 'gallery_metabox_dir_uri' ) );
        add_filter( 'widget_tag_cloud_args', array( $this, 'widget_tag_cloud_args' ) );
        add_filter( 'wp_list_categories', array( $this, 'wp_list_categories_args' ) );
        add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
        add_filter( 'user_contactmethods', array( $this, 'user_fields' ) );
        add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ), 99, 4 );
        add_filter( 'style_loader_src', array( $this, 'remove_scripts_version' ), 9999 );
        add_filter( 'script_loader_src', array( $this, 'remove_scripts_version' ), 9999 );
        add_filter( 'the_excerpt', 'shortcode_unautop');
        add_filter( 'the_excerpt', 'do_shortcode');
        add_filter( 'wp_get_attachment_url', array( $this, 'honor_ssl_for_attachments' ) );

    }

    /**
     * Functions called during each page load, after the theme is initialized
     * Perform basic setup, registration, and init actions for the theme
     *
     * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
     * @since   1.6.0
     */
    public function after_setup_theme() {

        // Register navigation menus
        register_nav_menus (
            array(
                'main_menu'         => __( 'Main', 'wpex' ),
                'mobile_menu'       => __( 'Mobile Icons', 'wpex' ),
                'mobile_menu_alt'   => __( 'Mobile Menu Alternative', 'wpex' ),
                'footer_menu'       => __( 'Footer', 'wpex' ),
            )
        );

        // Load text domain
        load_theme_textdomain( 'wpex', get_template_directory() .'/languages' );

        // Enable some useful post formats for the blog
        add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote', 'link' ) );
        
        // Add automatic feed links in the header - for themecheck nagg
        add_theme_support( 'automatic-feed-links' );
        
        // Enable featured image support
        add_theme_support( 'post-thumbnails' );
        
        // And HTML5 support
        add_theme_support( 'html5' );
        
        // Enable excerpts for pages.
        add_post_type_support( 'page', 'excerpt' );
        
        // Add support for WooCommerce - Yay!
        add_theme_support( 'woocommerce' );

        // Add styles to the WP editor
        add_editor_style( 'css/editor-style.css' );

        // Title tag
        add_theme_support( 'title-tag' );

    }

    /**
     * Include theme functions and classes
     *
     * @since 1.0.0
     */
    public function include_files() {

        // Migration function
        $this->auto_updates();
        $this->theme_addons();
        $this->theme_functions();
        $this->custom_widgets();
        $this->portfolio_functions();
        $this->staff_functions();
        $this->testimonials_functions();
        $this->woocommerce_functions();
        $this->theme_classes();

    }

    /**
     * This function never runs, but it does prevent some theme-check nags.
     *
     * @since 1.6.0
     */
    private function stop_nagging_me() {
        add_theme_support( 'custom-header' );
    }

    /**
     * Functions called after theme switch
     *
     * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/after_switch_theme
     * @since   1.6.0
     */
    public function after_switch_theme() {
        flush_rewrite_rules();
    }

    /**
     * Adds the meta tag to the site header
     *
     * @since 1.6.0
     */
    public function meta_viewport() {

        /**
         * Meta Viewport
         *
         * @since 1.6.0
         */

        // Responsive viewport viewport
        if ( $this->is_responsive_enabled ) {
            $viewport = '<meta name="viewport" content="width=device-width, initial-scale=1">';
        }

        // Non responsive meta viewport
        else {
            $viewport = '<meta name="viewport" content="width='. intval( get_theme_mod( 'main_container_width', '980' ) ) .'" />';
        }
        
        // Apply filters to the meta viewport for child theme tweaking
        echo apply_filters( 'wpex_meta_viewport', $viewport );

    }

    /**
     * Hooks functions to the wp_head hook
     *
     * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
     * @since   1.6.0
     */
    public function wp_head() {
        $this->ie8_css();
        $this->html5_shiv();
        $this->custom_css();
        $this->tracking();
    }

    /**
     * Load scripts in the WP admin
     *
     * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
     * @since   1.6.0
     */
    public function admin_scripts() {

        // Load FontAwesome for use with the Visual Composer backend editor and the Total metabox
        wp_enqueue_style( 'wpex-font-awesome', WPEX_CSS_DIR_UIR .'font-awesome.min.css' );

    }

    /**
     * Hooks functions to wp_enqueue_scripts to load scrips on the front-end 
     *
     * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
     * @since   1.6.0
     */
    public function wp_enqueue_scripts() {
        $this->theme_css();
        $this->theme_js();
    }

    /**
     * Returns all CSS needed for the front-end
     *
     * @since 1.6.0
     */
    public function theme_css() {
        
        /**
         * Loads all required CSS for the theme
         */

        // Load Visual composer CSS first so it's easier to override
        if ( WPEX_VC_ACTIVE ) {
            wp_enqueue_style( 'js_composer_front' );
        }

        // Font Awesome First
        wp_enqueue_style( 'wpex-font-awesome', WPEX_CSS_DIR_UIR .'font-awesome.min.css' );

        // Main Style.css File
        wp_enqueue_style( 'wpex-style', get_stylesheet_uri() );

        // Visual Composer CSS
        if ( WPEX_VC_ACTIVE ) {
            wp_enqueue_style( 'wpex-visual-composer', WPEX_CSS_DIR_UIR .'visual-composer-custom.css', array( 'js_composer_front' ) );
            wp_enqueue_style( 'wpex-visual-composer-extend', WPEX_CSS_DIR_UIR .'visual-composer-extend.css' );
        }

        // WooCommerce CSS
        if ( WPEX_WOOCOMMERCE_ACTIVE ) {
            wp_enqueue_style( 'wpex-woocommerce', WPEX_CSS_DIR_UIR .'woocommerce.css' );
        }

        // BBPress CSS
        if ( WPEX_BBPRESS_ACTIVE && is_bbpress() ) {
            wp_enqueue_style( 'wpex-bbpress', WPEX_CSS_DIR_UIR .'bbpress-edits.css', array( 'bbp-default' ) );
        }

        // Responsive CSS
        if ( $this->is_responsive_enabled && ! wpex_is_front_end_composer() ) {
            wp_enqueue_style( 'wpex-responsive', WPEX_CSS_DIR_UIR .'responsive.css', array( 'wpex-style' ) );
        }

        // Ligthbox skin
        wp_enqueue_style( 'wpex-lightbox-skin', WPEX_CSS_DIR_UIR .'lightbox/'. $this->lightbox_skin .'-skin/skin.css', array( 'wpex-style' ) );

        // Remove unwanted scripts
        wp_deregister_style( 'js_composer_custom_css' );

    }

    /**
     * Returns all js needed for the front-end
     *
     * @since 1.6.0
     */
    public function theme_js() {

        // jQuery main script
        wp_enqueue_script( 'jquery' );

        // Retina.js
        if ( get_theme_mod( 'retina', false ) ) {
            wp_enqueue_script( 'retina', WPEX_JS_DIR_URI .'plugins/retina.js', array( 'jquery' ), '0.0.2', true );
        }

        // Comment reply
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        // Mobile menu style
        if ( $this->is_responsive_enabled ) {
            $mobile_menu_style  = get_theme_mod( 'mobile_menu_style', 'sidr' );
            $mobile_menu_style  = $mobile_menu_style ? $mobile_menu_style : 'sidr'; // should never be empty
        } else {
             $mobile_menu_style = 'disabled';
        }

        // Localize array
        $localize_array = array(
            'mobileMenuStyle'       => $mobile_menu_style,
            'sidrSource'            => wpex_mobile_menu_source(),
            'lightboxSkin'          => $this->lightbox_skin,
            'lightboxArrows'        => get_theme_mod( 'lightbox_arrows', true ),
            'lightboxThumbnails'    => get_theme_mod( 'lightbox_thumbnails', true ),
            'lightboxFullScreen'    => get_theme_mod( 'lightbox_fullscreen', true ),
            'lightboxMouseWheel'    => get_theme_mod( 'lightbox_mousewheel', true ),
            'lightboxTitles'        => get_theme_mod( 'lightbox_titles', true ),
            'sidrSide'              => get_theme_mod( 'mobile_menu_sidr_direction', 'left' ),
            'isRTL'                 => is_rtl(),
            'stickyOnMobile'        => get_theme_mod( 'fixed_header_mobile', false ),
        );

        $localize_array = apply_filters( 'wpex_localize_array', $localize_array );

        // Load minified global scripts
        if ( get_theme_mod( 'minify_js', true ) ) {

            // Load super minified total js
            wp_enqueue_script( 'total-min', WPEX_JS_DIR_URI .'total-min.js', array( 'jquery' ), '5.13', true );

            // Localize
            wp_localize_script( 'total-min', 'wpexLocalize', $localize_array );

        }
        
        // Load all non-minified js
        else {
            // Core plugins
            wp_enqueue_script( 'wpex-superfish', WPEX_JS_DIR_URI .'plugins/superfish.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-supersubs', WPEX_JS_DIR_URI .'plugins/supersubs.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-hoverintent', WPEX_JS_DIR_URI .'plugins/hoverintent.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-sticky', WPEX_JS_DIR_URI .'plugins/sticky.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-tipsy', WPEX_JS_DIR_URI .'plugins/tipsy.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-waypoints', WPEX_JS_DIR_URI .'plugins/waypoints.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-scrollto', WPEX_JS_DIR_URI .'plugins/scrollto.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-images-loaded', WPEX_JS_DIR_URI .'plugins/images-loaded.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-isotope', WPEX_JS_DIR_URI .'plugins/isotope.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-leanner-modal', WPEX_JS_DIR_URI .'plugins/leanner-modal.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-infinite-scroll', WPEX_JS_DIR_URI .'plugins/infinite-scroll.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-flexslider', WPEX_JS_DIR_URI .'plugins/flexslider.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-touch-swipe', WPEX_JS_DIR_URI .'plugins/touch-swipe.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-count-to', WPEX_JS_DIR_URI .'plugins/count-to.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-owl-carousel', WPEX_JS_DIR_URI .'plugins/owl.carousel.min.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-appear', WPEX_JS_DIR_URI .'plugins/appear.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-sidr', WPEX_JS_DIR_URI .'plugins/sidr.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-custom-select', WPEX_JS_DIR_URI .'plugins/custom-select.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-scrolly', WPEX_JS_DIR_URI .'plugins/scrolly.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-match-height', WPEX_JS_DIR_URI .'plugins/match-height.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-mousewheel', WPEX_JS_DIR_URI .'plugins/jquery.mousewheel.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-request-animation', WPEX_JS_DIR_URI .'plugins/jquery.requestAnimationFrame.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-ilightbox', WPEX_JS_DIR_URI .'plugins/ilightbox.min.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'wpex-global', WPEX_JS_DIR_URI .'global.js', array( 'jquery' ), '', true );
            wp_localize_script( 'wpex-global', 'wpexLocalize', $localize_array );
        }

        // Remove scripts
        wp_dequeue_script( 'flexslider' );
        wp_deregister_script( 'flexslider' );

    }

    /**
     * Remove version numbers from scripts URL
     *
     * @link    https://developer.wordpress.org/reference/hooks/style_loader_src/
     * @link    https://developer.wordpress.org/reference/hooks/script_loader_src/ 
     * @since   1.6.0
     */
    public function remove_scripts_version( $src ) {
        if ( get_theme_mod( 'remove_scripts_version', true ) && strpos( $src, 'ver=' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        return $src;
    }

    /**
     * Adds CSS for ie8
     * Applies the wpex_ie_8_url filter so you can alter your IE8 stylesheet URL
     *
     * @since 1.6.0
     */
    public function ie8_css() {
        $ie_8_url   = WPEX_CSS_DIR_UIR .'ie8.css';
        $ie_8_url   = apply_filters( 'wpex_ie_8_url', $ie_8_url );
        echo '<!--[if IE 8]><link rel="stylesheet" type="text/css" href="'. $ie_8_url .'" media="screen"><![endif]-->';
    }

    /**
     * Load HTML5 dependencies for IE8
     *
     * @link    https://github.com/aFarkas/html5shiv
     * @since   1.6.0
     */
    public function html5_shiv() {
        echo '<!--[if lt IE 9]>
            <script src="'. WPEX_JS_DIR_URI .'plugins/html5.js"></script>
        <![endif]-->';
    }

    /**
     * Outputs tracking code in the header
     *
     * @since 1.6.0
     */
    public function tracking() {
        if ( $tracking = get_theme_mod( 'tracking' ) ) {
            echo $tracking;
        }
    }

    /**
     * Registers the theme sidebars (widget areas)
     *
     * @link    http://codex.wordpress.org/Function_Reference/register_sidebar
     * @since   1.6.0
     */
    public function register_sidebars() {

        // Heading element type
        $sidebar_headings   = get_theme_mod( 'sidebar_headings', 'div' );
        $footer_headings    = get_theme_mod( 'footer_headings', 'div' );

        // Sanitize just incase to prevent errors
        if ( ! $sidebar_headings ) {
            $sidebar_headings = 'div';
        }
        if ( ! $footer_headings ) {
            $footer_headings = 'div';
        }

        // Main Sidebar
        register_sidebar( array (
            'name'          => __( 'Main Sidebar', 'wpex' ),
            'id'            => 'sidebar',
            'description'   => __( 'Widgets in this area are used in the default sidebar. This sidebar will be used for your standard blog posts.', 'wpex' ),
            'before_widget' => '<div class="sidebar-box %2$s clr">',
            'after_widget'  => '</div>',
            'before_title'  => '<'. $sidebar_headings .' class="widget-title">',
            'after_title'   => '</'. $sidebar_headings .'>',
        ) );

        // Pages Sidebar
        if ( get_theme_mod( 'pages_custom_sidebar', true ) ) {
            register_sidebar( array (
                'name'          => __( 'Pages Sidebar', 'wpex' ),
                'id'            => 'pages_sidebar',
                'before_widget' => '<div class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<'. $sidebar_headings .' class="widget-title">',
                'after_title'   => '</'. $sidebar_headings .'>',
            ) );
        }

        // Search Results Sidebar
        if ( get_theme_mod( 'search_custom_sidebar', true ) ) {
            register_sidebar( array (
                'name'          => __( 'Search Results Sidebar', 'wpex' ),
                'id'            => 'search_sidebar',
                'before_widget' => '<div class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<'. $sidebar_headings .' class="widget-title">',
                'after_title'   => '</'. $sidebar_headings .'>',
            ) );
        }

        // Portfolio Sidebar
        if ( post_type_exists( 'portfolio' ) && get_theme_mod( 'portfolio_custom_sidebar', true ) ) {
            $obj            = get_post_type_object( 'portfolio' );
            $post_type_name = $obj->labels->name;
            register_sidebar( array (
                'name'          => $post_type_name .' '. __( 'Sidebar', 'wpex' ),
                'id'            => 'portfolio_sidebar',
                'before_widget' => '<div class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<'. $sidebar_headings .' class="widget-title">',
                'after_title'   => '</'. $sidebar_headings .'>',
            ) );
        }

        // Staff Sidebar
        if ( post_type_exists( 'staff' ) && get_theme_mod( 'staff_custom_sidebar', true ) ) {
            $obj            = get_post_type_object( 'staff' );
            $post_type_name = $obj->labels->name;
            register_sidebar( array (
                'name'          => $post_type_name .' '. __( 'Sidebar', 'wpex' ),
                'id'            => 'staff_sidebar',
                'before_widget' => '<div class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<'. $sidebar_headings .' class="widget-title">',
                'after_title'   => '</'. $sidebar_headings .'>',
            ) );
        }

        // Testimonials Sidebar
        if ( post_type_exists( 'testimonials' ) && get_theme_mod( 'testimonials_custom_sidebar', true ) ) {
            $obj            = get_post_type_object( 'testimonials' );
            $post_type_name = $obj->labels->name;
            register_sidebar( array (
                'name'          => $post_type_name .' '. __( 'Sidebar', 'wpex' ),
                'id'            => 'testimonials_sidebar',
                'before_widget' => '<div class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<'. $sidebar_headings .' class="widget-title">',
                'after_title'   => '</'. $sidebar_headings .'>',
            ) );
        }

        // WooCommerce Sidebar
        if ( WPEX_WOOCOMMERCE_ACTIVE && get_theme_mod( 'woo_custom_sidebar', true ) ) {
            register_sidebar( array (
                'name'          => __( 'WooCommerce Sidebar', 'wpex' ),
                'id'            => 'woo_sidebar',
                'before_widget' => '<div class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<'. $sidebar_headings .' class="widget-title">',
                'after_title'   => '</'. $sidebar_headings .'>',
            ) );
        }

        // bbPress Sidebar
        if ( WPEX_BBPRESS_ACTIVE && get_theme_mod( 'bbpress_custom_sidebar', true ) ) {
            register_sidebar( array (
                'name'          => __( 'bbPress Sidebar', 'wpex' ),
                'id'            => 'bbpress_sidebar',
                'before_widget' => '<div class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<'. $sidebar_headings .' class="widget-title">',
                'after_title'   => '</'. $sidebar_headings .'>',
            ) );
        }

        // Footer Sidebars
        if ( get_theme_mod( 'widgetized_footer', true ) ) {

            // Footer widget columns
            $footer_columns = get_theme_mod( 'footer_widgets_columns', '4' );
            
            // Footer 1
            register_sidebar( array (
                'name'          => __( 'Footer 1', 'wpex' ),
                'id'            => 'footer_one',
                'before_widget' => '<div class="footer-widget %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<'. $footer_headings .' class="widget-title">',
                'after_title'   => '</'. $footer_headings .'>',
            ) );
            
            // Footer 2
            if ( $footer_columns > '1' ) {
                register_sidebar( array (
                    'name'          => __( 'Footer 2', 'wpex' ),
                    'id'            => 'footer_two',
                    'before_widget' => '<div class="footer-widget %2$s clr">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<'. $footer_headings .' class="widget-title">',
                    'after_title'   => '</'. $footer_headings .'>'
                ) );
            }
            
            // Footer 3
            if ( $footer_columns > '2' ) {
                register_sidebar( array (
                    'name'          => __( 'Footer 3', 'wpex' ),
                    'id'            => 'footer_three',
                    'before_widget' => '<div class="footer-widget %2$s clr">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<'. $footer_headings .' class="widget-title">',
                    'after_title'   => '</'. $footer_headings .'>',
                ) );
            }
            
            // Footer 4
            if ( $footer_columns > '3' ) {
                register_sidebar( array (
                    'name'          => __( 'Footer 4', 'wpex' ),
                    'id'            => 'footer_four',
                    'before_widget' => '<div class="footer-widget %2$s clr">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<'. $footer_headings .' class="widget-title">',
                    'after_title'   => '</'. $footer_headings .'>',
                ) );
            }
        }

    }

    /**
     * Outputs custom CSS to the wp_head
     *
     * @since 1.6.3
     */
    public function gallery_metabox_dir_uri( $url ) {
        return WPEX_FRAMEWORK_DIR_URI .'classes/gallery-metabox/';
    }

    /**
     * All theme functions hook into the wpex_head_css filter for this function
     * so that all CSS is minified and outputted in one location in the site header.
     *
     * @since   1.6.0
     */
    public function custom_css( $output = NULL ) {

        // Add filter for adding custom css via other functions
        $output = apply_filters( 'wpex_head_css', $output );

        // Minify and output CSS in the wp_head
        if ( ! empty( $output ) ) {
            $output = wpex_minify_css( $output );
            $output = "<!-- TOTAL CSS -->\n<style type=\"text/css\">\n" . $output . "\n</style>";
            echo $output;
        }

    }

    /**
     * Alters the default WordPress tag cloud widget arguments
     * Makes sure all font sizes for the cloud widget are set to 1em
     *
     * @link    https://developer.wordpress.org/reference/hooks/widget_tag_cloud_args/
     * @since   1.6.0
     */
    public function widget_tag_cloud_args( $args ) {
        $args['largest']    = 1;
        $args['smallest']   = 1;
        $args['unit']       = 'em';
        return $args;
    }

    /**
     * Alter wp list categories arguments
     * Adds a span around the counter for easier styling
     *
     * @link    https://developer.wordpress.org/reference/functions/wp_list_categories/
     * @since   1.6.0
     */
    public function wp_list_categories_args( $links ) {
        $links  = str_replace( '</a> (', '</a> <span class="cat-count-span">(', $links );
        $links  = str_replace( ')', ')</span>', $links );
        return $links;
    }

    /**
     * This function runs before the main query
     *
     * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
     * @since   1.6.0
     */
    public function pre_get_posts() {

        // Exclude categories from the main blog
        if ( function_exists( 'wpex_blog_exclude_categories' ) ) {
            wpex_blog_exclude_categories( false );
        }

    }

    /**
     * Add new user fields
     *
     * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/user_contactmethods
     * @since   1.6.0
     */
    public function user_fields( $contactmethods ) {

        // Add Twitter
        if ( ! isset( $contactmethods['wpex_twitter'] ) ) {
            $contactmethods['wpex_twitter'] = WPEX_THEME_BRANDING .' - Twitter';
        }
        // Add Facebook
        if ( ! isset( $contactmethods['wpex_facebook'] ) ) {
            $contactmethods['wpex_facebook'] = WPEX_THEME_BRANDING .' - Facebook';
        }
        // Add GoglePlus
        if ( ! isset( $contactmethods['wpex_googleplus'] ) ) {
            $contactmethods['wpex_googleplus'] = WPEX_THEME_BRANDING .' - Google+';
        }
        // Add LinkedIn
        if ( ! isset( $contactmethods['wpex_linkedin'] ) ) {
            $contactmethods['wpex_linkedin'] = WPEX_THEME_BRANDING .' - LinkedIn';
        }
        // Add Pinterest
        if ( ! isset( $contactmethods['wpex_pinterest'] ) ) {
            $contactmethods['wpex_pinterest'] = WPEX_THEME_BRANDING .' - Pinterest';
        }
        // Add Pinterest
        if ( ! isset( $contactmethods['wpex_instagram'] ) ) {
            $contactmethods['wpex_instagram'] = WPEX_THEME_BRANDING .' - Instagram';
        }

        // Return contact methods
        return $contactmethods;

    }

    /**
     * Alters the default oembed output
     * Adds special classes for responsive oembeds via CSS
     *
     * @link    https://developer.wordpress.org/reference/hooks/embed_oembed_html/
     * @since   1.6.0
     */
    public function embed_oembed_html( $html, $url, $attr, $post_id ) {
        return '<div class="responsive-video-wrap entry-video">' . $html . '</div>';
    }

    /**
     * The wp_get_attachment_url() function doesn't distinguish whether a page request arrives via HTTP or HTTPS.
     * Using wp_get_attachment_url filter, we can fix this to avoid the dreaded mixed content browser warning
     *
     * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_get_attachment_url
     * @since   1.6.0
     */
    public function honor_ssl_for_attachments( $url ) {
        $http       = site_url( FALSE, 'http' );
        $https      = site_url( FALSE, 'https' );
        $isSecure   = false;
        if ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) {
            $isSecure = true;
        }
        if ( $isSecure ) {
            return str_replace( $http, $https, $url );
        } else {
            return $url;
        }
    }


    /**
     * Automatic updates
     *
     * @since 1.6.3
     */
    private function auto_updates() {
        if ( get_theme_mod( 'envato_license_key', false ) ) {
            require_once(  WPEX_FRAMEWORK_DIR .'classes/auto-updates/wp-updates-theme.php');
            new WPUpdatesThemeUpdater_479( 'http://wp-updates.com/api/2/theme', basename( get_template_directory() ), get_theme_mod( 'envato_license_key' ) );
        }
    }

    /**
     * Theme addons
     *
     * @since 1.6.3
     */
    private function theme_addons() {

        require_once( WPEX_FRAMEWORK_DIR .'addons/tweaks.php' );

        if ( get_theme_mod( 'favicons_enable', true ) ) {
            require_once( WPEX_FRAMEWORK_DIR .'addons/favicons.php' );
        }

        // Custom 404
        if ( get_theme_mod( 'custom_404_enable', true ) ) {
            require_once( WPEX_FRAMEWORK_DIR .'addons/custom-404.php' );
        }

        // Custom widget areas
        if ( get_theme_mod( 'widget_areas_enable', true ) ) {
            require_once( WPEX_FRAMEWORK_DIR .'addons/widget-areas.php' );
        }

        // Custom Login
        if ( get_theme_mod( 'custom_admin_login_enable', true ) ) {
            require_once( WPEX_FRAMEWORK_DIR .'addons/custom-login.php' );
        }

        // Custom CSS
        if ( get_theme_mod( 'custom_css_enable', true ) ) {
            require_once( WPEX_FRAMEWORK_DIR .'addons/custom-css.php' );
        }

        // Skins
        if ( get_theme_mod( 'skins_enable', true ) ) {
            require_once( WPEX_SKIN_DIR . 'skins.php' );
        }

        // Customizer
        require_once( WPEX_FRAMEWORK_DIR .'customizer/customizer.php' );
        //require_once( WPEX_FRAMEWORK_DIR .'helpers/customizer-js-generator.php' );

        // Import Export Functions
        if ( $this->is_admin ) {
            require_once( WPEX_FRAMEWORK_DIR .'addons/import-export.php' );
        }

    }

    /**
     * Theme functions
     *
     * @since 1.6.3
     */
    private function theme_functions() {

        // Core functions need to be added first
        require_once( WPEX_FRAMEWORK_DIR .'deprecated.php' );
        require_once( WPEX_FRAMEWORK_DIR .'core-functions.php' );
        require_once( WPEX_FRAMEWORK_DIR .'conditionals.php' );
        require_once( WPEX_FRAMEWORK_DIR .'arrays.php' );
        require_once( WPEX_FRAMEWORK_DIR .'fonts.php' );
        require_once( WPEX_FRAMEWORK_DIR .'elements.php' );
        require_once( WPEX_FRAMEWORK_DIR .'overlays.php' );
        require_once( WPEX_FRAMEWORK_DIR .'recommend-plugins.php' );

        // Hooks
        require_once( WPEX_FRAMEWORK_DIR .'hooks/hooks.php' );
        require_once( WPEX_FRAMEWORK_DIR .'hooks/actions.php' );

        // Meta
        require_once( WPEX_FRAMEWORK_DIR .'meta/post-meta/class.php');
        require_once( WPEX_FRAMEWORK_DIR .'meta/taxonomies/category-meta.php');

        // Advanced styles
        require_once( WPEX_FRAMEWORK_DIR .'design/advanced-styling.php' );
        require_once( WPEX_FRAMEWORK_DIR .'design/layout.php' );
        require_once( WPEX_FRAMEWORK_DIR .'design/backgrounds.php' );

        // Other core functions that should be added last
        require_once( WPEX_FRAMEWORK_DIR .'body-classes.php' );
        require_once( WPEX_FRAMEWORK_DIR .'togglebar.php' );
        require_once( WPEX_FRAMEWORK_DIR .'topbar.php' );
        require_once( WPEX_FRAMEWORK_DIR .'header-functions.php' );
        require_once( WPEX_FRAMEWORK_DIR .'search-functions.php' );
        require_once( WPEX_FRAMEWORK_DIR .'page-header.php' );
        require_once( WPEX_FRAMEWORK_DIR .'menu-functions.php' );
        require_once( WPEX_FRAMEWORK_DIR .'shortcodes/shortcodes.php' );
        require_once( WPEX_FRAMEWORK_DIR .'thumbnails/media-fields.php' );
        require_once( WPEX_FRAMEWORK_DIR .'post-layout.php' );
        require_once( WPEX_FRAMEWORK_DIR .'excerpts.php' );
        require_once( WPEX_FRAMEWORK_DIR .'tinymce.php' );
        require_once( WPEX_FRAMEWORK_DIR .'thumbnails/dashboard-thumbnails.php' );
        require_once( WPEX_FRAMEWORK_DIR .'thumbnails/featured-images.php');
        require_once( WPEX_FRAMEWORK_DIR .'thumbnails/featured-image-caption.php');
        require_once( WPEX_FRAMEWORK_DIR .'blog/blog-functions.php' );
        require_once( WPEX_FRAMEWORK_DIR .'footer/footer-functions.php' );
        require_once( WPEX_FRAMEWORK_DIR .'comments-callback.php');
        require_once( WPEX_FRAMEWORK_DIR .'post-slider.php' );
        require_once( WPEX_FRAMEWORK_DIR .'social-share.php' );
        require_once( WPEX_FRAMEWORK_DIR .'bbpress/bbpress-search.php' );
        require_once( WPEX_FRAMEWORK_DIR .'posts-per-page.php' );
        require_once( WPEX_FRAMEWORK_DIR .'password-protection-form.php' );
        require_once( WPEX_FRAMEWORK_DIR .'pagination.php' );
        require_once( WPEX_FRAMEWORK_DIR .'blog/register-post-series.php' );
        require_once( WPEX_FRAMEWORK_DIR .'remove-posttype-slugs.php' );

        // Visual composer tweaks
        require_once( WPEX_FRAMEWORK_DIR .'visual-composer/visual-composer.php' );

        // Translation plugin tweaks
        require_once( WPEX_FRAMEWORK_DIR .'translators/wpml.php' );
        require_once( WPEX_FRAMEWORK_DIR .'translators/polylang.php' );

    }

    /**
     * Theme Classes
     *
     * @since 1.6.3
     */
    private function theme_classes() {

        require_once( WPEX_FRAMEWORK_DIR .'classes/migrate/migrate.php' );
        require_once( WPEX_FRAMEWORK_DIR .'classes/tgm-plugin-activation/class-tgm-plugin-activation.php' );
        require_once( WPEX_FRAMEWORK_DIR .'classes/faster-menu-dashboard/faster-menu-dashboard.php' );
        require_once( WPEX_FRAMEWORK_DIR .'classes/breadcrumbs/breadcrumbs.php' );
        require_once( WPEX_FRAMEWORK_DIR .'classes/image-resize/image-resize.php' );
        require_once( WPEX_FRAMEWORK_DIR .'classes/gallery-metabox/gallery-metabox.php' );
        require_once( WPEX_FRAMEWORK_DIR .'classes/custom-wp-gallery/custom-wp-gallery.php' );

    }

    /**
     * Custom Widgets
     *
     * @since 1.6.3
     */
    private function custom_widgets() {

        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-social.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-social-fontawesome.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-modern-menu.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-simple-menu.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-flickr.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-video.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-posts-thumbnails.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-recent-posts-thumb-grid.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-posts-icons.php' );
        require_once( WPEX_FRAMEWORK_DIR . 'widgets/widget-comments-avatar.php' );

    }

    /**
     * Portfolio functions
     *
     * @since 1.6.3
     */
    private function portfolio_functions() {

        if ( WPEX_PORTFOLIO_IS_ACTIVE ) {
            require_once( WPEX_FRAMEWORK_DIR .'portfolio/portfolio-editor.php' );
            require_once( WPEX_FRAMEWORK_DIR .'portfolio/portfolio-register.php' );
            require_once( WPEX_FRAMEWORK_DIR .'portfolio/portfolio-functions.php' );
            require_once( WPEX_FRAMEWORK_DIR .'portfolio/portfolio-categories.php' );
            require_once( WPEX_FRAMEWORK_DIR .'portfolio/portfolio-entry.php' );

        }

    }

    /**
     * Staff functions
     *
     * @since 1.6.3
     */
    private function staff_functions() {

        if ( WPEX_STAFF_IS_ACTIVE ) {
            require_once( WPEX_FRAMEWORK_DIR .'staff/staff-editor.php' );
            require_once( WPEX_FRAMEWORK_DIR .'staff/staff-register.php' );
        }

        require_once( WPEX_FRAMEWORK_DIR .'staff/staff-functions.php' );

    }


    /**
     * Testimonials functions
     *
     * @since 1.6.3
     */
    private function testimonials_functions() {

        if ( WPEX_TESTIMONIALS_IS_ACTIVE ) {
            require_once( WPEX_FRAMEWORK_DIR .'testimonials/testimonials-editor.php' );
            require_once( WPEX_FRAMEWORK_DIR .'testimonials/testimonials-register.php' ); 
        }

    }

    /**
     * WooCommerce functions
     *
     * @since 1.6.3
     */
    private function woocommerce_functions() {

       if ( WPEX_WOOCOMMERCE_ACTIVE ) {

            // WooCommerce core tweaks
            require_once( WPEX_FRAMEWORK_DIR .'woocommerce/woocommerce-tweaks-class.php' );

            // WooCommerce menu icon and functions
            if ( get_theme_mod( 'woo_menu_icon', true ) ) {
                require_once( WPEX_FRAMEWORK_DIR .'woocommerce/woo-menucart.php' );
                require_once( WPEX_FRAMEWORK_DIR .'woocommerce/woo-cartwidget-overlay.php' );
                require_once( WPEX_FRAMEWORK_DIR .'woocommerce/woo-cartwidget-dropdown.php' );
            }

        }

    }

}

/**
 * Run the theme setup class
 *
 * @since 1.6.3
 */
$wpex_theme_setup = new WPEX_Theme_Setup;