<?php
/**
 * Topbar output
 *
 * @package		Total
 * @subpackage	Partials/Topbar
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

// Return if topbar isn't enabled
if ( ! wpex_is_top_bar_enabled() ) {
	return;
} ?>

<div id="top-bar-wrap" class="clr <?php echo get_theme_mod( 'top_bar_visibility', 'always-visible' ); ?>">
	<div id="top-bar" class="clr container">
		<?php
		// Get topbar content
		get_template_part( 'partials/topbar/topbar', 'content' );
		
		// Get topbar social profiles
		get_template_part( 'partials/topbar/topbar', 'social' ); ?>
	</div><!-- #top-bar -->
</div><!-- #top-bar-wrap -->