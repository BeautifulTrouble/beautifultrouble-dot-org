<?php
/**
 * Linkbox Inserter Module
 * 
 * @since 0.6
 */

if (class_exists('SU_Module')) {

class SU_Linkbox extends SU_Module {

	function get_module_title() { return __('Linkbox Inserter', 'seo-ultimate'); }
	
	function get_parent_module() { return 'misc'; }
	function get_settings_key() { return 'linkbox'; }
	
	function get_default_settings() {
		//The default linkbox HTML
		return array(
			  'html' => '<div class="su-linkbox" id="post-{id}-linkbox"><div class="su-linkbox-label">' .
						__('Link to this post!', 'seo-ultimate') .
						'</div><div class="su-linkbox-field">' .
						'<input type="text" value="&lt;a href=&quot;{url}&quot;&gt;{title}&lt;/a&gt;" '.
						'onclick="javascript:this.select()" readonly="readonly" style="width: 100%;" />' .
						'</div></div>'
		);
	}
	
	function init() {
		//We only want to filter post content when we're in the front-end, so we hook into template_redirect
		add_action('template_redirect', array(&$this, 'template_init'));
	}
	
	function template_init() {
		$enabled = false;
		
		if ($this->should_linkbox())
			//Add the linkbox to post/page content
			add_filter('the_content', array(&$this, 'linkbox_filter'));
		
		if ($this->get_setting('action_hook'))
			//Enable the action hook
			add_action('su_linkbox', array(&$this, 'linkbox_action'));
	}
	
	function admin_page_contents() {
		$this->child_admin_form_start();
		$this->checkboxes(array('filter_posts'	=> __('At the end of posts', 'seo-ultimate')
							,	'filter_pages'	=> __('At the end of pages', 'seo-ultimate')
							,	'action_hook'	=> __('When called by the su_linkbox hook', 'seo-ultimate')
		), __('Display linkboxes...', 'seo-ultimate'));
		$this->textarea('html', __('Linkbox HTML', 'seo-ultimate'));
		$this->child_admin_form_end();
	}
	
	function should_linkbox() {
		return (!is_page() && $this->get_setting('filter_posts'))
			|| ( is_page() && $this->get_setting('filter_pages'));
	}
	
	function linkbox_filter($content, $id = false) {
		
		//If no ID is provided, get the ID of the current post
		if (!$id) $id = suwp::get_post_id();
		
		if ($id) {
			//Don't add a linkbox if a "more" link is present (since a linkbox should go at the very bottom of a post)
			$morelink = '<a href="'.get_permalink($id).'#more-'.$id.'" class="more-link">';
			if (strpos($content, $morelink) !== false) return $content;
			
			//Load the HTML and replace the variables with the proper values
			$linkbox = $this->get_setting('html');
			$linkbox = str_replace(
				array('{id}', '{url}', '{title}'),
				array(intval($id), su_esc_attr(get_permalink($id)), su_esc_attr(get_the_title($id))),
				$linkbox
			);
			
			//Return the content with the linkbox added to the bottom
			return $content.$linkbox;
		}
		
		return $content;
	}
	
	function linkbox_action($id = false) {
		echo $this->linkbox_filter('', $id);
	}
	
	function add_help_tabs($screen) {
	
		$overview = __("
<ul>
	<li><strong>What it does:</strong> Linkbox Inserter can add linkboxes to your posts/pages.</li>
	<li><strong>Why it helps:</strong> Linkboxes contain HTML code that visitors can use to link to your site. This is a great way to encourage SEO-beneficial linking activity.</li>
	<li><strong>How to use it:</strong> Use the checkboxes to enable the Linkbox Inserter in various areas of your site. Customize the HTML if desired. Click &#8220;Save Changes&#8221; when finished.</li>
</ul>
", 'seo-ultimate');
		
		$settings = __("
<p>Here&#8217;s information on the various settings:</p>

<ul>
	<li>
		<strong>Display linkboxes...</strong>
		<ul>
			<li><strong>At the end of posts</strong> &mdash; Adds the linkbox HTML to the end of all posts (whether they're displayed on the blog homepage, in archives, or by themselves).</li>
			<li><strong>At the end of pages</strong> &mdash; Adds the linkbox HTML to the end of all Pages.</li>
			<li><strong>When called by the su_linkbox hook</strong> &mdash; For more fine-tuned control over where linkboxes appear, enable this option and add <code>&lt;?php do_action('su_linkbox'); ?&gt;</code> to your theme. You can also add an ID parameter to display the linkbox of a particular post/page; for example: <code>&lt;?php do_action('su_linkbox', 123); ?&gt;</code>.</li>
		</ul>
	</li>
	<li>
		<strong>HTML</strong> &mdash; The HTML that will be outputted to display the linkboxes. The HTML field supports these variables:
		<ul>
			<li>{id} &mdash; The ID of the current post/page, or the ID passed to the action hook call.</li>
			<li>{url} &mdash; The permalink URL of the post/page.</li>
			<li>{title} &mdash; The title of the post/page.</li>
		</ul>
	</li>
</ul>
", 'seo-ultimate');
		
		if ($this->has_enabled_parent()) {
			$screen->add_help_tab(array(
				  'id' => 'su-linkbox-help'
				, 'title' => __('Linkbox Inserter', 'seo-ultimate')
				, 'content' => 
					'<h3>' . __('Overview', 'seo-ultimate') . '</h3>' . $overview . 
					'<h3>' . __('Settings Help', 'seo-ultimate') . '</h3>' . $settings
			));
		} else {
			
			$screen->add_help_tab(array(
				  'id' => 'su-linkbox-overview'
				, 'title' => __('Overview', 'seo-ultimate')
				, 'content' => $overview));
			
			$screen->add_help_tab(array(
				  'id' => 'su-linkbox-settings'
				, 'title' => __('Settings Help', 'seo-ultimate')
				, 'content' => $settings));
		}
	}
}

}
?>