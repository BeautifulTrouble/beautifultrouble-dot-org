<?php
class MR_Social_Sharing_Toolkit_Flattr extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'fl_flattr', 'id' => 'id:', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Flattr';
		$this->icon = 'flattr';
	}

	function fl_flattr($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {		
		switch ($type) {
			case 'vertical':
				$retval = '<a class="FlattrButton" href="'.$url.'" title="'.$title.'" rel="flattr;uid:'.$id.';" lang="en_GB" style="display: none;">'.$description.'</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_flattr', plugins_url('/button.flattr.js', __FILE__), $footer);				
				break;
			case 'horizontal':
				$retval = '<a class="FlattrButton" href="'.$url.'" title="'.$title.'" rel="flattr;button:compact;uid:'.$id.';" lang="en_GB" style="display: none;">'.$description.'</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_flattr', plugins_url('/button.flattr.js', __FILE__), $footer);				
				break;
			default:
				$url = 'https://flattr.com/submit/auto?user_id='.urlencode($id).'&amp;url='.urlencode($url).'&amp;title='.urlencode($title).'&amp;&language=en_GB';
				$text = ($text == '') ? __('Flattr this!','mr_social_sharing_toolkit') : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}
		return $retval;
	}
}
?>