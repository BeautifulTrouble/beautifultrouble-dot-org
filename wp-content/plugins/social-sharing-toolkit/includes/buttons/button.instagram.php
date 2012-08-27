<?php
class MR_Social_Sharing_Toolkit_instagram extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_webstagram', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Instagram';
		$this->icon = 'instagram';
	}
	
	function follow_webstagram($type, $id, $text = '', $icon = '') {
		$url = 'http://web.stagram.com/n/'.$id;
		$text = ($text == '') ? __('Follow me on','mr_social_sharing_toolkit').' Instagram' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>