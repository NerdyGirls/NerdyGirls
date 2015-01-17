<?php
/**
 * Register the Staff custom post type
 *
 * @package		Total
 * @subpackage	Framework/Staff
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! class_exists( 'WPEX_Register_Staff_Post_Type' ) ) {
	class WPEX_Register_Staff_Post_Type {

		function __construct() {

			// Adds the staff post type and taxonomies
			$this->register();

			// Thumbnail support for staff posts
			add_theme_support( 'post-thumbnails', array( 'staff' ) );

			// Adds columns in the admin view for taxonomies
			add_filter( 'manage_edit-staff_columns', array( &$this, 'staff_edit_columns' ) );
			add_action( 'manage_staff_posts_custom_column', array( &$this, 'staff_column_display' ), 10, 2 );

			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( &$this, 'staff_add_taxonomy_filters' ) );

		}
		

		function register() {

			/**
			 * Enable the Staff custom post type
			 * http://codex.wordpress.org/Function_Reference/register_post_type
			 */
			$labels = array(
				'name'					=> __( 'Staff', 'wpex' ),
				'singular_name'			=> __( 'Staff Item', 'wpex' ),
				'add_new'				=> __( 'Add New Item', 'wpex' ),
				'add_new_item'			=> __( 'Add New Staff Item', 'wpex' ),
				'edit_item'				=> __( 'Edit Staff Item', 'wpex' ),
				'new_item'				=> __( 'Add New Staff Item', 'wpex' ),
				'view_item'				=> __( 'View Item', 'wpex' ),
				'search_items'			=> __( 'Search Staff', 'wpex' ),
				'not_found'				=> __( 'No staff items found', 'wpex' ),
				'not_found_in_trash'	=> __( 'No staff items found in trash', 'wpex' )
			);
			
			$args = array(
				'labels'			=> $labels,
				'public'			=> true,
				'supports'			=> array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'revisions' ),
				'capability_type'	=> 'post',
				'rewrite'			=> array(
					'slug' => 'staff-member'
				),
				'has_archive'		=> false,
				'menu_icon'			=> 'dashicons-groups',
				'menu_position'		=> 20,
			); 
			
			$args = apply_filters( 'wpex_staff_args', $args );
			register_post_type( 'staff', $args );

			/**
			 * Register a taxonomy for Staff Tags
			 * http://codex.wordpress.org/Function_Reference/register_taxonomy
			 */

			$labels = array(
				'name'							=> __( 'Staff Tags', 'wpex' ),
				'singular_name'					=> __( 'Staff Tag', 'wpex' ),
				'search_items'					=> __( 'Search Staff Tags', 'wpex' ),
				'popular_items'					=> __( 'Popular Staff Tags', 'wpex' ),
				'all_items'						=> __( 'All Staff Tags', 'wpex' ),
				'parent_item'					=> __( 'Parent Staff Tag', 'wpex' ),
				'parent_item_colon'				=> __( 'Parent Staff Tag:', 'wpex' ),
				'edit_item'						=> __( 'Edit Staff Tag', 'wpex' ),
				'update_item'					=> __( 'Update Staff Tag', 'wpex' ),
				'add_new_item'					=> __( 'Add New Staff Tag', 'wpex' ),
				'new_item_name'					=> __( 'New Staff Tag Name', 'wpex' ),
				'separate_items_with_commas'	=> __( 'Separate staff tags with commas', 'wpex' ),
				'add_or_remove_items'			=> __( 'Add or remove staff tags', 'wpex' ),
				'choose_from_most_used'			=> __( 'Choose from the most used staff tags', 'wpex' ),
				'menu_name'						=> __( 'Staff Tags', 'wpex' )
			);

			$args = array(
				'labels'			=> $labels,
				'public'			=> true,
				'show_in_nav_menus'	=> true,
				'show_ui'			=> true,
				'show_tagcloud'		=> true,
				'hierarchical'		=> false,
				'rewrite'			=> array( 'slug' => 'staff-tag' ),
				'query_var'			=> true
			);

			$args = apply_filters( 'wpex_taxonomy_staff_tag_args', $args );
			
			register_taxonomy( 'staff_tag', array( 'staff' ), $args );

			/**
			 * Register a taxonomy for Staff Categories
			 * http://codex.wordpress.org/Function_Reference/register_taxonomy
			 */

			$labels = array(
				'name'							=> __( 'Staff Categories', 'wpex' ),
				'singular_name'					=> __( 'Staff Category', 'wpex' ),
				'search_items'					=> __( 'Search Staff Categories', 'wpex' ),
				'popular_items'					=> __( 'Popular Staff Categories', 'wpex' ),
				'all_items'						=> __( 'All Staff Categories', 'wpex' ),
				'parent_item'					=> __( 'Parent Staff Category', 'wpex' ),
				'parent_item_colon'				=> __( 'Parent Staff Category:', 'wpex' ),
				'edit_item'						=> __( 'Edit Staff Category', 'wpex' ),
				'update_item'					=> __( 'Update Staff Category', 'wpex' ),
				'add_new_item'					=> __( 'Add New Staff Category', 'wpex' ),
				'new_item_name'					=> __( 'New Staff Category Name', 'wpex' ),
				'separate_items_with_commas'	=> __( 'Separate staff categories with commas', 'wpex' ),
				'add_or_remove_items'			=> __( 'Add or remove staff categories', 'wpex' ),
				'choose_from_most_used'			=> __( 'Choose from the most used staff categories', 'wpex' ),
				'menu_name'						=> __( 'Staff Categories', 'wpex' ),
			);

			$args = array(
				'labels'			=> $labels,
				'public'			=> true,
				'show_in_nav_menus'	=> true,
				'show_ui'			=> true,
				'show_tagcloud'		=> true,
				'hierarchical'		=> true,
				'rewrite'			=> array(
					'slug'	=> 'staff-category'
				),
				'query_var'			=> true
			);

			$args = apply_filters( 'wpex_taxonomy_staff_category_args', $args );
			
			register_taxonomy( 'staff_category', array( 'staff' ), $args );

		}

		/**
		 * Add Columns to Staff Edit Screen
		 * http://wptheming.com/2010/07/column-edit-pages/
		 */

		function staff_edit_columns( $columns ) {
			$columns['staff_position']	= __( 'Position', 'wpex' );
			$columns['staff_category']	= __( 'Category', 'wpex' );
			$columns['staff_tag']		= __( 'Tags', 'wpex' );
			return $columns;
		}

		function staff_column_display( $column, $post_id ) {

			// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

			switch ( $column ) {
					
					// Display the staff position
					case "staff_position":
						if ( $staff_position = get_post_meta( get_the_ID(), 'wpex_staff_position', true ) ) {
							echo $staff_position;
						} else {
							echo '&mdash;';
						}
					break;

					// Display the staff tags in the column view
					case "staff_category":
						if ( $category_list = get_the_term_list( $post_id, 'staff_category', '', ', ', '' ) ) {
							echo $category_list;
						} else {
							echo '&mdash;';
						}
					break;
		
					// Display the staff tags in the column view
					case "staff_tag":
						if ( $tag_list = get_the_term_list( $post_id, 'staff_tag', '', ', ', '' ) ) {
							echo $tag_list;
						} else {
							echo '&mdash;';
						}
					break;
			}
		}

		/**
		 * Adds taxonomy filters to the staff admin page
		 * Code artfully lifed from http://pippinsplugins.com
		 */

		function staff_add_taxonomy_filters() {
			global $typenow;

			// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
			$taxonomies = array( 'staff_category', 'staff_tag' );

			// must set this to the post type you want the filter(s) displayed on
			if ( 'staff' == $typenow ) {

				foreach ( $taxonomies as $tax_slug ) {
					$current_tax_slug	= isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
					$tax_obj			= get_taxonomy( $tax_slug );
					$tax_name			= $tax_obj->labels->name;
					$terms				= get_terms( $tax_slug );
					if ( count( $terms ) > 0) {
						echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
						echo "<option value=''>$tax_name</option>";
						foreach ( $terms as $term ) {
							echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
						}
						echo "</select>";
					}
				}
			}
		}

	}
}
new WPEX_Register_Staff_Post_Type;