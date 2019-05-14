<?php
class MR_Social_Sharing_Toolkit_Linkedin extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'li_share', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_linked', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_linked_co', 'title' => 'LinkedIn Company', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_linked_group', 'title' => 'LinkedIn Group', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'LinkedIn';
		$this->icon = 'linkedin';
	}

	function li_share($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		switch ($type) {
			case 'horizontal':
				$retval = '<script type="IN/Share" data-url="'.$url.'" data-counter="right"></script>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_linkedin', 'http://platform.linkedin.com/in.js', $footer);
				break;
			case 'vertical':
				$retval = '<script type="IN/Share" data-url="'.$url.'" data-counter="top"></script>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_linkedin', 'http://platform.linkedin.com/in.js', $footer);
				break;
			case 'none':
				$retval = '<script type="IN/Share" data-url="'.$url.'"></script>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_linkedin', 'http://platform.linkedin.com/in.js', $footer);
				break;
			default:
				$url = 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.urlencode($url).'&amp;title='.urlencode($title);
				$text = ($text == '') ? __('Share on','mr_social_sharing_toolkit').' LinkedIn' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}
		return $retval;
	}

	function follow_linked($type, $id, $text = '', $icon = '') {
		$url = 'http://www.linkedin.com/in/'.$id;
		$text = ($text == '') ? __('Join my network on','mr_social_sharing_toolkit').' LinkedIn' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}

	function follow_linked_co($type, $id, $text = '', $icon = '') {
		$url = 'http://www.linkedin.com/company/'.$id;
		$text = ($text == '') ? __('Follow my company on','mr_social_sharing_toolkit').' LinkedIn' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}

	function follow_linked_group($type, $id, $text = '', $icon = '') {
		$url = 'http://www.linkedin.com/groups?gid='.$id;
		$text = ($text == '') ? __('Join my group on','mr_social_sharing_toolkit').' LinkedIn' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>