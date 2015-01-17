<?php
/**
 * Template for the Title + Category Hover overlay style
 *
 * @package		Total
 * @subpackage	Partials/Overlays
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

// Only used for inside position
if ( 'inside_link' != $position ) {
	return;
}

// Get category taxonomy for current post type
$post_type = get_post_type();
if ( 'portfolio' == $post_type ) {
	$taxonomy = 'portfolio_category';
} elseif( 'staff' == $post_type ) {
	$taxonomy = 'staff_category';
} elseif ( 'post' == $post_type ) {
	$taxonomy = 'category';
} elseif ( 'product' == $post_type ) {
	$taxonomy = 'product_cat';
} else {
	$taxonomy = false;
}

// Get terms
if ( $taxonomy ) {
	$terms = wpex_list_post_terms( $taxonomy, $show_links = false, $echo = false );
}
?>

<div class="overlay-title-category-hover">
	<div class="overlay-title-category-hover-inner clr">
		<div class="overlay-title-category-hover-text clr">
			<div class="overlay-title-category-hover-title">
				<?php the_title(); ?>
			</div>
			<?php if ( $terms ) { ?>
				<div class="overlay-title-category-hover-category">
					<?php echo $terms; ?>
				</div><!-- .overlay-title-category-visible-category -->
			<?php } ?>
		</div>
	</div>
</div>