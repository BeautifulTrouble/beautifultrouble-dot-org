<?php
class MR_Social_Sharing_Toolkit_Youtube extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_youtube', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Youtube';
		$this->icon = 'youtube';
	}
	
	function follow_youtube($type, $id, $text = '', $icon = '') {
		$url = 'http://www.youtube.com/user/'.$id;
		$text = ($text == '') ? __('Watch me on','mr_social_sharing_toolkit').' YouTube' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>