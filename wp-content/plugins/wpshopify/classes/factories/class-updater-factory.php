<?php

namespace WPS\Factories;

use WPS\Updater;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Updater_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Updater(
				Factories\DB\Settings_License_Factory::build(),
				Factories\DB\Settings_General_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
