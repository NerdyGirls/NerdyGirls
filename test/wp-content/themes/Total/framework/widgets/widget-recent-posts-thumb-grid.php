<?php
/**
 * Recent posts grid custom widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package	Total
 * @author Alexander Clarke
 * @copyright Copyright (c) 2014, Symple Workz LLC
 * @link http://www.wpexplorer.com
 * @since Total 1.0
 */

class Wpex_Recent_Posts_Thumb_Grid extends WP_Widget {
	
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wpex_recent_posts_thumb_grid',
			WPEX_THEME_BRANDING . ' - '. __( 'Recent Posts Thumbnail Grid', 'wpex' ),
			array( 'description' => __( 'Displays a grid of featured images for your post type of choice.', 'wpex' ) )
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
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Recent Posts', 'wpex' );
		$title = apply_filters( 'widget_title', $title );
		$post_type = isset($instance['post_type']) ? $instance['post_type'] : '';
		$number = isset($instance['number']) ? $instance['number'] : '';
		$order = isset($instance['order']) ? $instance['order'] : '';
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title']; 
			} ?>
			<ul class="wpex-recent-posts-thumb-grid clearfix">
				<?php
				global $post;
				// Get current post ID to exclude it
				if ( $post ) {
					$current_post = $post->ID;
				} else {
					$current_post = '';
				}
				// Get posts
				$myposts = get_posts( array(
					'post_type'			=> $post_type,
					'numberposts'		=> $number,
					'orderby'			=> $order,
					'no_found_rows'		=> true,
					'meta_key'			=> '_thumbnail_id',
					'suppress_filters'	=> false,
					'exclude'			=> $current_post,
				) );
				$count=0;
				foreach( $myposts as $post ) : setup_postdata($post);
					$count++;
					if( has_post_thumbnail() ) { ?>
						<li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo wpex_image_resize( wp_get_attachment_url( get_post_thumbnail_id() ), '65', '65',  true ); ?>" alt="<?php the_title(); ?>" /></a></li>
				<?php
				}
				if ( $count == 3 ) {
					$count = '0';
				}
				endforeach;
				wp_reset_postdata(); ?>
			</ul>
		<?php echo $args['after_widget'];
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
			$number = '6';
		}

		// Order
		if ( isset( $instance[ 'order' ] ) ) {
			$order = $instance[ 'order' ];
		} else {
			$order = 'DESC';
		} ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'wpex' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title','wpex'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e( 'Post Type', 'wpex' ); ?></label>
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
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order', 'wpex' ); ?></label>
			<br />
			<select class='wpex-select' name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_id( 'order' ); ?>">
				<option value="DESC" <?php if( $order == 'DESC' ) { ?>selected="selected"<?php } ?>><?php _e( 'Recent', 'wpex' ); ?></option>
				<option value="rand" <?php if( $order == 'rand' ) { ?>selected="selected"<?php } ?>><?php _e( 'Random', 'wpex' ); ?></option>
				<option value="comment_count" <?php if( $order == 'comment_count' ) { ?>selected="selected"<?php } ?>><?php _e( 'Most Comments', 'wpex' ); ?></option>
				<option value="modified" <?php if( $order == 'modified' ) { ?>selected="selected"<?php } ?>><?php _e( 'Last Modified', 'wpex' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number', 'wpex' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" />
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
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
		$instance['order'] = ( ! empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : '';
		return $instance;
	}

}
if ( !function_exists( 'register_wpex_recent_posts_thumb_grid' ) ) {
	function register_wpex_recent_posts_thumb_grid() {
		register_widget( 'Wpex_Recent_Posts_Thumb_Grid' );
	}
}
add_action( 'widgets_init', 'register_wpex_recent_posts_thumb_grid' ); ?>