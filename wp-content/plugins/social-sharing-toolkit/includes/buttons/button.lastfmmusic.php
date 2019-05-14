<?php
class MR_Social_Sharing_Toolkit_LastfmMusic extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_lastfm_music', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Last.fm Music';
		$this->icon = 'lastfm';
	}
	
	function follow_lastfm_music($type, $id, $text = '', $icon = '') {
		$url = 'http://www.last.fm/music/'.$id;
		$text = ($text == '') ? __('My profile on','mr_social_sharing_toolkit').' Last.fm' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}	
}
?>