<?php
/**
 * Used for your "other" post type entries
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

// Post data
$esc_title	= esc_attr( the_title_attribute( 'echo=0' ) );

// Add extra classes
$classes = array( 'custom-posttype-entry' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<?php
	// Display post thumbnail
	if ( has_post_thumbnail() ) : ?>
		<?php
		// Get cropped featured image
		$wpex_image = wpex_image( 'array' ); ?>
		<div class="custom-posttype-entry-media">
			<a href="<?php the_permalink(); ?>" title="<?php echo $esc_title; ?>" rel="bookmark" class="custom-posttype-entry-media-link <?php wpex_img_animation_classes(); ?>">
				<img src="<?php echo $wpex_image['url']; ?>" alt="<?php echo the_title(); ?>" width="<?php echo $wpex_image['width']; ?>" height="<?php echo $wpex_image['height']; ?>" />
			</a>
		</div><!-- .custom-posttype-entry-media -->
	<?php endif; ?>
	<div class="custom-posttype-entry-content clr">
		<header class="clr">
			<h2 class="custom-posttype-entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo $esc_title; ?>" rel="bookmark"><?php the_title(); ?></a>
			</h2>
			<?php
			// Get post meta, you can create a new file called meta-posttype.php for your custom post type
			get_template_part( 'partials/meta/meta', get_post_type() ); ?>
		</header>
		<div class="custom-posttype-entry-excerpt entry">
			<?php
			// Display excerpt
			$args = array(
				'length'	=> '60',
				'post_id'	=> get_the_ID(),
			);
			wpex_excerpt( $args ); ?>
		</div><!-- .custom-posttype-entry-excerpt -->
		<div class="custom-posttype-entry-readmore clr">
			<a href="<?php the_permalink(); ?>" class="theme-button" title="<?php echo the_title(); ?>">
			<?php _e( 'Read more', 'wpex' ); ?> <span class="readmore-rarr">&rarr;</span>
			</a>
		</div>
		<?php
		// Social sharing links => Disable social sharing links
		//wpex_social_share(); ?>
	</div><!-- .custom-posttype-entry-content -->
</article><!-- .custom-posttype-entry-entry -->