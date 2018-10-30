<?php
/**
 * More Link Customizer Module
 * 
 * @since 1.3
 */

if (class_exists('SU_Module')) {

class SU_MoreLinks extends SU_Module {
	
	static function get_module_title() { return __('More Link Customizer', 'seo-ultimate'); }
	
	static function get_parent_module() { return 'misc'; }
	function get_settings_key() { return 'more-links'; }
	
	function get_default_settings() {
		return array(
			  'default' => 'Continue reading &#8220;{post}&#8221; &raquo;'
		);
	}
	
	function init() {
		add_filter('the_content_more_link', array(&$this, 'more_link_filter'), 10, 2);
		add_filter('su_get_postmeta-morelinktext', array(&$this, 'get_morelinktext_postmeta'), 10, 3);
	}
	
	function admin_page_contents() {
		$this->child_admin_form_start();
		$this->textbox('default', __('Default More Link Text', 'seo-ultimate'), $this->get_default_setting('default'));
		$this->child_admin_form_end();
	}
	
	function more_link_filter($link, $text=false) {
		
		if ($text === false) return $link; //Can't do it without $text parameter
		
		$default = $this->get_setting('default');
		
		if (strlen($newtext = trim($this->get_postmeta('morelinktext'))) || strlen(trim($newtext = $default))) {
			$newtext = str_replace('{post}', su_esc_html(get_the_title()), $newtext);
			$link = str_replace("$text</a>", "$newtext</a>", $link);
		}
		
		return $link;
	}
	
	function postmeta_fields($fields, $screen) {
		
		if (strcmp($screen, 'post') == 0)
			$fields['links'][20]['morelinktext'] = $this->get_postmeta_textbox('morelinktext', __('Anchor Text of &#8220;More&#8221; Link:', 'seo-ultimate'));
		
		return $fields;
	}
	
	function get_morelinktext_postmeta($value, $key, $post) {
		
		if (!strlen($value)) {
			
			//Import any custom anchors from the post itself
			$content = $post->post_content;
			$matches = array();
			if ( preg_match('/<!--more(.*?)?-->/', $content, $matches) ) {
				$content = explode($matches[0], $content, 2);
				if ( !empty($matches[1]) )
					return strip_tags(wp_kses_no_null(trim($matches[1])));
			}
		}
		
		return $value;
	}
	
	function add_help_tabs($screen) {
		
		$overview = __("
<ul>
	<li><strong>What it does:</strong> More Link Customizer lets you modify the anchor text of your posts&#8217; <a href='http://codex.wordpress.org/Customizing_the_Read_More' target='_blank'>&#8220;more&#8221; links</a>.</li>
	<li><strong>Why it helps:</strong> On the typical WordPress setup, the &#8220;more link&#8221; always has the same anchor text (e.g. &#8220;Read more of this entry&#8221;). Since internal anchor text conveys web page topicality to search engines, the &#8220;read more&#8221; phrase isn&#8217;t a desirable anchor phrase. More Link Customizer lets you replace the boilerplate text with a new anchor that, by default, integrates your post titles (which will ideally be keyword-oriented).</li>
	<li><strong>How to use it:</strong> On this page you can set the anchor text you&#8217;d like to use by default. The <code>{post}</code> variable will be replaced with the post&#8217;s title. HTML and encoded entities are supported. If instead you decide that you&#8217;d like to use the default anchor text specified by your currently-active theme, just erase the contents of the textbox. The anchor text can be overridden on a per-post basis via the &#8220;More Link Text&#8221; box in the &#8220;SEO Settings&#8221; section of the WordPress post editor.</li>
</ul>
", 'seo-ultimate');

		$faq = __("
<ul>
	<li>
		<p><strong>Why is the More Link Customizer an improvement over WordPress&#8217;s built-in functionality?</strong><br />Although WordPress does allow basic <a href='http://codex.wordpress.org/Customizing_the_Read_More#Having_a_custom_text_for_each_post' target='_blank'>custom &#8220;more&#8221; anchors</a>, the SEO Ultimate approach has several benefits:</p>
		<ul>
			<li>More Link Customizer (MLC) lets you set a custom default anchor text. WordPress, on the other hand, leaves this up to the currently-active theme.</li>
			<li>MLC lets you dynamically incorporate the post&#8217;s title into the anchor text.</li>
			<li>MLC lets you include HTML tags in your anchor, whereas WordPress strips these out.</li>
			<li>MLC&#8217;s functionality is much more prominent than WordPress&#8217;s unintuitive, barely-documented approach.</li>
			<li>Unlike WordPress's method, MLC doesn't require you to utilize the HTML editor.</li>
		</ul>
		<p>If you&#8217;ve already specified custom anchors via WordPress&#8217;s method, SEO Ultimate will import those anchors automatically into the More Link Customizer.</p>
	</li>
</ul>
", 'seo-ultimate');
		
		if ($this->has_enabled_parent()) {
			$screen->add_help_tab(array(
			  'id' => 'su-more-links-help'
			, 'title' => __('More Link Customizer', 'seo-ultimate')
			, 'content' => 
'<h3>' . __('Overview', 'seo-ultimate') . '</h3>' . $overview . 
'<h3>' . __('FAQ', 'seo-ultimate') . '</h3>' . $faq
));
		} else {
			
			$screen->add_help_tab(array(
				  'id' => 'su-more-links-overview'
				, 'title' => __('Overview', 'seo-ultimate')
				, 'content' => $overview));
			
			$screen->add_help_tab(array(
				  'id' => 'su-more-links-faq'
				, 'title' => __('FAQ', 'seo-ultimate')
				, 'content' => $faq));
		}
	}
}

}
?>