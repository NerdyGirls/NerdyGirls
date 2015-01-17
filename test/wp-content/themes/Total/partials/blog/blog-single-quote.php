<?php
/**
 * Blog entry layout
 *
 * @package		Total
 * @subpackage	Partials/Blog
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-quote-entry-inner clr">
		<span class="fa fa-quote-right"></span>
		<div class="quote-entry-content clr">
			<?php the_content(); ?>
		</div><!-- .quote-entry-content -->
		<div class="quote-entry-author clr">
			<?php the_title(); ?>
		</div><!-- .quote-entry-author -->
		</div><!-- .post-quote-entry-inner -->
</article><!-- .blog-entry -->