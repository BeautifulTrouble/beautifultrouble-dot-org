<?php	
class MR_Social_Sharing_Toolkit_Deviantart extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_deviant', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'deviantArt';
		$this->icon = 'deviantart';
	}

	function follow_deviant($type, $id, $text = '', $icon = '') {
		$url = 'http://'.$id.'.deviantart.com/';
		$text = ($text == '') ? __('My deviantArt','mr_social_sharing_toolkit') : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>