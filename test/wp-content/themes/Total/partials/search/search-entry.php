<?php
/**
 * The page header displays at the top of all single pages and posts
 * See framework/page-header.php for all page header related functions.
 *
 * @package		Total
 * @subpackage	Partials/Search
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

// Post vars
$post_id		= get_the_ID();
$the_permalink	= get_permalink( $post_id );

// Add classes to the post_class
$classes	= array();
$classes[]	= 'search-entry';
$classes[]	= 'clr';
if ( ! has_post_thumbnail( $post_id ) ) {
	$classes[] = 'search-entry-no-thumb';
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<?php
	// Display thumbnail if one is set
	if( has_post_thumbnail( $post_id ) ) {
		// Get cropped featured image
		$wpex_image = wpex_image( 'array' ); ?>
		<div class="search-entry-thumb">
			<a href="<?php echo $the_permalink; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" class="search-entry-img-link">
				<img src="<?php echo $wpex_image['url']; ?>" alt="<?php echo the_title(); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
			</a>
		</div><!-- .search-entry-thumb -->
	<?php } ?>
	<div class="search-entry-text">
		<header>
			<h2>
				<a href="<?php echo $the_permalink; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>">
					<?php the_title(); ?>
				</a>
			</h2>
		</header>
		<?php
		// Display excerpt
		$args = array(
			'length'	=> '30',
			'readmore'	=> false,
		);
		wpex_excerpt( $args ); ?>
	</div><!-- .search-entry-text -->
</article><!-- .entry -->