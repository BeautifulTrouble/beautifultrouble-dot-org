<?php
/**
 * Media Library Assistant Shortcode handler(s)
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Shortcodes defines the shortcodes available to MLA users
 *
 * @package Media Library Assistant
 * @since 0.20
 */
class MLAShortcodes {
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.20
	 *
	 * @return	void
	 */
	public static function initialize() {
		add_shortcode( 'mla_attachment_list', 'MLAShortcodes::mla_attachment_list_shortcode' );
		add_shortcode( 'mla_gallery', 'MLAShortcodes::mla_gallery_shortcode' );
	}

	/**
	 * WordPress Shortcode; renders a complete list of all attachments and references to them
	 *
	 * @since 0.1
	 *
	 * @return	void	echoes HTML markup for the attachment list
	 */
	public static function mla_attachment_list_shortcode( /* $atts */ ) {
		global $wpdb;
		
		/*	extract(shortcode_atts(array(
		'item_type'=>'attachment',
		'organize_by'=>'title',
		), $atts)); */
		
		/*
		 * Process the where-used settings option
		 */
		if ('checked' == MLAOptions::mla_get_option( 'exclude_revisions' ) )
			$exclude_revisions = "(post_type <> 'revision') AND ";
		else
			$exclude_revisions = '';
				
		$attachments = $wpdb->get_results(
				"
				SELECT ID, post_title, post_name, post_parent
				FROM {$wpdb->posts}
				WHERE {$exclude_revisions}post_type = 'attachment' 
				"
		);
		
		foreach ( $attachments as $attachment ) {
			$references = MLAData::mla_fetch_attachment_references( $attachment->ID, $attachment->post_parent );
			
			echo '&nbsp;<br><h3>' . $attachment->ID . ', ' . esc_attr( $attachment->post_title ) . ', Parent: ' . $attachment->post_parent . '<br>' . esc_attr( $attachment->post_name ) . '<br>' . esc_html( $references['base_file'] ) . "</h3>\r\n";
			
			/*
			 * Look for the "Featured Image(s)"
			 */
			if ( empty( $references['features'] ) ) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;not featured in any posts.<br>\r\n";
			} else {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;Featured in<br>\r\n";
				foreach ( $references['features'] as $feature_id => $feature ) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					
					if ( $feature_id == $attachment->post_parent ) {
						echo 'PARENT ';
						$found_parent = true;
					}
					
					echo $feature_id . ' (' . $feature->post_type . '), ' . esc_attr( $feature->post_title ) . "<br>\r\n";
				}
			}
			
