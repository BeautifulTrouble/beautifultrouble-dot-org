<?php

class SU_InternalLinkAliases extends SU_Module {
	
	function get_default_settings() {
		return array(
			  'alias_dir' => 'go'
		);
	}
	
	function init() {
		add_filter('su_custom_update_postmeta-aliases', array(&$this, 'save_post_aliases'), 10, 4);
		add_filter('su_get_setting-internal-link-aliases-alias_dir', array(&$this, 'filter_alias_dir'));
		
		if (suwp::permalink_mode() == SUWP_PRETTY_PERMALINKS) {
			add_filter('the_content', array(&$this, 'apply_aliases'), 9); //Run before wp_texturize etc.
			add_action('template_redirect', array(&$this, 'redirect_aliases'), 0);
			add_action('do_robotstxt', array(&$this, 'block_aliases_dir'));
			add_action('su_do_robotstxt', array(&$this, 'block_aliases_dir'));
		}
	}
	
	function admin_page_init() {
		$this->jlsuggest_init();
	}
	
	static function get_module_title() { return __('Link Mask Generator', 'seo-ultimate'); }
	static function get_menu_title() { return __('Link Mask Generator', 'seo-ultimate'); }
	
	function get_settings_key() { return 'internal-link-aliases'; }
	
	function admin_page_contents() {
		
		if (suwp::permalink_mode() != SUWP_PRETTY_PERMALINKS)
			$this->print_message('error', sprintf(__('Link Mask Generator won&#8217;t work with default or &#8220;pathinfo&#8221; permalinks. Please change your <a href="%s">permalink structure</a> to enable this module&#8217;s functionality.', 'seo-ultimate'), 'options-permalink.php'));
		
		$this->children_admin_page_tabs_form();
	}
	
	function get_admin_page_tabs() {
		return array(
			  array('id' => 'aliases',  'title' => __('Aliases', 'seo-ultimate'),  'callback' => 'editor_tab')
			, array('id' => 'settings', 'title' => __('Settings', 'seo-ultimate'), 'callback' => 'settings_tab')
		);
	}
	
	function remove_empty_aliases($alias) {
		return !empty($alias['to']);
	}
	
	function editor_tab() {
		
		$aliases = $this->get_setting('aliases', array());
		$aliases = array_map('unserialize', array_unique(array_map('serialize', $aliases)));
		$aliases = array_filter($aliases, array(&$this, 'remove_empty_aliases'));
		$num_aliases = count($aliases);
		
		if ($this->is_action('update')) {
			
			$aliases = array();
			
			for ($i=0; $i <= $num_aliases; $i++) {
				
				$id 	= stripslashes($_POST["alias_{$i}_id"]);
				$from	= stripslashes($_POST["alias_{$i}_from"]);
				$to		= stripslashes($_POST["alias_{$i}_to"]);
				
				$jls_post = stripslashes($_POST["alias_{$i}_posts"]);
				if ($jls_post) {
					$jls_post = $this->jlsuggest_value_explode($jls_post);
					$posts = array($jls_post[2]);
				} else {
					$posts = array();
				}
				
				$delete = isset($_POST["alias_{$i}_delete"]) ? (intval($_POST["alias_{$i}_delete"]) == 1) : false;
				
				if (!$delete && $from && $to)
					$aliases[$id] = compact('from', 'to', 'posts');
			}
			$this->update_setting('aliases', $aliases);
			
			$num_aliases = count($aliases);
		}
		
		if ($num_aliases > 0) {
			$this->admin_subheader(__('Edit Existing Aliases', 'seo-ultimate'));
			$this->aliases_form(0, $aliases);
		}
		
		$this->admin_subheader(__('Add a New Alias', 'seo-ultimate'));
		$this->aliases_form($num_aliases, array(array()), false);
	}
	
