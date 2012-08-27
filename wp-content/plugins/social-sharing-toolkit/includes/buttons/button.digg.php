<?php
class MR_Social_Sharing_Toolkit_Digg extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'dg_digg', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Digg';
		$this->icon = 'digg';
	}

	function dg_digg($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		switch ($type) {
			case 'horizontal':
				$retval = '<a class="DiggThisButton DiggCompact" href="http://digg.com/submit?url='.urlencode($url).'&amp;title='.urlencode($title).'"></a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_digg', plugins_url('/button.digg.js', __FILE__), $footer);
				break;
			case 'vertical':
				$retval = '<a class="DiggThisButton DiggMedium" href="http://digg.com/submit?url='.urlencode($url).'&amp;title='.urlencode($title).'"></a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_digg', plugins_url('/button.digg.js', __FILE__), $footer);
				break;
			default:
				$url = 'http://digg.com/submit?url='.urlencode($url).'&amp;title='.urlencode($title);
				$text = ($text == '') ? __('Digg This','mr_social_sharing_toolkit') : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}			
		return $retval;
	}
}
?>