<?php
/**
 * Topbar content
 *
 * @package		Total
 * @subpackage	Partials/Topbar
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

// Add classes for various top bar styles
$classes 		= '';
$topbar_style	= get_theme_mod( 'top_bar_style', 'one' );
if ( 'one' == $topbar_style ) {
	$classes = 'top-bar-left';
} elseif( 'two' == $topbar_style ) {
	$classes = 'top-bar-right';
} elseif( 'three' == $topbar_style ) {
	$classes = 'top-bar-centered';
}

// Get topbar content
$content = get_theme_mod( 'top_bar_content', '[font_awesome icon="phone" margin_right="5px" color="#000"] 1-800-987-654 [font_awesome icon="envelope" margin_right="5px" margin_left="20px" color="#000"] admin@total.com [font_awesome icon="user" margin_right="5px" margin_left="20px" color="#000"] [wp_login_url text="User Login" logout_text="Logout"]' );

// WPML translations
$content = wpex_translate_theme_mod( 'top_bar_content', $content );

// Display topbar content
if ( $content ) : ?>
	<div id="top-bar-content" class="clr <?php echo $classes; ?>">
		<?php echo do_shortcode( $content ); ?>
	</div><!-- #top-bar-content -->
<?php endif; ?>