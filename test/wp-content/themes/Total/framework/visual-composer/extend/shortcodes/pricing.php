<?php
/**
 * Registers the pricing shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.2
 */

if ( ! function_exists( 'vcex_pricing_shortcode' ) ) {
	function vcex_pricing_shortcode( $atts, $content = null  ) {
		
		extract( shortcode_atts( array(
			'position'				=> '',
			'featured'				=> 'no',
			'features_padding'		=> '',
			'features_border'		=> '',
			'plan'					=> '',
			'plan_background'		=> '',
			'plan_color'			=> '',
			'plan_padding'			=> '',
			'plan_margin'			=> '',
			'plan_size'				=> '',
			'plan_letter_spacing'	=> '',
			'plan_weight'			=> '',
			'plan_border'			=> '',
			'cost'					=> '',
			'cost_padding'			=> '',
			'cost_border'			=> '',
			'cost_background'		=> '',
			'cost_color'			=> '',
			'cost_size'				=> '',
			'cost_weight'			=> '',
			'per'					=> '',
			'per_display'			=> '',
			'per_color'				=> '',
			'per_weight'			=> '',
			'per_transform'			=> '',
			'per_size'				=> '',
			'button_url'			=> '',
			'button_text'			=> __( 'Button Text', 'wpex' ),
			'button_bg_color'		=> '',
			'button_hover_bg_color'	=> '',
			'button_wrap_padding'	=> '',
			'button_color'			=> '',
			'button_hover_color'	=> '',
			'button_target'			=> 'self',
			'button_rel'			=> 'nofollow',
			'button_border_radius'	=> '',
			'button_size'			=> '',
			'custom_button'			=> '',
			'button_padding'		=> '',
			'button_weight'			=> '',
			'button_letter_spacing'	=> '',
			'button_transform'		=> '',
			'class'					=> '',
			'font_color'			=> '',
			'font_size'				=> '',
			'button_icon_left'		=> '',
			'button_icon_right'		=> '',
		), $atts ) );

		// Turn output buffer on
		ob_start();
		
		// Featured Pricing Class
		$featured_pricing = ( $featured == 'yes' ) ? 'featured' : NULL;

		// Plan style
		$plan_style = '';
		if ( $plan_margin ) {
			$plan_style .= 'margin:'. $plan_margin .';';
		}
		if ( $plan_padding ) {
			$plan_style .= 'padding:'. $plan_padding .';';
		}
		if ( $plan_background ) {
			$plan_style .= 'background:'. $plan_background .';';
		}
		if ( $plan_color ) {
			$plan_style .= 'color:'. $plan_color .';';
		}
		if ( $plan_size ) {
			$plan_style .= 'font-size:'. $plan_size .';';
		}
		if ( $plan_weight ) {
			$plan_style .= 'font-weight:'. $plan_weight .';';
		}
		if ( $plan_letter_spacing ) {
			$plan_style .= 'letter-spacing:'. $plan_letter_spacing .';';
		}
		if ( $plan_border ) {
			$plan_style .= 'border:'. $plan_border .';';
		}
		if ( $plan_style ) {
			$plan_style = ' style="'. $plan_style .'"';
		}

		// Cost Wrap style
		$cost_wrap_style = '';
		if ( $cost_background ) {
			$cost_wrap_style .= 'background:'. $cost_background .';';
		}
		if ( $cost_padding ) {
			$cost_wrap_style .= 'padding:'. $cost_padding .';';
		}
		if ( $cost_border ) {
			$cost_wrap_style .= 'border:'. $cost_border .';';
		}
		if ( $cost_wrap_style ) {
			$cost_wrap_style = ' style="'. $cost_wrap_style .'"';
		}

		// Cost style
		$cost_style = '';
		if ( $cost_color ) {
			$cost_style .= 'color:'. $cost_color .';';
		}
		if ( $cost_size ) {
			$cost_style .= 'font-size:'. $cost_size .';';
		}
		if ( $cost_weight ) {
			$cost_style .= 'font-weight:'. $cost_weight .';';
		}
		if ( $cost_style ) {
			$cost_style = ' style="'. $cost_style .'"';
		}

		// Per style
		$per_style = '';
		if ( $per_display ) {
			$per_style .= 'display:'. $per_display .';';
		}
		if ( $per_size ) {
			$per_style .= 'font-size:'. $per_size .';';
		}
		if ( $per_color ) {
			$per_style .= 'color:'. $per_color .';';
		}
		if ( $per_weight ) {
			$per_style .= 'font-weight:'. $per_weight .';';
		}
		if ( $per_transform ) {
			$per_style .= 'text-transform:'. $per_transform .';';
		}
		if ( $per_style ) {
			$per_style = ' style="'. $per_style .'"';
		}

		// Features Style
		$features_style = '';
		if ( $features_padding ) {
			$features_style .= 'padding:'. $features_padding .';';
		}
		if ( $features_border ) {
			$features_style .= 'border:'. $features_border .';';
		}
		if ( $font_color ) {
			$features_style .= 'color:'. $font_color .';';
		}
		if ( $font_size ) {
			$features_style .= 'font-size:'. $font_size .';';
		}
		if ( $features_style ) {
			$features_style = ' style="'. $features_style .'"';
		}

		// Button Classes
		$button_classes = '';
		if ( $button_weight ) {
			$button_classes .= 'font-weight-'. $button_weight . ' ';
		}
		if ( $button_transform ) {
			$button_classes .= 'text-transform-'. $button_transform;
		}


		// Button Wrap Style
		$button_wrap_style = '';
		if ( $button_wrap_padding ) {
			$button_wrap_style .= 'padding:'. $button_wrap_padding .';';
		}
		if ( $button_wrap_style ) {
			$button_wrap_style = 'style="'. $button_wrap_style .'"';
		}

		// Button Style
		$button_style = '';
		if ( $button_bg_color ) {
			$button_style .= 'background:'. $button_bg_color .';';
		}
		if ( $button_color ) {
			$button_style .= 'color:'. $button_color .';';
		}
		if ( $button_letter_spacing ) {
			$button_style .= 'letter-spacing:'. $button_letter_spacing .';';
		}
		if ( $button_size ) {
			$button_style .= 'font-size:'. $button_size .';';
		}
		if ( $button_padding ) {
			$button_style .= 'padding:'. $button_padding .';';
		}
		if ( $button_border_radius ) {
			$button_style .= 'border-radius:'. $button_border_radius .';';
		}
		if ( $button_style ) {
			$button_style = ' style="'. $button_style .'"';
		}

		// Data attributes
		$data_attr = '';
		if ( $button_hover_bg_color ) {
			$data_attr .= 'data-hover-background="'. $button_hover_bg_color .'"';
			if ( $button_bg_color ) {
				$data_attr .= 'data-original-background="'. $button_bg_color .'"';
			}
		}
		if ( $button_hover_color ) {
			$original_color = '#fff';
			if ( $button_color ) {
				$original_color = $button_color;
			}
			$data_attr .= 'data-hover-color="'. $button_hover_color .'"';
			$data_attr .= 'data-original-color="'. $original_color .'"';
		}
		if ( $data_attr ) {
			$button_classes .= ' wpex-data-hover';
		} ?>

		<div class="vcex-pricing <?php echo $featured_pricing; ?> <?php echo $class; ?>">
			<?php if ( $plan ) { ?>
				<div class="vcex-pricing-header clr"<?php echo $plan_style; ?>>
					<?php echo $plan; ?>
				</div><!-- .vcex-pricing-header -->
			<?php }
			// Cost
			if ( $cost ) { ?>
				<div class="vcex-pricing-cost clr"<?php echo $cost_wrap_style; ?>>
					<div class="vcex-pricing-ammount" <?php echo $cost_style; ?>>
						<?php echo $cost; ?>
					</div><!-- .vcex-pricing-ammount -->
					<?php if ( $per ) { ?>
						<div class="vcex-pricing-per"<?php echo $per_style; ?>>
							<?php echo $per; ?>
						</div><!-- .vcex-pricing-per -->
					<?php } ?>
				</div><!-- .vcex-pricing-cost -->
			<?php }
			// Content
			if ( $content ) { ?>
				<div class="vcex-pricing-content"<?php echo $features_style; ?>>
					<?php echo do_shortcode( $content ); ?>
				</div><!-- .vcex-pricing-content -->
			<?php }
			// Standard Button
			if ( $button_url || $custom_button ) { ?>
				<div class="vcex-pricing-button"<?php echo $button_wrap_style; ?>>
					<?php if ( $button_url ) { ?>
						<a href="<?php echo $button_url; ?>" target="_<?php echo $button_target; ?>" rel="<?php echo $button_rel; ?>" <?php echo $button_style; ?> class="theme-button <?php echo $button_classes; ?>" <?php echo $data_attr; ?>>
							<?php if ( $button_icon_left ) { ?>
								<span class="fa fa-<?php echo $button_icon_left; ?> fa-left"></span>
							<?php } ?>
							<?php echo $button_text; ?>
							<?php if ( $button_icon_right ) { ?>
								<span class="fa fa-<?php echo $button_icon_right; ?> fa-right"></span>
							<?php } ?>
						</a>
					<?php }
					// Custom button
					elseif ( $custom_button ) {
						echo rawurldecode( base64_decode( strip_tags( $custom_button ) ) );
					} ?>
				</div><!-- .vcex-pricing-button -->
			<?php } ?>
		</div><!-- .vcex-pricing -->

		<?php
		// Return outbut buffer
		return ob_get_clean();
	}
}
add_shortcode( 'vcex_pricing', 'vcex_pricing_shortcode' );

