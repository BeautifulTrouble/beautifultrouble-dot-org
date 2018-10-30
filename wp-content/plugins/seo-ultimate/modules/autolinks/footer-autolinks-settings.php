<?php
/**
 * Footer Deeplink Juggernaut Settings Module
 * 
 * @since 6.5
 */

if (class_exists('SU_Module')) {

class SU_FooterAutolinksSettings extends SU_Module {
	
	static function get_parent_module() { return 'autolinks'; }
	static function get_child_order() { return 40; }
	static function is_independent_module() { return false; }
	
	static function get_module_title() { return __('Footer Deeplink Juggernaut Settings', 'seo-ultimate'); }
	function get_module_subtitle() { return __('Footer Link Settings', 'seo-ultimate'); }
	
	function get_default_settings() {
		return array(
			  'footer_link_section_format' => '<div id="su-footer-links" style="text-align: center;">{links}</div>'
			, 'footer_link_format' => '{link}'
			, 'footer_link_sep' => ' | '
		);
	}
	
	function admin_page_contents() {
		$this->admin_subheader(__('HTML Formats', 'seo-ultimate'));
		$this->admin_form_table_start();
		$this->textareas(array(
			  'footer_link_section_format' => __('Link Section Format', 'seo-ultimate')
			, 'footer_link_format' => __('Link Format', 'seo-ultimate')
		));
		$this->textbox('footer_link_sep', __('Link Separator', 'seo-ultimate'));
		$this->admin_form_table_end();
	}
}

}
?>