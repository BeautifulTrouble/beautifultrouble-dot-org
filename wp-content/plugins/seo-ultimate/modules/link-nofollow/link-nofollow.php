<?php
/**
 * Nofollow Manager Module
 * 
 * @since 5.6
 */

if (class_exists('SU_Module')) {

class SU_LinkNofollow extends SU_Module {
	
	function get_module_title() { return __('Nofollow Manager', 'seo-ultimate'); }
	function get_default_status() { return SU_MODULE_DISABLED; }
	
	function get_parent_module() { return 'misc'; }
	function get_settings_key() { return 'link-nofollow'; }
	
	function init() {
		$filterdata = array(
			  'nofollow_links' => array(
				  'nofollow_adjacent_post' => array('previous_post_link', 'next_post_link')
				, 'nofollow_category_loop' => 'the_category'
				, 'nofollow_category_list' => 'wp_list_categories'
				, 'nofollow_comment_feed' => 'post_comments_feed_link_html'
				, 'nofollow_date_archive' => 'get_archives_link'
				, 'nofollow_post_more' => 'the_content_more_link'
				, 'nofollow_register' => 'register'
				, 'nofollow_login' => 'loginout'
				, 'nofollow_tag_loop' => 'term_links-post_tag'
				, 'nofollow_tag_list' => 'wp_tag_cloud'
			)
			, 'nofollow_attributes_string' => array(
				  'nofollow_comment_popup' => 'comments_popup_link_attributes'
				, 'nofollow_paged' => array('previous_posts_link_attributes', 'next_posts_link_attributes')
				, 'nofollow_paged_home' => array('previous_posts_link_attributes', 'next_posts_link_attributes')
			)
		);
		
		if (!is_home()) unset($filterdata['nofollow_attributes_string']['nofollow_paged_home']);
		
		foreach ($filterdata as $callback => $filters) {
			foreach ($filters as $setting => $hooks) {
				if ($this->get_setting($setting)) {
					foreach ((array)$hooks as $hook) {
						add_filter($hook, array(&$this, $callback));
					}
				}
			}
		}
		
		add_filter('wp_list_pages', array(&$this, 'nofollow_page_links'));
	}
	
	function admin_page_contents() {
		$this->child_admin_form_start();
		$this->checkboxes(array(
				  'nofollow_adjacent_post' => __('Adjacent post links (next post / previous post)', 'seo-ultimate')
				, 'nofollow_category_loop' => __('Category links (after posts)', 'seo-ultimate')
				, 'nofollow_category_list' => __('Category links (in lists)', 'seo-ultimate')
				, 'nofollow_comment_popup' => __('Comment anchor links', 'seo-ultimate')
				, 'nofollow_comment_feed' => __('Comment feed links', 'seo-ultimate')
				, 'nofollow_date_archive' => __('Date-based archive links', 'seo-ultimate')
				, 'nofollow_paged' => __('Pagination navigation links (all)', 'seo-ultimate')
				, 'nofollow_paged_home' => __('Pagination navigation links (on blog home only)', 'seo-ultimate')
				, 'nofollow_post_more' => __('&#8220;Read more&#8221; links', 'seo-ultimate')
				, 'nofollow_register' => __('Registration link', 'seo-ultimate')
				, 'nofollow_login' => __('Login link', 'seo-ultimate')
				, 'nofollow_tag_loop' => __('Tag links (after posts)', 'seo-ultimate')
				, 'nofollow_tag_list' => __('Tag links (in lists and clouds)', 'seo-ultimate')
			), __('Add the nofollow attribute to...', 'seo-ultimate'));
		
		$this->child_admin_form_end();
	}
	
	function postmeta_fields($fields, $screen) {
		
		if (strcmp($screen, 'page') == 0)
			$fields['links'][30]['nofollow'] = $this->get_postmeta_checkbox('nofollow', __('When displaying page lists, nofollow links to this page', 'seo-ultimate'), __('Nofollow:', 'seo-ultimate'));
		
		return $fields;
	}
	
	function nofollow_links($html) {
		return preg_replace_callback('|<a (.+?)>|i', array(&$this, 'nofollow_links_callback'), $html);
	}
	
	function nofollow_links_callback($matches) {
		$html = $this->nofollow_attributes_string($matches[1]);
		return "<a $html>";
	}
	
	function nofollow_attributes_string($html) {
		if (preg_match('|rel=[\'"]?[^>]+nofollow[^>]+[\'"]?|i', $html))
			return $html;
		elseif (preg_match('|rel=[\'"][^>]+[\'"]|i', $html))
			return preg_replace('|rel=([\'"])|i', 'rel=\\1nofollow ', $html);
		else {
			if (strlen($html)) $html = rtrim($html, ' ').' ';
			return $html.'rel="nofollow"';
		}
	}
	
	function nofollow_page_links($html) {
		return preg_replace_callback('|<a (.+?)>|i', array(&$this, 'nofollow_page_links_callback'), $html);
	}
	
	function nofollow_page_links_callback($matches) {
		$html = $matches[1];
		
		if (preg_match('|href=[\'"]([^\'"]+)[\'"]|i', $html, $pagematches)) {
			$pageurl = $pagematches[1];
			$pagepath = str_replace(array(untrailingslashit(get_bloginfo('url')), '/index.php/'), '', $pageurl);
			
			if (preg_match('|/?\\?page_id=([0-9]+)|i', $pagepath, $qsmatches))
				//We're using query string URLs
				$page = get_page(intval($qsmatches[1]));
			else
				//We're using pretty or pathinfo permalinks
				$page = get_page_by_path($pagepath);
			
			if ($this->get_postmeta('nofollow', $page->ID))
				$html = $this->nofollow_attributes_string($html);
		}
		
		return "<a $html>";
	}

	function add_help_tabs($screen) {
		
		$screen->add_help_tab(array(
			  'id' => 'su-link-nofollow-overview'
			, 'title' => $this->has_enabled_parent() ? __('Nofollow Manager', 'seo-ultimate') : __('Overview', 'seo-ultimate')
			, 'content' => __("
<p>Nofollow Manager adds the <code>rel=&quot;nofollow&quot;</code> attribute to types of links that you specify. The <code>rel=&quot;nofollow&quot;</code> attribute prevents a link from passing PageRank.</p>
<p>If you&#8217;re migrating to SEO Ultimate from another plugin, Nofollow Manager can help you maintain your existing settings (as part of an &#8220;if it ain&#8217;t broke don&#8217;t fix it&#8221; strategy). In other cases, however, we recommend not using the Nofollow Manager because in 2008 Google disabled the ability to use the <code>rel=&quot;nofollow&quot;</code> attribute for PageRank sculpting.</p>
", 'seo-ultimate')));
	}

}

}
?>