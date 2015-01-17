<?php
/**
 * Post Series functions & classes
 *
 * @package		Total
 * @subpackage	Framework/Blog
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.0
 */

// Do nothing if disabled
if ( ! get_theme_mod( 'post_series_enable', true ) ) {
	return;
}

// Registers the Post Series Taxonomy
if ( ! class_exists( 'WPEX_Post Series_Post_Type' ) ) {
	class WPEX_Post_Series_Post_Type {

		// Constructor
		function __construct() {

			// Register the taxonomy
			$this->register();

			// Adds columns in the admin view for thumbnail and taxonomies
			add_filter( 'manage_edit-post_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_post_posts_custom_column', array( $this, 'column_display' ), 10, 2 );

			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( $this, 'tax_filters' ) );

		}

		// Register post taxonomy
		function register() {
			$taxonomy_post_series_category_labels = array(
				'name'							=> __( 'Post Series', 'wpex' ),
				'singular_name'					=> __( 'Post Series', 'wpex' ),
				'search_items'					=> __( 'Search Post Series', 'wpex' ),
				'popular_items'					=> __( 'Popular Post Series', 'wpex' ),
				'all_items'						=> __( 'All Post Series', 'wpex' ),
				'parent_item'					=> __( 'Parent Post Series', 'wpex' ),
				'parent_item_colon'				=> __( 'Parent Post Series:', 'wpex' ),
				'edit_item'						=> __( 'Edit Post Series', 'wpex' ),
				'update_item'					=> __( 'Update Post Series', 'wpex' ),
				'add_new_item'					=> __( 'Add New Post Series', 'wpex' ),
				'new_item_name'					=> __( 'New Post Series Name', 'wpex' ),
				'separate_items_with_commas'	=> __( 'Separate post series with commas', 'wpex' ),
				'add_or_remove_items'			=> __( 'Add or remove post series', 'wpex' ),
				'choose_from_most_used'			=> __( 'Choose from the most used post series', 'wpex' ),
				'menu_name'						=> __( 'Post Series', 'wpex' ),
			);
			$args = array(
				'labels'			=> $taxonomy_post_series_category_labels,
				'public'			=> true,
				'show_in_nav_menus'	=> true,
				'show_ui'			=> true,
				'show_tagcloud'		=> true,
				'hierarchical'		=> true,
				'rewrite'			=> array(
					'slug'	=> 'post-series'
				),
				'query_var'			=> true
			);
			$args = apply_filters( 'wpex_taxonomy_post_series_args', $args );
			register_taxonomy( 'post_series', array( 'post' ), $args );
		}

		// Display post series in columns
		function edit_columns( $columns ) {
			$columns['wpex_post_series'] = __( 'Post Series', 'wpex' );
			return $columns;
		}
		function column_display( $column, $post_id ) {
			switch ( $column ) {
				case "wpex_post_series":
				if ( $category_list = get_the_term_list( $post_id, 'post_series', '', ', ', '' ) ) {
					echo $category_list;
				} else {
					echo '&mdash;';
				}
				break;
			}
		}

		/**
		 * Adds taxonomy filters to the portfolio admin page
		 * Code artfully lifed from http://pippinsplugins.com
		 */
		function tax_filters() {
			global $typenow;
			if ( 'post' == $typenow ) {
			$tax_slug			= 'post_series';
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

// Start up post series class if enabled
new WPEX_Post_Series_Post_Type;