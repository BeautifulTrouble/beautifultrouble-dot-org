<?php
/**
 * The main class. Provides plugin-level functionality.
 * 
 * @since 0.1
 */
class SEO_Ultimate {
	
	/********** VARIABLES **********/
	
	/**
	 * Stores all module class instances.
	 * 
	 * @since 0.1
	 * @var array
	 */
	var $modules = array();
	
	/**
	 * Stores the names of disabled modules.
	 * 
	 * @since 0.1
	 * @var array
	 */
	var $disabled_modules = array();
	
	/**
	 * The key of the module whose admin page appears when the "SEO" menu item is clicked.
	 * 
	 * @since 1.5
	 * @var string
	 */
	var $default_menu_module = 'modules';
	
	/**
	 * The server path of the main plugin file.
	 * Example: /home/user/public_html/wp-content/plugins/seo-ultimate/seo-ultimate.php
	 * 
	 * @since 0.1
	 * @var string
	 */
	var $plugin_file_path;
	
	/**
	 * The public URL of the main plugin file.
	 * Example: http://www.example.com/wp-content/plugins/seo-ultimate/seo-ultimate.php
	 * 
	 * @since 0.1
	 * @var string
	 */
	var $plugin_file_url;
	
	/**
	 * The server path of the directory where this plugin is located, with trailing slash.
	 * Example: /home/user/public_html/wp-content/plugins/seo-ultimate/
	 * 
	 * @since 0.1
	 * @var string
	 */
	var $plugin_dir_path;
	
	/**
	 * The public URL of the directory where this plugin is located, with trailing slash.
	 * Example: http://www.example.com/wp-content/plugins/seo-ultimate/
	 * 
	 * @since 0.1
	 * @var string
	 */
	var $plugin_dir_url;
	
	/**
	 * The plugin file relative to the /wp-content/plugins/ directory.
	 * Should be "seo-ultimate/seo-ultimate.php"
	 * 
	 * @since 2.1
	 */
	var $plugin_basename = '';
	
	/**
	 * The array to be inserted into the hits table.
	 * 
	 * @since 0.9
	 * @var array
	 */
	var $hit = array();
	
	/**
	 * The name of the function/mechanism that triggered the current redirect.
	 * 
	 * @since 0.3
	 * @var string
	 */
	var $hit_redirect_trigger = '';
	
	/********** CLASS CONSTRUCTORS **********/
	
	/**
	 * Fills in class variables, loads modules, and hooks into WordPress.
	 * PHP5-style constructor.
	 * 
	 * @since 0.1
	 * @uses save_hit() Hooked into WordPress's "shutdown" action.
	 * @uses load_plugin_data()
	 * @uses SU_VERSION
	 * @uses install()
	 * @uses upgrade()
	 * @uses load_modules()
	 * @uses activate() Registered with WordPress as the activation hook.
	 * @uses init() Hooked into WordPress's "init" action.
	 * @uses add_menus() Hooked into WordPress's "admin_menu" action.
	 * @uses admin_includes() Hooked into WordPress's "admin_head" action.
	 * @uses plugin_page_notices() Hooked into WordPress's "admin_head" action.
	 * @uses admin_help() Hooked into WordPress's "contextual_help" filter.
	 * @uses add_postmeta_box() Hooked into WordPress's "admin_menu" action.
	 * @uses save_postmeta_box() Hooked into WordPress's "save_post" action.
	 * @uses plugin_update_info() Hooked into the "in_plugin_update_message-seo-ultimate/seo-ultimate.php" action.
	 * @uses log_redirect_canonical() Hooked into WordPress's "redirect_canonical" filter.
	 * @uses log_redirect() Hooked into WordPress's "wp_redirect" filter.
	 * @uses log_hit() Hooked into WordPress's "status_header" filter.
	 */
	function __construct($plugin_file) {
		
		//Upgrade
		$this->upgrade_to_08();
		$this->upgrade_to_40();
		$this->upgrade_to_50();
		
		//Save hit data
		add_action('shutdown', array(&$this, 'save_hit'));
		
		/********** CLASS CONSTRUCTION **********/
		
		//Load data about the plugin file itself into the class
		$this->load_plugin_data($plugin_file);
		
		
		/********** VERSION CHECKING **********/
		
		$psdata = get_option('seo_ultimate', array());
		if (!is_array($psdata)) $psdata = array();
		
		//Get the current version
		$version = SU_VERSION;
		
		//Get the version when the plugin last ran.
		if (isset($psdata['version']))
			$oldversion = $psdata['version'];
		else
			$oldversion = get_option('su_version', false);
		
		//Or, if this is the first time the plugin is running, then install()			
		if ($oldversion) {
			
			//If $oldversion is less than $version, then upgrade()
			if (version_compare($version, $oldversion) == 1)
				$this->upgrade($oldversion);
		} else {
			$this->install();
		}
		
		//Store the current version in the database.
		$psdata['version'] = $version;
		if ($oldversion != $version) update_option('seo_ultimate', $psdata);
		
		/********** INITIALIZATION **********/
		
		//Load plugin modules. Must be called *after* load_plugin_data()
		$this->load_modules();
		
		
		/********** PLUGIN EVENT HOOKS **********/
		
		//If we're activating the plugin, then call the activation function
		register_activation_hook($this->plugin_file_path, array(&$this, 'activate'));
		
		//If we're deactivating the plugin, then call the deactivation function
		register_deactivation_hook($this->plugin_file_path, array(&$this, 'deactivate'));
		
		
		/********** ACTION & FILTER HOOKS **********/
		
		//Initializes modules at WordPress initialization
		add_action('init', array(&$this, 'load_textdomain'), 0); //Run before widgets_init hook (wp-includes/default-widgets.php)
		add_action('init', array(&$this, 'init'));
		
		//Hook to output all <head> code
		add_action('wp_head', array(&$this, 'template_head'), 1);
		
		//Log this visitor!
		if ($this->get_setting('log_hits', true, 'settings')) {
			add_filter('redirect_canonical', array(&$this, 'log_redirect_canonical'));
			add_filter('wp_redirect', array(&$this, 'log_redirect'), 10, 2);
			add_filter('status_header', array(&$this, 'log_hit'), 10, 2);
		}
		
		//Admin-only hooks
		if (is_admin()) {
			
			//Hook to include JavaScript and CSS
			add_action('admin_enqueue_scripts', array(&$this, 'admin_includes'));
			
			//Hook to add plugin notice actions
			add_action('admin_head', array(&$this, 'plugin_page_notices'));
			
			//Hook to remove other plugins' notices from our admin pages
			add_action('admin_head', array(&$this, 'remove_admin_notices'));
			
			if (!get_option('blog_public')) {
				//Add admin-wide notice
				add_action('admin_notices', array(&$this, 'private_blog_admin_notice'));
			}
			
			add_action('admin_init', array(&$this, 'admin_init'));
			
			//When loading the admin menu, call on our menu constructor function.
			//For future-proofing purposes, we explicitly state the default priority of 10,
			//since some modules set a priority of 9 with the specific intention of running
			//before this main plugin's hook.
			add_action('admin_menu', array(&$this, 'add_blog_admin_menus'), 10);
			add_action('network_admin_menu', array(&$this, 'add_network_admin_menus'), 10);
			
			//Hook to customize contextual help
			add_action('admin_head', array(&$this, 'admin_help'), 11);
			
			//Postmeta box hooks
			add_action('admin_menu', array(&$this, 'add_postmeta_box'));
			add_action('save_post',  array(&$this, 'save_postmeta_box'), 10, 2);
			
			//Display info on new versions
			add_action("in_plugin_update_message-{$this->plugin_basename}", array(&$this, 'plugin_update_info'), 10, 2);
			add_filter('transient_update_plugins', array(&$this, 'add_plugin_upgrade_notice'));
			
			//Add plugin action links
			add_filter("plugin_action_links_{$this->plugin_basename}", array(&$this, 'plugin_action_links'));
			add_filter("network_admin_plugin_action_links_{$this->plugin_basename}", array(&$this, 'plugin_action_links'));
			
			//Add module links to plugin listing
			add_filter('plugin_row_meta', array(&$this, 'plugin_row_meta_filter'), 10, 2);
			
			//JLSuggest AJAX
			add_action('wp_ajax_su-jlsuggest-autocomplete', array(&$this, 'jlsuggest_autocomplete'));
		}
	}
	
