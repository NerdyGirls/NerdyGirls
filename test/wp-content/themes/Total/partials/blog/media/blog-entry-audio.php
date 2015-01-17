<?php
/**
 * Blog entry audio format media
 *
 * @package		Total
 * @subpackage	Partials/Blog/Media
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

// Get post id
$post_id = get_the_ID();

// Return if post thumbnail isn't defined
if ( ! has_post_thumbnail( $post_id ) ) {
	return;
}

$title_esc	= esc_attr( the_title_attribute( 'echo=0' ) );
$image		= wpex_image( 'array' ); ?>

<div class="blog-entry-media clr">
	<a href="<?php the_permalink( $post_id ); ?>" title="<?php echo $title_esc; ?>" rel="bookmark" class="blog-entry-img-link">
		<img src="<?php echo $image['url']; ?>" alt="<?php echo $title_esc; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />
		<div class="blog-entry-music-icon-overlay"><span class="fa fa-music"></span></div>
	</a>
</div><!-- .blog-entry-media -->