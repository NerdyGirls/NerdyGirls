<?php
/**
 * Registers the newsletter form shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( ! function_exists('vcex_newsletter_form_shortcode') ) {
	function vcex_newsletter_form_shortcode( $atts ) {

		extract( shortcode_atts( array(
			'provider'				=> 'mailchimp',
			'mailchimp_form_action'	=> '',
			'input_width'			=> '100%',
			'input_height'			=> '50px',
			'input_border'			=> '',
			'input_border_radius'	=> '',
			'input_bg'				=> '',
			'input_color'			=> '',
			'input_padding'			=> '',
			'input_font_size'		=> '',
			'input_letter_spacing'	=> '',
			'input_weight'			=> '',
			'input_transform'		=> '',
			'placeholder_text'		=> __( 'Enter your email address', 'wpex' ),
			'submit_text'			=> __( 'Go', 'wpex' ),
			'submit_border'			=> '',
			'submit_border_radius'	=> '',
			'submit_bg'				=> '',
			'submit_hover_bg'		=> '',
			'submit_color'			=> '',
			'submit_hover_color'	=> '',
			'submit_padding'		=> '',
			'submit_font_size'		=> '',
			'submit_height'			=> '',
			'submit_position_right'	=> '',
			'submit_letter_spacing'	=> '',
			'submit_weight'			=> '',
			'submit_transform'		=> '',
		),
		$atts ) );

		// Turn output buffer on
		ob_start();
		
		// Vars
		$output = $input_style = $submit_style = $input_classes = $submit_data = '';

		// Input Style
		if ( $input_border ) {
			$input_style .= 'border:'. $input_border .';';
		}
		if ( $input_border_radius ) {
			$input_style .= 'border-radius:'. $input_border_radius .';';
		}
		if ( $input_padding ) {
			$input_style .= 'padding:'. $input_padding .';';
		}
		if ( $input_letter_spacing ) {
			$input_style .= 'letter-spacing:'. $input_letter_spacing .';';
		}
		if ( $input_height ) {
			$input_height = intval( $input_height );
			$input_style .= 'height:'. $input_height .'px;';
		}
		if ( $input_bg ) {
			$input_style .= 'background:'. $input_bg .';';
		}
		if ( $input_color ) {
			$input_style .= 'color:'. $input_color .';';
		}
		if ( $input_font_size ) {
			$input_style .= 'font-size:'. $input_font_size .';';
		}
		if ( $input_style ) {
			$input_style = ' style="' . esc_attr($input_style) . '"';
		}

		// Input classes
		if ( $input_weight ) {
			$input_classes .= ' font-weight-'. $input_weight;
		}
		if ( $input_transform ) {
			$input_classes .= ' text-transform-'. $input_transform;
		}

		// Submit Style
		if ( $submit_height ) {
			$submit_height = intval( $submit_height );
			$submit_style .= 'height:'. $submit_height .'px;line-height: '. $submit_height .'px;margin-top:-'. $submit_height/2 .'px;';
		}
		if ( $submit_position_right ) {
			$submit_position_right = intval( $submit_position_right );
			$submit_style .= 'right:'. $submit_position_right .'px;';
		}
		if ( $submit_border ) {
			$submit_style .= 'border:'. $submit_border .';';
		}
		if ( $submit_letter_spacing ) {
			$submit_style .= 'letter-spacing:'. $submit_letter_spacing .';';
		}
		if ( $submit_border_radius ) {
			$submit_style .= 'border-radius:'. $submit_border_radius .';';
		}
		if ( $submit_padding ) {
			$submit_style .= 'padding:'. $submit_padding .';';
		}
		if ( $submit_bg ) {
			$submit_style .= 'background:'. $submit_bg .';';
		}
		if ( $submit_color ) {
			$submit_style .= 'color:'. $submit_color .';';
		}
		if ( $submit_font_size ) {
			$submit_style .= 'font-size:'. $submit_font_size .';';
		}
		if ( $submit_style ) {
			$submit_style = ' style="' . esc_attr($submit_style) . '"';
		}

		// Submit classes
		$submit_classes = 'vcex-newsletter-form-button';
		if ( $submit_weight ) {
			 ' font-weight-'. $submit_weight;
		}
		if ( $submit_transform ) {
			$submit_classes .= ' text-transform-'. $submit_transform;
		}

		// Submit Data
		if ( $submit_hover_bg ) {
			$submit_data .= ' data-hover-background="'. $submit_hover_bg .'"';
			$submit_classes .= ' wpex-data-hover';
		}
		if ( $submit_hover_color ) {
			$submit_data .= ' data-hover-color="'. $submit_hover_color .'"';
		}

		// Load inline js for data hover
		if ( $submit_hover_bg || $submit_hover_color ) {
			vcex_data_hover_js();
		}
		
		// Mailchimp
		if ( $provider == 'mailchimp' ) { ?>
			<div class="vcex-newsletter-form clr">
				<!-- Begin MailChimp Signup Form -->
				<div id="mc_embed_signup" class="vcex-newsletter-form-wrap" style="width: <?php echo $input_width; ?>;">
					<form action="<?php echo $mailchimp_form_action; ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<input type="email" value="<?php echo $placeholder_text; ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="EMAIL" class="required email<?php echo $input_classes; ?>" id="mce-EMAIL"<?php echo $input_style; ?>>
						<button type="submit" value="" name="subscribe" id="mc-embedded-subscribe" class="<?php echo $submit_classes; ?>"<?php echo $submit_style; ?><?php echo $submit_data; ?>>
							<?php echo $submit_text; ?>
						</button>
					</form>
				</div><!--End mc_embed_signup-->
			</div><!-- .vcex-newsletter-form -->
		<?php }

		// Return outbut buffer
		return ob_get_clean();
		
	}
}
add_shortcode( 'vcex_newsletter_form', 'vcex_newsletter_form_shortcode' );

if ( ! function_exists( 'vcex_newsletter_form_shortcode_vc_map' ) ) {
	function vcex_newsletter_form_shortcode_vc_map() {

		// Useful arrays
		$font_weights		= wpex_font_weights_array();
		$text_transforms	= wpex_text_transform_array();

		// VC Map
		vc_map( array(
			'name'					=> __( 'Mailchimp Form', 'wpex' ),
			'description'			=> __( 'Mailchimp subscription form', 'wpex' ),
			'base'					=> 'vcex_newsletter_form',
			'category'				=> WPEX_THEME_BRANDING,
			'icon' 					=> 'vcex-newsletter',
			'params'				=> array(

				// General
				array(
					'type'			=> 'textfield',
					'heading'		=> __( "Mailchimp Form Action", 'wpex' ),
					'param_name'	=> "mailchimp_form_action",
					'value'			=> "http://domain.us1.list-manage.com/subscribe/post?u=numbers_go_here",
					"description"	=> __( "Enter the MailChimp form action URL.","wpex") .' <a href="http://docs.shopify.com/support/configuration/store-customization/where-do-i-get-my-mailchimp-form-action?ref=wpexplorer" target="_blank">'. __('Learn More','wpex') .' &rarr;</a>',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Placeholder Text', 'wpex' ),
					'param_name'	=> 'placeholder_text',
					'std'			=> __('Enter your email address','wpex'),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Submit Button Text', 'wpex' ),
					'param_name'	=> 'submit_text',
					'std'			=> __( 'Go', 'wpex' ),
				),

				// Input
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( "Background", 'wpex' ),
					'param_name'	=> "input_bg",
					'dependency'	=> Array(
						'element'	=> "mailchimp_form_action",
						'not_empty'	=> true
					),
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Color', 'wpex' ),
					'param_name'	=> 'input_color',
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Width', 'wpex' ),
					'param_name'	=> 'input_width',
					'value'			=> '100%',
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Height', 'wpex' ),
					'param_name'	=> 'input_height',
					'value'			=> '50px',
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Padding', 'wpex' ),
					'param_name'	=> 'input_padding',
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Border', 'wpex' ),
					'param_name'	=> 'input_border',
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Border Radius', 'wpex' ),
					'param_name'	=> 'input_border_radius',
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Font Size', 'wpex' ),
					'param_name'	=> 'input_font_size',
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Letter Spacing', 'wpex' ),
					'param_name'	=> 'input_letter_spacing',
					'group'			=> __( 'Input', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Font Weight', 'wpex' ),
					'param_name'	=> 'input_weight',
					'group'			=> __( 'Input', 'wpex' ),
					'std'			=> '',
					'value'			=> $font_weights,
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Text Transform', 'wpex' ),
					'param_name'	=> 'input_transform',
					'group'			=> __( 'Input', 'wpex' ),
					'value'			=> $text_transforms,
				),

				// Submit
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Background', 'wpex' ),
					'param_name'	=> 'submit_bg',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Background: Hover', 'wpex' ),
					'param_name'	=> 'submit_hover_bg',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Color', 'wpex' ),
					'param_name'	=> 'submit_color',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Color: Hover', 'wpex' ),
					'param_name'	=> 'submit_hover_color',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Position Right', 'wpex' ),
					'param_name'	=> 'submit_position_right',
					'std'			=> '20px',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Height', 'wpex' ),
					'param_name'	=> 'submit_height',
					'std'			=> '30px',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Padding', 'wpex' ),
					'param_name'	=> 'submit_padding',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Border', 'wpex' ),
					'param_name'	=> 'submit_border',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Border Radius', 'wpex' ),
					'param_name'	=> 'submit_border_radius',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Font Size', 'wpex' ),
					'param_name'	=> 'submit_font_size',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Letter Spacing', 'wpex' ),
					'param_name'	=> 'submit_letter_spacing',
					'group'			=> __( 'Submit', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Font Weight', 'wpex' ),
					'param_name'	=> 'submit_weight',
					'group'			=> __( 'Submit', 'wpex' ),
					'std'			=> '',
					'value'			=> $font_weights,
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Text Transform', 'wpex' ),
					'param_name'	=> 'submit_transform',
					'group'			=> __( 'Submit', 'wpex' ),
					'std'			=> '',
					'value'			=> $text_transforms,
				),
			)

		) );
	}
}
add_action( 'vc_before_init', 'vcex_newsletter_form_shortcode_vc_map' );