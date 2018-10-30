<?php
/**
 * Footer Deeplink Juggernaut Module
 * 
 * @since 6.5
 */

if (class_exists('SU_Module')) {

class SU_FooterAutolinks extends SU_Module {
	
	static function get_parent_module() { return 'autolinks'; }
	static function get_child_order() { return 30; }
	static function is_independent_module() { return false; }
	
	static function get_module_title() { return __('Footer Deeplink Juggernaut', 'seo-ultimate'); }
	function get_module_subtitle() { return __('Footer Links', 'seo-ultimate'); }
	
	var $already_outputted = false;
	
	function init() {
		add_action('wp_footer', array(&$this, 'autolink_footer'));
	}
	
	function autolink_footer($args=array()) {
		
		if ($this->already_outputted) return;
		
		extract(wp_parse_args($args, array(
			  'footer_link_section_format' => $this->get_setting('footer_link_section_format', '{links}')
			, 'footer_link_format' => $this->get_setting('footer_link_format', '{link}')
			, 'footer_link_sep' => $this->get_setting('footer_link_sep', ' | ')
		)), EXTR_SKIP);
		
		$links = $this->get_setting('footer_links', array());
		suarr::vksort($links, 'anchor');
		
		$link_html = array();
		
		foreach ($links as $link_data) {
			
			if (isset($link_data['from']) && count($link_data['from']))
				$from = $link_data['from'][0];
			else
				$from = array('');
			
			$from_match_children = (isset($link_data['from_match_children']) && $link_data['from_match_children']);
			$from_match_negative = (isset($link_data['from_match_negative']) && $link_data['from_match_negative']);
			
			if (!isset($link_data['to'])) $link_data['to'] = '';
			
			list($from_genus, $from_type, $from_id) = $this->jlsuggest_value_explode($from);
			
			$is_from = $from_match_negative;
			switch ($from_genus) {
				case 'posttype':
					
					$post_ids = array($from_id);
					
					if ($from_match_children)
						$post_ids[] = wp_get_post_parent_id($from_id); //Requires WordPress 3.1
					
					foreach ($post_ids as $post_id) {
						if (is_single($post_id) || is_page($post_id)) {
							$is_from = !$from_match_negative;
							break;
						}
					}
					break;
					
				case 'taxonomy':
					
					if (	    suwp::is_tax($from_type, $from_id) //Is
							|| ($from_match_children && is_singular() && has_term($from_id, $from_type)) //In
							)
						$is_from = !$from_match_negative;
					break;
					
				case 'home':
					if (is_home())
						$is_from = !$from_match_negative;
					break;
				
				case 'author':
					if (		is_author($from_id)
							|| ($from_match_children && is_singular() && get_the_author_meta('id') == $from_id)
							)
						$is_from = !$from_match_negative;
					break;
					
				case 'url':
					
					if ($from_id) {
						if (	    suurl::equal(suurl::current(), $from_id)
								|| ($from_match_children && sustr::startswith(suurl::current(), $from_id))
								)
							$is_from = !$from_match_negative;
						
					} else
						$is_from = true; //No "from" restriction
					
					break;
			}
			
			if (!$is_from)
				continue;
			
			$h_anchor = esc_html($link_data['anchor']);
			$rel	= $link_data['nofollow'] ? ' rel="nofollow"' : '';
			$target	= ($link_data['target'] == 'blank') ? ' target="_blank"' : '';
			$title  = strlen($a_titletext = su_esc_attr($link_data['title'])) ? " title=\"$a_titletext\"" : '';
			
			$a_url = su_esc_attr($this->jlsuggest_value_to_url($link_data['to']));
			
			if (strlen(trim($h_anchor)) && strlen(trim((string)$a_url)) && $a_url != 'http://')
				$link_html[] = str_replace('{link}', "<a href=\"$a_url\"$title$rel$target>$h_anchor</a>", $footer_link_format);
		}
		
		echo str_replace('{links}', implode($footer_link_sep, $link_html), $footer_link_section_format);
	}
	
	function admin_page_init() {
		$this->jlsuggest_init();
	}
	
	function admin_page_contents() {
		
		$links = $this->get_setting('footer_links', array());
		$num_links = count($links);
		
		if ($this->is_action('update')) {
			
			$links = array();
			
			for ($i=0; $i <= $num_links; $i++) {
				
				$anchor = stripslashes($_POST["footer_link_{$i}_anchor"]);
				$from	= array(stripslashes($_POST["footer_link_{$i}_from"]));
				$from_match_children = isset($_POST["footer_link_{$i}_from_match_children"]) ? (intval($_POST["footer_link_{$i}_from_match_children"]) == 1) : false;
				$from_match_negative = isset($_POST["footer_link_{$i}_from_match_negative"]) ? (intval($_POST["footer_link_{$i}_from_match_negative"]) == 1) : false;
				$to		= stripslashes($_POST["footer_link_{$i}_to"]);
				$title  = stripslashes($_POST["footer_link_{$i}_title"]);
				$target = empty($_POST["footer_link_{$i}_target"]) ? 'self' : 'blank';
				
				$nofollow = isset($_POST["footer_link_{$i}_nofollow"]) ? (intval($_POST["footer_link_{$i}_nofollow"]) == 1) : false;
				$delete = isset($_POST["footer_link_{$i}_delete"]) ? (intval($_POST["footer_link_{$i}_delete"]) == 1) : false;
				
				if (!$delete && (strlen($anchor) || $to))
					$links[] = compact('anchor', 'from', 'from_match_children', 'from_match_negative', 'to', 'title', 'nofollow', 'target');
			}
			$this->update_setting('footer_links', $links);
			
			$num_links = count($links);
		}
		
		if ($num_links > 0) {
			$this->admin_subheader(__('Edit Existing Links', 'seo-ultimate'));
			$this->footer_links_form(0, $links);
		}
		
		$this->admin_subheader(__('Add a New Link', 'seo-ultimate'));
		$this->footer_links_form($num_links, array(array()), false);
	}
	
	function footer_links_form($start_id = 0, $links, $delete_option = true) {
		
		//Set headers
		$headers = array(
			  'link-from' => __('Link Location <em>(optional)</em>', 'seo-ultimate')
			, 'link-from-match' => ''
			, 'link-anchor' => __('Anchor Text', 'seo-ultimate')
			, 'link-to' => __('Destination', 'seo-ultimate')
			, 'link-title' => __('Title Attribute <em>(optional)</em>', 'seo-ultimate')
			, 'link-options' => __('Options', 'seo-ultimate')
		);
		if ($delete_option) $headers['link-delete'] = __('Delete', 'seo-ultimate');
		
		//Begin table; output headers
		$this->admin_wftable_start($headers);
		
		//Cycle through links
		$i = $start_id;
		foreach ($links as $link) {
			
			if (!isset($link['anchor']))	$link['anchor'] = '';
			if (!isset($link['from'][0]))	$link['from'][0] = '';
			if (!isset($link['from_match_children'])) $link['from_match_children'] = false;
			if (!isset($link['from_match_negative'])) $link['from_match_negative'] = false;
			if (!isset($link['to']))		$link['to'] = '';
			if (!isset($link['title']))		$link['title'] = '';
			if (!isset($link['nofollow']))	$link['nofollow'] = false;
			if (!isset($link['target']))	$link['target'] = '';
			
			$cells = array(
				  'link-from' => $this->get_jlsuggest_box("footer_link_{$i}_from", $link['from'][0], 'types=posttype,taxonomy,home,author')
				, 'link-from-match' =>
					 $this->get_input_element('checkbox', "footer_link_{$i}_from_match_children", $link['from_match_children'] == 1, str_replace(' ', '&nbsp;', __('Match child content', 'seo-ultimate')))
					.'<br />'
					.$this->get_input_element('checkbox', "footer_link_{$i}_from_match_negative", $link['from_match_negative'] == 1, str_replace(' ', '&nbsp;', __('Negative match', 'seo-ultimate')))
				/*, 'link-from-match' => $this->get_input_element('dropdown', "footer_link_{$i}_from_match", $link['from_match'], array(
					  'is' => __('If this:', 'seo-ultimate')
					, 'is,in' => __('If this or child:', 'seo-ultimate')
					, '!:is' => __('If not this:', 'seo-ultimate')
					, '!:is,in' => __('If not this and not child:', 'seo-ultimate')
				))*/
				, 'link-anchor' => $this->get_input_element('textbox', "footer_link_{$i}_anchor", $link['anchor'])
				, 'link-to' => $this->get_jlsuggest_box("footer_link_{$i}_to", $link['to'])
				, 'link-title' => $this->get_input_element('textbox', "footer_link_{$i}_title", $link['title'])
				, 'link-options' =>
					 $this->get_input_element('checkbox', "footer_link_{$i}_nofollow", $link['nofollow'], str_replace(' ', '&nbsp;', __('Nofollow', 'seo-ultimate')))
					.'<br />'
					.$this->get_input_element('checkbox', "footer_link_{$i}_target", $link['target'] == 'blank', str_replace(' ', '&nbsp;', __('New window', 'seo-ultimate')))
			);
			if ($delete_option)
				$cells['link-delete'] = $this->get_input_element('checkbox', "footer_link_{$i}_delete");
			
			$this->table_row($cells, $i, 'link');
			
			$i++;
		}
		
		$this->admin_wftable_end();
	}
}

}
?>