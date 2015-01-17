<?php
/**
 * Adds custom metaboxes to the WordPress categories
 *
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */
 
// Add extra fields to category edit form callback function
function extra_category_fields( $tag ) {

	// Get term id
	$tag_id		= $tag->term_id;
	$term_meta	= get_option( "category_$tag_id");

	// Category Style
	$style = isset ( $term_meta['wpex_term_style'] ) ? $term_meta['wpex_term_style'] : '' ; ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_style"><?php _e( 'Style', 'wpex' ); ?></label></th>
	<td>
		<select name="term_meta[wpex_term_style]" id="term_meta[term_style]">
			<option value="" <?php echo ( $style == "") ? 'selected="selected"': ''; ?>><?php _e( 'Default', 'wpex' ); ?></option>
			<option value="large-image" <?php echo ( $style == "large-image") ? 'selected="selected"': ''; ?>><?php _e( 'Large Image', 'wpex' ); ?></option>
			<option value="thumbnail" <?php echo ( $style == "thumbnail") ? 'selected="selected"': ''; ?>><?php _e( 'Thumbnail', 'wpex' ); ?></option>
			<option value="grid" <?php echo ( $style == "grid") ? 'selected="selected"': ''; ?>><?php _e( 'Grid', 'wpex' ); ?></option>
		</select>
	</td>
	</tr>
	
	<?php
	// Grid Columns
	$grid_cols = isset ( $term_meta['wpex_term_grid_cols'] ) ? $term_meta['wpex_term_grid_cols'] : '' ; ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_grid_cols"><?php _e( 'Grid Columns', 'wpex' ); ?></label></th>
	<td>
		<select name="term_meta[wpex_term_grid_cols]" id="term_meta[wpex_term_grid_cols]">
			<option value="4" <?php echo ( $grid_cols == "4" ) ? 'selected="selected"': ''; ?>>4</option>
			<option value="3" <?php echo ( $grid_cols == "3" ) ? 'selected="selected"': ''; ?>>3</option>
			<option value="2" <?php echo ( $grid_cols == "2" ) ? 'selected="selected"': ''; ?>>2</option>
			<option value="1" <?php echo ( $grid_cols == "1" ) ? 'selected="selected"': ''; ?>>1</option>
		</select>
	</td>
	</tr>

	<?php
	// Grid Style
	$grid_style = isset ( $term_meta['wpex_term_grid_style'] ) ? $term_meta['wpex_term_grid_style'] : '' ; ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_grid_style"><?php _e( 'Grid Style', 'wpex' ); ?></label></th>
	<td>
		<select name="term_meta[wpex_term_grid_style]" id="term_meta[wpex_term_grid_style]">
			<option value="" <?php echo ( $grid_style == "") ? 'selected="selected"': ''; ?>><?php _e( 'Default', 'wpex' ); ?></option>
			<option value="fit-rows" <?php echo ( $grid_style == "" ) ? 'selected="selected"': 'fit-rows'; ?>><?php _e( 'Fit Rows', 'wpex' ); ?></option>
			<option value="masonry" <?php echo ( $grid_style == "masonry" ) ? 'selected="selected"': ''; ?>><?php _e( 'Masonry', 'wpex' ); ?></option>
		</select>
	</td>
	</tr>
	
	<?php
	// Layout Style
	$layout = isset ( $term_meta['wpex_term_layout'] ) ? $term_meta['wpex_term_layout'] : '' ; ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_layout"><?php _e( 'Layout', 'wpex' ); ?></label></th>
	<td>
		<select name="term_meta[wpex_term_layout]" id="term_meta[wpex_term_layout]">
			<option value="" <?php echo ( $layout == "") ? 'selected="selected"': ''; ?>><?php _e( 'Default', 'wpex' ); ?></option>
			<option value="right-sidebar" <?php echo ( $layout == "right-sidebar") ? 'selected="selected"': ''; ?>><?php _e( 'Right Sidebar', 'wpex' ); ?></option>
			<option value="left-sidebar" <?php echo ( $layout == "left-sidebar") ? 'selected="selected"': ''; ?>><?php _e( 'Left Sidebar', 'wpex' ); ?></option>
			<option value="full-width" <?php echo ( $layout == "full-width") ? 'selected="selected"': ''; ?>><?php _e( 'Full Width', 'wpex' ); ?></option>
		</select>
	</td>
	</tr>
	
	<?php
	// Pagination Type
	$pagination = isset ( $term_meta['wpex_term_pagination'] ) ? $term_meta['wpex_term_pagination'] : ''; ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_pagination"><?php _e( 'Pagination', 'wpex' ); ?></label></th>
	<td>
		<select name="term_meta[wpex_term_pagination]" id="term_meta[wpex_term_pagination]">
			<option value="" <?php echo ( $pagination == "") ? 'selected="selected"': ''; ?>><?php _e( 'Default', 'wpex' ); ?></option>
			<option value="standard" <?php echo ( $pagination == "standard") ? 'selected="selected"': ''; ?>><?php _e( 'Standard', 'wpex' ); ?></option>
			<option value="infinite_scroll" <?php echo ( $pagination == "infinite_scroll") ? 'selected="selected"': ''; ?>><?php _e( 'Inifinite Scroll', 'wpex' ); ?></option>
			<option value="next_prev" <?php echo ( $pagination == "next_prev") ? 'selected="selected"': ''; ?>><?php _e( 'Next/Previous', 'wpex' ); ?></option>
		</select>
	</td>
	</tr>
	
	<?php
	// Excerpt length
	$excerpt_length = isset ( $term_meta['wpex_term_excerpt_length'] ) ? $term_meta['wpex_term_excerpt_length'] : get_theme_mod( 'blog_excerpt_length', '40' ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_excerpt_length"><?php _e( 'Excerpt Length', 'wpex' ); ?></label></th>
		<td>
		<input type="text" name="term_meta[wpex_term_excerpt_length]" id="term_meta[wpex_term_excerpt_length]" size="3" style="width:100px;" value="<?php echo $excerpt_length; ?>">
		</td>
	</tr>
	
	<?php
	// Posts Per Page
	$posts_per_page = isset ( $term_meta['wpex_term_posts_per_page'] ) ? $term_meta['wpex_term_posts_per_page'] : ''; ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_posts_per_page"><?php _e( 'Posts Per Page', 'wpex' ); ?></label></th>
		<td>
		<input type="text" name="term_meta[wpex_term_posts_per_page]" id="term_meta[wpex_term_posts_per_page]" size="3" style="width:100px;" value="<?php echo $posts_per_page; ?>">
		</td>
	</tr>
	
	<?php
	// Image Width
	if ( isset ( $term_meta['wpex_term_image_width'] ) ){
		$wpex_term_image_width = $term_meta['wpex_term_image_width'];
	} else {
		$wpex_term_image_width = get_theme_mod( 'blog_entry_image_width', '9999' );
	} ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_image_width"><?php _e( 'Image Width', 'wpex' ); ?></label></th>
		<td>
		<input type="text" name="term_meta[wpex_term_image_width]" id="term_meta[wpex_term_image_width]" size="3" style="width:100px;" value="<?php echo $wpex_term_image_width; ?>">
		</td>
	</tr>
		
	<?php
	// Image Height
	if ( isset ( $term_meta['wpex_term_image_height'] ) ){
		$wpex_term_image_height = $term_meta['wpex_term_image_height'];
	} else {
		$wpex_term_image_height = get_theme_mod( 'blog_entry_image_height', '9999' );
	} ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="wpex_term_image_height"><?php _e( 'Image Height', 'wpex' ); ?></label></th>
		<td>
		<input type="text" name="term_meta[wpex_term_image_height]" id="term_meta[wpex_term_image_height]" size="3" style="width:100px;" value="<?php echo $wpex_term_image_height; ?>">
		</td>
	</tr>
<?php
}
add_action ( 'edit_category_form_fields', 'extra_category_fields' );

// save extra category extra fields callback function
function save_extra_category_fileds( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$tag_id = $term_id;
		$term_meta = get_option( "category_$tag_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key){
			if ( isset( $_POST['term_meta'][$key] ) ){
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		//save the option array
		update_option( "category_$tag_id", $term_meta );
	}
}
add_action ( 'edited_category', 'save_extra_category_fileds' );