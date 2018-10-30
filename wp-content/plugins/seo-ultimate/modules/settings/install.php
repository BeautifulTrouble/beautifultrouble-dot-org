<?php
/**
 * Install Module
 * 
 * @since 2.5
 */

if (class_exists('SU_Module')) {

define('SU_DOWNGRADE_LIMIT', '5.0');

class SU_Install extends SU_Module {
	
	static function get_parent_module() { return 'settings'; }
	static function get_child_order() { return 20; }
	static function is_independent_module() { return false; }
	
	static function get_module_title() { return __('Upgrade/Downgrade/Reinstall', 'seo-ultimate'); }
	static function get_menu_title() { return __('Installer', 'seo-ultimate'); }
	
	function get_admin_page_tabs() {
		
		$tabs = array();
		
		if ($this->current_user_can_upgrade())
			$tabs[] = array('title' => __('Upgrade', 'seo-ultimate'), 'id' => 'su-upgrade', 'callback' => 'upgrade_tab');
		
		if ($this->current_user_can_downgrade())
			$tabs[] = array('title' => __('Downgrade', 'seo-ultimate'), 'id' => 'su-downgrade', 'callback' => 'downgrade_tab');
		
		if ($this->current_user_can_reinstall())
			$tabs[] = array('title' => __('Reinstall', 'seo-ultimate'), 'id' => 'su-reinstall', 'callback' => 'reinstall_tab');
		
		if (count($tabs))
			return $tabs;
		
		return false;
	}
	
	function belongs_in_admin($admin_scope = null) {
		
		if ($admin_scope === null)
			$admin_scope = suwp::get_admin_scope();
		
		if ( ! function_exists( 'is_plugin_active_for_network' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		
		switch ($admin_scope) {
			case 'blog':
				return !is_multisite() || !is_plugin_active_for_network($this->plugin->plugin_basename);
				break;
			case 'network':
				return is_plugin_active_for_network($this->plugin->plugin_basename);
				break;
			default:
				return false;
				break;
		}
	}
	
	function current_user_can_upgrade() {
		return current_user_can('update_plugins') && (!is_multisite() || is_super_admin());
	}
	
	function current_user_can_downgrade() {
		return current_user_can('install_plugins') && (!is_multisite() || is_super_admin());
	}
	
	function current_user_can_reinstall() {
		return current_user_can('install_plugins') && (!is_multisite() || is_super_admin());
	}
	
	function init() {
		if ($this->is_action('update')) {
			add_filter('su_custom_admin_page-settings', array(&$this, 'do_installation'));
		}
	}
	
	function upgrade_tab() {
		
		if (!$this->current_user_can_upgrade()) {
			$this->print_message('error', __('You do not have sufficient permissions to upgrade plugins on this site.', 'seo-ultimate'));
			return;
		}
		
		$radiobuttons = $this->get_version_radiobuttons(SU_VERSION, false);
		if (is_array($radiobuttons)) {
			if (count($radiobuttons) > 1) {
				
				echo "\n<p>";
				_e('From the list below, select the version to which you would like to upgrade. Then click the &#8220;Upgrade&#8221; button at the bottom of the screen.', 'seo-ultimate');
				echo "</p>\n";
				
				echo "<div class='su-xgrade'>\n";
				$this->admin_form_start();
				$this->radiobuttons('version', $radiobuttons);
				$this->admin_form_end(__('Upgrade', 'seo-ultimate'));
				echo "</div>\n";
			} else
				$this->print_message('success', __('You are already running the latest version.', 'seo-ultimate'));
		} else
			$this->print_message('error', __('There was an error retrieving the list of available versions. Please try again later. You can also upgrade to the latest version of SEO Ultimate using the WordPress plugin upgrader.', 'seo-ultimate'));
	}
	
	function downgrade_tab() {
		
		if (!$this->current_user_can_downgrade()) {
			$this->print_message('error', __('You do not have sufficient permissions to downgrade plugins on this site.', 'seo-ultimate'));
			return;
		}
		
		$radiobuttons = $this->get_version_radiobuttons(SU_DOWNGRADE_LIMIT, SU_VERSION, 5);
		if (is_array($radiobuttons)) {
			if (count($radiobuttons) > 1) {
				
				$this->print_message('warning', suwp::add_backup_url(__('Downgrading is provided as a convenience only and is not officially supported. Although unlikely, you may lose data in the downgrading process. It is your responsibility to backup your database before proceeding.', 'seo-ultimate')));
				
				echo "\n<p>";
				_e('From the list below, select the version to which you would like to downgrade. Then click the &#8220;Downgrade&#8221; button at the bottom of the screen.', 'seo-ultimate');
				echo "</p>\n";
				
				echo "<div class='su-xgrade'>\n";
				$this->admin_form_start();
				$this->radiobuttons('version', $radiobuttons);
				$this->admin_form_end(__('Downgrade', 'seo-ultimate'));
				echo "</div>\n";
			} else
				$this->print_message('warning', sprintf(__('Downgrading to versions earlier than %s is not supported because doing so will result the loss of some or all of your SEO Ultimate settings.', 'seo-ultimate'), SU_DOWNGRADE_LIMIT));
		} else
			$this->print_message('error', __('There was an error retrieving the list of available versions. Please try again later.', 'seo-ultimate'));
	}
	
	function reinstall_tab() {
		
		if (!$this->current_user_can_reinstall()) {
			$this->print_message('error', __('You do not have sufficient permissions to reinstall plugins on this site.', 'seo-ultimate'));
			return;
		}
		
		echo "\n<p>";
		_e('To download and install a fresh copy of the SEO Ultimate version you are currently using, click the &#8220;Reinstall&#8221; button below.', 'seo-ultimate');
		echo "</p>\n";
		
		$this->admin_form_start(false, false);
		echo "<input type='hidden' name='version' id='version' value='".su_esc_attr(SU_VERSION)."' />\n";
		$this->admin_form_end(__('Reinstall', 'seo-ultimate'), false);
	}
	
	function get_version_radiobuttons($min, $max, $limit=false) {
		
		$this->update_setting('version', SU_VERSION);
		
		$versions = $this->plugin->download_changelog();
		
		if (is_array($versions) && count($versions)) {
			
			$radiobuttons = array();
			$i = 0;
			foreach ($versions as $title => $changes) {
				if (preg_match('|Version ([0-9.]{3,9}) |', $title, $matches)) {
					$version = $matches[1];
					
					if ($max && version_compare($version, $max, '>')) continue;
					if ($min && version_compare($version, $min, '<')) break;
					
					$changes = wptexturize($changes);
					if ($version == SU_VERSION)
						$message = __('Your Current Version', 'seo-ultimate');
					elseif (0 == $i)
						$message = __('Latest Version', 'seo-ultimate');
					else
						$message = '';
					if ($message) $message = " &mdash; <em>$message</em>";
					
					$radiobuttons[$version] = "<strong>$title</strong>$message</label>\n$changes\n";
					
					if ($limit !== false && $limit > 0 && ++$i >= $limit) break;
				}
			}
			
			return $radiobuttons;
		}
		
		return false; //Error
	}
	
	function do_installation() {
		
		if (!isset($_POST['version'])) return false;
		
		$nv = sustr::preg_filter('0-9a-zA-Z .', $_POST['version']);
		if (!strlen($nv)) return false;
		
		//Don't allow downgrading to anything below the minimum limit
		if (version_compare(SU_DOWNGRADE_LIMIT, $nv, '>')) return;
		
		switch (version_compare($nv, SU_VERSION)) {
			case -1: //Downgrade
				$title = __('Downgrade to SEO Ultimate %s', 'seo-ultimate');
				
				if (!$this->current_user_can_downgrade()) {
					wp_die(__('You do not have sufficient permissions to downgrade plugins on this site.', 'seo-ultimate'));
					return;
				}
				
				break;
			case 0: //Reinstall
				$title = __('Reinstall SEO Ultimate %s', 'seo-ultimate');
				
				if (!$this->current_user_can_reinstall()) {
					wp_die(__('You do not have sufficient permissions to reinstall plugins on this site.', 'seo-ultimate'));
					return;
				}
				
				break;
			case 1: //Upgrade
				$title = __('Upgrade to SEO Ultimate %s', 'seo-ultimate');
				
				if (!$this->current_user_can_upgrade()) {
					wp_die(__('You do not have sufficient permissions to upgrade plugins on this site.', 'seo-ultimate'));
					return;
				}
				
				break;
			default:
				return;
		}
		
		$title = sprintf($title, $nv);
		$nonce = 'su-install-plugin';
		$plugin = 'seo-ultimate/seo-ultimate.php';
		$url = 'update.php?action=upgrade-plugin&plugin='.$plugin;
		
		include_once $this->plugin->plugin_dir_path.'plugin/class.su-installer.php';
		
		$upgrader = new SU_Installer( new SU_Installer_Skin( compact('title', 'nonce', 'url', 'plugin') ) );
		$upgrader->upgrade($plugin, SU_VERSION, $nv);
		
		return true;
	}
}

}
?>