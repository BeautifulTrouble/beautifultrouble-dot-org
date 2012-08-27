<?php
class MR_Social_Sharing_Toolkit_Hyves extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'hv_respect', 'title' => 'Hyves Respect', 'types' => array('horizontal'));
		$this->follow_buttons[] = array('name' => 'follow_hyves', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Hyves';
		$this->icon = 'hyves';
	}

	function hv_respect($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		$retval = '<iframe src="http://www.hyves.nl/respect/button?url='.urlencode($url).'&amp;title='.urlencode($title).'" style="border: medium none; overflow:hidden; width:150px; height:21px;" scrolling="no" frameborder="0" allowTransparency="true" ></iframe>';
		return $retval;
	}
	
	function follow_hyves($type, $id, $text = '', $icon = '') {
		$url = 'http://'.$id.'.hyves.nl';
		$text = ($text == '') ? __('Friend me on','mr_social_sharing_toolkit').' Hyves' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>