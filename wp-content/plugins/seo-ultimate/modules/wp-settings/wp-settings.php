<?php
/**
 * Settings Monitor Module
 * 
 * @since 6.9
 */

if (class_exists('SU_Module')) {

class SU_WpSettings extends SU_Module {
	
	var $results = array();
	
	function get_module_title() { return __('Settings Monitor', 'seo-ultimate'); }
	
	function has_menu_count() { return true; }
	function get_menu_count() {
		$count = 0;
		foreach ($this->results as $data) {
			if ($data[0] == SU_RESULT_ERROR) $count++;
		}
		return $count;
	}
	
	function init() {
		
		if (is_admin()) {
			if (get_option('blog_public'))
				$this->results[] = array(SU_RESULT_OK, __('Blog is visible to search engines', 'seo-ultimate'),
					__('WordPress will allow search engines to visit your site.', 'seo-ultimate'));
			else
				$this->results[] = array(SU_RESULT_ERROR, __('Blog is hidden from search engines', 'seo-ultimate'),
					__('WordPress is configured to discourage search engines. This will nullify your site&#8217;s SEO and should be resolved immediately.', 'seo-ultimate'), 'options-reading.php');
			
			switch (suwp::permalink_mode()) {
				case SUWP_QUERY_PERMALINKS:
					$this->results[] = array(SU_RESULT_ERROR, __('Query-string permalinks enabled', 'seo-ultimate'),
						__('It is highly recommended that you use a non-default and non-numeric permalink structure.', 'seo-ultimate'), 'options-permalink.php');
					break;
					
				case SUWP_INDEX_PERMALINKS:
					$this->results[] = array(SU_RESULT_WARNING, __('Pathinfo permalinks enabled', 'seo-ultimate'), 
						__('Pathinfo permalinks add a keyword-less &#8220;index.php&#8221; prefix. This is not ideal, but it may be beyond your control (since it&#8217;s likely caused by your site&#8217;s web hosting setup).', 'seo-ultimate'), 'options-permalink.php');
					
				case SUWP_PRETTY_PERMALINKS:
					
					if (strpos(get_option('permalink_structure'), '%postname%') !== false)
						$this->results[] = array(SU_RESULT_OK, __('Permalinks include the post slug', 'seo-ultimate'),
							__('Including a version of the post&#8217;s title helps provide keyword-rich URLs.', 'seo-ultimate'));
					else
						$this->results[] = array(SU_RESULT_ERROR, __('Permalinks do not include the post slug', 'seo-ultimate'),
							__('It is highly recommended that you include the %postname% variable in the permalink structure.', 'seo-ultimate'), 'options-permalink.php');
					
					break;
			}
		}
	}
	
	function admin_page_contents() {
		
		echo "\n<p>";
		_e("Settings Monitor analyzes your blog&#8217;s settings and notifies you of any problems. If any issues are found, they will show up in red or yellow below.", 'seo-ultimate');
		echo "</p>\n";
		
		echo "<table class='report'>\n";
		
		$first = true;
		foreach ($this->results as $data) {
			
			$result = $data[0];
			$title  = $data[1];
			$desc   = $data[2];
			$url    = isset($data[3]) ? $data[3] : false;
			$action = isset($data[4]) ? $data[4] : __('Go to setting &raquo;', 'seo-ultimate');
			
			switch ($result) {
				case SU_RESULT_OK: $class='success'; break;
				case SU_RESULT_ERROR: $class='error'; break;
				default: $class='warning'; break;
			}
			
			if ($result == SU_RESULT_OK || !$url)
				$link='';
			else {
				if (substr($url, 0, 7) == 'http://') $target = " target='_blank'"; else $target='';
				$link = "<a href='$url'$target>$action</a>";
			}
			
			if ($first) { $firstclass = " class='first'"; $first = false; } else $firstclass='';
			echo "\t<tr$firstclass>\n\t\t<td><div class='su-$class'><strong>$title</strong></div><div>$desc</div><div>$link</div></td>\n\t</tr>\n";
		}
		
		echo "</table>\n\n";
	}
}

}
?>