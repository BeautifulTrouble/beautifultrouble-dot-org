<?php
class MR_Social_Sharing_Toolkit_Reddit extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'rd_reddit', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Reddit';
		$this->icon = 'reddit';
	}	

	function rd_reddit($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		switch ($type) {
			case 'horizontal':
				$retval = '<script type="text/javascript">
							  reddit_url = "'.$url.'";
							  reddit_title = "'.$title.'";
							</script>
							<script type="text/javascript" src="http://www.reddit.com/static/button/button1.js"></script>';
				break;
			case 'vertical':
				$retval = '<script type="text/javascript">
							  reddit_url = "'.$url.'";
							  reddit_title = "'.$title.'";
							</script><script type="text/javascript" src="http://www.reddit.com/static/button/button2.js"></script>';
				break;
			default:
				$url = 'http://www.reddit.com/submit?url='.urlencode($url);
				$text = ($text == '') ? __('Submit to','mr_social_sharing_toolkit').' reddit' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}
		return $retval;
	}
}
?>