	function aliases_form($start_id = 0, $aliases, $existing_item = true) {
		
		//Set headers
		$headers = array(
			  'alias-from' => __('Actual URL', 'seo-ultimate')
			, 'alias-to' => __('Alias URL', 'seo-ultimate')
			, 'alias-posts' => __('Only on This Post&hellip; <em>(optional)</em>', 'seo-ultimate')
		);
		if ($existing_item) $headers['alias-delete'] = __('Delete', 'seo-ultimate');
		
		//Begin table; output headers
		$this->admin_wftable_start($headers);
		
		//Cycle through links
		$i = $start_id;
		foreach ($aliases as $id => $alias) {
			
			if (!is_string($id)) $id = uniqid($i, true);
			
			if (!isset($alias['from']))	$alias['from'] = '';
			if (!isset($alias['to']))	$alias['to'] = '';
			$u_alias_to = urlencode($alias['to']);
			
			if (isset($alias['posts'][0]))
				$jlsuggest_value = 'obj_posttype_' . get_post_type($alias['posts'][0]) . '/' . $alias['posts'][0];
			else
				$jlsuggest_value = '';
			
			$alias_dir = $this->get_setting('alias_dir', 'go', null, true);
			$alias_url = get_bloginfo('url') . "/$alias_dir/$u_alias_to/";
			
			$test_link = $existing_item ? "<td class='su-alias-to-test'>[<a href='$alias_url' target='_blank'>" . __('Test', 'seo-ultimate') . "</a>]</td>" : '';
			
			$cells = array(
				  'alias-from' =>
					  $this->get_input_element('hidden',  "alias_{$i}_id",   $id)
					. $this->get_input_element('textbox', "alias_{$i}_from", $alias['from'])
				, 'alias-to' => "
<table><tr>
	<td class='su-alias-to-dir'>/$alias_dir/</td>
	<td class='su-alias-to-slug'>" . $this->get_input_element('textbox', "alias_{$i}_to", $alias['to']) . "</td>
	$test_link
</tr></table>"
				, 'alias-posts' => $this->get_jlsuggest_box("alias_{$i}_posts", $jlsuggest_value, 'types=posttype')
			);
			if ($existing_item)
				$cells['alias-delete'] = $this->get_input_element('checkbox', "alias_{$i}_delete");
			
			$this->table_row($cells, $i, 'alias');
			
			$i++;
		}
		
		$this->admin_wftable_end();
	}
	
	function settings_tab() {
		$this->admin_form_table_start();
		$this->textbox('alias_dir', __('Alias Directory', 'seo-ultimate'), $this->get_default_setting('alias_dir'));
		if ($this->plugin->module_exists('link-nofollow'))
			$this->checkbox('nofollow_aliased_links', __('Nofollow masked links', 'seo-ultimate'), __('Link Attributes', 'seo-ultimate'));
		$this->admin_form_table_end();
	}
	
	function filter_alias_dir($alias_dir) {
		return trim(sustr::preg_filter('a-zA-Z0-9_/', $alias_dir), '/');
	}
	
	function postmeta_fields($fields, $screen) {
		
		if (!current_user_can('manage_options'))
			return $fields;
		
		$post_id = suwp::get_post_id();
		$post = get_post($post_id);
		if (!$post) return $fields;
		$content = $post->post_content;
		
		$alias_dir = $this->get_setting('alias_dir', 'go');
		
		if ($content && preg_match_all('@ href=["\']([^#][^"\']+)["\']@', $content, $matches)) {
			$urls = array_unique($matches[1]);
			
			$html = "<tr valign='top'>\n<th scope='row' class='su'>".__('Link Masks:', 'seo-ultimate')."</th>\n<td>\n";
			
			$html .= "<table class='widefat'><thead>\n";
			$headers = array(__('URL', 'seo-ultimate'), '', __('Mask URL', 'seo-ultimate'));
			foreach ($headers as $header) $html .= "<th>$header</th>\n";
			$html .= "</thead>\n<tbody>";
			
			$aliases = $this->get_setting('aliases', array());
			$post_aliases = array();
			foreach ($aliases as $id => $alias) {
				if (empty($alias['posts']) || in_array($post->ID, $alias['posts']))
					$post_aliases[$alias['from']] = array('id' => $id, 'to' => $alias['to']);
			}
			
			foreach ($urls as $url) {
				
				$a_url = su_esc_attr($url);
				$un_h_url = htmlspecialchars_decode($url);
				$ht_url = esc_html(sustr::truncate($url, 30));
				
				if (isset($post_aliases[$url]))
					$url_key = $url;
				elseif (isset($post_aliases[$un_h_url]))
					$url_key = $un_h_url;
				else
					$url_key = false;
				
				$alias_to = '';
				$alias_id = uniqid('', true);
				if ($url_key) {
					if (isset($post_aliases[$url_key]['to'])) $alias_to = $post_aliases[$url_key]['to'];
					if (isset($post_aliases[$url_key]['id'])) $alias_id = $post_aliases[$url_key]['id'];
				}
				
				$a_alias_to = esc_attr($alias_to);
				
				$html .= "<tr><td><a href='$a_url' title='$a_url' target='_blank'>$ht_url</a><input type='hidden' name='_su_aliases[$alias_id][from]' value='$a_url' /></td>\n<td>&rArr;</td><td>/$alias_dir/<input type='text' name='_su_aliases[$alias_id][to]' value='$a_alias_to' /></td></tr>\n";
			}
			
			$html .= "</tbody>\n</table>\n";
			
			$html .= '<p><small>' . __('You can stop search engines from following a link by typing in a mask for its URL.', 'seo-ultimate') . "</small></p>\n";
			
			$html .= "</td>\n</tr>\n";
			
			$fields['links'][100]['aliases'] = $html;
		}
		
		return $fields;
	}
	
