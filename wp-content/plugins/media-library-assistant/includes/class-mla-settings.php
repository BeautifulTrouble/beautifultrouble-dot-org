<?php
/**
 * Manages the settings page to edit the plugin option settings
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Settings provides the settings page to edit the plugin option settings
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLASettings {
	/**
	 * Slug for localizing and enqueueing JavaScript - MLA View List Table
	 *
	 * @since 1.40
	 *
	 * @var	string
	 */
	const JAVASCRIPT_INLINE_EDIT_VIEW_SLUG = 'mla-inline-edit-view-scripts';

	/**
	 * Object name for localizing JavaScript - MLA View List Table
	 *
	 * @since 1.40
	 *
	 * @var	string
	 */
	const JAVASCRIPT_INLINE_EDIT_VIEW_OBJECT = 'mla_inline_edit_view_vars';

	/**
	 * Slug for localizing and enqueueing JavaScript - MLA View List Table
	 *
	 * @since 1.40
	 *
	 * @var	string
	 */
	const JAVASCRIPT_INLINE_EDIT_UPLOAD_SLUG = 'mla-inline-edit-upload-scripts';

	/**
	 * Object name for localizing JavaScript - MLA View List Table
	 *
	 * @since 1.40
	 *
	 * @var	string
	 */
	const JAVASCRIPT_INLINE_EDIT_UPLOAD_OBJECT = 'mla_inline_edit_upload_vars';

	/**
	 * Provides a unique name for the settings page
	 */
	const MLA_SETTINGS_SLUG = 'mla-settings-menu';
	
	/**
	 * Holds screen id to match help text to corresponding screen
	 *
	 * @since 1.40
	 *
	 * @var	array
	 */
	private static $current_page_hook = '';
	
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function initialize( ) {
//		add_action( 'admin_page_access_denied', 'MLASettings::mla_admin_page_access_denied_action' );
		add_action( 'admin_init', 'MLASettings::mla_admin_init_action' );
		add_action( 'admin_menu', 'MLASettings::mla_admin_menu_action' );
		add_action( 'admin_enqueue_scripts', 'MLASettings::mla_admin_enqueue_scripts_action' );
		add_filter( 'set-screen-option', 'MLASettings::mla_set_screen_option_filter', 10, 3 ); // $status, $option, $value
		add_filter( 'screen_options_show_screen', 'MLASettings::mla_screen_options_show_screen_filter', 10, 2 ); // $show_screen, $this
		self::_version_upgrade();
	}
	
	/**
	 * Database and option update check, for installing new versions
	 *
	 * @since 0.30
	 *
	 * @return	void
	 */
	private static function _version_upgrade( ) {
		$current_version = MLAOptions::mla_get_option( MLAOptions::MLA_VERSION_OPTION );
		
		if ( ((float)'.30') > ((float)$current_version) ) {
			/*
			 * Convert attachment_category and _tag to taxonomy_support;
			 * change the default if either option is unchecked
			 */
			$category_option = MLAOptions::mla_get_option( 'attachment_category' );
			$tag_option = MLAOptions::mla_get_option( 'attachment_tag' );
			if ( ! ( ( 'checked' == $category_option ) && ( 'checked' == $tag_option ) ) ) {
				$tax_option = MLAOptions::mla_get_option( 'taxonomy_support' );
				if ( 'checked' != $category_option ) {
					if ( isset( $tax_option['tax_support']['attachment_category'] ) )
						unset( $tax_option['tax_support']['attachment_category'] );
				}

				if ( 'checked' != $tag_option )  {
					if ( isset( $tax_option['tax_support']['attachment_tag'] ) )
						unset( $tax_option['tax_support']['attachment_tag'] );
				}

				MLAOptions::mla_taxonomy_option_handler( 'update', 'taxonomy_support', MLAOptions::$mla_option_definitions['taxonomy_support'], $tax_option );
			} // one or both options unchecked

		MLAOptions::mla_delete_option( 'attachment_category' );
		MLAOptions::mla_delete_option( 'attachment_tag' );
		} // version is less than .30
		
		if ( ((float)'1.13') > ((float)$current_version) ) {
			/*
			 * Add quick_edit and bulk_edit values to custom field mapping rules
			 */
			$new_values = array();
			
			foreach( MLAOptions::mla_get_option( 'custom_field_mapping' ) as $key => $value ) {
				$value['quick_edit'] = ( isset( $value['quick_edit'] ) && $value['quick_edit'] ) ? true : false;
				$value['bulk_edit'] = ( isset( $value['bulk_edit'] ) && $value['bulk_edit'] ) ? true : false;
				$new_values[ $key ] = $value;
			}

			MLAOptions::mla_update_option( 'custom_field_mapping', $new_values );
		} // version is less than 1.13
		
		if ( ((float)'1.30') > ((float)$current_version) ) {
			/*
			 * Add metadata values to custom field mapping rules
			 */
			$new_values = array();
			
			foreach( MLAOptions::mla_get_option( 'custom_field_mapping' ) as $key => $value ) {
				$value['meta_name'] = isset( $value['meta_name'] ) ? $value['meta_name'] : '';
				$value['meta_single'] = ( isset( $value['meta_single'] ) && $value['meta_single'] ) ? true : false;
				$value['meta_export'] = ( isset( $value['meta_export'] ) && $value['meta_export'] ) ? true : false;
				$new_values[ $key ] = $value;
			}

			MLAOptions::mla_update_option( 'custom_field_mapping', $new_values );
		} // version is less than 1.30
		
		if ( ((float)'1.40') > ((float)$current_version) ) {
			/*
			 * Add metadata values to custom field mapping rules
			 */
			$new_values = array();
			
			foreach( MLAOptions::mla_get_option( 'custom_field_mapping' ) as $key => $value ) {
				$value['no_null'] = ( isset( $value['no_null'] ) && $value['no_null'] ) ? true : false;
				
				if ( isset( $value['meta_single'] ) && $value['meta_single'] )
					$value['option'] = 'single';
				elseif ( isset( $value['meta_export'] ) && $value['meta_export'] )
					$value['option'] = 'export';
				else
					$value['option'] = 'text';
				
				unset( $value['meta_single'] );
				unset( $value['meta_export'] );
				
				$new_values[ $key ] = $value;
			}

			MLAOptions::mla_update_option( 'custom_field_mapping', $new_values );
		} // version is less than 1.40
		
		MLAOptions::mla_update_option( MLAOptions::MLA_VERSION_OPTION, MLA::CURRENT_MLA_VERSION );
	}
	
	/**
	 * Perform one-time actions on plugin activation
	 *
	 * Adds a view to the database to support sorting the listing on 'ALT Text'.
	 *
	 * @since 0.40
	 *
	 * @return	void
	 */
	public static function mla_activation_hook( ) {
		// self::_create_alt_text_view(); DELETED v1.10, NO LONGER REQUIRED
	}
	
	/**
	 * Perform one-time actions on plugin deactivation
	 *
	 * Removes (if present) a view from the database that supports sorting the listing on 'ALT Text'.
	 *
	 * @since 0.40
	 *
	 * @return	void
	 */
	public static function mla_deactivation_hook( ) {
		global $wpdb, $table_prefix;
		
		$view_name = $table_prefix . MLA_OPTION_PREFIX . MLAData::MLA_ALT_TEXT_VIEW_SUFFIX;
		$result = $wpdb->query( "SHOW TABLES LIKE '{$view_name}'" );

		if ( $result) {		
			$result = $wpdb->query(	"DROP VIEW {$view_name}" );
		}
	}
	
	/**
	 * Debug logging for "You do not have sufficient permissions to access this page."
	 *
	 * @since 1.40
	 *
	 * @return	void
	 * /
	public static function mla_admin_page_access_denied_action() {
		global $pagenow;
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;
		global $plugin_page;
		global $_registered_pages;
	
		error_log( 'mla_admin_page_access_denied_action $_SERVER[REQUEST_URI] = ' .  var_export( $_SERVER['REQUEST_URI'], true), 0 );
		error_log( 'mla_admin_page_access_denied_action $_REQUEST = ' .  var_export( $_REQUEST, true), 0 );
		error_log( 'mla_admin_page_access_denied_action $pagenow = ' .  var_export( $pagenow, true), 0 );
		error_log( 'mla_admin_page_access_denied_action $parent = ' .  var_export( get_admin_page_parent(), true), 0 );
		error_log( 'mla_admin_page_access_denied_action $menu = ' .  var_export( $menu, true), 0 );
		error_log( 'mla_admin_page_access_denied_action $submenu = ' .  var_export( $submenu, true), 0 );
		error_log( 'mla_admin_page_access_denied_action $_wp_menu_nopriv = ' .  var_export( $_wp_menu_nopriv, true), 0 );
		error_log( 'mla_admin_page_access_denied_action $_wp_submenu_nopriv = ' .  var_export( $_wp_submenu_nopriv, true), 0 );
		error_log( 'mla_admin_page_access_denied_action $plugin_page = ' .  var_export( $plugin_page, true), 0 );
		error_log( 'mla_admin_page_access_denied_action $_registered_pages = ' .  var_export( $_registered_pages, true), 0 );
	}
	 */
	
	/**
	 * Load the plugin's Ajax handler
	 *
	 * @since 1.40
	 *
	 * @return	void
	 */
	public static function mla_admin_init_action() {
		add_action( 'wp_ajax_' . self::JAVASCRIPT_INLINE_EDIT_VIEW_SLUG, 'MLASettings::mla_inline_edit_view_action' );
		add_action( 'wp_ajax_' . self::JAVASCRIPT_INLINE_EDIT_UPLOAD_SLUG, 'MLASettings::mla_inline_edit_upload_action' );
	}
	
	/**
	 * Load the plugin's Style Sheet and Javascript files
	 *
	 * @since 1.40
	 *
	 * @param	string	Name of the page being loaded
	 *
	 * @return	void
	 */
	public static function mla_admin_enqueue_scripts_action( $page_hook ) {
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		
		if ( self::$current_page_hook != $page_hook )
			return;

		wp_register_style( MLA::STYLESHEET_SLUG, MLA_PLUGIN_URL . 'css/mla-style.css', false, MLA::CURRENT_MLA_VERSION );
		wp_enqueue_style( MLA::STYLESHEET_SLUG );
		
		if ( isset( $_REQUEST['mla_tab'] ) && ( $_REQUEST['mla_tab'] == 'view' ) ) {
			wp_enqueue_script( self::JAVASCRIPT_INLINE_EDIT_VIEW_SLUG, MLA_PLUGIN_URL . "js/mla-inline-edit-view-scripts{$suffix}.js", 
				array( 'wp-lists', 'suggest', 'jquery' ), MLA::CURRENT_MLA_VERSION, false );
				
			$script_variables = array(
				'fields' => array( 'original_slug', 'slug', 'singular', 'plural', 'specification', 'menu_order' ),
				'checkboxes' => array( 'post_mime_type', 'table_view' ),
				'error' => 'Error while saving the changes.',
				'ntdeltitle' => 'Remove From Bulk Edit',
				'notitle' => '(no slug)',
				'comma' => _x( ',', 'tag delimiter' ),
				'ajax_action' => self::JAVASCRIPT_INLINE_EDIT_VIEW_SLUG,
				'ajax_nonce' => wp_create_nonce( MLA::MLA_ADMIN_NONCE ) 
			);
			wp_localize_script( self::JAVASCRIPT_INLINE_EDIT_VIEW_SLUG, self::JAVASCRIPT_INLINE_EDIT_VIEW_OBJECT, $script_variables );
			return;
		}

		if ( isset( $_REQUEST['mla_tab'] ) && ( $_REQUEST['mla_tab'] == 'upload' ) ) {
			wp_enqueue_script( self::JAVASCRIPT_INLINE_EDIT_UPLOAD_SLUG, MLA_PLUGIN_URL . "js/mla-inline-edit-upload-scripts{$suffix}.js", 
				array( 'wp-lists', 'suggest', 'jquery' ), MLA::CURRENT_MLA_VERSION, false );

			$script_variables = array(
				'fields' => array( 'original_slug', 'slug', 'mime_type', 'icon_type', 'core_type', 'mla_type', 'source', 'standard_source' ),
				'checkboxes' => array( 'disabled' ),
				'error' => 'Error while saving the changes.',
				'ntdeltitle' => 'Remove From Bulk Edit',
				'notitle' => '(no slug)',
				'comma' => _x( ',', 'tag delimiter' ),
				'ajax_action' => self::JAVASCRIPT_INLINE_EDIT_UPLOAD_SLUG,
				'ajax_nonce' => wp_create_nonce( MLA::MLA_ADMIN_NONCE ) 
			);
			wp_localize_script( self::JAVASCRIPT_INLINE_EDIT_UPLOAD_SLUG, self::JAVASCRIPT_INLINE_EDIT_UPLOAD_OBJECT, $script_variables );
			return;
		}
	}
	
	/**
	 * Add settings page in the "Settings" section,
	 * add screen options and help tabs,
	 * add settings link in the Plugins section entry for MLA.
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function mla_admin_menu_action( ) {
		/*
		 * We need a tab-specific page ID to manage the screen options on the Views and Uploads tabs.
		 * Use the URL suffix, if present. If the URL doesn't have a tab suffix, use '-general'.
		 * This hack is required to pass the WordPress "referer" validation.
		 */
		$tab = isset( $_REQUEST['page'] ) ? substr( $_REQUEST['page'], 1 + strrpos( $_REQUEST['page'], '-' ) ) : 'general';
		$tab = isset ( self::$mla_tablist[ $tab ] ) ? '-' . $tab : '-general';
		self::$current_page_hook = add_submenu_page( 'options-general.php', 'Media Library Assistant Settings', 'Media Library Assistant', 'manage_options', self::MLA_SETTINGS_SLUG . $tab, 'MLASettings::mla_render_settings_page' );
		add_action( 'load-' . self::$current_page_hook, 'MLASettings::mla_add_menu_options_action' );
		add_action( 'load-' . self::$current_page_hook, 'MLASettings::mla_add_help_tab_action' );
		add_filter( 'plugin_action_links', 'MLASettings::mla_add_plugin_settings_link_filter', 10, 2 );
	}
	
	/**
	 * Add the "XX Entries per page" filter to the Screen Options tab
	 *
	 * @since 1.40
	 *
	 * @return	void
	 */
	public static function mla_add_menu_options_action( ) {
		if ( isset( $_REQUEST['mla_tab'] ) ) {
			if ( 'view' == $_REQUEST['mla_tab'] ) {
				$option = 'per_page';
				
				$args = array(
					 'label' => 'Views per page',
					'default' => 10,
					'option' => 'mla_views_per_page' 
				);
				
				add_screen_option( $option, $args );
			} // view
			elseif ( isset( $_REQUEST['mla-optional-uploads-display'] ) || isset( $_REQUEST['mla-optional-uploads-search'] ) ) {
				$option = 'per_page';
				
				$args = array(
					 'label' => 'Types per page',
					'default' => 10,
					'option' => 'mla_types_per_page' 
				);
				
				add_screen_option( $option, $args );
			} // optional upload
			elseif ( 'upload' == $_REQUEST['mla_tab'] ) {
				$option = 'per_page';
				
				$args = array(
					 'label' => 'Upload types per page',
					'default' => 10,
					'option' => 'mla_uploads_per_page' 
				);
				
				add_screen_option( $option, $args );
			} // upload
		} // isset mla_tab
	}
	
	/**
	 * Add contextual help tabs to all the MLA pages
	 *
	 * @since 1.40
	 *
	 * @return	void
	 */
	public static function mla_add_help_tab_action( )
	{
		$screen = get_current_screen();
		
		/*
		 * Is this our page and the Views or Uploads tab?
		 */
		if ( ! in_array( $screen->id, array( 'settings_page_' . self::MLA_SETTINGS_SLUG . '-view', 'settings_page_' . self::MLA_SETTINGS_SLUG . '-upload' ) ) )
			return;
		
		$file_suffix = self::$current_page_hook;
		
		/*
		 * Override the screen suffix if we are going to display something other than the attachment table
		 */
		if ( isset( $_REQUEST['mla-optional-uploads-display'] ) || isset( $_REQUEST['mla-optional-uploads-search'] ) ) {
			$file_suffix .= '-optional';
		}
		elseif ( isset( $_REQUEST['mla_admin_action'] ) ) {
			switch ( $_REQUEST['mla_admin_action'] ) {
				case MLA::MLA_ADMIN_SINGLE_EDIT_DISPLAY:
					$file_suffix .= '-edit';
					break;
			} // switch
		} // isset( $_REQUEST['mla_admin_action'] )
		
		$template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/help-for-' . $file_suffix . '.tpl' );
		if ( empty( $template_array ) ) {
			return;
		}
		
		if ( !empty( $template_array['sidebar'] ) ) {
			$screen->set_help_sidebar( $template_array['sidebar'] );
			unset( $template_array['sidebar'] );
		}
		
		/*
		 * Provide explicit control over tab order
		 */
		$tab_array = array();
		
		foreach ( $template_array as $id => $content ) {
			$match_count = preg_match( '#\<!-- title="(.+)" order="(.+)" --\>#', $content, $matches, PREG_OFFSET_CAPTURE );
			
			if ( $match_count > 0 ) {
				$tab_array[ $matches[ 2 ][ 0 ] ] = array(
					 'id' => $id,
					'title' => $matches[ 1 ][ 0 ],
					'content' => $content 
				);
			} else {
				error_log( 'ERROR: mla_add_help_tab_action discarding '.var_export( $id, true ), 0 );
			}
		}
		
		ksort( $tab_array, SORT_NUMERIC );
		foreach ( $tab_array as $indx => $value ) {
			$screen->add_help_tab( $value );
		}
	}
	
	/**
	 * Only show screen options on the View and Upload tabs
	 *
	 * @since 1.40
	 *
	 * @param	boolean	True to display "Screen Options", false to suppress them
	 * @param	string	Name of the page being loaded
	 *
	 * @return	boolean	True to display "Screen Options", false to suppress them
	 */
	public static function mla_screen_options_show_screen_filter( $show_screen, $this_screen ) {
		if ( self::$current_page_hook == $this_screen->base ) {
			if ( isset( $_REQUEST['mla_tab'] ) && in_array( $_REQUEST['mla_tab'], array( 'view', 'upload' ) ) )
				return true;
		}
		
		return $show_screen;
	}
	
	/**
	 * Save the "Views/Uploads per page" option set by this user
	 *
	 * @since 1.40
	 *
	 * @param	mixed	false or value returned by previous filter
	 * @param	string	Name of the option being changed
	 * @param	string	New value of the option
	 *
	 * @return	string|void	New value if this is our option, otherwise nothing
	 */
	public static function mla_set_screen_option_filter( $status, $option, $value ) {
		if ( 'mla_views_per_page' == $option || 'mla_uploads_per_page' == $option || 'mla_types_per_page' == $option )
			return $value;
		elseif ( $status )
			return $status;
	}
	
	/**
	 * Ajax handler for Post MIME Types inline editing (quick and bulk edit)
	 *
	 * Adapted from wp_ajax_inline_save in /wp-admin/includes/ajax-actions.php
	 *
	 * @since 1.40
	 *
	 * @return	void	echo HTML <tr> markup for updated row or error message, then die()
	 */
	public static function mla_inline_edit_view_action() {
		set_current_screen( $_REQUEST['screen'] );

		check_ajax_referer( MLA::MLA_ADMIN_NONCE, 'nonce' );
		
		if ( empty( $_REQUEST['original_slug'] ) ) {
			echo 'Error: no view slug found';
			die();
		}

		$request = array( 'original_slug' => $_REQUEST['original_slug'] );
		$request['slug'] = $_REQUEST['slug'];
		$request['specification'] = $_REQUEST['specification'];
		$request['singular'] = $_REQUEST['singular'];
		$request['plural'] = $_REQUEST['plural'];
		$request['post_mime_type'] = isset( $_REQUEST['post_mime_type'] ) && ( '1' == $_REQUEST['post_mime_type'] );
		$request['table_view'] = isset( $_REQUEST['table_view'] ) && ( '1' == $_REQUEST['table_view'] );
		$request['menu_order'] = $_REQUEST['menu_order'];
		$results = MLAMime::mla_update_post_mime_type( $request );

		if ( false === strpos( $results['message'], 'ERROR:' ) )
			$new_item = (object) MLAMime::mla_get_post_mime_type( $_REQUEST['slug'] );
		else
			$new_item = (object) MLAMime::mla_get_post_mime_type( $_REQUEST['original_slug'] );
			
		$new_item->post_ID = $_REQUEST['post_ID'];

		//	Create an instance of our package class and echo the new HTML
		$MLAListViewTable = new MLA_View_List_Table();
		$MLAListViewTable->single_row( $new_item );
		die(); // this is required to return a proper result
	}
	
	/**
	 * Ajax handler for Upload MIME Types inline editing (quick and bulk edit)
	 *
	 * Adapted from wp_ajax_inline_save in /wp-admin/includes/ajax-actions.php
	 *
	 * @since 1.40
	 *
	 * @return	void	echo HTML <tr> markup for updated row or error message, then die()
	 */
	public static function mla_inline_edit_upload_action() {
		set_current_screen( $_REQUEST['screen'] );

		check_ajax_referer( MLA::MLA_ADMIN_NONCE, 'nonce' );
		
		if ( empty( $_REQUEST['original_slug'] ) ) {
			echo 'Error: no upload slug found';
			die();
		}

		$request = array( 'original_slug' => $_REQUEST['original_slug'] );
		$request['slug'] = $_REQUEST['slug'];
		$request['mime_type'] = $_REQUEST['mime_type'];
		$request['icon_type'] = $_REQUEST['icon_type'];
		$request['disabled'] = isset( $_REQUEST['disabled'] ) && ( '1' == $_REQUEST['disabled'] );
		$results = MLAMime::mla_update_upload_mime( $request );

		if ( false === strpos( $results['message'], 'ERROR:' ) )
			$new_item = (object) MLAMime::mla_get_upload_mime( $_REQUEST['slug'] );
		else
			$new_item = (object) MLAMime::mla_get_upload_mime( $_REQUEST['original_slug'] );
		$new_item->post_ID = $_REQUEST['post_ID'];

		//	Create an instance of our package class and echo the new HTML
		$MLAListUploadTable = new MLA_Upload_List_Table();
		$MLAListUploadTable->single_row( $new_item );
		die(); // this is required to return a proper result
	}
	
	/**
	 * Add the "Settings" link to the MLA entry in the Plugins section
	 *
	 * @since 0.1
	 *
	 * @param	array 	array of links for the Plugin, e.g., "Activate"
	 * @param	string 	Directory and name of the plugin Index file
	 *
	 * @return	array	Updated array of links for the Plugin
	 */
	public static function mla_add_plugin_settings_link_filter( $links, $file ) {
		if ( $file == 'media-library-assistant/index.php' ) {
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=' . self::MLA_SETTINGS_SLUG . '-general' ), 'Settings' );
			array_unshift( $links, $settings_link );
		}
		
		return $links;
	}
	
	/**
	 * Update or delete a single MLA option value
	 *
	 * @since 0.80
	 * @uses $_REQUEST
 	 *
	 * @param	string	HTML id/name attribute and option database key (OMIT MLA_OPTION_PREFIX)
	 * @param	array	Option parameters, e.g., 'type', 'std'
	 *
	 * @return	string	HTML markup for the option's table row
	 */
	private static function _update_option_row( $key, $value ) {
		if ( isset( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) ) {
			$message = '<br>update_option(' . $key . ")\r\n";
			switch ( $value['type'] ) {
				case 'checkbox':
					MLAOptions::mla_update_option( $key, 'checked' );
					break;
				case 'header':
				case 'subheader':
					$message = '';
					break;
				case 'radio':
					MLAOptions::mla_update_option( $key, $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
					break;
				case 'select':
					MLAOptions::mla_update_option( $key, $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
					break;
				case 'text':
					MLAOptions::mla_update_option( $key, trim( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) );
					break;
				case 'textarea':
					MLAOptions::mla_update_option( $key, trim( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) );
					break;
				case 'custom':
					$message = MLAOptions::$value['update']( 'update', $key, $value, $_REQUEST );
					break;
				case 'hidden':
					break;
				default:
					error_log( 'ERROR: _save_settings unknown type(1): ' . var_export( $value, true ), 0 );
			} // $value['type']
		}  // isset $key
		else {
			$message = '<br>delete_option(' . $key . ')';
			switch ( $value['type'] ) {
				case 'checkbox':
					MLAOptions::mla_update_option( $key, 'unchecked' );
					break;
				case 'header':
				case 'subheader':
					$message = '';
					break;
				case 'radio':
					MLAOptions::mla_delete_option( $key );
					break;
				case 'select':
					MLAOptions::mla_delete_option( $key );
					break;
				case 'text':
					MLAOptions::mla_delete_option( $key );
					break;
				case 'textarea':
					MLAOptions::mla_delete_option( $key );
					break;
				case 'custom':
					$message = MLAOptions::$value['delete']( 'delete', $key, $value, $_REQUEST );
					break;
				case 'hidden':
					break;
				default:
					error_log( 'ERROR: _save_settings unknown type(2): ' . var_export( $value, true ), 0 );
			} // $value['type']
		}  // ! isset $key
			
		return $message;
	}
	
	/**
	 * Compose the table row for a single MLA option
	 *
	 * @since 0.80
	 * @uses $page_template_array contains option and option-item templates
 	 *
	 * @param	string	HTML id/name attribute and option database key (OMIT MLA_OPTION_PREFIX)
	 * @param	array	Option parameters, e.g., 'type', 'std'
	 *
	 * @return	string	HTML markup for the option's table row
	 */
	private static function _compose_option_row( $key, $value ) {
		switch ( $value['type'] ) {
			case 'checkbox':
				$option_values = array(
					'key' => MLA_OPTION_PREFIX . $key,
					'checked' => '',
					'value' => $value['name'],
					'help' => $value['help'] 
				);
				
				if ( 'checked' == MLAOptions::mla_get_option( $key ) )
					$option_values['checked'] = 'checked="checked"';
				
				return MLAData::mla_parse_template( self::$page_template_array['checkbox'], $option_values );
			case 'header':
			case 'subheader':
				$option_values = array(
					'key' => MLA_OPTION_PREFIX . $key,
					'value' => $value['name'] 
				);
				
				return MLAData::mla_parse_template( self::$page_template_array[ $value['type'] ], $option_values );
			case 'radio':
				$radio_options = '';
				foreach ( $value['options'] as $optid => $option ) {
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'option' => $option,
						'checked' => '',
						'value' => $value['texts'][$optid] 
					);
					
					if ( $option == MLAOptions::mla_get_option( $key ) )
						$option_values['checked'] = 'checked="checked"';
					
					$radio_options .= MLAData::mla_parse_template( self::$page_template_array['radio-option'], $option_values );
				}
				
				$option_values = array(
					'value' => $value['name'],
					'options' => $radio_options,
					'help' => $value['help'] 
				);
				
				return MLAData::mla_parse_template( self::$page_template_array['radio'], $option_values );
			case 'select':
				$select_options = '';
				foreach ( $value['options'] as $optid => $option ) {
					$option_values = array(
						'selected' => '',
						'value' => $option,
						'text' => $value['texts'][$optid]
					);
					
					if ( $option == MLAOptions::mla_get_option( $key ) )
						$option_values['selected'] = 'selected="selected"';
					
					$select_options .= MLAData::mla_parse_template( self::$page_template_array['select-option'], $option_values );
				}
				
				$option_values = array(
					'key' => MLA_OPTION_PREFIX . $key,
					'value' => $value['name'],
					'options' => $select_options,
					'help' => $value['help'] 
				);
				
				return MLAData::mla_parse_template( self::$page_template_array['select'], $option_values );
			case 'text':
				$option_values = array(
					'key' => MLA_OPTION_PREFIX . $key,
					'value' => $value['name'],
					'help' => $value['help'],
					'size' => '40',
					'text' => '' 
				);
				
				if ( !empty( $value['size'] ) )
					$option_values['size'] = $value['size'];
				
				$option_values['text'] = MLAOptions::mla_get_option( $key );
				
				return MLAData::mla_parse_template( self::$page_template_array['text'], $option_values );
			case 'textarea':
				$option_values = array(
					'key' => MLA_OPTION_PREFIX . $key,
					'value' => $value['name'],
					'options' => $select_options,
					'help' => $value['help'],
					'cols' => '90',
					'rows' => '5',
					'text' => '' 
				);
				
				if ( !empty( $value['cols'] ) )
					$option_values['cols'] = $value['cols'];
				
				if ( !empty( $value['rows'] ) )
					$option_values['rows'] = $value['rows'];
				
				$option_values['text'] = stripslashes( MLAOptions::mla_get_option( $key ) );
				
				return MLAData::mla_parse_template( self::$page_template_array['textarea'], $option_values );
			case 'custom':
				if ( isset( $value['render'] ) )
					return MLAOptions::$value['render']( 'render', $key, $value );

				break;
			case 'hidden':
				break;
			default:
				error_log( 'ERROR: mla_render_settings_page unknown type: ' . var_export( $value, true ), 0 );
		} //switch
		
		return '';
	}
	
	/**
	 * Template file for the Settings page(s) and parts
	 *
	 * This array contains all of the template parts for the Settings page(s). The array is built once
	 * each page load and cached for subsequent use.
	 *
	 * @since 0.80
	 *
	 * @var	array
	 */
	private static $page_template_array = null;

	/**
	 * Definitions for Settings page tab ids, titles and handlers
	 * Each tab is defined by an array with the following elements:
	 *
	 * array key => HTML id/name attribute and option database key (OMIT MLA_OPTION_PREFIX)
	 *
	 * title => tab label / heading text
	 * render => rendering function for tab messages and content. Usage:
	 *     $tab_content = ['render']( );
	 *
	 * @since 0.80
	 *
	 * @var	array
	 */
	private static $mla_tablist = array(
		'general' => array( 'title' => 'General', 'render' => '_compose_general_tab' ),
		'view' => array( 'title' => 'Views', 'render' => '_compose_view_tab' ),
		'upload' => array( 'title' => 'Uploads', 'render' => '_compose_upload_tab' ),
		'mla_gallery' => array( 'title' => 'MLA Gallery', 'render' => '_compose_mla_gallery_tab' ),
		'custom_field' => array( 'title' => 'Custom Fields', 'render' => '_compose_custom_field_tab' ),
		'iptc_exif' => array( 'title' => 'IPTC/EXIF', 'render' => '_compose_iptc_exif_tab' ),
		'documentation' => array( 'title' => 'Documentation', 'render' => '_compose_documentation_tab' )
	);

	/**
	 * Compose the navigation tabs for the Settings subpage
	 *
	 * @since 0.80
	 * @uses $page_template_array contains tablist and tablist-item templates
 	 *
	 * @param	string	Optional data-tab-id value for the active tab, default 'general'
	 *
	 * @return	string	HTML markup for the Settings subpage navigation tabs
	 */
	private static function _compose_settings_tabs( $active_tab = 'general' ) {
		$tablist_item = self::$page_template_array['tablist-item'];
		$tabs = '';
		foreach ( self::$mla_tablist as $key => $item ) {
			$item_values = array(
				'data-tab-id' => $key,
				'nav-tab-active' => ( $active_tab == $key ) ? 'nav-tab-active' : '',
				'settings-page' => self::MLA_SETTINGS_SLUG . '-' . $key,
				'title' => $item['title']
			);
			
			$tabs .= MLAData::mla_parse_template( $tablist_item, $item_values );
		} // foreach $item
		
		$tablist_values = array( 'tablist' => $tabs );
		return MLAData::mla_parse_template( self::$page_template_array['tablist'], $tablist_values );
	}
	
	/**
	 * Compose the General tab content for the Settings subpage
	 *
	 * @since 0.80
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_general_tab( ) {
		/*
		 * Check for submit buttons to change or reset settings.
		 * Initialize page messages and content.
		 */
		if ( !empty( $_REQUEST['mla-general-options-save'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_save_general_settings( );
		} elseif ( !empty( $_REQUEST['mla-general-options-reset'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_reset_general_settings( );
		} else {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		
		if ( !empty( $page_content['body'] ) ) {
			return $page_content;
		}
		
		$page_values = array(
			'shortcode_list' => '',
			'options_list' => '',
			'donateURL' => MLA_PLUGIN_URL . 'images/DonateButton.jpg',
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-general&mla_tab=general',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false )
		);
		
		/*
		 * $custom_fields documents the name and description of custom fields
		 */
		$custom_fields = array( 
			// array("name" => "field_name", "description" => "field description.")
		);
		
		/* 
		 * $shortcodes documents the name and description of plugin shortcodes
		 */
		$shortcodes = array( 
			// array("name" => "shortcode", "description" => "This shortcode...")
			array( 'name' => 'mla_attachment_list', 'description' => 'renders a complete list of all attachments and references to them.' ),
			array( 'name' => 'mla_gallery', 'description' => 'enhanced version of the WordPress [gallery] shortcode. For complete documentation <a href="?page=' . self::MLA_SETTINGS_SLUG . '-documentation&amp;mla_tab=documentation">click here</a>.' )
		);
		
		$shortcode_list = '';
		foreach ( $shortcodes as $shortcode ) {
			$shortcode_values = array ( 'name' => $shortcode['name'], 'description' => $shortcode['description'] );
			$shortcode_list .= MLAData::mla_parse_template( self::$page_template_array['shortcode-item'], $shortcode_values );
		}
		
		if ( ! empty( $shortcode_list ) ) {
			$shortcode_values = array ( 'shortcode_list' => $shortcode_list );
			$page_values['shortcode_list'] = MLAData::mla_parse_template( self::$page_template_array['shortcode-list'], $shortcode_values );
		}
		
		/*
		 * Fill in the current list of sortable columns
		 */
		$default_orderby = MLA_List_Table::mla_get_sortable_columns( );
		foreach ($default_orderby as $key => $value ) {
			MLAOptions::$mla_option_definitions['default_orderby']['options'][] = $value[0];
			MLAOptions::$mla_option_definitions['default_orderby']['texts'][] = $value[1];
		}
		
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'general' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;
		$page_content['body'] = MLAData::mla_parse_template( self::$page_template_array['general-tab'], $page_values );
		return $page_content;
	}
	
	/**
	 * Get the current action selected from the bulk actions dropdown
	 *
	 * @since 1.40
	 *
	 * @return string|false The action name or False if no action was selected
	 */
	private static function _current_bulk_action( )	{
		$action = false;
		
		if ( isset( $_REQUEST['action'] ) ) {
			if ( -1 != $_REQUEST['action'] )
				return $_REQUEST['action'];
			else
				$action = 'none';
		} // isset action
		
		if ( isset( $_REQUEST['action2'] ) ) {
			if ( -1 != $_REQUEST['action2'] )
				return $_REQUEST['action2'];
			else
				$action = 'none';
		} // isset action2
		
		return $action;
	}
	
	/**
	 * Compose the Edit View tab content for the Settings subpage
	 *
	 * @since 1.40
	 *
	 * @param	array	data values for the item
	 * @param	string	Display template
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_edit_view_tab( $view, $template ) {
		$page_values = array(
			'action' => MLA::MLA_ADMIN_SINGLE_EDIT_UPDATE,
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-view&mla_tab=view',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'original_slug' => $view['slug']
		);
		
		foreach ( $view as $key => $value ) {
			switch ( $key ) {
				case 'post_mime_type':
				case 'table_view':
					$page_values[ $key ] = $value ? 'checked="checked"' : '';
					break;
				default:
					$page_values[ $key ] = $value;
			}
		}

		return array(
			'message' => '',
			'body' => MLAData::mla_parse_template( $template, $page_values )
		);
	}
	
	/**
	 * Compose the Post MIME Type Views tab content for the Settings subpage
	 *
	 * @since 1.40
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_view_tab( ) {
		$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-settings-view-tab.tpl' );
		if ( ! array( $page_template_array ) ) {
			error_log( "ERROR: MLASettings::_compose_view_tab \$page_template_array = " . var_export( $page_template_array, true ), 0 );
			return '';
		}
		
		/*
		 * Convert checkbox values, if present
		 */
		if ( isset( $_REQUEST['mla_view_item'] ) ) {
			$_REQUEST['mla_view_item']['post_mime_type'] = isset( $_REQUEST['mla_view_item']['post_mime_type'] );
			$_REQUEST['mla_view_item']['table_view'] = isset( $_REQUEST['mla_view_item']['table_view'] );
		}

		/*
		 * Set default values, check for Add New Post MIME Type View button
		 */
		$add_form_values = array (
			'slug' => '',
			'singular' => '',
			'plural' => '',
			'specification' => '',
			'post_mime_type' => 'checked="checked"',
			'table_view' => 'checked="checked"',
			'menu_order' => '',
			'description' => ''
			);
			
		if ( !empty( $_REQUEST['mla-view-options-save'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_save_view_settings( );
		}
		elseif ( !empty( $_REQUEST['mla-add-view-submit'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = MLAMime::mla_add_post_mime_type( $_REQUEST['mla_view_item'] );
			if ( false !== strpos( $page_content['message'], 'ERROR:' ) ) {
				$add_form_values = $_REQUEST['mla_view_item'];
				$add_form_values['post_mime_type'] = $add_form_values['post_mime_type'] ? 'checked="checked"' : '';
				$add_form_values['table_view'] = $add_form_values['table_view'] ? 'checked="checked"' : '';
			}
		} else {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		
		/*
		 * Process bulk actions that affect an array of items
		 */
		$bulk_action = self::_current_bulk_action();
		if ( $bulk_action && ( $bulk_action != 'none' ) ) {
			if ( isset( $_REQUEST['cb_mla_item_ID'] ) ) {
				/*
				 * Convert post-ID to slug; separate loop required because delete changes post_IDs
				 */
				$slugs = array();
				foreach ( $_REQUEST['cb_mla_item_ID'] as $post_ID )
					$slugs[] = MLAMime::mla_get_post_mime_type_slug( $post_ID );
					
				foreach ( $slugs as $slug ) {
					switch ( $bulk_action ) {
						case 'delete':
							$item_content = MLAMime::mla_delete_post_mime_type( $slug );
							break;
						case 'edit':
							$request = array( 'slug' => $slug );
							if ( '-1' != $_REQUEST['post_mime_type'] )
								$request['post_mime_type'] = '1' == $_REQUEST['post_mime_type'];
							if ( '-1' != $_REQUEST['table_view'] )
								$request['table_view'] = '1' == $_REQUEST['table_view'];
							if ( !empty( $_REQUEST['menu_order'] ) )
								$request['menu_order'] = $_REQUEST['menu_order'];
							$item_content = MLAMime::mla_update_post_mime_type( $request );
							break;
						default:
							$item_content = array(
								 'message' => sprintf( 'Unknown bulk action %s', $bulk_action ),
								'body' => '' 
							);
					} // switch $bulk_action
					
					$page_content['message'] .= $item_content['message'] . '<br>';
				} // foreach cb_attachment
			} // isset cb_attachment
			else {
				$page_content['message'] = 'Bulk Action ' . $bulk_action . ' - no items selected.';
			}
		} // $bulk_action
		
		/*
		 * Process row-level actions that affect a single item
		 */
		if ( !empty( $_REQUEST['mla_admin_action'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE );
			
			switch ( $_REQUEST['mla_admin_action'] ) {
				case MLA::MLA_ADMIN_SINGLE_DELETE:
					$page_content = MLAMime::mla_delete_post_mime_type( $_REQUEST['mla_item_slug'] );
					break;
				case MLA::MLA_ADMIN_SINGLE_EDIT_DISPLAY:
					$view = MLAMime::mla_get_post_mime_type( $_REQUEST['mla_item_slug'] );
					$page_content = self::_compose_edit_view_tab( $view, $page_template_array['single-item-edit'] );
					break;
				case MLA::MLA_ADMIN_SINGLE_EDIT_UPDATE:
					if ( !empty( $_REQUEST['update'] ) ) {
						$page_content = MLAMime::mla_update_post_mime_type( $_REQUEST['mla_view_item'] );
						if ( false !== strpos( $page_content['message'], 'ERROR:' ) ) {
							$message = $page_content['message'];
							$page_content = self::_compose_edit_view_tab( $_REQUEST['mla_view_item'], $page_template_array['single-item-edit'] );
							$page_content['message'] = $message;
						}
			} else {
						$page_content = array(
							'message' => 'Edit view "' . $_REQUEST['mla_view_item']['original_slug'] . '" cancelled.',
							'body' => '' 
						);
					}
					break;
				default:
					$page_content = array(
						 'message' => sprintf( 'Unknown mla_admin_action - "%1$s"', $_REQUEST['mla_admin_action'] ),
						'body' => '' 
					);
					break;
			} // switch ($_REQUEST['mla_admin_action'])
		} // (!empty($_REQUEST['mla_admin_action'])

		if ( !empty( $page_content['body'] ) ) {
			return $page_content;
		}
		
		/*
		 * Check for disabled status
		 */
		if ( 'checked' != MLAOptions::mla_get_option( MLAOptions::MLA_ENABLE_POST_MIME_TYPES ) ) {
			/*
			 * Fill in with any page-level options
			 */
			$options_list = '';
			foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
				if ( 'view' == $value['tab'] )
					$options_list .= self::_compose_option_row( $key, $value );
			}
			
			$page_values = array(
				'options_list' => $options_list,
				'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
				'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-view&mla_tab=view',
			);
			
			$page_content['body'] .= MLAData::mla_parse_template( $page_template_array['view-disabled'], $page_values );
			return $page_content;
		}

		/*
		 * Display the View Table
		 */
		$_SERVER['REQUEST_URI'] = remove_query_arg( array(
			'mla_admin_action',
			'mla_item_slug',
			'mla_item_ID',
			'_wpnonce',
			'_wp_http_referer',
			'action',
			'action2',
			'cb_mla_item_ID',
			'mla-optional-uploads-search',
			'mla-optional-uploads-display'
		), $_SERVER['REQUEST_URI'] );
		
		//	Create an instance of our package class
		$MLAListViewTable = new MLA_View_List_Table();
		
		//	Fetch, prepare, sort, and filter our data
		$MLAListViewTable->prepare_items();
		$MLAListViewTable->views();
			
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'view' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values = array(
			'options_list' => $options_list,
			'colspan' => count( $MLAListViewTable->get_columns() ),
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-view&mla_tab=view',
			'results' => ! empty( $_REQUEST['s'] ) ? '<h3 style="float:left">Displaying search results for: "' . $_REQUEST['s'] . '"</h3>' : '',
			's' => isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : ''
		);
		
		foreach ( $add_form_values as $key => $value ) {
			$page_values[ $key ] = $value;
		}
		$page_content['body'] = MLAData::mla_parse_template( $page_template_array['before-table'], $page_values );
		
		//	 Now we can render the completed list table
		ob_start();
		$MLAListViewTable->display();
		$page_content['body'] .= ob_get_contents();
		ob_end_clean();
		
		$page_content['body'] .= MLAData::mla_parse_template( $page_template_array['after-table'], $page_values );

		return $page_content;
	}
	
	/**
	 * Get an HTML select element representing a list of icon types
	 *
	 * @since 1.40
	 *
	 * @param	array	Display template array
	 * @param	string	HTML name attribute value
	 * @param	string	currently selected Icon Type
	 *
	 * @return string HTML select element or empty string on failure.
	 */
	public static function mla_get_icon_type_dropdown( $templates, $name, $selection = '.none.' ) {
		$option_template = $templates['icon-type-select-option'];
		if ( '.nochange.' == $selection )
			$option_values = array (
				'selected' => 'selected="selected"',
				'value' => '.none.',
				'text' => '&mdash; No Change &mdash;'
			);
		else
			$option_values = array (
				'selected' => ( '.none.' == $selection ) ? 'selected="selected"' : '',
				'value' => '.none.',
				'text' => ' &mdash; None (select a value) &mdash; '
			);
		
		$options = MLAData::mla_parse_template( $option_template, $option_values );
		
		$icon_types = MLAMime::mla_get_current_icon_types(); 
		foreach ( $icon_types as $icon_type ) {
			$option_values = array (
				'selected' => ( $icon_type == $selection ) ? 'selected="selected"' : '',
				'value' => $icon_type,
				'text' => $icon_type
			);
			
			$options .= MLAData::mla_parse_template( $option_template, $option_values );					
		} // foreach icon_type
		
		return MLAData::mla_parse_template( $templates['icon-type-select'], array( 'name' => $name, 'options' => $options ) );
	}

	/**
	 * Compose the Edit Upload type tab content for the Settings subpage
	 *
	 * @since 1.40
	 *
	 * @param	array	data values for the item
	 * @param	string	Display template array
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_edit_upload_tab( $item, &$templates ) {
		$page_values = array(
			'action' => MLA::MLA_ADMIN_SINGLE_EDIT_UPDATE,
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-upload&mla_tab=upload',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'original_slug' => $item['slug'],
			'icon_types' => self::mla_get_icon_type_dropdown( $templates, 'mla_upload_item[icon_type]', $item['icon_type'] )
		);
		
		foreach ( $item as $key => $value ) {
			switch ( $key ) {
				case 'disabled':
					$page_values[ $key ] = $value ? 'checked="checked"' : '';
					break;
				default:
					$page_values[ $key ] = $value;
			}
		}

		return array(
			'message' => '',
			'body' => MLAData::mla_parse_template( $templates['single-item-edit'], $page_values )
		);
	}
	
	/**
	 * Compose the Optional File Upload MIME Types tab content for the Settings subpage
	 *
	 * @since 1.40
	 *
	 * @param	string	Display templates
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_optional_upload_tab( $page_template_array ) {
		/*
		 * Display the Optional Upload MIME Types Table
		 */
		$_SERVER['REQUEST_URI'] = add_query_arg( array( 'mla-optional-uploads-display' => 'true' ), remove_query_arg( array(
			'mla_admin_action',
			'mla_item_slug',
			'mla_item_ID',
			'_wpnonce',
			'_wp_http_referer',
			'action',
			'action2',
			'cb_attachment',
			'mla-optional-uploads-search'
		), $_SERVER['REQUEST_URI'] ) );
		
			/*
			 * Suppress display of the hidden columns selection list
			 */
			echo "  <style type='text/css'>\r\n";
			echo "    form#adv-settings div.metabox-prefs {\r\n";
			echo "      display: none;\r\n";
			echo "    }\r\n";
			echo "  </style>\r\n";

		//	Create an instance of our package class
		$MLAListUploadTable = new MLA_Upload_Optional_List_Table();
		
		//	Fetch, prepare, sort, and filter our data
		$MLAListUploadTable->prepare_items();

		$page_content = array(
			'message' => '',
			'body' => '' 
		);
			
		$page_values = array(
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-upload&mla_tab=upload',
			'results' => ! empty( $_REQUEST['s'] ) ? '<br>&nbsp;<br>Displaying search results for: "' . $_REQUEST['s'] . '"' : '',
			's' => isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : ''
		);

		$page_content['body'] = MLAData::mla_parse_template( $page_template_array['before-optional-uploads-table'], $page_values );
		
		//	 Now we can render the completed list table
		ob_start();
//		$MLAListUploadTable->views();
		$MLAListUploadTable->display();
		$page_content['body'] .= ob_get_contents();
		ob_end_clean();
		
		$page_content['body'] .= MLAData::mla_parse_template( $page_template_array['after-optional-uploads-table'], $page_values );

		return $page_content;
	}
	
	/**
	 * Process an Optional Upload MIME Type selection
	 *
	 * @since 1.40
 	 *
	 * @param	intger	MLA Optional Upload MIME Type ID
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _process_optional_upload_mime( $ID ) {
		$optional_type = MLAMime::mla_get_optional_upload_mime( $ID );
		$optional_type['disabled'] = false;

		if ( false === $upload_type = MLAMime::mla_get_upload_mime( $optional_type['slug'] ) ) {
			$optional_type['icon_type'] = '.none.';
			return MLAMime::mla_add_upload_mime( $optional_type );
		}

		$optional_type['original_slug'] = $optional_type['slug'];
		return MLAMime::mla_update_upload_mime( $optional_type );
	}
	
	/**
	 * Compose the File Upload MIME Types tab content for the Settings subpage
	 *
	 * @since 1.40
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_upload_tab( ) {
		$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-settings-upload-tab.tpl' );
		if ( ! array( $page_template_array ) ) {
			error_log( "ERROR: MLASettings::_compose_upload_tab \$page_template_array = " . var_export( $page_template_array, true ), 0 );
			return '';
		}
		
		/*
		 * Untangle confusion between searching, canceling and selecting on the Optional Uploads screen
		 */
		$bulk_action = self::_current_bulk_action();
		if ( isset( $_REQUEST['mla-optional-uploads-cancel'] ) || $bulk_action && ( $bulk_action == 'select' ) ) {
			unset( $_REQUEST['mla-optional-uploads-search'] );
			unset( $_REQUEST['s'] );
		}
			
		/*
		 * Convert checkbox values, if present
		 */
		if ( isset( $_REQUEST['mla_upload_item'] ) ) {
			$_REQUEST['mla_upload_item']['disabled'] = isset( $_REQUEST['mla_upload_item']['disabled'] );
		}

		/*
		 * Set default values, check for Add New Post MIME Type View button
		 */
		$add_form_values = array (
			'slug' => '',
			'mime_type' => '',
			'icon_type' => '.none.',
			'disabled' => '',
			'description' => ''
			);
			
		if ( !empty( $_REQUEST['mla-upload-options-save'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_save_upload_settings( );
		}
		elseif ( !empty( $_REQUEST['mla-optional-uploads-search'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_compose_optional_upload_tab( $page_template_array );
		}
		elseif ( !empty( $_REQUEST['mla-optional-uploads-cancel'] ) ) {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		elseif ( !empty( $_REQUEST['mla-optional-uploads-display'] ) ) {
			if ( 'true' != $_REQUEST['mla-optional-uploads-display'] ) {
				check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
				unset( $_REQUEST['s'] );
			}
			$page_content = self::_compose_optional_upload_tab( $page_template_array );
		}
		elseif ( !empty( $_REQUEST['mla-add-upload-submit'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = MLAMime::mla_add_upload_mime( $_REQUEST['mla_upload_item'] );
			if ( false !== strpos( $page_content['message'], 'ERROR:' ) ) {
				$add_form_values = $_REQUEST['mla_upload_item'];
				$add_form_values['disabled'] = $add_form_values['disabled'] ? 'checked="checked"' : '';
			}
		} else {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		
		/*
		 * Process bulk actions that affect an array of items
		 */
		if ( $bulk_action && ( $bulk_action != 'none' ) ) {
			if ( isset( $_REQUEST['cb_mla_item_ID'] ) ) {
				if ( 'select' == $bulk_action ) {
					foreach ( $_REQUEST['cb_mla_item_ID'] as $ID ) {
						$item_content = MLASettings::_process_optional_upload_mime( $ID );
						$page_content['message'] .= $item_content['message'] . '<br>';
					}
				}
				else {
					/*
					 * Convert post-ID to slug; separate loop required because delete changes post_IDs
					 */
					$slugs = array();
					foreach ( $_REQUEST['cb_mla_item_ID'] as $post_ID )
						$slugs[] = MLAMime::mla_get_upload_mime_slug( $post_ID );
						
					foreach ( $slugs as $slug ) {
						switch ( $bulk_action ) {
							case 'delete':
								$item_content = MLAMime::mla_delete_upload_mime( $slug );
								break;
							case 'edit':
								$request = array( 'slug' => $slug );
								if ( '-1' != $_REQUEST['disabled'] )
									$request['disabled'] = '1' == $_REQUEST['disabled'];
								if ( '.none.' != $_REQUEST['icon_type'] )
									$request['icon_type'] = $_REQUEST['icon_type'];
								$item_content = MLAMime::mla_update_upload_mime( $request );
								break;
							default:
								$item_content = array(
									 'message' => sprintf( 'Unknown bulk action %s', $bulk_action ),
									'body' => '' 
								);
						} // switch $bulk_action
						
						$page_content['message'] .= $item_content['message'] . '<br>';
					} // foreach cb_attachment
				} // != select
			} // isset cb_attachment
			else {
				$page_content['message'] = 'Bulk Action ' . $bulk_action . ' - no items selected.';
			}
		} // $bulk_action
		
		/*
		 * Process row-level actions that affect a single item
		 */
		if ( !empty( $_REQUEST['mla_admin_action'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE );
			
			switch ( $_REQUEST['mla_admin_action'] ) {
				case MLA::MLA_ADMIN_SINGLE_DELETE:
					$page_content = MLAMime::mla_delete_upload_mime( $_REQUEST['mla_item_slug'] );
					break;
				case MLA::MLA_ADMIN_SINGLE_EDIT_DISPLAY:
					$view = MLAMime::mla_get_upload_mime( $_REQUEST['mla_item_slug'] );
					$page_content = self::_compose_edit_upload_tab( $view, $page_template_array );
					break;
				case MLA::MLA_ADMIN_SINGLE_EDIT_UPDATE:
					if ( !empty( $_REQUEST['update'] ) ) {
						$page_content = MLAMime::mla_update_upload_mime( $_REQUEST['mla_upload_item'] );
						if ( false !== strpos( $page_content['message'], 'ERROR:' ) ) {
							$message = $page_content['message'];
							$page_content = self::_compose_edit_upload_tab( $_REQUEST['mla_upload_item'], $page_template_array );
							$page_content['message'] = $message;
						}
					}
					elseif ( !empty( $_REQUEST['mla_item_ID'] ) ) {
						$page_content = self::_process_optional_upload_mime( $_REQUEST['mla_item_ID'] );
					} else {
						$page_content = array(
							'message' => 'Edit view "' . $_REQUEST['mla_upload_item']['original_slug'] . '" cancelled.',
							'body' => '' 
						);
					}
					break;
				default:
					$page_content = array(
						 'message' => sprintf( 'Unknown mla_admin_action - "%1$s"', $_REQUEST['mla_admin_action'] ),
						'body' => '' 
					);
					break;
			} // switch ($_REQUEST['mla_admin_action'])
		} // (!empty($_REQUEST['mla_admin_action'])

		if ( !empty( $page_content['body'] ) ) {
			return $page_content;
		}
		
		/*
		 * Check for disabled status
		 */
		if ( 'checked' != MLAOptions::mla_get_option( MLAOptions::MLA_ENABLE_UPLOAD_MIMES ) ) {
			/*
			 * Fill in with any page-level options
			 */
			$options_list = '';
			foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
				if ( 'upload' == $value['tab'] )
					$options_list .= self::_compose_option_row( $key, $value );
			}
			
			$page_values = array(
				'options_list' => $options_list,
				'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
				'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-upload&mla_tab=upload',
			);
			
			$page_content['body'] .= MLAData::mla_parse_template( $page_template_array['upload-disabled'], $page_values );
			return $page_content;
		}

		/*
		 * Display the Upload MIME Types Table
		 */
		$_SERVER['REQUEST_URI'] = remove_query_arg( array(
			'mla_admin_action',
			'mla_item_slug',
			'mla_item_ID',
			'_wpnonce',
			'_wp_http_referer',
			'action',
			'action2',
			'cb_mla_item_ID',
			'mla-optional-uploads-search',
		), $_SERVER['REQUEST_URI'] );
		
		//	Create an instance of our package class
		$MLAListUploadTable = new MLA_Upload_List_Table();
		
		//	Fetch, prepare, sort, and filter our data
		$MLAListUploadTable->prepare_items();
			
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'upload' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values = array(
			'options_list' => $options_list,
			'colspan' => count( $MLAListUploadTable->get_columns() ),
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-upload&mla_tab=upload',
			'results' => ! empty( $_REQUEST['s'] ) ? '<h3 style="float:left">Displaying search results for: "' . $_REQUEST['s'] . '"</h3>' : '',
			's' => isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '',
			'icon_types' => self::mla_get_icon_type_dropdown( $page_template_array, 'mla_upload_item[icon_type]' ),
			'inline_icon_types' => self::mla_get_icon_type_dropdown( $page_template_array, 'icon_type' ),
			'bulk_icon_types' => self::mla_get_icon_type_dropdown( $page_template_array, 'icon_type', '.nochange.' ),
			'search_url' => wp_nonce_url( '?page=mla-settings-menu-upload&mla_tab=upload&mla-optional-uploads-search=Search', MLA::MLA_ADMIN_NONCE )
		);
		
		foreach ( $add_form_values as $key => $value ) {
			$page_values[ $key ] = $value;
		}
		$page_content['body'] = MLAData::mla_parse_template( $page_template_array['before-table'], $page_values );
		
		//	 Now we can render the completed list table
		ob_start();
		$MLAListUploadTable->views();
		$MLAListUploadTable->display();
		$page_content['body'] .= ob_get_contents();
		ob_end_clean();
		
		$page_content['body'] .= MLAData::mla_parse_template( $page_template_array['after-table'], $page_values );

		return $page_content;
	}
	
	/**
	 * Compose the MLA Gallery tab content for the Settings subpage
	 *
	 * @since 0.80
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_mla_gallery_tab( ) {
		/*
		 * Check for submit buttons to change or reset settings.
		 * Initialize page messages and content.
		 */
		if ( !empty( $_REQUEST['mla-gallery-options-save'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_save_gallery_settings( );
		} else {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		
		if ( !empty( $page_content['body'] ) ) {
			return $page_content;
		}
		
		$page_values = array(
			'options_list' => '',
			'style_options_list' => '',
			'markup_options_list' => '',
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-mla_gallery&mla_tab=mla_gallery',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false )
		);
		
		/*
		 * Build default template selection lists
		 */
		MLAOptions::$mla_option_definitions['default_style']['options'][] = 'none';
		MLAOptions::$mla_option_definitions['default_style']['texts'][] = '-- none --';

		$templates = MLAOptions::mla_get_style_templates();
		ksort($templates);
		foreach ($templates as $key => $value ) {
			MLAOptions::$mla_option_definitions['default_style']['options'][] = $key;
			MLAOptions::$mla_option_definitions['default_style']['texts'][] = $key;
		}

		$templates = MLAOptions::mla_get_markup_templates();
		ksort($templates);
		foreach ($templates as $key => $value ) {
			MLAOptions::$mla_option_definitions['default_markup']['options'][] = $key;
			MLAOptions::$mla_option_definitions['default_markup']['texts'][] = $key;
		}
		
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'mla_gallery' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;

		/*
		 * Add style templates; default goes first
		 */
		$style_options_list = '';
		$templates = MLAOptions::mla_get_style_templates();
		
		$name = 'default';
		$value =$templates['default'];
		if ( ! empty( $value ) ) {
			$template_values = array (
				'help' => 'The default template cannot be altered or deleted, but you can copy the styles.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-default'], $template_values );
			
			$template_values = array (
				'readonly' => 'readonly="readonly"',
				'name_name' => 'mla_style_templates_name[default]',
				'name_id' => 'mla_style_templates_name_default',
				'name_text' => 'default',
				'control_cells' => $control_cells,
				'value_name' => 'mla_style_templates_value[default]',
				'value_id' => 'mla_style_templates_value_default',
				'value_text' => esc_textarea( $value ),
				'value_help' => 'List of substitution parameters, e.g., [+selector+], on Documentation tab.'
			);

			$style_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-style'], $template_values );
		} // $value
		
		foreach ( $templates as $name => $value ) {
			$slug = sanitize_title( $name );

			if ( 'default' == $name )
				continue; // already handled above
				
			$template_values = array (
				'name' => 'mla_style_templates_delete[' . $slug . ']',
				'id' => 'mla_style_templates_delete_' . $slug,
				'value' => 'Delete this template',
				'help' => 'Check the box to delete this template when you press Update at the bottom of the page.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-delete'], $template_values );
			
			$template_values = array (
				'readonly' => '',
				'name_name' => 'mla_style_templates_name[' . $slug . ']',
				'name_id' => 'mla_style_templates_name_' . $slug,
				'name_text' => $slug,
				'control_cells' => $control_cells,
				'value_name' => 'mla_style_templates_value[' . $slug . ']',
				'value_id' => 'mla_style_templates_value_' . $slug,
				'value_text' => esc_textarea( $value ),
				'value_help' => 'List of substitution parameters, e.g., [+selector+], on Documentation tab.'
			);

			$style_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-style'], $template_values );
		} // foreach $templates
		
		/*
		 * Add blank style template for additions
		 */
		if ( ! empty( $value ) ) {
			$template_values = array (
				'help' => 'Fill in a name and styles to add a new template.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-default'], $template_values );
			
			$template_values = array (
				'readonly' => '',
				'name_name' => 'mla_style_templates_name[blank]',
				'name_id' => 'mla_style_templates_name_blank',
				'name_text' => '',
				'control_cells' => $control_cells,
				'value_name' => 'mla_style_templates_value[blank]',
				'value_id' => 'mla_style_templates_value_blank',
				'value_text' => '',
				'value_help' => 'List of substitution parameters, e.g., [+selector+], on Documentation tab.'
			);

			$style_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-style'], $template_values );
		} // $value
		
		$page_values['style_options_list'] = $style_options_list;
		
		/*
		 * Add markup templates; default goes first
		 */
		$markup_options_list = '';
		$templates = MLAOptions::mla_get_markup_templates();
		
		$name = 'default';
		$value =$templates['default'];
		if ( ! empty( $value ) ) {
			$template_values = array (
				'help' => 'The default template cannot be altered or deleted, but you can copy the markup.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-default'], $template_values );
			
			$template_values = array (
				'readonly' => 'readonly="readonly"',
				'name_name' => 'mla_markup_templates_name[default]',
				'name_id' => 'mla_markup_templates_name_default',
				'name_text' => 'default',
				'control_cells' => $control_cells,

				'open_name' => 'mla_markup_templates_open[default]',
				'open_id' => 'mla_markup_templates_open_default',
				'open_text' => esc_textarea( $value['open'] ),
				'open_help' => 'Markup for the beginning of the gallery. List of parameters, e.g., [+selector+], on Documentation tab.',

				'row_open_name' => 'mla_markup_templates_row_open[default]',
				'row_open_id' => 'mla_markup_templates_row_open_default',
				'row_open_text' => esc_textarea( $value['row-open'] ),
				'row_open_help' => 'Markup for the beginning of each row in the gallery.',

				'item_name' => 'mla_markup_templates_item[default]',
				'item_id' => 'mla_markup_templates_item_default',
				'item_text' => esc_textarea( $value['item'] ),
				'item_help' => 'Markup for each item/cell of the gallery.',

				'row_close_name' => 'mla_markup_templates_row_close[default]',
				'row_close_id' => 'mla_markup_templates_row_close_default',
				'row_close_text' => esc_textarea( $value['row-close'] ),
				'row_close_help' => 'Markup for the end of each row in the gallery.',

				'close_name' => 'mla_markup_templates_close[default]',
				'close_id' => 'mla_markup_templates_close_default',
				'close_text' => esc_textarea( $value['close'] ),
				'close_help' => 'Markup for the end of the gallery.'
			);

			$markup_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-markup'], $template_values );
		} // $value
		
		foreach ( $templates as $name => $value ) {
			$slug = sanitize_title( $name );

			if ( 'default' == $name )
				continue; // already handled above
				
			$template_values = array (
				'name' => 'mla_markup_templates_delete[' . $slug . ']',
				'id' => 'mla_markup_templates_delete_' . $slug,
				'value' => 'Delete this template',
				'help' => 'Check the box to delete this template when you press Update at the bottom of the page.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-delete'], $template_values );
			
			$template_values = array (
				'readonly' => '',
				'name_name' => 'mla_markup_templates_name[' . $slug . ']',
				'name_id' => 'mla_markup_templates_name_' . $slug,
				'name_text' => $slug,
				'control_cells' => $control_cells,

				'open_name' => 'mla_markup_templates_open[' . $slug . ']',
				'open_id' => 'mla_markup_templates_open_' . $slug,
				'open_text' => esc_textarea( $value['open'] ),
				'open_help' => 'Markup for the beginning of the gallery. List of parameters, e.g., [+selector+], on Documentation tab.',

				'row_open_name' => 'mla_markup_templates_row_open[' . $slug . ']',
				'row_open_id' => 'mla_markup_templates_row_open_' . $slug,
				'row_open_text' => esc_textarea( $value['row-open'] ),
				'row_open_help' => 'Markup for the beginning of each row.',

				'item_name' => 'mla_markup_templates_item[' . $slug . ']',
				'item_id' => 'mla_markup_templates_item_' . $slug,
				'item_text' => esc_textarea( $value['item'] ),
				'item_help' => 'Markup for each item/cell.',

				'row_close_name' => 'mla_markup_templates_row_close[' . $slug . ']',
				'row_close_id' => 'mla_markup_templates_row_close_' . $slug,
				'row_close_text' => esc_textarea( $value['row-close'] ),
				'row_close_help' => 'Markup for the end of each row.',

				'close_name' => 'mla_markup_templates_close[' . $slug . ']',
				'close_id' => 'mla_markup_templates_close_' . $slug,
				'close_text' => esc_textarea( $value['close'] ),
				'close_help' => 'Markup for the end of the gallery.'
			);

			$markup_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-markup'], $template_values );
		} // foreach $templates
		
		/*
		 * Add blank markup template for additions
		 */
		if ( ! empty( $value ) ) {
			$template_values = array (
				'help' => 'Fill in a name and markup to add a new template.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-default'], $template_values );
			
			$template_values = array (
				'readonly' => '',
				'name_name' => 'mla_markup_templates_name[blank]',
				'name_id' => 'mla_markup_templates_name_blank',
				'name_text' => '',
				'control_cells' => $control_cells,

				'open_name' => 'mla_markup_templates_open[blank]',
				'open_id' => 'mla_markup_templates_open_blank',
				'open_text' => '',
				'open_help' => 'Markup for the beginning of the gallery. List of parameters, e.g., [+selector+], on Documentation tab.',

				'row_open_name' => 'mla_markup_templates_row_open[blank]',
				'row_open_id' => 'mla_markup_templates_row_open_blank',
				'row_open_text' => '',
				'row_open_help' => 'Markup for the beginning of each row in the gallery.',

				'item_name' => 'mla_markup_templates_item[blank]',
				'item_id' => 'mla_markup_templates_item_blank',
				'item_text' => '',
				'item_help' => 'Markup for each item/cell of the gallery.',

				'row_close_name' => 'mla_markup_templates_row_close[blank]',
				'row_close_id' => 'mla_markup_templates_row_close_blank',
				'row_close_text' => '',
				'row_close_help' => 'Markup for the end of each row in the gallery.',

				'close_name' => 'mla_markup_templates_close[blank]',
				'close_id' => 'mla_markup_templates_close_blank',
				'close_text' => '',
				'close_help' => 'Markup for the end of the gallery.'
				
			);

			$markup_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-markup'], $template_values );
		} // $value
		
		$page_values['markup_options_list'] = $markup_options_list;
		
		$page_content['body'] = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-tab'], $page_values );
		return $page_content;
	}
	
	/**
	 * Compose the Custom Field tab content for the Settings subpage
	 *
	 * @since 1.10
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_custom_field_tab( ) {
		/*
		 * Check for action or submit buttons.
		 * Initialize page messages and content.
		 */
		if ( isset( $_REQUEST['custom_field_mapping'] ) && is_array( $_REQUEST['custom_field_mapping'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );

			/*
			 * Check for page-level submit buttons to change settings or map attachments.
			 * Initialize page messages and content.
			 */
			if ( !empty( $_REQUEST['custom-field-options-save'] ) ) {
				$page_content = self::_save_custom_field_settings( );
			}
			elseif ( !empty( $_REQUEST['custom-field-options-map'] ) ) {
				$page_content = self::_process_custom_field_mapping( );
			}
			else {
				$page_content = array(
					 'message' => '',
					'body' => '' 
				);

				/*
				 * Check for single-rule action buttons
				 */
				foreach( $_REQUEST['custom_field_mapping'] as $key => $value ) {
					if ( isset( $value['action'] ) ) {
						$settings = array( $key => $value );
						foreach ( $value['action'] as $action => $label ) {
							switch( $action ) {
								case 'delete_field':
									$delete_result = self::_delete_custom_field( $value );
								case 'delete_rule':
								case 'add_rule':
								case 'add_field':
								case 'update_rule':
									$page_content = self::_save_custom_field_settings( $settings );
									if ( isset( $delete_result ) )
										$page_content['message'] = $delete_result . $page_content['message'];
									break;
								case 'map_now':
									$page_content = self::_process_custom_field_mapping( $settings );
									break;
								case 'add_rule_map':
								case 'add_field_map':
									$page_content = self::_save_custom_field_settings( $settings );
									$map_content = self::_process_custom_field_mapping( $settings );
									$page_content['message'] .= '<br>&nbsp;<br>' . $map_content['message'];
									break;
								default:
									// ignore everything else
							} //switch action
						} // foreach action
					} /// isset action
				} // foreach rule
			} // specific rule check
		} // isset custom_field_mapping
		else {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		
		if ( !empty( $page_content['body'] ) ) {
			return $page_content;
		}
		
		$page_values = array(
			'options_list' => '',
			'custom_options_list' => '',
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-custom_field&mla_tab=custom_field',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false )
		);
		
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'custom_field' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;
		
		/*
		 * Add mapping options
		 */
		$page_values['custom_options_list'] = MLAOptions::mla_custom_field_option_handler( 'render', 'custom_field_mapping', MLAOptions::$mla_option_definitions['custom_field_mapping'] );
		
		$page_content['body'] = MLAData::mla_parse_template( self::$page_template_array['custom-field-tab'], $page_values );
		return $page_content;
	}
	
	/**
	 * Compose the IPTC/EXIF tab content for the Settings subpage
	 *
	 * @since 1.00
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_iptc_exif_tab( ) {
		/*
		 * Initialize page messages and content.
		 * Check for submit buttons to change or reset settings.
		 */
		$page_content = array(
			'message' => '',
			'body' => '' 
		);

		if ( isset( $_REQUEST['iptc_exif_mapping'] ) && is_array( $_REQUEST['iptc_exif_mapping'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );

			if ( !empty( $_REQUEST['iptc-exif-options-save'] ) ) {
				$page_content = self::_save_iptc_exif_settings( );
			}
			elseif ( !empty( $_REQUEST['iptc-exif-options-process-standard'] ) ) {
				$page_content = self::_process_iptc_exif_standard( );
			}
			elseif ( !empty( $_REQUEST['iptc-exif-options-process-taxonomy'] ) ) {
				$page_content = self::_process_iptc_exif_taxonomy( );
			}
			elseif ( !empty( $_REQUEST['iptc-exif-options-process-custom'] ) ) {
				$page_content = self::_process_iptc_exif_custom( );
			}
			else {
				/*
				 * Check for single-rule action buttons
				 */
				foreach( $_REQUEST['iptc_exif_mapping']['custom'] as $key => $value ) {
					if ( isset( $value['action'] ) ) {
						$settings = array( 'custom' => array( $key => $value ) );
						foreach ( $value['action'] as $action => $label ) {
							switch( $action ) {
								case 'delete_field':
									$delete_result = self::_delete_custom_field( $value );
								case 'delete_rule':
								case 'add_rule':
								case 'add_field':
								case 'update_rule':
									$page_content = self::_save_iptc_exif_custom_settings( $settings );
									if ( isset( $delete_result ) )
										$page_content['message'] = $delete_result . $page_content['message'];
									break;
								case 'map_now':
									$page_content = self::_process_iptc_exif_custom( $settings );
									break;
								case 'add_rule_map':
								case 'add_field_map':
									$page_content = self::_save_iptc_exif_custom_settings( $settings );
									$map_content = self::_process_iptc_exif_custom( $settings );
									$page_content['message'] .= '<br>&nbsp;<br>' . $map_content['message'];
									break;
								default:
									// ignore everything else
							} //switch action
						} // foreach action
					} /// isset action
				} // foreach rule
			}
			
			if ( !empty( $page_content['body'] ) ) {
				return $page_content;
			}
		}
		
		$page_values = array(
			'options_list' => '',
			'standard_options_list' => '',
			'taxonomy_options_list' => '',
			'custom_options_list' => '',
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-iptc_exif&mla_tab=iptc_exif',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false )
		);
		
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'iptc_exif' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;
		
		/*
		 * Add mapping options
		 */
		$page_values['standard_options_list'] = MLAOptions::mla_iptc_exif_option_handler( 'render', 'iptc_exif_standard_mapping', MLAOptions::$mla_option_definitions['iptc_exif_standard_mapping'] );
		
		$page_values['taxonomy_options_list'] = MLAOptions::mla_iptc_exif_option_handler( 'render', 'iptc_exif_taxonomy_mapping', MLAOptions::$mla_option_definitions['iptc_exif_taxonomy_mapping'] );
		
		$page_values['custom_options_list'] = MLAOptions::mla_iptc_exif_option_handler( 'render', 'iptc_exif_custom_mapping', MLAOptions::$mla_option_definitions['iptc_exif_custom_mapping'] );
		
		$page_content['body'] = MLAData::mla_parse_template( self::$page_template_array['iptc-exif-tab'], $page_values );
		return $page_content;
	}
	
	/**
	 * Compose the Documentation tab content for the Settings subpage
	 *
	 * @since 0.80
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_documentation_tab( ) {
		$page_template = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/documentation-settings-tab.tpl' );
		$page_values = array(
			'phpDocs_url' => MLA_PLUGIN_URL . 'phpDocs/index.html'
		);
		
		return array(
			'message' => '',
			'body' => MLAData::mla_parse_template( $page_template['documentation-tab'], $page_values ) 
		);
	}
	
	/**
	 * Render (echo) the "Media Library Assistant" subpage in the Settings section
	 *
	 * @since 0.1
	 *
	 * @return	void Echoes HTML markup for the Settings subpage
	 */
	public static function mla_render_settings_page( ) {
		if ( !current_user_can( 'manage_options' ) ) {
			echo "Media Library Assistant - Error</h2>\r\n";
			wp_die( __( 'You do not have permission to manage plugin settings.' ) );
		}
		
		/*
		 * Load template array and initialize page-level values.
		 */
		self::$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-settings-page.tpl' );
		$current_tab = isset( $_REQUEST['mla_tab'] ) ? $_REQUEST['mla_tab']: 'general';
		$page_values = array(
			'version' => 'v' . MLA::CURRENT_MLA_VERSION,
			'donateURL' => MLA_PLUGIN_URL . 'images/DonateButton.jpg',
			'messages' => '',
			'tablist' => self::_compose_settings_tabs( $current_tab ),
			'tab_content' => ''
		);
		
		/*
		 * Compose tab content
		 */
		if ( array_key_exists( $current_tab, self::$mla_tablist ) ) {
			if ( isset( self::$mla_tablist[ $current_tab ]['render'] ) ) {
				$handler = self::$mla_tablist[ $current_tab ]['render'];
				$page_content = self::$handler(  );
			} else {
				$page_content = array( 'message' => 'ERROR: cannot render content tab', 'body' => '' );
			}
		} else {
			$page_content = array( 'message' => 'ERROR: unknown content tab', 'body' => '' );
		}

		if ( ! empty( $page_content['message'] ) ) {
			if ( false !== strpos( $page_content['message'], 'ERROR:' ) )
				$messages_class = 'mla_errors';
			else
				$messages_class = 'mla_messages';

			$page_values['messages'] = MLAData::mla_parse_template( self::$page_template_array['messages'], array(
				 'messages' => $page_content['message'],
				 'mla_messages_class' => $messages_class 
			) );
		}

		$page_values['tab_content'] = $page_content['body'];
		echo MLAData::mla_parse_template( self::$page_template_array['page'], $page_values );
	} // mla_render_settings_page
	
	/**
	 * Save MLA Gallery settings to the options table
 	 *
	 * @since 0.80
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_gallery_settings( ) {
		$settings_changed = false;
		$message_list = '';
		$error_list = '';
		
		/*
		 * Start with any page-level options
		 */
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'mla_gallery' == $value['tab'] && ( 'select' == $value['type'] ) ) {
				$old_value = MLAOptions::mla_get_option( $key );
				if ( $old_value != $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) {
					$settings_changed = true;
					$message_list .= self::_update_option_row( $key, $value );
				}
			}
		} // foreach mla_options
		
		/*
		 * Get the current style contents for comparison
		 */
		$old_templates = MLAOptions::mla_get_style_templates();
		$new_templates = array();
		$new_names = $_REQUEST['mla_style_templates_name'];
		$new_values = stripslashes_deep( $_REQUEST['mla_style_templates_value'] );
		$new_deletes = isset( $_REQUEST['mla_style_templates_delete'] ) ? $_REQUEST['mla_style_templates_delete']: array();
		
		/*
		 * Build new style template array, noting changes
		 */
		$templates_changed = false;
		foreach ( $new_names as $name => $new_name ) {
			if ( 'default' == $name )
				continue;

			if( array_key_exists( $name, $new_deletes ) ) {
				$message_list .= "<br>Deleting style template '{$name}'.";
				$templates_changed = true;
				continue;
			}

			$new_slug = sanitize_title( $new_name );
			if ( 'blank' == $name ) {
				if ( '' == $new_slug )
					continue;
				elseif ( 'blank' == $new_slug ) {
					$error_list .= "<br>ERROR: reserved name '{$new_slug}', new style template discarded.";
					continue;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate name '{$new_slug}', new style template discarded.";
					continue;
				}
				else {
					$message_list .= "<br>Adding new style template '{$new_slug}'.";
					$templates_changed = true;
				}
			} // 'blank' - reserved name
			
			/*
			 * Handle name changes, check for duplicates
			 */
			if ( '' == $new_slug ) {
				$error_list .= "<br>ERROR: blank style template name value, reverting to '{$name}'.";
				$new_slug = $name;
			}
			
			if ( $new_slug != $name ) {
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate new style template name '{$new_slug}', reverting to '{$name}'.";
					$new_slug = $name;
				}
				elseif ( 'blank' != $name ) {
					$message_list .= "<br>Changing style template name from '{$name}' to '{$new_slug}'.";
					$templates_changed = true;
				}
			} // name changed
			
			if ( ( 'blank' != $name ) && ( $new_values[ $name ] != $old_templates[ $name ] ) ) {
				$message_list .= "<br>Updating contents of style template '{$new_slug}'.";
				$templates_changed = true;
			}
			
			$new_templates[ $new_slug ] = $new_values[ $name ];
		} // foreach $name
		
		if ( $templates_changed ) {
			$settings_changed = true;
			if ( false == MLAOptions::mla_put_style_templates( $new_templates ) )
				$error_list .= "<br>ERROR: update of style templates failed.";
		}
		
		/*
		 * Get the current markup contents for comparison
		 */
		$old_templates = MLAOptions::mla_get_markup_templates();
		$new_templates = array();
		$new_names = $_REQUEST['mla_markup_templates_name'];
		$new_values['open'] = stripslashes_deep( $_REQUEST['mla_markup_templates_open'] );
		$new_values['row-open'] = stripslashes_deep( $_REQUEST['mla_markup_templates_row_open'] );
		$new_values['item'] = stripslashes_deep( $_REQUEST['mla_markup_templates_item'] );
		$new_values['row-close'] = stripslashes_deep( $_REQUEST['mla_markup_templates_row_close'] );
		$new_values['close'] = stripslashes_deep( $_REQUEST['mla_markup_templates_close'] );
		$new_deletes = isset( $_REQUEST['mla_markup_templates_delete'] ) ? $_REQUEST['mla_markup_templates_delete']: array();
		
		/*
		 * Build new markup template array, noting changes
		 */
		$templates_changed = false;
		foreach ( $new_names as $name => $new_name ) {
			if ( 'default' == $name )
				continue;

			if( array_key_exists( $name, $new_deletes ) ) {
				$message_list .= "<br>Deleting markup template '{$name}'.";
				$templates_changed = true;
				continue;
			}

			$new_slug = sanitize_title( $new_name );
			if ( 'blank' == $name ) {
				if ( '' == $new_slug )
					continue;
					
				if ( 'blank' == $new_slug ) {
					$error_list .= "<br>ERROR: reserved name '{$new_slug}', new markup template discarded.";
					continue;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate name '{$new_slug}', new markup template discarded.";
					continue;
				}
				else {
					$message_list .= "<br>Adding new markup template '{$new_slug}'.";
					$templates_changed = true;
				}
			} // 'blank' - reserved name
			
			/*
			 * Handle name changes, check for duplicates
			 */
			if ( '' == $new_slug ) {
				$error_list .= "<br>ERROR: blank markup template name value, reverting to '{$name}'.";
				$new_slug = $name;
			}
			
			if ( $new_slug != $name ) {
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate new markup template name '{$new_slug}', reverting to '{$name}'.";
					$new_slug = $name;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate new markup template name '{$new_slug}', reverting to '{$name}'.";
					$new_slug = $name;
				}
				elseif ( 'blank' != $name ) {
					$message_list .= "<br>Changing markup template name from '{$name}' to '{$new_slug}'.";
					$templates_changed = true;
				}
			} // name changed
			
			if ( 'blank' != $name ) {
				if ( $new_values['open'][ $name ] != $old_templates[ $name ]['open'] ) {
					$message_list .= "<br>Updating open markup for '{$new_slug}'.";
					$templates_changed = true;
				}
				
				if ( $new_values['row-open'][ $name ] != $old_templates[ $name ]['row-open'] ) {
					$message_list .= "<br>Updating row open markup for '{$new_slug}'.";
					$templates_changed = true;
				}
				
				if ( $new_values['item'][ $name ] != $old_templates[ $name ]['item'] ) {
					$message_list .= "<br>Updating item markup for '{$new_slug}'.";
					$templates_changed = true;
				}
				
				if ( $new_values['row-close'][ $name ] != $old_templates[ $name ]['row-close'] ) {
					$message_list .= "<br>Updating row close markup for '{$new_slug}'.";
					$templates_changed = true;
				}
				
				if ( $new_values['close'][ $name ] != $old_templates[ $name ]['close'] ) {
					$message_list .= "<br>Updating close markup for '{$new_slug}'.";
					$templates_changed = true;
				}
			} // ! 'blank'
			
			$new_templates[ $new_slug ]['open'] = $new_values['open'][ $name ];
			$new_templates[ $new_slug ]['row-open'] = $new_values['row-open'][ $name ];
			$new_templates[ $new_slug ]['item'] = $new_values['item'][ $name ];
			$new_templates[ $new_slug ]['row-close'] = $new_values['row-close'][ $name ];
			$new_templates[ $new_slug ]['close'] = $new_values['close'][ $name ];
		} // foreach $name
		
		if ( $templates_changed ) {
			$settings_changed = true;
			if ( false == MLAOptions::mla_put_markup_templates( $new_templates ) )
				$error_list .= "<br>ERROR: update of markup templates failed.";
		}
		
		if ( $settings_changed )
			$message = "MLA Gallery settings saved.\r\n";
		else
			$message = "MLA Gallery no changes detected.\r\n";
		
		$page_content = array(
			'message' => $message . $error_list,
			'body' => '' 
		);
		
		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;

		return $page_content;
	} // _save_gallery_settings
	
	/**
	 * Save View settings to the options table
 	 *
	 * @since 1.40
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_view_settings( ) {
		$message_list = '';
		
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'view' == $value['tab'] ) {
				$message_list .= self::_update_option_row( $key, $value );
			} // view option
		} // foreach mla_options
		
		$page_content = array(
			'message' => "View settings saved.\r\n",
			'body' => '' 
		);
		
		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;

		return $page_content;
	} // _save_view_settings
	
	/**
	 * Save Upload settings to the options table
 	 *
	 * @since 1.40
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_upload_settings( ) {
		$message_list = '';
		
		if ( ! isset( $_REQUEST[ MLA_OPTION_PREFIX . MLAOptions::MLA_ENABLE_UPLOAD_MIMES ] ) )		
			unset( $_REQUEST[ MLA_OPTION_PREFIX . MLAOptions::MLA_ENABLE_MLA_ICONS ] );
			
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'upload' == $value['tab'] ) {
				$message_list .= self::_update_option_row( $key, $value );
			} // upload option
		} // foreach mla_options
		
		$page_content = array(
			'message' => "Upload MIME Type settings saved.\r\n",
			'body' => '' 
		);
		
		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;

		return $page_content;
	} // _save_upload_settings
	
	/**
	 * Process custom field settings against all image attachments
	 * without saving the settings to the mla_option
 	 *
	 * @since 1.10
	 * @uses $_REQUEST if passed a NULL parameter
	 *
	 * @param	array | NULL	specific custom_field_mapping values 
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _process_custom_field_mapping( $settings = NULL ) {
		global $wpdb;
		
		if ( NULL == $settings ) {
			$settings = ( isset( $_REQUEST['custom_field_mapping'] ) ) ? $_REQUEST['custom_field_mapping'] : array();
			if ( isset( $settings[ MLAOptions::MLA_NEW_CUSTOM_FIELD ] ) )
				unset( $settings[ MLAOptions::MLA_NEW_CUSTOM_FIELD ] );
			if ( isset( $settings[ MLAOptions::MLA_NEW_CUSTOM_RULE ] ) )
				unset( $settings[ MLAOptions::MLA_NEW_CUSTOM_RULE ] );
		}
		
		if ( empty( $settings ) )
			return array(
				'message' => 'ERROR: No custom field mapping rules to process.',
				'body' => '' 
			);

		$examine_count = 0;
		$update_count = 0;
		$post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE `post_type` = 'attachment'" );

		foreach( $post_ids as $key => $post_id ) {
			$updates = MLAOptions::mla_evaluate_custom_field_mapping( (integer) $post_id, 'custom_field_mapping', $settings );

			$examine_count += 1;
			if ( ! empty( $updates ) ) {
				$results = MLAData::mla_update_item_postmeta( (integer) $post_id, $updates['custom_updates'] );
				if ( ! empty( $results ) )
					$update_count += 1;
			}
		} // foreach post
		
		if ( $update_count )
			$message = "Custom field mapping completed; {$examine_count} attachment(s) examined, {$update_count} updated.\r\n";
		else
			$message = "Custom field mapping completed; {$examine_count} attachment(s) examined, no changes detected.\r\n";
		
		return array(
			'message' => $message,
			'body' => '' 
		);
	} // _process_custom_field_mapping
	
	/**
	 * Delete a custom field from the wp_postmeta table
 	 *
	 * @since 1.10
	 *
	 * @param	array specific custom_field_mapping rule
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _delete_custom_field( $value ) {
		global $wpdb;

		$post_meta_ids = $wpdb->get_col( $wpdb->prepare( "SELECT meta_id FROM {$wpdb->postmeta} LEFT JOIN {$wpdb->posts} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ) WHERE {$wpdb->postmeta}.meta_key = '%s' AND {$wpdb->posts}.post_type = 'attachment'", $value['name'] ));
		foreach ( $post_meta_ids as $mid )
			delete_metadata_by_mid( 'post', $mid );

		$count = count( $post_meta_ids );
		if ( $count )
			return sprintf( 'Deleted custom field value from ' . _n('%s attachment.<br>', '%s attachments.<br>', $count), $count);
		else
			return 'No attachments contained this custom field.<br>';
	} // _delete_custom_field
	
	/**
	 * Save custom field settings to the options table
 	 *
	 * @since 1.10
	 * @uses $_REQUEST if passed a NULL parameter
	 *
	 * @param	array | NULL	specific custom_field_mapping values 
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_custom_field_settings( $new_values = NULL ) {
		$message_list = '';
		$option_messages = '';

		if ( NULL == $new_values ) {
			/*
			 * Start with any page-level options
			 */
			foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
				if ( 'custom_field' == $value['tab'] )
					$option_messages .= self::_update_option_row( $key, $value );
			}
	
			/*
			 * Add mapping options
			 */
			$new_values = ( isset( $_REQUEST['custom_field_mapping'] ) ) ? $_REQUEST['custom_field_mapping'] : array();
		} // NULL

		/*
		 * Uncomment this for debugging.
		 */
		// $message_list = $option_messages . '<br>';
		
		return array(
			'message' => $message_list . MLAOptions::mla_custom_field_option_handler( 'update', 'custom_field_mapping', MLAOptions::$mla_option_definitions['custom_field_mapping'], $new_values ),
			'body' => '' 
		);
	} // _save_custom_field_settings
	
	/**
	 * Process IPTC/EXIF standard field settings against all image attachments
	 * without saving the settings to the mla_option
 	 *
	 * @since 1.00
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _process_iptc_exif_standard( ) {
		if ( ! isset( $_REQUEST['iptc_exif_mapping']['standard'] ) )
			return array(
				'message' => 'ERROR: No standard field settings to process.',
				'body' => '' 
			);

		$examine_count = 0;
		$update_count = 0;
		
		$query = array( 'orderby' => 'none', 'post_parent' => 'all' ); //, 'post_mime_type' => 'image,application/*pdf*' );
		$posts = MLAShortcodes::mla_get_shortcode_attachments( 0, $query );
		
		if ( is_string( $posts ) )
			return array(
				'message' => $posts,
				'body' => '' 
			);

		foreach( $posts as $key => $post ) {
			$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $post, 'iptc_exif_standard_mapping', $_REQUEST['iptc_exif_mapping'] );

			$examine_count += 1;
			if ( ! empty( $updates ) ) {
				MLAData::mla_update_single_item( $post->ID, $updates );
				$update_count += 1;
			}
		} // foreach post
		
		if ( $update_count )
			$message = "IPTC/EXIF Standard field mapping completed; {$examine_count} attachment(s) examined, {$update_count} updated.\r\n";
		else
			$message = "IPTC/EXIF Standard field mapping completed; {$examine_count} attachment(s) examined, no changes detected.\r\n";
		
		return array(
			'message' => $message,
			'body' => '' 
		);
	} // _process_iptc_exif_standard
	
	/**
	 * Process IPTC/EXIF taxonomy term settings against all image attachments
	 * without saving the settings to the mla_option
 	 *
	 * @since 1.00
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _process_iptc_exif_taxonomy( ) {
		if ( ! isset( $_REQUEST['iptc_exif_mapping']['taxonomy'] ) )
			return array(
				'message' => 'ERROR: No taxonomy term settings to process.',
				'body' => '' 
			);

		$examine_count = 0;
		$update_count = 0;
		
		$query = array( 'orderby' => 'none', 'post_parent' => 'all' ); //, 'post_mime_type' => 'image,application/*pdf*' );
		$posts = MLAShortcodes::mla_get_shortcode_attachments( 0, $query );
		
		if ( is_string( $posts ) )
			return array(
				'message' => $posts,
				'body' => '' 
			);

		foreach( $posts as $key => $post ) {
			$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $post, 'iptc_exif_taxonomy_mapping', $_REQUEST['iptc_exif_mapping'] );

			$examine_count += 1;
			if ( ! empty( $updates ) ) {
				$results = MLAData::mla_update_single_item( $post->ID, array(), $updates['taxonomy_updates']['inputs'], $updates['taxonomy_updates']['actions'] );
				if ( stripos( $results['message'], 'updated.' ) )
					$update_count += 1;
			}
		} // foreach post
		
		if ( $update_count )
			$message = "IPTC/EXIF Taxonomy term mapping completed; {$examine_count} attachment(s) examined, {$update_count} updated.\r\n";
		else
			$message = "IPTC/EXIF Taxonomy term mapping completed; {$examine_count} attachment(s) examined, no changes detected.\r\n";
		
		return array(
			'message' => $message,
			'body' => '' 
		);
	} // _process_iptc_exif_taxonomy
	
	/**
	 * Process IPTC/EXIF custom field settings against all image attachments
	 * without saving the settings to the mla_option
 	 *
	 * @since 1.00
	 *
	 * @uses $_REQUEST if passed a NULL parameter
	 *
	 * @param	array | NULL	specific iptc_exif_custom_mapping values 
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _process_iptc_exif_custom( $settings = NULL ) {
		if ( NULL == $settings ) {
			$settings = ( isset( $_REQUEST['iptc_exif_mapping'] ) ) ? $_REQUEST['iptc_exif_mapping'] : array();
			if ( isset( $settings['custom'][ MLAOptions::MLA_NEW_CUSTOM_FIELD ] ) )
				unset( $settings['custom'][ MLAOptions::MLA_NEW_CUSTOM_FIELD ] );
			if ( isset( $settings['custom'][ MLAOptions::MLA_NEW_CUSTOM_RULE ] ) )
				unset( $settings['custom'][ MLAOptions::MLA_NEW_CUSTOM_RULE ] );
		}
		
		if ( empty( $settings['custom'] ) )
			return array(
				'message' => 'ERROR: No custom field settings to process.',
				'body' => '' 
			);

		$examine_count = 0;
		$update_count = 0;
		
		$query = array( 'orderby' => 'none', 'post_parent' => 'all' ); //, 'post_mime_type' => 'image,application/*pdf*' );
		$posts = MLAShortcodes::mla_get_shortcode_attachments( 0, $query );
		
		if ( is_string( $posts ) )
			return array(
				'message' => $posts,
				'body' => '' 
			);

		foreach( $posts as $key => $post ) {
			$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $post, 'iptc_exif_custom_mapping', $settings );

			$examine_count += 1;
			if ( ! empty( $updates ) ) {
				$results = MLAData::mla_update_single_item( $post->ID, $updates );
				if ( stripos( $results['message'], 'updated.' ) )
					$update_count += 1;
			}
		} // foreach post
		
		if ( $update_count )
			$message = "IPTC/EXIF custom field mapping completed; {$examine_count} attachment(s) examined, {$update_count} updated.\r\n";
		else
			$message = "IPTC/EXIF custom field mapping completed; {$examine_count} attachment(s) examined, no changes detected.\r\n";
		
		return array(
			'message' => $message,
			'body' => '' 
		);
	} // _process_iptc_exif_custom
	
	/**
	 * Save IPTC/EXIF custom field settings to the options table
 	 *
	 * @since 1.30
	 *
	 * @param	array	specific iptc_exif_custom_mapping values 
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_iptc_exif_custom_settings( $new_values ) {
		return array(
			'message' => MLAOptions::mla_iptc_exif_option_handler( 'update', 'iptc_exif_custom_mapping', MLAOptions::$mla_option_definitions['iptc_exif_mapping'], $new_values ),
			'body' => '' 
		);
	} // _save_iptc_exif_custom_settings
	
	/**
	 * Save IPTC/EXIF settings to the options table
 	 *
	 * @since 1.00
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_iptc_exif_settings( ) {
		$message_list = '';
		$option_messages = '';

		/*
		 * Start with any page-level options
		 */
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'iptc_exif' == $value['tab'] )
				$option_messages .= self::_update_option_row( $key, $value );
		}

		/*
		 * Uncomment this for debugging.
		 */
		// $message_list = $option_messages . '<br>';
		
		/*
		 * Add mapping options
		 */
		$new_values = ( isset( $_REQUEST['iptc_exif_mapping'] ) ) ? $_REQUEST['iptc_exif_mapping'] : array( 'standard' => array(), 'taxonomy' => array(), 'custom' => array() );

		return array(
			'message' => $message_list . MLAOptions::mla_iptc_exif_option_handler( 'update', 'iptc_exif_mapping', MLAOptions::$mla_option_definitions['iptc_exif_mapping'], $new_values ),
			'body' => '' 
		);
	} // _save_iptc_exif_settings
	
	/**
	 * Save General settings to the options table
 	 *
	 * @since 0.1
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_general_settings( ) {
		$message_list = '';
		
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'general' == $value['tab'] ) {
				switch ( $key ) {
					case MLAOptions::MLA_FEATURED_IN_TUNING:
						MLAOptions::$process_featured_in = ( 'disabled' != $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						break;
					case MLAOptions::MLA_INSERTED_IN_TUNING:
						MLAOptions::$process_inserted_in = ( 'disabled' != $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						break;
					case MLAOptions::MLA_GALLERY_IN_TUNING:
						MLAOptions::$process_gallery_in = ( 'disabled' != $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						
						if ( 'refresh' == $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) {
							MLAData::mla_flush_mla_galleries( MLAOptions::MLA_GALLERY_IN_TUNING );
							$message_list .= "<br>Gallery in - references updated.\r\n";
							$_REQUEST[ MLA_OPTION_PREFIX . $key ] = 'cached';
						}
						break;
					case MLAOptions::MLA_MLA_GALLERY_IN_TUNING:
						MLAOptions::$process_mla_gallery_in = ( 'disabled' != $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						
						if ( 'refresh' == $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) {
							MLAData::mla_flush_mla_galleries( MLAOptions::MLA_MLA_GALLERY_IN_TUNING );
							$message_list .= "<br>MLA Gallery in - references updated.\r\n";
							$_REQUEST[ MLA_OPTION_PREFIX . $key ] = 'cached';
						}
						break;
					default:
						//	ignore everything else
				} // switch

				$message_list .= self::_update_option_row( $key, $value );
			} // general option
		} // foreach mla_options
		
		$page_content = array(
			'message' => "General settings saved.\r\n",
			'body' => '' 
		);
		
		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;

		return $page_content;
	} // _save_general_settings
	
	/**
	 * Delete saved settings, restoring default values
 	 *
	 * @since 0.1
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _reset_general_settings( ) {
		$message_list = '';
		
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'custom' == $value['type'] ) {
				$message = MLAOptions::$value['reset']( 'reset', $key, $value, $_REQUEST );
			}
			elseif ( ('header' == $value['type']) || ('hidden' == $value['type']) ) {
				$message = '';
			}
			else {
				MLAOptions::mla_delete_option( $key );
				$message = '<br>delete_option(' . $key . ')';
			}
			
			$message_list .= $message;
		}
		
		$page_content = array(
			'message' => 'Settings reset to default values.',
			'body' => '' 
		);

		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;
		
		return $page_content;
	} // _reset_general_settings
} // class MLASettings
?>