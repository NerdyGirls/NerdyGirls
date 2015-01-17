<?php
/**
 * Registers the icon box shortcode and adds it to the Visual Composer
 *
 * @package     Total
 * @subpackage  Framework/Visual Composer
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       Total 1.4.1
 * @version     1.0.0
 */

/**
 * Register the icon box shortcode
 *
 * @link    http://codex.wordpress.org/Function_Reference/add_shortcode
 * @since   1.4.1
 */
if ( ! function_exists( 'vcex_icon_box_shortcode' ) ) {
    function vcex_icon_box_shortcode( $atts, $content = NULL ) {
        
        extract( shortcode_atts( array(
            'unique_id'                 => '',
            'font_size'                 => '',
            'background'                => '',
            'font_color'                => '',
            'border_radius'             => '',
            'style'                     => 'one',
            'image'                     => '',
            'image_width'               => '',
            'icon'                      => '',
            'icon_color'                => '',
            'icon_width'                => '',
            'icon_height'               => '',
            'icon_alternative_classes'  => '',
            'icon_size'                 => '',
            'icon_background'           => '',
            'icon_border_radius'        => '',
            'icon_bottom_margin'        => '',
            'heading'                   => '',
            'heading_type'              => 'h2',
            'heading_color'             => '',
            'heading_size'              => '',
            'heading_weight'            => '',
            'heading_letter_spacing'    => '',
            'heading_transform'         => '',
            'heading_bottom_margin'     => '',
            'container_left_padding'    => '',
            'container_right_padding'   => '',
            'url'                       => '',
            'url_target'                => '',
            'url_rel'                   => '',
            'css_animation'             => '',
            'padding'                   => '',
            'margin_bottom'             => '',
            'el_class'                  => '',
            'alignment'                 => '',
            'background'                => '',
            'background_image'          => '',
            'background_image_style'    => 'strech',
            'border_color'              => '',
        ), $atts ) );

        // Turn output buffer on
        ob_start();
    
        // Set default vars
        $output = $container_background = '';

        // Seperate icons into a couple groups for styling/html purposes
        $standard_boxes = array( 'one', 'two', 'three', 'seven' );
        $clickable_boxes = array( 'four', 'five', 'six' ); 

        // Main Classes
        $add_classes = 'vcex-icon-box clr vcex-icon-box-'. $style;
        if ( $css_animation ) {
            $css_animation_class = 'wpb_animate_when_almost_visible wpb_'. $css_animation .'';
            $add_classes .= ' '. $css_animation_class;
        }
        if ( $url ) {
            $add_classes .= ' vcex-icon-box-with-link';
        }
        if ( $el_class ) {
            $add_classes .= ' '. $el_class;
        }
        if ( $alignment ) {
            $add_classes .= ' align-'. $alignment;
        } else {
            $add_classes .= ' align-center';
        }
        if ( $icon_background ) {
            $add_classes .= ' with-background';
        }
        
        // Container Style
        $inline_style = '';
        if ( $border_radius && in_array( $style, array( 'four', 'five', 'six' ) ) ) {
            $inline_style .= 'border-radius:'. $border_radius .';';
        }
        if ( $font_size ) {
            $inline_style .= 'font-size:'. intval( $font_size ).'px;';
        }
        if ( $font_color ) {
            $inline_style .= 'color:'. $font_color .';';
        }
        if ( 'four' == $style && $border_color ) {
            $inline_style .= 'border-color:'. $border_color .';';
        }
        if ( 'six' == $style && $icon_background && '' === $background ) {
            $inline_style .= 'background-color:'. $icon_background .';';
        }
        if ( $background && in_array( $style, $clickable_boxes ) ) {
            $inline_style .= 'background-color:'. $background .';';
        }
        if ( $background_image && in_array( $style, $clickable_boxes ) ) {
            $background_image = wp_get_attachment_url( $background_image );
            $inline_style .= 'background-image:url('. $background_image .');';
            $add_classes .= ' vcex-background-'. $background_image_style;
        }
        if ( 'six' == $style && $icon_color ) {
            $inline_style .= 'color:'. $icon_color .';';
        }
        if ( 'one' == $style && $container_left_padding ) {
            $inline_style .= 'padding-left:'. intval( $container_left_padding ) .'px;';
        }
        if ( 'seven' == $style && $container_right_padding ) {
            $inline_style .= 'padding-right:'. intval( $container_right_padding ) .'px;';
        }
        if ( $margin_bottom ) {
            $inline_style .= 'margin-bottom:'. intval( $margin_bottom ) .'px;';
        }
        if ( $padding && in_array( $style, array( 'four', 'five', 'six' ) ) ) {
            $inline_style .= 'padding:'. $padding .'';
        }
        if ( '' != $inline_style ) {
            $inline_style = ' style="' . $inline_style . '"';
        } ?>

        <div class="<?php echo $add_classes; ?>"<?php echo $inline_style; ?>>
            <?php
            /*** URL ***/
            if ( $url ) {
                // Link classes
                $add_classes = 'vcex-icon-box-'. $style .'-link';
                //Link Style
                $inline_style = '';
                if ( 'six' == $style ) {
                    $inline_style .= 'color:'. $icon_color .'';
                }
                if ( '' != $inline_style ) {
                    $inline_style = ' style="' . esc_attr( $inline_style ) . '"';
                }
                // Link target
                if ( 'local' == $url_target ) {
                    $url_target = '';
                    $add_classes .= ' local-scroll-link';
                } elseif ( '_blank' == $url_target ) {
                    $url_target = 'target="_blank"';
                } else {
                    $url_target = '';
                }
                if ( $url_rel ) {
                    $url_rel = 'rel="'. $url_rel .'"';
                } ?>
                <a href="<?php echo esc_url( $url ); ?>" title="<?php echo $heading; ?>" class="<?php echo $add_classes; ?>" <?php echo $url_target; ?> <?php echo $url_rel; ?><?php echo $inline_style; ?>>
            <?php }
            /*** Image ***/
            if ( $image ){
                $image_url = wp_get_attachment_url( $image );
                if ( $image_width ) {
                    $image_width = 'style="width:'. intval( $image_width ) .'px;"';
                } ?>
                <img class="vcex-icon-box-<?php echo $style; ?>-img-alt" src="<?php echo $image_url; ?>" alt="<?php echo $heading; ?>" <?php echo $image_width; ?> />
            <?php
            }
            /*** Icon ***/
            elseif ( $icon ) {
                // Icon Style
                $inline_style = '';
                if( $icon_bottom_margin && in_array( $style, array( 'two', 'three', 'four', 'five', 'six' ) ) ) {
                    $inline_style .= 'margin-bottom:' . intval( $icon_bottom_margin ) .'px;';
                }
                if ( $icon_color ) {
                    $inline_style .= 'color:' . $icon_color . ';';
                }
                if ( $icon_width ) {
                    $inline_style .= 'width:'. intval( $icon_width ) .'px;';
                }
                if ( $icon_height ) {
                    $inline_style .= 'height:'.  intval( $icon_height ) .'px;line-height:'.  intval( $icon_height ) .'px;';
                }
                if ( $icon_size ) {
                    $inline_style .= 'font-size:' . intval( $icon_size ) . 'px;';
                }
                if ( $icon_border_radius ) {
                    $inline_style .= 'border-radius:' . $icon_border_radius . ';';
                }
                if ( $icon_background ) {
                    $inline_style .= 'background-color: ' . $icon_background . ';';
                }
                if ( '' != $inline_style ) {
                    $inline_style = ' style="' . $inline_style . '"';
                }
                // Icon Classes
                $add_classes = 'vcex-icon-box-'. $style .'-icon vcex-icon-box-icon';
                if ( $icon_background ) {
                    $add_classes .= ' vcex-icon-box-icon-with-bg';
                }
                if ( $icon_width || $icon_height ) {
                    $add_classes .= ' no-padding';
                } ?>
                <div class="<?php echo $add_classes; ?>" <?php echo $inline_style; ?>>
                    <?php
                    // Custom icon
                    if ( '' != $icon_alternative_classes ) { ?>
                        <span class="<?php echo $icon_alternative_classes; ?>"></span>
                    <?php } else { ?>
                        <span class="fa fa-<?php echo $icon; ?>"></span>
                    <?php } ?>
                </div>
            <?php }
            /** Heading ***/
            if ( $heading ) {
                // Heading Classes
                $add_classes ='';
                if ( $heading_weight ) {
                    $add_classes .= 'font-weight-'. $heading_weight . ' ';
                }
                if ( $heading_transform ) {
                    $add_classes .= 'text-transform-'. $heading_transform;
                }
                // Heading Style
                $inline_style = '';
                if ( '' != $heading_color ) {
                    $inline_style .= 'color:'. $heading_color .';';
                }
                if ( '' != $heading_size ) {
                    $heading_size = intval( $heading_size );
                    $inline_style .= 'font-size:'. $heading_size .'px;';
                }
                if ( '' != $heading_letter_spacing ) {
                    $inline_style .= 'letter-spacing:'. $heading_letter_spacing .';';
                }
                if ( $heading_bottom_margin ) {
                    $inline_style .= 'margin-bottom:'. intval( $heading_bottom_margin ) .'px;';
                }
                if ( '' != $inline_style ) {
                    $inline_style = ' style="' . $inline_style . '"';
                } ?>
                <<?php echo $heading_type; ?> class="vcex-icon-box-<?php echo $style; ?>-heading <?php echo $add_classes; ?>"<?php echo $inline_style; ?>>
                    <?php echo $heading; ?>
                </<?php echo $heading_type; ?>>
            <?php
            }
            // Close link
            if ( $url && in_array( $style, $standard_boxes ) ) { ?>
                </a>
            <?php }
            // Display if content isn't empty
            if ( $content ) { ?>
                <div class="vcex-icon-box-<?php echo $style; ?>-content clr">
                    <?php echo apply_filters( 'the_content', $content ); ?>
                </div>
            <?php }
            // Close link
            if ( $url && in_array( $style, $clickable_boxes ) ) { ?>
                </a>
            <?php } ?>
        </div>
        
        <?php
        // Return outbut buffer
        return ob_get_clean();
    }
}
add_shortcode( 'vcex_icon_box', 'vcex_icon_box_shortcode' );

