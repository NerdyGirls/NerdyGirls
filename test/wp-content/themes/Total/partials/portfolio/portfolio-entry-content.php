<?php
/**
 * Portfolio entry content template part
 *
 * @package		Total
 * @subpackage	Partials/Portfolio
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

// Return if disabled for standard entries
if ( ! is_singular( 'portfolio' ) && ! get_theme_mod( 'portfolio_entry_details', true ) ) {
		return;
}

// Return if disabled for related entries
if ( is_singular( 'portfolio' ) && ! get_theme_mod( 'portfolio_related_excerpts', true ) ) {
	return;
} ?>

<div class="portfolio-entry-details clr">
	<?php
	// Match Height div
	if ( wpex_portfolio_match_height() ) { ?>
	<div class="match-height-content">
	<?php } ?>
	<h2 class="portfolio-entry-title">
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a>
	</h2>
	<div class="portfolio-entry-excerpt clr">
		<?php
		// Display excerpt
		$args = array(
			'length'	=> get_theme_mod( 'portfolio_entry_excerpt_length', '20'),
			'readmore'	=> false,
		);
		wpex_excerpt( $args ); ?>
	</div>
	<?php
	// Close Match Height div
	if ( wpex_portfolio_match_height() ) { ?>
	</div>
	<?php } ?>
</div><!-- .portfolio-entry-details -->