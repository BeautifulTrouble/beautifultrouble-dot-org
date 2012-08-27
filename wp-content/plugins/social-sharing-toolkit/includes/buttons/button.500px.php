<?php
class MR_Social_Sharing_Toolkit_500px extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_500px', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = '500px';
		$this->icon = '500px';
	}
	
	function follow_500px($type, $id, $text = '', $icon = '') {
		$url = 'http://500px.com/'.$id;
		$text = ($text == '') ? __('My portfolio on','mr_social_sharing_toolkit').' 500px' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>