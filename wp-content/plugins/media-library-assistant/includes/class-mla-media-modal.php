<?php
/**
 * Media Library Assistant Media Manager enhancements
 *
 * @package Media Library Assistant
 * @since 1.20
 */

/**
 * Class MLA (Media Library Assistant) Modal contains enhancements for the WordPress 3.5+ Media Manager
 *
 * @package Media Library Assistant
 * @since 1.20
 */
class MLAModal {
	/**
	 * Slug for localizing and enqueueing CSS - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_MEDIA_MODAL_STYLES = 'mla-media-modal-style';

	/**
	 * Slug for localizing and enqueueing JavaScript - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_MEDIA_MODAL_SLUG = 'mla-media-modal-scripts';

	/**
	 * Object name for localizing JavaScript - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_MEDIA_MODAL_OBJECT = 'mla_media_modal_vars';

	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 1.20
	 *
	 * @return	void
	 */
	public static function initialize() {
		/*
		 * WordPress 3.5's new Media Manager is supported on the server by
		 * /wp-includes/media.php function wp_enqueue_media(), which contains:
		 *
		 * $settings = apply_filters( 'media_view_settings', $settings, $post );
		 * $strings  = apply_filters( 'media_view_strings',  $strings,  $post );
		 *
		 * wp_enqueue_media() then contains a require_once for
		 * /wp-includes/media-template.php, which contains:
		 * do_action( 'print_media_templates' );
		 *
 		 * Finally wp_enqueue_media() contains:
		 * do_action( 'wp_enqueue_media' );
		 */
		if ( MLATest::$wordpress_3point5_plus && ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_TOOLBAR ) )
) {
			add_filter( 'media_view_settings', 'MLAModal::mla_media_view_settings_filter', 10, 2 );
			add_filter( 'media_view_strings', 'MLAModal::mla_media_view_strings_filter', 10, 2 );
			add_action( 'wp_enqueue_media', 'MLAModal::mla_wp_enqueue_media_action', 10, 0 );
			add_action( 'print_media_templates', 'MLAModal::mla_print_media_templates_action', 10, 0 );
			add_action( 'admin_init', 'MLAModal::mla_admin_init_ajax_action' );
			add_action( 'wp_ajax_' . self::JAVASCRIPT_MEDIA_MODAL_SLUG, 'MLAModal::mla_query_attachments_action' );
		} // $wordpress_3point5_plus
	}

	/**
	 * Display a monthly dropdown for filtering items
	 *
	 * Adapted from /wp-admin/includes/class-wp-list-table.php function months_dropdown()
	 *
	 * @since 1.20
	 *
	 * @param	string	post_type, e.g., 'attachment'
	 *
	 * @return	array	( value => label ) pairs
	 */
	private function _months_dropdown( $post_type ) {
		global $wpdb, $wp_locale;

		$months = $wpdb->get_results( $wpdb->prepare( "
			SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
			FROM $wpdb->posts
			WHERE post_type = %s
			ORDER BY post_date DESC
		", $post_type ) );

		$month_count = count( $months );
		$month_array = array( '0' => 'Show all dates' );
		
		if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
			return $month_array;

		foreach ( $months as $arc_row ) {
			if ( 0 == $arc_row->year )
				continue;

			$month = zeroise( $arc_row->month, 2 );
			$year = $arc_row->year;
			$month_array[ esc_attr( $arc_row->year . $month ) ] = 
				/* translators: 1: month name, 2: 4-digit year */
				sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year );
		}
		
		return $month_array;
	}

	/**
	 * Extract value and text elements from Dropdown HTML option tags
	 *
	 * @since 1.20
	 *
	 * @param	string	HTML markup for taxonomy terms dropdown <select> tag
	 *
	 * @return	array	( value => label ) pairs
	 */
	private function _terms_options( $markup ) {
		$match_count = preg_match_all( "#\<option(( class=\"([^\"]+)\" )|( ))value=((\'([^\']+)\')|(\"([^\"]+)\"))([^\>]*)\>([^\<]*)\<.*#", $markup, $matches );
		if ( ( $match_count == false ) || ( $match_count == 0 ) )
			return array( 'class' => array( '' ), 'value' => array( '0' ), 'text' => array( 'Show all terms' ) );
		
		$class_array = array();
		$value_array = array();
		$text_array = array();
			
		foreach ( $matches[11] as $index => $text ) {
			$class_array[ $index ] = $matches[3][ $index ];
			$value_array[ $index ] = ( ! '' == $matches[6][ $index ] )? $matches[7][ $index ] : $matches[9][ $index ];
			$text_array[ $index ] = $text;
		} // foreach
				
		return array( 'class' => $class_array, 'value' => $value_array, 'text' => $text_array );
	}

	/**
	 * Share the settings values between mla_media_view_settings_filter
	 * and mla_print_media_templates_action
	 *
	 * @since 1.20
	 *
	 * @var	array
	 */
	private static $mla_media_modal_settings = array(
			'ajaxAction' => self::JAVASCRIPT_MEDIA_MODAL_SLUG,
			'ajaxNonce' => '',
			'enableMimeTypes' => false,
			'enableMonthsDropdown' => false,
			'enableTermsDropdown' => false,
			'enableSearchBox' => false,
			'mimeTypes' => '',
			'months' => '',
			'termsClass' => array(),
			'termsValue' => array(),
			'termsText' => array(),
			'searchValue' => '',
			'searchFields' => array( 'title', 'content' ),
			'searchConnector' => 'AND'
			);
	
	/**
	 * Adds settings values to be passed to the Media Manager in /wp-includes/js/media-views.js.
	 * Declared public because it is a filter.
	 *
	 * @since 1.20
	 *
	 * @param	array	associative array with setting => value pairs
	 * @param	object || NULL	current post object, if available
	 *
	 * @return	array	updated $settings array
	 */
	public static function mla_media_view_settings_filter( $settings, $post ) {
		self::$mla_media_modal_settings['ajaxNonce'] = wp_create_nonce( MLA::MLA_ADMIN_NONCE );
		self::$mla_media_modal_settings['mimeTypes'] = MLAMime::mla_pluck_table_views();
		self::$mla_media_modal_settings['mimeTypes']['detached'] = 'Unattached';
		self::$mla_media_modal_settings['months'] = self::_months_dropdown('attachment');

		$terms_options = self::_terms_options( MLA_List_Table::mla_get_taxonomy_filter_dropdown() );
		self::$mla_media_modal_settings['termsClass'] = $terms_options['class'];
		self::$mla_media_modal_settings['termsValue'] = $terms_options['value'];
		self::$mla_media_modal_settings['termsText'] = $terms_options['text'];
		
		self::$mla_media_modal_settings['enableMimeTypes'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_MIMETYPES ) );
		self::$mla_media_modal_settings['enableMonthsDropdown'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_MONTHS ) );
		self::$mla_media_modal_settings['enableTermsDropdown'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_TERMS ) );
		self::$mla_media_modal_settings['enableSearchBox'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_SEARCHBOX ) );
		
		$settings = array_merge( $settings, array( 'mla_settings' => self::$mla_media_modal_settings ) );
		return $settings;
	} // mla_mla_media_view_settings_filter

	/**
	 * Adds strings values to be passed to the Media Manager in /wp-includes/js/media-views.js.
	 * Declared public because it is a filter.
	 *
	 * @since 1.20
	 *
	 * @param	array	associative array with string => value pairs
	 * @param	object || NULL	current post object, if available
	 *
	 * @return	array	updated $strings array
	 */
	public static function mla_media_view_strings_filter( $strings, $post ) {
		$mla_strings = array(
			'searchBoxPlaceholder' => 'Search Box',
			);
			
		$strings = array_merge( $strings, array( 'mla_strings' => $mla_strings ) );
		return $strings;
	} // mla_mla_media_view_strings_filter

	/**
	 * Enqueues the mla-media-modal-scripts.js file, adding it to the Media Manager scripts.
	 * Declared public because it is an action.
	 *
	 * @since 1.20
	 *
	 * @return	void
	 */
	public static function mla_wp_enqueue_media_action( ) {
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		// replaced by inline styles for now
		// wp_register_style( self::JAVASCRIPT_MEDIA_MODAL_STYLES, MLA_PLUGIN_URL . 'css/mla-media-modal-style.css', false, MLA::CURRENT_MLA_VERSION );
		// wp_enqueue_style( self::JAVASCRIPT_MEDIA_MODAL_STYLES );

		wp_enqueue_script( self::JAVASCRIPT_MEDIA_MODAL_SLUG, MLA_PLUGIN_URL . "js/mla-media-modal-scripts{$suffix}.js", array( 'media-views' ), MLA::CURRENT_MLA_VERSION, false );
	} // mla_wp_enqueue_media_action

	/**
	 * Prints the templates used in the MLA Media Manager enhancements.
	 * Declared public because it is an action.
	 *
	 * @since 1.20
	 *
	 * @return	void	echoes HTML script tags for the templates
	 */
	public static function mla_print_media_templates_action( ) {
		/*
		 * Adjust the toolbar styles based on which controls are present
		 */
		if ( self::$mla_media_modal_settings['enableSearchBox'] ) {
			if ( self::$mla_media_modal_settings['enableMonthsDropdown'] && self::$mla_media_modal_settings['enableTermsDropdown'] )
				$height = '100px';
			else
				$height = '70px';
		} else
			$height = '50px';

		echo '<style type="text/css">' . "\r\n";
		
		if ( self::$mla_media_modal_settings['enableSearchBox'] ) {
			echo "\t\t.media-frame .media-frame-content .media-toolbar-secondary {\r\n";
			echo "\t\t\twidth: 150px; }\r\n";
		}

		echo "\t\t.media-frame .media-frame-content .attachments-browser .media-toolbar {\r\n";
		echo "\t\t\theight: {$height}; }\r\n";
		echo "\t\t.media-frame .media-frame-content .attachments-browser .attachments,\r\n";
		echo "\t\t.media-frame .media-frame-content .attachments-browser .uploader-inline {\r\n";
		echo "\t\t\ttop: {$height}; }\r\n";
		echo "\t\t.media-frame .media-frame-content p.search-box {\r\n";
		echo "\t\t\tmargin-top: 7px;\r\n";
		echo "\t\t\tpadding: 4px;\r\n";
		echo "\t\t\tline-height: 18px;\r\n";
		echo "\t\t\tcolor: #464646;\r\n";
		echo "\t\t\tfont-family: sans-serif;\r\n";
		echo "\t\t\t-webkit-appearance: none; }\r\n";
		echo "\t" . '</style>' . "\r\n";
		
		/*
		 * Compose the Search Media box
		 */
		if ( isset( $_REQUEST['query']['mla_search_value'] ) )
			$search_value = esc_attr( stripslashes( trim( $_REQUEST['query']['mla_search_value'] ) ) );
		else
			$search_value = '';
			
		if ( isset( $_REQUEST['query']['mla_search_fields'] ) )
			$search_fields = $_REQUEST['query']['mla_search_fields'];
		else
			$search_fields = array ( 'title', 'content' );

		if ( isset( $_REQUEST['query']['mla_search_connector'] ) )
			$search_connector = $_REQUEST['query']['mla_search_connector'];
		else
			$search_connector = 'AND';

		echo "\t" . '<script type="text/html" id="tmpl-mla-search-box">' . "\r\n";
		echo "\t\t" . '<p class="search-box">' . "\r\n";
		echo "\t\t" . '<label class="screen-reader-text" for="media-search-input">Search Media:</label>' . "\r\n";
		echo "\t\t" . '<input type="text" name="mla_search_value" id="media-search-input" size="43" value="' . $search_value . '" />' . "\r\n";
		echo "\t\t" . '<input type="submit" name="mla_search_submit" id="search-submit" class="button" value="Search Media"  /><br>' . "\r\n";
		if ( 'OR' == $search_connector ) {
			echo "\t\t" . '<input type="radio" name="mla_search_connector" value="AND" />&nbsp;and&nbsp;' . "\r\n";
			echo "\t\t" . '<input type="radio" name="mla_search_connector" checked="checked" value="OR" />&nbsp;or&nbsp;' . "\r\n";
		} else {
			echo "\t\t" . '<input type="radio" name="mla_search_connector" checked="checked" value="AND" />&nbsp;and&nbsp;' . "\r\n";
			echo "\t\t" . '<input type="radio" name="mla_search_connector" value="OR" />&nbsp;or&nbsp;' . "\r\n";
		}

		if ( in_array( 'title', $search_fields ) )
			echo "\t\t" . '<input type="checkbox" name="mla_search_title" id="search-title" checked="checked" value="title" />&nbsp;title&nbsp;' . "\r\n";
		else
			echo "\t\t" . '<input type="checkbox" name="mla_search_title" id="search-title" value="title" />&nbsp;title&nbsp;' . "\r\n";
			
		if ( in_array( 'name', $search_fields ) )
			echo "\t\t" . '<input type="checkbox" name="mla_search_name" id="search-name" checked="checked" value="name" />&nbsp;name&nbsp;' . "\r\n";
		else
			echo "\t\t" . '<input type="checkbox" name="mla_search_name" id="search-name" value="name" />&nbsp;name&nbsp;' . "\r\n";

		if ( in_array( 'alt-text', $search_fields ) )
			echo "\t\t" . '<input type="checkbox" name="mla_search_alt_text" id="search-alt-text" checked="checked" value="alt-text" />&nbsp;ALT text&nbsp;' . "\r\n";
		else
			echo "\t\t" . '<input type="checkbox" name="mla_search_alt_text" id="search-alt-text" value="alt-text" />&nbsp;ALT text&nbsp;' . "\r\n";

		if ( in_array( 'excerpt', $search_fields ) )
			echo "\t\t" . '<input type="checkbox" name="mla_search_excerpt" id="search-excerpt" checked="checked" value="excerpt" />&nbsp;caption&nbsp;' . "\r\n";
		else
			echo "\t\t" . '<input type="checkbox" name="mla_search_excerpt" id="search-excerpt" value="excerpt" />&nbsp;caption&nbsp;' . "\r\n";

		if ( in_array( 'content', $search_fields ) )
			echo "\t\t" . '<input type="checkbox" name="mla_search_content" id="search-content" checked="checked" value="content" />&nbsp;description&nbsp;' . "\r\n";
		else
			echo "\t\t" . '<input type="checkbox" name="mla_search_content" id="search-content" value="content" />&nbsp;description&nbsp;' . "\r\n";

		echo "\t\t" . '</p>' . "\r\n";
		echo "\t" . '</script>' . "\r\n";
	} // mla_print_media_templates_action

	/**
	 * Adjust ajax handler for Media Manager queries 
	 *
	 * Replace 'query-attachments' with our own handler if the request is coming from the "Assistant" tab
	 *
	 * @since 1.20
	 *
	 * @return	void	
	 */
	public static function mla_admin_init_ajax_action() {
		if ( ( defined('WP_ADMIN') && WP_ADMIN ) && ( defined('DOING_AJAX') && DOING_AJAX ) ) {
			if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'query-attachments' ) && isset( $_POST['query']['mla_source'] ) ){
				$_POST['action'] = self::JAVASCRIPT_MEDIA_MODAL_SLUG;
				$_REQUEST['action'] = self::JAVASCRIPT_MEDIA_MODAL_SLUG;
			}
		}
	} // mla_print_media_templates_action

	/**
	 * Ajax handler for Media Manager queries 
	 *
	 * Adapted from wp_ajax_query_attachments in /wp-admin/includes/ajax-actions.php
	 *
	 * @since 1.20
	 *
	 * @return	void	echo HTML <tr> markup for updated row or error message, then die()
	 */
	public static function mla_query_attachments_action() {
		if ( ! current_user_can( 'upload_files' ) )
			wp_send_json_error();

		/*
		 * Pick out and clean up the query terms we can process
		 */
		$query = isset( $_REQUEST['query'] ) ? (array) $_REQUEST['query'] : array();
		$query = array_intersect_key( $query, array_flip( array(
			'order', 'orderby', 'posts_per_page', 'paged', 'post_mime_type',
			'post_parent', 'post__in', 'post__not_in', 'm', 'mla_filter_term',
			'mla_search_value', 'mla_search_fields', 'mla_search_connector'
		) ) );

		if ( isset( $query['post_mime_type'] ) ) {
			if ( 'detached' == $query['post_mime_type'] ) {
				$query['detached'] = '1';
				unset( $query['post_mime_type'] );
			}
			else {
				$view = $query['post_mime_type'];
				unset( $query['post_mime_type'] );
				$query = array_merge( $query, MLAMime::mla_prepare_view_query( $view ) );
			}
		}
		
		if ( isset( $query['mla_search_value'] ) ) {
			if ( ! empty( $query['mla_search_value'] ) )
				$query['s'] = $query['mla_search_value'];
			else
				unset( $query['s'] );
				
			unset( $query['mla_search_value'] );
		}
		
		if ( isset( $query['posts_per_page'] ) ) {
			$count = $query['posts_per_page'];
			$offset = $count * (isset( $query['paged'] ) ? $query['paged'] - 1 : 0);
		}
		else {
			$count = 0;
			$offset = 0;
		}
		
		$query['post_type'] = 'attachment';
		$query['post_status'] = 'inherit';
		if ( current_user_can( get_post_type_object( 'attachment' )->cap->read_private_posts ) )
			$query['post_status'] .= ',private';
	
		$query = MLAData::mla_query_media_modal_items( $query, $offset, $count );
	
		$posts = array_map( 'wp_prepare_attachment_for_js', $query->posts );
		$posts = array_filter( $posts );
	
		wp_send_json_success( $posts );
	}
} //Class MLAModal
?>