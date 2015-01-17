<?php
/**
 * Create simple social sharing buttons.
 *
 * @package		Total
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 * @version		1.0.1
 */

if ( ! function_exists( 'wpex_social_share' ) ) {
	function wpex_social_share( $post_id = NULL ) {

		// Get sharing sites
		$defaults		= array( 'twitter', 'facebook', 'google_plus', 'pinterest', 'linkedin' );
		$sharing_sites	= get_theme_mod( 'social_share_sites', $defaults );

		// Return nothing if there aren't any sites enabled
		if ( empty( $sharing_sites ) ) {
			return;
		}

		// Get post id
		$post_id = $post_id ? $post_id : wpex_get_the_id();
		
		// Check if disabled in page settings
		if ( 'on' == get_post_meta( $post_id, 'wpex_disable_social', true ) ) {
			return;
		}

		// Get sharing options
		$position	= get_theme_mod( 'social_share_position', 'horizontal' );
		$style		= get_theme_mod( 'social_share_style', 'minimal' );
		
		// Output var
		$output = '';

		// Get and encode permalink
		$permalink	= get_permalink( $post_id );
		$url		= urlencode( $permalink );

		// Get and encode title
		$args = array(
			'before'	=> false,
			'after'		=> false,
			'echo'		=> false,
			'post'		=> $post_id,
		);
		$title = urlencode( esc_attr( the_title_attribute( $args ) ) );
		
		// Get and encode summary
		$args = array(
			'length'			=> '40',
			'echo'				=> false,
			'ignore_more_tag'	=> true,
		);
		$summary = urlencode( wpex_excerpt( $args ) );

		// Get image
		$img = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
		$img = esc_url( $img );

		// Source URL
		$source = home_url();

		// Sharing block heading
		$heading = get_theme_mod( 'social_share_heading', __( 'Please Share This', 'wpex' ) );

		// Translate Heading with WML
		$heading = wpex_translate_theme_mod( 'social_share_heading', $heading );

		// Add filter to heading for child theming
		$heading = apply_filters( 'wpex_social_share_heading', $heading );

		// Get post layout
		$post_layout = wpex_get_post_layout_class( $post_id );

		// Only display horizontal style menu for mobile devices
		if ( wp_is_mobile() ) {
			$position = 'horizontal';
		}
		
		// Tooltip Style
		if ( is_rtl() ) {
			$tooltip_class = 'tooltip-right';
		} elseif( $position == 'horizontal' ) {
			$tooltip_class = 'tooltip-up';
		} else {
			if ( $post_layout == 'left-sidebar' ) {
				$tooltip_class ='tooltip-left';
			} else {
				$tooltip_class ='tooltip-right';
			}
		}
		
		// Display heading on Boxed layout
		if( $position == 'horizontal' ) { ?>
			<div class="social-share-title theme-heading"><span><?php echo $heading; ?></span></div>
		<?php } ?>

		<ul class="social-share-buttons position-<?php echo $position; ?> style-<?php echo $style; ?> clr">
			<?php
			// Loop through each social sharing site that is enabled
			foreach ( $sharing_sites as $site ) {
				// Twitter
				if ( 'twitter' == $site ) {
					// Get SEO meta and use instead if they exist
					if ( defined( 'WPSEO_VERSION' ) ) {
						if ( $meta = get_post_meta( $post_id, '_yoast_wpseo_twitter-title', true ) ) {
							$title = urlencode( $meta );
						}
						if ( $meta = get_post_meta( $post_id, '_yoast_wpseo_twitter-description', true ) ) {
							$title = $title .': '. $meta;
							$title = urlencode( $title );
						}
					} ?>
					<li class="share-twitter">
						<a href="http://twitter.com/share?text=<?php echo $title; ?>&amp;url=<?php echo $url; ?>" target="_blank" title="<?php _e( 'Share on Twitter', 'wpex' ); ?>" rel="nofollow" class="<?php echo $tooltip_class; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
							<span class="fa fa-twitter"></span>
							<?php if ( $position == 'horizontal' ) { ?>
								<span class="social-share-button-text"><?php _e('Tweet','wpex'); ?></span>
							<?php } ?>
						</a>
					</li>
				<?php }
				// Facebook
				elseif ( 'facebook' == $site ) { ?>
					<li class="share-facebook">
						<a href="http://www.facebook.com/share.php?u=<?php echo $url; ?>" target="_blank" title="<?php _e( 'Share on Facebook', 'wpex' ); ?>" rel="nofollow" class="<?php echo $tooltip_class; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
							<span class="fa fa-facebook"></span>
							<?php if ( $position == 'horizontal' ) { ?>
								<span class="social-share-button-text"><?php _e('Like','wpex'); ?></span>
							<?php } ?>
						</a>
					</li>
				<?php }
				// Google+
				elseif ( 'google_plus' == $site ) { ?>
					<li class="share-googleplus">
						<a title="<?php _e( 'Share on Google+', 'wpex' ); ?>" rel="external" href="https://plus.google.com/share?url=<?php echo $url; ?>" class="<?php echo $tooltip_class; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
							<span class="fa fa-google-plus"></span>
							<?php if ( $position == 'horizontal' ) { ?>
								<span class="social-share-button-text"><?php _e('Plus one','wpex'); ?></span>
							<?php } ?>
						</a>
					</li>
				<?php }
				// Pinterest
				elseif ( 'pinterest' == $site ) { ?>
					<li class="share-pinterest">
						<a href="http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&amp;media=<?php echo $img; ?>&amp;description=<?php echo $summary; ?>" target="_blank" title="<?php _e( 'Share on Pinterest', 'wpex' ); ?>" rel="nofollow" class="<?php echo $tooltip_class; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
							<span class="fa fa-pinterest"></span>
							<?php if ( $position == 'horizontal' ) { ?>
								<span class="social-share-button-text"><?php _e('Pin It','wpex'); ?></span>
							<?php } ?>
						</a>
					</li>
				<?php }
				// LinkedIn
				elseif ( 'linkedin' == $site ) { ?>
					<li class="share-linkedin">
						<a title="<?php _e( 'Share on LinkedIn', 'wpex' ); ?>" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;summary=<?php echo $summary; ?>&amp;source=<?php echo $source; ?>" target="_blank" rel="nofollow" class="<?php echo $tooltip_class; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
							<span class="fa fa-linkedin"></span>
							<?php if ( $position == 'horizontal' ) { ?>
								<span class="social-share-button-text"><?php _e('Share','wpex'); ?></span>
							<?php } ?>
						</a>
					</li>
				<?php }
			} ?>
		</ul>
	<?php
	}
}