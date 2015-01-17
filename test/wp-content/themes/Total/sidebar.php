<?php
/**
 * Main sidebar area containing your defined widgets
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */ ?>

<?php
// Don't display sidebar for full-screen and full-width layouts
if ( 'full-screen' == wpex_get_post_layout_class() || 'full-width' == wpex_get_post_layout_class() ) {
	return;
} ?>

<?php wpex_hook_sidebar_before(); ?>
<aside id="sidebar" class="sidebar-container sidebar-primary" role="complementary">
	<?php wpex_hook_sidebar_top(); ?>
	<div id="sidebar-inner" class="clr">
		<?php
		// See functions/hooks/actions.php
		// dynamic_sidebar() is added to this Hook
		wpex_hook_sidebar_inner(); ?>
	</div><!-- #sidebar-inner -->
	<?php wpex_hook_sidebar_bottom(); ?>
</aside><!-- #sidebar -->
<?php wpex_hook_sidebar_after(); ?>