<?php
class MR_Social_Sharing_Toolkit_Stumbleupon extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'su_stumble', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'StumbleUpon';
		$this->icon = 'stumbleupon';
	}	

	function su_stumble($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		switch ($type) {
			case 'horizontal':
				$retval = '<span class="stumble_horizontal"><su:badge layout="1" location="'.$url.'"></su:badge></span>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_stumbleupon', plugins_url('/button.stumbleupon.js', __FILE__), $footer);
				break;
			case 'vertical':			
				$retval = '<span class="stumble_vertical"><su:badge layout="5" location="'.$url.'"></su:badge></span>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_stumbleupon', plugins_url('/button.stumbleupon.js', __FILE__), $footer);
				break;
			default:
				$url = 'http://www.stumbleupon.com/submit?url='.urlencode($url);
				$text = ($text == '') ? __('Submit to','mr_social_sharing_toolkit').' StumbleUpon' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}
		return $retval;
	}
}
?>