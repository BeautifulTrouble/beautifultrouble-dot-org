<?php
class MR_Social_Sharing_Toolkit_Rss extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_rss', 'id' => 'url:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = __('RSS Feed','mr_social_sharing_toolkit');
		$this->icon = 'rss';
	}
	
	function follow_rss($type, $id, $text = '', $icon = '') {
		$url = $id;
		$text = ($text == '') ? 'RSS Feed' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>