	function save_post_aliases($false, $saved_aliases, $metakey, $post) {
		if ($post->post_type == 'revision' || !is_array($saved_aliases)) return true;
		
		$aliases = $this->get_setting('aliases', array());
		
		foreach ($saved_aliases as $saved_id => $saved_data) {
			
			if (isset($aliases[$saved_id])) {
				
				if ($saved_data['to'])
					$aliases[$saved_id]['to'] = $saved_data['to'];
				else
					unset($aliases[$saved_id]);
				
			} elseif ($saved_data['to']) {
				$aliases[$saved_id]['from'] = $saved_data['from'];
				$aliases[$saved_id]['to'] = $saved_data['to'];
				$aliases[$saved_id]['posts'] = array($post->ID);
			}
		}
		
		$this->update_setting('aliases', $aliases);
		
		return true;
	}
	
	function apply_aliases($content) {
		return preg_replace_callback('@<a ([^>]*)href=(["\'])([^"\']+)(["\'])([^>]*)>@', array(&$this, 'apply_aliases_callback'), $content);
	}
	
	function apply_aliases_callback($matches) {
		$id = suwp::get_post_id();
		
		static $aliases = false;
		//Just in case we have duplicate aliases, make sure the most recent ones are applied first
		if ($aliases === false) $aliases = array_reverse($this->get_setting('aliases', array()), true);
		
		static $alias_dir = false;
		if ($alias_dir === false) $alias_dir = $this->get_setting('alias_dir', 'go');
		
		$new_url = $old_url = $matches[3];
		
		foreach ($aliases as $alias) {
			$to = urlencode($alias['to']);
			
			if ((empty($alias['posts']) || in_array($id, $alias['posts'])) && $to) {
				$from = $alias['from'];
				$h_from = esc_html($from);
				$to = get_bloginfo('url') . "/$alias_dir/$to/";
				
				if ($from == $old_url || $h_from == $old_url) {
					$new_url = $to;
					break;
				}
			}
		}
		
		$attrs = "{$matches[1]}href={$matches[2]}{$new_url}{$matches[4]}{$matches[5]}";
		
		if ($old_url != $new_url && $this->get_setting('nofollow_aliased_links', false) && $this->plugin->module_exists('link-nofollow'))
			$this->plugin->call_module_func('link-nofollow', 'nofollow_attributes_string', $attrs, $attrs);
		
		return "<a $attrs>";
	}
	
	function redirect_aliases() {
		$aliases = $this->get_setting('aliases', array());
		$alias_dir = $this->get_setting('alias_dir', 'go');
		
		foreach ($aliases as $alias)
			if ($to = $alias['to'])
				if (suurl::equal(suurl::current(), get_bloginfo('url') . "/$alias_dir/$to/"))
					wp_redirect($alias['from']);
	}
	
