<?php
/**
 * Miscellaneous Module
 * 
 * @since 5.8
 */

if (class_exists('SU_Module')) {

class SU_Misc extends SU_Module {
	function get_module_title() { return __('Miscellaneous', 'seo-ultimate'); }
	function get_menu_pos() { return 30; }
	function admin_page_contents() {
		echo '<p>' . __('The Miscellaneous page contains modules that don&#8217;t have enough settings to warrant their own separate admin pages.', 'seo-ultimate') . '</p>';
		$this->children_admin_pages_form();
	}
}

}
?>