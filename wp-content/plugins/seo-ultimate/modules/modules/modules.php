<?php
/**
 * Module Manager Module
 * 
 * @since 0.7
 */

if (class_exists('SU_Module')) {

class SU_Modules extends SU_Module {
	
	function get_module_title() { return __('Module Manager', 'seo-ultimate'); }
	function get_menu_title() { return __('Modules', 'seo-ultimate'); }
	function get_menu_pos()   { return 0; }
	function is_menu_default(){ return true; }
	
	function init() {
		
		if ($this->is_action('update')) {
			
			$psdata = (array)get_option('seo_ultimate', array());
			
			foreach ($_POST as $key => $newvalue) {
				if (substr($key, 0, 3) == 'su-') {
					$key = str_replace(array('su-', '-module-status'), '', $key);
					
					$newvalue = intval($newvalue);
					$oldvalue = $psdata['modules'][$key];
					
					if ($oldvalue != $newvalue) {
						if ($oldvalue == SU_MODULE_DISABLED)
							$this->plugin->call_module_func($key, 'activate');
						if ($newvalue == SU_MODULE_DISABLED)
							$this->plugin->call_module_func($key, 'deactivate');
					}
					
					$psdata['modules'][$key] = $newvalue;
				}
			}
			
			update_option('seo_ultimate', $psdata);
			
			wp_redirect(add_query_arg('su-modules-updated', '1', suurl::current()), 301);
			exit;
		}
	}
	
	function admin_page_contents() {
		
		if ($this->plugin->should_show_wp_ultimate_promo()) {
?>
<div id="wp-ultimate">
	<a href="http://www.wpultimatetheme.com/" target="_blank">
		<img src="<?php echo $this->plugin->plugin_dir_url; ?>plugin/images/wp-ultimate.gif" alt="<?php esc_attr_e('Like SEO Ultimate? Check out the WP Ultimate theme from SEO Design Solutions.', 'seo-ultimate'); ?>" title="" />
	</a>
</div>

<?php
		}
		
		echo '<p>';
		_e('SEO Ultimate&#8217;s features are located in groups called &#8220;modules.&#8221; By default, most of these modules are listed in the &#8220;SEO&#8221; menu on the left. Whenever you&#8217;re working with a module, you can view documentation by clicking the &#8220;Help&#8221; tab in the upper-right-hand corner of your administration screen.', 'seo-ultimate');
		echo "</p>\n<p>";
		_e('The Module Manager lets you  disable or hide modules you don&#8217;t use. You can also silence modules from displaying bubble alerts on the menu.', 'seo-ultimate');
		echo "</p>\n";
		
		if (!empty($_GET['su-modules-updated']))
			$this->print_message('success', __('Modules updated.', 'seo-ultimate'));
		
		$this->admin_form_start(false, false);
		
		$headers = array(
			  __('Status', 'seo-ultimate')
			, __('Module', 'seo-ultimate')
		);
		echo <<<STR
<table class="widefat" cellspacing="0">
	<thead><tr>
		<th scope="col" class="module-status">{$headers[0]}</th>
		<th scope="col" class="module-name">{$headers[1]}</th>
	</tr></thead>
	<tbody>

STR;
		
		$statuses = array(
			  SU_MODULE_ENABLED => __('Enabled', 'seo-ultimate')
			, SU_MODULE_SILENCED => __('Silenced', 'seo-ultimate')
			, SU_MODULE_HIDDEN => __('Hidden', 'seo-ultimate')
			, SU_MODULE_DISABLED => __('Disabled', 'seo-ultimate')
		);
		
		$modules = array();
		
		foreach ($this->plugin->modules as $key => $x_module) {
			$module =& $this->plugin->modules[$key];
			
			//On some setups, get_parent_class() returns the class name in lowercase
			if (strcasecmp(get_parent_class($module), 'SU_Module') == 0 && !in_array($key, $this->plugin->get_invincible_modules()) && $module->is_independent_module())
				$modules[$key] = $module->get_module_title();
		}
		
		foreach ($this->plugin->disabled_modules as $key => $class) {
			
			if (call_user_func(array($class, 'is_independent_module')))
				$modules[$key] = call_user_func(array($class, 'get_module_title'));
		}
		
		asort($modules);
		
		//Do we have any modules requiring the "Silenced" column? Store that boolean in $any_hmc
		$any_hmc = false;
		foreach ($modules as $key => $name) {
			if ($this->plugin->call_module_func($key, 'has_menu_count', $hmc) && $hmc) {
				$any_hmc = true;
				break;
			}
		}
		
		$psdata = (array)get_option('seo_ultimate', array());
		
		foreach ($modules as $key => $name) {
			
			$currentstatus = $psdata['modules'][$key];
			
			echo "\t\t<tr>\n\t\t\t<td class='module-status' id='module-status-$key'>\n";
			echo "\t\t\t\t<input type='hidden' name='su-$key-module-status' id='su-$key-module-status' value='$currentstatus' />\n";
			
			$hidden_is_hidden = ($this->plugin->call_module_func($key, 'get_menu_title', $module_menu_title) && $module_menu_title === false)
								|| ($this->plugin->call_module_func($key, 'is_independent_module', $is_independent_module) && $is_independent_module &&
									$this->plugin->call_module_func($key, 'get_parent_module', $parent_module) && $parent_module &&
									$this->plugin->module_exists($parent_module));
			
			foreach ($statuses as $statuscode => $statuslabel) {
				
				$hmc = ($this->plugin->call_module_func($key, 'has_menu_count', $_hmc) && $_hmc);
				
				$is_current = false;
				$style = '';
				switch ($statuscode) {
					case SU_MODULE_ENABLED:
						if (($currentstatus == SU_MODULE_SILENCED && !$hmc) ||
							($currentstatus == SU_MODULE_HIDDEN && $hidden_is_hidden))
							$is_current = true;
						break;
					case SU_MODULE_SILENCED:
						if (!$any_hmc) continue 2; //break out of switch and foreach
						if (!$hmc) $style = " style='visibility: hidden;'";
						break;
					case SU_MODULE_HIDDEN:
						if ($hidden_is_hidden)
							$style = " style='visibility: hidden;'";
						break;
				}
				
				if ($is_current || $currentstatus == $statuscode) $current = ' current'; else $current = '';
				$codeclass = str_replace('-', 'n', strval($statuscode));
				echo "\t\t\t\t\t<span class='status-$codeclass'$style>";
				echo "<a href='javascript:void(0)' onclick=\"javascript:set_module_status('$key', $statuscode, this)\" class='$current'>$statuslabel</a></span>\n";
			}
			
			if (!$this->plugin->module_exists($key) || !$this->plugin->call_module_func($key, 'get_admin_url', $admin_url))
				$admin_url = false;
			
			if ($currentstatus > SU_MODULE_DISABLED && $admin_url) {
				$cellcontent = "<a href='{$admin_url}'>$name</a>";
			} else
				$cellcontent = $name;
			
			echo <<<STR
				</td>
				<td class='module-name'>
					$cellcontent
				</td>
			</tr>

STR;
		}
		
		echo "\t</tbody>\n</table>\n";
		
		$this->admin_form_end(null, false);
	}
	
	function add_help_tabs($screen) {
		
		$screen->add_help_tab(array(
			  'id' => 'su-modules-options'
			, 'title' => __('Options Help', 'seo-ultimate')
			, 'content' => __("
<p>SEO Ultimate&#8217;s features are located in groups called &#8220;modules.&#8221; By default, most of these modules are listed in the &#8220;SEO&#8221; menu on the left.</p>
<p>The Module Manager lets you customize the visibility and accessibility of each module; here are the options available:</p>
<ul>
	<li><strong>Enabled</strong> &mdash; The default option. The module will be fully enabled and accessible.</li>
	<li><strong>Silenced</strong> &mdash; The module will be enabled and accessible, but it won't be allowed to display numeric bubble alerts on the menu.</li>
	<li><strong>Hidden</strong> &mdash; The module's functionality will be enabled, but the module won't be visible on the SEO menu. You will still be able to access the module's admin page by clicking on its title in the Module Manager table.</li>
	<li><strong>Disabled</strong> &mdash; The module will be completely disabled and inaccessible.</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-modules-faq'
			, 'title' => __('FAQ', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>What are modules?</strong><br />SEO Ultimate&#8217;s features are divided into groups called &#8220;modules.&#8221; SEO Ultimate&#8217;s &#8220;Module Manager&#8221; lets you enable or disable each of these groups of features. This way, you can pick-and-choose which SEO Ultimate features you want.</li>
	<li><strong>Can I access a module again after I&#8217;ve hidden it?</strong><br />Yes. Just go to the Module Manager and click the module&#8217;s title to open its admin page. If you&#8217;d like to put the module back in the &#8220;SEO&#8221; menu, just re-enable the module in the Module Manager and click &#8220;Save Changes.&#8221;</li>
	<li><strong>How do I disable the number bubbles on the &#8220;SEO&#8221; menu?</strong><br />Just go to the Module Manager and select the &#8220;Silenced&#8221; option for any modules generating number bubbles. Then click &#8220;Save Changes.&#8221;</li>
</ul>
", 'seo-ultimate')));
	}
}

}
?>