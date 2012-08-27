<?php
class MR_Social_Sharing_Toolkit_Pinterest extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'pn_pinterest', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_pinterest', 'id' => 'id:', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Pinterest';
		$this->icon = 'pinterest';
	}

	function pn_pinterest($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {		
		$pin_url = 'http://pinterest.com/pin/create/button/?url='.urlencode($url).'&amp;media='.urlencode($media).'&amp;description='.urlencode($title);
		switch ($type) {
			case 'horizontal':
				$retval = '<a href="'.$pin_url.'" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_pinterest', 'http://assets.pinterest.com/js/pinit.js', $footer);
				break;
			case 'vertical':
				$retval = '<a href="'.$pin_url.'" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_pinterest', 'http://assets.pinterest.com/js/pinit.js', $footer);
				break;
			case 'none':
				$retval = '<a href="'.$pin_url.'" class="pin-it-button" count-layout="none"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_pinterest', 'http://assets.pinterest.com/js/pinit.js', $footer);
				break;
			default:
				$text = ($text == '') ? __('Pin it on','mr_social_sharing_toolkit').' Pinterest' : $text;
				$retval = $this->get_icon($type, $pin_url, $text, $icon, true);
				break;
		}		
		return $retval;
	}
	
	function follow_pinterest($type, $id, $text = '', $icon = '') {
		$url = 'http://pinterest.com/'.$id.'/';
		$text = ($text == '') ? __('Follow me on','mr_social_sharing_toolkit').' Pinterest' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>