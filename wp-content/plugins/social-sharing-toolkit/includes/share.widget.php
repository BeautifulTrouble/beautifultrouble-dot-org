<?php
class MR_Social_Sharing_Toolkit_Widget extends WP_Widget {
	function MR_Social_Sharing_Toolkit_Widget() {
		$widget_ops = array( 'classname' => 'MR_Social_Sharing_Toolkit_Widget', 'description' => '' );
		$control_ops = array( 'id_base' => 'mr-social-sharing-toolkit-widget' );
		$this->WP_Widget( 'mr-social-sharing-toolkit-widget', 'Social Sharing Toolkit '.__('Share Widget','mr_social_sharing_toolkit'), $widget_ops, $control_ops );
	}

	function widget ( $args, $instance) {
		extract( $args );
		global $MR_Social_Sharing_Toolkit;
		$widget_title = empty($instance['widget_title']) ? '' : $instance['widget_title'];
		$url = empty($instance['fixed_url']) ? '' : $instance['fixed_url'];
		$title = empty($instance['fixed_title']) ? wp_title('', false) : $instance['fixed_title'];
		$media = '';
		if ($this->options['mr_social_sharing_pinterest']['default_image'] != '' && $this->options['mr_social_sharing_pinterest']['fixed_image'] == 1) {
			$media = $this->options['mr_social_sharing_pinterest']['default_image'];
		} else {
			if (current_theme_supports('post-thumbnails')) {
				if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()))) {
		  			if (is_array($media)) {
		  				$media = $media[0];
		  			} else {
		  				$media = '';	
		  			}
				}
			}		
			if ($media == '') {
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches);
	  			$img = $matches[1][0];
				if($img != '') {
	    			$media = $img;
	  			}	
			}
			if ($media == '' && $this->options['mr_social_sharing_pinterest']['default_image'] != '') {
				$media = $this->options['mr_social_sharing_pinterest']['default_image'];
			}
		}
		$bookmarks = $MR_Social_Sharing_Toolkit->create_bookmarks($url, $title, 'widget_', $media);	
		echo $before_widget;
		if ($widget_title != '') {
			echo $before_title . $widget_title . $after_title;
		}
		echo $bookmarks;
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['widget_title'] = $new_instance['widget_title'];
		$instance['fixed_title'] = $new_instance['fixed_title'];
		$instance['fixed_url'] = $new_instance['fixed_url'];
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'widget_title' => '', 'fixed_title' => '', 'fixed_url' => ''));
		echo '			
		<p>
			<label for="'.$this->get_field_id( 'widget_title' ).'">'.__('Title').':</label>
			<input class="widefat" id="'.$this->get_field_id( 'widget_title' ).'" name="'.$this->get_field_name( 'widget_title' ).'" value="'.$instance['widget_title'].'" />
		</p>	
		<p>
			<label for="'.$this->get_field_id( 'fixed_title' ).'">'.__('Fixed title','mr_social_sharing_toolkit').':</label>
			<input class="widefat" id="'.$this->get_field_id( 'fixed_title' ).'" name="'.$this->get_field_name( 'fixed_title' ).'" value="'.$instance['fixed_title'].'" />
		</p>
		<p>
			<label for="'.$this->get_field_id( 'fixed_url' ).'">'.__('Fixed url','mr_social_sharing_toolkit').':</label>
			<input class="widefat" id="'.$this->get_field_id( 'fixed_url' ).'" name="'.$this->get_field_name( 'fixed_url' ).'" value="'.$instance['fixed_url'].'" />
		</p>
		<p>
			'.__('Further configuration is done via the','mr_social_sharing_toolkit').' <a href="options-general.php?page=mr_social_sharing#tab_3">'.__('plugin admin screen','mr_social_sharing_toolkit').'</a>.
		</p>';
	}
}
add_action('widgets_init', create_function('', 'return register_widget("MR_Social_Sharing_Toolkit_Widget");'));
?>