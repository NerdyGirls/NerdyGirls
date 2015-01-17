<?php
/**
 * Footer bottom content
 *
 * @package		Total
 * @subpackage	Partials/Footer
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

// Lets bail if this section is disabled
if ( ! get_theme_mod( 'footer_bottom', true ) ) {
	return; 
}

// Get copyright info
$copyright = get_theme_mod( 'footer_copyright_text', 'Copyright <a href="http://wpexplorer-themes.com/total/" target="_blank" title="Total WordPress Theme">Total WordPress Theme</a> - All Rights Reserved' );

// WPML translations
$copyright = wpex_translate_theme_mod( 'footer_copyright_text', $copyright ); ?>

<div id="footer-bottom" class="clr">
	<div id="footer-bottom-inner" class="container clr">
		<?php
		// Display copyright info
		if ( $copyright ) : ?>
			<div id="copyright" class="clr" role="contentinfo">
				<?php echo do_shortcode( $copyright ); ?>
			</div><!-- #copyright -->
		<?php endif; ?>
		<div id="footer-bottom-menu" class="clr">
			<?php
			// Display footer menu
			wp_nav_menu( array(
				'theme_location'	=> 'footer_menu',
				'sort_column'		=> 'menu_order',
				'fallback_cb'		=> false,
			) ); ?>
		</div><!-- #footer-bottom-menu -->
	</div><!-- #footer-bottom-inner -->
</div><!-- #footer-bottom -->