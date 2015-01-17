<?php
/**
 * Staff single related template part
 *
 * @package		Total
 * @subpackage	Partials/Staff
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if disabled
if ( ! get_theme_mod( 'staff_related', true ) ) {
	return;
}

// Vars
global $post;
$post_id	= $post->ID;
$post_count	= get_theme_mod( 'staff_related_count', '3' );

// Return if pass required
if ( post_password_required() ) {
	return;
}

// Return if full-screen post
if ( 'full-screen' == wpex_get_post_layout_class( $post_id ) ) {
	return;
}

// Disabled via meta setting - goodbye
if ( 'on' == get_post_meta( $post_id, 'wpex_disable_related_items', true ) ) {
	return;
}

// Create an array of current category ID's
$cats		= wp_get_post_terms( $post_id, 'staff_category' ); 
$cats_ids	= array();  
foreach( $cats as $wpex_related_cat ) {
	$cats_ids[] = $wpex_related_cat->term_id; 
}
if ( ! empty( $cats_ids ) ) {
	$tax_query = array (
		array (
			'taxonomy'	=> 'staff_category',
			'field' 	=> 'id',
			'terms' 	=> $cats_ids,
			'operator'	=> 'IN',
		),
	);
} else {
	$tax_query = '';
}

// Related query arguments
$args = array(
	'post_type'			=> 'staff',
	'posts_per_page'	=> $post_count,
	'orderby'			=> 'rand',
	'post__not_in'		=> array( $post_id ),
	'no_found_rows'		=> true,
	'tax_query'			=> $tax_query,
);
$args				= apply_filters( 'wpex_related_staff_args', $args );
$wpex_related_query = new wp_query( $args );

// If posts were found display related items
if ( $wpex_related_query->have_posts() ) : ?>

	<section class="related-staff-posts clr">
		<?php
		// Get heading text
		$heading = get_theme_mod( 'staff_related_title', __( 'Related Staff', 'wpex' ) );

		// Fallback
		$heading = $heading ? $heading : __( 'Related Staff', 'wpex' );

		// Translate heading with WPML
		$heading = wpex_translate_theme_mod( 'staff_related_title', $heading );

		// Display heading
		if ( $heading ) { ?>
			<div class="theme-heading">
				<span><?php echo $heading; ?></span>
			</div>
		<?php } ?>

		<div class="wpex-row clr">
			<?php
			// Create counter var and set to 0
			$wpex_count = 0;
			// Loop through related posts
			foreach( $wpex_related_query->posts as $post ) : setup_postdata( $post );
				// Counter for clearing floats
				$wpex_count++;
				// Get the staff entry content
				$template = locate_template( 'partials/staff/staff-entry.php' );
				if ( $template ) {
					include( $template );
				}
				// Reset loop counter
				if( $wpex_count == $post_count ) {
					$wpex_count = 0;
				}
			// Related posts loop ends here
			endforeach; ?>
		</div>
	</section><!-- .related-staff-posts -->

<?php endif; ?>
<?php wp_reset_postdata(); ?>