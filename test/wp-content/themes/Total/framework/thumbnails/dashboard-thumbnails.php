<?php
/**
 * Create Custom Columns for the WP dashboard
 *
 * @package		Total
 * @subpackage	Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Only needed in the admin
if( ! is_admin() ) {
	return;
}

// If disabled do nothing
if ( ! get_theme_mod( 'blog_dash_thumbs', true ) ) {
	return;
}

// Add thumbnails to post admin dashboard
add_filter( 'manage_post_posts_columns', 'wpex_posts_columns', 10 );
add_filter( 'manage_portfolio_posts_columns', 'wpex_posts_columns', 10 );
add_filter( 'manage_testimonials_posts_columns', 'wpex_posts_columns', 10 );
add_filter( 'manage_staff_posts_columns', 'wpex_posts_columns', 10 );
add_action( 'manage_posts_custom_column', 'wpex_posts_custom_columns', 10, 2 );

add_filter( 'manage_page_posts_columns', 'wpex_posts_columns', 10 );
add_action( 'manage_pages_custom_column', 'wpex_posts_custom_columns', 10, 2 );

if ( ! function_exists( 'wpex_posts_columns' ) ) {
	function wpex_posts_columns( $defaults ){
		$defaults['wpex_post_thumbs'] = __( 'Featured Image', 'wpex' );
		return $defaults;
	}
}

if ( ! function_exists( 'wpex_posts_custom_columns' ) ) {
	function wpex_posts_custom_columns( $column_name, $id ){
		if( $column_name != 'wpex_post_thumbs' ) {
			return;
		}
		if ( has_post_thumbnail( $id ) ) {
			$img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'thumbnail', false );
			if( ! empty( $img_src[0] ) ) { ?>
					<img src="<?php echo $img_src[0]; ?>" alt="<?php the_title(); ?>" style="max-width:100%;max-height:90px;" />
				<?php
			}
		} else {
			echo '—';
		}
	}
}