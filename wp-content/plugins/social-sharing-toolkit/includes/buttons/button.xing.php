<?php
class MR_Social_Sharing_Toolkit_Xing extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'xi_xing', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_xing', 'id' => 'id:', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Xing';
		$this->icon = 'xing';
	}
	
	function xi_xing($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		switch ($type) {
			case 'horizontal':
				$retval = '<script type="XING/Share" data-counter="right" data-lang="en" data-url="'.$url.'"></script>';				
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_xing', 'https://www.xing-share.com/js/external/share.js', false);
				break;
			case 'vertical':
				$retval = '<script type="XING/Share" data-counter="top" data-lang="en" data-url="'.$url.'"></script>';			
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_xing', 'https://www.xing-share.com/js/external/share.js', $footer);				
				break;
			case 'none':
				$retval = '<script type="XING/Share" data-counter="no_count" data-lang="en" data-url="'.$url.'" data-button-shape="rectangle"></script>';			
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_xing', 'https://www.xing-share.com/js/external/share.js', $footer);
				break;
			default:
				$url = 'https://www.xing.com/app/startpage?op=home;func_share=1;tab=link;url='.$url;
				$text = ($text == '') ? __('Share on','mr_social_sharing_toolkit').' Xing' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}		
		return $retval;
	}
	
	function follow_xing($type, $id, $text = '', $icon = '') {
		$url = 'http://www.xing.com/profile/'.$id;
		$text = ($text == '') ? __('Join my network on','mr_social_sharing_toolkit').' Xing' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>