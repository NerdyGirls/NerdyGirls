<?php
/**
 * Blog entry layout
 *
 * @package		Total
 * @subpackage	Partials/Blog
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
if ( ! get_theme_mod( 'blog_excerpt', 'on' ) && ! get_theme_mod( 'blog_entry_readmore', 'on' ) ) {
	return;
}

// Return if password protected
if ( post_password_required() ) {
	return;
}

// Vars
$post_id	= get_the_ID();
$format		= get_post_format( $post_id );
$text		= get_theme_mod( 'blog_entry_readmore_text', __( 'Read More', 'wpex' ) );

// Translate readmore text with WPML
$text = wpex_translate_theme_mod( 'blog_entry_readmore_text', $text );

// Button Text
if ( 'link' == $format ) {
	$text = __( 'Visit Website', 'wpex' );
} elseif ( ! $text ) {
	$text = __( 'Read More', 'wpex' );
}

// Apply filters for child theming
$text = apply_filters( 'wpex_post_readmore_link_text', $text );

// Output the readmore button
if ( 'link' == $format ) { ?>
	<div class="blog-entry-readmore clr">
		<a href="<?php echo wpex_permalink(); ?>" class="theme-button" title="<?php echo __( 'Visit Website', 'wpex' ) ?>" target="_blank">
			<?php echo $text ?><span class="readmore-rarr">&rarr;</span>
		</a>
	</div>
<?php }
// All other post formats
else { ?>
	<div class="blog-entry-readmore clr">
		<a href="<?php the_permalink(); ?>" class="theme-button" title="<?php echo $text ?>">
			<?php echo $text ?><span class="readmore-rarr">&rarr;</span>
		</a>
	</div>
<?php
}