	function block_aliases_dir() {
		echo '# ';
		_e("Added by SEO Ultimate's Link Mask Generator module", 'seo-ultimate');
		echo "\n";
		
		$urlinfo = parse_url(get_bloginfo('url'));
		$path = $urlinfo['path'];
		echo "User-agent: *\n";
		
		$alias_dir = $this->get_setting('alias_dir', 'go');
		echo "Disallow: $path/$alias_dir/\n";
		
		echo '# ';
		_e('End Link Mask Generator output', 'seo-ultimate');
		echo "\n\n";
	}
	
	
	function add_help_tabs($screen) {
		
		$screen->add_help_tab(array(
			  'id' => 'su-internal-link-aliases-overview'
			, 'title' => __('Overview', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>What it does:</strong> Link Mask Generator lets you replace ugly affiliate links with clean-looking link aliases that redirect to the real URLs. Link Mask Generator will scan your posts for the links to the actual URLs and replace them with links to the alias URLs. When a visitor clicks on the link to the alias URL, Link Mask Generator will redirect the visitor to the actual URL.</li>
	<li><strong>Why it helps:</strong> This type of functionality is a staple in an affiliate marketer&#8217;s toolkit. Link Mask Generator helps you by doing it in an SEO-friendly way: by funneling your affiliate links through a directory (e.g. <code>/go/</code>) which is blocked with <code>robots.txt</code> rules, effectively sealing off link juice flow to your affiliate links.</li>
	<li><strong>How to use it:</strong> Type in the real URL, type in an alias URL, and click &#8220;Save Changes&#8221; &mdash; that&#8217;s it!</li>
</ul>
", 'seo-ultimate')));

		$screen->add_help_tab(array(
			  'id' => 'su-internal-link-aliases-aliases'
			, 'title' => __('Aliases Tab', 'seo-ultimate')
			, 'content' => __("
<p>To add a link alias, fill in the fields and then click &#8220;Save Changes.&#8221; Once you do so, you can edit your newly masked link or add another one.</p>

<ul>
	<li><strong>Actual URL</strong> &mdash; This box is where you put your affiliate URL (or other URL that you want to mask).</li>
	<li><strong>Alias URL</strong> &mdash; This box is where you specify the new URL that will replace the actual one.</li>
	<li><strong>Only on This Post</strong> &mdash; If you want to mask the actual URL across your entire site, leave this box blank. If you only want to mask the actual URL within an individual post, then type its name into this box and select it from the dropdown.</li>
	<li><strong>Delete</strong> &mdash; To delete a link mask, tick its &#8220;Delete&#8221; checkbox and then click &#8220;Save Changes.&#8221;</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-internal-link-aliases-settings'
			, 'title' => __('Settings Tab', 'seo-ultimate')
			, 'content' => __("
<p>The following options are available on the Settings tab:</p>

<ul>
	<li><strong>Alias Directory</strong> &mdash; If you&#8217;d like, you can change the name of the directory that contains all your alias URLs. (Don&#8217;t worry, you won&#8217;t break any links by changing this.)</li>
	<li><strong>Nofollow masked links</strong> &mdash; Checking this will add the <code>rel=&quot;nofollow&quot;</code> attribute to any masked links on your site. This makes it super-easy to nofollow all your affiliate links automatically.</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-autolinks-faq'
			, 'title' => __('FAQ', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>Can I automatically link a phrase on my site to one of my alias URLs?</strong><br />Yes. Once you&#8217;ve created your link mask, go to Deeplink Juggernaut&#8217;s &#8220;Content Links&#8221; section, type the contents of your link mask&#8217;s &#8220;Alias URL&#8221; field into Deeplink Juggernaut&#8217;s &#8220;Destination&#8221; field, and select your link mask from the dropdown that appears.</li>
	<li><strong>Will Link Mask Generator still add the <code>robots.txt</code> rules if I&#8217;m using the File Editor module to create a custom <code>robots.txt</code>?</strong><br />Yes.</li>
</ul>
", 'seo-ultimate')));
		
	}
	
}
?>