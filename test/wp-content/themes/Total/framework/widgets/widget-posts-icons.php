<?php
/**
 * Recent posts with icons widget
 *
 * @package		Total
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! class_exists( 'WPEX_Recent_Posts_Icons_Widget' ) ) {
	class WPEX_Recent_Posts_Icons_Widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct(
				'wpex_recent_posts_icons',
				$name = __( 'Recent Posts With Icons', 'wpex' ),
				array()
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 1.0.0
		 *
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		function widget( $args, $instance ) {
			extract( $args );
			$title		= isset ( $instance['title'] ) ? $instance['title'] : '';
			$title		= apply_filters( 'widget_title', $instance['title'] );
			$number		= isset( $instance['number'] ) ? $instance['number'] : '5';
			$order		= isset( $instance['order'] ) ? $instance['order'] : 'DESC';
			$orderby	= isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
			$category	=  isset( $instance['category'] ) ? $instance['category'] : 'all';

			// Exclude current post
			if ( is_singular() ) {
				$exclude = array( get_the_ID() );
			} else {
				$exclude = NULL;
			}

			// Before Widget Hook
			echo $before_widget;

			// Title
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
				// Category
				if ( ! empty( $category ) && 'all' != $category ) {
					$taxonomy = array (
						array (
							'taxonomy'	=> 'category',
							'field'		=> 'id',
							'terms'		=> $category,
						)
					);
				} else {
					$taxonomy = NUll;
				}

				// Query Posts
				global $post;
				$wpex_query = new WP_Query( array(
					'post_type'				=> 'post',
					'posts_per_page'		=> $number,
					'orderby'				=> $orderby,
					'order'					=> $order,
					'no_found_rows'			=> true,
					'post__not_in'			=> $exclude,
					'tax_query'				=> $taxonomy,
					'ignore_sticky_posts'	=> 1
				) );

				// Loop through posts
				if ( $wpex_query->have_posts() ) { ?>
					<ul class="widget-recent-posts-icons clr">
						<?php foreach( $wpex_query->posts as $post ) : setup_postdata( $post ); ?>
							<li class="clr">
								<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
									<span class="fa <?php wpex_post_format_icon(); ?>"></span><?php the_title(); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php }

			// Reset post data
			wp_reset_postdata();

			// After widget hook
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
			$instance				= $old_instance;
			$instance['title']		= strip_tags( $new_instance['title'] );
			$instance['number']		= strip_tags( $new_instance['number'] );
			$instance['order']		= strip_tags( $new_instance['order'] );
			$instance['orderby']	= strip_tags( $new_instance['orderby'] );
			$instance['category']	= strip_tags( $new_instance['category'] );
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
			$instance = wp_parse_args( ( array ) $instance, array(
				'title'		=> __( 'Recent Posts', 'wpex' ),
				'number'	=> '5',
				'order'		=> 'DESC',
				'orderby'	=> 'date',
				'category'	=> 'all'

			) );
			$title		= esc_attr( $instance['title'] );
			$number		= esc_attr( $instance['number'] );
			$order		= esc_attr( $instance['order'] );
			$orderby	= esc_attr( $instance['orderby'] );
			$category	= esc_attr( $instance['category'] ); ?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpex' ); ?>:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title','wpex' ); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number to Show', 'wpex' ); ?>:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order', 'wpex' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_id( 'order' ); ?>">
				<option value="DESC" <?php if( $order == 'DESC' ) { ?>selected="selected"<?php } ?>><?php _e( 'Descending', 'wpex' ); ?></option>
				<option value="ASC" <?php if( $order == 'ASC' ) { ?>selected="selected"<?php } ?>><?php _e( 'Ascending', 'wpex' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By', 'wpex' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>">
				<?php
				// Orderby options
				$orderby_array = array (
					'date'			=> __( 'Date', 'wpex' ),
					'title'			=> __( 'Title', 'wpex' ),
					'modified'		=> __( 'Modified', 'wpex' ),
					'author'		=> __( 'Author', 'wpex' ),
					'rand'			=> __( 'Random', 'wpex' ),
					'comment_count'	=> __( 'Comment Count', 'wpex' ),
				);
				foreach ( $orderby_array as $key => $value ) { ?>
					<option value="<?php echo $key; ?>" <?php if( $orderby == $key ) { ?>selected="selected"<?php } ?>>
						<?php echo $value; ?>
					</option>
				<?php } ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category', 'wpex' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo $this->get_field_name( 'category' ); ?>" id="<?php echo $this->get_field_id( 'category' ); ?>">
				<option value="all" <?php if($category == 'all' ) { ?>selected="selected"<?php } ?>><?php _e( 'All', 'wpex' ); ?></option>
				<?php
				$terms = get_terms( 'category' );
				if ( ! empty ( $terms ) ) {
					foreach ( $terms as $term ) { ?>
						<option value="<?php echo $term->term_id; ?>" <?php if( $category == $term->term_id ) { ?>selected="selected"<?php } ?>><?php echo $term->name; ?></option>
					<?php }
				} ?>
				</select>
			</p>
			<?php
		}
	}
}

// Register the WPEX_Tabs_Widget custom widget
if ( ! function_exists( 'register_recent_posts_icons_widget' ) ) {
	function register_recent_posts_icons_widget() {
		register_widget( 'WPEX_Recent_Posts_Icons_Widget' );
	}
}
add_action( 'widgets_init', 'register_recent_posts_icons_widget' );