<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Get settings
if ( get_theme_mod( 'error_page_redirect' ) ) {
	wp_redirect( home_url(), 301 );
	exit;
}

get_header(); ?>
	
	<div class="container clr">
		<section id="primary" class="content-area full-width clr">
			<div id="content" class="clr site-content" role="main">
				<div class="entry clr">
					<?php
					// Display custom text
					if ( $wpex_error_page_text = get_theme_mod( 'error_page_text' ) ) {
						// Translate theme mod
						$wpex_error_page_text = wpex_translate_theme_mod( 'error_page_text', $wpex_error_page_text ); ?>
						<div class="custom-error404-content clr">
							<?php echo apply_filters( 'the_content', $wpex_error_page_text ); ?>
						</div><!-- .custom-error404-content -->
					<?php }
					// Display default text
					else { ?>
						<div class="error404-content clr">
							<h1><?php _e( 'You Broke The Internet!', 'wpex' ) ?></h1>
							<p><?php _e( 'We are just kidding...but sorry the page you were looking for can not be found.', 'wpex' ); ?></p>
						</div><!-- .error404-content -->
					<?php } ?>
				</div><!-- .entry -->
			</div><!-- #content -->
		</section><!-- #primary -->
	</div><!-- .container -->
	
<?php get_footer(); ?>