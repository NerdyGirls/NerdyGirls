<?php
/**
 * Template for the Plus Two hover overlay style
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

<div class="overlay-plus-two-hover"><span class="fa fa-plus"></span></div>