<?php
/**
 * The Header for our theme
 * See partials/header/ for all template files used in the header
 * You can copy any template file and add to your child theme for modifications
 * Just make sure to keep the same path structure.
 *
 * See framework/header/actions.php for all actions attached to your header hooks.
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */ ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<!-- Begin Body -->
<body <?php body_class(); ?>>

<div id="outer-wrap" class="clr">

	<?php
	// Wrap before hook
	wpex_hook_wrap_before(); ?>

	<div id="wrap" class="clr">

		<?php
		// Header top hook
		wpex_hook_wrap_top(); ?>
	
		<?php
		// Header layout - see @ partials/header/header-layout.php
		wpex_header_layout(); ?>
		
		<?php
		// Main before hook
		wpex_hook_main_before(); ?>
	
		<div id="main" class="site-main clr">
	
			<?php
			// Main top hook
			wpex_hook_main_top(); ?>