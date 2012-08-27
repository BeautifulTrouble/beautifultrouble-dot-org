<?php
class MR_Social_Sharing_Toolkit_GooglePlus extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'gl_plus', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_plus', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Google+';
		$this->icon = 'googleplus';
	}
	
	function gl_plus($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		switch ($type) {
			case 'horizontal':
				$retval = '<div class="g-plusone" data-size="medium" data-href="'.$url.'"></div>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_googleplus', plugins_url('/button.googleplus.js', __FILE__), $footer);				
				break;
			case 'vertical':
				$retval = '<div class="g-plusone" data-size="tall" data-href="'.$url.'"></div>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_googleplus', plugins_url('/button.googleplus.js', __FILE__), $footer);	
				break;
			case 'none':
				$retval = '<div class="g-plusone" data-size="medium" data-annotation="none" data-href="'.$url.'"></div>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_googleplus', plugins_url('/button.googleplus.js', __FILE__), $footer);	
				break;
			default:
				$url = 'https://plusone.google.com/_/+1/confirm?hl=en&amp;url='.urlencode($url).'&amp;title='.urlencode($title);
				$text = ($text == '') ? '+1' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}
		return $retval;
	}
	
	function follow_plus($type, $id, $text = '', $icon = '') {
		$url = 'http://plus.google.com/'.$id;
		$text = ($text == '') ? __('Add me to your circles','mr_social_sharing_toolkit') : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>