			/*
			 * Look for item(s) inserted in post_content
			 */
			if ( empty( $references['inserts'] ) ) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;no inserts in any post_content.<br>\r\n";
			} else {
				foreach ( $references['inserts'] as $file => $inserts ) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $file . " inserted in<br>\r\n";
					foreach ( $inserts as $insert ) {
						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						
						if ( $insert->ID == $attachment->post_parent ) {
							echo 'PARENT ';
							$found_parent = true;
						}
						
						echo $insert->ID . ' (' . $insert->post_type . '), ' . esc_attr( $insert->post_title ) . "<br>\r\n";
					} // foreach $insert
				} // foreach $file
			}
			
			$errors = '';
			
			if ( !$references['found_reference'] )
				$errors .= '(ORPHAN) ';
			
			if ( $references['is_unattached'] )
				$errors .= '(UNATTACHED) ';
			else {
				if ( !$references['found_parent'] ) {
					if ( isset( $references['parent_title'] ) )
						$errors .= '(BAD PARENT) ';
					else
						$errors .= '(INVALID PARENT) ';
				}
			}
			
			if ( !empty( $errors ) )
				echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $errors . "<br>\r\n";
		} // foreach attachment
		
		echo "<br>----- End of Report -----\r\n";
	}
	
	/**
	 * Accumulates debug messages
	 *
	 * @since 0.60
	 *
	 * @var	string
	 */
	public static $mla_debug_messages = '';
	
	/**
	 * Turn debug collection and display on or off
	 *
	 * @since 0.70
	 *
	 * @var	boolean
	 */
	private static $mla_debug = false;
	
	/**
	 * The MLA Gallery shortcode.
	 *
	 * This is a superset of the WordPress Gallery shortcode for displaying images on a post,
	 * page or custom post type. It is adapted from /wp-includes/media.php gallery_shortcode.
	 * Enhancements include many additional selection parameters and full taxonomy support.
	 *
	 * @since .50
	 *
	 * @param array $attr Attributes of the shortcode.
	 *
	 * @return string HTML content to display gallery.
	 */
	public static function mla_gallery_shortcode($attr) {
		global $post;

		/*
		 * Some do_shortcode callers may not have a specific post in mind
		 */
		if ( ! is_object( $post ) )
			$post = (object) array( 'ID' => 0 );
			
		/*
		 * These are the parameters for gallery display
		 */
		$mla_arguments = array(
			'mla_output' => 'gallery',
			'mla_style' => MLAOptions::mla_get_option('default_style'),
			'mla_markup' => MLAOptions::mla_get_option('default_markup'),
			'mla_float' => is_rtl() ? 'right' : 'left',
			'mla_itemwidth' => NULL,
			'mla_margin' => '1.5',
			'mla_link_href' => '',
			'mla_link_attributes' => '',
			'mla_link_text' => '',
			'mla_rollover_text' => '',
			'mla_image_class' => '',
			'mla_image_alt' => '',
			'mla_image_attributes' => '',
			'mla_caption' => '',
			'mla_target' => '',
			'mla_debug' => false,
			'mla_viewer' => false,
			'mla_viewer_extensions' => 'doc,xls,ppt,pdf,txt',
			'mla_viewer_page' => '1',
			'mla_viewer_width' => '150',
			'mla_alt_shortcode' => NULL,
			'mla_alt_ids_name' => 'ids'
		);
		
		$default_arguments = array_merge( array(
			'size' => 'thumbnail', // or 'medium', 'large', 'full' or registered size
			'itemtag' => 'dl',
			'icontag' => 'dt',
			'captiontag' => 'dd',
			'columns' => 3,
			'link' => 'permalink', // or 'post' or file' or a registered size
			// Photonic-specific
			'id' => NULL,
			'style' => NULL,
			'type' => 'default', // also used by WordPress.com Jetpack!
			'thumb_width' => 75,
			'thumb_height' => 75,
			'thumbnail_size' => 'thumbnail',
			'slide_size' => 'large',
			'slideshow_height' => 500,
			'fx' => 'fade',
			'timeout' => 4000,
			'speed' => 1000,
			'pause' => NULL),
			$mla_arguments
		);
		
		/*
		 * Look for 'request' substitution parameters,
		 * which can be added to any input parameter
		 */
		foreach ( $attr as $attr_key => $attr_value ) {
			$attr_value = str_replace( '{+', '[+', str_replace( '+}', '+]', $attr_value ) );
			$replacement_values = array();
			$placeholders = MLAData::mla_get_template_placeholders( $attr_value );
			foreach ($placeholders as $key => $value ) {
				if ( 'request' == $value['prefix'] ) {
					if ( isset( $_REQUEST[ $value['value'] ] ) )
						$replacement_values[ $key ] = $_REQUEST[ $value['value'] ];
					else
						$replacement_values[ $key ] = '';
				}
			} // $placeholders
			
			if ( ! empty( $replacement_values ) )
				$attr[ $attr_key ] = MLAData::mla_parse_template( $attr_value, $replacement_values );
		}
		
		/*
		 * Merge gallery arguments with defaults, pass the query arguments on to mla_get_shortcode_attachments.
		 */
		 
		$arguments = shortcode_atts( $default_arguments, $attr );
		self::$mla_debug = !empty( $arguments['mla_debug'] ) && ( 'true' == strtolower( $arguments['mla_debug'] ) );

		$attachments = self::mla_get_shortcode_attachments( $post->ID, $attr );
			
		if ( is_string( $attachments ) )
			return $attachments;
			
		if ( empty($attachments) ) {
			if ( self::$mla_debug ) {
				$output = '<p><strong>mla_debug empty gallery</strong>, query = ' . var_export( $attr, true ) . '</p>';
				$output .= self::$mla_debug_messages;
				self::$mla_debug_messages = '';
				return $output;
			}
			else {
				return '';
			}
		} // empty $attachments
	
		/*
		 * Look for user-specified alternate gallery shortcode
		 */
		if ( is_string( $arguments['mla_alt_shortcode'] ) ) {
			/*
			 * Replace data-selection parameters with the "ids" list
			 */
			$blacklist = array_merge( $mla_arguments, self::$data_selection_parameters );
			$new_args = '';
			foreach ( $attr as $key => $value ) {
				if ( array_key_exists( $key, $blacklist ) ) {
					continue;
				}
				
				$slashed = addcslashes( $value, chr(0).chr(7).chr(8)."\f\n\r\t\v\"\\\$" );
				if ( ( false !== strpos( $value, ' ' ) ) || ( false !== strpos( $value, '\'' ) ) || ( $slashed != $value ) ) {
					$value = '"' . $slashed . '"';
				}
				
				$new_args .= empty( $new_args ) ? $key . '=' . $value : ' ' . $key . '=' . $value;
			} // foreach $attr
			
			$new_ids = '';
			foreach ( $attachments as $value ) {
				$new_ids .= empty( $new_ids ) ? (string) $value->ID : ',' . $value->ID;
			} // foreach $attachments

			$new_ids = $arguments['mla_alt_ids_name'] . '="' . $new_ids . '"';
			
			if ( self::$mla_debug ) {
				$output = self::$mla_debug_messages;
				self::$mla_debug_messages = '';
			}
			else
				$output = '';
			/*
			 * Execute the alternate gallery shortcode with the new parameters
			 */
			return $output . do_shortcode( sprintf( '[%1$s %2$s %3$s]', $arguments['mla_alt_shortcode'], $new_ids, $new_args ) );
		} // mla_alt_shortcode

		/*
		 * Look for Photonic-enhanced gallery
		 */
		global $photonic;
		
		if ( is_object( $photonic ) && ! empty( $arguments['style'] ) ) {
			if ( 'default' != strtolower( $arguments['type'] ) ) 
				return '<p><strong>Photonic-enhanced [mla_gallery]</strong> type must be <strong>default</strong>, query = ' . var_export( $attr, true ) . '</p>';

			$images = array();
			foreach ($attachments as $key => $val) {
				$images[$val->ID] = $attachments[$key];
			}
			
			if ( isset( $arguments['pause'] ) && ( 'false' == $arguments['pause'] ) )
				$arguments['pause'] = NULL;

			$output = $photonic->build_gallery( $images, $arguments['style'], $arguments );
			return $output;
		}
		
		$size = $size_class = $arguments['size'];
		if ( 'icon' == strtolower( $size) ) {
			if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_ENABLE_MLA_ICONS ) )
				$size = array( 64, 64 );
			else
				$size = array( 60, 60 );
				
			$show_icon = true;
		}
		else
			$show_icon = false;
		
		/*
		 * Feeds such as RSS, Atom or RDF do not require styled and formatted output
		 */
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}

		/*
		 * Check for Google File Viewer arguments
		 */
		$arguments['mla_viewer'] = !empty( $arguments['mla_viewer'] ) && ( 'true' == strtolower( $arguments['mla_viewer'] ) );
		if ( $arguments['mla_viewer'] ) {
			$arguments['mla_viewer_extensions'] = array_filter( array_map( 'trim', explode( ',', $arguments['mla_viewer_extensions'] ) ) );
			$arguments['mla_viewer_page'] = absint( $arguments['mla_viewer_page'] );
			$arguments['mla_viewer_width'] = absint( $arguments['mla_viewer_width'] );
		}
			
		// $instance supports multiple galleries in one page/post	
		static $instance = 0;
		$instance++;

		/*
		 * The default style template includes "margin: 1.5%" to put a bit of
		 * minimum space between the columns. "mla_margin" can be used to increase
		 * this. "mla_itemwidth" can be used with "columns=0" to achieve a "responsive"
		 * layout.
		 */
		 
		$margin = absint( 2 * (float) $arguments['mla_margin'] );
		if ( isset ( $arguments['mla_itemwidth'] ) ) {
			$itemwidth = absint( $arguments['mla_itemwidth'] );
		}
		else {
			$itemwidth = $arguments['columns'] > 0 ? (floor(100/$arguments['columns']) - $margin) : 100 - $margin;
		}
		
		$float = strtolower( $arguments['mla_float'] );
		if ( ! in_array( $float, array( 'left', 'none', 'right' ) ) )
			$float = is_rtl() ? 'right' : 'left';
		
		$style_values = array(
			'mla_style' => $arguments['mla_style'],
			'mla_markup' => $arguments['mla_markup'],
			'instance' => $instance,
			'id' => $post->ID,
			'itemtag' => tag_escape( $arguments['itemtag'] ),
			'icontag' => tag_escape( $arguments['icontag'] ),
			'captiontag' => tag_escape( $arguments['captiontag'] ),
			'columns' => intval( $arguments['columns']),
			'itemwidth' => intval( $itemwidth ),
			'margin' => $arguments['mla_margin'],
			'float' => $float,
			'selector' => "mla_gallery-{$instance}",
			'size_class' => sanitize_html_class( $size_class )
		);

		$style_template = $gallery_style = '';
		$use_mla_gallery_style = ( 'none' != strtolower( $style_values['mla_style'] ) );
		if ( apply_filters( 'use_mla_gallery_style', $use_mla_gallery_style, $style_values['mla_style'] ) ) {
			$style_template = MLAOptions::mla_fetch_gallery_template( $style_values['mla_style'], 'style' );
			if ( empty( $style_template ) ) {
				$style_values['mla_style'] = 'default';
				$style_template = MLAOptions::mla_fetch_gallery_template( 'default', 'style' );
			}
				
			if ( ! empty ( $style_template ) ) {
				/*
				 * Look for 'query' and 'request' substitution parameters
				 */
				$placeholders = MLAData::mla_get_template_placeholders( $style_template );
				foreach ($placeholders as $key => $value ) {
					if ( 'query' == $value['prefix'] ) {
						if ( isset( $attr[ $value['value'] ] ) )
							$style_values[ $key ] = $attr[ $value['value'] ];
						else
							$style_values[ $key ] = '';
					}
					elseif ( 'request' == $value['prefix'] ) {
						if ( isset( $_REQUEST[ $value['value'] ] ) )
							$style_values[ $key ] = $_REQUEST[ $value['value'] ];
						else
							$style_values[ $key ] = '';
					}
				} // $placeholders
				 
				$gallery_style = MLAData::mla_parse_template( $style_template, $style_values );
			} // !empty template
		} // use_mla_gallery_style
		
		$upload_dir = wp_upload_dir();
		$markup_values = $style_values;
		$markup_values['site_url'] = site_url();
		$markup_values['base_url'] = $upload_dir['baseurl'];
		$markup_values['base_dir'] = $upload_dir['basedir'];

		/*
		 * Variable 'query' and 'request' placeholders can be anywhere in the markup template
		 */
		$query_placeholders = array();
		$request_placeholders = array();

		/*
		 * Variable item-level placeholders
		 */
		$meta_placeholders = array();
		$terms_placeholders = array();
		$custom_placeholders = array();
		$iptc_placeholders = array();
		$exif_placeholders = array();

		$open_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-open', 'markup' );
		if ( false === $open_template ) {
			$markup_values['mla_markup'] = 'default';
			$open_template = MLAOptions::mla_fetch_gallery_template( 'default-open', 'markup' );
		}
			
		if ( empty( $open_template ) )
			$open_template = '';

		$row_open_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-row-open', 'markup' );
		if ( empty( $row_open_template ) )
			$row_open_template = '';
				
		$item_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-item', 'markup' );
		if ( empty( $item_template ) )
			$item_template = '';

		$row_close_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-row-close', 'markup' );
		if ( empty( $row_close_template ) )
			$row_close_template = '';
			
		$close_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-close', 'markup' );
		if ( empty( $close_template ) )
			$close_template = '';

		/*
		 * Look for variable query and item-level placeholders
		 */
		$new_text = str_replace( '{+', '[+', str_replace( '+}', '+]', $arguments['mla_link_href'] . $arguments['mla_link_attributes'] . $arguments['mla_link_text'] . $arguments['mla_rollover_text'] . $arguments['mla_image_class'] . $arguments['mla_image_alt'] . $arguments['mla_image_attributes'] . $arguments['mla_caption'] ) );

		$placeholders = MLAData::mla_get_template_placeholders( $new_text . $open_template . $row_open_template . $item_template . $row_close_template . $close_template );
		foreach ($placeholders as $key => $value ) {
			switch ( $value['prefix'] ) {
				case 'meta':
					$meta_placeholders[ $key ] = $value;
					break;
				case 'query':
					$query_placeholders[ $key ] = $value;
					break;
				case 'request':
					$request_placeholders[ $key ] = $value;
					break;
				case 'terms':
					$terms_placeholders[ $key ] = $value;
					break;
				case 'custom':
					$custom_placeholders[ $key ] = $value;
					break;
				case 'iptc':
					$iptc_placeholders[ $key ] = $value;
					break;
				case 'exif':
					$exif_placeholders[ $key ] = $value;
					break;
				default:
					// ignore anything else
			} // switch
		} // $placeholders
				
		/*
		 * Add 'query' and 'request' placeholders
		 */
		foreach ( $query_placeholders as $key => $value ) {
			if ( isset( $attr[ $value['value'] ] ) )
				$markup_values[ $key ] = $attr[ $value['value'] ];
			else
				$markup_values[ $key ] = '';
		} // $query_placeholders

		foreach ( $request_placeholders as $key => $value ) {
			if ( isset( $_REQUEST[ $value['value'] ] ) )
				$markup_values[ $key ] = $_REQUEST[ $value['value'] ];
			else
				$markup_values[ $key ] = '';
		} // $request_placeholders

		/*
		 * Determine output type
		 */
		$output = strtolower( trim( $arguments['mla_output'] ) );	
		if ( ! $is_gallery = 'gallery' == $output ) {
		}
		
		if ( self::$mla_debug ) {
			$output = self::$mla_debug_messages;
			self::$mla_debug_messages = '';
		}
		else
			$output = '';

		if ($is_gallery ) {
			if ( empty( $open_template ) )
				$gallery_div = '';
			else
				$gallery_div = MLAData::mla_parse_template( $open_template, $markup_values );
	
			$output .= apply_filters( 'mla_gallery_style', $gallery_style . $gallery_div, $style_values, $markup_values, $style_template, $open_template );
		}
		
		/*
		 * For "previous_link" and "next_link", discard all of the $attachments except the appropriate choice
		 */
		if ( ! $is_gallery ) {
			$output_parameters = explode( ',', $arguments['mla_output'] );
			$output_type = strtolower( trim( $output_parameters[0] ) );
			
			$is_previous = 'previous_link' == $output_type;
			$is_next = 'next_link' == $output_type;
			
			if ( ! ( $is_previous || $is_next ) )
				return ''; // unknown outtput type
			
			$is_wrap = isset( $output_parameters[1] ) && 'wrap' == strtolower( trim( $output_parameters[1] ) );
			$current_id = empty( $arguments['id'] ) ? $markup_values['id'] : $arguments['id'];
				
			foreach ( $attachments as $id => $attachment ) {
				if ( $attachment->ID == $current_id )
					break;
			}
		
			$target_id = $is_previous ? $id - 1 : $id + 1;
			if ( isset( $attachments[ $target_id ] ) ) {
				$attachments = array( $attachments[ $target_id ] );
			}
			elseif ( $is_wrap ) {
				if ( $is_next )
					$attachments = array( array_shift( $attachments ) );
				else
					$attachments = array( array_pop( $attachments ) );
			} // is_wrap
			else
				return '';
		} // ! is_gallery
		
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			/*
			 * fill in item-specific elements
			 */
			$markup_values['index'] = (string) 1 + $i;

			$markup_values['excerpt'] = wptexturize( $attachment->post_excerpt );
			$markup_values['attachment_ID'] = $attachment->ID;
			$markup_values['mime_type'] = $attachment->post_mime_type;
			$markup_values['menu_order'] = $attachment->menu_order;
			$markup_values['date'] = $attachment->post_date;
			$markup_values['modified'] = $attachment->post_modified;
			$markup_values['parent'] = $attachment->post_parent;
			$markup_values['parent_title'] = '(unattached)';
			$markup_values['parent_type'] = '';
			$markup_values['parent_date'] = '';
			$markup_values['title'] = wptexturize( $attachment->post_title );
			$markup_values['slug'] = wptexturize( $attachment->post_name );
			$markup_values['width'] = '';
			$markup_values['height'] = '';
			$markup_values['image_meta'] = '';
			$markup_values['image_alt'] = '';
			$markup_values['base_file'] = '';
			$markup_values['path'] = '';
			$markup_values['file'] = '';
			$markup_values['description'] = wptexturize( $attachment->post_content );
			$markup_values['file_url'] = wptexturize( $attachment->guid );
			$markup_values['author_id'] = $attachment->post_author;
		
			$user = get_user_by( 'id', $attachment->post_author );
			if ( isset( $user->data->display_name ) )
				$markup_values['author'] = wptexturize( $user->data->display_name );
			else
				$markup_values['author'] = 'unknown';

			$post_meta = MLAData::mla_fetch_attachment_metadata( $attachment->ID );
			$base_file = $post_meta['mla_wp_attached_file'];
			$sizes = isset( $post_meta['mla_wp_attachment_metadata']['sizes'] ) ? $post_meta['mla_wp_attachment_metadata']['sizes'] : array();

			if ( !empty( $post_meta['mla_wp_attachment_metadata']['width'] ) )
				$markup_values['width'] = $post_meta['mla_wp_attachment_metadata']['width'];
			if ( !empty( $post_meta['mla_wp_attachment_metadata']['height'] ) )
				$markup_values['height'] = $post_meta['mla_wp_attachment_metadata']['height'];
			if ( !empty( $post_meta['mla_wp_attachment_metadata']['image_meta'] ) )
				$markup_values['image_meta'] = wptexturize( var_export( $post_meta['mla_wp_attachment_metadata']['image_meta'], true ) );
			if ( !empty( $post_meta['mla_wp_attachment_image_alt'] ) )
				$markup_values['image_alt'] = wptexturize( $post_meta['mla_wp_attachment_image_alt'] );

			if ( ! empty( $base_file ) ) {
				$last_slash = strrpos( $base_file, '/' );
				if ( false === $last_slash ) {
					$file_name = $base_file;
					$markup_values['base_file'] = wptexturize( $base_file );
					$markup_values['file'] = wptexturize( $base_file );
				}
				else {
					$file_name = substr( $base_file, $last_slash + 1 );
					$markup_values['base_file'] = wptexturize( $base_file );
					$markup_values['path'] = wptexturize( substr( $base_file, 0, $last_slash + 1 ) );
					$markup_values['file'] = wptexturize( $file_name );
				}
			}
			else
				$file_name = '';

			$parent_info = MLAData::mla_fetch_attachment_parent_data( $attachment->post_parent );
			if ( isset( $parent_info['parent_title'] ) )
				$markup_values['parent_title'] = wptexturize( $parent_info['parent_title'] );
				
			if ( isset( $parent_info['parent_date'] ) )
				$markup_values['parent_date'] = wptexturize( $parent_info['parent_date'] );
				
			if ( isset( $parent_info['parent_type'] ) )
				$markup_values['parent_type'] = wptexturize( $parent_info['parent_type'] );
				
			/*
			 * Add variable placeholders
			 */
			$image_metadata = get_metadata( 'post', $attachment->ID, '_wp_attachment_metadata', true );
			foreach ( $meta_placeholders as $key => $value ) {
				$markup_values[ $key ] = MLAData::mla_find_array_element( $value['value'], $image_metadata, $value['option'] );
			} // $meta_placeholders */
			
			foreach ( $terms_placeholders as $key => $value ) {
				$terms = wp_get_object_terms( $attachment->ID, $value['value'] );
			
				$text = '';
				if ( is_wp_error( $terms ) ) {
					$text = implode( ',', $terms->get_error_messages() );
				}
				elseif ( ! empty( $terms ) ) {
					if ( 'single' == $value['option'] || 1 == count( $terms ) )
						$text = sanitize_term_field( 'name', $terms[0]->name, $terms[0]->term_id, $value, 'display' );
					elseif ( 'export' == $value['option'] )
						$text = sanitize_text_field( var_export( $terms, true ) );
					else
						foreach ( $terms as $term ) {
							$term_name = sanitize_term_field( 'name', $term->name, $term->term_id, $value, 'display' );
							$text .= strlen( $text ) ? ', ' . $term_name : $term_name;
						}
				}
				
				$markup_values[ $key ] = $text;
			} // $terms_placeholders
			
			foreach ( $custom_placeholders as $key => $value ) {
				$record = get_metadata( 'post', $attachment->ID, $value['value'], 'single' == $value['option'] );

				$text = '';
				if ( is_wp_error( $record ) )
					$text = implode( ',', $terms->get_error_messages() );
				elseif ( ! empty( $record ) ) {
					if ( is_scalar( $record ) )
						$text = sanitize_text_field( (string) $record );
					elseif ( is_array( $record ) ) {
						if ( 'export' == $value['option'] )
							$text = sanitize_text_field( var_export( $haystack, true ) );
						else {
							$text = '';
							foreach ( $record as $term ) {
								$term_name = sanitize_text_field( $term );
								$text .= strlen( $text ) ? ', ' . $term_name : $term_name;
							}
						}
					} // is_array
				} // ! empty
				
				$markup_values[ $key ] = $text;
			} // $custom_placeholders
			
			if ( !empty( $iptc_placeholders ) || !empty( $exif_placeholders ) ) {
				$image_metadata = MLAData::mla_fetch_attachment_image_metadata( $attachment->ID );
			}
			
			foreach ( $iptc_placeholders as $key => $value ) {
				$text = '';
				$record = MLAData::mla_iptc_metadata_value( $value['value'], $image_metadata );
				if ( is_array( $record ) ) {
					if ( 'single' == $value['option'] )
						$text = sanitize_text_field( array_shift( $record ) );
					elseif ( 'export' == $value['option'] )
						$text = sanitize_text_field( var_export( $record, true ) );
					else
						foreach ( $record as $term ) {
							$term_name = sanitize_text_field( $term );
							$text .= strlen( $text ) ? ', ' . $term_name : $term_name;
						}
				} // is_array
				else
					$text = $record;
					
				$markup_values[ $key ] = $text;
			} // $iptc_placeholders
			
			foreach ( $exif_placeholders as $key => $value ) {
				$text = '';
				$record = MLAData::mla_exif_metadata_value( $value['value'], $image_metadata );
				if ( is_array( $record ) ) {
					if ( 'single' == $value['option'] )
						$text = sanitize_text_field( array_shift( $record ) );
					elseif ( 'export' == $value['option'] )
						$text = sanitize_text_field( var_export( $record, true ) );
					else
						foreach ( $record as $term ) {
							$term_name = sanitize_text_field( $term );
							$text .= strlen( $text ) ? ', ' . $term_name : $term_name;
						}
				} // is_array
				else
					$text = $record;
					
				$markup_values[ $key ] = $text;
			} // $exif_placeholders
			
			unset(
				$markup_values['caption'],
				$markup_values['pagelink'],
				$markup_values['filelink'],
				$markup_values['link'],
				$markup_values['pagelink_url'],
				$markup_values['filelink_url'],
				$markup_values['link_url'],
				$markup_values['thumbnail_content'],
				$markup_values['thumbnail_width'],
				$markup_values['thumbnail_height'],
				$markup_values['thumbnail_url']
			);
			
			if ( $markup_values['captiontag'] ) {
				$markup_values['caption'] = wptexturize( $attachment->post_excerpt );
				if ( ! empty( $arguments['mla_caption'] ) )
					$markup_values['caption'] = wptexturize( self::_process_shortcode_parameter( $arguments['mla_caption'], $markup_values ) );
			}
			else
				$markup_values['caption'] = '';
			
			if ( ! empty( $arguments['mla_link_text'] ) )
				$link_text = self::_process_shortcode_parameter( $arguments['mla_link_text'], $markup_values );
			else
				$link_text = false;

			$markup_values['pagelink'] = wp_get_attachment_link($attachment->ID, $size, true, $show_icon, $link_text);
			$markup_values['filelink'] = wp_get_attachment_link($attachment->ID, $size, false, $show_icon, $link_text);

			/*
			 * Apply the Gallery Display Content parameters.
			 * Note that $link_attributes and $rollover_text
			 * are used in the Google Viewer code below
			 */
			if ( ! empty( $arguments['mla_target'] ) )
				$link_attributes = 'target="' . $arguments['mla_target'] . '" ';
			else
				$link_attributes = '';
				
			if ( ! empty( $arguments['mla_link_attributes'] ) )
				$link_attributes .= self::_process_shortcode_parameter( $arguments['mla_link_attributes'], $markup_values ) . ' ';

			if ( ! empty( $link_attributes ) ) {
				$markup_values['pagelink'] = str_replace( '<a href=', '<a ' . $link_attributes . 'href=', $markup_values['pagelink'] );
				$markup_values['filelink'] = str_replace( '<a href=', '<a ' . $link_attributes . 'href=', $markup_values['filelink'] );
			}
			
			if ( ! empty( $arguments['mla_rollover_text'] ) ) {
				$rollover_text = esc_attr( self::_process_shortcode_parameter( $arguments['mla_rollover_text'], $markup_values ) );
				
				/*
				 * Replace single- and double-quote delimited values
				 */
				$markup_values['pagelink'] = preg_replace('# title=\'([^\']*)\'#', " title='{$rollover_text}'", $markup_values['pagelink'] );
				$markup_values['pagelink'] = preg_replace('# title=\"([^\"]*)\"#', " title=\"{$rollover_text}\"", $markup_values['pagelink'] );
				$markup_values['filelink'] = preg_replace('# title=\'([^\']*)\'#', " title='{$rollover_text}'", $markup_values['filelink'] );
				$markup_values['filelink'] = preg_replace('# title=\"([^\"]*)\"#', " title=\"{$rollover_text}\"", $markup_values['filelink'] );
			}
			else
				$rollover_text = $markup_values['title'];

			/*
			 * Process the <img> tag, if present
			 * Note that $image_attributes, $image_class and $image_alt
			 * are used in the Google Viewer code below
			 */
			if ( ! empty( $arguments['mla_image_attributes'] ) )
				$image_attributes = self::_process_shortcode_parameter( $arguments['mla_image_attributes'], $markup_values ) . ' ';
			else
				$image_attributes = '';
				
			if ( ! empty( $arguments['mla_image_class'] ) )
				$image_class = esc_attr( self::_process_shortcode_parameter( $arguments['mla_image_class'], $markup_values ) );
			else
				$image_class = '';

				if ( ! empty( $arguments['mla_image_alt'] ) )
					$image_alt = esc_attr( self::_process_shortcode_parameter( $arguments['mla_image_alt'], $markup_values ) );
				else
					$image_alt = '';

			if ( false !== strpos( $markup_values['pagelink'], '<img ' ) ) {
				if ( ! empty( $image_attributes ) ) {
					$markup_values['pagelink'] = str_replace( '<img ', '<img ' . $image_attributes, $markup_values['pagelink'] );
					$markup_values['filelink'] = str_replace( '<img ', '<img ' . $image_attributes, $markup_values['filelink'] );
				}
				
				/*
				 * Extract existing class values and add to them
				 */
				if ( ! empty( $image_class ) ) {
					$match_count = preg_match_all( '# class=\"([^\"]+)\" #', $markup_values['pagelink'], $matches, PREG_OFFSET_CAPTURE );
					if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
						$class = $matches[1][0][0] . ' ' . $image_class;
					}
					else
						$class = $image_class;
					
					$markup_values['pagelink'] = preg_replace('# class=\"([^\"]*)\"#', " class=\"{$class}\"", $markup_values['pagelink'] );
					$markup_values['filelink'] = preg_replace('# class=\"([^\"]*)\"#', " class=\"{$class}\"", $markup_values['filelink'] );
				}
				
				if ( ! empty( $image_alt ) ) {
					$markup_values['pagelink'] = preg_replace('# alt=\"([^\"]*)\"#', " alt=\"{$image_alt}\"", $markup_values['pagelink'] );
					$markup_values['filelink'] = preg_replace('# alt=\"([^\"]*)\"#', " alt=\"{$image_alt}\"", $markup_values['filelink'] );
				}
			} // process <img> tag
			
			switch ( $arguments['link'] ) {
				case 'permalink':
				case 'post':
					$markup_values['link'] = $markup_values['pagelink'];
					break;
				case 'file':
				case 'full':
					$markup_values['link'] = $markup_values['filelink'];
					break;
				default:
					$markup_values['link'] = $markup_values['filelink'];

					/*
					 * Check for link to specific (registered) file size
					 */
					if ( array_key_exists( $arguments['link'], $sizes ) ) {
						$target_file = $sizes[ $arguments['link'] ]['file'];
						$markup_values['link'] = str_replace( $file_name, $target_file, $markup_values['filelink'] );
					}
			} // switch 'link'
			
			/*
			 * Extract target and thumbnail fields
			 */
			$match_count = preg_match_all( '#href=\'([^\']+)\'#', $markup_values['pagelink'], $matches, PREG_OFFSET_CAPTURE );
 			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['pagelink_url'] = $matches[1][0][0];
			}
			else
				$markup_values['pagelink_url'] = '';

			$match_count = preg_match_all( '#href=\'([^\']+)\'#', $markup_values['filelink'], $matches, PREG_OFFSET_CAPTURE );
			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['filelink_url'] = $matches[1][0][0];
			}
			else
				$markup_values['filelink_url'] = '';

			$match_count = preg_match_all( '#href=\'([^\']+)\'#', $markup_values['link'], $matches, PREG_OFFSET_CAPTURE );
			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['link_url'] = $matches[1][0][0];
			}
			else
				$markup_values['link_url'] = '';

			/*
			 * Override the link value; leave filelink and pagelink unchanged
			 * Note that $link_href is used in the Google Viewer code below
			 */
			if ( ! empty( $arguments['mla_link_href'] ) ) {
				$link_href = self::_process_shortcode_parameter( $arguments['mla_link_href'], $markup_values );

				/*
				 * Replace single- and double-quote delimited values
				 */
				$markup_values['link'] = preg_replace('# href=\'([^\']*)\'#', " href='{$link_href}'", $markup_values['link'] );
				$markup_values['link'] = preg_replace('# href=\"([^\"]*)\"#', " href=\"{$link_href}\"", $markup_values['link'] );
			}
			else
				$link_href = '';
			
			$match_count = preg_match_all( '#\<a [^\>]+\>(.*)\</a\>#', $markup_values['link'], $matches, PREG_OFFSET_CAPTURE );
			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['thumbnail_content'] = $matches[1][0][0];
			}
			else
				$markup_values['thumbnail_content'] = '';

			$match_count = preg_match_all( '# width=\"([^\"]+)\" height=\"([^\"]+)\" src=\"([^\"]+)\" #', $markup_values['link'], $matches, PREG_OFFSET_CAPTURE );
			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['thumbnail_width'] = $matches[1][0][0];
				$markup_values['thumbnail_height'] = $matches[2][0][0];
				$markup_values['thumbnail_url'] = $matches[3][0][0];
			}
			else {
				$markup_values['thumbnail_width'] = '';
				$markup_values['thumbnail_height'] = '';
				$markup_values['thumbnail_url'] = '';
			}

			/*
			 * Check for Google file viewer substitution, uses above-defined
			 * $link_attributes (includes target), $rollover_text, $link_href (link only),
			 * $image_attributes, $image_class, $image_alt
			 */
			if ( $arguments['mla_viewer'] && empty( $markup_values['thumbnail_url'] ) ) {
				$last_dot = strrpos( $markup_values['file'], '.' );
				if ( !( false === $last_dot) ) {
					$extension = substr( $markup_values['file'], $last_dot + 1 );
					if ( in_array( $extension, $arguments['mla_viewer_extensions'] ) ) {
						/*
						 * <img> tag (thumbnail_text)
						 */
						if ( ! empty( $image_class ) )
							$image_class = ' class="' . $image_class . '"';
							
						if ( ! empty( $image_alt ) )
							$image_alt = ' alt="' . $image_alt . '"';
						elseif ( ! empty( $markup_values['caption'] ) )
							$image_alt = ' alt="' . $markup_values['caption'] . '"';

						$markup_values['thumbnail_content'] = sprintf( '<img %1$ssrc="http://docs.google.com/viewer?url=%2$s&a=bi&pagenumber=%3$d&w=%4$d"%5$s%6$s>', $image_attributes, $markup_values['filelink_url'], $arguments['mla_viewer_page'], $arguments['mla_viewer_width'], $image_class, $image_alt );
						
						/*
						 * Filelink, pagelink and link
						 */
						$markup_values['pagelink'] = sprintf( '<a %1$shref="%2$s" title="%3$s">%4$s</a>', $link_attributes, $markup_values['pagelink_url'], $rollover_text, $markup_values['thumbnail_content'] );
						$markup_values['filelink'] = sprintf( '<a %1$shref="%2$s" title="%3$s">%4$s</a>', $link_attributes, $markup_values['filelink_url'], $rollover_text, $markup_values['thumbnail_content'] );

						if ( ! empty( $link_href ) )
							$markup_values['link'] = sprintf( '<a %1$shref="%2$s" title="%3$s">%4$s</a>', $link_attributes, $link_href, $rollover_text, $markup_values['thumbnail_content'] );
						elseif ( 'permalink' == $arguments['link'] )
							$markup_values['link'] = $markup_values['pagelink'];
						else
							$markup_values['link'] = $markup_values['filelink'];
					} // viewer extension
				} // has extension
			} // mla_viewer
			
			if ($is_gallery ) {
				/*
				 * Start of row markup
				 */
				if ( $markup_values['columns'] > 0 && $i % $markup_values['columns'] == 0 )
					$output .= MLAData::mla_parse_template( $row_open_template, $markup_values );
				
				/*
				 * item markup
				 */
				$output .= MLAData::mla_parse_template( $item_template, $markup_values );
	
				/*
				 * End of row markup
				 */
				$i++;
				if ( $markup_values['columns'] > 0 && $i % $markup_values['columns'] == 0 )
					$output .= MLAData::mla_parse_template( $row_close_template, $markup_values );
			} // is_gallery
			elseif ( ( $is_previous || $is_next ) )
				return $markup_values['link'];
		}
	
		if ($is_gallery ) {
			/*
			 * Close out partial row
			 */
			if ( ! ($markup_values['columns'] > 0 && $i % $markup_values['columns'] == 0 ) )
				$output .= MLAData::mla_parse_template( $row_close_template, $markup_values );
				
			$output .= MLAData::mla_parse_template( $close_template, $markup_values );
		} // is_gallery
	
		return $output;
	}

	/**
	 * Handles brace/bracket escaping and parses template for a shortcode parameter
	 *
	 * @since 1.14
	 *
	 * @param string raw shortcode parameter, e.g., "text {+field+} {brackets} \\{braces\\}"
	 * @param string template substitution values, e.g., ('instance' => '1', ...  )
	 *
	 * @return string query specification with HTML escape sequences and line breaks removed
	 */
	private static function _process_shortcode_parameter( $text, $markup_values ) {
		$new_text = str_replace( '{', '[', str_replace( '}', ']', $text ) );
		$new_text = str_replace( '\[', '{', str_replace( '\]', '}', $new_text ) );
		return MLAData::mla_parse_template( $new_text, $markup_values );
	}
	
	/**
	 * WP_Query filter "parameters"
	 *
	 * This array defines parameters for the query's where and orderby filters,
	 * mla_shortcode_query_posts_where_filter and mla_shortcode_query_posts_orderby_filter.
	 * The parameters are set up in the mla_get_shortcode_attachments function, and
	 * any further logic required to translate those values is contained in the filter.
	 *
	 * Array index values are: orderby, post_parent
	 *
	 * @since 1.13
	 *
	 * @var	array
	 */
	private static $query_parameters = array();

	/**
	 * Cleans up damage caused by the Visual Editor to the tax_query and meta_query specifications
	 *
	 * @since 1.14
	 *
	 * @param string query specification; PHP nested arrays
	 *
	 * @return string query specification with HTML escape sequences and line breaks removed
	 */
	private static function _sanitize_query_specification( $specification ) {
		$specification = wp_specialchars_decode( $specification );
		$specification = str_replace( array( '<br />', '<p>', '</p>', "\r", "\n" ), ' ', $specification );
		return $specification;
	}
	
	/**
	 * Translates query parameters to a valid SQL order by clause.
	 *
	 * Accepts one or more valid columns, with or without ASC/DESC.
	 * Enhanced version of /wp-includes/formatting.php function sanitize_sql_orderby().
	 *
	 * @since 1.20
	 *
	 * @param array Validated query parameters
	 * @return string|bool Returns the orderby clause if present, false otherwise.
	 */
	private static function _validate_sql_orderby( $query_parameters ){
		global $wpdb;

		$results = array ();
		$order = isset( $query_parameters['order'] ) ? ' ' . $query_parameters['order'] : '';
		$orderby = isset( $query_parameters['orderby'] ) ? $query_parameters['orderby'] : '';
		$meta_key = isset( $query_parameters['meta_key'] ) ? $query_parameters['meta_key'] : '';
		$post__in = isset( $query_parameters['post__in'] ) ? implode(',', array_map( 'absint', $query_parameters['post__in'] )) : '';

		if ( empty( $orderby ) ) {
			$orderby = "$wpdb->posts.post_date " . $order;
		} elseif ( 'none' == $orderby ) {
			return '';
		} elseif ( $orderby == 'post__in' && ! empty( $post__in ) ) {
			$orderby = "FIELD( {$wpdb->posts}.ID, {$post__in} )";
		} else {
			$allowed_keys = array('ID', 'author', 'date', 'description', 'content', 'title', 'caption', 'excerpt', 'slug', 'name', 'modified', 'parent', 'menu_order', 'mime_type', 'comment_count', 'rand');
			if ( ! empty( $meta_key ) ) {
				$allowed_keys[] = $meta_key;
				$allowed_keys[] = 'meta_value';
				$allowed_keys[] = 'meta_value_num';
			}
		
			$obmatches = preg_split('/\s*,\s*/', trim($query_parameters['orderby']));
			foreach( $obmatches as $index => $value ) {
				$count = preg_match('/([a-z0-9_]+)(\s+(ASC|DESC))?/i', $value, $matches);

				if ( $count && ( $value == $matches[0] ) && in_array( $matches[1], $allowed_keys ) ) {
					if ( 'rand' == $matches[1] )
							$results[] = 'RAND()';
					else {
						switch ( $matches[1] ) {
							case 'ID':
								$matches[1] = "$wpdb->posts.ID";
								break;
							case 'description':
								$matches[1] = "$wpdb->posts.post_content";
								break;
							case 'caption':
								$matches[1] = "$wpdb->posts.post_excerpt";
								break;
							case 'slug':
								$matches[1] = "$wpdb->posts.post_name";
								break;
							case 'menu_order':
								$matches[1] = "$wpdb->posts.menu_order";
								break;
							case 'comment_count':
								$matches[1] = "$wpdb->posts.comment_count";
								break;
							case $meta_key:
							case 'meta_value':
								$matches[1] = "$wpdb->postmeta.meta_value";
								break;
							case 'meta_value_num':
								$matches[1] = "$wpdb->postmeta.meta_value+0";
								break;
							default:
								$matches[1] = "$wpdb->posts.post_" . $matches[1];
						} // switch $matches[1]
	
						$results[] = isset( $matches[2] ) ? $matches[1] . $matches[2] : $matches[1] . $order;
					} // not 'rand'
				} // valid column specification
			} // foreach $obmatches

			$orderby = implode( ', ', $results );
			if ( empty( $orderby ) )
				return false;
		} // else filter by allowed keys, etc.

		return $orderby;
	}

	/**
	 * Data selection parameters for the WP_Query in [mla_gallery]
	 *
	 * @since 1.30
	 *
	 * @var	array
	 */
	private static $data_selection_parameters = array(
			'order' => 'ASC', // or 'DESC' or 'RAND'
			'orderby' => 'menu_order,ID',
			'id' => NULL,
			'ids' => array(),
			'include' => array(),
			'exclude' => array(),
			// MLA extensions, from WP_Query
			// Force 'get_children' style query
			'post_parent' => NULL, // post/page ID or 'current' or 'all'
			// Author
			'author' => NULL,
			'author_name' => '',
			// Category
			'cat' => 0,
			'category_name' => '',
			'category__and' => array(),
			'category__in' => array(),
			'category__not_in' => array(),
			// Tag
			'tag' => '',
			'tag_id' => 0,
			'tag__and' => array(),
			'tag__in' => array(),
			'tag__not_in' => array(),
			'tag_slug__and' => array(),
			'tag_slug__in' => array(),
			// Taxonomy parameters are handled separately
			// {tax_slug} => 'term' | array ( 'term, 'term, ... )
			// 'tax_query' => ''
			'tax_operator' => '',
			// Post 
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => 'image',
			// Pagination - no default for most of these
			'nopaging' => true,
			'numberposts' => 0,
			'posts_per_page' => 0,
			'posts_per_archive_page' => 0,
			'paged' => NULL, // page number or 'current'
			'offset' => NULL,
			// TBD Time
			// Custom Field
			'meta_key' => '',
			'meta_value' => '',
			'meta_value_num' => NULL,
			'meta_compare' => '',
			'meta_query' => '',
			// Search
			's' => ''
		);

	/**
	 * Parses shortcode parameters and returns the gallery objects
	 *
	 * @since .50
	 *
	 * @param int Post ID of the parent
	 * @param array Attributes of the shortcode
	 *
	 * @return array List of attachments returned from WP_Query
	 */
	public static function mla_get_shortcode_attachments( $post_parent, $attr ) {
		/*
		 * Parameters passed to the where and orderby filter functions
		 */
		self::$query_parameters = array();

		/*
		 * Merge input arguments with defaults, then extract the query arguments.
		 */
		 
		if ( is_string( $attr ) )
			$attr = shortcode_parse_atts( $attr );
			
		$arguments = shortcode_atts( self::$data_selection_parameters, $attr );

		/*
		 * 'RAND' is not documented in the codex, but is present in the code.
		 */
		if ( 'RAND' == strtoupper( $arguments['order'] ) ) {
			$arguments['orderby'] = 'none';
			unset( $arguments['order'] );
		}

		if ( !empty( $arguments['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) )
				$arguments['orderby'] = 'post__in';

			$arguments['include'] = $arguments['ids'];
		}
		unset( $arguments['ids'] );
	
		/*
		 * Extract taxonomy arguments
		 */
		$taxonomies = get_taxonomies( array ( 'show_ui' => true ), 'names' ); // 'objects'
		$query_arguments = array();
		if ( ! empty( $attr ) ) {
			foreach ( $attr as $key => $value ) {
				if ( 'tax_query' == $key ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = $value;
					else {
						$tax_query = NULL;
						$value = self::_sanitize_query_specification( $value );
						$function = @create_function('', 'return ' . $value . ';' );
						if ( is_callable( $function ) )
							$tax_query = $function();

						if ( is_array( $tax_query ) )						
							$query_arguments[ $key ] = $tax_query;
						else
							return '<p>ERROR: invalid mla_gallery tax_query = ' . var_export( $value, true ) . '</p>';
					} // not array
				}  // tax_query
				elseif ( array_key_exists( $key, $taxonomies ) ) {
					$query_arguments[ $key ] = implode(',', array_filter( array_map( 'trim', explode( ',', $value ) ) ) );
					
					if ( in_array( strtoupper( $arguments['tax_operator'] ), array( 'IN', 'NOT IN', 'AND' ) ) ) {
						$query_arguments['tax_query'] =	array( array( 'taxonomy' => $key, 'field' => 'slug', 'terms' => explode( ',', $query_arguments[ $key ] ), 'operator' => strtoupper( $arguments['tax_operator'] ) ) );
						unset( $query_arguments[ $key ] );
					}
				} // array_key_exists
			} //foreach $attr
		} // ! empty
		unset( $arguments['tax_operator'] );
		
		/*
		 * $query_arguments has been initialized in the taxonomy code above.
		 */
		$use_children = empty( $query_arguments );
		foreach ($arguments as $key => $value ) {
			/*
			 * There are several "fallthru" cases in this switch statement that decide 
			 * whether or not to limit the query to children of a specific post.
			 */
			$children_ok = true;
			switch ( $key ) {
			case 'post_parent':
				switch ( strtolower( $value ) ) {
				case 'all':
					$value = NULL;
					$use_children = false;
					break;
				case 'any':
					self::$query_parameters['post_parent'] = 'any';
					$value = NULL;
					$use_children = false;
					break;
				case 'current':
					$value = $post_parent;
					break;
				case 'none':
					self::$query_parameters['post_parent'] = 'none';
					$value = NULL;
					$use_children = false;
					break;
				}
				// fallthru
			case 'id':
				if ( is_numeric( $value ) ) {
					$query_arguments[ $key ] = intval( $value );
					if ( ! $children_ok )
						$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'numberposts':
			case 'posts_per_page':
			case 'posts_per_archive_page':
				if ( is_numeric( $value ) ) {
					$value =  intval( $value );
					if ( ! empty( $value ) ) {
						$query_arguments[ $key ] = $value;
					}
				}
				unset( $arguments[ $key ] );
				break;
			case 'meta_value_num':
				$children_ok = false;
				// fallthru
			case 'offset':
				if ( is_numeric( $value ) ) {
					$query_arguments[ $key ] = intval( $value );
					if ( ! $children_ok )
						$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'paged':
				if ( 'current' == strtolower( $value ) )
					$query_arguments[ $key ] = (get_query_var('paged')) ? get_query_var('paged') : 1;
				elseif ( is_numeric( $value ) )
					$query_arguments[ $key ] = intval( $value );
				unset( $arguments[ $key ] );
				break;
			case 'author':
			case 'cat':
			case 'tag_id':
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = array_filter( $value );
					else
						$query_arguments[ $key ] = array_filter( array_map( 'intval', explode( ",", $value ) ) );
						
					if ( 1 == count( $query_arguments[ $key ] ) )
						$query_arguments[ $key ] = $query_arguments[ $key ][0];
					else
						$query_arguments[ $key ] = implode(',', $query_arguments[ $key ] );

					$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'category__and':
			case 'category__in':
			case 'category__not_in':
			case 'tag__and':
			case 'tag__in':
			case 'tag__not_in':
			case 'include':
				$children_ok = false;
				// fallthru
			case 'exclude':
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = array_filter( $value );
					else
						$query_arguments[ $key ] = array_filter( array_map( 'intval', explode( ",", $value ) ) );
						
					if ( ! $children_ok )
						$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'tag_slug__and':
			case 'tag_slug__in':
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = $value;
					else
						$query_arguments[ $key ] = array_filter( array_map( 'trim', explode( ",", $value ) ) );

					$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'nopaging': // boolean
				if ( ! empty( $value ) && ( 'false' != strtolower( $value ) ) )
					$query_arguments[ $key ] = true;
				unset( $arguments[ $key ] );
				break;
			case 'author_name':
			case 'category_name':
			case 'tag':
			case 'meta_key':
			case 'meta_value':
			case 'meta_compare':
			case 's':
				$children_ok = false;
				// fallthru
			case 'post_type':
			case 'post_status':
			case 'post_mime_type':
			case 'orderby':
				if ( ! empty( $value ) ) {
					$query_arguments[ $key ] = $value;
					
					if ( ! $children_ok )
						$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'order':
				if ( ! empty( $value ) ) {
					$value = strtoupper( $value );
					if ( in_array( $value, array( 'ASC', 'DESC' ) ) )
						$query_arguments[ $key ] = $value;
				}
				unset( $arguments[ $key ] );
				break;
			case 'meta_query':
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = $value;
					else {
						$meta_query = NULL;
						$value = self::_sanitize_query_specification( $value );
						$function = @create_function('', 'return ' . $value . ';' );
						if ( is_callable( $function ) )
							$meta_query = $function();
						
						if ( is_array( $meta_query ) )
							$query_arguments[ $key ] = $meta_query;
						else
							return '<p>ERROR: invalid mla_gallery meta_query = ' . var_export( $value, true ) . '</p>';
					} // not array

					$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			default:
				// ignore anything else
			} // switch $key
		} // foreach $arguments 

		/*
		 * Decide whether to use a "get_children" style query
		 */
		if ( $use_children && ! isset( $query_arguments['post_parent'] ) ) {
			if ( ! isset( $query_arguments['id'] ) )
				$query_arguments['post_parent'] = $post_parent;
			else				
				$query_arguments['post_parent'] = $query_arguments['id'];

			unset( $query_arguments['id'] );
		}

		if ( isset( $query_arguments['numberposts'] ) && ! isset( $query_arguments['posts_per_page'] )) {
			$query_arguments['posts_per_page'] = $query_arguments['numberposts'];
		}
		unset( $query_arguments['numberposts'] );

		if ( isset( $query_arguments['posts_per_page'] ) || isset( $query_arguments['posts_per_archive_page'] ) ||
			isset( $query_arguments['paged'] ) || isset( $query_arguments['offset'] ) ) {
			unset( $query_arguments['nopaging'] );
		}

		if ( isset( $query_arguments['post_mime_type'] ) && ('all' == strtolower( $query_arguments['post_mime_type'] ) ) )
			unset( $query_arguments['post_mime_type'] );

		if ( ! empty($query_arguments['include']) ) {
			$incposts = wp_parse_id_list( $query_arguments['include'] );
			$query_arguments['posts_per_page'] = count($incposts);  // only the number of posts included
			$query_arguments['post__in'] = $incposts;
		} elseif ( ! empty($query_arguments['exclude']) )
			$query_arguments['post__not_in'] = wp_parse_id_list( $query_arguments['exclude'] );
	
		$query_arguments['ignore_sticky_posts'] = true;
		$query_arguments['no_found_rows'] = true;
	
		/*
		 * We will always handle "orderby" in our filter
		 */ 
		self::$query_parameters['orderby'] = self::_validate_sql_orderby( $query_arguments );
		if ( false === self::$query_parameters['orderby'] )
			unset( self::$query_parameters['orderby'] );
			
		unset( $query_arguments['orderby'] );
		unset( $query_arguments['order'] );
	
		if ( self::$mla_debug ) {
			add_filter( 'posts_clauses', 'MLAShortcodes::mla_shortcode_query_posts_clauses_filter', 0x7FFFFFFF, 1 );
			add_filter( 'posts_clauses_request', 'MLAShortcodes::mla_shortcode_query_posts_clauses_request_filter', 0x7FFFFFFF, 1 );
		}
		
		add_filter( 'posts_orderby', 'MLAShortcodes::mla_shortcode_query_posts_orderby_filter', 0x7FFFFFFF, 1 );
		add_filter( 'posts_where', 'MLAShortcodes::mla_shortcode_query_posts_where_filter', 0x7FFFFFFF, 1 );

		if ( self::$mla_debug ) {
			global $wp_filter;
			self::$mla_debug_messages .= '<p><strong>mla_debug $wp_filter[posts_where]</strong> = ' . var_export( $wp_filter['posts_where'], true ) . '</p>';
			self::$mla_debug_messages .= '<p><strong>mla_debug $wp_filter[posts_orderby]</strong> = ' . var_export( $wp_filter['posts_orderby'], true ) . '</p>';
		}
		
		$get_posts = new WP_Query;
		$attachments = $get_posts->query($query_arguments);
		remove_filter( 'posts_where', 'MLAShortcodes::mla_shortcode_query_posts_where_filter', 0x7FFFFFFF, 1 );
		remove_filter( 'posts_orderby', 'MLAShortcodes::mla_shortcode_query_posts_orderby_filter', 0x7FFFFFFF, 1 );
		
		if ( self::$mla_debug ) {
			remove_filter( 'posts_clauses', 'MLAShortcodes::mla_shortcode_query_posts_clauses_filter', 0x7FFFFFFF, 1 );
			remove_filter( 'posts_clauses_request', 'MLAShortcodes::mla_shortcode_query_posts_clauses_request_filter', 0x7FFFFFFF, 1 );

			self::$mla_debug_messages .= '<p><strong>mla_debug query</strong> = ' . var_export( $query_arguments, true ) . '</p>';
			self::$mla_debug_messages .= '<p><strong>mla_debug request</strong> = ' . var_export( $get_posts->request, true ) . '</p>';
			self::$mla_debug_messages .= '<p><strong>mla_debug query_vars</strong> = ' . var_export( $get_posts->query_vars, true ) . '</p>';
			self::$mla_debug_messages .= '<p><strong>mla_debug post_count</strong> = ' . var_export( $get_posts->post_count, true ) . '</p>';
		}
		
		return $attachments;
	}

	/**
	 * Filters the WHERE clause for shortcode queries
	 * 
	 * Captures debug information. Adds whitespace to the post_type = 'attachment'
	 * phrase to circumvent subsequent Role Scoper modification of the clause.
	 * Handles post_parent "any" and "none" cases.
	 * Defined as public because it's a filter.
	 *
	 * @since 0.70
	 *
	 * @param	string	query clause before modification
	 *
	 * @return	string	query clause after modification
	 */
	public static function mla_shortcode_query_posts_where_filter( $where_clause ) {
		global $table_prefix;

		if ( self::$mla_debug ) {
			$old_clause = $where_clause;
			self::$mla_debug_messages .= '<p><strong>mla_debug WHERE filter</strong> = ' . var_export( $where_clause, true ) . '</p>';
		}
		
		if ( strpos( $where_clause, "post_type = 'attachment'" ) ) {
			$where_clause = str_replace( "post_type = 'attachment'", "post_type  =  'attachment'", $where_clause );
		}

		if ( isset( self::$query_parameters['post_parent'] ) ) {
			switch ( self::$query_parameters['post_parent'] ) {
			case 'any':
				$where_clause .= " AND {$table_prefix}posts.post_parent > 0";
				break;
			case 'none':
				$where_clause .= " AND {$table_prefix}posts.post_parent < 1";
				break;
			}
		}

		if ( self::$mla_debug && ( $old_clause != $where_clause ) ) 
			self::$mla_debug_messages .= '<p><strong>mla_debug modified WHERE filter</strong> = ' . var_export( $where_clause, true ) . '</p>';

		return $where_clause;
	}

	/**
	 * Filters the ORDERBY clause for shortcode queries
	 * 
	 * This is an enhanced version of the code found in wp-includes/query.php, function get_posts.
	 * Defined as public because it's a filter.
	 *
	 * @since 1.20
	 *
	 * @param	string	query clause before modification
	 *
	 * @return	string	query clause after modification
	 */
	public static function mla_shortcode_query_posts_orderby_filter( $orderby_clause ) {
		global $wpdb;

		if ( self::$mla_debug ) {
			self::$mla_debug_messages .= '<p><strong>mla_debug ORDER BY filter, incoming</strong> = ' . var_export( $orderby_clause, true ) . '<br>Replacement ORDER BY clause = ' . var_export( self::$query_parameters['orderby'], true ) . '</p>';
		}

		if ( isset( self::$query_parameters['orderby'] ) )
			return self::$query_parameters['orderby'];
		else
			return $orderby_clause;
	}

	/**
	 * Filters all clauses for shortcode queries, pre caching plugins
	 * 
	 * This is for debug purposes only.
	 * Defined as public because it's a filter.
	 *
	 * @since 1.30
	 *
	 * @param	array	query clauses before modification
	 *
	 * @return	array	query clauses after modification (none)
	 */
	public static function mla_shortcode_query_posts_clauses_filter( $pieces ) {
		self::$mla_debug_messages .= '<p><strong>mla_debug posts_clauses filter</strong> = ' . var_export( $pieces, true ) . '</p>';

		return $pieces;
	}

	/**
	 * Filters all clauses for shortcode queries, post caching plugins
	 * 
	 * This is for debug purposes only.
	 * Defined as public because it's a filter.
	 *
	 * @since 1.30
	 *
	 * @param	array	query clauses before modification
	 *
	 * @return	array	query clauses after modification (none)
	 */
	public static function mla_shortcode_query_posts_clauses_request_filter( $pieces ) {
		self::$mla_debug_messages .= '<p><strong>mla_debug posts_clauses_request filter</strong> = ' . var_export( $pieces, true ) . '</p>';

		return $pieces;
	}
} // Class MLAShortcodes
?>