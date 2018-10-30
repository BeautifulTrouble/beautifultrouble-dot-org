<?php

define('SUWP_QUERY_PERMALINKS', 0);
define('SUWP_INDEX_PERMALINKS', 1);
define('SUWP_PRETTY_PERMALINKS', 2);

class suwp {

	/**
	 * Determines the ID of the current post.
	 * Works in the admin as well as the front-end.
	 * 
	 * @return int|false The ID of the current post, or false on failure.
	 */
	static function get_post_id() {
		if (is_admin()) {
			if (!empty($_REQUEST['post']))
				return intval($_REQUEST['post']);
			elseif (!empty($_REQUEST['post_ID']))
				return intval($_REQUEST['post_ID']);
			else
				return false;
		} elseif (in_the_loop()) {
			return intval(get_the_ID());
		} elseif (is_singular()) {
			global $wp_query;
			return $wp_query->get_queried_object_id();
		}
		
		return false;
	}
	
	static function is_tax($taxonomy='', $term='') {
		if ($taxonomy) {
			switch ($taxonomy) {
				case 'category': return is_category($term); break;
				case 'post_tag': return is_tag($term); break;
				default: return is_tax($taxonomy, $term); break;
			}
		} else {
			return is_category() || is_tag() || is_tax();
		}
	}
	
	static function get_taxonomies() {
		$taxonomies = get_taxonomies(array('public' => true), 'objects');
		if (isset($taxonomies['post_format']) && $taxonomies['post_format']->labels->name == _x( 'Format', 'post format' ))
			$taxonomies['post_format']->labels->name = __('Post Format Archives', 'seo-ultimate');
		return $taxonomies;
	}
	
	static function get_taxonomy_names() {
		return get_taxonomies(array('public' => true), 'names');
	}
	
	static function get_object_taxonomies($post_type) {
		$taxonomies = get_object_taxonomies($post_type, 'objects');
		$taxonomies = wp_filter_object_list($taxonomies, array('public' => true, 'show_ui' => true));
		return $taxonomies;
	}
	
	function get_object_taxonomy_names($post_type) {
		$taxonomies = get_object_taxonomies($post_type, 'objects');
		$taxonomies = wp_filter_object_list($taxonomies, array('public' => true, 'show_ui' => true), 'and', 'name');
		return $taxonomies;
	}
	
	/**
	 * Loads an RSS feed and returns it as an object.
	 * 
	 * @param string $url The URL of the RSS feed to load.
	 * @param callback $ua The user agent to use.
	 * @return object $rss The RSS object.
	 */
	static function load_rss($url, $ua) {
		$ua = addslashes($ua);
		$uafunc = create_function('', "return '$ua';");
		add_filter('http_headers_useragent', $uafunc);
		require_once (ABSPATH . WPINC . '/class-simplepie.php');
		$rss = fetch_feed($url);
		remove_filter('http_headers_useragent', $uafunc);
		return $rss;
	}
	
	/**
	 * @return string
	 */
	static function add_backup_url($text) {
		$anchor = __('backup your database', 'seo-ultimate');
		return str_replace($anchor, '<a href="'.suwp::get_backup_url().'" target="_blank">'.$anchor.'</a>', $text);
	}
	
	/**
	 * @return string
	 */
	static function get_backup_url() {
		if (is_plugin_active('wp-db-backup/wp-db-backup.php'))
			return admin_url('tools.php?page=wp-db-backup');
		else
			return 'http://codex.wordpress.org/Backing_Up_Your_Database';
	}
	
	static function get_edit_term_link($id, $taxonomy) {
		$tax_obj = get_taxonomy($taxonomy);
		if ($tax_obj->show_ui)
			return get_edit_term_link($id, $taxonomy);
		else
			return false;
	}
	
	static function get_term_slug($term_obj) {
		$tax_name = $term_obj->taxonomy;
		$tax_obj = get_taxonomy($tax_name);
		if ($tax_obj->rewrite['hierarchical']) {
			$hierarchical_slugs = array();
			$ancestors = get_ancestors($term_obj->term_id, $tax_name);
			foreach ( (array)$ancestors as $ancestor ) {
				$ancestor_term = get_term($ancestor, $tax_name);
				$hierarchical_slugs[] = $ancestor_term->slug;
			}
			$hierarchical_slugs = array_reverse($hierarchical_slugs);
			$hierarchical_slugs[] = $term_obj->slug;
			$term_slug = implode('/', $hierarchical_slugs);
		} else {
			$term_slug = $term_obj->slug;
		}
		
		if ('post_format' == $tax_name)
			$term_slug = str_replace('post-format-', '', $term_slug);
		
		return $term_slug;
	}
	
	static function permalink_mode() {
		if (strlen($struct = get_option('permalink_structure'))) {
			if (sustr::startswith($struct, '/index.php/'))
				return SUWP_INDEX_PERMALINKS;
			else
				return SUWP_PRETTY_PERMALINKS;
		} else
			return SUWP_QUERY_PERMALINKS;
	}
	
	static function get_blog_home_url() {
		if ('page' == get_option('show_on_front') && $page_id = (int)get_option('page_for_posts'))
			return get_permalink($page_id);
		
		return home_url('/');
	}
	
	static function get_admin_scope() {
		if (is_blog_admin())
			return 'blog';
		
		if (is_network_admin())
			return 'network';
		
		if (is_user_admin())
			return 'user';
		
		return false;
	}
}

?>