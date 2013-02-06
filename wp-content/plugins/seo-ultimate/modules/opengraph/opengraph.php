<?php
/**
 * Open Graph Integrator Module
 * 
 * @since 7.3
 */

if (class_exists('SU_Module')) {

class SU_OpenGraph extends SU_Module {
	
	var $namespaces_declared = false;
	var $jlsuggest_box_post_id = false;
	
	function get_module_title() { return __('Open Graph Integrator', 'seo-ultimate'); }
	function get_menu_title() { return __('Open Graph', 'seo-ultimate'); }
	
	function get_default_settings() {
		return array(
			  'default_post_og_type' => 'article'
			, 'default_page_og_type' => 'article'
			, 'default_post_twitter_card' => 'summary'
			, 'default_page_twitter_card' => 'summary'
			, 'default_attachment_twitter_card' => 'photo'
			, 'enable_og_article_author' => true
		);
	}
	
	function init() {
		add_filter('language_attributes', array(&$this, 'html_tag_attrs'), 1000);
		add_action('su_head', array(&$this, 'head_tag_output'));
		add_filter('su_get_setting-opengraph-twitter_site_handle', array(&$this, 'sanitize_twitter_handle'));
		add_filter('user_contactmethods', array(&$this, 'add_twitter_field'));
	}
	
	function html_tag_attrs($attrs) {
		$this->namespaces_declared = true;
		$namespace_urls = $this->get_namespace_urls();
		
		$doctype = $this->get_setting('doctype', '');
		switch ($doctype) {
			case 'xhtml':
				foreach ($namespace_urls as $namespace => $url) {
					$namespace = su_esc_attr($namespace);
					$url = su_esc_attr($url);
					$attrs .= " xmlns:$namespace=\"$url\"";
				}
				break;
			case 'html5':
			default:
				$attrs .= ' prefix="';
				$whitespace = '';
				foreach ($namespace_urls as $namespace => $url) {
					$namespace = su_esc_attr($namespace);
					$url = su_esc_attr($url);
					$attrs .= "$whitespace$namespace: $url";
					$whitespace = ' ';
				}
				$attrs .= '"';
				break;
		}
		
		return $attrs;
	}
	
	function get_namespace_urls() {
		return array(
			  'og' => 'http://ogp.me/ns#'
			, 'fb' => 'http://ogp.me/ns/fb#'
		);
	}
	
	function head_tag_output() {
		global $wp_query;
		
		$tags = $twitter_tags = array();
		
		if (is_home()) {
			
			//Type
			$tags['og:type'] = 'blog';
			
			//Twitter Type
			$twitter_tags['twitter:card'] = 'summary';
			
			//Title
			if (!($tags['og:title'] = $this->get_setting('home_og_title')))
				$tags['og:title'] = get_bloginfo('name');
			
			//Description
			if (!($tags['og:description'] = $this->get_setting('home_og_description')))
				$tags['og:description'] = get_bloginfo('description');
			
			//URL
			$tags['og:url'] = suwp::get_blog_home_url();
			
			//Image
			$tags['og:image'] = $this->get_setting('home_og_image');
			
		} elseif (is_singular()) {
			
			$post = $wp_query->get_queried_object();
			
			if (is_object($post)) {
				//Type
				if (!($tags['og:type'] = $this->get_postmeta('og_type')))
					$tags['og:type'] = $this->get_setting("default_{$post->post_type}_og_type");
				
				//Twitter Type
				if (!($twitter_tags['twitter:card'] = $this->get_postmeta('twitter_card')))
					$twitter_tags['twitter:card'] = $this->get_setting("default_{$post->post_type}_twitter_card");
				
				//Title
				if (!($tags['og:title'] = $this->get_postmeta('og_title')))
					$tags['og:title'] = strip_tags( apply_filters( 'single_post_title', $post->post_title ) );
				
				//Description
				if (!($tags['og:description'] = $this->get_postmeta('og_description')))
					if ($this->plugin->call_module_func('meta-descriptions', 'get_meta_desc', $meta_desc, false) && $meta_desc)
						$tags['og:description'] = $meta_desc;
				
				//URL
				$tags['og:url'] = get_permalink($post->ID);
				
				//Image
				$tags['og:image'] = $this->jlsuggest_value_to_url($this->get_postmeta('og_image'), true);
				if (!$tags['og:image']) {
					if ('attachment' == $post->post_type)
						$tags['og:image'] = wp_get_attachment_url();
					elseif (current_theme_supports('post-thumbnails') && $thumbnail_id = get_post_thumbnail_id($post->ID))
						$tags['og:image'] = wp_get_attachment_url($thumbnail_id);
				}
				
				//Additional fields
				switch ($tags['og:type']) {
					case 'article':
						
						$tags['article:published_time'] = get_the_date('Y-m-d');
						$tags['article:modified_time'] = get_the_modified_date('Y-m-d');
						
						//Authorship generally doesn't apply to pages
						if (!is_page() && $this->get_setting('enable_og_article_author', true))
							$tags['article:author'] = get_author_posts_url($post->post_author);
						
						$single_category = (count(get_the_category()) == 1);
						
						$taxonomy_names = suwp::get_taxonomy_names();
						foreach ($taxonomy_names as $taxonomy_name) {
							if ($terms = get_the_terms(get_the_ID(), $taxonomy_name)) {
								
								if ($single_category && 'category' == $taxonomy_name)
									$meta_property = 'article:section';
								else
									$meta_property = 'article:tag';
								
								foreach ($terms as $term) {
									$tags[$meta_property][] = $term->name;
								}
							}
						}
						
						break;
				}
				
				//Author's Twitter Handle
				$handle = get_user_meta($post->post_author, 'twitter', true);
				$handle = $this->sanitize_twitter_handle($handle);
				$twitter_tags['twitter:creator'] = $handle;
			}
		} elseif (is_author()) {
			
			$author = $wp_query->get_queried_object();
			
			if (is_object($author)) {
				//Type
				$tags['og:type'] = 'profile';
				
				//Title
				$tags['og:title'] = $author->display_name;
				
				//Description
				$tags['og:title'] = get_the_author_meta('description', $author->ID);
				
				//Image
				$tags['og:image'] = false;
				
				//URL
				$tags['og:url'] = get_author_posts_url($author->ID, $author->user_nicename);
				
				//First Name
				$tags['profile:first_name'] = get_the_author_meta('first_name', $author->ID);
				
				//Last Name
				$tags['profile:last_name'] = get_the_author_meta('last_name', $author->ID);
				
				//Username
				$tags['profile:username'] = $author->user_login;
				
				//Twitter Handle
				$handle = get_user_meta($author->ID, 'twitter', true);
				$handle = $this->sanitize_twitter_handle($handle);
				$twitter_tags['twitter:creator'] = $handle;
			}
		} else
			return;
		
		if ($tags['og:type'] == 'none')
			$tags['og:type'] = '';
		
		if ((!isset($tags['og:image']) || !$tags['og:image']) && $tags['og:image'] !== false)
			$tags['og:image'] = $this->jlsuggest_value_to_url($this->get_setting('default_og_image'), true);
		
		//Site Name
		if (!($tags['og:site_name'] = $this->get_setting('og_site_name')))
			$tags['og:site_name'] = get_bloginfo('name');
		
		//FB App ID
		$tags['fb:app_id'] = $this->get_setting('default_fb_app_id');
		
		//Twitter Site Handle
		$twitter_tags['twitter:site'] = $this->get_setting('twitter_site_handle');
		
		//Output meta tags
		$namespace_urls = $this->namespaces_declared ? array() : $this->get_namespace_urls();
		$doctype = $this->get_setting('doctype', '');
		
		switch ($doctype) {
			case 'xhtml':
				$output_formats = array('<meta%3$s name="%1$s" content="%2$s" />' => array_merge($tags, $twitter_tags));
				break;
			case 'html5':
				$output_formats = array('<meta%3$s property="%1$s" content="%2$s">' => array_merge($tags, $twitter_tags));
				break;
			default:
				$output_formats = array(
					  '<meta%3$s property="%1$s" content="%2$s" />' => $tags
					, '<meta%3$s name="%1$s" content="%2$s" />' => $twitter_tags
				);
				break;
		}
		
		foreach ($output_formats as $html_format => $format_tags) {
			foreach ($format_tags as $property => $values) {
				foreach ((array)$values as $value) {
					$property = su_esc_attr($property);
					$value  = su_esc_attr($value);
					if (strlen(trim($property)) && strlen(trim($value))) {
						
						$namespace_attr = '';
						$namespace = sustr::upto($property, ':');
						if (!empty($namespace_urls[$namespace])) {
							$a_namespace = su_esc_attr($namespace);
							$a_namespace_url = su_esc_attr($namespace_urls[$namespace]);
						
							switch ($doctype) {
								case 'xhtml':
									$namespace_attr = " xmlns:$a_namespace=\"$a_namespace_url\"";
									break;
								case 'html5':
								default:
									$namespace_attr = " prefix=\"$a_namespace: $a_namespace_url\"";
									break;
							}
						}
						
						echo "\t";
						printf($html_format, $property, $value, $namespace_attr);
						echo "\n";
					}
				}
			}
		}
	}
	
	function admin_page_init() {
		$this->jlsuggest_init();
	}
	
	function editor_init() {
		$this->jlsuggest_init();
	}
	
	function get_admin_page_tabs() {
		
		$postmeta_edit_tabs = $this->get_postmeta_edit_tabs(array(
			  array(
				  'type' => 'dropdown'
				, 'options' => array_merge(array('' => __('Use default', 'seo-ultimate')), $this->get_type_options())
				, 'name' => 'og_type'
				, 'label' => __('Type', 'seo-ultimate')
				)
			, array(
				  'type' => 'textbox'
				, 'name' => 'og_title'
				, 'label' => __('Title', 'seo-ultimate')
				)
			, array(
				  'type' => 'textbox'
				, 'name' => 'og_description'
				, 'label' => __('Description', 'seo-ultimate')
				)
			, array(
				  'type' => 'jlsuggest'
				, 'name' => 'og_image'
				, 'label' => __('Image', 'seo-ultimate')
				, 'options' => array(
					  'params' => 'types=posttype_attachment&post_mime_type=image/*'
				))
		));
		
		//Remove the Image boxes from the Media tab
		//(it's obvious what the og:image of an attachment should be...)
		unset($postmeta_edit_tabs['attachment']['callback'][5][3]);
		
		return array_merge(
			  array(
				  array('title' => __('Sitewide Values', 'seo-ultimate'), 'id' => 'su-sitewide-values', 'callback' => 'global_tab')
				, array('title' => __('Default Values', 'seo-ultimate'), 'id' => 'su-default-values', 'callback' => 'defaults_tab')
				, array('title' => __('Settings', 'seo-ultimate'), 'id' => 'su-settings', 'callback' => 'settings_tab')
				, array('title' => __('Blog Homepage', 'seo-ultimate'), 'id' => 'su-homepage', 'callback' => 'home_tab')
				)
			, $postmeta_edit_tabs
		);
	}
	
	function global_tab() {
		$this->admin_form_table_start();
		$this->textbox('og_site_name', __('Site Name', 'seo-ultimate'), false, false, array(), array('placeholder' => get_bloginfo('name')));
		$this->textbox('default_fb_app_id', __('Facebook App ID', 'seo-ultimate'));
		$this->textbox('twitter_site_handle', __('This Site&#8217;s Twitter Handle', 'seo-ultimate'));
		$this->admin_form_table_end();
	}
	
	function defaults_tab() {
		$posttypes = get_post_types(array('public' => true), 'objects');
		
		$this->admin_subheader(__('Default Types', 'seo-ultimate'));
		$this->admin_wftable_start(array(
			  'posttype' => __('Post Type', 'seo-ultimate')
			, 'og' => __('Open Graph Type', 'seo-ultimate')
			, 'twitter' => __('Twitter Type', 'seo-ultimate')
		));
		foreach ($posttypes as $posttype) {
			echo "<tr valign='middle'>\n";
			echo "\t<th class='su-opengraph-posttype' scope='row'>" . esc_html($posttype->labels->name) . "</th>\n";
			echo "\t<td class='su-opengraph-og'>";
			$this->dropdown("default_{$posttype->name}_og_type", $this->get_type_options(), false, '%s', array('in_table' => false));
			echo "</td>\n";
			echo "\t<td class='su-opengraph-twitter'>";
			$this->dropdown("default_{$posttype->name}_twitter_card", $this->get_twitter_type_options(), false, '%s', array('in_table' => false));
			echo "</td>\n";
			echo "</tr>\n";
		}
		$this->admin_wftable_end();
		
		$this->admin_subheader(__('Default Image', 'seo-ultimate'));
		$this->admin_form_table_start();
		
		$this->textblock(__('In the box below, you can specify an image URL or an image from your media library to use as a default image in the event that there is no image otherwise specified for a given webpage on your site.', 'seo-ultimate'));
		
		$this->jlsuggest_box('default_og_image', __('Default Image', 'seo-ultimate'), 'types=posttype_attachment&post_mime_type=image/*');
		
		$this->admin_form_table_end();
	}
	
	function settings_tab() {
		$this->admin_form_table_start();
		$this->checkbox('enable_og_article_author', __('Include author data for posts', 'seo-ultimate'), __('Open Graph Data', 'seo-ultimate'));
		$this->radiobuttons('doctype', array(
			  '' => __('Use the non-validating code prescribed by Open Graph and Twitter', 'seo-ultimate')
			, 'xhtml' => __('Alter the code to validate as XHTML', 'seo-ultimate')
			, 'html5' => __('Alter the code to validate as HTML5', 'seo-ultimate')
		), __('HTML Validation', 'seo-ultimate'));
		$this->admin_form_table_end();
	}
	
	function home_tab() {
		$this->admin_form_table_start();
		$this->textbox('home_og_title', __('Blog Homepage Title', 'seo-ultimate'), false, false, array(), array('placeholder' => get_bloginfo('name')));
		$this->textbox('home_og_description', __('Blog Homepage Description', 'seo-ultimate'), false, false, array(), array('placeholder' => get_bloginfo('description')));
		$this->jlsuggest_box('home_og_image', __('Blog Homepage Image', 'seo-ultimate'), 'types=posttype_attachment&post_mime_type=image/*');
		$this->admin_form_table_end();
	}
	
	function postmeta_fields($fields) {
		
		$fields['opengraph'][10]['og_title'] = $this->get_postmeta_textbox('og_title', __('Title:', 'seo-ultimate'));
		$fields['opengraph'][20]['og_description'] = $this->get_postmeta_textarea('og_description', __('Description:', 'seo-ultimate'));
		$fields['opengraph'][30]['og_image'] = $this->get_postmeta_jlsuggest_box('og_image', __('Image:', 'seo-ultimate'), 'types=posttype_attachment&post_mime_type=image/*');
		$fields['opengraph'][40]['og_type'] = $this->get_postmeta_dropdown('og_type', array_merge(array('' => __('Use default', 'seo-ultimate')), $this->get_type_options()), __('Open Graph Type:', 'seo-ultimate'));
		$fields['opengraph'][50]['twitter_card'] = $this->get_postmeta_dropdown('twitter_card', array_merge(array('' => __('Use default', 'seo-ultimate')), $this->get_twitter_type_options()), __('Twitter Type:', 'seo-ultimate'));
		
		return $fields;
	}
	
	function get_postmeta_jlsuggest_boxes($jls_boxes) {
		$this->jlsuggest_box_post_id = suwp::get_post_id();
		return parent::get_postmeta_jlsuggest_boxes($jls_boxes);
	}
	
	function get_input_element($type, $name, $value=null, $extra=false, $inputid=true) {
		
		$name_parts = explode('_', $name);
		if (isset($name_parts[1]) && is_numeric($post_id = $name_parts[1]))
			$this->jlsuggest_box_post_id = $post_id;
		else
			$this->jlsuggest_box_post_id = false;
		
		return parent::get_input_element($type, $name, $value, $extra, $inputid);
	}
	
	function get_jlsuggest_box($name, $value, $params='', $placeholder='') {
		
		if (empty($value) && $this->jlsuggest_box_post_id && current_theme_supports('post-thumbnails') && $thumbnail_id = get_post_thumbnail_id($this->jlsuggest_box_post_id)) {
			$selected_post = get_post($thumbnail_id);
			$placeholder = sprintf(__('Featured Image: %s', 'seo-ultimate'), $selected_post->post_title);
		}
		
		return parent::get_jlsuggest_box($name, $value, $params, $placeholder);
	}
	
	function get_type_options() {
		return array(
			  'none' => __('None', 'seo-ultimate')
			, __('Internet', 'seo-ultimate') => array(
				  'article' => __('Article', 'seo-ultimate')
				, 'blog' => __('Blog', 'seo-ultimate')
				, 'profile' => __('Profile', 'seo-ultimate')
				, 'website' => __('Website', 'seo-ultimate')
			),__('Products', 'seo-ultimate') => array(
				  'book' => __('Book', 'seo-ultimate')
			),__('Music', 'seo-ultimate') => array(
				  'music.album' => __('Album', 'seo-ultimate')
				, 'music.playlist' => __('Playlist', 'seo-ultimate')
				, 'music.radio_station' => __('Radio Station', 'seo-ultimate')
				, 'music.song' => __('Song', 'seo-ultimate')
			),__('Videos', 'seo-ultimate') => array(
				  'video.movie' => __('Movie', 'seo-ultimate')
				, 'video.episode' => __('TV Episode', 'seo-ultimate')
				, 'video.tv_show' => __('TV Show', 'seo-ultimate')
				, 'video.other' => __('Video', 'seo-ultimate')
			)
		);
	}
	
	function get_twitter_type_options() {
		return array(
			  'summary' => __('Regular', 'seo-ultimate')
			, 'photo' => __('Photo', 'seo-ultimate')
		);
	}
	
	function sanitize_twitter_handle($value) {
		if (strpos($value, '/') === false) {
			$handle = ltrim($value, '@');
		} else {
			$url_parts = explode('/', $value);
			$handle = array_pop($url_parts);
		}
		
		$handle = sustr::preg_filter('a-zA-Z0-9_', $handle);
		$handle = trim($handle);
		
		if ($handle)
			$handle = "@$handle";
		
		return $handle;
	}
	
	function add_twitter_field( $contactmethods ) {
		$contactmethods['twitter'] = __('Twitter Handle', 'seo-ultimate');
		return $contactmethods;
	}
	
	function add_help_tabs($screen) {
		
		$screen->add_help_tab(array(
			  'id' => 'su-opengraph-overview'
			, 'title' => __('Overview', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>What it does:</strong> Open Graph Integrator makes it easy for you to convey information about your site to social networks like Facebook, Twitter, and Google+.</li>
	<li><strong>Why it helps:</strong> By providing this Open Graph data, you can customize how these social networks will present your site when people share it with their followers.</li>
	<li><strong>How to use it:</strong> The &#8220;Sitewide Values&#8221; tab lets you specify data that applies to your entire site. The &#8220;Default Values&#8221; tab lets you specify default data for your posts, pages, etc. The bulk editor tabs let you override those defaults on individual posts and pages. If the authors on your site fill in the &#8220;Twitter Handle&#8221; field which Open Graph Integrator adds to the <a href='profile.php'>profile editor</a>, Open Graph Integrator will communicate that information to Twitter as well.</li>
</ul>
", 'seo-ultimate')));
		
	}
}

}
?>