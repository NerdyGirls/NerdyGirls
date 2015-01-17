 <?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments and the comment
 * form. The actual display of comments is handled by a callback to
 * wpex_comment() which is located at functions/comments-callback.php
 *
 * @package		Total
 * @subpackage	Templates
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Return if password is required
if ( post_password_required() ) {
	return;
}

// Add classes to the comments main wrapper
$comments_wrap_classes = ' comments-area';
if ( ! comments_open() && get_comments_number() < 1 ) {
	$comments_wrap_classes .= ' empty-closed-comments';
}
if ( 'full-screen' == wpex_get_post_layout_class() ) {
	$comments_wrap_classes .= ' container';
} ?>

<section id="comments" class="<?php echo $comments_wrap_classes; ?>">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title theme-heading">
			<span><?php
			// Display Comments Title
			$comments_number = number_format_i18n( get_comments_number() );
			if ( '1' == $comments_number ) {
				$comments_title = __( 'This Post Has One Comment', 'wpex' );
			} else {
				$comments_title = sprintf( __( 'This Post Has %s Comments', 'wpex' ), $comments_number );
			}
			$comments_title = apply_filters( 'wpex_comments_title', $comments_title );
			echo $comments_title; ?></span>
		</h2>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'callback' => 'wpex_comment', 'style' => 'ol' ) ); ?>
		</ol><!-- .comment-list -->
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
			<nav class="navigation comment-navigation row clr" role="navigation">
				<h4 class="assistive-text section-heading heading"><span><?php _e( 'Comment navigation', 'wpex' ); ?></span></h4>
				<div class="nav-previous span_12 col clr-margin"><?php previous_comments_link( __( '&larr; Older Comments', 'wpex' ) ); ?></div>
				<div class="nav-next span_12 col"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wpex' ) ); ?></div>
			</nav>
		<?php } // Check for comment navigation ?>
		<?php if ( ! comments_open() && get_comments_number() ) { ?>
			<p class="no-comments"><i class="icon-remove-circle"></i><?php _e( 'Comments are closed.' , 'wpex' ); ?></p>
		<?php } ?>
	<?php endif; // have_comments() ?>
	<?php
	// The comment form
	comment_form( array(
		'cancel_reply_link'	=> '<i class="fa fa-times"></i>'. __ ('Cancel comment reply', 'wpex' ),
	) ); ?>
</section><!-- #comments -->