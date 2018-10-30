<?php
/**
 * AISOP Import Module
 * 
 * @since 1.6
 */

if (class_exists('SU_ImportModule')) {

class SU_ImportAIOSP extends SU_ImportModule {
	
	static function get_module_title() { return __('Import from All in One SEO Pack', 'seo-ultimate'); }
	static function get_menu_title() { return __('AIOSP Import', 'seo-ultimate'); }
	
	function get_op_title() { return __('All in One SEO Pack', 'seo-ultimate'); }
	function get_op_abbr()  { return __('AIOSP', 'seo-ultimate'); }
	function get_import_desc() { return __('Import post data (custom title tags and meta tags).', 'seo-ultimate'); }
	
	function admin_page_contents() {
		echo "<p>";
		_e('Here you can move post fields from the All in One SEO Pack (AIOSP) plugin to SEO Ultimate. AIOSP&#8217;s data remains in your WordPress database after AIOSP is deactivated or even uninstalled. This means that as long as AIOSP was active on this blog sometime in the past, AIOSP does <em>not</em> need to be currently installed or activated for the import to take place.', 'seo-ultimate');
		echo "</p>\n<p>";
		_e('The import tool can only move over data from AIOSP version 1.6 or above. If you use an older version of AIOSP, you should update to the latest version first and run AIOSP&#8217;s upgrade process.', 'seo-ultimate');
		echo "</p>\n";
		
		$this->admin_form_start();
		$this->admin_page_postmeta();
		$this->admin_form_end();
	}
	
	function do_import() {
		$this->do_import_deactivate(SU_AIOSP_PATH);
		
		$this->do_import_postmeta(
			  suarr::aprintf('_aioseop_%s', '_su_%s', array('title', 'description', 'keywords'))
			, '_aioseop_disable'
		);
	}
}

}
?>