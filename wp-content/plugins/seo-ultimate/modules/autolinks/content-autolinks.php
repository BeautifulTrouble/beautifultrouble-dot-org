<?php
/**
 * Content Deeplink Juggernaut Module
 * 
 * @since 2.2
 */

if (class_exists('SU_Module')) {

class SU_ContentAutolinks extends SU_Module {
	
	var $legacy_sitewide_lpa_in_use = false;
	
	function get_parent_module() { return 'autolinks'; }
	function get_child_order() { return 10; }
	function is_independent_module() { return false; }
	
	function get_module_title() { return __('Content Deeplink Juggernaut', 'seo-ultimate'); }
	function get_module_subtitle() { return __('Content Links', 'seo-ultimate'); }
	
	function init() {
		add_filter('the_content', array(&$this, 'autolink_content'));
		
		add_filter('su_postmeta_help', array(&$this, 'postmeta_help'), 35);
		add_filter('su_get_postmeta-autolinks', array(&$this, 'get_post_autolinks'), 10, 3);
		add_filter('su_custom_update_postmeta-autolinks', array(&$this, 'save_post_autolinks'), 10, 4);
		
		add_filter('su_get_setting-autolinks-linkfree_tags', array(&$this, 'filter_linkfree_tags'));
		add_filter('su_get_setting-autolinks-dampen_sitewide_lpa_value', array(&$this, 'filter_dampen_sitewide_lpa_value'));
	}
	
	function filter_linkfree_tags($tags) {
		return sustr::preg_filter('a-z0-9,', strtolower($tags));
	}
	
	function filter_dampen_sitewide_lpa_value($value) {
		$value = sustr::to_int($value);
		if ($value > 100) $value = 100;
		return $value;
	}
	
	function autolink_content($content) {
		
		$links = $this->get_setting('links', array());
		if (!count($links)) return $content;
		
		suarr::vklrsort($links, 'anchor');
		$links = array_values($links);
		
		//Dummy vars; needed for PHP4 compat
		$count = 0;
		$link_count = array();
		$content = $this->_autolink_content(suwp::get_post_id(), $content, $links, $this->get_setting('limit_lpp_value', 5), $count, $link_count);
		
		return $content;
	}
	
	function _autolink_content($id, $content, $links, $limit, &$count, &$link_count, $round=1, $linked_urls=array(), $context='the_content') {
		$links = array_values($links);
		$count = 0;
		
		if (!is_array($link_count)) $link_count = array();
		$i=0; foreach ($links as $data) {
			if (!isset($link_count[$i]))
				$link_count[$i] = 0;
			$i++;
		}
		
		if (!$this->get_setting('autolink_posttype_' . get_post_type($id)))
			return $content;
		
		if ($this->get_postmeta('disable_autolinks', $id))
			return $content;
		
		$limit_enabled = $this->get_setting('limit_lpp', false);
		if ($limit_enabled && $limit < 1)
			return $content;
		$oldlimit = $limit;
		
		$lpa_limit_enabled = $this->get_setting('limit_lpa', false);
		$lpa_limit = $lpa_limit_enabled ? $this->get_setting('limit_lpa_value', 5) : -1;
		
		$lpu_limit_enabled = $this->get_setting('limit_lpu', false);
		$lpu_limit = $lpu_limit_enabled ? $this->get_setting('limit_lpu_value', 1) : -1;
		
		$from_post_type = get_post_type();
		$dest_limit = $from_post_type ? (bool)$this->get_setting('dest_limit_' . $from_post_type, false) : false;
		$dest_limit_taxonomies = array();
		if ($dest_limit) {
			$from_post_type_taxonomies = suwp::get_object_taxonomy_names($from_post_type);
			foreach ($from_post_type_taxonomies as $from_post_type_taxonomy) {
				if ($this->get_setting('dest_limit_' . $from_post_type . '_within_' . $from_post_type_taxonomy, false))
					$dest_limit_taxonomies[] = $from_post_type_taxonomy;
			}
		}
		
		$autolink_class = $this->get_setting('autolink_class', '');
		
		$post = get_post($id);
		
		$i=0;
		foreach ($links as $data) {
			$anchor = $data['anchor'];
			$to_id = su_esc_attr($data['to_id']);
			
			if (strlen(trim($anchor)) && $to_id !== 0 && $to_id != 'http://') {
				
				//*** Begin sitewide links-per-anchor dampening effect ***
				
				//Get the dampening percentage for this link, but only if per-link values are enabled
				if ($this->get_setting('enable_perlink_dampen_sitewide_lpa', false))
					$link_dswlpa = $data['dampen_sitewide_lpa']; //Should be a number (0 to 100 inclusive) or bool(false)
				else
					$link_dswlpa = false;
				
				if (false === $link_dswlpa) { //We need the === operator here so we don't match a zero
					
					//There's no per-link value, so get the default, if a default value is specified and enabled
					if ($this->get_setting('dampen_sitewide_lpa', false))
						$link_dswlpa = $this->get_setting('dampen_sitewide_lpa_value', 0);
					else
						$link_dswlpa = false; //Indicates there's neither a per-link value or a default value available
				}
				
				if (false !== $link_dswlpa) {
					$link_dswlpa = absint($link_dswlpa);
					if ($link_dswlpa == 0) break;
					if ($link_dswlpa > 100) $link_dswlpa = 100;
					
					//Rather than generating a random number, we use the MD5s of the anchor and the post's ID.
					//This gives us a quasi-random dampening effect that will turn out the same way for any given post each time the dampener is applied.
					//We don't want a post's autolinks changing every time the post is viewed.
					$md5starts = array_slice(array_unique(str_split(md5($anchor))), 0, intval(round(16*(1-($link_dswlpa / 100)))));
					
					//Only apply this autolink if the MD5 of the post's ID starts with one of the allowed characters
					if (!in_array(substr(md5($id), 0, 1), $md5starts))
						continue; //Don't apply autolink; continue to next item in the $links foreach loop
				}
				
				//*** End sitewide LPA dampener ***
				
				
				$type = $data['to_type'];
				
				if (sustr::startswith($type, 'posttype_')) {
					$to_id = intval($to_id);
					$to_post = get_post($to_id);
					
					if (get_post_status($to_id) != 'publish') continue;
					
					if (count($dest_limit_taxonomies)) {
						$shares_term = false;
						foreach ($dest_limit_taxonomies as $dest_limit_taxonomy) {
							$from_terms = suarr::flatten_values(get_the_terms(null, $dest_limit_taxonomy), 'term_id');
							
							if (is_object_in_taxonomy($to_post, $dest_limit_taxonomy))
								$to_terms = suarr::flatten_values(get_the_terms($to_id, $dest_limit_taxonomy), 'term_id');
							else
								$to_terms = array();
							
							if (count(array_intersect($from_terms, $to_terms))) {
								$shares_term = true;
								break;
							}
						}
						
						if (!$shares_term)
							continue;
					}
					
					$url = get_permalink($to_id);
				} elseif ($type == 'url')
					$url = $to_id;
				else
					$url = $this->jlsuggest_value_to_url($to_id ? "obj_$type/$to_id" : "obj_$type");
				
				if (!is_string($url))
					continue;
				
				if (!$this->get_setting('enable_current_url_links', false) && suurl::equal($url, suurl::current()))
					continue;
				
				if (!$this->get_setting('enable_self_links', false) && is_singular() && suurl::equal($url, get_permalink()))
					continue;
				
				if ($lpu_limit_enabled && isset($linked_urls[$url]) && $linked_urls[$url] >= $lpu_limit)
					continue;
				
				$rel	= $data['nofollow'] ? ' rel="nofollow"' : '';
				$target	= ($data['target'] == 'blank') ? ' target="_blank"' : '';
				$title	= strlen($titletext = su_esc_attr($data['title'])) ? " title=\"$titletext\"" : '';
				$class  = $autolink_class ? ' class="' . su_esc_attr($autolink_class) . '"' : '';
				$a_url  = su_esc_attr($url);
				$h_anchor = esc_html($anchor);
				
				$link = "<a href=\"$a_url\"$title$rel$target$class>$1</a>";
				
				$lpa_lpu_limits = array();
				if ($lpa_limit_enabled) $lpa_lpu_limits[] = $lpa_limit;
				if ($lpu_limit_enabled) $lpa_lpu_limits[] = $lpu_limit;
				$lpa_lpu_limits = count($lpa_lpu_limits) ? sunum::lowest($lpa_lpu_limits) : -1;
				
				$content = sustr::htmlsafe_str_replace($h_anchor, $link, $content, $limit_enabled ? 1 : $lpa_lpu_limits, $new_count, $this->get_linkfree_tags());
				$link_count[$i] += $new_count;
				$count += $new_count;
				
				if ($lpu_limit_enabled) {
					if (isset($linked_urls[$url]))
						$linked_urls[$url] += $new_count;
					else
						$linked_urls[$url] = $new_count;
				}
				
				if ($limit_enabled) {
					$limit -= $new_count;
					if ($limit < 1) return $content;
				}
			}
			
			$i++;
		}
		
		if ($limit_enabled && $limit < $oldlimit && $round < $lpa_limit)
			$content = $this->_autolink_content($id, $content, $links, $limit, $count, $link_count, $round+1, $linked_urls, $context);
		
		return $content;
	}
	
	function get_linkfree_tags() {
		if ($linkfree_tags = $this->get_setting('linkfree_tags')) {
			$linkfree_tags = explode(',', $linkfree_tags);
			array_unshift($linkfree_tags, 'a');
		} else {
			$linkfree_tags = array('a');
		}
		
		return $linkfree_tags;
	}
	
	function admin_page_init() {
		$this->jlsuggest_init();
	}
	
	function admin_page_contents() {
		
		echo "\n<p>";
		_e('The Content Links section of Deeplink Juggernaut lets you automatically link a certain word or phrase in your post/page content to a URL you specify.', 'seo-ultimate');
		echo "</p>\n";
		
		$links = $this->get_setting('links', array());
		$num_links = count($links);
		
		if ($this->is_action('update')) {
			
			$links = array();
			
			$guid = stripslashes($_POST['_link_guid']);
			
			for ($i=0; $i <= $num_links; $i++) {
				
				$anchor = stripslashes($_POST["link_{$i}_anchor"]);
				
				$to	= stripslashes($_POST["link_{$i}_to"]);
				
				if (sustr::startswith($to, 'obj_')) {
					$to = sustr::ltrim_str($to, 'obj_');
					$to = explode('/', $to);
					if (count($to) == 2) {
						$to_type = $to[0];
						$to_id = $to[1];
					} else {
						$to_type = $to[0];
						$to_id = null;
					}
				} else {
					$to_type = 'url';
					$to_id = $to;
				}
				
				$title  = stripslashes($_POST["link_{$i}_title"]);
				
				$dampen_sitewide_lpa = sustr::preg_filter('0-9', strval($_POST["link_{$i}_dampen_sitewide_lpa"]));
				$dampen_sitewide_lpa = ($dampen_sitewide_lpa === '') ? false : intval($dampen_sitewide_lpa);
				
				$sitewide_lpa = isset($_POST["link_{$i}_sitewide_lpa"]) ? sustr::preg_filter('0-9', strval($_POST["link_{$i}_sitewide_lpa"])) : '';
				$sitewide_lpa = ($sitewide_lpa === '') ? false : intval($sitewide_lpa);
				
				$target = empty($_POST["link_{$i}_target"]) ? 'self' : 'blank';
				
				$nofollow = isset($_POST["link_{$i}_nofollow"]) ? (intval($_POST["link_{$i}_nofollow"]) == 1) : false;
				$delete = isset($_POST["link_{$i}_delete"]) ? (intval($_POST["link_{$i}_delete"]) == 1) : false;
				
				if (!$delete && (strlen($anchor) || $to_id))
					$links[] = compact('anchor', 'to_type', 'to_id', 'title', 'dampen_sitewide_lpa', 'sitewide_lpa', 'nofollow', 'target');
			}
			$this->update_setting('links', $links);
			
			$num_links = count($links);
		}
		
		$this->legacy_sitewide_lpa_in_use = false;
		foreach ($links as $link) {
			if (isset($link['sitewide_lpa']) && $link['sitewide_lpa']) {
				$this->legacy_sitewide_lpa_in_use = true;
				break;
			}
		}
		
		if ($this->legacy_sitewide_lpa_in_use) {
			$this->print_message('warning', __('<strong>Functionality Change Notice:</strong> The &#8220;Site Cap&#8221; feature (which allowed you set a per-link sitewide quantity limit) has been replaced with a more efficient &#8220;Dampener&#8221; feature that lets you reduce autolinking frequency by a percentage. Although the Site Cap feature has been replaced, we retained the &#8220;Site Cap&#8221; column for you in the table below, since it looks like you&#8217;ve used the Site Cap feature in the past. We retained the column to help you remember which links used the old feature, so that you know to which links to apply the new &#8220;Dampener&#8221; feature. Once you&#8217;re done migrating the Site Cap values to Dampener percentages, just clear the &#8220;Site Cap&#8221; boxes to make those boxes (and this message) go away.', 'seo-ultimate'));
		}
		
		$guid = substr(md5(time()), 0, 10);
		
		if ($num_links > 0) {
			$this->admin_subheader(__('Edit Existing Links', 'seo-ultimate'));
			$this->content_links_form($guid, 0, $links);
		}
		
		$this->admin_subheader(__('Add a New Link', 'seo-ultimate'));
		$this->content_links_form($guid, $num_links, array(array()), false);
	}
	
	function content_links_form($guid, $start_id = 0, $links, $delete_option = true) {
		
		//Get settings
		$default_dampen_sitewide_lpa = $this->get_setting('dampen_sitewide_lpa', false, null, true)
			? $this->get_setting('dampen_sitewide_lpa_value', 0, null, true)
			: false;
		
		//Set headers
		$headers = array();
		$headers['link-anchor'] = __('Anchor Text', 'seo-ultimate');
		$headers['link-to'] = __('Destination', 'seo-ultimate');
		$headers['link-title'] = __('Title Attribute <em>(optional)</em>', 'seo-ultimate');
		if ($this->get_setting('enable_perlink_dampen_sitewide_lpa', false, null, true) || $this->legacy_sitewide_lpa_in_use)
			$headers['link-dampen-sitewide-lpa'] = __('Dampener', 'seo-ultimate');
		if ($this->legacy_sitewide_lpa_in_use)
			$headers['link-sitewide-lpa'] = __('Site Cap', 'seo-ultimate');
		$headers['link-options'] = __('Options', 'seo-ultimate');
		if ($delete_option)
			$headers['link-delete'] = __('Delete', 'seo-ultimate');
		
		//Begin table; output headers
		$this->admin_wftable_start($headers);
		
		//Cycle through links
		$i = $start_id;
		foreach ($links as $link) {
			
			if (!isset($link['anchor']))		$link['anchor'] = '';
			if (!isset($link['to_id']))			$link['to_id'] = '';
			if (!isset($link['to_type']))		$link['to_type'] = 'url';
			if (!isset($link['title']))			$link['title'] = '';
			if (!isset($link['dampen_sitewide_lpa']))	$link['dampen_sitewide_lpa'] = '';
			if (!isset($link['sitewide_lpa']))	$link['sitewide_lpa'] = '';
			if (!isset($link['nofollow']))		$link['nofollow'] = false;
			if (!isset($link['target']))		$link['target'] = '';
			
			$to_type_arr = array_pad(explode('_', $link['to_type'], 2), 2, null);
			$jlsuggest_box_params = array($to_type_arr[0], $to_type_arr[1], $link['to_id']);
			
			$cells = array();
			$cells['link-anchor'] = $this->get_input_element('textbox', "link_{$i}_anchor", $link['anchor']);
			$cells['link-to'] = $this->get_jlsuggest_box("link_{$i}_to", $jlsuggest_box_params);
			$cells['link-title'] = $this->get_input_element('textbox', "link_{$i}_title", $link['title']);
			
			if ($this->get_setting('enable_perlink_dampen_sitewide_lpa', false, null, true) || $this->legacy_sitewide_lpa_in_use) {
				$cells['link-dampen-sitewide-lpa'] = $this->get_input_element('textbox', "link_{$i}_dampen_sitewide_lpa", $link['dampen_sitewide_lpa'], $default_dampen_sitewide_lpa) . '%';
				$cells['link-options'] = '';
			} else {
				$cells['link-options'] = $this->get_input_element('hidden', "link_{$i}_dampen_sitewide_lpa", $link['dampen_sitewide_lpa'], $default_dampen_sitewide_lpa);
			}
			
			if ($this->legacy_sitewide_lpa_in_use)
				$cells['link-sitewide-lpa'] = $this->get_input_element('textbox', "link_{$i}_sitewide_lpa", $link['sitewide_lpa']);
			
			$cells['link-options'] .=
					 $this->get_input_element('checkbox', "link_{$i}_nofollow", $link['nofollow'], str_replace(' ', '&nbsp;', __('Nofollow', 'seo-ultimate')))
					.'<br />'
					.$this->get_input_element('checkbox', "link_{$i}_target", $link['target'] == 'blank', str_replace(' ', '&nbsp;', __('New window', 'seo-ultimate')));
			if ($delete_option)
				$cells['link-delete'] = $this->get_input_element('checkbox', "link_{$i}_delete");
			
			$this->table_row($cells, $i, 'link');
			
			$i++;
		}
		
		$this->admin_wftable_end();
		echo $this->get_input_element('hidden', '_link_guid', $guid);
	}
	
	function get_post_autolinks($value, $key, $post) {
		$links = $this->get_setting('links', array());
		$postlinks = '';
		foreach ($links as $link_data) {
			if ($link_data['to_type'] == 'posttype_'.$post->post_type && $link_data['to_id'] == $post->ID)
				$postlinks .= $link_data['anchor']."\r\n";
		}
		return trim($postlinks);
	}
	
	function save_post_autolinks($false, $value, $metakey, $post) {
		if ($post->post_type == 'revision') return true;
		
		$links = $this->get_setting('links', array());
		$new_links = array();
		
		$keep_anchors = array();
		$others_anchors = array();
		$new_anchors = suarr::explode_lines($value);
		$new_anchors = array_map('trim', $new_anchors);
		array_filter($new_anchors);
		
		if (count($new_anchors)) {
			
			foreach ($links as $link_data) {
				if ($link_data['to_type'] == 'posttype_'.$post->post_type && $link_data['to_id'] == $post->ID) {
					if (in_array($link_data['anchor'], $new_anchors)) {
						$keep_anchors[] = $link_data['anchor'];
						$new_links[] = $link_data;
					}
				} else {
					$others_anchors[] = $link_data['anchor'];
					$new_links[] = $link_data;
				}
			}
			
			$anchors_to_add = array_diff($new_anchors, $keep_anchors, $others_anchors);
			
			if (count($anchors_to_add)) {
				foreach ($anchors_to_add as $anchor_to_add) {
					if (trim($anchor_to_add))
						$new_links[] = array(
							  'anchor' => $anchor_to_add
							, 'to_type' => 'posttype_'.$post->post_type
							, 'to_id' => $post->ID
							, 'title' => ''
							, 'nofollow' => false
							, 'target' => 'self'
						);
				}
			}
			
			$this->update_setting('links', $new_links);
		}
		
		return true;
	}
	
	function postmeta_fields($fields) {
		$id = suwp::get_post_id();
		
		if ($id)
			$type = get_post_type($id);
		elseif (!empty($_GET['post_type']))
			$type = $_GET['post_type'];
		else
			$type = 'post';
		
		$fields['links'][10]['autolinks'] = $this->get_postmeta_textarea('autolinks', __('Inbound Autolink Anchors:<br /><em>(one per line)</em>', 'seo-ultimate'));
		
		if ($this->get_setting("autolink_posttype_$type"))
			$fields['links'][15]['disable_autolinks'] = $this->get_postmeta_checkbox('disable_autolinks', __('Don&#8217;t add autolinks to anchor texts found in this post.', 'seo-ultimate'), __('Autolink Exclusion:', 'seo-ultimate'));
		
		return $fields;
	}
	
	function postmeta_help($help) {
		$help[] = __('<strong>Incoming Autolink Anchors</strong> &mdash; When you enter anchors into this box, Deeplink Juggernaut will search for that anchor in all your other posts and link it to this post. For example, if the post you&#8217;re editing is about &#8220;blue widgets,&#8221; you could type &#8220;blue widgets&#8221; into the &#8220;Incoming Autolink Anchors&#8221; box and Deeplink Juggernaut will automatically build internal links to this post with that anchor text (assuming other posts contain that text). If you&#8217;re working on a draft post or a scheduled post, don&#8217;t worry &mdash; SEO Ultimate won&#8217;t add autolinks to this post until it&#8217;s published.', 'seo-ultimate');
		return $help;
	}
}

}
?>