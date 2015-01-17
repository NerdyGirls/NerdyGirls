<?php
/**
 * Useful global functions for the portfolio
 *
 * @package		Total
 * @subpackage	Framework/Portfolio
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Displays the media (featured image or video ) for the portfolio entries
 *
 * @since Total 1.3.6
 */
if ( ! function_exists( 'wpex_portfolio_entry_media' ) ) {
	function wpex_portfolio_entry_media() {
		get_template_part( 'partials/portfolio/entry', 'media' );
	}
}

/**
 * Displays the details for the portfolio entries
 *
 * @since Total 1.3.6
 */
if ( ! function_exists( 'wpex_portfolio_entry_content' ) ) {
	function wpex_portfolio_entry_content() {
		get_template_part( 'partials/portfolio/entry', 'content' );
	}
}