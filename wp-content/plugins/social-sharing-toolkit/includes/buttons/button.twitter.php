<?php
class MR_Social_Sharing_Toolkit_Twitter extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'tw_tweet', 'id' => '@', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_twitter', 'id' => '@', 'types' => array('none', 'horizontal', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Twitter';
		$this->icon = 'twitter';
		//add_shortcode('social_share_twitter', array($this, 'shortcode_tweet'));
		//add_shortcode('social_follow_twitter', array($this, 'shortcode_follow'));
	}

	function tw_tweet($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		$count_url = '';
		$bitly = get_option('mr_social_sharing_bitly');
		if ($bitly['enable'] == 1 && $bitly['username'] != '' && $bitly['key'] != '') {
			if (is_array($bitly['cache']) && array_key_exists($url, $bitly['cache'])) {
				$count_url = $url;
				$url = $bitly['cache'][$url];
			} else {
				$ch = curl_init('https://api-ssl.bitly.com/v3/shorten?login='.$bitly['username'].'&apiKey='.$bitly['key'].'&longUrl='.urlencode($url).'&format=txt');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				if ($short_url = curl_exec($ch)) {
					$count_url = $url;
					$url = trim($short_url);
					$bitly['cache'][$count_url] = $url;
					update_option( 'mr_social_sharing_bitly', $bitly);
				}
			}
		}
		switch ($type) {
			case 'horizontal':
				$retval = '<a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="'.$url.'"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				if ($count_url != '') {
					$retval .= ' data-counturl="'.$count_url.'"';	
				}
				$retval .= ' data-text="'.$title.'">Tweet</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_twitter', 'https://platform.twitter.com/widgets.js', $footer);
				break;
			case 'vertical':
				$retval = '<a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical" data-url="'.$url.'"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				if ($count_url != '') {
					$retval .= ' data-counturl="'.$count_url.'"';	
				}
				$retval .= ' data-text="'.$title.'">Tweet</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_twitter', 'https://platform.twitter.com/widgets.js', $footer);
				break;
			case 'none':
				$retval = '<a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-url="'.$url.'"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				if ($count_url != '') {
					$retval .= ' data-counturl="'.$count_url.'"';	
				}
				$retval .= ' data-text="'.$title.'">Tweet</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_twitter', 'https://platform.twitter.com/widgets.js', $footer);
				break;
			default:
				$url = 'https://twitter.com/share?url='.urlencode($url).'&amp;text='.urlencode($title);
				if ($id != '') {
					$url .= '&amp;via='.$id;
				}
				if ($count_url != '') {
					$url .= '&amp;counturl='.urlencode($count_url);	
				}
				$text = ($text == '') ? __('Share on','mr_social_sharing_toolkit').' Twitter' : $text;
				$retval = $this->get_icon($type, $url, $text, $icon, true);
				break;
		}		
		return $retval;
	}	
	
	function follow_twitter($type, $id, $text = '', $icon = '') {
		switch ($type) {
			case 'none':
				$retval = '<a href="https://twitter.com/'.$id.'" class="twitter-follow-button" data-show-count="false">Follow @'.$id.'</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_twitter', 'https://platform.twitter.com/widgets.js', $footer);
				break;
			case 'horizontal':
				$retval = '<a href="https://twitter.com/'.$id.'" class="twitter-follow-button">Follow @'.$id.'</a>';
				$footer = (get_option('mr_social_sharing_js_footer') == 1) ? true : false;
				$this->enqueue_script('Social_sharing_twitter', 'https://platform.twitter.com/widgets.js', $footer);
				break;
			default:
				$url = 'https://twitter.com/'.$id;
				$text = ($text == '') ? __('Follow me on','mr_social_sharing_toolkit').' Twitter' : $text;
				$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
				$retval = $this->get_icon($type, $url, $text, $icon, false, $blank);
				break;	
		}
		return $retval;
	}
	
	/*function shortcode_tweet() {
		if (!is_feed()) {		
			extract(shortcode_atts(array('id' => '', 'type' => 'none', 'title' => get_the_title(), 'url' => get_permalink()), $atts));
			return $this->tw_tweet($url, $title, $type, $id);
		}
		return '';
	}
	
	function shortcode_follow() {
		if (!is_feed()) {
			extract(shortcode_atts(array('id' => '', 'type' => 'none'), $atts));
			if ($id != '') {
				return $this->follow_twitter($type, $id);
			}
		}
		return '';
	}*/
}
$MR_Social_Sharing_Toolkit_Twitter = new MR_Social_Sharing_Toolkit_Twitter();
?>