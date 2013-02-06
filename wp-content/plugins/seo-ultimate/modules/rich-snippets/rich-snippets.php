<?php
/**
 * Rich Snippet Creator Module
 * 
 * @since 3.0
 */

if (class_exists('SU_Module')) {

class SU_RichSnippets extends SU_Module {
	
	var $apply_subproperty_markup_args = array();
	
	function get_module_title() { return __('Rich Snippet Creator', 'seo-ultimate'); }
	
	function get_parent_module() { return 'misc'; }
	function get_settings_key() { return 'rich-snippets'; }
	
	function init() {
		add_filter('the_content', array(&$this, 'apply_markup'));
	}
	
	function admin_page_contents() {
		$this->child_admin_form_start();
		$this->textblock(__('Rich Snippet Creator adds a &#8220;Search Result Type&#8221; dropdown to the WordPress content editor screen. To add rich snippet data to a post, select &#8220;Review&#8221; or &#8220;Place&#8221; from a post&#8217;s  &#8220;Search Result Type&#8221; dropdown and fill in the fields that appear.', 'seo-ultimate'));
		$this->child_admin_form_end();
	}
	
	function get_supported_snippet_formats() {
		
		return array(
			  'so' => array(
				  'label' => __('Schema.org Microdata', 'seo-ultimate')
				, 'item_tags_template' => '<div itemscope itemtype="http://schema.org/%1$s">%2$s</div>'
				, 'property_tags_template' => '<span itemprop="%1$s">%2$s</span>'
				, 'hidden_property_tags_template' => '<meta itemprop="%1$s" content="%2$s" />'
				)
		);
	}
	
	function get_supported_snippet_types() {

		return array(
			//REVIEW
			  'review' => array(
				  'label' => __('Review', 'seo-ultimate')
				, 'tags' => 'Review'
				, 'content_tags' => '<div itemprop="reviewBody">%s</div>'
				, 'properties' => array(
					  'item' => array(
						  'label' => __('Name of Reviewed Item', 'seo-ultimate')
						, 'tags' => 'itemReviewed'
					),'rating' => array(
						  'label' => __('Star Rating', 'seo-ultimate')
						, 'value_format' => array('%s star', '%s stars', '%s-star', '%s-stars')
						, 'tags' =>   '<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">'
									. '<meta itemprop="worstRating" content="0" />'
									. '<span itemprop="ratingValue">%s</span>'
									. '<meta itemprop="bestRating" content="5" />'
									. '</span>'
						, 'hidden_tags'=> '<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">'
										. '<meta itemprop="worstRating" content="0" />'
										. '<meta itemprop="ratingValue" content="%s" />'
										. '<meta itemprop="bestRating" content="5" />'
										. '</span>'
					),'image' => array(
						  'label' => __('Image of Reviewed Item', 'seo-ultimate')
						, 'tags' => '<a itemprop="image" href="%1$s">%1$s</a>'
						, 'hidden_tags'=> '<link itemprop="image" href="%s" />'
						, 'jlsuggest' => true
					),'reviewer' => array(
						  'label' => __('Review Author', 'seo-ultimate')
						, 'editable' => false
						, 'value_function' => 'get_the_author'
						, 'tags' => 'author'
					),'date_reviewed' => array(
						  'label' => __('Date Reviewed', 'seo-ultimate')
						, 'editable' => false
						, 'value_function' => array('get_the_time', 'Y-m-d')
						, 'tags' => '<time itemprop="datePublished">%s</time>'
						, 'hidden_tags' => '<meta itemprop="datePublished" content="%s" />'
					)
				)
			),'place' => array(
				  'label' => __('Place', 'seo-ultimate')
				, 'tags' => 'Place'
				, 'properties' => array(
					  'address' => array(
						  'label' => __('Address', 'seo-ultimate')
						, 'tags' => '<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">%s</span>'
						, 'properties' => array(
							  'street' => array(
								  'label' => __('Street Address', 'seo-ultimate')
								, 'tags' => 'streetAddress'
							),'po_box' => array(
								  'label' => __('PO Box', 'seo-ultimate')
								, 'tags' => 'postOfficeBoxNumber'
							),'city' => array(
								  'label' => __('City', 'seo-ultimate')
								, 'tags' => 'addressLocality'
							),'state' => array(
								  'label' => __('City', 'seo-ultimate')
								, 'tags' => 'addressRegion'
							),'country' => array(
								  'label' => __('Country', 'seo-ultimate')
								, 'tags' => 'addressCountry'
							),'postal_code' => array(
								  'label' => __('Postal Code', 'seo-ultimate')
								, 'tags' => 'postalCode'
							)
						)
					),'fax_number' => array(
						  'label' => __('Fax Number', 'seo-ultimate')
						, 'tags' => 'faxNumber'
					),'map_url' => array(
						  'label' => __('Map URL', 'seo-ultimate')
						, 'tags' => '<a itemprop="map" href="%1$s">%1$s</a>'
						, 'hidden_tags' => '<link itemprop="map" href="%s" />'
						, 'jlsuggest' => true
					),'photo' => array(
						  'label' => __('Photo', 'seo-ultimate')
						, 'tags'=>'<span itemprop="photo" itemscope itemtype="http://schema.org/Photograph">'
								. '<a itemprop="url" href="%1$s">%1$s</a>'
								. '</span>'
						, 'hidden_tags'=> '<span itemprop="photo" itemscope itemtype="http://schema.org/Photograph">'
										. '<link itemprop="url" href="%s" />'
										. '</span>'
						, 'jlsuggest' => true
					),'tel_number' => array(
						  'label' => __('Phone Number', 'seo-ultimate')
						, 'tags' => 'telephone'
					)
				)
			)
		);
	}
	
	function add_tags($content, $tags, $template, $escape=true) {
		if ($escape) $content = su_esc_attr($content);
		$tags = array_reverse((array)$tags);
		foreach ($tags as $tag) {
			if (sustr::startswith($tag, '<'))
				$content = sprintf($tag, $content);
			else
				$content = sprintf($template, $tag, $content);
		}
		return $content;
	}
	
	function apply_markup($content) {
		
		//Single items only
		if (!is_singular() || !in_the_loop()) return $content;
		
		//Get the current type
		$type = $this->get_postmeta('rich_snippet_type');
		if (!strlen($type) || $type == 'none') return $content;
		
		//Get the current format
		$format = 'so';
		
		//Get tag templates for the current format
		$formats = $this->get_supported_snippet_formats();
		
		//Get data for the current type
		$types = $this->get_supported_snippet_types();
		$type_data = $types[$type];
		
		//Cycle through the current type's properties
		$append = '';
		$num_properties = 0;
		$supervalue_regex = '';
		foreach ($type_data['properties'] as $property => $property_data) {
			
			//Get the property tags
			$tag = is_array($property_data['tags']) ?
							$property_data['tags'][$format] :
							$property_data['tags'];
			
			if (isset($property_data['hidden_tags'])) {
				$hidden_tag = is_array($property_data['hidden_tags']) ?
								$property_data['hidden_tags'][$format] :
								$property_data['hidden_tags'];
			} else
				$hidden_tag = $tag;
			
			
			if (isset($property_data['properties']) && is_array($property_data['properties']) && count($property_data['properties'])) {
				
				$subproperty_regex_pieces = array();
				$subproperty_hidden_markedup_values = array();
				foreach ($property_data['properties'] as $subproperty => $subproperty_data) {
					
					//Get the subproperty tags
					$subproperty_tag = is_array($subproperty_data['tags']) ?
										$subproperty_data['tags'][$format] :
										$subproperty_data['tags'];
					
					if (isset($subproperty_data['hidden_tags'])) {
						$subproperty_hidden_tag = is_array($subproperty_data['hidden_tags']) ?
													$subproperty_data['hidden_tags'][$format] :
													$subproperty_data['hidden_tags'];
					} else
						$subproperty_hidden_tag = $subproperty_tag;
					
					$subproperty_value = strval($this->get_postmeta("rich_snippet_{$type}_{$property}_{$subproperty}"));
					if ($subproperty_value) {
						$subproperty_hidden_markedup_values[] = $this->add_tags($subproperty_value, $subproperty_hidden_tag, $formats[$format]['hidden_property_tags_template']);
						$subproperty_regex_pieces[] = sustr::preg_escape($subproperty_value);
					}
				}
				/*$supervalue_regex = implode('(<br ?/?>|\s|,)*?', $subproperty_regex_pieces);*/
				$supervalue_regex = implode('|', $subproperty_regex_pieces);
				$supervalue_regex = "($supervalue_regex)";
				$supervalue_regex = array_fill(0, count($subproperty_regex_pieces), $supervalue_regex);
				$supervalue_regex = implode('(<br ?/?>|\s|.){0,20}', $supervalue_regex);
				
				$this->apply_subproperty_markup_args = array(
					  'format' => $format
					, 'type' => $type
					, 'property' => $property
					, 'property_tag' => $tag
					, 'property_tag_template' => $formats[$format]['property_tags_template']
					, 'subproperties' => $property_data['properties']
				);
				$count = 0;
				$content = preg_replace_callback("%({$supervalue_regex})%", array(&$this, 'apply_subproperty_markup'), $content, 1, $count);
				
				if ($count == 0) {
					if (count($subproperty_hidden_markedup_values)) {
						$append .= $this->add_tags(implode($subproperty_hidden_markedup_values), $tag, $formats[$format]['property_tags_template'], false);
						$num_properties++;
					}
				} else {
					$num_properties++;
				}
				
			} else {
				
				//Get the current value for this property
				$value = strval($this->get_postmeta("rich_snippet_{$type}_{$property}"));
				
				if (strlen($value)) {
					
					if (sustr::startswith($value, 'obj_') && isset($property_data['jlsuggest']) && $property_data['jlsuggest'])
						$value = $this->jlsuggest_value_to_url($value, true);
					
				} else {
					
					//If a value is not set, look for a value-generating function
					if (isset($property_data['value_function'])) {
						$valfunc = (array)$property_data['value_function'];
						if (is_callable($valfunc[0])) {
							$valfunc_args = isset($valfunc[1]) ? (array)$valfunc[1] : array();
							$value = call_user_func_array($valfunc[0], $valfunc_args);
						}
					}
				}
				
				//If still no value, skip this property
				if (!strlen($value)) continue;
				
				//Add property tags to the value
				$markedup_value = $this->add_tags($value, $tag, $formats[$format]['property_tags_template']);
				$hidden_markedup_value = $this->add_tags($value, $hidden_tag, $formats[$format]['hidden_property_tags_template']);
				
				//Apply a value format to visible values if provided
				if (isset($property_data['value_format'])) {
					$values = array_values(sustr::batch_replace('%s', $value, $property_data['value_format']));
					$markedup_values = array_values(sustr::batch_replace('%s', $markedup_value, $property_data['value_format']));
				} else {
					$values = array($value);
					$markedup_values = array($markedup_value);
				}
				
				//Is the value in the content, and are we allowed to search/replace the content for this value?
				$count = 0;
				if (empty($property_data['always_hidden'])) {
					for ($i=0; $i<count($values); $i++) {
						$content = sustr::htmlsafe_str_replace($values[$i], $markedup_values[$i], $content, 1, $count);
						if ($count > 0) break;
					}
				}
				
				if ($count == 0)
					$append .= $hidden_markedup_value;
				
				$num_properties++;
			}
		}
		
		if (isset($type_data['content_tags'])) {
			$content_tag = is_array($type_data['content_tags']) ?
				$type_data['content_tags'][$format] :
				$type_data['content_tags'];
			
			$content = $this->add_tags($content, $content_tag, $formats[$format]['property_tags_template'], false);
		}
		
		if ($num_properties) {
			$type_tag = is_array($type_data['tags']) ?
						$type_data['tags'][$format] :
						$type_data['tags'];
			$content = $this->add_tags("$content<div>$append</div>", $type_tag, $formats[$format]['item_tags_template'], false);
			
			if ($this->get_setting('mark_code', true, 'settings'))
				$content .= "\n\n<!-- " . sprintf(__('Schema.org markup generated by %1$s (%2$s)', 'seo-ultimate'), SU_PLUGIN_NAME, SU_PLUGIN_URI) . " -->\n\n";
		}
		
		//Return filtered content
		return $content;
	}
	
	function apply_subproperty_markup($matches) {
		
		if (empty($matches[1]))
			return '';
		
		$content = $matches[1];
		
		extract($this->apply_subproperty_markup_args, EXTR_SKIP);
		
		foreach ($subproperties as $subproperty => $subproperty_data) {
			
			//Get the subproperty tags
			$subproperty_tag = is_array($subproperty_data['tags']) ?
								$subproperty_data['tags'][$format] :
								$subproperty_data['tags'];
			
			$subproperty_value = strval($this->get_postmeta("rich_snippet_{$type}_{$property}_{$subproperty}"));
			
			if ($subproperty_value) {
				$subproperty_markedup_value = $this->add_tags($subproperty_value, $subproperty_tag, $property_tag_template);
				$content = sustr::htmlsafe_str_replace($subproperty_value, $subproperty_markedup_value, $content, 1, $count);
			}
		}
		
		$content = $this->add_tags($content, $property_tag, $property_tag_format, false);
		
		return $content;
	}
	
	function postmeta_fields($fields) {
		$fields['serp'][40]['rich_snippet_type'] = $this->get_postmeta_dropdown('rich_snippet_type', array(
			  'none' => __('Standard', 'seo-ultimate')
			, 'review' => __('Review', 'seo-ultimate')
			, 'place' => __('Place', 'seo-ultimate')
		), __('Search Result Type:', 'seo-ultimate'));
		
		$fields['serp'][45]['rich_snippet_review_item|rich_snippet_review_image|rich_snippet_review_rating'] =
			$this->get_postmeta_subsection('rich_snippet_type', 'review',
				
				  $this->get_postmeta_textbox('rich_snippet_review_item', __('Name of Reviewed Item:', 'seo-ultimate'))
				
				. $this->get_postmeta_jlsuggest_box('rich_snippet_review_image', __('Image of Reviewed Item:', 'seo-ultimate'), 'types=posttype_attachment&post_mime_type=image/*')
				
				. $this->get_postmeta_dropdown('rich_snippet_review_rating', array(
					  '0'   => __('None', 'seo-ultimate')
					, '0.5' => __('0.5 stars', 'seo-ultimate')
					, '1'   => __('1 star', 'seo-ultimate')
					, '1.5' => __('1.5 stars', 'seo-ultimate')
					, '2'   => __('2 stars', 'seo-ultimate')
					, '2.5' => __('2.5 stars', 'seo-ultimate')
					, '3'   => __('3 stars', 'seo-ultimate')
					, '3.5' => __('3.5 stars', 'seo-ultimate')
					, '4'   => __('4 stars', 'seo-ultimate')
					, '4.5' => __('4.5 stars', 'seo-ultimate')
					, '5'   => __('5 stars', 'seo-ultimate')
				), __('Star Rating for Reviewed Item:', 'seo-ultimate'))
			);
		
		$fields['serp'][46]['rich_snippet_place_address_street|rich_snippet_place_address_po_box|rich_snippet_place_address_city|rich_snippet_place_address_state|rich_snippet_place_address_country|rich_snippet_place_address_postal_code|rich_snippet_place_map_url|rich_snippet_place_tel_number|rich_snippet_place_fax_number|rich_snippet_place_photo'] =
			$this->get_postmeta_subsection('rich_snippet_type', 'place',
				  $this->get_postmeta_textboxes(array(
					  'rich_snippet_place_address_street'  => __('Street Address:', 'seo-ultimate')
					, 'rich_snippet_place_address_po_box'  => __('Post Office Box Number:', 'seo-ultimate')
					, 'rich_snippet_place_address_city'    => __('City:', 'seo-ultimate')
					, 'rich_snippet_place_address_state'   => __('State or Region:', 'seo-ultimate')
					, 'rich_snippet_place_address_country' => __('Country:', 'seo-ultimate')
					, 'rich_snippet_place_address_postal_code' => __('Postal Code:', 'seo-ultimate')
				), array(), __('Address:', 'seo-ultimate'))
				. $this->get_postmeta_jlsuggest_box('rich_snippet_place_map_url', __('Map Page:', 'seo-ultimate'), 'types=posttype')
				. $this->get_postmeta_textbox('rich_snippet_place_tel_number', __('Phone Number:', 'seo-ultimate'), array('type' => 'tel'))
				. $this->get_postmeta_textbox('rich_snippet_place_fax_number', __('Fax Number:', 'seo-ultimate'), array('type' => 'tel'))
				. $this->get_postmeta_jlsuggest_box('rich_snippet_place_photo', __('Photo:', 'seo-ultimate'), 'types=posttype_attachment&post_mime_type=image/*')
			);
		
		return $fields;
	}

	
	function add_help_tabs($screen) {
		
		$overview = __("
<ul>
	<li><strong>What it does:</strong> Rich Snippet Creator adds special code (called Schema.org data) to your posts that asks Google and other major search engines to display special pertinent information (known as Rich Snippets) in search results for certain types of content. For example, if you&#8217;ve written a product review, you can use Rich Snippet Creator to ask Google to display the star rating that you gave the product in your review next to your review webpage when it appears in search results.</li>
	<li><strong>Why it helps:</strong> Rich Snippet Creator enhances the search engine results for your content by asking Google to add extra, eye-catching info that could help draw in more search engine visitors.</li>
	<li><strong>How it works:</strong> When editing one of your posts or pages, see if your content fits one of the available rich snippet types (for example, a review). If so, select that type from the &#8220;Search Result Type&#8221; dropdown box. Once you select the applicable type, additional options will appear that vary based on the type selected. For example, a &#8220;Star Rating for Reviewed Item&#8221; field will appear if you select the &#8220;Review&#8221; type. Once you save the post/page, Rich Snippet Creator will add the special code to it. You can remove this code at any time by selecting &#8220;Standard&#8221; from the &#8220;Search Result Type&#8221; dropdown and resaving the post/page.</li>
</ul>
", 'seo-ultimate');
		
		$troubleshooting = __("
<ul>
	<li><p><strong>Why aren&#8217;t rich snippets showing up in Google search results for my site?</strong><br />Enter the URL of your post/page into <a href='http://www.google.com/webmasters/tools/richsnippets' target='_blank'>Google&#8217;s testing tool</a> to make sure Google can find the rich snippet code on your site. If no code is found, check and make sure you&#8217;ve enabled rich snippets for that particular post/page.</p><p>Note that having the code on a post/page doesn&#8217;t guarantee that Google will actually use it to create a rich snippet. If Google is able to read your code but isn&#8217;t using it to generate rich snippets, you can ask Google to do so using <a href='http://www.google.com/support/webmasters/bin/request.py?contact_type=rich_snippets_feedback' target='_blank'>this form</a>.</p></li>
</ul>
", 'seo-ultimate');
		
		if ($this->has_enabled_parent()) {
			$screen->add_help_tab(array(
			  'id' => 'su-rich-snippets-help'
			, 'title' => __('Rich Snippet Creator', 'seo-ultimate')
			, 'content' => 
				'<h3>' . __('Overview', 'seo-ultimate') . '</h3>' . $overview . 
				'<h3>' . __('Troubleshooting', 'seo-ultimate') . '</h3>' . $troubleshooting
			));
		} else {
			
			$screen->add_help_tab(array(
				  'id' => 'su-rich-snippets-overview'
				, 'title' => __('Overview', 'seo-ultimate')
				, 'content' => $overview));
			
			$screen->add_help_tab(array(
				  'id' => 'su-rich-snippets-troubleshooting'
				, 'title' => __('Troubleshooting', 'seo-ultimate')
				, 'content' => $troubleshooting));
		}
	
	}
}

}
?>