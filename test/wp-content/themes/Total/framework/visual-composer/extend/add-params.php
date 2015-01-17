<?php
/**
 * Add new params to Visual Composer modules
 *
 * @package		Total
 * @subpackage	Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */


/*-----------------------------------------------------------------------------------*/
/*	- Toggle Module
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_toggle', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Custom ID', 'wpex' ),
	'param_name'	=> 'id',
	'group'			=> __( 'ID', 'wpe' )
) );


/*-----------------------------------------------------------------------------------*/
/*	- Single Image
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_single_image', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Image alignment', 'wpex' ),
	'param_name'	=> 'alignment',
	'value'			=> wpex_alignments_array(),
	'description'	=> __( 'Select image alignment.', 'wpex' )
) );

if ( function_exists( 'vcex_image_hovers' ) ) {
	vc_add_param( 'vc_single_image', array(
		'type'			=> 'dropdown',

		'heading'		=> __( 'CSS3 Image Link Hover', 'wpex' ),
		'param_name'	=> 'img_hover',
		'value'			=> vcex_image_hovers(),
		'description'	=> __( 'Select your preferred image hover effect. Please note this will only work if the image links to a URL or a large version of itself. Please note these effects may not work in all browsers.', 'wpex' ),
	) );
}

if ( function_exists( 'vcex_image_filters' ) ) {
	vc_add_param( 'vc_single_image', array(
		'type'			=> 'dropdown',
		'heading'		=> __( 'Image Filter', 'wpex' ),
		'param_name'	=> 'img_filter',
		'value'			=> vcex_image_filters(),
		'description'	=> __( 'Select an image filter style.', 'wpex' ),
	) );
}

vc_add_param( 'vc_single_image', array(
	'type'			=> 'checkbox',
	'heading'		=> __( 'Rounded Image?', 'wpex' ),
	'param_name'	=> 'rounded_image',
	'value'			=> Array(
		__( 'Yes please.', 'wpex' )	=> 'yes'
	),
	'description'	=> __( 'For truely rounded images make sure your images are cropped to the same width and height.', 'wpex' ),
) );

vc_add_param( 'vc_single_image', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Image Link Caption', 'wpex' ),
	'param_name'	=> 'img_caption',
	'description'	=> __( 'Use this field to add a caption to any single image with a link.', 'wpex' ),
) );

vc_add_param( 'vc_single_image', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Video, SWF, Flash, URL Lightbox', 'wpex' ),
	'param_name'	=> 'lightbox_video',
	'description'	=> __( 'Enter the URL to a video, SWF file, flash file or a website URL to open in lightbox.', 'wpex' ),
	'group'			=> __( 'Custom Lightbox', 'wpex' ),
) );

vc_add_param( 'vc_single_image', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Lightbox Type', 'wpex' ),
	'param_name'	=> 'lightbox_iframe_type',
	'value'			=> array(
		__( 'Auto Detect', 'wpex' )			=> '',
		__( 'URL', 'wpex' )					=> 'url',
		__( 'Embed/Iframe URL', 'wpex' )	=> 'video_embed',
		__( 'HTML5', 'wpex' )				=> 'html5',
		__( 'Quicktime', 'wpex' )			=> 'quicktime',
	),
	'description'	=> __( 'Auto detect depends on the iLightbox API, so by choosing your type it speeds things up and you also allows for HTTPS support.', 'wpex' ),
	'group'			=> __( 'Custom Lightbox', 'wpex' ),
	'dependency'	=> Array(
		'element'	=> 'lightbox_video',
		'not_empty'	=> true,
	),
) );

vc_add_param( 'vc_single_image', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'HTML5 Webm URL', 'wpex' ),
	'param_name'	=> 'lightbox_video_html5_webm',
	'description'	=> __( 'Enter the URL to a video, SWF file, flash file or a website URL to open in lightbox.', 'wpex' ),
	'group'			=> __( 'Custom Lightbox', 'wpex' ),
	'dependency'	=> Array(
		'element'	=> 'lightbox_iframe_type',
		'value'		=> 'html5',
	),
) );

vc_add_param( 'vc_single_image', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Video/Iframe Lightbox Dimensions', 'wpex' ),
	'param_name'	=> 'lightbox_dimensions',
	'description'	=> __( 'Enter a custom width/height for your lightbox pop-up window. Use format Width x Height. Example: 900x600.', 'wpex' ),
	'group'			=> __( 'Custom Lightbox', 'wpex' ),
) );

vc_add_param( 'vc_single_image', array(
	'type'			=> 'attach_image',
	'admin_label'	=> false,
	'holder'		=> __( 'Custom Image Lightbox', 'wpex' ),
	'heading'		=> __( 'Custom Image Lightbox', 'wpex' ),
	'param_name'	=> 'lightbox_custom_img',
	'description'	=> __( 'Select a custom image to open in lightbox format', 'wpex' ),
	'group'			=> __( 'Custom Lightbox', 'wpex' ),
) );

vc_add_param( 'vc_single_image', array(
	'type'			=> 'attach_images',
	'admin_label'	=> false,
	'heading'		=> __( 'Gallery Lightbox', 'wpex' ),
	'param_name'	=> 'lightbox_gallery',
	'description'	=> __( 'Select images to create a lightbox Gallery.', 'wpex' ),
	'group'			=> __( 'Custom Lightbox', 'wpex' ),
) );


/*-----------------------------------------------------------------------------------*/
/*	- Seperator With Text
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_text_separator', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Element Type', 'wpex' ),
	'param_name'	=> 'element_type',
	'value'			=> array(
		__( 'Div', 'wpex' )	=> 'div',
		__( 'H1', 'wpex' )	=> 'h1',
		__( 'H2', 'wpex' )	=> 'h2',
		__( 'H3', 'wpex' )	=> 'h3',
		__( 'H4', 'wpex' )	=> 'h4',
		__( 'H5', 'wpex' )	=> 'h5',
		__( 'H6', 'wpex' )	=> 'h6',
	),
	'group'			=> __( 'Design', 'wpex' ),
) );

vc_add_param( 'vc_text_separator', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Style', 'wpex' ),
	'param_name'	=> 'style',
	'value'			=> array(
		__( 'Bottom Border', 'wpex' )				=> 'one',
		__( 'Bottom Border With Color', 'wpex' )	=> 'two',
		__( 'Line Through', 'wpex' )				=> 'three',
		__( 'Double Line Through', 'wpex' )			=> 'four',
		__( 'Dotted', 'wpex' )						=> 'five',
		__( 'Dashed', 'wpex' )						=> 'six',
		__( 'Top & Bottom Borders', 'wpex' )		=> 'seven',
		__( 'Graphical', 'wpex' )					=> 'eight',
		__( 'Outlined', 'wpex' )					=> 'nine',
	),
	'group'			=> __( 'Design', 'wpex' ),
) );

vc_add_param( 'vc_text_separator', array(
	'type'			=> 'colorpicker',
	'heading'		=> __( 'Border Color', 'wpex' ),
	'param_name'	=> 'border_color',
	'description'	=> __( 'Select a custom color for your colored border under the title.', 'wpex' ),
	'dependency'	=> Array(
		'element'	=> 'style',
		'value'		=> array( 'two' ),
	),
	'group'			=> __( 'Design', 'wpex' ),
) );

vc_add_param( 'vc_text_separator', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Font size (px or em)', 'wpex' ),
	'param_name'	=> 'font_size',
	'description'	=> __( 'Enter a custom font size for your heading.', 'wpex' ),
	'group'			=> __( 'Design', 'wpex' ),
) );

vc_add_param( 'vc_text_separator', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Font Weight', 'wpex' ),
	'param_name'	=> 'font_weight',
	'description'	=> __( 'Enter a custom font weight for your heading (300,400,600,700,900).', 'wpex' ),
	'group'			=> __( 'Design', 'wpex' ),
) );

vc_add_param( 'vc_text_separator', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Bottom Margin', 'wpex' ),
	'param_name'	=> 'margin_bottom',
	'description'	=> __( 'Enter a bottom margin in pixels for your heading.', 'wpex' ),
	'group'			=> __( 'Design', 'wpex' ),
) );

vc_add_param( 'vc_text_separator', array(
	'type'			=> 'colorpicker',
	'heading'		=> __( 'Background Color', 'wpex' ),
	'param_name'	=> 'span_background',
	'description'	=> __( 'The background color option is used for the background behind the text.', 'wpex' ),
	'dependency'	=> Array(
		'element'	=> 'style',
		'value'		=> array( 'three', 'four', 'five', 'six' ),
		'group'			=> __( 'Design', 'wpex' ),
	)
) );

vc_add_param( 'vc_text_separator', array(
	'type'			=> 'colorpicker',
	'heading'		=> __( 'Font Color', 'wpex' ),
	'param_name'	=> 'span_color',
	'description'	=> __( 'Select a custom font color for your heading.', 'wpex' ),
	'group'			=> __( 'Design', 'wpex' ),
) );

/*-----------------------------------------------------------------------------------*/
/*	- Columns
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_column', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Style', 'wpex' ),
	'param_name'	=> 'style',
	'value'			=> array(
		__( 'Default', 'wpex' )		=> '',
		__( 'Bordered', 'wpex' )	=> 'bordered',
		__( 'Boxed', 'wpex' )		=> 'boxed',
		//__( 'No Spacing', 'wpex' )	=> 'no-spacing',
	),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Visibility', 'wpex' ),
	'param_name'	=> 'visibility',
	'value'			=> wpex_visibility_array(),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Animation', 'wpex' ),
	'param_name'	=> 'css_animation',
	'value'			=> wpex_css_animations_array(),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Typography Style', 'wpex' ),
	'param_name'	=> 'typo_style',
	'value'			=> array(
		__( 'Dark Text', 'wpex' )	=> '',
		__( 'White Text', 'wpex' )	=> 'light',
	),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'checkbox',
	'heading'		=> __( 'Drop Shadow?', 'wpex' ),
	'param_name'	=> 'drop_shadow',
	'value'			=> Array(
		__( 'Yes please.', 'wpex' )	=> 'yes'
	),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'colorpicker',
	'heading'		=> __( 'Background Color', 'wpex' ),
	'param_name'	=> 'bg_color',
	'group'			=> __( 'Background', 'wpex' ),
) );


vc_add_param( 'vc_column', array(
	'type'			=> 'attach_image',
	'heading'		=> __( 'Background Image', 'wpex' ),
	'param_name'	=> 'bg_image',
	'description'	=> __( 'Select image from media library.', 'wpex' ),
	'group'			=> __( 'Background', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Background Image Style', 'wpex' ),
	'param_name'	=> 'bg_style',
	'value'			=> array(
		__( 'Default', 'wpex' )		=> '',
		__( 'Stretched', 'wpex' )	=> 'stretch',
		__( 'Fixed', 'wpex' )		=> 'fixed',
		__( 'Parallax', 'wpex' )	=> 'parallax',
		__( 'Repeat', 'wpex' )		=> 'repeat',
	),
	'dependency' => Array(
		'element'	=> 'background_image',
		'not_empty'	=> true
	),
	'group'			=> __( 'Background', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Border Style', 'wpex' ),
	'param_name'	=> 'border_style',
	'value'			=> wpex_border_styles_array(),
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'colorpicker',
	'heading'		=> __( 'Border Color', 'wpex' ),
	'param_name'	=> 'border_color',
	'value' 		=> '',
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Border Width', 'wpex' ),
	'param_name'	=> 'border_width',
	'description'	=> __( 'Your border width. Example: 1px 1px 1px 1px.', 'wpex' ),
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Margin Top', 'wpex' ),
	'param_name'	=> 'margin_top',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Margin Bottom', 'wpex' ),
	'param_name'	=> 'margin_bottom',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Padding Top', 'wpex' ),
	'param_name'	=> 'padding_top',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Padding Bottom', 'wpex' ),
	'param_name'	=> 'padding_bottom',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Padding Left', 'wpex' ),
	'param_name'	=> 'padding_left',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Padding Right', 'wpex' ),
	'param_name'	=> 'padding_right',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

/*-----------------------------------------------------------------------------------*/
/*	- Inner Columns
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_column_inner', array(
	'type'			=> 'dropdown',
	'class'			=> '',
	'heading'		=> __( 'Style', 'wpex' ),
	'param_name'	=> 'style',
	'value'			=> array(
		__( 'Default', 'wpex' )		=> 'default',
		__( 'Bordered', 'wpex' )	=> 'bordered',
		__( 'Boxed', 'wpex' )		=> 'boxed',
	),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'dropdown',
	'class'			=> '',
	'heading'		=> __( 'Visibility', 'wpex' ),
	'param_name'	=> 'visibility',
	'value'			=> wpex_visibility_array(),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'dropdown',
	'class'			=> '',
	'heading'		=> __( 'Animation', 'wpex' ),
	'param_name'	=> 'css_animation',
	'value'			=> wpex_css_animations_array(),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'dropdown',
	'class'			=> '',
	'heading'		=> __( 'Typography Style', 'wpex' ),
	'param_name'	=> 'typo_style',
	'value'			=> array(
		__( 'Dark Text', 'wpex' )	=> '',
		__( 'White Text', 'wpex' )	=> 'light',
	),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'checkbox',
	'class'			=> '',
	'heading'		=> __( 'Drop Shadow?', 'wpex' ),
	'param_name'	=> 'drop_shadow',
	'value'			=> Array(
		__( 'Yes please.', 'wpex' )	=> 'yes'
	),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'colorpicker',
	'heading'		=> __( 'Background Color', 'wpex' ),
	'param_name'	=> 'bg_color',
	'group'			=> __( 'Background', 'wpex' ),
) );


vc_add_param( 'vc_column_inner', array(
	'type'			=> 'attach_image',
	'heading'		=> __( 'Background Image', 'wpex' ),
	'param_name'	=> 'bg_image',
	'description'	=> __( 'Select image from media library.', 'wpex' ),
	'group'			=> __( 'Background', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Background Image Style', 'wpex' ),
	'param_name'	=> 'bg_style',
	'value'			=> array(
		__( 'Stretched', 'wpex' )	=> 'stretch',
		__( 'Fixed', 'wpex' )		=> 'fixed',
		__( 'Parallax', 'wpex' )	=> 'parallax',
		__( 'Repeat', 'wpex' )		=> 'repeat',
	),
	'dependency' => Array(
		'element'	=> 'background_image',
		'not_empty'	=> true
	),
	'group'			=> __( 'Background', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'dropdown',
	'class'			=> '',
	'heading'		=> __( 'Border Style', 'wpex' ),
	'param_name'	=> 'border_style',
	'value'			=> wpex_border_styles_array(),
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'colorpicker',
	'class'			=> '',
	'heading'		=> __( 'Border Color', 'wpex' ),
	'param_name'	=> 'border_color',
	'value' 		=> '',
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'textfield',
	'class'			=> '',
	'heading'		=> __( 'Border Width', 'wpex' ),
	'param_name'	=> 'border_width',
	'description'	=> __( 'Your border width. Example: 1px 1px 1px 1px.', 'wpex' ),
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'textfield',
	'class'			=> '',
	'heading'		=> __( 'Margin Top', 'wpex' ),
	'param_name'	=> 'margin_top',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'textfield',
	'class'			=> '',
	'heading'		=> __( 'Margin Bottom', 'wpex' ),
	'param_name'	=> 'margin_bottom',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'textfield',
	'class'			=> '',
	'heading'		=> __( 'Padding Top', 'wpex' ),
	'param_name'	=> 'padding_top',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'textfield',
	'class'			=> '',
	'heading'		=> __( 'Padding Bottom', 'wpex' ),
	'param_name'	=> 'padding_bottom',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'textfield',
	'class'			=> '',
	'heading'		=> __( 'Padding Left', 'wpex' ),
	'param_name'	=> 'padding_left',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

vc_add_param( 'vc_column_inner', array(
	'type'			=> 'textfield',
	'class'			=> '',
	'heading'		=> __( 'Padding Right', 'wpex' ),
	'param_name'	=> 'padding_right',
	'group'			=> __( 'Margin & Padding', 'wpex' ),
) );

/*-----------------------------------------------------------------------------------*/
/*	- Tabs
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_tabs', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Style', 'wpex' ),
	'param_name'	=> 'style',
	'value'			=> array(
		__( 'Default', 'wpex' )			=> 'default',
		__( 'Alternative #1', 'wpex' )	=> 'alternative-one',
		__( 'Alternative #2', 'wpex' )	=> 'alternative-two',
	),	
) );

/*-----------------------------------------------------------------------------------*/
/*	- Tours
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_tour', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Style', 'wpex' ),
	'param_name'	=> 'style',
	'value'			=> array(
		__( 'Default', 'wpex' )			=> 'default',
		__( 'Alternative #1', 'wpex' )	=> 'alternative-one',
		__( 'Alternative #2', 'wpex' )	=> 'alternative-two',
	),
	
) );

/*-----------------------------------------------------------------------------------*/
/*	- Rows
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Row ID', 'wpex' ),
	'param_name'	=> 'id',
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Visibility', 'wpex' ),
	'param_name'	=> 'visibility',
	'value'			=> wpex_visibility_array(),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Animation', 'wpex' ),
	'param_name'	=> 'css_animation',
	'value'			=> wpex_css_animations_array(),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Typography Style', 'wpex' ),
	'param_name'	=> 'style',
	'value'			=> array(
		__( 'Dark Text', 'wpex' )	=> '',
		__( 'White Text', 'wpex' )	=> 'light',
	),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Spacing Between Columns', 'wpex' ),
	'param_name'	=> 'column_spacing',
	'value'			=> array(
		__( 'Default', 'wpex' )	=> '',
		'0px'					=> '0px',
		'20px'					=> '20',
		'30px'					=> '30',
		'40px'					=> '40',
		'50px'					=> '50',
		'60px'					=> '60',
	),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Center Row Content', 'wpex' ),
	'param_name'	=> 'center_row',
	'value'			=> Array(
		__( 'No', 'wpex' )	=> '',
		__( 'Yes', 'wpex' )	=> 'yes',
	),
	'description'	=> __( 'Use this option to center the inner content (Horizontally). Useful when using full-width pages.', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Remove Spacing', 'wpex' ),
	'param_name'	=> 'no_margins',
	'value'			=> Array(
		__( 'No', 'wpex' )	=> '',
		__( 'Yes', 'wpex' )	=> 'true',
	),
	'description'	=> __( 'Check this option to remove all spacing between columns', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Full-Width Columns On Tablets', 'wpex' ),
	'param_name'	=> 'tablet_fullwidth_cols',
	'value'			=> Array(
		__( 'No', 'wpex' )	=> '',
		__( 'Yes', 'wpex' )	=> 'yes',
	),
	'description'	=> __( 'Check this box to make all columns inside this row full-width for tablets.', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Minimum Height', 'wpex' ),
	'param_name'	=> 'min_height',
	'description'	=> __( 'You can enter a minimum height for this row.', 'wpex' ),
) );


vc_add_param( 'vc_row', array(
	'type'			=> 'colorpicker',
	'heading'		=> __( 'Background Color', 'wpex' ),
	'param_name'	=> 'bg_color',
	'group'			=> __( 'Background', 'wpex' ),
) );


vc_add_param( 'vc_row', array(
	'type'			=> 'attach_image',
	'heading'		=> __( 'Background Image', 'wpex' ),
	'param_name'	=> 'bg_image',
	'description'	=> __( 'Select image from media library.', 'wpex' ),
	'group'			=> __( 'Background', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Background Image Style', 'wpex' ),
	'param_name'	=> 'bg_style',
	'value'			=> array(
		__( 'Default', 'wpex' )				=> '',
		__( 'Stretched', 'wpex' )			=> 'stretch',
		__( 'Fixed', 'wpex' )				=> 'fixed',
		__( 'Simple Parallax', 'wpex' )		=> 'parallax',
		__( 'Advanced Parallax', 'wpex' )	=> 'parallax-advanced',
		__( 'Repeat', 'wpex' )				=> 'repeat',
	),
	'dependency'	=> Array(
		'element'	=> 'background_image',
		'not_empty'	=> true
	),
	'group'			=> __( 'Background', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Parallax Style', 'wpex' ),
	'param_name'	=> 'parallax_style',
	'value'			=> array(
		__( 'Fixed & Repeat', 'wpex' )		=> '',
		__( 'Fixed & No-Repeat', 'wpex' )	=> 'fixed-no-repeat',
	),
	'dependency'	=> Array(
		'element'	=> 'bg_style',
		'value'		=> array( 'parallax-advanced' ),
	),
	'group'			=> __( 'Background', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Parallax Direction', 'wpex' ),
	'param_name'	=> 'parallax_direction',
	'value'			=> array(
		__( 'Up', 'wpex' )		=> '',
		__( 'Down', 'wpex' )	=> 'down',
		__( 'Left', 'wpex' )	=> 'left',
		__( 'Right', 'wpex' )	=> 'right',
	),
	'group'			=> __( 'Background', 'wpex' ),
	'dependency'	=> Array(
		'element'	=> 'bg_style',
		'value'		=> array( 'parallax-advanced' ),
	),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Parallax Speed', 'wpex' ),
	'param_name'	=> 'parallax_speed',
	'description'	=> __( 'The movement speed, value should be between 0.1 and 1.0. The default is 0.5. A lower number means slower scrolling speed. Be mindful of the background size and the dimensions of your background image when setting this value. Faster scrolling means that the image will move faster, make sure that your background image has enough width or height for the offset.', 'wpex' ),
	'group'			=> __( 'Background', 'wpex' ),
	'dependency'	=> Array(
		'element'	=> 'bg_style',
		'value'		=> array( 'parallax-advanced' ),
	),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'checkbox',
	'heading'		=> __( 'Enable parallax for mobile devices', 'wpex' ),
	'param_name'	=> 'parallax_mobile',
	'value'			=> Array(
		__( 'Check to enable parallax for mobile devices', 'wpex' )	=> 'on',
	),
	'description'	=> __( 'Parallax effects would most probably cause slowdowns when your site is viewed in mobile devices. By default it is disabled.', 'wpex' ),
	'group'			=> __( 'Background', 'wpex' ),
	'dependency'	=> Array(
		'element'	=> 'bg_style',
		'value'		=> array( 'parallax-advanced' ),
	),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'checkbox',
	'heading'		=> __( 'Enable Self Hosted Video Background?', 'wpex' ),
	'param_name'	=> 'video_bg',
	'description'	=> __( 'Check this box to enable the options for a self hosted video background.', 'wpex' ),
	'value'			=> Array(
		__( 'Yes, please', 'wpex' )	=> 'yes'
	),
	'group'			=> __( 'Video', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Video Background Overlay', 'wpex' ),
	'param_name'	=> 'video_bg_overlay',
	'value'			=> array(
		__( 'None', 'wpex' )			=> '',
		__( 'Dark', 'wpex' )			=> 'dark',
		__( 'Dotted', 'wpex' )			=> 'dotted',
		__( 'Diagonal Lines', 'wpex' )	=> 'dashed',
	),
	'dependency'	=> Array(
		'element'	=> 'video_bg',
		'value'		=> 'yes'
	),
	'group'			=> __( 'Video', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Video URL: MP4 URL', 'wpex' ),
	'param_name'	=> 'video_bg_mp4',
	'dependency'	=> Array(
		'element'	=> 'video_bg',
		'value'		=> 'yes'
	),
	'group'			=> __( 'Video', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Video URL: WEBM URL', 'wpex' ),
	'param_name'	=> 'video_bg_webm',
	'dependency'	=> Array(
		'element'	=> 'video_bg',
		'value'		=> 'yes'
	),
	'group'			=> __( 'Video', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Video URL: OGV URL', 'wpex' ),
	'param_name'	=> 'video_bg_ogv',
	'dependency'	=> Array(
		'element'	=> 'video_bg',
		'value'		=> 'yes'
	),
	'group'			=> __( 'Video', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Border Style', 'wpex' ),
	'param_name'	=> 'border_style',
	'value'			=> wpex_border_styles_array(),
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'colorpicker',
	'heading'		=> __( 'Border Color', 'wpex' ),
	'param_name'	=> 'border_color',
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Border Width', 'wpex' ),
	'param_name'	=> 'border_width',
	'description'	=> __( 'Your border width. Example: 1px 1px 1px 1px.', 'wpex' ),
	'group'			=> __( 'Border', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Margin Top', 'wpex' ),
	'param_name'	=> 'margin_top',
	'group'			=> __( 'Margin', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Margin Bottom', 'wpex' ),
	'param_name'	=> 'margin_bottom',
	'group'			=> __( 'Margin', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Margin Left', 'wpex' ),
	'param_name'	=> 'margin_left',
	'group'			=> __( 'Margin', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Margin Right', 'wpex' ),
	'param_name'	=> 'margin_right',
	'group'			=> __( 'Margin', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Padding Top', 'wpex' ),
	'param_name'	=> 'padding_top',
	'group'			=> __( 'Padding', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Padding Bottom', 'wpex' ),
	'param_name'	=> 'padding_bottom',
	'group'			=> __( 'Padding', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Padding Left', 'wpex' ),
	'param_name'	=> 'padding_left',
	'group'			=> __( 'Padding', 'wpex' ),
) );

vc_add_param( 'vc_row', array(
	'type'			=> 'textfield',
	'heading'		=> __( 'Padding Right', 'wpex' ),
	'param_name'	=> 'padding_right',
	'group'			=> __( 'Padding', 'wpex' ),
) );

/*-----------------------------------------------------------------------------------*/
/*	- Custom Heading
/*-----------------------------------------------------------------------------------*/
vc_add_param( 'vc_custom_heading', array(
	'type'			=> 'dropdown',
	'heading'		=> __( 'Enqueue Font Style', 'wpex' ),
	'param_name'	=> 'enqueue_font_style',
	'value'			=> array(
		__( 'Yes', 'wpex' )	=> '',
		__( 'No', 'wpex' )	=> 'false',
	),
	'descriptipn'	=> __( 'If the Google Font you are using is already in use by the theme select No to prevent this font from loading again on the site.', 'wpex' ),
) );