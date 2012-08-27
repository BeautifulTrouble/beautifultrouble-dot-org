<?php
class MR_Social_Sharing_Toolkit_Flickr extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_flickr', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Flickr';
		$this->icon = 'flickr';
	}

	function follow_flickr($type, $id, $text = '', $icon = '') {
		$url = 'http://www.flickr.com/photos/'.$id;
		$text = ($text == '') ? __('My photostream on','mr_social_sharing_toolkit').' Flickr' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>