<?php
/*
Plugin Name: Recent Facebook Posts
Plugin URI: http://dannyvankooten.com/wordpress-plugins/recent-facebook-posts/
Description: Lists most recent posts from a public Facebook page.
Version: 1.8.1
Author: Danny van Kooten
Author URI: http://dannyvankooten.com/
Text Domain: recent-facebook-posts
License: GPL2
*/

/*  Copyright 2012  Danny van Kooten  (email : hi@dannyvankooten.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define("RFBP_VERSION", "1.8.1");
define("RFBP_PLUGIN_DIR", plugin_dir_path(__FILE__)); 

// define WP_CONTENT_DIR since we're using it..
if ( ! defined( 'WP_CONTENT_DIR' ) ) { define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' ); }

require RFBP_PLUGIN_DIR . 'includes/RFBP.php';
new RFBP();

