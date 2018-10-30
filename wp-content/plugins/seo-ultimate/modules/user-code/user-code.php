<?php
/**
 * Code Inserter Module
 * 
 * @since 2.7
 */

if (class_exists('SU_Module')) {

function su_user_code_import_filter($all_settings) {
	
	if (!SU_UserCode::user_authorized())
		unset($all_settings['user-code']);
	
	return $all_settings;
}
add_filter('su_settings_import_array', 'su_user_code_import_filter');

class SU_UserCode extends SU_Module {
	
	static function get_module_title() { return __('Code Inserter', 'seo-ultimate'); }
	static function get_menu_title() { return __('Code Inserter', 'seo-ultimate'); }
	
	function get_default_settings() {
		return array(
			  'global_wp_head' => $this->flush_setting('custom_html', '', 'meta')
		);
	}
	
	function get_default_status() {
		if (is_multisite())
			return SU_MODULE_DISABLED;
		
		return SU_MODULE_ENABLED;
	}
	
	function user_authorized() {
		return current_user_can('unfiltered_html');
	}
	
	function init() {
		$hooks = array('su_head', 'the_content', 'wp_footer');
		foreach ($hooks as $hook) add_filter($hook, array(&$this, "{$hook}_code"), 'su_head' == $hook ? 11 : 10);
	}
	
	
	
	function admin_page_contents() {
		
		if ($this->should_show_sdf_theme_promo()) {
			echo "\n\n<div class='row'>\n";
			echo "\n\n<div class='col-sm-8 col-md-9'>\n";
		}
		
		$this->admin_form_start(false, false);
		$textareas = array(
			  'wp_head' => __('&lt;head&gt; Tag', 'seo-ultimate')
			, 'the_content_before' => __('Before Item Content', 'seo-ultimate')
			, 'the_content_after' => __('After Item Content', 'seo-ultimate')
			, 'wp_footer' => __('Footer', 'seo-ultimate')
		);
		$textareas = suarr::aprintf("global_%s", false, $textareas);
		$this->textareas($textareas, 5, 30, array('disabled' => !$this->user_authorized()));
		$this->admin_form_end(null, false);
			
		if ($this->should_show_sdf_theme_promo()) {
			echo "\n\n</div>\n";
			echo "\n\n<div class='col-sm-4 col-md-3'>\n";
			$this->promo_sdf_banners();
			echo "\n\n</div>\n";
			echo "\n\n</div>\n";
		}
	}
	
	function get_usercode($field) {
		
		$code = $this->get_setting("global_$field", '');
		if (is_front_page()) $code .= $this->get_setting("frontpage_$field", '');
		
		return $this->plugin->mark_code($code, __('Code Inserter module', 'seo-ultimate'), $field == 'wp_head');
	}
	
	function su_head_code() {
		echo $this->get_usercode('wp_head');
	}
	
	function wp_footer_code() {
		echo $this->get_usercode('wp_footer');
	}
	
	function the_content_code($content) {
		return $this->get_usercode('the_content_before') . $content . $this->get_usercode('the_content_after');
	}
	
	function add_help_tabs($screen) {
		
		$screen->add_help_tab(array(
			  'id' => 'su-user-code-overview'
			, 'title' => __('Overview', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>What it does:</strong> Code Inserter can add custom HTML code to various parts of your site.</li>
	<li>
		<p><strong>Why it helps:</strong> Code Inserter is useful for inserting third-party code that can improve the SEO or user experience of your site. For example, you can use Code Inserter to add Google Analytics code to your footer, Feedburner FeedFlares or social media widgets after your posts, or Google AdSense section targeting code before/after your content.</p>
		<p>Using Code Inserter is easier than editing your theme manually because your custom code is stored in one convenient location and will be added to your site even if you change your site&#8217;s theme.</p>
	</li>
	<li><strong>How to use it:</strong> Just paste the desired HTML code into the appropriate fields and then click Save Changes.</li>
</ul>
", 'seo-ultimate')));

		$screen->add_help_tab(array(
			  'id' => 'su-user-code-troubleshooting'
			, 'title' => __('Troubleshooting', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><p><strong>Why do I get a message saying my account doesn&#8217;t have permission to insert arbitrary HTML code?</strong><br />WordPress has a security feature that only allows administrators to insert arbitrary, unfiltered HTML into the site. On single-site setups, site administrators have this capability. On multisite setups, only network admins have this capability. This is done for security reasons, since site users with the ability to insert arbitrary HTML could theoretically insert malicious code that could be used to hijack control of the site.</p><p>If you are the administrator of a site running on a network, then you will not be able to use Code Inserter under default security settings. However, the network administrator <em>will</em> be able to edit the fields on this page. If you have code that you really want inserted into a certain part of your site, ask your network administrator to do it for you.</p><p>If you are the network administrator of a multisite WordPress setup, and you completely trust all of the administrators and editors of the various sites on your network, you can install the <a href='http://wordpress.org/extend/plugins/unfiltered-mu/' target='_blank'>Unfiltered MU</a> plugin to enable the Code Inserter for all of those users.</p></li>
	<li><strong>Why doesn't my code appear on my site?</strong><br />It&#8217;s possible that your theme doesn't have the proper &#8220;hooks,&#8221; which are pieces of code that let WordPress plugins insert custom HTML into your theme. <a href='http://johnlamansky.com/wordpress/theme-plugin-hooks/' target='_blank'>Click here</a> for information on how to check your theme and add the hooks if needed.</li>
</ul>
", 'seo-ultimate')));
		
	}
}

}

?>