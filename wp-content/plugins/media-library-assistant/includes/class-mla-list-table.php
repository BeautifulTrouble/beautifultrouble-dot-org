<?php
/**
 * Media Library Assistant extended List Table class
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/* 
 * The WP_List_Table class isn't automatically available to plugins
 */
if ( !class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class MLA (Media Library Assistant) List Table implements the "Assistant" admin submenu
 *
 * Extends the core WP_List_Table class.
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLA_List_Table extends WP_List_Table {
	/*
	 * These variables are used to assign row_actions to exactly one visible column
	 */

	/**
	 * Records assignment of row-level actions to a table row
	 *
	 * Set to the current Post-ID when row-level actions are output for the row.
	 *
	 * @since 0.1
	 *
	 * @var	int
	 */
	private $rollover_id = 0;

	/**
	 * Currently hidden columns
	 *
	 * Records hidden columns so row-level actions are not assigned to them.
	 *
	 * @since 0.1
	 *
	 * @var	array
	 */
	private $currently_hidden = array();
	
	/*
	 * These arrays define the table columns.
	 */
	
	/**
	 * Table column definitions
	 *
	 * This array defines table columns and titles where the key is the column slug (and class)
	 * and the value is the column's title text. If you need a checkbox for bulk actions,
	 * use the special slug "cb".
	 * 
	 * The 'cb' column is treated differently than the rest. If including a checkbox
	 * column in your table you must create a column_cb() method. If you don't need
	 * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
	 *
	 * Taxonomy columns are added to this array by mla_admin_init_action.
	 * Custom field columns are added to this array by mla_admin_init_action.
	 * 
	 * @since 0.1
	 *
	 * @var	array
	 */
	private static $default_columns = array(
		'cb'     => '<input type="checkbox" />', //Render a checkbox instead of text
		'icon'   => '',
		'ID_parent'     => 'ID/Parent',
		'title_name'  => 'Title/Name',
		'post_title'  => 'Title',
		'post_name'  => 'Name',
		'parent'  => 'Parent ID',
		'menu_order' => 'Menu Order',
		'featured'   => 'Featured in',
		'inserted' => 'Inserted in',
		'galleries' => 'Gallery in',
		'mla_galleries' => 'MLA Gallery in',
		'alt_text' => 'ALT Text',
		'caption' => 'Caption',
		'description' => 'Description',
		'post_mime_type' => 'MIME Type',
		'base_file' => 'Base File',
		'date' => 'Date',
		'modified' => 'Last Modified',
		'author' => 'Author',
		'attached_to' => 'Attached to'
		// taxonomy columns added by mla_admin_init_action
		// custom field columns added by mla_admin_init_action
	);
	
	/**
	 * Default values for hidden columns
	 *
	 * This array is used when the user-level option is not set, i.e.,
	 * the user has not altered the selection of hidden columns.
	 *
	 * The value on the right-hand side must match the column slug, e.g.,
	 * array(0 => 'ID_parent, 1 => 'title_name').
	 *
	 * Taxonomy columns are added to this array by mla_admin_init_action.
	 * Custom field columns are added to this array by mla_admin_init_action.
	 * 
	 * @since 0.1
	 *
	 * @var	array
	 */
	private static $default_hidden_columns	= array(
		// 'ID_parent',
		// 'title_name',
		'post_title',
		'post_name',
		'parent',
		'menu_order',
		// 'featured',
		// 'inserted,
		'galleries',
		'mla_galleries',
		'alt_text',
		'caption',
		'description',
		'post_mime_type',
		'base_file',
		'date',
		'modified',
		'author',
		'attached_to',
		// taxonomy columns added by mla_admin_init_action
		// custom field columns added by mla_admin_init_action
	);
	
	/**
	 * Sortable column definitions
	 *
	 * This array defines the table columns that can be sorted. The array key
	 * is the column slug that needs to be sortable, and the value is database column
	 * to sort by. Often, the key and value will be the same, but this is not always
	 * the case (as the value is a column name from the database, not the list table).
	 *
	 * The array value also contains a boolean which is 'true' if the data is currently
	 * sorted by that column. This is computed each time the table is displayed.
	 *
	 * Taxonomy columns, if any, are added to this array by mla_admin_init_action.
	 * Custom field columns are added to this array by mla_admin_init_action.
	 *
	 * @since 0.1
	 *
	 * @var	array
	 */
	private static $default_sortable_columns = array(
		'ID_parent' => array('ID',false),
		'title_name' => array('title_name',false),
		'post_title' => array('post_title',false),
		'post_name' => array('post_name',false),
		'parent' => array('post_parent',false),
		'menu_order' => array('menu_order',false),
		// 'featured'   => array('featured',false),
		// 'inserted' => array('inserted',false),
		// 'galleries' => array('galleries',false),
		// 'mla_galleries' => array('mla_galleries',false),
		'alt_text' => array('_wp_attachment_image_alt',false),
		'caption' => array('post_excerpt',false),
		'description' => array('post_content',false),
		'post_mime_type' => array('post_mime_type',false),
		'base_file' => array('_wp_attached_file',false),
		'date' => array('post_date',false),
		'modified' => array('post_modified',false),
		'author' => array('post_author',false),
		'attached_to' => array('post_parent',false),
		// sortable taxonomy columns, if any, added by mla_admin_init_action
		// sortable custom field columns, if any, added by mla_admin_init_action
        );

	/**
	 * Access the default list of hidden columns
	 *
	 * @since 0.1
	 *
	 * @return	array	default list of hidden columns
	 */
	private static function _default_hidden_columns( ) {
		return MLA_List_Table::$default_hidden_columns;
	}
	
	/**
	 * Get MIME types with one or more attachments for view preparation
	 *
	 * Modeled after get_available_post_mime_types in wp-admin/includes/post.php,
	 * but uses the output of wp_count_attachments() as input.
	 *
	 * @since 0.1
	 *
	 * @param	array	Number of posts for each MIME type
	 *
	 * @return	array	Mime type names
	 */
	private function _avail_mime_types( $num_posts ) {
		$available = array();
		
		foreach ( $num_posts as $mime_type => $number ) {
			if ( ( $number > 0 ) && ( $mime_type <> 'trash' ) )
				$available[ ] = $mime_type;
		}
		
		return $available;
	}
	
	/**
	 * Get dropdown box of terms to filter by, if available
	 *
	 * @since 1.20
	 *
	 * @param	integer	currently selected term_id || zero (default)
	 *
	 * @return	string	HTML markup for dropdown box
	 */
	public static function mla_get_taxonomy_filter_dropdown( $selected = 0 ) {
		$dropdown = '';
		$tax_filter =  MLAOptions::mla_taxonomy_support('', 'filter');
		
		if ( ( '' != $tax_filter ) && ( is_object_in_taxonomy( 'attachment', $tax_filter ) ) ) {
			$tax_object = get_taxonomy( $tax_filter );
			$dropdown_options = array(
				'show_option_all' => 'All ' . $tax_object->labels->name,
				'show_option_none' => 'No ' . $tax_object->labels->name,
				'orderby' => 'name',
				'order' => 'ASC',
				'show_count' => false,
				'hide_empty' => false,
				'child_of' => 0,
				'exclude' => '',
				// 'exclude_tree => '', 
				'echo' => true,
				'depth' => 3,
				'tab_index' => 0,
				'name' => 'mla_filter_term',
				'id' => 'name',
				'class' => 'postform',
				'selected' => $selected,
				'hierarchical' => true,
				'pad_counts' => false,
				'taxonomy' => $tax_filter,
				'hide_if_empty' => false 
			);
			
			ob_start();
			wp_dropdown_categories( $dropdown_options );
			$dropdown = ob_get_contents();
			ob_end_clean();
		}
			
		return $dropdown;
	}
	
	/**
	 * Return the names and display values of the sortable columns
	 *
	 * @since 0.30
	 *
	 * @return	array	name => array( orderby value, heading ) for sortable columns
	 */
	public static function mla_get_sortable_columns( )
	{
		$results = array() ;
			
		foreach ( MLA_List_Table::$default_sortable_columns as $key => $value ) {
			$value[1] = MLA_List_Table::$default_columns[ $key ];
			$results[ $key ] = $value;
		}
		
		return $results;
	}
	
	/**
	 * Handler for filter 'get_user_option_managemedia_page_mla-menucolumnshidden'
	 *
	 * Required because the screen.php get_hidden_columns function only uses
	 * the get_user_option result. Set when the file is loaded because the object
	 * is not created in time for the call from screen.php.
	 *
	 * @since 0.1
	 *
	 * @param	string	current list of hidden columns, if any
	 * @param	string	'managemedia_page_mla-menucolumnshidden'
	 * @param	object	WP_User object, if logged in
	 *
	 * @return	array	updated list of hidden columns
	 */
	public static function mla_manage_hidden_columns_filter( $result, $option, $user_data ) {
		if ( $result )
			return $result;
		else
			return self::_default_hidden_columns();
	}
	
	/**
	 * Handler for filter 'manage_media_page_mla-menu_columns'
	 *
	 * This required filter dictates the table's columns and titles. Set when the
	 * file is loaded because the list_table object isn't created in time
	 * to affect the "screen options" setup.
	 *
	 * @since 0.1
	 *
	 * @return	array	list of table columns
	 */
	public static function mla_manage_columns_filter( )
	{
		return MLA_List_Table::$default_columns;
	}
	
	/**
	 * Adds support for taxonomy and custom field columns
	 *
	 * Called in the admin_init action because the list_table object isn't
	 * created in time to affect the "screen options" setup.
	 *
	 * @since 0.30
	 *
	 * @return	void
	 */
	public static function mla_admin_init_action( )
	{
		$taxonomies = get_taxonomies( array ( 'show_ui' => true ), 'names' );

		foreach ( $taxonomies as $tax_name ) {
			if ( MLAOptions::mla_taxonomy_support( $tax_name ) ) {
				$tax_object = get_taxonomy( $tax_name );
				MLA_List_Table::$default_columns[ 't_' . $tax_name ] = $tax_object->labels->name;
				MLA_List_Table::$default_hidden_columns [] = 't_' . $tax_name;
				// MLA_List_Table::$default_sortable_columns [] = none at this time
			} // supported taxonomy
		} // foreach $tax_name
		
		MLA_List_Table::$default_columns = array_merge( MLA_List_Table::$default_columns, MLAOptions::mla_custom_field_support( 'default_columns' ) );
		MLA_List_Table::$default_hidden_columns = array_merge( MLA_List_Table::$default_hidden_columns, MLAOptions::mla_custom_field_support( 'default_hidden_columns' ) );
		MLA_List_Table::$default_sortable_columns = array_merge( MLA_List_Table::$default_sortable_columns, MLAOptions::mla_custom_field_support( 'default_sortable_columns' ) );
	}
	
	/**
	 * Initializes some properties from $_REQUEST vairables, then
	 * calls the parent constructor to set some default configs.
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	function __construct( ) {
		$this->detached = isset( $_REQUEST['detached'] );
		$this->is_trash = isset( $_REQUEST['status'] ) && $_REQUEST['status'] == 'trash';
		
		//Set parent defaults
		parent::__construct( array(
			'singular' => 'attachment', //singular name of the listed records
			'plural' => 'attachments', //plural name of the listed records
			'ajax' => true, //does this table support ajax?
			'screen' => 'media_page_' . MLA::ADMIN_PAGE_SLUG
		) );
		
		$this->currently_hidden = self::get_hidden_columns();
		
		/*
		 * NOTE: There is one add_action call at the end of this source file.
		 * NOTE: There are two add_filter calls at the end of this source file.
		 */
	}
	
	/**
	 * Supply a column value if no column-specific function has been defined
	 *
	 * Called when the parent class can't find a method specifically built for a given column.
	 * The taxonomy and custom field columns are handled here. All other columns should have
	 * a specific method, so this function returns a troubleshooting message.
	 *
	 * @since 0.1
	 *
	 * @param	array	A singular item (one full row's worth of data)
	 * @param	array	The name/slug of the column to be processed
	 * @return	string	Text or HTML to be placed inside the column
	 */
	function column_default( $item, $column_name ) {
		if ( 't_' == substr( $column_name, 0, 2 ) ) {
			$taxonomy = substr( $column_name, 2 );
			$tax_object = get_taxonomy( $taxonomy );
			$terms = wp_get_object_terms( $item->ID, $taxonomy );
			
			if ( !is_wp_error( $terms ) ) {
				if ( empty( $terms ) )
					return 'none';

				$list = array();
				foreach ( $terms as $term ) {
					$term_name = esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'category', 'display' ) );
					$list[ ] = sprintf( '<a href="%1$s" title="Filter by &#8220;%2$s&#8221;">%3$s</a>', esc_url( add_query_arg( array(
						'page' => MLA::ADMIN_PAGE_SLUG,
						'mla-tax' => $taxonomy,
						'mla-term' => $term->slug,
						'heading_suffix' => urlencode( $tax_object->label . ': ' . $term->name ) 
					), 'upload.php' ) ), $term_name, $term_name );
				} // foreach $term
				
				return join( ', ', $list );
			} // if !is_wp_error
			else {
				return 'not supported';
			}
		} // 't_'
		elseif ( 'c_' == substr( $column_name, 0, 2 ) ) {
			$values = get_post_meta( $item->ID, MLA_List_Table::$default_columns[ $column_name ], false );
			if ( empty( $values ) )
				return '';
			
			$list = array();
			foreach( $values as $index => $value ) {
				/*
				 * For display purposes, convert array values.
				 * They are not links because no search will match them.
				 */
				if ( is_array( $value ) )
					$list[ ] = 'array( ' . implode( ', ', $value ) . ' )';
				else
					$list[ ] = sprintf( '<a href="%1$s" title="Filter by &#8220;%2$s&#8221;">%3$s</a>', esc_url( add_query_arg( array(
						'page' => MLA::ADMIN_PAGE_SLUG,
						'mla-metakey' => urlencode( MLA_List_Table::$default_columns[ $column_name ] ),
						'mla-metavalue' => urlencode( $value ),
						'heading_suffix' => urlencode( MLA_List_Table::$default_columns[ $column_name ] . ': ' . $value ) 
					), 'upload.php' ) ), esc_html( substr( $value, 0, 64 ) ), esc_html( $value ) );
			}

			if ( count( $list ) > 1 )
				return '[' . join( '], [', $list ) . ']';
			else
				return $list[0];
		} // 'c_'
		else {
			//Show the whole array for troubleshooting purposes
			return 'column_default: ' . $column_name . ', ' . print_r( $item, true );
		}
	}
	
	/**
	 * Displays checkboxes for using bulk actions. The 'cb' column
	 * is given special treatment when columns are processed.
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_cb( $item )
	{
		return sprintf( '<input type="checkbox" name="cb_%1$s[]" value="%2$s" />',
		/*%1$s*/ $this->_args['singular'], //Let's simply repurpose the table's singular label ("attachment")
		/*%2$s*/ $item->ID //The value of the checkbox should be the object's id
		);
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_icon( $item )
	{
		if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_ENABLE_MLA_ICONS ) )
			$thumb = wp_get_attachment_image( $item->ID, array( 64, 64 ), true, array( 'class' => 'mla_media_thumbnail_64_64' ) );
		else
			$thumb = wp_get_attachment_image( $item->ID, array( 80, 60 ), true, array( 'class' => 'mla_media_thumbnail_80_60' ) );

		if ( $this->is_trash || ! current_user_can( 'edit_post', $item->ID ) )
			return $thumb;
		
		return sprintf( '<a href="%1$s" title="Edit &#8220;%2$s&#8221;">%3$s</a>', get_edit_post_link( $item->ID, true ), esc_attr( $item->post_title ), $thumb ); 
		}
	
	/**
	 * Add rollover actions to exactly one of the following displayed columns:
	 * 'ID_parent', 'title_name', 'post_title', 'post_name'
	 *
	 * @since 0.1
	 * 
	 * @param	object	A singular attachment (post) object
	 * @param	string	Current column name
	 *
	 * @return	array	Names and URLs of row-level actions
	 */
	private function _build_rollover_actions( $item, $column ) {
		$actions = array();
		
		if ( ( $this->rollover_id != $item->ID ) && !in_array( $column, $this->currently_hidden ) ) {
			/*
			 * Build rollover actions
			 */
			
			$view_args = array(
				 'page' => $_REQUEST['page'],
				'mla_item_ID' => $item->ID 
			);

			if ( isset( $_REQUEST['paged'] ) )
				$view_args['paged'] = $_REQUEST['paged'];
			
			if ( isset( $_REQUEST['order'] ) )
				$view_args['order'] = $_REQUEST['order'];
			
			if ( isset( $_REQUEST['orderby'] ) )
				$view_args['orderby'] = $_REQUEST['orderby'];
			
			if ( isset( $_REQUEST['detached'] ) )
				$view_args['detached'] = $_REQUEST['detached'];
			elseif ( isset( $_REQUEST['status'] ) )
				$view_args['status'] = $_REQUEST['status'];
			elseif ( isset( $_REQUEST['post_mime_type'] ) )
				$view_args['post_mime_type'] = $_REQUEST['post_mime_type'];
			
			if ( isset( $_REQUEST['m'] ) )
				$view_args['m'] = $_REQUEST['m'];
			
			if ( isset( $_REQUEST['mla_filter_term'] ) )
				$view_args['mla_filter_term'] = $_REQUEST['mla_filter_term'];

			if ( current_user_can( 'edit_post', $item->ID ) ) {
				if ( $this->is_trash )
					$actions['restore'] = '<a class="submitdelete" href="' . add_query_arg( $view_args, wp_nonce_url( '?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_RESTORE, MLA::MLA_ADMIN_NONCE ) ) . '" title="Restore this item from the Trash">Restore</a>';
				else {
					/*
					 * Use the WordPress Edit Media screen for 3.5 and later
					 */
					if( MLATest::$wordpress_3point5_plus ) {
						$actions['edit'] = '<a href="' . admin_url( 'post.php' ) . '?post=' . $item->ID . '&action=edit&mla_source=edit" title="Edit this item">Edit</a>';
					}
					else {
						$actions['edit'] = '<a href="' . add_query_arg( $view_args, wp_nonce_url( '?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_EDIT_DISPLAY, MLA::MLA_ADMIN_NONCE ) ) . '" title="Edit this item">Edit</a>';
					}
					$actions['inline hide-if-no-js'] = '<a class="editinline" href="#" title="Edit this item inline">Quick Edit</a>';
				}
			} // edit_post
			
			if ( current_user_can( 'delete_post', $item->ID ) ) {
				if ( !$this->is_trash && EMPTY_TRASH_DAYS && MEDIA_TRASH )
					$actions['trash'] = '<a class="submitdelete" href="' . add_query_arg( $view_args, wp_nonce_url( '?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_TRASH, MLA::MLA_ADMIN_NONCE ) ) . '" title="Move this item to the Trash">Move to Trash</a>';
				else {
					// If using trash for posts and pages but not for attachments, warn before permanently deleting 
					$delete_ays = EMPTY_TRASH_DAYS && !MEDIA_TRASH ? ' onclick="return showNotice.warn();"' : '';
					
					$actions['delete'] = '<a class="submitdelete"' . $delete_ays . ' href="' . add_query_arg( $view_args, wp_nonce_url( '?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_DELETE, MLA::MLA_ADMIN_NONCE ) ) . '" title="Delete this item Permanently">Delete Permanently</a>';
				}
			} // delete_post
			
			$this->rollover_id = $item->ID;
		} // $this->rollover_id != $item->ID
		
		return $actions;
	}
	
	/**
	 * Add hidden fields with the data for use in the inline editor
	 *
	 * @since 0.20
	 * 
	 * @param	object	A singular attachment (post) object
	 *
	 * @return	string	HTML <div> with row data
	 */
	private function _build_inline_data( $item ) {
		$inline_data = "\r\n" . '<div class="hidden" id="inline_' . $item->ID . "\">\r\n";
		$inline_data .= '	<div class="post_title">' . esc_attr( $item->post_title ) . "</div>\r\n";
		$inline_data .= '	<div class="post_name">' . esc_attr( $item->post_name ) . "</div>\r\n";
		$inline_data .= '	<div class="post_excerpt">' . esc_attr( $item->post_excerpt ) . "</div>\r\n";
		
		if ( !empty( $item->mla_wp_attachment_metadata ) ) {
			if ( isset( $item->mla_wp_attachment_image_alt ) )
				$inline_data .= '	<div class="image_alt">' . esc_attr( $item->mla_wp_attachment_image_alt ) . "</div>\r\n";
			else
				$inline_data .= '	<div class="image_alt">' . "</div>\r\n";
		}
		
		$inline_data .= '	<div class="post_parent">' . $item->post_parent . "</div>\r\n";
		$inline_data .= '	<div class="menu_order">' . $item->menu_order . "</div>\r\n";
		$inline_data .= '	<div class="post_author">' . $item->post_author . "</div>\r\n";
		
		$custom_fields = MLAOptions::mla_custom_field_support( 'quick_edit' );
		$custom_fields = array_merge( $custom_fields, MLAOptions::mla_custom_field_support( 'bulk_edit' ) );
		foreach ($custom_fields as $slug => $label ) {
			$value = get_metadata( 'post', $item->ID, $label, true );
			$inline_data .= '	<div class="' . $slug . '">' . $value . "</div>\r\n";
		}
		
		$taxonomies = get_object_taxonomies( 'attachment', 'objects' );
		
		foreach ( $taxonomies as $tax_name => $tax_object ) {
			if ( $tax_object->hierarchical && $tax_object->show_ui && MLAOptions::mla_taxonomy_support($tax_name, 'quick-edit') ) {
				$inline_data .= '	<div class="mla_category" id="' . $tax_name . '_' . $item->ID . '">'
					. implode( ',', wp_get_object_terms( $item->ID, $tax_name, array( 'fields' => 'ids' ) ) ) . "</div>\r\n";
			} elseif ( $tax_object->show_ui && MLAOptions::mla_taxonomy_support($tax_name, 'quick-edit') ) {
				$inline_data .= '	<div class="mla_tags" id="'.$tax_name.'_'.$item->ID. '">'
					. esc_html( str_replace( ',', ', ', get_terms_to_edit( $item->ID, $tax_name ) ) ) . "</div>\r\n";
			}
		}
		
		$inline_data .= "</div>\r\n";
		return $inline_data;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_ID_parent( $item ) {
		$row_actions = self::_build_rollover_actions( $item, 'ID_parent' );
		if ( $item->post_parent ) {
			if ( isset( $item->parent_title ) )
				$parent_title = $item->parent_title;
			else
				$parent_title = '(no title: bad ID)';

			$parent = sprintf( '<a href="%1$s" title="Filter by Parent ID">(parent:%2$s)</a>', esc_url( add_query_arg( array(
					'page' => MLA::ADMIN_PAGE_SLUG,
					'parent' => $item->post_parent,
					'heading_suffix' => urlencode( 'Parent: ' .  $parent_title ) 
				), 'upload.php' ) ), (string) $item->post_parent );
		} // $item->post_parent
		else
			$parent = 'parent:0';

		if ( !empty( $row_actions ) ) {
			return sprintf( '%1$s<br><span style="color:silver">%2$s</span><br>%3$s%4$s', /*%1$s*/ $item->ID, /*%2$s*/ $parent, /*%3$s*/ $this->row_actions( $row_actions ), /*%4$s*/ $this->_build_inline_data( $item ) );
		} else {
			return sprintf( '%1$s<br><span style="color:silver">%2$s</span>', /*%1$s*/ $item->ID, /*%2$s*/ $parent );
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_title_name( $item ) {
		$row_actions = self::_build_rollover_actions( $item, 'title_name' );
		$post_title = esc_attr( $item->post_title );
		$post_name = esc_attr( $item->post_name );
		$errors = $item->mla_references['parent_errors'];
		if ( '(NO REFERENCE TESTS)' == $errors )
			$errors = '';
		
		if ( !empty( $row_actions ) ) {
			return sprintf( '%1$s<br>%2$s<br>%3$s%4$s%5$s', /*%1$s*/ $post_title, /*%2$s*/ $post_name, /*%3$s*/ $errors, /*%4$s*/ $this->row_actions( $row_actions ), /*%5$s*/ $this->_build_inline_data( $item ) );
		} else {
			return sprintf( '%1$s<br>%2$s<br>%3$s', /*%1$s*/ $post_title, /*%2$s*/ $post_name, /*%3$s*/ $errors );
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_post_title( $item ) {
		$row_actions = self::_build_rollover_actions( $item, 'post_title' );
		
		if ( !empty( $row_actions ) ) {
			return sprintf( '%1$s<br>%2$s%3$s', /*%1$s*/ esc_attr( $item->post_title ), /*%2$s*/ $this->row_actions( $row_actions ), /*%3$s*/ $this->_build_inline_data( $item ) );
		} else {
			return esc_attr( $item->post_title );
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_post_name( $item ) {
		$row_actions = self::_build_rollover_actions( $item, 'post_name' );
		
		if ( !empty( $row_actions ) ) {
			return sprintf( '%1$s<br>%2$s%3$s', /*%1$s*/ esc_attr( $item->post_name ), /*%2$s*/ $this->row_actions( $row_actions ), /*%3$s*/ $this->_build_inline_data( $item ) );
		} else {
			return esc_attr( $item->post_name );
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_parent( $item ) {
		if ( $item->post_parent ){
			if ( isset( $item->parent_title ) )
				$parent_title = $item->parent_title;
			else
				$parent_title = '(no title: bad ID)';

			return sprintf( '<a href="%1$s" title="Filter by Parent ID">%2$s</a>', esc_url( add_query_arg( array(
				'page' => MLA::ADMIN_PAGE_SLUG,
				'parent' => $item->post_parent,
				'heading_suffix' => urlencode( 'Parent: ' . $parent_title ) 
			), 'upload.php' ) ), (string) $item->post_parent );
		}
		else
			return (string) $item->post_parent;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.60
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_menu_order( $item ) {
		return (string) $item->menu_order;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_featured( $item ) {
		if ( !MLAOptions::$process_featured_in )
			return 'disabled';
			
		$value = '';
		
		foreach ( $item->mla_references['features'] as $feature_id => $feature ) {
			if ( $feature_id == $item->post_parent )
				$parent = ',<br>PARENT';
			else
				$parent = '';
			
			$value .= sprintf( '(%1$s %2$s%3$s), <a href="%4$s" title="Edit &#8220;%5$s&#8221;">%6$s</a>',
				/*%1$s*/ esc_attr( $feature->post_type ),
				/*%2$s*/ $feature_id,
				/*%3$s*/ $parent,
				/*%4$s*/ esc_url( add_query_arg( array('post' => $feature_id, 'action' => 'edit'), 'post.php' ) ),
				/*%5$s*/ esc_attr( $feature->post_title ),
				/*%6$s*/ esc_attr( $feature->post_title ) ) . "<br>\r\n";
		} // foreach $feature
		
		return $value;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_inserted( $item ) {
		if ( !MLAOptions::$process_inserted_in )
			return 'disabled';
			
		$value = '';
		
		foreach ( $item->mla_references['inserts'] as $file => $inserts ) {
			$value .= sprintf( '<strong>%1$s</strong><br>', $file );
			
			foreach ( $inserts as $insert ) {
				if ( $insert->ID == $item->post_parent )
					$parent = ',<br>PARENT';
				else
					$parent = '';
				
			$value .= sprintf( '(%1$s %2$s%3$s), <a href="%4$s" title="Edit &#8220;%5$s&#8221;">%6$s</a>',
				/*%1$s*/ esc_attr( $insert->post_type ),
				/*%2$s*/ $insert->ID,
				/*%3$s*/ $parent,
				/*%4$s*/ esc_url( add_query_arg( array('post' => $insert->ID, 'action' => 'edit'), 'post.php' ) ),
				/*%5$s*/ esc_attr( $insert->post_title ),
				/*%6$s*/ esc_attr( $insert->post_title ) ) . "<br>\r\n";
			} // foreach $insert
		} // foreach $file
		
		return $value;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.70
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_galleries( $item ) {
		if ( !MLAOptions::$process_gallery_in )
			return 'disabled';
			
		$value = '';
		
		foreach ( $item->mla_references['galleries'] as $ID => $gallery ) {
			if ( $ID == $item->post_parent )
				$parent = ',<br>PARENT';
			else
				$parent = '';
			
			$value .= sprintf( '(%1$s %2$s%3$s), <a href="%4$s" title="Edit &#8220;%5$s&#8221;">%6$s</a>',
				/*%1$s*/ esc_attr( $gallery['post_type'] ),
				/*%2$s*/ $ID,
				/*%3$s*/ $parent,
				/*%4$s*/ esc_url( add_query_arg( array('post' => $ID, 'action' => 'edit'), 'post.php' ) ),
				/*%5$s*/ esc_attr( $gallery['post_title'] ),
				/*%6$s*/ esc_attr( $gallery['post_title'] ) ) . "<br>\r\n";
		} // foreach $gallery
		
		return $value;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.70
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_mla_galleries( $item ) {
		if ( !MLAOptions::$process_mla_gallery_in )
			return 'disabled';
			
		$value = '';
		
		foreach ( $item->mla_references['mla_galleries'] as $ID => $gallery ) {
			if ( $ID == $item->post_parent )
				$parent = ',<br>PARENT ';
			else
				$parent = '';
			
			$value .= sprintf( '(%1$s %2$s%3$s), <a href="%4$s" title="Edit &#8220;%5$s&#8221;">%6$s</a>',
				/*%1$s*/ esc_attr( $gallery['post_type'] ),
				/*%2$s*/ $ID,
				/*%3$s*/ $parent,
				/*%4$s*/ esc_url( add_query_arg( array('post' => $ID, 'action' => 'edit'), 'post.php' ) ),
				/*%5$s*/ esc_attr( $gallery['post_title'] ),
				/*%6$s*/ esc_attr( $gallery['post_title'] ) ) . "<br>\r\n";
		} // foreach $gallery
		
		return $value;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_alt_text( $item ) {
		if ( isset( $item->mla_wp_attachment_image_alt ) )
			return sprintf( '<a href="%1$s" title="Filter by &#8220;%2$s&#8221;"">%3$s</a>', esc_url( add_query_arg( array(
				'page' => MLA::ADMIN_PAGE_SLUG,
				'mla-metakey' => '_wp_attachment_image_alt',
				'mla-metavalue' => urlencode( $item->mla_wp_attachment_image_alt ),
				'heading_suffix' => urlencode( 'ALT Text: ' . $item->mla_wp_attachment_image_alt ) 
			), 'upload.php' ) ), esc_html( $item->mla_wp_attachment_image_alt ), esc_html( $item->mla_wp_attachment_image_alt ) );
		else
			return '';
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_caption( $item ) {
		return esc_attr( $item->post_excerpt );
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_description( $item ) {
		return esc_textarea( $item->post_content );
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.30
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_post_mime_type( $item ) {
		return sprintf( '<a href="%1$s" title="Filter by &#8220;%2$s&#8221;"">%2$s</a>', esc_url( add_query_arg( array(
			'page' => MLA::ADMIN_PAGE_SLUG,
			'post_mime_type' => urlencode( $item->post_mime_type ),
			'heading_suffix' => urlencode( 'MIME Type: ' . $item->post_mime_type ) 
		), 'upload.php' ) ), esc_html( $item->post_mime_type ), esc_html( $item->post_mime_type ) );
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_base_file( $item ) {
		return $item->mla_references['base_file'];
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_date( $item ) {
		if ( '0000-00-00 00:00:00' == $item->post_date ) {
			$t_time = $h_time = __( 'Unpublished' );
		} else {
			$t_time = get_the_time( __( 'Y/m/d g:i:s A' ), $item );
			$m_time = $item->post_date;
			$time = get_post_time( 'G', true, $item, false );
			
			if ( ( abs( $t_diff = time() - $time ) ) < 86400 ) {
				if ( $t_diff < 0 )
					$h_time = sprintf( __( '%s from now' ), human_time_diff( $time ) );
				else
					$h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
			} else {
				$h_time = mysql2date( __( 'Y/m/d' ), $m_time );
			}
		}
		
		return $h_time;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.30
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_modified( $item ) {
		if ( '0000-00-00 00:00:00' == $item->post_modified ) {
			$t_time = $h_time = __( 'Unpublished' );
		} else {
			$t_time = get_the_time( __( 'Y/m/d g:i:s A' ), $item );
			$m_time = $item->post_modified;
			$time = get_post_time( 'G', true, $item, false );
			
			if ( ( abs( $t_diff = time() - $time ) ) < 86400 ) {
				if ( $t_diff < 0 )
					$h_time = sprintf( __( '%s from now' ), human_time_diff( $time ) );
				else
					$h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
			} else {
				$h_time = mysql2date( __( 'Y/m/d' ), $m_time );
			}
		}
		
		return $h_time;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.30
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_author( $item ) {
		$user = get_user_by( 'id', $item->post_author );
		
		if ( isset( $user->data->display_name ) )
			return sprintf( '<a href="%s" title="Filter by Author ID">%s</a>', esc_url( add_query_arg( array(
				 'page' => MLA::ADMIN_PAGE_SLUG,
				'author' => $item->post_author,
				'heading_suffix' => urlencode( 'Author: ' . $user->data->display_name ) 
			), 'upload.php' ) ), esc_html( $user->data->display_name ) );
		else
			return 'unknown';
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_attached_to( $item ) {
		if ( isset( $item->parent_date ) )
			$parent_date = $item->parent_date;
		else
			$parent_date = '';
		
		if ( isset( $item->parent_title ) )
			$parent_title = sprintf( '<a href="%1$s" title="Edit &#8220;%2$s&#8221;">%3$s</a>', esc_url( add_query_arg( array(
				'post' => $item->post_parent,
				'action' => 'edit'
			), 'post.php' ) ), esc_attr( $item->parent_title ), esc_attr( $item->parent_title ) );
		else
			$parent_title = '(Unattached)';
		
		if ( isset( $item->parent_type ) )
			$parent_type = '(' . $item->parent_type . ' ' . (string) $item->post_parent . ')';
		else
			$parent_type = '';
		
		return sprintf( '%1$s<br>%2$s<br>%3$s', /*%1$s*/ $parent_title, /*%2$s*/ mysql2date( __( 'Y/m/d' ), $parent_date ), /*%3$s*/ $parent_type ) . "<br>\r\n";
	}
	
	/**
	 * This method dictates the table's columns and titles
	 *
	 * @since 0.1
	 * 
	 * @return	array	Column information: 'slugs'=>'Visible Titles'
	 */
	function get_columns( ) {
		return $columns = MLA_List_Table::mla_manage_columns_filter();
	}
	
	/**
	 * Returns the list of currently hidden columns from a user option or
	 * from default values if the option is not set
	 *
	 * @since 0.1
	 * 
	 * @return	array	Column information,e.g., array(0 => 'ID_parent, 1 => 'title_name')
	 */
	function get_hidden_columns( )
	{
		$columns = get_user_option( 'managemedia_page_' . MLA::ADMIN_PAGE_SLUG . 'columnshidden' );

		if ( is_array( $columns ) )
			return $columns;
		else
			return self::_default_hidden_columns();
	}
	
	/**
	 * Returns an array where the  key is the column that needs to be sortable
	 * and the value is db column to sort by. Also notes the current sort column,
	 * if set.
	 *
	 * @since 0.1
	 * 
	 * @return	array	Sortable column information,e.g.,
	 * 					'slugs'=>array('data_values',boolean)
	 */
	function get_sortable_columns( ) {
		$columns = MLA_List_Table::$default_sortable_columns;
		
		if ( isset( $_REQUEST['orderby'] ) ) {
			$needle = array(
				 $_REQUEST['orderby'],
				false 
			);
			$key = array_search( $needle, $columns );
			if ( $key ) {
				$columns[ $key ][ 1 ] = true;
			}
		} else {
			$columns['title_name'][ 1 ] = true;
		}

		return $columns;
	}
	
	/**
	 * Returns HTML markup for one view that can be used with this table
	 *
	 * @since 1.40
	 *
	 * @param	string	View slug, key to MLA_POST_MIME_TYPES array 
	 * @param	string	Slug for current view 
	 * 
	 * @return	string | false	HTML for link to display the view, false if count = zero
	 */
	function _get_view( $view_slug, $current_view ) {
		global $wpdb;
		static $mla_types = NULL, $posts_per_type, $post_mime_types, $avail_post_mime_types, $matches, $num_posts, $base_url;
		
		$class = ( $view_slug == $current_view ) ? ' class="current"' : '';

		/*
		 * Calculate the common values once per page load
		 */
		if ( is_null( $mla_types ) ) {
			$query_types = MLAMime::mla_query_view_items( array( 'orderby' => 'menu_order' ), 0, 0 );
			if ( ! is_array( $query_types ) )
				$query_types = array ();
				
			$mla_types = array ();
			foreach( $query_types as $value )
				$mla_types[ $value->slug ] = $value;

			$posts_per_type = (array) wp_count_attachments();
			$post_mime_types = get_post_mime_types();
			$avail_post_mime_types = $this->_avail_mime_types( $posts_per_type );
			$matches = wp_match_mime_types( array_keys( $post_mime_types ), array_keys( $posts_per_type ) );

			foreach ( $matches as $type => $reals )
				foreach ( $reals as $real )
					$num_posts[ $type ] = ( isset( $num_posts[ $type ] ) ) ? $num_posts[ $type ] + $posts_per_type[ $real ] : $posts_per_type[ $real ];
			
			/*
			 * Remember the view filters
			 */
			$base_url = 'upload.php?page=' . MLA::ADMIN_PAGE_SLUG;
			
			if ( isset( $_REQUEST['m'] ) )
				$base_url = add_query_arg( array(
					 'm' => $_REQUEST['m'] 
				), $base_url );
			
			if ( isset( $_REQUEST['mla_filter_term'] ) )
				$base_url = add_query_arg( array(
					 'mla_filter_term' => $_REQUEST['mla_filter_term'] 
				), $base_url );
		}

		/*
		 * Handle the special cases: all, unattached and trash
		 */
		switch( $view_slug ) {
			case 'all':
				$total_items = array_sum( $posts_per_type ) - $posts_per_type['trash'];
				return "<a href='{$base_url}'$class>" . sprintf( _nx( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $total_items, 'uploaded files' ), number_format_i18n( $total_items ) ) . '</a>';
			case 'unattached':
				$total_items = $wpdb->get_var(
						"
						SELECT COUNT( * ) FROM {$wpdb->posts}
						WHERE post_type = 'attachment' AND post_status != 'trash' AND post_parent < 1
						"
				);

				if ( $total_items )
					return '<a href="' . add_query_arg( array( 'detached' => '1' ), $base_url ) . '"' . $class . '>' . sprintf( _nx( 'Unattached <span class="count">(%s)</span>', 'Unattached <span class="count">(%s)</span>', $total_items, 'detached files' ), number_format_i18n( $total_items ) ) . '</a>';
				else
					return false;
			case 'trash':
				if ( $posts_per_type['trash'] )
					return '<a href="' . add_query_arg( array(
						 'status' => 'trash' 
					), $base_url ) . '"' . $class . '>' . sprintf( _nx( 'Trash <span class="count">(%s)</span>', 'Trash <span class="count">(%s)</span>', $posts_per_type['trash'], 'uploaded files' ), number_format_i18n( $posts_per_type['trash'] ) ) . '</a>';
				else
					return false;
		} // switch special cases

		/*
		 * Make sure the slug is in our list
		 */
		if ( array_key_exists( $view_slug, $mla_types ) )
			$mla_type = $mla_types[ $view_slug ];
		else
			return false;
		
		/*
		 * Handle post_mime_types
		 */
		if ( $mla_type->post_mime_type ) {
			if ( !empty( $num_posts[ $view_slug ] ) )
				return "<a href='" . add_query_arg( array(
					 'post_mime_type' => $view_slug 
				), $base_url ) . "'$class>" . sprintf( translate_nooped_plural( $post_mime_types[ $view_slug ][ 2 ], $num_posts[ $view_slug ] ), number_format_i18n( $num_posts[ $view_slug ] ) ) . '</a>';
			else
				return false;
		}

		/*
		 * Handle extended specification types
		 */
		if ( empty( $mla_type->specification ) )
			$query = array ( 'post_mime_type' => $view_slug );
		else
			$query = MLAMime::mla_prepare_view_query( $mla_type->specification );
			
		$total_items = MLAData::mla_count_list_table_items( $query );
		if ( $total_items ) {
			$singular = sprintf('%s <span class="count">(%%s)</span>', $mla_type->singular );
			$plural = sprintf('%s <span class="count">(%%s)</span>', $mla_type->plural );
			$nooped_plural = _n_noop( $singular, $plural );
			
			if ( isset( $query['post_mime_type'] ) ) 
				$query['post_mime_type'] = urlencode( $query['post_mime_type'] );
			else
				$query['meta_query'] = urlencode( serialize( $query['meta_query'] ) );

			return "<a href='" . add_query_arg( $query, $base_url ) . "'$class>" . sprintf( translate_nooped_plural( $nooped_plural, $total_items ), number_format_i18n( $total_items ) ) . '</a>';
		}

		return false;
	} // _get_view
	
	/**
	 * Returns an associative array listing all the views that can be used with this table.
	 * These are listed across the top of the page and managed by WordPress.
	 *
	 * @since 0.1
	 * 
	 * @return	array	View information,e.g., array ( id => link )
	 */
	function get_views( ) {
		/*
		 * Find current view
		 */
		if ( $this->detached  )
			$current_view = 'unattached';
		elseif ( $this->is_trash )
			$current_view = 'trash';
		elseif ( empty( $_REQUEST['post_mime_type'] ) )
			$current_view = 'all';
		else
			$current_view = $_REQUEST['post_mime_type'];
		
		$mla_types = MLAMime::mla_query_view_items( array( 'orderby' => 'menu_order' ), 0, 0 );
		if ( ! is_array( $mla_types ) )
			$mla_types = array ();
			
		/*
		 * Filter the list, generate the views
		 */
		$view_links = array();
		foreach ( $mla_types as $value ) {
			if ( $value->table_view && $link = self::_get_view( $value->slug, $current_view ) )
				$view_links[ $value->slug ] = $link;
		}

		return $view_links;
	}
	
	/**
	 * Get an associative array ( option_name => option_title ) with the list
	 * of bulk actions available on this table.
	 *
	 * @since 0.1
	 * 
	 * @return	array	Contains all the bulk actions: 'slugs'=>'Visible Titles'
	 */
	function get_bulk_actions( )
	{
		$actions = array();
		
		if ( $this->is_trash ) {
			$actions['restore'] = 'Restore';
			$actions['delete'] = 'Delete Permanently';
		} else {
			$actions['edit'] = 'Edit';
			// $actions['attach'] = 'Attach';
			
			if ( EMPTY_TRASH_DAYS && MEDIA_TRASH )
				$actions['trash'] = 'Move to Trash';
			else
				$actions['delete'] = 'Delete Permanently';
		}
		
		return $actions;
	}
	
	/**
	 * Extra controls to be displayed between bulk actions and pagination
	 *
	 * Modeled after class-wp-posts-list-table.php in wp-admin/includes.
	 *
	 * @since 0.1
	 * 
	 * @param	string	'top' or 'bottom', i.e., above or below the table rows
	 *
	 * @return	array	Contains all the bulk actions: 'slugs'=>'Visible Titles'
	 */
	function extra_tablenav( $which )
	{
		echo ( '<div class="alignleft actions">' );
		
		if ( 'top' == $which ) {
			$this->months_dropdown( 'attachment' );
			
			echo self::mla_get_taxonomy_filter_dropdown( isset( $_REQUEST['mla_filter_term'] ) ? $_REQUEST['mla_filter_term'] : 0 );
			
			submit_button( __( 'Filter' ), 'secondary', 'mla_filter', false, array(
				 'id' => 'post-query-submit' 
			) );
		}
		
		if ( $this->is_trash && current_user_can( 'edit_others_posts' ) ) {
			submit_button( __( 'Empty Trash' ), 'button-secondary apply', 'delete_all', false );
		}
		
		echo ( '</div>' );
	}
	
	/**
	 * Prepares the list of items for displaying
	 *
	 * This is where you prepare your data for display. This method will usually
	 * be used to query the database, sort and filter the data, and generally
	 * get it ready to be displayed. At a minimum, we should set $this->items and
	 * $this->set_pagination_args().
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	function prepare_items( ) {
		$this->_column_headers = array(
			$this->get_columns(),
			$this->get_hidden_columns(),
			$this->get_sortable_columns() 
		);
		
		/*
		 * REQUIRED for pagination.
		 */
		$user = get_current_user_id();
		$screen = get_current_screen();
		$option = $screen->get_option( 'per_page', 'option' );
		$per_page = get_user_meta( $user, $option, true );
		if ( empty( $per_page ) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}

//		$current_page = $this->get_pagenum();
		$current_page = isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;

		/*
		 * REQUIRED. Assign sorted and paginated data to the items property, where 
		 * it can be used by the rest of the class.
		 */
		$total_items = MLAData::mla_count_list_table_items( $_REQUEST, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->items = MLAData::mla_query_list_table_items( $_REQUEST, ( ( $current_page - 1 ) * $per_page ), $per_page );
		
		/*
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page' => $per_page, //WE have to determine how many items to show on a page
			'total_pages' => ceil( $total_items / $per_page ) //WE have to calculate the total number of pages
		) );
	}
	
	/**
	 * Generates (echoes) content for a single row of the table
	 *
	 * @since .20
	 *
	 * @param object the current item
	 *
	 * @return void Echoes the row HTML
	 */
	function single_row( $item ) {
		static $row_class = '';
		$row_class = ( $row_class == '' ? ' class="alternate"' : '' );

		echo '<tr id="attachment-' . $item->ID . '"' . $row_class . '>';
		echo parent::single_row_columns( $item );
		echo '</tr>';
	}
} // class MLA_List_Table

/*
 * Filters are added here, when the source file is loaded, because the MLA_List_Table
 * object is created too late to be useful.
 */
add_action( 'admin_init', 'MLA_List_Table::mla_admin_init_action' );
 
add_filter( 'get_user_option_managemedia_page_' . MLA::ADMIN_PAGE_SLUG . 'columnshidden', 'MLA_List_Table::mla_manage_hidden_columns_filter', 10, 3 );
add_filter( 'manage_media_page_' . MLA::ADMIN_PAGE_SLUG . '_columns', 'MLA_List_Table::mla_manage_columns_filter', 10, 0 );
?>