<?php
class MR_Social_Sharing_Toolkit_Delicious extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'dl_delicious', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Delicious';
		$this->icon = 'delicious';
	}	
	
	function dl_delicious($url, $title, $type, $id, $text = '', $icon = '') {		
		switch ($type) {
			case 'horizontal':
				$hash = md5($url);
				$retval = '<div class="delicious_horizontal"><span class="delicious_hash">'.$hash.'</span><a class="mr_social_sharing_popup_link" href="http://del.icio.us/post?v=4&amp;noui&amp;jump=close&amp;url='.urlencode($url).'&amp;title='.urlencode($title).'" target="_blank"></a></div>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_delicious', plugins_url('/button.delicious.js', __FILE__), $footer); 
				
				break;
			case 'vertical':
				$hash = md5($url);
				$retval = '<div class="delicious_vertical"><span class="delicious_hash">'.$hash.'</span><a class="mr_social_sharing_popup_link" href="http://del.icio.us/post?v=4&amp;noui&amp;jump=close&amp;url='.urlencode($url).'&amp;title='.urlencode($title).'" target="_blank"></a></div>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_delicious', plugins_url('/button.delicious.js', __FILE__), $footer);
				
				break;
			default:
				$url = 'http://del.icio.us/post?url='.urlencode($url).'&amp;title='.urlencode($title);
				$text = ($text == '') ? __('Save on','mr_social_sharing_toolkit').' Delicious' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}
		return $retval;			
	}
}
?>