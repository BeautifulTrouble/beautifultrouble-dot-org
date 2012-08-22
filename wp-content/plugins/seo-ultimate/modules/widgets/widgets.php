<?php
/**
 * Widgets Module
 * 
 * @since 6.6
 */

if (class_exists('SU_Module')) {

class SU_Widgets extends SU_Module {
	
	function get_module_title() { return __('SEO Ultimate Widgets', 'seo-ultimate'); }
	function get_menu_title()	{ return false; }
	
	function get_admin_url($key = false) {
		
		if ($key)
			return parent::get_admin_url($key);
		
		if (is_network_admin())
			return false;
		
		return 'widgets.php';
	}
	
	function __construct() {
		add_action('widgets_init', array(&$this, 'register_widgets'));
	}
	
	function register_widgets() {
		register_widget('SU_Widget_SiloedTerms');
		
		if ($this->plugin->module_exists('footer-autolinks'))
			register_widget('SU_Widget_FooterLinks');
	}
}

}

if (class_exists('WP_Widget')) {

//Based on WordPress' WP_Widget_Categories & WP_Widget_Tag_Cloud
class SU_Widget_SiloedTerms extends WP_Widget {
	
	function __construct() {
		$widget_ops = array( 'description' => __( "On category archives, displays a list of child categories and/or posts in the category. Displays a list of top-level categories everywhere else. Powered by the SEO Ultimate plugin.", 'seo-ultimate' ) );
		parent::__construct('su_siloed_terms', __('Siloed Categories', 'seo-ultimate'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		global $wp_query;
		
		extract( $args );
		
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else {
			if ( 'post_tag' == $current_taxonomy ) {
				$title = __('Tags');
			} else {
				$tax = get_taxonomy($current_taxonomy);
				$title = $tax->labels->name;
			}
		}
		
		$use_desc_for_title = isset($instance['use_desc_for_title']) ? $instance['use_desc_for_title'] : true;
		$count = isset($instance['count']) ? $instance['count'] : false;
		
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		$current_term = false;
		$current_term_id = $current_post_id = 0;
		
		if (suwp::is_tax($current_taxonomy)) {
			$current_term = $wp_query->get_queried_object();
			$current_term_id = $wp_query->get_queried_object_id();
			$title = $current_term->name;
		} elseif (is_singular()) {
			$current_post_id = $wp_query->get_queried_object_id();
			$post_terms = get_the_terms($current_post_id, $current_taxonomy);
			if (is_array($post_terms) && count($post_terms)) {				
				$current_term = reset($post_terms);
				$current_term_id = $current_term->term_id;
				$title = $current_term->name;
			}
		}
		
		$term_args = array('taxonomy' => $current_taxonomy, 'orderby' => 'name', 'show_count' => $count ? '1' : '0', 'hierarchical' => '0', 'title_li' => '', 'parent' => $current_term_id, 'show_option_none' => false, 'use_desc_for_title' => $use_desc_for_title ? '1' : '0', 'echo' => false);
		
		$category_output = $post_output = '';
		
		if (!$current_term || is_taxonomy_hierarchical($current_taxonomy))
			$category_output = wp_list_categories($term_args);
		
		if ($current_term) {
			$child_posts = get_posts(array('taxonomy' => $current_taxonomy, 'term' => $current_term->slug, 'numberposts' => 5));
			
			foreach ($child_posts as $child_post) {
				
				$css_class = '';
				if ($child_post->ID == $current_post_id)
					$css_class = 'current_post_item';
				
				$post_output .= "\n\t\t\t<li class=\"" . $css_class . '"><a href="' . get_permalink($child_post->ID) . '" title="' . esc_attr( wp_strip_all_tags( apply_filters( 'the_title', $child_post->post_title, $child_post->ID ) ) ) . '">' . apply_filters( 'the_title', $child_post->post_title, $child_post->ID ) . "</a></li>\n";
			}
		}
		
		if ($category_output || $post_output) {
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
			echo "\n\t\t<ul>\n";
			echo $category_output;
			echo $post_output;
			echo "\n\t\t</ul>\n";
			echo $after_widget;
		}
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['use_desc_for_title'] = !empty($new_instance['use_desc_for_title']) ? 1 : 0;
		
		return $instance;
	}
	
	function form( $instance ) {
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$count = isset($instance['count']) ? (bool)$instance['count'] : false;
		$use_desc_for_title = isset($instance['use_desc_for_title']) ? (bool)$instance['use_desc_for_title'] : true;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'seo-ultimate' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts', 'seo-ultimate' ); ?></label>		
		<br /><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('use_desc_for_title'); ?>" name="<?php echo $this->get_field_name('use_desc_for_title'); ?>"<?php checked( $use_desc_for_title ); ?> />
		<label for="<?php echo $this->get_field_id('use_desc_for_title'); ?>"><?php _e( 'Use term descriptions in title attributes', 'seo-ultimate' ); ?></label></p>
		
		<p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:', 'seo-ultimate') ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
		<?php foreach ( get_object_taxonomies('post') as $taxonomy ) :
					$tax = get_taxonomy($taxonomy);
					if ( empty($tax->labels->name) )
						continue;
		?>
			<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
		<?php endforeach; ?>
		</select></p>
<?php
	}
	
	function _get_current_taxonomy($instance) {
		if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
			return $instance['taxonomy'];
		
		return 'category';
	}

}

class SU_Widget_FooterLinks extends WP_Widget {
	
	function __construct() {
		$widget_ops = array( 'description' => __( "Add this widget to display Deeplink Juggernaut&#8217;s Footer Links in a widget area of your choosing rather than the default wp_footer section. Powered by the SEO Ultimate plugin.", 'seo-ultimate' ) );
		parent::__construct('su_footer_autolinks', __('Footer Links', 'seo-ultimate'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		
		extract( $args );
		
		global $seo_ultimate;
		$display = empty($instance['display']) ? 'list' : $instance['display'];
		
		$title = empty($instance['title']) ? false : $instance['title'];
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		
		switch ($display) {
			case 'list':
				$args = array(
					  'footer_link_section_format' => "\n\t\t<ul>{links}\n\t\t</ul>\n"
					, 'footer_link_format' => "\n\t\t\t<li>{link}</li>"
					, 'footer_link_sep' => ''
				);
				break;
				
			default:
				$args = array();
				break;
		}
		
		if ($seo_ultimate->call_module_func('footer-autolinks', 'autolink_footer', $unused, $args))
			$seo_ultimate->set_module_var('footer-autolinks', 'already_outputted', true);
		
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['display'] = stripslashes($new_instance['display']);
		
		return $instance;
	}
	
	function form( $instance ) {
		global $seo_ultimate;
		
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$display = empty($instance['display']) ? 'list' : $instance['display'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title: <em>(optional)</em>', 'seo-ultimate' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('display'); ?>" value="list" <?php checked('list', $display); ?>/> <?php _e('Display as a list', 'seo-ultimate'); ?></label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('display'); ?>" value="usersettings" <?php checked('usersettings', $display); ?>/> <?php
				$seo_ultimate->call_module_func('footer-autolinks-settings', 'get_admin_url', $formats_url);
				printf(__('Use my <a href="%s" target="_blank">footer link HTML formats</a>', 'seo-ultimate'), $formats_url);
			?></label>
		</p>
<?php
	}
}

}

?>