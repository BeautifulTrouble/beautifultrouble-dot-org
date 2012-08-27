<?php
class MR_Social_Sharing_Toolkit_Follow_Widget extends WP_Widget {
	function MR_Social_Sharing_Toolkit_Follow_Widget() {
		$widget_ops = array( 'classname' => 'MR_Social_Sharing_Toolkit_Follow_Widget', 'description' => '' );
		$control_ops = array( 'id_base' => 'mr-social-sharing-toolkit-follow-widget' );
		$this->WP_Widget( 'mr-social-sharing-toolkit-follow-widget', 'Social Sharing Toolkit '.__('Follow Widget','mr_social_sharing_toolkit'), $widget_ops, $control_ops );
	}

	function widget ( $args, $instance) {
		extract( $args );
		global $MR_Social_Sharing_Toolkit;
		$widget_title = empty($instance['widget_title']) ? '' : $instance['widget_title'];
		$followers = $MR_Social_Sharing_Toolkit->create_followers();	
		echo $before_widget;
		if ($widget_title != '') {
			echo $before_title . $widget_title . $after_title;
		}
		echo $followers;
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['widget_title'] = $new_instance['widget_title'];
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'widget_title' => ''));
		echo '			
		<p>
			<label for="'.$this->get_field_id( 'widget_title' ).'">'.__('Title').':</label>
			<input class="widefat" id="'.$this->get_field_id( 'widget_title' ).'" name="'.$this->get_field_name( 'widget_title' ).'" value="'.$instance['widget_title'].'" />
		</p>
		<p>
			'.__('Further configuration is done via the','mr_social_sharing_toolkit').' <a href="options-general.php?page=mr_social_sharing#tab_4">'.__('plugin admin screen','mr_social_sharing_toolkit').'</a>.
		</p>';
	}	
}
add_action('widgets_init', create_function('', 'return register_widget("MR_Social_Sharing_Toolkit_Follow_Widget");'));
?>