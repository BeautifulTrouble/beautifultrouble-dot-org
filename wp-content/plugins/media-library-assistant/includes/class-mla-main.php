<?php
/**
 * Top-level functions for the Media Library Assistant
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/* 
 * The Meta Boxes functions are't automatically available to plugins.
 */
if ( !function_exists( 'post_categories_meta_box' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/meta-boxes.php' );
}

/**
 * Class MLA (Media Library Assistant) provides several enhancements to the handling
 * of images and files held in the WordPress Media Library.
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLA {

	/**
	 * Current version number
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const CURRENT_MLA_VERSION = '2.02';

	/**
	 * Slug for registering and enqueueing plugin style sheet
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const STYLESHEET_SLUG = 'mla-style';

	/**
	 * Slug for localizing and enqueueing JavaScript - edit single item page
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const JAVASCRIPT_SINGLE_EDIT_SLUG = 'mla-single-edit-scripts';

	/**
	 * Object name for localizing JavaScript - edit single item page
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const JAVASCRIPT_SINGLE_EDIT_OBJECT = 'mla_single_edit_vars';

	/**
	 * Slug for localizing and enqueueing JavaScript - MLA List Table
	 *
	 * @since 0.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_INLINE_EDIT_SLUG = 'mla-inline-edit-scripts';

	/**
	 * Object name for localizing JavaScript - MLA List Table
	 *
	 * @since 0.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_INLINE_EDIT_OBJECT = 'mla_inline_edit_vars';

	/**
	 * Slug for adding plugin submenu
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const ADMIN_PAGE_SLUG = 'mla-menu';

	/**
	 * Action name; uniquely identifies the nonce
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_NONCE = 'mla_admin';

	/**
	 * mla_admin_action value for permanently deleting a single item
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_DELETE = 'single_item_delete';

	/**
	 * mla_admin_action value for displaying a single item
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_EDIT_DISPLAY = 'single_item_edit_display';

	/**
	 * mla_admin_action value for updating a single item
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_EDIT_UPDATE = 'single_item_edit_update';

	/**
	 * mla_admin_action value for restoring a single item from the trash
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_RESTORE = 'single_item_restore';

	/**
	 * mla_admin_action value for moving a single item to the trash
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_TRASH = 'single_item_trash';

	/**
	 * mla_admin_action value for mapping Custom Field metadata
	 *
	 * @since 1.10
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_CUSTOM_FIELD_MAP = 'single_item_custom_field_map';

	/**
	 * mla_admin_action value for mapping IPTC/EXIF metadata
	 *
	 * @since 1.00
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_MAP = 'single_item_map';

	/**
	 * mla_admin_action value for setting an item's parent object
	 *
	 * @since 1.82
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SET_PARENT = 'set_parent';

	/**
	 * mla_admin_action value for searching taxonomy terms
	 *
	 * @since 1.90
	 *
	 * @var	string
	 */
	const MLA_ADMIN_TERMS_SEARCH = 'terms_search';

	/**
	 * Holds screen ids to match help text to corresponding screen
	 *
	 * @since 0.1
	 *
	 * @var	array
	 */
	private static $page_hooks = array();

	/**
	 * Initialization function, similar to __construct()
	 *
	 * This function contains add_action and add_filter calls
	 * to set up the Ajax handlers, enqueue JavaScript and CSS files, and 
	 * set up the Assistant submenu.
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function initialize( ) {
		add_action( 'admin_init', 'MLA::mla_admin_init_action' );
		add_action( 'admin_enqueue_scripts', 'MLA::mla_admin_enqueue_scripts_action' );
		add_action( 'admin_menu', 'MLA::mla_admin_menu_action' );
		add_filter( 'set-screen-option', 'MLA::mla_set_screen_option_filter', 10, 3 ); // $status, $option, $value
		add_filter( 'screen_options_show_screen', 'MLA::mla_screen_options_show_screen_filter', 10, 2 ); // $show_screen, $this
	}

	/**
	 * Load a plugin text domain
	 * 
	 * The "add_action" for this function is in mla-plugin-loader.php, because the "initialize"
	 * function above doesn't run in time.
	 * Defined as public because it's an action.
	 *
	 * @since 1.60
	 *
	 * @return	void
	 */
	public static function mla_plugins_loaded_action(){
		$text_domain = 'media-library-assistant';
		$locale = apply_filters( 'mla_plugin_locale', get_locale(), $text_domain );

		/*
		 * To override the plugin's translation files for one, some or all strings,
		 * create a sub-directory named 'media-library-assistant' in the WordPress
		 * WP_LANG_DIR (e.g., /wp-content/languages) directory.
		 */
		load_textdomain( $text_domain, trailingslashit( WP_LANG_DIR ) . $text_domain . '/' . $text_domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $text_domain, false, MLA_PLUGIN_BASENAME . '/languages/' );

		/*
		 * Now we can localize values in other plugin components
		 */
		MLAOptions::mla_localize_option_definitions_array();
		MLASettings::mla_localize_tablist();
		MLA_List_Table::mla_localize_default_columns_array();
		MLA_Upload_List_Table::mla_localize_default_columns_array();
		MLA_Upload_Optional_List_Table::mla_localize_default_columns_array();
		MLA_View_List_Table::mla_localize_default_columns_array();
	}

	/**
	 * Load the plugin's Ajax handler or process Edit Media update actions
	 *
	 * @since 0.20
	 *
	 * @return	void
	 */
	public static function mla_admin_init_action() {
//error_log( 'DEBUG: mla_admin_init_action $_REQUEST = ' . var_export( $_REQUEST, true ), 0 );
		/*
		 * Process secure file download requests
		 */
		if ( isset( $_REQUEST['mla_download_file'] ) && isset( $_REQUEST['mla_download_type'] ) ) {
			check_admin_referer( self::MLA_ADMIN_NONCE );
			self::_process_mla_download_file();
			exit();
		}

		/*
		 * Process row-level actions from the Edit Media screen
		 */
		if ( !empty( $_REQUEST['mla_admin_action'] ) ) {
			if ( isset( $_REQUEST['mla-set-parent-ajax-nonce'] ) ) {
				check_admin_referer( 'mla_find_posts', 'mla-set-parent-ajax-nonce' );
			} else {
				check_admin_referer( self::MLA_ADMIN_NONCE );
			}

			switch ( $_REQUEST['mla_admin_action'] ) {
				case self::MLA_ADMIN_SINGLE_CUSTOM_FIELD_MAP:
					do_action( 'mla_begin_mapping', 'single_custom', $_REQUEST['mla_item_ID'] );
					$updates = MLAOptions::mla_evaluate_custom_field_mapping( $_REQUEST['mla_item_ID'], 'single_attachment_mapping' );
					do_action( 'mla_end_mapping' );

					if ( !empty( $updates ) ) {
						$item_content = MLAData::mla_update_single_item( $_REQUEST['mla_item_ID'], $updates );
					}

					$view_args = isset( $_REQUEST['mla_source'] ) ? array( 'mla_source' => $_REQUEST['mla_source']) : array();
					wp_redirect( add_query_arg( $view_args, admin_url( 'post.php' ) . '?post=' . $_REQUEST['mla_item_ID'] . '&action=edit&message=101' ), 302 );
					exit;
				case self::MLA_ADMIN_SINGLE_MAP:
					$item = get_post( $_REQUEST['mla_item_ID'] );
					do_action( 'mla_begin_mapping', 'single_iptc_exif', $_REQUEST['mla_item_ID'] );
					$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $item, 'iptc_exif_mapping' );
					do_action( 'mla_end_mapping' );
					$page_content = MLAData::mla_update_single_item( $_REQUEST['mla_item_ID'], $updates );

					$view_args = isset( $_REQUEST['mla_source'] ) ? array( 'mla_source' => $_REQUEST['mla_source']) : array();
					wp_redirect( add_query_arg( $view_args, admin_url( 'post.php' ) . '?post=' . $_REQUEST['mla_item_ID'] . '&action=edit&message=102' ), 302 );
					exit;
				default:
					// ignore the rest
			} // switch ($_REQUEST['mla_admin_action'])
		} // (!empty($_REQUEST['mla_admin_action'])

		if ( ( defined('WP_ADMIN') && WP_ADMIN ) && ( defined('DOING_AJAX') && DOING_AJAX ) ) {
			add_action( 'wp_ajax_' . self::JAVASCRIPT_INLINE_EDIT_SLUG, 'MLA::mla_inline_edit_ajax_action' );
			add_action( 'wp_ajax_' . self::JAVASCRIPT_INLINE_EDIT_SLUG . '-set-parent', 'MLA::mla_set_parent_ajax_action' );
			add_action( 'wp_ajax_' . 'mla_find_posts', 'MLA::mla_find_posts_ajax_action' );
		}
	}

	/**
	 * Load the plugin's Style Sheet and Javascript files
	 *
	 * @since 0.1
	 *
	 * @param	string	Name of the page being loaded
	 *
	 * @return	void
	 */
	public static function mla_admin_enqueue_scripts_action( $page_hook ) {
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		if ( 'checked' != MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_DISPLAY_LIBRARY ) ) {
			wp_register_style( self::STYLESHEET_SLUG . '-nolibrary', MLA_PLUGIN_URL . 'css/mla-nolibrary.css', false, self::CURRENT_MLA_VERSION );
			wp_enqueue_style( self::STYLESHEET_SLUG . '-nolibrary' );
		}

		if ( 'edit-tags.php' == $page_hook ) {
			wp_register_style( self::STYLESHEET_SLUG, MLA_PLUGIN_URL . 'css/mla-edit-tags-style.css', false, self::CURRENT_MLA_VERSION );
			wp_enqueue_style( self::STYLESHEET_SLUG );
			return;
		}

		if ( 'media_page_' . self::ADMIN_PAGE_SLUG != $page_hook ) {
			return;
		}

		wp_register_style( self::STYLESHEET_SLUG, MLA_PLUGIN_URL . 'css/mla-style.css', false, self::CURRENT_MLA_VERSION );
		wp_enqueue_style( self::STYLESHEET_SLUG );

		wp_register_style( self::STYLESHEET_SLUG . '-set-parent', MLA_PLUGIN_URL . 'css/mla-style-set-parent.css', false, self::CURRENT_MLA_VERSION );
		wp_enqueue_style( self::STYLESHEET_SLUG . '-set-parent' );

		if ( isset( $_REQUEST['mla_admin_action'] ) && ( $_REQUEST['mla_admin_action'] == self::MLA_ADMIN_SINGLE_EDIT_DISPLAY ) ) {
			wp_enqueue_script( self::JAVASCRIPT_SINGLE_EDIT_SLUG, MLA_PLUGIN_URL . "js/mla-single-edit-scripts{$suffix}.js", 
				array( 'wp-lists', 'suggest', 'jquery' ), self::CURRENT_MLA_VERSION, false );
			$script_variables = array(
				'comma' => _x( ',', 'tag_delimiter', 'media-library-assistant' ),
				'Ajax_Url' => admin_url( 'admin-ajax.php' ) 
			);
			wp_localize_script( self::JAVASCRIPT_SINGLE_EDIT_SLUG, self::JAVASCRIPT_SINGLE_EDIT_OBJECT, $script_variables );
		} else {
			wp_enqueue_script( self::JAVASCRIPT_INLINE_EDIT_SLUG, MLA_PLUGIN_URL . "js/mla-inline-edit-scripts{$suffix}.js", 
				array( 'wp-lists', 'suggest', 'jquery' ), self::CURRENT_MLA_VERSION, false );

			wp_enqueue_script( self::JAVASCRIPT_INLINE_EDIT_SLUG . '-set-parent', MLA_PLUGIN_URL . "js/mla-set-parent-scripts{$suffix}.js", 
				array( 'wp-lists', 'suggest', 'jquery', self::JAVASCRIPT_INLINE_EDIT_SLUG ), self::CURRENT_MLA_VERSION, false );

			MLAModal::mla_add_terms_search_scripts();

			$fields = array( 'post_title', 'post_name', 'post_excerpt', 'post_content', 'image_alt', 'post_parent', 'post_parent_title', 'menu_order', 'post_author' );
			$custom_fields = MLAOptions::mla_custom_field_support( 'quick_edit' );
			$custom_fields = array_merge( $custom_fields, MLAOptions::mla_custom_field_support( 'bulk_edit' ) );
			foreach ($custom_fields as $slug => $label ) {
				$fields[] = $slug;
			}

			$fields = apply_filters( 'mla_list_table_inline_fields', $fields );

			$script_variables = array(
				'fields' => $fields,
				'ajaxFailError' => __( 'An ajax.fail error has occurred. Please reload the page and try again.', 'media-library-assistant' ),
				'ajaxDoneError' => __( 'An ajax.done error has occurred. Please reload the page and try again.', 'media-library-assistant' ),
				'error' => __( 'Error while saving the changes.', 'media-library-assistant' ),
				'ntdelTitle' => __( 'Remove From Bulk Edit', 'media-library-assistant' ),
				'noTitle' => __( '(no title)', 'media-library-assistant' ),
				'bulkTitle' => __( 'Bulk Edit items', 'media-library-assistant' ),
				'bulkWaiting' => __( 'Waiting', 'media-library-assistant' ),
				'bulkComplete' => __( 'Complete', 'media-library-assistant' ),
				'bulkUnchanged' => __( 'Unchanged', 'media-library-assistant' ),
				'bulkSuccess' => __( 'Succeeded', 'media-library-assistant' ),
				'bulkFailure' => __( 'Failed', 'media-library-assistant' ),
				'bulkCanceled' => __( 'CANCELED', 'media-library-assistant' ),
				'bulkChunkSize' => MLAOptions::mla_get_option( MLAOptions::MLA_BULK_CHUNK_SIZE ),
				'comma' => _x( ',', 'tag_delimiter', 'media-library-assistant' ),
				'ajax_action' => self::JAVASCRIPT_INLINE_EDIT_SLUG,
				'ajax_nonce' => wp_create_nonce( self::MLA_ADMIN_NONCE ) 
			);

			wp_localize_script( self::JAVASCRIPT_INLINE_EDIT_SLUG, self::JAVASCRIPT_INLINE_EDIT_OBJECT, $script_variables );
		}
	}

	/**
	 * Add the submenu pages
	 *
	 * Add a submenu page in the "Media" section,
	 * add settings page in the "Settings" section.
	 * add settings link in the Plugins section entry for MLA.
	 *
	 * For WordPress versions before 3.5, 
	 * add submenu page(s) for attachment taxonomies,
	 * add filter to clean up taxonomy submenu labels.
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function mla_admin_menu_action( ) {
		global $submenu;

		if ( 'checked' != MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_DISPLAY_LIBRARY ) ) {
			add_action( 'load-upload.php', 'MLA::mla_load_media_action' );
		}

		$page_title = MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_PAGE_TITLE );
		if ( empty( $page_title ) ) {
			$page_title = MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_PAGE_TITLE, true );
		}

		$menu_title = MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_MENU_TITLE );
		if ( empty( $menu_title ) ) {
			$menu_title = MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_MENU_TITLE, true );
		}

		$hook = add_submenu_page( 'upload.php', $page_title, $menu_title, 'upload_files', self::ADMIN_PAGE_SLUG, 'MLA::mla_render_admin_page' );
		add_action( 'load-' . $hook, 'MLA::mla_add_menu_options' );
		add_action( 'load-' . $hook, 'MLA::mla_add_help_tab' );
		self::$page_hooks[ $hook ] = $hook;

		$taxonomies = get_object_taxonomies( 'attachment', 'objects' );
		if ( !empty( $taxonomies ) ) {
			foreach ( $taxonomies as $tax_name => $tax_object ) {
				/*
				 * WordPress 3.5 adds native support for taxonomies
				 */
				if ( ! MLATest::$wordpress_3point5_plus ) {
					$hook = add_submenu_page( 'upload.php', $tax_object->label, $tax_object->label, 'manage_categories', 'mla-edit-tax-' . $tax_name, 'MLA::mla_edit_tax_redirect' );
					add_action( 'load-' . $hook, 'MLA::mla_edit_tax_redirect' );
				} // ! MLATest::$wordpress_3point5_plus

				/*
				 * The page_hook we need for taxonomy edits is slightly different
				 */
				$hook = 'edit-' . $tax_name;
				self::$page_hooks[ $hook ] = 't_' . $tax_name;
			} // foreach $taxonomies

			/*
			 * Load here, not 'load-edit-tags.php', to put our tab after the defaults
			 */
			add_action( 'admin_head-edit-tags.php', 'MLA::mla_add_help_tab' );
		}

		/*
		 * If we are suppressing the Media/Library submenu, force Media/Assistant to come first
		 */
		if ( 'checked' != MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_DISPLAY_LIBRARY ) ) {
			$menu_position = 4;
		} else {
			$menu_position = (integer) MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_ORDER );
		}

		if ( $menu_position && is_array( $submenu['upload.php'] ) ) {
			foreach ( $submenu['upload.php'] as $menu_order => $menu_item ) {
				if ( self::ADMIN_PAGE_SLUG == $menu_item[2] ) {
					$menu_item[2] = 'upload.php?page=' . self::ADMIN_PAGE_SLUG;
					$submenu['upload.php'][$menu_position] = $menu_item;
					unset( $submenu['upload.php'][$menu_order] );
					ksort( $submenu['upload.php'] );
					break;
				}
			}
		}

		add_filter( 'parent_file', 'MLA::mla_parent_file_filter', 10, 1 );
	}

	/**
	 * Redirect to Media/Assistant if Media/Library is hidden
	 *
	 * @since 1.60
	 *
	 * @return	void
	 */
	public static function mla_load_media_action( ) {
		if ( 'checked' != MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_DISPLAY_LIBRARY ) ) {
			$query_args = '?page=' . self::ADMIN_PAGE_SLUG;

			/*
			 * Compose a message if returning from the Edit Media screen
			 */
			if ( ! empty( $_GET['deleted'] ) && $deleted = absint( $_GET['deleted'] ) ) {
				$query_args .= '&mla_admin_message=' . urlencode( sprintf( _n( 'Item permanently deleted.', '%d items permanently deleted.', $deleted, 'media-library-assistant' ), number_format_i18n( $_GET['deleted'] ) ) );
			}

			if ( ! empty( $_GET['trashed'] ) && $trashed = absint( $_GET['trashed'] ) ) {
				/* translators: 1: post ID */
				$query_args .= '&mla_admin_message=' . urlencode( sprintf( __( 'Item %1$d moved to Trash.', 'media-library-assistant' ), $_GET['ids'] ) );
			}

			wp_redirect( admin_url( 'upload.php' ) . $query_args, 302 );
			exit;
		}
	}

	/**
	 * Add the "XX Entries per page" filter to the Screen Options tab
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function mla_add_menu_options( ) {
		$option = 'per_page';

		$args = array(
			 'label' => __( 'Entries per page', 'media-library-assistant' ),
			'default' => 10,
			'option' => 'mla_entries_per_page' 
		);

		add_screen_option( $option, $args );
	}

	/**
	 * Add contextual help tabs to all the MLA pages
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function mla_add_help_tab( ) {
		$screen = get_current_screen();
		/*
		 * Is this one of our pages?
		 */
		if ( !array_key_exists( $screen->id, self::$page_hooks ) ) {
			return;
		}

		if ( 'edit-tags' == $screen->base && 'attachment' != $screen->post_type ) {
			return;
		}

		$file_suffix = $screen->id;

		/*
		 * Override the screen suffix if we are going to display something other than the attachment table
		 */
		if ( isset( $_REQUEST['mla_admin_action'] ) ) {
			switch ( $_REQUEST['mla_admin_action'] ) {
				case self::MLA_ADMIN_SINGLE_EDIT_DISPLAY:
					$file_suffix = self::MLA_ADMIN_SINGLE_EDIT_DISPLAY;
					break;
			} // switch
		} else { // isset( $_REQUEST['mla_admin_action'] )
			/*
			 * Use a generic page for edit taxonomy screens
			 */
			if ( 't_' == substr( self::$page_hooks[ $file_suffix ], 0, 2 ) ) {
				$taxonomy = substr( self::$page_hooks[ $file_suffix ], 2 );
				switch ( $taxonomy ) {
					case 'attachment_category':
					case 'attachment_tag':
						break;
					default:
						$tax_object = get_taxonomy( $taxonomy );

						if ( $tax_object->hierarchical ) {
							$file_suffix = 'edit-hierarchical-taxonomy';
						} else {
							$file_suffix = 'edit-flat-taxonomy';
						}
				} // $taxonomy switch
			} // is taxonomy
		}

		$template_array = MLAData::mla_load_template( 'help-for-' . $file_suffix . '.tpl' );
		if ( empty( $template_array ) ) {
			return;
		}

		/*
		 * Don't add sidebar to the WordPress category and post_tag screens
		 */
		if ( ! ( 'edit-tags' == $screen->base && in_array( $screen->taxonomy, array( 'post_tag', 'category' ) ) ) ) {
			if ( !empty( $template_array['sidebar'] ) ) {
				$page_values = array( 'settingsURL' => admin_url('options-general.php') );
				$content = MLAData::mla_parse_template( $template_array['sidebar'], $page_values );
				$screen->set_help_sidebar( $content );
			}
		}
		unset( $template_array['sidebar'] );

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
				/* translators: 1: ERROR tag 2: function name 3: template key */
				error_log( sprintf( _x( '%1$s: %2$s discarding "%3$s"; no title/order', 'error_log', 'media-library-assistant' ), __( 'ERROR', 'media-library-assistant' ), 'mla_add_help_tab', $id ), 0 );
			}
		}

		ksort( $tab_array, SORT_NUMERIC );
		foreach ( $tab_array as $indx => $value ) {
			/*
			 * Don't add duplicate tabs to the WordPress category and post_tag screens
			 */
			if ( 'edit-tags' == $screen->base && in_array( $screen->taxonomy, array( 'post_tag', 'category' ) ) ) {
				if ( 'mla-attachments-column' != $value['id'] ) {
					continue;
				}
			}

			$page_values = array( 'settingsURL' => admin_url('options-general.php') );
			$value = MLAData::mla_parse_template( $value, $page_values );
			$screen->add_help_tab( $value );
		}
	}

	/**
	 * Only show screen options on the table-list screen
	 *
	 * @since 0.1
	 *
	 * @param	boolean	True to display "Screen Options", false to suppress them
	 * @param	string	Name of the page being loaded
	 *
	 * @return	boolean	True to display "Screen Options", false to suppress them
	 */
	public static function mla_screen_options_show_screen_filter( $show_screen, $this_screen ) {
		if ( isset( $_REQUEST['mla_admin_action'] ) && ( $_REQUEST['mla_admin_action'] == self::MLA_ADMIN_SINGLE_EDIT_DISPLAY ) ) {
			return false;
		}

		return $show_screen;
	}

	/**
	 * Save the "Entries per page" option set by this user
	 *
	 * @since 0.1
	 *
	 * @param	mixed	false or value returned by previous filter
	 * @param	string	Name of the option being changed
	 * @param	string	New value of the option
	 *
	 * @return	string|void	New value if this is our option, otherwise nothing
	 */
	public static function mla_set_screen_option_filter( $status, $option, $value ) {
		if ( 'mla_entries_per_page' == $option ) {
			return $value;
		} elseif ( $status ) {
			return $status;
		}
	}

	/**
	 * Redirect to the Edit Tags/Categories page
	 *
	 * The custom taxonomy add/edit submenu entries go to "upload.php" by default.
	 * This filter is the only way to redirect them to the correct WordPress page.
	 * The filter is not required for WordPress 3.5 and later.
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function mla_edit_tax_redirect( ) {
		/*
		 * WordPress 3.5 adds native support for taxonomies
		 */
		if ( MLATest::$wordpress_3point5_plus ) {
			return;
		}

		$screen = get_current_screen();

		if ( isset( $_REQUEST['page'] ) && ( substr( $_REQUEST['page'], 0, 13 ) == 'mla-edit-tax-' ) ) {
			$taxonomy = substr( $_REQUEST['page'], 13 );
			wp_redirect( admin_url( 'edit-tags.php?taxonomy=' . $taxonomy . '&post_type=attachment' ), 302 );
			exit;
		}
	}

	/**
	 * Cleanup menus for Edit Tags/Categories page
	 *
	 * For WordPress before 3.5, the submenu entries for custom taxonomies
	 * under the "Media" menu are not set up correctly by WordPress, so this
	 * function cleans them up, redirecting the request to the right WordPress
	 * page for editing/adding taxonomy terms.
	 * For WordPress 3.5 and later, the function fixes the submenu bolding when
	 * going to the Edit Media screen.
	 *
	 * @since 0.1
	 *
	 * @param	array	The top-level menu page
	 *
	 * @return	string	The updated top-level menu page
	 */
	public static function mla_parent_file_filter( $parent_file ) {
		global $submenu_file, $submenu, $hook_suffix;

		/*
		 * Make sure the "Assistant" submenu line is bolded if it's the default
		 */
		if ( 'media_page_' . self::ADMIN_PAGE_SLUG == $hook_suffix ) {
			$submenu_file = 'upload.php?page=' . self::ADMIN_PAGE_SLUG;
		}

		/*
		 * Make sure the "Assistant" submenu line is bolded if the Media/Library submenu is hidden
		 */
		if ( 'checked' != MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_DISPLAY_LIBRARY ) &&
		     'upload.php' == $parent_file && 'upload.php' == $submenu_file ) {
			$submenu_file = 'upload.php?page=' . self::ADMIN_PAGE_SLUG;
		}

		/*
		 * Make sure the "Assistant" submenu line is bolded when we go to the Edit Media page
		 */
		if ( isset( $_REQUEST['mla_source'] ) ) {
			$submenu_file = 'upload.php?page=' . self::ADMIN_PAGE_SLUG;
		}

		/*
		 * WordPress 3.5 adds native support for taxonomies
		 */
		if ( MLATest::$wordpress_3point5_plus ) {
			return $parent_file;
		}

		if ( isset( $_REQUEST['taxonomy'] ) ) {
			$taxonomies = get_object_taxonomies( 'attachment', 'objects' );

			foreach ( $taxonomies as $tax_name => $tax_object ) {
				if ( $_REQUEST['taxonomy'] == $tax_name ) {
					$mla_page = 'mla-edit-tax-' . $tax_name;
					$real_page = 'edit-tags.php?taxonomy=' . $tax_name . '&post_type=attachment';

					foreach ( $submenu['upload.php'] as $submenu_index => $submenu_entry ) {
						if ( $submenu_entry[ 2 ] == $mla_page ) {
							$submenu['upload.php'][ $submenu_index ][ 2 ] = $real_page;
							return 'upload.php';
						}
					}
				}
			}
		}

		return $parent_file;
	}

	/**
	 * Process secure file download
	 *
	 * Requires _wpnonce, mla_download_file and mla_download_type in $_REQUEST; mla_download_disposition is optional.
	 *
	 * @since 2.00
	 *
	 * @return	void	echos file contents and calls exit();
	 */
	private static function _process_mla_download_file() {
		if ( isset( $_REQUEST['mla_download_file'] ) && isset( $_REQUEST['mla_download_type'] ) ) {
			if( ini_get( 'zlib.output_compression' ) ) { 
				ini_set( 'zlib.output_compression', 'Off' );
			}
			
			$file_name = stripslashes( $_REQUEST['mla_download_file'] );
		
			header('Pragma: public'); 	// required
			header('Expires: 0');		// no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: '.gmdate ( 'D, d M Y H:i:s', filemtime ( $file_name ) ).' GMT');
			header('Cache-Control: private',false);
			header('Content-Type: '.$_REQUEST['mla_download_type']);
			header('Content-Disposition: attachment; filename="'.basename( $file_name ).'"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.filesize( $file_name ));	// provide file size
			header('Connection: close');
		
			readfile( $file_name );
		
			if ( isset( $_REQUEST['mla_download_disposition'] ) && 'delete' == $_REQUEST['mla_download_disposition'] ) {
				@unlink( $file_name );
			}
			
			exit();
		} else {
			$message = __( 'ERROR', 'media-library-assistant' ) . ': ' . 'download argument(s) not set.';
		}
		
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml">';
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<title>Download Error</title>';
		echo '</head>';
		echo '';
		echo '<body>';
		echo $message;
		echo '</body>';
		echo '</html> ';
		exit();
	}

	/**
	 * Process bulk edit area fields, which may contain a Content Template
	 *
	 * @since 1.80
	 *
	 * @param	integer	Current post ID
	 * @param	string	Field value as entered
	 *
	 * @return	string	Empty, or new value for the field
	 */
	private static function _process_bulk_value( $post_id, $bulk_value ) {
		$new_value = trim( $bulk_value );

		if ( 'template:[+empty+]' == $new_value ) {
			return NULL;
		} elseif ( 'template:' == substr( $new_value, 0, 9 ) ) {
			$data_value = array(
				'data_source' => 'template',
				'meta_name' => substr( $new_value, 9 ),
				'keep_existing' => false,
				'format' => 'raw',
				'option' => 'text' );

			$new_value =  MLAOptions::mla_get_data_source( $post_id, 'single_attachment_mapping', $data_value );
			if ( ' ' == $new_value ) {
				$new_value = '';
			}
		} elseif ( ! empty( $new_value ) ) {
			// preserve leading/trailing whitespace on non-empty entered values
			return $bulk_value;
		}

		return $new_value;
	}

	/**
	 * Process bulk action for one or more attachments
	 *
	 * @since 2.00
	 *
	 * @param	string	Bulk action slug: delete, edit, restore, trash, custom action
	 * @param	array	Form elements, e.g., from $_REQUEST
	 *
	 * @return	array	messages and page content: ( 'message', 'body', 'unchanged', 'success', 'failure', 'item_results' )
	 */
	public static function mla_process_bulk_action( $bulk_action, $request = NULL ) {
		$page_content = array( 'message' => '', 'body' => '', 'unchanged' => 0, 'success' => 0, 'failure' => 0, 'item_results' => array() );
		
		if ( NULL == $request ) {
			$request = $_REQUEST;
			$do_cleanup = true;
		} else {
			$do_cleanup = false;
		}
		
		if ( isset( $request['cb_attachment'] ) ) {
			$item_content = apply_filters( 'mla_list_table_begin_bulk_action', NULL, $bulk_action );
			if ( is_null( $item_content ) ) {
				$prevent_default = false;
			} else {
				$prevent_default = isset( $item_content['prevent_default'] ) ? $item_content['prevent_default'] : false;
			}

			if ( $prevent_default ) {
				if ( isset( $item_content['message'] ) ) {
					$page_content['message'] = $item_content['message'];
				}

				if ( isset( $item_content['body'] ) ) {
					$page_content['body'] = $item_content['body'];
				}
				
				return $page_content;
			}
			
			if ( !empty( $request['bulk_custom_field_map'] ) ) {
				do_action( 'mla_begin_mapping', 'bulk_custom', NULL );
			} elseif ( !empty( $request['bulk_map'] ) ) {
				do_action( 'mla_begin_mapping', 'bulk_iptc_exif', NULL );
			}

			foreach ( $request['cb_attachment'] as $index => $post_id ) {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					$page_content['message'] .= __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'You are not allowed to edit Attachment: ', 'media-library-assistant' ) . $post_id . '<br>';
					continue;
				}

				$item_content = apply_filters( 'mla_list_table_bulk_action', NULL, $bulk_action, $post_id );
				if ( is_null( $item_content ) ) {
					$prevent_default = false;
					$custom_message = '';
				} else {
					$prevent_default = isset( $item_content['prevent_default'] ) ? $item_content['prevent_default'] : false;
					$custom_message = isset( $item_content['message'] ) ? $item_content['message'] : '';
				}
	
				if ( ! $prevent_default ) {
					switch ( $bulk_action ) {
						case 'delete':
							$item_content = self::_delete_single_item( $post_id );
							break;
						case 'edit':
							if ( !empty( $request['bulk_custom_field_map'] ) ) {
								$updates = MLAOptions::mla_evaluate_custom_field_mapping( $post_id, 'single_attachment_mapping' );
								$item_content = MLAData::mla_update_single_item( $post_id, $updates );
								break;
							}

							if ( !empty( $request['bulk_map'] ) ) {
								$item = get_post( $post_id );
								$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $item, 'iptc_exif_mapping' );
								$item_content = MLAData::mla_update_single_item( $post_id, $updates );
								break;
							}

							/*
							 * Copy the edit form contents to $new_data
							 * Trim text values for testing purposes only
							 */
							$new_data = array() ;
							if ( isset( $request['post_title'] ) ) {
								$test_value = self::_process_bulk_value( $post_id, $request['post_title'] );
								if ( ! empty( $test_value ) ) {
									$new_data['post_title'] = $test_value;
								} elseif ( is_null( $test_value ) ) {
									$new_data['post_title'] = '';
								}
							}

							if ( isset( $request['post_excerpt'] ) ) {
								$test_value = self::_process_bulk_value( $post_id, $request['post_excerpt'] );
								if ( ! empty( $test_value ) ) {
									$new_data['post_excerpt'] = $test_value;
								} elseif ( is_null( $test_value ) ) {
									$new_data['post_excerpt'] = '';
								}
							}

							if ( isset( $request['post_content'] ) ) {
								$test_value = self::_process_bulk_value( $post_id, $request['post_content'] );
								if ( ! empty( $test_value ) ) {
									$new_data['post_content'] = $test_value;
								} elseif ( is_null( $test_value ) ) {
									$new_data['post_content'] = '';
								}
							}

							/*
							 * image_alt requires a separate key because some attachment types
							 * should not get a value, e.g., text or PDF documents
							 */
							if ( isset( $request['image_alt'] ) ) {
								$test_value = self::_process_bulk_value( $post_id, $request['image_alt'] );
								if ( ! empty( $test_value ) ) {
									$new_data['bulk_image_alt'] = $test_value;
								} elseif ( is_null( $test_value ) ) {
									$new_data['bulk_image_alt'] = '';
								}
							}

							if ( isset( $request['post_parent'] ) ) {
								if ( is_numeric( $request['post_parent'] ) ) {
									$new_data['post_parent'] = $request['post_parent'];
								}
							}

							if ( isset( $request['post_author'] ) ) {
								if ( -1 != $request['post_author'] ) {
										$new_data['post_author'] = $request['post_author'];
								}
							}

							if ( isset( $request['comment_status'] ) ) {
								if ( -1 != $request['comment_status'] ) {
										$new_data['comment_status'] = $request['comment_status'];
								}
							}

							if ( isset( $request['ping_status'] ) ) {
								if ( -1 != $request['ping_status'] ) {
										$new_data['ping_status'] = $request['ping_status'];
								}
							}

							/*
							 * Custom field support
							 */
							$custom_fields = array();
							foreach (MLAOptions::mla_custom_field_support( 'bulk_edit' ) as $slug => $label ) {
								if ( isset( $request[ $slug ] ) ) {
									$test_value = self::_process_bulk_value( $post_id, $request[ $slug ] );
									if ( ! empty( $test_value ) ) {
										$custom_fields[ $label ] = $test_value;
									} elseif ( is_null( $test_value ) ) {
										$custom_fields[ $label ] = '';
									}
								}
							} // foreach

							if ( ! empty( $custom_fields ) ) {
								$new_data[ 'custom_updates' ] = $custom_fields;
							}

							/*
							 * Taxonomy Support
							 */
							$tax_input = array();
							foreach ( $request['tax_input'] as $taxonomy => $terms ) {
								if ( ! empty( $request['tax_action'] ) ) {
									$tax_action = $request['tax_action'][ $taxonomy ];
								} else {
									$tax_action = 'replace';
								}

								/*
								 * Ignore empty updates
								 */
								if ( $hierarchical = is_array( $terms ) ) {
									if ( false !== ( $index = array_search( 0, $terms ) ) ) {
										unset( $terms[ $index ] );
									}
								} else {
									/*
									 * Parse out individual terms
									 */
									$comma = _x( ',', 'tag_delimiter', 'media-library-assistant' );
									if ( ',' !== $comma ) {
										$tags = str_replace( $comma, ',', $terms );
									}
						
									$fragments = explode( ',', trim( $terms, " \n\t\r\0\x0B," ) );
									$terms = array();
									foreach( $fragments as $fragment ) {
										$fragment = trim( wp_unslash( $fragment ) );
										if ( ! empty( $fragment ) ) {
											$terms[] = $fragment;
										}
									} // foreach fragment

									$terms = array_unique( $terms );
								}

								if ( empty( $terms ) && 'replace' != $tax_action ) {
									continue;
								}

								$post_terms = get_object_term_cache( $post_id, $taxonomy );
								if ( false === $post_terms ) {
									$post_terms = wp_get_object_terms( $post_id, $taxonomy );
									wp_cache_add( $post_id, $post_terms, $taxonomy . '_relationships' );
								}

								$current_terms = array();
								foreach( $post_terms as $new_term ) {
									if ( $hierarchical ) {
										$current_terms[ $new_term->term_id ] =  $new_term->term_id;
									} else {
										$current_terms[ $new_term->name ] =  $new_term->name;
									}
								}
								
								if ( 'add' == $tax_action ) {
									/*
									 * Add new terms; remove existing terms
									 */
									foreach ( $terms as $index => $new_term ) {
										if ( isset( $current_terms[ $new_term ] ) ) {
											unset( $terms[ $index ] );
										}
									}
									
									$do_update = ! empty( $terms );
								} elseif ( 'remove' == $tax_action ) {
									/*
									 * Remove only the existing terms
									 */
									foreach ( $terms as $index => $new_term ) {
										if ( ! isset( $current_terms[ $new_term ] ) ) {
											unset( $terms[ $index ] );
										}
									}
									
									$do_update = ! empty( $terms );
								} else { 
									/*
									 * Replace all terms; if the new terms match the term
									 * cache, we can skip the update
									 */
									foreach ( $terms as $new_term ) {
										if ( isset( $current_terms[ $new_term ] ) ) {
											unset( $current_terms[ $new_term ] );
										} else {
											$current_terms[ $new_term ] = $new_term;
											break; // not a match; stop checking
										}
									}
									
									$do_update = ! empty( $current_terms );
								}

								if ( $do_update ) {
									$tax_input[ $taxonomy ] = $terms;
								}
							} // foreach taxonomy

							$item_content = MLAData::mla_update_single_item( $post_id, $new_data, $tax_input, $request['tax_action'] );
							break;
						case 'restore':
							$item_content = self::_restore_single_item( $post_id );
							break;
						case 'trash':
							$item_content = self::_trash_single_item( $post_id );
							break;
						default:
							$item_content = apply_filters( 'mla_list_table_custom_bulk_action', NULL, $bulk_action, $post_id );
	
							if ( is_null( $item_content ) ) {
								$prevent_default = false;
								/* translators: 1: ERROR tag 2: bulk action */
								$custom_message = sprintf( __( '%1$s: Unknown bulk action %2$s', 'media-library-assistant' ), __( 'ERROR', 'media-library-assistant' ), $bulk_action );
							} else {
								$prevent_default = isset( $item_content['prevent_default'] ) ? $item_content['prevent_default'] : false;
							}
					} // switch $bulk_action
				} // ! $prevent_default
				
				// Custom action can set $prevent_default, so test again.
				if ( ! $prevent_default ) {
					if ( ! empty( $custom_message ) ) {
						$no_changes = sprintf( __( 'Item %1$d, no changes detected.', 'media-library-assistant' ), $post_id );
						if ( $no_changes == $item_content['message'] ) {
							$item_content['message'] = $custom_message;
						} else {
							$item_content['message'] = $custom_message . '<br>' . $item_content['message'];
						}
					}
	
					$page_content['item_results'][ $post_id ] = array( 'result' => 'unknown', 'message' => $item_content['message'] );
					if ( ! empty( $item_content['message'] ) ) {
						$page_content['message'] .= $item_content['message'] . '<br>';
	
						if ( false !== strpos( $item_content['message'], __( 'no changes detected', 'media-library-assistant' ) ) ) {
							$page_content['unchanged'] += 1;
							$page_content['item_results'][ $post_id ]['result'] = 'unchanged';
						} elseif (	 false !== strpos( $item_content['message'], __( 'ERROR', 'media-library-assistant' ) ) ) {
							$page_content['failure'] += 1;
							$page_content['item_results'][ $post_id ]['result'] = 'failure';
						} else {
							$page_content['success'] += 1;
							$page_content['item_results'][ $post_id ]['result'] = 'success';
						}
					}
				} // ! $prevent_default
			} // foreach cb_attachment

			if ( !empty( $request['bulk_custom_field_map'] ) || !empty( $request['bulk_map'] ) ) {
				do_action( 'mla_end_mapping' );
			}

			$item_content = apply_filters( 'mla_list_table_end_bulk_action', NULL, $bulk_action );
			if ( isset( $item_content['message'] ) ) {
				$page_content['message'] .= $item_content['message'];
			}

			if ( isset( $item_content['body'] ) ) {
				$page_content['body'] = $item_content['body'];
			}

			if ( $do_cleanup ) {
				unset( $_REQUEST['post_title'] );
				unset( $_REQUEST['post_excerpt'] );
				unset( $_REQUEST['post_content'] );
				unset( $_REQUEST['image_alt'] );
				unset( $_REQUEST['comment_status'] );
				unset( $_REQUEST['ping_status'] );
				unset( $_REQUEST['post_parent'] );
				unset( $_REQUEST['post_author'] );
				unset( $_REQUEST['tax_input'] );
				unset( $_REQUEST['tax_action'] );
	
				foreach (MLAOptions::mla_custom_field_support( 'bulk_edit' ) as $slug => $label )
					unset( $_REQUEST[ $slug ] );
	
				unset( $_REQUEST['cb_attachment'] );
			}
		} else { // isset cb_attachment
			/* translators: 1: action name, e.g., edit */
			$page_content['message'] = sprintf( __( 'Bulk Action %1$s - no items selected.', 'media-library-assistant' ), $bulk_action );
		}

		if ( $do_cleanup ) {
			unset( $_REQUEST['action'] );
			unset( $_REQUEST['bulk_custom_field_map'] );
			unset( $_REQUEST['bulk_map'] );
			unset( $_REQUEST['bulk_edit'] );
			unset( $_REQUEST['action2'] );
		}
		
		return $page_content;
	}

	/**
	 * Render the "Assistant" subpage in the Media section, using the list_table package
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function mla_render_admin_page( ) {
		/*
		 * WordPress class-wp-list-table.php doesn't look in hidden fields to set
		 * the month filter dropdown or sorting parameters
		 */
		if ( isset( $_REQUEST['m'] ) ) {
			$_GET['m'] = $_REQUEST['m'];
		}

		if ( isset( $_REQUEST['order'] ) ) {
			$_GET['order'] = $_REQUEST['order'];
		}

		if ( isset( $_REQUEST['orderby'] ) ) {
			$_GET['orderby'] = $_REQUEST['orderby'];
		}

		$bulk_action = self::_current_bulk_action();

		$page_title = MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_PAGE_TITLE );
		if ( empty( $page_title ) ) {
			$page_title = MLAOptions::mla_get_option( MLAOptions::MLA_SCREEN_PAGE_TITLE, true );
		}

		echo "<div class=\"wrap\">\n";
		echo "<div id=\"icon-upload\" class=\"icon32\"><br/></div>\n";
		echo "<h2>{$page_title}"; // trailing </h2> is action-specific

		if ( !current_user_can( 'upload_files' ) ) {
			echo " - Error</h2>\n";
			wp_die( __( 'You do not have permission to manage attachments.', 'media-library-assistant' ) );
		}

		$page_content = array(
			'message' => '',
			'body' => '' 
		);

		if ( !empty( $_REQUEST['mla_admin_message'] ) ) {
			$page_content['message'] = $_REQUEST['mla_admin_message'];
		}

		/*
		 * The category taxonomy (edit screens) is a special case because 
		 * post_categories_meta_box() changes the input name
		 */
		if ( !isset( $_REQUEST['tax_input'] ) ) {
			$_REQUEST['tax_input'] = array();
		}

		if ( isset( $_REQUEST['post_category'] ) ) {
			$_REQUEST['tax_input']['category'] = $_REQUEST['post_category'];
			unset ( $_REQUEST['post_category'] );
		}

		/*
		 * Process bulk actions that affect an array of items
		 */
		if ( $bulk_action && ( $bulk_action != 'none' ) ) {
			// bulk_refresh simply refreshes the page, ignoring other bulk actions
			if ( empty( $_REQUEST['bulk_refresh'] ) ) {
				$item_content = self::mla_process_bulk_action( $bulk_action );
				$page_content['message'] .= $item_content['message'] . '<br>';
			}
		} // $bulk_action

		if ( isset( $_REQUEST['clear_filter_by'] ) ) {
			unset( $_REQUEST['heading_suffix'] );
			unset( $_REQUEST['parent'] );
			unset( $_REQUEST['author'] );
			unset( $_REQUEST['mla-tax'] );
			unset( $_REQUEST['mla-term'] );
			unset( $_REQUEST['mla-metakey'] );
			unset( $_REQUEST['mla-metavalue'] );
			do_action( 'mla_list_table_clear_filter_by' );
		}

		/*
		 * Empty the Trash?
		 */
		if ( isset( $_REQUEST['delete_all'] ) ) {
			global $wpdb;

			$ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_status = %s", 'attachment', 'trash' ) );
			$delete_count = 0;
			foreach ( $ids as $post_id ) {
				$item_content = self::_delete_single_item( $post_id );

				if ( false !== strpos( $item_content['message'], __( 'ERROR', 'media-library-assistant' ) ) ) {
					$page_content['message'] .= $item_content['message'] . '<br>';
				} else {
					$delete_count++;
				}
			}

			if ( $delete_count ) {
				/* translators: 1: number of items */
				$page_content['message'] .= sprintf( _nx( '%s item deleted.', '%s items deleted.', $delete_count, 'deleted items', 'media-library-assistant' ), number_format_i18n( $delete_count ) );
			} else {
				$page_content['message'] .= __( 'No items deleted.', 'media-library-assistant' );
			}
		}

		/*
		 * Process row-level actions that affect a single item
		 */
		if ( !empty( $_REQUEST['mla_admin_action'] ) ) {
			check_admin_referer( self::MLA_ADMIN_NONCE );

			$page_content = apply_filters( 'mla_list_table_single_action', NULL, $_REQUEST['mla_admin_action'], ( isset( $_REQUEST['mla_item_ID'] ) ? $_REQUEST['mla_item_ID'] : 0 ) );
			if ( is_null( $page_content ) ) {
				$prevent_default = false;
				$custom_message = '';
			} else {
				$prevent_default = isset( $page_content['prevent_default'] ) ? $page_content['prevent_default'] : false;
				$custom_message = isset( $page_content['message'] ) ? $page_content['message'] : '';
			}

			if ( ! $prevent_default ) {
				switch ( $_REQUEST['mla_admin_action'] ) {
					case self::MLA_ADMIN_SINGLE_DELETE:
						$page_content = self::_delete_single_item( $_REQUEST['mla_item_ID'] );
						break;
					case self::MLA_ADMIN_SINGLE_EDIT_DISPLAY:
						echo ' - ' . __( 'Edit single item', 'media-library-assistant' ) . '</h2>';
						$page_content = self::_display_single_item( $_REQUEST['mla_item_ID'] );
						break;
					case self::MLA_ADMIN_SINGLE_EDIT_UPDATE:
						if ( !empty( $_REQUEST['update'] ) ) {
							$page_content = MLAData::mla_update_single_item( $_REQUEST['mla_item_ID'], $_REQUEST['attachments'][ $_REQUEST['mla_item_ID'] ], $_REQUEST['tax_input'] );
						} elseif ( !empty( $_REQUEST['map-iptc-exif'] ) ) {
							$item = get_post( $_REQUEST['mla_item_ID'] );
							do_action( 'mla_begin_mapping', 'single_iptc_exif', $_REQUEST['mla_item_ID'] );
							$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $item, 'iptc_exif_mapping' );
							do_action( 'mla_end_mapping' );
							$page_content = MLAData::mla_update_single_item( $_REQUEST['mla_item_ID'], $updates );
						} else {
							$page_content = array(
								/* translators: 1: post ID */
								'message' => sprintf( __( 'Item %1$d cancelled.', 'media-library-assistant' ), $_REQUEST['mla_item_ID'] ),
								'body' => '' 
							);
						}
						break;
					case self::MLA_ADMIN_SINGLE_RESTORE:
						$page_content = self::_restore_single_item( $_REQUEST['mla_item_ID'] );
						break;
					case self::MLA_ADMIN_SINGLE_TRASH:
						$page_content = self::_trash_single_item( $_REQUEST['mla_item_ID'] );
						break;
					case self::MLA_ADMIN_SET_PARENT:
						$new_data = array( 'post_parent' => $_REQUEST['found_post_id'] );
	
						foreach( $_REQUEST['children'] as $child ) {
							$item_content = MLAData::mla_update_single_item( $child, $new_data );
							$page_content['message'] .= $item_content['message'] . '<br>';
						}
	
						unset( $_REQUEST['parent'] );
						unset( $_REQUEST['children'] );
						unset( $_REQUEST['mla-set-parent-ajax-nonce'] );
						unset( $_REQUEST['mla_set_parent_search_text'] );
						unset( $_REQUEST['found_post_id'] );
						unset( $_REQUEST['mla-set-parent-submit'] );
						break;
					case self::MLA_ADMIN_TERMS_SEARCH:
						/*
						 * This will be handled as a database query argument,
						 * but validate the arguments here
						 */
						$mla_terms_search = isset( $_REQUEST['mla_terms_search'] ) ? $_REQUEST['mla_terms_search'] : array( 'phrases' => '', 'taxonomies' => array() );
						if ( ! is_array( $mla_terms_search ) || empty( $mla_terms_search['phrases'] ) || empty( $mla_terms_search['taxonomies'] ) ) {
							unset( $_REQUEST['mla_terms_search'] );
							$page_content = array(
								'message' => __( 'Empty Terms Search; ignored', 'media-library-assistant' ),
								'body' => '' 
							);
						} else {
							unset( $_REQUEST['mla_terms_search']['submit'] );
						}
						break;
					default:
						$page_content = apply_filters( 'mla_list_table_custom_single_action', NULL, $_REQUEST['mla_admin_action'], ( isset( $_REQUEST['mla_item_ID'] ) ? $_REQUEST['mla_item_ID'] : 0 ) );
						if ( is_null( $page_content ) ) {
							$page_content = array(
								/* translators: 1: row-level action, e.g., single_item_delete, single_item_edit */
								'message' => sprintf( __( 'Unknown mla_admin_action - "%1$s"', 'media-library-assistant' ), $_REQUEST['mla_admin_action'] ),
								'body' => '' 
							);
						} // Unknown mla_admin_action
				} // switch ($_REQUEST['mla_admin_action'])
			} // ! $prevent_default
			
			if ( ! empty( $custom_message ) ) {
				$page_content['message'] = $custom_message . $page_content['message'];
			}
		} // (!empty($_REQUEST['mla_admin_action'])

		if ( !empty( $page_content['body'] ) ) {
			if ( !empty( $page_content['message'] ) ) {
				if ( false !== strpos( $page_content['message'], __( 'ERROR', 'media-library-assistant' ) ) ) {
					$messages_class = 'mla_errors';
				} else {
					$messages_class = 'mla_messages';
				}

				echo "  <div class=\"{$messages_class}\"><p>\n";
				echo '    ' . $page_content['message'] . "\n";
				echo "  </p></div>\n"; // id="message"
			}

			echo $page_content['body'];
		} else {
			/*
			 * Display Attachments list
			 */
			if ( !empty( $_REQUEST['heading_suffix'] ) ) {
				echo ' - ' . esc_html( $_REQUEST['heading_suffix'] ) . "</h2>\n";
			} elseif ( !empty( $_REQUEST['mla_terms_search'] ) ) {
					echo ' - ' . __( 'term search results for', 'media-library-assistant' ) . ' "' . esc_html( stripslashes( trim( $_REQUEST['mla_terms_search']['phrases'] ) ) ) . "\"</h2>\n";
			} elseif ( !empty( $_REQUEST['s'] ) ) {
				if ( empty( $_REQUEST['mla_search_fields'] ) ) {
					echo ' - ' . __( 'post/parent results for', 'media-library-assistant' ) . ' "' . esc_html( stripslashes( trim( $_REQUEST['s'] ) ) ) . "\"</h2>\n";
				} else {
					echo ' - ' . __( 'search results for', 'media-library-assistant' ) . ' "' . esc_html( stripslashes( trim( $_REQUEST['s'] ) ) ) . "\"</h2>\n";
				}
			} else {
				echo "</h2>\n";
			}

			if ( !empty( $page_content['message'] ) ) {
				if ( false !== strpos( $page_content['message'], __( 'ERROR', 'media-library-assistant' ) ) ) {
					$messages_class = 'mla_errors';
				} else {
					$messages_class = 'mla_messages';
				}

				echo "  <div class=\"{$messages_class}\"><p>\n";
				echo '    ' . $page_content['message'] . "\n";
				echo "  </p></div>\n"; // id="message"
			}

			/*
			 * Optional - limit width of the views list
			 */
			$option_value = MLAOptions::mla_get_option( MLAOptions::MLA_TABLE_VIEWS_WIDTH );
			if ( !empty( $option_value ) ) {
				if ( is_numeric( $option_value ) ) {
					$option_value .= 'px';
				}

				echo "  <style type='text/css'>\n";
				echo "    ul.subsubsub {\n";
				echo "      width: {$option_value};\n";
				echo "      max-width: {$option_value};\n";
				echo "    }\n";
				echo "  </style>\n";
			}

			/*
			 * Optional - change the size of the thumbnail/icon images
			 */
			$option_value = MLAOptions::mla_get_option( MLAOptions::MLA_TABLE_ICON_SIZE );
			if ( !empty( $option_value ) ) {
				if ( is_numeric( $option_value ) ) {
					$option_value .= 'px';
				}

				if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_ENABLE_MLA_ICONS ) ) {
					$class = 'mla_media_thumbnail_64_64';
				} else {
					$class = 'mla_media_thumbnail_80_60';
				}

				echo "  <style type='text/css'>\n";
				echo "  #icon.column-icon {\n";
				echo "    width: {$option_value};\n";
				echo "    max-width: {$option_value};\n";
				//echo "    height: {$option_value};\n";
				//echo "    max-height: {$option_value};\n";
				echo "  }\n";
				echo "  img.{$class} {\n";
				echo "    width: {$option_value};\n";
				echo "    max-width: {$option_value};\n";
				echo "    height: {$option_value};\n";
				echo "    max-height: {$option_value};\n";
				echo "  }\n";
				echo "  </style>\n";
			}

			//	Create an instance of our package class...
			$MLAListTable = apply_filters( 'mla_list_table_new_instance', NULL );
			if ( is_null( $MLAListTable ) ) {
				$MLAListTable = new MLA_List_Table();
			}

			//	Fetch, prepare, sort, and filter our data...
			$MLAListTable->prepare_items();
			$MLAListTable->views();
			
			$view_arguments = MLA_List_Table::mla_submenu_arguments();
			if ( isset( $view_arguments['lang'] ) ) {
				$form_url = 'upload.php?page=' . self::ADMIN_PAGE_SLUG . '&lang=' . $view_arguments['lang'];
			} else {
				$form_url = 'upload.php?page=' . self::ADMIN_PAGE_SLUG;
			}

			//	 Forms are NOT created automatically, wrap the table in one to use features like bulk actions
			echo '<form action="' . admin_url( $form_url ) . '" method="post" id="mla-filter">' . "\n";
			/*
			 * Include the Search Media box
			 */
			require_once MLA_PLUGIN_PATH . 'includes/mla-main-search-box-template.php';

			/*
			 * We also need to ensure that the form posts back to our current page and remember all the view arguments
			 */
			echo sprintf( '<input type="hidden" name="page" value="%1$s" />', $_REQUEST['page'] ) . "\n";

			foreach ( $view_arguments as $key => $value ) {
				if ( 'meta_query' == $key ) {
					$value = stripslashes( $_REQUEST['meta_query'] );
				}

				/*
				 * Search box elements are already set up in the above "search-box"
				 * 'lang' has already been added to the form action attribute
				 */
				if ( in_array( $key, array( 's', 'mla_search_connector', 'mla_search_fields', 'lang' ) ) ) {
					continue;
				}

				if ( is_array( $value ) ) {
					foreach ( $value as $element_key => $element_value )
						echo sprintf( '<input type="hidden" name="%1$s[%2$s]" value="%3$s" />', $key, $element_key, esc_attr( $element_value ) ) . "\n";
				} else {
					echo sprintf( '<input type="hidden" name="%1$s" value="%2$s" />', $key, esc_attr( $value ) ) . "\n";
				}
			}

			//	 Now we can render the completed list table
			$MLAListTable->display();
			echo "</form><!-- id=mla-filter -->\n";

			/*
			 * Insert the hidden form and table for inline edits (quick & bulk)
			 */
			echo self::_build_inline_edit_form( $MLAListTable );

			echo "<div id=\"ajax-response\"></div>\n";
			echo "<br class=\"clear\" />\n";
			echo "</div><!-- class=wrap -->\n";
		} // display attachments list
	}

	/**
	 * Ajax handler to fetch candidates for the "Set Parent" popup window
	 *
	 * Adapted from wp_ajax_find_posts in /wp-admin/includes/ajax-actions.php.
	 * Adds filters for post type and pagination.
	 *
	 * @since 1.90
	 *
	 * @return	void	passes results to wp_send_json_success() for JSON encoding and transmission
	 */
	public static function mla_find_posts_ajax_action() {
		global $wpdb;

		check_ajax_referer( 'mla_find_posts' );

		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		unset( $post_types['attachment'] );

		$s = stripslashes( $_REQUEST['mla_set_parent_search_text'] );
		$count = isset( $_REQUEST['mla_set_parent_count'] ) ? $_REQUEST['mla_set_parent_count'] : 50;
		$paged = isset( $_REQUEST['mla_set_parent_paged'] ) ? $_REQUEST['mla_set_parent_paged'] : 1;

		$args = array(
			'post_type' => ( 'all' == $_REQUEST['mla_set_parent_post_type'] ) ? array_keys( $post_types ) : $_REQUEST['mla_set_parent_post_type'],
			'post_status' => 'any',
			'posts_per_page' => $count,
			'paged' => $paged,
		);

		if ( '' !== $s )
			$args['s'] = $s;

		$posts = get_posts( $args );

		if ( ( ! $posts ) && $paged > 1 ) {
			$args['paged'] = $paged = 1;
			$posts = get_posts( $args );
		}

		$found = count( $posts );

		$html = '<input name="mla_set_parent_count" id="mla-set-parent-count" type="hidden" value="' . $count . "\">\n";
		$html .= '<input name="mla_set_parent_paged" id="mla-set-parent-paged" type="hidden" value="' . $paged . "\">\n";
		$html .= '<input name="mla_set_parent_found" id="mla-set-parent-found" type="hidden" value="' . $found . "\">\n";

		$html .= '<table class="widefat"><thead><tr><th class="found-radio"><br /></th><th>'.__('Title').'</th><th class="no-break">'.__('Type').'</th><th class="no-break">'.__('Date').'</th><th class="no-break">'.__('Status').'</th></tr></thead><tbody>' . "\n";
		if ( $found ) {
			$alt = '';
			foreach ( $posts as $post ) {
				$title = trim( $post->post_title ) ? $post->post_title : __( '(no title)' );
				$alt = ( 'alternate' == $alt ) ? '' : 'alternate';

				switch ( $post->post_status ) {
					case 'publish' :
					case 'private' :
						$stat = __('Published');
						break;
					case 'future' :
						$stat = __('Scheduled');
						break;
					case 'pending' :
						$stat = __('Pending Review');
						break;
					case 'draft' :
						$stat = __('Draft');
						break;
					default:
						$stat = sanitize_text_field( $post->post_status );
				}

				if ( '0000-00-00 00:00:00' == $post->post_date ) {
					$time = '';
				} else {
					/* translators: date format in table columns, see http://php.net/date */
					$time = mysql2date(__('Y/m/d'), $post->post_date);
				}

				$html .= '<tr class="' . trim( 'found-posts ' . $alt ) . '"><td class="found-radio"><input type="radio" id="found-'.$post->ID.'" name="found_post_id" value="' . esc_attr($post->ID) . '"></td>';
				$html .= '<td><label for="found-'.$post->ID.'">' . esc_html( $title ) . '</label></td><td class="no-break">' . esc_html( $post_types[$post->post_type]->labels->singular_name ) . '</td><td class="no-break">'.esc_html( $time ) . '</td><td class="no-break">' . esc_html( $stat ). ' </td></tr>' . "\n";
			} // foreach post
		} else {
				$html .= '<tr class="' . trim( 'found-posts ' ) . '"><td class="found-radio">&nbsp;</td>';
				$html .= '<td colspan="4">No results found.</td></tr>' . "\n";
		}

		$html .= "</tbody></table>\n";

		wp_send_json_success( $html );
	}

	/**
	 * Ajax handler to set post_parent for a single attachment
	 *
	 * Adapted from wp_ajax_inline_save in /wp-admin/includes/ajax-actions.php
	 *
	 * @since 0.20
	 *
	 * @return	void	echo HTML <td> innerHTML for updated call or error message, then die()
	 */
	public static function mla_set_parent_ajax_action() {
		check_ajax_referer( self::MLA_ADMIN_NONCE, 'nonce' );

		if ( empty( $_REQUEST['post_ID'] ) ) {
			echo __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'No post ID found', 'media-library-assistant' );
			die();
		} else {
			$post_id = $_REQUEST['post_ID'];
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'You are not allowed to edit this Attachment.', 'media-library-assistant' ) );
		}

		$results = MLAData::mla_update_single_item( $post_id, $_REQUEST );
		if ( false !== strpos( $results['message'], __( 'ERROR', 'media-library-assistant' ) ) ) {
			wp_die( $results['message'] );
		}

		$new_item = (object) MLAData::mla_get_attachment_by_id( $post_id );

		//	Create an instance of our package class and echo the new HTML
		$MLAListTable = new MLA_List_Table();
		$MLAListTable->single_row( $new_item );
		die(); // this is required to return a proper result
	}

	/**
	 * Ajax handler for bulk editing and mapping
	 *
	 * @since 2.00
	 *
	 * @return	void	echo json results or error message, then die()
	 */
	private static function _bulk_edit_ajax_handler() {
		/*
		 * The category taxonomy (edit screens) is a special case because 
		 * post_categories_meta_box() changes the input name
		 */
		if ( !isset( $_REQUEST['tax_input'] ) ) {
			$_REQUEST['tax_input'] = array();
		}

		if ( isset( $_REQUEST['post_category'] ) ) {
			$_REQUEST['tax_input']['category'] = $_REQUEST['post_category'];
			unset ( $_REQUEST['post_category'] );
		}

		/*
		 * Convert bulk_action to the old button name/value variables
		 */
		switch ( $_REQUEST['bulk_action'] ) {
			case 'bulk_custom_field_map':
				$_REQUEST['bulk_custom_field_map'] = __( 'Map Custom Field Metadata', 'media-library-assistant' );
				break;
			case 'bulk_map':
				$_REQUEST['bulk_map'] = __( 'Map IPTC/EXIF metadata', 'media-library-assistant' );
				break;
			case 'bulk_edit':
				$_REQUEST['bulk_edit'] = __( 'Update', 'media-library-assistant' );
		}
		
		$item_content = (object) self::mla_process_bulk_action( 'edit' );
		wp_send_json_success( $item_content );
	}

	/**
	 * Ajax handler for inline editing
	 *
	 * Adapted for Quick Edit from wp_ajax_inline_save in /wp-admin/includes/ajax-actions.php
	 *
	 * @since 0.20
	 *
	 * @return	void	echo HTML <tr> markup for updated row or error message, then die()
	 */
	public static function mla_inline_edit_ajax_action() {
		set_current_screen( $_REQUEST['screen'] );

		check_ajax_referer( self::MLA_ADMIN_NONCE, 'nonce' );

		if ( ! empty( $_REQUEST['bulk_action'] ) ) {
			self::_bulk_edit_ajax_handler();
		}

		if ( empty( $_REQUEST['post_ID'] ) ) {
			echo __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'No post ID found', 'media-library-assistant' );
			die();
		} else {
			$post_id = $_REQUEST['post_ID'];
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'You are not allowed to edit this Attachment.', 'media-library-assistant' ) );
		}

		/*
		 * Custom field support
		 */
		$custom_fields = array();
		foreach (MLAOptions::mla_custom_field_support( 'quick_edit' ) as $slug => $label ) {
			if ( isset( $_REQUEST[ $slug ] ) ) {
				$custom_fields[ $label ] = $_REQUEST[ $slug ];
				unset ( $_REQUEST[ $slug ] );
			  }
		}

		if ( ! empty( $custom_fields ) ) {
			$_REQUEST[ 'custom_updates' ] = $custom_fields;
		}

		/*
		 * The category taxonomy is a special case because post_categories_meta_box() changes the input name
		 */
		if ( !isset( $_REQUEST['tax_input'] ) ) {
			$_REQUEST['tax_input'] = array();
		}

		if ( isset( $_REQUEST['post_category'] ) ) {
			$_REQUEST['tax_input']['category'] = $_REQUEST['post_category'];
			unset ( $_REQUEST['post_category'] );
		}

		if ( ! empty( $_REQUEST['tax_input'] ) ) {
			/*
			 * Flat taxonomy strings must be cleaned up and duplicates removed
			 */
			$tax_output = array();
			$tax_input = $_REQUEST['tax_input'];
			foreach ( $tax_input as $tax_name => $tax_value ) {
				if ( ! is_array( $tax_value ) ) {
					$comma = _x( ',', 'tag_delimiter', 'media-library-assistant' );
					if ( ',' != $comma ) {
						$tax_value = str_replace( $comma, ',', $tax_value );
					}

					$tax_value = preg_replace( '#\s*,\s*#', ',', $tax_value );
					$tax_value = preg_replace( '#,+#', ',', $tax_value );
					$tax_value = preg_replace( '#[,\s]+$#', '', $tax_value );
					$tax_value = preg_replace( '#^[,\s]+#', '', $tax_value );

					if ( ',' != $comma ) {
						$tax_value = str_replace( ',', $comma, $tax_value );
					}

					$tax_array = array();
					$dedup_array = explode( $comma, $tax_value );
					foreach ( $dedup_array as $tax_value )
						$tax_array [$tax_value] = $tax_value;

					$tax_value = implode( $comma, $tax_array );
				} // ! array( $tax_value )

				$tax_output[$tax_name] = $tax_value;
			} // foreach $tax_input
		} else { // ! empty( $_REQUEST['tax_input'] )
			$tax_output = NULL;
		}

		$item_content = apply_filters( 'mla_list_table_inline_action', NULL, $post_id );
		if ( is_null( $item_content ) ) {
			$prevent_default = false;
			$custom_message = '';
		} else {
			$prevent_default = isset( $item_content['prevent_default'] ) ? $item_content['prevent_default'] : false;
			$custom_message = isset( $item_content['message'] ) ? $page_content['message'] : '';
		}

		if ( ! $prevent_default ) {
			$results = MLAData::mla_update_single_item( $post_id, $_REQUEST, $tax_output );
		}
	
		$new_item = (object) MLAData::mla_get_attachment_by_id( $post_id );

		//	Create an instance of our package class and echo the new HTML
		$MLAListTable = new MLA_List_Table();
		$MLAListTable->single_row( $new_item );
		die(); // this is required to return a proper result
	}

	/**
	 * Compose a Post Type Options list with current selection
 	 *
	 * @since 1.90
	 * @uses $mla_option_templates contains row and table templates
	 *
	 * @param	array 	template parts
	 * @param	string 	current selection or 'all' (default)
	 *
	 * @return	string	HTML markup with select field options
	 */
	private static function _compose_post_type_select( &$templates, $selection = 'all' ) {
		$option_template = $templates['post-type-select-option'];
		$option_values = array (
			'selected' => ( 'all' == $selection ) ? 'selected="selected"' : '',
			'value' => 'all',
			'text' => '&mdash; ' . __( 'All Post Types', 'media-library-assistant' ) . ' &mdash;'
		);
		$options = MLAData::mla_parse_template( $option_template, $option_values );

		$post_types = get_post_types( array( 'public' => true ), 'objects' );	
		unset( $post_types['attachment'] );

		foreach ( $post_types as $key => $value ) {
			$option_values = array (
				'selected' => ( $key == $selection ) ? 'selected="selected"' : '',
				'value' => $key,
				'text' => $value->labels->name
			);

			$options .= MLAData::mla_parse_template( $option_template, $option_values );					
		} // foreach post_type

		$select_template = $templates['post-type-select'];
		$select_values = array (
			'options' => $options,
		);
		$select = MLAData::mla_parse_template( $select_template, $select_values );
		return $select;
	} // _compose_post_type_select

	/**
	 * Build the hidden form for the "Set Parent" popup modal window
	 *
	 * @since 1.90
	 *
	 * @param	boolean	true to return complete form, false to return mla-set-parent-div
	 *
	 * @return	string	HTML <form> markup for hidden form
	 */
	public static function mla_set_parent_form( $return_form = true ) {
		$set_parent_template = MLAData::mla_load_template( 'admin-set-parent-form.tpl' );
		if ( ! is_array( $set_parent_template ) ) {
			/* translators: 1: ERROR tag 2: function name 3: non-array value */
			error_log( sprintf( _x( '%1$s: %2$s non-array "%3$s"', 'error_log', 'media-library-assistant' ), __( 'ERROR', 'media-library-assistant' ), 'MLA::_build_inline_edit_form', var_export( $set_parent_template, true ) ), 0 );
			return '';
		}

		$page_values = array(
			'Select Parent' => __( 'Select Parent', 'media-library-assistant' ),
			'Search' => __( 'Search', 'media-library-assistant' ),
			'post_type_dropdown' => self::_compose_post_type_select( $set_parent_template, 'all' ),
			'For' => __( 'For', 'media-library-assistant' ),
			'Previous' => '&laquo;',
			'Next' => '&raquo;',
			'count' => '50',
			'paged' => '1',
			'found' => '0',
			'Title' => __( 'Title', 'media-library-assistant' ),
			'Type' => __( 'Type', 'media-library-assistant' ),
			'Date' => __( 'Date', 'media-library-assistant' ),
			'Status' => __( 'Status', 'media-library-assistant' ),
			'Unattached' => __( 'Unattached', 'media-library-assistant' ),
			'mla_find_posts_nonce' => wp_nonce_field( 'mla_find_posts', 'mla-set-parent-ajax-nonce', false, false ),
		);

		ob_start();
		submit_button( __( 'Cancel', 'media-library-assistant' ), 'button-secondary cancel alignleft', 'mla-set-parent-cancel', false );
		$page_values['mla_set_parent_cancel'] = ob_get_clean();

		ob_start();
		submit_button( __( 'Update', 'media-library-assistant' ), 'button-primary alignright', 'mla-set-parent-submit', false );
		$page_values['mla_set_parent_update'] = ob_get_clean();

		$set_parent_div = MLAData::mla_parse_template( $set_parent_template['mla-set-parent-div'], $page_values );

		if ( ! $return_form ) {
			return $set_parent_div;
		}

		$page_values = array(
			'mla_set_parent_url' => esc_url( add_query_arg( array_merge( MLA_List_Table::mla_submenu_arguments( false ), array( 'page' => MLA::ADMIN_PAGE_SLUG ) ), admin_url( 'upload.php' ) ) ),
			'mla_set_parent_action' => self::MLA_ADMIN_SET_PARENT,
			'wpnonce' => wp_nonce_field( self::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'mla_set_parent_div' => $set_parent_div,
		);

		$set_parent_form = MLAData::mla_parse_template( $set_parent_template['mla-set-parent-form'], $page_values );

		return $set_parent_form;
	}

	/**
	 * Build the hidden row templates for inline editing (quick and bulk edit)
	 *
	 * inspired by inline_edit() in wp-admin\includes\class-wp-posts-list-table.php.
	 *
	 * @since 0.20
	 *
	 * @param	object	MLA List Table object
	 *
	 * @return	string	HTML <form> markup for hidden rows
	 */
	private static function _build_inline_edit_form( $MLAListTable ) {
		$taxonomies = get_object_taxonomies( 'attachment', 'objects' );

		$hierarchical_taxonomies = array();
		$flat_taxonomies = array();
		foreach ( $taxonomies as $tax_name => $tax_object ) {
			if ( $tax_object->hierarchical && $tax_object->show_ui && MLAOptions::mla_taxonomy_support($tax_name, 'quick-edit') ) {
				$hierarchical_taxonomies[$tax_name] = $tax_object;
			} elseif ( $tax_object->show_ui && MLAOptions::mla_taxonomy_support($tax_name, 'quick-edit') ) {
				$flat_taxonomies[$tax_name] = $tax_object;
			}
		}

		$page_template_array = MLAData::mla_load_template( 'admin-inline-edit-form.tpl' );
		if ( ! is_array( $page_template_array ) ) {
			/* translators: 1: ERROR tag 2: function name 3: non-array value */
			error_log( sprintf( _x( '%1$s: %2$s non-array "%3$s"', 'error_log', 'media-library-assistant' ), __( 'ERROR', 'media-library-assistant' ), 'MLA::_build_inline_edit_form', var_export( $page_template_array, true ) ), 0 );
			return '';
		}

		if ( $authors = self::mla_authors_dropdown() ) {
			$authors_dropdown  = '              <label class="inline-edit-author">' . "\n";
			$authors_dropdown .= '                <span class="title">' . __( 'Author', 'media-library-assistant' ) . '</span>' . "\n";
			$authors_dropdown .= $authors . "\n";
			$authors_dropdown .= '              </label>' . "\n";
		} else {
			$authors_dropdown = '';
		}

		$custom_fields = '';
		foreach (MLAOptions::mla_custom_field_support( 'quick_edit' ) as $slug => $label ) {
			  $page_values = array(
				  'slug' => $slug,
				  'label' => esc_attr( $label ),
			  );
			  $custom_fields .= MLAData::mla_parse_template( $page_template_array['custom_field'], $page_values );
		}

		/*
		 * The middle column contains the hierarchical taxonomies, e.g., Attachment Category
		 */
		$quick_middle_column = '';
		$bulk_middle_column = '';

		if ( count( $hierarchical_taxonomies ) ) {
			$quick_category_blocks = '';
			$bulk_category_blocks = '';

			foreach ( $hierarchical_taxonomies as $tax_name => $tax_object ) {
				if ( current_user_can( $tax_object->cap->assign_terms ) ) {
				  ob_start();
				  wp_terms_checklist( NULL, array( 'taxonomy' => $tax_name ) );
				  $tax_checklist = ob_get_contents();
				  ob_end_clean();
  
				  $page_values = array(
					  'tax_html' => esc_html( $tax_object->labels->name ),
					  'more' => __( 'more', 'media-library-assistant' ),
					  'less' => __( 'less', 'media-library-assistant' ),
					  'tax_attr' => esc_attr( $tax_name ),
					  'tax_checklist' => $tax_checklist,
					  'Add' => __( 'Add', 'media-library-assistant' ),
					  'Remove' => __( 'Remove', 'media-library-assistant' ),
					  'Replace' => __( 'Replace', 'media-library-assistant' ),
				  );
				  $category_block = MLAData::mla_parse_template( $page_template_array['category_block'], $page_values );
				  $taxonomy_options = MLAData::mla_parse_template( $page_template_array['taxonomy_options'], $page_values );
  
				  $quick_category_blocks .= $category_block;
				  $bulk_category_blocks .= $category_block . $taxonomy_options;
				} // current_user_can
			} // foreach $hierarchical_taxonomies

			$page_values = array(
				'category_blocks' => $quick_category_blocks
			);
			$quick_middle_column = MLAData::mla_parse_template( $page_template_array['category_fieldset'], $page_values );

			$page_values = array(
				'category_blocks' => $bulk_category_blocks
			);
			$bulk_middle_column = MLAData::mla_parse_template( $page_template_array['category_fieldset'], $page_values );
		} // count( $hierarchical_taxonomies )

		/*
		 * The right-hand column contains the flat taxonomies, e.g., Attachment Tag
		 */
		$quick_right_column = '';
		$bulk_right_column = '';

		if ( count( $flat_taxonomies ) ) {
			$quick_tag_blocks = '';
			$bulk_tag_blocks = '';

			foreach ( $flat_taxonomies as $tax_name => $tax_object ) {
				if ( current_user_can( $tax_object->cap->assign_terms ) ) {
					$page_values = array(
						'tax_html' => esc_html( $tax_object->labels->name ),
						'tax_attr' => esc_attr( $tax_name ),
						'Add' => __( 'Add', 'media-library-assistant' ),
						'Remove' => __( 'Remove', 'media-library-assistant' ),
						'Replace' => __( 'Replace', 'media-library-assistant' ),
					);
					$tag_block = MLAData::mla_parse_template( $page_template_array['tag_block'], $page_values );
					$taxonomy_options = MLAData::mla_parse_template( $page_template_array['taxonomy_options'], $page_values );

				$quick_tag_blocks .= $tag_block;
				$bulk_tag_blocks .= $tag_block . $taxonomy_options;
				} // current_user_can
			} // foreach $flat_taxonomies

			$page_values = array(
				'tag_blocks' => $quick_tag_blocks
			);
			$quick_right_column = MLAData::mla_parse_template( $page_template_array['tag_fieldset'], $page_values );

			$page_values = array(
				'tag_blocks' => $bulk_tag_blocks
			);
			$bulk_right_column = MLAData::mla_parse_template( $page_template_array['tag_fieldset'], $page_values );
		} // count( $flat_taxonomies )

		if ( $authors = self::mla_authors_dropdown( -1 ) ) {
			$bulk_authors_dropdown  = '              <label class="inline-edit-author alignright">' . "\n";
			$bulk_authors_dropdown .= '                <span class="title">' . __( 'Author', 'media-library-assistant' ) . '</span>' . "\n";
			$bulk_authors_dropdown .= $authors . "\n";
			$bulk_authors_dropdown .= '              </label>' . "\n";
		} else {
			$bulk_authors_dropdown = '';
		}

		$bulk_custom_fields = '';
		foreach (MLAOptions::mla_custom_field_support( 'bulk_edit' ) as $slug => $label ) {
			  $page_values = array(
				  'slug' => $slug,
				  'label' => esc_attr( $label ),
			  );
			  $bulk_custom_fields .= MLAData::mla_parse_template( $page_template_array['custom_field'], $page_values );
		}

		$set_parent_form = MLA::mla_set_parent_form();

		$page_values = array(
			'colspan' => count( $MLAListTable->get_columns() ),
			'Quick Edit' => __( 'Quick Edit', 'media-library-assistant' ),
			'Title' => __( 'Title', 'media-library-assistant' ),
			'Name/Slug' => __( 'Name/Slug', 'media-library-assistant' ),
			'Caption' => __( 'Caption', 'media-library-assistant' ),
			'Description' => __( 'Description', 'media-library-assistant' ),
			'ALT Text' => __( 'ALT Text', 'media-library-assistant' ),
			'Parent ID' => __( 'Parent ID', 'media-library-assistant' ),
			'Select' => __( 'Select', 'media-library-assistant' ),
			'Menu Order' => __( 'Menu Order', 'media-library-assistant' ),
			'authors' => $authors_dropdown,
			'custom_fields' => $custom_fields,
			'quick_middle_column' => $quick_middle_column,
			'quick_right_column' => $quick_right_column,
			'Cancel' => __( 'Cancel', 'media-library-assistant' ),
			'Update' => __( 'Update', 'media-library-assistant' ),
			'Bulk Edit' => __( 'Bulk Edit', 'media-library-assistant' ),
			'bulk_middle_column' => $bulk_middle_column,
			'bulk_right_column' => $bulk_right_column,
			'bulk_authors' => $bulk_authors_dropdown,
			'Comments' => __( 'Comments', 'media-library-assistant' ),
			'Pings' => __( 'Pings', 'media-library-assistant' ),
			'No Change' => __( 'No Change', 'media-library-assistant' ),
			'Allow' => __( 'Allow', 'media-library-assistant' ),
			'Do not allow' => __( 'Do not allow', 'media-library-assistant' ),
			'bulk_custom_fields' => $bulk_custom_fields,
			'Map IPTC/EXIF metadata' =>  __( 'Map IPTC/EXIF metadata', 'media-library-assistant' ),
			'Map Custom Field metadata' =>  __( 'Map Custom Field Metadata', 'media-library-assistant' ),
			'Bulk Waiting' =>  __( 'Waiting', 'media-library-assistant' ),
			'Bulk Running' =>  __( 'In-process', 'media-library-assistant' ),
			'Bulk Complete' =>  __( 'Complete', 'media-library-assistant' ),
			'Refresh' =>  __( 'Refresh', 'media-library-assistant' ),
			'set_parent_form' => $set_parent_form,
		);
		
		$page_values = apply_filters( 'mla_list_table_inline_values', $page_values );
		$page_template = apply_filters( 'mla_list_table_inline_template', $page_template_array['page'] );
		$parse_value = MLAData::mla_parse_template( $page_template, $page_values );
		return apply_filters( 'mla_list_table_inline_parse', $parse_value, $page_template, $page_values );
	}

	/**
	 * Get the edit Authors dropdown box, if user has suitable permissions
	 *
	 * @since 0.20
	 *
	 * @param	integer	Optional User ID of the current author, default 0
	 * @param	string	Optional HTML name attribute, default 'post_author'
	 * @param	string	Optional HTML class attribute, default 'authors'
	 *
	 * @return string|false HTML markup for the dropdown field or False
	 */
	public static function mla_authors_dropdown( $author = 0, $name = 'post_author', $class = 'authors' ) {
		$post_type_object = get_post_type_object('attachment');
		if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) ) {
			$users_opt = array(
				'hide_if_only_one_author' => false,
				'who' => 'authors',
				'name' => $name,
				'class'=> $class,
				'multi' => 1,
				'echo' => 0
			);

			if ( $author > 0 ) {
				$users_opt['selected'] = $author;
				$users_opt['include_selected'] = true;
			} elseif ( -1 == $author ) {
				$users_opt['show_option_none'] = '&mdash; ' . __( 'No Change', 'media-library-assistant' ) . ' &mdash;';
			}

			if ( $authors = wp_dropdown_users( $users_opt ) ) {
				return $authors;
			}
		}

		return false;
	}

	/**
	 * Get the current action selected from the bulk actions dropdown
	 *
	 * @since 0.1
	 *
	 * @return string|false The action name or False if no action was selected
	 */
	private static function _current_bulk_action( )	{
		$action = false;

		if ( isset( $_REQUEST['action'] ) ) {
			if ( -1 != $_REQUEST['action'] ) {
				return $_REQUEST['action'];
			} else {
				$action = 'none';
			}
		} // isset action

		if ( isset( $_REQUEST['action2'] ) ) {
			if ( -1 != $_REQUEST['action2'] ) {
				return $_REQUEST['action2'];
			} else {
				$action = 'none';
			}
		} // isset action2

		return $action;
	}

	/**
	 * Delete a single item permanently
	 * 
	 * @since 0.1
	 * 
	 * @param	array The form POST data
	 *
	 * @return	array success/failure message and NULL content
	 */
	private static function _delete_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) ) {
			return array(
				'message' => __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'You are not allowed to delete this item.', 'media-library-assistant' ),
				'body' => '' 
			);
		}

		if ( !wp_delete_attachment( $post_id, true ) ) {
			return array(
				/* translators: 1: ERROR tag 2: post ID */
				'message' => sprintf( __( '%1$s: Item %2$d could NOT be deleted.', 'media-library-assistant' ), __( 'ERROR', 'media-library-assistant' ), $post_id ),
				'body' => '' 
			);
		}

		return array(
			/* translators: 1: post ID */
			'message' => sprintf( __( 'Item %1$d permanently deleted.', 'media-library-assistant' ), $post_id ),
			'body' => '' 
		);
	}

	/**
	 * Display a single item sub page; prepare the form to 
	 * change the meta data for a single attachment.
	 * 
	 * This function is not used in WordPress 3.5 and later.
	 *
	 * @since 0.1
	 * 
	 * @param	integer	The WordPress Post ID of the attachment item
	 *
	 * @return	array	message and/or HTML content
	 */
	private static function _display_single_item( $post_id ) {
		global $post;

		/*
		 * This function sets the global $post
		 */
		$post_data = MLAData::mla_get_attachment_by_id( $post_id );
		if ( !isset( $post_data ) ) {
			return array(
				'message' => __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'Could not retrieve Attachment.', 'media-library-assistant' ),
				'body' => '' 
			);
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return array(
				'message' => __( 'You are not allowed to edit this Attachment.', 'media-library-assistant' ),
				'body' => '' 
			);
		}

		if ( 0 === strpos( strtolower( $post_data['post_mime_type'] ), 'image' )  ) {
			$page_template_array = MLAData::mla_load_template( 'admin-display-single-image.tpl' );
			$width = isset( $post_data['mla_wp_attachment_metadata']['width'] ) ? $post_data['mla_wp_attachment_metadata']['width'] : '';
			$height = isset( $post_data['mla_wp_attachment_metadata']['height'] ) ? $post_data['mla_wp_attachment_metadata']['height'] : '';
			$image_meta = var_export( $post_data['mla_wp_attachment_metadata'], true );

			if ( !isset( $post_data['mla_wp_attachment_image_alt'] ) ) {
				$post_data['mla_wp_attachment_image_alt'] = '';
			}
		} else {
			$page_template_array = MLAData::mla_load_template( 'admin-display-single-document.tpl' );
			$width = '';
			$height = '';
			$image_meta = '';
		}

		if ( array( $page_template_array ) ) {
			$page_template = $page_template_array['page'];
			$authors_template = $page_template_array['authors'];
			$postbox_template = $page_template_array['postbox'];
		} else {
			/* translators: 1: ERROR tag 2: page_template_array */
			error_log( sprintf( _x( '%1$s: MLA::_display_single_item \$page_template_array = "%2$s"', 'error_log', 'media-library-assistant' ), __( 'ERROR', 'media-library-assistant' ), var_export( $page_template_array, true ) ), 0 );
			$page_template = $page_template_array;
			$authors_template = '';
			$postbox_template = '';
		}

		if ( empty($post_data['mla_references']['parent_title'] ) ) {
			$parent_info = $post_data['mla_references']['parent_errors'];
		} else {
			$parent_info = sprintf( '(%1$s) %2$s %3$s', $post_data['mla_references']['parent_type'], $post_data['mla_references']['parent_title'], $post_data['mla_references']['parent_errors'] );
		}

		if ( $authors = self::mla_authors_dropdown( $post_data['post_author'], 'attachments[' . $post_data['ID'] . '][post_author]' ) ) {
			$args = array (
				'ID' => $post_data['ID'],
				'Author' => __( 'Author', 'media-library-assistant' ),
				'authors' => $authors
				);
			$authors = MLAData::mla_parse_template( $authors_template, $args );
		} else {
			$authors = '';
		}

		if ( MLAOptions::$process_featured_in ) {
			$features = '';

			foreach ( $post_data['mla_references']['features'] as $feature_id => $feature ) {
				if ( $feature_id == $post_data['post_parent'] ) {
					$parent = __( 'PARENT', 'media-library-assistant' ) . ' ';
				} else {
					$parent = '';
				}

				$features .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $feature->post_type, /*$3%s*/ $feature_id, /*$4%s*/ $feature->post_title ) . "\n";
			} // foreach $feature
		} else {
			$features = __( 'Disabled', 'media-library-assistant' );
		}

		if ( MLAOptions::$process_inserted_in ) {
			$inserts = '';

			foreach ( $post_data['mla_references']['inserts'] as $file => $insert_array ) {
				$inserts .= $file . "\n";

				foreach ( $insert_array as $insert ) {
					if ( $insert->ID == $post_data['post_parent'] ) {
						$parent = '  ' . __( 'PARENT', 'media-library-assistant' ) . ' ';
					} else {
						$parent = '  ';
					}

					$inserts .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $insert->post_type, /*$3%s*/ $insert->ID, /*$4%s*/ $insert->post_title ) . "\n";
				} // foreach $insert
			} // foreach $file
		} else {
			$inserts = __( 'Disabled', 'media-library-assistant' );
		}

		if ( MLAOptions::$process_gallery_in ) {
			$galleries = '';

			foreach ( $post_data['mla_references']['galleries'] as $gallery_id => $gallery ) {
				if ( $gallery_id == $post_data['post_parent'] ) {
					$parent = __( 'PARENT', 'media-library-assistant' ) . ' ';
				} else {
					$parent = '';
				}

				$galleries .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $gallery['post_type'], /*$3%s*/ $gallery_id, /*$4%s*/ $gallery['post_title'] ) . "\n";
			} // foreach $gallery
		} else {
			$galleries = __( 'Disabled', 'media-library-assistant' );
		}

		if ( MLAOptions::$process_mla_gallery_in ) {
			$mla_galleries = '';

			foreach ( $post_data['mla_references']['mla_galleries'] as $gallery_id => $gallery ) {
				if ( $gallery_id == $post_data['post_parent'] ) {
					$parent = __( 'PARENT', 'media-library-assistant' ) . ' ';
				} else {
					$parent = '';
				}

				$mla_galleries .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $gallery['post_type'], /*$3%s*/ $gallery_id, /*$4%s*/ $gallery['post_title'] ) . "\n";
			} // foreach $gallery
		} else {
			$mla_galleries = __( 'Disabled', 'media-library-assistant' );
		}

		/*
		 * WordPress doesn't look in hidden fields to set the month filter dropdown or sorting parameters
		 */
		if ( isset( $_REQUEST['m'] ) ) {
			$url_args = '&m=' . $_REQUEST['m'];
		} else {
			$url_args = '';
		}

		if ( isset( $_REQUEST['post_mime_type'] ) ) {
			$url_args .= '&post_mime_type=' . $_REQUEST['post_mime_type'];
		}

		if ( isset( $_REQUEST['order'] ) ) {
			$url_args .= '&order=' . $_REQUEST['order'];
		}

		if ( isset( $_REQUEST['orderby'] ) ) {
			$url_args .= '&orderby=' . $_REQUEST['orderby'];
		}

		/*
		 * Add the current view arguments
		 */
		if ( isset( $_REQUEST['detached'] ) ) {
			$view_args = '<input type="hidden" name="detached" value="' . $_REQUEST['detached'] . "\" />\n";
		} elseif ( isset( $_REQUEST['status'] ) ) {
			$view_args = '<input type="hidden" name="status" value="' . $_REQUEST['status'] . "\" />\n";
		} else {
			$view_args = '';
		}

		if ( isset( $_REQUEST['paged'] ) ) {
			$view_args .= sprintf( '<input type="hidden" name="paged" value="%1$s" />', $_REQUEST['paged'] ) . "\n";
		}

		$side_info_column = '';
		$taxonomies = get_object_taxonomies( 'attachment', 'objects' );

		foreach ( $taxonomies as $tax_name => $tax_object ) {
			ob_start();

			if ( $tax_object->hierarchical && $tax_object->show_ui ) {
				$box = array(
					 'id' => $tax_name . 'div',
					'title' => esc_html( $tax_object->labels->name ),
					'callback' => 'categories_meta_box',
					'args' => array(
						 'taxonomy' => $tax_name 
					),
					'inside_html' => '' 
				);
				post_categories_meta_box( $post, $box );
			} elseif ( $tax_object->show_ui ) {
				$box = array(
					 'id' => 'tagsdiv-' . $tax_name,
					'title' => esc_html( $tax_object->labels->name ),
					'callback' => 'post_tags_meta_box',
					'args' => array(
						 'taxonomy' => $tax_name 
					),
					'inside_html' => '' 
				);
				post_tags_meta_box( $post, $box );
			}

			$box['inside_html'] = ob_get_contents();
			ob_end_clean();
			$side_info_column .= MLAData::mla_parse_template( $postbox_template, $box );
		}

		$page_values = array(
			'form_url' => admin_url( 'upload.php' ) . '?page=' . self::ADMIN_PAGE_SLUG . $url_args,
			'ID' => $post_data['ID'],
			'post_mime_type' => $post_data['post_mime_type'],
			'menu_order' => $post_data['menu_order'],
			'mla_admin_action' => self::MLA_ADMIN_SINGLE_EDIT_UPDATE,
			'view_args' => $view_args,
			'wpnonce' => wp_nonce_field( self::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'Cancel' => __( 'Cancel', 'media-library-assistant' ),
			'Update' => __( 'Update', 'media-library-assistant' ),
			'Map IPTC/EXIF metadata' =>  __( 'Map IPTC/EXIF metadata', 'media-library-assistant' ),
			'attachment_icon' => wp_get_attachment_image( $post_id, array( 160, 120 ), true ),
			'File name' => __( 'File name', 'media-library-assistant' ),
			'file_name' => esc_html( $post_data['mla_references']['file'] ),
			'File type' => __( 'File type', 'media-library-assistant' ),
			'Upload date' => __( 'Upload date', 'media-library-assistant' ),
			'post_date' => $post_data['post_date'],
			'Last modified' => __( 'Last modified', 'media-library-assistant' ),
			'post_modified' => $post_data['post_modified'],
			'Dimensions' => __( 'Dimensions', 'media-library-assistant' ),
			'width' => $width,
			'height' => $height,
			'Title' => __( 'Title', 'media-library-assistant' ),
			'required' => __( 'required', 'media-library-assistant' ),
			'post_title_attr' => esc_attr( $post_data['post_title'] ),
			'Name/Slug' => __( 'Name/Slug', 'media-library-assistant' ),
			'post_name_attr' => esc_attr( $post_data['post_name'] ),
			'Must be unique' => __( 'Must be unique; will be validated.', 'media-library-assistant' ),
			'ALT Text' => __( 'ALT Text', 'media-library-assistant' ),
			'image_alt_attr' => '',
			'ALT Text Help' => __( 'Alternate text for the image, e.g. &#8220;The Mona Lisa&#8221;', 'media-library-assistant' ),
			'Caption' => __( 'Caption', 'media-library-assistant' ),
			'post_excerpt_attr' => esc_attr( $post_data['post_excerpt'] ),
			'Description' => __( 'Description', 'media-library-assistant' ),
			'post_content' => esc_textarea( $post_data['post_content'] ),
			'Parent Info' => __( 'Parent Info', 'media-library-assistant' ),
			'post_parent' => $post_data['post_parent'],
			'parent_info' => esc_attr( $parent_info ),
			'Parent Info Help' => __( 'ID, type and title of parent, if any.', 'media-library-assistant' ),
			'Menu Order' => __( 'Menu Order', 'media-library-assistant' ),
			'authors' => $authors,
			'File URL' => __( 'File URL', 'media-library-assistant' ),
			'guid_attr' => esc_attr( $post_data['guid'] ),
			'File URL Help' => __( 'Location of the uploaded file.', 'media-library-assistant' ),
			'Image Metadata' => __( 'Image Metadata', 'media-library-assistant' ),
			'image_meta' => esc_textarea( $image_meta ),
			'Featured in' => __( 'Featured in', 'media-library-assistant' ),
			'features' => esc_textarea( $features ),
			'Inserted in' => __( 'Inserted in', 'media-library-assistant' ),
			'inserts' => esc_textarea( $inserts ),
			'Gallery in' => __( 'Gallery in', 'media-library-assistant' ),
			'galleries' => esc_textarea( $galleries ),
			'MLA Gallery in' => __( 'MLA Gallery in', 'media-library-assistant' ),
			'mla_galleries' => esc_textarea( $mla_galleries ),
			'side_info_column' => $side_info_column 
		);

		if ( !empty( $post_data['mla_wp_attachment_image_alt'] ) ) {
			$page_values['image_alt_attr'] = esc_attr( $post_data['mla_wp_attachment_image_alt'] );
		}

		return array(
			'message' => '',
			'body' => MLAData::mla_parse_template( $page_template, $page_values ) 
		);
	}

	/**
	 * Restore a single item from the Trash
	 * 
	 * @since 0.1
	 * 
	 * @param	integer	The WordPress Post ID of the attachment item
	 *
	 * @return	array	success/failure message and NULL content
	 */
	private static function _restore_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) ) {
			return array(
				'message' => __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'You are not allowed to move this item out of the Trash.', 'media-library-assistant' ),
				'body' => '' 
			);
		}

		if ( !wp_untrash_post( $post_id ) ) {
			return array(
				/* translators: 1: ERROR tag 2: post ID */
				'message' => sprintf( __( '%1$s: Item %2$d could NOT be restored from Trash.', 'media-library-assistant' ), __( 'ERROR', 'media-library-assistant' ), $post_id ),
				'body' => '' 
			);
		}

		/*
		 * Posts are restored to "draft" status, so this must be updated.
		 */
		$update_post = array();
		$update_post['ID'] = $post_id;
		$update_post['post_status'] = 'inherit';
		wp_update_post( $update_post );

		return array(
			/* translators: 1: post ID */
			'message' => sprintf( __( 'Item %1$d restored from Trash.', 'media-library-assistant' ), $post_id ),
			'body' => '' 
		);
	}

	/**
	 * Move a single item to Trash
	 * 
	 * @since 0.1
	 * 
	 * @param	integer	The WordPress Post ID of the attachment item
	 *
	 * @return	array	success/failure message and NULL content
	 */
	private static function _trash_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) ) {
			return array(
				'message' => __( 'ERROR', 'media-library-assistant' ) . ': ' . __( 'You are not allowed to move this item to the Trash.', 'media-library-assistant' ),
				'body' => '' 
			);
		}

		if ( !wp_trash_post( $post_id, false ) ) {
			return array(
				/* translators: 1: ERROR tag 2: post ID */
				'message' => sprintf( __( '%1$s: Item %2$d could NOT be moved to Trash.', 'media-library-assistant' ), __( 'ERROR', 'media-library-assistant' ), $post_id ),
				'body' => '' 
			);
		}

		return array(
			/* translators: 1: post ID */
			'message' => sprintf( __( 'Item %1$d moved to Trash.', 'media-library-assistant' ), $post_id ),
			'body' => '' 
		);
	}
} // class MLA
?>