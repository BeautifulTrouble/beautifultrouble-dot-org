<?php
/**
 * Author Highlighter Module
 * 
 * @since 7.4
 */

if (class_exists('SU_Module')) {

define('SU_AUTHORLINKS_MODE_OFF', 0);
define('SU_AUTHORLINKS_MODE_SINGLEAUTHOR', 1);
define('SU_AUTHORLINKS_MODE_MULTIAUTHOR', 2);

class SU_AuthorLinks extends SU_Module {
	
	static function get_module_title() { return __('Author Highlighter', 'seo-ultimate'); }
	static function get_menu_title() { return __('Author Highlighter', 'seo-ultimate'); }
	static function get_parent_module() { return 'misc'; }
	function get_settings_key() { return 'author-links'; }
	
	function init() {
		add_filter('user_contactmethods', array(&$this, 'add_google_profile_field'));
		add_action('su_head', array(&$this, 'output_author_link_tags'));
	}
	
	function admin_page_contents() {
		
		$mode = $this->get_mode();
		switch ($mode) {
			case SU_AUTHORLINKS_MODE_OFF:
				$this->print_message('warning', __('In order for author highlighting to work, your authors with Google+ accounts need to add their Google+ URLs to their profile page on this site. Why don&#8217;t you start by adding your Google+ URL to <a href="profile.php">your profile</a>?', 'seo-ultimate'));
				break;
			
			case SU_AUTHORLINKS_MODE_SINGLEAUTHOR:
				$this->child_admin_form_start();
				$this->textblock(__('Since this site only has one <a href="users.php">registered user</a> who has written posts, Author Highlighter is providing that user&#8217;s Google+ profile image to Google for it to use when any page on this WordPress site appears in Google&#8217;s search result listings. If at some point you were to add an additional user to this site and that user were to write some posts, then additional options would show up in this section.', 'seo-ultimate'));
				$this->child_admin_form_end();
				break;
			
			case SU_AUTHORLINKS_MODE_MULTIAUTHOR:
				$users_with_gp = get_users(array(
					  'fields' => array('ID', 'user_login', 'display_name')
					, 'meta_key' => 'googleplus'
					, 'meta_value' => ''
					, 'meta_compare' => '!='
				));
				
				$this->child_admin_form_start();
				
				$user_dropdown_options = array('none' => __('(None)', 'seo-ultimate'));
				foreach ($users_with_gp as $user) {
					$user_dropdown_options[$user->ID] = $user->display_name ? $user->display_name : $user->user_login;
				}
				
				$this->dropdown('home_author', $user_dropdown_options, __('Author of Blog Homepage', 'seo-ultimate'));
				$this->dropdown('archives_author', $user_dropdown_options, __('Author of Archive Pages', 'seo-ultimate'));
				
				$this->child_admin_form_end();
				break;
		}
	}
	
	function get_users_with_gp() {
		static $users_with_gp = null;
		if ($users_with_gp === null) {
			$users_with_gp = get_users(array(
				  'fields' => array('ID', 'user_login', 'display_name')
				, 'meta_key' => 'googleplus'
				, 'meta_value' => ''
				, 'meta_compare' => '!='
			));
		}
		return $users_with_gp;
	}
	
	function get_mode() {
		
		if (count($this->get_users_with_gp()) > 0) {
			//We have at least one user who provided a Google+ profile
			
			if (is_multi_author())
				return SU_AUTHORLINKS_MODE_MULTIAUTHOR;
			else
				return SU_AUTHORLINKS_MODE_SINGLEAUTHOR;
			
		} else {
			return SU_AUTHORLINKS_MODE_OFF;
		}
		
	}
	
	function add_google_profile_field( $contactmethods ) {
		$contactmethods['googleplus'] = __('Google+ Profile URL', 'seo-ultimate');
		return $contactmethods;
	}
	
	function output_author_link_tags() {
		
		if (is_404())
			return;
		
		$user_id = false;
		
		$mode = $this->get_mode();
		switch ($mode) {
			case SU_AUTHORLINKS_MODE_OFF:
				return;
				break;
			case SU_AUTHORLINKS_MODE_SINGLEAUTHOR:
				$users_with_gp = (array)$this->get_users_with_gp();
				$user = reset($users_with_gp);
				$user_id = $user->ID;
				break;
			case SU_AUTHORLINKS_MODE_MULTIAUTHOR:
				if (is_home()) {
					$home_author = $this->get_setting('home_author', 'none');
					if (is_numeric($home_author)) $user_id = $home_author;
				} elseif (is_singular()) {
					global $post;
					if (is_object($post)) $user_id = $post->post_author;
				} elseif (is_author()) {
					global $wp_query;
					$user_id = $wp_query->get_queried_object_id();
				} elseif (is_archive()) {
					$archives_author = $this->get_setting('archives_author', 'none');
					if (is_numeric($archives_author)) $user_id = $archives_author;
				}
				break;
		}
		
		if ($user_id !== false) {
			$url = get_user_meta($user_id, 'googleplus', true);
			$url = su_esc_attr($url);
			if ($url)
				echo "\t<link rel=\"author\" href=\"$url\" />\n";
		}
	}
	
}

}
?>