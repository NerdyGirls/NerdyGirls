<?php
/**
 * Simple Menu custom widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package		Total
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! class_exists( 'WPEX_Simple_Menu' ) ) {
	class WPEX_Simple_Menu extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			parent::__construct(
				'wpex_simple_menu',
				WPEX_THEME_BRANDING . ' - '. __( 'Simple Custom Menu', 'wpex' ),
				array( 'description' => __( 'Displays a custom menu without any toggles or styling.', 'wpex' ) )
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 1.0.0
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		function widget( $args, $instance ) {

			// Set vars
			$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Recent Posts', 'wpex' );
			$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

			// Important hook
			echo $args['before_widget'];

			// Display title
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title']; 
			}

			if ( $nav_menu ) {
				echo wp_nav_menu( array(
					'fallback_cb'	=> '',
					'menu'			=> $nav_menu
					)
				);
			}

			// Important hook
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 1.0.0
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title']		= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['nav_menu']	= ( ! empty( $new_instance['nav_menu'] ) ) ? strip_tags( $new_instance['nav_menu'] ) : '';
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 1.0.0
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			// Vars
			$title		= isset( $instance['title'] ) ? $instance['title'] : __( 'Browse', 'wpex' );
			$nav_menu	= isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : ''; ?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wpex'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title','wpex'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:','wpex'); ?></label>
				<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
					<?php
					// Get all menus
					$menus = get_terms( 'nav_menu', array(
						'hide_empty'	=> true
					) );
					// Loop through menus to add options
					if ( ! empty( $menus ) ) {
						$nav_menu = $nav_menu ? $nav_menu : '';
						foreach ( $menus as $menu ) {
							echo '<option value="' . $menu->term_id . '"' . selected( $nav_menu, $menu->term_id, false ) . '>'. $menu->name . '</option>';
						}
					} ?>
				</select>
			</p>
			<?php
		}
	}
}

// Register the WPEX_Tabs_Widget custom widget
if ( ! function_exists( 'register_wpex_simple_menu' ) ) {
	function register_wpex_simple_menu() {
		register_widget( 'WPEX_Simple_Menu' );
	}
}
add_action( 'widgets_init', 'register_wpex_simple_menu' );