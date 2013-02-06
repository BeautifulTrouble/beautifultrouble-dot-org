<?php
/**
 * SEO Design Solutions Whitepapers Module
 * 
 * @since 0.1
 */

if (class_exists('SU_Module')) {

class SU_SdsBlog extends SU_Module {
	
	function get_module_title() { return __('Whitepapers', 'seo-ultimate'); }
	function get_page_title() { return __('SEO Design Solutions Whitepapers', 'seo-ultimate'); }	
	function has_menu_count() { return true; }
	function get_menu_count() { return $this->get_unread_count(); }
	
	function __construct() {
		add_filter('su_settings_export_array', array(&$this, 'filter_export_array'));
	}
	
	function init() {
		$this->cron('load_blog_rss', 'hourly');
	}
	
	function upgrade() {
		$this->delete_setting('rssitems');
	}
	
	function get_default_settings() {
		//Don't notify about new items when the plugin is just installed
		return array('lastread' => time());
	}
	
	function filter_export_array($settings) {
		unset($settings[$this->get_module_key()]['rss_item_times']);
		return $settings;
	}
	
	function load_blog_rss() {
		$rss = suwp::load_rss('http://feeds.seodesignsolutions.com/SeoDesignSolutionsBlog', SU_USER_AGENT);
		if ($rss && $rss->items) {
			$times = array();
			foreach ($rss->items as $item) $times[] = $this->get_feed_item_date($item);
			$this->update_setting('rss_item_times', $times);
		}
	}
	
	function admin_page_contents() {
		echo "<a href='http://www.seodesignsolutions.com'><img src='{$this->plugin->plugin_dir_url}plugin/images/sds-logo.png' alt='".__('SEO Design Solutions', 'seo-ultimate')."' id='sds-logo' /></a>";
		echo "<p>".__('The search engine optimization articles below are loaded from the website of SEO Design Solutions, the company behind the SEO Ultimate plugin. Click on an article&#8217;s title to read it.', 'seo-ultimate')."</p>\n";
		echo "<div class='rss-widget'>\n";
		
		add_filter('http_headers_useragent', 'su_get_user_agent');
		add_filter('esc_html', array(&$this, 'truncate_at_ellipsis'));
		wp_widget_rss_output( 'http://feeds.seodesignsolutions.com/SeoDesignSolutionsBlog', array('show_summary' => 1, 'show_date' => 1) );
		remove_filter('esc_html', array(&$this, 'truncate_at_ellipsis'));
		remove_filter('http_headers_useragent', 'su_get_user_agent');
		
		echo "</div>\n";
		$this->update_setting('lastread', time());
	}
	
	function truncate_at_ellipsis($content) {
		$end = '[...]';
		if (sustr::has($content, $end)) {
			$content = sustr::upto($content, $end);
			$content = sustr::rtrim_substr($content, $end);
		}
		return sustr::endwith($content, '[&hellip;]');
	}
	
	function get_unread_count() {
		
		if (count($times = $this->get_setting('rss_item_times', array()))) {
			$lastread = $this->get_setting('lastread');
			$new = 0; foreach ($times as $time) if ($time > $lastread) $new++;
			return $new;
		}
		
		return 0;
	}
	
	function get_feed_item_date($item) {
		
		//Is there an Atom date? If so, parse it.
		if (isset($item['issued']) && $atom_date = $item['issued'])
			$date = parse_w3cdtf($atom_date);
		
		//Or is there an RSS2 date? If so, parse it.
		elseif (isset($item['pubdate']) && $rss_2_date = $item['pubdate'])
			$date = strtotime($rss_2_date);
		
		//Or is there an RSS1 date? If so, parse it.
		elseif (isset($item['dc']['date']) && $rss_1_date = $item['dc']['date'])
			$date = parse_w3cdtf($rss_1_date);
			
		else $date = null;
		
		//Return a UNIX timestamp.
		if ($date) return $date; else return 0;
	}
}

}
?>