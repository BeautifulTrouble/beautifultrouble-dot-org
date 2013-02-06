<?php
/**
 * Permalink Tweaker Module
 * 
 * @since 5.8
 */

//Permalink base removal code based on code from WP No Category Base plugin
//http://wordpress.org/extend/plugins/wp-no-category-base/

if (class_exists('SU_Module')) {

class SU_Permalinks extends SU_Module {
	
	function get_module_title() { return __('Permalink Tweaker', 'seo-ultimate'); }
	
	function get_parent_module() { return 'misc'; }
	function get_settings_key() { return 'permalinks'; }
	
	function get_default_settings() {
		return array(
			  'add_rule_if_conflict' => true
		);
	}
	
	function init() {
		if (suwp::permalink_mode()) {
			$nobase_enabled = false;
			$taxonomies = suwp::get_taxonomy_names();
			foreach ($taxonomies as $taxonomy) {
				if ($this->get_setting("nobase_$taxonomy", false)) {
					add_action("created_$taxonomy", array(&$this, 'flush_rewrite_rules'));
					add_action("edited_$taxonomy", array(&$this, 'flush_rewrite_rules'));
					add_action("delete_$taxonomy", array(&$this, 'flush_rewrite_rules'));
					add_filter("{$taxonomy}_rewrite_rules", array(&$this, 'nobase_rewrite_rules'));
					$nobase_enabled = true;
				}
			}
			if ($nobase_enabled) {
				add_action('wp_insert_post', array(&$this, 'flush_rewrite_rules'));
				add_filter('term_link', array(&$this, 'nobase_term_link'), 1000, 2);
				add_filter('query_vars', array(&$this, 'nobase_query_vars'));
				add_filter('request', array(&$this, 'nobase_old_base_redirect'));
			}
		}
	}
	
	function deactivate() {
		if (suwp::permalink_mode()) {
			$nobase_enabled = false;
			$taxonomies = suwp::get_taxonomy_names();
			foreach ($taxonomies as $taxonomy) {
				if ($this->get_setting("nobase_$taxonomy", false)) {
					remove_action("created_$taxonomy", array(&$this, 'flush_rewrite_rules'));
					remove_action("edited_$taxonomy", array(&$this, 'flush_rewrite_rules'));
					remove_action("delete_$taxonomy", array(&$this, 'flush_rewrite_rules'));
					remove_filter("{$taxonomy}_rewrite_rules", array(&$this, 'nobase_rewrite_rules'));
					$nobase_enabled = true;
				}
			}
			if ($nobase_enabled) {
				remove_action('wp_insert_post', array(&$this, 'flush_rewrite_rules'));
				remove_filter('term_link', array(&$this, 'nobase_term_link'), 1000, 2);
				remove_filter('query_vars', array(&$this, 'nobase_query_vars'));
				remove_filter('request', array(&$this, 'nobase_old_base_redirect'));
			}
		}
		
		$this->flush_rewrite_rules();
	}
	
	function admin_page_contents() {
		
		if (!suwp::permalink_mode()) {
			$this->print_message('warning', __('To use the Permalinks Tweaker, you must disable default (query-string) permalinks in your <a href="options-permalink.php">Permalink Settings</a>.', 'seo-ultimate'));
			return;
		}
		
		$this->child_admin_form_start();
		
		$nobase_checkboxes = array();
		$taxonomies = suwp::get_taxonomies();
		foreach ($taxonomies as $taxonomy) {
			
			global $wp_rewrite;
			$before_url = $wp_rewrite->get_extra_permastruct($taxonomy->name);
			$before_url = str_replace("%{$taxonomy->name}%", 'example', $before_url);
			$before_url = home_url( user_trailingslashit($before_url, 'category') );
			
			$after_url = home_url( user_trailingslashit('/example', 'category') );
			
			$nobase_checkboxes[] = array(
				  'setting_id' => 'nobase_' . $taxonomy->name
				, 'taxonomy_label' => $taxonomy->labels->name
				, 'example_before' => $before_url
				, 'example_after' => $after_url
			);
		}
		
		$this->admin_form_group_start(__('Remove the URL bases of...', 'seo-ultimate'));
		
		echo "<tr><td>\n";
		$this->admin_wftable_start(array(
			  'taxonomy' => ' '
			, 'before' => __('Before', 'seo-ultimate')
			, 'arrow' => ' '
			, 'after' => __('After', 'seo-ultimate')
		));
		
		foreach ($nobase_checkboxes as $nobase_checkbox) {
			echo "<tr>\n";
			echo "<td class='su-permalinks-taxonomy'>";
			$this->checkbox($nobase_checkbox['setting_id'], $nobase_checkbox['taxonomy_label'], false, array('output_tr' => false));
			echo "</td>\n";
			echo "<td class='su-permalinks-before'>" . esc_html($nobase_checkbox['example_before']) . "</td>\n";
			echo "<td class='su-permalinks-arrow'>&rArr;</td>\n";
			echo "<td class='su-permalinks-after'>" . esc_html($nobase_checkbox['example_after']) . "</td>\n";
			echo "</tr>\n";
		}
		
		$this->admin_wftable_end();
		echo "</td></tr>\n";
		
		$this->admin_form_group_end();
		
		$this->dropdown('add_rule_if_conflict', array(
			  '1' => __('term archive', 'seo-ultimate')
			, '0' => __('page', 'seo-ultimate')
		), __('URL Conflict Resolution', 'seo-ultimate'), __('If a term archive and a Page with the same slug end up having the same URL because of the term&#8217;s base being removed, the URL should be given to the %s.', 'seo-ultimate'));
		
		$this->child_admin_form_end();
		
		$this->update_rewrite_filters();
		$this->flush_rewrite_rules();
	}
	
	function flush_rewrite_rules() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	
	function update_rewrite_filters() {
		if (suwp::permalink_mode()) {
			$taxonomies = suwp::get_taxonomy_names();
			foreach ($taxonomies as $taxonomy) {
				if ($this->get_setting("nobase_$taxonomy", false))
					add_filter("{$taxonomy}_rewrite_rules", array(&$this, 'nobase_rewrite_rules'));
				else
					remove_filter("{$taxonomy}_rewrite_rules", array(&$this, 'nobase_rewrite_rules'));
			}
		}
	}
	
	function nobase_term_link($url, $term_obj) {
		if ($this->get_setting('nobase_' . $term_obj->taxonomy, false))
			return home_url( user_trailingslashit('/' . suwp::get_term_slug($term_obj), 'category') );
		
		return $url;
	}
	
	function nobase_rewrite_rules($rules) {
		$rules=array();
		
		$tax_name = sustr::rtrim_str(current_filter(), '_rewrite_rules');
		$tax_obj = get_taxonomy($tax_name);
		
		wp_cache_flush(); //Otherwise get_terms() won't include the term just added
		$terms = get_terms($tax_name);
		if ($terms && !is_wp_error($terms)) {
			foreach ($terms as $term_obj) {
				$term_slug = suwp::get_term_slug($term_obj);
				
				if ($tax_obj->query_var && is_string($tax_obj->query_var))
					$url_start = "index.php?{$tax_obj->query_var}=";
				else
					$url_start = "index.php?taxonomy={$tax_name}&term=";
				
				if ($this->get_setting('add_rule_if_conflict', true) || get_page_by_path($term_slug) === null) {
					$rules['('.$term_slug.')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = $url_start . '$matches[1]&feed=$matches[2]';
					$rules['('.$term_slug.')/page/?([0-9]{1,})/?$'] = $url_start . '$matches[1]&paged=$matches[2]';
					$rules['('.$term_slug.')/?$'] = $url_start . '$matches[1]';
				}
			}
		}
		
		global $wp_rewrite;
		$old_base = $wp_rewrite->get_extra_permastruct($tax_name);
		$old_base = str_replace( "%{$tax_name}%", '(.+)', $old_base );
		$old_base = trim($old_base, '/');
		$rules[$old_base.'$'] = 'index.php?su_term_redirect=$matches[1]';
		
		return $rules;
	}
	
	function nobase_query_vars($query_vars) {
		$query_vars[] = 'su_term_redirect';
		return $query_vars;
	}
	
	function nobase_old_base_redirect($query_vars) {
		if (isset($query_vars['su_term_redirect'])) {
			wp_redirect(home_url(user_trailingslashit($query_vars['su_term_redirect'], 'category')));
			exit;
		}
		return $query_vars;
	}
}

}
?>