if ( ! function_exists( 'vcex_pricing_shortcode_vc_map' ) ) {
	function vcex_pricing_shortcode_vc_map() {

		vc_map( array(
			'name'					=> __( 'Pricing Table', 'wpex' ),
			'description'			=> __( 'Insert a pricing column', 'wpex' ),
			'base'					=> 'vcex_pricing',
			'category'				=> WPEX_THEME_BRANDING,
			'icon'					=> 'vcex-pricing',
			'admin_enqueue_css'		=> wpex_font_awesome_css_url(),
			'front_enqueue_css'		=> wpex_font_awesome_css_url(),
			'params'				=> array(

				// Features
				array(
					'type'			=> 'textarea_html',
					'heading'		=> __( 'Features', 'wpex' ),
					'param_name'	=> 'content',
					'value'			=> '<ul>
											<li>30GB Storage</li>
											<li>512MB Ram</li>
											<li>10 databases</li>
											<li>1,000 Emails</li>
											<li>25GB Bandwidth</li>
										</ul>',
					'description'	=> __('Enter your pricing content. You can use a UL list as shown by default but anything would really work!','wpex'),
					'group'			=> __( 'Features', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Features Padding', 'wpex' ),
					'param_name'	=> 'features_padding',
					'group'			=> __( 'Features', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Features Border', 'wpex' ),
					'param_name'	=> 'features_border',
					'group'			=> __( 'Features', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Features Font Size', 'wpex' ),
					'param_name'	=> 'font_size',
					'group'			=> __( 'Features', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Features Font Color', 'wpex' ),
					'param_name'	=> 'font_color',
					'group'			=> __( 'Features', 'wpex' ),
				),

				// Plan
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Featured', 'wpex' ),
					'param_name'	=> 'featured',
					'value'			=> array(
						__( 'No', 'wpex' )	=> 'no',
						__( 'Yes', 'wpex')	=> 'yes',
					),
					'group'			=> __( 'Plan', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Plan', 'wpex' ),
					'param_name'	=> 'plan',
					'group'			=> __( 'Plan', 'wpex' ),
					'std'			=> __( 'Basic', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Plan Background Color', 'wpex' ),
					'param_name'	=> 'plan_background',
					'group'			=> __( 'Plan', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'plan',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Plan Padding', 'wpex' ),
					'param_name'	=> 'plan_padding',
					'group'			=> __( 'Plan', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'plan',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Plan Font Color', 'wpex' ),
					'param_name'	=> 'plan_color',
					'group'			=> __( 'Plan', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'plan',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Plan Font Size', 'wpex' ),
					'param_name'	=> 'plan_size',
					'group'			=> __( 'Plan', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'plan',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Plan Font Weight', 'wpex' ),
					'param_name'	=> 'plan_weight',
					'group'			=> __( 'Plan', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'plan',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Plan Letter Spacing', 'wpex' ),
					'param_name'	=> 'plan_letter_spacing',
					'group'			=> __( 'Plan', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'plan',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Plan Margin', 'wpex' ),
					'param_name'	=> 'plan_margin',
					'group'			=> __( 'Plan', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'plan',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Plan Border', 'wpex' ),
					'param_name'	=> 'plan_border',
					'group'			=> __( 'Plan', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'plan',
						'not_empty'	=> true,
					),
				),

				// Cost
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Cost', 'wpex' ),
					'param_name'	=> 'cost',
					'group'			=> __( 'Cost', 'wpex' ),
					'std'			=> '$20',
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Cost Background Color', 'wpex' ),
					'param_name'	=> 'cost_background',
					'group'			=> __( 'Cost', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'cost',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Cost Font Color', 'wpex' ),
					'param_name'	=> 'cost_color',
					'group'			=> __( 'Cost', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'cost',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Cost Padding', 'wpex' ),
					'param_name'	=> 'cost_padding',
					'group'			=> __( 'Cost', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'cost',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Cost Border', 'wpex' ),
					'param_name'	=> 'cost_border',
					'group'			=> __( 'Cost', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'cost',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Cost Font Size', 'wpex' ),
					'param_name'	=> 'cost_size',
					'group'			=> __( 'Cost', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'cost',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Cost Font Weight', 'wpex' ),
					'param_name'	=> 'cost_weight',
					'group'			=> __( 'Cost', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'cost',
						'not_empty'	=> true,
					),
				),

				// Per
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Per', 'wpex' ),
					'param_name'	=> 'per',
					'group'			=> __( 'Per', 'wpex' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Per Display', 'wpex' ),
					'param_name'	=> 'per_display',
					'value'			=> array(
						__( 'Default', 'wpex' )			=> '',
						__( 'Inline', 'wpex' )			=> 'inline',
						__( 'Block', 'wpex' )			=> 'block',
						__( 'Inline-Block', 'wpex' )	=> 'inline-block',
					),
					'group'			=> __( 'Per', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'per',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Per Font Color', 'wpex' ),
					'param_name'	=> 'per_color',
					'group'			=> __( 'Per', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'per',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Per Font Size', 'wpex' ),
					'param_name'	=> 'per_size',
					'group'			=> __( 'Per', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'per',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Per Font Weight', 'wpex' ),
					'param_name'	=> 'per_weight',
					'group'			=> __( 'Per', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'per',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Per Text Transform', 'wpex' ),
					'param_name'	=> 'per_transform',
					'group'			=> __( 'Per', 'wpex' ),
					'value'			=> array(
						__( 'Default', 'wpex' )		=> '',
						__( 'None', 'wpex' )		=> 'none',
						__( 'Capitalize', 'wpex' )	=> 'capitalize',
						__( 'Uppercase', 'wpex' )	=> 'uppercase',
						__( 'Lowercase', 'wpex' )	=> 'lowercase',
					),
					'dependency'	=> Array(
						'element'	=> 'per',
						'not_empty'	=> true,
					),
				),

				// Button
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Button Area Padding', 'wpex' ),
					'param_name'	=> 'button_wrap_padding',
					'group'			=> __( 'Button', 'wpex' ),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Button URL', 'wpex' ),
					'param_name'	=> 'button_url',
					'group'			=> __( 'Button', 'wpex' ),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Button Background Color', 'wpex' ),
					'param_name'	=> 'button_bg_color',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Button Background Hover Color', 'wpex' ),
					'param_name'	=> 'button_hover_bg_color',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Button Border Radius', 'wpex' ),
					'param_name'	=> 'button_border_radius',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Button Text', 'wpex' ),
					'param_name'	=> 'button_text',
					'group'			=> __( 'Button', 'wpex' ),
					'default'		=> __( 'Button Text', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Button Font Color', 'wpex' ),
					'param_name'	=> 'button_color',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> __( 'Button Font Hover Color', 'wpex' ),
					'param_name'	=> 'button_hover_color',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Button Font Size', 'wpex' ),
					'param_name'	=> 'button_size',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Button Padding', 'wpex' ),
					'param_name'	=> 'button_padding',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Button Font Weight', 'wpex' ),
					'param_name'	=> 'button_weight',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> __( 'Button Letter Spacing', 'wpex' ),
					'param_name'	=> 'button_letter_spacing',
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Button Text Transform', 'wpex' ),
					'param_name'	=> 'button_transform',
					'group'			=> __( 'Button', 'wpex' ),
					'value'			=> array(
						__( 'Default', 'wpex' )		=> '',
						__( 'None', 'wpex' )		=> 'none',
						__( 'Capitalize', 'wpex' )	=> 'capitalize',
						__( 'Uppercase', 'wpex' )	=> 'uppercase',
						__( 'Lowercase', 'wpex' )	=> 'lowercase',
					),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Button Link Target', 'wpex' ),
					'param_name'	=> 'button_target',
					'value'			=> array(
						__( 'Self', 'wpex')		=> 'self',
						__( 'Blank', 'wpex' )	=> 'blank',
					),
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Button Rel', 'wpex' ),
					'param_name'	=> 'button_rel',
					'value'			=> array(
						__( 'None', 'wpex')			=> 'none',
						__( 'Nofollow', 'wpex' )	=> 'nofollow',
					),
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'not_empty'	=> true,
					),
				),
				array(
					'type'			=> 'textarea_raw_html',
					'heading'		=> __( 'Custom Button HTML', 'wpex' ),
					'param_name'	=> 'custom_button',
					'description'	=> __( 'Enter your custom button HTML, such as your paypal button code.', 'wpex' ),
					'group'			=> __( 'Button', 'wpex' ),
					'dependency'	=> Array(
						'element'	=> 'button_url',
						'value'		=> '',
					),
				),
				array(
					'type'			=> 'vcex_icon',
					'heading'		=> __( 'Button Icon Left', 'wpex' ),
					'param_name'	=> 'button_icon_left',
					'group'			=> __( 'Icons', 'wpex' ),
				),
				array(
					'type'			=> 'vcex_icon',
					'heading'		=> __( 'Button Icon Right', 'wpex' ),
					'param_name'	=> 'button_icon_right',
					'group'			=> __( 'Icons', 'wpex' ),
				),
			)
		) );

	}
}
add_action( 'vc_before_init', 'vcex_pricing_shortcode_vc_map' );