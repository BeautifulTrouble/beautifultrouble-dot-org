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
	function get_post_id() {
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
	
	function get_any_posts($args = array()) {
		if (!is_array($args)) $args = array();
		$args['post_type'] = suwp::get_post_type_names(); //...as opposed to "any" because then get_posts() will remove search-excluded post types
		$args['numberposts'] = -1;
		return get_posts($args);
	}
	
	function get_post_type_names() {
		//If WP 2.9+ && WP 3.0+...
		if (function_exists('get_post_types') && $types = get_post_types(array('public' => true), 'names'))
			return $types;
		
		//WP 2.9 or less
		return array('post', 'page', 'attachment');
	}
	
	function get_post_type_objects() {
		$types = array();
		
		//Custom post type support - requires WordPress 3.0 or above (won't work with 2.9 custom post types)
		if (function_exists('get_post_types'))
			$types = get_post_types(array('public' => true), 'objects');
		
		/*
		if (function_exists('get_post_types'))
			$types = suarr::flatten_values(get_post_types(array('public' => true), 'objects'), array('labels', 'name'));
		*/
		
		//Legacy support for WordPress 2.9 and below
		if (!count($types)) {
			
			$_types = array(
				  array('post', __('Posts'), __('Post'))
				, array('page', __('Pages'), __('Page'))
				, array('attachment', __('Attachments'), __('Attachment'))
			);
			$types = array();
			foreach ($_types as $_type) {
				$type = new stdClass();
				$type->name = $_type[0];
				$type->labels->name = $_type[1];
				$type->labels->singular_name = $_type[2];
				$types[] = $type;
			}
		}
		
		return $types;
	}
	
	function is_tax($taxonomy='', $term='') {
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
	
	function get_taxonomies() {
		$taxonomies = get_taxonomies(array('public' => true), 'objects');
		if (isset($taxonomies['post_format']) && $taxonomies['post_format']->labels->name == _x( 'Format', 'post format' ))
			$taxonomies['post_format']->labels->name = __('Post Format Archives', 'seo-ultimate');
		return $taxonomies;
	}
	
	function get_taxonomy_names() {
		return get_taxonomies(array('public' => true), 'names');
	}
	
	function get_object_taxonomies($post_type) {
		$taxonomies = get_object_taxonomies($post_type, 'objects');
		$taxonomies = wp_filter_object_list($taxonomies, array('public' => true, 'show_ui' => true));
		return $taxonomies;
	}
	
	function get_object_taxonomy_names($post_type) {
		$taxonomies = get_object_taxonomies($post_type, 'objects');
		$taxonomies = wp_filter_object_list($taxonomies, array('public' => true, 'show_ui' => true), 'and', 'name');
		return $taxonomies;
	}
	
	function get_any_terms($args = array()) {
		$taxonomies = get_taxonomies(array('public' => true));
		return get_terms($taxonomies, $args);
	}
	
	/**
	 * Loads a webpage and returns its HTML as a string.
	 * 
	 * @param string $url The URL of the webpage to load.
	 * @param string $ua The user agent to use.
	 * @return string The HTML of the URL.
	 */
	function load_webpage($url, $ua) {
		
		$options = array();
		$options['headers'] = array(
			'User-Agent' => $ua
		);
		
		$response = wp_remote_request($url, $options);
		
		if ( is_wp_error( $response ) ) return false;
		if ( 200 != $response['response']['code'] ) return false;
		
		return trim( $response['body'] );
	}
	
	/**
	 * Loads an RSS feed and returns it as an object.
	 * 
	 * @param string $url The URL of the RSS feed to load.
	 * @param callback $ua The user agent to use.
	 * @return object $rss The RSS object.
	 */
	function load_rss($url, $ua) {
		$ua = addslashes($ua);
		$uafunc = create_function('', "return '$ua';");
		add_filter('http_headers_useragent', $uafunc);
		require_once (ABSPATH . WPINC . '/rss.php');
		$rss = fetch_rss($url);
		remove_filter('http_headers_useragent', $uafunc);
		return $rss;
	}
	
	/**
	 * @return string
	 */
	function add_backup_url($text) {
		$anchor = __('backup your database', 'seo-ultimate');
		return str_replace($anchor, '<a href="'.suwp::get_backup_url().'" target="_blank">'.$anchor.'</a>', $text);
	}
	
	/**
	 * @return string
	 */
	function get_backup_url() {
		if (is_plugin_active('wp-db-backup/wp-db-backup.php'))
			return admin_url('tools.php?page=wp-db-backup');
		else
			return 'http://codex.wordpress.org/Backing_Up_Your_Database';
	}
	
	function get_edit_term_link($id, $taxonomy) {
		$tax_obj = get_taxonomy($taxonomy);
		if ($tax_obj->show_ui)
			return get_edit_tag_link($id, $taxonomy);
		else
			return false;
	}
	
	function get_all_the_terms($id = 0) {
		
		$id = (int)$id;
		if (!$id) $id = suwp::get_post_id();
		$post = get_post($id);
		if (!$post) return false;
		
		$taxonomies = get_object_taxonomies($post);
		$terms = array();
		
		foreach ($taxonomies as $taxonomy) {
			$newterms = get_the_terms($id, $taxonomy);
			if ($newterms) $terms = array_merge($terms, $newterms);
		}
		
		return $terms;
	}
	
	function get_term_slug($term_obj) {
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
	
	function remove_instance_action($tag, $class, $function, $priority=10) {
		return suwp::remove_instance_filter($tag, $class, $function, $priority);
	}
	
	function remove_instance_filter($tag, $class, $function, $priority=10) {
		if (isset($GLOBALS['wp_filter'][$tag][$priority]) && count($GLOBALS['wp_filter'][$tag][$priority])) {
			foreach ($GLOBALS['wp_filter'][$tag][$priority] as $key => $x) {
				if (sustr::startswith($key, $class.$function)) {
					unset($GLOBALS['wp_filter'][$tag][$priority][$key]);
					if ( empty($GLOBALS['wp_filter'][$tag][$priority]) )
						unset($GLOBALS['wp_filter'][$tag][$priority]);
					unset($GLOBALS['merged_filters'][$tag]);
					return true;
				}
			}
		}
		
		return false;
	}
	
	function permalink_mode() {
		if (strlen($struct = get_option('permalink_structure'))) {
			if (sustr::startswith($struct, '/index.php/'))
				return SUWP_INDEX_PERMALINKS;
			else
				return SUWP_PRETTY_PERMALINKS;
		} else
			return SUWP_QUERY_PERMALINKS;
	}
	
	function get_blog_home_url() {
		if ('page' == get_option('show_on_front') && $page_id = (int)get_option('page_for_posts'))
			return get_permalink($page_id);
		
		return home_url('/');
	}
	
	function get_user_edit_url($user_id) {
		if (is_object($user_id)) $user_id = intval($user_id->ID);
		
		if ( get_current_user_id() == $user_id )
			return 'profile.php';
		else
			return esc_url( add_query_arg( 'wp_http_referer', urlencode( stripslashes( $_SERVER['REQUEST_URI'] ) ), "user-edit.php?user_id=$user_id" ) );
	}
	
	function is_comment_subpage() {
		if (is_singular()) {
			global $cpage;
			return $cpage > 1;
		}
		
		return false;
	}
	
	function get_admin_scope() {
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