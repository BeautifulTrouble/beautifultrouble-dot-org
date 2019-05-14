<?php
class MR_Social_Sharing_Toolkit_Facebook extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'fb_like', 'title' => 'Facebook Like', 'types' => array('none', 'none_text', 'horizontal', 'vertical'));
		$this->share_buttons[] = array('name' => 'fb_share', 'title' => 'Facebook Share', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons[] = array('name' => 'fb_send', 'title' => 'Facebook Send', 'types' => array('none'));
		$this->follow_buttons[] = array('name' => 'follow_facebook', 'title' => 'Facebook', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Facebook';
		$this->icon = 'facebook';
	}
	
	function fb_like($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		$retval = '<div class="fb-like" data-href="'.$url.'" data-send="false" ';
		switch ($type) {
			case 'horizontal':
				if ($id == 'recommend') {
					$width = __('fb_horizontal_recommend_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_horizontal_recommend_width') ? '120' : $width;
					$retval .= 'data-layout="button_count" data-width="'.$width.'" data-action="recommend"';					
				} else {
					$width = __('fb_horizontal_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_horizontal_width') ? '90' : $width;
					$retval .= 'data-layout="button_count" data-width="'.$width.'"';					
				}
				$height = '21';				
				break;
			case 'vertical':
				if ($id == 'recommend') {
					$width = __('fb_vertical_recommend_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_vertical_recommend_width') ? '92' : $width;
					$retval .= 'data-layout="box_count" data-width="'.$width.'" data-action="recommend"';					
				} else {
					$width = __('fb_vertical_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_vertical_width') ? '55' : $width;
					$retval .= 'data-layout="box_count" data-width="'.$width.'"';					
				}
				$height = '62';
				break;
			case 'none_text':
				if ($id == 'recommend') {
					$width = 'auto';
					$retval .= 'data-width="'.$width.'" data-action="recommend"';					
				} else {
					$width = 'auto';
					$retval .= 'data-width="'.$width.'"';					
				}
				$height = '25';
				break;
			default:
				if ($id == 'recommend') {
					$width = __('fb_standard_recommend_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_standard_standard_recommend_width') ? '91' : $width;
					$retval .= 'data-width="'.$width.'" data-action="recommend"';					
				} else {
					$width = __('fb_standard_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_standard_width') ? '51' : $width;
					$retval .= 'data-width="'.$width.'"';					
				}
				$height = '21';
				break;
		}
		$retval .= ' data-show-faces="false"></div>';
		$lang = __('en_US','mr_social_sharing_toolkit');
		$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
		$this->enqueue_script('Social_sharing_facebook_root', plugins_url('/button.facebook.js', __FILE__), $footer);
		$this->enqueue_script('Social_sharing_facebook_xfbml', '//connect.facebook.net/'.$lang.'/all.js#xfbml=1&appId=188707654478', $footer);
		return '<span style="display: inline-block; width: '.$width.'px; height: '.$height.'px; overflow: hidden;">'.$retval.'</span>';
	}
				
	function fb_share($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {	
		switch ($type) {
			case 'vertical':
				$retval = '<fb:share-button type="box_count" href="'.$url.'"></fb:share-button>';
				$this->enqueue_script('Social_sharing_facebook_root', plugins_url('/button.facebook.js', __FILE__));
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_facebook_xfbml', '//connect.facebook.net/en_US/all.js#xfbml=1&appId=188707654478', $footer);
				break;
			case 'horizontal':
				$retval = '<fb:share-button type="button_count" href="'.$url.'"></fb:share-button>';
				$this->enqueue_script('Social_sharing_facebook_root', plugins_url('/button.facebook.js', __FILE__));
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_facebook_xfbml', '//connect.facebook.net/en_US/all.js#xfbml=1&appId=188707654478', $footer);
				break;
			case 'none':
				$retval = '<fb:share-button type="button" href="'.$url.'"></fb:share-button>';
				$this->enqueue_script('Social_sharing_facebook_root', plugins_url('/button.facebook.js', __FILE__));
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_facebook_xfbml', '//connect.facebook.net/en_US/all.js#xfbml=1&appId=188707654478', $footer);
				break;
			default:
				$url = '//www.facebook.com/sharer/sharer.php?u='.urlencode($url).'&amp;t='.urlencode($title);
				$text = ($text == '') ? __('Share on','mr_social_sharing_toolkit').' Facebook' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}
		return $retval;
	}
	
	function fb_send($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		$retval = '<div class="fb-send" data-href="'.$url.'"></div>';
		$lang = __('en_US','mr_social_sharing_toolkit');
		$this->enqueue_script('Social_sharing_facebook_root', plugins_url('/button.facebook.js', __FILE__));
		$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
		$this->enqueue_script('Social_sharing_facebook_xfbml', '//connect.facebook.net/'.$lang.'/all.js#xfbml=1&appId=188707654478', $footer);
		return $retval;			
	}	
	
	function follow_facebook($type, $id, $text = '', $icon = '') {
		$url = '//www.facebook.com/'.$id;
		$text = ($text == '') ? __('Friend me on','mr_social_sharing_toolkit').' Facebook' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>