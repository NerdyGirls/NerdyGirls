<?php
/**
 * Template for the Title + Date Hover overlay style
 *
 * @package		Total
 * @subpackage	Partials/Overlays
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

// Only used for inside position
if ( 'inside_link' != $position ) {
	return;
} ?>

<div class="overlay-title-date-hover">
	<div class="overlay-title-date-hover-inner clr">
		<div class="overlay-title-date-hover-text clr">
			<div class="overlay-title-date-hover-title">
				<?php the_title(); ?>
			</div>
			<div class="overlay-title-date-hover-date">
				<?php echo get_the_date( 'F j, Y' ); ?>
			</div>
		</div>
	</div>
</div>