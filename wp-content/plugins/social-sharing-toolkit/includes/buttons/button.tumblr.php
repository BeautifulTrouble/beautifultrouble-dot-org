<?php
class MR_Social_Sharing_Toolkit_Tumblr extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'tu_tumblr', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_tumblr', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Tumblr';
		$this->icon = 'tumblr';
	}	

	function tu_tumblr($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		$url = 'http://www.tumblr.com/share/link?url='.urlencode($url).'&amp;name='.urlencode($title);
		$text = ($text == '') ? __('Share on','mr_social_sharing_toolkit').' Tumblr' : $text;
		return $this->get_icon($type, $url, $text, $icon, true);
	}
	
	function follow_tumblr($type, $id, $text = '', $icon = '') {
		$url = 'http://'.$id.'.tumblr.com';
		$text = ($text == '') ? __('Follow me on','mr_social_sharing_toolkit').' Tumblr' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>