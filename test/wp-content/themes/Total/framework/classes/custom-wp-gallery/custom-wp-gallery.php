<?php
/**
 * Create custom gallery output for the WP gallery shortcode
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

// Not needed in the admin
if ( is_admin() ) {
	return;
}

// Return if disabled
if ( ! get_theme_mod( 'custom_wp_gallery', true ) ) {
	return;
}

// Render the class
if ( ! class_exists( 'WPEX_Custom_WP_Gallery' ) ) {
	class WPEX_Custom_WP_Gallery {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since	1.0.0
		 */
		public function __construct() {
			add_filter( 'post_gallery', array( $this, 'output' ), 10, 2 );
		}
		
		/**
		 * Tweaks the default WP Gallery Output
		 *
		 * @link	http://codex.wordpress.org/Plugin_API/Filter_Reference/post_gallery
		 * @since	1.0.0
		 */
		function output( $output, $attr) {
			
			// Main Variables
			global $post, $wp_locale;
			static $instance = 0;
			$instance++;
			$output = '';

			// Sanitize orderby statement
			if ( isset( $attr['orderby'] ) ) {
				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
				if ( ! $attr['orderby'] ) {
					unset( $attr['orderby'] );
				}
			}

			// Get shortcode attributes
			extract( shortcode_atts( array(
				'order'			=> 'ASC',
				'orderby'		=> 'menu_order ID',
				'id'			=> $post->ID,
				'columns'		=> 3,
				'include'		=> '',
				'exclude'		=> '',
				'img_height'	=> '',
				'img_width'		=> '',
				'size'			=> 'medium',
			), $attr ) );

			// Get post ID
			$id	= intval( $id );

			if ( 'RAND' == $order ) {
				$orderby = 'none';
			}

			if ( ! empty( $include ) ) {
				$include		= preg_replace( '/[^0-9,]+/', '', $include );
				$_attachments	= get_posts(
					array(
						'include'			=> $include,
						'post_status'		=> '',
						'inherit'			=> '',
						'post_type'			=> 'attachment',
						'post_mime_type'	=> 'image',
						'order'				=> $order,
						'orderby'			=> $orderby
					)
				);

			$attachments = array();
				foreach ( $_attachments as $key => $val ) {
					$attachments[$val->ID] = $_attachments[$key];
				}
			} elseif ( ! empty( $exclude ) ) {
				$exclude		= preg_replace( '/[^0-9,]+/', '', $exclude );
				$attachments	= get_children( array(
					'post_parent'		=> $id,
					'exclude'			=> $exclude,
					'post_status'		=> 'inherit',
					'post_type'			=> 'attachment',
					'post_mime_type'	=> 'image',
					'order'				=> $order,
					'orderby'			=> $orderby) );
			} else {
				$attachments	= get_children( array(
					'post_parent'		=> $id,
					'post_status'		=> 'inherit',
					'post_type'			=> 'attachment',
					'post_mime_type'	=> 'image',
					'order'				=> $order,
					'orderby'			=> $orderby
				) );
			}

			if ( empty( $attachments ) ) {
				return '';
			}

			if ( is_feed() ) {
				$output = "\n";
				foreach ( $attachments as $attachment_id => $attachment )
					$output .= wp_get_attachment_link( $attachment_id, $size, true ) . "\n";
				return $output;
			}

			// Get columns #
			$columns = intval( $columns );

			// Float
			$float = is_rtl() ? 'right' : 'left';

			$output .= '<div id="gallery-'. $instance .'" class="wpex-gallery wpex-row lightbox-group clr">';
				
				// Begin Loop
				$count	= 0;
				foreach ( $attachments as $attachment_id => $attachment ) {

					// Increase counter for clearing floats
					$count++;

					// Attachment Vars
					$alt		= get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
					$caption	= $attachment->post_excerpt;
					$full_img	= wp_get_attachment_url( $attachment_id );
				
					// Crop images using built-in function if enabled
					if ( get_theme_mod( 'wpex_image_resizer', true ) ) {

						// Set cropping sizes
						if ( ! $img_width ) {
							$img_width = get_theme_mod( 'gallery_image_width', '9999' );
						}
						if ( ! $img_height ) {
							$img_height = get_theme_mod( 'gallery_image_height', '9999' );
						}

						// Set hard crop
						if ( '9999' == $img_height ) {
							$img_crop = false;
						} else {
							$img_crop = true;
						}
						
						// Set correct cropping sizes
						if ( $columns > 1 ) {
							$img_url = wpex_image_resize( $full_img, $img_width, $img_height, $img_crop );
						} else {
							$img_url = wp_get_attachment_url( $attachment_id );
						}

					} else {
						if ( '1' == $columns ) {
							$size = 'large';
						} elseif ( $columns >= '4' ) {
							$size = 'thumbnail';
						}
						$img_url = wp_get_attachment_image_src( $attachment_id, $size, false );
						$img_url = $img_url[0];
					}
			
					// Start Gallery Item
					$output .= '<figure class="gallery-item '. wpex_grid_class( $columns ) .' col col-'. $count .'">';
					
						// Display image
						$output .= '<a href="'. $full_img .'" title="'. wp_strip_all_tags( $caption ) .'" class="lightbox-group-item">
										<img src="'. $img_url .'" alt="'. $alt .'" />
									</a>';

						// Display Caption
						if ( trim ( $attachment->post_excerpt ) ) {
							$output .= '<figcaption class="gallery-caption">'. wptexturize( $attachment->post_excerpt ) . '</figcaption>';
						}
						
					// Close gallery item div
					$output .= "</figure>";

							
					// Set vars to remove margin on last item of each row and clear floats
					if ( $count == $columns ) {
						$count = '0';
					}
					
				}

			// Close gallery div
			$output .= "</div>\n";

			return $output;
		}
	}
}
new WPEX_Custom_WP_Gallery;