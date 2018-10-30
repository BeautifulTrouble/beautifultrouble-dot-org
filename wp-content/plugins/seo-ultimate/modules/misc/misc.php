<?php
/**
 * Miscellaneous Module
 * 
 * @since 5.8
 */

if (class_exists('SU_Module')) {

class SU_Misc extends SU_Module {
	static function get_module_title() { return __('Miscellaneous', 'seo-ultimate'); }
	static function get_menu_title() { return __('Miscellaneous', 'seo-ultimate'); }
	static function get_menu_pos() { return 30; }
	function admin_page_contents() {
		
		echo "\n\n<div class='row'>\n";
			
		if ($this->should_show_sdf_theme_promo()) {
			echo "\n\n<div class='col-sm-8 col-md-9'>\n";
		}
		else {
			echo "\n\n<div class='col-md-12'>\n";
		}
		
		echo '<p>' . __('The Miscellaneous page contains modules that don&#8217;t have enough settings to warrant their own separate admin pages.', 'seo-ultimate') . '</p>';
		$this->children_admin_pages_form();
		
		echo "\n\n</div>\n";
		
		if ($this->should_show_sdf_theme_promo()) {
			echo "\n\n<div class='col-sm-4 col-md-3'>\n";
			$this->promo_sdf_banners();
			echo "\n\n</div>\n";
		}
		
		echo "\n\n</div>\n";
		
	}
}

}
?>