	/**
	 * PHP4 constructor that redirects to the PHP5 constructor.
	 * 
	 * @since 0.1
	 * @uses __construct()
	 */
	function SEO_Ultimate($plugin_file) {
	
		$this->__construct($plugin_file);
	}
	
	
	/********** PLUGIN EVENT FUNCTIONS **********/
	
	/**
	 * This will be called if the plugin is being run for the first time.
	 * 
	 * @since 0.1
	 */
	function install() { }
	
	/**
	 * This will be called if the plugin's version has increased since the last run.
	 * 
	 * @since 0.1
	 * 
	 * @param string $oldversion The version that was last installed.
	 */
	function upgrade($oldversion) {
		define('SU_UPGRADE', true);
	}
	
	/**
	 * Upgrades SEO Ultimate to version 0.8.
	 * 
	 * @since 0.8
	 */
	function upgrade_to_08() {
		$psdata = (array)get_option('seo_ultimate', array());
		
		$options = array('cron', 'modules', 'settings', 'version');
		$save = false;
		foreach ($options as $option) {
			if (($value = get_option("su_$option", false)) !== false) {
				
				if ('settings' == $option) {
					foreach ((array)$value as $module => $module_data)
						update_option("seo_ultimate_module_$module", $module_data);
				} else {
					$psdata[$option] = $value;
					$save = true;
				}
				
				delete_option("su_$option");
			}
		}
		if ($save) update_option('seo_ultimate', $psdata);
	}
	
	/**
	 * Upgrades SEO Ultimate to version 4.0.
	 * 
	 * @since 4.0
	 */
	function upgrade_to_40() {
		$this->copy_module_states(array('meta' => array('meta-descriptions', 'meta-keywords', 'webmaster-verify'), 'noindex' => 'meta-robots'));
	}
	
	/**
	 * Upgrades SEO Ultimate to version 5.0.
	 * 
	 * @since 5.0
	 */
	function upgrade_to_50() {
		$psdata = (array)get_option('seo_ultimate', array());
		
		if ($psdata && isset($psdata['settings']) && is_array($psdata['settings'])) {
			
			foreach ($psdata['settings'] as $module => $module_data)
				update_option("seo_ultimate_module_$module", $module_data);
			
			unset($psdata['settings']);
			update_option('seo_ultimate', $psdata);
		}
	}
	
	/**
	 * Copies the enabled/disabled/etc. states from one module to others.
	 * 
	 * @since 4.0
	 * 
	 * @param array $copy
	 */
	function copy_module_states($copy) {
		$psdata = (array)get_option('seo_ultimate', array());
		$save = false;
		foreach ($copy as $from => $tos)
			if (isset($psdata['modules'][$from]))
				foreach ((array)$tos as $to)
					if (!isset($psdata['modules'][$to])) {
						$psdata['modules'][$to] = $psdata['modules'][$from];
						$save = true;
					}
		if ($save) update_option('seo_ultimate', array());
	}
	
	/**
	 * WordPress will call this when the plugin is activated, as instructed by the register_activation_hook() call in {@link __construct()}.
	 * 
	 * @since 0.1
	 */
	function activate() {		
		foreach ($this->modules as $key => $module) {
			$this->modules[$key]->activate();
		}
	}
	
	/**
	 * WordPress will call this when the plugin is deactivated, as instructed by the register_deactivation_hook() call in {@link __construct()}.
	 * 
	 * @since 0.1
	 */
	function deactivate() {
		
		//Let modules run deactivation tasks
		foreach ($this->modules as $key => $module) {
			$this->modules[$key]->deactivate();
		}
		
		//Unschedule all cron jobs		
		$this->remove_cron_jobs(true);
		
		//Delete all cron job records, since the jobs no longer exist
		$psdata = (array)get_option('seo_ultimate', array());
		unset($psdata['cron']);
		update_option('seo_ultimate', $psdata);
	}
	
	/**
	 * Calls module deactivation/uninstallation functions and deletes all database data.
	 * 
	 * @since 0.1
	 */
	function uninstall() {
		
		//Deactivate modules and cron jobs
		$this->deactivate();
		
		//Let modules run uninstallation tasks
		do_action('su_uninstall');
		
		//Delete module data
		$psdata = (array)get_option('seo_ultimate', array());
		if (!empty($psdata['modules'])) {
			$module_keys = array_keys($psdata['modules']);
			foreach ($module_keys as $module)
				delete_option("seo_ultimate_module_$module");
		}
		
		//Delete plugin data
		delete_option('seo_ultimate');
	}
	
	
	/********** INITIALIZATION FUNCTIONS **********/
	
	/**
	 * Fills class variables with information about where the plugin is located.
	 * 
	 * @since 0.1
	 * @uses $plugin_file_path
	 * @uses $plugin_file_url
	 * @uses $plugin_dir_path
	 * @uses $plugin_dir_url
	 * 
	 * @param string $plugin_path The path to the "official" plugin file.
	 */
	function load_plugin_data($plugin_path) {
		
		//Load plugin path/URL information
		$this->plugin_basename  = plugin_basename($plugin_path);
		$this->plugin_dir_path  = trailingslashit(dirname(trailingslashit(WP_PLUGIN_DIR).$this->plugin_basename));
		$this->plugin_file_path = trailingslashit(WP_PLUGIN_DIR).$this->plugin_basename;
		$this->plugin_dir_url   = trailingslashit(plugins_url(dirname($this->plugin_basename)));
		$this->plugin_file_url  = trailingslashit(plugins_url($this->plugin_basename));
	}
	
