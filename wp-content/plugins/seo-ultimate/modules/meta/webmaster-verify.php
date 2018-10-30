<?php
/**
 * Webmaster Verification Assistant Module
 * 
 * @since 4.0
 */

if (class_exists('SU_Module')) {

class SU_WebmasterVerify extends SU_Module {
	
	static function get_module_title() { return __('Webmaster Verification Assistant', 'seo-ultimate'); }
	static function get_menu_title() { return __('W.M. Verification', 'seo-ultimate'); }
	
	static function get_parent_module() { return 'misc'; }
	function get_settings_key() { return 'meta'; }
	
	function init() {
		add_action('su_head', array(&$this, 'head_tag_output'));
	}
	
	function get_supported_search_engines() {
		return array(
			  'google' => array(
				  'title' => __('Google Webmaster Tools', 'seo-ultimate')
				, 'meta_name' => 'google-site-verification'
			), 'microsoft' => array(
				  'title' => __('Bing Webmaster Center', 'seo-ultimate')
				, 'meta_name' => 'msvalidate.01'
			)
		);
	}
	
	function head_tag_output() {
		$verify = $this->get_supported_search_engines();
		foreach ($verify as $site => $site_data) {
			$name = $site_data['meta_name'];
			//Do we have verification tags? If so, output them.
			if ($value = $this->get_setting($site.'_verify')) {
				if (current_user_can('unfiltered_html') && sustr::startswith(trim($value), '<meta ') && sustr::endswith(trim($value), '/>'))
					echo "\t".trim($value)."\n";
				else {
					$value = su_esc_attr($value);
					echo "\t<meta name=\"$name\" content=\"$value\" />\n";
				}
			}
		}
	}
	
	function admin_page_contents() {
		
		$this->child_admin_form_start(false);
		
		$this->admin_wftable_start(array(
			  'portal' => __('Webmaster Portal', 'seo-ultimate')
			, 'meta_tag_before' => __('Meta Tag', 'seo-ultimate')
			, 'meta_tag' => ' '
			, 'meta_tag_after' => ' '
		));
		
		$sites = $this->get_supported_search_engines();
		
		foreach ($sites as $site => $site_data) {
			echo "<tr>\n";
			echo "<td class='su-webmaster-verify-portal'>" . esc_html($site_data['title']) . "</td>\n";
			echo "<td class='su-webmaster-verify-meta_tag_before'>&lt;meta name=&quot;"
				. esc_html($site_data['meta_name']) . "&quot; content=&quot;</td>\n";
			echo "<td class='su-webmaster-verify-meta_tag'>";
			$this->textbox("{$site}_verify", '', false, false, array('in_table' => false));
			echo "</td>\n";
			echo "<td class='su-webmaster-verify-meta_tag_after'>&quot; /&gt;</td>\n";
			echo "</tr>\n";
		}
		
		$this->admin_wftable_end();
		
		$this->child_admin_form_end(false);
	}

	function add_help_tabs($screen) {
		
		$overview = __("
<ul>
	<li><strong>What it does:</strong> Webmaster Verification Assistant lets you enter in verification codes for the webmaster portals of leading search engines.</li>
	<li><strong>Why it helps:</strong> Webmaster Verification Assistant assists you in obtaining access to webmaster portals, which can provide you with valuable SEO tools.</li>
	<li><strong>How to use it:</strong> Use a search engine to locate the webmaster portal you&#8217;re interested in, sign up at the portal, and then obtain a verification code. Once you have the code, you can paste it in here, click Save Changes, then return to the portal to verify that you own the site. Once that&#8217;s done, you'll have access to the portal&#8217;s SEO tools.</li>
</ul>
", 'seo-ultimate');
		
		if ($this->has_enabled_parent()) {
			$screen->add_help_tab(array(
			  'id' => 'su-webmaster-verify-help'
			, 'title' => __('Webmaster Verification Assistant', 'seo-ultimate')
			, 'content' => 
				'<h3>' . __('Overview', 'seo-ultimate') . '</h3>' . $overview
			));
		} else {
			
			$screen->add_help_tab(array(
				  'id' => 'su-webmaster-verify-overview'
				, 'title' => __('Overview', 'seo-ultimate')
				, 'content' => $overview));
			
		}
	}

}

}
?>