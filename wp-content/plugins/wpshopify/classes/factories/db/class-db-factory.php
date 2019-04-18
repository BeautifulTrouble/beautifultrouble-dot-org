<?php

namespace WPS\Factories\DB;

use WPS\DB;

if (!defined('ABSPATH')) {
	exit;
}

class DB_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB();

		}

		return self::$instantiated;

	}


}
