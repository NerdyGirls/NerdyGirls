<?php
/**
 * This is an old is outdated, doesn't really do anything else but provide a backup
 * for people that had been using the theme before version 1.6.0
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Search entry
if ( is_search() ) {
	get_template_part( 'partials/search/search', 'entry' );
	return;
}

// Prevent issues with post types incorrectly trying to load this template file
if ( in_array( get_post_type(), array( 'portfolio', 'staff', 'testimonials' ) ) ) :
	get_template_part( 'partials/' . get_post_type() .'/'. get_post_type(), 'entry' );
	return;

// Get single format (fallback for changes made in 1.6.0 )
elseif ( is_singular() ) :
	$post_format = get_post_format() ? get_post_format() : 'thumbnail';
	get_template_part( 'partials/blog/formats/blog-single', $post_format );

// Standard blog entries
else :
	get_template_part( 'partials/blog/blog-entry', 'layout' );

endif;