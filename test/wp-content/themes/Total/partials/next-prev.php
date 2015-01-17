<?php
/**
 * The page header displays at the top of all single pages and posts
 * See framework/page-header.php for all page header related functions.
 *
 * @package		Total
 * @subpackage	Partials/Page Header
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get post ID
global $post;
$post_id = $post->ID;

// Get current post post type
$post_type = get_post_type( $post_id );

// Set default same category + taxonomy vars
$taxonomy = '';

// Check if post has terms if so then show next/prev from the same_cat
$has_terms	= wpex_post_has_terms( $post_id );
$same_cat	= $has_terms;
$same_cat	= apply_filters( 'wpex_prev_post_link_same_cat', $same_cat );

// Set the taxonomy for the next/prev when in the same cat
if ( $post_type == 'post' ) {
	$taxonomy = 'category';
} elseif ( $post_type == 'portfolio' ) {
	$taxonomy = 'portfolio_category';
} elseif ( $post_type == 'staff' ) {
	$taxonomy = 'staff_category';
} elseif ( $post_type == 'testimonials' ) {
	$taxonomy = 'testimonials_category';
}

// Previous post link title
$prev_post_link_title = ( in_array( $post_type, array( 'testimonials' ) ) ) ? __( 'Next', 'wpex' ) : '%title';
$prev_post_link_title = $prev_post_link_title . '<span class="fa fa-angle-double-right"></span>';
$prev_post_link_title = apply_filters( 'wpex_prev_post_link_title', $prev_post_link_title );

// Next post link title
$next_post_link_title = ( in_array( $post_type, array( 'testimonials' ) ) ) ? __( 'Previous', 'wpex' ) : '%title';
$next_post_link_title = '<span class="fa fa-angle-double-left"></span>' . $next_post_link_title;
$next_post_link_title = apply_filters( 'wpex_next_post_link_title', $next_post_link_title ); ?>

<div class="clr"></div>
<div class="post-pagination-wrap clr">
	<ul class="post-pagination container clr">
		<?php
		// Next/Prev in same cat
		if ( $has_terms ) {
			previous_post_link( '<li class="post-next">%link</li>',  $prev_post_link_title, $same_cat, '', $taxonomy );
			next_post_link( '<li class="post-prev">%link</li>', $next_post_link_title, $same_cat, '', $taxonomy );
		}
		// Next/Prev not in same cat
		else {
			previous_post_link( '<li class="post-next">%link</li>', $prev_post_link_title );
			next_post_link( '<li class="post-prev">%link</li>', $next_post_link_title );
		} ?>
	</ul><!-- .post-post-pagination -->
</div><!-- .post-pagination-wrap -->