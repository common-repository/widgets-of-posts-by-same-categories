<?php
/*
Plugin Name: Widgets of Posts by Same Categories
Plugin URI: http://alphasis.info/developments/wordpress-plugins/widgets-of-posts-by-same-categories/
Description: The widget area lists posts of the same category as the current post. This plugin requires the WP_Widget class introduced in WordPress Version 2.8. This widget works when any single Post page is being displayed.
Version: 1.0.2
Author: alphasis
Author URI: http://alphasis.info/developments/wordpress-plugins/
*/

/*
Copyright 2010  alphasis  (http://alphasis.info/)
*/

/**
 * class widgets_of_posts_by_same_categories
 */
class widgets_of_posts_by_same_categories extends WP_Widget {

	/** constructor */
	function widgets_of_posts_by_same_categories() {
		$widget_ops = array('classname' => 'widgets_of_posts_by_same_categories', 'description' => __('The widget area lists posts of the same category as the current post.'));
		$control_ops = array('width' => 400);
		$this->WP_Widget('of_posts_by_same_categories', __('Posts by Same Categories'), $widget_ops, $control_ops);
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {		
		extract( $args );
		if (is_single()) {
			$number_of_posts_to_show_by_categories = $instance['number_of_posts_to_show_by_categories'];
			$orderby = $instance['orderby'];
			$order = $instance['order'];
			$display_link = $instance['display_link'];
			$separator = $instance['separator'];
			$exclude_categories = explode (",", $instance['exclude_categories']);
			$exclude_posts = get_the_ID();
			if (!$number_of_posts_to_show_by_categories) { $number_of_posts_to_show_by_categories = 5;}
			?>
				<?php
					foreach ( (get_the_category()) as $category ) :
					$cat_ID = $category->cat_ID;
					foreach ( $exclude_categories as $exclude_category ) {
						if( $cat_ID == $exclude_category ){ continue 2; }
					}
					if( $category->count <= 1 ){ continue; }
					$numberposts =  $number_of_posts_to_show_by_categories;
				?>
					<?php echo $before_widget; ?>
					<?php echo $before_title . (get_category_parents("$cat_ID", $display_link, "$separator")) . $after_title; ?>
					<ul>
					<?php
						$postslist = get_posts("category=$cat_ID&numberposts=$numberposts&orderby=$orderby&order=$order&exclude=$exclude_posts");
						foreach ( $postslist as $post ) :
					?>
					<li><a href="<?php print get_permalink($post->ID); ?>" title="<?php  print get_the_title($post->ID); ?>"><?php  print get_the_title($post->ID); ?></a></li>
					<?php endforeach; ?>
					</ul>
					<?php echo $after_widget; ?>
				<?php endforeach; ?>
			<?php
		}
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {				
		return $new_instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {				
		$number_of_posts_to_show_by_categories = esc_attr($instance['number_of_posts_to_show_by_categories']);
		$orderby = esc_attr($instance['orderby']);
		$order = esc_attr($instance['order']);
		$display_link = esc_attr($instance['display_link']);
		$exclude_categories = esc_attr($instance['exclude_categories']);
		$separator = esc_attr($instance['separator']);
		?>
			<p>
				<label for="<?php echo $this->get_field_id('number_of_posts_to_show_by_categories'); ?>"><?php _e('Maximum Number of Posts to Show by Categories:', 'widgets-of-posts-by-same-categories'); ?> <input id="<?php echo $this->get_field_id('number_of_posts_to_show_by_categories'); ?>" name="<?php echo $this->get_field_name('number_of_posts_to_show_by_categories'); ?>" size="3" type="text" value="<?php echo $number_of_posts_to_show_by_categories; ?>" /><br />( <?php _e('Default:', 'widgets-of-posts-by-same-categories'); ?> 5 )</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By:', 'widgets-of-posts-by-same-categories'); ?>
					<select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
						<option value="title"<?php if($orderby=="title")echo ' selected'; ?>><?php _e('Sort by title', 'widgets-of-posts-by-same-categories'); ?></option>
						<option value="date"<?php if($orderby=="date")echo ' selected'; ?>><?php _e('Sort by creation date', 'widgets-of-posts-by-same-categories'); ?></option>
						<option value="modified"<?php if($orderby=="modified")echo ' selected'; ?>><?php _e('Sort by last modified date', 'widgets-of-posts-by-same-categories'); ?></option>
						<option value="ID"<?php if($orderby=="ID")echo ' selected'; ?>><?php _e('Sort by numeric post ID', 'widgets-of-posts-by-same-categories'); ?></option>
						<option value="rand"<?php if($orderby=="rand")echo ' selected'; ?>><?php _e('Random', 'widgets-of-posts-by-same-categories'); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:', 'widgets-of-posts-by-same-categories'); ?>
					<select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
						<option value="ASC"<?php if($order=="ASC")echo ' selected'; ?>><?php _e('Ascending', 'widgets-of-posts-by-same-categories'); ?></option>
						<option value="DESC"<?php if($order=="DESC")echo ' selected'; ?>><?php _e('Descending', 'widgets-of-posts-by-same-categories'); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('display_link'); ?>"><?php _e('Creates a link to each category?:', 'widgets-of-posts-by-same-categories'); ?>
					<select id="<?php echo $this->get_field_id('display_link'); ?>" name="<?php echo $this->get_field_name('display_link'); ?>">
						<option value="1"<?php if($display_link=="1")echo ' selected'; ?>><?php _e('Yes', 'widgets-of-posts-by-same-categories'); ?></option>
						<option value="0"<?php if($display_link=="0")echo ' selected'; ?>><?php _e('No', 'widgets-of-posts-by-same-categories'); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('exclude_categories'); ?>"><?php _e('Exclude (Categories):', 'widgets-of-posts-by-same-categories'); ?> <input id="<?php echo $this->get_field_id('exclude_categories'); ?>" name="<?php echo $this->get_field_name('exclude_categories'); ?>" class="widefat" type="text" value="<?php echo $exclude_categories; ?>" /><?php _e('The IDs of any categories you want to exclude, separated by commas.', 'widgets-of-posts-by-same-categories'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('separator'); ?>"><?php _e('Separator:', 'widgets-of-posts-by-same-categories'); ?> <input id="<?php echo $this->get_field_id('separator'); ?>" name="<?php echo $this->get_field_name('separator'); ?>" class="widefat" type="text" value="<?php echo $separator; ?>" /><?php _e('What to separate each category by.', 'widgets-of-posts-by-same-categories'); ?></label>
			</p>
			<p>
				<?php _e('Official page', 'widgets-of-posts-by-same-categories'); ?> : <a href="http://alphasis.info/developments/wordpress-plugins/widgets-of-posts-by-same-categories/" target="_blank"><?php _e('English', 'widgets-of-posts-by-same-categories'); ?></a> / <a href="http://alphasis.info/2010/11/widgets-of-posts-by-same-categories/" target="_blank"><?php _e('Japanese', 'widgets-of-posts-by-same-categories'); ?></a>
			</p>
			<p>
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZRXPVTJMYGDDL" target="_blank"><?php _e('Please pay if you like "Widgets of Posts by Same Categories".', 'widgets-of-posts-by-same-categories'); ?></a>
			</p>
		<?php 
	}

} // class widgets_of_posts_by_same_categories

// register widgets_of_posts_by_same_categories widget
add_action('widgets_init', create_function('', 'return register_widget("widgets_of_posts_by_same_categories");'));
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'widgets-of-posts-by-same-categories', false, $plugin_dir );

?>
