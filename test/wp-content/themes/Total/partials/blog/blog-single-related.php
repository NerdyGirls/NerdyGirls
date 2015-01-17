<?php
/**
 * Single related posts
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
if ( ! get_theme_mod( 'blog_related', true ) ) {
	return;
}

// Get Post Data
global $post, $post_id;
$post_id = $post_id ? $post_id : $post->ID;

// Return if full-screen post
if ( 'full-screen' == wpex_get_post_layout_class( $post_id ) ) {
	return;
}

// Create an array of current category ID's
$cats		= wp_get_post_terms( $post_id, 'category' );
$cats_ids	= array();
foreach( $cats as $wpex_related_cat ) {
	$cats_ids[] = $wpex_related_cat->term_id;
}

// Query args
$args = array(
	'posts_per_page'		=> get_theme_mod( 'blog_related_count', '3' ),
	'orderby' 				=> 'rand',
	'category__in'			=> $cats_ids,
	'post__not_in'			=> array( $post_id ),
	'no_found_rows'			=> true,
	'tax_query'				=> array (
		'relation'	=> 'AND',
		array (
			'taxonomy'	=> 'post_format',
			'field'		=> 'slug',
			'terms'		=> array( 'post-format-quote', 'post-format-link' ),
			'operator'	=> 'NOT IN',
		),
	),
);
$args = apply_filters( 'wpex_blog_post_related_query_args', $args );

// Related query arguments
$wpex_related_query = new wp_query( $args );

// If the custom query returns post display related posts section
if ( $wpex_related_query->have_posts() ) :

	// Check if excerpts are enabled
	$has_excerpt = get_theme_mod( 'blog_related_excerpt', true ); ?>

	<div class="related-posts clr">

		<?php
		// Get heading text
		$heading = get_theme_mod( 'blog_related_title', __( 'Related Posts', 'wpex' ) );

		// Fallback
		$heading = $heading ? $heading : __( 'Related Posts', 'wpex' );

		// Translate heading with WPML
		$heading = wpex_translate_theme_mod( 'blog_related_title', $heading );

		// Display Heading
		if ( $heading ) { ?>
			<div class="related-posts-title theme-heading">
				<span><?php echo $heading; ?></span>
			</div>
		<?php } ?>

		<div class="wpex-row">
			<?php
			// Set columns
			$columns = apply_filters( 'wpex_related_blog_posts_columns', get_theme_mod( 'blog_related_columns', '3' ) );
			// Set counter var for clearing floats
			$count = $count_all = 0;
			// Loop through related posts
			foreach( $wpex_related_query->posts as $post ) : setup_postdata( $post );
				// Add row for equal heights
				if ( 0 == $count && $has_excerpt ) { ?>
					<div class="match-height-row clr">
				<?php }
				// Increase counter by 1 for each post
				$count++;
				$count_all++;
				// Define post ID
				$post_id = $post->ID; ?>
				<article class="related-post clr col <?php echo wpex_grid_class( $columns ); ?> col-<?php echo $count; ?>">
					<?php
					// Display related post thumbnail
					if ( has_post_thumbnail( $post_id ) ) {
						$image = wpex_image( 'array', '', true ); ?>
						<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="related-post-thumb">
							<img src="<?php echo $image['url']; ?>" alt="<?php echo the_title(); ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />
						</a>
					<?php } else { ?>
						<?php
						// Display post video if video post type
						if ( '' != get_post_meta( $post_id, 'wpex_post_oembed', true ) ) { ?>
							<div class="related-post-video responsive-video-wrap"><?php echo wp_oembed_get( get_post_meta( $post_id, 'wpex_post_oembed', true ) ); ?></div>
						<?php } elseif ( get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode', true ) !== '' ) { ?>
							<div class="related-post-video responsive-video-wrap"><?php echo do_shortcode( get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode', true ) ); ?></div>
						<?php } ?>
					<?php }
					// Display excerpt if enabled
					if ( $has_excerpt ) { ?>
					<div class="related-post-content match-height-content clr">
						<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="related-post-title heading-typography"><?php the_title(); ?></a>
						<div class="related-post-excerpt clr">
							<?php
							// Display excerpt
							$args = array(
								'length'	=> get_theme_mod( 'blog_related_excerpt_length', '15' ),
								'readmore'	=> false,
							);
							wpex_excerpt( $args ); ?>
						</div><!-- related-post-excerpt -->
					</div><!-- .related-post-content -->
					<?php } ?>
				</article>
				<?php
				// Clear counter
				if ( $columns == $count ) {
					// Close equal height row
					if ( $has_excerpt ) {
						echo '</div><!-- .row -->';
					}
					$count=0;
				}
				endforeach;
			// Make sure row is closed for the fit rows style blog
			if ( $has_excerpt ) {
				if ( '4' == $columns && ( $count_all % 4 != 0 ) ) {
					echo '</div><!-- .match-height-row -->';
				}
				if ( '3' == $columns && ( $count_all % 3 != 0 ) ) {
					echo '</div><!-- .match-height-row -->';
				}
				if ( '2' == $columns && ( $count_all % 2 != 0 ) ) {
					echo '</div><!-- .match-height-row -->';
				}
			} ?>
		</div><!-- .wpex-row -->
	</div><!-- .related-posts -->
<?php endif; ?>
<?php wp_reset_postdata(); ?>