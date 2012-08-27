<?php
class MR_Social_Sharing_Toolkit_Buffer extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'bf_buffer', 'id' => '@', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Buffer';
		$this->icon = 'buffer';
	}	

	function bf_buffer($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {		
		switch ($type) {
			case 'horizontal':
				$retval = '<a href="http://bufferapp.com/add" class="buffer-add-button" data-url="'.$url.'" data-count="horizontal"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				$retval .= ' data-text="'.$title.'">Buffer</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_buffer', 'http://static.bufferapp.com/js/button.js', $footer);
				break;
			case 'vertical':
				$retval = '<a href="http://bufferapp.com/add" class="buffer-add-button" data-url="'.$url.'" data-count="vertical"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				$retval .= ' data-text="'.$title.'">Buffer</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_buffer', 'http://static.bufferapp.com/js/button.js', $footer);
				break;
			case 'none':
				$retval = '<a href="http://bufferapp.com/add" class="buffer-add-button" data-url="'.$url.'" data-count="none"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				$retval .= ' data-text="'.$title.'">Buffer</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_buffer', 'http://static.bufferapp.com/js/button.js', $footer);
				break;
			default:
				$url = 'http://bufferapp.com/add?url='.urlencode($url).'&amp;text='.urlencode($title);
				if ($id != '') {
					$url .= '&amp;via='.urlencode($id);
				}
				$text = ($text == '') ? __('Add to','mr_social_sharing_toolkit').' Buffer' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}		
		return $retval;
	}
}
?>