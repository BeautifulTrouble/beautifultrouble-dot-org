<?php
/**
 * Canonicalizer Module
 * 
 * @since 0.3
 */

if (class_exists('SU_Module')) {

class SU_Canonical extends SU_Module {

	static function get_module_title() { return __('Canonicalizer', 'seo-ultimate'); }
	
	static function get_parent_module() { return 'misc'; }
	function get_settings_key() { return 'canonical'; }
	
	function init() {
		add_filter('su_get_setting-canonical-canonical_url_scheme', array(&$this, 'filter_canonical_url_scheme'));
		
		//If the canonical tags are enabled, then...
		if ($this->get_setting('link_rel_canonical')) {
			
			//...remove WordPress's default canonical tags (since they only handle posts/pages/attachments)
			remove_action('wp_head', 'rel_canonical');
			
			//...and add our custom canonical tags.
			add_action('su_head', array(&$this, 'link_rel_canonical_tag'));
		}
		
		if ($this->get_setting('http_link_rel_canonical'))
			add_action('template_redirect', array(&$this, 'http_link_rel_canonical'), 11, 0);
		
		//Should we remove nonexistent pagination?
		if ($this->get_setting('remove_nonexistent_pagination'))
			add_action('template_redirect', array(&$this, 'remove_nonexistent_pagination'), 11);
	}
	
	function admin_page_contents() {
		$this->child_admin_form_start();
		$this->checkboxes(array(
				  'link_rel_canonical' => __('Generate <code>&lt;link rel=&quot;canonical&quot; /&gt;</code> meta tags', 'seo-ultimate')
				, 'http_link_rel_canonical' => __('Send <code>rel=&quot;canonical&quot;</code> HTTP headers', 'seo-ultimate')
			), __('Canonical URL Generation', 'seo-ultimate'));
		$this->radiobuttons('canonical_url_scheme', array(
			  '' => __('Use <code>http://</code> or <code>https://</code> depending on how the visitor accessed the page', 'seo-ultimate')
			, 'http' => __('Make all canonical URLs begin with <code>http://</code>', 'seo-ultimate')
			, 'https' => __('Make all canonical URLs begin with <code>https://</code>', 'seo-ultimate')
		), __('Canonical URL Scheme', 'seo-ultimate'));
		$this->checkboxes(array(
				  'remove_nonexistent_pagination' => __('Redirect requests for nonexistent pagination', 'seo-ultimate')
			), __('Automated 301 Redirects', 'seo-ultimate'));
		$this->child_admin_form_end();
	}
	
	function link_rel_canonical_tag() {
		//Display the canonical tag if a canonical URL is available
		if ($url = $this->get_canonical_url()) {
			$url = su_esc_attr($url);
			echo "\t<link rel=\"canonical\" href=\"$url\" />\n";
		}
	}
	
	function http_link_rel_canonical() {
		if (headers_sent())
			return;
		
		if ($url = $this->get_canonical_url()) {
			$url = su_esc_attr($url);
			header("Link: <$url>; rel=\"canonical\"", false);
		}
	}
	
	/**
	 * Returns the canonical URL to put in the link-rel-canonical tag.
	 * 
	 * This function is modified from the GPL-licensed {@link http://wordpress.org/extend/plugins/canonical/ Canonical URLs} plugin,
	 * which in turn was heavily based on the {@link http://svn.fucoder.com/fucoder/permalink-redirect/ Permalink Redirect} plugin.
	 */
	function get_canonical_url() {
		global $wp_query, $wp_rewrite;
		
		//404s and search results don't have canonical URLs
		if ($wp_query->is_404 || $wp_query->is_search) return false;
		
		//Are there posts in the current Loop?
		$haspost = count($wp_query->posts) > 0;
		
		//Handling special case with '?m=yyyymmddHHMMSS'.
		if (get_query_var('m')) {
			$m = preg_replace('/[^0-9]/', '', get_query_var('m'));
			switch (strlen($m)) {
				case 4: // Yearly
					$link = get_year_link($m);
					break;
				case 6: // Monthly
					$link = get_month_link(substr($m, 0, 4), substr($m, 4, 2));
					break;
				case 8: // Daily
					$link = get_day_link(substr($m, 0, 4), substr($m, 4, 2),
										 substr($m, 6, 2));
					break;
				default:
					//Since there is no code for producing canonical archive links for is_time, we will give up and not try to produce a link.
					return false;
			}
		
		//Posts and pages
		} elseif (($wp_query->is_single || $wp_query->is_page) && $haspost) {
			$post = $wp_query->posts[0];
			$link = get_permalink($post->ID);
			if (is_front_page()) $link = trailingslashit($link);
			
		//Author archives
		} elseif ($wp_query->is_author && $haspost) {
			$author = get_userdata(get_query_var('author'));
			if ($author === false) return false;
			$link = get_author_posts_url($author->ID, $author->user_nicename);
			
		//Category archives
		} elseif ($wp_query->is_category && $haspost) {
			$link = get_category_link(get_query_var('cat'));
			
		//Tag archives
		} else if ($wp_query->is_tag  && $haspost) {
			$tag = get_term_by('slug',get_query_var('tag'),'post_tag');
			if (!empty($tag->term_id)) $link = get_tag_link($tag->term_id);
		
		//Day archives
		} elseif ($wp_query->is_day && $haspost) {
			$link = get_day_link(get_query_var('year'),
								 get_query_var('monthnum'),
								 get_query_var('day'));
		
		//Month archives
		} elseif ($wp_query->is_month && $haspost) {
			$link = get_month_link(get_query_var('year'),
								   get_query_var('monthnum'));
		
		//Year archives
		} elseif ($wp_query->is_year && $haspost) {
			$link = get_year_link(get_query_var('year'));
		
		//Homepage
		} elseif ($wp_query->is_home) {
			if ((get_option('show_on_front') == 'page') && ($pageid = get_option('page_for_posts')))
				$link = trailingslashit(get_permalink($pageid));
			else
				$link = trailingslashit(get_option('home'));
			
		//Other
		} else
			return false;
		
		//Handle pagination
		$page = get_query_var('paged');
		if ($page && $page > 1) {
			if ($wp_rewrite->using_permalinks()) {
				$link = trailingslashit($link) ."page/$page";
				$link = user_trailingslashit($link, 'paged');
			} else {
				$link = esc_url( add_query_arg( 'paged', $page, $link ) );
			}
		}
		
		//Handle protocol change
		if ($scheme = $this->get_setting('canonical_url_scheme', 'http'))
			$link = preg_replace('@^https?://@', "$scheme://", $link);
		
		//Return the canonical URL
		return $link;
	}
	
	function remove_nonexistent_pagination() {
		
		if (!is_admin()) {
			
			global $wp_rewrite, $wp_query;
			
			$url = suurl::current();
			
			if (is_singular()) {
				$num = absint(get_query_var('page'));
				$post = $wp_query->get_queried_object();
				$max = count(explode('<!--nextpage-->', $post->post_content));
				
				if ($max > 0 && ($num == 1 || ($num > 1 && $num > $max))) {
					
					if ($wp_rewrite->using_permalinks())
						wp_redirect( preg_replace( '|/[0-9]{1,9}/?$|', '/', $url ), 301 );
					else
						wp_redirect( esc_url( remove_query_arg( 'page', $url ) ), 301 );
				}
				
			} elseif (is_404() && $num = absint(get_query_var('paged'))) {
				
				if ($wp_rewrite->using_permalinks())
					wp_redirect( preg_replace('|/page/[0-9]{1,9}/?$|', '/', $url), 301 );
				else
					wp_redirect( esc_url( remove_query_arg( 'paged', $url ) ), 301 );
			}
		}
	}
	
	function filter_canonical_url_scheme($scheme) {
		return sustr::preg_filter('a-z', $scheme);
	}
	
	function add_help_tabs($screen) {
		
		$overview = __("
<ul>
	<li><strong>What it does:</strong> Canonicalizer will point Google to the correct URL for your homepage and each of your posts, Pages, categories, tags, date archives, and author archives.</li>
	<li><strong>Why it helps:</strong> If Google comes across an alternate URL by which one of those items can be accessed, it will be able to find the correct URL and won&#8217;t penalize you for having two identical pages on your site.</li>
	<li><strong>How to use it:</strong> Just check the three checkboxes. If your site is accessible using both <code>http://</code> and <code>https://</code>, be sure to set the preferred one under &#8220;Canonical URL Scheme.&#8221;</li>
</ul>
", 'seo-ultimate');
		
		if ($this->has_enabled_parent()) {
			$screen->add_help_tab(array(
			  'id' => 'su-canonical-help'
			, 'title' => __('Canonicalizer', 'seo-ultimate')
			, 'content' => 
				'<h3>' . __('Overview', 'seo-ultimate') . '</h3>' . $overview
			));
		} else {
			
			$screen->add_help_tab(array(
				  'id' => 'su-canonical-overview'
				, 'title' => __('Overview', 'seo-ultimate')
				, 'content' => $overview));
			
		}
	}
}

}
?>