/**
 * Register the shortcode for use with the Visual Composer
 *
 * @link    https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
 * @since   1.4.1
 */
if ( ! function_exists( 'vcex_icon_box_shortcode_vc_map' ) ) {
    function vcex_icon_box_shortcode_vc_map() {

        vc_map( array(
            'name'                  => __( 'Icon Box', 'wpex' ),
            'base'                  => 'vcex_icon_box',
            'category'              => WPEX_THEME_BRANDING,
            'icon'                  => 'vcex-icon-box',
            'description'           => __( 'Content box with icon', 'wpex' ),
            'admin_enqueue_css'     => wpex_font_awesome_css_url(),
            'front_enqueue_css'     => wpex_font_awesome_css_url(),
            'params'                => array(
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Extra class name', 'wpex' ),
                    'param_name'    => 'el_class',
                    'description'   => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wpex' ),
                ),

                // Icon
                array(
                    'type'          => 'attach_image',
                    'heading'       => __( 'Icon Image Alternative', 'wpex' ),
                    'param_name'    => 'image',
                    'group'         => __( 'Icon', 'wpex' ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Icon Image Alternative Width', 'wpex' ),
                    'param_name'    => 'image_width',
                    'group'         => __( 'Icon', 'wpex' ),
                    'dependency'    => Array(
                        'element'   => 'image',
                        'not_empty' => true,
                    ),
                ),
                array(
                    'type'          => 'vcex_icon',
                    'heading'       => __( 'Icon', 'wpex' ),
                    'param_name'    => 'icon',
                    'value'         => wpex_get_awesome_icons(),
                    'group'         => __( 'Icon', 'wpex' ),
                    'std'           => 'star',
                    'dependency'    => Array(
                        'element'   => 'image',
                        'value'     => '',
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Icon Font Alternative Classes', 'wpex' ),
                    'param_name'    => 'icon_alternative_classes',
                    'group'         => __( 'Icon', 'wpex' ),
                    'dependency'    => Array(
                        'element'   => 'image',
                        'value'     => '',
                    ),
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => __( 'Icon Color', 'wpex' ),
                    'param_name'    => 'icon_color',
                    'value'         => '',
                    'group'         => __( 'Icon', 'wpex' ),
                    'dependency'    => Array(
                        'element'   => 'image',
                        'value'     => '',
                    ),
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => __( 'Icon Background', 'wpex' ),
                    'param_name'    => 'icon_background',
                    'group'         => __( 'Icon', 'wpex' ),
                    'dependency'    => Array(
                        'element'   => 'image',
                        'value'     => '',
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Icon Border Radius', 'wpex' ),
                    'param_name'    => 'icon_border_radius',
                    'group'         => __( 'Icon', 'wpex' ),
                    'dependency'    => Array(
                        'element'   => 'image',
                        'value'     => '',
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Icon Size In Pixels', 'wpex' ),
                    'param_name'    => 'icon_size',
                    'value'         => '',
                    'group'         => __( 'Icon', 'wpex' ),
                    'dependency'    => Array(
                        'element'   => 'image',
                        'value'     => '',
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Fixed Icon Width', 'wpex' ),
                    'param_name'    => 'icon_width',
                    'value'         => '',
                    'group'         => __( 'Icon', 'wpex' ),
                    'dependency'    => Array(
                        'element'   => 'image',
                        'value'     => '',
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Fixed Icon Height', 'wpex' ),
                    'param_name'    => 'icon_height',
                    'value'         => '',
                    'group'         => __( 'Icon', 'wpex' ),
                    'dependency'    => Array(
                        'element'   => 'image',
                        'value'     => '',
                    ),
                ),

                // Design
                array(
                    'type'          => 'dropdown',
                    'heading'       => __('CSS Animation', 'wpex'),
                    'param_name'    => 'css_animation',
                    'value'         => array(
                        __( 'No', 'wpex' )                  => '',
                        __( 'Top to bottom', 'wpex' )       => 'top-to-bottom',
                        __( 'Bottom to top', 'wpex' )       => 'bottom-to-top',
                        __( 'Left to right', 'wpex' )       => 'left-to-right',
                        __( 'Right to left', 'wpex' )       => 'right-to-left',
                        __( 'Appear from center', 'wpex' )  => 'appear'
                    ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => __( 'Icon Box Style', 'wpex' ),
                    'param_name'    => 'style',
                    'value'         => array(
                        __( 'Left Icon', 'wpex')                    => 'one',
                        __( 'Right Icon', 'wpex')                   => 'seven',
                        __( 'Top Icon', 'wpex' )                    => 'two',
                        __( 'Top Icon Style 2 (legacy)', 'wpex' )   => 'three',
                        __( 'Outlined & Top Icon', 'wpex' )         => 'four',
                        __( 'Boxed & Top Icon', 'wpex' )            => 'five',
                        __( 'Boxed & Top Icon Style 2', 'wpex' )    => 'six',
                    ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => __( 'Alignment', 'wpex' ),
                    'param_name'    => 'alignment',
                    'dependency'    => Array(
                        'element'   => 'style',
                        'value'     => array( 'two' ),
                    ),
                    'value'         => array(
                        __( 'Default', 'wpex')  => '',
                        __( 'Center', 'wpex')   => 'center',
                        __( 'Left', 'wpex' )    => 'left',
                        __( 'Right', 'wpex' )   => 'right',
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Icon Bottom Margin', 'wpex' ),
                    'param_name'    => 'icon_bottom_margin',
                    'dependency'    => Array(
                        'element'   => 'style',
                        'value'     => array( 'two', 'three', 'four', 'five', 'six' ),
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Container Left Padding', 'wpex' ),
                    'param_name'    => 'container_left_padding',
                    'dependency'    => Array(
                        'element'   => 'style',
                        'value'     => array( 'one' )
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Container Right Padding', 'wpex' ),
                    'param_name'    => 'container_right_padding',
                    'dependency'    => Array(
                        'element'   => 'style',
                        'value'     => array( 'seven' )
                    ),
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => __( 'Background Color', 'wpex' ),
                    'param_name'    => 'background',
                    'dependency'    => array(
                        'element'   => 'style',
                        'value'     => array( 'four', 'five', 'six' ),
                    ),
                ),
                array(
                    'type'          => 'attach_image',
                    'heading'       => __( 'Background Image', 'wpex' ),
                    'param_name'    => 'background_image',
                    'value'         => '',
                    'description'   => __( 'Select image from media library.', 'wpex' ),
                    'dependency'    => array(
                        'element'   => 'style',
                        'value'     => array( 'four', 'five', 'six' ),
                    ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => __( 'Background Image Style', 'wpex' ),
                    'param_name'    => 'background_image_style',
                    'value'         => array(
                        __( 'Default', 'wpex' )     => '',
                        __( 'Stretched', 'wpex' )   => 'stretch',
                        __( 'Fixed', 'wpex' )       => 'fixed',
                        __( 'Repeat', 'wpex' )      => 'repeat',
                    ),
                    'dependency'    => array(
                        'element'   => 'style',
                        'value'     => array( 'four', 'five', 'six' ),
                    ),
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => __( 'Border Color', 'wpex' ),
                    'param_name'    => 'border_color',
                    'dependency'    => array(
                        'element'   => 'style',
                        'value'     => array( 'four' ),
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Padding', 'wpex' ),
                    'param_name'    => 'padding',
                    'dependency'    => Array(
                        'element'   => 'style',
                        'value'     => array( 'four', 'five', 'six' )
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Border Radius', 'wpex' ),
                    'param_name'    => 'border_radius',
                    'dependency'    => Array(
                        'element'   => 'style',
                        'value'     => array( 'four', 'five', 'six' )
                    ),
                ),

                // Heading
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Heading', 'wpex' ),
                    'param_name'    => 'heading',
                    'value'         => 'Sample Heading',
                    'group'         => __( 'Heading', 'wpex' ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => __( 'Heading Type', 'wpex' ),
                    'param_name'    => 'heading_type',
                    'value'     => array(
                        'h2'    => 'h2',
                        'h3'    => 'h3',
                        'h4'    => 'h4',
                        'h5'    => 'h5',
                        'div'   => 'div',
                        'span'  => 'span',
                    ),
                    'group'         => __( 'Heading', 'wpex' ),
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => __( 'Heading Font Color', 'wpex' ),
                    'param_name'    => 'heading_color',
                    'group'         => __( 'Heading', 'wpex' ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Heading Font Size', 'wpex' ),
                    'param_name'    => 'heading_size',
                    'group'         => __( 'Heading', 'wpex' ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Heading Font Weight', 'wpex' ),
                    'param_name'    => 'heading_weight',
                    'group'         => __( 'Heading', 'wpex' ),
                    'value'         => '',
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Heading Letter Spacing', 'wpex' ),
                    'param_name'    => 'heading_letter_spacing',
                    'group'         => __( 'Heading', 'wpex' ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => __( 'Heading Text Transform', 'wpex' ),
                    'param_name'    => 'heading_transform',
                    'group'         => __( 'Heading', 'wpex' ),
                    'value'         => array(
                        __( 'Default', 'wpex' )     => '',
                        __( 'None', 'wpex' )        => 'none',
                        __( 'Capitalize', 'wpex' )  => 'capitalize',
                        __( 'Uppercase', 'wpex' )   => 'uppercase',
                        __( 'Lowercase', 'wpex' )   => 'lowercase',
                    ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Heading Bottom Margin', 'wpex' ),
                    'param_name'    => 'heading_bottom_margin',
                    'group'         => __( 'Heading', 'wpex' ),
                ),


                // Content
                array(
                    'type'          => 'textarea_html',
                    'holder'        => 'div',
                    'heading'       => __( 'Content', 'wpex' ),
                    'param_name'    => 'content',
                    'value'         => __( 'Don\'t forget to change this dummy text in your page editor for this lovely icon box.', 'wpex' ),
                    'group'         => __( 'Content', 'wpex' ),
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Content Font Size', 'wpex' ),
                    'param_name'    => 'font_size',
                    'group'         => __( 'Content', 'wpex' ),
                ),
                array(
                    'type'          => 'colorpicker',
                    'heading'       => __( 'Content Font Color', 'wpex' ),
                    'param_name'    => 'font_color',
                    'group'         => __( 'Content', 'wpex' ),
                ),

                // URL
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'URL', 'wpex' ),
                    'param_name'    => 'url',
                    'group'         => __( 'URL', 'wpex' ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => __( 'URL Target', 'wpex' ),
                    'param_name'    => 'url_target',
                     'value'        => array(
                        __( 'Self', 'wpex' )    => '',
                        __( 'Blank', 'wpex' )   => '_blank',
                        __( 'Local', 'wpex' )   => 'local',
                    ),
                    'group'         => __( 'URL', 'wpex' ),
                ),
                array(
                    'type'          => 'dropdown',
                    'heading'       => __( 'URL Rel', 'wpex' ),
                    'param_name'    => 'url_rel',
                    'value'         => array(
                        __( 'None', 'wpex' )        => '',
                        __( 'Nofollow', 'wpex' )    => 'nofollow',
                    ),
                    'group'         => __( 'URL', 'wpex' ),
                ),

                // Margin
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Bottom Margin', 'wpex' ),
                    'param_name'    => 'margin_bottom',
                    'group'         => __( 'Margin', 'wpex' ),
                ),
            )
        ) );

    }
}
add_action( 'vc_before_init', 'vcex_icon_box_shortcode_vc_map' );