<?php
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments
 * template simply create your own wpex_comment(), and that function
 * will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @package Total
 * @subpackage functions
 * @author Alexander Clarke
 * @copyright Copyright (c) 2014, Symple Workz LLC
 * @link http://www.wpexplorer.com
 * @since Total 1.0
 */

if ( ! function_exists( 'wpex_comment' ) ) {
	function wpex_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			// Pingback and trackbacks display
			case 'pingback' :
			case 'trackback' : ?>
				<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
					<p><?php _e( 'Pingback:', 'wpex' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'wpex' ), '<span class="ping-meta"><span class="edit-link">', '</span></span>' ); ?></p>
				<?php
			break;
			// Default comment display
			default : ?>
				<li id="li-comment-<?php comment_ID(); ?>">
					<article id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
						<?php if ( get_option( 'show_avatars' ) ){ ?>
							<div class="comment-author vcard">
								<?php echo get_avatar( $comment, 50 ); ?>
							</div><!-- .comment-author -->
						<?php } ?>
						<div class="comment-details clr <?php if ( ! get_option( 'show_avatars' ) ) echo 'no-left-margin'; ?>">
							<header class="comment-meta">
								<cite class="fn"><?php comment_author_link(); ?></cite>
								<span class="comment-date">
								<?php
								// Comment date
								printf( '<time datetime="%1$s">%2$s</time>',
									get_comment_time( 'c' ),
									sprintf( _x( '%1$s at %2$s', '1: date, 2: time', 'wpex' ), get_comment_date(), get_comment_time() )
								); ?>
								</span><!-- .comment-date -->
							</header><!-- .comment-meta -->
							<?php if ( '0' == $comment->comment_approved ) : ?>
								<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'wpex' ); ?></p>
							<?php endif; ?>
							<div class="comment-content">
								<?php comment_text(); ?>
							</div><!-- .comment-content -->
							<div class="comment-reply">
								<?php
								// Comment reply link
								comment_reply_link( array_merge( $args, array(
									'reply_text'	=> __( 'Reply to this message', 'wpex' ),
									'depth'			=> $depth,
									'max_depth'		=> $args['max_depth']
								) ) ); ?>
								</div>
							<?php
							// Edit comment link
							//edit_comment_link( __( 'Edit', 'wpex' ), ' <span class="edit-link">', '<span>' ); ?>
						</div><!-- .comment-details -->
					</article><!-- #comment-## -->
		<?php
		break;
		endswitch;
	}
}