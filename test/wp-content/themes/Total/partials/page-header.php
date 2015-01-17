<?php
/**
 * The page header displays at the top of all single pages and posts
 * See framework/page-header.php for all page header related functions.
 *
 * @package		Total
 * @subpackage	Partials/Page Header
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define main vars
$classes				= '';
$post_id				= wpex_get_the_id();
$display_title			= true;
$display_breadcrumbs	= true;
$display_breadcrumbs	= apply_filters( 'wpex_page_header_breadcrumbs', $display_breadcrumbs );
$title_style			= wpex_page_header_style( $post_id );

// Add classes for title style
if ( $title_style ) {
	$classes .= ' '. $title_style .'-page-header';
}

// Disable title if the page header is disabled but the page header background is defined
if ( 'on' == get_post_meta( $post_id, 'wpex_disable_title', true ) && 'background-image' == $title_style ) {
	$display_title = false;
}

// Before Hook
wpex_hook_page_header_before(); ?>
	<header class="page-header<?php echo $classes; ?>">
		<?php
		// Top Hook
		wpex_hook_page_header_top(); ?>
		<div class="container clr page-header-inner">
			<?php
			// Inner hook
			wpex_hook_page_header_inner();

			//  Display header and subheading if enabled
			if ( $display_title ) :

				// Default heading tag is an h1
				$heading_tag = 'h1';

				// Alter the heading for single blog posts and product posts to a span
				if ( is_singular( 'post' ) || is_singular( 'product' ) ) {
					$heading_tag = 'span';
				}

				// Echo the heading_tag
				echo '<'. $heading_tag .' class="page-header-title">'. wpex_page_title( $post_id ) .'</'. $heading_tag .'>';
			
				// Function used to display the subheading defined in the meta options
				wpex_post_subheading( $post_id );

			endif;
			
			// Display built-in breadcrumbs - see functions/breadcrumbs.php
			if ( $display_breadcrumbs ) :
				wpex_display_breadcrumbs( $post_id );
			endif; ?>
		</div><!-- .page-header-inner -->
		<?php
		// Page header overlay
		wpex_page_header_overlay( $post_id );
		// Bottom Hook
		wpex_hook_page_header_bottom(); ?>
	</header><!-- .page-header -->
<?php
// After Hook
wpex_hook_page_header_after(); ?>