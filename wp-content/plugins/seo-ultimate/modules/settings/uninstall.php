<?php
/**
 * Uninstaller Module
 * 
 * @since 2.1
 */

if (class_exists('SU_Module')) {

class SU_Uninstall extends SU_Module {

	static function get_parent_module() { return 'settings'; }
	static function get_child_order() { return 40; }
	static function is_independent_module() { return false; }
	function get_settings_key() { return $this->get_module_key(); }
	
	static function get_module_title() { return __('Uninstaller', 'seo-ultimate'); }
	function get_module_subtitle() { return __('Uninstall', 'seo-ultimate'); }
	
	function get_admin_page_tabs() {
		if ($this->current_user_can_uninstall())
			return array(array('title' => __('Uninstall', 'seo-ultimate'), 'id' => 'su-uninstall', 'callback' => 'uninstall_tab'));
		else
			return false;
	}
	
	function belongs_in_admin($admin_scope = null) {
		
		if ($admin_scope === null)
			$admin_scope = suwp::get_admin_scope();
		
		switch ($admin_scope) {
			case 'blog':
				
				if ( ! function_exists( 'is_plugin_active_for_network' ) )
					require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
				
				return !is_multisite() || !is_plugin_active_for_network($this->plugin->plugin_basename);
				break;
			case 'network':
				return true;
				break;
			default:
				return false;
				break;
		}
	}
	
	function current_user_can_uninstall() {
		return current_user_can('delete_plugins') && (!is_multisite() || is_super_admin());
	}
	
	function init() {
		if ($this->is_action('su-uninstall'))
			add_filter('su_custom_admin_page-settings', array(&$this, 'do_uninstall'));
	}
	
	function uninstall_tab() {
		
		if (!$this->current_user_can_uninstall()) {
			$this->print_message('error', __('You do not have sufficient permissions to delete plugins on this site.', 'seo-ultimate'));
			return;
		}
		
		echo "\n<p>";
		_e('Uninstalling SEO Ultimate will delete your settings and the plugin&#8217;s files.', 'seo-ultimate');
		echo "</p>\n";
		$url = $this->get_nonce_url('su-uninstall');
		$confirm = __('Are you sure you want to uninstall SEO Ultimate? This will permanently erase your SEO Ultimate settings and cannot be undone.', 'seo-ultimate');
		echo "<p><a href='$url' class='button-primary' onclick=\"javascript:return confirm('$confirm')\">".__('Uninstall Now', 'seo-ultimate')."</a></p>";
	}
	
	function enable_post_uninstall_page() {
		add_submenu_page('su-hidden-modules', __('Uninstall SEO Ultimate', 'seo-ultimate'), 'Uninstall',
			'manage_options', 'seo-ultimate', array(&$this->parent_module, 'admin_page_contents'));
	}
	
	function do_uninstall() {
		
		if (!$this->current_user_can_uninstall())
			wp_die(__('You do not have sufficient permissions to delete plugins on this site.', 'seo-ultimate'));
		
		echo "<script type='text/javascript'>jQuery('#adminmenu .current').hide(); jQuery('#toplevel_page_seo').hide();</script>";
		echo "<div class=\"wrap\">\n";
		echo "\n<h2>".__('Uninstall SEO Ultimate', 'seo-ultimate')."</h2>\n";
		
		//Delete settings and do miscellaneous clean up
		$this->plugin->uninstall();
		$this->print_mini_message('success', __('Deleted settings.', 'seo-ultimate'));
		
		//Deactivate the plugin
		deactivate_plugins(array($this->plugin->plugin_basename), true);
		
		//Attempt to delete the plugin's files and output result
		if (is_wp_error($error = delete_plugins(array($this->plugin->plugin_basename))))
			$this->print_mini_message('error', __('An error occurred while deleting files.', 'seo-ultimate').'<br />'.$error->get_error_message());
		else {
			$this->print_mini_message('success', __('Deleted files.', 'seo-ultimate'));
			$this->print_mini_message('success', __('Uninstallation complete. Thanks for trying SEO Ultimate.', 'seo-ultimate'));
		}
		
		echo "\n</div>\n";
		
		return true;
	}
}

}
?>