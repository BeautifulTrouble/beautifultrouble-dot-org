<?php
/*
Plugin Name: Show Template
Plugin URI: http://sivel.net/wordpress/show-template/
Description: Prints an html comment in the footer of every page letting you know which template file of your theme was used for the display.
Author: Matt Martz
Author URI: http://sivel.net/
Version: 1.1

        Copyright (c) 2009-2010 Matt Martz (http://sivel.net/)
        Show Template is released under the GNU General Public License (GPL)
        http://www.gnu.org/licenses/gpl-2.0.txt
*/

class ShowTemplate {

	var $template = false;

	function __construct() {
		if ( is_admin() )
			return;
		add_action( 'template_redirect', array( &$this, 'check_template' ), 0 );
	}

	// Using the same logic used by WordPress determine the template to be used
	function check_template() {
		if     ( is_404()            && $template = get_404_template() )            : $this->template = $template;
		elseif ( is_search()         && $template = get_search_template() )         : $this->template = $template;
		elseif ( is_tax()            && $template = get_taxonomy_template() )       : $this->template = $template;
		elseif ( is_home()           && $template = get_home_template() )           : $this->template = $template;
		elseif ( is_attachment()     && $template = get_attachment_template() )     : $this->template = $template;
		elseif ( is_single()         && $template = get_single_template() )         : $this->template = $template;
		elseif ( is_page()           && $template = get_page_template() )           : $this->template = $template;
		elseif ( is_category()       && $template = get_category_template() )       : $this->template = $template;
		elseif ( is_tag()            && $template = get_tag_template())             : $this->template = $template;
		elseif ( is_author()         && $template = get_author_template() )         : $this->template = $template;
		elseif ( is_date()           && $template = get_date_template() )           : $this->template = $template;
		elseif ( is_archive()        && $template = get_archive_template() )        : $this->template = $template;
		elseif ( is_comments_popup() && $template = get_comments_popup_template() ) : $this->template = $template;
		elseif ( is_paged()          && $template = get_paged_template() )          : $this->template = $template;
		else :
			$this->template = function_exists( 'get_index_template' ) ? get_index_template() : TEMPLATEPATH . "/index.php";
		endif;
		$this->template = apply_filters( 'template_include', $this->template );
		// Hook into the footer so we can echo the active template
		add_action( 'wp_footer', array( &$this, 'show_template' ), 100 );
	}

	// Echo the active template to the footer
	// Try to catch when a plugin or otherwise hooks template_redirect to include a different template
	function show_template() {
		$fudge = false;
		foreach ( debug_backtrace() as $trace ) {
			switch ( $trace['function'] ) {
				case 'wp_footer':
					$wp_footer = $trace['file'];
					break;
				case 'get_footer':
					$get_footer = $trace['file'];
					break;
			}
		}
		$fudge = isset( $get_footer ) ? $get_footer : $wp_footer;
		if ( $fudge == $this->template || $fudge === false ) {
			echo "<!-- Active Template: {$this->template} -->\n";
		} else {
			echo "<!--\n";
			echo "The template loader logic has chosen a different template than what was used.\n\n";
			echo "Chosen Template: {$this->template}\n";
			echo "Actual Template: $fudge\n\n";
			echo "This will usually occur if the template file was overriden using an action on template_redirect.\n";
			echo "This is a best effort guess to catch such scenarios as mentioned above but can be incorrect.\n";
			echo "-->\n";
		}
	}
}

$ShowTemplate = new ShowTemplate();