	/**
	 * Finds and loads all modules. Runs the activation functions of newly-uploaded modules.
	 * Updates the modules list and saves it in the database. Removes the cron jobs of deleted modules.
	 * 
	 * SEO Ultimate uses a modular system that allows functionality to be added and removed on-the-fly.
	 * 
	 * @since 0.1
	 * @uses $plugin_dir_path
	 * @uses $modules Stores module classes in this array.
	 * @uses $disabled_modules
	 * @uses module_sort_callback() Passes this function to uasort() to sort the $modules array.
	 * @uses SU_MODULE_ENABLED
	 * @uses SU_MODULE_DISABLED
	 * @uses remove_cron_jobs()
	 */
	function load_modules() {
		
		$this->disabled_modules = array();
		$this->modules = array();
		
		$psdata = (array)get_option('seo_ultimate', array());
		
		//The plugin_dir_path variable must be set before calling this function!
		if (!$this->plugin_dir_path) return false;
		
		//If no modules list is found, then create a new, empty list.
		if (!isset($psdata['modules']))
			$psdata['modules'] = array();
		
		//Get the modules list from last time the plugin was loaded.
		$oldmodules = $psdata['modules'];
		
		//The modules are in the "modules" subdirectory of the plugin folder.
		$dirpath = $this->plugin_dir_path.'modules';
		$dir = opendir($dirpath);
		
		//This loop will be repeated as long as there are more folders to inspect
		while ($folder = readdir($dir)) {
			
			//If the item is a folder...
			if (suio::is_dir($folder, $dirpath)) {
				
				//Open the subfolder
				$subdirpath = $dirpath.'/'.$folder;
				$subdir = opendir($subdirpath);
				
				//Scan the files in the subfolder (seo-ultimate/modules/???/*)
				while ($file = readdir($subdir)) {
					
					//Modules are non-directory files with the .php extension
					//We need to exclude index.php or else we'll get 403s galore
					if (suio::is_file($file, $subdirpath, 'php') && $file != 'index.php') {
						
						$filepath = $subdirpath.'/'.$file;
						
						//Figure out the module's array key and class name
						$module = strval(strtolower(substr($file, 0, -4)));
						$class = 'SU_'.str_replace(' ', '', ucwords(str_replace('-', ' ', $module)));
						
						//Load the module's code
						include_once $filepath;
						
						//If this is actually a module...
						if (class_exists($class)) {
							
							if (	   ($module_parent = call_user_func(array($class, 'get_parent_module')))
									&& !call_user_func(array($class, 'is_independent_module'))
								)
								$module_disabled = (isset($oldmodules[$module_parent]) && $oldmodules[$module_parent] == SU_MODULE_DISABLED);
							else
								$module_disabled = (isset($oldmodules[$module]) && $oldmodules[$module] == SU_MODULE_DISABLED);
							
							if (!isset($oldmodules[$module]) && call_user_func(array($class, 'get_default_status')) == SU_MODULE_DISABLED)
								$module_disabled = true;
							
							if (in_array($module, $this->get_invincible_modules())) {
								$module_disabled = false;
								$oldmodules[$module] = SU_MODULE_ENABLED;
							}
							
							//If this module is disabled...
							if ($module_disabled) {
								
								$this->disabled_modules[$module] = $class;
								
							} else {
								
								//Create an instance of the module's class and store it in the array
								$this->modules[$module] = new $class;
								
								//We must tell the module what its key is so that it can save settings
								$this->modules[$module]->module_key = $module;
								
								//Tell the module what its URLs are
								$this->modules[$module]->module_dir_rel_url = $mdirrelurl = "modules/$folder/";
								$this->modules[$module]->module_rel_url = $mdirrelurl . $file;
								$this->modules[$module]->module_dir_url = $mdirurl = $this->plugin_dir_url . $mdirrelurl;
								$this->modules[$module]->module_url		= $mdirurl . $file;
								
								/*
								//Is this module the default menu module?
								if ($this->modules[$module]->get_menu_parent() === 'seo' && $this->modules[$module]->is_menu_default())
									$this->default_menu_module = $module;
								*/
								
								//Give the module this plugin's object by reference
								$this->modules[$module]->plugin =& $this;
								
								//Call post-construction function
								$this->modules[$module]->load();
							}
						} //If this isn't a module, then the file will simply be included as-is
					}
				}
			}
		}
		
		//If the loop above found modules, then sort them with our special sorting function
		//so they appear on the admin menu in the right order
		if (count($this->modules) > 0)
			uasort($this->modules, array(&$this, 'module_sort_callback'));
		
		//Now we'll compare the current module set with the one from last time.
		
		//Construct the new modules list that'll go in the database.
		//This code block will add/activate new modules, keep existing ones, and remove (i.e. not add) deleted ones.
		foreach ($this->modules as $key => $module) {
			if (isset($oldmodules[$key])) {
				$newmodules[$key] = $oldmodules[$key];
			} else {
				$this->modules[$key]->activate();
				$newmodules[$key] = $this->modules[$key]->get_default_status();
			}
		}
		
		foreach ($this->modules as $key => $module) {
			if (($module_parent = $this->modules[$key]->get_parent_module()) && !$this->modules[$key]->is_independent_module())
				$newmodules[$key] = $newmodules[$module_parent];
		}
		
		//Register disabled modules as such
		foreach ($this->disabled_modules as $key => $name) {
			$newmodules[$key] = SU_MODULE_DISABLED;
		}
		
		//Save the new modules list
		$psdata['modules'] = $newmodules;
		if ($newmodules != $oldmodules) update_option('seo_ultimate', $psdata);
		
		//Remove the cron jobs of deleted modules
		$this->remove_cron_jobs();
		
		//Tell the modules what their plugin page hooks are
		foreach ($this->modules as $key => $module) {
			$menu_parent_hook = $this->modules[$key]->get_menu_parent_hook();
			
			if ($this->modules[$key]->is_menu_default())
				$this->modules[$key]->plugin_page_hook = $plugin_page_hook = "toplevel_page_$menu_parent_hook";
			elseif ('options-general.php' == $menu_parent_hook)
				$this->modules[$key]->plugin_page_hook = $plugin_page_hook = 'settings_page_' .
					$this->key_to_hook($this->modules[$key]->get_module_or_parent_key());
			else
				$this->modules[$key]->plugin_page_hook = $plugin_page_hook = $menu_parent_hook . '_page_' .
					$this->key_to_hook($this->modules[$key]->get_module_or_parent_key());
			
			add_action("load-$plugin_page_hook", array($this->modules[$key], 'load_hook'));
		}
		
		if (!$this->module_exists($this->default_menu_module)) {
			foreach ($this->modules as $key => $module) {
				if ($this->modules[$key]->get_menu_parent() === 'seo' && $this->modules[$key]->get_parent_module() == false) {
					$this->default_menu_module = $key;
					break;
				}
			}
		}
	}
	
	/**
	 * Runs during WordPress's init action.
	 * Loads the textdomain and calls modules' initialization functions.
	 * 
	 * @since 0.1
	 * @uses $plugin_file_path
	 * @uses SU_Module::load_default_settings()
	 * @uses SU_Module::init()
	 */
	function init() {
		
		//Load default module settings and run modules' init tasks
		foreach ($this->modules as $key => $module) {
			//Accessing $module directly causes problems when the modules use the &$this reference
			$this->modules[$key]->load_default_settings();
			$this->modules[$key]->load_child_modules();
		}
		
		//Only run init tasks after all other init functions are completed for all modules
		foreach ($this->modules as $key => $module) {
			if (count($this->modules[$key]->get_children_admin_page_tabs()))
				$this->modules[$key]->admin_page_tabs_init();
			if (defined('SU_UPGRADE'))
				$this->modules[$key]->upgrade();
			$this->modules[$key]->init();
		}
		
		global $pagenow;
		if ('post.php' == $pagenow || 'post-new.php' == $pagenow) {
			add_action('admin_enqueue_scripts', array(&$this, 'postmeta_box_tabs_init'));
		}
	}
	
	/**
	 * @since 6.9.7
	 */
	function load_textdomain() {
		load_plugin_textdomain('seo-ultimate', '', trailingslashit(plugin_basename($this->plugin_dir_path)) . 'translations');
	}
	
	/**
	 * Attached to WordPress' admin_init hook.
	 * Calls the admin_page_init() function of the current module(s).
	 * 
	 * @since 6.0
	 * @uses $modules
	 * @uses SU_Module::is_module_admin_page()
	 * @uses SU_Module::admin_page_init()
	 */
	function admin_init() {
		global $pagenow;
		
		foreach ($this->modules as $key => $x_module) {
			if ('post.php' == $pagenow || 'post-new.php' == $pagenow)
				$this->modules[$key]->editor_init();
			elseif ($this->modules[$key]->is_module_admin_page())
				$this->modules[$key]->admin_page_init();
		}
	}
	
	/********** MODULE FUNCTIONS **********/
	
