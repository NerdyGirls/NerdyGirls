<?php
/**
 * Used for renaming the Staff post type
 *
 * @package		Total
 * @subpackage	Framework/Staff
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
if ( ! class_exists( 'WPEX_Staff_Editor' ) ) {
	class WPEX_Staff_Editor {

		/**
		 * Start things up
		 */
		public function __construct() {
			
			// Add the page to the admin menu
			add_action( 'admin_menu', array( $this, 'add_page' ) );

			// Register page options
			add_action( 'admin_init', array( $this,'register_page_options' ) );

			// Display notices on form submission
			add_action( 'admin_notices', array( $this, 'notices' ) );

			// Filter the post type arguments with custom values
			add_action( 'wpex_staff_args', array( $this,'posttype_args' ) );

			// Filter the post type category arguments with custom values
			add_action( 'wpex_taxonomy_staff_category_args', array( $this,'cat_args' ) );

			// Filter the post type tag arguments with custom values
			add_action( 'wpex_taxonomy_staff_tag_args', array( $this,'tag_args' ) );

		}

		/**
		 * Add sub menu page for the custom CSS input
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_page
		 */
		function add_page() {
			add_submenu_page(
				'edit.php?post_type=staff',
				__( 'Post Type Editor', 'wpex' ),
				__( 'Post Type Editor', 'wpex' ),
				'administrator',
				'wpex-staff-editor',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Function that will register admin page options.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_setting
		 */
		function register_page_options() {
			register_setting( 'wpex_staff_options', 'wpex_staff_branding', array( $this, 'sanitize' ) );
		}

		/**
		 * Displays all messages registered to 'wpex-custom_css-notices'
		 *
		 * @link http://codex.wordpress.org/Function_Reference/settings_errors
		 */
		function notices() {
			settings_errors( 'wpex_staff_editor_page_notices' );
		}

		/**
		 * Sanitization callback
		 */
		function sanitize( $options ) {

			// Save values to theme mod
			if ( ! empty ( $options ) ) {
				foreach( $options as $key => $value ) {
					set_theme_mod( $key, $value );
				}
			}

			// Add notice
			add_settings_error(
				'wpex_staff_editor_page_notices',
				esc_attr( 'settings_updated' ),
				__( 'Settings saved.', 'wpex' ),
				'updated'
			);

			// Lets delete the options as we are saving them into theme mods
			$options = '';
			return $options;
		}

		/**
		 * Settings page output
		 */
		function create_admin_page() { ?>
			<div class="wrap">
				<h2><?php _e( 'Post Type Editor', 'wpex' ); ?></h2>
				<form method="post" action="options.php">
					<?php settings_fields( 'wpex_staff_options' ); ?>
					<p><?php _e( 'If you alter any slug\'s make sure to reset your permalinks to prevent 404 errors.', 'wpex' ); ?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e( 'Admin Icon', 'wpex' ); ?></th>
							<td>
								<?php
								// Dashicons select
								$dashicons = array('admin-appearance','admin-collapse','admin-comments','admin-generic','admin-home','admin-media','admin-network','admin-page','admin-plugins','admin-settings','admin-site','admin-tools','admin-users','align-center','align-left','align-none','align-right','analytics','arrow-down','arrow-down-alt','arrow-down-alt2','arrow-left','arrow-left-alt','arrow-left-alt2','arrow-right','arrow-right-alt','arrow-right-alt2','arrow-up','arrow-up-alt','arrow-up-alt2','art','awards','backup','book','book-alt','businessman','calendar','camera','cart','category','chart-area','chart-bar','chart-line','chart-pie','clock','cloud','dashboard','desktop','dismiss','download','edit','editor-aligncenter','editor-alignleft','editor-alignright','editor-bold','editor-customchar','editor-distractionfree','editor-help','editor-indent','editor-insertmore','editor-italic','editor-justify','editor-kitchensink','editor-ol','editor-outdent','editor-paste-text','editor-paste-word','editor-quote','editor-removeformatting','editor-rtl','editor-spellcheck','editor-strikethrough','editor-textcolor','editor-ul','editor-underline','editor-unlink','editor-video','email','email-alt','exerpt-view','facebook','facebook-alt','feedback','flag','format-aside','format-audio','format-chat','format-gallery','format-image','format-links','format-quote','format-standard','format-status','format-video','forms','googleplus','groups','hammer','id','id-alt','image-crop','image-flip-horizontal','image-flip-vertical','image-rotate-left','image-rotate-right','images-alt','images-alt2','info','leftright','lightbulb','list-view','location','location-alt','lock','marker','menu','migrate','minus','networking','no','no-alt','performance','plus','staff','post-status','pressthis','products','redo','rss','screenoptions','search','share','share-alt','share-alt2','share1','shield','shield-alt','slides','smartphone','smiley','sort','sos','star-empty','star-filled','star-half','tablet','tag','testimonial','translation','trash','twitter','undo','update','upload','vault','video-alt','video-alt2','video-alt3','visibility','welcome-add-page','welcome-comments','welcome-edit-page','welcome-learn-more','welcome-view-site','welcome-widgets-menus','wordpress','wordpress-alt','yes');
								$dashicons = array_combine( $dashicons, $dashicons ); ?>
								<select name="wpex_staff_branding[staff_admin_icon]">
									<option value="0"><?php _e( 'Select', 'wpex' ); ?></option>
									<?php foreach ( $dashicons as $dashicon ) { ?>
										<option value="<?php echo $dashicon; ?>" <?php selected( get_theme_mod( 'staff_admin_icon' ), $dashicon, true ); ?>><?php echo $dashicon; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Post Type: Name', 'wpex' ); ?></th>
							<td><input type="text" name="wpex_staff_branding[staff_labels]" value="<?php echo get_theme_mod( 'staff_labels' ); ?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Post Type: Singular Name', 'wpex' ); ?></th>
							<td><input type="text" name="wpex_staff_branding[staff_singular_name]" value="<?php echo get_theme_mod( 'staff_singular_name' ); ?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Post Type: Slug', 'wpex' ); ?></th>
							<td><input type="text" name="wpex_staff_branding[staff_slug]" value="<?php echo get_theme_mod( 'staff_slug' ); ?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Tags: Label', 'wpex' ); ?></th>
							<td><input type="text" name="wpex_staff_branding[staff_tag_labels]" value="<?php echo get_theme_mod( 'staff_tag_labels' ); ?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Tags: Slug', 'wpex' ); ?></th>
							<td><input type="text" name="wpex_staff_branding[staff_tag_slug]" value="<?php echo get_theme_mod( 'staff_tag_slug' ); ?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Categories: Label', 'wpex' ); ?></th>
							<td><input type="text" name="wpex_staff_branding[staff_cat_labels]" value="<?php echo get_theme_mod( 'staff_cat_labels' ); ?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Categories: Slug', 'wpex' ); ?></th>
							<td><input type="text" name="wpex_staff_branding[staff_cat_slug]" value="<?php echo get_theme_mod( 'staff_cat_slug' ); ?>" /></td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			</div>
		<?php }

		/**
		 * Filter the post type arguments
		 */
		function posttype_args( $args ) {

			// Labels
			$option = get_theme_mod( 'staff_labels' );
			if ( $option && 'Staff' != $option ) {
				$args['labels']['name']					= $option;
				$args['labels']['singular_name']		= $option;
				$args['labels']['add_new']				= __( 'Add New', 'wpex' );
				$args['labels']['add_new_item']			= __( 'Add New Item', 'wpex' );
				$args['labels']['edit_item']			= __( 'Edit Item', 'wpex' );
				$args['labels']['new_item']				= __( 'New Item', 'wpex' );
				$args['labels']['view_item']			= __( 'View Item', 'wpex' );
				$args['labels']['search_items']			= __( 'Search Items', 'wpex' );
				$args['labels']['not_found']			= __( 'No Items Found', 'wpex' );
				$args['labels']['not_found_in_trash']	= __( 'No Items Found In Trash', 'wpex' );
			}

			// Singular name
			$option = get_theme_mod( 'staff_singular_name' );
			if ( $option && 'Staff Item' != $option ) {
				$args['labels']['singular_name'] = $option;
			}

			// Slug
			$option = get_theme_mod( 'staff_slug', 'staff-member' );
			if ( $option && 'staff-member' != $option ) {
				$args['rewrite'] = array( "slug" => $option );
			}

			// Admin Icon
			if ( ( $option = get_theme_mod( 'staff_admin_icon' ) ) && ! is_array( $option ) ) {
				$args['menu_icon'] = 'dashicons-'. $option;
			}

			// Search
			if ( ! get_theme_mod( 'staff_search', true ) ) {
				$args['exclude_from_search'] = true;
			}

			// Return args
			return $args;

		}

		/**
		 * Filter the post type category arguments
		 */
		function cat_args( $args ) {

			// Labels
			$option = get_theme_mod( 'staff_cat_labels', 'Staff Categories' );
			if ( $option && 'Staff Categories' != $option ) {
				$args['labels']['name']							= $option;
				$args['labels']['singular_name']				= $option;
				$args['labels']['search_items']					= __( 'Search','wpex');
				$args['labels']['popular_items']				= __( 'Popular','wpex');
				$args['labels']['all_items']					= __( 'All','wpex');
				$args['labels']['parent_item']					= __( 'Parent','wpex');
				$args['labels']['parent_item_colon']			= __( 'Parent','wpex');
				$args['labels']['edit_item']					= __( 'Edit','wpex');
				$args['labels']['update_item']					= __( 'Update','wpex');
				$args['labels']['add_new_item']					= __( 'Add New','wpex');
				$args['labels']['new_item_name']				= __( 'New Item Name','wpex');
				$args['labels']['separate_items_with_commas']	= __( 'Seperate with commas','wpex');
				$args['labels']['add_or_remove_items']			= __( 'Add or remove','wpex');
				$args['labels']['choose_from_most_used']		= __( 'Choose from the most used','wpex');
				$args['labels']['menu_name']					= $option;
			}

			// Custom Slug
			$option = get_theme_mod( 'staff_cat_slug', 'staff-category' );
			if ( $option && 'staff-category' != $option ) {
				$args['rewrite'] = array( "slug" => $option );
			}

			// Return args
			return $args;

		}

		/**
		 * Filter the post type tag arguments
		 */
		function tag_args( $args ) {

			// Labels
			$option = get_theme_mod( 'staff_tag_labels', 'Staff Tags' );
			if ( ! empty( $option ) && 'Staff Tags' != $option ) {
				$args['labels']['name']							= $option;
				$args['labels']['singular_name']				= $option;
				$args['labels']['search_items']					= __( 'Search','wpex');
				$args['labels']['popular_items']				= __( 'Popular','wpex');
				$args['labels']['all_items']					= __( 'All','wpex');
				$args['labels']['parent_item']					= __( 'Parent','wpex');
				$args['labels']['parent_item_colon']			= __( 'Parent','wpex');
				$args['labels']['edit_item']					= __( 'Edit','wpex');
				$args['labels']['update_item']					= __( 'Update','wpex');
				$args['labels']['add_new_item']					= __( 'Add New','wpex');
				$args['labels']['new_item_name']				= __( 'New Item Name','wpex');
				$args['labels']['separate_items_with_commas']	= __( 'Seperate with commas','wpex');
				$args['labels']['add_or_remove_items']			= __( 'Add or remove','wpex');
				$args['labels']['choose_from_most_used']		= __( 'Choose from the most used','wpex');
				$args['labels']['menu_name']					= $option;
			}

			// Custom Slug
			$option = get_theme_mod( 'staff_tag_slug', 'staff-tags' );
			if ( ! empty( $option ) && 'staff-tags' != $option ) {
				$args['rewrite'] = array( "slug" => $option );
			}

			// Return args
			return $args;

		}

	}
}
new WPEX_Staff_Editor();