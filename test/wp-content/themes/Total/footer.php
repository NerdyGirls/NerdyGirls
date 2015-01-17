<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */ ?>

			<?php
			// Main bottom hook
			wpex_hook_main_bottom(); ?>

		</div><!-- #main-content --><?php // main-content opens in header.php ?>
		
		<?php
		// Main after hook
		wpex_hook_main_after(); ?>
		
		<?php
		// Get footer unless disabled
		// See functions/footer-display.php
		if ( wpex_display_footer() ) { ?>

				<?php
				// Open class for the footer reveal option
				if( wpex_footer_reveal_enabled() ) { ?>
					<div class="footer-reveal">
				<?php } ?> 
		
				<?php
				// Footer before hook
				// The callout is added to this hook by default
				wpex_hook_footer_before(); ?>
			
				<?php
				// Display footer Widgets if enabled
				if ( wpex_display_footer_widgets() ) { ?>
					<footer id="footer" class="site-footer">
						<?php
						// Footer top hook
						wpex_hook_footer_top(); ?>
						<div id="footer-inner" class="container clr">
							<div id="footer-row" class="wpex-row clr">
								<?php
								// Footer innner hook
								// The widgets are added to this hook by default
								// See functions/hooks/hooks-default.php
								wpex_hook_footer_inner(); ?>
							</div><!-- .wpex-row -->
						</div><!-- #footer-widgets -->
						<?php
						// Footer bottom hook
						wpex_hook_footer_bottom(); ?>
					</footer><!-- #footer -->
				<?php } // End disable widgets check ?>
				
				<?php
				// Footer after hook
				// The footer bottom area is added to this hook by default
				wpex_hook_footer_after(); ?>

			<?php
			// Close class for the footer reveal option
			if( wpex_footer_reveal_enabled() ) { ?>
				</div><!-- .footer-reveal -->
			<?php } ?> 
		
		<?php } // Disable footer check ?>

		<?php
		// Bottom wrap hook
		wpex_hook_wrap_bottom(); ?>

	</div><!-- #wrap -->

	<?php
	// After wrap hook
	wpex_hook_wrap_after(); ?>

</div><!-- .outer-wrap -->

<?php
// Important WordPress Hook - DO NOT DELETE!
wp_footer(); ?>

</body>
</html>