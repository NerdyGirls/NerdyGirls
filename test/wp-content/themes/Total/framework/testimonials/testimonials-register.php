<?php
/**
 * Register the Testimonials custom post type
 *
 * @package		Total
 * @subpackage	Framework/Testimonials
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

if ( ! class_exists( 'WPEX_Testimonials_Post_Type' ) ) {
	class WPEX_Testimonials_Post_Type {

		function __construct() {

			// Adds the testimonials post type and taxonomies
			$this->register();

			// Thumbnail support for testimonials posts
			add_theme_support( 'post-thumbnails', array( 'testimonials' ) );

			// Adds columns in the admin view for taxonomies
			add_filter( 'manage_edit-testimonials_columns', array( &$this, 'testimonials_edit_columns' ) );
			add_action( 'manage_testimonials_posts_custom_column', array( &$this, 'testimonials_column_display' ), 10, 2 );
			
		}
		

		function register() {

			/**
			 * Enable the Testimonials custom post type
			 * http://codex.wordpress.org/Function_Reference/register_post_type
			 */

			$labels = array(
				'name'					=> __( 'Testimonials', 'wpex' ),
				'singular_name'			=> __( 'Testimonial', 'wpex' ),
				'add_new'				=> __( 'Add New Item', 'wpex' ),
				'add_new_item'			=> __( 'Add New Testimonials Item', 'wpex' ),
				'edit_item'				=> __( 'Edit Testimonials Item', 'wpex' ),
				'new_item'				=> __( 'Add New Testimonials Item', 'wpex' ),
				'view_item'				=> __( 'View Item', 'wpex' ),
				'search_items'			=> __( 'Search Testimonials', 'wpex' ),
				'not_found'				=> __( 'No testimonials items found', 'wpex' ),
				'not_found_in_trash'	=> __( 'No testimonials items found in trash', 'wpex' )
			);
			
			$args = array(
				'labels'			=> $labels,
				'public'			=> true,
				'supports'			=> array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'revisions' ),
				'capability_type'	=> 'post',
				'rewrite'			=> array( "slug" => "testimonial" ),
				'has_archive'		=> false,
				'menu_icon'			=> 'dashicons-format-status',
				'menu_position'		=> 20,
			); 
			
			$args = apply_filters( 'wpex_testimonials_args', $args );
			
			register_post_type( 'testimonials', $args );

		
			/**
			 * Register a taxonomy for Testimonials Categories
			 * http://codex.wordpress.org/Function_Reference/register_taxonomy
			 */

			$labels = array(
				'name'							=> __( 'Testimonials Categories', 'wpex' ),
				'singular_name'					=> __( 'Testimonials Category', 'wpex' ),
				'search_items'					=> __( 'Search Testimonials Categories', 'wpex' ),
				'popular_items'					=> __( 'Popular Testimonials Categories', 'wpex' ),
				'all_items'						=> __( 'All Testimonials Categories', 'wpex' ),
				'parent_item'					=> __( 'Parent Testimonials Category', 'wpex' ),
				'parent_item_colon'				=> __( 'Parent Testimonials Category:', 'wpex' ),
				'edit_item'						=> __( 'Edit Testimonials Category', 'wpex' ),
				'update_item'					=> __( 'Update Testimonials Category', 'wpex' ),
				'add_new_item'					=> __( 'Add New Testimonials Category', 'wpex' ),
				'new_item_name'					=> __( 'New Testimonials Category Name', 'wpex' ),
				'separate_items_with_commas'	=> __( 'Separate testimonials categories with commas', 'wpex' ),
				'add_or_remove_items'			=> __( 'Add or remove testimonials categories', 'wpex' ),
				'choose_from_most_used'			=> __( 'Choose from the most used testimonials categories', 'wpex' ),
				'menu_name'						=> __( 'Testimonials Categories', 'wpex' ),
			);

			$args = array(
				'labels'				=> $labels,
				'public'				=> true,
				'show_in_nav_menus'		=> true,
				'show_ui'				=> true,
				'show_tagcloud'			=> true,
				'hierarchical'			=> true,
				'rewrite'				=> array(
					'slug'	=> 'testimonials-category'
				),
				'query_var'				=> true
			);

			$args = apply_filters( 'wpex_taxonomy_testimonials_category_args', $args );
			
			register_taxonomy( 'testimonials_category', array( 'testimonials' ), $args );

		}

		/**
		 * Add Columns to Testimonials Edit Screen
		 * http://wptheming.com/2010/07/column-edit-pages/
		 */

		function testimonials_edit_columns( $columns ) {
			$columns['testimonials_author']		= __( 'Author', 'wpex' );
			$columns['testimonials_category']	= __( 'Category', 'wpex' );
			return $columns;
		}

		function testimonials_column_display( $column, $post_id ) {

			switch ( $column ) {
					
				// Display the testimonials author
				case "testimonials_author":
				if ( $testimonials_author = get_post_meta( get_the_ID(), 'wpex_testimonial_author', true ) ) {
					echo $testimonials_author;
				} else {
					echo '&mdash;';
				}
				break;

				// Display the testimonials category in the column view
				case "testimonials_category":

				if ( $category_list = get_the_term_list( $post_id, 'testimonials_category', '', ', ', '' ) ) {
					echo $category_list;
				} else {
					echo '&mdash;';
				}
				break;		
			}
		}

		/**
		 * Adds taxonomy filters to the testimonials admin page
		 * Code artfully lifed from http://pippinsplugins.com
		 */

		function testimonials_add_taxonomy_filters() {
			global $typenow;

			// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
			$taxonomies = array( 'testimonials_category' );

			// must set this to the post type you want the filter(s) displayed on
			if ( $typenow == 'testimonials' ) {
				foreach ( $taxonomies as $tax_slug ) {
					$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
					$tax_obj = get_taxonomy( $tax_slug );
					$tax_name = $tax_obj->labels->name;
					$terms = get_terms($tax_slug);
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

		/**
		 * Add Testimonials count to "Right Now" Dashboard Widget
		 */

		function add_testimonials_counts() {
				if ( ! post_type_exists( 'testimonials' ) ) {
					 return;
				}
				$num_posts	= wp_count_posts( 'testimonials' );
				$num		= number_format_i18n( $num_posts->publish );
				$text		= _n( 'Testimonials Item', 'Testimonials Items', intval($num_posts->publish) );
				if ( current_user_can( 'edit_posts' ) ) {
					$num = "<a href='edit.php?post_type=testimonials'>$num</a>";
					$text = "<a href='edit.php?post_type=testimonials'>$text</a>";
				}
				echo '<td class="first b b-testimonials">' . $num . '</td>';
				echo '<td class="t testimonials">' . $text . '</td>';
				echo '</tr>';
				if ( $num_posts->pending > 0 ) {
					$num	= number_format_i18n( $num_posts->pending );
					$text	= _n( 'Testimonials Item Pending', 'Testimonials Items Pending', intval($num_posts->pending) );
					if ( current_user_can( 'edit_posts' ) ) {
						$num	= "<a href='edit.php?post_status=pending&post_type=testimonials'>$num</a>";
						$text	= "<a href='edit.php?post_status=pending&post_type=testimonials'>$text</a>";
					}
					echo '<td class="first b b-testimonials">' . $num . '</td>';
					echo '<td class="t testimonials">' . $text . '</td>';
					echo '</tr>';
				}
		}

	}
}
new WPEX_Testimonials_Post_Type;