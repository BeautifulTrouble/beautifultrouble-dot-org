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
	 * Display name for this plugin
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const PLUGIN_NAME = 'Media Library Assistant';

	/**
	 * Current version number
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const CURRENT_MLA_VERSION = '1.41';

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
	public static function initialize( )
	{
		add_action( 'admin_init', 'MLA::mla_admin_init_action' );
		add_action( 'admin_enqueue_scripts', 'MLA::mla_admin_enqueue_scripts_action' );
		add_action( 'admin_menu', 'MLA::mla_admin_menu_action' );
		add_filter( 'set-screen-option', 'MLA::mla_set_screen_option_filter', 10, 3 ); // $status, $option, $value
		add_filter( 'screen_options_show_screen', 'MLA::mla_screen_options_show_screen_filter', 10, 2 ); // $show_screen, $this
	}
	
	/**
	 * Load the plugin's Ajax handler or process Edit Media update actions
	 *
	 * @since 0.20
	 *
	 * @return	void
	 */
	public static function mla_admin_init_action() {
		/*
		 * Process row-level actions from the Edit Media screen
		 */
		if ( !empty( $_REQUEST['mla_admin_action'] ) ) {
			check_admin_referer( self::MLA_ADMIN_NONCE );
			
			switch ( $_REQUEST['mla_admin_action'] ) {
				case self::MLA_ADMIN_SINGLE_CUSTOM_FIELD_MAP:
					$updates = MLAOptions::mla_evaluate_custom_field_mapping( $_REQUEST['mla_item_ID'], 'single_attachment_mapping' );
					
					if ( !empty( $updates ) )
						$item_content = MLAData::mla_update_single_item( $_REQUEST['mla_item_ID'], $updates );

					$view_args = isset( $_REQUEST['mla_source'] ) ? array( 'mla_source' => $_REQUEST['mla_source']) : array();
					wp_redirect( add_query_arg( $view_args, admin_url( 'post.php' ) . '?post=' . $_REQUEST['mla_item_ID'] . '&action=edit&message=101' ), 302 );
					exit;
				case self::MLA_ADMIN_SINGLE_MAP:
					$item = get_post( $_REQUEST['mla_item_ID'] );
					$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $item, 'iptc_exif_mapping' );
					$page_content = MLAData::mla_update_single_item( $_REQUEST['mla_item_ID'], $updates );

					$view_args = isset( $_REQUEST['mla_source'] ) ? array( 'mla_source' => $_REQUEST['mla_source']) : array();
					wp_redirect( add_query_arg( $view_args, admin_url( 'post.php' ) . '?post=' . $_REQUEST['mla_item_ID'] . '&action=edit&message=102' ), 302 );
					exit;
				default:
					// ignore the rest
			} // switch ($_REQUEST['mla_admin_action'])
		} // (!empty($_REQUEST['mla_admin_action'])
		
		add_action( 'wp_ajax_' . self::JAVASCRIPT_INLINE_EDIT_SLUG, 'MLA::mla_inline_edit_action' );
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
		
		if( 'edit-tags.php' == $page_hook ) {
			wp_register_style( self::STYLESHEET_SLUG, MLA_PLUGIN_URL . 'css/mla-edit-tags-style.css', false, self::CURRENT_MLA_VERSION );
			wp_enqueue_style( self::STYLESHEET_SLUG );
			return;
		}
		
		if ( 'media_page_' . self::ADMIN_PAGE_SLUG != $page_hook )
			return;

		wp_register_style( self::STYLESHEET_SLUG, MLA_PLUGIN_URL . 'css/mla-style.css', false, self::CURRENT_MLA_VERSION );
		wp_enqueue_style( self::STYLESHEET_SLUG );

		if ( isset( $_REQUEST['mla_admin_action'] ) && ( $_REQUEST['mla_admin_action'] == self::MLA_ADMIN_SINGLE_EDIT_DISPLAY ) ) {
			wp_enqueue_script( self::JAVASCRIPT_SINGLE_EDIT_SLUG, MLA_PLUGIN_URL . "js/mla-single-edit-scripts{$suffix}.js", 
				array( 'wp-lists', 'suggest', 'jquery' ), self::CURRENT_MLA_VERSION, false );
			$script_variables = array(
				'comma' => _x( ',', 'tag delimiter' ),
				'Ajax_Url' => admin_url( 'admin-ajax.php' ) 
			);
			wp_localize_script( self::JAVASCRIPT_SINGLE_EDIT_SLUG, self::JAVASCRIPT_SINGLE_EDIT_OBJECT, $script_variables );
		}
		else {
			wp_enqueue_script( self::JAVASCRIPT_INLINE_EDIT_SLUG, MLA_PLUGIN_URL . "js/mla-inline-edit-scripts{$suffix}.js", 
				array( 'wp-lists', 'suggest', 'jquery' ), self::CURRENT_MLA_VERSION, false );
				
			$fields = array( 'post_title', 'post_name', 'post_excerpt', 'image_alt', 'post_parent', 'menu_order', 'post_author' );
			$custom_fields = MLAOptions::mla_custom_field_support( 'quick_edit' );
			$custom_fields = array_merge( $custom_fields, MLAOptions::mla_custom_field_support( 'bulk_edit' ) );
			foreach ($custom_fields as $slug => $label ) {
				$fields[] = $slug;
			}

			$script_variables = array(
				'fields' => $fields,
				'error' => 'Error while saving the changes.',
				'ntdeltitle' => 'Remove From Bulk Edit',
				'notitle' => '(no title)',
				'comma' => _x( ',', 'tag delimiter' ),
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
		$hook = add_submenu_page( 'upload.php', 'Media Library Assistant', 'Assistant', 'upload_files', self::ADMIN_PAGE_SLUG, 'MLA::mla_render_admin_page' );
		add_action( 'load-' . $hook, 'MLA::mla_add_menu_options' );
		add_action( 'load-' . $hook, 'MLA::mla_add_help_tab' );
		self::$page_hooks[ $hook ] = $hook;
		
		$taxonomies = get_object_taxonomies( 'attachment', 'objects' );
		if ( !empty( $taxonomies ) ) {
			foreach ( $taxonomies as $tax_name => $tax_object ) {
				/*
				 * WordPress 3.5 adds native support for taxonomies
				 */
				if( ! MLATest::$wordpress_3point5_plus ) {
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
		
		add_filter( 'parent_file', 'MLA::mla_parent_file_filter', 10, 1 );
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
			 'label' => 'Entries per page',
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
	public static function mla_add_help_tab( )
	{
		$screen = get_current_screen();
		/*
		 * Is this one of our pages?
		 */
		if ( !array_key_exists( $screen->id, self::$page_hooks ) )
			return;
		
		if ( 'edit-tags' == $screen->base && 'attachment' != $screen->post_type )
			return;
		
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
		} // isset( $_REQUEST['mla_admin_action'] )
		else {
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

						if ( $tax_object->hierarchical )
							$file_suffix = 'edit-hierarchical-taxonomy';
						else
							$file_suffix = 'edit-flat-taxonomy';
				} // $taxonomy switch
			} // is taxonomy
		}
		
		$template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/help-for-' . $file_suffix . '.tpl' );
		if ( empty( $template_array ) ) {
			return;
		}
		
		/*
		 * Don't add sidebar to the WordPress category and post_tag screens
		 */
		if ( ! ( 'edit-tags' == $screen->base && in_array( $screen->taxonomy, array( 'post_tag', 'category' ) ) ) )
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
				error_log( 'ERROR: mla_add_help_tab discarding '.var_export( $id, true ), 0 );
			}
		}
		
		ksort( $tab_array, SORT_NUMERIC );
		foreach ( $tab_array as $indx => $value ) {
			/*
			 * Don't add duplicate tabs to the WordPress category and post_tag screens
			 */
			if ( 'edit-tags' == $screen->base && in_array( $screen->taxonomy, array( 'post_tag', 'category' ) ) )
				if ( 'mla-attachments-column' != $value['id'] )
					continue;
			
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
		if ( isset( $_REQUEST['mla_admin_action'] ) && ( $_REQUEST['mla_admin_action'] == self::MLA_ADMIN_SINGLE_EDIT_DISPLAY ) )
			return false;

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
	public static function mla_set_screen_option_filter( $status, $option, $value )
	{
		if ( 'mla_entries_per_page' == $option )
			return $value;
		elseif ( $status )
			return $status;
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
	public static function mla_edit_tax_redirect( )
	{
		/*
		 * WordPress 3.5 adds native support for taxonomies
		 */
		if( MLATest::$wordpress_3point5_plus ) 
			return;

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
		global $submenu_file, $submenu;
		
		/*
		 * Make sure the "Assistant" submenu line is bolded when we go to the Edit Media page
		 */
		if ( isset( $_REQUEST['mla_source'] ) )
			$submenu_file = self::ADMIN_PAGE_SLUG;
			
		/*
		 * WordPress 3.5 adds native support for taxonomies
		 */
		if( MLATest::$wordpress_3point5_plus ) 
			return $parent_file;

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
		if ( isset( $_REQUEST['m'] ) )
			$_GET['m'] = $_REQUEST['m'];
			
		if ( isset( $_REQUEST['order'] ) )
			$_GET['order'] = $_REQUEST['order'];
		
		if ( isset( $_REQUEST['orderby'] ) )
			$_GET['orderby'] = $_REQUEST['orderby'];
		
		$bulk_action = self::_current_bulk_action();
		
		echo "<div class=\"wrap\">\r\n";
		echo "<div id=\"icon-upload\" class=\"icon32\"><br/></div>\r\n";
		echo "<h2>Media Library Assistant"; // trailing </h2> is action-specific
		
		if ( !current_user_can( 'upload_files' ) ) {
			echo " - Error</h2>\r\n";
			wp_die( __( 'You do not have permission to manage attachments.' ) );
		}
		
		$page_content = array(
			 'message' => '',
			'body' => '' 
		);
		
		/*
		 * The category taxonomy is a special case because post_categories_meta_box() changes the input name
		 */
		if ( !isset( $_REQUEST['tax_input'] ) )
			$_REQUEST['tax_input'] = array();
				
		if ( isset( $_REQUEST['post_category'] ) ) {
			$_REQUEST['tax_input']['category'] = $_REQUEST['post_category'];
			unset ( $_REQUEST['post_category'] );
		}
			
		/*
		 * Process bulk actions that affect an array of items
		 */
		if ( $bulk_action && ( $bulk_action != 'none' ) ) {
//			echo "</h2>\r\n";
			
			if ( isset( $_REQUEST['cb_attachment'] ) ) {
				foreach ( $_REQUEST['cb_attachment'] as $index => $post_id ) {
					switch ( $bulk_action ) {
						//case 'attach':
						//case 'catagorize':
						case 'delete':
							$item_content = self::_delete_single_item( $post_id );
							break;
						case 'edit':
							if ( !empty( $_REQUEST['bulk_custom_field_map'] ) ) {
								$updates = MLAOptions::mla_evaluate_custom_field_mapping( $post_id, 'single_attachment_mapping' );
								
								$item_content = MLAData::mla_update_single_item( $post_id, $updates );
								break;
							}
							
							if ( !empty( $_REQUEST['bulk_map'] ) ) {
								$item = get_post( $post_id );
								$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $item, 'iptc_exif_mapping' );

								$item_content = MLAData::mla_update_single_item( $post_id, $updates );
								break;
							}
							
							/*
							 * Copy the edit form contents to $new_data and remove them from $_REQUEST
							 */
							$new_data = array() ;
							if ( isset( $_REQUEST['post_parent'] ) ) {
								if ( is_numeric( $_REQUEST['post_parent'] ) )
									$new_data['post_parent'] = $_REQUEST['post_parent'];
									
								unset( $_REQUEST['post_parent'] );
							}
							
							if ( isset( $_REQUEST['post_author'] ) ) {
								if ( -1 != $_REQUEST['post_author'] )
										$new_data['post_author'] = $_REQUEST['post_author'];
										
								unset( $_REQUEST['post_author'] );
							}
							
							/*
							 * Custom field support
							 */
							$custom_fields = array();
							foreach (MLAOptions::mla_custom_field_support( 'bulk_edit' ) as $slug => $label ) {
								$field_name =  $slug;
								if ( isset( $_REQUEST[ $field_name ] ) ) {
									if ( ! empty( $_REQUEST[ $field_name ] ) )
										$custom_fields[ $label ] = $_REQUEST[ $field_name ];
											
									unset( $_REQUEST[ $field_name ] );
								}
							} // foreach
					
							if ( ! empty( $custom_fields ) )
								$new_data[ 'custom_updates' ] = $custom_fields;
							
							$item_content = MLAData::mla_update_single_item( $post_id, $new_data, $_REQUEST['tax_input'], $_REQUEST['tax_action'] );
							break;
						case 'restore':
							$item_content = self::_restore_single_item( $post_id );
							break;
						//case 'tag':
						case 'trash':
							$item_content = self::_trash_single_item( $post_id );
							break;
						default:
							$item_content = array(
								 'message' => sprintf( 'Unknown bulk action %s', $bulk_action ),
								'body' => '' 
							);
					} // switch $bulk_action
					
					$page_content['message'] .= $item_content['message'] . '<br>';
				} // foreach cb_attachment

				unset( $_REQUEST['tax_input'] );
				unset( $_REQUEST['tax_action'] );
				unset( $_REQUEST['cb_attachment'] );
			} // isset cb_attachment
			else {
				$page_content['message'] = 'Bulk Action ' . $bulk_action . ' - no items selected.';
			}

			unset( $_REQUEST['action'] );
			unset( $_REQUEST['bulk_edit'] );
			unset( $_REQUEST['action2'] );
		} // $bulk_action
		
		/*
		 * Process row-level actions that affect a single item
		 */
		if ( !empty( $_REQUEST['mla_admin_action'] ) ) {
			check_admin_referer( self::MLA_ADMIN_NONCE );
			
			switch ( $_REQUEST['mla_admin_action'] ) {
				case self::MLA_ADMIN_SINGLE_DELETE:
					$page_content = self::_delete_single_item( $_REQUEST['mla_item_ID'] );
					break;
				case self::MLA_ADMIN_SINGLE_EDIT_DISPLAY:
					echo " - Edit single item</h2>";
					$page_content = self::_display_single_item( $_REQUEST['mla_item_ID'] );
					break;
				case self::MLA_ADMIN_SINGLE_EDIT_UPDATE:
					if ( !empty( $_REQUEST['update'] ) ) {
						$page_content = MLAData::mla_update_single_item( $_REQUEST['mla_item_ID'], $_REQUEST['attachments'][ $_REQUEST['mla_item_ID'] ], $_REQUEST['tax_input'] );
					} elseif ( !empty( $_REQUEST['map-iptc-exif'] ) ) {
						$item = get_post( $_REQUEST['mla_item_ID'] );
						$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $item, 'iptc_exif_mapping' );
						$page_content = MLAData::mla_update_single_item( $_REQUEST['mla_item_ID'], $updates );
					} else {
						$page_content = array(
							'message' => 'Item: ' . $_REQUEST['mla_item_ID'] . ' cancelled.',
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
				default:
					$page_content = array(
						 'message' => sprintf( 'Unknown mla_admin_action - "%1$s"', $_REQUEST['mla_admin_action'] ),
						'body' => '' 
					);
					break;
			} // switch ($_REQUEST['mla_admin_action'])
		} // (!empty($_REQUEST['mla_admin_action'])
		
		if ( !empty( $page_content['body'] ) ) {
			if ( !empty( $page_content['message'] ) ) {
				if ( false !== strpos( $page_content['message'], 'ERROR:' ) )
					$messages_class = 'mla_errors';
				else
					$messages_class = 'mla_messages';

				echo "  <div class=\"{$messages_class}\"><p>\r\n";
				echo '    ' . $page_content['message'] . "\r\n";
				echo "  </p></div>\r\n"; // id="message"
			}
			
			echo $page_content['body'];
		} else {
			/*
			 * Display Attachments list
			 */
			if ( !empty( $_REQUEST['heading_suffix'] ) ) {
				echo ' - ' . esc_html( $_REQUEST['heading_suffix'] ) . "</h2>\r\n";
			} elseif ( !empty( $_REQUEST['s'] ) && !empty( $_REQUEST['mla_search_fields'] ) ) {
				echo ' - search results for "' . esc_html( stripslashes( trim( $_REQUEST['s'] ) ) ) . "\"</h2>\r\n";
			} else
				echo "</h2>\r\n";
			
			if ( !empty( $page_content['message'] ) ) {
				if ( false !== strpos( $page_content['message'], 'ERROR:' ) )
					$messages_class = 'mla_errors';
				else
					$messages_class = 'mla_messages';

				echo "  <div class=\"{$messages_class}\"><p>\r\n";
				echo '    ' . $page_content['message'] . "\r\n";
				echo "  </p></div>\r\n"; // id="message"
			}
			
			/*
			 * Optional - limit width of the views list
			 */
			$view_width = MLAOptions::mla_get_option( 'table_views_width' );
			if ( !empty( $view_width ) ) {
				if ( is_numeric( $view_width ) )
					$view_width .= 'px';
					
				echo "  <style type='text/css'>\r\n";
				echo "    ul.subsubsub {\r\n";
				echo "      width: {$view_width};\r\n";
				echo "      max-width: {$view_width};\r\n";
				echo "    }\r\n";
				echo "  </style>\r\n";
			}

			//	Create an instance of our package class...
			$MLAListTable = new MLA_List_Table();
			
			//	Fetch, prepare, sort, and filter our data...
			$MLAListTable->prepare_items();
			$MLAListTable->views();
			
			//	 Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions
//			echo '<form action="' . admin_url( 'upload.php' ) . '" method="get" id="mla-filter">' . "\r\n";
			echo '<form action="' . admin_url( 'upload.php?page=mla-menu' ) . '" method="post" id="mla-filter">' . "\r\n";
			/*
			 * Compose the Search Media box
			 */
			if ( !empty( $_REQUEST['s'] ) && !empty( $_REQUEST['mla_search_fields'] ) ) {
				$search_value = esc_attr( stripslashes( trim( $_REQUEST['s'] ) ) );
				$search_fields = $_REQUEST['mla_search_fields'];
				$search_connector = $_REQUEST['mla_search_connector'];
			} else {
				$search_value = '';
				$search_fields = array ( 'title', 'content' );
				$search_connector = 'AND';
			}
				
			echo '<p class="search-box">' . "\r\n";
			echo '<label class="screen-reader-text" for="media-search-input">Search Media:</label>' . "\r\n";
			echo '<input type="text" size="45"  id="media-search-input" name="s" value="' . $search_value . '" />' . "\r\n";
			echo '<input type="submit" name="mla-search-submit" id="search-submit" class="button" value="Search Media"  /><br>' . "\r\n";
			if ( 'OR' == $search_connector ) {
				echo '<input type="radio" name="mla_search_connector" value="AND" />&nbsp;and&nbsp;' . "\r\n";
				echo '<input type="radio" name="mla_search_connector" checked="checked" value="OR" />&nbsp;or&nbsp;' . "\r\n";
			} else {
				echo '<input type="radio" name="mla_search_connector" checked="checked" value="AND" />&nbsp;and&nbsp;' . "\r\n";
				echo '<input type="radio" name="mla_search_connector" value="OR" />&nbsp;or&nbsp;' . "\r\n";
			}

			if ( in_array( 'title', $search_fields ) )
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-title" checked="checked" value="title" />&nbsp;title&nbsp;' . "\r\n";
			else
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-title" value="title" />&nbsp;title&nbsp;' . "\r\n";
				
			if ( in_array( 'name', $search_fields ) )
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-name" checked="checked" value="name" />&nbsp;name&nbsp;' . "\r\n";
			else
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-name" value="name" />&nbsp;name&nbsp;' . "\r\n";

			if ( in_array( 'alt-text', $search_fields ) )
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-alt-text" checked="checked" value="alt-text" />&nbsp;ALT text&nbsp;' . "\r\n";
			else
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-alt-text" value="alt-text" />&nbsp;ALT text&nbsp;' . "\r\n";

			if ( in_array( 'excerpt', $search_fields ) )
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-excerpt" checked="checked" value="excerpt" />&nbsp;caption&nbsp;' . "\r\n";
			else
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-excerpt" value="excerpt" />&nbsp;caption&nbsp;' . "\r\n";

			if ( in_array( 'content', $search_fields ) )
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-content" checked="checked" value="content" />&nbsp;description&nbsp;' . "\r\n";
			else
				echo '<input type="checkbox" name="mla_search_fields[]" id="search-content" value="content" />&nbsp;description&nbsp;' . "\r\n";

			echo '</p>' . "\r\n";

			/*
			 * We also need to ensure that the form posts back to our current page and remember all the view arguments
			 */
			echo sprintf( '<input type="hidden" name="page" value="%1$s" />', $_REQUEST['page'] ) . "\r\n";
			
			if ( isset( $_REQUEST['detached'] ) ) // Unattached items
				echo sprintf( '<input type="hidden" name="detached" value="%1$s" />', $_REQUEST['detached'] ) . "\r\n";
			
			if ( isset( $_REQUEST['status'] ) ) // Trash items
				echo sprintf( '<input type="hidden" name="status" value="%1$s" />', $_REQUEST['status'] ) . "\r\n";
			
			if ( isset( $_REQUEST['post_mime_type'] ) ) // e.g., Images
				echo sprintf( '<input type="hidden" name="post_mime_type" value="%1$s" />', $_REQUEST['post_mime_type'] ) . "\r\n";
			
			if ( isset( $_REQUEST['m'] ) ) // filter by date
				echo sprintf( '<input type="hidden" name="m" value="%1$s" />', $_REQUEST['m'] ) . "\r\n";
			
			//	 Now we can render the completed list table
			$MLAListTable->display();
			echo "</form><!-- id=mla-filter -->\r\n";
			
			/*
			 * Insert the hidden form and table for inline edits (quick & bulk)
			 */
			echo self::_build_inline_edit_form($MLAListTable);
			
			echo "<div id=\"ajax-response\"></div>\r\n";
			echo "<br class=\"clear\" />\r\n";
			echo "</div><!-- class=wrap -->\r\n";
		} // display attachments list
	}
	
	/**
	 * Ajax handler for inline editing (quick and bulk edit)
	 *
	 * Adapted from wp_ajax_inline_save in /wp-admin/includes/ajax-actions.php
	 *
	 * @since 0.20
	 *
	 * @return	void	echo HTML <tr> markup for updated row or error message, then die()
	 */
	public static function mla_inline_edit_action() {
		set_current_screen( $_REQUEST['screen'] );

		check_ajax_referer( self::MLA_ADMIN_NONCE, 'nonce' );
		
		if ( empty( $_REQUEST['post_ID'] ) ) {
			echo 'Error: no post ID found';
			die();
		}
		else
			$post_id = $_REQUEST['post_ID'];
			
		if ( ! current_user_can( 'edit_post', $post_id ) )
			wp_die( __( 'You are not allowed to edit this Attachment.' ) );

		/*
		 * Custom field support
		 */
		$custom_fields = array();
		foreach (MLAOptions::mla_custom_field_support( 'quick_edit' ) as $slug => $label ) {
			$field_name =  $slug;
			if ( isset( $_REQUEST[ $field_name ] ) ) {
				$custom_fields[ $label ] = $_REQUEST[ $field_name ];
				unset ( $_REQUEST[ $field_name ] );
			  }
		}

		if ( ! empty( $custom_fields ) )
			$_REQUEST[ 'custom_updates' ] = $custom_fields;
		
		/*
		 * The category taxonomy is a special case because post_categories_meta_box() changes the input name
		 */
		if ( !isset( $_REQUEST['tax_input'] ) )
			$_REQUEST['tax_input'] = array();
				
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
					$comma = _x( ',', 'tag delimiter' );
					if ( ',' != $comma )
						$tax_value = str_replace( $comma, ',', $tax_value );
					
					$tax_value = preg_replace( '#\s*,\s*#', ',', $tax_value );
					$tax_value = preg_replace( '#,+#', ',', $tax_value );
					$tax_value = preg_replace( '#[,\s]+$#', '', $tax_value );
					$tax_value = preg_replace( '#^[,\s]+#', '', $tax_value );
					
					if ( ',' != $comma )
						$tax_value = str_replace( ',', $comma, $tax_value );
					
					$tax_array = array();
					$dedup_array = explode( $comma, $tax_value );
					foreach ( $dedup_array as $tax_value )
						$tax_array [$tax_value] = $tax_value;
						
					$tax_value = implode( $comma, $tax_array );
				} // ! array( $tax_value )
				
				$tax_output[$tax_name] = $tax_value;
			} // foreach $tax_input
		} // ! empty( $_REQUEST['tax_input'] )
		else
			$tax_output = NULL;
		
		$results = MLAData::mla_update_single_item( $post_id, $_REQUEST, $tax_output );
		$new_item = (object) MLAData::mla_get_attachment_by_id( $post_id );

		//	Create an instance of our package class and echo the new HTML
		$MLAListTable = new MLA_List_Table();
		$MLAListTable->single_row( $new_item );
		die(); // this is required to return a proper result
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

		$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-inline-edit-form.tpl' );
		if ( ! array( $page_template_array ) ) {
			error_log( "ERROR: MLA::_build_inline_edit_form \$page_template_array = " . var_export( $page_template_array, true ), 0 );
			return '';
		}
		
		if ( $authors = self::_authors_dropdown() ) {
			$authors_dropdown  = '              <label class="inline-edit-author">' . "\r\n";
			$authors_dropdown .= '                <span class="title">' . __( 'Author' ) . '</span>' . "\r\n";
			$authors_dropdown .= $authors . "\r\n";
			$authors_dropdown .= '              </label>' . "\r\n";
		}
		else
			$authors_dropdown = '';

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
					  'tax_attr' => esc_attr( $tax_name ),
					  'tax_checklist' => $tax_checklist
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
						'tax_attr' => esc_attr( $tax_name )
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
		
		if ( $authors = self::_authors_dropdown( -1 ) ) {
			$bulk_authors_dropdown  = '              <label class="inline-edit-author">' . "\r\n";
			$bulk_authors_dropdown .= '                <span class="title">' . __( 'Author' ) . '</span>' . "\r\n";
			$bulk_authors_dropdown .= $authors . "\r\n";
			$bulk_authors_dropdown .= '              </label>' . "\r\n";
		}
		else
			$bulk_authors_dropdown = '';

		$bulk_custom_fields = '';
		foreach (MLAOptions::mla_custom_field_support( 'bulk_edit' ) as $slug => $label ) {
			  $page_values = array(
				  'slug' => $slug,
				  'label' => esc_attr( $label ),
			  );
			  $bulk_custom_fields .= MLAData::mla_parse_template( $page_template_array['custom_field'], $page_values );
		}

		$page_values = array(
			'colspan' => count( $MLAListTable->get_columns() ),
			'authors' => $authors_dropdown,
			'custom_fields' => $custom_fields,
			'quick_middle_column' => $quick_middle_column,
			'quick_right_column' => $quick_right_column,
			'bulk_middle_column' => $bulk_middle_column,
			'bulk_right_column' => $bulk_right_column,
			'bulk_authors' => $bulk_authors_dropdown,
			'bulk_custom_fields' => $bulk_custom_fields			
		);
		$page_template = MLAData::mla_parse_template( $page_template_array['page'], $page_values );
		return $page_template;
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
	private static function _authors_dropdown( $author = 0, $name = 'post_author', $class = 'authors' ) {
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
			}
			elseif ( -1 == $author )
				$users_opt['show_option_none'] = __( '&mdash; No Change &mdash;' );

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
	 * Delete a single item permanently
	 * 
	 * @since 0.1
	 * 
	 * @param	array The form POST data
	 *
	 * @return	array success/failure message and NULL content
	 */
	private static function _delete_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) )
			return array(
				'message' => 'ERROR: You are not allowed to delete this item.',
				'body' => '' 
			);
		
		if ( !wp_delete_attachment( $post_id, true ) )
			return array(
				'message' => 'ERROR: Item ' . $post_id . ' could NOT be deleted.',
				'body' => '' 
			);
		
		return array(
			'message' => 'Item: ' . $post_id . ' permanently deleted.',
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
	 * @param	int		The WordPress Post ID of the attachment item
	 *
	 * @return	array	message and/or HTML content
	 */
	private static function _display_single_item( $post_id ) {
		global $post;

		/*
		 * This function sets the global $post
		 */
		$post_data = MLAData::mla_get_attachment_by_id( $post_id );
		if ( !isset( $post_data ) )
			return array(
				 'message' => 'ERROR: Could not retrieve Attachment.',
				'body' => '' 
			);
		
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return array(
				 'message' => 'You are not allowed to edit this Attachment.',
				'body' => '' 
			);

		if ( !empty( $post_data['mla_wp_attachment_metadata'] ) ) {
			$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-single-image.tpl' );
			$width = isset( $post_data['mla_wp_attachment_metadata']['width'] ) ? $post_data['mla_wp_attachment_metadata']['width'] : '';
			$height = isset( $post_data['mla_wp_attachment_metadata']['height'] ) ? $post_data['mla_wp_attachment_metadata']['height'] : '';
			$image_meta = var_export( $post_data['mla_wp_attachment_metadata'], true );
			
			if ( !isset( $post_data['mla_wp_attachment_image_alt'] ) )
				$post_data['mla_wp_attachment_image_alt'] = '';
		} else {
			$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-single-document.tpl' );
			$width = '';
			$height = '';
			$image_meta = '';
		}
		
		if ( array( $page_template_array ) ) {
			$page_template = $page_template_array['page'];
			$authors_template = $page_template_array['authors'];
			$postbox_template = $page_template_array['postbox'];
		} else {
			error_log( "ERROR: MLA::_display_single_item \$page_template_array = " . var_export( $page_template_array, true ), 0 );
			$page_template = $page_template_array;
			$authors_template = '';
			$postbox_template = '';
		}
		
		if ( empty($post_data['mla_references']['parent_title'] ) )
			$parent_info = $post_data['mla_references']['parent_errors'];
		else
			$parent_info = sprintf( '(%1$s) %2$s %3$s', $post_data['mla_references']['parent_type'], $post_data['mla_references']['parent_title'], $post_data['mla_references']['parent_errors'] );

		if ( $authors = self::_authors_dropdown( $post_data['post_author'], 'attachments[' . $post_data['ID'] . '][post_author]' ) ) {
			$args = array (
				'ID' => $post_data['ID'],
				'authors' => $authors
				);
			$authors = MLAData::mla_parse_template( $authors_template, $args );
		}
		else
			$authors = '';

		if ( MLAOptions::$process_featured_in ) {
			$features = '';
			
			foreach ( $post_data['mla_references']['features'] as $feature_id => $feature ) {
				if ( $feature_id == $post_data['post_parent'] )
					$parent = 'PARENT ';
				else
					$parent = '';
				
				$features .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $feature->post_type, /*$3%s*/ $feature_id, /*$4%s*/ $feature->post_title ) . "\r\n";
			} // foreach $feature
		}
		else
			$features = 'disabled';
			
		if ( MLAOptions::$process_inserted_in ) {
			$inserts = '';
			
			foreach ( $post_data['mla_references']['inserts'] as $file => $insert_array ) {
				$inserts .= $file . "\r\n";
				
				foreach ( $insert_array as $insert ) {
					if ( $insert->ID == $post_data['post_parent'] )
						$parent = '  PARENT ';
					else
						$parent = '  ';
					
					$inserts .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $insert->post_type, /*$3%s*/ $insert->ID, /*$4%s*/ $insert->post_title ) . "\r\n";
				} // foreach $insert
			} // foreach $file
		}
		else
			$inserts = 'disabled';
		
		if ( MLAOptions::$process_gallery_in ) {
			$galleries = '';
				
			foreach ( $post_data['mla_references']['galleries'] as $gallery_id => $gallery ) {
				if ( $gallery_id == $post_data['post_parent'] )
					$parent = 'PARENT ';
				else
					$parent = '';
				
				$galleries .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $gallery['post_type'], /*$3%s*/ $gallery_id, /*$4%s*/ $gallery['post_title'] ) . "\r\n";
			} // foreach $gallery
		}
		else
			$galleries = 'disabled';

		if ( MLAOptions::$process_mla_gallery_in ) {
			$mla_galleries = '';
				
			foreach ( $post_data['mla_references']['mla_galleries'] as $gallery_id => $gallery ) {
				if ( $gallery_id == $post_data['post_parent'] )
					$parent = 'PARENT ';
				else
					$parent = '';
				
				$mla_galleries .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $gallery['post_type'], /*$3%s*/ $gallery_id, /*$4%s*/ $gallery['post_title'] ) . "\r\n";
			} // foreach $gallery
		}
		else
			$mla_galleries = 'disabled';
		
		/*
		 * WordPress doesn't look in hidden fields to set the month filter dropdown or sorting parameters
		 */
		if ( isset( $_REQUEST['m'] ) )
			$url_args = '&m=' . $_REQUEST['m'];
		else
			$url_args = '';
			
		if ( isset( $_REQUEST['post_mime_type'] ) )
			$url_args .= '&post_mime_type=' . $_REQUEST['post_mime_type'];
		
		if ( isset( $_REQUEST['order'] ) )
			$url_args .= '&order=' . $_REQUEST['order'];
		
		if ( isset( $_REQUEST['orderby'] ) )
			$url_args .= '&orderby=' . $_REQUEST['orderby'];
		
		/*
		 * Add the current view arguments
		 */
		if ( isset( $_REQUEST['detached'] ) )
			$view_args = '<input type="hidden" name="detached" value="' . $_REQUEST['detached'] . "\" />\r\n";
		elseif ( isset( $_REQUEST['status'] ) )
			$view_args = '<input type="hidden" name="status" value="' . $_REQUEST['status'] . "\" />\r\n";
		else
			$view_args = '';
		
		if ( isset( $_REQUEST['paged'] ) )
			$view_args .= sprintf( '<input type="hidden" name="paged" value="%1$s" />', $_REQUEST['paged'] ) . "\r\n";
		
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
			'ID' => $post_data['ID'],
			'post_mime_type' => $post_data['post_mime_type'],
			'menu_order' => $post_data['menu_order'],
			'post_date' => $post_data['post_date'],
			'post_modified' => $post_data['post_modified'],
			'post_parent' => $post_data['post_parent'],
			'menu_order' => $post_data['menu_order'],
			'attachment_icon' => wp_get_attachment_image( $post_id, array( 160, 120 ), true ),
			'file_name' => esc_html( $post_data['mla_references']['file'] ),
			'width' => $width,
			'height' => $height,
			'post_title_attr' => esc_attr( $post_data['post_title'] ),
			'post_name_attr' => esc_attr( $post_data['post_name'] ),
			'image_alt_attr' => '',
			'post_excerpt_attr' => esc_attr( $post_data['post_excerpt'] ),
			'post_content' => esc_textarea( $post_data['post_content'] ),
			'image_meta' => esc_textarea( $image_meta ),
			'parent_info' => esc_attr( $parent_info ),
			'guid_attr' => esc_attr( $post_data['guid'] ),
			'authors' => $authors,
			'features' => esc_textarea( $features ),
			'inserts' => esc_textarea( $inserts ),
			'galleries' => esc_textarea( $galleries ),
			'mla_galleries' => esc_textarea( $mla_galleries ),
			'mla_admin_action' => self::MLA_ADMIN_SINGLE_EDIT_UPDATE,
			'form_url' => admin_url( 'upload.php' ) . '?page=' . self::ADMIN_PAGE_SLUG . $url_args,
			'view_args' => $view_args,
			'wpnonce' => wp_nonce_field( self::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
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
	 * @param	array	The form POST data
	 *
	 * @return	array	success/failure message and NULL content
	 */
	private static function _restore_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) )
			return array(
				'message' => 'ERROR: You are not allowed to move this item out of the Trash.',
				'body' => '' 
			);
		
		if ( !wp_untrash_post( $post_id ) )
			return array(
				'message' => 'ERROR: Item ' . $post_id . ' could NOT be restored from Trash.',
				'body' => '' 
			);
		
		/*
		 * Posts are restored to "draft" status, so this must be updated.
		 */
		$update_post = array();
		$update_post['ID'] = $post_id;
		$update_post['post_status'] = 'inherit';
		wp_update_post( $update_post );
		
		return array(
			'message' => 'Item: ' . $post_id . ' restored from Trash.',
			'body' => '' 
		);
	}
	
	/**
	 * Move a single item to Trash
	 * 
	 * @since 0.1
	 * 
	 * @param	array	The form POST data
	 *
	 * @return	array	success/failure message and NULL content
	 */
	private static function _trash_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) )
			return array(
				'message' => 'ERROR: You are not allowed to move this item to the Trash.',
				'body' => '' 
			);
		
		if ( !wp_trash_post( $post_id, false ) )
			return array(
				'message' => 'ERROR: Item ' . $post_id . ' could NOT be moved to Trash.',
				'body' => '' 
			);
		
		return array(
			'message' => 'Item: ' . $post_id . ' moved to Trash.',
			'body' => '' 
		);
	}
} // class MLA
?>