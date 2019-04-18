<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


class Deactivator {


	/*

	Initialize the class and set its properties.

	*/
	public function __construct() {

	}


	/*

	Things to do on plugin deactivation

	*/
	public function on_plugin_deactivate() {

	}


	public function hooks() {
		add_action('wps_on_plugin_deactivate', [$this, 'on_plugin_deactivate']);
	}


	public function init() {
		$this->hooks();
	}


}
