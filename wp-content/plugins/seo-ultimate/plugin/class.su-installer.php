<?php
if (!defined('ABSPATH')) die();

include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

class SU_Installer extends Plugin_Upgrader {
	
	function su_strings($cv, $nv) {
		
		//Generic
		$this->strings['no_package'] = __('Package not available.', 'seo-ultimate');
		
		//Upgrade
		$this->strings['remove_old'] = __('Removing the current version of the plugin&#8230;', 'seo-ultimate');
		$this->strings['remove_old_failed'] = __('Could not remove the current version of the plugin.', 'seo-ultimate');
		
		switch (version_compare($nv, $cv)) {
			case -1: //Downgrade
				$this->strings['downloading_package'] = __('Downloading old version from <span class="code">%s</span>&#8230;', 'seo-ultimate');
				$this->strings['unpack_package'] = __('Unpacking the downgrade&#8230;', 'seo-ultimate');
				$this->strings['installing_package'] = __('Installing the downgrade&#8230;', 'seo-ultimate');
				$this->strings['process_failed'] = __('Plugin downgrade failed.', 'seo-ultimate');
				$this->strings['process_success'] = __('Plugin downgraded successfully.', 'seo-ultimate');
				break;
			case 0: //Reinstall
				$this->strings['downloading_package'] = __('Downloading from <span class="code">%s</span>&#8230;', 'seo-ultimate');
				$this->strings['unpack_package'] = __('Unpacking the reinstall&#8230;', 'seo-ultimate');
				$this->strings['installing_package'] = __('Reinstalling the current version&#8230;', 'seo-ultimate');
				$this->strings['process_failed'] = __('Plugin reinstallation failed.', 'seo-ultimate');
				$this->strings['process_success'] = __('Plugin reinstalled successfully.', 'seo-ultimate');
				break;
			case 1: //Upgrade
			default:
				$this->strings['downloading_package'] = __('Downloading upgrade from <span class="code">%s</span>&#8230;', 'seo-ultimate');
				$this->strings['unpack_package'] = __('Unpacking the upgrade&#8230;', 'seo-ultimate');
				$this->strings['installing_package'] = __('Installing the upgrade&#8230;', 'seo-ultimate');
				$this->strings['process_failed'] = __('Plugin upgrade failed.', 'seo-ultimate');
				$this->strings['process_success'] = __('Plugin upgraded successfully.', 'seo-ultimate');
				break;
		}
	}
	
	function upgrade($plugin, $cv, $nv) {
		
		$this->init();
		$this->upgrade_strings();
		$this->su_strings($cv, $nv);
		
		add_filter('upgrader_pre_install', array(&$this, 'deactivate_plugin_before_upgrade'), 10, 2);
		add_filter('upgrader_clear_destination', array(&$this, 'delete_old_plugin'), 10, 4);
		
		$this->run(array(
					'package' => "http://downloads.wordpress.org/plugin/seo-ultimate.$nv.zip",
					'destination' => WP_PLUGIN_DIR,
					'clear_destination' => true,
					'clear_working' => true,
					'hook_extra' => array(
								'plugin' => $plugin
					)
				));
		
		// Clean up our hooks, in case something else does an upgrade
		remove_filter('upgrader_pre_install', array(&$this, 'deactivate_plugin_before_upgrade'));
		remove_filter('upgrader_clear_destination', array(&$this, 'delete_old_plugin'));
		
		if ( ! $this->result || is_wp_error($this->result) )
			return $this->result;
		
		// Force refresh of plugin update information
		delete_site_transient('update_plugins');
	}
}

class SU_Installer_Skin extends Plugin_Upgrader_Skin {
	
	function header() {
		if ( $this->done_header )
			return;
		$this->done_header = true;
		echo '<div class="wrap">';
		echo screen_icon('plugins');
		echo '<h2>' . $this->options['title'] . '</h2>';
	}

}
?>