<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

get_header();

	// Display a slider on your shop page if defined in the admin
	if ( get_theme_mod( 'woo_shop_slider' ) && is_shop() ) { ?>
		<div class="page-slider clr">
			<?php echo apply_filters( 'the_content', get_theme_mod( 'woo_shop_slider' ) ); ?>
		</div><!-- .page-slider -->
	<?php } ?>

	<div id="content-wrap" class="container clr <?php echo wpex_get_post_layout_class(); ?>">
		<section id="primary" class="content-area clr">
			<div id="content" class="clr site-content" role="main">
				<?php
				// Display category description if enabled here and on tax
				if( ( function_exists( 'wpex_is_woo_tax' ) )
					&& wpex_is_woo_tax()
					&& get_theme_mod( 'category_descriptions', '1' )
					&& 'above_loop' == get_theme_mod( 'woo_category_description_position' )
					&& term_description() ) { ?>
						<div class="woo-tax-description clr">
							<?php echo term_description(); ?>
						</div><!-- .woo-tax-description -->
				<?php } ?>
				<article class="entry-content entry clr">
					<?php woocommerce_content(); ?>
				</article><!-- #post -->
				<?php
				// Display social sharing links
				if ( function_exists( 'wpex_social_share' ) && is_singular() && get_theme_mod( 'social_share_woo', false ) ) {
					wpex_social_share();
				} ?>
			</div><!-- #content -->
		</section><!-- #primary -->
		<?php get_sidebar(); ?>
	</div><!-- #content-wrap -->
	<?php
	// Display next/prev links if enabled
	if ( is_singular( 'product' ) && get_theme_mod( 'woo_next_prev', true ) ) : ?>
		<?php get_template_part( 'partials/next', 'prev' ); ?>
	<?php endif; ?>
<?php get_footer(); ?>