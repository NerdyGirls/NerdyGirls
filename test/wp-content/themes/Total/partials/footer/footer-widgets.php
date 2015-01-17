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
 * @version		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get footer widgets columns
$columns	= get_theme_mod( 'footer_widgets_columns', '4' );
$grid_class	= wpex_grid_class( $columns ); ?>

<div id="footer-widgets" class="clr <?php if ( '1' == $columns ) echo 'single-col-footer'; ?>">

	<?php
	// Footer box 1 ?>
	<div class="footer-box <?php echo $grid_class; ?> col col-1">
		<?php dynamic_sidebar( 'footer_one' ); ?>
	</div><!-- .footer-one-box -->

	<?php
	// Footer box 2
	if ( $columns > '1' ) : ?>
		<div class="footer-box <?php echo $grid_class; ?> col col-2">
			<?php dynamic_sidebar( 'footer_two' ); ?>
		</div><!-- .footer-one-box -->
	<?php endif; ?>
	
	<?php
	// Footer box 3
	if ( $columns > '2' ) : ?>
		<div class="footer-box <?php echo $grid_class; ?> col col-3 ">
			<?php dynamic_sidebar( 'footer_three' ); ?>
		</div><!-- .footer-one-box -->
	<?php endif; ?>

	<?php
	// Footer box 4
	if ( $columns > '3' ) : ?>
		<div class="footer-box <?php echo $grid_class; ?> col col-4">
			<?php dynamic_sidebar( 'footer_four' ); ?>
		</div><!-- .footer-box -->
	<?php endif; ?>

</div><!-- #footer-widgets -->