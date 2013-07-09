<?php
/**
 * Provides several enhancements to the handling of images and files held in the WordPress Media Library
 *
 * This file contains several tests for name conflicts with other plugins. Only if the tests are passed
 * will the rest of the plugin be loaded and run.
 *
 * @package Media Library Assistant
 * @version 1.41
 */

/*
Plugin Name: Media Library Assistant
Plugin URI: http://fairtradejudaica.org/media-library-assistant-a-wordpress-plugin/
Description: Enhances the Media Library; powerful[mla_gallery], taxonomy support, IPTC/EXIF processing, bulk & quick edit actions and where-used reporting.
Author: David Lingren
Version: 1.41
Author URI: http://fairtradejudaica.org/our-story/staff/
*/

/**
 * Accumulates error messages from name conflict tests
 *
 * @since 0.20
 */
$mla_name_conflict_error_messages = '';
 
if ( defined( 'MLA_PLUGIN_PATH' ) ) {
	$mla_name_conflict_error_messages .= '<li>constant MLA_PLUGIN_PATH</li>';
}
else {
	/**
	 * Provides path information to the plugin root in file system format.
	 */
	define( 'MLA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
 
if ( defined( 'MLA_PLUGIN_URL' ) ) {
	$mla_name_conflict_error_messages .= '<li>constant MLA_PLUGIN_URL</li>';
}
else {
	/**
	 * Provides path information to the plugin root in URL format.
	 */
	define( 'MLA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Defines classes, functions and constants for name conflict tests. There are no global functions
 * or other constants in this version; everything is wrapped in classes to minimize potential conflicts.
 *
 * @since 0.20
 */
$mla_name_conflict_candidates =
	array (
		'MLA' => 'class',
		'MLAData' => 'class',
		'MLAEdit' => 'class',
		'MLAMime' => 'class',
		'MLAModal' => 'class',
		'MLAObjects' => 'class',
		'MLASettings' => 'class',
		'MLAShortcodes' => 'class',
		'MLATest' => 'class',
		'MLA_List_Table' => 'class',
		'MLA_View_List_Table' => 'class',
		'MLA_Optional_Upload_List_Table' => 'class',
		'MLA_Upload_List_Table' => 'class'
	);

/*
 * Check for conflicting names, i.e., already defined by some other plugin or theme
 */
foreach ( $mla_name_conflict_candidates as $value => $type ) {
	switch ($type) {
		case 'class':
			if ( class_exists( $value ) )
				$mla_name_conflict_error_messages .= "<li>class {$value}</li>";
			break;
		case 'function':
			if ( function_exists( $value ) )
				$mla_name_conflict_error_messages .= "<li>function {$value}</li>";
			break;
		case 'constant':
			if ( defined( $value ) )
				$mla_name_conflict_error_messages .= "<li>constant {$value}</li>";
			break;
		default:
	} // switch $type
}

/**
 * Displays name conflict error messages at the top of the Dashboard
 *
 * @since 0.20
 */
function mla_name_conflict_reporting_action () {
	global $mla_name_conflict_error_messages;
	
	echo '<div class="error"><p><strong>The Media Library Assistant cannot load.</strong> Another plugin or theme has declared conflicting class, function or constant names:</p>'."\r\n";
	echo "<ul>{$mla_name_conflict_error_messages}</ul>\r\n";
	echo '<p>You must resolve these conflicts before this plugin can safely load.</p></div>'."\r\n";
}

/*
 * Load the plugin or display conflict message(s)
 */
if ( empty( $mla_name_conflict_error_messages ) ) {
	require_once('includes/mla-plugin-loader.php');

	register_activation_hook( __FILE__, array( 'MLASettings', 'mla_activation_hook' ) );
	register_deactivation_hook( __FILE__, array( 'MLASettings', 'mla_deactivation_hook' ) );
}
else {
	add_action( 'admin_notices', 'mla_name_conflict_reporting_action' );
}
?>