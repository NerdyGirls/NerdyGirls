<?php
// Shortcode params
extract( shortcode_atts( array(
	'title'						=> '',
	'image'						=> '',
	'link'						=> '',
	'img_size'					=> 'full',
	'img_link_large'			=> false,
	'img_link'					=> '',
	'img_link_target'			=> '_self',
	'alignment'					=> '',
	'el_class'					=> '',
	'css_animation'				=> '',
	'img_hover'					=> '',
	'img_caption'				=> '',
	'rounded_image'				=> '',
	'img_filter'				=> '',
	'style'						=> '',
	'border_color'				=> '',
	'css'						=> '',
	'lightbox_video'			=> '',
	'lightbox_custom_img'		=> '',
	'lightbox_gallery'			=> '',
	'lightbox_iframe_type'		=> '',
	'lightbox_video_html5_webm'	=> '',
	'lightbox_dimensions'		=> '',
), $atts ) );

// Output var
$output = '';

// Link
if ( $link ) {
	$img_link = $link;
}

// Style
if ( $style ) {
	$style = $style;
}

// Border color
if ( $border_color ) {
	$border_color = ' vc_box_border_' . $border_color;
}

// Data attributes
$data_attributes = '';

// Get the image ID
$img_id = preg_replace('/[^\d]/', '', $image);

// Get image alt
if( $img_alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ) {
	$img_alt = 'alt="'. $img_alt .'"';
}

// The image string
$img = wpb_getImageBySize( array(
	'attach_id'		=> $img_id,
	'thumb_size'	=> $img_size,
	'class'			=> $style.$border_color
) );

// No Image so bail.
if ( $img == NULL ) {
	$thumbnail 	= '<img src="'. get_template_directory_uri() .'/images/dummy-image.jpg" />';
	$img		= array(
		'thumbnail'	=> $thumbnail
	);
}

$el_class = $this->getExtraClass( $el_class );

$link_to = '';
$a_class='';

// Image hover classes
if ( $img_hover ) {
	$a_class .= 'vcex-img-hover-parent vcex-img-hover-'. $img_hover;
}

// Gallery Lightbox
if ( $lightbox_gallery ) {
	$gallery_ids = explode( ",",$lightbox_gallery );
	if ( $gallery_ids && is_array( $gallery_ids ) ) {
		$gallery_images = '';
		$count=0;
		foreach ( $gallery_ids as $id ) {
			$count++;
			if ( $count != count( $gallery_ids ) ) {
				$gallery_images .= wp_get_attachment_url( $id ) . ',';
			} else {
				$gallery_images .= wp_get_attachment_url( $id );
			}
		}
		$data_attributes .= 'data-gallery="'. $gallery_images .'"';
	}
	$link_to = '#';
	$a_class .= ' wpex-lightbox-gallery';
}

// Link to custom Video
elseif ( '' != $lightbox_video ) {
	$lightbox_video	= esc_url( $lightbox_video );
	if ( '' != $lightbox_dimensions ) {
		$lightbox_dimensions	= explode( 'x', $lightbox_dimensions );
		$lightbox_width			= isset( $lightbox_dimensions[0] ) ? $lightbox_dimensions[0] : '1920';
		$lightbox_height		= isset( $lightbox_dimensions[1] ) ? $lightbox_dimensions[1] : '1080';
		$lightbox_dimensions	= 'width:'. $lightbox_width .',height:'. $lightbox_height .'';
	} else {
		$lightbox_dimensions = 'width:1920,height:1080';
	}
	$link_to = $lightbox_video;
	if ( '' != $lightbox_iframe_type ) {
		if ( 'video_embed' == $lightbox_iframe_type ) {
			$a_class .= ' wpex-lightbox';
			$data_attributes .= ' data-type="iframe"';
			$data_attributes .= ' data-options="'. $lightbox_dimensions .'"';
		} elseif ( 'url' == $lightbox_iframe_type ) {
			$a_class .= ' wpex-lightbox';
			$data_attributes .= ' data-type="iframe"';
			$data_attributes .= ' data-options="'. $lightbox_dimensions .'"';
		} elseif ( 'html5' == $lightbox_iframe_type ) {
			$poster = wp_get_attachment_image_src( $img_id, 'large');
			$poster = $poster[0];
			$a_class .= ' wpex-lightbox';
			$data_attributes .= ' data-type="video"';
			$data_attributes .= ' data-options="'. $lightbox_dimensions .',html5video: { webm: \''. $lightbox_video_html5_webm .'\', poster: \''. $poster .'\' }"';
		} elseif ( 'quicktime' == $lightbox_iframe_type ) {
			$a_class .= ' wpex-lightbox';
			$data_attributes .= ' data-type="video"';
			$data_attributes .= ' data-options="'. $lightbox_dimensions .'"';
		}
	} else {
		$a_class .= ' wpex-lightbox-autodetect';
	}
}

// Link to custom image
elseif ( '' != $lightbox_custom_img ) {
	$link_to = wp_get_attachment_image_src( $lightbox_custom_img, 'large');
	$link_to = $link_to[0];
	$data_attributes .= 'data-type="image"';
	$a_class .= ' wpex-lightbox';
}

// Link to large image lightbox
elseif ( $img_link_large == true ) {
	$link_to = wp_get_attachment_image_src( $img_id, 'large');
	$link_to = $link_to[0];
	$data_attributes .= 'data-type="image"';
	$a_class .= ' wpex-lightbox';
}

// Link to external URL
elseif ( ! empty( $img_link ) ) {
	$link_to = $img_link;
}

// Image output
$image_string = '';

// Open image link
if ( ! empty( $link_to ) ) {
	if ( '_blank' == $img_link_target ) {
		$img_link_target = ' target="_blank"';
	} else {
		$img_link_target = '';	
	}
	$image_string .= '<a class="'.$a_class.'" href="'. esc_url( $link_to ) .'"'. $data_attributes . $img_link_target .'>';
}

// Caption
if ( $img_caption ) {
	$image_string .='<span class="wpb_single_image_caption">'. $img_caption .'</span>';
}

// Style
if ( $style == 'vc_box_shadow_3d' ) {
	$image_string .= '<span class="vc_box_shadow_3d_wrap">' . $img['thumbnail'] . '</span>';
} else {
	$image_string .= $img['thumbnail'];
}

// Close link
if ( ! empty( $link_to ) ) {
	$image_string .='</a>';
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_single_image wpb_content_element clr' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
if ( $css_animation ) {
	$css_class .= $this->getCSSAnimation( $css_animation );
}

if ( $alignment ) {
	$css_class .= ' vc_align_' . $alignment;
}

if ( 'yes' == $rounded_image ) {
	$css_class .= ' vcex-rounded-images';
}

if ( $img_filter && 'none' != $img_filter ) {
	$css_class .= ' vcex-'. $img_filter;
}

$output .= "\n\t".'<div class="'. $css_class .'">';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= "\n\t\t\t". wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_singleimage_heading' ) );
$output .= "\n\t\t\t". $image_string;
$output .= "\n\t\t".'</div><!-- .wpb_wrapper -->';
$output .= "\n\t".'</div><!-- .wpb_single_image -->';

echo $output;