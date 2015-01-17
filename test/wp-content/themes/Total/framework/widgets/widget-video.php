<?php
/**
 * Video Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package		Total
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! class_exists( 'WPEX_Video_Widget' ) ) {
	class WPEX_Video_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct(
				'wpex_video',
				WPEX_THEME_BRANDING . ' - '. __( 'Video', 'wpex' ),
				array(
					'description' => __( 'Embed a video using the WordPress built-in oEmbed function.', 'wpex' )
				)
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

			// Extract args
			extract( $args );
			$title			= apply_filters( 'widget_title', $instance['title'] );
			$video_url		= isset ( $instance['video_url'] ) ? $instance['video_url'] : '';
			$description	= isset ( $instance['video_description'] ) ? $instance['video_description'] : '';
			
			// Before widget WP hook
			echo $before_widget;

				// Show widget title
				if ( $title ) {
					echo $before_title . $title . $after_title;
				}
				
				// Define video height and width
				$video_size = array(
					'width' => 270
				);
				
				// Show video
				if( $video_url )  {
					echo '<div class="wpex-video-embed clr">' . wp_oembed_get(  $video_url, $video_size ) . '</div>';
				} else { 
					_e( 'You forgot to enter a video URL.', 'wpex' );
				}
				
				// Show video description if field isn't empty
				if( $description ) {
					echo '<div class="wpex-video-widget-description">'. $description . '</div>';
				}

			// After widget WP hook
			echo $after_widget;
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
			$instance 						= $old_instance;
			$instance['title']				= strip_tags($new_instance['title']);
			$instance['video_url']			= strip_tags($new_instance['video_url']);
			$instance['video_description']	= strip_tags($new_instance['video_description']);
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
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array(
				'title'				=> 'Video',
				'id'				=> '',
				'video_url'			=> '',
				'video_description'	=> '',
			) );
			$title				= strip_tags( $instance['title'] );
			$video_url			= strip_tags( $instance['video_url'] );
			$video_description	= strip_tags( $instance['video_description'] ); ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title:', 'wpex' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'video_url' ); ?>">
				<?php _e( 'Video URL ', 'wpex' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'video_url' ); ?>" name="<?php echo $this->get_field_name( 'video_url' ); ?>" type="text" value="<?php echo esc_attr($video_url); ?>" />
				<span style="display:block;padding:5px 0" class="description"><?php _e( 'Enter in a video URL that is compatible with WordPress\'s built-in oEmbed feature.', 'wpex' ); ?> <a href="http://codex.wordpress.org/Embeds" target="_blank"><?php _e( 'Learn More', 'wpex' ); ?></a></span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'video_description' ); ?>">
				<?php _e( 'Description', 'wpex' ); ?></label>
				<textarea rows="5" class="widefat" id="<?php echo $this->get_field_id( 'video_description' ); ?>" name="<?php echo $this->get_field_name( 'video_description' ); ?>" type="text"><?php echo stripslashes($instance['video_description']); ?></textarea>
			</p>
			
		<?php }

	}
}

// Register the WPEX_Tabs_Widget custom widget
if ( ! function_exists( 'register_wpex_video_widget' ) ) {
	function register_wpex_video_widget() {
		register_widget( 'WPEX_Video_Widget' );
	}
}
add_action( 'widgets_init', 'register_wpex_video_widget' );