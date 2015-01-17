<?php
/**
 * Recent posts with Thumbnails custom widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package	Total
 * @author Alexander Clarke
 * @copyright Copyright (c) 2014, Symple Workz LLC
 * @link http://www.wpexplorer.com
 * @since Total 1.0
 */

class Wpex_Recent_Posts_Thumb extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wpex_recent_posts_thumb',
			WPEX_THEME_BRANDING . ' - '. __( 'Recent Posts With Thumbnails', 'wpex' ),
			array( 'description' => __( 'Shows a listing of your recent or random posts with their thumbnail for any chosen post type.', 'wpex' ) )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {

		// Set vars
		$title			= isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Recent Posts', 'wpex' );
		$number			= isset( $instance['number'] ) ? $instance['number'] : '3';
		$style			= isset( $instance['style'] ) ? $instance['style'] : 'default';
		$order			= isset( $instance['order'] ) ? $instance['order'] : '';
		$img_height		= ( !empty( $instance['img_height'] ) ) ? intval( $instance['img_height'] ) : '65';
		$img_width		= ( !empty( $instance['img_width'] ) ) ? intval( $instance['img_width'] ) : '65';
		$date			= isset( $instance['date'] ) ? $instance['date'] : '';
		$post_type		= isset( $instance['post_type'] ) ? $instance['post_type'] : '';

		// Important hook
		echo $args['before_widget'];

			// Display title
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title']; 
			}

			// The Query
			global $post;

			// Get current post ID to exclude it
			if ( is_singular() ) {
				$current_post = $post->ID;
			} else {
				$current_post = '';
			}
			$myposts = get_posts( array(
				'post_type'			=> $post_type,
				'numberposts'		=> $number,
				'orderby'			=> $order,
				'no_found_rows'		=> true,
				'suppress_filters'	=> false,
				'meta_key'			=> '_thumbnail_id',
				'exclude'			=> $current_post,
			) );
			if ( $myposts ) : ?>
				<ul class="wpex-widget-recent-posts clr style-<?php echo $style; ?>">
					<?php
					// Loop through posts
					foreach( $myposts as $post ) : setup_postdata( $post );
					// Set featured image dimensions and crop
					if( has_post_thumbnail() ) {
						if ( '9999' == $img_height ){
							$img_crop = false;
						} else {
							$img_crop = true;
						}
						$featured_image = wpex_image_resize( wp_get_attachment_url( get_post_thumbnail_id() ), $img_width, $img_height, $img_crop );
						// Output entry if a featured image exists and can be cropped
						if ( $featured_image ) { ?>
							<li class="clearfix wpex-widget-recent-posts-li">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="wpex-widget-recent-posts-thumbnail">
									<img src="<?php echo $featured_image; ?>" alt="<?php the_title(); ?>" />
								</a>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="wpex-widget-recent-posts-title"><?php the_title(); ?></a>
								<?php
								// Display date if enabled
								if ( $date !== '1' ) { ?>
									<div class="wpex-widget-recent-posts-date"><?php echo get_the_date(); ?></div>
								<?php } ?>
							</li>
						<?php
						}
					} endforeach; ?>
				</ul>
			<?php
			endif;
			// Reset the query
			wp_reset_postdata();
		// Important hook
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// Title
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __('Recent Posts','wpex');
		}

		// Post Type
		if ( isset( $instance[ 'post_type' ] ) ) {
			$post_type = $instance[ 'post_type' ];
		} else {
			$post_type = 'post';
		}

		// Number
		if ( isset( $instance[ 'number' ] ) ) {
			$number = $instance[ 'number' ];
		} else {
			$number = '3';
		}

		// Style
		if ( isset( $instance[ 'style' ] ) ) {
			$style = $instance[ 'style' ];
		} else {
			$style = 'default';
		}

		// Date
		if ( isset( $instance[ 'date' ] ) ) {
			$date = $instance[ 'date' ];
		} else {
			$date = '';
		}

		// Img Height
		if ( isset( $instance[ 'img_height' ] ) ) {
			$img_height = $instance[ 'img_height' ];
		} else {
			$img_height = '65';
		}

		// Img Width
		if ( isset( $instance[ 'img_width' ] ) ) {
			$img_width = $instance[ 'img_width' ];
		} else {
			$img_width = '65';
		}

		// Order
		if ( isset( $instance[ 'order' ] ) ) {
			$order = $instance[ 'order' ];
		} else {
			$order = 'DESC';
		} ?>
		
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'wpex' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title','wpex' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e( 'Style', 'wpex' ); ?></label>
			<br />
			<select class='wpex-select' name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>">
				<option value="default" <?php if($style == 'default') { ?>selected="selected"<?php } ?>><?php _e( 'Small Image', 'wpex' ); ?></option>
				<option value="fullimg" <?php if($style == 'fullimg') { ?>selected="selected"<?php } ?>><?php _e( 'Full Image', 'wpex' ); ?></option>
			</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post Type', 'wpex' ); ?></label> 
		<br />
		<select class='wpex-select' name="<?php echo $this->get_field_name( 'post_type' ); ?>" id="<?php echo $this->get_field_id( 'post_type' ); ?>">
			<option value="post" <?php if($post_type == 'post') { ?>selected="selected"<?php } ?>><?php _e( 'Post', 'wpex' ); ?></option>
			<?php
			// Get Post Types
			$args = array(
				'public'				=> true,
				'_builtin'				=> false,
				'exclude_from_search'	=> false
			); 
			$output = 'names';
			$operator = 'and';
			$get_post_types = get_post_types( $args, $output, $operator );
			foreach ( $get_post_types as $get_post_type ) {
				if( $get_post_type != 'post' ){ ?>
				<option value="<?php echo $get_post_type; ?>" id="<?php $get_post_type; ?>" <?php if( $post_type == $get_post_type ) { ?>selected="selected"<?php } ?>><?php echo ucfirst( $get_post_type ); ?></option>
			<?php } } ?>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e( 'Order', 'wpex' ); ?></label>
		<br />
		<select class='wpex-select' name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_id( 'order' ); ?>">
			<option value="DESC" <?php if( $order == 'DESC' ) { ?>selected="selected"<?php } ?>><?php _e( 'Recent', 'wpex' ); ?></option>
			<option value="rand" <?php if( $order == 'rand' ) { ?>selected="selected"<?php } ?>><?php _e( 'Random', 'wpex' ); ?></option>
			<option value="comment_count" <?php if( $order == 'comment_count' ) { ?>selected="selected"<?php } ?>><?php _e( 'Most Comments', 'wpex' ); ?></option>
			<option value="modified" <?php if( $order == 'modified' ) { ?>selected="selected"<?php } ?>><?php _e( 'Last Modified', 'wpex' ); ?></option>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number', 'wpex' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('img_width'); ?>"><?php _e( 'Image Crop Width', 'wpex' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('img_width'); ?>" name="<?php echo $this->get_field_name('img_width'); ?>" type="text" value="<?php echo $img_width; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('img_height'); ?>"><?php _e( 'Image Crop Height', 'wpex' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('img_height'); ?>" name="<?php echo $this->get_field_name('img_height'); ?>" type="text" value="<?php echo $img_height; ?>" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="checkbox" value="1" <?php checked( '1', $date ); ?> />
			<label for="<?php echo $this->get_field_id('date'); ?>"><?php _e( 'Disable Date?', 'wpex' ); ?></label>
		</p>

	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']		= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['post_type']	= ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
		$instance['number']		= ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
		$instance['order']		= ( ! empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : '';
		$instance['style']		= ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : '';
		$instance['img_height']	= ( ! empty( $new_instance['img_height'] ) ) ? strip_tags( $new_instance['img_height'] ) : '';
		$instance['img_width']	= ( ! empty( $new_instance['img_width'] ) ) ? strip_tags( $new_instance['img_width'] ) : '';
		$instance['date']		= ( ! empty( $new_instance['date'] ) ) ? strip_tags( $new_instance['date'] ) : '';
		return $instance;
	}

}
if ( !function_exists( 'register_wpex_recent_posts_thumb' ) ) {
	function register_wpex_recent_posts_thumb() {
		register_widget( 'Wpex_Recent_Posts_Thumb' );
	}
}
add_action( 'widgets_init', 'register_wpex_recent_posts_thumb' ); ?>