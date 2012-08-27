<?php
class MR_Social_Sharing_Toolkit_Picasa extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_picasa', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Picasa';
		$this->icon = 'picasa';
	}
	
	function follow_picasa($type, $id, $text = '', $icon = '') {
		$url = 'http://picasaweb.google.com/'.$id;
		$text = ($text == '') ? __('My Picasa Web Albums','mr_social_sharing_toolkit') : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>