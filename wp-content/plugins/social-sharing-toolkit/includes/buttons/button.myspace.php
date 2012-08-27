<?php
class MR_Social_Sharing_Toolkit_Myspace extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'ms_myspace', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_myspace', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Myspace';
		$this->icon = 'myspace';
	}	

	function ms_myspace($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		$url = 'http://www.myspace.com/Modules/PostTo/Pages/?t='.urlencode($title).'&amp;u='.urlencode($url);
		$text = ($text == '') ? __('Share on','mr_social_sharing_toolkit').' Myspace' : $text;
		return $this->get_icon($type, $url, $text, $icon, true);
	}
	
	function follow_myspace($type, $id, $text = '', $icon = '') {
		$url = 'http://www.myspace.com/'.$id;
		$text = ($text == '') ? __('Friend me on','mr_social_sharing_toolkit').' Myspace' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>