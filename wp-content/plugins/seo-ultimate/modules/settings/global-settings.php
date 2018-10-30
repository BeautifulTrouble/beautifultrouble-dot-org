<?php
/**
 * Global Settings Module
 * 
 * @since 2.1
 */

if (class_exists('SU_Module')) {

class SU_GlobalSettings extends SU_Module {
	
	var $wp_meta_called = false;
	
	static function get_parent_module() { return 'settings'; }
	static function get_child_order() { return 10; }
	static function is_independent_module() { return false; }
	
	static function get_module_title() { return __('Global Settings', 'seo-ultimate'); }
	
	function get_default_settings() {
		return array(
			  'attribution_link' => false
			, 'mark_code' => true
			, 'sdf_theme' => true
			, 'seo_toolbar_menu' => true
		);
	}
	
	function init() {
		//Hook to add attribution link
		if ($this->get_setting('attribution_link')) {
			//add_action('wp_meta', array(&$this, 'meta_link'));
			//add_action('wp_footer', array(&$this, 'footer_link'));
		}
	}
	
	function admin_page_contents() {
		
		$this->admin_form_start();
		
		$checkboxes = array(
			  'mark_code' => __('Identify the plugin&#8217;s HTML code insertions with HTML comment tags', 'seo-ultimate')
			, 'sdf_theme' => __('Show the promo slider for SEO Design Framework on plugin pages', 'seo-ultimate')
			, 'seo_toolbar_menu' => __('Show "SEO" Menu in admin toolbar', 'seo-ultimate')
			//, 'attribution_link' => __('Enable nofollow&#8217;d attribution link on my site', 'seo-ultimate')
			//, 'attribution_link_css' => array('description' => __('Add CSS styles to the attribution link', 'seo-ultimate'), 'indent' => true)
		);
		
		$this->checkboxes($checkboxes);
		$this->admin_form_end();
	}
	
	function meta_link() {
		echo "<li><a href='http://www.seodesignsolutions.com/' title='Search engine optimization technology by SEO Design Solutions' rel='nofollow'>SEO</a></li>\n";
		$this->wp_meta_called = true;
	}
	
	function footer_link() {
		if (!$this->wp_meta_called) {
			if ($this->get_setting('attribution_link_css')) {
				$pstyle = " style='text-align: center; font-size: smaller;'";
				$astyle = " style='color: inherit;'"; 
			} else $pstyle = $astyle = '';
			
			echo "\n<p id='suattr'$pstyle>Optimized by <a href='http://www.seodesignsolutions.com/' rel='nofollow'$astyle>SEO</a> Ultimate</p>\n";
		}
	}
}

}
?>