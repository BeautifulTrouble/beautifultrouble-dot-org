<?php
class MR_Social_Sharing_Toolkit_WhatsApp extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'whatsapp_share', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'WhatsApp';
		$this->icon = 'whatsapp';
	}

	function whatsapp_share($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		$url = 'whatsapp://send?text='.urlencode($title).'%20'.urlencode($url);
		$text = ($text == '') ? __('Share on','mr_social_sharing_toolkit').' WhatsApp' : $text;
		return $this->get_icon($type, $url, $text, $icon, true);
	}	
}
?>