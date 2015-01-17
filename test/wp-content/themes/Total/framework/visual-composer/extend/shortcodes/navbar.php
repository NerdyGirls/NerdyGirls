<?php
/**
 * Registers the navbar shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.5.3
 * @version		1.0.0
 */

if ( ! function_exists( 'vcex_navbar_shortcode' ) ) {
	function vcex_navbar_shortcode( $atts ) {

		extract( shortcode_atts( array(
			'unique_id'		=> '',
			'classes'		=> '',
			'menu'			=> '',
			'style'			=> 'buttons',
			'button_color'	=> '',
		),
		$atts ) );

		// Turn output buffer on
		ob_start();

		// Get current post ID
		$post_id = get_the_ID();

		// Create a unique ID for this navbar
		if ( $unique_id ) {
			$unique_id = 'id="'. $unique_id .'"';
		}

		// Classes
		$classes .= ' vcex-navbar clr';
		if ( $style ) {
			$classes .= ' style-'. $style;
		} ?>

		<nav class="<?php echo $classes; ?>" <?php echo $unique_id; ?>>
			<div class="vcex-navbar-inner clr">
				<?php
				// Display Menu
				$menu = wp_get_nav_menu_object( $menu );
				if ( !empty( $menu ) ) {
					$menu_items = wp_get_nav_menu_items( $menu->term_id );
					foreach ( $menu_items as $menu_item ) {
						$active_class='';
						if ( $menu_item->object_id == $post_id ) {
							$active_class='active';
						}
						if ( $menu_item->target ) {
							$target = 'target="'. $menu_item->target .'"';
						} else {
							$target = '';
						} ?>
						<a href="<?php echo esc_url( $menu_item->url ); ?>" title="<?php echo $menu_item->attr_title; ?>" class="vcex-navbar-link <?php echo $active_class; ?>"<?php echo $target; ?>>
							<?php echo $menu_item->title; ?>
						</a>
					<?php }
				} ?>
			</div>
		</nav><!-- .vcex-navbar -->

		<?php // Return outbut buffer
		return ob_get_clean();
		
	}
}
add_shortcode( 'vcex_navbar', 'vcex_navbar_shortcode' );

if ( ! function_exists( 'vcex_navbar_shortcode_vc_map' ) ) {
	function vcex_navbar_shortcode_vc_map() {
		// Create an array of menu items
		$menus_array = array();
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		foreach ( $menus as $menu) {
			$menus_array[$menu->name] = $menu->term_id;
		}
		// Add new shortcode to the Visual Composer
		vc_map( array(
			'name'					=> __( 'Navigation Bar', 'wpex' ),
			'description'			=> __( 'Custom menu navigation bar', 'wpex' ),
			'base'					=> 'vcex_navbar',
			'icon' 					=> 'vcex-navbar',
			'category'				=> WPEX_THEME_BRANDING,
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Unique ID', 'wpex' ),
					'param_name'	=> 'unique_id',
					'value'			=> ''
				),
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Classes', 'wpex' ),
					'param_name'	=> 'classes',
					'value'			=> ''
				),
				array(
					'type'			=> 'dropdown',
					'admin_label'	=> true,
					'heading'		=> __( 'Menu', 'wpex' ),
					'param_name'	=> 'menu',
					'value'			=> $menus_array,
				),
				/*array(
					'type'		=> "dropdown",
					'heading'		=> __( "Style", 'wpex' ),
					'param_name'	=> "style",
					'value'			=> array(
						__( 'Buttons', 'wpex' ) 	=> 'buttons',
						__( 'Navbar', 'wpex' )		=> 'navbar',
					),
				),*/
			)
		) );
	}
}
add_action( 'vc_before_init', 'vcex_navbar_shortcode_vc_map' );