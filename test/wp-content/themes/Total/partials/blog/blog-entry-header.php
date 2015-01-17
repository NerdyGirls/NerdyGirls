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
}

// Get post id
$post_id		= get_the_ID();
$display_avatar	= wpex_post_entry_author_avatar_enabled( $post_id ); ?>

<header class="clr <?php if ( $display_avatar ) echo 'header-with-avatar'; ?>">
	<h2 class="blog-entry-title">
		<a href="<?php echo wpex_permalink( $post_id ); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
			<?php the_title(); ?>
		</a>
	</h2>
	<?php
	// Display avatar if enabled
	if ( $display_avatar ) : ?>
		<div class="blog-entry-author-avatar">
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo __( 'Visit Author Page', 'wpex' ); ?>">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'wpex_blog_entry_author_avatar_size', 74 ) ) ?>
			</a>
		</div>
	<?php endif; ?>
	<?php
	// Display post meta
	get_template_part( 'partials/meta/meta', 'blog' ); ?>
</header>