<?php
/**
 * Main Header Layout Output
 * Have a look at framework/hooks/actions to see what is hooked into the header
 * See all header parts at partials/header/
 *
 * @package		Total
 * @subpackage	Partials/Header
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

// Return if header is disabled
if ( ! wpex_display_header() ) {
	return;
} ?>

<?php wpex_hook_header_before(); ?>

	<header id="site-header" class="<?php wpex_header_classes(); ?>" role="banner">
		<?php wpex_hook_header_top(); ?>
		<div id="site-header-inner" class="container clr">
			<?php wpex_hook_header_inner(); ?>
		</div><!-- #site-header-inner -->
		<?php wpex_hook_header_bottom(); ?>
	</header><!-- #header -->

<?php wpex_hook_header_after(); ?>