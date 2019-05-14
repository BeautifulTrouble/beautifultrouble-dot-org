<?php
class MR_Social_Sharing_Toolkit_LinksAlpha extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'linksalpha', 'types' => array('none', 'icon_small', 'icon_medium', 'icon_large'));
		$this->title = 'LinksAlpha Share';
		$this->icon = 'share';
	}
	function linksalpha($url, $title, $type, $id = '', $media = '', $description = '', $text = '', $icon = '') {
		switch ($type) {
			case 'icon_small':
				$image = 'social_share_'.$type.'.png';
				break;
			case 'icon_medium':
				$image = 'social_share_'.$type.'.png';
				break;
			case 'icon_large':
				$image = 'social_share_'.$type.'.png';
				break;
			default:
				$image = 'social_share_button.png';
				break;
		}
		$retval =  '<a rel="nofollow" class="linksalpha_button linksalpha_link" href="//www.linksalpha.com/social/mobile" data-url="'.$url.'" data-text="'.$title.'" data-desc="'.$description.'" data-image="'.$media.'" data-button="'.$type.'">
					<img src="//www.linksalpha.com/images/'.$image.'" alt="Share" class="linksalpha_image" />
					</a><script type="text/javascript" src="//www.linksalpha.com/scripts/loader_iconbox.js?v=2.4"></script>';
		return $retval;
	}
}
$MR_Social_Sharing_Toolkit_LinksAlpha = new MR_Social_Sharing_Toolkit_LinksAlpha();
?>