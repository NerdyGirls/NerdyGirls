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

<div class="overlay-title-date-visible">
	<div class="overlay-title-date-visible-inner clr">
		<div class="overlay-title-date-visible-text clr">
			<div class="overlay-title-date-visible-title">
				<?php the_title(); ?>
			</div>
			<div class="overlay-title-date-visible-date">
				<?php echo get_the_date( 'F j, Y' ); ?>
			</div>
		</div>
	</div>
</div>