<?php
class MR_Social_Sharing_Toolkit_Button {
	protected $share_buttons;
	protected $follow_buttons;
	protected $title;
	protected $icon;
	protected $scripts;
	
	function MR_Social_Sharing_Toolkit_Button() {
		$this->share_buttons = array();
		$this->follow_buttons = array();
		$this->title = '';
		$this->icon = '';
		$this->scripts = array();
	}
	
	function getTitle() {
		return $this->title;
	}
	
	function getIcon() {
		return $this->icon;
	}
	
	function hasShare() {
		if (is_array($this->share_buttons) && count($this->share_buttons) > 0) {
			return true;	
		}
		return false;
	}
	
	function getShareButtons() {
		return $this->share_buttons;	
	}
	
	function hasFollow() {
		if (is_array($this->follow_buttons) && count($this->follow_buttons) > 0) {
			return true;	
		}
		return false;
	}
	
	function getFollowButtons() {
		return $this->follow_buttons;	
	}
	
	function get_icon($type, $url, $title, $icon = '', $popup = false, $blank = false) {
		if ($blank) {
			$url .= '" target="_blank';
		}
		if ($popup) {
			$url .= '" class="mr_social_sharing_popup_link';	
		}
		$text = '';
		switch ($type) {
			case 'none':
				$icon = ($icon == '') ? str_replace('includes/', '', plugins_url('/images/buttons/'.$this->icon.'.png', __FILE__)) : $icon;
				break;
			case 'icon_small':
				$icon = ($icon == '') ? str_replace('includes/', '', plugins_url('/images/icons_small/'.$this->icon.'.png', __FILE__)) : $icon;
				break;
			case 'icon_small_text':
				$icon = ($icon == '') ? str_replace('includes/', '', plugins_url('/images/icons_small/'.$this->icon.'.png', __FILE__)) : $icon;
				$text = '<span class="mr_small_icon">'.$title.'</span></a>';
				break;
			case 'icon_medium':
				$icon = ($icon == '') ? str_replace('includes/', '', plugins_url('/images/icons_medium/'.$this->icon.'.png', __FILE__)) : $icon;
				break;
			case 'icon_medium_text':
				$icon = ($icon == '') ? str_replace('includes/', '', plugins_url('/images/icons_medium/'.$this->icon.'.png', __FILE__)) : $icon;
				$text = '<span class="mr_medium_icon">'.$title.'</span></a>';
				break;
			case 'icon_large':
				$icon = ($icon == '') ? str_replace('includes/', '', plugins_url('/images/icons_large/'.$this->icon.'.png', __FILE__)) : $icon;
				break;
			default:
				$icon = ($icon == '') ? str_replace('includes/', '', plugins_url('/images/icons_small/'.$this->icon.'.png', __FILE__)) : $icon;
				break;
		}
		$retval = '<a href="'.$url.'"><img src="'.$icon.'" alt="'.$title.'" title="'.$title.'"/>'.$text.'</a>';
		return $retval;	
	}
	
	function enqueue_script($name, $src, $footer = false) {
		$this->scripts[] = array('name' => $name, 'src' => $src, 'in_footer' => $footer);
	}
	
	function get_enqueued_scripts() {
		return $this->scripts;	
	}
}
?>