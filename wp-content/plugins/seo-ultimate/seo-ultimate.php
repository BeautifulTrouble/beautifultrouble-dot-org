<?php
/*
Plugin Name: SEO Ultimate
Plugin URI: http://www.seodesignsolutions.com/wordpress-seo/
Description: This all-in-one SEO plugin gives you control over title tags, noindex/nofollow, meta tags, rich snippets, slugs, canonical tags, autolinks, 404 errors, rich snippets, and more.
Version: 7.6.1
Author: SEO Design Solutions
Author URI: http://www.seodesignsolutions.com/
Text Domain: seo-ultimate
*/

/**
 * The main SEO Ultimate plugin file.
 * @package SeoUltimate
 * @version 7.6.1
 * @link http://www.seodesignsolutions.com/wordpress-seo/ SEO Ultimate Homepage
 */

/*
Copyright (c) 2009-2012 John Lamansky

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	die();
}

/********** CONSTANTS **********/

//The bare minimum version of WordPress required to run without generating a fatal error.
//SEO Ultimate will refuse to run if activated on a lower version of WP.
define('SU_MINIMUM_WP_VER', '3.3');

//Reading plugin info from constants is faster than trying to parse it from the header above.
define('SU_PLUGIN_NAME', 'SEO Ultimate');
define('SU_PLUGIN_URI', 'http://www.seodesignsolutions.com/wordpress-seo/');
define('SU_VERSION', '7.6.1');
define('SU_AUTHOR', 'SEO Design Solutions');
define('SU_AUTHOR_URI', 'http://www.seodesignsolutions.com/');
define('SU_USER_AGENT', 'SeoUltimate/7.6.1');

/********** INCLUDES **********/

//Libraries
include 'includes/jlfunctions/jlfunctions.php';
include 'includes/jlwp/jlwp.php';

//Plugin files
include 'plugin/su-constants.php';
include 'plugin/su-functions.php';
include 'plugin/class.seo-ultimate.php';

//Module files
include 'modules/class.su-module.php';
include 'modules/class.su-importmodule.php';


/********** VERSION CHECK & INITIALIZATION **********/

global $wp_version;
if (version_compare($wp_version, SU_MINIMUM_WP_VER, '>=')) {
	global $seo_ultimate;
	$seo_ultimate =& new SEO_Ultimate(__FILE__);
} else {
	add_action('admin_notices', 'su_wp_incompat_notice');
}

function su_wp_incompat_notice() {
	echo '<div class="error"><p>';
	printf(__('SEO Ultimate requires WordPress %s or above. Please upgrade to the latest version of WordPress to enable SEO Ultimate on your blog, or deactivate SEO Ultimate to remove this notice.', 'seo-ultimate'), SU_MINIMUM_WP_VER);
	echo "</p></div>\n";
}

?>