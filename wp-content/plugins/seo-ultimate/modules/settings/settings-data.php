<?php
/**
 * Settings Data Manager Module
 * 
 * @since 2.1
 */

if (class_exists('SU_Module')) {

class SU_SettingsData extends SU_Module {

	function get_parent_module() { return 'settings'; }
	function get_child_order() { return 20; }
	function is_independent_module() { return false; }
	
	function get_module_title() { return __('Settings Data Manager', 'seo-ultimate'); }
	function get_module_subtitle() { return __('Manage Settings Data', 'seo-ultimate'); }
	
	function get_admin_page_tabs() {
		return array(
			  array('title' => __('Import', 'seo-ultimate'), 'id' => 'su-import', 'callback' => 'import_tab')
			, array('title' => __('Export', 'seo-ultimate'), 'id' => 'su-export', 'callback' => 'export_tab')
			, array('title' => __('Reset', 'seo-ultimate'),  'id' => 'su-reset',  'callback' => 'reset_tab')
		);
	}
	
	function portable_options() {
		return array('settings', 'modules');
	}
	
	function init() {
		
		if ($this->is_action('su-export')) {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="SEO Ultimate Settings ('.date('Y-m-d').').dat"');
			
			$export = array();
			
			$psdata = (array)get_option('seo_ultimate', array());
			
			//Module statuses
			$export['modules'] = apply_filters('su_modules_export_array', $psdata['modules']);
			
			//Module settings
			$modules = array_keys($psdata['modules']);
			$module_settings = array();
			foreach($modules as $module) {
				if (!$this->plugin->call_module_func($module, 'get_settings_key', $key) || !$key)
					$key = $module;
				
				$msdata = (array)get_option("seo_ultimate_module_$key", array());
				if ($msdata) $module_settings[$key] = $msdata;
			}
			$export['settings'] = apply_filters('su_settings_export_array', $module_settings);
			
			//Encode
			$export = base64_encode(serialize($export));
			
			//Output
			echo $export;
			die();
			
		} elseif ($this->is_action('su-import')) {
			
			if (strlen($_FILES['settingsfile']['name'])) {
				
				$file = $_FILES['settingsfile']['tmp_name'];			
				if (is_uploaded_file($file)) {
					$import = base64_decode(file_get_contents($file));
					if (is_serialized($import)) {
						$import = unserialize($import);
						
						//Module statuses
						$psdata = (array)get_option('seo_ultimate', array());
						$psdata['modules'] = array_merge($psdata['modules'], $import['modules']);
						update_option('seo_ultimate', $psdata);
						
						//Module settings
						$module_settings = apply_filters('su_settings_import_array', $import['settings']);
						foreach ($module_settings as $key => $module_settings) {
							$msdata = (array)get_option("seo_ultimate_module_$key", array());
							$msdata = array_merge($msdata, $module_settings);
							update_option("seo_ultimate_module_$key", $msdata);							
						}
						
						$this->queue_message('success', __('Settings successfully imported.', 'seo-ultimate'));
					} else
						$this->queue_message('error', __('The uploaded file is not in the proper format. Settings could not be imported.', 'seo-ultimate'));
				} else
					$this->queue_message('error', __('The settings file could not be uploaded successfully.', 'seo-ultimate'));
					
			} else
				$this->queue_message('warning', __('Settings could not be imported because no settings file was selected. Please click the &#8220;Browse&#8221; button and select a file to import.', 'seo-ultimate'));
			
		} elseif ($this->is_action('su-reset')) {
			
			$psdata = (array)get_option('seo_ultimate', array());
			$modules = array_keys($psdata['modules']);
			foreach ($modules as $module) {
				
				if (!$this->plugin->call_module_func($module, 'get_settings_key', $key) || !$key)
					$key = $module;
				
				delete_option("seo_ultimate_module_$key");
			}
			unset($psdata['modules']);
			update_option('seo_ultimate', $psdata);
			
			$this->load_default_settings();
			
		} elseif ($this->is_action('dj-export')) {
			header('Content-Disposition: attachment; filename="Deeplink Juggernaut Content Links ('.date('Y-m-d').').csv"');
			
			$djlinks = $this->get_setting('links', array(), 'autolinks');
			$csv_headers = array(
				  'anchor' => 'Anchor'
				, 'to_type' => 'Destination Type'
				, 'to_id' => 'Destination'
				, 'title' => 'Title'
				, 'sitewide_lpa' => 'Site Cap'
				, 'nofollow' => 'Nofollow'
				, 'target' => 'Target'
			);
			if (is_array($djlinks) && count($djlinks))
				$djlinks = suarr::key_replace($djlinks, $csv_headers, true, true);
			else
				$djlinks = array(array_fill_keys($csv_headers, ''));
			
			suio::export_csv($djlinks);
			die();
			
		} elseif ($this->is_action('dj-import')) {
			
			if (strlen($_FILES['settingsfile']['name'])) {
			
				$file = $_FILES['settingsfile']['tmp_name'];			
				if (is_uploaded_file($file)) {
					$import = suio::import_csv($file);
					if ($import === false)
						$this->queue_message('error', __('The uploaded file is not in the proper format. Links could not be imported.', 'seo-ultimate'));
					else {
						$import = suarr::key_replace($import, array(
							  'Anchor' => 'anchor'
							, 'Destination Type' => 'to_type'
							, 'Destination' => 'to_id'
							, 'URL' => 'to_id'
							, 'Title' => 'title'
							, 'Site Cap' => 'sidewide_lpa'
							, 'Nofollow' => 'nofollow'
							, 'Target' => 'target'
						), true, true);
						$import = suarr::value_replace($import, array(
							  'No' => false
							, 'Yes' => true
							, 'URL' => 'url'
						), true, false);
						
						$djlinks = array();
						foreach ($import as $link) {
							
							//Validate destination type
							if ($link['to_type'] != 'url'
									&& !sustr::startswith($link['to_type'], 'posttype_')
									&& !sustr::startswith($link['to_type'], 'taxonomy_'))
								$link['to_type'] = 'url';
							
							//Validate nofollow
							if (!is_bool($link['nofollow']))
								$link['nofollow'] = false;
							
							//Validate target
							$link['target'] = ltrim($link['target'], '_');
							if (!in_array($link['target'], array('self', 'blank'))) //Only _self or _blank are supported  right now
								$link['target'] = 'self';
							
							//Add link!
							$djlinks[] = $link;
						}
						
						$this->update_setting('links', $djlinks, 'autolinks');
						
						$this->queue_message('success', __('Links successfully imported.', 'seo-ultimate'));
					}	
				} else
					$this->queue_message('error', __('The CSV file could not be uploaded successfully.', 'seo-ultimate'));
					
			} else
				$this->queue_message('warning', __('Links could not be imported because no CSV file was selected. Please click the &#8220;Browse&#8221; button and select a file to import.', 'seo-ultimate'));
			
		}
	}
	
	function import_tab() {
		$this->print_messages();
		$hook = $this->plugin->key_to_hook($this->get_module_or_parent_key());
		
		//SEO Ultimate
		$this->admin_subheader(__('Import SEO Ultimate Settings File', 'seo-ultimate'));
		echo "\n<p>";
		_e('You can use this form to upload and import an SEO Ultimate settings file stored on your computer. (These files can be created using the Export tool.) Note that importing a file will overwrite your existing settings with those in the file.', 'seo-ultimate');
		echo "</p>\n";
		echo "<form enctype='multipart/form-data' method='post' action='?page=$hook&amp;action=su-import#su-import'>\n";
		echo "\t<input name='settingsfile' type='file' /> ";
		$confirm = __('Are you sure you want to import this settings file? This will overwrite your current settings and cannot be undone.', 'seo-ultimate');
		echo "<input type='submit' class='button-primary' value='".__('Import Settings File', 'seo-ultimate')."' onclick=\"javascript:return confirm('$confirm')\" />\n";
		wp_nonce_field($this->get_nonce_handle('su-import'));
		echo "</form>\n";
		
		if ($this->plugin->module_exists('content-autolinks')) {
			//Deeplink Juggernaut
			$this->admin_subheader(__('Import Deeplink Juggernaut CSV File', 'seo-ultimate'));
			echo "\n<p>";
			_e('You can use this form to upload and import a Deeplink Juggernaut CSV file stored on your computer. (These files can be created using the Export tool.) Note that importing a file will overwrite your existing links with those in the file.', 'seo-ultimate');
			echo "</p>\n";
			echo "<form enctype='multipart/form-data' method='post' action='?page=$hook&amp;action=dj-import#su-import'>\n";
			echo "\t<input name='settingsfile' type='file' /> ";
			$confirm = __('Are you sure you want to import this CSV file? This will overwrite your current Deeplink Juggernaut links and cannot be undone.', 'seo-ultimate');
			echo "<input type='submit' class='button-primary' value='".__('Import CSV File', 'seo-ultimate')."' onclick=\"javascript:return confirm('$confirm')\" />\n";
			wp_nonce_field($this->get_nonce_handle('dj-import'));
			echo "</form>\n";
		}
		
		//Import from other plugins
		$importmodules = array();
		foreach ($this->plugin->modules as $key => $x_module) {
			$module =& $this->plugin->modules[$key];
			if (is_a($module, 'SU_ImportModule')) {
				$importmodules[$key] =& $module;
			}
		}
		
		if (count($importmodules)) {
			$this->admin_subheader(__('Import from Other Plugins', 'seo-ultimate'));
			echo "\n<p>";
			_e('You can import settings and data from these plugins. Clicking a plugin&#8217;s name will take you to the importer page, where you can customize parameters and start the import.', 'seo-ultimate');
			echo "</p>\n";
			echo "<table class='widefat'>\n";
			
			$class = '';
			foreach ($importmodules as $key => $x_module) {
				$module =& $importmodules[$key];
				$title = $module->get_op_title();
				$desc = $module->get_import_desc();
				$url = $module->get_admin_url();
				$class = ($class) ? '' : 'alternate';
				echo "\t<tr class='$class'><td><a href='$url'>$title</a></td><td>$desc</td></tr>\n";
			}
			
			echo "</table>\n";
		}
	}
	
	function export_tab() {
		//SEO Ultimate
		$this->admin_subheader(__('Export SEO Ultimate Settings File', 'seo-ultimate'));
		echo "\n<p>";
		_e('You can use this export tool to download an SEO Ultimate settings file to your computer.', 'seo-ultimate');
		echo "</p>\n<p>";
		_e('A settings file includes the data of every checkbox and textbox of every installed module. It does NOT include site-specific data like logged 404s or post/page title/meta data (this data would be included in a standard database backup, however).', 'seo-ultimate');
		echo "</p>\n<p>";
		$url = $this->get_nonce_url('su-export');
		echo "<a href='$url' class='button-primary'>".__('Download Settings File', 'seo-ultimate')."</a>";
		echo "</p>\n";
		
		if ($this->plugin->module_exists('content-autolinks')) {
			//Deeplink Juggernaut
			$this->admin_subheader(__('Export Deeplink Juggernaut CSV File', 'seo-ultimate'));
			echo "\n<p>";
			_e('You can use this export tool to download a CSV file (comma-separated values file) that contains your Deeplink Juggernaut links. Once you download this file to your computer, you can edit it using your favorite spreadsheet program. When you&#8217;re done editing, you can re-upload the file using the Import tool.', 'seo-ultimate');
			echo "</p>\n<p>";
			$url = $this->get_nonce_url('dj-export');
			echo "<a href='$url' class='button-primary'>".__('Download CSV File', 'seo-ultimate')."</a>";
			echo "</p>\n";
		}
	}
	
	function reset_tab() {
		if ($this->is_action('su-reset'))
			$this->print_message('success', __('All settings have been erased and defaults have been restored.', 'seo-ultimate'));
		echo "\n<p>";
		_e('You can erase all your SEO Ultimate settings and restore them to &#8220;factory defaults&#8221; by clicking the button below.', 'seo-ultimate');
		echo "</p>\n<p>";
		$url = $this->get_nonce_url('su-reset');
		$confirm = __('Are you sure you want to erase all module settings? This cannot be undone.', 'seo-ultimate');
		echo "<a href='$url#su-reset' class='button-primary' onclick=\"javascript:return confirm('$confirm')\">".__('Restore Default Settings', 'seo-ultimate')."</a>";
		echo "</p>\n";
	}
}

}

?>