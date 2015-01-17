<?php
/**
 * Main portfolio entry template part
 *
 * @package		Total
 * @subpackage	Templates/Portfolio
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Counter for clearing floats and margins
if ( ! isset( $wpex_related_query ) ) {
	global $wpex_count;
	$query = 'archive';
} else {
	$query = 'related';
}

// Add Standard Classes
$classes	= array();
$classes[]	= 'portfolio-entry';
$classes[]	= 'col';
$classes[]	= wpex_portfolio_column_class( $query );
$classes[]	= 'col-'. $wpex_count;

// Masonry Classes
if ( 'archive' == $query && $wpex_grid_style = get_theme_mod( 'portfolio_archive_grid_style', 'fit-rows' ) ) {
	if( 'masonry' == $wpex_grid_style || 'no-margins' == $wpex_grid_style ) {
		$classes[] = ' isotope-entry';
	}
} ?>

<article id="#post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<?php get_template_part( 'partials/portfolio/portfolio-entry', 'media' ); ?>
	<?php get_template_part( 'partials/portfolio/portfolio-entry', 'content' ); ?>
</article><!-- .portfolio-entry -->