	/**
	 * @since 7.2.5
	 */
	function get_invincible_modules() {
		$ims = array('modules');
		
		if ( ! function_exists( 'is_plugin_active_for_network' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		
		if (is_multisite() && is_plugin_active_for_network($this->plugin_basename))
			$ims[] = 'settings';
		
		return $ims;
	}
	
	/********** SETTINGS FUNCTIONS **********/
	
	/**
	 * Gets the value of a module setting.
	 * 
	 * @since 1.0
	 * @uses $modules
	 * @uses SU_Module::get_setting()
	 * 
	 * @param string $key The name of the setting to retrieve.
	 * @param mixed $default What should be returned if the setting does not exist. Optional.
	 * @param string|null $module The module to which the setting belongs. Defaults to the current module. Optional.
	 * @return mixed The value of the setting, or the $default variable.
	 */
	function get_setting($key, $default, $module) {
		if (isset($this->modules[$module]))
			return $this->modules[$module]->get_setting($key, $default);
		else
			return $default;
	}

	/********** LOGGING FUNCTIONS **********/
	
	/**
	 * Saves the hit data to the database if so instructed by a module.
	 * 
	 * @since 0.9
	 * @uses $hit
	 */
	function save_hit() {
		
		if (!empty($this->hit) && $this->get_setting('log_hits', true, 'settings'))
			do_action('su_save_hit', $this->hit);
	}
	
	/**
	 * Saves information about the current hit into an array, which is later saved to the database.
	 * 
	 * @since 0.1
	 * @uses get_current_url()
	 * @uses $hit_id
	 * 
	 * @param string $status_header The full HTTP status header. Unused and returned as-is.
	 * @param int $status_code The numeric HTTP status code.
	 * @param string $redirect_url The URL to which the visitor is being redirected. Optional.
	 * @return string Returns the $status_header variable unchanged.
	 */
	function log_hit($status_header, $status_code, $redirect_url = '') {
		
		//Only log hits from non-logged-in users
		if (!is_user_logged_in()) {
			
			//Get the current URL
			$url = suurl::current();
			
			//Put it all into an array
			$data = array(
				  'time' => time()
				, 'ip_address' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''
				, 'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
				, 'url' => $url
				, 'redirect_url' => $redirect_url
				, 'redirect_trigger' => $this->hit_redirect_trigger
				, 'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''
				, 'status_code' => $status_code
			);
			
			//We don't want to overwrite a redirect URL if it's already been logged
			if (!empty($this->hit['redirect_url']))
				$data['redirect_url'] = $this->hit['redirect_url'];
			
			//Put the hit data into our variable.
			//We'll save it to the database later, since the hit data may change as we gather further information
			//(e.g. when the redirect URL is discovered).
			$this->hit = $data;
		}
		
		//This function can be used as a WordPress filter, so we return the needed variable.
		return $status_header;
	}
	
	/**
	 * A wp_redirect WordPress filter that logs the URL to which the visitor is being redirected.
	 * 
	 * @since 0.2
	 * @uses log_hit()
	 * 
	 * @param string $redirect_url The URL to which the visitor is being redirected.
	 * @param int $status_code The numeric HTTP status code.
	 * @return string The unchanged $redirect_url parameter.
	 */
	function log_redirect($redirect_url, $status_code) {
		if (empty($this->hit_redirect_trigger)) $this->hit_redirect_trigger = 'wp_redirect';
		$this->log_hit(null, $status_code, $redirect_url); //We call log_hit() again so we can pass along the redirect URL
		return $redirect_url;
	}
	
	/**
	 * A redirect_canonical WordPress filter that logs the fact that a canonical redirect is being issued.
	 * 
	 * @since 0.3
	 * @uses log_hit()
	 * 
	 * @param string $redirect_url The URL to which the visitor is being redirected.
	 * @return string The unchanged $redirect_url parameter.
	 */
	function log_redirect_canonical($redirect_url) {
		if (empty($this->hit_redirect_trigger)) $this->hit_redirect_trigger = 'redirect_canonical';
		return $redirect_url;
	}
	
	
	/********** ADMIN MENU FUNCTIONS **********/
	
	/**
	 * Constructs the "SEO" menu and its subitems.
	 * 
	 * @since 0.1
	 * @uses $modules
	 * @uses get_module_count_code()
	 * @uses SU_Module::get_menu_count()
	 * @uses SU_Module::get_menu_pos()
	 * @uses SU_Module::get_menu_title()
	 * @uses SU_Module::get_page_title()
	 * @uses key_to_hook()
	 */
	function add_menus($admin_scope = 'blog') {
		
		$psdata = (array)get_option('seo_ultimate', array());
		
		//If subitems have numeric bubbles, then add them up and show the total by the main menu item
		$count = 0;
		foreach ($this->modules as $key => $module) {
			if (	(empty($psdata['modules']) || $psdata['modules'][$key] > SU_MODULE_SILENCED)
					&& $module->get_menu_count() > 0
					&& $module->get_menu_parent() == 'seo'
					&& $module->is_independent_module()
					&& $module->belongs_in_admin($admin_scope)
					)
				$count += $module->get_menu_count();
		}
		$main_count_code = $this->get_menu_count_code($count);
		
		$added_main_menu = false;
		
		//Add all the subitems
		foreach ($this->modules as $key => $x_module) {
			$module =& $this->modules[$key];
			
			//Show a module on the menu only if it provides a menu title, it belongs in the current admin scope (blog/network/user), and it doesn't have an enabled parent module
			if ($module->get_menu_title()
					&& $module->belongs_in_admin($admin_scope)
					&& (!$module->get_parent_module() || !$this->module_exists($module->get_parent_module()))
					) {
				
				//If the module is hidden, put the module under a non-existent menu parent
				//(this will let the module's admin page be loaded, but it won't show up on the menu)
				if (empty($psdata['modules']) || $psdata['modules'][$key] > SU_MODULE_HIDDEN)
					$parent = $module->get_menu_parent();
				else
					$parent = 'su-hidden-modules';
				
				if (empty($psdata['modules']) || $psdata['modules'][$key] > SU_MODULE_SILENCED)
					$count_code = $this->get_menu_count_code($module->get_menu_count());
				else
					$count_code = '';
				
				$hook = $this->key_to_hook($key);
				
				if ($parent == 'seo' && !$added_main_menu) {
					//Add the "SEO" menu item!
					add_utility_page(__('SEO Ultimate', 'seo-ultimate'), __('SEO', 'seo-ultimate').$main_count_code, 'manage_options', 'seo', array(), 'div');
					
					//Translations and count codes will mess up the admin page hook, so we need to fix it manually.
					global $admin_page_hooks;
					$admin_page_hooks['seo'] = 'seo';
					
					$added_main_menu = true;
				}
				
				add_submenu_page($parent, $module->get_page_title(), $module->get_menu_title().$count_code,
					'manage_options', $hook, array($module, 'admin_page'));
				
				//Support for the "Ozh' Admin Drop Down Menu" plugin
				add_filter("ozh_adminmenu_icon_$hook", array(&$this, 'get_admin_menu_icon_url'));
			}
		}
	}
	
	/**
	 * @since 7.2.5
	 */
	function add_blog_admin_menus() {
		$this->add_menus('blog');
	}
	
	/**
	 * @since 7.2.5
	 */
	function add_network_admin_menus() {
		$this->add_menus('network');
	}
	
	/**
	 * Compares two modules to determine which of the two should be displayed first on the menu.
	 * Sorts by menu position first, and title second.
	 * Works as a uasort() callback.
	 * 
	 * @since 0.1
	 * @uses SU_Module::get_menu_pos()
	 * @uses SU_Module::get_menu_title()
	 * 
	 * @param SU_Module $a The first module to compare.
	 * @param SU_Module $b The second module to compare.
	 * @return int This will be -1 if $a comes first, or 1 if $b comes first.
	 */
	function module_sort_callback($a, $b) {
		if ($a->get_menu_pos() == $b->get_menu_pos()) {
			return strcmp($a->get_menu_title(), $b->get_menu_title());
		}
		
		return ($a->get_menu_pos() < $b->get_menu_pos()) ? -1 : 1;
	}
	
	/**
	 * If the bubble alert count parameter is greater than zero, then returns the HTML code for a numeric bubble to display next to a menu item.
	 * Otherwise, returns an empty string.
	 * 
	 * @since 0.1
	 * 
	 * @param int $count The number that should appear in the bubble.
	 * @return string The string that should be added to the end of the menu item title.
	 */
	function get_menu_count_code($count) {
	
		//If we have alerts that need a bubble, then return the bubble HTML.
		if ($count > 0)
			return "<span class='update-plugins count-$count'><span class='plugin-count'>".number_format_i18n($count)."</span></span>";
		else
			return '';
	}
	
	/**
	 * Converts a module key to a menu hook.
	 * (Makes the "Module Manager" module load when the "SEO" parent item is clicked.)
	 * 
	 * @since 0.1
	 * 
	 * @param string $key The module key.
	 * @return string The menu hook.
	 */
	function key_to_hook($key) {
		switch ($key) {
			case $this->default_menu_module: return 'seo'; break;
			case 'settings': return 'seo-ultimate'; break;
			default: return "su-$key"; break;
		}
	}
	
	/**
	 * Converts a menu hook to a module key.
	 * (If the "SEO" parent item is clicked, then the Module Manager is being shown.)
	 * 
	 * @since 0.1
	 * 
	 * @param string $hook The menu hook.
	 * @return string The module key.
	 */
	function hook_to_key($hook) {
		switch ($hook) {
			case 'seo': return $this->default_menu_module; break;
			case 'seo-ultimate': return 'settings'; break;
			default: return substr($hook, 3); break;
		}
	}
	
	/**
	 * Returns the icon for one of the plugin's admin menu items.
	 * Used to provide support for the Ozh' Admin Drop Down Menu plugin.
	 * 
	 * @since 1.0
	 * 
	 * @param string $hook The menu item for which an icon is needed.
	 * @return string The absolute URL of the menu icon.
	 */
	function get_admin_menu_icon_url($hook) {
		$key = $this->hook_to_key($hook);
		if (isset($this->modules[$key])) {
			if (strlen($image = $this->modules[$key]->get_menu_icon_filename()))
				return $this->modules[$key]->module_dir_url.$image;
		}
		
		return $hook;
	}
	
	
	/********** OTHER ADMIN FUNCTIONS **********/
	
	/**
	 * Returns a boolean indicating whether the user is currently viewing an admin page generated by this plugin.
	 * 
	 * @since 0.1
	 * 
	 * @return bool Whether the user is currently viewing an admin page generated by this plugin.
	 */
	function is_plugin_admin_page() {
		if (is_admin()) {
			global $plugin_page;
			
			foreach ($this->modules as $key => $module) {
				if (strcmp($plugin_page, $this->key_to_hook($key)) == 0) return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Includes the plugin's CSS and JavaScript in the header.
	 * Also includes a module's CSS/JavaScript on its administration page.
	 * 
	 * @todo Link to global plugin includes only when on plugin pages.
	 * 
	 * @since 0.1
	 * @uses $modules
	 * @uses $plugin_file_url
	 * @uses $plugin_dir_url
	 * @uses hook_to_key()
	 */
	function admin_includes() {
		
		//Global CSS/JS
		$this->queue_css('plugin', 'global');
		$this->queue_js ('plugin', 'global');
		
		//Figure out what plugin admin page we're on
		global $plugin_page;
		$pp = $this->hook_to_key($plugin_page);
		
		if (strlen($pp)) {
			$outputted_module_files = false;
			
			foreach ($this->modules as $key => $module) {
				
				//Does the current admin page belong to this module?
				if (strcmp($key, $pp) == 0)
					//Output AJAX page var fix
					echo "\n<script type='text/javascript'>pagenow = '".su_esc_attr($module->plugin_page_hook)."';</script>\n";
				
				//Does the current admin page belong to this module or its parent?
				if (strcmp($key, $pp) == 0 || strcmp($module->get_parent_module(), $pp) == 0) {
					
					//We're viewing a module page, so print links to the CSS/JavaScript files loaded for all modules
					if (!$outputted_module_files) {
						$this->queue_css('modules', 'modules');
						$this->queue_js ('modules', 'modules', array('jquery'), array(
							'unloadConfirmMessage' => __("It looks like you made changes to the settings of this SEO Ultimate module. If you leave before saving, those changes will be lost.", 'seo-ultimate')
						));
						$outputted_module_files = true;
					}
					
					//Print links to the module's CSS and JavaScript.
					$this->queue_css($module->module_dir_rel_url, $module->get_module_key());
					$this->queue_js ($module->module_dir_rel_url, $module->get_module_key());
					
					//Queue up the module's columns, if any
					if (count($columns = $module->get_admin_table_columns()))
						register_column_headers($module->plugin_page_hook, $columns);
				}
			}
		}
	}
	
	/**
	 * Output an HTML <link> to a CSS file if the CSS file exists.
	 * Includes a version-based query string parameter to prevent caching old versions.
	 * 
	 * @since 2.1
	 * @uses $plugin_dir_path
	 * @uses $plugin_dir_url
	 * @uses SU_VERSION
	 * 
	 * @param string $relurl The URL to the CSS file, relative to the plugin directory.
	 */
	function queue_css($reldir, $filename) {
		$this->queue_file($reldir, $filename, '.css', 'wp_enqueue_style');
	}
	
	/**
	 * Output an HTML <script> tag if the corresponding JavaScript file exists.
	 * Includes a version-based query string parameter to prevent caching old versions.
	 * 
	 * @since 2.1
	 * @uses $plugin_dir_path
	 * @uses $plugin_dir_url
	 * @uses SU_VERSION
	 * 
	 * @param string $relurl The URL to the JavaScript file, relative to the plugin directory.
	 */
	function queue_js($reldir, $filename, $deps=array(), $l10n=array()) {
		$this->queue_file($reldir, $filename, '.js', 'wp_enqueue_script', $deps, $l10n);
	}
	
	/**
	 * Queues a CSS/JS file with WordPress if the file exists.
	 * 
	 * @since 2.1
	 */
	function queue_file($reldir, $filename, $ext, $func, $deps=array(), $l10n=array()) {
		if (!function_exists($func)) return;
		$reldir = untrailingslashit($reldir);
		$dirid = str_replace('/', '-', $reldir);
		$relurl = $reldir . '/';
		$file = sustr::endwith($filename, $ext);
		if (file_exists($this->plugin_dir_path.$relurl.$file))
			$func("su-$dirid-$filename", $this->plugin_dir_url.$relurl.$file, $deps, SU_VERSION);
		
		if (count($l10n))
			wp_localize_script("su-$dirid-$filename", sustr::camel_case("su $dirid $filename l10n"), $l10n);
	}
	
	/**
	 * Replaces WordPress's default contextual help with postmeta help when appropriate.
	 * 
	 * @since 0.1
	 * @uses $modules
	 * 
	 * @param string $text WordPress's default contextual help.
	 * @param string $screen The screen currently being shown.
	 * @return string The contextual help content that should be shown.
	 */
	function admin_help() {
		
		$screen = get_current_screen();
		if ('post' != $screen->base) //WP_Screen->base added in WP 3.3
			return;
		
		//Gather post meta help content
		$helparray = apply_filters('su_postmeta_help', array());
		
		if ($helparray) {
		
			$customhelp = '';
			foreach ($helparray as $line) {
				$customhelp .= "<p>$line</p>\n";
			}
			
			//WP_Screen->add_help_tab added in WP 3.3
			$screen->add_help_tab(array(
				  'id' => 'seo-ultimate-post-meta-help'
				, 'title' => __('SEO Settings', 'seo-ultimate')
				, 'content' => "<div class='su-help'>\n$customhelp\n</div>\n"
			));
		}
	}
	
	/**
	 * Notifies the user if he/she is using plugins whose functionality SEO Ultimate replaces.
	 * 
	 * @since 0.1
	 * @uses plugin_page_notice() Hooked into the after_plugin_row_$path actions.
	 */
	function plugin_page_notices() {
		
		global $pagenow;
		
		if ($pagenow == 'plugins.php') {
		
			$r_plugins = array(
				  'wordpress-seo/wp-seo.php'
			);
			
			$i_plugins = get_plugins();
			
			foreach ($r_plugins as $path) {
				if (isset($i_plugins[$path]))
					add_action("after_plugin_row_$path", array(&$this, 'plugin_page_notice'), 10, 3);
			}
		}
	}
	
	/**
	 * Outputs a table row notifying the user that he/she is using a plugin which may conflict with SEO Ultimate.
	 * 
	 * @since 0.1
	 */
	function plugin_page_notice($file, $data, $context) {
		if (is_plugin_active($file)) {
			echo "<tr class='plugin-update-tr su-plugin-notice'><td colspan='3' class='plugin-update colspanchange'><div class='update-message'>\n";
			printf(__('%1$s is known to cause conflicts with SEO Ultimate. Please deactivate %1$s if you wish to continue using SEO Ultimate.', 'seo-ultimate'), $data['Name']);
			echo "</div></td></tr>\n";
		}
	}
	
	/**
	 * Displays new-version info in this plugin's update row on WordPress's plugin admin page.
	 * Hooked into WordPress's in_plugin_update_message-(file) action.
	 * 
	 * @since 0.1
	 * 
	 * @param array $plugin_data An array of this plugin's information. Unused.
	 * @param obejct $r The response object from the WordPress Plugin Directory.
	 */
	function plugin_update_info($plugin_data, $r) {
		//If a new version is available...
		if ($r && $r->new_version && !is_plugin_active('changelogger/changelogger.php'))
			//If info on the new version is available...
			if ($info = $this->get_plugin_update_info($r->new_version))
				//Output the new-version info
				echo "<span class='su-plugin-update-info'><br />$info</span>";
	}
	
	/**
	 * Loads new-version info and returns it as a string.
	 * 
	 * @since 2.1
	 * 
	 * @return string
	 */
	function get_plugin_update_info($nv) {
		
		$change_types = array(
			  'New Module' => 'module'
			, 'Feature' => 'feature'
			, 'SEO Feature' => 'feature'
			, 'Bugfix' => 'bugfix'
			, 'Improvement' => 'improvement'
			, 'Security Fix' => 'security'
			, 'New Translation' => 'new-lang'
			, 'Updated Translation' => 'updated-lang'
		);
		
		$change_labels = array(
			  'module'		=> array(__('new module', 'seo-ultimate'), __('new modules', 'seo-ultimate'))
			, 'feature'     => array(__('new feature', 'seo-ultimate'), __('new features', 'seo-ultimate'))
			, 'bugfix'      => array(__('bugfix', 'seo-ultimate'), __('bugfixes', 'seo-ultimate'))
			, 'improvement' => array(__('improvement', 'seo-ultimate'), __('improvements', 'seo-ultimate'))
			, 'security'    => array(__('security fix', 'seo-ultimate'), __('security fixes', 'seo-ultimate'))
			, 'new-lang'    => array(__('new language pack', 'seo-ultimate'), __('new language packs', 'seo-ultimate'))
			, 'updated-lang'=> array(__('language pack update', 'seo-ultimate'), __('language pack updates', 'seo-ultimate'))
		);
		
		$changes = array_fill_keys($change_types, 0);
		
		$versions = $this->download_changelog();
		if (!is_array($versions) || !count($versions)) return '';
		
		foreach ($versions as $version_title => $version_changelog) {
			if (preg_match('|Version ([0-9.]{3,9}) |', $version_title, $matches)) {
				$version = $matches[1];
				
				//If we're running the same version or a newer version, continue
				if (version_compare(SU_VERSION, $version, '>=')) continue;
				
				$version_changes = explode('</li>', $version_changelog);
				foreach ($version_changes as $change) {
					if (preg_match('|<li>([a-zA-Z ]+): |', $change, $matches2)) {
						$change_type_label = $matches2[1];
						if (isset($change_types[$change_type_label]))
							$changes[$change_types[$change_type_label]]++;
					}
				}
			}
		}
		
		if (!count($changes)) return '';
		
		$nlchanges = array();
		foreach ($changes as $change_type => $changes_count) {
			if (is_string($change_type) && $changes_count > 0)
				$nlchanges[] = sprintf(__('%d %s', 'seo-ultimate'),
									number_format_i18n($changes_count),
									_n($change_labels[$change_type][0], $change_labels[$change_type][1], $changes_count, 'seo-ultimate')
								);
		}
		
		return sprintf(__('Upgrade now to get %s. %s.', 'seo-ultimate')
					, sustr::nl_implode($nlchanges)
					, '<a href="plugin-install.php?tab=plugin-information&amp;plugin=seo-ultimate&amp;section=changelog&amp;TB_iframe=true&amp;width=640&amp;height=530" class="thickbox">' . __('View changelog', 'seo-ultimate') . '</a>'
				);
	}
	
	/**
	 * Downloads the plugin's changelog.
	 * 
	 * @since 3.1
	 * 
	 * @return array An array of changelog headers {Version X.X (Month Day, Year)} => <ul> lists of changes.
	 */
	function download_changelog() {
		
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		
		$plugin = plugins_api('plugin_information', array('slug' => 'seo-ultimate'));
		if (is_wp_error($plugin)) return false;
		$changelog = $plugin->sections['changelog'];
		
		$entries = explode('<h4>', $changelog);
		$versions = array();
		foreach ($entries as $entry) {
			$item = explode('</h4>', $entry, 2);
			if (count($item) == 2) $versions[$item[0]] = $item[1];
		}
		
		return $versions;
	}
	
	/**
	 * Provides WordPress with SEO Ultimate's custom update info notices.
	 * 
	 * @since 2.1
	 */
	function add_plugin_upgrade_notice($current) {
		static $info;
		if (isset($current->response[$this->plugin_basename])) {
			if (!strlen($current->response[$this->plugin_basename]->upgrade_notice)) {
				if (!$info)
					$info = $this->get_plugin_update_info($current->response[$this->plugin_basename]->new_version);
				$current->response[$this->plugin_basename]->upgrade_notice = $info;
			}
		}
		return $current;
	}
	
	/**
	 * Filters the list of plugin action links for SEO Ultimate and adds links to certain modules if those modules are enabled.
	 * 
	 * @since 2.1
	 * 
	 * @param array $actions The array of <a> links.
	 * @return array The $actions array with additional links.
	 */
	function plugin_action_links($actions) {
		$su_actions = array(
			  'uninstall' => __('Uninstall', 'seo-ultimate')
		);
		
		foreach ($su_actions as $module => $anchor) {
			if ($this->module_exists($module) && $url = $this->modules[$module]->get_admin_url()) {
				$actions[] = "<a href='$url'>$anchor</a>";
			}
		}
		
		return $actions;
	}
	
	/**
	 * Outputs a list of active modules on SEO Ultimate's plugin page listing.
	 * 
	 * @since 2.1
	 */
	function plugin_row_meta_filter($plugin_meta, $plugin_file) {
		if ($plugin_file == $this->plugin_basename) {
			
			if (is_blog_admin())
				$title = __('Active Modules: ', 'seo-ultimate');
			else
				$title = '';
			
			echo $this->get_module_links_list('<p id="su-active-modules-list">'.$title, ' | ', '</p>');
		}
		
		return $plugin_meta;
	}
	
	/**
	 * Returns a list of links to active, independent modules.
	 * 
	 * @since 2.1
	 */
	function get_module_links_list($before = '', $between = ' | ', $after = '') {
		
		$list = '';
		
		if (count($this->modules)) {
			
			$modules = array();
			foreach ($this->modules as $key => $x_module) {
				$module =& $this->modules[$key];
				if (strcasecmp(get_parent_class($module), 'SU_Module') == 0 && $module->is_independent_module()) {
					if ($url = $module->get_admin_url())
						$modules[$module->get_module_title()] = $url;
				}
			}
			
			ksort($modules);
			
			$list = $before;
			$first = true;
			foreach ($modules as $title => $url) {
				$url = su_esc_attr($url);
				$title = str_replace(' ', '&nbsp;', su_esc_html($title));
				if ($first) $first = false; else $list .= $between;
				$list .= "<a href='$url'>$title</a>";
			}
			$list .= $after;
		}
		
		return $list;
	}
	
	/**
	 * Removes the activation notices of All in One SEO Pack and Akismet from our admin pages.
	 * (It could be confusing for users to see another plugin's notices on our plugin's pages.)
	 * 
	 * @since 1.1
	 */
	function remove_admin_notices() {
		if ($this->is_plugin_admin_page()) {
			remove_action('admin_notices', 'aioseop_activation_notice');
			remove_action('admin_notices', 'akismet_warning');
		}
	}
	
	/**
	 * Outputs a WordPress-esque admin notice regarding the "discourage search engines" setting.
	 * The value of this setting must be assessed *before* this function is hooked into WordPress.
	 * 
	 * @since 1.7
	 */
	function private_blog_admin_notice() {
		echo "\n<div class='error'><p>";
		_e('<strong>SEO Ultimate Notice:</strong> Your blog is configured to block search engine spiders. To resolve this, <a href="options-reading.php" target="_blank">go to your Reading settings</a> and disable the &#8220;discourage search engines&#8221; option.', 'seo-ultimate');
		echo "</p></div>";
	}
	
	/**
	 * @since 7.6
	 */
	function should_show_wp_ultimate_promo() {
		return $this->is_wp_ultimate_promo_applicable() && $this->get_setting('wp_ultimate', true, 'settings');
	}
	
	/**
	 * @since 7.6
	 */
	function is_wp_ultimate_promo_applicable() {
		//If the current user can install themes and if WP Ultimate isn't already uploaded...
		return current_user_can('install_themes') && (wp_get_theme('wpultimate')->errors() !== false);
	}
	
	/********** MODULE FUNCTIONS ***********/
	
	/**
	 * Checks to see whether an instantiation of the specified module exists (i.e. whether the module is non-disabled).
	 * 
	 * @since 1.5
	 * 
	 * @param string $key The key of the module to check.
	 * @return boolean Whether the module is enabled (or silent or hidden).
	 */
	function module_exists($key) {
		return isset($this->modules[$key]);
	}
	
	/**
	 * Calls the function of a module.
	 * 
	 * @since 1.5
	 * 
	 * @param string $key The key of the module to which the function belongs.
	 * @param string $function The name of the function to call.
	 * @param mixed $result Passed by reference. Set to the result of the function.
	 * @return boolean Whether or not the function existed.
	 */
	function call_module_func($key, $function, &$result = null, $call_even_if_disabled=true) {
		
		//Wipe passed-by-reference variable clean
		$result = null;
		
		$args = func_get_args();
		$args = array_slice($args, 3);
		
		if (isset($this->modules[$key]))
			$obj =& $this->modules[$key];
		elseif (isset($this->disabled_modules[$key]) && $call_even_if_disabled)
			$obj = $this->disabled_modules[$key];
		else
			return false;
		
		if (is_callable($call = array($obj, $function))) {
			$result = call_user_func_array($call, $args);
			return true;
		}
		
		return false;
	}
	
	/**
	 * @since 7.6
	 */
	function get_module_var($key, $var, $default) {
		
		if (isset($this->modules[$key]) && property_exists($this->modules[$key], $var))
			return $this->modules[$key]->$var;
		
		return $default;
	}
	
	/**
	 * @since 6.4
	 */
	function set_module_var($key, $var, $value) {
		
		if (isset($this->modules[$key]) && property_exists($this->modules[$key], $var)) {
			$this->modules[$key]->$var = $value;
			return true;
		}
		return false;
	}
	
	/********** ADMIN POST META BOX FUNCTIONS **********/
	
	/**
	 * @since 7.3
	 */
	function get_postmeta_tabs() {
		return array(
			  'serp' => __('Search Engine Listing', 'seo-ultimate')
			, 'opengraph' => __('Social Networks Listing', 'seo-ultimate')
			, 'links' => __('Links', 'seo-ultimate')
			, 'misc' => __('Miscellaneous', 'seo-ultimate')
		);
	}
	
	/**
	 * Compiles the post meta box field array based on data provided by the modules.
	 * 
	 * @since 0.8
	 * @uses SU_Module::postmeta_fields()
	 * 
	 * @param string $screen The admin screen currently being viewed (post, page).
	 * @return array An array structured like this: $data[tab ID][position #][field name] = HTML
	 */
	function get_postmeta_array($screen) {
		
		static $return = array();
		if (!empty($return[$screen]))
			return $return[$screen];
		
		$tabs = $this->get_postmeta_tabs();
		
		$module_fields = array();
		
		foreach ($this->modules as $key => $module) {
			
			$module_fields = $this->modules[$key]->postmeta_fields(array(), $screen);
			
			foreach ($module_fields as $tab => $tab_fields) {
				if (isset($tabs[$tab])) {
					if (!isset($fields[$tab])) $fields[$tab] = array();
					$fields[$tab] += $tab_fields;
				} else { //Backcompat
					if (strpos($tab, '|') === false) {
						if (!isset($fields['misc'][$tab])) $fields['misc'][$tab] = array();
						$fields['misc'][$tab] += $tab_fields;
					} else {
						list($pos, $keys) = explode('|', $tab, 2);
						$fields['misc'][$pos][$keys] = $tab_fields;
					}
				}
			}
		}
		
		foreach ($fields as $tab => $tab_poses) {
			ksort($fields[$tab]);
		}
		
		$return[$screen] = $fields;
		
		return $fields;
	}
	
	/**
	 * If we have post meta fields to display, then register our meta box with WordPress.
	 * 
	 * @since 0.1
	 * @uses get_postmeta_array()
	 */
	function add_postmeta_box() {
		
		//Add the metabox to posts and pages.
		$posttypes = get_post_types(array('public' => true), 'names');
		foreach ($posttypes as $screen) {
			
			if (strpos($screen, '"') !== false)
				continue;
			
			//Only show the meta box if there are fields to show.
			if ($this->get_postmeta_array($screen))
				add_meta_box('su_postmeta', __('SEO Settings', 'seo-ultimate'), create_function('', 'global $seo_ultimate; $seo_ultimate->show_postmeta_box("'.$screen.'");'), $screen, 'normal', 'high');
		}
	}
	
	/**
	 * Displays the inner contents of the post meta box.
	 * 
	 * @since 0.1
	 * @uses get_postmeta_array()
	 * 
	 * @param string $screen The admin screen currently being viewed (post, page).
	 */
	function show_postmeta_box($screen) {
		
		//Begin box
		echo "<div id='su-postmeta-box'>\n";
		wp_nonce_field('su-update-postmeta', '_su_wpnonce');
		
		//Output postmeta tabs
		$data = $this->get_postmeta_array($screen);
		$_tabs = $this->get_postmeta_tabs();
		$tabs = array();
		foreach ($_tabs as $tab_id => $tab_title) {
			if (isset($data[$tab_id]))
				$tabs[] = array('title' => $tab_title, 'id' => $tab_id, 'callback' => array('postmeta_tab', $tab_id, $screen));
		}
		$this->tabs($tabs);
		
		//Meta box footer
		echo '<p class="su-postmeta-box-footer">';
		printf(__('%1$s %2$s by %3$s', 'seo-ultimate'),
			'<a href="'.SU_PLUGIN_URI.'" target="_blank">'.__(SU_PLUGIN_NAME, 'seo-ultimate').'</a>',
			SU_VERSION,
			'<a href="'.SU_AUTHOR_URI.'" target="_blank">'.__(SU_AUTHOR, 'seo-ultimate').'</a>'
		);
		echo '</p>';
		
		//End box
		echo "</div>\n";
	}
	
	/**
	 * @since 7.3
	 */
	function postmeta_tab($tab, $screen) {
		echo "\n<table>\n";
		
		$data = $this->get_postmeta_array($screen);
		foreach ($data[$tab] as $tab_pos) {
			foreach ($tab_pos as $pos_field) {
				echo $pos_field;
			}
		}
		
		echo "\n</table>\n";
	}
	
	/**
	 * Saves the values of the fields in the post meta box.
	 * 
	 * @since 0.1
	 * 
	 * @param int $post_id The ID of the post being saved.
	 * @param object $post The post being saved.
	 */
	function save_postmeta_box($post_id, $post) {
		
		//Sanitize
		$post_id = (int)$post_id;
		
		//Run preliminary permissions checks
		if ( !isset($_REQUEST['_su_wpnonce']) || !wp_verify_nonce($_REQUEST['_su_wpnonce'], 'su-update-postmeta') ) return;
		$post_type = isset($_POST['post_type']) ? $_POST['post_type'] : 'post';
		$post_type_object = get_post_type_object($post_type);
		if (!current_user_can($post_type_object->cap->edit_posts)) return;
		
		//Get an array of the postmeta fields
		$data = $this->get_postmeta_array($post_type);
		foreach ($data as $tab => $tab_poses) {
			foreach ($tab_poses as $tab_pos) {
				foreach ($tab_pos as $fields => $html) {
					$fields = explode('|', $fields);
					foreach ($fields as $field) {
						$metakey = "_su_$field";
						
						$value = isset($_POST[$metakey]) ? stripslashes_deep($_POST[$metakey]) : '';
						if (!apply_filters("su_custom_update_postmeta-$field", false, $value, $metakey, $post)) {
							if (empty($value))
								//Delete the old value
								delete_post_meta($post_id, $metakey);
							else
								//Add the new value
								update_post_meta($post_id, $metakey, $value);
						}
					}
				}
			}
		}
	}
	
	/**
	 * @since 7.3
	 */
	function postmeta_box_tabs_init() {
		wp_enqueue_script('jquery-ui-tabs');
	}
	
	
	/********** CRON FUNCTION **********/
	
	/**
	 * Can remove cron jobs for modules that no longer exist, or remove all cron jobs.
	 * 
	 * @since 0.1
	 * 
	 * @param bool $remove_all Whether to remove all cron jobs. Optional.
	 */
	function remove_cron_jobs($remove_all = false) {
		
		$psdata = (array)get_option('seo_ultimate', array());
		
		if (isset($psdata['cron']) && is_array($psdata['cron'])) {
			$newcrondata = $crondata = $psdata['cron'];
			
			foreach ($crondata as $key => $crons) {
				if ($remove_all || !isset($this->modules[$key])) {
					foreach ($crons as $data) { wp_clear_scheduled_hook($data[0]); }
					unset($newcrondata[$key]);
				}
			}
			
			$psdata['cron'] = $newcrondata;
			
			update_option('seo_ultimate', $psdata);
		}
	}
	
	
	/********** TEMPLATE OUTPUT FUNCTION **********/
	
	/**
	 * Outputs code into the template's <head> tag.
	 * 
	 * @since 0.1
	 */
	function template_head() {
		
		if ($markcode = $this->get_setting('mark_code', true, 'settings'))
			echo "\n<!-- ".SU_PLUGIN_NAME." (".SU_PLUGIN_URI.") -->\n";
		
		//Let modules output head code.
		do_action('su_head');
		
		//Make sure the blog is public. Telling robots what to do is a moot point if they aren't even seeing the blog.
		if (get_option('blog_public')) {
			$robots = implode(',', apply_filters('su_meta_robots', array()));
			$robots = su_esc_attr($robots);
			if ($robots) echo "\t<meta name=\"robots\" content=\"$robots\" />\n";
		}
		
		if ($markcode) echo "<!-- /".SU_PLUGIN_NAME." -->\n\n";
	}
	
	/**
	 * Marks code with HTML comments identifying SEO Ultimate, if the user has set this option.
	 * 
	 * @since 2.7
	 */
	function mark_code($code, $info = '', $info_only = false) {
		
		if (!strlen($code)) return '';
		
		if ($this->get_setting('mark_code', false, 'settings')) {
		
			if ($info_only)
				$start = $end = $info;
			else {
				if ($info) $info = " - $info";
				$start = sprintf('%s (%s)%s', SU_PLUGIN_NAME, SU_PLUGIN_URI, $info);
				$end = SU_PLUGIN_NAME;
			}
			
			return "\n<!-- $start -->\n$code\n<!-- /$end -->\n\n";
		}
		return $code;
	}
	
	
	/********** README FUNCTIONS **********/
	
	/**
	 * Returns the full server path to the main readme.txt file.
	 * 
	 * @since 1.5
	 * @return string
	 */
	function get_readme_path() {
		return $this->plugin_dir_path.'readme.txt';
	}
	
	/********** JLSUGGEST **********/
	
	/**
	 * Outputs a JSON-encoded list of posts and terms on the blog.
	 * 
	 * @since 6.0
	 */
	function jlsuggest_autocomplete() {
		
		if ( !function_exists('json_encode') ) die();
		if ( !current_user_can( 'manage_options' ) ) die();
		
		$items = array();
		
		$include = empty($_GET['types']) ? array() : explode(',', $_GET['types']);
		
		if ((!$include || in_array('home', $include)) && sustr::ihas($_GET['q'], 'home')) {
			$items[] = array('text' => __('Home', 'seo-ultimate'), 'isheader' => true);
			$items[] = array('text' => __('Blog Homepage', 'seo-ultimate'), 'value' => 'obj_home', 'selectedtext' => __('Blog Homepage', 'seo-ultimate'));
		}
		
		
		$posttypeobjs = get_post_types(array('public' => true), 'objects');
		foreach ($posttypeobjs as $posttypeobj) {
			
			if ($include && !in_array('posttype', $include) && !in_array('posttype_' . $posttypeobj->name, $include))
				continue;
			
			$stati = get_available_post_statuses($posttypeobj->name);
			suarr::remove_value($stati, 'auto-draft');
			$stati = implode(',', $stati);
			
			$posts = get_posts(array(
				  'orderby' => 'title'
				, 'order' => 'ASC'
				, 'post_status' => $stati
				, 'numberposts' => -1
				, 'post_type' => $posttypeobj->name
				, 'post_mime_type' => isset($_GET['post_mime_type']) ? $_GET['post_mime_type'] : ''
				, 'sentence' => 1
				, 's' => $_GET['q']
			));
			
			if (count($posts)) {
				
				$items[] = array('text' => $posttypeobj->labels->name, 'isheader' => true);
				
				foreach ($posts as $post)
					$items[] = array(
						  'text' => $post->post_title
						, 'value' => 'obj_posttype_' . $posttypeobj->name . '/' . $post->ID
						, 'selectedtext' => $post->post_title . '<span class="type">&nbsp;&mdash;&nbsp;'.$posttypeobj->labels->singular_name.'</span>'
					);
			}
		}
		
		$taxonomyobjs = suwp::get_taxonomies();
		foreach ($taxonomyobjs as $taxonomyobj) {
			
			if ($include && !in_array('taxonomy', $include) && !in_array('taxonomy_' . $posttypeobj->name, $include))
				continue;
			
			$terms = get_terms($taxonomyobj->name, array(
				'search' => $_GET['q']
			));
			
			if (count($terms)) {
				
				$items[] = array('text' => $taxonomyobj->labels->name, 'isheader' => true);
				
				foreach ($terms as $term)
					$items[] = array(
						  'text' => $term->name
						, 'value' => 'obj_taxonomy_' . $taxonomyobj->name . '/' . $term->term_id
						, 'selectedtext' => $term->name . '<span class="type"> &mdash; '.$taxonomyobj->labels->singular_name.'</span>'
					);
			}
		}
		
		if (!$include || in_array('author', $include)) {
			
			$authors = get_users(array(
				  'search' => $_GET['q']
				, 'fields' => array('ID', 'user_login')
			));
			
			if (count($authors)) {
				
				$items[] = array('text' => __('Author Archives', 'seo-ultimate'), 'isheader' => true);
				
				foreach ($authors as $author)
					$items[] = array(
						  'text' => $author->user_login
						, 'value' => 'obj_author/' . $author->ID
						, 'selectedtext' => $author->user_login . '<span class="type"> &mdash; '.__('Author', 'seo-ultimate').'</span>'
					);
			}
		}
		
		if ($this->module_exists('internal-link-aliases') && (!$include || in_array('internal-link-alias', $include))) {
			
			$aliases = $this->get_setting('aliases', array(), 'internal-link-aliases');
			$alias_dir = $this->get_setting('alias_dir', 'go', 'internal-link-aliases');
			
			if (is_array($aliases) && count($aliases)) {
				
				$header_outputted = false;
				foreach ($aliases as $alias_id => $alias) {
					
					if ($alias['to']) {
						
						$h_alias_to = su_esc_html($alias['to']);
						$to_rel_url = "/$alias_dir/$h_alias_to/";
						
						if ((strpos($alias['from'], $_GET['q']) !== false) || (strpos($to_rel_url, $_GET['q']) !== false)) {
							
							if (!$header_outputted) {
								$items[] = array('text' => __('Link Masks', 'seo-ultimate'), 'isheader' => true);
								$header_outputted = true;
							}
							
							$items[] = array(
								  'text' => $to_rel_url
								, 'value' => 'obj_internal-link-alias/' . $alias_id
								, 'selectedtext' => $to_rel_url . '<span class="type"> &mdash; '.__('Link Mask', 'seo-ultimate').'</span>'
							);
							
						}
					}
				}
			}
		}
		
		echo json_encode($items);
		die();
	}
	
	/********** TABS **********/
	
	function tabs($tabs=array(), $table=false, &$callback=null) {
		
		if ($callback == null)
			$callback = $this;
		
		if ($c = count($tabs)) {
			
			if ($c > 1)
				echo "\n\n<div id='su-tabset' class='su-tabs'>\n";
			
			foreach ($tabs as $tab) {
				
				if (isset($tab['title']))	$title	  = $tab['title'];	  else return;
				if (isset($tab['id']))		$id		  = $tab['id'];		  else return;
				if (isset($tab['callback']))$function = $tab['callback']; else return;
				
				if ($c > 1) {
					//$id = 'su-' . sustr::preg_filter('a-z0-9', strtolower($title));
					echo "<fieldset id='$id'>\n<h3>$title</h3>\n<div class='su-tab-contents'>\n";
				}
				
				if ($table) echo "<table class='form-table'>\n";
				
				$call = $args = array();
				
				if (is_array($function)) {
					
					if (is_array($function[0])) {
						$call = array_shift($function);
						$args = $function;
					} elseif (is_string($function[0])) {
						$call = array_shift($function);
						$call = array($callback, $call);
						$args = $function;
					} else {
						$call = $function;
					}
				} else {
					$call = array($callback, $function);
				}
				if (is_callable($call)) call_user_func_array($call, $args);
				
				if ($table) echo "</table>";
				
				if ($c > 1)
					echo "</div>\n</fieldset>\n";
			}
			
			if ($c > 1) {
				echo "</div>\n";
				
				echo '<script type="text/javascript" src="'.$this->plugin_dir_url.'includes/tabs.js?v='.SU_VERSION.'"></script>';
			}
		}
	}
}
?>