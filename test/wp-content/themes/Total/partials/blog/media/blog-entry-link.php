<?php
/**
 * Blog entry link format media
 *
 * @package		Total
 * @subpackage	Framework/Partials/Blog/Media
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

// Return if there isn't any thumbnail
if ( ! has_post_thumbnail() ) {
	return;
}

// Get cropped featured image
$image = wpex_image( 'array' );

// Get title
$esc_title = esc_attr( the_title_attribute( 'echo=0' ) ); ?>

<div class="blog-entry-media clr">

	<a href="<?php echo wpex_permalink( get_the_ID() ); ?>" title="<?php echo $esc_title; ?>" rel="bookmark" class="blog-entry-media-link <?php wpex_img_animation_classes(); ?>" target="_blank">
		<img src="<?php echo $image['url']; ?>" alt="<?php echo $esc_title; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />
	</a>

</div><!-- .blog-entry-media -->