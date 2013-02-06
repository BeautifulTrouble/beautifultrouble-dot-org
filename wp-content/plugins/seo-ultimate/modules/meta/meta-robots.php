<?php
/**
 * Meta Robot Tags Editor Module
 * 
 * @since 4.0
 */

if (class_exists('SU_Module')) {

class SU_MetaRobots extends SU_Module {
	
	function get_module_title() { return __('Meta Robot Tags Editor', 'seo-ultimate'); }
	function get_menu_title()   { return __('Meta Robot Tags', 'seo-ultimate'); }
	function get_settings_key() { return 'meta'; }
	
	function init() {
		add_filter('su_meta_robots', array(&$this, 'meta_robots'));
	}
	
	function get_admin_page_tabs() {
		return array(
			array('title' => __('Sitewide Values', 'seo-ultimate'), 'id' => 'su-sitewide-values', 'callback' => 'global_tab')
		);
	}
	
	function global_tab() {
		$this->admin_form_table_start();
		$this->checkboxes(array(
				  'noodp' => __('Don&#8217t use this site&#8217s Open Directory description in search results.', 'seo-ultimate')
				, 'noydir' => __('Don&#8217t use this site&#8217s Yahoo! Directory description in search results.', 'seo-ultimate')
				, 'noarchive' => __('Don&#8217t cache or archive this site.', 'seo-ultimate')
			), __('Spider Instructions', 'seo-ultimate'));
		$this->admin_form_table_end();
	}
	
	//Add the appropriate commands to the meta robots array
	function meta_robots($commands) {
		
		$tags = array('noodp', 'noydir', 'noarchive');
		
		foreach ($tags as $tag) {
			if ($this->get_setting($tag)) $commands[] = $tag;
		}
		
		return $commands;
	}
	
	function add_help_tabs($screen) {
		
		$screen->add_help_tab(array(
			  'id' => 'su-meta-robots-overview'
			, 'title' => __('Overview', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>What it does:</strong> Meta Robot Tags Editor lets you convey instructions to search engine spiders, as well as prohibit the spiders from indexing certain webpages on your blog using the <code>&lt;meta name=&quot;robots&quot; content=&quot;noindex&quot; /&gt;</code> tag.</li>
	<li><strong>Why it helps:</strong> The &#8220;Global&#8221; tab lets you stop DMOZ or Yahoo! Directory from overriding your custom meta descriptions, as well as prevent spiders from caching your site if you so desire. The &#8220;Default Values&#8221; tab lets you deindex entire sections of your site that contain content unimportant to visitors (e.g. the administration section), or sections of your site that mostly contain duplicate content (e.g. date archives). The editor tabs can do something similar, but for individual content items. By removing webpages from search results that visitors find unhelpful, you can help increase the focus on your more useful content.</li>
	<li><strong>How to use it:</strong> Adjust the settings as desired, and then click Save Changes. You can refer to the &#8220;Settings Help&#8221; tab for information on the settings available. You can also use the editor tabs to deindex individual content items on your site as well as enable the &#8220;nofollow&#8221; meta parameter that will nullify all outgoing links on a specific webpage.</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
				  'id' => 'su-meta-robots-global'
				, 'title' => __('Sitewide Settings Tab', 'seo-ultimate')
				, 'content' => __("
<ul>
	<li><strong>Don&#8217;t use this site&#8217;s Open Directory / Yahoo! Directory description in search results</strong> &mdash; If your site is listed in the <a href='http://www.dmoz.org/' target='_blank'>Open Directory (DMOZ)</a> or the <a href='http://dir.yahoo.com/' target='_blank'>Yahoo! Directory</a>, some search engines may use your directory listing as the meta description. These boxes tell search engines not to do that and will give you full control over your meta descriptions. These settings have no effect if your site isn&#8217;t listed in the Open Directory or Yahoo! Directory respectively.</li>
	<li><strong>Don&#8217;t cache or archive this site</strong> &mdash; When you check this box, Meta Editor will ask search engines (Google, Yahoo!, Bing, etc.) and archivers (Archive.org, etc.) to <em>not</em> make cached or archived &#8220;copies&#8221; of your site.</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
				  'id' => 'su-meta-robots-defaults'
				, 'title' => __('Default Values Tab', 'seo-ultimate')
				, 'content' => __("
<p><strong>Prevent indexing of&hellip;</strong></p>
<ul>
	<li><strong>Administration back-end pages</strong> &mdash; Tells spiders not to index the administration area (the part you&#8217;re in now), in the unlikely event a spider somehow gains access to the administration. Recommended.</li>
	<li><strong>Author archives</strong> &mdash; Tells spiders not to index author archives. Useful if your blog only has one author.</li>
	<li><strong>Blog search pages</strong> &mdash; Tells spiders not to index the result pages of WordPress's blog search function. Recommended.</li>
	<li><strong>Category archives</strong> &mdash; Tells spiders not to index category archives. Recommended only if you don't use categories.</li>
	<li><strong>Comment feeds</strong> &mdash; Tells spiders not to index the RSS feeds that exist for every post's comments. (These comment feeds are totally separate from your normal blog feeds.)</li>
	<li><strong>Comment subpages</strong> &mdash; Tells spiders not to index posts' comment subpages.</li>
	<li><strong>Date-based archives</strong> &mdash; Tells spiders not to index day/month/year archives. Recommended, since these pages have little keyword value.</li>
	<li><strong>Subpages of the homepage</strong> &mdash; Tells spiders not to index the homepage's subpages (page 2, page 3, etc). Recommended.</li>
	<li><strong>Tag archives</strong> &mdash; Tells spiders not to index tag archives. Recommended only if you don't use tags.</li>
	<li><strong>User login/registration pages</strong> &mdash; Tells spiders not to index WordPress's user login and registration pages. Recommended.</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
				  'id' => 'su-meta-robots-metaedit'
				, 'title' => __('Bulk Editor Tabs', 'seo-ultimate')
				, 'content' => __("
<ul>
	<li><strong>Noindex</strong> &mdash; Checking this for an item will ask search engines to remove that item&#8217;s webpage from their indices. Use this to remove pages that you don&#8217;t want showing up in search results (such as a Privacy Policy page, for example).</li>
	<li><strong>Nofollow</strong> &mdash; Checking this for an item will tell search engines to ignore the links to other webpages that are on that item&#8217;s webpage. Note: this is page-level &#8220;meta nofollow,&#8221; not to be confused with link-level &#8220;rel nofollow.&#8221;</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-meta-robots-troubleshooting'
			, 'title' => __('Troubleshooting', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li>
		<p><strong>What do I do if my site has multiple meta tags?</strong><br />First, try removing your theme&#8217;s built-in meta tags if it has them. Go to <a href='theme-editor.php' target='_blank'>Appearance &rArr; Editor</a> and edit <code>header.php</code>. Delete or comment-out any <code>&lt;meta&gt;</code> tags.</p>
		<p>If the problem persists, try disabling other SEO plugins that may be generating meta tags.</p>
		<p>Troubleshooting tip: Go to <a href='options-general.php?page=seo-ultimate'>Settings &rArr; SEO Ultimate</a> and enable the &#8220;Insert comments around HTML code insertions&#8221; option. This will mark SEO Ultimate&#8217;s meta tags with comments, allowing you to see which meta tags are generated by SEO Ultimate and which aren&#8217;t.</p>
	</li>
</ul>
", 'seo-ultimate')));
		
	}
}

}
?>