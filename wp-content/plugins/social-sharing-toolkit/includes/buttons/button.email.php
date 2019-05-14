<?php
class MR_Social_Sharing_Toolkit_Email extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'ml_send', 'title' => __('Send email','mr_social_sharing_toolkit'), 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Email';
		$this->icon = 'email';
	}
	
	function ml_send($url, $title, $type, $id = '', $media = '', $description = '', $text = '', $icon = '') {
		$url = 'mailto:?subject='.$title.'&amp;body='.$url;
		$text = ($text == '') ? __('Share via email','mr_social_sharing_toolkit') : $text;
		return $this->get_icon($type, $url, $text, $icon, false, true);
	}	
}
?>