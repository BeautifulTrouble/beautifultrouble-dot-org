<?php
/**
 * SEO Design Solutions Whitepapers Module
 * 
 * @since 0.1
 */

if (class_exists('SU_Module')) {

class SU_SdsBlog extends SU_Module {
	
	static function get_module_title() { return __('Whitepapers', 'seo-ultimate'); }
	static function get_menu_title() { return __('Whitepapers', 'seo-ultimate'); }
	function get_page_title() { return __('SEO Design Solutions Whitepapers', 'seo-ultimate'); }	
	static function has_menu_count() { return true; }
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
		if ($rss && $rss->get_items()) {
			$times = array();
			foreach ($rss->get_items() as $item) $times[] = $this->get_feed_item_date($item);
			$this->update_setting('rss_item_times', $times);
		}
	}
	
	function admin_page_contents() {
		
		if ($this->should_show_sdf_theme_promo()) {
			echo "\n\n<div class='row'>\n";
			echo "\n\n<div class='col-sm-8 col-md-9'>\n";
		}
		
		echo "<a href='http://www.seodesignsolutions.com'><img src='{$this->plugin->plugin_dir_url}plugin/img/sds-logo.png' alt='".__('SEO Design Solutions', 'seo-ultimate')."' id='sds-logo' /></a>";
		echo "<p>".__('The search engine optimization articles below are loaded from the website of SEO Design Solutions, the company behind the SEO Ultimate plugin. Click on an article&#8217;s title to read it.', 'seo-ultimate')."</p>\n";
		echo "<div class='rss-widget'>\n";
		
		add_filter('http_headers_useragent', 'su_get_user_agent');
		add_filter('esc_html', array(&$this, 'truncate_at_ellipsis'));
		$this->sds_blog_rss_output( 'http://feeds.seodesignsolutions.com/SeoDesignSolutionsBlog', array('show_summary' => 1, 'show_date' => 1, 'items' => 10) );
		remove_filter('esc_html', array(&$this, 'truncate_at_ellipsis'));
		remove_filter('http_headers_useragent', 'su_get_user_agent');
		
		echo "</div>\n";
		$this->update_setting('lastread', time());
		
		if ($this->should_show_sdf_theme_promo()) {
			echo "\n\n</div>\n";
			echo "\n\n<div class='col-sm-4 col-md-3'>\n";
			$this->promo_sdf_banners();
			echo "\n\n</div>\n";
			echo "\n\n</div>\n";
		}
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
		if (isset($item->issued) && $atom_date = $item->issued)
			$date = parse_w3cdtf($atom_date);
		
		//Or is there an RSS2 date? If so, parse it.
		elseif (isset($item->pubdate) && $rss_2_date = $item->pubdate)
			$date = strtotime($rss_2_date);
		
		//Or is there an RSS1 date? If so, parse it.
		elseif (isset($item->dc['date']) && $rss_1_date = $item->dc['date'])
			$date = parse_w3cdtf($rss_1_date);
			
		else $date = null;
		
		//Return a UNIX timestamp.
		if ($date) return $date; else return 0;
	}
	
	/**
	 * Display the RSS entries in a list.
	 *
	 * @since 2.5.0
	 *
	 * @param string|array|object $rss RSS url.
	 * @param array $args Widget arguments.
	 */
	function sds_blog_rss_output( $rss, $args = array() ) {
		if ( is_string( $rss ) ) {
			$rss = fetch_feed($rss);
		} elseif ( is_array($rss) && isset($rss['url']) ) {
			$args = $rss;
			$rss = fetch_feed($rss['url']);
		} elseif ( !is_object($rss) ) {
			return;
		}

		if ( is_wp_error($rss) ) {
			if ( is_admin() || current_user_can('manage_options') )
				echo '<p>' . sprintf( __('<strong>RSS Error</strong>: %s'), $rss->get_error_message() ) . '</p>';
			return;
		}

		$default_args = array( 'show_author' => 0, 'show_date' => 0, 'show_summary' => 0 );
		$args = wp_parse_args( $args, $default_args );
		extract( $args, EXTR_SKIP );

		$items = (int) $items;
		if ( $items < 1 || 20 < $items )
			$items = 10;
		$show_summary  = (int) $show_summary;
		$show_author   = (int) $show_author;
		$show_date     = (int) $show_date;

		if ( !$rss->get_item_quantity() ) {
			echo '<ul><li>' . __( 'An error has occurred, which probably means the feed is down. Try again later.' ) . '</li></ul>';
			$rss->__destruct();
			unset($rss);
			return;
		}

		echo '<ul>';
		foreach ( $rss->get_items(0, $items) as $item ) {
			$link = $item->get_link();
			while ( stristr($link, 'http') != $link )
				$link = substr($link, 1);
			$link = esc_url(strip_tags($link));
			$title = esc_attr(strip_tags($item->get_title()));
			if ( empty($title) )
				$title = __('Untitled');

			$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
			$excerpt = wp_html_excerpt( $desc, 360 );

			// Append ellipsis. Change existing [...] to [&hellip;].
			if ( '[...]' == substr( $excerpt, -5 ) )
				$excerpt = substr( $excerpt, 0, -5 ) . '[&hellip;]';
			elseif ( '[&hellip;]' != substr( $excerpt, -10 ) && $desc != $excerpt )
				$excerpt .= ' [&hellip;]';

			$excerpt = esc_html( $excerpt );

			if ( $show_summary ) {
				$summary = "<div class='rssSummary'>$excerpt</div>";
			} else {
				$summary = '';
			}

			$date = '';
			if ( $show_date ) {
				$date = $item->get_date( 'U' );

				if ( $date ) {
					$date = ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>';
				}
			}

			$author = '';
			if ( $show_author ) {
				$author = $item->get_author();
				if ( is_object($author) ) {
					$author = $author->get_name();
					$author = ' <cite>' . esc_html( strip_tags( $author ) ) . '</cite>';
				}
			}

			if ( $link == '' ) {
				echo "<li>$title{$date}{$summary}{$author}</li>";
			} else {
				echo "<li><a class='rsswidget' href='$link' title='$desc' target='_blank' rel='nofollow'>$title</a>{$date}{$summary}{$author}</li>";
			}
		}
		echo '</ul>';
		$rss->__destruct();
		unset($rss);
	}
}

}
?>