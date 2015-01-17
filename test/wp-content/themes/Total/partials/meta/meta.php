<?php
/**
 * Post meta
 * This template part can be used with various post types
 *
 * @package		Total
 * @subpackage	Partials/Post Meta
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

// Post data
$post_type	= get_post_type();
$post_id	= get_the_ID();
$post_type	= get_post_type( $post_id );

// Check if singular or entry
if ( is_singular() ) {
	$type	= 'post';
} else {
	$type	= 'entry';
}

// Return if disabled
if ( 'post' == $post_type && ! get_theme_mod( 'blog_' . $type . '_meta', true ) ) {
	return;
}

// Default enabled meta sections
$meta_sections	= array( 'date', 'author', 'categories', 'comments' );

// Check what options are enabled for standard posts only
if ( 'post' == $post_type ) { 
	$meta_sections = get_theme_mod( 'blog_' . $type . '_meta_sections', $meta_sections );
}

// Apply filters for easy modification
$meta_sections = apply_filters( 'wpex_meta_sections', $meta_sections );

// Add class for meta with title
$classes = 'meta clr';
if ( is_singular( 'post' ) && 'custom_text' == get_theme_mod( 'blog_single_header', 'custom_text' ) ) {
	$classes .= ' meta-with-title';
} ?>

<ul class="<?php echo $classes; ?>">
	<?php
	// Date
	if ( in_array( 'date', $meta_sections ) ) { ?>
		<li class="meta-date"><span class="fa fa-clock-o"></span><?php echo get_the_date(); ?></li>
	<?php }
	// Author
	if ( in_array( 'author', $meta_sections ) ) { ?>
		<li class="meta-author"><span class="fa fa-user"></span><?php the_author_posts_link(); ?></li>
	<?php }
	// Category
	if ( in_array( 'categories', $meta_sections ) ) {
		if ( 'post' == $post_type ) { ?>
			<li class="meta-category"><span class="fa fa-folder-o"></span><?php the_category( ', ', $post_id ); ?></li>
		<?php } ?>
	<?php }
	// Comments
	if ( in_array( 'comments', $meta_sections ) && comments_open() && ! post_password_required() ) { ?>
		<li class="meta-comments comment-scroll"><span class="fa fa-comment-o"></span><?php comments_popup_link( __( '0 Comments', 'wpex' ), __( '1 Comment',  'wpex' ), __( '% Comments', 'wpex' ), 'comments-link' ); ?></li>
	<?php } ?>
</ul>