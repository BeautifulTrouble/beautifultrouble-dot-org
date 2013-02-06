<?php
/**
 * 404 Monitor Log Module
 * 
 * @since 2.1
 */

if (class_exists('SU_Module')) {

function su_fofs_log_export_filter($all_settings) {
	unset($all_settings['404s']['log']);
	return $all_settings;
}
add_filter('su_settings_export_array', 'su_fofs_log_export_filter');

class SU_FofsLog extends SU_Module {
	
	function get_parent_module() { return 'fofs'; }
	function get_child_order() { return 10; }
	function is_independent_module() { return false; }
	
	function get_module_title() { return __('404 Monitor Log', 'seo-ultimate'); }
	function get_module_subtitle() { return __('Log', 'seo-ultimate'); }
	
	function has_menu_count() { return true; }
	function get_settings_key() { return '404s'; }
	
	function get_menu_count() {
		$new = 0;
		$the404s = $this->get_setting('log');
		if (count($the404s)) {
			foreach ($the404s as $a404) {
				if ($a404['is_new']) $new++;
			}
		}
		return $new;
	}
	
	function init() {
		add_action('admin_enqueue_scripts', array(&$this, 'queue_admin_scripts'));
		add_action('su_save_hit', array(&$this, 'log_hit'));
	}
	
	//Upgrade to new wp_options-only system if needed
	function upgrade() {
		global $wpdb;
		
		$suppress = $wpdb->suppress_errors(true);
		
		//Get old storage system if it exists
		if ($result = @$wpdb->get_results("SELECT * FROM {$wpdb->prefix}sds_hits WHERE status_code=404 AND redirect_url='' AND url NOT LIKE '%/favicon.ico' ORDER BY id DESC", ARRAY_A)) {
			
			//Get new storage system
			$l = $this->get_setting('log', array());
			
			//Move old to new
			foreach ($result as $row) $this->log_hit($row);
			
			//Out with the old
			mysql_query("DROP TABLE IF EXISTS {$wpdb->prefix}sds_hits");
		}
		
		$wpdb->suppress_errors($suppress);
	}
	
	function queue_admin_scripts() {
		if ($this->is_module_admin_page()) wp_enqueue_script('scriptaculous-effects');
	}
	
	function log_hit($hit) {
		
		if ($hit['status_code'] == 404) {
			
			if ($this->get_setting('restrict_logging', true)) {
				if (!($this->get_setting('log_spiders', true) && suweb::is_search_engine_ua($hit['user_agent'])) &&
					!($this->get_setting('log_errors_with_referers', true) && strlen($hit['referer'])))
						return $hit;
			}
			
			$exceptions = suarr::explode_lines($this->get_setting('exceptions', ''));
			foreach ($exceptions as $exception) {
				if (preg_match(sustr::wildcards_to_regex($exception), $hit['url']))
					return $hit;
			}
			
			$l = $this->get_setting('log', array());
			$max_log_size = absint(sustr::preg_filter('0-9', strval($this->get_setting('max_log_size', 100))));
			while (count($l) > $max_log_size) array_pop($l);
			
			$u = $hit['url'];
			if (!isset($l[$u])) {
				$l[$u] = array();
				$l[$u]['hit_count'] = 0;
				$l[$u]['is_new'] = isset($hit['is_new']) ? $hit['is_new'] : true;
				$l[$u]['referers'] = array();
				$l[$u]['user_agents'] = array();
				$l[$u]['last_hit_time'] = 0;
			}
			
			$l[$u]['hit_count']++;
			if (!$l[$u]['is_new'] && $hit['is_new'])
				$l[$u]['is_new'] = true;
			if ($hit['time'] > $l[$u]['last_hit_time'])
				$l[$u]['last_hit_time'] = $hit['time'];
			if (strlen($hit['referer']) && !in_array($hit['referer'], $l[$u]['referers']))
				$l[$u]['referers'][] = $hit['referer'];
			if (strlen($hit['user_agent']) && !in_array($hit['user_agent'], $l[$u]['user_agents']))
				$l[$u]['user_agents'][] = $hit['user_agent'];
			
			$this->update_setting('log', $l);
		}
		
		return $hit;
	}
	
	function get_admin_table_columns() {
		return array(
			  'actions' => __('Actions', 'seo-ultimate')
			, 'hit-count' => __('Hits', 'seo-ultimate')
			, 'url' => __('URL with 404 Error', 'seo-ultimate')
			, 'last-hit-time' => __('Date of Most Recent Hit', 'seo-ultimate')
			, 'referers' => __('Referers', 'seo-ultimate')
			, 'user-agents' => __('User Agents', 'seo-ultimate')
		);
	}
	
	function sort_log_callback($a, $b) {
		if ($a['is_new'] == $b['is_new'])
			return $b['last_hit_time'] - $a['last_hit_time'];
		
		return $a['is_new'] ? -1 : 1;
	}
	
	function admin_page_contents() {
		
		$the404s = $this->get_setting('log');
		
		if (!$this->get_setting('log_enabled', true))
			$this->queue_message('warning', __('New 404 errors will not be recorded because 404 logging is disabled on the Settings tab.', 'seo-ultimate'));
		
		//Are we deleting a 404 entry?
		if ($this->is_action('delete')) {
		
			if (isset($the404s[$_GET['object']])) {
				unset($the404s[$_GET['object']]);
				$this->queue_message('success', __('The log entry was successfully deleted.', 'seo-ultimate'));
			} else
				$this->queue_message('error', __('This log entry has already been deleted.', 'seo-ultimate'));
			
			$this->update_setting('log', $the404s);
			
		//Are we clearing the whole 404 log?
		} elseif ($this->is_action('clear')) {
			
			$the404s = array();
			$this->update_setting('log', array());
			$this->queue_message('success', __('The log was successfully cleared.', 'seo-ultimate'));
		}
		
		if (!count($the404s))
			$this->queue_message('success', __('No 404 errors in the log.', 'seo-ultimate'));
		
		$this->print_messages();
		
		if (count($the404s)) {
			
			$this->clear_log_button();
			
			echo "<div id='su-404s-log-table'>\n";
			$headers = $this->get_admin_table_columns();
			$this->admin_wftable_start();
			
			uasort($the404s, array(&$this, 'sort_log_callback'));
			
			foreach ($the404s as $url => $data) {
				$new = $data['is_new'] ? ' su-404s-new-hit' : '';
				
				$a_url = su_esc_attr($url);
				$ae_url = su_esc_attr(urlencode($url));
				$md5url = md5($url);
				
				echo "\t<tr id='su-404s-hit-$md5url-data' class='su-404s-hit-data$new'>\n";
				
				$this->table_cells(array(
					  'actions' =>
							  "<span class='su-404s-hit-open'><a href='$a_url' target='_blank'><img src='{$this->module_dir_url}hit-open.png' title='".__('Open URL in new window (will not be logged)', 'seo-ultimate')."' /></a></span>"
							. "<span class='su-404s-hit-cache'><a href='http://www.google.com/search?q=cache%3A{$ae_url}' target='_blank'><img src='{$this->module_dir_url}hit-cache.png' title='".__('Query Google for cached version of URL (opens in new window)', 'seo-ultimate')."' /></a></span>"
							. "<span class='su-404s-hit-delete'><a href='".$this->get_nonce_url('delete', $url)."'><img src='{$this->module_dir_url}hit-delete.png' title='".__('Remove this URL from the log', 'seo-ultimate')."' /></a></span>"
					, 'hit-count' => $data['hit_count']
					, 'url' => "<attr title='$a_url'>" . esc_html(sustr::truncate($url, 100)) . '</attr>'
					, 'last-hit-time' => sprintf(__('%s at %s', 'seo-ultimate')
						, date_i18n(get_option('date_format'), $data['last_hit_time'])
						, date_i18n(get_option('time_format'), $data['last_hit_time'])
						)
					, 'referers' => number_format_i18n(count($data['referers'])) . (count($data['referers']) ? " <a href='#' onclick=\"return su_toggle_blind('su-404s-hit-$md5url-referers')\";'><img src='{$this->module_dir_url}hit-details.png' title='".__('View list of referring URLs', 'seo-ultimate')."' /></a>" : '')
					, 'user-agents' => number_format_i18n(count($data['user_agents'])) . (count($data['user_agents']) ? " <a href='#' onclick=\"return su_toggle_blind('su-404s-hit-$md5url-user-agents')\";'><img src='{$this->module_dir_url}hit-details.png' title='".__('View list of user agents', 'seo-ultimate')."' /></a>" : '')
				));
				
				echo "\t</tr>\n";
				
				echo "\t<tr class='su-404s-hit-referers$new'>\n\t\t<td colspan='".count($headers)."'>";
				
				if (count($data['referers'])) {
					
					echo "<div id='su-404s-hit-$md5url-referers' class='su-404s-hit-referers-list' style='display: none;'>\n";
					echo "\t\t\t<div><strong>".__('Referring URLs', 'seo-ultimate')."</strong> &mdash; ";
					echo "<a href='#' onclick=\"Effect.BlindUp('su-404s-hit-$md5url-referers'); return false;\">".__('Hide list', 'seo-ultimate')."</a>";
					echo "</div>\n";
					echo "\t\t\t<ul>\n";
					
					foreach ($data['referers'] as $referer) {
						$referer = su_esc_attr($referer); //Don't let attacks pass through the referer URLs!
						echo "\t\t\t\t<li><a href='$referer' target='_blank'>$referer</a></li>\n";
					}
					
					echo "\t\t\t</ul>\n";
					
					echo "\t\t</div>";
				}
				
				echo "</td>\n\t</tr>\n";
				
				echo "\t<tr class='su-404s-hit-user-agents$new'>\n\t\t<td colspan='".count($headers)."'>";
				
				if (count($data['user_agents'])) {
					echo "<div id='su-404s-hit-$md5url-user-agents' class='su-404s-hit-user-agents-list'  style='display: none;'>\n";
					echo "\t\t\t<div><strong>".__('User Agents', 'seo-ultimate')."</strong> &mdash; ";
					echo "<a href='#' onclick=\"Effect.BlindUp('su-404s-hit-$md5url-user-agents'); return false;\">".__('Hide list', 'seo-ultimate')."</a>";
					echo "</div>\n";
					echo "\t\t\t<ul>\n";
					
					foreach ($data['user_agents'] as $useragent) {
						$useragent = su_esc_html($useragent); //Don't let attacks pass through the user agent strings!
						echo "\t\t\t\t<li>$useragent</li>\n";
					}
					
					echo "\t\t\t</ul>\n";
					
					echo "</td>\n\t</tr>\n";
				}
				
				echo "\t\t</div>";
				
				$the404s[$url]['is_new'] = false;
			}
			
			$this->update_setting('log', $the404s);
			
			$this->admin_wftable_end();
			echo "</div>\n";
			
			$this->clear_log_button();
		}
	}
	
	function clear_log_button() {
		//Create the "Clear Log" button
		$clearurl = $this->get_nonce_url('clear');
		$confirm = __('Are you sure you want to delete all 404 log entries?', 'seo-ultimate');
		echo "<div class='su-404s-clear-log'><a href=\"$clearurl\" class=\"button-secondary\" onclick=\"javascript:return confirm('$confirm')\">";
		_e('Clear Log', 'seo-ultimate');
		echo "</a></div>";
	}
}

}
?>