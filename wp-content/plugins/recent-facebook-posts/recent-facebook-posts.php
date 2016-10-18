<?php
/*
Plugin Name: Recent Facebook Posts
Plugin URI: https://dannyvankooten.com/donate/
Description: Lists most recent posts from a public Facebook page.
Version: 2.0.10
Author: Danny van Kooten
Author URI: https://dannyvankooten.com/
Text Domain: recent-facebook-posts
Domain Path: /languages/
License: GPL3 or later

Recent Facebook Posts Plugin
Copyright (C) 2012-2015, Danny van Kooten, support@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin Constants
define( 'RFBP_VERSION', '2.0.10' );
define( 'RFBP_PLUGIN_DIR', dirname( __FILE__ ) . '/' );

/**
 * Load the plugin files at `plugins_loaded:10`
 *
 * @ignore
 */
function _rfbp_bootstrap() {

	// Include Global code
	require RFBP_PLUGIN_DIR . 'includes/functions/global.php';

    $settings = rfbp_get_settings();

	if( ! is_admin() ) {

		// frontend requests
		include_once RFBP_PLUGIN_DIR . 'includes/functions/helpers.php';
		include_once RFBP_PLUGIN_DIR . 'includes/functions/template.php';
		require RFBP_PLUGIN_DIR . 'includes/class-public.php';

		$rfbp_public = RFBP_Public::instance( $settings );
		$rfbp_public->add_hooks();

	} elseif( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {

		// admin requests
		require RFBP_PLUGIN_DIR . 'includes/class-admin.php';
		$admin = new RFBP_Admin( $settings );
		$admin->add_hooks();

	}

}

add_action( 'plugins_loaded', '_rfbp_bootstrap' );

