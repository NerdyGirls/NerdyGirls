<?php
/**
 * Main testimonials entry template part
 *
 * @package		Total
 * @subpackage	Partials/Testimonials
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

// Get counter & increase it
global $wpex_count;
$wpex_count++;

// Testimonial data
$post_id		= get_the_ID();
$author			= get_post_meta( $post_id, 'wpex_testimonial_author', true );
$company		= get_post_meta( $post_id, 'wpex_testimonial_company', true );
$company_url	= get_post_meta( $post_id, 'wpex_testimonial_url', true );
$wpex_image		= wpex_image( 'array' );

// Add classes to the entry
$classes	= array();
$classes[]	= 'testimonial-entry';
$classes[]	= 'col';
$classes[]	= wpex_grid_class( get_theme_mod( 'testimonials_entry_columns', '4' ) );
$classes[]	= 'col-'. $wpex_count; ?>

<article id="#post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="testimonial-entry-content clr">
		<span class="testimonial-caret"></span>
		<?php the_content(); ?>
	</div><!-- .home-testimonial-entry-content-->
	<div class="testimonial-entry-bottom">
		<div class="testimonial-entry-thumb">
			<img src="<?php echo $wpex_image['url']; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
		</div><!-- /testimonial-thumb -->
		<div class="testimonial-entry-meta">
			<?php if ( $author ) : ?>
				<span class="testimonial-entry-author"><?php echo $author; ?></span>
			<?php endif; ?>
			<?php if ( $company ) : ?>
				<?php if ( $company_url ) : ?>
					<a href="<?php echo esc_url( $company_url ); ?>" class="testimonial-entry-company" title="<?php echo $company; ?>" target="_blank"><?php echo $company; ?></a>
				<?php else : ?>
					<span class="testimonial-entry-company"><?php echo $company; ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</div><!-- .testimonial-entry-meta -->
	</div><!-- .home-testimonial-entry-bottom -->
</article><!-- .testimonial-entry -->

<?php
// Reset counter
if( $wpex_count == get_theme_mod( 'testimonials_entry_columns','4' ) ) {
	$wpex_count=0;
} ?>