<?php

if( ! defined( 'RFBP_VERSION' ) ) {
	exit;
}

/**
 * Get the plugin settings (merged with defaults)
 *
 * @return array
 */
function rfbp_get_settings() {

	static $settings = null;

	if ( is_null( $settings ) ) {

		$defaults = array(
			'app_id' => '',
			'app_secret' => '',
			'fb_id' => '',
			'load_css' => 1,
			'page_link_text' => __( 'Find us on Facebook', 'recent-facebook-posts' ),
			'link_new_window' => 0,
			'img_size' => 'normal',
			'img_width' => '',
			'img_height' => ''
		);

		// get user options
		$options = get_option( 'rfb_settings', array() );
		$settings = array_merge( $defaults, $options );
	}

	return $settings;
}

/**
 * Register the `Recent Facebook Posts` widget
 */
function rfbp_register_widget() {
	include_once RFBP_PLUGIN_DIR . 'includes/class-widget.php';
	register_widget( "RFBP_Widget" );
}

add_action( 'widgets_init', 'rfbp_register_widget' );

/**
 * Load plugin translations
 */
function rfbp_load_textdomain() {
	load_plugin_textdomain( 'recent-facebook-posts', false, basename( RFBP_PLUGIN_DIR ) . '/languages/' );
}

add_action( 'init', 'rfbp_load_textdomain' );

/**
 * @return RFBP_API
 */
function rfbp_get_api() {

	static $api = null;

	if( is_null( $api ) ) {
		$opts = rfbp_get_settings();
		require_once RFBP_PLUGIN_DIR . 'includes/class-api.php';
		$api = new RFBP_API( $opts['app_id'], $opts['app_secret'], $opts['fb_id'] );
	}

	return $api;
}

/**
 * @return bool
 */
function rfbp_valid_config() {
	$opts = rfbp_get_settings();
	return ( ! empty( $opts['fb_id'] ) && ! empty( $opts['app_id'] ) && ! empty( $opts['app_secret'] ) );
}