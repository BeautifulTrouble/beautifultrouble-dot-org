<?php
class MR_Social_Sharing_Toolkit_Soundcloud extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_soundcloud', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Soundcloud';
		$this->icon = 'soundcloud';
	}
	
	function follow_soundcloud($type, $id, $text = '', $icon = '') {
		$url = 'http://soundcloud.com/'.$id;
		$text = ($text == '') ? __('My sounds on','mr_social_sharing_toolkit').' Soundcloud' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>