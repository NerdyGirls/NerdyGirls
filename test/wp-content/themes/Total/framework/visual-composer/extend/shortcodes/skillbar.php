<?php
/**
 * Registers the skillbar shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 * @version		1.0.0
 */

if ( ! function_exists('vcex_skillbar_shortcode') ) {
	function vcex_skillbar_shortcode( $atts  ) {
		extract( shortcode_atts( array(
			'title'			=> '',
			'percentage'	=> '100',
			'background'	=> '',
			'box_shadow'	=> '',
			'color'			=> '#6adcfa',
			'class'			=> '',
			'show_percent'	=> 'true',
			'css_animation'	=> '',
			'icon'			=> '',
		), $atts ) );

		// Enable output buffer
		ob_start();

			// Classes
			$classes = 'vcex-skillbar clr';
			if( $class ) {
				$classes .= ' '. $class;
			}
			if ( '' != $css_animation ) {
				$classes .= ' wpb_animate_when_almost_visible wpb_'. $css_animation;
			}

			// Style
			$style = '';
			if( $background ) {
				$style .= 'background:'. $background .';';
			}
			if( 'false' == $box_shadow ) {
				$style .= 'box-shadow: none;';
			}
			if( $style ) {
				$style = ' style="'. $style .'"';
			}

			// Front End composer js
			if ( wpex_is_front_end_composer() ) { ?>
				<script type="text/javascript">
					jQuery(function($){
						$('.vcex-skillbar').each(function(){
							$(this).find('.vcex-skillbar-bar').animate({ width: $(this).attr('data-percent') }, 800 );
						});
					});
				</script>
			<?php } ?>

			<div class="<?php echo $classes; ?>" data-percent="<?php echo $percentage; ?>%"<?php echo $style; ?>>
				<div class="vcex-skillbar-title" style="background: <?php echo $color; ?>;">
					<span>
						<?php if ( $icon ) { ?>
							<i class="fa fa-<?php echo $icon; ?>"></i>
						<?php } ?>
						<?php echo $title; ?>
					</span>
				</div>
				<div class="vcex-skillbar-bar" style="background:<?php echo $color; ?>;">
					<?php if ( $show_percent == 'true' ) { ?>
						<div class="vcex-skill-bar-percent"><?php echo $percentage; ?>%</div>
					<?php } ?>
				</div>
			</div>

		<?php
		// Return content
		return ob_get_clean();
	}
}
add_shortcode( 'vcex_skillbar', 'vcex_skillbar_shortcode' );

if ( ! function_exists( 'vcex_skillbar_shortcode_vc_map' ) ) {
	function vcex_skillbar_shortcode_vc_map() {
		vc_map( array(
			"name"					=> __( "Skill Bar", 'wpex' ),
			"description"			=> __( "Animated skill bar", 'wpex' ),
			"base"					=> "vcex_skillbar",
			'category'				=> WPEX_THEME_BRANDING,
			"icon"					=> "vcex-skill-bar",
			'admin_enqueue_css'		=> wpex_font_awesome_css_url(),
			'front_enqueue_css'		=> wpex_font_awesome_css_url(),
			"params"				=> array(
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Title", 'wpex' ),
					"param_name"	=> "title",
					"admin_label"	=> true,
					"value"			=> "Web Design",
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __("CSS Animation", "wpex"),
					"param_name"	=> "css_animation",
					"value"			=> array(
						__("No", "wpex")					=> '',
						__("Top to bottom", "wpex")			=> "top-to-bottom",
						__("Bottom to top", "wpex")			=> "bottom-to-top",
						__("Left to right", "wpex")			=> "left-to-right",
						__("Right to left", "wpex")			=> "right-to-left",
						__("Appear from center", "wpex")	=> "appear"),
				),
				array(
					"type"			=> "textfield",
					"heading"		=> __( "Percentage", 'wpex' ),
					"param_name"	=> "percentage",
					"value"			=> "70",
				),
				array(
					"type"			=> "colorpicker",
					"heading"		=> __( "Container Background", 'wpex' ),
					"param_name"	=> "background",
					"value"			=> "",
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Container Inset Shadow", 'wpex' ),
					"param_name"	=> "box_shadow",
					"value"			=> array(
						__( "True", "wpex" )	=> "",
						__( "False", "wpex" )	=> "false",
					),
				),
				array(
					"type"			=> "colorpicker",
					"heading"		=> __( "Skill Bar Color", 'wpex' ),
					"param_name"	=> "color",
					"value"			=> "#65C25C",
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> __( "Display % Number", 'wpex' ),
					"param_name"	=> "show_percent",
					"value"			=> array(
						__( "True", "wpex" )	=> "true",
						__( "False", "wpex" )	=> "false",
					),
				),
				array(
					"type"			=> "vcex_icon",
					"class"			=> "",
					"heading"		=> __( "Icon", 'wpex' ),
					"param_name"	=> "icon",
					"admin_label"	=> true,
					"value"			=> 'flag',
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_skillbar_shortcode_vc_map' );