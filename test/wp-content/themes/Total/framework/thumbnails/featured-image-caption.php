<?php
/**
 * Displays the featured image caption of a post with $id
 *
 * @package		Total
 * @subpackage	Customizer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! function_exists( 'wpex_featured_image_caption' ) ) {
	function wpex_featured_image_caption( $post_id = '' ) {
		$post_id		= $post_id ? $post_id : get_the_ID();
		$thumbnail_id	= get_post_thumbnail_id( $post_id );
		$caption		= get_post_field( 'post_excerpt', $thumbnail_id );
		if ( $caption ) {
			return $caption;
		}
	}
}